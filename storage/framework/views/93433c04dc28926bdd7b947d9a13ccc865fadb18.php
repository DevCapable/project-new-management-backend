











<li class="dash-item <?php echo e((Request::route()->getName() == 'client-projects-index' || Request::segment(3) == 'projects') ? ' active' : ''); ?>">
    <a href="<?php echo e(route('client-projects-index',$currentWorkspace->slug)); ?>"
       class="dash-link "><span class="dash-micon"><i data-feather="briefcase"></i></span><span
            class="dash-mtext"><?php echo e(__('Projects')); ?></span></a>
</li>

















<?php if(env('CHAT_MODULE') == 'on'): ?>
    <li class="dash-item <?php echo e((Request::route()->getName() == 'chats') ? ' active' : ''); ?>">
        <a href="<?php echo e(route('chats')); ?>" class="dash-link"><span class="dash-micon"><i
                    class="ti ti-message-circle"></i></span><span
                class="dash-mtext"><?php echo e(__('Messenger')); ?></span></a>

    </li>
<?php endif; ?>


<li class="dash-item <?php echo e((Request::route()->getName() == 'client.invoices.index') ? ' active' : ''); ?>">
    <a href="<?php echo e(route('client.invoices.index',$currentWorkspace->slug)); ?>"
       class="dash-link "><span class="dash-micon"><i
                data-feather="printer"></i></span><span
            class="dash-mtext"><?php echo e(__('Invoices')); ?> </span></a>
</li>







<li class="dash-item <?php echo e((Request::route()->getName() == 'client.project_report.index' || Request::segment(3) == 'project_report') ? ' active' : ''); ?>">
    <a href="<?php echo e(route('client.project_report.index',$currentWorkspace->slug)); ?>"
       class="dash-link "><span class="dash-micon"><i
                class="ti ti-chart-line"></i></span><span
            class="dash-mtext"><?php echo e(__('Project Report')); ?></span></a>
</li>


<li class="dash-item <?php echo e((Request::route()->getName() == 'client.zoom-meeting.index') ? ' active' : ''); ?>">
    <a href="<?php echo e(route('client.zoom-meeting.index',$currentWorkspace->slug)); ?>"
       class="dash-link "><span
            class="dash-micon"><i data-feather="video"></i></span><span
            class="dash-mtext"><?php echo e(__('Zoom Meeting')); ?></span></a>

</li>
<?php /**PATH /home/heritage/PROJECTS/NEW/management/resources/views/partials/sidebars/client_sidebar.blade.php ENDPATH**/ ?>