
<li class="dash-item <?php echo e((Request::route()->getName() == 'admin-client-projects-index' || Request::segment(2) == 'projects') ? ' active' : ''); ?>">
    <a href="<?php echo e(route('admin-client-projects-index',$currentWorkspace->slug)); ?>" class="dash-link"><span
            class="dash-micon"> <i data-feather="briefcase"></i></span><span
            class="dash-mtext"><?php echo e(__('Client Projects')); ?></span></a>
</li>
<li class="dash-item <?php echo e((Request::route()->getName() == 'admin-projects-index' || Request::segment(2) == 'projects') ? ' active' : ''); ?>">
    <a href="<?php echo e(route('admin-projects-index',$currentWorkspace->slug)); ?>" class="dash-link"><span
            class="dash-micon"> <i data-feather="briefcase"></i></span><span
            class="dash-mtext"><?php echo e(__('My Projects')); ?></span></a>
</li>
<?php if(isset($currentWorkspace) && $currentWorkspace  && Auth::user()->getGuard() != 'client'): ?>

    <li class="dash-item <?php echo e((Request::route()->getName() == 'workspace.settings') ? ' active' : ''); ?>">
        <a href="<?php echo e(route('workspace.settings',$currentWorkspace->slug)); ?>" class="dash-link "><span
                class="dash-micon"><i data-feather="settings"></i></span><span
                class="dash-mtext"><?php echo e(__('Settings')); ?></span></a>
    </li>
<?php endif; ?>

<?php /**PATH /home/heritage/SHINE/LATEST/projectmanagement/resources/views/partials/sidebars/user_sidebar.blade.php ENDPATH**/ ?>