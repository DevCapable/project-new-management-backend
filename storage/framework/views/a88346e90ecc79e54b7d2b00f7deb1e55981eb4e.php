<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Languages')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('links'); ?>
<?php if(\Auth::guard('client')->check()): ?>   
<li class="breadcrumb-item"><a href="<?php echo e(route('client.home')); ?>"><?php echo e(__('Home')); ?></a></li>
 <?php else: ?>
 <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
 <?php endif; ?>
<li class="breadcrumb-item"> <?php echo e(__('Manage Languages')); ?></li>
<?php $__env->stopSection(); ?>



<?php $__env->startSection('action-button'); ?>
    
    <?php if($currantLang != (env('DEFAULT_LANG') ?? 'en')): ?>
        <div class="col-auto">
                <a href="#" class="btn btn-sm btn-danger bs-pass-para" data-toggle="tooltip" title="<?php echo e(__('Delete This Language')); ?>" data-confirm="<?php echo e(__('Are You Sure?')); ?>" data-text="<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="delete-form-<?php echo e($currantLang); ?>"><i class="ti ti-trash text-white"></i> </a>

                <?php echo Form::open(['method' => 'DELETE', 'route' => ['lang.destroy', $currantLang],'id'=>'delete-form-'.$currantLang]); ?>

                <?php echo Form::close(); ?>

        </div> 
    <?php endif; ?>

    <div class="col-auto mx-1">
            <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="<?php echo e(__('Create Language')); ?>" data-toggle="tooltip" title="<?php echo e(__('Create Language')); ?>"  data-url="<?php echo e(route('create_lang_workspace')); ?>">
                <i class="ti ti-plus"></i> 
            </a>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body p-3">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <?php $__currentLoopData = $workspace->languages(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('lang_workspace',$lang)); ?>" class="nav-link text-sm font-weight-bold <?php if($currantLang == $lang): ?> active <?php endif; ?>">
                                <i class="d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block"><?php echo e(Str::upper($lang)); ?></span>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
                    <div class="p-3 card">
            <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-user-tab-1" data-bs-toggle="pill"
                        data-bs-target="#labels" type="button"><?php echo e(__('Labels')); ?></button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-user-tab-2" data-bs-toggle="pill"
                        data-bs-target="#messages" type="button"><?php echo e(__('Messages')); ?></button>
                </li>

            </ul>
        </div>
            <div class="card">
                <div class="card-body p-3">
              <!--       <ul class="nav nav-tabs  bordar_styless my-4">
                        <li>
                            <a data-toggle="tab" href="#labels" class="active"><?php echo e(__('Labels')); ?></a>
                        </li>
                        <li class="annual-billing">
                            <a data-toggle="tab" href="#messages" class=""><?php echo e(__('Messages')); ?> </a>
                        </li>
                    </ul>
 -->
                    <form method="post" action="<?php echo e(route('store_lang_data_workspace',$currantLang)); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="tab-content">
                            <div class="tab-pane active" id="labels">
                                <div class="row">
                                    <?php $__currentLoopData = $arrLabel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label text-dark"><?php echo e($label); ?></label>
                                                <input type="text" class="form-control" name="label[<?php echo e($label); ?>]" value="<?php echo e($value); ?>">
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                            <div class="tab-pane" id="messages">
                                <?php $__currentLoopData = $arrMessage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fileName => $fileValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h6><?php echo e(ucfirst($fileName)); ?></h6>
                                        </div>
                                        <?php $__currentLoopData = $fileValue; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if(is_array($value)): ?>
                                                <?php $__currentLoopData = $value; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label2 => $value2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if(is_array($value2)): ?>
                                                        <?php $__currentLoopData = $value2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label3 => $value3): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php if(is_array($value3)): ?>
                                                                <?php $__currentLoopData = $value3; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label4 => $value4): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php if(is_array($value4)): ?>
                                                                        <?php $__currentLoopData = $value4; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label5 => $value5): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <div class="col-lg-6">
                                                                                <div class="form-group mb-3">
                                                                                    <label class="form-label text-dark"><?php echo e($fileName); ?>.<?php echo e($label); ?>.<?php echo e($label2); ?>.<?php echo e($label3); ?>.<?php echo e($label4); ?>.<?php echo e($label5); ?></label>
                                                                                    <input type="text" class="form-control" name="message[<?php echo e($fileName); ?>][<?php echo e($label); ?>][<?php echo e($label2); ?>][<?php echo e($label3); ?>][<?php echo e($label4); ?>][<?php echo e($label5); ?>]" value="<?php echo e($value5); ?>">
                                                                                </div>
                                                                            </div>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php else: ?>
                                                                        <div class="col-lg-6">
                                                                            <div class="form-group mb-3">
                                                                                <label class="form-label text-dark"><?php echo e($fileName); ?>.<?php echo e($label); ?>.<?php echo e($label2); ?>.<?php echo e($label3); ?>.<?php echo e($label4); ?></label>
                                                                                <input type="text" class="form-control" name="message[<?php echo e($fileName); ?>][<?php echo e($label); ?>][<?php echo e($label2); ?>][<?php echo e($label3); ?>][<?php echo e($label4); ?>]" value="<?php echo e($value4); ?>">
                                                                            </div>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            <?php else: ?>
                                                                <div class="col-lg-6">
                                                                    <div class="form-group mb-3">
                                                                        <label class="form-label text-dark"><?php echo e($fileName); ?>.<?php echo e($label); ?>.<?php echo e($label2); ?>.<?php echo e($label3); ?></label>
                                                                        <input type="text" class="form-control" name="message[<?php echo e($fileName); ?>][<?php echo e($label); ?>][<?php echo e($label2); ?>][<?php echo e($label3); ?>]" value="<?php echo e($value3); ?>">
                                                                    </div>
                                                                </div>
                                                            <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php else: ?>
                                                        <div class="col-lg-6">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label text-dark"><?php echo e($fileName); ?>.<?php echo e($label); ?>.<?php echo e($label2); ?></label>
                                                                <input type="text" class="form-control" name="message[<?php echo e($fileName); ?>][<?php echo e($label); ?>][<?php echo e($label2); ?>]" value="<?php echo e($value2); ?>">
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php else: ?>
                                                <div class="col-lg-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label text-dark"><?php echo e($fileName); ?>.<?php echo e($label); ?></label>
                                                        <input type="text" class="form-control" name="message[<?php echo e($fileName); ?>][<?php echo e($label); ?>]" value="<?php echo e($value); ?>">
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <div class="text-end">
                            <input type="submit" value="<?php echo e(__('Save Changes')); ?>" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/heritage/SHINE/LATEST/projectmanagement/resources/views/lang/index.blade.php ENDPATH**/ ?>