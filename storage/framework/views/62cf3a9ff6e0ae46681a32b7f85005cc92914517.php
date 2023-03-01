<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Appointment Detail')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('links'); ?>
    <?php if(\Auth::guard('client')->check()): ?>
        <li class="breadcrumb-item"><a href="<?php echo e(route('client.home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <?php else: ?>
        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <?php endif; ?>
    <?php if(\Auth::guard('client')->check()): ?>
        <li class="breadcrumb-item"><a
                href="<?php echo e(route('client-appointment-index',$currentWorkspace->slug)); ?>"><?php echo e(__('Appointment')); ?></a></li>
    <?php else: ?>
        <li class="breadcrumb-item"><a href="<?php echo e(route('client-appointment-index',$currentWorkspace->slug)); ?>"><?php echo e(__('Appointment')); ?></a>
        </li>
    <?php endif; ?>
    <li class="breadcrumb-item"><?php echo e(__('Appointment Details')); ?></li>
<?php $__env->stopSection(); ?>


<style type="text/css">
    .fix_img {
        width: 40px !important;
        border-radius: 50%;
    }
</style>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="row">

                <div class="col-xxl-4">


                    
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0"><?php echo e(__('Appointment')); ?></h5>
                                </div>
                                <a href="javascript:history.back()"><i class="ti ti-arrow-back"
                                                                       style="float: right; font-size: 40px; padding-right: 10px"></i></a>

                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="timeline timeline-one-side " data-timeline-content="axis"
                                 data-timeline-axis-style="dashed">
                                <?php if((isset($permissions) && in_array('show activity',$permissions)) || $currentWorkspace->permission == 'Owner'): ?>

                                    <div class="timeline-block px-2 pt-3">
                                        <table class="table table-responsive">
                                            <tr>
                                                <th> Title</th>
                                                <td> <?php echo e($appointment->title); ?></td>
                                            </tr>
                                            <tr>
                                                <th> Description</th>
                                                <td> <?php echo e($appointment->description); ?></td>
                                            </tr>
                                            <tr>
                                                <th> Date</th>
                                                <td> <?php echo e($appointment->date_schedule); ?></td>
                                            </tr>
                                        </table>
                                        <div class="card-footer">
                                            
                                            <?php if($appointment->status == 'Submitted'): ?>
                                                <?php echo get_appointment_status_label('Submitted'); ?>

                                            <?php else: ?>
                                                <a style="float: right; font-size: 30px; padding-right: 10px" href="#"
                                                   class="action-btn btn-info mx-1  btn btn-sm d-inline-flex align-items-center"
                                                   data-ajax-popup="true" data-size="lg"
                                                   data-toggle="popover" title="<?php echo e(__('Update')); ?>"
                                                   data-title="<?php echo e(__('Update Appointment')); ?>"
                                                   data-url="<?php echo e(route('client-edit-appointment',[$currentWorkspace->slug, $appointment->id])); ?>"><i
                                                        class="ti ti-edit"></i></a>
                                            <?php endif; ?>

                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    

                </div>

            </div>
            <!-- [ sample-page ] end -->
        </div>
        <!-- [ Main Content ] end -->
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('css-page'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('custom/css/dropzone.min.css')); ?>">
<?php $__env->stopPush(); ?>
<?php $__env->startPush('scripts'); ?>

    <!--
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

 -->
    <script src="<?php echo e(asset('assets/js/plugins/apexcharts.min.js')); ?>"></script>
    <script>


    </script>






    <script>
        $(document).ready(function () {
            if ($(".top-10-scroll").length) {
                $(".top-10-scroll").css({
                    "max-height": 300
                }).niceScroll();
            }
        });

    </script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/heritage/SHINE/LATEST/projectmanagement/resources/views/clients/appointments/show.blade.php ENDPATH**/ ?>