<form class="" method="post" action="<?php echo e(route('store-client-appointment',$currentWorkspace->slug)); ?>">
    <?php echo csrf_field(); ?>
    <div class="modal-body">

        <?php if($payment_data == null): ?>
            <?php if($check_chance_left->appointment_chance == 0): ?>

                <div class="row">
                    <?php echo $__env->make('partials._notifications', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>

            <?php else: ?>
                <?php echo $__env->make('clients.appointments._appointment_form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <?php endif; ?>

        <?php else: ?>
            <?php if( $payment_data->chance == 0): ?>
                <div class="row">
                    <?php echo $__env->make('partials._notifications', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            <?php else: ?>
                <?php echo $__env->make('clients.appointments._appointment_form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>

        <?php endif; ?>
    </div>

    <div class="modal-footer">

        <?php if($payment_data == null): ?>
            <?php if($check_chance_left->appointment_chance == 0): ?>
                <button type="button" class="btn  btn-light" data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>
                <a href="<?php echo e(route('client-appointment-renew',$currentWorkspace->slug)); ?>" class="btn  btn-danger right">
                    Click here to proceed <i class="ti ti-arrow-bar-right"
                                             style="float: right; font-size: 40px; padding-right: 10px"></i></a>
            <?php else: ?>
                <button type="submit" name="action" value="submit"
                        class="btn  btn-danger right"><?php echo e(__('Submit Appointment')); ?></button>
                <button type="submit" name="action" value="save"
                        class="btn  btn-primary right"><?php echo e(__('Add New Appointment')); ?></button>

            <?php endif; ?>
        <?php else: ?>
            <?php if( $payment_data->chance == 0): ?>
                <button type="button" class="btn  btn-light" data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>
                <a href="<?php echo e(route('client-appointment-renew',$currentWorkspace->slug)); ?>" class="btn  btn-danger right">
                    Click here to proceed <i class="ti ti-arrow-bar-right"
                                             style="float: right; font-size: 40px; padding-right: 10px"></i></a>
            <?php else: ?>
                <button type="submit" name="action" value="submit"
                        class="btn  btn-danger right"><?php echo e(__('Submit Appointment')); ?></button>
                <button type="submit" name="action" value="save"
                        class="btn  btn-primary right"><?php echo e(__('Add New Appointment')); ?></button>

            <?php endif; ?>

        <?php endif; ?>
    </div>


</form>
<link rel="stylesheet" href="<?php echo e(asset('custom/libs/bootstrap-daterangepicker/daterangepicker.css')); ?>">
<script src="<?php echo e(asset('custom/libs/bootstrap-daterangepicker/daterangepicker.js')); ?>"></script>

<script>
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
<?php /**PATH /home/heritage/SHINE/LATEST/projectmanagement/resources/views/clients/appointments/create.blade.php ENDPATH**/ ?>