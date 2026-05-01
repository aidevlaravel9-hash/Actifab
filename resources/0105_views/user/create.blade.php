@extends('layouts.user')

@section('title')
    {{ isset($project) ? 'Edit Project' : 'Create Project' }}
@endsection

@section('content')
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

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                @include('common.alert')

                {{-- Page Title --}}
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">
                                {{ isset($project) ? 'Edit Project' : 'Create Project' }}
                            </h4>
                            <div class="page-title-right">
                                <a href="{{ route('indexProject') }}" class="btn btn-sm btn-primary shadow-sm">
                                    <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Section --}}
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">

                                <form method="POST"
                                    action="{{ isset($project) ? route('updateProject', $project->id) : route('storeProject') }}"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div class="row">
                                        
                                        <div class="col-md-4 mb-3 form-inline-group">
                                            <label class="form-label">Created By</label>
                                            <input type="text" class="form-control" value="{{ $user->contact_person }}"
                                                readonly>
                                        </div>
                                        <div class="col-md-4 mb-3 form-inline-group">
                                            <label class="form-label">Creator Email</label>
                                            <input type="text" class="form-control" value="{{ $user->email }}" readonly>
                                        </div>


                                        <div class="col-md-4 mb-3 form-inline-group">
                                            <label class="form-label">Creater Contact</label>
                                            <input type="text" class="form-control" value="{{ $user->mobile }}" readonly>
                                        </div>

                                        {{-- Inquiry No --}}
                                        <div class="col-md-4 mb-3 form-inline-group">
                                            <label class="form-label">Inquiry No</label>
                                            <input type="text" class="form-control"
                                                value="{{ isset($project) ? $project->inquiry_no : $inquiryNo }}" readonly>
                                        </div>

                                        {{-- Inquiry Date --}}
                                        <div class="col-md-4 mb-3 form-inline-group">
                                            <label class="form-label">Inquiry Date</label>
                                            <input type="date" name="inquiry_date" class="form-control"
                                                value="{{ old('inquiry_date', $project->inquiry_date ?? date('Y-m-d')) }}"
                                                readonly>
                                        </div>

                                        {{-- Project Name --}}
                                        <div class="col-md-4 mb-3 form-inline-group">
                                            
                                        </div>

                                        {{-- Customer Name --}}
                                        <div class="col-md-4 mb-3 form-inline-group">
                                            <label class="form-label">
                                                Customer Name <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="customer_name" class="form-control"
                                                value="{{ old('customer_name', $project->customer_name ?? '') }}">
                                            @error('customer_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- Customer Email --}}
                                        <div class="col-md-4 mb-3 form-inline-group">
                                            <label class="form-label">Customer Email</label>
                                            <input type="email" name="customer_email" class="form-control"
                                                value="{{ old('customer_email', $project->customer_email ?? '') }}">
                                        </div>

                                        {{-- Customer Address --}}
                                        <div class="col-md-4 mb-3 form-inline-group">
                                            <label class="form-label">Customer Address</label>
                                            <textarea name="customer_address" class="form-control" rows="2">{{ old('customer_address', $project->customer_address ?? '') }}</textarea>
                                        </div>

                                        {{-- Contact Person Name --}}
                                        <div class="col-md-4 mb-3 form-inline-group">
                                            <label class="form-label">Contact Person Name</label>
                                            <input type="text" name="contact_person_name" class="form-control"
                                                value="{{ old('contact_person_name', $project->contact_person_name ?? '') }}">
                                        </div>

                                        {{-- Contact Person Mobile --}}
                                        <div class="col-md-4 mb-3 form-inline-group">
                                            <label class="form-label">Contact Person Mobile</label>
                                            <input type="text" name="contact_person_mobile" class="form-control"
                                                value="{{ old('contact_person_mobile', $project->contact_person_mobile ?? '') }}">
                                        </div>

                                        {{-- Contact Person Email --}}
                                        <div class="col-md-4 mb-3 form-inline-group">
                                            <label class="form-label">Contact Person Email</label>
                                            <input type="email" name="contact_person_email" class="form-control"
                                                value="{{ old('contact_person_email', $project->contact_person_email ?? '') }}">
                                        </div>
                                         <div class="col-md-4 mb-3 form-inline-group">
                                            <label class="form-label">
                                                Project Name <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="project_name" class="form-control"
                                                value="{{ old('project_name', $project->project_name ?? '') }}">
                                            @error('project_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        {{-- Attachment --}} 
                                        <!--<div class="col-md-4 mb-3 form-inline-group">-->
                                        <!--    <label class="form-label">Attach File</label>-->
                                        <!--    <input type="file" name="attachment" class="form-control">-->

                                        <!--    {{-- Show existing file in edit --}}-->
                                        <!--    @if (isset($project) && $project->attachment)-->
                                        <!--        <div class="mt-2">-->
                                        <!--            <a href="{{ asset('uploads/projects/' . $project->attachment) }}"-->
                                        <!--                target="_blank" class="btn btn-sm btn-info">-->
                                        <!--                View Existing File-->
                                        <!--            </a>-->
                                        <!--        </div>-->
                                        <!--    @endif-->
                                        <!--</div>-->
                                        
                                        <div class="col-md-4 mb-3 form-inline-group">
                                            <label class="form-label">Attach File</label>
                                            <input type="file" name="attachment" class="form-control">
                                        
                                            {{-- Show existing file --}}
                                            @if (isset($project) && $project->attachment)
                                                @php
                                                    $file = $project->attachment;
                                                    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                                    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                                                @endphp
                                        
                                                <div class="mt-2">
                                        
                                                    {{-- If Image --}}
                                                    <!--@if (in_array($extension, $imageExtensions))-->
                                                        <div class="mb-2">
                                                            <img src="{{ asset('uploads/projects/' . $file) }}"
                                                                 alt="Project Image"
                                                                 style="width:40px; height:40px; object-fit:cover; border-radius:8px; border:1px solid #ccc;">
                                                        </div>
                                                    <!--@endif-->
                                        
                                                    {{-- View File Button --}}
                                                    <a href="{{ asset('uploads/projects/' . $file) }}"
                                                       target="_blank"
                                                       class="btn btn-sm btn-info">
                                                        View File
                                                    </a>
                                                </div>
                                            @endif
                                        </div>

                                    </div>

                                    {{-- Submit Button --}}
                                    <div class="mt-3 text-end">
                                        <button type="submit" class="btn btn-success px-4">
                                            {{ isset($project) ? 'Update Project' : 'Submit' }}
                                        </button>
                                    </div>


                                </form>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
