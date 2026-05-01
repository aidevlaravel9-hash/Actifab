<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f6fa;
        }

        /* Top Navbar */
        .topbar {
            background: #2a2e30;
            padding: 0 20px;
        }

        .topbar .navbar-brand img {
            max-height: 34px;
        }

        .topbar .nav-link {
            color: #fff !important;
            padding: 16px 18px;
            font-size: 14px;
        }

        .topbar .nav-link:hover,
        .topbar .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
        }

        /* Cards */
        .card-box {
            border-radius: 8px;
            border: 1px solid #2f80ed;
            box-shadow: none;
        }

        .card-box h2 {
            font-weight: 700;
        }

        .card-box small {
            letter-spacing: 0.5px;
        }
    </style>

    @stack('styles')
</head>

<body>

    {{-- TOP NAVBAR --}}
    <nav class="navbar navbar-expand-lg topbar">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Acti Fab">
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#topMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="topMenu">
            <ul class="navbar-nav ml-auto">

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        href="{{ route('dashboard') }}">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}" href="#">
                        Manage Projects
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('indexProject') ? 'active' : '' }}"
                        href="{{ route('indexProject') }}">
                        Create Project
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('actimod') ? 'active' : '' }}" href="#">
                        Know ACTiMOD 2.0
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('AddPanelBoard') ? 'active' : '' }}"
                        href="{{ route('AddPanelBoard') }}">
                        Add Panel Board
                    </a>
                </li>

            </ul>
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <div class="container-fluid p-4">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
