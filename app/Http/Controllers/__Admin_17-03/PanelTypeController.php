<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PanelType;
use Illuminate\Support\Facades\Validator;

class PanelTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = PanelType::where('isDelete', 0);

        if ($request->has('search') && $request->search != '') {
            $query->where('panel_type', 'like', '%' . $request->search . '%');
        }

        $data = $query->orderBy('panel_type_id', 'desc')->paginate(10);
        return view('admin.panel-type.index', compact('data'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'panel_type' =>
            'required|unique:panel_type_master,panel_type,NULL,panel_type_id,isDelete,0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        PanelType::create([
            'panel_type' => $request->panel_type,
            'iStatus' => $request->iStatus ?? 1,
        ]);

        return redirect()->back()->with('success', 'Panel Type added successfully!');
    }

    public function edit($id)
    {
        $record = PanelType::findOrFail($id);
        return response()->json($record);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'panel_type' =>
            'required|unique:panel_type_master,panel_type,' .
                $request->edit_id . ',panel_type_id,isDelete,0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $record = PanelType::findOrFail($request->edit_id);
        $record->update([
            'panel_type' => $request->panel_type,
            'iStatus' => $request->iStatus ?? 1,
        ]);

        return redirect()->back()->with('success', 'Panel Type updated successfully!');
    }

    public function destroy($id)
    {
        $PanelType = PanelType::findOrFail($id);
        $PanelType->delete();

        return redirect()
            ->route('panel-type.index')
            ->with('success', 'Panel Type deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        if ($request->has('ids')) {
            PanelType::whereIn('panel_type_id', $request->ids)->update(['isDelete' => 1]);
        }

        return response()->json(['status' => true]);
    }

    public function status(Request $request)
    {
        $record = PanelType::findOrFail($request->id);
        $record->iStatus = $record->iStatus == 1 ? 0 : 1;
        $record->save();

        return response()->json(['status' => true, 'new_status' => $record->iStatus]);
    }
}
