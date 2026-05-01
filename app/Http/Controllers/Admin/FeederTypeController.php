<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeederCategory;
use App\Models\SectionMaster;
use Illuminate\Http\Request;
use App\Models\FeederType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FeederTypeController extends Controller
{
    public function getFeederCategories($sectionTypeId)
    {

        $categories = \App\Models\FeederCategory::where('section_master_id', $sectionTypeId)
            ->where('iStatus', 1)
            ->get(['feeder_category_id', 'feeder_category_name']);

        return response()->json($categories);
    }
    
    public function getCategory($section_id)
    {
        $categories = FeederCategory::where('section_master_id', $section_id)
            ->where('isDelete', 0)
            ->get();
    
        return response()->json($categories);
    }

    public function index(Request $request)
    {
        $sections = SectionMaster::where('isDelete', 1)->get(); 
        
        $FeederCategory = FeederCategory::where('isDelete', 0)->get();

        $data = FeederType::with('FeederCategory', 'section')
            ->where('isDelete', 0)
            ->orderBy('feeder_type_id', 'desc')
            ->get();

        return view('admin.feeder-type.index', compact('data', 'FeederCategory','sections'));
    }
    // public function index(Request $request)
    // {


    //     $FeederCategory = FeederCategory::where('isDelete', 0)->get();
    //     $query = FeederType::with('FeederCategory')->where('isDelete', 0);

    //     if ($request->has('search') && $request->search != '') {
    //         $query->where('feeder_type', 'like', '%' . $request->search . '%');
    //     }

    //     $data = $query->orderBy('feeder_type_id', 'desc')->paginate(10);
    //     return view('admin.feeder-type.index', compact('data', 'FeederCategory'));
    // }

    public function store(Request $request)
    {
        // dd($request);
        
        $validator = Validator::make($request->all(), [
            'feeder_type' => [
                'required',
                Rule::unique('feeder_type')
                    ->where(function ($query) use ($request) {
                        return $query->where('feeder_category_id', $request->feeder_category_id)
                                     ->where('isDelete', 0);
                    }),
            ],
            'feeder_category_id' => 'required',
        ]);


        // $validator = Validator::make($request->all(), [
        //     'feeder_type' =>
        //     'required|unique:feeder_type,feeder_type,NULL,feeder_type_id,isDelete,0',
        //     'feeder_category_id' => 'required',
            
        // ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $imageName = null;

        if ($request->hasFile('image')) {

            $file = $request->file('image');

            // Unique image name
            $imageName = time() . '_' . mt_rand(1000, 9999) . '.' . $file->getClientOriginalExtension();

            // Path using helper
            $uploadPath = FolderPath('/uploads/feeder_type');

            // Create folder if not exists
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Move image
            $file->move($uploadPath, $imageName);
        }

        $category = FeederCategory::findOrFail($request->feeder_category_id);
        
        FeederType::create([
            'section_master_id' => $category->section_master_id,
            'feeder_category_id' => $request->feeder_category_id,
            'feeder_type' => $request->feeder_type,
            'image'      => $imageName ?? null,
            'iStatus' => $request->iStatus ?? 1,
        ]);

        return redirect()->back()->with('success', 'Feeder Type added successfully!');
    }

    // public function edit($id)
    // {
    //     $record = FeederType::findOrFail($id);
    //     return response()->json($record);
    // }
    
    public function edit($id)
{
    $record = FeederType::findOrFail($id);
    
    return response()->json([
        'feeder_type_id' => $record->feeder_type_id,
        'feeder_type' => $record->feeder_type,
        'feeder_category_id' => $record->feeder_category_id,
        'section_master_id' => $record->section_master_id, // ðŸ”¥ ADD
        'iStatus' => $record->iStatus,
        'image' => $record->image,
    ]);
}

public function update(Request $request)
{
    $validator = Validator::make($request->all(), [
        'feeder_type' => [
            'required',
            Rule::unique('feeder_type')
                ->where(function ($query) use ($request) {
                    return $query->where('feeder_category_id', $request->feeder_category_id)
                                 ->where('isDelete', 0);
                })
                ->ignore($request->edit_id, 'feeder_type_id'),
        ],
        'feeder_category_id' => 'required',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,jfif|max:2048',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    // ✅ GET SECTION FROM CATEGORY
    $category = FeederCategory::findOrFail($request->feeder_category_id);

    $record = FeederType::findOrFail($request->edit_id);

    // ✅ IMAGE HANDLE
    if ($request->hasFile('image')) {

        $file = $request->file('image');
        $uploadPath = FolderPath('/uploads/feeder_type');

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // 🔥 DELETE OLD IMAGE
        if ($record->image) {
            $oldImagePath = $uploadPath . '/' . $record->image;
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        // 🔥 UPLOAD NEW IMAGE
        $imageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move($uploadPath, $imageName);

        $record->image = $imageName;
    }

    // ✅ UPDATE OTHER FIELDS
    $record->section_master_id = $category->section_master_id;
    $record->feeder_category_id = $request->feeder_category_id;
    $record->feeder_type = $request->feeder_type;
    $record->iStatus = $request->iStatus ?? 1;

    $record->save();

    return redirect()->back()->with('success', 'Feeder Type updated successfully!');
}

// public function update(Request $request)
// {
//     $validator = Validator::make($request->all(), [
//     'feeder_type' => [
//         'required',
//         Rule::unique('feeder_type')
//             ->where(function ($query) use ($request) {
//                 return $query->where('feeder_category_id', $request->feeder_category_id)
//                              ->where('isDelete', 0);
//             })
//             ->ignore($request->edit_id, 'feeder_type_id'),
//     ],
//     'feeder_category_id' => 'required',
// ]);


//     if ($validator->fails()) {
//         return redirect()->back()->withErrors($validator)->withInput();
//     }

//     // ðŸ”¥ GET SECTION FROM CATEGORY
//     $category = FeederCategory::findOrFail($request->feeder_category_id);

//     $record = FeederType::findOrFail($request->edit_id);
//     $record->update([
//         'section_master_id' => $category->section_master_id, // âœ… AUTO FIX
//         'feeder_category_id' => $request->feeder_category_id,
//         'feeder_type' => $request->feeder_type,
//         'iStatus' => $request->iStatus ?? 1,
//     ]);

//     return redirect()->back()->with('success', 'Feeder Type updated successfully!');
// }

    public function destroy($id)
    {
        $feederType = FeederType::findOrFail($id);
        $feederType->delete();

        return redirect()
            ->route('feeder-type.index')
            ->with('success', 'Feeder Type deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        if ($request->has('ids')) {
            FeederType::whereIn('feeder_type_id', $request->ids)->update(['isDelete' => 1]);
        }

        return response()->json(['status' => true]);
    }

    public function status(Request $request)
    {
        $record = FeederType::findOrFail($request->id);
        $record->iStatus = $record->iStatus == 1 ? 0 : 1;
        $record->save();

        return response()->json(['status' => true, 'new_status' => $record->iStatus]);
    }
}
