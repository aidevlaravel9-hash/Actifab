@extends('layouts.app')
@section('title', 'Admin - Page Not Found')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <div class="row justify-content-center mt-5">
                    <div class="col-lg-8 text-center">
                        <div class="card shadow-sm">
                            <div class="card-body py-5">
                                <h1 class="text-danger fw-bold mb-3">404 - Admin Page Not Found</h1>
                                <p class="text-muted mb-4">Sorry, the page you are looking for does not exist in the admin
                                    panel.</p>
                                <a href="{{ route('home') }}" class="btn btn-success">Go back to Dashboard</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
