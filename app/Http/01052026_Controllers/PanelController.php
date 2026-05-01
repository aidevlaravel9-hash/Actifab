<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Project;

use App\Models\ProjectPanelBoard;
use App\Models\SystemCurrentRating;
use App\Models\NoOfPoles;
use App\Models\PanelType;
use App\Models\OperatingVoltage;
use App\Models\FormType;
use App\Models\PanelAccess;
use App\Models\PanelBoardColour;
use App\Models\Certification;
use App\Models\IpProtection;
use App\Models\LockSystem;
use App\Models\BusbarPosition;
use App\Models\GlandPlateThickness;
use App\Models\OutgoingCablePosition;
use App\Models\PlinthType;
use Illuminate\Support\Facades\Auth;



class PanelController extends Controller
{


    public function dashboard(Request $request)
    {

        return view('user.dashboard');
    }


    public function index()
    {
        $user = auth()->user();

        $projects = Project::where('registration_id', $user->id)
            ->withCount('panels') // 👈 counts panels per project
            ->latest()
            ->paginate(10);

        return view('user.index', compact('projects'));
    }

    public function submitProject($id)
    {
        $panel = ProjectPanelBoard::findOrFail($id);

        $panel->submit_project = 1;
        $panel->save();

        return response()->json([
            'success' => true,
            'message' => 'Project Submitted Successfully'
        ]);
    }


    public function panelListing($project_id)
    {
        $project = Project::findOrFail($project_id);

        $panels = ProjectPanelBoard::with('panelType', 'panelAccess')
            ->where('project_id', $project_id)
            ->latest()
            ->get();

        return view('user.manage-panel', compact('project', 'panels'));
    }

    public function create_project()
    {
        $user = auth()->user();

        // Financial Year (April to March)
        $currentYear = date('Y');
        $month = date('m');

        if ($month < 4) {
            $startYear = substr($currentYear - 1, -2);
            $endYear = substr($currentYear, -2);
        } else {
            $startYear = substr($currentYear, -2);
            $endYear = substr($currentYear + 1, -2);
        }

        $financialYear = $startYear . $endYear;

        // 🔥 Get LAST project globally (NOT year-wise)
        $lastProject = Project::orderBy('id', 'desc')->first();

        if ($lastProject) {
            $nextNumber = $lastProject->running_no + 1;
        } else {
            $nextNumber = 1;
        }

        $inquiryNo = $financialYear . '-' . $nextNumber;

        return view('user.create', compact('user', 'inquiryNo'));
    }

