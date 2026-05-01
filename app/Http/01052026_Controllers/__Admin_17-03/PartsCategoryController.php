<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PartsCategory;
use Illuminate\Support\Facades\Validator;

class PartsCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = PartsCategory::where('isDelete', 0);

        if ($request->has('search') && $request->search != '') {
            $query->where('parts_category_name', 'like', '%' . $request->search . '%');
        }

        $data = $query->orderBy('parts_id', 'desc')->paginate(10);
        return view('admin.parts-category.index', compact('data'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parts_category_name' =>
            'required|unique:parts_category,parts_category_name,NULL,parts_id,isDelete,0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        PartsCategory::create([
            'parts_category_name' => $request->parts_category_name,
            'iStatus' => $request->iStatus ?? 1,
        ]);

        return redirect()->back()->with('success', 'Parts Category added successfully!');
    }

    public function edit($id)
    {
        $record = PartsCategory::findOrFail($id);
        return response()->json($record);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parts_category_name' =>
            'required|unique:parts_category,parts_category_name,' .
                $request->edit_id . ',parts_id,isDelete,0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $record = PartsCategory::findOrFail($request->edit_id);
        $record->update([
            'parts_category_name' => $request->parts_category_name,
            'iStatus' => $request->iStatus ?? 1,
        ]);

        return redirect()->back()->with('success', 'Parts Category updated successfully!');
    }

    public function destroy($id)
    {
        $PartsCategory = PartsCategory::findOrFail($id);
        $PartsCategory->delete();

        return redirect()
            ->route('parts-category.index')
            ->with('success', 'Parts Category deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        if ($request->has('ids')) {
            PartsCategory::whereIn('parts_id', $request->ids)->update(['isDelete' => 1]);
        }

        return response()->json(['status' => true]);
    }

    public function status(Request $request)
    {
        $record = PartsCategory::findOrFail($request->id);
        $record->iStatus = $record->iStatus == 1 ? 0 : 1;
        $record->save();

        return response()->json(['status' => true, 'new_status' => $record->iStatus]);
    }
}
