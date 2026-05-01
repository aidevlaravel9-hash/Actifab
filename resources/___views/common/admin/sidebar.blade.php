<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">

                <li class="menu-title"><span data-key="t-menu"></span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link @if (request()->routeIs('home')) {{ 'active' }} @endif"
                        href="{{ route('home') }}">
                        <i class="mdi mdi-view-dashboard-outline"></i>
                        <span data-key="t-dashboards">Dashboards</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#sidebarMore" data-bs-toggle="collapse" role="button"
                        aria-expanded="true" aria-controls="sidebarMore">
                        <i class="fa fa-list text-white"></i>Panel Master&nbsp; </a>
                    <div class="menu-dropdown collapse show" id="sidebarMore" style="">
                        <ul class="nav nav-sm flex-column">


                            <li class="nav-item">
                                <a href="{{ route('feeder-category.index') }}"
                                    class="nav-link {{ request()->is('admin/feeder-category*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-list"></i>
                                    Feeder Category
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('feeder-type.index') }}"
                                    class="nav-link {{ request()->is('admin/feeder-type*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-cog"></i>
                                    Feeder Type
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('feeder-sub-type.index') }}"
                                    class="nav-link {{ request()->is('admin/feeder-sub-type*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-code-branch"></i>
                                    Feeder Sub Type
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('panel-type.index') }}"
                                    class="nav-link {{ request()->is('admin/panel-type*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-columns"></i>
                                    Panel Type
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('parts-category.index') }}"
                                    class="nav-link {{ request()->is('admin/parts-category*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-cogs"></i>
                                    Parts Category
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('parts-master.index') }}"
                                    class="nav-link {{ request()->is('admin/parts-master*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-tools"></i>
                                    Parts Master
                                </a>
                            </li>

                            



                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#sidebarMore" data-bs-toggle="collapse" role="button"
                        aria-expanded="true" aria-controls="sidebarMore">
                        <i class="fa fa-list text-white"></i> Master&nbsp; Entry</a>
                    <div class="menu-dropdown collapse show" id="sidebarMore" style="">
                        <ul class="nav nav-sm flex-column">
                
                <li class="nav-item">
                                <a href="{{ route('system-rating.index') }}"
                                    class="nav-link {{ request()->is('admin/system-rating*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-bolt"></i>
                                    System Rating
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('poles.index') }}"
                                    class="nav-link {{ request()->is('admin/poles*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-sliders-h"></i>
                                    No of Poles
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('voltage.index') }}"
                                    class="nav-link {{ request()->is('admin/voltage*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-bolt"></i>
                                    Operating Voltage
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('form-type.index') }}"
                                    class="nav-link {{ request()->is('admin/form-type*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-file-alt"></i>
                                    Form Type
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('panel-access.index') }}"
                                    class="nav-link {{ request()->is('admin/panel-access*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-lock"></i>
                                    Panel Access
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('panel-board-colour.index') }}"
                                    class="nav-link {{ request()->is('admin/panel-board-colour*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-palette"></i>
                                    Panel Board Colour
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('ip-protection.index') }}"
                                    class="nav-link {{ request()->is('admin/ip-protection*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-shield-alt"></i>
                                    IP Protection
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('lock-system.index') }}"
                                    class="nav-link {{ request()->is('admin/lock-system*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-lock"></i>
                                    Lock System
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('busbar-position.index') }}"
                                    class="nav-link {{ request()->is('admin/busbar-position*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-bars"></i>
                                    Busbar Position
                                </a>
                            </li>

                <li class="nav-item">
                    <a href="{{ route('gland-plate-thickness.index') }}"
                        class="nav-link {{ request()->is('admin/gland-plate-thickness*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-border-style"></i>
                        Gland Plate Thickness
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('outgoing-cable-position.index') }}"
                        class="nav-link {{ request()->is('admin/outgoing-cable-position*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-external-link-alt"></i>
                        Outgoing Cable Position
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('plinth-type.index') }}"
                        class="nav-link {{ request()->is('admin/plinth-type*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-border-style"></i>
                        Plinth Type
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('certification.index') }}"
                        class="nav-link {{ request()->is('admin/certification*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-certificate"></i>
                        Certification
                    </a>
                </li>

                {{--  <li class="nav-item">
                    <a href="{{ route('project.index') }}"
                        class="nav-link {{ request()->is('admin/project*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-project-diagram"></i>
                        Project
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
