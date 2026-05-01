@extends('layouts.front')

@section('content')
    <main class="main">

        <!-- Page Header -->
        <section class="page-header" style="background: linear-gradient(135deg, #2a7d3e, #8bc34a)">
            <div class="header-overlay"></div>
            <div class="header-content">
                <h1 class="fw-bold mb-3">404 Error</h1>
                <nav class="breadcrumb justify-content-center mb-0">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item">
                            <a href="{{ route('front.index') }}" class="text-white text-decoration-none">Home</a>
                        </li>
                        <li class="list-inline-item text-white">/</li>
                        <li class="list-inline-item text-white">404 Error</li>
                    </ul>
                </nav>
            </div>
        </section>
        <!-- End Page Header -->


        <!-- Error Area -->
        <section class="error-area py-5" style="background-color: #f9f9f9;">
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-md-8 col-lg-6">
                        <div class="error-wrapper p-4 rounded shadow-sm bg-white">
                            <div class="error-img mb-4">
                                <img src="{{ asset('assets/img/error/02.jpg') }}" alt="404 Error" class="img-fluid"
                                    style="max-width: 250px;">
                            </div>
                            <h2 class="fw-bold text-dark mb-3">Oops... Page Not Found!</h2>
                            <p class="text-muted mb-4">The page you’re looking for doesn’t exist or has been removed.</p>
                            <a href="{{ route('front.index') }}" class="btn-primary-2025 px-4 py-2">
                                <i class="bi bi-house-door me-2"></i> Go Back Home
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Error Area -->

    </main>
@endsection
