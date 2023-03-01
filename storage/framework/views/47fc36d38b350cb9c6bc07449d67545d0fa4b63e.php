<form class="" method="post" action="<?php echo e(route('update-client-project',[$currentWorkspace->slug,$project->project_id])); ?>">
    <?php echo csrf_field(); ?>
    <div class="modal-body">
        <div class="row">
            <div class="form-group col-md-12">
                <label for="projectname" class="form-label"><?php echo e(__('Name')); ?></label>
                <input class="form-control" type="text" id="projectname" name="name" required="" placeholder="<?php echo e(__('Project Name')); ?>" value="<?php echo e($project->name); ?>">
            </div>
            <div class="form-group col-md-12">
                <label for="description" class="form-label"><?php echo e(__('Description')); ?></label>
                <textarea class="form-control" id="description" name="description" required="" placeholder="<?php echo e(__('Add Description')); ?>"><?php echo e($project->description); ?></textarea>
            </div>
            
            
            
            
            
            
            
            

            <div class="form-group col-md-12">
                <label for="budget" class="form-label"><?php echo e(__('Budget')); ?></label>
                <div class="form-icon-user ">
                    <span class="currency-icon bg-primary "><?php echo e((!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'); ?></span>
                    <input class="form-control currency_input" type="number" min="0" id="budget" name="budget" value="<?php echo e($project->budget); ?>" placeholder="<?php echo e(__('Project Budget')); ?>">
                </div>
            </div>
            <div class="form-group col-md-6">
                <label class="form-label"><?php echo e(__('Start Date')); ?></label>


                <div class="input-group date ">
                    <input class="form-control datepicker2" type="text" id="start_date" name="start_date" value="<?php echo e($project->start_date); ?>" autocomplete="off" required="required">
                    <span class="input-group-text">
                 <i class="feather icon-calendar"></i>
            </span>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label class="form-label"><?php echo e(__('End Date')); ?></label>
                <div class="input-group date ">
                    <input class="form-control datepicker3" type="text" id="end_date" name="end_date" value="<?php echo e($project->end_date); ?>" autocomplete="off" required="required">
                    <span class="input-group-text">
                 <i class="feather icon-calendar"></i>
            </span>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>
        <button type="submit" name="action" value="submit" class="btn  btn-danger right"><?php echo e(__('Submit project for review')); ?></button>
        <button type="submit" name="action" value="save" class="btn  btn-primary right"><?php echo e(__('Save changes')); ?></button>
    </div>

</form>

<script>
    (function () {
        const d_week = new Datepicker(document.querySelector('.datepicker2'), {
            buttonClass: 'btn',
            todayBtn: true,
            clearBtn: true,
            format: 'yyyy-mm-dd',
        });
    })();
</script>

<script>
    (function () {
        const d_week = new Datepicker(document.querySelector('.datepicker3'), {
            buttonClass: 'btn',
            todayBtn: true,
            clearBtn: true,
            format: 'yyyy-mm-dd',
        });
    })();
</script>
<?php /**PATH /home/heritage/SHINE/LATEST/projectmanagement/resources/views/projects/edit.blade.php ENDPATH**/ ?>