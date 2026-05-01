

<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>

    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col">

                        <div class="h-100">
                            <div class="row mb-3 pb-1">
                                <div class="col-12">
                                    <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                                        <div class="flex-grow-1">
                                            
                                        </div>

                                    </div><!-- end card header -->
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                            <div class="row">
                                <?php
                                    $feederCount = \App\Models\FeederCategory::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                ?>

                                <div class="col-md-3">
                                    <div class="card text-white bg-info">
                                        <div class="card-body">
                                            <h5 class="card-title">Feeder Categories</h5>
                                            <p class="card-text"><?php echo e($feederCount); ?></p>
                                            <a href="<?php echo e(route('feeder-category.index')); ?>" class="btn btn-light">Manage</a>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                    $typeCount = \App\Models\FeederType::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                ?>

                                <div class="col-md-3">
                                    <div class="card text-white bg-warning">
                                        <div class="card-body">
                                            <h5 class="card-title">Feeder Types</h5>
                                            <p class="card-text"><?php echo e($typeCount); ?></p>
                                            <a href="<?php echo e(route('feeder-type.index')); ?>" class="btn btn-light">Manage</a>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                    $subTypeCount = \App\Models\FeederSubType::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                ?>

                                <div class="col-md-3">
                                    <div class="card text-white bg-dark">
                                        <div class="card-body">
                                            <h5 class="card-title">Feeder Sub Types</h5>
                                            <p class="card-text"><?php echo e($subTypeCount); ?></p>
                                            <a href="<?php echo e(route('feeder-sub-type.index')); ?>" class="btn btn-light">Manage</a>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                    $panelCount = \App\Models\PanelType::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                ?>

                                <div class="col-md-3">
                                    <div class="card text-white bg-primary">
                                        <div class="card-body">
                                            <h5 class="card-title">Panel Types</h5>
                                            <p class="card-text"><?php echo e($panelCount); ?></p>
                                            <a href="<?php echo e(route('panel-type.index')); ?>" class="btn btn-light">Manage</a>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                    $partsCount = \App\Models\PartsCategory::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                ?>

                                <div class="col-md-3">
                                    <div class="card text-white bg-secondary">
                                        <div class="card-body">
                                            <h5 class="card-title">Parts Categories</h5>
                                            <p class="card-text"><?php echo e($partsCount); ?></p>
                                            <a href="<?php echo e(route('parts-category.index')); ?>" class="btn btn-light">Manage</a>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                    $partsTotal = \App\Models\PartsMaster::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                ?>

                                <div class="col-md-3">
                                    <div class="card text-white bg-dark">
                                        <div class="card-body">
                                            <h5 class="card-title">Parts Master</h5>
                                            <p class="card-text"><?php echo e($partsTotal); ?></p>
                                            <a href="<?php echo e(route('parts-master.index')); ?>" class="btn btn-light">Manage</a>
                                        </div>
                                    </div>
                                </div>

                                

                                <?php
                                    $poleCount = \App\Models\NoOfPole::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                ?>

                                <div class="col-md-3">
                                    <div class="card text-white bg-dark">
                                        <div class="card-body">
                                            <h5 class="card-title">No of Poles</h5>
                                            <p class="card-text"><?php echo e($poleCount); ?></p>
                                            <a href="<?php echo e(route('poles.index')); ?>" class="btn btn-light">Manage</a>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                    $voltageCount = \App\Models\OperatingVoltage::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                ?>

                                <div class="col-md-3">
                                    <div class="card text-white bg-dark">
                                        <div class="card-body">
                                            <h5 class="card-title">Operating Voltage</h5>
                                            <p class="card-text"><?php echo e($voltageCount); ?></p>
                                            <a href="<?php echo e(route('voltage.index')); ?>" class="btn btn-light">Manage</a>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                    $formTypeCount = \App\Models\FormType::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                ?>

                                <div class="col-md-3">
                                    <div class="card text-white bg-primary">
                                        <div class="card-body">
                                            <h5 class="card-title">Form Type</h5>
                                            <p class="card-text"><?php echo e($formTypeCount); ?></p>
                                            <a href="<?php echo e(route('form-type.index')); ?>" class="btn btn-light">Manage</a>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                    $panelAccessCount = \App\Models\PanelAccess::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                ?>

                                <div class="col-md-3">
                                    <div class="card text-white bg-info">
                                        <div class="card-body">
                                            <h5 class="card-title">Panel Access</h5>
                                            <p class="card-text"><?php echo e($panelAccessCount); ?></p>
                                            <a href="<?php echo e(route('panel-access.index')); ?>" class="btn btn-light">Manage</a>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                    $colourCount = \App\Models\PanelBoardColour::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                ?>

                                <div class="col-md-3">
                                    <div class="card text-white bg-dark">
                                        <div class="card-body">
                                            <h5 class="card-title">Board Colour</h5>
                                            <p class="card-text"><?php echo e($colourCount); ?></p>
                                            <a href="<?php echo e(route('panel-board-colour.index')); ?>"
                                                class="btn btn-light">Manage</a>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                    $ipProtectionCount = \App\Models\IpProtection::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                ?>

                                <div class="col-md-3">
                                    <div class="card text-white bg-primary">
                                        <div class="card-body">
                                            <h5 class="card-title">IP Protection</h5>
                                            <p class="card-text"><?php echo e($ipProtectionCount); ?></p>
                                            <a href="<?php echo e(route('ip-protection.index')); ?>" class="btn btn-light">Manage</a>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                    $lockSystemCount = \App\Models\LockSystem::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                ?>

                                <div class="col-md-3">
                                    <div class="card text-white bg-dark">
                                        <div class="card-body">
                                            <h5 class="card-title">Lock System</h5>
                                            <p class="card-text"><?php echo e($lockSystemCount); ?></p>
                                            <a href="<?php echo e(route('lock-system.index')); ?>" class="btn btn-light">Manage</a>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                    $busbarPositionCount = \App\Models\BusbarPosition::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                ?>

                                <div class="col-md-3">
                                    <div class="card text-white bg-info">
                                        <div class="card-body">
                                            <h5>Busbar Position</h5>
                                            <h3><?php echo e($busbarPositionCount); ?></h3>
                                            <a href="<?php echo e(route('busbar-position.index')); ?>"
                                                class="btn btn-light btn-sm">Manage</a>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                    $gptCount = \App\Models\GlandPlateThickness::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                ?>
                                <div class="col-md-3">
                                    <div class="card text-white bg-dark">
                                        <div class="card-body">
                                            <h5 class="card-title">Gland Plate Thickness</h5>
                                            <p class="card-text"><?php echo e($gptCount); ?></p>
                                            <a href="<?php echo e(route('gland-plate-thickness.index')); ?>"
                                                class="btn btn-light">Manage</a>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                    $ocpCount = \App\Models\OutgoingCablePosition::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                ?>
                                <div class="col-md-3">
                                    <div class="card text-white bg-dark">
                                        <div class="card-body">
                                            <h5 class="card-title">Outgoing Cable Position</h5>
                                            <p class="card-text"><?php echo e($ocpCount); ?></p>
                                            <a href="<?php echo e(route('outgoing-cable-position.index')); ?>"
                                                class="btn btn-light">Manage</a>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                    $plinthTypeCount = \App\Models\PlinthType::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                ?>
                                <div class="col-md-3">
                                    <div class="card text-white bg-dark">
                                        <div class="card-body">
                                            <h5 class="card-title">Plinth Type</h5>
                                            <p class="card-text"><?php echo e($plinthTypeCount); ?></p>
                                            <a href="<?php echo e(route('plinth-type.index')); ?>" class="btn btn-light">Manage</a>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                    $certificationCount = \App\Models\Certification::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                ?>
                                <div class="col-md-3">
                                    <div class="card text-white bg-primary">
                                        <div class="card-body">
                                            <h5 class="card-title">Certifications</h5>
                                            <p class="card-text"><?php echo e($certificationCount); ?></p>
                                            <a href="<?php echo e(route('certification.index')); ?>" class="btn btn-light">Manage</a>
                                        </div>
                                    </div>
                                </div>





                            </div>





                        </div>
                    </div>

                </div>

            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <script>
                            document.write(new Date().getFullYear())
                        </script> © <?php echo e(env('APP_NAME')); ?>

                    </div>

                </div>
            </div>
        </footer>
    </div>
    <!-- end main content-->


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Actifab\resources\views/home.blade.php ENDPATH**/ ?>