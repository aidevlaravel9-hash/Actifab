@extends('layouts.user')

@section('title', 'Create Project')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                @include('common.alert')

                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Create Project</h4>
                            <div class="page-title-right">
                                <a href="{{ route('indexProject') }}" class="btn btn-sm btn-primary shadow-sm">
                                    <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">

                                <form method="POST" action="{{ route('storeProject') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">

                                        {{-- Inquiry No --}}
                                        <div class="col-md-6 mb-4">
                                            <label class="form-label">Inquiry No</label>
                                            <input type="text" class="form-control" value="{{ $inquiryNo }}" readonly>
                                        </div>

                                        {{-- Inquiry Date --}}
                                        <div class="col-md-6 mb-4">
                                            <label class="form-label">Inquiry Date</label>
                                            <input type="date" name="inquiry_date" class="form-control"
                                                value="{{ date('Y-m-d') }}" readonly>
                                        </div>

                                        {{-- Created By --}}


                                        {{-- Project Name --}}
                                        <div class="col-md-6 mb-4">
                                            <label class="form-label">Project Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="project_name" class="form-control"
                                                value="{{ old('project_name') }}">
                                            @error('project_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- Customer Name --}}
                                        <div class="col-md-6 mb-4">
                                            <label class="form-label">Customer Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="customer_name" class="form-control"
                                                value="{{ old('customer_name') }}">
                                            @error('customer_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- Customer Email --}}
                                        <div class="col-md-6 mb-4">
                                            <label class="form-label">Customer Email</label>
                                            <input type="email" name="customer_email" class="form-control"
                                                value="{{ old('customer_email') }}">
                                        </div>

                                        {{-- Customer Address --}}
                                        <div class="col-md-6 mb-4">
                                            <label class="form-label">Customer Address</label>
                                            <textarea name="customer_address" class="form-control" rows="2">{{ old('customer_address') }}</textarea>
                                        </div>

                                        {{-- Contact Person Name --}}
                                        <div class="col-md-6 mb-4">
                                            <label class="form-label">Contact Person Name</label>
                                            <input type="text" name="contact_person_name" class="form-control"
                                                value="{{ old('contact_person_name') }}">
                                        </div>

                                        {{-- Contact Person Mobile --}}
                                        <div class="col-md-6 mb-4">
                                            <label class="form-label">Contact Person Mobile</label>
                                            <input type="text" name="contact_person_mobile" class="form-control"
                                                value="{{ old('contact_person_mobile') }}">
                                        </div>

                                        {{-- Contact Person Email --}}
                                        <div class="col-md-6 mb-4">
                                            <label class="form-label">Contact Person Email</label>
                                            <input type="email" name="contact_person_email" class="form-control"
                                                value="{{ old('contact_person_email') }}">
                                        </div>

                                        {{-- Attachment --}}
                                        <div class="col-md-6 mb-4">
                                            <label class="form-label">Attach File</label>
                                            <input type="file" name="attachment" class="form-control">
                                        </div>

                                    </div>

                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-success px-4">
                                            Submit
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
