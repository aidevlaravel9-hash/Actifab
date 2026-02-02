<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::where('isDelete', 0);

        if ($request->has('search') && $request->search != '') {
            $query->where('project_name', 'like', '%' . $request->search . '%');
        }

        $data = $query->orderBy('project_id', 'desc')->paginate(10);
        return view('admin.project.index', compact('data'));
    }

    public function create()
    {
        return view('admin.project.add-edit');
    }

    public function edit($id)
    {
        $data = Project::findOrFail($id);
        return view('admin.project.add-edit', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_name' => 'required',
            'creater_email' => 'required|email',
            'creater_contact' => 'required',
            'created_by' => 'required|numeric',
            'customer_name' => 'required',
            'customer_email' => 'required|email',
            'customer_address' => 'required',
            'contact_person' => 'required',
            'contact_person_mobile' => 'required',
            'contact_person_email' => 'required|email',
            'inquiry_date' => 'required|date',
        ]);

        $filename = null;
        if ($request->hasFile('attach_files')) {
            $file = $request->file('attach_files');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(FolderPath('uploads/projects'), $filename);
        }

        Project::create([
            'project_name' => $request->project_name,
            'creater_email' => $request->creater_email,
            'creater_contact' => $request->creater_contact,
            'created_by' => $request->created_by,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_address' => $request->customer_address,
            'contact_person' => $request->contact_person,
            'contact_person_mobile' => $request->contact_person_mobile,
            'contact_person_email' => $request->contact_person_email,
            'inquiry_date' => $request->inquiry_date,
            'attach_files' => $filename,
            'iStatus' => $request->iStatus ?? 1,
        ]);

        return redirect()->route('project.index')->with('success', 'Project added successfully!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'project_name' => 'required',
            'creater_email' => 'required|email',
            'creater_contact' => 'required',
            'created_by' => 'required|numeric',
            'customer_name' => 'required',
            'customer_email' => 'required|email',
            'customer_address' => 'required',
            'contact_person' => 'required',
            'contact_person_mobile' => 'required',
            'contact_person_email' => 'required|email',
            'inquiry_date' => 'required|date',
        ]);

        $record = Project::findOrFail($request->edit_id);
        $filename = $record->attach_files;

        if ($request->hasFile('attach_files')) {
            if (!empty($record->attach_files) && file_exists(FolderPath('uploads/projects') . '/' . $record->attach_files)) {
                unlink(FolderPath('uploads/projects') . '/' . $record->attach_files);
            }

            $file = $request->file('attach_files');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(FolderPath('uploads/projects'), $filename);
        }

        $record->update([
            'project_name' => $request->project_name,
            'creater_email' => $request->creater_email,
            'creater_contact' => $request->creater_contact,
            'created_by' => $request->created_by,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_address' => $request->customer_address,
            'contact_person' => $request->contact_person,
            'contact_person_mobile' => $request->contact_person_mobile,
            'contact_person_email' => $request->contact_person_email,
            'inquiry_date' => $request->inquiry_date,
            'attach_files' => $filename,
            'iStatus' => $request->iStatus ?? 1,
        ]);

        return redirect()->route('project.index')->with('success', 'Project updated successfully!');
    }

    public function destroy(Request $request)
    {
        $record = Project::findOrFail($request->id);

        if (!empty($record->attach_files) && file_exists(FolderPath('uploads/projects') . '/' . $record->attach_files)) {
            unlink(FolderPath('uploads/projects') . '/' . $record->attach_files);
        }

        $record->isDelete = 1;
        $record->save();

        return response()->json(['status' => true]);
    }

    public function bulkDelete(Request $request)
    {
        if ($request->has('ids')) {
            $records = Project::whereIn('project_id', $request->ids)->get();

            foreach ($records as $rec) {
                if (!empty($rec->attach_files) && file_exists(FolderPath('uploads/projects') . '/' . $rec->attach_files)) {
                    unlink(FolderPath('uploads/projects') . '/' . $rec->attach_files);
                }
            }

            Project::whereIn('project_id', $request->ids)->update(['isDelete' => 1]);
        }

        return response()->json(['status' => true]);
    }

    public function status(Request $request)
    {
        $record = Project::findOrFail($request->id);
        $record->iStatus = $record->iStatus == 1 ? 0 : 1;
        $record->save();

        return response()->json(['status' => true, 'new_status' => $record->iStatus]);
    }
}
