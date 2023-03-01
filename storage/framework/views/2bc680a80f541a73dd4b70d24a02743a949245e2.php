<?php $__env->startComponent('mail::message'); ?>
# <?php echo e(__('Hello')); ?>, <?php if($user->name!='No Name'): ?><?php echo e($user->name); ?><?php endif; ?>

<?php echo e(__('You are invited into new project')); ?> <b> <?php echo e($project->name); ?></b> <?php echo e(__('by')); ?> <?php echo e($project->creater->name); ?>


<?php $__env->startComponent('mail::button', ['url' => route('projects.show',[$project->workspaceData->slug,$project->id])]); ?>
<?php echo e(__('Open Project')); ?>

<?php echo $__env->renderComponent(); ?>

<?php echo e(__('Thanks')); ?>,<br>
<?php echo e(config('app.name')); ?>

<?php echo $__env->renderComponent(); ?>
<?php /**PATH /home/heritage/SHINE/LATEST/projectmanagement/resources/views/email/invitation.blade.php ENDPATH**/ ?>