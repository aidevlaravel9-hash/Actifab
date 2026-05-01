@extends('layouts.user')

@section('title', 'User Profile')

@section('content')

    <div class="main-content">

        {{-- Alert Messages --}}
        @include('common.alert')

        <div class="page-content">
            <div class="container-fluid mt-4">

                <!-- Profile Header -->
                <div class="profile-foreground position-relative mx-n4 mt-n4">
                    <div class="profile-wid-bg">
                        <img src="{{ asset('assets/images/profile-bg.jpg') }}" class="profile-wid-img" />
                    </div>
                </div>

                <div class="pt-4 mb-4 pb-3">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="avatar-lg">
                                <img src="{{ asset('assets/images/users/undraw_profile.webp') }}"
                                    class="img-thumbnail rounded-circle" />
                            </div>
                        </div>

                        <div class="col">
                            <h3 class="text-white mb-1">
                                {{ $user->company_name }}
                            </h3>
                            <p class="text-white-75 mb-0">
                                User
                            </p>
                        </div>

                        <div class="col-auto">
                            <a href="{{ route('user.edit') }}" class="btn btn-light">
                                <i class="ri-edit-box-line align-bottom"></i> Edit Profile
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Profile Details -->
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Company Information</h5>

                                <table class="table table-borderless">
                                    <tr>
                                        <th width="40%">Company Name :</th>
                                        <td>{{ $user->company_name }}</td>
                                    </tr>

                                    <tr>
                                        <th>Contact Person :</th>
                                        <td>{{ $user->contact_person }}</td>
                                    </tr>

                                    <tr>
                                        <th>Email :</th>
                                        <td>{{ $user->email }}</td>
                                    </tr>

                                    <tr>
                                        <th>Mobile :</th>
                                        <td>{{ $user->mobile }}</td>
                                    </tr>

                                    <tr>
                                        <th>Address :</th>
                                        <td>{{ $user->Address }}</td>
                                    </tr>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

@endsection
