<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PlinthType;


class PlinthTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = PlinthType::where('isDelete', 0);
        if ($request->search) {
            $query->where('plinth_type', 'like', "%{$request->search}%");
        }
        $data = $query->orderByDesc('plinth_type_id')->paginate(10);
        return view('admin.plinth-type.index', compact('data'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'plinth_type' => 'required|unique:plinth_type,plinth_type,NULL,plinth_type_id,isDelete,0',
        ]);


        PlinthType::create([
            'plinth_type' => $request->plinth_type,
            'iStatus' => $request->iStatus ?? 1,
        ]);


        return redirect()->back()->with('success', 'Plinth Type added successfully!');
    }


    public function update(Request $request)
    {
        $request->validate([
            'plinth_type' => 'required|unique:plinth_type,plinth_type,' . $request->edit_id . ',plinth_type_id,isDelete,0',
        ]);


        $record = PlinthType::findOrFail($request->edit_id);
        $record->update([
            'plinth_type' => $request->plinth_type,
            'iStatus' => $request->iStatus ?? 1,
        ]);


        return redirect()->back()->with('success', 'Plinth Type updated successfully!');
    }


    public function destroy(Request $request)
    {
        PlinthType::where('plinth_type_id', $request->id)->update(['isDelete' => 1]);
        return response()->json(['status' => true]);
    }


    public function bulkDelete(Request $request)
    {
        if ($request->ids) {
            PlinthType::whereIn('plinth_type_id', $request->ids)->update(['isDelete' => 1]);
        }
        return response()->json(['status' => true]);
    }


    public function status(Request $request)
    {
        $record = PlinthType::findOrFail($request->id);
        $record->iStatus = $record->iStatus ? 0 : 1;
        $record->save();


        return response()->json(['status' => true, 'new_status' => $record->iStatus]);
    }
}
