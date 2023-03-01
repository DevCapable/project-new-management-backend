<style type="text/css">
    .modal-body{
    background: #fff !important;
        padding: 25px !important;
    }


</style>

<?php $__env->startSection('page-title'); ?> <?php echo e(__('Calendar')); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('links'); ?>
<?php if(\Auth::guard('client')->check()): ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('client.home')); ?>"><?php echo e(__('Home')); ?></a></li>
 <?php else: ?>
 <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
 <?php endif; ?>
<li class="breadcrumb-item"> <?php echo e(__('Calendar')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('multiple-action-button'); ?>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-6 pt-lg-3 pt-xl-2">
        <div class=" form-group col-auto">
        <select class="  form-select select2" id="projects" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
            <option value="<?php if(auth()->guard('web')->check()): ?><?php echo e(route('calender.index',$currentWorkspace->slug)); ?><?php else: ?><?php echo e(route('client.calender.index',$currentWorkspace->slug)); ?><?php endif; ?>"><?php echo e(__('All Projects')); ?></option>
            <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php if(auth()->guard('web')->check()): ?><?php echo e(route('calender.index',$currentWorkspace->slug)); ?><?php else: ?><?php echo e(route('client.calender.index',$currentWorkspace->slug)); ?><?php endif; ?><?php echo e('/'.$project->id); ?>" <?php if($project_id == $project->id): ?> selected <?php endif; ?>><?php echo e($project->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
                    <div class="row">
                        <!-- [ sample-page] start -->
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Calendar</h5>
                                </div>
                                <div class="card-body">
                                    <div id='calendar' class='calendar'></div>
                                </div>
                            </div>
                        </div>

                         <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-4">Tasks</h4>
                                          <ul class="event-cards list-group list-group-flush mt-3 w-100">
                                            <?php
                                            $date = Carbon\Carbon::now()->format('m');
                                             $this_month_task = App\Models\task::paginate(6);
                                            ?>

                                            <?php $__currentLoopData = $this_month_task; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                             <?php
                                             $month =date('m', strtotime($task->start_date));
                                            ?>
                                            <?php if($date == $month): ?>
                                            <li class="list-group-item card mb-3">
                                                <div class="row align-items-center justify-content-between">
                                                    <div class="col-auto mb-3 mb-sm-0">
                                                        <div class="d-flex align-items-center">
                                                            <div class="theme-avtar bg-primary">
                                                                <i class="fa fa-tasks"></i>
                                                            </div>
                                                            <div class="ms-3">
                                                            <h6 class="m-0"><?php echo e($task->title); ?></h6>
                                                            <small class="text-muted"><?php echo e($task->start_date); ?> to <?php echo e($task->due_date); ?></small>
                                                            </div>

                                                        </div>
                                                    </div>

                                                </div>
                                            </li>
                                            <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                              <?php echo $this_month_task->links(); ?>

                                        </ul>
                                    </div>
                                </div>
                           </div>
<?php $__env->stopSection(); ?>


<?php if($currentWorkspace): ?>
    <?php $__env->startPush('scripts'); ?>


        <script>
     (function () {
        var etitle;
        var etype;
        var etypeclass;
        var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
             buttonText: {
            timeGridDay: "<?php echo e(__('Day')); ?>",
            timeGridWeek: "<?php echo e(__('Week')); ?>",
            dayGridMonth: "<?php echo e(__('Month')); ?>"
        },
            themeSystem: 'bootstrap',
            slotDuration: '00:10:00',
            navLinks: true,
            droppable: true,
            selectable: true,
            selectMirror: true,
            editable: true,
            dayMaxEvents: true,
            handleWindowResize: true,
            events: <?php echo json_encode($arrayJson); ?>,
        });
        calendar.render();
    })();
            <?php if(auth()->guard('web')->check()): ?>
       $(document).on('click', '#form-comment button', function (e) {
                var comment = $.trim($("#form-comment textarea[name='comment']").val());
                if (comment != '') {
                    $.ajax({
                        url: $("#form-comment").data('action'),
                        data: {comment: comment},
                        type: 'POST',
                        success: function (data) {
                            data = JSON.parse(data);

                            if (data.user_type == 'Client') {
                                var avatar = "avatar='" + data.client.name + "'";
                                var html = "<li class='media border-bottom mb-3'>" +
                                    "                    <img class='mr-3 avatar-sm rounded-circle img-thumbnail hight_img' width='60' " + avatar + " alt='" + data.client.name + "'>" +
                                    "                    <div class='media-body mb-2'>" +
                                        "                    <div class='float-left'>" +
                                        "                        <h5 class='mt-0 mb-1 form-control-label'>" + data.client.name + "</h5>" +
                                        "                        " + data.comment +
                                        "                    </div>" +
                                    "                    </div>" +
                                    "                </li>";
                            } else {
                                var avatar = (data.user.avatar) ? "src='<?php echo e(asset('/storage/avatars/')); ?>/" + data.user.avatar + "'" : "avatar='" + data.user.name + "'";
                                var html = "<li class='media border-bottom mb-3'>" +
                                    "                    <img class='mr-3 preview_img_comment avatar-sm rounded-circle img-thumbnail hight_img ' width='60' " + avatar + " alt='" + data.user.name + "'>" +
                                    "                    <div class='media-body mb-2'>" +
                                        "                    <div class='float-left'>" +
                                    "                        <h5 class='mt-0 mb-1 form-control-label'>" + data.user.name + "</h5>" +
                                    "                        " + data.comment +
                                    "                           </div>" +
                                    "                           <div class='text-end'>" +
                                    "                               <a href='#' class='delete-icon action-btn btn-danger  btn btn-sm d-inline-flex align-items-center delete-comment' data-url='" + data.deleteUrl + "'>" +
                                    "                                   <i class='ti ti-trash'></i>" +
                                    "                               </a>" +
                                    "                           </div>" +
                                    "                    </div>" +
                                    "                </li>";
                            }

                            $("#task-comments").prepend(html);
                            LetterAvatar.transform();
                            $("#form-comment textarea[name='comment']").val('');
                            show_toastr('<?php echo e(__('Success')); ?>', '<?php echo e(__("Comment Added Successfully!")); ?>', 'success');
                        },
                        error: function (data) {
                            show_toastr('<?php echo e(__('Error')); ?>', '<?php echo e(__("Some Thing Is Wrong!")); ?>', 'error');
                        }
                    });
                } else {
                    show_toastr('<?php echo e(__('Error')); ?>', '<?php echo e(__("Please write comment!")); ?>', 'error');
                }
            });
            $(document).on("click", ".delete-comment", function () {
                if (confirm('<?php echo e(__('Are you sure ?')); ?>')) {
                    var btn = $(this);
                    $.ajax({
                        url: $(this).attr('data-url'),
                        type: 'DELETE',
                        dataType: 'JSON',
                        success: function (data) {
                            show_toastr('<?php echo e(__('Success')); ?>', '<?php echo e(__("Comment Deleted Successfully!")); ?>', 'success');
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
            $(document).on('click', '#form-subtask button', function (e) {
                e.preventDefault();

                var name = $.trim($("#form-subtask input[name=name]").val());
                var due_date = $.trim($("#form-subtask input[name=due_date]").val());
                if (name == '' || due_date == '') {
                    show_toastr('<?php echo e(__('Error')); ?>', '<?php echo e(__("Please enter fields!")); ?>', 'error');
                    return false;
                }

                $.ajax({
                    url: $("#form-subtask").data('action'),
                    type: 'POST',
                    data: {
                        name: name,
                        due_date: due_date,
                    },
                    dataType: 'JSON',
                    success: function (data) {
                        show_toastr('<?php echo e(__('Success')); ?>', '<?php echo e(__("Sub Task Added Successfully!")); ?>', 'success');

                        var html = '<li class="list-group-item py-3">' +
                            '    <div class="form-check form-switch d-inline-block">' +
                            '        <input type="checkbox" class="form-check-input" name="option" id="option' + data.id + '" value="' + data.id + '" data-url="' + data.updateUrl + '">' +
                            '        <label class="custom-control-label form-control-label" for="option' + data.id + '">' + data.name +'</label>' +
                            '    </div>' +
                            '    <div class="text-end">' +
                            '        <a href="#" class=" action-btn btn-danger  btn btn-sm d-inline-flex align-items-center delete-comment delete-icon delete-subtask" data-url="' + data.deleteUrl + '">' +
                            '            <i class="ti ti-trash"></i>' +
                            '        </a>' +
                            '    </div>' +
                            '</li>';

                        $("#subtasks").prepend(html);
                        $("#form-subtask input[name=name]").val('');
                        $("#form-subtask input[name=due_date]").val('');
                        $("#form-subtask").collapse('toggle');
                    },
                    error: function (data) {
                        data = data.responseJSON;
                        if (data.message) {
                            show_toastr('<?php echo e(__('Error')); ?>', data.message, 'error');
                            $('#file-error').text(data.errors.file[0]).show();
                        } else {
                            show_toastr('<?php echo e(__('Error')); ?>', '<?php echo e(__("Some Thing Is Wrong!")); ?>', 'error');
                        }
                    }
                });
            });
            $(document).on("change", "#subtasks input[type=checkbox]", function () {
                $.ajax({
                    url: $(this).attr('data-url'),
                    type: 'POST',
                    dataType: 'JSON',
                    success: function (data) {
                        show_toastr('<?php echo e(__('Success')); ?>', '<?php echo e(__("Subtask Updated Successfully!")); ?>', 'success');
                        // console.log(data);
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
            });
            $(document).on("click", ".delete-subtask", function () {
                if (confirm('<?php echo e(__('Are you sure ?')); ?>')) {
                    var btn = $(this);
                    $.ajax({
                        url: $(this).attr('data-url'),
                        type: 'DELETE',
                        dataType: 'JSON',
                        success: function (data) {
                            show_toastr('<?php echo e(__('Success')); ?>', '<?php echo e(__("Subtask Deleted Successfully!")); ?>', 'success');
                            btn.closest('.list-group-item').remove();
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
            $(document).on('submit', '#form-file', function (e) {
                e.preventDefault();

                $.ajax({
                    url: $("#form-file").data('url'),
                    type: 'POST',
                    data: new FormData(this),
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        show_toastr('<?php echo e(__('Success')); ?>', '<?php echo e(__("File Upload Successfully!")); ?>', 'success');
                        // console.log(data);
                        var delLink = '';

                        if (data.deleteUrl.length > 0) {
                            delLink = "<a href='#' class=' action-btn btn-danger  btn btn-sm d-inline-flex align-items-center delete-comment delete-icon delete-comment-file'  data-url='" + data.deleteUrl + "'>" +
                                "                                        <i class='ti ti-trash'></i>" +
                                "                                    </a>";
                        }

                        var html = "<div class='card mb-1 shadow-none border'>" +

                            "                        <div class='card-body p-3'>" +

                            "                            <div class='row align-items-center'>" +

                            "                                <div class='col-auto'>" +

                            "                                    <div class='avatar-sm'>" +

                            "                                        <span class='avatar-title rounded text-uppercase'>" +
                             "<img class='preview_img_size' " + "src='<?php echo e(asset('/storage/tasks/')); ?>/" + data.file + "'>"
                           +
                            "                                        </span>" +
                            "                                    </div>" +
                            "                                </div>" +
                            "                                <div class='col pl-0'>" +
                            "                                    <a href='#' class='text-muted form-control-label'>" + data.name + "</a>" +
                            "                                    <p class='mb-0'>" + data.file_size + "</p>" +
                            "                                </div>" +
                            "                                <div class='col-auto'>" +
                            "                                    <a download href='<?php echo e(asset('/storage/tasks/')); ?>/" + data.file + "' class='edit-icon action-btn btn-primary  btn btn-sm d-inline-flex align-items-center'>" +
                            "                                        <i class='ti ti-download'></i>" +
                            "                                    </a>" +


                            "                                   <a  href='<?php echo e(asset('/storage/tasks/')); ?>/" + data.file + "' class='edit-icon action-btn btn-secondary  btn btn-sm d-inline-flex align-items-center mx-1'>" +
                            "                                        <i class='ti ti-crosshair text-white'></i>" +
                            "                                    </a>" +
                            delLink +
                            "                                </div>" +
                            "                            </div>" +
                            "                        </div>" +
                            "                    </div>";
                        $("#comments-file").prepend(html);
                    },
                    error: function (data) {
                        data = data.responseJSON;
                        if (data.message) {
                            show_toastr('<?php echo e(__('Error')); ?>', data.message, 'error');
                            $('#file-error').text(data.errors.file[0]).show();
                        } else {
                            show_toastr('<?php echo e(__('Error')); ?>', '<?php echo e(__("Some Thing Is Wrong!")); ?>', 'error');
                        }
                    }
                });
            });
            $(document).on("click", ".delete-comment-file", function () {
                if (confirm('<?php echo e(__('Are you sure ?')); ?>')) {
                    var btn = $(this);
                    $.ajax({
                        url: $(this).attr('data-url'),
                        type: 'DELETE',
                        dataType: 'JSON',
                        success: function (data) {
                            show_toastr('<?php echo e(__('Success')); ?>', '<?php echo e(__("File Deleted Successfully!")); ?>', 'success');
                            btn.closest('.border').remove();
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
            <?php endif; ?>

        </script>
    <?php $__env->stopPush(); ?>
<?php endif; ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/heritage/SHINE/LATEST/projectmanagement/resources/views/calendar/index.blade.php ENDPATH**/ ?>