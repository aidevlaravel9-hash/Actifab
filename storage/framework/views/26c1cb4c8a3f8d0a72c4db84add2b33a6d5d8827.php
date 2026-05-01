<?php $__env->startSection('title', 'Feeder Sub Type'); ?>

<?php $__env->startSection('content'); ?>
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <?php echo $__env->make('common.alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <div class="row">
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">Add Feeder Sub Type</div>
                            <div class="card-body">
                                <form action="<?php echo e(route('feeder-sub-type.store')); ?>" method="POST"
                                    enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>
                                    <div class="mb-3">
                                        <label>Section Type  <span style="color:red;">*</span></label>
                                        <select id="section_id" class="form-control">
                                            <option value="">-- Select Section --</option>
                                            <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($section->section_id); ?>">
                                                    <?php echo e($section->section_name); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Feeder Category  <span style="color:red;">*</span></label>
                                        <select id="feeder_category_id" class="form-control">
                                            <option value="">-- Select Category --</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <div class="mb-3">

                                        <label class="form-label">Feeder Type <span style="color:red;">*</span></label>
                                            <select name="feeder_type_id" id="feeder_type_id" class="form-control">
                                                <option value="">-- Select Feeder Type --</option>
                                            </select>
                                        </div>
                                        <?php if($errors->has('feeder_type_id')): ?>
                                            <span class="text-danger">
                                                <?php echo e($errors->first('feeder_type_id')); ?>

                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Feeder Sub Type <span style="color:red;">*</span></label>
                                        <input type="text" name="feeder_sub_type" class="form-control"
                                            value="<?php echo e(old('feeder_sub_type')); ?>">
                                        <?php if($errors->has('feeder_sub_type')): ?>
                                            <span class="text-danger">
                                                <?php echo e($errors->first('feeder_sub_type')); ?>

                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- ✅ IMAGE FIELD (OPTIONAL) -->
                                    <div class="mb-3">
                                        <label class="form-label">Image <small class="text-muted">(optional)</small></label>
                                        <input type="file" name="image" class="form-control" accept="image/*">
                                        <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="text-danger"><?php echo e($message); ?></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                                    <table id="feederSubType" class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="checkAll"></th>
                                                <th>Action</th>
                                                <th>Section Type</th>
                                                <th>Feeder Category</th>
                                                <th>Feeder Type</th>
                                                <th>Feeder Sub Type</th>
                                                <th>Image</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr class="text-center">
                                                    <td><input type="checkbox" class="recordCheckbox"
                                                            value="<?php echo e($row->feeder_sub_type_id); ?>"></td>

                                                    <td>
                                                        <button class="btn btn-sm btn-info editBtn"
                                                            data-id="<?php echo e($row->feeder_sub_type_id); ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </button>

                                                        <button class="btn btn-sm btn-danger deleteBtn"
                                                            style="width:32px; height:32px;" data-bs-toggle="modal"
                                                            data-bs-target="#deleteRecordModal"
                                                            data-id="<?php echo e($row->feeder_sub_type_id); ?>">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                    <td><?php echo e($row->section->section_name ?? '-'); ?></td>

<td><?php echo e($row->category->feeder_category_name ?? '-'); ?></td>
                                                    <td><?php echo e($row->feederType->feeder_type ?? ''); ?></td>
                                                    <td><?php echo e($row->feeder_sub_type); ?></td>
                                                    <td>
                                                        <?php if($row->image): ?>
                                                            <img src="<?php echo e(asset('uploads/feeder_sub_type/' . $row->image)); ?>"
                                                                width="50" height="50"
                                                                style="object-fit:cover;border-radius:4px;">
                                                        <?php else: ?>
                                                            <span class="text-muted">No Image</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <button
                                                            class="btn btn-sm toggleStatus <?php echo e($row->iStatus == 1 ? 'btn-success' : 'btn-secondary'); ?>"
                                                            data-id="<?php echo e($row->feeder_sub_type_id); ?>">
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
            <form method="POST" action="<?php echo e(route('feeder-sub-type.update')); ?>" enctype="multipart/form-data">
                
                <?php echo csrf_field(); ?>

                <input type="hidden" name="edit_id" id="edit_id">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Feeder Sub Type</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        
                        <div class="mb-3">
                            <label>Section *</label>
                            <select id="edit_section_id" class="form-control">
                                <option value="">-- Select Section --</option>
                                <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($section->section_id); ?>">
                                        <?php echo e($section->section_name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label>Feeder Category *</label>
                            <select id="edit_feeder_category_id" class="form-control">
                                <option value="">-- Select Category --</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Feeder Type <span style="color:red;">*</span></label>
                            <select name="feeder_type_id" id="edit_feeder_type_id" class="form-control">
                                <option value="">-- Select Feeder Type --</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Feeder Sub Type <span style="color:red;">*</span></label>
                            <input type="text" name="feeder_sub_type" id="edit_feeder_sub_type" class="form-control">
                        </div>

                        
                        <div class="mb-3" id="editImagePreview" style="display:none;">
                            <label class="form-label">Current Image</label><br>
                            <img id="editImageTag" src="" width="80" height="80"
                                style="object-fit:cover;border:1px solid #ccc;">
                        </div>

                        
                        <div class="mb-3">
                            <label class="form-label">Change Image <small>(optional)</small></label>
                            <input type="file" name="image" class="form-control" accept="image/*">
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
    $('#section_id').on('change', function () {
    let section_id = $(this).val();

    $('#feeder_category_id').html('<option>Loading...</option>');

    $.get("<?php echo e(url('admin/get-feeder-category')); ?>/" + section_id, function (data) {

        let options = '<option value="">-- Select Category --</option>';

        data.forEach(function (item) {
            options += `<option value="${item.feeder_category_id}">
                            ${item.feeder_category_name}
                        </option>`;
        });

        $('#feeder_category_id').html(options);
    });
});



$('#feeder_category_id').on('change', function () {
    let category_id = $(this).val();

    $('#feeder_type_id').html('<option>Loading...</option>');

    $.get("<?php echo e(url('admin/get-feeder-type')); ?>/" + category_id, function (data) {

        let options = '<option value="">-- Select Feeder Type --</option>';

        data.forEach(function (item) {
            options += `<option value="${item.feeder_type_id}">
                            ${item.feeder_type}
                        </option>`;
        });

        $('#feeder_type_id').html(options);
    });
});
</script>
    <script>
        $(document).ready(function() {
            $('#feederSubType').DataTable({
                pageLength: 25,
                lengthMenu: [ 25, 50, 100 , 125],
                language: {
                    search: "",
                    searchPlaceholder: "Search Feeder Sub Type..."
                },
                dom: '<"row mb-3"<"col-md-6"l><"col-md-6 text-end"f>>rt<"row mt-3"<"col-md-6"i><"col-md-6 text-end"p>>'
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
                        url: "<?php echo e(route('feeder-sub-type.bulkDelete')); ?>",
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

    <script>
        $(document).on('click', '.toggleStatus', function() {
            const btn = $(this);
            const id = btn.data('id');

            if (!id) return;

            $.ajax({
                url: "<?php echo e(route('feeder-sub-type.status')); ?>",
                type: "POST",
                data: {
                    _token: "<?php echo e(csrf_token()); ?>",
                    id: id
                },
                success: function(res) {
                    if (res.status && btn.length > 0) {
                        const isActive = res.new_status == 1;

                        btn.removeClass('btn-success btn-secondary');
                        btn.addClass(isActive ? 'btn-success' : 'btn-secondary');
                        btn.text(isActive ? 'Active' : 'Inactive');
                    }
                },
                error: function() {
                    alert('Status update failed.');
                }
            });
        });
    </script>

    <script>
 $(document).on('click', '.editBtn', function () {

    const id = $(this).data('id');

    $.get("<?php echo e(url('admin/feeder-sub-type/edit')); ?>/" + id, function (data) {

        $('#edit_id').val(data.feeder_sub_type_id);
        $('#edit_feeder_sub_type').val(data.feeder_sub_type);
        $('#edit_iStatus').val(data.iStatus);

        // STEP 1: SET SECTION
        $('#edit_section_id').val(data.section_master_id);

        // STEP 2: LOAD CATEGORY
        $.get("<?php echo e(url('admin/get-feeder-category')); ?>/" + data.section_master_id, function (categories) {

            let options = '<option value="">-- Select Category --</option>';

            categories.forEach(function (item) {
                options += `<option value="${item.feeder_category_id}">
                                ${item.feeder_category_name}
                            </option>`;
            });
         

            $('#edit_feeder_category_id').html(options);

            // SELECT CATEGORY
            $('#edit_feeder_category_id').val(data.feeder_category_id);

            // STEP 3: LOAD TYPE
            $.get("<?php echo e(url('admin/get-feeder-type')); ?>/" + data.feeder_category_id, function (types) {
                

                let options = '<option value="">-- Select Feeder Type --</option>';

                types.forEach(function (item) {
                    options += `<option value="${item.feeder_type_id}">
                                    ${item.feeder_type}
                                </option>`;
                });

                $('#edit_feeder_type_id').html(options);

                // FIX
                let selectedId = String(data.feeder_type_id);
                
                $('#edit_feeder_type_id option').each(function () {
                    if (String($(this).val()) === selectedId) {
                        $(this).prop('selected', true);
                    }
                });

                // OPEN MODAL AFTER EVERYTHING READY
                new bootstrap.Modal(document.getElementById('editModal')).show();
            });
        });

        // IMAGE
        if (data.image) {
            $('#editImageTag').attr(
                'src',
                "<?php echo e(asset('uploads/feeder_sub_type')); ?>/" + data.image
            );
            $('#editImagePreview').show();
        } else {
            $('#editImagePreview').hide();
        }
    });
});

$('#edit_section_id').on('change', function () {
    let section_id = $(this).val();

    $('#edit_feeder_category_id').html('<option>Loading...</option>');

    $.get("<?php echo e(url('admin/get-feeder-category')); ?>/" + section_id, function (data) {

        let options = '<option value="">-- Select Category --</option>';

        data.forEach(function (item) {
            options += `<option value="${item.feeder_category_id}">
                            ${item.feeder_category_name}
                        </option>`;
        });

        $('#edit_feeder_category_id').html(options);
        $('#edit_feeder_type_id').html('<option>-- Select Feeder Type --</option>');
    });
});

$('#edit_feeder_category_id').on('change', function () {
    let category_id = $(this).val();

    $('#edit_feeder_type_id').html('<option>Loading...</option>');

    $.get("<?php echo e(url('admin/get-feeder-type')); ?>/" + category_id, function (data) {

        let options = '<option value="">-- Select Feeder Type --</option>';

        data.forEach(function (item) {
            options += `<option value="${item.feeder_type_id}">
                            ${item.feeder_type}
                        </option>`;
        });

        $('#edit_feeder_type_id').html(options);
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

                var url = "<?php echo e(route('feeder-sub-type.destroy', ':id')); ?>";
                url = url.replace(':id', id);

                form.action = url;
            });

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/getdemo/Actifab/resources/views/admin/feeder-sub-type/index.blade.php ENDPATH**/ ?>