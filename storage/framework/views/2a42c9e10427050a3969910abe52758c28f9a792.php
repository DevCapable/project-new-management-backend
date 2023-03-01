<form class="" method="post" action="<?php echo e(route('update-client-appointment',[$currentWorkspace->slug,$appointment->id])); ?>">
    <?php echo csrf_field(); ?>
     <div class="modal-body">

         <div class="row">
             <div class="form-group col-md-12">
                 <label class="col-form-label"><?php echo e(__('Date / Time Schedule')); ?></label>
                 <input type="text" class="form-control form-control-light" id="date_schedule" value="<?php echo e($appointment->date_schedule); ?>" name="date_schedule" required autocomplete="off">


                 <input type="hidden" value="start_date"  name="start_date">
                 <input type="hidden" value="due_date" name="due_date">
             </div>
             <div class="form-group col-md-12">
                 <label for="appointment" class="col-form-label"><?php echo e(__('Title')); ?></label>
                 <input class="form-control" type="text" id="appointmentTitle" name="title" required="" placeholder="<?php echo e(__('Title')); ?>" value="<?php echo e($appointment->title); ?>">
             </div>
             <div class="form-group col-md-12">
                 <label for="description" class="col-form-label"><?php echo e(__('Description')); ?></label>
                 <textarea class="form-control" id="description" name="description" required="" placeholder="<?php echo e(__('Add Description')); ?>"><?php echo e($appointment->description); ?></textarea>
             </div>
             <?php echo $__env->make('partials._notifications', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
             
             
             
             
             
             
             
             










         </div>

     </div>
        <div class="modal-footer">
           <button type="button" class="btn  btn-light" data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>
            <?php if($appointment->status == 'Submitted'): ?>
                <?php echo get_appointment_status_label('Submitted'); ?>

            <?php else: ?>
                <button type="submit" name="action" value="submit" Onclick="return ConfirmSubmit();" class="btn  btn-danger right"><?php echo e(__('Submit Appointment')); ?></button>
                <button type="submit" name="action" value="save" class="btn  btn-primary right"><?php echo e(__('Save')); ?></button>
            <?php endif; ?>
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
<link rel="stylesheet" href="<?php echo e(asset('custom/libs/bootstrap-daterangepicker/daterangepicker.css')); ?>">
<script src="<?php echo e(asset('custom/libs/bootstrap-daterangepicker/daterangepicker.js')); ?>"></script>

<script>
    function ConfirmSubmit()
    {
        return confirm("Are you sure you want to submit this? note that you cant edit again once submitted");
    }
    $(document).on("click", ".delete-me", function () {
        confirm('<?php echo e(__('Are you sure ?')); ?>')
        if (confirm('<?php echo e(__('Are you sure ?')); ?>')) {
            var btn = $(this);
            $.ajax({
                url: 'client-appointment-delete',
                type: 'DELETE',
                dataType: 'JSON',
                success: function (data) {
                    show_toastr('<?php echo e(__('Success')); ?>', '<?php echo e(__("Appointment Deleted Successfully!")); ?>', 'success');
                    btn.closest('.media').remove();
                },
                error: function (data) {
                    data = data.responseJSON;
                    if (data.message) {
                        show_toastr('<?php echo e(__('Error')); ?>', data.message, 'error');
                    } else {
                        show_toastr('<?php echo e(__('Error')); ?>', '<?php echo e(__("Some Thing Is Wrong!")); ?>', 'error');
                    }
                }
            });
        }
    });
    $(function () {
        var start = moment('<?php echo e(date('Y-m-d')); ?>', 'YYYY-MM-DD HH:mm:ss');
        var end = moment('<?php echo e(date('Y-m-d')); ?>', 'YYYY-MM-DD HH:mm:ss');

        function cb(start, end) {
            $("form #date_schedule").val(start.format('MMM D, YY hh:mm A') + ' - ' + end.format('MMM D, YY hh:mm A'));
            $('form input[name="start_date"]').val(start.format('YYYY-MM-DD HH:mm:ss'));
            $('form input[name="due_date"]').val(end.format('YYYY-MM-DD HH:mm:ss'));
        }

        $('form #date_schedule').daterangepicker({
            /*autoApply: true,
            autoclose: true,*/
            autoApply: true,
            timePicker: true,
            autoUpdateInput: false,
            startDate: start,
            endDate: end,
            /*startDate: start,
            endDate: end,
            autoApply: true,
            autoclose: true,
            autoUpdateInput: false,*/
            locale: {
                format: 'MMMM D, YYYY hh:mm A',
                applyLabel: "<?php echo e(__('Apply')); ?>",
                cancelLabel: "<?php echo e(__('Cancel')); ?>",
                fromLabel: "<?php echo e(__('From')); ?>",
                toLabel: "<?php echo e(__('To')); ?>",
                daysOfWeek: [
                    "<?php echo e(__('Sun')); ?>",
                    "<?php echo e(__('Mon')); ?>",
                    "<?php echo e(__('Tue')); ?>",
                    "<?php echo e(__('Wed')); ?>",
                    "<?php echo e(__('Thu')); ?>",
                    "<?php echo e(__('Fri')); ?>",
                    "<?php echo e(__('Sat')); ?>"
                ],
                monthNames: [
                    "<?php echo e(__('January')); ?>",
                    "<?php echo e(__('February')); ?>",
                    "<?php echo e(__('March')); ?>",
                    "<?php echo e(__('April')); ?>",
                    "<?php echo e(__('May')); ?>",
                    "<?php echo e(__('June')); ?>",
                    "<?php echo e(__('July')); ?>",
                    "<?php echo e(__('August')); ?>",
                    "<?php echo e(__('September')); ?>",
                    "<?php echo e(__('October')); ?>",
                    "<?php echo e(__('November')); ?>",
                    "<?php echo e(__('December')); ?>"
                ],
            }
        }, cb);

        cb(start, end);
    });


</script>
<?php /**PATH /home/heritage/SHINE/LATEST/projectmanagement/resources/views/clients/appointments/edit.blade.php ENDPATH**/ ?>