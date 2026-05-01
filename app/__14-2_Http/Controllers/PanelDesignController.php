<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\ProjectPanelBoard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Project;
use App\Models\Section;
use App\Models\Feeder;


class PanelDesignController extends Controller
{
    public function show($id)
    {
        $panel = ProjectPanelBoard::with('sections.feeders')->findOrFail($id);
        // Used Width
        $usedWidth = $panel->sections->sum('width');
        // Remaining Width
        $remainingWidth = $panel->frame_width - $usedWidth;
        return view('panel.manage', compact('panel', 'remainingWidth'));
    }

    public function storeSection(Request $request)
    {
        $request->validate([
            'panel_id' => 'required|exists:project_panel_boards,id',
            'section_name' => 'required',
            'width' => 'required|numeric|min:1'
        ]);

        $panel = ProjectPanelBoard::find($request->panel_id);

        // Already used width
        $usedWidth = (float) Section::where('panel_id', $panel->id)->sum('width');
        $newWidth = (float) $request->width;
        $totalWidth = (float) $panel->frame_width;

        if (($usedWidth + $newWidth) > $totalWidth) {

            $remaining = $totalWidth - $usedWidth;

            return redirect()->back()
                ->withInput()
                ->with([
                    'success' => 'No Width Remain.'
                ]);
        }

        // If valid then store
        Section::create([
            'panel_id' => $panel->id,
            'section_name' => $request->section_name,
            'width' => $request->width
        ]);

        return redirect()->back()->with('success', 'Section Created');
    }

    public function storeFeeder(Request $request)
    {

        $request->validate([
            'panel_id' => 'required|exists:project_panel_boards,id',
            'section_id' => 'required|exists:section,id',
            'feeder_name' => 'required',
            'height' => 'required|numeric|min:1',

        ]);

        $panel = ProjectPanelBoard::find($request->panel_id);
        $section = Section::find($request->section_id);

        $usedHeight = (float) Feeder::where('section_id', $section->id)->sum('height');
        $newHeight = (float) $request->height;
        $totalHeight = (float) $panel->frame_height;

        $remainingHeight = (float) $totalHeight - $usedHeight;
        if ($newHeight > $remainingHeight) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'height' => 'No Height Remain.'
                ]);
        }
        Feeder::create([
            'panel_id' => $panel->id,
            'section_id' => $section->id,
            'feeder_name' => $request->feeder_name,
            'height' => $request->height
        ]);

        return redirect()->back()->with('success', 'Feeder Created');
    }
}
