<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">

                <li class="menu-title"><span data-key="t-menu"></span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link <?php if(request()->routeIs('home')): ?> <?php echo e('active'); ?> <?php endif; ?>"
                        href="<?php echo e(route('home')); ?>">
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
                                <a href="<?php echo e(route('section.index')); ?>"
                                    class="nav-link <?php echo e(request()->is('admin/section*') ? 'active' : ''); ?>">
                                    <i class="nav-icon fas fa-list"></i>
                                    Section Type
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo e(route('feeder-category.index')); ?>"
                                    class="nav-link <?php echo e(request()->is('admin/feeder-category*') ? 'active' : ''); ?>">
                                    <i class="nav-icon fas fa-list"></i>
                                    Feeder Category
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo e(route('feeder-type.index')); ?>"
                                    class="nav-link <?php echo e(request()->is('admin/feeder-type*') ? 'active' : ''); ?>">
                                    <i class="nav-icon fas fa-cog"></i>
                                    Feeder Type
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo e(route('feeder-sub-type.index')); ?>"
                                    class="nav-link <?php echo e(request()->is('admin/feeder-sub-type*') ? 'active' : ''); ?>">
                                    <i class="nav-icon fas fa-code-branch"></i>
                                    Feeder Sub Type
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo e(route('panel-type.index')); ?>"
                                    class="nav-link <?php echo e(request()->is('admin/panel-type*') ? 'active' : ''); ?>">
                                    <i class="nav-icon fas fa-columns"></i>
                                    Panel Type
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('parts-category.index')); ?>"
                                    class="nav-link <?php echo e(request()->is('admin/parts-category*') ? 'active' : ''); ?>">
                                    <i class="nav-icon fas fa-cogs"></i>
                                    Parts Category
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo e(route('parts-master.index')); ?>"
                                    class="nav-link <?php echo e(request()->is('admin/parts-master*') ? 'active' : ''); ?>">
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
                                <a href="<?php echo e(route('system-rating.index')); ?>"
                                    class="nav-link <?php echo e(request()->is('admin/system-rating*') ? 'active' : ''); ?>">
                                    <i class="nav-icon fas fa-bolt"></i>
                                    System Rating
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo e(route('poles.index')); ?>"
                                    class="nav-link <?php echo e(request()->is('admin/poles*') ? 'active' : ''); ?>">
                                    <i class="nav-icon fas fa-sliders-h"></i>
                                    No of Poles
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo e(route('voltage.index')); ?>"
                                    class="nav-link <?php echo e(request()->is('admin/voltage*') ? 'active' : ''); ?>">
                                    <i class="nav-icon fas fa-bolt"></i>
                                    Operating Voltage
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo e(route('form-type.index')); ?>"
                                    class="nav-link <?php echo e(request()->is('admin/form-type*') ? 'active' : ''); ?>">
                                    <i class="nav-icon fas fa-file-alt"></i>
                                    Form Type
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo e(route('panel-access.index')); ?>"
                                    class="nav-link <?php echo e(request()->is('admin/panel-access*') ? 'active' : ''); ?>">
                                    <i class="nav-icon fas fa-lock"></i>
                                    Panel Access
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo e(route('panel-board-colour.index')); ?>"
                                    class="nav-link <?php echo e(request()->is('admin/panel-board-colour*') ? 'active' : ''); ?>">
                                    <i class="nav-icon fas fa-palette"></i>
                                    Panel Board Colour
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo e(route('ip-protection.index')); ?>"
                                    class="nav-link <?php echo e(request()->is('admin/ip-protection*') ? 'active' : ''); ?>">
                                    <i class="nav-icon fas fa-shield-alt"></i>
                                    IP Protection
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo e(route('lock-system.index')); ?>"
                                    class="nav-link <?php echo e(request()->is('admin/lock-system*') ? 'active' : ''); ?>">
                                    <i class="nav-icon fas fa-lock"></i>
                                    Lock System
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo e(route('busbar-position.index')); ?>"
                                    class="nav-link <?php echo e(request()->is('admin/busbar-position*') ? 'active' : ''); ?>">
                                    <i class="nav-icon fas fa-bars"></i>
                                    Busbar Position
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo e(route('gland-plate-thickness.index')); ?>"
                                    class="nav-link <?php echo e(request()->is('admin/gland-plate-thickness*') ? 'active' : ''); ?>">
                                    <i class="nav-icon fas fa-border-style"></i>
                                    Gland Plate Thickness
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo e(route('outgoing-cable-position.index')); ?>"
                                    class="nav-link <?php echo e(request()->is('admin/outgoing-cable-position*') ? 'active' : ''); ?>">
                                    <i class="nav-icon fas fa-external-link-alt"></i>
                                    Outgoing Cable Position
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo e(route('plinth-type.index')); ?>"
                                    class="nav-link <?php echo e(request()->is('admin/plinth-type*') ? 'active' : ''); ?>">
                                    <i class="nav-icon fas fa-border-style"></i>
                                    Plinth Type
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo e(route('certification.index')); ?>"
                                    class="nav-link <?php echo e(request()->is('admin/certification*') ? 'active' : ''); ?>">
                                    <i class="nav-icon fas fa-certificate"></i>
                                    Certification
                                </a>
                            </li>

                            

                        </ul>


                    </div>
                </li>
                
                 <li class="nav-item">
                    <a class="nav-link" href="#sidebarMore" data-bs-toggle="collapse" role="button"
                        aria-expanded="true" aria-controls="sidebarMore">
                        <i class="fa fa-list text-white"></i>Project Submit&nbsp; </a>
                    <div class="menu-dropdown collapse show" id="sidebarMore" style="">
                        <ul class="nav nav-sm flex-column">

                            <li class="nav-item">
                                <a href="<?php echo e(route('projects.inDevelopment')); ?>"
                                    class="nav-link <?php echo e(request()->is('projects/in-development') ? 'active' : ''); ?>">
                                    <i class="nav-icon fas fa-list"></i>
                                    In Development
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo e(route('projects.completed')); ?>"
                                    class="nav-link <?php echo e(request()->is('projects/completed') ? 'active' : ''); ?>">
                                    <i class="nav-icon fas fa-list"></i>
                                    Completed
                                </a>
                            </li>
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
<?php /**PATH /home1/getdemo/Actifab/resources/views/common/admin/sidebar.blade.php ENDPATH**/ ?>