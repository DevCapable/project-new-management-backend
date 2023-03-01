<?php if($message = Session::get('success')): ?>
    <div class="alert alert-success alert-block">
        <i class="fa fa-check-circle-o"></i>
        <strong><?php echo e($message); ?></strong>
    </div>
<?php endif; ?>

<?php if($message = Session::get('error')): ?>
    <div class="alert alert-danger alert-block">
       <i class="fa fa-check-circle-o"></i>
        <strong><?php echo e($message); ?></strong>
    </div>
<?php endif; ?>

<?php if($message = Session::get('warning')): ?>
    <div class="alert alert-warning alert-block">
        <i class="fa fa-check-circle-o"></i>
        <strong><?php echo e($message); ?></strong>
    </div>
<?php endif; ?>

<?php if($message = Session::get('info')): ?>
    <div class="alert alert-info alert-block">
        <i class="fa fa-check-circle-o"></i>
        <strong><?php echo e($message); ?></strong>
    </div>
<?php endif; ?>

<?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <ul type="disc">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <li><i class="fa fa-asterisk"></i><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<?php if(isset($warning)): ?>
    <div class="alert alert-warning alert-block">
        <i class="fa fa-check-circle-o"></i>
        <strong><?php echo $warning; ?></strong>
    </div>
<?php endif; ?>

<?php if(isset($danger)): ?>
    <div class="alert alert-danger alert-block">
        <i class="fa fa-check-circle-o"></i>
        <strong><?php echo e($danger); ?></strong>
    </div>
<?php endif; ?>
<?php if(isset($info)): ?>
    <div class="alert alert-info alert-block">
        <i class="fa fa-check-circle-o"></i>
        <strong><?php echo e($info); ?></strong>
    </div>
<?php endif; ?>
<?php /**PATH /home/heritage/SHINE/LATEST/projectmanagement/resources/views/partials/_notifications.blade.php ENDPATH**/ ?>