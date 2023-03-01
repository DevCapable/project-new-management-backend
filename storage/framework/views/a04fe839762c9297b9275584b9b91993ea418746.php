<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Add new Task')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('links'); ?>
    <?php if(\Auth::guard('client')->check()): ?>
        <li class="breadcrumb-item"><a href="<?php echo e(route('client.home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <?php else: ?>
        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <?php endif; ?>
    <li class="breadcrumb-item"> <?php echo e(__('Add new Task List')); ?></li>
<?php $__env->stopSection(); ?>
<?php

    $client_keyword = Auth::user()->getGuard() == 'client' ? 'client.' : '';
?>

<?php $__env->startSection('content'); ?>

    <!--  <div class="col-lg-12 appointmentreportdata p-0">
 </div> -->

    <form class="" method="post" action="<?php if(auth()->guard('client')->check()): ?><?php echo e(route('client-task-store',[$currentWorkspace->slug,$project->project_id])); ?> <?php elseif(auth()->guard('web')->check()): ?><?php echo e(route('admin-task-store',[$currentWorkspace->slug,$project->project_id])); ?> <?php endif; ?>">
        <?php echo csrf_field(); ?>
        <div>
            <div class="row">
                <input type="hidden"  id="project_id"
                       name="project_id" value="<?php echo e($project->project_id); ?>" required autocomplete="off">
                <?php if(auth()->guard('web')->check()): ?>
                <input type="hidden"  id="status"
                       name="status" value="UnderReview" required autocomplete="off">

                <?php endif; ?>

                <?php echo $__env->make('projects.task._admin_task_form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn  btn-primary right"><?php echo e(__('Add New Task(s)')); ?></button>
        </div>


    </form>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('custom/libs/bootstrap-daterangepicker/daterangepicker.js')); ?>"></script>
    <?php echo $__env->make('projects.task._add_more_fields_js', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/heritage/SHINE/LATEST/projectmanagement/resources/views/projects/task/_create_task.blade.php ENDPATH**/ ?>