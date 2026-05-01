<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PanelAccess;

class PanelAccessController extends Controller
{
    public function index(Request $request)
    {
        $query = PanelAccess::where('isDelete', 0);
        if ($request->search) {
            $query->where('panel_access', 'like', '%' . $request->search . '%');
        }
        $data = $query->orderBy('panel_access_id', 'desc')->paginate(10);
        return view('admin.panel-access.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'panel_access' => 'required|unique:panel_access,panel_access,NULL,panel_access_id,isDelete,0',
        ]);

        PanelAccess::create([
            'panel_access' => $request->panel_access,
            'iStatus' => $request->iStatus ?? 1,
        ]);

        return redirect()->back()->with('success', 'Panel Access added successfully!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'panel_access' => 'required|unique:panel_access,panel_access,' . $request->edit_id . ',panel_access_id,isDelete,0',
        ]);

        $record = PanelAccess::findOrFail($request->edit_id);
        $record->update([
            'panel_access' => $request->panel_access,
            'iStatus' => $request->iStatus ?? 1,
        ]);

        return redirect()->back()->with('success', 'Panel Access updated successfully!');
    }

    public function destroy(Request $request)
    {
        PanelAccess::where('panel_access_id', $request->id)->update(['isDelete' => 1]);
        return response()->json(['status' => true]);
    }

    public function bulkDelete(Request $request)
    {
        if ($request->has('ids')) {
            PanelAccess::whereIn('panel_access_id', $request->ids)->update(['isDelete' => 1]);
        }
        return response()->json(['status' => true]);
    }

    public function status(Request $request)
    {
        $record = PanelAccess::findOrFail($request->id);
        $record->iStatus = $record->iStatus == 1 ? 0 : 1;
        $record->save();
        return response()->json(['status' => true, 'new_status' => $record->iStatus]);
    }
}
