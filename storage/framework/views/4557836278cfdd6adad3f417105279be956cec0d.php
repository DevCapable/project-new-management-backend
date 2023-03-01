<?php if(isset($uploadedFiles)): ?>
    <?php $__currentLoopData = $uploadedFiles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($task->task_id === $file->type_id): ?>

            <?php if($currentWorkspace->permission == 'Owner'): ?>
                <ul>
                    <li>  <?php if(auth()->guard('client')->check()): ?>
                            <a
                                href="<?php echo e(route('client-tasks-download',[$currentWorkspace->slug,$file->id,])); ?>"><i
                                    class="ti ti-download"></i><?php echo e($file->name?:'N/A'); ?></a>
                        <?php elseif(auth()->guard('web')->check()): ?>
                            <a
                                href="<?php echo e(route('admin-tasks-download',[$currentWorkspace->slug,$file->id,])); ?>"><i
                                    class="ti ti-download"></i><?php echo e($file->name?:'N/A'); ?></a>
                        <?php endif; ?>

                <a href="#"
                   class="action-btn btn-danger mx-1  btn btn-sm d-inline-flex align-items-center bs-pass-para"
                   data-confirm="<?php echo e(__('Are You Sure?')); ?>"
                   data-toggle="popover" title="<?php echo e(__('Delete')); ?>"
                   data-text="<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>"
                   data-confirm-yes="delete-form1-<?php echo e($file->id); ?>"><i
                        class="ti ti-trash"></i></a>
                <form id="delete-form1-<?php echo e($file->id); ?>"
                      action="<?php echo e(route('tasks-file-destroy',[$currentWorkspace->slug,$file->id])); ?>"
                      method="POST" style="display: none;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                </form>
                    </li>
                </ul>
            <?php endif; ?>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>
<?php /**PATH /home/heritage/SHINE/LATEST/projectmanagement/resources/views/projects/task/_task_uploaded_files.blade.php ENDPATH**/ ?>