    public function storeproject(Request $request)
    {
        $request->validate([
            'inquiry_date' => 'required|date',
            'project_name' => 'required',
            'customer_name' => 'required',
        ]);

        $user = auth()->user();

        DB::beginTransaction();

        try {
            // 🔒 LOCK the table row (important)
            $lastProject = Project::lockForUpdate()->orderBy('id', 'desc')->first();

            if ($lastProject) {
                $nextNumber = $lastProject->running_no + 1;
            } else {
                $nextNumber = 1;
            }

            // Financial Year (April logic)
            $currentYear = date('Y');
            $month = date('m');

            if ($month < 4) {
                $startYear = substr($currentYear - 1, -2);
                $endYear = substr($currentYear, -2);
            } else {
                $startYear = substr($currentYear, -2);
                $endYear = substr($currentYear + 1, -2);
            }

            $financialYear = $startYear . $endYear;

            $inquiryNo = $financialYear . '-' . $nextNumber;

            $project = new Project();
            $project->registration_id = $user->id;
            $project->financial_year = $financialYear;
            $project->running_no = $nextNumber;
            $project->inquiry_no = $inquiryNo;

            $project->inquiry_date = $request->inquiry_date;
            $project->project_name = $request->project_name;
            $project->customer_name = $request->customer_name;
            $project->customer_email = $request->customer_email;
            $project->customer_address = $request->customer_address;
            $project->contact_person_name = $request->contact_person_name;
            $project->contact_person_mobile = $request->contact_person_mobile;
            $project->contact_person_email = $request->contact_person_email;

            // if ($request->hasFile('attachment')) {
            //     $file = $request->file('attachment');
            //     $filename = time() . '_' . $file->getClientOriginalName();
            //     $file->move(public_path('uploads/projects'), $filename);
            //     $project->attachment = $filename;
            // }

            if ($request->hasFile('attachment')) {

                $file = $request->file('attachment');
                $imageName = time() . '_' . mt_rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
                $uploadPath = FolderPath('/uploads/projects');
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                $file->move($uploadPath, $imageName);
                $project->attachment = $imageName;
            }

            $project->save();

            DB::commit();

            return redirect()->route('indexProject')
                ->with('success', 'Project Created Successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function editproject($id)
    {
        $project = Project::findOrFail($id);
        $user = auth()->user();

        return view('user.create', compact('project', 'user'));
    }

    public function updateproject(Request $request, $id)
    {
        $request->validate([
            'inquiry_date' => 'required|date',
            'project_name' => 'required',
            'customer_name' => 'required',
        ]);

        $project = Project::findOrFail($id);

        $project->inquiry_date = $request->inquiry_date;
        $project->project_name = $request->project_name;
        $project->customer_name = $request->customer_name;
        $project->customer_email = $request->customer_email;
        $project->customer_address = $request->customer_address;
        $project->contact_person_name = $request->contact_person_name;
        $project->contact_person_mobile = $request->contact_person_mobile;
        $project->contact_person_email = $request->contact_person_email;

        if ($request->hasFile('attachment')) {

            $file = $request->file('attachment');

            // Get upload path using helper
            $uploadPath = FolderPath('/uploads/projects');

            // Create folder if not exists
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            if ($project->attachment) {

                $oldImagePath = $uploadPath . '/' . $project->attachment;

                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Generate unique image name
            $imageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Move new image
            $file->move($uploadPath, $imageName);

            // Save new image name
            $project->attachment = $imageName;
        }

        $project->save();

        return redirect()->route('indexProject')
            ->with('success', 'Project Updated Successfully');
    }

    // public function updateproject(Request $request, $id)
    // {
    //     $request->validate([
    //         'inquiry_date' => 'required|date',
    //         'project_name' => 'required',
    //         'customer_name' => 'required',
    //     ]);

    //     $project = Project::findOrFail($id);

    //     $project->inquiry_date = $request->inquiry_date;
    //     $project->project_name = $request->project_name;
    //     $project->customer_name = $request->customer_name;
    //     $project->customer_email = $request->customer_email;
    //     $project->customer_address = $request->customer_address;
    //     $project->contact_person_name = $request->contact_person_name;
    //     $project->contact_person_mobile = $request->contact_person_mobile;
    //     $project->contact_person_email = $request->contact_person_email;

    //     // Update attachment
    //     if ($request->hasFile('attachment')) {

    //         // Delete old file
    //         if ($project->attachment && file_exists(public_path('uploads/projects/' . $project->attachment))) {
    //             unlink(public_path('uploads/projects/' . $project->attachment));
    //         }

    //         $file = $request->file('attachment');
    //         $filename = time() . '_' . $file->getClientOriginalName();
    //         $file->move(public_path('uploads/projects'), $filename);
    //         $project->attachment = $filename;
    //     }

    //     $project->save();

    //     return redirect()->route('indexProject')
    //         ->with('success', 'Project Updated Successfully');
    // }
    public function deleteproject($id)
    {
        $project = Project::findOrFail($id);
        // if ($project->attachment && file_exists(public_path('uploads/projects/' . $project->attachment))) {
        //     unlink(public_path('uploads/projects/' . $project->attachment));
        // }

        $project->delete();

        return redirect()->route('indexProject')
            ->with('success', 'Project Deleted Successfully');
    }

    public function add_panel_board(Request $request, $project_id)
    {
        $project = Project::findOrFail($project_id);

        $projects = Project::where('registration_id', Auth::id())->get();

        $systemRatings = SystemCurrentRating::where('iStatus', 1)->where('isDelete', 0)->get();
        $noOfPoles = NoOfPoles::where('iStatus', 1)->where('isDelete', 0)->get();
        $panelTypes = PanelType::where('iStatus', 1)->where('isDelete', 0)->get();
        $operatingVoltages = OperatingVoltage::where('iStatus', 1)->where('isDelete', 0)->get();
        $formTypes = FormType::where('iStatus', 1)->where('isDelete', 0)->get();
        $panelAccesses = PanelAccess::where('iStatus', 1)->where('isDelete', 0)->get();
        $panelColours = PanelBoardColour::where('iStatus', 1)->where('isDelete', 0)->get();
        $ipProtections = IpProtection::where('iStatus', 1)->where('isDelete', 0)->get();
        $lockSystems = LockSystem::where('iStatus', 1)->where('isDelete', 0)->get();
        $busbarPositions = BusbarPosition::where('iStatus', 1)->where('isDelete', 0)->get();
        $glandPlateThicknesses = GlandPlateThickness::where('iStatus', 1)->where('isDelete', 0)->get();
        $outgoingCablePositions = OutgoingCablePosition::where('iStatus', 1)->where('isDelete', 0)->get();
        $plinthTypes = PlinthType::where('iStatus', 1)->where('isDelete', 0)->get();
        $certifications = Certification::where('iStatus', 1)->where('isDelete', 0)->get();

        return view('user.add-panel-board', compact(
            'project',   // 👈 important
            'projects',
            'systemRatings',
            'noOfPoles',
            'panelTypes',
            'operatingVoltages',
            'formTypes',
            'panelAccesses',
            'panelColours',
            'ipProtections',
            'lockSystems',
            'busbarPositions',
            'glandPlateThicknesses',
            'outgoingCablePositions',
            'plinthTypes',
            'certifications'
        ));
    }


    public function store_panel_board(Request $request)
    {
        $request->validate([
            'project_id' => 'required',
            'panel_board_name' => 'required',
            'panel_board_job_no' => 'required'
        ]);

        ProjectPanelBoard::create([
            'project_id' => $request->project_id,
            'registration_id' => Auth::id(),
            'panel_board_job_no' => $request->panel_board_job_no,
            'panel_board_name' => $request->panel_board_name,

            'system_current_rating_id' => $request->system_current_rating_id,
            'no_of_poles_id' => $request->no_of_poles_id,
            'type_of_panel_id' => $request->type_of_panel_id,
            'operating_voltage_id' => $request->operating_voltage_id,
            'form_type_id' => $request->form_type_id,
            'panel_access_id' => $request->panel_access_id,
            'panel_board_colour_id' => $request->panel_board_colour_id,
            'ip_protection_id' => $request->ip_protection_id,

            'frame_height' => $request->frame_height,
            'lock_system_id' => $request->lock_system_id,
            'busbar_position_id' => $request->busbar_position_id,
            'frame_width' => $request->frame_width,
            'gland_plate_thickness_id' => $request->gland_plate_thickness_id,
            'outgoing_cable_position_id' => $request->outgoing_cable_position_id,
            'frame_depth' => $request->frame_depth,
            'plinth_height' => $request->plinth_height,
            'plinth_type_id' => $request->plinth_type_id,
            'thickness' => $request->thickness,
            'certification_id' => $request->certification_id,
        ]);

        return redirect()->route('PanelListing', $request->project_id)
            ->with('success', 'Panel Board Created Successfully');
    }

    public function edit_panel_board($project_id, $id)
    {
        $project = Project::findOrFail($project_id);
        $panelBoard = ProjectPanelBoard::findOrFail($id);

        $projects = Project::where('registration_id', Auth::id())->get();

        $systemRatings = SystemCurrentRating::where('iStatus', 1)->where('isDelete', 0)->get();
        $noOfPoles = NoOfPoles::where('iStatus', 1)->where('isDelete', 0)->get();
        $panelTypes = PanelType::where('iStatus', 1)->where('isDelete', 0)->get();
        $operatingVoltages = OperatingVoltage::where('iStatus', 1)->where('isDelete', 0)->get();
        $formTypes = FormType::where('iStatus', 1)->where('isDelete', 0)->get();
        $panelAccesses = PanelAccess::where('iStatus', 1)->where('isDelete', 0)->get();
        $panelColours = PanelBoardColour::where('iStatus', 1)->where('isDelete', 0)->get();
        $ipProtections = IpProtection::where('iStatus', 1)->where('isDelete', 0)->get();
        $lockSystems = LockSystem::where('iStatus', 1)->where('isDelete', 0)->get();
        $busbarPositions = BusbarPosition::where('iStatus', 1)->where('isDelete', 0)->get();
        $glandPlateThicknesses = GlandPlateThickness::where('iStatus', 1)->where('isDelete', 0)->get();
        $outgoingCablePositions = OutgoingCablePosition::where('iStatus', 1)->where('isDelete', 0)->get();
        $plinthTypes = PlinthType::where('iStatus', 1)->where('isDelete', 0)->get();
        $certifications = Certification::where('iStatus', 1)->where('isDelete', 0)->get();

        return view('user.add-panel-board', compact(
            'project',
            'panelBoard',   // 👈 IMPORTANT
            'projects',
            'systemRatings',
            'noOfPoles',
            'panelTypes',
            'operatingVoltages',
            'formTypes',
            'panelAccesses',
            'panelColours',
            'ipProtections',
            'lockSystems',
            'busbarPositions',
            'glandPlateThicknesses',
            'outgoingCablePositions',
            'plinthTypes',
            'certifications'
        ));
    }
    public function update_panel_board(Request $request, $id)
    {
        $request->validate([
            'project_id' => 'required',
            'panel_board_name' => 'required',
            'panel_board_job_no' => 'required'
        ]);

        $panelBoard = ProjectPanelBoard::findOrFail($id);

        $panelBoard->update([
            'panel_board_name' => $request->panel_board_name,

            'system_current_rating_id' => $request->system_current_rating_id,
            'no_of_poles_id' => $request->no_of_poles_id,
            'type_of_panel_id' => $request->type_of_panel_id,
            'operating_voltage_id' => $request->operating_voltage_id,
            'form_type_id' => $request->form_type_id,
            'panel_access_id' => $request->panel_access_id,
            'panel_board_colour_id' => $request->panel_board_colour_id,
            'ip_protection_id' => $request->ip_protection_id,

            'frame_height' => $request->frame_height,
            'lock_system_id' => $request->lock_system_id,
            'busbar_position_id' => $request->busbar_position_id,
            'frame_width' => $request->frame_width,
            'gland_plate_thickness_id' => $request->gland_plate_thickness_id,
            'outgoing_cable_position_id' => $request->outgoing_cable_position_id,
            'frame_depth' => $request->frame_depth,
            'plinth_height' => $request->plinth_height,
            'plinth_type_id' => $request->plinth_type_id,
            'thickness' => $request->thickness,
            'certification_id' => $request->certification_id,
        ]);

        return redirect()->route('PanelListing', $request->project_id)
            ->with('success', 'Panel Board Updated Successfully');
    }

    public function delete_panel_board($id)
    {
        dd($id);
        $panel = ProjectPanelBoard::findOrFail($id);

        $projectId = $panel->project_id;

        $panel->delete();

        return redirect()->route('PanelListing', $projectId)
            ->with('success', 'Panel Board Deleted Successfully');
    }
    // public function delete_panel_board($id)
    // {
    //     dd($id);
    //     $panel = ProjectPanelBoard::findOrFail($id);
    //     $projectId = $panel->project_id;

    //     $panel->delete();

    //     return redirect()->route('PanelListing', $projectId)
    //         ->with('success', 'Panel Board Deleted Successfully');
    // }

    public function getPanelJobNo($project_id)
    {
        try {
            $project = Project::find($project_id);

            if (!$project) {
                return response()->json(['job_no' => '']);
            }

            $lastPanel = ProjectPanelBoard::where('project_id', $project_id)
                ->orderBy('id', 'desc')
                ->first();

            if ($lastPanel && $lastPanel->panel_board_job_no) {
                $parts = explode('-', $lastPanel->panel_board_job_no);
                $lastNumber = (int) end($parts);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            $jobNo = 'PB-' . $project->inquiry_no . '-' . $nextNumber;

            return response()->json([
                'job_no' => $jobNo
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'job_no' => '',
                'message' => $e->getMessage() // 👈 SHOW REAL ERROR
            ]);
        }
    }


    // public function getPanelJobNo($project_id)
    // {
    //     $project = Project::find($project_id);

    //     if (!$project) {
    //         return response()->json(['job_no' => '']);
    //     }

    //     // Get last panel of this project
    //     $lastPanel = ProjectPanelBoard::where('project_id', $project_id)
    //         ->orderBy('id', 'desc')
    //         ->first();

    //     if ($lastPanel) {
    //         // Split by "-"
    //         $parts = explode('-', $lastPanel->panel_board_job_no);

    //         // Get last number dynamically
    //         $lastNumber = (int) end($parts);

    //         $nextNumber = $lastNumber + 1; // ✅ no padding
    //     } else {
    //         $nextNumber = 1; // ✅ start from 1
    //     }

    //     $jobNo = 'PB-' . $project->inquiry_no . '-' . $nextNumber;

    //     return response()->json([
    //         'job_no' => $jobNo
    //     ]);
    // }


    // public function add_panel_board(Request $request)
    // {

    //     $projects = Project::where('registration_id', Auth::id())->get();

    //     $systemRatings = SystemCurrentRating::where('iStatus', 1)
    //         ->where('isDelete', 0)
    //         ->get();

    //     $noOfPoles = NoOfPoles::where('iStatus', 1)
    //         ->where('isDelete', 0)
    //         ->get();

    //     $panelTypes = PanelType::where('iStatus', 1)
    //         ->where('isDelete', 0)
    //         ->get();

    //     $operatingVoltages = OperatingVoltage::where('iStatus', 1)
    //         ->where('isDelete', 0)
    //         ->get();

    //     $formTypes = FormType::where('iStatus', 1)
    //         ->where('isDelete', 0)
    //         ->get();

    //     $panelAccesses = PanelAccess::where('iStatus', 1)
    //         ->where('isDelete', 0)
    //         ->get();

    //     $panelColours = PanelBoardColour::where('iStatus', 1)
    //         ->where('isDelete', 0)
    //         ->get();

    //     $ipProtections = IpProtection::where('iStatus', 1)
    //         ->where('isDelete', 0)
    //         ->get();

    //     $lockSystems = LockSystem::where('iStatus', 1)
    //         ->where('isDelete', 0)
    //         ->get();

    //     $busbarPositions = BusbarPosition::where('iStatus', 1)
    //         ->where('isDelete', 0)
    //         ->get();

    //     $glandPlateThicknesses = GlandPlateThickness::where('iStatus', 1)
    //         ->where('isDelete', 0)
    //         ->get();

    //     $outgoingCablePositions = OutgoingCablePosition::where('iStatus', 1)
    //         ->where('isDelete', 0)
    //         ->get();

    //     $plinthTypes = PlinthType::where('iStatus', 1)
    //         ->where('isDelete', 0)
    //         ->get();

    //     $certifications = Certification::where('iStatus', 1)
    //         ->where('isDelete', 0)
    //         ->get();

    //     return view('user.add-panel-board', compact(
    //         'projects',
    //         'systemRatings',
    //         'noOfPoles',
    //         'panelTypes',
    //         'operatingVoltages',
    //         'formTypes',
    //         'panelAccesses',
    //         'panelColours',
    //         'ipProtections',
    //         'lockSystems',
    //         'busbarPositions',
    //         'glandPlateThicknesses',
    //         'outgoingCablePositions',
    //         'plinthTypes',
    //         'certifications'
    //     ));
    // }

    // public function getPanelJobNo($project_id)
    // {
    //     $project = Project::find($project_id);

    //     if (!$project) {
    //         return response()->json(['job_no' => '']);
    //     }

    //     // Get last panel of this project
    //     $lastPanel = ProjectPanelBoard::where('project_id', $project_id)
    //         ->orderBy('id', 'desc')
    //         ->first();

    //     if ($lastPanel) {
    //         // Extract last 3 digits
    //         $lastNumber = (int) substr($lastPanel->panel_board_job_no, -3);
    //         $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    //     } else {
    //         $nextNumber = '001';
    //     }

    //     $jobNo = 'PB-' . $project->inquiry_no . '-' . $nextNumber;

    //     return response()->json([
    //         'job_no' => $jobNo
    //     ]);
    // }

    // public function getPanelJobNo($project_id)
    // {
    //     $project = Project::find($project_id);

    //     if (!$project) {
    //         return response()->json(['job_no' => '']);
    //     }

    //     // Count existing panel boards for this project
    //     $count = ProjectPanelBoard::where('project_id', $project_id)->count();

    //     // Generate next number (3 digit format)
    //     $nextNumber = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

    //     // Format: PB-2526-263-003
    //     $jobNo = 'PB-' . $project->inquiry_no . '-' . $nextNumber;

    //     return response()->json([
    //         'job_no' => $jobNo
    //     ]);
    // }

    public function create_section(Request $request)
    {

        return view('user.add-sections-demo');
    }


    public function toggleStatus($id)
    {
        $project = Project::findOrFail($id);

        // security: only own project
        if ($project->registration_id != auth()->id()) {
            abort(403);
        }

        $project->status = $project->status == 1 ? 2 : 1;
        $project->save();

        return redirect()->back()->with('success', 'Status updated successfully');
    }
}
