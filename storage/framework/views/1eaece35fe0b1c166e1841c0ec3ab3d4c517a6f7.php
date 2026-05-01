<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">

                <div class="navbar-brand-box horizontal-logo">
                    <a href="<?php echo e(route('home')); ?>" class="logo logo-dark">
                        <span class="logo-lg">
                            <img src="<?php echo e(asset('assets/images/logo.png')); ?>" alt="" height="80">
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
                                src="<?php echo e(asset('assets/images/users/undraw_profile.webp')); ?>" alt="Header Avatar">
                            <span class="text-start ms-xl-2">
                                <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">
                                    <?php if(auth()->guard()->check()): ?>
                                        <span>Welcome, <?php echo e(auth()->user()->full_name); ?></span>
                                    <?php else: ?>
                                        <span>Welcome, Guest</span>
                                    <?php endif; ?>
                                </span>


                                <?php if(auth()->guard()->check()): ?>
                                    <?php
                                        $role = App\Models\User::select('users.id', 'roles.name')
                                            ->join('roles', 'users.role_id', '=', 'roles.id')
                                            ->where('users.id', auth()->id())
                                            ->first();
                                    ?>
                                    <span class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text">
                                        <?php echo e($role->name ?? 'User'); ?>

                                    </span>
                                <?php endif; ?>

                            </span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <h6 class="dropdown-header">
                            Welcome
                            <?php if(auth()->guard()->check()): ?>
                                <?php echo e(auth()->user()->full_name); ?>

                            <?php else: ?>
                                Guest
                            <?php endif; ?>
                        </h6>
                        <a class="dropdown-item" href="<?php echo e(route('profile.detail')); ?>"><i
                                class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span
                                class="align-middle">Profile</span></a>
                        <a class="dropdown-item" href="<?php echo e(route('admin.logout')); ?>"><i
                                class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span
                                class="align-middle" data-key="t-logout">Logout</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<?php /**PATH C:\laragon\www\Actifab\resources\views/common/admin/header.blade.php ENDPATH**/ ?>