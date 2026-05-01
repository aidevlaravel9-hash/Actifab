<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LockSystem;

class LockSystemController extends Controller
{
    public function index(Request $request)
    {
        $query = LockSystem::where('isDelete', 0);
        if ($request->search) {
            $query->where('lock_system', 'like', '%' . $request->search . '%');
        }
        $data = $query->orderBy('lock_system_id', 'desc')->get();
        return view('admin.lock-system.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lock_system' => 'required|unique:lock_system,lock_system,NULL,lock_system_id,isDelete,0',
        ]);

        LockSystem::create([
            'lock_system' => $request->lock_system,
            'iStatus' => $request->iStatus ?? 1,
        ]);

        return redirect()->back()->with('success', 'Lock System added successfully!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'lock_system' => 'required|unique:lock_system,lock_system,' . $request->edit_id . ',lock_system_id,isDelete,0',
        ]);

        $record = LockSystem::findOrFail($request->edit_id);
        $record->update([
            'lock_system' => $request->lock_system,
            'iStatus' => $request->iStatus ?? 1,
        ]);

        return redirect()->back()->with('success', 'Lock System updated successfully!');
    }


    public function destroy($id)
    {
        $LockSystem = LockSystem::findOrFail($id);
        $LockSystem->delete();

        return redirect()
            ->route('lock-system.index')
            ->with('success', 'Lock System deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        if ($request->has('ids')) {
            LockSystem::whereIn('lock_system_id', $request->ids)->update(['isDelete' => 1]);
        }
        return response()->json(['status' => true]);
    }

    public function status(Request $request)
    {
        $record = LockSystem::findOrFail($request->id);
        $record->iStatus = $record->iStatus == 1 ? 0 : 1;
        $record->save();
        return response()->json(['status' => true, 'new_status' => $record->iStatus]);
    }
}
