<div class="app-menu navbar-menu">
    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu"></span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?php echo e(request()->routeIs('manager.dashboard') ? 'active' : ''); ?>"
                        href="<?php echo e(route('manager.dashboard')); ?>">
                        <i class="mdi mdi-view-dashboard-outline"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="sidebar-background"></div>
<div class="vertical-overlay"></div>
<?php /**PATH C:\laragon\www\Actifab\resources\views/common/manager/sidebar.blade.php ENDPATH**/ ?>