<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Appointments')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('links'); ?>
    <?php if(\Auth::guard('client')->check()): ?>
        <li class="breadcrumb-item"><a href="<?php echo e(route('client.home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <?php else: ?>
        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <?php endif; ?>
    <li class="breadcrumb-item"> <?php echo e(__('Appointment List')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('action-button'); ?>
    <a href="#" class="btn btn-sm btn-primary filter" data-toggle="tooltip" title="<?php echo e(__('Filter')); ?>">
        <i class="ti ti-filter"></i>
    </a>
    <?php if(auth()->guard('client')->check()): ?>

        <a href="<?php echo e(route('project.export')); ?>" class="btn btn-sm btn-primary " data-toggle="tooltip"
           title="<?php echo e(__('Export ')); ?>"
        > <i class="ti ti-file-x"></i></a>

        <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-title="<?php echo e(__('Import Project')); ?>"
           data-url="<?php echo e(route('project.file.import' ,$currentWorkspace->slug)); ?>" data-toggle="tooltip"
           title="<?php echo e(__('Import')); ?>"><i class="ti ti-file-import"></i> </a>

        <?php if(isset($currentWorkspace) || $currentWorkspace->creater->id == Auth::id()): ?>
            <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md"
               data-title="<?php echo e(__('Create New Appointment')); ?>"
               data-url="<?php echo e(route('client-create-appointment',$currentWorkspace->slug)); ?>" data-toggle="tooltip"
               title="<?php echo e(__('Create')); ?>">
                <i class="ti ti-plus"></i>
            </a>
        <?php endif; ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php

    $client_keyword = Auth::user()->getGuard() == 'client' ? 'client.' : '';
?>

<?php $__env->startSection('content'); ?>

    <!--  <div class="col-lg-12 appointmentreportdata p-0">
 </div> -->

    <div class="row  display-none" id="show_filter">
        <!--       <div class=" form-group col-3">
            <select class=" form-select" name="project" id="project">
                <option value=""><?php echo e(__('All Projects')); ?></option>
                <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($project->id); ?>"><?php echo e($project->name); ?></option>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div> -->
        <?php if($currentWorkspace->permission == 'Owner' || Auth::user()->getGuard() == 'client'): ?>
            <div class="col-2">
                <select class="select2 form-select" name="all_users" id="all_users">
                    <option value="" class="px-4"><?php echo e(__('All Users')); ?></option>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        <?php endif; ?>

        <div class="col-3">
            <select class="select2 form-select" name="status" id="status">
                <option value="" class="px-4"><?php echo e(__('All Status')); ?></option>

                <option value="OnRequest"><?php echo e(__('OnRequest')); ?></option>
                <option value="Finished"><?php echo e(__('Finished')); ?></option>
                <option value="OnHold"><?php echo e(__('OnHold')); ?></option>

            </select>
        </div>


        <div class="form-group col-md-3">
            <div class="input-group date ">
                <input class="form-control " type="text" id="date_schedule" name="date_schedule" value=""
                       autocomplete="off" required="required" placeholder="<?php echo e(__('Start Date')); ?>">
                <span class="input-group-text">
                 <i class="feather icon-calendar"></i>
            </span>
            </div>
        </div>
        <div class="form-group col-md-3">
            <div class="input-group date ">
                <input class="form-control datepicker4" type="text" id="end_date" name="end_date" value=""
                       autocomplete="off" required="required" placeholder="<?php echo e(__('End Date')); ?>">
                <span class="input-group-text">
                 <i class="feather icon-calendar"></i>
            </span>
            </div>
        </div>
        <button class=" btn btn-primary col-1  apply btn-filter"><?php echo e(__('Apply')); ?></button>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style mt-3 mx-2">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0 animated selection-datatable px-4 mt-2"
                               id="selection-datatable1">
                            <thead>
                            <tr>
                                <th> <?php echo e(__('Title')); ?></th>
                                <th> <?php echo e(__('Description')); ?></th>

                                    <th><?php echo e(__('Zoom')); ?></th>

                                <th> <?php echo e(__('Proposed Date')); ?></th>
                                
                                
                                <th><?php echo e(__('Status')); ?></th>
                                    <th><?php echo e(__('Action')); ?></th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        <div>
                            <a style="float: right; font-size: 40px; padding-right: 10px" href="<?php echo e(route('client.calender.index',$currentWorkspace->slug)); ?>"
                               class="dash-link "><span class="dash-micon"><i
                                        data-feather="calendar"></i></span><span
                                    class="dash-mtext"><?php echo e(__('Calendar')); ?></span></a>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>




    <?php $__env->stopSection(); ?>
    <?php $__env->startPush('css-page'); ?>
        <link rel="stylesheet" href="<?php echo e(asset('custom/css/datatables.min.css')); ?>">
        <style>
            table.dataTable.no-footer {
                border-bottom: none !important;
            }
            .display-none {
                display: none !important;
            }
        </style>
    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('scripts'); ?>
        <script>
            function ConfirmDelete()
            {
                return confirm("Are you sure you want to delete this?");
            }

        </script>

        <script>
            (function () {
                const d_week = new Datepicker(document.querySelector('.datepicker5'), {
                    buttonClass: 'btn',
                    todayBtn: true,
                    clearBtn: true,
                    format: 'yyyy-mm-dd',
                });
            })();
        </script>
        <script src="<?php echo e(asset('custom/js/jquery.dataTables.min.js')); ?>"></script>
        <script>
            const dataTable = new simpleDatatables.DataTable("#selection-datatable1");
        </script>


        <script type="text/javascript">
            $(".filter").click(function() {
                $("#show_filter").toggleClass('display-none');
            });
        </script>
        <script>
            $(document).ready(function() {
                var table = $("#selection-datatable1").DataTable({
                    order: [],
                    select: {
                        style: "multi"
                    },
                    "language": dataTableLang,
                    drawCallback: function() {
                        $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                    }
                });
                $(document).on("click", ".btn-filter", function() {
                    getData();
                });

                function getData() {
                    table.clear().draw();
                    $("#selection-datatable1 tbody tr").html(
                        '<td colspan="11" class="text-center"> <?php echo e(__('Loading ...')); ?></td>');

                    var data = {
                        status: $("#status").val(),
                        date_schedule: $("#date_schedule").val(),
                        all_users :$("#all_users").val(),
                    };
                    $.ajax({
                        url: '<?php echo e(route('appointment-ajax', [$currentWorkspace->slug])); ?>',
                        type: 'POST',
                        data: data,
                        success: function(data) {
                            table.rows.add(data.data).draw(true);
                            loadConfirm();
                        },
                        error: function(data) {
                            show_toastr('Info', data.error, 'error')
                        }
                    })
                }

                getData();

            });
        </script>

    <?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/heritage/SHINE/LATEST/projectmanagement/resources/views/clients/appointments/index.blade.php ENDPATH**/ ?>