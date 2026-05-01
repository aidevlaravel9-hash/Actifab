<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Log;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $Testimonial = Testimonial::orderBy('id', 'desc')->paginate(10);

        return view('admin.testimonial.index', compact('Testimonial'));
    }

    public function create(Request $request)
    {
        try {
            return view('admin.testimonial.add');
        } catch (\Throwable $e) {
            Log::error("Category create error: " . $e->getMessage());
            return back()->with('error', 'Failed to load create form.');
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required',
                'designation' => 'required|string|max:255',
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            ]);

            $img = "";
            if ($request->hasFile('photo')) {
                $image = $request->file('photo');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(FolderPath('/uploads/testimonial'), $imageName);
                $img = $imageName;
            }

            $Data = array(
                'name' => $request->name,
                'description' => $request->description,
                'designation' => $request->designation,
                'photo' => $img,
                'created_at' => now(),
                'strIP' => $request->ip()
            );
            Testimonial::create($Data);

            return redirect()->route('testimonial.index')->with('success', 'Testimonial Created Successfully.');
        } catch (\Throwable $e) {
            Log::error("Testimonial store error: " . $e->getMessage());
            return back()->with('error', 'Failed to create category.')->withInput();
        }
    }

    public function editview(Request $request, $id)
    {
        try {
            $data = Testimonial::where(['iStatus' => 1, 'isDelete' => 0, 'id' => $id])->first();

            return view('admin.testimonial.edit', compact('data'));
        } catch (\Throwable $e) {
            Log::error("Category edit view error: " . $e->getMessage());
            return back()->with('error', 'Failed to load category edit page.');
        }
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required',
                'designation' => 'required|string|max:255',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            ]);

            // Find category safely
            $category = Testimonial::where('isDelete', 0)->where('id', $request->id)->firstOrFail();

            $img = $category->photo;

            if ($request->hasFile('photo')) {
                $image = $request->file('photo');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                $destinationPath = FolderPath('/uploads/testimonial');

                // Ensure folder exists
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $image->move($destinationPath, $imageName);
                $img = $imageName;

                // Delete old image if exists
                $oldImagePath = $destinationPath . '/' . $category->photo;
                if ($category->photo && file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $category->update([
                'name' => $request->name,
                'description' => $request->description,
                'designation' => $request->designation,
                'photo' => $img,
                'updated_at' => now(),
                'strIP' => $request->ip()
            ]);

            return redirect()->route('testimonial.index')->with('success', 'Testimonial Updated Successfully.');
        } catch (\Throwable $e) {
            Log::error("Testimonial update error: " . $e->getMessage());
            return back()->with('error', 'Failed to update testimonial.')->withInput();
        }
    }


    public function delete(Request $request)
    {
        try {
            $testimonial = Testimonial::where('isDelete', 0)->findOrFail($request->id);

            $imagePath = FolderPath('/uploads/testimonial') . '/' . $testimonial->photo;

            if ($testimonial->photo && file_exists($imagePath)) {
                unlink($imagePath);
            }

            $testimonial->delete();

            return back()->with('success', 'Testimonial Deleted Successfully!');
        } catch (\Throwable $e) {
            Log::error("Testimonial delete error: " . $e->getMessage());
            return back()->with('error', 'Failed to delete testimonial.');
        }
    }
}
