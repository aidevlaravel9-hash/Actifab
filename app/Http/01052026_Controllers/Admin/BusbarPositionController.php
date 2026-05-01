<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusbarPosition;

class BusbarPositionController extends Controller
{
    public function index(Request $request)
    {
        $query = BusbarPosition::where('isDelete', 0);

        if ($request->search) {
            $query->where('busbar_position', 'like', '%' . $request->search . '%');
        }

        $data = $query->orderBy('busbar_position_id', 'desc')->get();
        return view('admin.busbar-position.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'busbar_position' =>
            'required|unique:busbar_position,busbar_position,NULL,busbar_position_id,isDelete,0',
        ]);

        BusbarPosition::create([
            'busbar_position' => $request->busbar_position,
            'iStatus' => $request->iStatus ?? 1,
        ]);

        return redirect()->back()->with('success', 'Busbar Position added successfully!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'busbar_position' =>
            'required|unique:busbar_position,busbar_position,' .
                $request->edit_id . ',busbar_position_id,isDelete,0',
        ]);

        $record = BusbarPosition::findOrFail($request->edit_id);
        $record->update([
            'busbar_position' => $request->busbar_position,
            'iStatus' => $request->iStatus ?? 1,
        ]);

        return redirect()->back()->with('success', 'Busbar Position updated successfully!');
    }


    public function destroy($id)
    {
        $BusbarPosition = BusbarPosition::findOrFail($id);
        $BusbarPosition->delete();

        return redirect()
            ->route('busbar-position.index')
            ->with('success', 'Busbar Position deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        if ($request->ids) {
            BusbarPosition::whereIn('busbar_position_id', $request->ids)
                ->update(['isDelete' => 1]);
        }
        return response()->json(['status' => true]);
    }

    public function status(Request $request)
    {
        $record = BusbarPosition::findOrFail($request->id);
        $record->iStatus = $record->iStatus == 1 ? 0 : 1;
        $record->save();

        return response()->json([
            'status' => true,
            'new_status' => $record->iStatus
        ]);
    }
}
