<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FeederSubType;
use App\Models\SectionMaster;
use App\Models\FeederCategory;
use App\Models\FeederType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FeederSubTypeController extends Controller
{

    public function getCategory($section_id)
    {
        return FeederCategory::where('section_master_id', $section_id)
            ->where('isDelete', 0)
            ->get();
    }
    
    public function getType($category_id)
    {
        return 
        $test = FeederType::where('feeder_category_id', $category_id)
            ->where('isDelete', 0)
            ->get();
        dd($test);
    }

    public function index(Request $request)
    {
        $query = FeederSubType::where('isDelete', 0)->with('feederType');

        // $data = $query->orderBy('feeder_sub_type_id', 'desc')->get();
        $data = FeederSubType::with(['feederType', 'category', 'section'])
                ->where('isDelete', 0)
                ->orderBy('feeder_sub_type_id', 'desc')
                ->get();

        $feederTypes = FeederType::where('isDelete', 0)
            ->orderBy('feeder_type')
            ->get();
            
            $sections = SectionMaster::where('isDelete', 1)->get();

        return view('admin.feeder-sub-type.index', compact('data', 'feederTypes','sections'));
    }
public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'feeder_type_id' => 'required|exists:feeder_type,feeder_type_id,isDelete,0',
        'feeder_sub_type' => [
            'required',
            Rule::unique('feeder_sub_type_master')
                ->where(function ($query) use ($request) {
                    return $query->where('feeder_type_id', $request->feeder_type_id)
                                 ->where('isDelete', 0);
                }),
        ],
        // 'feeder_sub_type' => [
        //     'required',
        //     Rule::unique('feeder_sub_type_master')
        //         ->where(function ($query) use ($request) {
        //             return $query->where('feeder_type_id', $request->feeder_type_id)
        //                          ->where('isDelete', 0);
        //         }),
        // ],
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }
     $imageName = null;

        if ($request->hasFile('image')) {

            $file = $request->file('image');

            // Unique image name
            $imageName = time() . '_' . mt_rand(1000, 9999) . '.' . $file->getClientOriginalExtension();

            // Path using helper
            $uploadPath = FolderPath('/uploads/feeder_sub_type');

            // Create folder if not exists
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Move image
            $file->move($uploadPath, $imageName);
        }

    // 🔥 GET TYPE → CATEGORY → SECTION
    $type = FeederType::with('FeederCategory')->findOrFail($request->feeder_type_id);

    FeederSubType::create([
        'section_master_id'   => $type->section_master_id,
        'feeder_category_id'  => $type->feeder_category_id,
        'feeder_type_id'      => $request->feeder_type_id,
        'feeder_sub_type'     => $request->feeder_sub_type,
        'image'               => $imageName ?? null,
        'iStatus'             => $request->iStatus ?? 1,
    ]);

    return redirect()->back()->with('success', 'Feeder Sub Type added successfully!');
}

    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'feeder_type_id' => 'required|exists:feeder_type,feeder_type_id,isDelete,0',
    //         'feeder_sub_type' =>
    //         'required|unique:feeder_sub_type_master,feeder_sub_type,NULL,feeder_sub_type_id,isDelete,0',
    //         'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->back()->withErrors($validator)->withInput();
    //     }

    //     $imageName = null;

    //     if ($request->hasFile('image')) {

    //         $file = $request->file('image');

    //         // Unique image name
    //         $imageName = time() . '_' . mt_rand(1000, 9999) . '.' . $file->getClientOriginalExtension();

    //         // Path using helper
    //         $uploadPath = FolderPath('/uploads/feeder_sub_type');

    //         // Create folder if not exists
    //         if (!is_dir($uploadPath)) {
    //             mkdir($uploadPath, 0755, true);
    //         }

    //         // Move image
    //         $file->move($uploadPath, $imageName);
    //     }
    //     FeederSubType::create([
    //         'feeder_type_id'  => $request->feeder_type_id,
    //         'feeder_sub_type' => $request->feeder_sub_type,
    //         'image'           => $imageName, // âœ… nullable
    //         'iStatus'         => $request->iStatus ?? 1,
    //     ]);
    //     // dd($request->all());

    //     return redirect()->back()->with('success', 'Feeder Sub Type added successfully!');
    // }


    // public function edit($id)
    // {
    //     $record = FeederSubType::with('feederType')->findOrFail($id);
    //     return response()->json($record);
    // }
    
    public function edit($id)
{
    $record = FeederSubType::findOrFail($id);

    return response()->json([
        'feeder_sub_type_id' => $record->feeder_sub_type_id,
        'feeder_sub_type' => $record->feeder_sub_type,
        'feeder_type_id' => $record->feeder_type_id,
        'feeder_category_id' => $record->feeder_category_id,
        'section_master_id' => $record->section_master_id,
        'image' => $record->image,
        'iStatus' => $record->iStatus,
    ]);
}


