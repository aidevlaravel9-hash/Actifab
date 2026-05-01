<?php $__env->startSection('title', isset($panelBoard) ? 'Edit Panel Board' : 'Add Panel Board'); ?>

<?php $__env->startSection('content'); ?>

<style>
.form-inline-group {
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-inline-group label {
    min-width: 140px;
    margin-bottom: 0;
}
</style>

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <?php echo $__env->make('common.alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">
                                <?php echo e(isset($panelBoard) ? 'Edit Panel Board' : 'Add Panel Board'); ?>

                            </h4>
                            <div class="page-title-right">
                                <a href="<?php echo e(route('PanelListing', $project->id)); ?>" class="btn btn-sm btn-primary shadow-sm">
                                    <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">

                                <form method="POST"
                                    action="<?php echo e(isset($panelBoard) ? route('UpdatePanelBoard', $panelBoard->id) : route('StorePanelBoard')); ?>">
                                    <?php echo csrf_field(); ?>

                                    <input type="hidden" name="project_id" value="<?php echo e($project->id); ?>">

                                    
                                    <div class="row mb-4">

                                        <div class="col-md-4 form-inline-group">
                                            <label class="form-label">Inquiry No.</label>
                                            <input type="text" class="form-control form-control-sm"
                                                value="<?php echo e($project->inquiry_no); ?>" readonly>
                                        </div>

                                        <div class="col-md-4 form-inline-group">
                                            <label class="form-label">Panel Board Job No</label>
                                            <input type="text" id="panel_job_no" name="panel_board_job_no"
                                                value="<?php echo e(old('panel_board_job_no', $panelBoard->panel_board_job_no ?? '')); ?>"
                                                class="form-control form-control-sm w-75" readonly>
                                        </div>

                                        <div class="col-md-4 form-inline-group">
                                            <label class="form-label">Panel Board Name</label>
                                            <input type="text" name="panel_board_name"
                                                value="<?php echo e(old('panel_board_name', $panelBoard->panel_board_name ?? '')); ?>"
                                                class="form-control form-control-sm w-75" required>
                                        </div>

                                    </div>

                                    <hr>

                                    
                                    <div class="row">

                                        
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">System Current Rating</label>
                                            <select name="system_current_rating_id" class="form-select form-select-sm w-75">
                                                <option value="">Select Rating</option>
                                                <?php $__currentLoopData = $systemRatings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rating): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($rating->system_current_rating_id); ?>"
                                                        <?php echo e(old('system_current_rating_id', $panelBoard->system_current_rating_id ?? '') == $rating->system_current_rating_id ? 'selected' : ''); ?>>
                                                        <?php echo e($rating->rating); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>

                                        
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">No of Poles</label>
                                            <select name="no_of_poles_id" class="form-select form-select-sm w-75">
                                                <option value="">Select Poles</option>
                                                <?php $__currentLoopData = $noOfPoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pole): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($pole->no_of_poles_id); ?>"
                                                        <?php echo e(old('no_of_poles_id', $panelBoard->no_of_poles_id ?? '') == $pole->no_of_poles_id ? 'selected' : ''); ?>>
                                                        <?php echo e($pole->no_of_poles); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>

                                        
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">Type of Panel</label>
                                            <select name="type_of_panel_id" class="form-select form-select-sm w-75">
                                                <option value="">Select Panel Type</option>
                                                <?php $__currentLoopData = $panelTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($type->panel_type_id); ?>"
                                                        <?php echo e(old('type_of_panel_id', $panelBoard->type_of_panel_id ?? '') == $type->panel_type_id ? 'selected' : ''); ?>>
                                                        <?php echo e($type->panel_type); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>

                                        
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">Operating Voltage</label>
                                            <select name="operating_voltage_id" class="form-select form-select-sm w-75">
                                                <option value="">Select Voltage</option>
                                                <?php $__currentLoopData = $operatingVoltages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $voltage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($voltage->operating_voltage_id); ?>"
                                                        <?php echo e(old('operating_voltage_id', $panelBoard->operating_voltage_id ?? '') == $voltage->operating_voltage_id ? 'selected' : ''); ?>>
                                                        <?php echo e($voltage->operating_voltage); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>

                                        
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">Form Type</label>
                                            <select name="form_type_id" class="form-select form-select-sm w-75">
                                                <option value="">Select Form Type</option>
                                                <?php $__currentLoopData = $formTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $form): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($form->form_type_id); ?>"
                                                        <?php echo e(old('form_type_id', $panelBoard->form_type_id ?? '') == $form->form_type_id ? 'selected' : ''); ?>>
                                                        <?php echo e($form->form_type); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>

                                        
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">Panel Access</label>
                                            <select name="panel_access_id" class="form-select form-select-sm w-75">
                                                <option value="">Select Panel Access</option>
                                                <?php $__currentLoopData = $panelAccesses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $access): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($access->panel_access_id); ?>"
                                                        <?php echo e(old('panel_access_id', $panelBoard->panel_access_id ?? '') == $access->panel_access_id ? 'selected' : ''); ?>>
                                                        <?php echo e($access->panel_access); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>

                                        
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">Panel Board Colour</label>
                                            <select name="panel_board_colour_id" class="form-select form-select-sm w-75">
                                                <option value="">Select Colour</option>
                                                <?php $__currentLoopData = $panelColours; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $colour): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($colour->panel_board_colour_id); ?>"
                                                        <?php echo e(old('panel_board_colour_id', $panelBoard->panel_board_colour_id ?? '') == $colour->panel_board_colour_id ? 'selected' : ''); ?>>
                                                        <?php echo e($colour->panel_board_colour); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>

                                        
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">IP Protection</label>
                                            <select name="ip_protection_id" class="form-select form-select-sm w-75">
                                                <option value="">Select IP Protection</option>
                                                <?php $__currentLoopData = $ipProtections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($ip->ip_protection_id); ?>"
                                                        <?php echo e(old('ip_protection_id', $panelBoard->ip_protection_id ?? '') == $ip->ip_protection_id ? 'selected' : ''); ?>>
                                                        <?php echo e($ip->ip_protection); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>

                                    </div>

                                    
                                    <div class="row">

                                        
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">Frame Height (Without Plinth)</label>
                                            <input type="text" name="frame_height"
                                                value="<?php echo e(old('frame_height', $panelBoard->frame_height ?? '')); ?>"
                                                class="form-control form-control-sm w-75">
                                        </div>

                                        
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">Lock System</label>
                                            <select name="lock_system_id" class="form-select form-select-sm w-75">
                                                <option value="">Select Lock System</option>
                                                <?php $__currentLoopData = $lockSystems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lock): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($lock->lock_system_id); ?>"
                                                        <?php echo e(old('lock_system_id', $panelBoard->lock_system_id ?? '') == $lock->lock_system_id ? 'selected' : ''); ?>>
                                                        <?php echo e($lock->lock_system); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        
                                        
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            
                                        </div>
                                        
                                        
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">Frame Width</label>
                                            <input type="text" name="frame_width"
                                                value="<?php echo e(old('frame_width', $panelBoard->frame_width ?? '')); ?>"
                                                class="form-control form-control-sm w-75">
                                        </div>

                                        
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">Busbar Position</label>
                                            <select name="busbar_position_id" class="form-select form-select-sm w-75">
                                                <option value="">Select Busbar Position</option>
                                                <?php $__currentLoopData = $busbarPositions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $position): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($position->busbar_position_id); ?>"
                                                        <?php echo e(old('busbar_position_id', $panelBoard->busbar_position_id ?? '') == $position->busbar_position_id ? 'selected' : ''); ?>>
                                                        <?php echo e($position->busbar_position); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>

                                        
                                        
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">Gland Plate Thickness</label>
                                            <select name="gland_plate_thickness_id"
                                                class="form-select form-select-sm w-75">
                                                <option value="">Select Thickness</option>
                                                <?php $__currentLoopData = $glandPlateThicknesses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thickness): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($thickness->gland_plate_thickness_id); ?>"
                                                        <?php echo e(old('gland_plate_thickness_id', $panelBoard->gland_plate_thickness_id ?? '') == $thickness->gland_plate_thickness_id ? 'selected' : ''); ?>>
                                                        <?php echo e($thickness->gland_plate_thickness); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        
                                        
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">Frame Depth</label>
                                            <input type="text" name="frame_depth"
                                                value="<?php echo e(old('frame_depth', $panelBoard->frame_depth ?? '')); ?>"
                                                class="form-control form-control-sm w-75">
                                        </div>  

                                        
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">Outgoing Cable Position</label>
                                            <select name="outgoing_cable_position_id"
                                                class="form-select form-select-sm w-75">
                                                <option value="">Select Cable Position</option>
                                                <?php $__currentLoopData = $outgoingCablePositions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $position): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($position->outgoing_cable_position_id); ?>"
                                                        <?php echo e(old('outgoing_cable_position_id', $panelBoard->outgoing_cable_position_id ?? '') == $position->outgoing_cable_position_id ? 'selected' : ''); ?>>
                                                        <?php echo e($position->outgoing_cable_position); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>

                                        
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">Thickness – Door / Cover / Other</label>
                                            <input type="text" name="thickness"
                                                value="<?php echo e(old('thickness', $panelBoard->thickness ?? '')); ?>"
                                                class="form-control form-control-sm w-75">
                                        </div>

                                        
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">Plinth Height</label>
                                            <input type="text" name="plinth_height"
                                                value="<?php echo e(old('plinth_height', $panelBoard->plinth_height ?? '')); ?>"
                                                class="form-control form-control-sm w-75">
                                        </div>

                                        
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">Plinth Type</label>
                                            <select name="plinth_type_id" class="form-select form-select-sm w-75">
                                                <option value="">Select Plinth Type</option>
                                                <?php $__currentLoopData = $plinthTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plinth): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($plinth->plinth_type_id); ?>"
                                                        <?php echo e(old('plinth_type_id', $panelBoard->plinth_type_id ?? '') == $plinth->plinth_type_id ? 'selected' : ''); ?>>
                                                        <?php echo e($plinth->plinth_type); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>


                                        
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">Certification</label>
                                            <select name="certification_id" class="form-select form-select-sm w-75">
                                                <option value="">Select Certification</option>
                                                <?php $__currentLoopData = $certifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($cert->certification_id); ?>"
                                                        <?php echo e(old('certification_id', $panelBoard->certification_id ?? '') == $cert->certification_id ? 'selected' : ''); ?>>
                                                        <?php echo e($cert->certification); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>

                                    </div>

                                    <div class="mt-3 text-end">
                                        <button type="submit" class="btn btn-success px-4">
                                            <?php echo e(isset($panelBoard) ? 'Update' : 'Submit'); ?>

                                        </button>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
document.addEventListener('DOMContentLoaded', function () {

    let jobInput = document.getElementById('panel_job_no');
    let projectId = "<?php echo e($project->id); ?>";

    <?php if(!isset($panelBoard)): ?>

    // ✅ Dynamic correct URL (handles /Actifab automatically)
    let url = "<?php echo e(url('user-panel/get-panel-job-no')); ?>/" + projectId;

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(text => {
        try {
            let data = JSON.parse(text);
            jobInput.value = data.job_no || '';
        } catch (e) {
            console.error('Invalid JSON:', text);
            jobInput.value = '';
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        jobInput.value = '';
    });

    <?php endif; ?>

});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/getdemo/Actifab/resources/views/user/add-panel-board.blade.php ENDPATH**/ ?>