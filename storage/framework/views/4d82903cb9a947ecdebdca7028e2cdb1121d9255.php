<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>

<?php $__env->startSection('content'); ?>

    <style>
      
        .cover-dot {
            position: absolute;
            font-size: 8px;
            line-height: 1;
            z-index: 10;
        }
        
        .dot-tl {
            top: 3px;
            left: 3px;
        }
        
        .dot-tr {
            top: 3px;
            right: 3px;
        }
        
        .dot-bl {
            bottom: 3px;
            left: 3px;
        }
        
        .dot-br {
            bottom: 3px;
            right: 3px;
        }

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

        .is-invalid {
            border: 1px solid red !important;
            background: #fff5f5 !important;
        }
    </style>

    <?php
        if ($editSection) {
            // Edit mode â†’ sirf wahi section
            $sectionsToShow = collect([$editSection]);
        } else {
            // Create mode â†’ ek temporary blank section
            $tempSection = new \stdClass();
            $tempSection->section_name = old('section_name', 'New Section');
            $tempSection->width = old('width', 0);
            $tempSection->feeders = collect(); // no feeders

            $sectionsToShow = collect([$tempSection]);
        }
    ?>
    

    @endphp
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <?php
                    $scale = 0.25; // ðŸ‘ˆ Preview small karne ke liye
                ?>

                
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if(session('success')): ?>
                    <div class="alert alert-success" id="success-alert">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                <?php
                    $lastSection = $panel->sections->last();
                    $canCreateSection = true;

                    if ($lastSection) {
                        $usedHeightLast = $lastSection->feeders->sum('height');

                        if ($usedHeightLast < $panel->frame_height) {
                            $canCreateSection = false;
                        }
                    }
                ?>
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">


                        </div>
                    </div>
                    <div class="col-12 d-flex gap-2 mb-3">
                        <h4 class="mb-sm-0">Add Section</h4>

                        <a href="<?php echo e(route('panel.show', $panel->id)); ?>" class="btn btn-sm btn-primary">
                            Add Section
                        </a>

                        <div class="page-title-right">
                            <a href="<?php echo e(route('panel.section.list', $panel->id)); ?>"
                                class="btn btn-sm btn-primary shadow-sm">
                                
                                Preview All Section
                            </a>
                        </div>
                    </div>
                </div>

                
                <div class="card shadow-sm mb-2">
                    <div class="card-body d-flex justify-content-  pb-1 pt-2">

                        <div class="me-5">
                            <h6>Project Name:</h6>
                            <h6> <span class="text-black"><?php echo e($panel->project->project_name ?? ''); ?></h6>
                            </span>
                        </div>

                        <div class="me-5">
                            <h6>Panel Board Name:</h6>
                            <h6><span class="text-black"><?php echo e($panel->panel_board_name); ?></h6> </span>
                        </div>

                        <div class="me-5">
                            <h6>Panel Board Job No: </h6>
                            <h6><span class="text-black"><?php echo e($panel->panel_board_job_no ?? ''); ?></h6>
                            </span>
                        </div>

                        <div class="me-5">
                            <h6>Size: </h6>
                            <h6><span class="text-black"><?php echo e($panel->frame_width); ?> x <?php echo e($panel->frame_height); ?></h6>
                            </span>
                        </div>
                    </div>


                    
                    
                    <div class="card shadow-sm mb-4 section-card-live">
                        <div class="card-header bg-primary d-flex justify-content- text-white pb-1 pt-2">
                            Add Section
                            <span class="badge badge-light fs-6">
                                Remaining Width: <?php echo e($remainingWidth); ?>

                            </span>
                        </div>

                        <div class="card-body pb-1 pt-2">
                            <form method="POST"
                                action="<?php echo e($editSection ? route('section.update', $editSection->id) : route('section.store')); ?>">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="panel_id" value="<?php echo e($panel->id); ?>">

                                <div class="row">
                                    <div class="col-md-2">
                                        <label>Section Name</label>
                                        <input type="text" name="section_name"
                                            value="<?php echo e($editSection->section_name ?? ''); ?>" class="form-control" required>
                                    </div>

                                    <div class="col-md-3">
                                        <label>Section Type</label>
                                        <select name="section_type" class="form-control" id="section_type_select">
                                            <option value="">Please Select</option>
                                            <?php $__currentLoopData = $sectionmaster; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($section->section_id); ?>"
                                                    <?php echo e(isset($editSection) && $section->section_id == $editSection->section_type_id ? 'selected' : ''); ?>>
                                                    <?php echo e($section->section_name); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label>Width</label>
                                        <input type="number" name="width" value="<?php echo e($editSection->width ?? ''); ?>"
                                            max="<?php echo e($editSection ? $remainingWidth + $editSection->width : $remainingWidth); ?>"
                                            class="form-control" required>
                                    </div>

                                    <div class="col-md-2">
                                        <label>Lock Position</label>
                                        <select name="lock_position" class="form-control">
                                            <option value="">Please Select</option>
                                            <option value="Left"
                                                <?php echo e(isset($editSection) && $editSection->lock_position == 'Left' ? 'selected' : ''); ?>>
                                                Left</option>
                                            <option value="Right"
                                                <?php echo e(isset($editSection) && $editSection->lock_position == 'Right' ? 'selected' : ''); ?>>
                                                Right</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3 d-flex align-items-end">

                                        <?php if($editSection || ($remainingWidth > 0 && $canCreateSection)): ?>
                                            <button class="btn btn-success btn-block">
                                                <?php echo e($editSection ? 'Update Section' : 'Create Section'); ?>

                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                    

                    
                    <?php if($editSection): ?>
                        <?php $__currentLoopData = $sectionsToShow; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $usedHeight = $section->feeders->sum('height');
                                $remainingHeight = $panel->frame_height - $usedHeight;
                            ?>

                            <div class="card shadow-sm mb-4 section-card-live">

                                <div class="card-header bg-info text-white d-flex gap-4 pb-1 pt-2">
                                    <span>Section: <?php echo e($section->section_name); ?></span>

                                    <span>
                                        <strong> Width:</strong> <?php echo e($section->width); ?>

                                    </span>
                                </div>

                                <div class="card-body ">

                                    
                                    <?php if($section->feeders->count() < 9 && $usedHeight < $panel->frame_height): ?>
                                        <form method="POST" action="<?php echo e(route('feeder.store')); ?>" class="mt-3">

                                            <div class="text-danger fw-bold live-height-message mt-2" style="display:none;">
                                                Your feeder height is already used. You cannot add more height.
                                            </div>

                                            <div class="text-danger fw-bold live-height-exceed-message mt-2"
                                                style="display:none;">
                                                Entered feeder height exceeds available section height.
                                            </div>

                                            <span>
                                                <strong>Remaining Height:</strong>
                                                <span class="live-remaining-height"
                                                    data-frame-height="<?php echo e($panel->frame_height); ?>"
                                                    data-used-height="<?php echo e($usedHeight); ?>">
                                                    <?php echo e($remainingHeight); ?>

                                                </span>
                                            </span>

                                            <?php echo csrf_field(); ?>

                                            <input type="hidden" name="panel_id" value="<?php echo e($panel->id); ?>">
                                            <input type="hidden" name="section_id" value="<?php echo e($section->id); ?>">
                                            <input type="hidden" id="current_section_type_id"
                                                value="<?php echo e($section->section_type_id); ?>">
                                            
                                            <div class="row mb-2 fw-bold text-center"
                                                style="border-bottom:2px solid #ccc; padding-bottom:8px;">
                                                <div class="col-md-2" style="width:7%">Feeder Name</div>
                                                <div class="col-md-2" style="width:9%">Feeder Height</div>
                                                <div class="col-md-2 " style="width:15%">Feeder Category</div>
                                                <div class="col-md-2 " style="width:15%">Feeder Type</div>
                                                <div class="col-md-2" style="width:15%">Feeder Sub Type</div>
                                                <div class="col-md-2" style="width:15%">Door</div>
                                                <div class="col-md-2" style="width:24%">Customer Remarks</div>
                                            </div>

                                            <?php for($i = 0; $i < 9; $i++): ?>
                                                <?php
                                                    $existingFeeder = $section->feeders[$i] ?? null;

                                                    $sectionNumber =
                                                        $panel->sections->search(function ($sec) use ($section) {
                                                            return $sec->id == $section->id;
                                                        }) + 1;

                                                    $feederName = $sectionNumber . 'F' . ($i + 1);
                                                ?>

                                                <div class="row mb-2"
                                                    style="border-bottom:1px solid #eee; padding-bottom:10px;">

                                                    
                                                    <div class="col-md-2 d-flex justify-content-center" style="width:7%">
                                                        <input type="text" class="form-control form-control-sm"
                                                            style="width:50px;"
                                                            value="<?php echo e($existingFeeder->feeder_name ?? $feederName); ?>"
                                                            readonly>
                                                    </div>

                                                    
                                                    <div class="col-md-2" style="width:9%">
                                                        <input type="number" name="feeders[<?php echo e($i); ?>][height]"
                                                            class="form-control feeder-height-input" style="width:75px;"
                                                            value="<?php echo e($existingFeeder->height ?? ''); ?>"
                                                            <?php echo e($existingFeeder ? 'disabled' : ''); ?>>
                                                    </div>

                                                    
                                                    <div class="col-md-2" style="width:15%">

                                                        <?php if($existingFeeder): ?>
                                                            <input type="text" class="form-control feeder_category"
                                                                style="width:160px;" id="feeder_category"
                                                                value="<?php echo e($existingFeeder->FeederCategory->feeder_category_name ?? ''); ?>"
                                                                readonly>
                                                        <?php else: ?>
                                                            <select name="feeders[<?php echo e($i); ?>][category]"
                                                                style="width:160px;" id="feeder_category"
                                                                class="form-control
                                                                feeder_category">
                                                                <option value="">Select</option>
                                                                <?php $__currentLoopData = $feedercategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($cat->feeder_category_id); ?>">
                                                                        <?php echo e($cat->feeder_category_name); ?>

                                                                    </option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </select>
                                                        <?php endif; ?>
                                                    </div>

                                                    
                                                    <div class="col-md-2" style="width:15%">

                                                        <?php if($existingFeeder): ?>
                                                            <input type="text" id="feeder_type" style="width:160px;"
                                                                class="form-control feeder_type"
                                                                value="<?php echo e($existingFeeder->FeederType->feeder_type ?? ''); ?>"
                                                                readonly>
                                                        <?php else: ?>
                                                            <select name="feeders[<?php echo e($i); ?>][type]"
                                                                style="width:160px;" id="feeder_type"
                                                                class="form-control feeder_type">
                                                                <option value="">Select</option>
                                                                <?php $__currentLoopData = $feedertype; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($type->feeder_type_id); ?>">
                                                                        <?php echo e($type->feeder_type); ?>

                                                                    </option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </select>
                                                        <?php endif; ?>
                                                    </div>

                                                    
                                                    <div class="col-md-2" style="width:15%">

                                                        <?php if($existingFeeder): ?>
                                                            <input type="text" class="form-control feeder_subtype"
                                                                style="width:160px;" id="feeder_subtype"
                                                                value="<?php echo e($existingFeeder->FeederSubType->feeder_sub_type ?? ''); ?>"
                                                                readonly>
                                                        <?php else: ?>
                                                            <select name="feeders[<?php echo e($i); ?>][subtype]"
                                                                style="width:160px;" id="feeder_subtype"
                                                                class="form-control feeder_subtype">
                                                                <option value="">Select</option>
                                                                <?php $__currentLoopData = $feedersubtype; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($sub->feeder_sub_type_id); ?>">
                                                                        <?php echo e($sub->feeder_sub_type); ?>

                                                                    </option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </select>
                                                        <?php endif; ?>
                                                    </div>

                                                    
                                                    <div class="col-md-2" style="width:15%">

                                                        <?php if($existingFeeder): ?>
                                                            <input type="text" class="form-control"
                                                                style="width:160px;"
                                                                value="<?php echo e($existingFeeder->door_cover); ?>" readonly>
                                                        <?php else: ?>
                                                            <select name="feeders[<?php echo e($i); ?>][door]"
                                                                class="form-control" style="width:160px;">
                                                                <option value="">Select</option>
                                                                <option value="Door">Door</option>
                                                                <option value="Cover">Cover</option>
                                                            </select>
                                                        <?php endif; ?>
                                                    </div>

                                                    <div class="col-md-2 d-flex justify-content-center" style="width:24%">
                                                        <input type="text"
                                                            name="feeders[<?php echo e($i); ?>][customer_remarks]"
                                                            class="form-control feeder-customer_remarks-input"
                                                            style="width:350px;"
                                                            value="<?php echo e($existingFeeder->customer_remarks ?? ''); ?>"
                                                            <?php echo e($existingFeeder ? 'disabled' : ''); ?>>
                                                    </div>

                                                </div>
                                            <?php endfor; ?>
                                            <button type="submit" class="btn btn-success mt-2 save-feeder-btn">Save
                                                Feeders</button>
                                        </form>
                                    <?php endif; ?>


                                    

                                    <table class="table table-sm table-bordered mt-3">
                                        <thead class="thead-light">
                                            <tr>
                                                <th width="60">Sr No.</th>
                                                <th width="80">Name</th>
                                                <th width="70">Height</th>
                                                <th>Category</th>
                                                <th>Type</th>
                                                <th>Sub Type</th>
                                                <th width="70">Door/Cover</th>
                                                <th width="120">Customer Remarks</th>
                                                <th width="120">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">

                                            <?php $__currentLoopData = $section->feeders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $feeder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($key + 1); ?></td>
                                                    <td><?php echo e($feeder->feeder_name); ?></td>
                                                    <td><?php echo e($feeder->height); ?></td>
                                                    <td>
                                                        <?php echo e($feeder->FeederCategory->feeder_category_name ?? '-'); ?>

                                                    </td>

                                                    <td>
                                                        <?php echo e($feeder->FeederType->feeder_type ?? '-'); ?>

                                                    </td>

                                                    <td>
                                                        <?php echo e($feeder->FeederSubType->feeder_sub_type ?? '-'); ?>

                                                    </td>
                                                    <td><?php echo e($feeder->door_cover); ?></td>
                                                    <td><?php echo e(\Illuminate\Support\Str::limit($feeder->customer_remarks, 50, '...')); ?>

                                                    </td>
                                                    <td>

                                                        <button class="btn btn-sm btn-warning" data-toggle="modal"
                                                            data-target="#editFeederModal<?php echo e($feeder->id); ?>">
                                                            <i class="fas fa-edit"></i>

                                                        </button>

                                                        <form action="<?php echo e(route('feeder.delete', $feeder->id)); ?>"
                                                            method="POST" class="delete-section-form"
                                                            style="display:inline;">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger delete-btn">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>

                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        </tbody>
                                    </table>


                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>

                    <div class="card shadow-sm">
                        <div class="card-header">
                            Panel Preview (Scaled)
                        </div>

                        <div class="card-body">
                            <div
                                style="overflow:auto; border:1px solid #ccc; padding:10px; display:flex; align-items:flex-start;">
                                <?php
                                    $fixedHeight = $panel->frame_height;
                                    $sectionHeaderHeight = 0; // no top header space in this preview
                                    $rollerHeight = $fixedHeight * $scale + $sectionHeaderHeight;
                                ?>

                                <div
                                    style="
                                        width:48px;
                                        min-width:48px;
                                        height: <?php echo e($rollerHeight); ?>px;
                                        position:relative;
                                        border-right:1px solid #000;
                                        background:#fff;
                                        box-sizing:border-box;
                                        margin-right:40px;
                                        overflow:visible;
                                    ">

                                    <?php for($i = $fixedHeight; $i >= 0; $i -= 100): ?>
                                        <?php
                                            $lineTop = $sectionHeaderHeight + ($fixedHeight - $i) * $scale;

                                            if ($i == $fixedHeight) {
                                                $lineTop = $sectionHeaderHeight + 2;
                                            }

                                            if ($i == 0) {
                                                $lineTop = $sectionHeaderHeight + $fixedHeight * $scale - 1;
                                            }
                                        ?>

                                        <div
                                            style="
                                                position:absolute;
                                                top: <?php echo e($lineTop); ?>px;
                                                left:0;
                                                width:100%;
                                                height:0;
                                            ">

                                            
                                            <span
                                                style="
                                                    position:absolute;
                                                    right:0;
                                                    width:12px;
                                                    border-top:1px solid #777;
                                                ">
                                            </span>

                                            
                                            <span
                                                style="
                                                    position:absolute;
                                                    right:14px;
                                                    top:-4px;
                                                    font-size:7px;
                                                    line-height:1;
                                                    color:#333;
                                                    background:#fff;
                                                    white-space:nowrap;
                                                    padding:0;
                                                ">
                                                <?php echo e($i); ?>

                                            </span>
                                        </div>
                                    <?php endfor; ?>
                                </div>

                                <div
                                    style="
                                            display:flex;
                                            width: <?php echo e($panel->frame_width * $scale); ?>px;
                                            height: <?php echo e($fixedHeight * $scale); ?>px;
                                            box-sizing: border-box;
                                            border-left:2px solid black;
                                            align-items:flex-start;
                                        ">

                                    <?php $__currentLoopData = $sectionsToShow->take(1); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $totalHeight = $section->feeders->sum('height');
                                            $remainingHeight = $fixedHeight - $totalHeight;
                                        ?>

                                        <div
                                            style="
                                                width: <?php echo e($section->width * $scale); ?>px;
                                                height: 100%;
                                                border-right:1px solid black;
                                                border-top:1px solid black;
                                                border-bottom:1px solid black;
                                                display:flex;
                                                flex-direction:column;
                                                overflow:hidden;
                                                box-sizing:border-box;
                                            ">
                                            <div
                                                style="
                                                    height:20px;
                                                    background:#e9ecef;
                                                    text-align:center;
                                                    font-size:10px;
                                                    border-bottom:1px solid black;
                                                ">
                                            </div>
                                            <div
                                                style="
                                                    position:absolute;
                                                    top:-16px;
                                                    left:0;
                                                    width:100%;
                                                    text-align:center;
                                                    font-size:20px;
                                                    line-height:1.1;
                                                    font-weight:600;
                                                    z-index:10;
                                                    pointer-events:none;
                                                    background:transparent;
                                                ">
                                                <?php echo e($section->section_name); ?> (<?php echo e($section->width); ?>)
                                            </div>

                                            <?php $__currentLoopData = $section->feeders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feeder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div
                                                    style="
                                                        height: <?php echo e($feeder->height * $scale); ?>px;
                                                        min-height: <?php echo e($feeder->height * $scale); ?>px;
                                                        border-bottom:1px solid black;
                                                        position:relative;
                                                        display:flex;
                                                        align-items:center;
                                                        justify-content:center;
                                                        font-size:10px;
                                                        box-sizing:border-box;
                                                        background:#f8f9fa;
                                                    ">

                                                    
                                                    <div style="text-align:center; line-height:1.35;">
                                                        <div><?php echo e($feeder->feeder_name); ?></div>
                                                        <?php if(!empty($feeder->FeederCategory->feeder_category_name)): ?>
                                                            <div>
                                                                <?php echo e($feeder->FeederCategory->feeder_category_name); ?>

                                                            </div>
                                                            <div>
                                                                <?php echo e($feeder->FeederType->feeder_type); ?>

                                                            </div>
                                                            <div>
                                                                <?php echo e($feeder->FeederSubType->feeder_sub_type); ?>

                                                            </div>
                                                        <?php endif; ?>
                                                    </div>

                                                    
                                                    <?php
                                                        $arrowSide =
                                                            $section->lock_position == 'Left' ? 'right' : 'left';
                                                        $arrowOffset = '24px';
                                                        $heightOffset = '2px';
                                                    ?>

                                                    <div
                                                        style="
                                                            position:absolute;
                                                            top:6px;
                                                            bottom:6px;
                                                            <?php echo e($arrowSide); ?>: <?php echo e($arrowOffset); ?>;
                                                            width:0;
                                                            border-left:2px solid rgba(255, 0, 0, 0.65);
                                                            z-index:5;
                                                            pointer-events:none;
                                                        ">

                                                        
                                                        <span
                                                            style="
                                                                    position:absolute;
                                                                    top:-6px;
                                                                    left:-5px;
                                                                    width:0;
                                                                    height:0;
                                                                    border-left:5px solid transparent;
                                                                    border-right:5px solid transparent;
                                                                    border-bottom:8px solid rgba(255, 0, 0, 0.65);
                                                                ">
                                                        </span>

                                                        
                                                        <span
                                                            style="
                                                                    position:absolute;
                                                                    bottom:-6px;
                                                                    left:-5px;
                                                                    width:0;
                                                                    height:0;
                                                                    border-left:5px solid transparent;
                                                                    border-right:5px solid transparent;
                                                                    border-top:8px solid rgba(255, 0, 0, 0.65);
                                                                ">
                                                        </span>
                                                    </div>

                                                    <div
                                                        style="
                                                                position:absolute;
                                                                <?php echo e($arrowSide); ?>: <?php echo e($heightOffset); ?>;
                                                                top:50%;
                                                                transform:translateY(-50%);
                                                                font-size:9px;
                                                                color:rgba(255, 0, 0, 0.65);
                                                                font-weight:600;
                                                                line-height:1;
                                                            ">
                                                        <?php echo e($feeder->height); ?>

                                                    </div>
                                                    <?php if($feeder->door_cover == 'Door'): ?>
                                                        <?php if($section->lock_position == 'Left'): ?>
                                                            <span
                                                                style="
                                                                position:absolute;
                                                                left:4px;
                                                                top:50%;
                                                                transform:translateY(-50%);
                                                                width:14px;
                                                                height:14px;
                                                                border:2px solid black;
                                                                border-radius:4px;
                                                                background:#fff;
                                                            "></span>
                                                        <?php elseif($section->lock_position == 'Right'): ?>
                                                            <span
                                                                style="
                                                                position:absolute;
                                                                right:4px;
                                                                top:50%;
                                                                transform:translateY(-50%);
                                                                width:14px;
                                                                height:14px;
                                                                border:2px solid black;
                                                                border-radius:4px;
                                                                background:#fff;
                                                            "></span>
                                                        <?php endif; ?>
                                                    <?php endif; ?>

                                                    <?php if($feeder->door_cover == 'Cover'): ?>
                                                        <div class="cover-dot dot-tl">â€¢</div>
                                                        <div class="cover-dot dot-tr">â€¢</div>
                                                        <div class="cover-dot dot-bl">â€¢</div>
                                                        <div class="cover-dot dot-br">â€¢</div>
                                                    <?php endif; ?>

                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            <?php if($remainingHeight > 0): ?>
                                                <div
                                                    style="
                                                        height: <?php echo e($remainingHeight * $scale); ?>px;
                                                        border-bottom:1px dashed #ccc;
                                                    ">
                                                </div>
                                            <?php endif; ?>

                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </div>

                            </div>
                        </div>
                    </div>

                    
                    <?php $__currentLoopData = $sectionsToShow; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $__currentLoopData = $section->feeders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feeder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="modal fade" id="editFeederModal<?php echo e($feeder->id); ?>">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <form method="POST" action="<?php echo e(route('feeder.update', $feeder->id)); ?>">
                                            <?php echo csrf_field(); ?>

                                            <div class="modal-header">
                                                <h5>Edit Feeder</h5>
                                                <button type="button" class="close"
                                                    data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Name</label>
                                                    <input type="text" name="feeder_name"
                                                        value="<?php echo e($feeder->feeder_name); ?>" class="form-control" readonly>
                                                </div>

                                                <div class="form-group">
                                                    <label>Height</label>
                                                    <input type="number" name="height" value="<?php echo e($feeder->height); ?>"
                                                        class="form-control" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Feeder Category</label>
                                                    <select name="feeder_category" class="form-control feeder_category">
                                                        <option value="">Please Select</option>
                                                        <?php $__currentLoopData = $feedercategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feedercat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($feedercat->feeder_category_id); ?>"
                                                                <?php echo e($feedercat->feeder_category_id == $feeder->f_category_id ? 'selected' : ''); ?>>
                                                                <?php echo e($feedercat->feeder_category_name); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Feeder Type</label>
                                                    <select name="feeder_type" class="form-control feeder_type">
                                                        <option value="">Please Select</option>
                                                        <?php $__currentLoopData = $feedertype; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feederty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($feederty->feeder_type_id); ?>"
                                                                <?php echo e($feederty->feeder_type_id == $feeder->f_type_id ? 'selected' : ''); ?>>
                                                                <?php echo e($feederty->feeder_type); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Feeder Sub Type</label>
                                                    <select name="feeder_subtype" class="form-control feeder_subtype">
                                                        <option value="">Please Select</option>
                                                        <?php $__currentLoopData = $feedersubtype; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feedersub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($feedersub->feeder_sub_type_id); ?>"
                                                                <?php echo e($feedersub->feeder_sub_type_id == $feeder->f_subtype_id ? 'selected' : ''); ?>>
                                                                <?php echo e($feedersub->feeder_sub_type); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Door/Cover</label>
                                                    <select name="door_cover" class="form-control">
                                                        <option value="">Please Select</option>
                                                        <option value="Door"
                                                            <?php echo e($feeder->door_cover == 'Door' ? 'selected' : ''); ?>>
                                                            Door
                                                        </option>
                                                        <option value="Cover"
                                                            <?php echo e($feeder->door_cover == 'Cover' ? 'selected' : ''); ?>>
                                                            Cover
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Customer Remarks</label>
                                                    <input type="text" name="customer_remarks"
                                                        value="<?php echo e($feeder->customer_remarks); ?>" class="form-control">
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button class="btn btn-success">
                                                    Update
                                                </button>
                                            </div>

                                        </form>

                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    <?php $__env->stopSection(); ?>
    <?php $__env->startSection('scripts'); ?>
        <script>
            // =============================================
            // FUNCTION: Load feeder categories into all
            // feeder_category dropdowns by section_type_id
            // =============================================
            function loadFeederCategories(sectionTypeId) {
                if (!sectionTypeId) {
                    $('select.feeder_category').html('<option value="">Select</option>');
                    return;
                }

                console.log('Calling URL:', '/get-feeder-categories/' + sectionTypeId);

                $.ajax({
                    url: '/Actifab/get-feeder-categories/' + sectionTypeId,
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    success: function(data) {
                        console.log('Success:', data);

                        let options = '<option value="">Select</option>';
                        data.forEach(function(cat) {
                            options += `<option value="${cat.feeder_category_id}">
                    ${cat.feeder_category_name}
                </option>`;
                        });

                        $('select.feeder_category').html(options);
                        $('select.feeder_type').html('<option value="">Select</option>');
                        $('select.feeder_subtype').html('<option value="">Select</option>');
                    },
                    error: function(xhr) {
                        // ðŸ‘‡ This will show you EXACTLY what error is coming back
                        console.error('Status:', xhr.status);
                        console.error('Response:', xhr.responseText);
                        $('select.feeder_category').html('<option value="">Error loading</option>');
                    }
                });
            }

            // =============================================
            // ON PAGE LOAD â†’ if edit mode, auto-load
            // categories based on saved section_type_id
            // =============================================
            $(document).ready(function() {

                // Edit mode: section_type already selected â†’ load categories
                let sectionTypeOnLoad = $('#current_section_type_id').val();
                if (sectionTypeOnLoad) {
                    loadFeederCategories(sectionTypeOnLoad);
                }

                // When admin changes section type in the Add Section form
                $('#section_type_select').on('change', function() {
                    let sectionTypeId = $(this).val();
                    loadFeederCategories(sectionTypeId);
                });

            });
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {

                function updateRemainingHeight(sectionCard) {
                    const remainingEl = sectionCard.querySelector(".live-remaining-height");
                    const fullMsgEl = sectionCard.querySelector(".live-height-message");
                    const exceedMsgEl = sectionCard.querySelector(".live-height-exceed-message");
                    const saveBtn = sectionCard.querySelector(".save-feeder-btn");

                    const inputs = Array.from(sectionCard.querySelectorAll(".feeder-height-input"));

                    if (!remainingEl || inputs.length === 0) return;

                    const frameHeight = parseInt(remainingEl.dataset.frameHeight || 0, 10);
                    const usedHeight = parseInt(remainingEl.dataset.usedHeight || 0, 10);

                    let newInputTotal = 0;

                    inputs.forEach(input => {
                        if (!input.disabled) {
                            const val = parseInt(input.value || 0, 10);
                            newInputTotal += isNaN(val) ? 0 : val;
                        }
                        input.classList.remove("is-invalid");
                    });

                    const totalUsed = usedHeight + newInputTotal;
                    const remaining = frameHeight - totalUsed;

                    remainingEl.textContent = remaining > 0 ? remaining : 0;

                    if (fullMsgEl) fullMsgEl.style.display = "none";
                    if (exceedMsgEl) exceedMsgEl.style.display = "none";
                    if (saveBtn) saveBtn.disabled = false;

                    if (totalUsed === frameHeight) {
                        if (fullMsgEl) {
                            fullMsgEl.style.display = "block";
                            fullMsgEl.textContent = "Your feeder height is used.";
                        }
                    }

                    if (totalUsed > frameHeight) {
                        if (exceedMsgEl) {
                            exceedMsgEl.style.display = "block";
                            exceedMsgEl.textContent =
                                "Your feeder height is already used. You cannot enter more height.";
                        }
                        if (saveBtn) saveBtn.disabled = true;
                    }

                    // next empty textbox readonly when height full
                    let runningTotal = usedHeight;
                    inputs.forEach(input => {
                        if (input.disabled) return;

                        const row = input.closest('.row'); // ðŸ‘ˆ same row pakadna

                        const val = parseInt(input.value || 0, 10);
                        const safeVal = isNaN(val) ? 0 : val;

                        const otherFields = row.querySelectorAll(
                            '.feeder_category, .feeder_type, .feeder_subtype, select, .feeder-customer_remarks-input'
                        );

                        if (runningTotal >= frameHeight && !input.value) {

                            // Height readonly
                            input.setAttribute("readonly", true);
                            input.style.backgroundColor = "#f1f1f1";
                            input.style.cursor = "not-allowed";

                            // ðŸ‘‡ Other fields disable
                            otherFields.forEach(field => {
                                field.setAttribute("disabled", true);
                                field.style.backgroundColor = "#f1f1f1";
                                field.style.cursor = "not-allowed";
                            });

                        } else {

                            input.removeAttribute("readonly");
                            input.style.backgroundColor = "";
                            input.style.cursor = "";

                            // ðŸ‘‡ enable back
                            otherFields.forEach(field => {
                                field.removeAttribute("disabled");
                                field.style.backgroundColor = "";
                                field.style.cursor = "";
                            });
                        }

                        if (runningTotal >= frameHeight && safeVal > 0) {
                            input.classList.add("is-invalid");
                        }

                        runningTotal += safeVal;
                    });
                }

                document.querySelectorAll(".section-card-live").forEach(sectionCard => {
                    updateRemainingHeight(sectionCard);

                    sectionCard.querySelectorAll(".feeder-height-input").forEach(input => {
                        input.addEventListener("input", function() {
                            updateRemainingHeight(sectionCard);
                        });
                    });
                });

            });
        </script>

        <script>
            setTimeout(function() {
                let alertBox = document.getElementById('success-alert');
                if (alertBox) {
                    alertBox.style.transition = "opacity 0.5s ease";
                    alertBox.style.opacity = "0";

                    setTimeout(() => {
                        alertBox.remove();
                    }, 500);
                }
            }, 5000); // 5000ms = 5 sec
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let sectionSelect = document.querySelector('[name="section_id"]');
                let feederInput = document.querySelector('[name="feeder_name"]');

                if (sectionSelect && feederInput) {
                    sectionSelect.addEventListener('change', function() {
                        let sectionId = this.value;

                        if (sectionId) {
                            fetch(`/get-feeder-count/${sectionId}`)
                                .then(res => res.json())
                                .then(data => {
                                    let nextNumber = data.count + 1;
                                    feederInput.value = sectionId + 'F' + nextNumber;
                                });
                        }
                    });
                }
            });
        </script>

        <script>
            $(document).on('change', '.feeder_category', function() {

                let categoryId = $(this).val();

                // same form ke andar ke type & subtype pakadenge
                let form = $(this).closest('.row');

                let typeDropdown = form.find('.feeder_type');
                let subTypeDropdown = form.find('.feeder_subtype');

                typeDropdown.html('<option value="">Loading...</option>');
                subTypeDropdown.html('<option value="">Please Select</option>');

                if (categoryId !== '') {

                    $.ajax({
                        url: '/Actifab/get-feeder-types/' + categoryId,
                        type: 'GET',
                        success: function(data) {

                            let options = '<option value="">Please Select</option>';

                            data.forEach(function(type) {
                                options += `<option value="${type.feeder_type_id}">
                                    ${type.feeder_type}
                                </option>`;
                            });

                            typeDropdown.html(options);
                        }
                    });

                }
            });


            $(document).on('change', '.feeder_type', function() {

                let typeId = $(this).val();

                let form = $(this).closest('.row');

                let typeDropdown = form.find('.feeder_subtype');
                let subTypeDropdown = form.find('.feeder_subtype');

                subTypeDropdown.html('<option value="">Loading...</option>');

                if (typeId !== '') {

                    $.ajax({
                        url: '/Actifab/get-feeder-subtypes/' + typeId,
                        type: 'GET',
                        success: function(data) {

                            let options = '<option value="">Please Select</option>';

                            data.forEach(function(sub) {
                                options += `<option value="${sub.feeder_sub_type_id}">
                                    ${sub.feeder_sub_type}
                                </option>`;
                            });

                            subTypeDropdown.html(options);
                        }
                    });

                }
            });
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {

                document.querySelectorAll(".delete-btn").forEach(function(button) {

                    button.addEventListener("click", function() {

                        let form = this.closest("form");

                        Swal.fire({
                            title: "Are you sure?",
                            text: "This section will be permanently deleted!",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#d33",
                            cancelButtonColor: "#3085d6",
                            confirmButtonText: "Yes, delete it!"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });

                    });

                });

            });
        </script>
    <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/getdemo/Actifab/resources/views/panel/manage.blade.php ENDPATH**/ ?>