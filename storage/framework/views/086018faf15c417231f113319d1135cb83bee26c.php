<div class="card-body p-3">
    <div class="timeline timeline-one-side " data-timeline-content="axis" data-timeline-axis-style="dashed">
        <form method="post" action="<?php if(auth()->guard('client')->check()): ?><?php echo e(route('store-client-project',$currentWorkspace->slug)); ?>

        <?php elseif(auth()->guard('web')->check()): ?><?php echo e(route('store-admin-project',$currentWorkspace->slug)); ?> <?php endif; ?>">
            <?php echo csrf_field(); ?>
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0"><?php echo e(__('Comment')); ?></h5>
                        </div>
                    </div>
                </div>
                <textarea id="summernote" name="comment"></textarea>
            </div>
            <input type="hidden" name="project_id" value="<?php echo e($project->project_id); ?>">

            <br>


            <div style="float: right">
                <?php if(auth()->guard('client')->check()): ?>
                    <?php if($currentWorkspace->permission == 'Owner'): ?>
                        <?php if($project->status == 'NotSubmitted'): ?>
                            <div class="d-flex align-items-center ">

                                <button type="submit" name="action" value="submit"
                                        class="btn  btn-danger btn-lg btn-block float-md-right">
                                    <i class="ti ti-send fa-lg"></i><?php echo e(__('Submit project for review')); ?>

                                </button>

                                <button type="submit" name="action" value="project_page"
                                        class="btn btn-success btn-lg btn-block float-md-right">
                                    <i class="fa fa-envelope-open-text fa-lg"></i><?php echo e(__('Dashboard')); ?>

                                </button>

                                <button class="btn btn-light d-flex align-items-between me-3">
                                    <a href="#" class=""
                                       data-url="<?php echo e(route('edit-client-project',[$currentWorkspace->slug,$project->project_id])); ?>"
                                       data-ajax-popup="true" data-title="<?php echo e(__('Edit Project')); ?>"
                                       data-toggle="popover" title="<?php echo e(__('Edit')); ?>">
                                        <i class="ti ti-edit"> </i>Edit
                                    </a>
                                </button>

                        <?php else: ?>
                            <span class="badge rounded-pill bg-danger">Submitted</span>

                        <?php endif; ?>

            <?php endif; ?>

            <?php elseif(auth()->guard('web')->check()): ?>
                    <?php if($currentWorkspace->permission == 'Owner' && !$project->creater): ?>
                        <?php if($project->status == 'NotSubmitted'): ?>
                            <div class="d-flex align-items-center ">

                                <button type="submit" name="action" value="submit"
                                        class="btn  btn-danger btn-lg btn-block float-md-right">
                                    <i class="ti ti-send fa-lg"></i><?php echo e(__('Submit project for review')); ?>

                                </button>

                                <button type="submit" name="action" value="submit"
                                        class="btn btn-success btn-lg btn-block float-md-right">
                                    <i class="fa fa-envelope-open-text fa-lg"></i><?php echo e(__('Dashboard')); ?>

                                </button>

                                <div class="d-flex align-items-center ">
                                    <button class="btn btn-outline-success d-flex align-items-between me-3">
                                        <a href="#" class=""
                                           data-url="<?php echo e(route('admin-project-review',[$currentWorkspace->slug,$project->project_id])); ?>"
                                           data-ajax-popup="true" data-title="<?php echo e(__('Review Project')); ?>"
                                           data-toggle="popover" title="<?php echo e(__('Review')); ?>">
                                            <i class="ti ti-box-multiple-7"> Review </i>
                                        </a>
                                    </button>
                                </div>


                                <?php else: ?>
                                    <span class="badge rounded-pill bg-danger">Submitted</span>

                                <?php endif; ?>

                                <?php else: ?>
            <?php endif; ?>
            <?php endif; ?>
        </form>
    </div>
</div>
<?php /**PATH /home/heritage/SHINE/LATEST/projectmanagement/resources/views/projects/action_button/_action_button.blade.php ENDPATH**/ ?>