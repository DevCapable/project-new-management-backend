
<div class="row">

    <div class="form-group col-md-12">
        <label class="col-form-label"><?php echo e(__('Date / Time Schedule')); ?></label>
        <input type="text" class="form-control form-control-light" id="date_schedule" name="date_schedule"
               required autocomplete="off">
        <input type="hidden" name="start_date">
        <input type="hidden" name="due_date">
    </div>
    <div class="form-group col-md-12">
        <label for="appointment" class="col-form-label"><?php echo e(__('Title')); ?></label>
        <input class="form-control" type="text" id="appointmentTitle" name="title" required=""
               placeholder="<?php echo e(__('Title')); ?>">
    </div>
    <div class="form-group col-md-12">
        <label for="description" class="col-form-label"><?php echo e(__('Description')); ?></label>
        <textarea class="form-control" id="description" name="description" required=""
                  placeholder="<?php echo e(__('Add Description')); ?>"></textarea>
    </div>





</div>
<div class="alert alert-info alert-block">
    <i class="fa fa-check-circle-o"></i>
    <strong><?php echo e($notice); ?></strong>
</div>
<?php /**PATH /home/heritage/SHINE/LATEST/projectmanagement/resources/views/clients/appointments/_appointment_form.blade.php ENDPATH**/ ?>