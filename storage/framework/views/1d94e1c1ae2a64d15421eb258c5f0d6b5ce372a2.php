<?php
    if(Auth::user()->type == 'admin')
    {
    $setting = App\Models\Utility::getAdminPaymentSettings();
        if ($setting['color']) {
            $color = $setting['color'];
        }
        else{
        $color = 'theme-3';
        }
        $dark_mode = $setting['cust_darklayout'];
        $cust_theme_bg =$setting['cust_theme_bg'];
        $SITE_RTL = env('SITE_RTL');
         $company_logo = App\Models\Utility::get_logo();
    }
    else {
        $setting = App\Models\Utility::getcompanySettings($currentWorkspace->id);
        $color = $setting->theme_color;
        $dark_mode = $setting->cust_darklayout;
        $SITE_RTL = $setting->site_rtl;
        $cust_theme_bg = $setting->cust_theme_bg;
        $company_logo = App\Models\Utility::getcompanylogo($currentWorkspace->id);
    }

       if($color == '' || $color == null){
          $settings = App\Models\Utility::getAdminPaymentSettings();
          $color = $settings['color'];
       }

       if($dark_mode == '' || $dark_mode == null){
         $company_logo = App\Models\Utility::get_logo();
          $dark_mode = $settings['cust_darklayout'];
       }

       if($cust_theme_bg == '' || $dark_mode == null){
          $cust_theme_bg = $settings['cust_theme_bg'];
       }

        if($SITE_RTL == '' || $SITE_RTL == null){
          $SITE_RTL = env('SITE_RTL');
       }
