<head>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta charset="utf-8" />
    <title><?php echo e(config('app.name')); ?> - <?php echo $__env->yieldContent('title'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <!--<link rel="shortcut icon" href="<?php echo e(asset('assets/images/logo.png')); ?>">-->
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo e(asset('assets/images/actifab-favicon.png')); ?>">

    <!-- Layout config Js -->
    <script src="<?php echo e(asset('assets/js/layout.js')); ?>"></script>
    <!-- Bootstrap Css -->
    <link href="<?php echo e(asset('assets/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="<?php echo e(asset('assets/css/icons.min.css')); ?>" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?php echo e(asset('assets/css/app.min.css')); ?>" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="<?php echo e(asset('assets/css/custom.min.css')); ?>" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="<?php echo e(asset('assest/css/datatables.min.css')); ?>">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        .dataTables_filter input {
            border-radius: 6px;
            padding: 6px 10px;
            border: 1px solid #ccc;
        }

        .dataTables_length select {
            border-radius: 6px;
            padding: 4px;
        }

        .dataTables_paginate .paginate_button {
            border-radius: 4px !important;
            padding: 5px 10px !important;
            margin: 2px;
        }

        .dataTables_paginate .paginate_button.current {
            background: #0d6efd !important;
            color: white !important;
        }

        table.dataTable thead {
            background-color: #2f536a;
            color: white;
        }
    </style>
</head>
<?php /**PATH C:\laragon\www\Actifab\resources\views/common/admin/head.blade.php ENDPATH**/ ?>