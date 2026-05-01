<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeederCategory;
use Illuminate\Http\Request;
use App\Models\FeederType;
use Illuminate\Support\Facades\Validator;

class FeederTypeController extends Controller
{
    public function index(Request $request)
    {
        $FeederCategory = FeederCategory::where('isDelete', 0)->get();
        $query = FeederType::with('FeederCategory')->where('isDelete', 0);

        if ($request->has('search') && $request->search != '') {
            $query->where('feeder_type', 'like', '%' . $request->search . '%');
        }

        $data = $query->orderBy('feeder_type_id', 'desc')->paginate(10);
        return view('admin.feeder-type.index', compact('data', 'FeederCategory'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'feeder_type' =>
            'required|unique:feeder_type,feeder_type,NULL,feeder_type_id,isDelete,0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        FeederType::create([
            'feeder_category_id' => $request->feeder_category_id,
            'feeder_type' => $request->feeder_type,
            'iStatus' => $request->iStatus ?? 1,
        ]);

        return redirect()->back()->with('success', 'Feeder Type added successfully!');
    }

    public function edit($id)
    {
        $record = FeederType::findOrFail($id);
        return response()->json($record);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'feeder_type' =>
            'required|unique:feeder_type,feeder_type,' .
                $request->edit_id . ',feeder_type_id,isDelete,0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $record = FeederType::findOrFail($request->edit_id);
        $record->update([
            'feeder_category_id' => $request->feeder_category_id,
            'feeder_type' => $request->feeder_type,
            'iStatus' => $request->iStatus ?? 1,
        ]);

        return redirect()->back()->with('success', 'Feeder Type updated successfully!');
    }

    public function destroy($id)
    {
        $feederType = FeederType::findOrFail($id);
        $feederType->delete();

        return redirect()
            ->route('feeder-type.index')
            ->with('success', 'Feeder Type deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        if ($request->has('ids')) {
            FeederType::whereIn('feeder_type_id', $request->ids)->update(['isDelete' => 1]);
        }

        return response()->json(['status' => true]);
    }

    public function status(Request $request)
    {
        $record = FeederType::findOrFail($request->id);
        $record->iStatus = $record->iStatus == 1 ? 0 : 1;
        $record->save();

        return response()->json(['status' => true, 'new_status' => $record->iStatus]);
    }
}
