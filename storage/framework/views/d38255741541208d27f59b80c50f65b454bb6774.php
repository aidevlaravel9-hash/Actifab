<?php $__env->startSection('title', 'Manager / Designer Users'); ?>

<?php $__env->startSection('content'); ?>
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <?php echo $__env->make('common.alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <div class="row">

                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">Add User</div>
                            <div class="card-body">

                                <form action="<?php echo e(route('manager-designer-user.store')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>

                                    <div class="mb-3">
                                        <label>First Name <span style="color:red;">*</span></label>
                                        <input type="text" name="first_name" class="form-control"
                                            placeholder="Enter first name" value="<?php echo e(old('first_name')); ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label>Email <span style="color:red;">*</span></label>
                                        <input type="email" name="email" placeholder="Enter Email" class="form-control"
                                            value="<?php echo e(old('email')); ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label>Mobile <span style="color:red;">*</span></label>
                                        <input type="text" name="mobile_number" placeholder="Enter Mobile number"
                                            class="form-control" value="<?php echo e(old('mobile_number')); ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label>Role <span style="color:red;">*</span></label>
                                        <select name="role_type" class="form-control">
                                            <option value="manager">Manager</option>
                                            <option value="designer">Designer</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>

                                    <button class="btn btn-primary w-10">Submit</button>

                                </form>

                            </div>
                        </div>
                    </div>

                    
                    <div class="col-md-8">
                        <div class="card">

                            <div class="card-header d-flex justify-content-between">

                                <form method="GET" class="d-flex">
                                    <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                                        class="form-control me-2" placeholder="Search user">
                                    <button class="btn btn-outline-primary">Search</button>
                                </form>

                            </div>

                            <div class="card-body">

                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Mobile</th>
                                                <th>Role</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e(($data->currentPage() - 1) * $data->perPage() + $loop->iteration); ?>

                                                    </td>
                                                    <td><?php echo e($row->first_name); ?> <?php echo e($row->last_name); ?></td>
                                                    <td><?php echo e($row->email); ?></td>
                                                    <td><?php echo e($row->mobile_number); ?></td>
                                                    <td><?php echo e(ucfirst($row->role_type)); ?></td>
                                                    <td>
                                                        <span
                                                            class="badge <?php echo e($row->status ? 'bg-success' : 'bg-secondary'); ?>">
                                                            <?php echo e($row->status ? 'Active' : 'Inactive'); ?>

                                                        </span>
                                                    </td>
                                                    <td>

                                                        <button class="btn btn-info btn-sm editBtn"
                                                            data-id="<?php echo e($row->manager_designer_user_id); ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </button>

                                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                            data-bs-target="#deleteRecordModal"
                                                            data-id="<?php echo e($row->manager_designer_user_id); ?>">
                                                            <i class="fas fa-trash"></i>
                                                        </button>

                                                        <button class="btn btn-warning btn-sm passwordBtn"
                                                            data-id="<?php echo e($row->manager_designer_user_id); ?>"
                                                            data-name="<?php echo e($row->first_name); ?>" title="Change Password">
                                                            <i class="fas fa-key"></i>
                                                        </button>

                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>

                                    <?php echo e($data->links()); ?>


                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="editModal">
        <div class="modal-dialog">
            <form method="POST" action="<?php echo e(route('manager-designer-user.update')); ?>">
                <?php echo csrf_field(); ?>

                <input type="hidden" name="edit_id" id="edit_id">

                <div class="modal-content">

                    <div class="modal-header">
                        <h5>Edit User</h5>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label>First Name</label>
                            <input type="text" id="edit_first_name" name="first_name" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" id="edit_email" name="email" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Mobile</label>
                            <input type="text" id="edit_mobile_number" name="mobile_number" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Role</label>
                            <select id="edit_role_type" name="role_type" class="form-control">
                                <option value="manager">Manager</option>
                                <option value="designer">Designer</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Status</label>
                            <select id="edit_status" name="status" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <!-- DELETE MODAL -->
    <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body text-center">

                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                        colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px">
                    </lord-icon>

                    <h4 class="mt-3">Are you sure?</h4>
                    <p class="text-muted">Are you sure you want to delete this user?</p>

                    <div class="d-flex justify-content-center gap-2 mt-4">

                        <a href="javascript:void(0);" class="btn btn-danger"
                            onclick="event.preventDefault(); document.getElementById('user-delete-form').submit();">
                            Yes, Delete It!
                        </a>

                        <button class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>

                    </div>

                    <form id="user-delete-form" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                    </form>

                </div>

            </div>
        </div>
    </div>

    
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="<?php echo e(route('manager-designer-user.password-update')); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="user_id" id="password_user_id">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Change Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p class="mb-3">Update password for <strong id="password_user_name"></strong>.</p>

                        <div class="mb-3">
                            <label>New Password</label>
                            <div class="input-group">
                                <input type="password" placeholder="Enter New Password" name="password"
                                    id="new_password_input" class="form-control" required minlength="6">
                                <button type="button" class="btn btn-outline-secondary togglePasswordBtn"
                                    data-target="#new_password_input">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Confirm Password</label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" placeholder="Enter Confirm Password"
                                    id="confirm_password_input" class="form-control" required minlength="6">
                                <button type="button" class="btn btn-outline-secondary togglePasswordBtn"
                                    data-target="#confirm_password_input">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary">Change Password</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>

    <script>
        $(document).on('click', '.editBtn', function() {

            let id = $(this).data('id');

            $.get("<?php echo e(url('admin/manager-designer-user/edit')); ?>/" + id, function(data) {

                $('#edit_id').val(data.manager_designer_user_id);
                $('#edit_first_name').val(data.first_name);
                $('#edit_last_name').val(data.last_name);
                $('#edit_email').val(data.email);
                $('#edit_mobile_number').val(data.mobile_number);
                $('#edit_role_type').val(data.role_type);
                $('#edit_status').val(data.status);

                new bootstrap.Modal(document.getElementById('editModal')).show();

            });

        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            var deleteModal = document.getElementById('deleteRecordModal');

            deleteModal.addEventListener('show.bs.modal', function(event) {

                var button = event.relatedTarget;
                var id = button.getAttribute('data-id');

                var form = document.getElementById('user-delete-form');

                var url = "<?php echo e(route('manager-designer-user.destroy', ':id')); ?>";
                url = url.replace(':id', id);

                form.action = url;

            });

        });
    </script>
    <script>
        $(document).on('click', '.passwordBtn', function() {
            const userId = $(this).data('id');
            const userName = $(this).data('name');

            $('#password_user_id').val(userId);
            $('#password_user_name').text(userName);

            new bootstrap.Modal(document.getElementById('changePasswordModal')).show();
        });

        $(document).on('click', '.togglePasswordBtn', function() {
            const targetSelector = $(this).data('target');
            const input = $(targetSelector);
            const icon = $(this).find('i');

            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                input.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Actifab\resources\views/admin/manager-designer-user/index.blade.php ENDPATH**/ ?>