?>
<nav class="dash-sidebar light-sidebar <?php echo e((isset($cust_theme_bg) && $cust_theme_bg == 'on') ? 'transprent-bg':''); ?>">

    <div class="navbar-wrapper">
        <div class="m-header main-logo">
            <a href="<?php echo e(route('home')); ?>" class="b-brand">
                <!-- ========   change your logo hear   ============ -->
                <?php echo $__env->make('layouts._company_logo', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </a>
        </div>
        <div class="navbar-content">
            <ul class="dash-navbar">
                
                <?php if(\Auth::guard('client')->check()): ?>
                    <li class="dash-item dash-hasmenu">
                        <a href="<?php echo e(route('client.home')); ?>"
                           class="dash-link <?php echo e((Request::route()->getName() == 'home' || Request::route()->getName() == NULL || Request::route()->getName() == 'client.home') ? ' active' : ''); ?>">
                            <span class="dash-micon"><i class="ti ti-home"></i></span>
                            <span class="dash-mtext"><?php echo e(__('Dashboard')); ?></span>
                        </a>
                    </li>







                    <?php echo $__env->make('partials.sidebars.client_sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php else: ?>
                    <li class="dash-item dash-hasmenu">
                        <a href="<?php echo e(route('home')); ?>"
                           class="dash-link  <?php echo e((Request::route()->getName() == 'home' || Request::route()->getName() == NULL) ? ' active' : ''); ?>">
                            <?php if(Auth::user()->type == 'admin'): ?>
                                <span class="dash-micon"><i class="ti ti-user"></i></span>
                                <span class="dash-mtext"><?php echo e(__('Users')); ?></span>
                            <?php else: ?>
                                <span class="dash-micon"><i class="ti ti-home"></i></span>
                                <span class="dash-mtext"><?php echo e(__('Dashboard')); ?></span>
                            <?php endif; ?>
                        </a>
                    </li>


                <?php endif; ?>
                <?php if(isset($currentWorkspace) && $currentWorkspace): ?>
                    <?php if(auth()->guard('web')->check()): ?>
                        <li class="dash-item dash-hasmenu">
                            <a href="<?php echo e(route('users.index',$currentWorkspace->slug)); ?>"
                               class="dash-link <?php echo e((Request::route()->getName() == 'users.index') ? ' active' : ''); ?>"><span
                                    class="dash-micon"> <i data-feather="user"></i></span><span
                                    class="dash-mtext"><?php echo e(__('Staffs')); ?></span></a>
                        </li>
                        <?php if(Auth::user()->type == 'user'): ?>
                            <?php if($currentWorkspace->creater->id == Auth::user()->id): ?>
                                <li class="dash-item dash-hasmenu">
                                    <a href="<?php echo e(route('clients.index',$currentWorkspace->slug)); ?>"
                                       class="dash-link <?php echo e((Request::route()->getName() == 'clients.index') ? ' active' : ''); ?>"><span
                                            class="dash-micon">  <i class="ti ti-brand-python"></i></span><span
                                            class="dash-mtext"> <?php echo e(__('Clients')); ?></span></a>
                                </li>
                            <?php endif; ?>
                                <?php echo $__env->make('partials.sidebars.user_sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>








                        <?php endif; ?>
                        <li class="dash-item dash-hasmenu">
                            <a href="<?php echo e(route('client-appointment-index',$currentWorkspace->slug)); ?>"
                               class="dash-link  <?php echo e((Request::route()->getName() == 'appointment' || Request::route()->getName() == 'appointment.index') ? ' active' : ''); ?>">
                                <?php if(Auth::user()->type == 'admin'): ?>
                                    <span class="dash-micon"><i class="ti ti-calendar"></i></span>
                                    <span class="dash-mtext"><?php echo e(__('Appointments')); ?></span>
                                <?php else: ?>
                                    <span class="dash-micon"><i class="ti ti-calendar"></i></span>
                                    <span class="dash-mtext"><?php echo e(__('Appointments')); ?></span>
                                <?php endif; ?>
                            </a>
                        </li>

                            <li class="dash-item <?php echo e((Request::route()->getName() == 'tasks.index') ? ' active' : ''); ?>">
                                <a href="<?php echo e(route('tasks.index',$currentWorkspace->slug)); ?>" class="dash-link "><span
                                        class="dash-micon"><i data-feather="list"></i></span><span
                                        class="dash-mtext"><?php echo e(__('Tasks')); ?></span></a>
                            </li>







                            <?php if(isset($currentWorkspace) && Auth::user()->type == 'user' && $currentWorkspace && $currentWorkspace->creater->id == Auth::user()->id): ?>
                                <li class="dash-item d-none dash-hasmenu <?php echo e((Request::route()->getName() == 'contracts.index' || Request::route()->getName() == 'contracts.show') ? ' active' : ''); ?>">
                                    <a href="#" class="dash-link"
                                    ><span class="dash-micon"><i class="ti ti-device-floppy"></i></span
                                        ><span class="dash-mtext"><?php echo e(__('Contracts')); ?> </span
                                        ><span class="dash-arrow"><i data-feather="chevron-right"></i></span
                                        ></a>

                                </li>
                            <?php endif; ?>






















                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if(isset($currentWorkspace) && Auth::user()->type == 'user' && $currentWorkspace): ?>
                        <?php if(auth()->guard('web')->check()): ?>

                            <li class="dash-item <?php echo e((Request::route()->getName() == 'project_report.index' || Request::segment(2) == 'project_report') ? ' active' : ''); ?>">
                                <a href="<?php echo e(route('project_report.index',$currentWorkspace->slug)); ?>"
                                   class="dash-link "><span class="dash-micon"><i
                                            class="ti ti-chart-line"></i></span><span
                                        class="dash-mtext"><?php echo e(__('Project Report')); ?></span></a>
                            </li>

                            <li class="dash-item <?php echo e((Request::route()->getName() == 'zoom-meeting.index') ? ' active' : ''); ?>">
                                <a href="<?php echo e(route('zoom-meeting.index',$currentWorkspace->slug)); ?>"
                                   class="dash-link "><span
                                        class="dash-micon"><i data-feather="video"></i></span><span
                                        class="dash-mtext"><?php echo e(__('Zoom Meeting')); ?></span></a>

                            </li>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if(Auth::user()->type == 'admin'): ?>

                        <?php echo $__env->make('partials.sidebars.admin_sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php endif; ?>








        </div>
    </div>
</nav>
<?php /**PATH /home/heritage/PROJECTS/NEW/management/resources/views/partials/sidebar.blade.php ENDPATH**/ ?>