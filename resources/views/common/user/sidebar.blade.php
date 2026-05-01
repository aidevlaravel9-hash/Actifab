<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">

                <li class="menu-title"><span data-key="t-menu"></span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link @if (request()->routeIs('dashboard')) {{ 'active' }} @endif"
                        href="{{ route('dashboard') }}">
                        <i class="mdi mdi-view-dashboard-outline"></i>
                        <span data-key="t-dashboards">Dashboards</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('CreateProject') ? 'active' : '' }}"
                        href="{{ route('CreateProject') }}">
                        <i class="mdi mdi-plus-box-outline"></i>
                        <span>Create New Project</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('indexProject') ? 'active' : '' }}"
                        href="{{ route('indexProject') }}">
                        <i class="mdi mdi-briefcase-outline"></i>
                        <span>Manage Projects</span>
                    </a>
                </li>

                

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('actimod') ? 'active' : '' }}" href="#">
                        <i class="mdi mdi-information-outline"></i>
                        <span>Know ACTiMOD 2.0</span>
                    </a>
                </li>

                {{--  <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('AddPanelBoard') ? 'active' : '' }}"
                        href="{{ route('AddPanelBoard') }}">
                        <i class="mdi mdi-view-grid-plus-outline"></i>
                        <span>Add Panel Board</span>
                    </a>
                </li>  --}}


            </ul>


        </div>
        </li>
    </div>
    <!-- Sidebar -->
</div>

<div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
