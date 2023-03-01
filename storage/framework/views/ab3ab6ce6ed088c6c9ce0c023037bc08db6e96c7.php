<?php $__env->startComponent('mail::message'); ?>
# <?php echo e(__('Hello')); ?>, <?php echo e($user->name != 'No Name' ? $user->name : ''); ?>


<?php echo e(__('You have been assigned to a new task')); ?> <b> <?php echo e($task->title); ?></b> <?php echo e(__('attached to a project, ')); ?> <b><?php echo e($task->project->name); ?>

<br>
<?php echo e(__('Please react to this as soon as possible')); ?>

<?php $__env->startComponent('mail::button', ['url' => route('home',[$task->slug])]); ?>
    <?php echo e(__('Open Workspace')); ?>

<?php echo $__env->renderComponent(); ?>

<?php echo e(__('Thanks')); ?>,<br>
<?php echo e(config('app.name')); ?>

<?php echo $__env->renderComponent(); ?>
<?php /**PATH /home/heritage/SHINE/LATEST/projectmanagement/resources/views/email/new_task_assigned_notification.blade.php ENDPATH**/ ?>