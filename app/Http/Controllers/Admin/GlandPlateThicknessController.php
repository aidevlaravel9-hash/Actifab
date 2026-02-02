<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GlandPlateThickness;


class GlandPlateThicknessController extends Controller
{
    public function index(Request $request)
    {
        $query = GlandPlateThickness::where('isDelete', 0);
        if ($request->search) {
            $query->where('gland_plate_thickness', 'like', "%{$request->search}%");
        }
        $data = $query->orderByDesc('gland_plate_thickness_id')->paginate(10);
        return view('admin.gland-plate-thickness.index', compact('data'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'gland_plate_thickness' => 'required|unique:gland_plate_thickness,gland_plate_thickness,NULL,gland_plate_thickness_id,isDelete,0',
        ]);


        GlandPlateThickness::create([
            'gland_plate_thickness' => $request->gland_plate_thickness,
            'iStatus' => $request->iStatus ?? 1,
        ]);


        return redirect()->back()->with('success', 'Gland Plate Thickness added successfully!');
    }


    public function update(Request $request)
    {
        $request->validate([
            'gland_plate_thickness' => 'required|unique:gland_plate_thickness,gland_plate_thickness,' . $request->edit_id . ',gland_plate_thickness_id,isDelete,0',
        ]);


        $record = GlandPlateThickness::findOrFail($request->edit_id);
        $record->update([
            'gland_plate_thickness' => $request->gland_plate_thickness,
            'iStatus' => $request->iStatus ?? 1,
        ]);


        return redirect()->back()->with('success', 'Gland Plate Thickness updated successfully!');
    }


    public function destroy(Request $request)
    {
        GlandPlateThickness::where('gland_plate_thickness_id', $request->id)->update(['isDelete' => 1]);
        return response()->json(['status' => true]);
    }


    public function bulkDelete(Request $request)
    {
        if ($request->ids) {
            GlandPlateThickness::whereIn('gland_plate_thickness_id', $request->ids)->update(['isDelete' => 1]);
        }
        return response()->json(['status' => true]);
    }


    public function status(Request $request)
    {
        $record = GlandPlateThickness::findOrFail($request->id);
        $record->iStatus = $record->iStatus ? 0 : 1;
        $record->save();


        return response()->json(['status' => true, 'new_status' => $record->iStatus]);
    }
}
