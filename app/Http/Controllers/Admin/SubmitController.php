<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Project;
use App\Models\ProjectPanelBoard;
use Illuminate\Support\Facades\Auth;



class SubmitController extends Controller
{

    public function completeProject($id)
    {
        $panel = ProjectPanelBoard::findOrFail($id);

        $panel->submit_project = 2;
        $panel->save();

        return response()->json([
            'success' => true,
            'message' => 'Project Complete Successfully'
        ]);
    }

    public function adminsectionFeederList($panelId)
    {

        $panel = ProjectPanelBoard::with([
            'sections.feeders.FeederCategory',
            'sections.feeders.FeederType',
            'sections.feeders.FeederSubType'
        ])->findOrFail($panelId);
        return view('admin.SubmitProject.SectionView', compact('panel'));
    }

    public function inDevelopment()
    {
        $panels = ProjectPanelBoard::with('project')
            ->where('submit_project', 1)
            ->latest()
            ->get();

        return view('admin.SubmitProject.in_development', compact('panels'));
    }

    public function completedProjects()
    {
        $panels = ProjectPanelBoard::with('project')
            ->where('submit_project', 2)
            ->latest()
            ->get();

        return view('admin.SubmitProject.completed_project', compact('panels'));
    }
}
