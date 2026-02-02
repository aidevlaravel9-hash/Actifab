<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PanelBoardColour;

class PanelBoardColourController extends Controller
{
    public function index(Request $request)
    {
        $query = PanelBoardColour::where('isDelete', 0);
        if ($request->search) {
            $query->where('panel_board_colour', 'like', '%' . $request->search . '%');
        }
        $data = $query->orderBy('panel_board_colour_id', 'desc')->paginate(10);
        return view('admin.panel-board-colour.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'panel_board_colour' => 'required|unique:panel_board_colour,panel_board_colour,NULL,panel_board_colour_id,isDelete,0',
        ]);

        PanelBoardColour::create([
            'panel_board_colour' => $request->panel_board_colour,
            'iStatus' => $request->iStatus ?? 1,
        ]);

        return redirect()->back()->with('success', 'Panel Board Colour added successfully!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'panel_board_colour' => 'required|unique:panel_board_colour,panel_board_colour,' . $request->edit_id . ',panel_board_colour_id,isDelete,0',
        ]);

        $record = PanelBoardColour::findOrFail($request->edit_id);
        $record->update([
            'panel_board_colour' => $request->panel_board_colour,
            'iStatus' => $request->iStatus ?? 1,
        ]);

        return redirect()->back()->with('success', 'Panel Board Colour updated successfully!');
    }

    public function destroy(Request $request)
    {
        PanelBoardColour::where('panel_board_colour_id', $request->id)->update(['isDelete' => 1]);
        return response()->json(['status' => true]);
    }

    public function bulkDelete(Request $request)
    {
        if ($request->has('ids')) {
            PanelBoardColour::whereIn('panel_board_colour_id', $request->ids)->update(['isDelete' => 1]);
        }
        return response()->json(['status' => true]);
    }

    public function status(Request $request)
    {
        $record = PanelBoardColour::findOrFail($request->id);
        $record->iStatus = $record->iStatus == 1 ? 0 : 1;
        $record->save();
        return response()->json(['status' => true, 'new_status' => $record->iStatus]);
    }
}
