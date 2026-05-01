<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemCurrentRating;

class SystemRatingController extends Controller
{
    public function index(Request $request)
    {
        $query = SystemCurrentRating::where('isDelete', 0);
        if ($request->search) {
            $query->where('rating', 'like', '%' . $request->search . '%');
        }
        $data = $query->orderBy('system_current_rating_id', 'desc')->paginate(10);
        return view('admin.system-rating.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|unique:system_current_rating,rating,NULL,system_current_rating_id,isDelete,0',
        ]);

        SystemCurrentRating::create([
            'rating' => $request->rating,
            'iStatus' => $request->iStatus ?? 1,
        ]);

        return redirect()->back()->with('success', 'Rating added successfully!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'rating' => 'required|unique:system_current_rating,rating,' . $request->edit_id . ',system_current_rating_id,isDelete,0',
        ]);

        $record = SystemCurrentRating::findOrFail($request->edit_id);
        $record->update([
            'rating' => $request->rating,
            'iStatus' => $request->iStatus ?? 1,
        ]);

        return redirect()->back()->with('success', 'Rating updated successfully!');
    }

    public function destroy(Request $request)
    {
        $record = SystemCurrentRating::where('system_current_rating_id', $request->id)->firstOrFail();
        $record->update(['isDelete' => 1]);
        return response()->json(['status' => true]);
    }


    public function bulkDelete(Request $request)
    {
        if ($request->has('ids')) {
            SystemCurrentRating::whereIn('system_current_rating_id', $request->ids)->update(['isDelete' => 1]);
        }
        return response()->json(['status' => true]);
    }

    public function status(Request $request)
    {
        $record = SystemCurrentRating::findOrFail($request->id);
        $record->iStatus = $record->iStatus == 1 ? 0 : 1;
        $record->save();
        return response()->json(['status' => true, 'new_status' => $record->iStatus]);
    }
}
