<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FeederCategory;
use Illuminate\Support\Facades\Validator;

class FeederCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = FeederCategory::where('isDelete', 0);

        if ($request->has('search') && $request->search != '') {
            $query->where('feeder_category_name', 'like', '%' . $request->search . '%');
        }

        $data = $query->orderBy('feeder_category_id', 'desc')->paginate(10);
        return view('admin.feeder-category.index', compact('data'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'feeder_category_name' =>
            'required|unique:feeder_category_master,feeder_category_name,NULL,feeder_category_id,isDelete,0',
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        FeederCategory::create([
            'feeder_category_name' => $request->feeder_category_name,
            'iStatus' => $request->iStatus ?? 1,
        ]);

        return redirect()->back()->with('success', 'Feeder Category added successfully!');
    }

    public function edit($id)
    {
        $record = FeederCategory::findOrFail($id);
        return response()->json($record);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'feeder_category_name' =>
            'required|unique:feeder_category_master,feeder_category_name,' .
                $request->edit_id . ',feeder_category_id,isDelete,0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $record = FeederCategory::findOrFail($request->edit_id);
        $record->update([
            'feeder_category_name' => $request->feeder_category_name,
            'iStatus' => $request->iStatus ?? 1,
        ]);

        return redirect()->back()->with('success', 'Feeder Category updated successfully!');
    }

    public function destroy($id)
    {
        $FeederCategory = FeederCategory::findOrFail($id);
        $FeederCategory->delete();

        return redirect()
            ->route('feeder-category.index')
            ->with('success', 'Feeder Category deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        if ($request->has('ids')) {
            FeederCategory::whereIn('feeder_category_id', $request->ids)->update(['isDelete' => 1]);
        }
        return response()->json(['status' => true]);
    }

    public function status(Request $request)
    {
        $record = FeederCategory::findOrFail($request->id);
        $record->iStatus = $record->iStatus == 1 ? 0 : 1;
        $record->save();

        return response()->json(['status' => true, 'new_status' => $record->iStatus]);
    }
}
