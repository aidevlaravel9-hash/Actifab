<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">
                <div class="navbar-brand-box horizontal-logo">
                    <a href="<?php echo e(route('manager.dashboard')); ?>" class="logo logo-dark">
                        <span class="logo-lg d-flex align-items-center">
                            <img src="<?php echo e(asset('assets/images/logo.png')); ?>" alt="" height="30" class="me-3">
                            <p class="mb-0" style="font-weight:bold">ACTiMOD 2.0 PANELBOARD DESIGN</p>
                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger"
                    id="topnav-hamburger-icon">
                    <span class="hamburger-icon"><span></span><span></span><span></span></span>
                </button>
            </div>

            <div class="d-flex align-items-center">
                <div class="dropdown ms-sm-3 header-item topbar-user">
                    <button type="button" class="btn shadow-none" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user"
                                src="<?php echo e(asset('assets/images/users/undraw_profile.webp')); ?>" alt="Header Avatar">
                            <span class="text-start ms-xl-2">
                                <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">
                                    Welcome, <?php echo e(auth('manager')->user()->first_name ?? 'Manager'); ?>

                                </span>
                                <span class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text">Manager</span>
                            </span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <h6 class="dropdown-header">Welcome <?php echo e(auth('manager')->user()->first_name ?? 'Manager'); ?></h6>
                        <form method="POST" action="<?php echo e(route('manager.logout')); ?>">
                            <?php echo csrf_field(); ?>
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
<?php /**PATH C:\laragon\www\Actifab\resources\views/common/manager/header.blade.php ENDPATH**/ ?>