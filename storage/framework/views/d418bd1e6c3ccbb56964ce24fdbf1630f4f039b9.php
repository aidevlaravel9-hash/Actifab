<?php $__env->startSection('title', 'Section'); ?>

<?php $__env->startSection('content'); ?>

<style>
    
</style>
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                
                <?php echo $__env->make('common.alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <div class="row">
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">Add Section</div>
                            <div class="card-body">
                                <form action="<?php echo e(route('sectionstore')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <div class="mb-3">
                                        <label class="form-label">Section Name <span style="color:red;">*</span></label>
                                        <input type="text" name="section_name" class="form-control" required
                                            value="<?php echo e(old('section_name')); ?>">
                                        <?php if($errors->has('section_name')): ?>
                                            <span class="text-danger">
                                                <?php echo e($errors->first('section_name')); ?>

                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select name="iStatus" class="form-control">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                
                                <button id="deleteSelected" class="btn btn-danger btn-sm">Bulk Delete</button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id ="sectiontype" class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="checkAll"></th>
                                                <th width="15%">Action</th>
                                                <th>Section Name</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr class="text-center">
                                                    <td><input type="checkbox" class="recordCheckbox"
                                                            value="<?php echo e($row->section_id); ?>"></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-info editBtn"
                                                            data-id="<?php echo e($row->section_id); ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </button>

                                                        <button class="btn btn-sm btn-danger deleteBtn"
                                                            style="width:32px; height:32px;" data-bs-toggle="modal"
                                                            data-bs-target="#deleteRecordModal"
                                                            data-id="<?php echo e($row->section_id); ?>">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                    <td><?php echo e($row->section_name); ?></td>
                                                    <td>
                                                        <button
                                                            class="btn btn-sm toggleStatus <?php echo e($row->iStatus == 1 ? 'btn-success' : 'btn-secondary'); ?>"
                                                            data-id="<?php echo e($row->section_id); ?>">
                                                            <?php echo e($row->iStatus == 1 ? 'Active' : 'Inactive'); ?>

                                                        </button>
                                                    </td>

                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-center mt-3">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="POST" action="<?php echo e(route('sectionupdate')); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="edit_id" id="edit_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Section</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Section Name <span style="color:red;">*</span></label>
                            <input type="text" name="section_name" id="edit_section_name" class="form-control">
                            <?php if($errors->has('section_name')): ?>
                                <span class="text-danger">
                                    <?php echo e($errors->first('section_name')); ?>

                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="iStatus" id="edit_iStatus" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
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
                            onclick="event.preventDefault(); document.getElementById('feeder-delete-form').submit();">
                            Yes, Delete It!
                        </a>

                        <button type="button" class="btn btn-secondary mx-2" data-bs-dismiss="modal">
                            Close
                        </button>

                        <form id="feeder-delete-form" method="POST">
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
            $('#sectiontype').DataTable({
                pageLength: 25,
                lengthMenu: [ 25, 50, 100, 125],
                language: {
                    search: "",
                    searchPlaceholder: "Search Section Type..."
                },
                dom: '<"row mb-3"<"col-md-6"l><"col-md-6 text-end"f>>rt<"row mt-3"<"col-md-6"i><"col-md-6 text-end"p>>'
            });
        });
    </script>
    <script>
        $('.editBtn').on('click', function() {
            const id = $(this).data('id');

            $.get("<?php echo e(url('admin/section/edit')); ?>/" + id, function(data) {
                $('#edit_id').val(data.section_id);
                $('#edit_section_name').val(data.section_name);
                $('#edit_iStatus').val(data.iStatus);
                $('#editModal').modal('show');
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.toggleStatus').on('click', function() {
                const button = $(this);
                const id = button.data('id');

                if (!id) return;

                $.ajax({
                    url: "<?php echo e(route('section.status')); ?>",
                    method: "POST",
                    data: {
                        _token: "<?php echo e(csrf_token()); ?>",
                        id: id
                    },
                    success: function(response) {
                        if (response.status) {
                            if (response.new_status == 1) {
                                button.removeClass('btn-secondary').addClass('btn-success')
                                    .text('Active');
                            } else {
                                button.removeClass('btn-success').addClass('btn-secondary')
                                    .text('Inactive');
                            }
                        }
                    },
                    error: function() {
                        alert('Something went wrong. Please try again.');
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            var deleteModal = document.getElementById('deleteRecordModal');

            deleteModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var id = button.getAttribute('data-id');

                var form = document.getElementById('feeder-delete-form');

                var url = "<?php echo e(route('section.destroy', ':id')); ?>";
                url = url.replace(':id', id);

                form.action = url;
            });

        });
    </script>

    <script>
        $('#checkAll').on('click', function() {
            $('.recordCheckbox').prop('checked', $(this).prop('checked'));
        });

        $('#deleteSelected').on('click', function() {

            const selected = $('.recordCheckbox:checked');

            if (selected.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: 'Please select at least one record to delete.',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }

            const ids = [];
            selected.each(function() {
                ids.push($(this).val());
            });

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: "<?php echo e(route('section.bulkDelete')); ?>",
                        type: 'POST',
                        data: {
                            _token: '<?php echo e(csrf_token()); ?>',
                            ids: ids
                        },
                        success: function(res) {

                            if (res.status) {

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'Selected records have been deleted.',
                                    timer: 1500,
                                    showConfirmButton: false
                                });

                                setTimeout(function() {
                                    location.reload();
                                }, 1500);
                            }
                        },
                        error: function() {
                            Swal.fire(
                                'Error!',
                                'Something went wrong.',
                                'error'
                            );
                        }
                    });

                }

            });

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/getdemo/Actifab/resources/views/admin/section-master/index.blade.php ENDPATH**/ ?>