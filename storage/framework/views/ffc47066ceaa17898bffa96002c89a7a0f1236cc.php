<form class="" method="post" action="<?php echo e(route('projects.share',[$currentWorkspace->slug,$project->id])); ?>">
    <?php echo csrf_field(); ?>
     <div class="modal-body">
    <div class=" col-md-12 mb-0">
        <label for="users_list" class="col-form-label"><?php echo e(__('Clients')); ?></label>
        <select class="multi-select" id="clients" data-toggle="select2" required name="clients[]" multiple="multiple" data-placeholder="<?php echo e(__('Select Clients ...')); ?>">
            <?php $__currentLoopData = $currentWorkspace->clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($client->pivot->is_active): ?>
                    <option value="<?php echo e($client->id); ?>"><?php echo e($client->name); ?> - <?php echo e($client->email); ?></option>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
</div>
    <div class="modal-footer">
           <button type="button" class="btn  btn-light" data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>
             <input type="submit" value="<?php echo e(__('Share to Client')); ?>" class="btn  btn-primary">
        </div>
</form>
<?php /**PATH /home/heritage/SHINE/LATEST/projectmanagement/resources/views/projects/share.blade.php ENDPATH**/ ?>