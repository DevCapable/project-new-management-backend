<?php $__env->startSection('page-title'); ?> <?php echo e(__('Projects')); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('links'); ?>
    <?php if(\Auth::guard('client')->check()): ?>
        <li class="breadcrumb-item"><a href="<?php echo e(route('client.home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <?php else: ?>
        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <?php endif; ?>
    <li class="breadcrumb-item"> <?php echo e(__('Projects')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('action-button'); ?>
    <?php if(auth()->guard('web')->check()): ?>

        <a href="<?php echo e(route('project.export')); ?>"  class="btn btn-sm btn-primary "  data-toggle="tooltip" title="<?php echo e(__('Export ')); ?>"
        > <i class="ti ti-file-x"></i></a>

        <a href="#"  class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-title="<?php echo e(__('Import Project')); ?>" data-url="<?php echo e(route('project.file.import' ,$currentWorkspace->slug)); ?>"  data-toggle="tooltip" title="<?php echo e(__('Import')); ?>"><i class="ti ti-file-import"></i> </a>

        <?php if(isset($currentWorkspace) && $currentWorkspace->creater->id == Auth::id()): ?>
            <a href="<?php echo e(route('create-new-admin-projects',$currentWorkspace->slug)); ?>" class="btn btn-sm btn-primary" data-toggle="tooltip" title="<?php echo e(__('Create')); ?>">
                <i class="ti ti-plus"></i>
            </a>
        <?php endif; ?>
    <?php endif; ?>
    <?php if(auth()->guard('client')->check()): ?>

        <a href="<?php echo e(route('project.export')); ?>"  class="btn btn-sm btn-primary "  data-toggle="tooltip" title="<?php echo e(__('Export ')); ?>"
        > <i class="ti ti-file-x"></i></a>

        <a href="#"  class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-title="<?php echo e(__('Import Project')); ?>" data-url="<?php echo e(route('project.file.import' ,$currentWorkspace->slug)); ?>"  data-toggle="tooltip" title="<?php echo e(__('Import')); ?>"><i class="ti ti-file-import"></i> </a>

        <?php if(isset($currentWorkspace) || $currentWorkspace->creater->id == Auth::id()): ?>
            <a href="<?php echo e(route('create-new-client-projects',$currentWorkspace->slug)); ?>" class="btn btn-sm btn-primary" data-toggle="tooltip" title="<?php echo e(__('Create')); ?>">
                <i class="ti ti-plus"></i>
            </a>
        <?php endif; ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="section">
        <?php if($projects && $currentWorkspace): ?>
            <div class="row mb-2">
                <div class="col-xl-12 col-lg-12 col-md-12 d-flex align-items-center justify-content-end">
                    <div class="text-sm-right status-filter">
                        <div class="btn-group mb-3">

                         <?php echo $__env->make('partials.buttons._status_butons', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                    </div>
                </div><!-- end col-->
            </div>

            <div class="filters-content">
                <div class="row grid">
                    <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-3 All <?php echo e($project->status); ?>">
                            <div class="card">
                                <div class="card-header border-0 pb-0">
                                    <div class="d-flex align-items-center">
                                        <?php if($project->is_active): ?>
                                            <a href="<?php if(auth()->guard('web')->check()): ?><?php echo e(route('projects.show',[$currentWorkspace->slug,$project->id])); ?><?php elseif(auth()->guard()->check()): ?><?php echo e(route('client.projects.show',[$currentWorkspace->slug,$project->id])); ?><?php endif; ?>" class="">
                                                <img alt="<?php echo e($project->name); ?>" class="img-fluid wid-30 me-2 fix_img" avatar="<?php echo e($project->name); ?>">
                                            </a>
                                        <?php else: ?>
                                            <a href="#" class="">
                                                <img alt="<?php echo e($project->name); ?>" class="img-fluid wid-30 me-2 fix_img" avatar="<?php echo e($project->name); ?>">
                                            </a>
                                        <?php endif; ?>

                                        <h5 class="mb-0">
                                            <?php if($project->is_active): ?>
                                                <?php if(auth()->guard('web')->check()): ?>
                                                    <a href="<?php echo e(route('admin-show-project',[$currentWorkspace->slug,$project->project_id])); ?> " title="<?php echo e($project->name); ?>" class=""><?php echo e($project->name); ?></a>
                                                <?php elseif(auth()->guard()->check()): ?>
                                                    <a href="<?php echo e(route('show-client-project',[$currentWorkspace->slug,$project->project_id])); ?>" title="<?php echo e($project->name); ?>" class=""><?php echo e($project->name); ?></a>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <a href="#" title="<?php echo e(__('Locked')); ?>" class=""><?php echo e($project->name); ?></a>
                                            <?php endif; ?>
                                        </h5>
                                    </div>
                                    <div class="card-header-right">
                                        <div class="btn-group card-option">
                                            <?php if(auth()->guard('web')->check()): ?>
                                                <button type="button" class="btn dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                    <i class="feather icon-more-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">


                                                    <?php if($project->is_active): ?>

                                                        <?php if($currentWorkspace->permission == 'Owner'): ?>
                                                            <a href="#" class="dropdown-item" data-ajax-popup="true" data-size="md" data-title="<?php echo e(__('Invite Users')); ?>" data-url="<?php echo e(route('admin-projects-invite-popup',[$currentWorkspace->slug,$project->project_id])); ?>">
                                                                <i class="ti ti-user-plus"></i> <span><?php echo e(__('Invite Users')); ?></span>
                                                            </a>
                                                            <a href="#" class="dropdown-item" data-ajax-popup="true" data-size="lg" data-title="<?php echo e(__('Edit Project')); ?>" data-url="<?php echo e(route('admin-projects-edit',[$currentWorkspace->slug,$project->project_id])); ?>">
                                                                <i class="ti ti-edit"></i> <span><?php echo e(__('Edit')); ?></span>
                                                            </a>
                                                            <a href="#" class="dropdown-item" data-ajax-popup="true" data-size="md" data-title="<?php echo e(__('Share to Clients')); ?>" data-url="<?php echo e(route('admin-projects-share-popup',[$currentWorkspace->slug,$project->project_id])); ?>">
                                                                <i class="ti ti-share"></i> <span><?php echo e(__('Share to Clients')); ?></span>
                                                            </a>
                                                            <a href="#" class="dropdown-item text-danger delete-popup bs-pass-para" data-confirm="<?php echo e(__('Are You Sure?')); ?>" data-text="<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="delete-form-<?php echo e($project->project_id); ?>" >
                                                                <i class="ti ti-trash"></i>  <span><?php echo e(__('Delete')); ?></span>
                                                            </a>
                                                            <form id="delete-form-<?php echo e($project->project_id); ?>" action="<?php echo e(route('admin-projects-destroy',[$currentWorkspace->slug,$project->project_id])); ?>" method="POST" style="display: none;">
                                                                <?php echo csrf_field(); ?>
                                                                <?php echo method_field('DELETE'); ?>
                                                            </form>
                                                        <?php else: ?>
                                                            <a href="#" class="dropdown-item text-danger bs-pass-para" data-confirm="<?php echo e(__('Are You Sure?')); ?>" data-text="<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="leave-form-<?php echo e($project->id); ?>">
                                                                <i class="ti ti-trash"></i>  <span><?php echo e(__('Delete')); ?></span>
                                                            </a>
                                                            <form id="leave-form-<?php echo e($project->project_id); ?>" action="<?php echo e(route('admin-projects-leave',[$currentWorkspace->slug,$project->id])); ?>" method="POST" style="display: none;">
                                                                <?php echo csrf_field(); ?>
                                                                <?php echo method_field('DELETE'); ?>
                                                            </form>
                                                        <?php endif; ?>

                                                    <?php else: ?>
                                                        <a href="#" class="dropdown-item" title="<?php echo e(__('Locked')); ?>">
                                                            <i data-feather="lock"></i> <span><?php echo e(__('Locked')); ?></span>
                                                        </a>
                                                    <?php endif; ?>

                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row g-2 justify-content-between">

                                        <div class="col-auto"> <?php echo get_projects_status_label($project->status); ?></div>

                                        <div class="col-auto">
                                            <p class="mb-0"><b><?php echo e(__('Due Date:')); ?></b> <?php echo e($project->end_date); ?></p>
                                        </div>
                                    </div>
                                    <p class="text-muted text-sm mt-3"><?php echo e($project->description); ?></p>

                                        <h6 class="text-muted">STAFF(S) INCHARGED</h6>
                                        <div class="user-group mx-2">
                                            <?php if(isset($project->users)): ?>
                                                <?php $__currentLoopData = $project->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($user->pivot->is_active): ?>
                                                        <!-- <img src="../assets/images/user/avatar-1.jpg" alt="image"> -->
                                                        <a href="#" class="img_group" data-toggle="tooltip" data-placement="top" title="<?php echo e($user->name); ?>">
                                                            <img alt="<?php echo e($user->name); ?>" <?php if($user->avatar): ?> src="<?php echo e(asset('/storage/avatars/'.$user->avatar)); ?>" <?php else: ?> avatar="<?php echo e($user->name); ?>" <?php endif; ?>>
                                                        </a>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                                <a href="<?php if(auth()->guard('web')->check()): ?><?php echo e(route('projects.show',[$currentWorkspace->slug,$project->id])); ?><?php elseif(auth()->guard()->check()): ?><?php echo e(route('client.projects.show',[$currentWorkspace->slug,$project->id])); ?><?php endif; ?>" class="">
                                                    <img alt="No staff yet" class="img-fluid wid-30 me-2 fix_img" avatar="N/A">
                                                </a>



                                        </div>

                                    <div class="card mb-0 mt-3">
                                        <div class="card-body p-3">
                                            <div class="row">
                                                <div class="col-6">
                                                    <h6 class="mb-0"><?php echo e($project->countTask()); ?></h6>
                                                    <p class="text-muted text-sm mb-0"><?php echo e(__('Tasks')); ?></p>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <h6 class="mb-0"><?php echo e($project->countTaskComments()); ?></h6>
                                                    <p class="text-muted text-sm mb-0"><?php echo e(__('Comments')); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php if(auth()->guard('web')->check()): ?>
                        <?php if(isset($currentWorkspace) && $currentWorkspace->creater->id == Auth::id()): ?>
                            <div class="col-md-3 All add_project">
                                <a href="<?php echo e(route('create-new-admin-projects',$currentWorkspace->slug)); ?>" class="btn-addnew-project " style="padding: 90px 10px;"  data-size="md" data-title="<?php echo e(__('Create New Project')); ?>">
                                    <div class="bg-primary proj-add-icon">
                                        <i class="ti ti-plus"></i>
                                    </div>
                                    <h6 class="mt-4 mb-2">Add Project</h6>
                                    <p class="text-muted text-center">Click here to add New Project</p>
                                </a>
                            </div>
                        <?php endif; ?>











                    <?php endif; ?>
                    <?php if(auth()->guard('client')->check()): ?>
                        <?php if(isset($currentWorkspace) || $currentWorkspace->clientCreater->id == Auth::id()): ?>
                            <div class="col-md-3 All add_project">
                                <a href="<?php echo e(route('create-new-client-projects',$currentWorkspace->slug)); ?>" class="btn-addnew-project " style="padding: 90px 10px;"  data-size="md" data-title="<?php echo e(__('Create New Project')); ?>">
                                    <div class="bg-primary proj-add-icon">
                                        <i class="ti ti-plus"></i>
                                    </div>
                                    <h6 class="mt-4 mb-2">Add Project</h6>
                                    <p class="text-muted text-center">Click here to add New Project</p>
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                </div>
            </div>
        <?php else: ?>
            <div class="container mt-5">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="page-error">
                            <div class="page-inner">
                                <h1>404</h1>
                                <div class="page-description">
                                    <?php echo e(__('Page Not Found')); ?>

                                </div>
                                <div class="page-search">
                                    <p class="text-muted mt-3"><?php echo e(__("It's looking like you may have taken a wrong turn. Don't worry... it happens to the best of us. Here's a little tip that might help you get back on track.")); ?></p>
                                    <div class="mt-3">
                                        <a class="btn-return-home badge-blue" href="<?php echo e(route('home')); ?>"><i class="fas fa-reply"></i> <?php echo e(__('Return Home')); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('css-page'); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('custom/js/isotope.pkgd.min.js')); ?>"></script>
    <script>
        $(document).ready(function () {

            $('.status-filter button').click(function () {
                $('.status-filter button').removeClass('active');
                $(this).addClass('active');

                var data = $(this).attr('data-filter');
                $grid.isotope({
                    filter: data
                })
            });

            var $grid = $(".grid").isotope({
                itemSelector: ".All",
                percentPosition: true,
                masonry: {
                    columnWidth: ".All"
                }
            })
        });
    </script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/heritage/SHINE/LATEST/projectmanagement/resources/views/projects/index.blade.php ENDPATH**/ ?>