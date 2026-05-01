<?php $__env->startSection('title', 'Create Project'); ?>

<?php $__env->startSection('content'); ?>

    <style>
        table {
            border:1px solid #646060 !important;
        }
        
        #projectList td,
        #projectList th {
            border: 1px solid #646060 !important;
        }
        
        #projectList tbody tr:nth-child(odd) {
            background-color: #fff; /* light grey */
        }
        
        #projectList tbody tr:nth-child(even) {
            background-color: #dddddd; /* white */
        }
    </style>

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                
                <?php echo $__env->make('common.alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Projects List
                            <a href="<?php echo e(route('CreateProject')); ?>" class="btn btn-sm btn-primary float-end">
                                <i class="fas fa-plus"></i> Create Project
                            </a>
                        </h5>
                    </div>

                    <div class="card-body">

                        
                        

                        <div class="table-responsive">
                            <table id="projectList" class="table table-bordered table-striped align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        
                                        <th width="20%">Action</th>
                                        <th>Document</th>
                                        <th>Inquiry No</th>
                                        <th>Project Name</th>
                                        <th>Customer Name</th>
                                        <th>Customer Mobile</th>
                                        <th width="15%">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr class="text-center ">
                                            
                                            <td class="table-visible">
                                                <div class="d-flex flex-column gap-2">

                                                    
                                                    <div class="d-flex align-items-center gap-2">
                                                        <a href="<?php echo e(route('AddPanelBoard', $project->id)); ?>"
                                                            class="btn btn-sm btn-secondary">
                                                            Create Panel
                                                        </a>

                                                        <a href="<?php echo e(route('PanelListing', $project->id)); ?>"
                                                            class="btn btn-sm btn-secondary">
                                                            Manage Panel(<?php echo e($project->panels_count ?? 0); ?>)
                                                        </a>
                                                        
                                                        
                                                        
    
                                                    </div>

                                                    
                                                    <div class="d-flex align-items-center gap-2">

                                                        
                                                        <!--<?php if($project->status == 1): ?>-->
                                                        <!--    <a href="<?php echo e(route('project.toggleStatus', $project->id)); ?>"-->
                                                        <!--        class="btn btn-sm btn-success">-->
                                                        <!--        Active-->
                                                        <!--    </a>-->
                                                        <!--<?php else: ?>-->
                                                        <!--    <a href="<?php echo e(route('project.toggleStatus', $project->id)); ?>"-->
                                                        <!--        class="btn btn-sm btn-danger">-->
                                                        <!--        Inactive-->
                                                        <!--    </a>-->
                                                        <!--<?php endif; ?>-->
                                                        <!--<a href="<?php echo e(route('PanelListing', $project->id)); ?>"-->
                                                        <!--    class="btn btn-sm btn-secondary">-->
                                                        <!--    Panelcount (<?php echo e($project->panels_count ?? 0); ?>)-->
                                                        <!--</a>-->

                                                        
                                                        <a href="<?php echo e(route('editProject', $project->id)); ?>"
                                                            class="btn btn-sm btn-info editBtn btn-info d-flex align-items-center justify-content-center"
                                                            style="width:32px; height:32px;">
                                                            <i class="fas fa-edit"></i>
                                                        </a>

                                                        
                                                        <button
                                                            class="btn btn-sm btn-danger deleteBtn d-flex align-items-center justify-content-center"
                                                            style="width:32px; height:32px;" data-bs-toggle="modal"
                                                            data-bs-target="#deleteRecordModal"
                                                            data-id="<?php echo e($project->id); ?>">
                                                            <i class="fas fa-trash"></i>
                                                        </button>

                                                    </div>

                                                </div>
                                            </td>


                                             <td>
                                                <?php if($project->attachment): ?>
                                                    <a href="<?php echo e(asset('uploads/projects/' . $project->attachment)); ?>"
                                                        target="_blank" class="btn btn-sm btn-info">
                                                        View File
                                                    </a>
                                                <?php endif; ?>
                                            </td>

                                            <td><?php echo e($project->inquiry_no); ?></td>

                                            <td><?php echo e($project->project_name); ?></td>

                                            <td><?php echo e($project->customer_name); ?></td>
                                            <td><?php echo e($project->contact_person_mobile); ?></td>

                                            <td><?php echo e(\Carbon\Carbon::parse($project->inquiry_date)->format('d-m-Y')); ?></td>


                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                       
                                    <?php endif; ?>
                                </tbody>

                            </table>
                        </div>

                        
                        <div class="d-flex justify-content-center mt-3">
                            <!--<?php echo e($projects->links()); ?>-->
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

                <div class="modal-header">
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
                                Are you sure you want to remove this Project ?
                            </p>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">

                        <a href="javascript:void(0);" class="btn btn-danger mx-2"
                            onclick="event.preventDefault(); document.getElementById('project-delete-form').submit();">
                            Yes, Delete It!
                        </a>

                        <button type="button" class="btn btn-secondary mx-2" data-bs-dismiss="modal">
                            Close
                        </button>

                        <form id="project-delete-form" method="POST">
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
        $(document).ready(function() {
            $('#projectList').DataTable({
                pageLength: 5,
                lengthMenu: [5, 10, 25, 50, 100],
                language: {
                    search: "",
                    searchPlaceholder: "Search Projects ..."
                },
                dom: '<"row mb-3"<"col-md-6"l><"col-md-6 text-end"f>>rt<"row mt-3"<"col-md-6"i><"col-md-6 text-end"p>>'
            });
        });
    </script>
    <script>
        var deleteModal = document.getElementById('deleteRecordModal');

        deleteModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var projectId = button.getAttribute('data-id');

            var form = document.getElementById('project-delete-form');
            // Laravel route generate karo
            var url = "<?php echo e(route('deleteproject', ':id')); ?>";
            url = url.replace(':id', projectId);
    
            form.action = url;
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/getdemo/Actifab/resources/views/user/index.blade.php ENDPATH**/ ?>