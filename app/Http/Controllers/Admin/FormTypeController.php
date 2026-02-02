<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FormType;

class FormTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = FormType::where('isDelete', 0);
        if ($request->search) {
            $query->where('form_type', 'like', '%' . $request->search . '%');
        }
        $data = $query->orderBy('form_type_id', 'desc')->paginate(10);
        return view('admin.form-type.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'form_type' => 'required|unique:form_type,form_type,NULL,form_type_id,isDelete,0',
        ]);

        FormType::create([
            'form_type' => $request->form_type,
            'iStatus' => $request->iStatus ?? 1,
        ]);

        return redirect()->back()->with('success', 'Form Type added successfully!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'form_type' => 'required|unique:form_type,form_type,' . $request->edit_id . ',form_type_id,isDelete,0',
        ]);

        $record = FormType::findOrFail($request->edit_id);
        $record->update([
            'form_type' => $request->form_type,
            'iStatus' => $request->iStatus ?? 1,
        ]);

        return redirect()->back()->with('success', 'Form Type updated successfully!');
    }

    public function destroy(Request $request)
    {
        FormType::where('form_type_id', $request->id)->update(['isDelete' => 1]);
        return response()->json(['status' => true]);
    }

    public function bulkDelete(Request $request)
    {
        if ($request->has('ids')) {
            FormType::whereIn('form_type_id', $request->ids)->update(['isDelete' => 1]);
        }
        return response()->json(['status' => true]);
    }

    public function status(Request $request)
    {
        $record = FormType::findOrFail($request->id);
        $record->iStatus = $record->iStatus == 1 ? 0 : 1;
        $record->save();
        return response()->json(['status' => true, 'new_status' => $record->iStatus]);
    }
}