public function update(Request $request)
{
    $validator = Validator::make($request->all(), [
        'feeder_type_id' => 'required|exists:feeder_type,feeder_type_id,isDelete,0',
        
        'feeder_sub_type' => [
            'required',
            Rule::unique('feeder_sub_type_master')
                ->where(function ($query) use ($request) {
                    return $query->where('feeder_type_id', $request->feeder_type_id)
                                 ->where('isDelete', 0);
                })
                ->ignore($request->edit_id, 'feeder_sub_type_id'),
        ],

        // 'feeder_sub_type' => [
        //     'required',
        //     Rule::unique('feeder_sub_type_master')
        //         ->where(function ($query) use ($request) {
        //             return $query->where('feeder_type_id', $request->feeder_type_id)
        //                          ->where('isDelete', 0);
        //         })
        //         ->ignore($request->edit_id, 'feeder_sub_type_id'),
        // ],
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $record = FeederSubType::findOrFail($request->edit_id);

    // 🔥 IMAGE HANDLE
    if ($request->hasFile('image')) {

        $file = $request->file('image');
        $uploadPath = FolderPath('/uploads/feeder_sub_type');

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // delete old image
        if ($record->image) {
            $oldImagePath = $uploadPath . '/' . $record->image;
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        $imageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move($uploadPath, $imageName);

        $record->image = $imageName;
    }

    // 🔥 GET TYPE → CATEGORY → SECTION (MAIN FIX)
    $type = FeederType::findOrFail($request->feeder_type_id);

    // 🔥 UPDATE ALL FIELDS
    $record->section_master_id   = $type->section_master_id;
    $record->feeder_category_id  = $type->feeder_category_id;
    $record->feeder_type_id      = $request->feeder_type_id;
    $record->feeder_sub_type     = $request->feeder_sub_type;
    $record->iStatus             = $request->iStatus ?? 1;

    $record->save();

    return redirect()->back()->with('success', 'Feeder Sub Type updated successfully!');
}

    // public function update(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'feeder_type_id' => 'required|exists:feeder_type,feeder_type_id,isDelete,0',
    //         'feeder_sub_type' =>
    //         'required|unique:feeder_sub_type_master,feeder_sub_type,' .
    //             $request->edit_id . ',feeder_sub_type_id,isDelete,0',
    //         'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->back()->withErrors($validator)->withInput();
    //     }

    //     $record = FeederSubType::findOrFail($request->edit_id);

    //     // ðŸ”¹ Handle image replace (optional)
    //     if ($request->hasFile('image')) {

    //         $file = $request->file('image');

    //         // Get upload path using helper
    //         $uploadPath = FolderPath('/uploads/feeder_sub_type');

    //         // Create folder if not exists
    //         if (!is_dir($uploadPath)) {
    //             mkdir($uploadPath, 0755, true);
    //         }

    //         if ($record->image) {

    //             $oldImagePath = $uploadPath . '/' . $record->image;

    //             if (file_exists($oldImagePath)) {
    //                 unlink($oldImagePath);
    //             }
    //         }

    //         // Generate unique image name
    //         $imageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

    //         // Move new image
    //         $file->move($uploadPath, $imageName);

    //         // Save new image name
    //         $record->image = $imageName;
    //     }

    //     // ðŸ”¹ Update other fields
    //     $record->feeder_type_id  = $request->feeder_type_id;
    //     $record->feeder_sub_type = $request->feeder_sub_type;
    //     $record->iStatus         = $request->iStatus ?? 1;
    //     $record->save();

    //     return redirect()->back()->with('success', 'Feeder Sub Type updated successfully!');
    // }


    public function destroy($id)
    {
        $FeederSubType = FeederSubType::findOrFail($id);
        $FeederSubType->delete();

        return redirect()
            ->route('feeder-sub-type.index')
            ->with('success', 'Feeder Feeder Sub Type deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        if ($request->has('ids')) {
            FeederSubType::whereIn('feeder_sub_type_id', $request->ids)->update(['isDelete' => 1]);
        }

        return response()->json(['status' => true]);
    }

    public function status(Request $request)
    {
        $record = FeederSubType::findOrFail($request->id);
        $record->iStatus = $record->iStatus == 1 ? 0 : 1;
        $record->save();

        return response()->json(['status' => true, 'new_status' => $record->iStatus]);
    }
}
