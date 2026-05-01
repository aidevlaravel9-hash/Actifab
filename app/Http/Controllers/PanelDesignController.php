<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\ProjectPanelBoard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Project;
use App\Models\Section;
use App\Models\Feeder;
use App\Models\FeederCategory;
use App\Models\FeederSubType;
use App\Models\FeederType;
use App\Models\SectionMaster;

class PanelDesignController extends Controller
{
    // public function show($id)
    // {
    //     $panel = ProjectPanelBoard::with('project', 'sections.feeders')
    //         ->findOrFail($id);

    //     $usedWidth = $panel->sections->sum('width');
    //     $remainingWidth = $panel->frame_width - $usedWidth;

    //     // ðŸ‘‡ EDIT MODE CHECK
    //     $editSection = null;

    //     if (request()->has('edit_section')) {
    //         $editSection = $panel->sections
    //             ->where('id', request()->edit_section)
    //             ->first();
    //     }

    //     return view('panel.manage', compact(
    //         'panel',
    //         'remainingWidth',
    //         'editSection'
    //     ));
    // }
    public function getTypes($categoryId)
    {
        $types = FeederType::where('feeder_category_id', $categoryId)
            ->where('iStatus', 1)
            ->get();

        return response()->json($types);
    }


    public function getSubTypes($typeId)
    {
        $subtypes = FeederSubType::where('feeder_type_id', $typeId)
            ->where('iStatus', 1)
            ->get();

        return response()->json($subtypes);
    }

    public function show($panelId, $sectionId = null)
    {
        $panel = ProjectPanelBoard::with('project', 'sections.feeders')
            ->findOrFail($panelId);

        $panel = ProjectPanelBoard::with([
            'project',
            'sections.feeders',
            'sections.feeders.FeederCategory',
            'sections.feeders.FeederType',
            'sections.feeders.FeederSubType'
        ])->findOrFail($panelId);

        $sectionmaster = SectionMaster::where('isDelete', 1)->get();
        $feedercategory = FeederCategory::where('isDelete', 0)->get();
        $feedertype = FeederType::where('isDelete', 0)->get();
        $feedersubtype = FeederSubType::where('isDelete', 0)->get();

        $editSection = null;

        if ($sectionId) {
            $editSection = $panel->sections->where('id', $sectionId)->first();
        }

        $showOnlyNew = request()->has('new');
        $usedWidth = $panel->sections->sum('width');
        $remainingWidth = $panel->frame_width - $usedWidth;

        return view('panel.manage', compact(
            'sectionmaster',
            'panel',
            'remainingWidth',
            'editSection',
            'showOnlyNew',
            'feedercategory',
            'feedersubtype',
            'feedertype'
        ));
    }

    public function storeSection(Request $request)
    {
        $request->validate([
            'panel_id' => 'required|exists:project_panel_boards,id',
            'section_name' => 'required',
            'width' => 'required|numeric|min:1'
        ]);

        $lastSection = Section::with('feeders')
            ->where('panel_id', $request->panel_id)
            ->latest()
            ->first();

        if ($lastSection) {
            $usedHeight = $lastSection->feeders->sum('height');
            $panelHeight = $lastSection->panel->frame_height;

            if ($usedHeight < $panelHeight) {
                return back()->withErrors([
                    'Please complete current section height first'
                ]);
            }
        }
        $panel = ProjectPanelBoard::find($request->panel_id);

        // Already used width
        $usedWidth = (float) Section::where('panel_id', $panel->id)->sum('width');
        $newWidth = (float) $request->width;
        $totalWidth = (float) $panel->frame_width;

        if (($usedWidth + $newWidth) > $totalWidth) {

            $remaining = $totalWidth - $usedWidth;

            return redirect()->back()
                ->withInput()
                ->with([
                    'success' => 'No Width Remain.'
                ]);
        }

        // If valid then store
        $section =  Section::create([
            'panel_id' => $panel->id,
            'section_type_id' => $request->section_type,
            'section_name' => $request->section_name,
            'lock_position' => $request->lock_position,
            'width' => $request->width
        ]);
        // return redirect()->back()->with('success', 'Section Created');
        return redirect()->route('panel.show', [
            $panel->id,
            $section->id
        ])->with('success', 'Section Created');
    }

    public function updateSection(Request $request, $id)
    {
        $request->validate([
            'section_name' => 'required',
            //'height' => 'required|numeric',
            'width' => 'required|numeric',
        ]);

        $section = Section::findOrFail($id);

        $section->update([
            'section_name' => $request->section_name,
            'section_type_id' => $request->section_type,
            'lock_position' => $request->lock_position,
            'width' => $request->width,
        ]);

        return redirect()->back()->with('success', 'Section Updated Successfully');
    }

    public function deleteSection($id)
    {
        $section = Section::findOrFail($id);

        // Agar section ke feeders bhi delete karne ho
        // to pehle feeders delete karo (agar cascade nahi hai)
        $section->feeders()->delete();

        $section->delete();

        return redirect()->back()->with('success', 'Section Deleted Successfully');
    }

