<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IpProtection;

class IpProtectionController extends Controller
{
    public function index(Request $request)
    {
        $query = IpProtection::where('isDelete', 0);
        if ($request->search) {
            $query->where('ip_protection', 'like', '%' . $request->search . '%');
        }
        $data = $query->orderBy('ip_protection_id', 'desc')->paginate(10);
        return view('admin.ip-protection.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ip_protection' => 'required|unique:ip_protection,ip_protection,NULL,ip_protection_id,isDelete,0',
        ]);

        IpProtection::create([
            'ip_protection' => $request->ip_protection,
            'iStatus' => $request->iStatus ?? 1,
        ]);

        return redirect()->back()->with('success', 'IP Protection added successfully!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'ip_protection' => 'required|unique:ip_protection,ip_protection,' . $request->edit_id . ',ip_protection_id,isDelete,0',
        ]);

        $record = IpProtection::findOrFail($request->edit_id);
        $record->update([
            'ip_protection' => $request->ip_protection,
            'iStatus' => $request->iStatus ?? 1,
        ]);

        return redirect()->back()->with('success', 'IP Protection updated successfully!');
    }

    public function destroy($id)
    {
        $IpProtection = IpProtection::findOrFail($id);
        $IpProtection->delete();

        return redirect()
            ->route('ip-protection.index')
            ->with('success', 'Ip Protection deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        if ($request->has('ids')) {
            IpProtection::whereIn('ip_protection_id', $request->ids)->update(['isDelete' => 1]);
        }
        return response()->json(['status' => true]);
    }

    public function status(Request $request)
    {
        $record = IpProtection::findOrFail($request->id);
        $record->iStatus = $record->iStatus == 1 ? 0 : 1;
        $record->save();
        return response()->json(['status' => true, 'new_status' => $record->iStatus]);
    }
}
