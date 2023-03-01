<form class="" method="post" action="<?php echo e(route('update-project-review',[$currentWorkspace->slug,$project->project_id])); ?>">
    <?php echo csrf_field(); ?>
    <div class="modal-body">
        <div class="row">
            <div class="form-group col-md-12">
                <label for="projectname" class="form-label"><?php echo e(__('Name')); ?></label>
                <input class="form-control" type="text" id="projectname" name="name" required=""
                       placeholder="<?php echo e(__('Project Name')); ?>" value="<?php echo e($project->name); ?>">
            </div>
            <div class="form-group col-md-12">
                <label for="description" class="form-label"><?php echo e(__('Description')); ?></label>
                <textarea class="form-control" id="description" name="description" required=""
                          placeholder="<?php echo e(__('Add Description')); ?>"><?php echo e($project->description); ?></textarea>
            </div>
            <div class="form-group col-md-12">
                <label for="status" class="form-label"><?php echo e(__('Update Status')); ?></label>
                <select id="status" name="status" class="form-control select2">
                    <option value="CostFixed"><?php echo e(__('CostFixed')); ?></option>
                    <option value="NotSubmitted"
                            <?php if($project->status == 'NotSubmitted'): ?> selected <?php endif; ?>><?php echo e(__('NotSubmitted')); ?></option>
                    <option value="UnderReview"
                            <?php if($project->status == 'UnderReview'): ?> selected <?php endif; ?>><?php echo e(__('UnderReview')); ?></option>
                    <option value="Processing"
                            <?php if($project->status == 'Processing'): ?> selected <?php endif; ?>><?php echo e(__('Processing')); ?></option>
                    <option value="Finished"
                            <?php if($project->status == 'Finished'): ?> selected <?php endif; ?>><?php echo e(__('Finished')); ?></option>
                    <option value="PENDING"
                            <?php if($project->status == 'PENDING'): ?> selected <?php endif; ?>><?php echo e(__('PENDING')); ?></option>


                </select>
            </div>

            
            

            
            
            
            
            
            

            <div class="form-group "
                 style="background-color:limegreen; padding: 5px; border-radius: 10px; font-weight: bold">
                <h3>Required Amount</h3>
                <label for="is_paid" class="col-form-label"><?php echo e(__('Paid')); ?></label>
                <input type="radio" id="proj_fee" name="proj_fee" value="paid">
                <label for="is_paid" class="col-form-label"><?php echo e(__('Not paid')); ?></label>
                <input type="radio" id="proj_fee" name="proj_fee" value="not_paid">
                <div class="row">
                    <div class="col-md-6">
                        <input class="form-control" type="text" id="amount_required" name="amount_required" value="">
                    </div>
                    <div class="col-md-6">
                        <select class="form-control" type="text" id="currency" name="currency">
                            <option class="form-control" value="NGN">NGN</option>
                            <option class="form-control" value="USD">USD</option>
                        </select>
                    </div>
                </div>

            </div>

            <div class="form-group col-md-6">
                <label class="form-label"><?php echo e(__('Start Date')); ?></label>


                <div class="input-group date ">
                    <input class="form-control datepicker2" type="text" id="start_date" name="start_date"
                           value="<?php echo e($project->start_date); ?>" autocomplete="off" required="required">
                    <span class="input-group-text">
                 <i class="feather icon-calendar"></i>
            </span>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label class="form-label"><?php echo e(__('End Date')); ?></label>
                <div class="input-group date ">
                    <input class="form-control datepicker3" type="text" id="end_date" name="end_date"
                           value="<?php echo e($project->end_date); ?>" autocomplete="off" required="required">
                    <span class="input-group-text">
                 <i class="feather icon-calendar"></i>
            </span>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>
        <input type="submit" value="<?php echo e(__('Save Changes')); ?>" class="btn  btn-primary">
    </div>

</form>

<script>
    if ($(".multi-select").length > 0) {
        $($(".multi-select")).each(function (index, element) {
            var id = $(element).attr('id');
            var multipleCancelButton = new Choices(
                '#' + id, {
                    removeItemButton: true,
                }
            );
        });
    }

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
<?php /**PATH /home/heritage/SHINE/LATEST/projectmanagement/resources/views/projects/review/_review_project.blade.php ENDPATH**/ ?>