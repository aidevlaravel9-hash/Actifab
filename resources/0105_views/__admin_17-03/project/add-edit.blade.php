@extends('layouts.app')

@section('title', isset($data) ? 'Edit Project' : 'Add Project')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                @include('common.alert')

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">{{ isset($data) ? 'Edit Project' : 'Add Project' }}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ isset($data) ? route('project.update') : route('project.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @if (isset($data))
                                <input type="hidden" name="edit_id" value="{{ $data->project_id }}">
                            @endif

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Project Name <span style="color:red;">*</span></label>
                                    <textarea name="project_name" class="form-control">{{ old('project_name', $data->project_name ?? '') }}</textarea>
                                    @if ($errors->has('project_name'))
                                        <span class="text-danger">{{ $errors->first('project_name') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Creater Email <span style="color:red;">*</span></label>
                                    <input type="email" name="creater_email" class="form-control"
                                        value="{{ old('creater_email', $data->creater_email ?? '') }}">
                                    @if ($errors->has('creater_email'))
                                        <span class="text-danger">{{ $errors->first('creater_email') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Creater Contact <span style="color:red;">*</span></label>
                                    <input type="text" name="creater_contact" class="form-control"
                                        value="{{ old('creater_contact', $data->creater_contact ?? '') }}">
                                    @if ($errors->has('creater_contact'))
                                        <span class="text-danger">{{ $errors->first('creater_contact') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Created By <span style="color:red;">*</span></label>
                                    <input type="number" name="created_by" class="form-control"
                                        value="{{ old('created_by', $data->created_by ?? '') }}">
                                    @if ($errors->has('created_by'))
                                        <span class="text-danger">{{ $errors->first('created_by') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Customer Name <span style="color:red;">*</span></label>
                                    <input type="text" name="customer_name" class="form-control"
                                        value="{{ old('customer_name', $data->customer_name ?? '') }}">
                                    @if ($errors->has('customer_name'))
                                        <span class="text-danger">{{ $errors->first('customer_name') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Customer Email <span style="color:red;">*</span></label>
                                    <input type="email" name="customer_email" class="form-control"
                                        value="{{ old('customer_email', $data->customer_email ?? '') }}">
                                    @if ($errors->has('customer_email'))
                                        <span class="text-danger">{{ $errors->first('customer_email') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Customer Address <span style="color:red;">*</span></label>
                                    <textarea name="customer_address" class="form-control">{{ old('customer_address', $data->customer_address ?? '') }}</textarea>
                                    @if ($errors->has('customer_address'))
                                        <span class="text-danger">{{ $errors->first('customer_address') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Contact Person <span style="color:red;">*</span></label>
                                    <input type="text" name="contact_person" class="form-control"
                                        value="{{ old('contact_person', $data->contact_person ?? '') }}">
                                    @if ($errors->has('contact_person'))
                                        <span class="text-danger">{{ $errors->first('contact_person') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Contact Mobile <span style="color:red;">*</span></label>
                                    <input type="text" name="contact_person_mobile" class="form-control"
                                        value="{{ old('contact_person_mobile', $data->contact_person_mobile ?? '') }}">
                                    @if ($errors->has('contact_person_mobile'))
                                        <span class="text-danger">{{ $errors->first('contact_person_mobile') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Contact Email <span style="color:red;">*</span></label>
                                    <input type="email" name="contact_person_email" class="form-control"
                                        value="{{ old('contact_person_email', $data->contact_person_email ?? '') }}">
                                    @if ($errors->has('contact_person_email'))
                                        <span class="text-danger">{{ $errors->first('contact_person_email') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Inquiry Date <span style="color:red;">*</span></label>
                                    <input type="date" name="inquiry_date" class="form-control"
                                        value="{{ old('inquiry_date', $data->inquiry_date ?? '') }}">
                                    @if ($errors->has('inquiry_date'))
                                        <span class="text-danger">{{ $errors->first('inquiry_date') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Attach File</label>
                                    <input type="file" name="attach_files" class="form-control">
                                    @if (isset($data->attach_files))
                                        <small class="d-block">Current File: <a
                                                href="{{ asset('uploads/projects/' . $data->attach_files) }}"
                                                target="_blank">View</a></small>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Status</label>
                                    <select name="iStatus" class="form-control">
                                        <option value="1" {{ isset($data) && $data->iStatus == 1 ? 'selected' : '' }}>
                                            Active</option>
                                        <option value="0" {{ isset($data) && $data->iStatus == 0 ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <button type="submit"
                                class="btn btn-primary">{{ isset($data) ? 'Update' : 'Submit' }}</button>
                            <a href="{{ route('project.index') }}" class="btn btn-secondary">Back</a>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