    public function sectionFeederList($panelId)
    {

        $panel = ProjectPanelBoard::with([
            'sections.feeders.FeederCategory',
            'sections.feeders.FeederType',
            'sections.feeders.FeederSubType'
        ])->findOrFail($panelId);
        return view('panel.section-list', compact('panel'));
    }

    public function storeFeeder(Request $request)
    {
        $request->validate([
            'panel_id' => 'required|exists:project_panel_boards,id',
            'section_id' => 'required|exists:section,id',
        ]);
        $section = Section::with('feeders', 'panel')->find($request->section_id);

        $panelHeight = $section->panel->frame_height;
        // $tempHeight = 1500

        // ================= NEW VALIDATION =================

        // existing feeders count
        $existingCount = Feeder::where('section_id', $section->id)->count();
        // sirf wo feeders jisme height filled hai
        $newFeeders = collect($request->feeders)->filter(function ($f) {
            return !empty($f['height']);
        });

        $newCount = $newFeeders->count();

        $maxFeeder = 9;

        //  Max feeder check
        if (($existingCount + $newCount) > $maxFeeder) {
            return back()->withErrors([
                'feeder' => "Maximum {$maxFeeder} feeders allowed"
            ]);
        }
        //  Height check
        $tempHeight = Feeder::where('section_id', $section->id)->sum('height');
        foreach ($newFeeders as $feeder) {

            if (($tempHeight + $feeder['height']) > $panelHeight) {
                return back()->withErrors([
                    'height' => 'Total feeder height exceeds panel height'
                ]);
            }

            $tempHeight += $feeder['height'];
        }

        // ================= SAVE LOOP =================
        foreach ($request->feeders as $feeder) {

            if (empty($feeder['height'])) {
                continue;
            }

            $feederCount = Feeder::where('section_id', $section->id)->count();
            $sectionNumber = $section->panel->sections->count();
            $sectionIndex = $section->panel->sections
                ->search(function ($sec) use ($section) {
                    return $sec->id == $section->id;
                }) + 1;
            $feederName = $sectionIndex . 'F' . ($feederCount + 1);

            Feeder::create([
                'panel_id' => $request->panel_id,
                'section_id' => $request->section_id,
                'feeder_name' => $feederName,
                'height' => $feeder['height'],
                'f_category_id' => $feeder['category'] ?? null,
                'f_type_id' => $feeder['type'] ?? null,
                'f_subtype_id' => $feeder['subtype'] ?? null,
                'door_cover' => $feeder['door'] ?? null,
                'customer_remarks' => $feeder['customer_remarks'] ?? null,
            ]);
        }

        return redirect()->back()->with('success', 'Feeders Created Successfully');
    }

    // public function storeFeeder(Request $request)
    // {


    //     $request->validate([
    //         'panel_id' => 'required|exists:project_panel_boards,id',
    //         'section_id' => 'required|exists:section,id',
    //         'feeder_name' => 'required',
    //         'height' => 'required|numeric|min:1',

    //     ]);

    //     $panel = ProjectPanelBoard::find($request->panel_id);
    //     $section = Section::find($request->section_id);

    //     $usedHeight = (float) Feeder::where('section_id', $section->id)->sum('height');
    //     $newHeight = (float) $request->height;
    //     $totalHeight = (float) $panel->frame_height;

    //     $remainingHeight = (float) $totalHeight - $usedHeight;
    //     if ($newHeight > $remainingHeight) {
    //         return redirect()->back()
    //             ->withInput()
    //             ->withErrors([
    //                 'height' => 'No Height Remain.'
    //             ]);
    //     }
    //     Feeder::create([
    //         'panel_id' => $panel->id,
    //         'section_id' => $section->id,
    //         'feeder_name' => $request->feeder_name,
    //         'height' => $request->height,
    //         'f_category_id' => $request->feeder_category,
    //         'f_type_id' => $request->feeder_type,
    //         'f_subtype_id' => $request->feeder_subtype,
    //         'door_cover' => $request->door_cover,
    //     ]);

    //     return redirect()->back()->with('success', 'Feeder Created');
    // }

    public function updateFeeder(Request $request, $id)
    {
        $request->validate([
            'feeder_name' => 'required',
            'height' => 'required|numeric|min:1',
        ]);

        $feeder = Feeder::findOrFail($id);
        $section = Section::findOrFail($feeder->section_id);
        $panel = ProjectPanelBoard::findOrFail($feeder->panel_id);

        $usedHeight = Feeder::where('section_id', $section->id)
            ->where('id', '!=', $feeder->id)
            ->sum('height');

        if (($usedHeight + $request->height) > $panel->frame_height) {
            return back()->withErrors([
                'height' => 'Height exceeds remaining space.'
            ]);
        }
        $feeder->update([
            'feeder_name' => $request->feeder_name,
            'height' => $request->height,
            'f_category_id' => $request->feeder_category,
            'f_subtype_id' => $request->feeder_subtype,
            'f_type_id' => $request->feeder_type,
            'door_cover' => $request->door_cover,
            'customer_remarks' => $request->customer_remarks,
        ]);

        return back()->with('success', 'Feeder updated successfully.');
    }

    public function deleteFeeder($id)
    {
        $feeder = Feeder::findOrFail($id);
        $feeder->delete();

        return back()->with('success', 'Feeder deleted successfully.');
    }
}
