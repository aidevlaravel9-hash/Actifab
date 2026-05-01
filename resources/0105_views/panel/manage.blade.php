@extends('layouts.user')

{{-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> --}}
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>

@section('content')

    <style>
      {{-- ✅ REPLACE WITH THIS --}}
        .cover-dot {
            position: absolute;
            font-size: 8px;
            line-height: 1;
            z-index: 10;
        }
        
        .dot-tl {
            top: 3px;
            left: 3px;
        }
        
        .dot-tr {
            top: 3px;
            right: 3px;
        }
        
        .dot-bl {
            bottom: 3px;
            left: 3px;
        }
        
        .dot-br {
            bottom: 3px;
            right: 3px;
        }

        .form-inline-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-inline-group label {
            min-width: 140px;
            margin-bottom: 0;
        }
    </style>
    <style>
        .form-inline-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-inline-group label {
            min-width: 140px;
            margin-bottom: 0;
        }

        .is-invalid {
            border: 1px solid red !important;
            background: #fff5f5 !important;
        }
    </style>

    @php
        if ($editSection) {
            // Edit mode â†’ sirf wahi section
            $sectionsToShow = collect([$editSection]);
        } else {
            // Create mode â†’ ek temporary blank section
            $tempSection = new \stdClass();
            $tempSection->section_name = old('section_name', 'New Section');
            $tempSection->width = old('width', 0);
            $tempSection->feeders = collect(); // no feeders

            $sectionsToShow = collect([$tempSection]);
        }
    @endphp
    {{-- @php
        $usedWidth = $panel->sections->sum('width');
        $remainingWidth = $panel->frame_width - $usedWidth;
    @endphp --}}

    @endphp
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                @php
                    $scale = 0.25; // ðŸ‘ˆ Preview small karne ke liye
                @endphp

                {{-- ================= ALERTS ================= --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success" id="success-alert">
                        {{ session('success') }}
                    </div>
                @endif

                @php
                    $lastSection = $panel->sections->last();
                    $canCreateSection = true;

                    if ($lastSection) {
                        $usedHeightLast = $lastSection->feeders->sum('height');

                        if ($usedHeightLast < $panel->frame_height) {
                            $canCreateSection = false;
                        }
                    }
                @endphp
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">


                        </div>
                    </div>
                    <div class="col-12 d-flex gap-2 mb-3">
                        <h4 class="mb-sm-0">Add Section</h4>

                        <a href="{{ route('panel.show', $panel->id) }}" class="btn btn-sm btn-primary">
                            Add Section
                        </a>

                        <div class="page-title-right">
                            <a href="{{ route('panel.section.list', $panel->id) }}"
                                class="btn btn-sm btn-primary shadow-sm">
                                {{-- <i class="fas fa-arrow-left fa-sm text-white-50"></i>  --}}
                                Preview All Section
                            </a>
                        </div>
                    </div>
                </div>

                {{-- ================= HEADER ================= --}}
                <div class="card shadow-sm mb-2">
                    <div class="card-body d-flex justify-content-  pb-1 pt-2">

                        <div class="me-5">
                            <h6>Project Name:</h6>
                            <h6> <span class="text-black">{{ $panel->project->project_name ?? '' }}</h6>
                            </span>
                        </div>

                        <div class="me-5">
                            <h6>Panel Board Name:</h6>
                            <h6><span class="text-black">{{ $panel->panel_board_name }}</h6> </span>
                        </div>

                        <div class="me-5">
                            <h6>Panel Board Job No: </h6>
                            <h6><span class="text-black">{{ $panel->panel_board_job_no ?? '' }}</h6>
                            </span>
                        </div>

                        <div class="me-5">
                            <h6>Size: </h6>
                            <h6><span class="text-black">{{ $panel->frame_width }} x {{ $panel->frame_height }}</h6>
                            </span>
                        </div>
                    </div>


                    {{-- ================= ADD SECTION FORM ================= --}}
                    {{-- @if (!$lastSection || $lastSection->feeders->sum('height') >= $panel->frame_height) --}}
                    <div class="card shadow-sm mb-4 section-card-live">
                        <div class="card-header bg-primary d-flex justify-content- text-white pb-1 pt-2">
                            Add Section
                            <span class="badge badge-light fs-6">
                                Remaining Width: {{ $remainingWidth }}
                            </span>
                        </div>

                        <div class="card-body pb-1 pt-2">
                            <form method="POST"
                                action="{{ $editSection ? route('section.update', $editSection->id) : route('section.store') }}">
                                @csrf
                                <input type="hidden" name="panel_id" value="{{ $panel->id }}">

                                <div class="row">
                                    <div class="col-md-2">
                                        <label>Section Name</label>
                                        <input type="text" name="section_name"
                                            value="{{ $editSection->section_name ?? '' }}" class="form-control" required>
                                    </div>

                                    <div class="col-md-3">
                                        <label>Section Type</label>
                                        <select name="section_type" class="form-control" id="section_type_select">
                                            <option value="">Please Select</option>
                                            @foreach ($sectionmaster as $section)
                                                <option value="{{ $section->section_id }}"
                                                    {{ isset($editSection) && $section->section_id == $editSection->section_type_id ? 'selected' : '' }}>
                                                    {{ $section->section_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label>Width</label>
                                        <input type="number" name="width" value="{{ $editSection->width ?? '' }}"
                                            max="{{ $editSection ? $remainingWidth + $editSection->width : $remainingWidth }}"
                                            class="form-control" required>
                                    </div>

                                    <div class="col-md-2">
                                        <label>Lock Position</label>
                                        <select name="lock_position" class="form-control">
                                            <option value="">Please Select</option>
                                            <option value="Left"
                                                {{ isset($editSection) && $editSection->lock_position == 'Left' ? 'selected' : '' }}>
                                                Left</option>
                                            <option value="Right"
                                                {{ isset($editSection) && $editSection->lock_position == 'Right' ? 'selected' : '' }}>
                                                Right</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3 d-flex align-items-end">

                                        @if ($editSection || ($remainingWidth > 0 && $canCreateSection))
                                            <button class="btn btn-success btn-block">
                                                {{ $editSection ? 'Update Section' : 'Create Section' }}
                                            </button>
                                        @endif
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                    {{-- @endif --}}

                    {{-- ================= ALL SECTIONS DISPLAY ================= --}}
                    @if ($editSection)
                        @foreach ($sectionsToShow as $section)
                            @php
                                $usedHeight = $section->feeders->sum('height');
                                $remainingHeight = $panel->frame_height - $usedHeight;
                            @endphp

                            <div class="card shadow-sm mb-4 section-card-live">

                                <div class="card-header bg-info text-white d-flex gap-4 pb-1 pt-2">
                                    <span>Section: {{ $section->section_name }}</span>

                                    <span>
                                        <strong> Width:</strong> {{ $section->width }}
                                    </span>
                                </div>

                                <div class="card-body ">

                                    {{-- ================= ADD FEEDER FORM ================= --}}
                                    @if ($section->feeders->count() < 9 && $usedHeight < $panel->frame_height)
                                        <form method="POST" action="{{ route('feeder.store') }}" class="mt-3">

                                            <div class="text-danger fw-bold live-height-message mt-2" style="display:none;">
                                                Your feeder height is already used. You cannot add more height.
                                            </div>

                                            <div class="text-danger fw-bold live-height-exceed-message mt-2"
                                                style="display:none;">
                                                Entered feeder height exceeds available section height.
                                            </div>

                                            <span>
                                                <strong>Remaining Height:</strong>
                                                <span class="live-remaining-height"
                                                    data-frame-height="{{ $panel->frame_height }}"
                                                    data-used-height="{{ $usedHeight }}">
                                                    {{ $remainingHeight }}
                                                </span>
                                            </span>

                                            @csrf

                                            <input type="hidden" name="panel_id" value="{{ $panel->id }}">
                                            <input type="hidden" name="section_id" value="{{ $section->id }}">
                                            <input type="hidden" id="current_section_type_id"
                                                value="{{ $section->section_type_id }}">
                                            {{-- Header only one time --}}
                                            <div class="row mb-2 fw-bold text-center"
                                                style="border-bottom:2px solid #ccc; padding-bottom:8px;">
                                                <div class="col-md-2" style="width:7%">Feeder Name</div>
                                                <div class="col-md-2" style="width:9%">Feeder Height</div>
                                                <div class="col-md-2 " style="width:15%">Feeder Category</div>
                                                <div class="col-md-2 " style="width:15%">Feeder Type</div>
                                                <div class="col-md-2" style="width:15%">Feeder Sub Type</div>
                                                <div class="col-md-2" style="width:15%">Door</div>
                                                <div class="col-md-2" style="width:24%">Customer Remarks</div>
                                            </div>

                                            @for ($i = 0; $i < 9; $i++)
                                                @php
                                                    $existingFeeder = $section->feeders[$i] ?? null;

                                                    $sectionNumber =
                                                        $panel->sections->search(function ($sec) use ($section) {
                                                            return $sec->id == $section->id;
                                                        }) + 1;

                                                    $feederName = $sectionNumber . 'F' . ($i + 1);
                                                @endphp

                                                <div class="row mb-2"
                                                    style="border-bottom:1px solid #eee; padding-bottom:10px;">

                                                    {{-- Feeder Name --}}
                                                    <div class="col-md-2 d-flex justify-content-center" style="width:7%">
                                                        <input type="text" class="form-control form-control-sm"
                                                            style="width:50px;"
                                                            value="{{ $existingFeeder->feeder_name ?? $feederName }}"
                                                            readonly>
                                                    </div>

                                                    {{-- Height --}}
                                                    <div class="col-md-2" style="width:9%">
                                                        <input type="number" name="feeders[{{ $i }}][height]"
                                                            class="form-control feeder-height-input" style="width:75px;"
                                                            value="{{ $existingFeeder->height ?? '' }}"
                                                            {{ $existingFeeder ? 'disabled' : '' }}>
                                                    </div>

                                                    {{-- Category --}}
                                                    <div class="col-md-2" style="width:15%">

                                                        @if ($existingFeeder)
                                                            <input type="text" class="form-control feeder_category"
                                                                style="width:160px;" id="feeder_category"
                                                                value="{{ $existingFeeder->FeederCategory->feeder_category_name ?? '' }}"
                                                                readonly>
                                                        @else
                                                            <select name="feeders[{{ $i }}][category]"
                                                                style="width:160px;" id="feeder_category"
                                                                class="form-control
                                                                feeder_category">
                                                                <option value="">Select</option>
                                                                @foreach ($feedercategory as $cat)
                                                                    <option value="{{ $cat->feeder_category_id }}">
                                                                        {{ $cat->feeder_category_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @endif
                                                    </div>

                                                    {{-- Type --}}
                                                    <div class="col-md-2" style="width:15%">

                                                        @if ($existingFeeder)
                                                            <input type="text" id="feeder_type" style="width:160px;"
                                                                class="form-control feeder_type"
                                                                value="{{ $existingFeeder->FeederType->feeder_type ?? '' }}"
                                                                readonly>
                                                        @else
                                                            <select name="feeders[{{ $i }}][type]"
                                                                style="width:160px;" id="feeder_type"
                                                                class="form-control feeder_type">
                                                                <option value="">Select</option>
                                                                @foreach ($feedertype as $type)
                                                                    <option value="{{ $type->feeder_type_id }}">
                                                                        {{ $type->feeder_type }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @endif
                                                    </div>

                                                    {{-- Subtype --}}
                                                    <div class="col-md-2" style="width:15%">

                                                        @if ($existingFeeder)
                                                            <input type="text" class="form-control feeder_subtype"
                                                                style="width:160px;" id="feeder_subtype"
                                                                value="{{ $existingFeeder->FeederSubType->feeder_sub_type ?? '' }}"
                                                                readonly>
                                                        @else
                                                            <select name="feeders[{{ $i }}][subtype]"
                                                                style="width:160px;" id="feeder_subtype"
                                                                class="form-control feeder_subtype">
                                                                <option value="">Select</option>
                                                                @foreach ($feedersubtype as $sub)
                                                                    <option value="{{ $sub->feeder_sub_type_id }}">
                                                                        {{ $sub->feeder_sub_type }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @endif
                                                    </div>

                                                    {{-- Door --}}
                                                    <div class="col-md-2" style="width:15%">

                                                        @if ($existingFeeder)
                                                            <input type="text" class="form-control"
                                                                style="width:160px;"
                                                                value="{{ $existingFeeder->door_cover }}" readonly>
                                                        @else
                                                            <select name="feeders[{{ $i }}][door]"
                                                                class="form-control" style="width:160px;">
                                                                <option value="">Select</option>
                                                                <option value="Door">Door</option>
                                                                <option value="Cover">Cover</option>
                                                            </select>
                                                        @endif
                                                    </div>

                                                    <div class="col-md-2 d-flex justify-content-center" style="width:24%">
                                                        <input type="text"
                                                            name="feeders[{{ $i }}][customer_remarks]"
                                                            class="form-control feeder-customer_remarks-input"
                                                            style="width:350px;"
                                                            value="{{ $existingFeeder->customer_remarks ?? '' }}"
                                                            {{ $existingFeeder ? 'disabled' : '' }}>
                                                    </div>

                                                </div>
                                            @endfor
                                            <button type="submit" class="btn btn-success mt-2 save-feeder-btn">Save
                                                Feeders</button>
                                        </form>
                                    @endif


                                    {{-- ================= FEEDER LIST ================= --}}

                                    <table class="table table-sm table-bordered mt-3">
                                        <thead class="thead-light">
                                            <tr>
                                                <th width="60">Sr No.</th>
                                                <th width="80">Name</th>
                                                <th width="70">Height</th>
                                                <th>Category</th>
                                                <th>Type</th>
                                                <th>Sub Type</th>
                                                <th width="70">Door/Cover</th>
                                                <th width="120">Customer Remarks</th>
                                                <th width="120">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">

                                            @foreach ($section->feeders as $key => $feeder)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $feeder->feeder_name }}</td>
                                                    <td>{{ $feeder->height }}</td>
                                                    <td>
                                                        {{ $feeder->FeederCategory->feeder_category_name ?? '-' }}
                                                    </td>

                                                    <td>
                                                        {{ $feeder->FeederType->feeder_type ?? '-' }}
                                                    </td>

                                                    <td>
                                                        {{ $feeder->FeederSubType->feeder_sub_type ?? '-' }}
                                                    </td>
                                                    <td>{{ $feeder->door_cover }}</td>
                                                    <td>{{ \Illuminate\Support\Str::limit($feeder->customer_remarks, 50, '...') }}
                                                    </td>
                                                    <td>

                                                        <button class="btn btn-sm btn-warning" data-toggle="modal"
                                                            data-target="#editFeederModal{{ $feeder->id }}">
                                                            <i class="fas fa-edit"></i>

                                                        </button>

                                                        <form action="{{ route('feeder.delete', $feeder->id) }}"
                                                            method="POST" class="delete-section-form"
                                                            style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger delete-btn">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>

                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>


                                </div>
                            </div>
                        @endforeach
                    @endif

                    <div class="card shadow-sm">
                        <div class="card-header">
                            Panel Preview (Scaled)
                        </div>

                        <div class="card-body">
                            <div
                                style="overflow:auto; border:1px solid #ccc; padding:10px; display:flex; align-items:flex-start;">
                                @php
                                    $fixedHeight = $panel->frame_height;
                                    $sectionHeaderHeight = 0; // no top header space in this preview
                                    $rollerHeight = $fixedHeight * $scale + $sectionHeaderHeight;
                                @endphp

                                <div
                                    style="
                                        width:48px;
                                        min-width:48px;
                                        height: {{ $rollerHeight }}px;
                                        position:relative;
                                        border-right:1px solid #000;
                                        background:#fff;
                                        box-sizing:border-box;
                                        margin-right:40px;
                                        overflow:visible;
                                    ">

                                    @for ($i = $fixedHeight; $i >= 0; $i -= 100)
                                        @php
                                            $lineTop = $sectionHeaderHeight + ($fixedHeight - $i) * $scale;

                                            if ($i == $fixedHeight) {
                                                $lineTop = $sectionHeaderHeight + 2;
                                            }

                                            if ($i == 0) {
                                                $lineTop = $sectionHeaderHeight + $fixedHeight * $scale - 1;
                                            }
                                        @endphp

                                        <div
                                            style="
                                                position:absolute;
                                                top: {{ $lineTop }}px;
                                                left:0;
                                                width:100%;
                                                height:0;
                                            ">

                                            {{-- tick --}}
                                            <span
                                                style="
                                                    position:absolute;
                                                    right:0;
                                                    width:12px;
                                                    border-top:1px solid #777;
                                                ">
                                            </span>

                                            {{-- number --}}
                                            <span
                                                style="
                                                    position:absolute;
                                                    right:14px;
                                                    top:-4px;
                                                    font-size:7px;
                                                    line-height:1;
                                                    color:#333;
                                                    background:#fff;
                                                    white-space:nowrap;
                                                    padding:0;
                                                ">
                                                {{ $i }}
                                            </span>
                                        </div>
                                    @endfor
                                </div>

                                <div
                                    style="
                                            display:flex;
                                            width: {{ $panel->frame_width * $scale }}px;
                                            height: {{ $fixedHeight * $scale }}px;
                                            box-sizing: border-box;
                                            border-left:2px solid black;
                                            align-items:flex-start;
                                        ">

                                    @foreach ($sectionsToShow->take(1) as $section)
                                        @php
                                            $totalHeight = $section->feeders->sum('height');
                                            $remainingHeight = $fixedHeight - $totalHeight;
                                        @endphp

                                        <div
                                            style="
                                                width: {{ $section->width * $scale }}px;
                                                height: 100%;
                                                border-right:1px solid black;
                                                border-top:1px solid black;
                                                border-bottom:1px solid black;
                                                display:flex;
                                                flex-direction:column;
                                                overflow:hidden;
                                                box-sizing:border-box;
                                            ">
                                            <div
                                                style="
                                                    height:20px;
                                                    background:#e9ecef;
                                                    text-align:center;
                                                    font-size:10px;
                                                    border-bottom:1px solid black;
                                                ">
                                            </div>
                                            <div
                                                style="
                                                    position:absolute;
                                                    top:-16px;
                                                    left:0;
                                                    width:100%;
                                                    text-align:center;
                                                    font-size:20px;
                                                    line-height:1.1;
                                                    font-weight:600;
                                                    z-index:10;
                                                    pointer-events:none;
                                                    background:transparent;
                                                ">
                                                {{ $section->section_name }} ({{ $section->width }})
                                            </div>

                                            @foreach ($section->feeders as $feeder)
                                                <div
                                                    style="
                                                        height: {{ $feeder->height * $scale }}px;
                                                        min-height: {{ $feeder->height * $scale }}px;
                                                        border-bottom:1px solid black;
                                                        position:relative;
                                                        display:flex;
                                                        align-items:center;
                                                        justify-content:center;
                                                        font-size:10px;
                                                        box-sizing:border-box;
                                                        background:#f8f9fa;
                                                    ">

                                                    {{-- {{ $feeder->feeder_name }} --}}
                                                    <div style="text-align:center; line-height:1.35;">
                                                        <div>{{ $feeder->feeder_name }}</div>
                                                        @if (!empty($feeder->FeederCategory->feeder_category_name))
                                                            <div>
                                                                {{ $feeder->FeederCategory->feeder_category_name }}
                                                            </div>
                                                            <div>
                                                                {{ $feeder->FeederType->feeder_type }}
                                                            </div>
                                                            <div>
                                                                {{ $feeder->FeederSubType->feeder_sub_type }}
                                                            </div>
                                                        @endif
                                                    </div>

                                                    {{-- RED ARROW + HEIGHT POSITION BASED ON LOCK POSITION --}}
                                                    @php
                                                        $arrowSide =
                                                            $section->lock_position == 'Left' ? 'right' : 'left';
                                                        $arrowOffset = '24px';
                                                        $heightOffset = '2px';
                                                    @endphp

                                                    <div
                                                        style="
                                                            position:absolute;
                                                            top:6px;
                                                            bottom:6px;
                                                            {{ $arrowSide }}: {{ $arrowOffset }};
                                                            width:0;
                                                            border-left:2px solid rgba(255, 0, 0, 0.65);
                                                            z-index:5;
                                                            pointer-events:none;
                                                        ">

                                                        {{-- top arrow --}}
                                                        <span
                                                            style="
                                                                    position:absolute;
                                                                    top:-6px;
                                                                    left:-5px;
                                                                    width:0;
                                                                    height:0;
                                                                    border-left:5px solid transparent;
                                                                    border-right:5px solid transparent;
                                                                    border-bottom:8px solid rgba(255, 0, 0, 0.65);
                                                                ">
                                                        </span>

                                                        {{-- bottom arrow --}}
                                                        <span
                                                            style="
                                                                    position:absolute;
                                                                    bottom:-6px;
                                                                    left:-5px;
                                                                    width:0;
                                                                    height:0;
                                                                    border-left:5px solid transparent;
                                                                    border-right:5px solid transparent;
                                                                    border-top:8px solid rgba(255, 0, 0, 0.65);
                                                                ">
                                                        </span>
                                                    </div>

                                                    <div
                                                        style="
                                                                position:absolute;
                                                                {{ $arrowSide }}: {{ $heightOffset }};
                                                                top:50%;
                                                                transform:translateY(-50%);
                                                                font-size:9px;
                                                                color:rgba(255, 0, 0, 0.65);
                                                                font-weight:600;
                                                                line-height:1;
                                                            ">
                                                        {{ $feeder->height }}
                                                    </div>
                                                    @if ($feeder->door_cover == 'Door')
                                                        @if ($section->lock_position == 'Left')
                                                            <span
                                                                style="
                                                                position:absolute;
                                                                left:4px;
                                                                top:50%;
                                                                transform:translateY(-50%);
                                                                width:14px;
                                                                height:14px;
                                                                border:2px solid black;
                                                                border-radius:4px;
                                                                background:#fff;
                                                            "></span>
                                                        @elseif($section->lock_position == 'Right')
                                                            <span
                                                                style="
                                                                position:absolute;
                                                                right:4px;
                                                                top:50%;
                                                                transform:translateY(-50%);
                                                                width:14px;
                                                                height:14px;
                                                                border:2px solid black;
                                                                border-radius:4px;
                                                                background:#fff;
                                                            "></span>
                                                        @endif
                                                    @endif

                                                    @if ($feeder->door_cover == 'Cover')
                                                        <div class="cover-dot dot-tl">â€¢</div>
                                                        <div class="cover-dot dot-tr">â€¢</div>
                                                        <div class="cover-dot dot-bl">â€¢</div>
                                                        <div class="cover-dot dot-br">â€¢</div>
                                                    @endif

                                                </div>
                                            @endforeach

                                            @if ($remainingHeight > 0)
                                                <div
                                                    style="
                                                        height: {{ $remainingHeight * $scale }}px;
                                                        border-bottom:1px dashed #ccc;
                                                    ">
                                                </div>
                                            @endif

                                        </div>
                                    @endforeach

                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- ================= FEEDER EDIT MODALS ================= --}}
                    @foreach ($sectionsToShow as $section)
                        @foreach ($section->feeders as $feeder)
                            <div class="modal fade" id="editFeederModal{{ $feeder->id }}">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <form method="POST" action="{{ route('feeder.update', $feeder->id) }}">
                                            @csrf

                                            <div class="modal-header">
                                                <h5>Edit Feeder</h5>
                                                <button type="button" class="close"
                                                    data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Name</label>
                                                    <input type="text" name="feeder_name"
                                                        value="{{ $feeder->feeder_name }}" class="form-control" readonly>
                                                </div>

                                                <div class="form-group">
                                                    <label>Height</label>
                                                    <input type="number" name="height" value="{{ $feeder->height }}"
                                                        class="form-control" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Feeder Category</label>
                                                    <select name="feeder_category" class="form-control feeder_category">
                                                        <option value="">Please Select</option>
                                                        @foreach ($feedercategory as $feedercat)
                                                            <option value="{{ $feedercat->feeder_category_id }}"
                                                                {{ $feedercat->feeder_category_id == $feeder->f_category_id ? 'selected' : '' }}>
                                                                {{ $feedercat->feeder_category_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Feeder Type</label>
                                                    <select name="feeder_type" class="form-control feeder_type">
                                                        <option value="">Please Select</option>
                                                        @foreach ($feedertype as $feederty)
                                                            <option value="{{ $feederty->feeder_type_id }}"
                                                                {{ $feederty->feeder_type_id == $feeder->f_type_id ? 'selected' : '' }}>
                                                                {{ $feederty->feeder_type }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Feeder Sub Type</label>
                                                    <select name="feeder_subtype" class="form-control feeder_subtype">
                                                        <option value="">Please Select</option>
                                                        @foreach ($feedersubtype as $feedersub)
                                                            <option value="{{ $feedersub->feeder_sub_type_id }}"
                                                                {{ $feedersub->feeder_sub_type_id == $feeder->f_subtype_id ? 'selected' : '' }}>
                                                                {{ $feedersub->feeder_sub_type }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Door/Cover</label>
                                                    <select name="door_cover" class="form-control">
                                                        <option value="">Please Select</option>
                                                        <option value="Door"
                                                            {{ $feeder->door_cover == 'Door' ? 'selected' : '' }}>
                                                            Door
                                                        </option>
                                                        <option value="Cover"
                                                            {{ $feeder->door_cover == 'Cover' ? 'selected' : '' }}>
                                                            Cover
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Customer Remarks</label>
                                                    <input type="text" name="customer_remarks"
                                                        value="{{ $feeder->customer_remarks }}" class="form-control">
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button class="btn btn-success">
                                                    Update
                                                </button>
                                            </div>

                                        </form>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    @endsection
    @section('scripts')
        <script>
            // =============================================
            // FUNCTION: Load feeder categories into all
            // feeder_category dropdowns by section_type_id
            // =============================================
            function loadFeederCategories(sectionTypeId) {
                if (!sectionTypeId) {
                    $('select.feeder_category').html('<option value="">Select</option>');
                    return;
                }

                console.log('Calling URL:', '/get-feeder-categories/' + sectionTypeId);

                $.ajax({
                    url: '/Actifab/get-feeder-categories/' + sectionTypeId,
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    success: function(data) {
                        console.log('Success:', data);

                        let options = '<option value="">Select</option>';
                        data.forEach(function(cat) {
                            options += `<option value="${cat.feeder_category_id}">
                    ${cat.feeder_category_name}
                </option>`;
                        });

                        $('select.feeder_category').html(options);
                        $('select.feeder_type').html('<option value="">Select</option>');
                        $('select.feeder_subtype').html('<option value="">Select</option>');
                    },
                    error: function(xhr) {
                        // ðŸ‘‡ This will show you EXACTLY what error is coming back
                        console.error('Status:', xhr.status);
                        console.error('Response:', xhr.responseText);
                        $('select.feeder_category').html('<option value="">Error loading</option>');
                    }
                });
            }

            // =============================================
            // ON PAGE LOAD â†’ if edit mode, auto-load
            // categories based on saved section_type_id
            // =============================================
            $(document).ready(function() {

                // Edit mode: section_type already selected â†’ load categories
                let sectionTypeOnLoad = $('#current_section_type_id').val();
                if (sectionTypeOnLoad) {
                    loadFeederCategories(sectionTypeOnLoad);
                }

                // When admin changes section type in the Add Section form
                $('#section_type_select').on('change', function() {
                    let sectionTypeId = $(this).val();
                    loadFeederCategories(sectionTypeId);
                });

            });
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {

                function updateRemainingHeight(sectionCard) {
                    const remainingEl = sectionCard.querySelector(".live-remaining-height");
                    const fullMsgEl = sectionCard.querySelector(".live-height-message");
                    const exceedMsgEl = sectionCard.querySelector(".live-height-exceed-message");
                    const saveBtn = sectionCard.querySelector(".save-feeder-btn");

                    const inputs = Array.from(sectionCard.querySelectorAll(".feeder-height-input"));

                    if (!remainingEl || inputs.length === 0) return;

                    const frameHeight = parseInt(remainingEl.dataset.frameHeight || 0, 10);
                    const usedHeight = parseInt(remainingEl.dataset.usedHeight || 0, 10);

                    let newInputTotal = 0;

                    inputs.forEach(input => {
                        if (!input.disabled) {
                            const val = parseInt(input.value || 0, 10);
                            newInputTotal += isNaN(val) ? 0 : val;
                        }
                        input.classList.remove("is-invalid");
                    });

                    const totalUsed = usedHeight + newInputTotal;
                    const remaining = frameHeight - totalUsed;

                    remainingEl.textContent = remaining > 0 ? remaining : 0;

                    if (fullMsgEl) fullMsgEl.style.display = "none";
                    if (exceedMsgEl) exceedMsgEl.style.display = "none";
                    if (saveBtn) saveBtn.disabled = false;

                    if (totalUsed === frameHeight) {
                        if (fullMsgEl) {
                            fullMsgEl.style.display = "block";
                            fullMsgEl.textContent = "Your feeder height is used.";
                        }
                    }

                    if (totalUsed > frameHeight) {
                        if (exceedMsgEl) {
                            exceedMsgEl.style.display = "block";
                            exceedMsgEl.textContent =
                                "Your feeder height is already used. You cannot enter more height.";
                        }
                        if (saveBtn) saveBtn.disabled = true;
                    }

                    // next empty textbox readonly when height full
                    let runningTotal = usedHeight;
                    inputs.forEach(input => {
                        if (input.disabled) return;

                        const row = input.closest('.row'); // ðŸ‘ˆ same row pakadna

                        const val = parseInt(input.value || 0, 10);
                        const safeVal = isNaN(val) ? 0 : val;

                        const otherFields = row.querySelectorAll(
                            '.feeder_category, .feeder_type, .feeder_subtype, select, .feeder-customer_remarks-input'
                        );

                        if (runningTotal >= frameHeight && !input.value) {

                            // Height readonly
                            input.setAttribute("readonly", true);
                            input.style.backgroundColor = "#f1f1f1";
                            input.style.cursor = "not-allowed";

                            // ðŸ‘‡ Other fields disable
                            otherFields.forEach(field => {
                                field.setAttribute("disabled", true);
                                field.style.backgroundColor = "#f1f1f1";
                                field.style.cursor = "not-allowed";
                            });

                        } else {

                            input.removeAttribute("readonly");
                            input.style.backgroundColor = "";
                            input.style.cursor = "";

                            // ðŸ‘‡ enable back
                            otherFields.forEach(field => {
                                field.removeAttribute("disabled");
                                field.style.backgroundColor = "";
                                field.style.cursor = "";
                            });
                        }

                        if (runningTotal >= frameHeight && safeVal > 0) {
                            input.classList.add("is-invalid");
                        }

                        runningTotal += safeVal;
                    });
                }

                document.querySelectorAll(".section-card-live").forEach(sectionCard => {
                    updateRemainingHeight(sectionCard);

                    sectionCard.querySelectorAll(".feeder-height-input").forEach(input => {
                        input.addEventListener("input", function() {
                            updateRemainingHeight(sectionCard);
                        });
                    });
                });

            });
        </script>

        <script>
            setTimeout(function() {
                let alertBox = document.getElementById('success-alert');
                if (alertBox) {
                    alertBox.style.transition = "opacity 0.5s ease";
                    alertBox.style.opacity = "0";

                    setTimeout(() => {
                        alertBox.remove();
                    }, 500);
                }
            }, 5000); // 5000ms = 5 sec
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let sectionSelect = document.querySelector('[name="section_id"]');
                let feederInput = document.querySelector('[name="feeder_name"]');

                if (sectionSelect && feederInput) {
                    sectionSelect.addEventListener('change', function() {
                        let sectionId = this.value;

                        if (sectionId) {
                            fetch(`/get-feeder-count/${sectionId}`)
                                .then(res => res.json())
                                .then(data => {
                                    let nextNumber = data.count + 1;
                                    feederInput.value = sectionId + 'F' + nextNumber;
                                });
                        }
                    });
                }
            });
        </script>

        <script>
            $(document).on('change', '.feeder_category', function() {

                let categoryId = $(this).val();

                // same form ke andar ke type & subtype pakadenge
                let form = $(this).closest('.row');

                let typeDropdown = form.find('.feeder_type');
                let subTypeDropdown = form.find('.feeder_subtype');

                typeDropdown.html('<option value="">Loading...</option>');
                subTypeDropdown.html('<option value="">Please Select</option>');

                if (categoryId !== '') {

                    $.ajax({
                        url: '/Actifab/get-feeder-types/' + categoryId,
                        type: 'GET',
                        success: function(data) {

                            let options = '<option value="">Please Select</option>';

                            data.forEach(function(type) {
                                options += `<option value="${type.feeder_type_id}">
                                    ${type.feeder_type}
                                </option>`;
                            });

                            typeDropdown.html(options);
                        }
                    });

                }
            });


            $(document).on('change', '.feeder_type', function() {

                let typeId = $(this).val();

                let form = $(this).closest('.row');

                let typeDropdown = form.find('.feeder_subtype');
                let subTypeDropdown = form.find('.feeder_subtype');

                subTypeDropdown.html('<option value="">Loading...</option>');

                if (typeId !== '') {

                    $.ajax({
                        url: '/Actifab/get-feeder-subtypes/' + typeId,
                        type: 'GET',
                        success: function(data) {

                            let options = '<option value="">Please Select</option>';

                            data.forEach(function(sub) {
                                options += `<option value="${sub.feeder_sub_type_id}">
                                    ${sub.feeder_sub_type}
                                </option>`;
                            });

                            subTypeDropdown.html(options);
                        }
                    });

                }
            });
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {

                document.querySelectorAll(".delete-btn").forEach(function(button) {

                    button.addEventListener("click", function() {

                        let form = this.closest("form");

                        Swal.fire({
                            title: "Are you sure?",
                            text: "This section will be permanently deleted!",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#d33",
                            cancelButtonColor: "#3085d6",
                            confirmButtonText: "Yes, delete it!"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });

                    });

                });

            });
        </script>
    @endsection
