<?php $__env->startSection('title', 'Panel Listing'); ?>

<?php $__env->startSection('content'); ?>

    <style>
        #ManagePanel td,
        #ManagePanel th {
            border: 1px solid #646060 !important;
        }

        #ManagePanel tbody tr:nth-child(odd) {
            background-color: #fff;
            /* light grey */
        }

        #ManagePanel tbody tr:nth-child(even) {
            background-color: #dddddd;
            /* white */
        }
    </style>

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                
                <?php echo $__env->make('common.alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Project Name - <?php echo e($project->project_name); ?>

                            &nbsp; &nbsp; &nbsp; &nbsp; Inquiry No. - <?php echo e($project->inquiry_no); ?>


                            <a href="<?php echo e(route('AddPanelBoard', $project->id)); ?>" class="btn btn-sm btn-primary float-end">
                                <i class="fas fa-plus"></i> Create Panel
                            </a>
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">
                            <table id="ManagePanel" class="table table-bordered table-striped align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="15%">Action</th>
                                        <th>Panel Name</th>
                                        <th>Panel Type</th>
                                        <th>Frame Height</th>
                                        <th>Frame Width</th>
                                        <th>Frame Depth</th>
                                        <th>Panel Access</th>
                                        <th>Created Date</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">

                                    <?php $__empty_1 = true; $__currentLoopData = $panels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $panel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex gap-2">

                                                    <a href="<?php echo e(route('panel.section.list', $panel->id)); ?>"
                                                        class="btn btn-sm btn-outline-primary">
                                                        View Section
                                                    </a>
                                                    <?php if($panel->submit_project == 0): ?>
                                                        <a href="javascript:void(0)"
                                                            class="btn btn-sm btn-success submit-project-btn"
                                                            data-id="<?php echo e($panel->id); ?>">
                                                            Submit Project
                                                        </a>
                                                    <?php endif; ?>

                                                    
                                                    <a href="<?php echo e(route('EditPanelBoard', [$panel->project_id, $panel->id])); ?>"
                                                        class="btn btn-sm btn-info">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    
                                                    <button type="button" class="btn btn-sm btn-danger delete-btn"
                                                        data-id="<?php echo e($panel->id); ?>"
                                                        data-name="<?php echo e($panel->panel_board_name); ?>" data-bs-toggle="modal"
                                                        data-bs-target="#deleteRecordModal">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                    <!--<button type="button" class="btn btn-sm btn-danger delete-btn"-->
                                                    <!--    data-id="<?php echo e($panel->id); ?>"-->
                                                    <!--    data-name="<?php echo e($panel->panel_board_name); ?>" data-bs-toggle="modal"-->
                                                    <!--    data-bs-target="#deleteRecordModal">-->
                                                    <!--    <i class="fas fa-trash"></i>-->
                                                    <!--</button>-->




                                                </div>
                                            </td>
                                            <td><?php echo e($panel->panel_board_name); ?></td>

                                            <td><?php echo e($panel->panelType->panel_type ?? '-'); ?></td>

                                            <td><?php echo e($panel->frame_height ?? '-'); ?></td>
                                            <td><?php echo e($panel->frame_width ?? '-'); ?></td>
                                            <td><?php echo e($panel->frame_depth ?? '-'); ?></td>
                                            <td><?php echo e($panel->panelAccess->panel_access ?? '-'); ?></td>

                                            <td><?php echo e(\Carbon\Carbon::parse($panel->created_at)->format('d-m-Y')); ?></td>



                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <?php endif; ?>

                                </tbody>
                            </table>
                        </div>

                        
                        <div class="d-flex justify-content-center mt-3">

                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Delete Modal Start -->
    <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mt-2 text-center">

                        <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                            colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px">
                        </lord-icon>

                        <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                            <h4>Are you Sure ?</h4>
                            <p class="text-muted mx-4 mb-0">
                                Are you sure you want to remove
                                <strong id="delete-panel-name"></strong> ?
                            </p>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">

                        <button type="submit" class="btn btn-danger">
                            Yes, Delete It!
                        </button>

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>

                        <form id="panel-delete-form" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Delete Modal End -->

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            document.addEventListener('click', function(e) {
                if (e.target.closest('.submit-project-btn')) {

                    let button = e.target.closest('.submit-project-btn');
                    let panelId = button.getAttribute('data-id');

                    if (confirm('Are you sure you want to submit this project?')) {

                        fetch('/Actifab/submit-project-panel/' + panelId, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                alert(data.message);
                                location.reload();
                            });
                    }
                }
            });

        });
    </script>

    <script>
        $(document).ready(function() {
            $('#ManagePanel').DataTable({
                pageLength: 5,
                lengthMenu: [5, 10, 25, 50, 100],
                language: {
                    emptyTable: "No Panels Found",
                    search: "",
                    searchPlaceholder: "Search Manage Panel..."
                },
                dom: '<"row mb-3"<"col-md-6"l><"col-md-6 text-end"f>>rt<"row mt-3"<"col-md-6"i><"col-md-6 text-end"p>>'
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const deleteForm = document.getElementById('panel-delete-form');
            const panelNameText = document.getElementById('delete-panel-name');

            document.addEventListener('click', function(e) {
                if (e.target.closest('.delete-btn')) {

                    let button = e.target.closest('.delete-btn');

                    let panelId = button.getAttribute('data-id');
                    let panelName = button.getAttribute('data-name');

                    // Set modal data
                    panelNameText.textContent = panelName;

                    // Set correct delete URL
                    deleteForm.setAttribute('action', '/delete-panel-board/' + panelId);
                }
            });

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/getdemo/Actifab/resources/views/user/manage-panel.blade.php ENDPATH**/ ?>