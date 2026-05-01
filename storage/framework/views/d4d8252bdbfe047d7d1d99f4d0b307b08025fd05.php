<!doctype html>
<html lang="en" data-layout="horizontal" data-layout-style="default" data-layout-position="fixed" data-topbar="light"
    data-sidebar="dark" data-sidebar-size="sm-hover" data-layout-width="fluid">


<?php echo $__env->make('common.admin.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        <!-- Topbar -->
        <?php echo $__env->make('common.admin.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- End of Topbar -->

        <!-- Sidebar -->
        <?php echo $__env->make('common.admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- End of Sidebar -->

        <?php echo $__env->yieldContent('content'); ?>

        <?php echo $__env->make('common.admin.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    </div>

    <?php echo $__env->make('common.admin.footerjs', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <?php echo $__env->yieldContent('scripts'); ?>

</body>

</html>
<?php /**PATH /home1/getdemo/Actifab/resources/views/layouts/app.blade.php ENDPATH**/ ?>