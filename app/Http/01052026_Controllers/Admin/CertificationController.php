<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Certification;


class CertificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Certification::where('isDelete', 0);
        if ($request->search) {
            $query->where('certification', 'like', "%{$request->search}%");
        }
        $data = $query->orderByDesc('certification_id')->get();
        return view('admin.certification.index', compact('data'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'certification' => 'required|unique:certification,certification,NULL,certification_id,isDelete,0',
        ]);


        Certification::create([
            'certification' => $request->certification,
            'iStatus' => $request->iStatus ?? 1,
        ]);


        return redirect()->back()->with('success', 'Certification added successfully!');
    }


    public function update(Request $request)
    {
        $request->validate([
            'certification' => 'required|unique:certification,certification,' . $request->edit_id . ',certification_id,isDelete,0',
        ]);


        $record = Certification::findOrFail($request->edit_id);
        $record->update([
            'certification' => $request->certification,
            'iStatus' => $request->iStatus ?? 1,
        ]);


        return redirect()->back()->with('success', 'Certification updated successfully!');
    }

    public function destroy($id)
    {
        $Certification = Certification::findOrFail($id);
        $Certification->delete();

        return redirect()
            ->route('certification.index')
            ->with('success', 'Certification deleted successfully.');
    }


    public function bulkDelete(Request $request)
    {
        if ($request->ids) {
            Certification::whereIn('certification_id', $request->ids)->update(['isDelete' => 1]);
        }
        return response()->json(['status' => true]);
    }


    public function status(Request $request)
    {
        $record = Certification::findOrFail($request->id);
        $record->iStatus = $record->iStatus ? 0 : 1;
        $record->save();


        return response()->json(['status' => true, 'new_status' => $record->iStatus]);
    }
}
