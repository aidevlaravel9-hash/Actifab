<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PartsMaster;
use App\Models\PartsCategory;
use Illuminate\Support\Facades\Validator;

class PartsMasterController extends Controller
{
    public function index(Request $request)
    {
        $query = PartsMaster::where('isDelete', 0)->with('category');

        if ($request->has('search') && $request->search != '') {
            $query->where('parts_name', 'like', '%' . $request->search . '%');
        }

        $data = $query->orderBy('parts_id', 'desc')->paginate(10);
        $categories = PartsCategory::where('isDelete', 0)->orderBy('parts_category_name')->get();

        return view('admin.parts-master.index', compact('data', 'categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parts_category_id' => 'required|exists:parts_category,parts_id,isDelete,0',
            'parts_name' =>
            'required|unique:parts_master,parts_name,NULL,parts_id,isDelete,0',
            'part_amount' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // 🔹 Image upload (optional)
        $imageName = null;

        if ($request->hasFile('image')) {

            $folder = 'uploads/parts';

            // create folder if not exists
            if (!file_exists(public_path($folder))) {
                mkdir(public_path($folder), 0755, true);
            }

            $imageName = time() . '_' . $request->image->getClientOriginalName();

            $request->image->move(
                public_path($folder),
                $imageName
            );
        }

        PartsMaster::create([
            'parts_category_id' => $request->parts_category_id,
            'parts_name'        => $request->parts_name,
            'part_amount'       => $request->part_amount,
            'image'             => $imageName, // ✅ nullable
            'iStatus'           => $request->iStatus ?? 1,
        ]);

        return redirect()->back()->with('success', 'Part added successfully!');
    }

    public function edit($id)
    {
        $record = PartsMaster::findOrFail($id);
        return response()->json($record);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parts_category_id' => 'required|exists:parts_category,parts_id,isDelete,0',
            'parts_name' =>
            'required|unique:parts_master,parts_name,' .
                $request->edit_id . ',parts_id,isDelete,0',
            'part_amount' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $record = PartsMaster::findOrFail($request->edit_id);

        // 🔹 Handle image replace (optional)
        if ($request->hasFile('image')) {

            $folder = 'uploads/parts';

            // delete old image
            if ($record->image && file_exists(public_path($folder . '/' . $record->image))) {
                unlink(public_path($folder . '/' . $record->image));
            }

            // create folder if not exists
            if (!file_exists(public_path($folder))) {
                mkdir(public_path($folder), 0755, true);
            }

            $imageName = time() . '_' . $request->image->getClientOriginalName();

            $request->image->move(
                public_path($folder),
                $imageName
            );

            $record->image = $imageName;
        }

        // 🔹 Update other fields
        $record->parts_category_id = $request->parts_category_id;
        $record->parts_name        = $request->parts_name;
        $record->part_amount       = $request->part_amount;
        $record->iStatus           = $request->iStatus ?? 1;
        $record->save();

        return redirect()->back()->with('success', 'Part updated successfully!');
    }


    public function destroy(Request $request)
    {
        $record = PartsMaster::findOrFail($request->id);
        $record->isDelete = 1;
        $record->save();

        return response()->json(['status' => true]);
    }

    public function bulkDelete(Request $request)
    {
        if ($request->has('ids')) {
            PartsMaster::whereIn('parts_id', $request->ids)->update(['isDelete' => 1]);
        }

        return response()->json(['status' => true]);
    }

    public function status(Request $request)
    {
        $record = PartsMaster::findOrFail($request->id);
        $record->iStatus = $record->iStatus == 1 ? 0 : 1;
        $record->save();

        return response()->json(['status' => true, 'new_status' => $record->iStatus]);
    }
}
