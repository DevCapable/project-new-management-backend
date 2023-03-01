<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Project')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('links'); ?>
    <?php if(\Auth::guard('client')->check()): ?>
        <li class="breadcrumb-item"><a href="<?php echo e(route('client.home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <?php else: ?>
        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <?php endif; ?>
    <li class="breadcrumb-item"> <?php echo e(__('Project')); ?></li>
<?php $__env->stopSection(); ?>
<?php

    $client_keyword = Auth::user()->getGuard() == 'client' ? 'client.' : '';
?>

<?php $__env->startSection('content'); ?>

    <!--  <div class="col-lg-12 appointmentreportdata p-0">
 </div> -->

    <form class="" method="post" action="<?php if(auth()->guard('client')->check()): ?><?php echo e(route('store-client-project',$currentWorkspace->slug)); ?><?php elseif(auth()->guard('web')->check()): ?><?php echo e(route('store-admin-project',$currentWorkspace->slug)); ?> <?php endif; ?>">
        <?php echo csrf_field(); ?>
        <div>
            <div class="row">
                <div class="form-group col-md-12">
                    <label for="project_name" class="col-form-label"><?php echo e(__('Name')); ?></label>
                    <input class="form-control" type="text" id="project_name" name="project_name" required=""
                           placeholder="<?php echo e(__('Project Name')); ?>">
                    <?php if(auth()->guard('client')->check()): ?>
                        <input type="hidden" value="<?php echo e(generate_project_id('CLI')); ?>" name="project_id">
                    <?php endif; ?>
                </div>
                <div class="form-group col-md-12">
                    <label for="description" class="col-form-label"><?php echo e(__('Description')); ?></label>
                    <textarea class="form-control" id="project_description" name="project_description" required=""
                              placeholder="<?php echo e(__('Add Description')); ?>"></textarea>
                </div>
                <?php if(auth()->guard('web')->check()): ?>
                    <input type="hidden" value="<?php echo e(generate_project_id('ADM')); ?>" name="project_id">
                    <div class="col-md-12">
                        <label for="users_list" class="col-form-label"><?php echo e(__('Users')); ?></label>
                        <select class=" multi-select" id="users_list" name="users_list[]" data-toggle="select2" multiple="multiple" data-placeholder="<?php echo e(__('Select Users ...')); ?>">
                            <?php $__currentLoopData = $currentWorkspace->users($currentWorkspace->created_by); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($user->email); ?>"><?php echo e($user->name); ?> - <?php echo e($user->email); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                <?php endif; ?>
                <div class="form-group col-md-12">
                    <label for="budget" class="form-label"><?php echo e(__('Budget')); ?></label>
                    <div class="form-icon-user ">
                        <span
                            class="currency-icon bg-primary "><?php echo e((!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'); ?></span>
                        <input class="form-control currency_input" type="number" min="0" id="budget" name="budget"
                               value="<?php echo e($currentWorkspace->budget); ?>" placeholder="<?php echo e(__('Project Budget')); ?>">
                    </div>
                </div>

            </div>
            <?php echo $__env->make('projects.task._admin_task_form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        </div>
        <div class="card-footer">
            <button type="submit" name="action" value="save" class="btn  btn-primary right"><?php echo e(__('Continue')); ?></button>
        </div>


    </form>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('custom/libs/bootstrap-daterangepicker/daterangepicker.js')); ?>"></script>
    <?php echo $__env->make('projects.task._add_more_fields_js', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/heritage/PROJECTS/NEW/management/resources/views/projects/create.blade.php ENDPATH**/ ?>