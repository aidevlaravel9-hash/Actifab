<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manager Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
</head>

<body
    style="background: linear-gradient(135deg, #405189, #0ab39c); min-height:100vh; display:flex; align-items:center; justify-content:center;">
    <div class="card shadow border-0" style="max-width:420px; width:100%;">
        <div class="card-body p-4">
            <h4 class="text-primary text-center mb-2">Manager Login</h4>
            <p class="text-muted text-center">Sign in to continue</p>

            <?php if(session('error')): ?>
                <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('manager.login.post')); ?>">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo e(old('email')); ?>" required>
                </div>
                <div class="mb-4 position-relative">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" id="managerPassword" class="form-control pe-5" required>
                    <button type="button"
                        class="btn btn-link position-absolute end-0 top-50 text-muted text-decoration-none"
                        onclick="toggleManagerPassword()">
                        <i class="ri-eye-fill" id="managerPasswordIcon"></i>
                    </button>
                </div>
                <button class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>

    <script>
        function toggleManagerPassword() {
            const passwordInput = document.getElementById('managerPassword');
            const passwordIcon = document.getElementById('managerPasswordIcon');
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            passwordIcon.classList.toggle('ri-eye-fill', !isPassword);
            passwordIcon.classList.toggle('ri-eye-off-fill', isPassword);
        }
    </script>
</body>

</html>
<?php /**PATH C:\laragon\www\Actifab\resources\views/manager_auth/login.blade.php ENDPATH**/ ?>