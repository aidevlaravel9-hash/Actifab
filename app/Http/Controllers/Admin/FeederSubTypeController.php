<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FeederSubType;
use App\Models\FeederType;
use Illuminate\Support\Facades\Validator;

class FeederSubTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = FeederSubType::where('isDelete', 0)->with('feederType');

        if ($request->has('search') && $request->search != '') {
            $query->where('feeder_sub_type', 'like', '%' . $request->search . '%');
        }

        $data = $query->orderBy('feeder_sub_type_id', 'desc')->paginate(10);
        $feederTypes = FeederType::where('isDelete', 0)->orderBy('feeder_type')->get();

        return view('admin.feeder-sub-type.index', compact('data', 'feederTypes'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'feeder_type_id' => 'required|exists:feeder_type,feeder_type_id,isDelete,0',
            'feeder_sub_type' =>
            'required|unique:feeder_sub_type_master,feeder_sub_type,NULL,feeder_sub_type_id,isDelete,0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        FeederSubType::create([
            'feeder_type_id' => $request->feeder_type_id,
            'feeder_sub_type' => $request->feeder_sub_type,
            'iStatus' => $request->iStatus ?? 1,
        ]);

        return redirect()->back()->with('success', 'Feeder Sub Type added successfully!');
    }

    public function edit($id)
    {
        $record = FeederSubType::findOrFail($id);
        return response()->json($record);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'feeder_type_id' => 'required|exists:feeder_type,feeder_type_id,isDelete,0',
            'feeder_sub_type' =>
            'required|unique:feeder_sub_type_master,feeder_sub_type,' .
                $request->edit_id . ',feeder_sub_type_id,isDelete,0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $record = FeederSubType::findOrFail($request->edit_id);
        $record->update([
            'feeder_type_id' => $request->feeder_type_id,
            'feeder_sub_type' => $request->feeder_sub_type,
            'iStatus' => $request->iStatus ?? 1,
        ]);

        return redirect()->back()->with('success', 'Feeder Sub Type updated successfully!');
    }

    public function destroy(Request $request)
    {
        $record = FeederSubType::findOrFail($request->id);
        $record->isDelete = 1;
        $record->save();

        return response()->json(['status' => true]);
    }

    public function bulkDelete(Request $request)
    {
        if ($request->has('ids')) {
            FeederSubType::whereIn('feeder_sub_type_id', $request->ids)->update(['isDelete' => 1]);
        }

        return response()->json(['status' => true]);
    }

    public function status(Request $request)
    {
        $record = FeederSubType::findOrFail($request->id);
        $record->iStatus = $record->iStatus == 1 ? 0 : 1;
        $record->save();

        return response()->json(['status' => true, 'new_status' => $record->iStatus]);
    }
}
