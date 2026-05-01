@extends('layouts.user')

@section('content')
    <style>
        .cover-dot {
            position: absolute;
            font-size: 10px;
        }

        .dot-tl {
            top: 8%;
            left: 12%;
        }

        .dot-tr {
            top: 8%;
            right: 12%;
        }

        .dot-bl {
            bottom: 8%;
            left: 12%;
        }

        .dot-br {
            bottom: 8%;
            right: 12%;
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
    </style>
    <style>
        .section-header-cell {
            text-align: center;
            vertical-align: middle;
            padding: 10px;
        }

        .icon-wrap i {
            font-size: 14px;
        }

        .icon-btn {
            margin: 0 3px;
        }
    </style>

    <style>
        .section-grid-table {
            border-collapse: collapse;
            width: max-content;
            min-width: 100%;
        }

        .section-grid-table td,
        .section-grid-table th {
            border: 1px solid #666;
            text-align: center;
            vertical-align: middle;
            padding: 0;
        }

        .blank-merged-cell {
            background: #fff;
            width: 270px;
            min-width: 270px;
        }

        .blank-bottom {
            border: none !important;
            background: transparent !important;
            height: 34px;
        }

        .small-col {
            width: 50px;
            min-width: 50px;
        }

        .feeder-col {
            width: 120px;
            min-width: 120px;
        }

        .height-col {
            width: 80px;
            min-width: 80px;
        }

        .section-top-icons,
        .section-header-cell,
        .section-height-head,
        .section-data-cell,
        .bottom-number-cell {
            width: 120px;
            min-width: 120px;
        }

        .main-head {
            background: #1f4e6b;
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            padding: 8px 6px !important;
        }

        .left-grid-cell {
            background: #efefef;
            height: 36px;
            font-size: 14px;
            padding: 6px !important;
        }

        .section-top-icons {
            height: 42px;
            padding: 4px !important;
        }

        .section-header-cell {
            height: 64px;
            text-align: left;
            vertical-align: top;
            padding: 8px !important;
        }

        .section-title {
            font-size: 14px;
            line-height: 1.2;
            color: #000;
        }

        .yellow-col {
            background: #fdf5e2 !important;
        }

        .green-col {
            background: #e2eafd !important;
        }

        .section-data-cell {
            height: 36px;
            font-size: 14px;
            padding: 6px !important;
        }

        .red-text {
            color: red;
            font-weight: 600;
        }

        .icon-wrap {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 6px;
            background: #3b73d9;
            padding: 6px 8px;
            width: fit-content;
            margin: 0 auto;
        }

        .icon-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 22px;
            height: 22px;
            color: #fff;
            border: none;
            border-radius: 2px;
            text-decoration: none;
            cursor: pointer;
            padding: 0;
        }

        .edit-btn {
            background: #f0b43c;
        }

        .delete-btn {
            background: #5a2d0c;
        }

        .bottom-number-cell {
            border: none !important;
            background: transparent !important;
            font-size: 18px;
            font-weight: 700;
            padding-top: 8px !important;
        }

        .section-grid-table {
            width: auto !important;
            min-width: auto !important;
            table-layout: fixed !important;
            border-collapse: collapse;
        }

        .serial-col,
        .serial-cell {
            width: 36px !important;
            min-width: 36px !important;
            max-width: 36px !important;
            padding: 1px 2px !important;
            text-align: center;
        }

        .feeder-name-col,
        .feeder-name-cell {
            width: 58px !important;
            min-width: 58px !important;
            max-width: 58px !important;
            padding: 1px 2px !important;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .left-grid-cell {
            height: 24px !important;
            font-size: 12px;
            line-height: 1;
        }

        .main-head {
            padding: 2px 3px !important;
            font-size: 12px !important;
        }

        .blank-merged-cell {
            width: 94px !important;
            /* 36 + 58 */
            min-width: 94px !important;
            max-width: 94px !important;
        }
    </style>

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="col-md-12 text-end mb-2">
                    @if ($panel->submit_project == 0)
                        <a href="{{ route('panel.show', $panel->id) }}" class="btn btn-sm btn-primary">
                            Add Section
                        </a>
                    @else
                        <button class="btn btn-sm btn-secondary" disabled>
                            Add Section (Locked)
                        </button>
                    @endif
                </div>

                <div class="card shadow-sm mb-2">
                    <div class="card-body d-flex  pb-1 pt-2">

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
                </div>

                {{-- SECTION + FEEDER LIST --}}
                <div class="row">
                    @php
                        $sections = $panel->sections->values();
                        $maxFeeders = 9; // fixed like your screenshot F1 to F9
                    @endphp

                    <div class="card shadow-sm mt-3">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Section Grid View</h5>
                        </div>

                        <div class="card-body p-2" style="overflow-x:auto;">
                            <table class="section-grid-table">
                                <tbody>
                                    {{-- Top action row --}}
                                    <tr>
                                        <td colspan="2" class="blank-merged-cell"></td>


                                        @foreach ($sections as $section)
                                            <td class="section-top-icons">
                                                <div class="icon-wrap">
                                                    @if ($panel->submit_project == 0)
                                                        <a href="{{ route('panel.show', [$panel->id, $section->id]) }}"
                                                            class="icon-btn edit-btn">
                                                            <i class="fas fa-edit"></i>
                                                        </a>

                                                        <form action="{{ route('section.delete', $section->id) }}"
                                                            method="POST" class="d-inline delete-section-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="icon-btn delete-btn">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <button class="icon-btn" style="background:#ccc;" disabled>
                                                            <i class="fas fa-edit"></i>
                                                        </button>

                                                        <button class="icon-btn" style="background:#ccc;" disabled>
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>

                                    {{-- Section title row --}}
                                    <tr>
                                        <td colspan="2" class="blank-merged-cell"></td>


                                        @foreach ($sections as $index => $section)
                                            <td
                                                class="section-header-cell {{ $index % 2 == 0 ? 'yellow-col' : 'green-col' }}">
                                                <div class="section-title">
                                                    <div>Section: {{ $index + 1 }}</div>
                                                    <div>Width: {{ $section->width }}</div>
                                                    <div>{{ $section->section_name }}</div>
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>

                                    {{-- Column header row --}}
                                    <tr>
                                        <th class="main-head serial-col" style="width:10%">#</th>
                                        <th class="main-head feeder-name-col">Feeder</th>

                                        @foreach ($sections as $section)
                                            <th class="main-head section-height-head">Height</th>
                                        @endforeach
                                    </tr>

                                    {{-- Feeder rows --}}
                                    @for ($i = 0; $i < $maxFeeders; $i++)
                                        <tr>
                                            <td class="left-grid-cell serial-cell">{{ $i + 1 }}</td>
                                            <td class="left-grid-cell feeder-name-cell">F{{ $i + 1 }}</td>

                                            @foreach ($sections as $index => $section)
                                                @php
                                                    $feeder = $section->feeders[$i] ?? null;
                                                @endphp
                                                <td
                                                    class="section-data-cell {{ $index % 2 == 0 ? 'yellow-col' : 'green-col' }} red-text">
                                                    {{ $feeder->height ?? '' }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endfor

                                    {{-- Bottom numbering row --}}
                                    <tr>
                                        <td class="blank-bottom"></td>
                                        <td class="blank-bottom"></td>

                                        @foreach ($sections as $index => $section)
                                            <td class="bottom-number-cell">{{ $index + 1 }}</td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">

                    @php
                        $sections = $panel->sections;

                        $previewFrameWidth = max($sections->sum('width'), 1);
                        $previewFrameHeight = $panel->frame_height;

                        $scale = 0.25;
                        $sectionHeaderHeight = 22;

                        $panelOuterHeight = $previewFrameHeight * $scale + $sectionHeaderHeight;
                        $rollerHeight = $panelOuterHeight;
                        $feederAreaHeight = $previewFrameHeight * $scale;
                        $maxScale = $previewFrameHeight;
                    @endphp

                    <div class="col-12">
                        <div class="card mt-4">
                            <div class="card-header">
                                Full Panel Preview
                            </div>

                            <div class="card-body" style="background:#e5e5e5; padding:15px;">
                                <div style="overflow-x:auto; overflow-y:hidden; width:100%; padding-bottom:4px;">
                                    <div style="display:flex; justify-content:flex-start; width:100%;">
                                        <div style="display:flex; justify-content:flex-start; align-items:flex-start;">

                                            {{-- LEFT ROLLER / SCALE --}}
                                            <div
                                                style="
                                    width:48px;
                                    min-width:48px;
                                    height: {{ $rollerHeight }}px;
                                    position:relative;
                                    border-right:1px solid #000;
                                    background:#fff;
                                    box-sizing:border-box;
                                    margin-right:30px;
                                    overflow:visible;
                                ">

                                                @for ($i = $maxScale; $i >= 0; $i -= 100)
                                                    @php
                                                        $lineTop = $sectionHeaderHeight + ($maxScale - $i) * $scale;

                                                        if ($i == $maxScale) {
                                                            $lineTop = $sectionHeaderHeight;
                                                        }

                                                        if ($i == 0) {
                                                            $lineTop = $sectionHeaderHeight + $feederAreaHeight - 1;
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

                                                        <span
                                                            style="
                                                position:absolute;
                                                right:0;
                                                width:10px;
                                                border-top:1px solid #777;
                                            ">
                                                        </span>

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

                                            {{-- PREVIEW PANEL --}}

                                            <div
                                                style="
                                    display:flex;
                                    width: {{ $previewFrameWidth * $scale }}px;
                                    height: {{ $panelOuterHeight }}px;
                                    background:#fff;
                                    border:none;
                                    box-sizing:border-box;
                                    position:relative;
                                ">
                                                {{-- Horizontal Line Start --}}
                                                <div
                                                    style="
                                                        position:absolute;
                                                        top: {{ $sectionHeaderHeight }}px;
                                                        left:0;
                                                        right:0;
                                                        border-top:1px solid #000;
                                                        z-index:10;
                                                    ">
                                                </div>

                                                @foreach ($sections as $section)
                                                    @php
                                                        $totalHeight = $section->feeders->sum('height');
                                                        $remainingHeight = max($previewFrameHeight - $totalHeight, 0);
                                                    @endphp

                                                    <div
                                                        style="
                                            width: {{ $section->width * $scale }}px;
                                            height: 100%;
                                            border-left:1px solid #222;
                                            border-right:2px solid #222;
                                            display:flex;
                                            flex-direction:column;
                                            background:#fcfcfc;
                                            box-sizing:border-box;
                                            position:relative;
                                        ">

                                                        {{-- SECTION NAME --}}
                                                        <div
                                                            style="
                                                                position:relative;
                                                                height: {{ $sectionHeaderHeight }}px;
                                                                display:flex;
                                                                align-items:center;
                                                                justify-content:center;
                                                                background:#f2f2f2;
                                                                font-size:10px;
                                                                font-weight:bold;
                                                            ">

                                                            {{-- hide left line --}}
                                                            <span
                                                                style="
                                                                position:absolute;
                                                                left:-2px;
                                                                top:0;
                                                                width:4px;
                                                                height:100%;
                                                                background:#f2f2f2;
                                                            "></span>

                                                            {{-- hide right line --}}
                                                            <span
                                                                style="
                                                                position:absolute;
                                                                right:-2px;
                                                                top:0;
                                                                width:4px;
                                                                height:100%;
                                                                background:#f2f2f2;
                                                            "></span>

                                                            {{ $section->section_name }} / {{ $section->width }}
                                                        </div>

                                                        {{-- FEEDERS --}}
                                                        @foreach ($section->feeders as $feeder)
                                                            @php
                                                                $feederPixelHeight = $feeder->height * $scale;
                                                            @endphp

                                                            <div
                                                                style="
                                                                height: {{ $feederPixelHeight }}px;
                                                                min-height: {{ $feederPixelHeight }}px;
                                                                border-bottom:1px solid #222;
                                                                display:flex;
                                                                align-items:center;
                                                                justify-content:center;
                                                                font-size:10px;
                                                                font-weight:500;
                                                                position:relative;
                                                                box-sizing:border-box;
                                                                background:#fff;
                                                                color:#111;
                                                            ">

                                                                {{-- feeder center text --}}
                                                                <div style="text-align:center; line-height:1.35;">
                                                                    <div>{{ $feeder->feeder_name }}</div>
                                                                    @if (!empty($feeder->FeederCategory->feeder_category_name))
                                                                        <div>
                                                                            <div>
                                                                                {{ \Illuminate\Support\Str::limit($feeder->FeederCategory->feeder_category_name, 10, '...') }}
                                                                            </div>
                                                                        </div>
                                                                        <div>
                                                                            {{ \Illuminate\Support\Str::limit($feeder->FeederType->feeder_type, 10, '...') }}
                                                                        </div>
                                                                        <div>
                                                                            {{ \Illuminate\Support\Str::limit($feeder->FeederSubType->feeder_sub_type, 10, '...') }}
                                                                        </div>
                                                                    @endif
                                                                </div>

                                                                {{-- RED ARROW + HEIGHT POSITION BASED ON LOCK POSITION --}}
                                                                @php
                                                                    $arrowSide =
                                                                        $section->lock_position == 'Left'
                                                                            ? 'right'
                                                                            : 'left';
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
                                                                {{-- LOCK --}}
                                                                {{-- @if ($feeder->door_cover == 'Door')
                                                                    <span
                                                                        style="
                                                            position:absolute;
                                                            {{ $section->lock_position == 'Left' ? 'left:4px;' : 'left:4px;' }}
                                                            top:50%;
                                                            transform:translateY(-50%);
                                                            width:12px;
                                                            height:12px;
                                                            border:2px solid #000;
                                                            border-radius:4px;
                                                            background:#fff;
                                                        "></span>
                                                                @endif --}}

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

                                                                {{-- COVER --}}
                                                                @if ($feeder->door_cover == 'Cover')
                                                                    <div class="cover-dot dot-tl">•</div>
                                                                    <div class="cover-dot dot-tr">•</div>
                                                                    <div class="cover-dot dot-bl">•</div>
                                                                    <div class="cover-dot dot-br">•</div>
                                                                @endif
                                                            </div>
                                                        @endforeach

                                                        {{-- EMPTY SPACE --}}
                                                        @if ($remainingHeight > 0)
                                                            @php
                                                                $remainingPixelHeight = $remainingHeight * $scale;
                                                            @endphp

                                                            <div
                                                                style="
                                                    height: {{ $remainingPixelHeight }}px;
                                                    min-height: {{ $remainingPixelHeight }}px;
                                                    background:#fafafa;
                                                ">
                                                            </div>
                                                        @endif

                                                    </div>
                                                @endforeach

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    @endsection
    @section('scripts')
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
