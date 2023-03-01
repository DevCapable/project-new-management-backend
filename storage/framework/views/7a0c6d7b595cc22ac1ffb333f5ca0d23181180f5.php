<?php $__env->startComponent('mail::message'); ?>
# <?php echo e(__('Hello')); ?>, <?php echo e($user->name != 'No Name' ? $user->name : ''); ?>


<?php echo e(__('This amount '.$invoicePayments->amount_paid.' has been paid successfuly for the execution of your project named ')); ?> <b> <?php echo e($project->name); ?></b> <?php echo e(__('and this payment is liable to expired as soon as theres expansion. This project was initiated  by')); ?> <?php echo e($project->creater->name); ?>

<br>

<?php $__env->startComponent('mail::button', ['url' => route('home',[$project->slug])]); ?>
    <?php echo e(__('Go to your profile')); ?>

<?php echo $__env->renderComponent(); ?>


<?php echo e(config('app.name')); ?><br>
<?php echo e(__('Regards')); ?>,
<?php echo $__env->renderComponent(); ?>
<?php /**PATH /home/heritage/SHINE/LATEST/projectmanagement/resources/views/email/user_project_receipt_notification.blade.php ENDPATH**/ ?>