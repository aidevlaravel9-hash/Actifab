<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">

                <div class="navbar-brand-box horizontal-logo">
                    <a href="{{ route('dashboard') }}" class="logo logo-dark">
                        <span class="logo-lg d-flex align-items-center">
                            <img src="{{ asset('assets/images/logo.png') }}" alt="" height="30" class="me-3">
                            <p class="mb-0" style="font-weight:bold" >ACTiMOD 2.0 PANELBOARD DESIGN</p>
                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger"
                    id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>

            </div>

            <div class="d-flex align-items-center">

                <div class="dropdown ms-sm-3 header-item topbar-user">
                    <button type="button" class="btn shadow-none" id="page-header-user-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user"
                                src="{{ asset('assets/images/users/undraw_profile.webp') }}" alt="Header Avatar">

                            @php
                                $admin = auth('web')->user();
                                $user = auth('user')->user();
                                $authUser = $user ?? $admin;
                            @endphp

                            <span class="text-start ms-xl-2">
                                <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">
                                    @php
                                        $authUser = auth('user')->user() ?? auth('web')->user();
                                    @endphp

                                    @if ($authUser)
                                        Welcome, {{ $authUser->contact_person ?? $authUser->full_name }}
                                    @else
                                        Welcome, Guest
                                    @endif
                                </span>


                                @if ($admin)
                                    @php
                                        $role = App\Models\User::select('users.id', 'roles.name')
                                            ->join('roles', 'users.role_id', '=', 'roles.id')
                                            ->where('users.id', $admin->id)
                                            ->first();
                                    @endphp

                                    <span class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text">
                                        {{ $role->name ?? 'Admin' }}
                                    </span>
                                @elseif ($user)
                                    <span class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text">
                                        User
                                    </span>
                                @endif


                            </span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <h6 class="dropdown-header">
                            Welcome
                            @if ($admin)
                                {{ $admin->full_name }}
                            @elseif ($user)
                                {{ $user->full_name }}
                            @else
                                Guest
                            @endif

                        </h6>
                        <a class="dropdown-item" href="{{ route('user.detail') }}"><i
                                class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span
                                class="align-middle">Profile</span></a>
                        <form method="POST" action="{{ route('user.logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item border-0 bg-transparent w-100 text-start">
                                <i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i>
                                <span class="align-middle">Logout</span>
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
