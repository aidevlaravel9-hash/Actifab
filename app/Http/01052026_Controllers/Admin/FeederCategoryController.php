<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FeederCategory;
use Illuminate\Support\Facades\Validator;
use App\Models\SectionMaster;
use Illuminate\Validation\Rule;

class FeederCategoryController extends Controller
{
    public function index()
    {
        $data = FeederCategory::with('section') 
                ->orderBy('feeder_category_id', 'desc')
                ->get();
            
        $sections = SectionMaster::where('isDelete', 1)->get(); 
        

        return view('admin.feeder-category.index', compact('data','sections'));
    }


    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'feeder_category_name' => [
                'required',
                Rule::unique('feeder_category_master')
                    ->where(function ($query) use ($request) {
                        return $query->where('section_master_id', $request->section_master_id)
                                     ->where('isDelete', 0);
                    })
                    ->ignore($request->edit_id, 'feeder_category_id'),
            ],
            'section_master_id' => 'required',
        ]);

        // $validator = Validator::make($request->all(), [
        //     'feeder_category_name' =>
        //     'required|unique:feeder_category_master,feeder_category_name,NULL,feeder_category_id,isDelete,0',
        //     'section_master_id' => 'required',
        // ]);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        FeederCategory::create([
            'feeder_category_name' => $request->feeder_category_name,
            'section_master_id' => $request->section_master_id, // ADD THIS
            'iStatus' => $request->iStatus ?? 1,
        ]);

        return redirect()->back()->with('success', 'Feeder Category added successfully!');
    }

    public function edit($id)
    {
        $record = FeederCategory::findOrFail($id);
        return response()->json($record);
    }
    

public function update(Request $request)
{
    $validator = Validator::make($request->all(), [
        'feeder_category_name' => [
            'required',
            Rule::unique('feeder_category_master')
                ->where(function ($query) use ($request) {
                    return $query->where('section_master_id', $request->section_master_id)
                                 ->where('isDelete', 0);
                })
                ->ignore($request->edit_id, 'feeder_category_id'),
        ],
        'section_master_id' => 'required'
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $record = FeederCategory::findOrFail($request->edit_id);
    $record->update([
        'feeder_category_name' => $request->feeder_category_name,
        'section_master_id' => $request->section_master_id,
        'iStatus' => $request->iStatus ?? 1,
    ]);

    return redirect()->back()->with('success', 'Feeder Category updated successfully!');
}

    // public function update(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'feeder_category_name' =>
    //         'required|unique:feeder_category_master,feeder_category_name,' .
    //             $request->edit_id . ',feeder_category_id,isDelete,0',
    //             'section_master_id' => 'required' 
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->back()->withErrors($validator)->withInput();
    //     }

    //     $record = FeederCategory::findOrFail($request->edit_id);
    //     $record->update([
    //         'feeder_category_name' => $request->feeder_category_name,
    //         'section_master_id' => $request->section_master_id, // ADD THIS
    //         'iStatus' => $request->iStatus ?? 1,
    //     ]);

    //     return redirect()->back()->with('success', 'Feeder Category updated successfully!');
    // }

    public function destroy($id)
    {
        $FeederCategory = FeederCategory::findOrFail($id);
        $FeederCategory->delete();

        return redirect()
            ->route('feeder-category.index')
            ->with('success', 'Feeder Category deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        if ($request->has('ids')) {
            FeederCategory::whereIn('feeder_category_id', $request->ids)->update(['isDelete' => 1]);
        }
        return response()->json(['status' => true]);
    }

    public function status(Request $request)
    {
        $record = FeederCategory::findOrFail($request->id);
        $record->iStatus = $record->iStatus == 1 ? 0 : 1;
        $record->save();

        return response()->json(['status' => true, 'new_status' => $record->iStatus]);
    }
}
