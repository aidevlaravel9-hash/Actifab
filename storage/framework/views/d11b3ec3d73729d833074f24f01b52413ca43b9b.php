<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">

                <li class="menu-title"><span data-key="t-menu"></span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link <?php if(request()->routeIs('dashboard')): ?> <?php echo e('active'); ?> <?php endif; ?>"
                        href="<?php echo e(route('dashboard')); ?>">
                        <i class="mdi mdi-view-dashboard-outline"></i>
                        <span data-key="t-dashboards">Dashboards</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link menu-link <?php echo e(request()->routeIs('CreateProject') ? 'active' : ''); ?>"
                        href="<?php echo e(route('CreateProject')); ?>">
                        <i class="mdi mdi-plus-box-outline"></i>
                        <span>Create New Project</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link <?php echo e(request()->routeIs('indexProject') ? 'active' : ''); ?>"
                        href="<?php echo e(route('indexProject')); ?>">
                        <i class="mdi mdi-briefcase-outline"></i>
                        <span>Manage Projects</span>
                    </a>
                </li>

                

                <li class="nav-item">
                    <a class="nav-link menu-link <?php echo e(request()->routeIs('actimod') ? 'active' : ''); ?>" href="#">
                        <i class="mdi mdi-information-outline"></i>
                        <span>Know ACTiMOD 2.0</span>
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
<?php /**PATH /home1/getdemo/Actifab/resources/views/common/user/sidebar.blade.php ENDPATH**/ ?>