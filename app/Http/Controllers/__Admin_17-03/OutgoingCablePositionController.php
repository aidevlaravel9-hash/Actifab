<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OutgoingCablePosition;


class OutgoingCablePositionController extends Controller
{
    public function index(Request $request)
    {
        $query = OutgoingCablePosition::where('isDelete', 0);
        if ($request->search) {
            $query->where('outgoing_cable_position', 'like', "%{$request->search}%");
        }
        $data = $query->orderByDesc('outgoing_cable_position_id')->paginate(10);
        return view('admin.outgoing-cable-position.index', compact('data'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'outgoing_cable_position' => 'required|unique:outgoing_cable_position,outgoing_cable_position,NULL,outgoing_cable_position_id,isDelete,0',
        ]);


        OutgoingCablePosition::create([
            'outgoing_cable_position' => $request->outgoing_cable_position,
            'iStatus' => $request->iStatus ?? 1,
        ]);


        return redirect()->back()->with('success', 'Outgoing Cable Position added successfully!');
    }


    public function update(Request $request)
    {
        $request->validate([
            'outgoing_cable_position' => 'required|unique:outgoing_cable_position,outgoing_cable_position,' . $request->edit_id . ',outgoing_cable_position_id,isDelete,0',
        ]);


        $record = OutgoingCablePosition::findOrFail($request->edit_id);
        $record->update([
            'outgoing_cable_position' => $request->outgoing_cable_position,
            'iStatus' => $request->iStatus ?? 1,
        ]);


        return redirect()->back()->with('success', 'Outgoing Cable Position updated successfully!');
    }


    public function destroy($id)
    {
        $OutgoingCablePosition = OutgoingCablePosition::findOrFail($id);
        $OutgoingCablePosition->delete();

        return redirect()
            ->route('outgoing-cable-position.index')
            ->with('success', 'Outgoing Cable Position deleted successfully.');
    }


    public function bulkDelete(Request $request)
    {
        if ($request->ids) {
            OutgoingCablePosition::whereIn('outgoing_cable_position_id', $request->ids)->update(['isDelete' => 1]);
        }
        return response()->json(['status' => true]);
    }


    public function status(Request $request)
    {
        $record = OutgoingCablePosition::findOrFail($request->id);
        $record->iStatus = $record->iStatus ? 0 : 1;
        $record->save();


        return response()->json(['status' => true, 'new_status' => $record->iStatus]);
    }
}
