<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NoOfPole;

class NoOfPolesController extends Controller
{
    public function index(Request $request)
    {
        $query = NoOfPole::where('isDelete', 0);
        if ($request->search) {
            $query->where('no_of_poles', 'like', '%' . $request->search . '%');
        }
        $data = $query->orderBy('no_of_poles_id', 'desc')->paginate(10);
        return view('admin.no-of-poles.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_of_poles' => 'required|unique:no_of_poles,no_of_poles,NULL,no_of_poles_id,isDelete,0',
        ]);

        NoOfPole::create([
            'no_of_poles' => $request->no_of_poles,
            'iStatus' => $request->iStatus ?? 1,
        ]);

        return redirect()->back()->with('success', 'No of Poles added successfully!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'no_of_poles' => 'required|unique:no_of_poles,no_of_poles,' . $request->edit_id . ',no_of_poles_id,isDelete,0',
        ]);

        $record = NoOfPole::findOrFail($request->edit_id);
        $record->update([
            'no_of_poles' => $request->no_of_poles,
            'iStatus' => $request->iStatus ?? 1,
        ]);

        return redirect()->back()->with('success', 'Updated successfully!');
    }

    public function destroy(Request $request)
    {
        NoOfPole::where('no_of_poles_id', $request->id)->update(['isDelete' => 1]);
        return response()->json(['status' => true]);
    }

    public function bulkDelete(Request $request)
    {
        if ($request->has('ids')) {
            NoOfPole::whereIn('no_of_poles_id', $request->ids)->update(['isDelete' => 1]);
        }
        return response()->json(['status' => true]);
    }

    public function status(Request $request)
    {
        $record = NoOfPole::findOrFail($request->id);
        $record->iStatus = $record->iStatus == 1 ? 0 : 1;
        $record->save();
        return response()->json(['status' => true, 'new_status' => $record->iStatus]);
    }
}
