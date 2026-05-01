@extends('layouts.user')

@section('title', 'Manage Panel')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                @include('common.alert')
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Page Title --}}
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Manage Panel</h4>
                            <div class="page-title-right">
                                <a href="{{ url()->previous() }}" class="btn btn-sm btn-primary shadow-sm">
                                    <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PANEL INFO --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="alert alert-info mb-0">
                            <strong>Total Size:</strong>
                            {{ $panel->frame_width }} x {{ $panel->frame_height }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="alert alert-warning mb-0">
                            <strong>Remaining Width:</strong>
                            {{ $remainingWidth }}
                        </div>
                    </div>
                </div>

                {{-- ADD SECTION --}}
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Add Section</h5>
                            </div>
                            <div class="card-body">

                                <form method="POST" action="{{ route('section.store') }}">
                                    @csrf

                                    <input type="hidden" name="panel_id" value="{{ $panel->id }}">

                                    <div class="row">

                                        <div class="col-md-5 mb-3">
                                            <label class="form-label">Section Name</label>
                                            <input type="text" name="section_name" class="form-control">
                                        </div>

                                        <div class="col-md-5 mb-3">
                                            <label class="form-label">Width</label>
                                            <input type="number" name="width" class="form-control">
                                        </div>

                                        <div class="col-md-2 mb-3 d-flex align-items-end">
                                            <button class="btn btn-primary w-100">Create</button>
                                        </div>

                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

                {{-- ADD FEEDER --}}
                <div class="row mt-3">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Add Feeder</h5>
                            </div>
                            <div class="card-body">

                                <form method="POST" action="{{ route('feeder.store') }}">
                                    @csrf

                                    <input type="hidden" name="panel_id" value="{{ $panel->id }}">

                                    <div class="row">

                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Select Section</label>
                                            <select name="section_id" class="form-control">
                                                @foreach ($panel->sections as $section)
                                                    <option value="{{ $section->id }}">
                                                        {{ $section->section_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Feeder Name</label>
                                            <input type="text" name="feeder_name" class="form-control">
                                        </div>

                                        <div class="col-md-2 mb-3">
                                            <label class="form-label">Height</label>
                                            <input type="number" name="height" class="form-control">
                                            @error('height')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- <div class="col-md-2 mb-3">
                                            <label class="form-label">Width</label>
                                            <input type="number" name="width" class="form-control">
                                        </div> --}}

                                        <div class="col-md-2 mb-3 d-flex align-items-end">
                                            <button class="btn btn-success w-100">Create</button>
                                        </div>

                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>
                </div>

                {{-- PREVIEW --}}
                <div class="row mt-4">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Preview</h5>
                            </div>
                            <div class="card-body">

                                <div
                                    style="width:{{ $panel->frame_width }}px;
                                        height:{{ $panel->frame_height }}px;
                                        border:2px solid #000;
                                        display:flex;">

                                    @foreach ($panel->sections as $section)
                                        @php
                                            $usedHeight = $section->feeders->sum('height');
                                            $remainingHeight = $panel->frame_height - $usedHeight;
                                        @endphp

                                        <div
                                            style="width:{{ $section->width }}px;
                                                border-right:1px solid #000;
                                                display:flex;
                                                flex-direction:column;">

                                            <div
                                                style="background:#f1f1f1;
                                                    font-size:12px;
                                                    text-align:center;">
                                                Remaining: {{ $remainingHeight }}
                                            </div>

                                            @foreach ($section->feeders as $feeder)
                                                <div
                                                    style="height:{{ $feeder->height }}px;
                                                        border-bottom:1px solid #000;
                                                        text-align:center;">
                                                    {{ $feeder->feeder_name }}
                                                </div>
                                            @endforeach

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

@endsection
