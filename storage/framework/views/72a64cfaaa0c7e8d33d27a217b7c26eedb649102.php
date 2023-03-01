<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Project Detail')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('links'); ?>
    <?php if(\Auth::guard('client')->check()): ?>
        <li class="breadcrumb-item"><a href="<?php echo e(route('client.home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <?php else: ?>
        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <?php endif; ?>
    <?php if(\Auth::guard('client')->check()): ?>
        <li class="breadcrumb-item"><a
                href="<?php echo e(route('client-projects-index',$currentWorkspace->slug)); ?>"><?php echo e(__('Project')); ?></a></li>
    <?php else: ?>
        <li class="breadcrumb-item"><a href="<?php echo e(route('projects.index',$currentWorkspace->slug)); ?>"><?php echo e(__('Project')); ?></a>
        </li>
    <?php endif; ?>
    <li class="breadcrumb-item"><?php echo e(__('Project Details')); ?></li>
<?php $__env->stopSection(); ?>
<?php if(Auth::user()->type == 'user'): ?>
    <?php
        $permissions = Auth::user()->getPermission($project->project_id);
        $client_keyword = Auth::user()->getGuard() == 'client' ? 'client.' : '';
    ?>
<?php endif; ?>

<?php $__env->startSection('multiple-action-button'); ?>
    
    
    
    
    
    

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
                <?php echo $__env->make('partials._notifications', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <div class="col-xxl-8">
                    <div class="card bg-primary">
                        <div class="card-body">
                            <div class="d-block d-sm-flex align-items-center justify-content-between">
                                <h4 class="text-white">  <?php echo e($project->name); ?></h4>
                                <div class="d-flex  align-items-center">
                                    <div class="px-3">
                                        <span class="text-white text-sm"><?php echo e(__('Start Date')); ?>:</span>
                                        <h5 class="text-white text-nowrap"><?php echo e(App\Models\Utility::dateFormat($project->start_date)); ?></h5>
                                    </div>
                                    <div class="px-3">
                                        <span class="text-white text-sm"><?php echo e(__('Due Date')); ?>:</span>
                                        <h5 class="text-white"><?php echo e(App\Models\Utility::dateFormat($project->end_date)); ?></h5>
                                    </div>
                                    <div class="px-3">
                                        <span class="text-white text-sm"><?php echo e(__('Total Members')); ?>:</span>
                                        <h5 class="text-white text-nowrap"><?php echo e((int) $project->users->count() + (int) $project->clients->count()); ?></h5>
                                    </div>
                                    <div class="px-3">

                                        <div class="col-auto">
                                            <?php if($project->status == 'Processing'): ?>
                                                <span class="badge rounded-pill bg-danger">Processing</span>

                                            <?php else: ?>
                                                <?php echo get_projects_status_label($project->status); ?>

                                            <?php endif; ?>
                                            <?php if($project->payment_links !== NULL): ?>
                                                

                                                <span
                                                    class="badge rounded-pill bg-danger"><?php echo $project->payment_links; ?></span>
                                            <?php endif; ?>
                                        </div>

                                    </div>
                                </div>

                                <?php if(!$project->is_active): ?>
                                    <button class="btn btn-light d"><a href="#" class="" title="<?php echo e(__('Locked')); ?>">
                                            <i data-feather="lock"> </i>
                                        </a></button>

                                <?php else: ?>
                                    <?php if(auth()->guard('web')->check()): ?>
                                        <?php if($currentWorkspace || $currentWorkspace->permission == 'Owner'): ?>

                                            <div class="d-flex align-items-center ">
                                                <button class="btn btn-light d-flex align-items-between me-3">
                                                    <a href="#" class=""
                                                       data-url="<?php echo e(route('admin-project-review',[$currentWorkspace->slug,$project->project_id])); ?>"
                                                       data-ajax-popup="true" data-title="<?php echo e(__('Review Project')); ?>"
                                                       data-toggle="popover" title="<?php echo e(__('Review')); ?>">
                                                        <i class="ti ti-box-multiple-7"> Review </i>
                                                    </a>
                                                </button>
                                                <button class="btn btn-light d"><a href="#" class="bs-pass-para"
                                                                                   data-confirm="<?php echo e(__('Are You Sure?')); ?>"
                                                                                   data-text="<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>"
                                                                                   data-confirm-yes="delete-form-<?php echo e($project->id); ?>"
                                                                                   data-toggle="popover"
                                                                                   title="<?php echo e(__('Delete')); ?>"><i
                                                            class="ti ti-trash"> </i></a></button>

                                            </div>
                                            <form id="delete-form-<?php echo e($project->id); ?>"
                                                  action="<?php echo e(route('projects.destroy',[$currentWorkspace->slug,$project->id])); ?>"
                                                  method="POST" style="display: none;">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                            </form>

                                        <?php else: ?>

                                            <button class="btn btn-light d"><a href="#" class="bs-pass-para"
                                                                               data-confirm="<?php echo e(__('Are You Sure?')); ?>"
                                                                               data-text="<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>"
                                                                               data-confirm-yes="leave-form-<?php echo e($project->id); ?>"><i
                                                        class="ti ti-trash"> </i> </i></a></button>

                                            <form id="leave-form-<?php echo e($project->id); ?>"
                                                  action="<?php echo e(route('projects.leave',[$currentWorkspace->slug,$project->id])); ?>"
                                                  method="POST" style="display: none;">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                            </form>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                                
                                <?php if(auth()->guard('client')->check()): ?>
                                    <?php if($currentWorkspace->permission == 'Owner'): ?>
                                        <?php if($project->status == 'NotSubmitted'): ?>
                                            <div class="d-flex align-items-center ">
                                                <button class="btn btn-light d-flex align-items-between me-3">
                                                    <a href="#" class=""
                                                       data-url="<?php echo e(route('edit-client-project',[$currentWorkspace->slug,$project->project_id])); ?>"
                                                       data-ajax-popup="true" data-title="<?php echo e(__('Edit Project')); ?>"
                                                       data-toggle="popover" title="<?php echo e(__('Edit')); ?>">
                                                        <i class="ti ti-edit"> </i>
                                                    </a>
                                                </button>
                                                <button class="btn btn-light d"><a href="#" class="bs-pass-para"
                                                                                   data-confirm="<?php echo e(__('Are You Sure?')); ?>"
                                                                                   data-text="<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>"
                                                                                   data-confirm-yes="delete-form-<?php echo e($project->id); ?>"
                                                                                   data-toggle="popover"
                                                                                   title="<?php echo e(__('Delete')); ?>"><i
                                                            class="ti ti-trash" style="color: red"> </i></a></button>

                                            </div>
                                            <form id="delete-form-<?php echo e($project->id); ?>"
                                                  action="<?php echo e(route('delete-client-project',[$currentWorkspace->slug,$project->id])); ?>"
                                                  method="POST" style="display: none;">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                            </form>

                                        <?php else: ?>
                                            <span class="badge rounded-pill bg-danger">Submitted</span>

                                        <?php endif; ?>

                                    <?php else: ?>

                                        <button class="btn btn-light d"><a href="#" class="bs-pass-para"
                                                                           data-confirm="<?php echo e(__('Are You Sure?')); ?>"
                                                                           data-text="<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>"
                                                                           data-confirm-yes="leave-form-<?php echo e($project->id); ?>"><i
                                                    class="ti ti-trash"> </i> </i>ddd</a></button>

                                        <form id="leave-form-<?php echo e($project->id); ?>"
                                              action="<?php echo e(route('projects.leave',[$currentWorkspace->slug,$project->id])); ?>"
                                              method="POST" style="display: none;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                        </form>
                                    <?php endif; ?>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="theme-avtar bg-primary">
                                            <i class="fas fas fa-calendar-day"></i>
                                        </div>
                                        <div class="col text-end">
                                            <h6 class="text-muted mb-1"><?php echo e(__('Days left')); ?></h6>
                                            <span class="h6 font-weight-bold mb-0 "><?php echo e($daysleft); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="theme-avtar bg-info">
                                            <i class="fas fa-money-bill-alt"></i>
                                        </div>
                                        <div class="col text-end">
                                            <h6 class="text-muted mb-1"><?php echo e(__('Budget')); ?></h6>
                                            <span
                                                class="h6 font-weight-bold mb-0 "><?php echo e((!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'); ?><?php echo e(number_format($project->budget)); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="theme-avtar bg-danger">
                                            <i class="fas fa-check-double"></i>
                                        </div>
                                        <div class="col text-end">
                                            <h6 class="text-muted mb-1"><?php echo e(__('Total Task')); ?></h6>
                                            <span class="h6 font-weight-bold mb-0 "><?php echo e($project->countTask()); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="theme-avtar bg-success">
                                            <i class="fas fa-comments"></i>
                                        </div>
                                        <div class="col text-end">
                                            <h6 class="text-muted mb-1"><?php echo e(__('Comment')); ?></h6>
                                            <span
                                                class="h6 font-weight-bold mb-0 "><?php echo e($project->countTaskComments()); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card" style="min-height:350;">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-0"><?php echo e(__('Staff(s) incharge')); ?> (<?php echo e(count($project->clients)); ?>

                                                )</h5>
                                        </div>

                                        <div class="float-end">
                                            <p class="text-muted d-none d-sm-flex align-items-center mb-0"> <?php if($currentWorkspace->permission == 'Owner'): ?>
                                                    <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true"
                                                       data-title="<?php echo e(__('Share to Client')); ?>" data-toggle="popover"
                                                       title="<?php echo e(__('Share to Client')); ?>"
                                                       data-url="<?php echo e(route('projects.share.popup',[$currentWorkspace->slug,$project->id])); ?>"><i
                                                            class="ti ti-share"></i></a>
                                                <?php endif; ?> </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">

                                    <?php $__currentLoopData = $project->clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item px-0">
                                                <div class="row align-items-center justify-content-between">
                                                    <div class="col-sm-auto mb-3 mb-sm-0">
                                                        <div class="d-flex align-items-center px-2">
                                                            <a href="#" class=" text-start">
                                                                <img class="fix_img"
                                                                     <?php if($client->avatar): ?> src="<?php echo e(asset('/storage/avatars/'.$client->avatar)); ?>"
                                                                     <?php else: ?> avatar="<?php echo e($client->name); ?>"<?php endif; ?>>
                                                            </a>
                                                            <div class="px-2">
                                                                <h5 class="m-0"><?php echo e($client->name); ?></h5>
                                                                <small class="text-muted"><?php echo e($client->email); ?></small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-auto text-sm-end d-flex align-items-center">
                                                        <?php if(auth()->guard('web')->check()): ?>
                                                            <?php if($currentWorkspace->permission == 'Owner'): ?>
                                                                <a href="#"
                                                                   class="action-btn btn-primary mx-1  btn btn-sm d-inline-flex align-items-center"
                                                                   data-ajax-popup="true" data-toggle="popover"
                                                                   title="<?php echo e(__('Permission')); ?>" data-size="lg"
                                                                   data-title="<?php echo e(__('Edit Permission')); ?>"
                                                                   data-url="<?php echo e(route('projects.client.permission',[$currentWorkspace->slug,$project->id,$client->id])); ?>"><i
                                                                        class="ti ti-lock"></i></a>

                                                                <a href="#"
                                                                   class="action-btn btn-danger mx-1  btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                                   data-confirm="<?php echo e(__('Are You Sure?')); ?>"
                                                                   data-toggle="popover" title="<?php echo e(__('Delete')); ?>"
                                                                   data-text="<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>"
                                                                   data-confirm-yes="delete-client-<?php echo e($client->id); ?>"><i
                                                                        class="ti ti-trash"></i></a>

                                                                <form id="delete-client-<?php echo e($client->id); ?>"
                                                                      action="<?php echo e(route('projects.client.delete',[$currentWorkspace->slug,$project->id,$client->id])); ?>"
                                                                      method="POST" style="display: none;">
                                                                    <?php echo csrf_field(); ?>
                                                                    <?php echo method_field('DELETE'); ?>
                                                                </form>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-xxl-4">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header" style="padding: 25px 35px !important;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="row">
                                            <h5 class="mb-0"><?php echo e(__('Progress')); ?><span class="text-end">  (Last Week Tasks) </span>
                                            </h5>

                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-start">
                                    </div>
                                </div>
                                <div id="task-chart"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0"><?php echo e(__('Activity')); ?></h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="timeline timeline-one-side top-10-scroll" data-timeline-content="axis"
                                 data-timeline-axis-style="dashed">
                                <?php if((isset($permissions) && in_array('show activity',$permissions)) || $currentWorkspace->permission == 'Owner'): ?>
                                    <?php $__currentLoopData = $project->activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="timeline-block px-2 pt-3">
                                            <?php if($activity->log_type == 'Upload File'): ?>
                                                <span
                                                    class="timeline-step timeline-step-sm border border-primary text-white"> <i
                                                        class="fas fa-file"></i></span>
                                            <?php elseif($activity->log_type == 'Create Milestone'): ?>
                                                <span
                                                    class="timeline-step timeline-step-sm border border-info text-white"> <i
                                                        class="fas fa-cubes"></i></span>
                                            <?php elseif($activity->log_type == 'Create Task'): ?>
                                                <span
                                                    class="timeline-step timeline-step-sm border border-success text-white"> <i
                                                        class="fas fa-tasks"></i></span>

                                            <?php elseif($activity->log_type == 'Update Project'): ?>
                                                <span
                                                    class="timeline-step timeline-step-sm border border-success text-white"> <i
                                                        class="fas fa-tasks"></i></span>
                                            <?php elseif($activity->log_type == 'Submit Project'): ?>
                                                <span
                                                    class="timeline-step timeline-step-sm border border-success text-white"> <i
                                                        class="fas fa-tasks"></i></span>

                                            <?php elseif($activity->log_type == 'Create New Project'): ?>
                                                <span
                                                    class="timeline-step timeline-step-sm border border-success text-white"> <i
                                                        class="fas fa-tasks"></i></span>

                                            <?php elseif($activity->log_type == 'Create Bug'): ?>
                                                <span
                                                    class="timeline-step timeline-step-sm border border-warning text-white"> <i
                                                        class="fas fa-bug"></i></span>
                                            <?php elseif($activity->log_type == 'Move' || $activity->log_type == 'Move Bug'): ?>
                                                <span
                                                    class="timeline-step timeline-step-sm border round border-danger text-white"> <i
                                                        class="fas fa-align-justify"></i></span>
                                            <?php elseif($activity->log_type == 'Create Invoice'): ?>
                                                <span
                                                    class="timeline-step timeline-step-sm border border-bg-dark text-white"> <i
                                                        class="fas fa-file-invoice"></i></span>
                                            <?php elseif($activity->log_type == 'Invite User'): ?>
                                                <span
                                                    class="timeline-step timeline-step-sm border border-success text-white"> <i
                                                        class="fas fa-plus"></i></span>
                                            <?php elseif($activity->log_type == 'Share with Client'): ?>
                                                <span
                                                    class="timeline-step timeline-step-sm border border-info text-white"> <i
                                                        class="fas fa-share"></i></span>
                                            <?php elseif($activity->log_type == 'Create Timesheet'): ?>
                                                <span
                                                    class="timeline-step timeline-step-sm border border-success text-white"> <i
                                                        class="fas fa-clock-o"></i></span>
                                            <?php endif; ?>

                                            <div class="last_notification_text">
                                                <p class="m-0"><span><?php echo e($activity->log_type); ?> </span></p> <br>
                                                <p> <?php echo $activity->getRemark(); ?> </p>
                                                <div class="notification_time_main">
                                                    <p><?php echo e($activity->created_at->diffForHumans()); ?></p>
                                                </div>
                                            </div>

                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>


                    
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0"><?php echo e(__('Comments')); ?></h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="timeline timeline-one-side top-10-scroll" data-timeline-content="axis"
                                 data-timeline-axis-style="dashed">
                                <?php if((isset($permissions) && in_array('show activity',$permissions)) || $currentWorkspace->permission == 'Owner'): ?>
                                    <?php $__currentLoopData = $project->activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="timeline-block px-2 pt-3">


                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    

                </div>

                

                <div class="col-md-12">
                    <?php if((isset($permissions) && in_array('show task', $permissions)) || $currentWorkspace->permission == 'Owner'): ?>

                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-0"><?php echo e(__('Task(s)')); ?> (<?php echo e(count($project->tasks())); ?>)</h5>
                                    </div>
                                    <div class="float-end">
                                        <?php if((isset($permissions) && in_array('create task',$permissions)) || $currentWorkspace->permission == 'Owner'): ?>
                                            <?php if(auth()->guard('web')->check()): ?>
                                                <a href="<?php echo e(route('admin-create-new-task',[$currentWorkspace->slug,$project->id])); ?>"
                                                   class="btn btn-sm btn-primary"
                                                   data-title="<?php echo e(__('Create Task')); ?>"
                                                   title="<?php echo e(__('Create')); ?>"><i class="ti ti-plus"></i></a>
                                            <?php elseif(auth()->guard('client')->check()): ?>
                                                <a href="<?php echo e(route('client-create-new-task',[$currentWorkspace->slug,$project->project_id])); ?>"
                                                   class="btn btn-sm btn-primary"
                                                   data-title="<?php echo e(__('Create Task')); ?>"
                                                   title="<?php echo e(__('Create')); ?>"><i class="ti ti-plus"></i></a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="" class="table table-bordered px-2">
                                        <thead>
                                        <tr>
                                            <th><?php echo e(__('Name')); ?></th>
                                            <th><?php echo e(__('Status')); ?></th>
                                            <th><?php echo e(__('Start Date')); ?></th>
                                            <th><?php echo e(__('End Date')); ?></th>
                                            <th><?php echo e(__('Assignee')); ?></th>
                                            <th><?php echo e(__('Cost')); ?></th>
                                            <th><?php echo e(__('Progress')); ?></th>
                                            <th> <?php echo e(__('Files')); ?></th>
                                            <th><?php echo e(__('Action')); ?> </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $__currentLoopData = $project->tasks(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><a href="#" class="d-block font-weight-500 mb-0"
                                                       data-ajax-popup="true" data-title="<?php echo e(__('Milestone Details')); ?>"
                                                       data-url="<?php echo e(route('admin-tasks-edit',[$currentWorkspace->slug,$task->project_id,$task->id,])); ?>">
                                                        <h5 class="m-0">  <?php echo e($task->title); ?> </h5>
                                                    </a></td>
                                                <td> <?php if($task->status == 'complete'): ?>
                                                        <label
                                                            class="badge bg-success p-2 px-3 rounded"><?php echo e(__('Complete')); ?></label>
                                                    <?php else: ?>
                                                        <label
                                                            class="badge bg-warning p-2 px-3 rounded"><?php echo e(__('Incomplete')); ?></label>
                                                    <?php endif; ?></td>
                                                <td><?php echo e(format_date($task->start_date)); ?></td>
                                                <td><?php echo e(format_date($task->due_date)); ?></td>
                                                <td>
                                                    <?php echo e($task->assign_to? : 'N/A'); ?>


                                                </td>
                                                <td><?php echo e((!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'); ?><?php echo e($task->cost); ?></td>
                                                <td>
                                                    <div class="progress_wrapper">
                                                        <div class="progress">
                                                            <div class="progress-bar" role="progressbar"
                                                                 style="width: <?php echo e($task->progress); ?>;"
                                                                 aria-valuenow="55" aria-valuemin="0"
                                                                 aria-valuemax="100"></div>
                                                        </div>
                                                        <div class="progress_labels">
                                                            <div class="total_progress">

                                                                <strong> <?php echo e($task->progress); ?>%</strong>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                        <?php echo $__env->make('projects.task._task_uploaded_files', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                </td>

                                                <td class="text-right">
                                                    <div class="col-auto">
                                                        <?php if($currentWorkspace->permission == 'Owner'): ?>
                                                            <?php if(auth()->guard('client')->check()): ?>
                                                                <a href="#"
                                                                   class="action-btn btn-info mx-1  btn btn-sm d-inline-flex align-items-center"
                                                                   data-ajax-popup="true" data-size="lg"
                                                                   data-toggle="popover" title="<?php echo e(__('Edit')); ?>"
                                                                   data-title="<?php echo e(__('Edit Task')); ?>"
                                                                   data-url="<?php echo e(route('client-tasks-edit',[$currentWorkspace->slug,$task->project_id,$task->id,])); ?>"><i
                                                                        class="ti ti-edit"></i></a>
                                                            <?php elseif(auth()->guard('web')->check()): ?>

                                                                <a href="#"
                                                                   class="action-btn btn-info mx-1  btn btn-sm d-inline-flex align-items-center"
                                                                   data-ajax-popup="true" data-size="lg"
                                                                   data-toggle="popover" title="<?php echo e(__('Edit')); ?>"
                                                                   data-title="<?php echo e(__('Edit Task')); ?>"
                                                                   data-url="<?php echo e(route('admin-tasks-edit',[$currentWorkspace->slug,$task->project_id,$task->id,])); ?>"><i
                                                                        class="ti ti-edit"></i></a>
                                                            <?php endif; ?>

                                                            <a href="#"
                                                               class="action-btn btn-danger mx-1  btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                               data-confirm="<?php echo e(__('Are You Sure?')); ?>"
                                                               data-toggle="popover" title="<?php echo e(__('Delete')); ?>"
                                                               data-text="<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>"
                                                               data-confirm-yes="delete-form1-<?php echo e($task->id); ?>"><i
                                                                    class="ti ti-trash"></i></a>
                                                            <form id="delete-form1-<?php echo e($task->id); ?>"
                                                                  action="<?php if(auth()->guard('web')->check()): ?><?php echo e(route('admin-tasks-destroy',[$currentWorkspace->slug,$task->project_id,$task->id])); ?>

                                                                  <?php elseif(auth()->guard('client')->check()): ?><?php echo e(route('client-tasks-destroy',[$currentWorkspace->slug,$task->project_id,$task->id])); ?> <?php endif; ?>"
                                                                  method="POST" style="display: none;">
                                                                <?php echo csrf_field(); ?>
                                                                <?php echo method_field('DELETE'); ?>
                                                            </form>
                                                        <?php elseif(isset($permissions)): ?>
                                                            <?php if(in_array('edit task',$permissions)): ?>
                                                                <a href="#"
                                                                   class="action-btn btn-info mx-1  btn btn-sm d-inline-flex align-items-center"
                                                                   data-ajax-popup="true" data-size="lg"
                                                                   data-toggle="popover" title="<?php echo e(__('Edit')); ?>"
                                                                   data-title="<?php echo e(__('Edit Task')); ?>"
                                                                   data-url="<?php echo e(route('admin-tasks-edit',[$currentWorkspace->slug,$task->project_id,$task->id,])); ?>"><i
                                                                        class="ti ti-edit"></i></a>
                                                                <?php if(auth()->guard('web')->check()): ?>
                                                                    <?php echo e(route('admin-tasks-edit',[$currentWorkspace->slug,$task->project_id,$task->id,])); ?>

                                                                <?php endif; ?>
                                                            <?php endif; ?>

                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    <?php endif; ?>
                </div>


                
                <div class="col-md-12">
                    <?php if((isset($permissions) && in_array('show task', $permissions)) || $currentWorkspace->permission == 'Owner'): ?>

                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <?php if(isset($uploadedFiles)): ?>
                                            <h5 class="mb-0"><?php echo e(__('Project File(s)')); ?> (<?php echo e(count($uploadedFiles)); ?>)</h5>
                                        <?php else: ?>
                                            <h5 class="mb-0"><?php echo e(__('Project File(s)')); ?> (0)</h5>

                                        <?php endif; ?>
                                    </div>

                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="" class="table table-bordered px-2">
                                        <thead>
                                        <tr>
                                            <th><?php echo e(__('Files')); ?></th>

                                            <th><?php echo e(__('Action')); ?> </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(isset($uploadedFiles)): ?>
                                            <?php $__currentLoopData = $uploadedFiles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>

                                                    <td><?php echo e($file->name); ?></td>

                                                    <td class="text-right">
                                                        <div class="col-auto">
                                                            <?php if($currentWorkspace->permission == 'Owner'): ?>
                                                                <?php if(auth()->guard('client')->check()): ?>
                                                                    <a class="action-btn btn-info mx-1  btn btn-sm d-inline-flex align-items-center"
                                                                       href="<?php echo e(route('client-tasks-download',[$currentWorkspace->slug,$file->id,])); ?>"><i
                                                                            class="ti ti-download"></i></a>
                                                                <?php elseif(auth()->guard('web')->check()): ?>
                                                                    <a class="action-btn btn-info mx-1  btn btn-sm d-inline-flex align-items-center"
                                                                       href="<?php echo e(route('admin-tasks-download',[$currentWorkspace->slug,$file->id,])); ?>"><i
                                                                            class="ti ti-download"></i></a>
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
                                                            <?php elseif(isset($permissions)): ?>
                                                                <?php if(in_array('edit task',$permissions)): ?>
                                                                    <a href="#"
                                                                       class="action-btn btn-info mx-1  btn btn-sm d-inline-flex align-items-center"
                                                                       data-ajax-popup="true" data-size="lg"
                                                                       data-toggle="popover" title="<?php echo e(__('Edit')); ?>"
                                                                       data-title="<?php echo e(__('Edit Task')); ?>"
                                                                       data-url="<?php echo e(route('admin-tasks-edit',[$currentWorkspace->slug,$task->project_id,$task->id,])); ?>"><i
                                                                            class="ti ti-edit"></i></a>
                                                                    <?php if(auth()->guard('web')->check()): ?>
                                                                        <?php echo e(route('admin-tasks-edit',[$currentWorkspace->slug,$task->project_id,$task->id,])); ?>

                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                                <?php if(in_array('delete milestone',$permissions)): ?>
                                                                    <a href="#"
                                                                       class="action-btn btn-danger mx-1  btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                                       data-confirm="<?php echo e(__('Are You Sure?')); ?>"
                                                                       data-toggle="popover" title="<?php echo e(__('Delete')); ?>"
                                                                       data-text="<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>"
                                                                       data-confirm-yes="delete-form1-<?php echo e($task->id); ?>"><i
                                                                            class="ti ti-trash"></i></a>
                                                                    <form id="delete-form1-<?php echo e($task->id); ?>"
                                                                          action="<?php echo e(route($client_keyword.'projects.milestone.destroy',[$currentWorkspace->slug,$task->id])); ?>"
                                                                          method="POST" style="display: none;">
                                                                        <?php echo csrf_field(); ?>
                                                                        <?php echo method_field('DELETE'); ?>
                                                                    </form>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    <?php endif; ?>
                </div>

                
            </div>
            <!-- [ sample-page ] end -->
        </div>
        <!-- [ Main Content ] end -->
    </div>

    


    <?php echo $__env->make('projects.action_button._action_button', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
        $(document).ready(function () {
            $('#summernote').summernote();
        });
        (function () {
            var options = {
                chart: {
                    type: 'area',
                    height: 60,
                    sparkline: {
                        enabled: true,
                    },
                },
                colors: <?php echo json_encode($chartData['color']); ?>,
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 2,
                },
                series: [<?php $__currentLoopData = $chartData['stages']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                {
                    name: "<?php echo e(__($name)); ?>",
                    // data:
                    data: <?php echo json_encode($chartData[$id]); ?>,
                },
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>],
                xaxis: {
                    type: "category",
                    categories: <?php echo json_encode($chartData['label']); ?>,
                    title: {
                        text: '<?php echo e(__("Days")); ?>'
                    },
                    tooltip: {
                        enabled: false,
                    }
                },
                yaxis: {
                    show: true,
                    position: "left",
                    title: {
                        text: '<?php echo e(__("Tasks")); ?>'
                    },
                },
                grid: {
                    show: true,
                    borderColor: "#EBEBEB",
                    strokeDashArray: 0,
                    position: "back",
                    xaxis: {
                        show: true,
                        lines: {
                            show: true,
                        },
                    },
                    yaxis: {
                        show: false,
                        lines: {
                            show: false,
                        },
                    },
                    row: {
                        colors: undefined,
                        opacity: 0.5,
                    },
                    column: {
                        position: "back",
                        colors: undefined,
                        opacity: 0.5,
                    },
                    padding: {
                        top: 0,
                        right: 0,
                        bottom: 0,
                        left: 0,
                    },
                },
                tooltip: {
                    followCursor: false,
                    fixed: {
                        enabled: false
                    },
                    x: {
                        format: 'dd/MM/yy HH:mm'
                    },

                    marker: {
                        show: false
                    }
                }
            }
            var chart = new ApexCharts(document.querySelector("#task-chart"), options);
            chart.render();
        })();

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

    <script src="<?php echo e(asset('custom/js/dropzone.min.js')); ?>"></script>
    <script>
        Dropzone.autoDiscover = false;
        myDropzone = new Dropzone("#dropzonewidget", {
            maxFiles: 20,
            maxFilesize: 20,
            parallelUploads: 1,
            acceptedFiles: ".jpeg,.jpg,.png,.gif,.svg,.pdf,.txt,.doc,.docx,.zip,.rar",

            url: "<?php if(auth()->guard('client')->check()): ?> <?php echo e(route('projects-file-upload',[$currentWorkspace->slug,$project->project_id])); ?>

                <?php elseif(auth()->guard('web')->check()): ?> <?php echo e(route('admin-file-upload',[$currentWorkspace->slug,$project->project_id])); ?><?php endif; ?>",
            success: function (file, response) {
                if (response.is_success) {
                    dropzoneBtn(file, response);
                } else {
                    myDropzone.removeFile(file);
                    toastr('<?php echo e(__('Error')); ?>', response.error, 'error');
                }
            },
            error: function (file, response) {
                myDropzone.removeFile(file);
                if (response.error) {
                    toastr('<?php echo e(__('Error')); ?>', response.error, 'error');
                } else {
                    toastr('<?php echo e(__('Error')); ?>', response, 'error');
                }
            }
        });
        myDropzone.on("sending", function (file, xhr, formData) {
            formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
            formData.append("project_id", <?php echo e($project->id); ?>);
        });

        <?php if(isset($permisions) && in_array('show uploading',$permisions)): ?>
        $(".dz-hidden-input").prop("disabled", true);
        myDropzone.removeEventListeners();
        <?php endif; ?>

        function dropzoneBtn(file, response) {

            var html = document.createElement('span');
            var download = document.createElement('a');
            download.setAttribute('href', response.download);
            download.setAttribute('class', "action-btn btn-primary mx-1  btn btn-sm d-inline-flex align-items-center");
            download.setAttribute('data-toggle', "popover");
            download.setAttribute('title', "<?php echo e(__('Download')); ?>");
            // download.innerHTML = "<i class='fas fa-download mt-2'></i>";
            download.innerHTML = "<i class='ti ti-download'> </i>";
            html.appendChild(download);

            <?php if(isset($permisions) && in_array('show uploading',$permisions)): ?>
            <?php else: ?>
            var del = document.createElement('a');
            del.setAttribute('href', response.delete);
            del.setAttribute('class', "action-btn btn-danger mx-1  btn btn-sm d-inline-flex align-items-center");
            del.setAttribute('data-toggle', "popover");
            del.setAttribute('title', "<?php echo e(__('Delete')); ?>");
            del.innerHTML = "<i class='ti ti-trash '></i>";

            del.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                if (confirm("Are you sure ?")) {
                    var btn = $(this);
                    $.ajax({
                        url: btn.attr('href'),
                        type: 'DELETE',
                        success: function (response) {
                            if (response.is_success) {
                                btn.closest('.dz-image-preview').remove();
                            } else {
                                toastr('<?php echo e(__('Error')); ?>', response.error, 'error');
                            }
                        },
                        error: function (response) {
                            response = response.responseJSON;
                            if (response.is_success) {
                                toastr('<?php echo e(__('Error')); ?>', response.error, 'error');
                            } else {
                                toastr('<?php echo e(__('Error')); ?>', response, 'error');
                            }
                        }
                    })
                }
            });
            html.appendChild(del);
            <?php endif; ?>

            file.previewTemplate.appendChild(html);
        }

        <?php ($files = $project->files); ?>
        <?php $__currentLoopData = $files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        <?php ($storage_file = storage_path('project_files/'.$file->file_path)); ?>
        // Create the mock file:
        var mockFile = {
            name: "<?php echo e($file->file_name); ?>",
            size: <?php echo e(file_exists($storage_file) ? filesize($storage_file) : 0); ?>

        };
        // Call the default addedfile event handler
        myDropzone.emit("addedfile", mockFile);
        // And optionally show the thumbnail of the file:
        myDropzone.emit("thumbnail", mockFile, "<?php echo e(asset('storage/project_files/'.$file->file_path)); ?>");
        myDropzone.emit("complete", mockFile);

        dropzoneBtn(mockFile, {
            download: "<?php if(auth()->guard('client')->check()): ?> <?php echo e(route('projects-file-download',[$currentWorkspace->slug,$project->id,$file->id])); ?>

                <?php elseif(auth()->guard('web')->check()): ?> <?php echo e(route('admin-file-download',[$currentWorkspace->slug,$project->project_id])); ?><?php endif; ?>",


            delete: "<?php if(auth()->guard('client')->check()): ?> <?php echo e(route('projects-file-delete',[$currentWorkspace->slug,$project->id,$file->id])); ?>

                <?php elseif(auth()->guard('web')->check()): ?> <?php echo e(route('admin-file-delete',[$currentWorkspace->slug,$project->project_id])); ?><?php endif; ?>",
        });

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/heritage/SHINE/LATEST/projectmanagement/resources/views/projects/show.blade.php ENDPATH**/ ?>