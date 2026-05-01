@extends('layouts.app')

@section('title', 'Edit Testimonial')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Alert Messages --}}
                @include('common.alert')

                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Edit Testimonial</h4>
                            <div class="page-title-right">
                                <a href="{{ route('testimonial.index') }}"
                                    class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                                    Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="live-preview">
                                    <form method="POST" onsubmit="return validateFile()"
                                        action="{{ route('testimonial.update') }}" enctype="multipart/form-data">
                                        @csrf

                                        <input type="hidden" name="id" value="{{ $data->id }}">
                                        <div class="row gy-4">

                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    Name <span style="color:red;">*</span>
                                                    <input type="text" class="form-control" placeholder="Enter Name"
                                                        name="name" id="name" autocomplete="off"
                                                        value="{{ $data->name }}" required>
                                                </div>
                                                @error('name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    Designation <span style="color:red;">*</span>
                                                    <input type="text" class="form-control"
                                                        placeholder="Enter Designation" name="designation" id="designation"
                                                        autocomplete="off" value="{{ $data->designation }}" required>
                                                </div>
                                                @error('designation')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-lg-3 col-md-6">
                                                <div>
                                                    Photo <span style="color:red;"></span>
                                                    <input type="file" class="form-control" name="photo" id="strPhoto"
                                                        value="{{ $data->photo }}">
                                                    <input type="hidden" name="hiddenPhoto" class="form-control"
                                                        value="{{ old('photo') ? old('photo') : $data->photo }}"
                                                        id="hiddenPhoto">
                                                </div>
                                            </div>
                                            <div class="col-lg-1 col-md-6">
                                                <div id="viewimg">
                                                    @if ($data->photo)
                                                        <img src="{{ asset('uploads/testimonial') . '/' . $data->photo }}"
                                                            alt="" height="70" width="70">
                                                    @else
                                                        <img src="{{ asset('assets/images/noimage.png') }}"
                                                            style="width: 50px;height: 50px;">
                                                    @endif
                                                    @error('photo')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-lg-12 col-md-6">
                                                <span style="color:red;"></span> Description
                                                <textarea class="form-control ckeditor" name="description" id="description" rows="6">{{ $data->description }}</textarea>
                                                @error('description')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                        </div>
                                        <div class="card-footer mt-5" style="float: right;">
                                            <button type="submit"
                                                class="btn btn-primary btn-user float-right mb-3 mx-2">Update</button>
                                            <a class="btn btn-primary float-right mr-3 mb-3 mx-2"
                                                href="{{ route('testimonial.index') }}">Cancel</a>
                                        </div>
                                    </form>
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

    <script src="https://cdn.ckeditor.com/4.12.1/standard/ckeditor.js"></script>
    <script>
        function validateFile() {
            var allowedExtension = ['jpeg', 'jpg', 'png', 'webp', ''];
            var fileExtension = document.getElementById('strPhoto').value.split('.').pop().toLowerCase();
            var isValidFile = false;
            var image = document.getElementById('strPhoto').value;

            for (var index in allowedExtension) {

                if (fileExtension === allowedExtension[index]) {
                    isValidFile = true;
                    break;
                }
            }
            if (image != "") {
                if (!isValidFile) {
                    alert('Allowed Extensions are : *.' + allowedExtension.join(', *.'));
                }
                return isValidFile;
            }

            return true;
        }
    </script>

    {{-- Add photo --}}
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#hello').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#strPhoto").change(function() {
            html =
                '<img src="' + readURL(this) +
                '"   id="hello" width="70px" height = "70px" > ';
            $('#viewimg').html(html);
        });
    </script>

@endsection
