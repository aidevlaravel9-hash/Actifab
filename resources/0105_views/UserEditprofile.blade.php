@extends('layouts.user')

@section('title', 'User Edit Profile')

@section('content')

    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid mt-4">

                @include('common.alert')

                <!-- Profile Background -->
                <div class="position-relative mx-n4 mt-n4">
                    <div class="profile-wid-bg profile-setting-img">
                        <img src="{{ asset('assets/images/profile-bg.jpg') }}" class="profile-wid-img" alt="">
                    </div>
                </div>

                <div class="row">

                    <!-- Left Profile Card -->
                    <div class="col-xxl-3">
                        <div class="card mt-n5">
                            <div class="card-body text-center p-4">

                                <div class="profile-user mb-4">
                                    <img src="{{ asset('assets/images/users/undraw_profile.webp') }}"
                                        class="rounded-circle avatar-xl img-thumbnail">
                                </div>

                                <h5 class="mb-1">{{ $user->company_name }}</h5>
                                <p class="text-muted mb-0">
                                    User
                                </p>

                            </div>
                        </div>
                    </div>

                    <!-- Right Edit Section -->
                    <div class="col-xxl-9">
                        <div class="card mt-xxl-n5">

                            <div class="card-header">
                                <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails">
                                            Personal Details
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#changePassword">
                                            Change Password
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="card-body p-4">
                                <div class="tab-content">

                                    <!-- PERSONAL DETAILS -->
                                    <div class="tab-pane active" id="personalDetails">

                                        <form action="{{ route('user.update') }}" method="POST">
                                            @csrf

                                            <div class="row">

                                                <!-- Company Name -->
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">
                                                            <span class="text-danger">*</span>
                                                            Company Name
                                                        </label>

                                                        <input type="text" name="company_name"
                                                            class="form-control @error('company_name') is-invalid @enderror"
                                                            value="{{ old('company_name', $user->company_name) }}" required>

                                                        @error('company_name')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <!-- Contact Person -->
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">
                                                            <span class="text-danger">*</span>
                                                            Contact Person
                                                        </label>

                                                        <input type="text" name="contact_person"
                                                            class="form-control @error('contact_person') is-invalid @enderror"
                                                            value="{{ old('contact_person', $user->contact_person) }}"
                                                            required>

                                                        @error('contact_person')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <!-- Email -->
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">
                                                            <span class="text-danger">*</span>
                                                            Email
                                                        </label>

                                                        <input type="email" name="email"
                                                            class="form-control @error('email') is-invalid @enderror"
                                                            value="{{ old('email', $user->email) }}" required>

                                                        @error('email')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <!-- Mobile -->
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">
                                                            <span class="text-danger">*</span>
                                                            Mobile
                                                        </label>

                                                        <input type="text" name="mobile"
                                                            class="form-control @error('mobile') is-invalid @enderror"
                                                            value="{{ old('mobile', $user->mobile) }}" maxlength="10"
                                                            onkeyup="this.value=this.value.replace(/\D/g,'')" required>

                                                        @error('mobile')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <!-- Address -->
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">
                                                            Address
                                                        </label>

                                                        <textarea name="Address" class="form-control @error('Address') is-invalid @enderror" rows="3">{{ old('Address', $user->Address) }}</textarea>

                                                        @error('Address')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <!-- Submit -->
                                                <div class="col-lg-12 text-end">
                                                    <button type="submit" class="btn btn-primary">
                                                        Update Profile
                                                    </button>
                                                </div>

                                            </div>
                                        </form>

                                    </div>

                                    <!-- CHANGE PASSWORD -->
                                    <div class="tab-pane" id="changePassword">

                                        <form action="{{ route('user.change-password') }}" method="POST">
                                            @csrf

                                            <div class="row g-3">

                                                <div class="col-lg-4">
                                                    <label class="form-label">
                                                        <span class="text-danger">*</span>
                                                        Current Password
                                                    </label>
                                                    <input type="password" name="current_password" class="form-control"
                                                        required>
                                                </div>

                                                <div class="col-lg-4">
                                                    <label class="form-label">
                                                        <span class="text-danger">*</span>
                                                        New Password
                                                    </label>
                                                    <input type="password" name="new_password" class="form-control"
                                                        minlength="4" required>
                                                </div>

                                                <div class="col-lg-4">
                                                    <label class="form-label">
                                                        <span class="text-danger">*</span>
                                                        Confirm Password
                                                    </label>
                                                    <input type="password" name="new_confirm_password"
                                                        class="form-control" required>
                                                </div>

                                                <div class="col-lg-12 text-end">
                                                    <button type="submit" class="btn btn-success">
                                                        Change Password
                                                    </button>
                                                </div>

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

    </div>

@endsection
