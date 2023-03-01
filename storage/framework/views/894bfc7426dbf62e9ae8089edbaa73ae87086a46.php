<li class="dash-item <?php echo e((Request::route()->getName() == 'admin-client-projects-index' || Request::segment(2) == 'projects') ? ' active' : ''); ?>">
    <a href="<?php echo e(route('admin-client-projects-index',$currentWorkspace->slug)); ?>" class="dash-link"><span
            class="dash-micon"> <i data-feather="briefcase"></i></span><span
            class="dash-mtext"><?php echo e(__('Client Projects')); ?></span></a>
</li>
<li class="dash-item <?php echo e((Request::route()->getName() == 'lang_workspace') ? ' active' : ''); ?>">
    <a href="<?php echo e(route('lang_workspace')); ?>" class="dash-link "><span class="dash-micon"><i
                class="ti ti-world nocolor"></i></span><span
            class="dash-mtext"><?php echo e(__('Languages')); ?></span></a>
</li>


<li class="dash-item <?php echo e((Request::route()->getName() == 'email_template*' || Request::segment(1) == 'email_template_lang') ? ' active' : ''); ?>">
    <a class="dash-link" href="<?php echo e(route('email_template.index')); ?>">
        <span class="dash-micon"><i class="ti ti-mail"></i></span><span
            class="dash-mtext"><?php echo e(__('Email Templates')); ?></span>
    </a>
</li>
<li class="dash-item <?php echo e((Request::route()->getName() == 'settings.index') ? ' active' : ''); ?>">
    <a href="<?php echo e(route('settings.index')); ?>" class="dash-link "><span class="dash-micon"><i
                data-feather="settings"></i></span><span
            class="dash-mtext"> <?php echo e(__('Settings')); ?></span></a>
</li>
<li class="dash-item <?php echo e((Request::route()->getName() == 'admin-projects-index' || Request::segment(2) == 'projects') ? ' active' : ''); ?>">
    <a href="<?php echo e(route('admin-projects-index',$currentWorkspace->slug)); ?>" class="dash-link"><span class="dash-micon"> <i data-feather="briefcase"></i></span><span  class="dash-mtext"><?php echo e(__('Projects ')); ?></span></a>
</li>
<?php /**PATH /home/heritage/SHINE/LATEST/projectmanagement/resources/views/partials/sidebars/admin_sidebar.blade.php ENDPATH**/ ?>