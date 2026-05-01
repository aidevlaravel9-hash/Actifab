<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OperatingVoltage;

class OperatingVoltageController extends Controller
{
    public function index(Request $request)
    {
        $query = OperatingVoltage::where('isDelete', 0);
        if ($request->search) {
            $query->where('operating_voltage', 'like', '%' . $request->search . '%');
        }
        $data = $query->orderBy('operating_voltage_id', 'desc')->get();
        return view('admin.operating-voltage.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'operating_voltage' => 'required|unique:operating_voltage,operating_voltage,NULL,operating_voltage_id,isDelete,0',
        ]);

        OperatingVoltage::create([
            'operating_voltage' => $request->operating_voltage,
            'iStatus' => $request->iStatus ?? 1,
        ]);

        return redirect()->back()->with('success', 'Operating Voltage added successfully!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'operating_voltage' => 'required|unique:operating_voltage,operating_voltage,' . $request->edit_id . ',operating_voltage_id,isDelete,0',
        ]);

        $record = OperatingVoltage::findOrFail($request->edit_id);
        $record->update([
            'operating_voltage' => $request->operating_voltage,
            'iStatus' => $request->iStatus ?? 1,
        ]);

        return redirect()->back()->with('success', 'Updated successfully!');
    }


    public function destroy($id)
    {
        $OperatingVoltage = OperatingVoltage::findOrFail($id);
        $OperatingVoltage->delete();

        return redirect()
            ->route('voltage.index')
            ->with('success', 'Operating Voltage deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        if ($request->has('ids')) {
            OperatingVoltage::whereIn('operating_voltage_id', $request->ids)->update(['isDelete' => 1]);
        }
        return response()->json(['status' => true]);
    }

    public function status(Request $request)
    {
        $record = OperatingVoltage::findOrFail($request->id);
        $record->iStatus = $record->iStatus == 1 ? 0 : 1;
        $record->save();
        return response()->json(['status' => true, 'new_status' => $record->iStatus]);
    }
}
