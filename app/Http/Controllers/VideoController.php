<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use Illuminate\Support\Facades\Log;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        try {
            $datas = Video::orderBy('id', 'desc')
                ->where(['isDelete' => 0])
                ->paginate(config('app.per_page', 10));

            return view('admin.video.index', compact('datas'));
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error fetching videos: ' . $e->getMessage());
            // Optionally, handle the exception gracefully with a custom message
            return back()->with('error', 'Error fetching videos. Please try again.');
        }
    }
    
    public function edit(Request $request)
    {
        try {
            $id = $request->id; 
            $data = Video::where('isDelete', 0)->findOrFail($id);

            echo json_encode($data);
        } catch (\Throwable $e) {
            Log::error("videos edit view error: " . $e->getMessage());
            return back()->with('error', 'Failed to load videos edit page.');
        }
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                'url' => 'required',
            ]);
            
            $video =  Video::where('isDelete', 0)->findOrFail($request->id);
            
            $video->update([
                'url' => $request->url,
                'updated_at' => now(),
                'strIP' => $request->ip()
            ]);
            return back()->with('success', 'Video Uploaded Successfully.');
            
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error uploading video: ' . $e->getMessage());
            // Optionally, handle the exception gracefully with a custom message
            return back()->with('error', 'Error uploading video. Please try again.');
        }
        
    }

}
