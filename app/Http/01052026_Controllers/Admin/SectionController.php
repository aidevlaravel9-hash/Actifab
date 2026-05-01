<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SectionMaster;
use Illuminate\Support\Facades\Validator;

class SectionController extends Controller
{
    public function index(Request $request)
    {

        $data = SectionMaster::where('isDelete', 1)
            ->orderBy('section_id', 'desc')
            ->get();

        // $query = SectionMaster::where('isDelete', 1);

        // if ($request->has('search') && $request->search != '') {
        //     $query->where('section_name', 'like', '%' . $request->search . '%');
        // }

        // $data = $query->orderBy('section_id', 'desc')->get();

        return view('admin.section-master.index', compact('data'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'section_name' =>
            'required|unique:section_master,section_name,NULL,section_id,isDelete,0',
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        SectionMaster::create([
            'section_name' => $request->section_name,
            'iStatus' => $request->iStatus ?? 1,
        ]);

        return redirect()->back()->with('success', 'Section added successfully!');
    }

    public function edit($id)
    {
        $record = SectionMaster::findOrFail($id);
        return response()->json($record);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'section_name' =>
            'required|unique:section_master,section_name,' .
                $request->edit_id . ',section_id,isDelete,0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $record = SectionMaster::findOrFail($request->edit_id);
        $record->update([
            'section_name' => $request->section_name,
            'iStatus' => $request->iStatus ?? 1,
        ]);

        return redirect()->back()->with('success', 'Section updated successfully!');
    }

    public function destroy($id)
    {
        $SectionMaster = SectionMaster::findOrFail($id);
        $SectionMaster->delete();

        return redirect()
            ->route('section.index')
            ->with('success', 'Section deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        if ($request->has('ids')) {
            SectionMaster::whereIn('section_id', $request->ids)->update(['isDelete' => 1]);
        }
        return response()->json(['status' => true]);
    }

    public function status(Request $request)
    {
        $record = SectionMaster::findOrFail($request->id);
        $record->iStatus = $record->iStatus == 1 ? 0 : 1;
        $record->save();

        return response()->json(['status' => true, 'new_status' => $record->iStatus]);
    }
}
