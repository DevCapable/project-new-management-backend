<form class="" method="post" action="<?php echo e(route('projects.invite.update',[$currentWorkspace->slug,$project->id])); ?>">
    <?php echo csrf_field(); ?>
     <div class="modal-body">
    <div class="form-group col-md-12 mb-0">
        <label for="users_list" class="form-label"><?php echo e(__('Users')); ?></label>
        <select class=" multi-select" required id="users_list" name="users_list[]" data-toggle="select2" multiple="multiple" data-placeholder="<?php echo e(__('Select Users ...')); ?>">
            <?php $__currentLoopData = $currentWorkspace->users($currentWorkspace->created_by); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($user->pivot->is_active): ?>
                    <option value="<?php echo e($user->email); ?>"><?php echo e($user->name); ?> - <?php echo e($user->email); ?></option>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
</div>
    <div class="modal-footer">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>
        <input type="submit" value="<?php echo e(__('Invite')); ?>" class="btn  btn-primary">
    </div>
</form>
<?php /**PATH /home/heritage/SHINE/LATEST/projectmanagement/resources/views/projects/invite.blade.php ENDPATH**/ ?>