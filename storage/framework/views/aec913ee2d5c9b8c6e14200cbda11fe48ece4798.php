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
}
else {
    $setting = App\Models\Utility::getcompanySettings($currentWorkspace->id);
    $color = $setting->theme_color;
    $dark_mode = $setting->cust_darklayout;
    $SITE_RTL = $setting->site_rtl;
    $cust_theme_bg = $setting->cust_theme_bg;
}

   if($color == '' || $color == null){
      $settings = App\Models\Utility::getAdminPaymentSettings();
      $color = $settings['color'];
   }

   if($dark_mode == '' || $dark_mode == null){
      $dark_mode = $settings['cust_darklayout'];
   }

   if($cust_theme_bg == '' || $dark_mode == null){
      $cust_theme_bg = $settings['cust_theme_bg'];
   }

    if($SITE_RTL == '' || $SITE_RTL == null){
      $SITE_RTL = env('SITE_RTL');
    }
?>


<?php $__env->startSection('page-title', __('Settings')); ?>
<?php $__env->startSection('links'); ?>
<?php if(\Auth::guard('client')->check()): ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('client.home')); ?>"><?php echo e(__('Home')); ?></a></li>
 <?php else: ?>
 <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
 <?php endif; ?>
<li class="breadcrumb-item"> <?php echo e(__('Setting')); ?></li>
<?php $__env->stopSection(); ?>
<style type="text/css">
    .row > * {
    flex-shrink: 0;
    /* width: 100%; */
    width: none !important;
    max-width: 100% !important;
    padding-right: calc(var(--bs-gutter-x) * .5);
    padding-left: calc(var(--bs-gutter-x) * .5);
    margin-top: var(--bs-gutter-y);
    /* width: auto; */
}
</style>
<?php $__env->startSection('content'); ?>
    <div class="row">
            <!-- [ sample-page ] start -->
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-xl-3">
                        <div class="card sticky-top" style="top:30px">
                            <div class="list-group list-group-flush" id="useradd-sidenav">
                                <a href="#site-settings" class="list-group-item list-group-item-action border-0 "><?php echo e(__('Site Setting')); ?> <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                <a href="#task-stages-settings" class="list-group-item list-group-item-action border-0 "><?php echo e(__('Task Stages')); ?> <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                <a href="#bug-stages-settings" class="list-group-item list-group-item-action border-0"><?php echo e(__('Bug Stages')); ?>  <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                <a href="#taxes-settings" class="list-group-item list-group-item-action border-0"><?php echo e(__('Taxes')); ?> <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                <a href="#billing-settings" class="list-group-item list-group-item-action border-0"><?php echo e(__('Billing')); ?> <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                <a href="#payment-settings" class="list-group-item list-group-item-action border-0"><?php echo e(__('Payment')); ?> <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                <a href="#invoices-settings" class="list-group-item list-group-item-action border-0"><?php echo e(__('Invoices')); ?> <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                <a  href="#email-notification" class="list-group-item list-group-item-action border-0"><?php echo e(__('Email Notification')); ?><div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                <a href="#zoom-settings" class="list-group-item list-group-item-action border-0"><?php echo e(__('Zoom Meeting')); ?> <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                 <?php if(Auth::user()->type == 'user'): ?>
                                <a href="#slack-setting" class="list-group-item list-group-item-action border-0"><?php echo e(__('Slack Setting ')); ?><div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                <a href="#telegram-setting" class="list-group-item list-group-item-action border-0"><?php echo e(__('Telegram Setting')); ?> <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                 <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9">

                         <div id="site-settings" class="">

                            <?php echo e(Form::open(array('route'=>['workspace.settings.store', $currentWorkspace->slug],'method'=>'post', 'enctype' => 'multipart/form-data'))); ?>

                            <div class="row">
                            <div class="col-12">
                                <div class="card ">
                                    <div class="card-header">
                                        <h5><?php echo e(__('Workspace Settings')); ?></h5>
                                    </div>
                                    <div class="card-body">
                                       <div class="col-12">
                                         <div class="row">
                                        <div class="col-6">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5><?php echo e(__('Dark Logo')); ?></h5>

                                                </div>
                                                <div class="card-body">
                                                        <div class="logo-content">
                                                            <img src="<?php if($currentWorkspace->logo): ?><?php echo e(asset(Storage::url('logo/'.$currentWorkspace->logo))); ?><?php else: ?><?php echo e(asset(Storage::url('logo/logo-light.png'))); ?><?php endif; ?>" class="small_logo" id="dark_logo"/>
                                                        </div>
                                                        <div class="choose-file mt-5 ">
                                                            <label for="logo">

                                                                <div class=" bg-primary"> <i class="ti ti-upload px-1"></i><?php echo e(__('Choose file here')); ?></div>
                                                                <input type="file" class="form-control" name="logo" id="logo" data-filename="edit-logo">
                                                            </label>
                                                            <p class="edit-logo"></p>
                                                        </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="card ">
                                                <div class="card-header">
                                                    <h5><?php echo e(__('Light Logo')); ?></h5>

                                                </div>
                                                <div class="card-body">
                                                        <div class="logo-content">
                                                            <img src="<?php if($currentWorkspace->logo_white): ?><?php echo e(asset(Storage::url('logo/'.$currentWorkspace->logo_white))); ?><?php else: ?><?php echo e(asset(Storage::url('logo/logo-dark.png'))); ?><?php endif; ?>" id="image"  class="small_logo"/>
                                                        </div>
                                                        <div class="choose-file mt-5 ">
                                                            <label for="logo_white">

                                                                <div class=" bg-primary"> <i class="ti ti-upload px-1"></i><?php echo e(__('Choose file here')); ?></div>
                                                                <input type="file" class="form-control" name="logo_white" id="logo_white" data-filename="edit-logo_white">
                                                            </label>
                                                            <p class="edit-logo_white"></p>
                                                        </div>
                                                </div>
                                            </div>
                                        </div>
                                         </div>
                                          </div>
                                            <div class="row mt-2">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                           <?php echo e(Form::label('name',__('Name'),array('class'=>'form-label'))); ?>

                                                          <?php echo e(Form::text('name',$currentWorkspace->name,array('class'=>'form-control', 'required' => 'required','placeholder'=>__('Enter Name')))); ?>

                                                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <span class="invalid-name" role="alert">
                                                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                                                </span>
                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <?php echo e(Form::label('interval_time',__('Tracking Interval'),array('class'=>'form-label'))); ?>


                                                        <?php echo e(Form::number('interval_time',$currentWorkspace->interval_time, ['class' => 'form-control', 'placeholder' => __('Enter Tracking Interval'),'required'=>'required'])); ?>

                                                        <small><?php echo e(__("Image Screenshort Take Interval time ( 1 = 1 min)")); ?></small>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label class="form-label"><?php echo e(__('App Site URL')); ?></label>
                                                         <?php echo e(Form::text('currency',URL::to('/'), ['class' => 'form-control', 'placeholder' => __('Enter Currency'),'disabled'=>'true'])); ?>

                                                          <small><?php echo e(__("App Site URL to login app.")); ?></small>
                                                    </div>
                                                </div>


                                        <h4 class="small-title mb-4">Theme Customizer</h4>
                                        <div class="col-12">
                                            <div class="pct-body">
                                                <div class="row">
                                                    <div class="col-4">
                                                        <h6 class="">
                                                        <i data-feather="credit-card" class="me-2"></i>Primary color settings
                                                        </h6>
                                                        <hr class="my-2" />
                                                        <div class="theme-color themes-color">
                                                        <input type="hidden" name="theme_color" id="" value="<?php echo e($color); ?>">
                                                        <a href="#!" class="<?php echo e(($color =='theme-1') ? 'active_color' : ''); ?>" data-value="theme-1" onclick="check_theme('theme-1')"></a>
                                                        <input type="radio" class="theme_color " name="theme_color" value="theme-1" style="display: none;">
                                                        <a href="#!" class="<?php echo e(($color =='theme-2') ? 'active_color' : ''); ?>" data-value="theme-2" onclick="check_theme('theme-2')"></a>
                                                            <input type="radio" class="theme_color" name="theme_color" value="theme-2" style="display: none;">
                                                        <a href="#!" class="<?php echo e(($color =='theme-3') ? 'active_color' : ''); ?>" data-value="theme-3" onclick="check_theme('theme-3')"></a>
                                                            <input type="radio" class="theme_color" name="theme_color" value="theme-3" style="display: none;">
                                                        <a href="#!" class="<?php echo e(($color =='theme-4') ? 'active_color' : ''); ?>" data-value="theme-4" onclick="check_theme('theme-4')"></a>
                                                        <input type="radio" class="theme_color" name="theme_color" value="theme-4" style="display: none;">
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <h6 class="">
                                                            <i data-feather="layout" class="me-2"></i>Sidebar settings
                                                        </h6>
                                                        <hr class="my-2" />
                                                        <div class="form-check form-switch">
                                                             <input type="checkbox" class="form-check-input" id="cust-theme-bg" name="cust_theme_bg"  <?php if($cust_theme_bg == 'on'): ?> checked <?php endif; ?>/>
                                                               <label class="form-check-label f-w-600 pl-1" for="cust-theme-bg">Transparent layout</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <h6 class="">
                                                        <i data-feather="sun" class=""></i>Layout settings
                                                        </h6>
                                                        <hr class="my-2" />
                                                        <div class="form-check form-switch mt-2">
                                                            <input type="checkbox" class="form-check-input" id="cust-darklayout" name="cust_darklayout" <?php if($dark_mode == 'on'): ?> checked <?php endif; ?>/>

                                                        <label class="form-check-label f-w-600 pl-1" for="cust-darklayout" >Dark Layout</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-2">
                                                     <div class="col switch-width">
                                                         <div class="form-group ml-2 mr-3 ">
                                                             <label class="form-label mb-1"><?php echo e(__('RTL')); ?></label>
                                                             <div class="custom-control custom-switch">
                                                                  <input type="checkbox" data-toggle="switchbutton" data-onstyle="primary" class="" name="site_rtl" id="site_rtl" <?php echo e(! empty($SITE_RTL) && $SITE_RTL == 'on' ? 'checked="checked"' : ''); ?>>
                                                                 <label class="custom-control-label" for="site_rtl"></label>
                                                             </div>
                                                         </div>
                                                      </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-end mt-2">
                                            <input type="submit" value="<?php echo e(__('Save Changes')); ?>" class="btn btn-primary">
                                        </div>
                                            </div>

                                    </div>
                                </div>



























                            </div>
                            </div>
                                <?php echo e(Form::close()); ?>

                        </div>

                    <div id="task-stages-settings" class="">
                        <div class="">
                            <div class="col-md-12">
                               <div class="card task-stages" data-value="<?php echo e(json_encode($stages)); ?>">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-11">
                                        <h5 class="">
                                            <?php echo e(__('Task Stages')); ?>


                                        </h5>
                                         <small class=""><?php echo e(__('System will consider last stage as a completed / done task for get progress on project.')); ?></small></div>
                                         <div class="col-auto  text-end">

                                         <button data-repeater-create type="button" class="btn-submit btn btn-sm btn-primary btn-icon " data-toggle="tooltip" title="<?php echo e(__('Add')); ?>">
                                            <i class="ti ti-plus"></i>
                                        </button>

                                </div>
                                    </div>
                                    </div>

                                    <div class="card-body">
                                        <form method="post" action="<?php echo e(route('stages.store',$currentWorkspace->slug)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <table class="table table-hover" data-repeater-list="stages">
                                                <thead>
                                                <th>
                                                    <div data-toggle="tooltip" data-placement="left" data-title="<?php echo e(__('Drag Stage to Change Order')); ?>" data-original-title="" title="">
                                                        <i class="fas fa-crosshairs"></i>
                                                    </div>
                                                </th>
                                                <th><?php echo e(__('Color')); ?></th>
                                                <th><?php echo e(__('Name')); ?></th>
                                                <th class="text-right"><?php echo e(__('Delete')); ?></th>
                                                </thead>
                                                <tbody>
                                                <tr data-repeater-item>
                                                    <td><i class="fas fa-crosshairs sort-handler"></i></td>
                                                    <td>
                                                        <input type="color" name="color">
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="id" id="id"/>
                                                        <input type="text" name="name" class="form-control mb-0" required/>
                                                    </td>
                                                    <td class="text-right ">
                                                        <a data-repeater-delete class=" action-btn btn-danger  btn btn-sm d-inline-flex align-items-center" data-toggle="tooltip" title="<?php echo e(__('Delete')); ?>"><i class="ti ti-trash text-white"></i></a>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <div class="text-end pt-2">
                                                <button class="btn-submit btn btn-primary" type="submit"><?php echo e(__('Save Changes')); ?></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                            <div id="bug-stages-settings" class="tab-pane">
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <div class="card bug-stages" data-value="<?php echo e(json_encode($bugStages)); ?>">
                                    <div class="card-header">
                                         <div class="row">
                                            <div class="col-11">
                                        <h5 class="">
                                            <?php echo e(__('Bug Stages')); ?>


                                        </h5>
                                        <small class=""><?php echo e(__('System will consider last stage as a completed / done task for get progress on project.')); ?></small>
                                    </div>
                                        <div class=" col-auto text-end">
                                        <button data-repeater-create type="button" class="btn-submit btn btn-sm btn-primary " data-toggle="tooltip" title="<?php echo e(__('Add')); ?>">
                                            <i class="ti ti-plus"></i>
                                        </button>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="card-body">
                                        <form method="post" action="<?php echo e(route('bug.stages.store',$currentWorkspace->slug)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <table class="table table-hover" data-repeater-list="stages">
                                                <thead>
                                                <th>
                                                    <div data-toggle="tooltip" data-placement="left" data-title="<?php echo e(__('Drag Stage to Change Order')); ?>" data-original-title="" title="">
                                                        <i class="fas fa-crosshairs"></i>
                                                    </div>
                                                </th>
                                                <th><?php echo e(__('Color')); ?></th>
                                                <th><?php echo e(__('Name')); ?></th>
                                                <th class="text-right"><?php echo e(__('Delete')); ?></th>
                                                </thead>
                                                <tbody>
                                                <tr data-repeater-item>
                                                    <td><i class="fas fa-crosshairs sort-handler"></i></td>
                                                    <td>
                                                        <input type="color" name="color">
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="id" id="id"/>
                                                        <input type="text" name="name" class="form-control mb-0" required/>
                                                    </td>
                                                    <td class="text-right">
                                                        <a data-repeater-delete class="action-btn btn-danger  btn btn-sm d-inline-flex align-items-center" data-toggle="tooltip" title="<?php echo e(__('Delete')); ?>"><i class="ti ti-trash text-white"></i></a>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <div class="text-end pt-2">
                                                <button class="btn-submit btn btn-primary" type="submit"><?php echo e(__('Save Changes')); ?></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="taxes-settings" class="">
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                          <div class="row">
                                            <div class="col-11">
                                        <h5 class="">
                                            <?php echo e(__('Taxes')); ?>

                                        </h5>
                                    </div>
                                        <div class="text-end  col-auto">
                                        <button class="btn-submit btn btn-sm btn-primary" type="button" data-ajax-popup="true" data-title="<?php echo e(__('Add Tax')); ?>" data-url="<?php echo e(route('tax.create',$currentWorkspace->slug)); ?>" data-toggle="tooltip" title="<?php echo e(__('Add Tax')); ?>">
                                            <i class="ti ti-plus"></i>
                                        </button>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">

                                            <table id="" class="table table-bordered px-2">
                                                <thead>
                                                <tr>
                                                    <th><?php echo e(__('Name')); ?></th>
                                                    <th><?php echo e(__('Rate')); ?></th>
                                                    <th width="200px" class="text-right"><?php echo e(__('Action')); ?></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php $__currentLoopData = $taxes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tax): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td><?php echo e($tax->name); ?></td>
                                                        <td><?php echo e($tax->rate); ?>%</td>
                                                        <td class="text-right">
                                                            <a href="#" class="action-btn btn-info  btn btn-sm d-inline-flex align-items-center" data-ajax-popup="true" data-title="<?php echo e(__('Edit Tax')); ?>" data-url="<?php echo e(route('tax.edit',[$currentWorkspace->slug,$tax->id])); ?>" data-toggle="tooltip" title="<?php echo e(__('Edit Tax')); ?>">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                            <a href="#" class="action-btn btn-danger  btn btn-sm d-inline-flex align-items-center bs-pass-para" data-confirm="<?php echo e(__('Are You Sure?')); ?>" data-text="<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>"  data-confirm-yes="delete-form-<?php echo e($tax->id); ?>"data-toggle="tooltip" title="<?php echo e(__('Delete')); ?>">
                                                                <i class="ti ti-trash text-white"></i>
                                                            </a>
                                                            <form id="delete-form-<?php echo e($tax->id); ?>" action="<?php echo e(route('tax.destroy',[$currentWorkspace->slug,$tax->id])); ?>" method="POST" style="display: none;">
                                                                <?php echo csrf_field(); ?>
                                                                <?php echo method_field('DELETE'); ?>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                     <div id="billing-settings" class="tab-pane">
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="">
                                            <?php echo e(__('Billing Details')); ?>

                                        </h5>
                                    </div>
                                    <div class="card-body p-4">
                                        <form method="post" action="<?php echo e(route('workspace.settings.store',$currentWorkspace->slug)); ?>" class="payment-form">
                                            <?php echo csrf_field(); ?>
                                            <div class="row mt-3">
                                                <div class="form-group col-md-6">
                                                    <label for="company" class="form-label"><?php echo e(__('Name')); ?></label>
                                                    <input type="text" name="company" id="company" class="form-control" value="<?php echo e($currentWorkspace->company); ?>" required="required"/>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="address" class="form-label"><?php echo e(__('Address')); ?></label>
                                                    <input type="text" name="address" id="address" class="form-control" value="<?php echo e($currentWorkspace->address); ?>" required="required"/>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="city" class="form-label"><?php echo e(__('City')); ?></label>
                                                    <input class="form-control" name="city" type="text" value="<?php echo e($currentWorkspace->city); ?>" id="city">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="state" class="form-label"><?php echo e(__('State')); ?></label>
                                                    <input class="form-control" name="state" type="text" value="<?php echo e($currentWorkspace->state); ?>" id="state">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="zipcode" class="form-label"><?php echo e(__('Zip/Post Code')); ?></label>
                                                    <input class="form-control" name="zipcode" type="text" value="<?php echo e($currentWorkspace->zipcode); ?>" id="zipcode">
                                                </div>
                                                <div class="form-group  col-md-6">
                                                    <label for="country" class="form-label"><?php echo e(__('Country')); ?></label>
                                                    <input class="form-control" name="country" type="text" value="<?php echo e($currentWorkspace->country); ?>" id="country">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="telephone" class="form-label"><?php echo e(__('Telephone')); ?></label>
                                                    <input class="form-control" name="telephone" type="text" value="<?php echo e($currentWorkspace->telephone); ?>" id="telephone">
                                                </div>
                                            </div>
                                             <div class="text-end">
                                            <button type="submit" class="btn-submit btn btn-primary"><?php echo e(__('Save Changes')); ?></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div id="payment-settings" class="faq">
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="">
                                            <?php echo e(__('Payment Details')); ?>

                                        </h5>
                                         <small class="d-block mt-2"><?php echo e(__("This detail will use for collect payment on invoice from workspace's client. On invoice client will find out pay now button based on your below configuration.")); ?></small>
                                    </div>
                                    <div class="card-body p-4">
                                        <form method="post" action="<?php echo e(route('workspace.settings.store',$currentWorkspace->slug)); ?>" class="payment-form">
                                            <?php echo csrf_field(); ?>
                                            <div class="row mt-3">
                                                <div class="form-group col-md-6">
                                                    <label for="currency_code" class="form-label"><?php echo e(__('Currency Code')); ?></label>
                                                    <input type="text" name="currency_code" id="currency_code" class="form-control" value="<?php echo e($currentWorkspace->currency_code); ?>" required="required"/>
                                                    <small> <?php echo e(__('Note: Add currency code as per three-letter ISO code.')); ?> <a href="https://stripe.com/docs/currencies" target="_new"><?php echo e(__('you can find out here.')); ?></a>.</small>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="currency" class="form-label"><?php echo e(__('Currency')); ?></label>
                                                    <input type="text" name="currency" id="currency" class="form-control" value="<?php echo e($currentWorkspace->currency); ?>" required="required"/>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="accordion accordion-flush" id="payment-gateways">
                                                      <div id="" class="accordion-item card">
                                                        <!-- Stripe -->

                                                        <h2 class="accordion-header" id="stripe">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapseone"
                                                                aria-expanded="false" aria-controls="collapseone">
                                                                <span class="d-flex align-items-center">
                                                                    <i class="ti ti-credit-card"></i> <?php echo e(__('Stripe')); ?>

                                                                </span>
                                                            </button>
                                                        </h2>
                                                <div id="collapseone" class="accordion-collapse collapse"
                                                    aria-labelledby="stripe" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="col-12 d-flex justify-content-between">

                                                            <small class="">
                                                                <?php echo e(__('Note: This detail will use for make checkout of plan.')); ?></small>

                                                                   <div class="form-check form-switch d-inline-block">
                                                                                <input type="checkbox" class="form-check-input" name="is_stripe_enabled" id="is_stripe_enabled" <?php echo e((isset($currentWorkspace->is_stripe_enabled) && $currentWorkspace->is_stripe_enabled == '1') ? 'checked' : ''); ?>>
                                                                                <label class="custom-control-label form-control-label" for="is_stripe_enabled"><?php echo e(__('Enable Stripe')); ?></label>
                                                                            </div>



                                                        </div>
                                                        <div class="row mt-2">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <?php echo e(Form::label('stripe_key', __('Stripe Key'),['class' => 'form-label'])); ?>

                                                                     <?php echo e(Form::text('stripe_key', (isset($currentWorkspace->stripe_key) && !empty($currentWorkspace->stripe_key)) ? $currentWorkspace->stripe_key :'', ['class' => 'form-control','placeholder' => __('Stripe Key')])); ?>


                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                     <?php echo e(Form::label('stripe_secret', __('Stripe Secret'),['class' => 'form-label'])); ?>

                                                                     <?php echo e(Form::text('stripe_secret', (isset($currentWorkspace->stripe_secret) && !empty($currentWorkspace->stripe_secret)) ? $currentWorkspace->stripe_secret:'', ['class' => 'form-control','placeholder' => __('Stripe Secret')])); ?>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                             <div id="" class="accordion-item card">
                                                        <!-- paypal -->

                                                        <h2 class="accordion-header" id="paypal">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapsetwo"
                                                                aria-expanded="false" aria-controls="collapsetwo">
                                                                <span class="d-flex align-items-center">
                                                                    <i class="ti ti-credit-card"></i> <?php echo e(__('Paypal')); ?>

                                                                </span>
                                                            </button>
                                                        </h2>
                                                  <div id="collapsetwo" class="accordion-collapse collapse"
                                                    aria-labelledby="paypal" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="col-12 d-flex justify-content-between">

                                                            <small class="">
                                                                <?php echo e(__('Note: This detail will use for make checkout of plan.')); ?></small>

                                                                   <div class="form-check form-switch d-inline-block">
                                                                                <input type="checkbox" class="form-check-input" name="is_paypal_enabled" id="is_paypal_enabled" <?php echo e((isset($currentWorkspace->is_paypal_enabled) && $currentWorkspace->is_paypal_enabled == '1') ? 'checked' : ''); ?>><label class="custom-control-label form-control-label" for="is_paypal_enabled"><?php echo e(__('Enable Paypal')); ?></label>
                                                                            </div>
                                                        </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pb-4">
                                                        <div class="row pt-2">
                                                             <label class="pb-2" for="paypal_mode"><?php echo e(__('Paypal Mode')); ?></label>
                                                            <div class="col-lg-3">
                                                                <div class="border card p-3">
                                                                    <div class="form-check">
                                                                        <input type="radio" name="paypal_mode" class="form-check-input input-primary " value="sandbox" <?php echo e((!isset($currentWorkspace-> paypal_mode) || empty($currentWorkspace->paypal_mode) || $currentWorkspace->paypal_mode == 'sandbox') ? 'checked' : ''); ?>

                                                                            id="">
                                                                        <label class="form-check-label d-block" for="">
                                                                            <span>
                                                                                <span class="h5 d-block"><strong
                                                                                        class="float-end"></strong><?php echo e(__('Sandbox')); ?></span>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <div class="border card p-3">
                                                                    <div class="form-check">
                                                                        <input type="radio" name="paypal_mode" class="form-check-input input-primary "  value="live" <?php echo e((isset($currentWorkspace->paypal_mode) && $currentWorkspace->paypal_mode == 'live') ? 'checked' : ''); ?>>
                                                                        <label class="form-check-label d-block" for="">
                                                                            <span>
                                                                                <span class="h5 d-block"><strong
                                                                                class="float-end"></strong><?php echo e(__('Live')); ?></span>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                        <div class="row mt-2">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <?php echo e(Form::label('paypal_client_id', __('Client ID'),['class' => 'form-label'])); ?>

                                                                                <?php echo e(Form::text('paypal_client_id', (isset($currentWorkspace->paypal_client_id)) ? $currentWorkspace->paypal_client_id : '', ['class' => 'form-control','placeholder' => __('Client ID')])); ?>


                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <?php echo e(Form::label('paypal_secret_key', __('Secret Key'),['class' => 'form-label'])); ?>

                                                                                <?php echo e(Form::text('paypal_secret_key', isset($currentWorkspace->paypal_secret_key) ? $currentWorkspace->paypal_secret_key : '', ['class' => 'form-control','placeholder' => __('Secret Key')])); ?>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



                                                <div id="" class="accordion-item card">
                                                        <!-- paystack -->

                                                        <h2 class="accordion-header" id="paystack">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapsethree"
                                                                aria-expanded="false" aria-controls="collapsethree">
                                                                <span class="d-flex align-items-center">
                                                                    <i class="ti ti-credit-card"></i> <?php echo e(__('Paystack')); ?>

                                                                </span>
                                                            </button>
                                                        </h2>
                                                  <div id="collapsethree" class="accordion-collapse collapse"
                                                    aria-labelledby="paystack" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="col-12 d-flex justify-content-between">

                                                            <small class="">
                                                                <?php echo e(__('Note: This detail will use for make checkout of plan.')); ?></small>

                                                                   <div class="form-check form-switch d-inline-block">
                                                                                <input type="checkbox" class="form-check-input" name="is_paystack_enabled" id="is_paystack_enabled" <?php echo e(isset($payment_detail['is_paystack_enabled']) && $payment_detail['is_paystack_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>  <label class="custom-control-label form-control-label" for="is_paystack_enabled"><?php echo e(__('Enable Paystack')); ?></label>
                                                                            </div>
                                                        </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pb-4">

                                                    </div>
                                                        <div class="row mt-2">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                     <label class="form-label" for="paystack_public_key"><?php echo e(__('Public Key')); ?></label>
                                                                     <input type="text" name="paystack_public_key" id="paystack_public_key" class="form-control" value="<?php echo e(isset($payment_detail['paystack_public_key']) ? $payment_detail['paystack_public_key']:''); ?>" placeholder="<?php echo e(__('Public Key')); ?>"/>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                   <label class="form-label" for="paystack_secret_key"><?php echo e(__('Secret Key')); ?></label>
                                                                    <input type="text" name="paystack_secret_key" id="paystack_secret_key" class="form-control" value="<?php echo e(isset($payment_detail['paystack_secret_key']) ? $payment_detail['paystack_secret_key']:''); ?>" placeholder="<?php echo e(__('Secret Key')); ?>"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div id="" class="accordion-item card">
                                                        <!-- Flutterwave -->

                                                        <h2 class="accordion-header" id="Flutterwave">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapsefor"
                                                                aria-expanded="false" aria-controls="collapsefor">
                                                                <span class="d-flex align-items-center">
                                                                    <i class="ti ti-credit-card"></i> <?php echo e(__('Flutterwave')); ?>

                                                                </span>
                                                            </button>
                                                        </h2>
                                                  <div id="collapsefor" class="accordion-collapse collapse"
                                                    aria-labelledby="Flutterwave" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="col-12 d-flex justify-content-between">

                                                            <small class="">
                                                                <?php echo e(__('Note: This detail will use for make checkout of plan.')); ?></small>

                                                                   <div class="form-check form-switch d-inline-block">
                                                                    <input type="checkbox" class="form-check-input"  name="is_flutterwave_enabled" id="is_flutterwave_enabled" <?php echo e(isset($payment_detail['is_flutterwave_enabled'])  && $payment_detail['is_flutterwave_enabled']== 'on' ? 'checked="checked"' : ''); ?>><label class="custom-control-label form-control-label" for="is_flutterwave_enabled"><?php echo e(__('Enable Flutterwave')); ?></label>
                                                                </div>
                                                        </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pb-4">

                                                    </div>
                                                        <div class="row mt-2">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                      <label class="form-label" for="flutterwave_public_key"><?php echo e(__('Public Key')); ?></label>
                                                                    <input type="text" name="flutterwave_public_key" id="flutterwave_public_key" class="form-control" value="<?php echo e(isset($payment_detail['flutterwave_public_key'])?$payment_detail['flutterwave_public_key']:''); ?>" placeholder="<?php echo e(__('Public Key')); ?>"/>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                 <label class="form-label" for="paystack_secret_key"><?php echo e(__('Secret Key')); ?></label>
                                                                 <input type="text" name="flutterwave_secret_key" id="flutterwave_secret_key" class="form-control" value="<?php echo e(isset($payment_detail['flutterwave_secret_key'])?$payment_detail['flutterwave_secret_key']:''); ?>" placeholder="<?php echo e(__('Secret Key')); ?>"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div id="" class="accordion-item card">
                                                        <!-- Razorpay -->

                                                        <h2 class="accordion-header" id="Razorpay">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapsefive"
                                                                aria-expanded="false" aria-controls="collapsefive">
                                                                <span class="d-flex align-items-center">
                                                                    <i class="ti ti-credit-card"></i> <?php echo e(__('Razorpay')); ?>

                                                                </span>
                                                            </button>
                                                        </h2>
                                                  <div id="collapsefive" class="accordion-collapse collapse"
                                                    aria-labelledby="Razorpay" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="col-12 d-flex justify-content-between">

                                                            <small class="">
                                                                <?php echo e(__('Note: This detail will use for make checkout of plan.')); ?></small>

                                                                   <div class="form-check form-switch d-inline-block">
                                                                                <input type="checkbox" class="form-check-input" name="is_razorpay_enabled" id="is_razorpay_enabled" <?php echo e(isset($payment_detail['is_razorpay_enabled']) && $payment_detail['is_razorpay_enabled'] == 'on' ? 'checked="checked"' : ''); ?>><label class="custom-control-label form-control-label" for="is_razorpay_enabled"><?php echo e(__('Enable Razorpay')); ?></label>
                                                                            </div>
                                                        </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pb-4">

                                                    </div>
                                                        <div class="row mt-2">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                     <label class="form-label" for="razorpay_public_key"><?php echo e(__('Public Key')); ?></label>
                                                                    <input type="text" name="razorpay_public_key" id="razorpay_public_key" class="form-control" value="<?php echo e(isset($payment_detail['razorpay_public_key'])?$payment_detail['razorpay_public_key']:''); ?>" placeholder="<?php echo e(__('Public Key')); ?>"/>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                     <label class="form-label" for="paystack_secret_key"><?php echo e(__('Secret Key')); ?></label>
                                                                    <input type="text" name="razorpay_secret_key" id="razorpay_secret_key" class="form-control" value="<?php echo e(isset($payment_detail['razorpay_secret_key'])?$payment_detail['razorpay_secret_key']:''); ?>" placeholder="<?php echo e(__('Secret Key')); ?>"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                                 <div id="" class="accordion-item card">
                                                        <!-- paypal -->

                                                        <h2 class="accordion-header" id="mercado">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapsetsix"
                                                                aria-expanded="false" aria-controls="collapsetsix">
                                                                <span class="d-flex align-items-center">
                                                                    <i class="ti ti-credit-card"></i> <?php echo e(__('Mercado Pago')); ?>

                                                                </span>
                                                            </button>
                                                        </h2>
                                                  <div id="collapsetsix" class="accordion-collapse collapse"
                                                    aria-labelledby="mercado" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="col-12 d-flex justify-content-between">

                                                            <small class="">
                                                                <?php echo e(__('Note: This detail will use for make checkout of plan.')); ?></small>

                                                                   <div class="form-check form-switch d-inline-block">
                                                                                <input type="checkbox" class="form-check-input" name="is_mercado_enabled" id="is_mercado_enabled" <?php echo e(isset($payment_detail['is_mercado_enabled']) &&  $payment_detail['is_mercado_enabled'] == 'on' ? 'checked="checked"' : ''); ?>> <label class="custom-control-label form-control-label" for="is_mercado_enabled"><?php echo e(__('Enable Mercado Pago')); ?></label>
                                                                            </div>
                                                        </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pb-4">
                                                        <div class="row pt-2">
                                                             <label class="pb-2" for="paypal_mode"><?php echo e(__('Mercado Mode')); ?></label>
                                                            <div class="col-lg-3">
                                                                <div class="border card p-3">
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input input-primary "name="mercado_mode" value="sandbox" <?php echo e(isset($payment_detail['mercado_mode']) && $payment_detail['mercado_mode'] == '' || isset($payment_detail['mercado_mode']) && $payment_detail['mercado_mode'] == 'sandbox' ? 'checked' : ''); ?>>


                                                                        <label class="form-check-label d-block" for="">
                                                                            <span>
                                                                                <span class="h5 d-block"><strong
                                                                                        class="float-end"></strong><?php echo e(__('Sandbox')); ?></span>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <div class="border card p-3">
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input input-primary "name="mercado_mode" value="live" <?php echo e(isset($payment_detail['mercado_mode']) && $payment_detail['mercado_mode'] == 'live' ? 'checked="checked"' : ''); ?>>
                                                                        <label class="form-check-label d-block" for="">
                                                                            <span>
                                                                                <span class="h5 d-block"><strong
                                                                                class="float-end"></strong><?php echo e(__('Live')); ?></span>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                        <div class="row mt-2">
                                                            <div class="col-md-12">
                                                                 <label for="mercado_access_token" class="form-label"><?php echo e(__('Access Token')); ?></label>
                                                                    <input type="text" name="mercado_access_token" id="mercado_access_token" class="form-control" value="<?php echo e(isset($payment_detail['mercado_access_token']) ? $payment_detail['mercado_access_token']:''); ?>" placeholder="<?php echo e(__('Access Token')); ?>"/>
                                                                    <?php if($errors->has('mercado_secret_key')): ?>
                                                                        <span class="invalid-feedback d-block">
                                                                            <?php echo e($errors->first('mercado_access_token')); ?>

                                                                        </span>
                                                                    <?php endif; ?>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                              <div id="" class="accordion-item card">
                                                        <!-- Paytm -->

                                                        <h2 class="accordion-header" id="Paytm">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapset7"
                                                                aria-expanded="false" aria-controls="collapset7">
                                                                <span class="d-flex align-items-center">
                                                                    <i class="ti ti-credit-card"></i> <?php echo e(__('Paytm')); ?>

                                                                </span>
                                                            </button>
                                                        </h2>
                                                  <div id="collapset7" class="accordion-collapse collapse"
                                                    aria-labelledby="Paytm" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="col-12 d-flex justify-content-between">

                                                            <small class="">
                                                                <?php echo e(__('Note: This detail will use for make checkout of plan.')); ?></small>

                                                                   <div class="form-check form-switch d-inline-block">
                                                                                <input type="checkbox" class="form-check-input" name="is_paytm_enabled" id="is_paytm_enabled" <?php echo e(isset($payment_detail['is_paytm_enabled']) && $payment_detail['is_paytm_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                                  <label class="custom-control-label form-control-label" for="is_paytm_enabled"><?php echo e(__('Enable Paytm')); ?></label>
                                                                            </div>
                                                        </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pb-4">
                                                        <div class="row pt-2">
                                                             <label class="pb-2" for="paypal_mode"><?php echo e(__('Paytm Environment')); ?></label>
                                                            <div class="col-lg-3">
                                                                <div class="border card p-3">
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input input-primary "name="paytm_mode" value="local" <?php echo e(isset($payment_detail['paytm_mode']) && $payment_detail['paytm_mode'] == '' || isset($payment_detail['paytm_mode']) && $payment_detail['paytm_mode'] == 'local' ? 'checked="checked"' : ''); ?>>


                                                                        <label class="form-check-label d-block" for="">
                                                                            <span>
                                                                                <span class="h5 d-block"><strong
                                                                                        class="float-end"></strong><?php echo e(__('Local')); ?></span>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <div class="border card p-3">
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input input-primary"name="paytm_mode" value="production" <?php echo e(isset($payment_detail['paytm_mode']) && $payment_detail['paytm_mode'] == 'production' ? 'checked="checked"' : ''); ?>>
                                                                        <label class="form-check-label d-block" for="">
                                                                            <span>
                                                                                <span class="h5 d-block"><strong
                                                                                class="float-end"></strong><?php echo e(__('Production')); ?></span>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                        <div class="row mt-2">
                                                            <div class="col-md-4">
                                                                  <div class="form-group">
                                                                    <label class="form-label" for="paytm_public_key"><?php echo e(__('Merchant ID')); ?></label>
                                                                    <input type="text" name="paytm_merchant_id" id="paytm_merchant_id" class="form-control" value="<?php echo e(isset($payment_detail['paytm_merchant_id'])? $payment_detail['paytm_merchant_id']:''); ?>" placeholder="<?php echo e(__('Merchant ID')); ?>"/>
                                                                </div>
                                                            </div>
                                                           <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="paytm_secret_key"><?php echo e(__('Merchant Key')); ?></label>
                                                                        <input type="text" name="paytm_merchant_key" id="paytm_merchant_key" class="form-control" value="<?php echo e(isset($payment_detail['paytm_merchant_key']) ? $payment_detail['paytm_merchant_key']:''); ?>" placeholder="<?php echo e(__('Merchant Key')); ?>"/>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="paytm_industry_type"><?php echo e(__('Industry Type')); ?></label>
                                                                        <input type="text" name="paytm_industry_type" id="paytm_industry_type" class="form-control" value="<?php echo e(isset($payment_detail['paytm_industry_type']) ?$payment_detail['paytm_industry_type']:''); ?>" placeholder="<?php echo e(__('Industry Type')); ?>"/>
                                                                    </div>
                                                                </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="" class="accordion-item card">
                                                        <!-- Mollie -->

                                                        <h2 class="accordion-header" id="Mollie">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapset8"
                                                                aria-expanded="false" aria-controls="collapset8">
                                                                <span class="d-flex align-items-center">
                                                                    <i class="ti ti-credit-card"></i> <?php echo e(__('Mollie')); ?>

                                                                </span>
                                                            </button>
                                                        </h2>
                                                  <div id="collapset8" class="accordion-collapse collapse"
                                                    aria-labelledby="Mollie" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="col-12 d-flex justify-content-between">

                                                            <small class="">
                                                                <?php echo e(__('Note: This detail will use for make checkout of plan.')); ?></small>

                                                                   <div class="form-check form-switch d-inline-block">
                                                                                <input type="checkbox" class="form-check-input"name="is_mollie_enabled" id="is_mollie_enabled" <?php echo e(isset($payment_detail['is_mollie_enabled']) && $payment_detail['is_mollie_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                                 <label class="custom-control-label form-control-label" for="is_mollie_enabled"><?php echo e(__('Enable Mollie')); ?></label>
                                                                            </div>
                                                        </div>

                                                        <div class="row mt-2">
                                                               <div class="col-md-4 col-lg-4">
                                                                            <div class="form-group">
                                                                                <label class="form-label" for="mollie_api_key"><?php echo e(__('Mollie Api Key')); ?></label>
                                                                                <input type="text" name="mollie_api_key" id="mollie_api_key" class="form-control" value="<?php echo e(isset($payment_detail['mollie_api_key'])?$payment_detail['mollie_api_key']:''); ?>" placeholder="<?php echo e(__('Mollie Api Key')); ?>"/>
                                                                            </div>
                                                                        </div>
                                                                        <div class=" col-md-4 col-lg-4">
                                                                            <div class="form-group">
                                                                                <label class="form-label" for="mollie_profile_id"><?php echo e(__('Mollie Profile Id')); ?></label>
                                                                                <input type="text" name="mollie_profile_id" id="mollie_profile_id" class="form-control" value="<?php echo e(isset($payment_detail['mollie_profile_id'])?$payment_detail['mollie_profile_id']:''); ?>" placeholder="<?php echo e(__('Mollie Profile Id')); ?>"/>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4 col-lg-4">
                                                                            <div class="form-group">
                                                                                <label class="form-label" for="mollie_partner_id"><?php echo e(__('Mollie Partner Id')); ?></label>
                                                                                <input type="text" name="mollie_partner_id" id="mollie_partner_id" class="form-control" value="<?php echo e(isset($payment_detail['mollie_partner_id'])?$payment_detail['mollie_partner_id']:''); ?>" placeholder="<?php echo e(__('Mollie Partner Id')); ?>"/>
                                                                            </div>
                                                                        </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



                                               <div id="" class="accordion-item card">
                                                        <!-- Skrill -->

                                                        <h2 class="accordion-header" id="Skrill">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapset9"
                                                                aria-expanded="false" aria-controls="collapset9">
                                                                <span class="d-flex align-items-center">
                                                                    <i class="ti ti-credit-card"></i> <?php echo e(__('Skrill')); ?>

                                                                </span>
                                                            </button>
                                                        </h2>
                                                  <div id="collapset9" class="accordion-collapse collapse"
                                                    aria-labelledby="Skrill" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="col-12 d-flex justify-content-between">

                                                            <small class="">
                                                                <?php echo e(__('Note: This detail will use for make checkout of plan.')); ?></small>

                                                                   <div class="form-check form-switch d-inline-block">
                                                                                <input type="checkbox" class="form-check-input"name="is_skrill_enabled" id="is_skrill_enabled" <?php echo e(isset($payment_detail['is_skrill_enabled']) && $payment_detail['is_skrill_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                                 <label class="custom-control-label form-control-label" for="is_skrill_enabled"><?php echo e(__('Enable Skrill')); ?></label>
                                                                            </div>
                                                              </div>

                                                                 <div class="row mt-2">
                                                                                  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                            <div class="form-group">
                                                                                <label class="form-label" for="mollie_api_key"><?php echo e(__('Skrill Email')); ?></label>
                                                                                <input type="email" name="skrill_email" id="skrill_email" class="form-control" value="<?php echo e(isset($payment_detail['skrill_email'])?$payment_detail['skrill_email']:''); ?>" placeholder="<?php echo e(__('Skrill Email')); ?>"/>
                                                                            </div>
                                                                        </div>
                                                                </div>
                                                           </div>
                                                      </div>
                                                  </div>

                                                    <div id="" class="accordion-item card">
                                                        <!-- paypal -->

                                                        <h2 class="accordion-header" id="CoinGate">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapset10"
                                                                aria-expanded="false" aria-controls="collapset10">
                                                                <span class="d-flex align-items-center">
                                                                    <i class="ti ti-credit-card"></i> <?php echo e(__('CoinGate')); ?>

                                                                </span>
                                                            </button>
                                                        </h2>
                                                  <div id="collapset10" class="accordion-collapse collapse"
                                                    aria-labelledby="CoinGate" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="col-12 d-flex justify-content-between">

                                                            <small class="">
                                                                <?php echo e(__('Note: This detail will use for make checkout of plan.')); ?></small>

                                                                   <div class="form-check form-switch d-inline-block">
                                                                                <input type="checkbox" class="form-check-input" name="is_coingate_enabled" id="is_coingate_enabled" <?php echo e(isset($payment_detail['is_coingate_enabled']) && $payment_detail['is_coingate_enabled'] == 'on' ? 'checked="checked"' : ''); ?>> <label class="custom-control-label form-control-label" for="is_mercado_enabled"><?php echo e(__('Enable CoinGate')); ?></label>
                                                                            </div>
                                                        </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pb-4">
                                                        <div class="row pt-2">
                                                             <label class="pb-2" for="paypal_mode"><?php echo e(__('CoinGate Mode')); ?></label>
                                                            <div class="col-lg-3">
                                                                <div class="border card p-3">
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input input-primary "name="coingate_mode" value="sandbox" <?php echo e(isset($payment_detail['coingate_mode']) && $payment_detail['coingate_mode'] == '' || isset($payment_detail['coingate_mode']) && $payment_detail['coingate_mode'] == 'sandbox' ? 'checked="checked"' : ''); ?>>


                                                                        <label class="form-check-label d-block" for="">
                                                                            <span>
                                                                                <span class="h5 d-block"><strong
                                                                                        class="float-end"></strong><?php echo e(__('Sandbox')); ?></span>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <div class="border card p-3">
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input input-primary name="coingate_mode" value="live" <?php echo e(isset($payment_detail['coingate_mode']) && $payment_detail['coingate_mode'] == 'live' ? 'checked="checked"' : ''); ?>>
                                                                        <label class="form-check-label d-block" for="">
                                                                            <span>
                                                                                <span class="h5 d-block"><strong
                                                                                class="float-end"></strong><?php echo e(__('Live')); ?></span>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                        <div class="row mt-2">
                                                               <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                            <div class="form-group">
                                                                                <label class="form-label" for="coingate_auth_token"><?php echo e(__('CoinGate Auth Token')); ?></label>
                                                                                <input type="text" name="coingate_auth_token" id="coingate_auth_token" class="form-control" value="<?php echo e(isset($payment_detail['coingate_auth_token'])?$payment_detail['coingate_auth_token']:''); ?>" placeholder="<?php echo e(__('CoinGate Auth Token')); ?>"/>
                                                                            </div>
                                                                        </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                                  <div id="" class="accordion-item card">
                                                        <!-- Paymentwall -->

                                                        <h2 class="accordion-header" id="Paymentwall">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapse11"
                                                                aria-expanded="false" aria-controls="collapse11">
                                                                <span class="d-flex align-items-center">
                                                                    <i class="ti ti-credit-card"></i> <?php echo e(__('Paymentwall')); ?>

                                                                </span>
                                                            </button>
                                                        </h2>
                                                <div id="collapse11" class="accordion-collapse collapse"
                                                    aria-labelledby="Paymentwall" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="col-12 d-flex justify-content-between">

                                                            <small class="">
                                                                <?php echo e(__('Note: This detail will use for make checkout of plan.')); ?></small>

                                                                   <div class="form-check form-switch d-inline-block">

                                                                                 <input type="hidden" name="is_paymentwall_enabled" value="off">
                                                                                <input type="checkbox" class="form-check-input" name="is_paymentwall_enabled" id="is_paymentwall_enabled" <?php echo e(isset($payment_detail['is_paymentwall_enabled'])  && $payment_detail['is_paymentwall_enabled']== 'on' ? 'checked="checked"' : ''); ?>>
                                                                                 <label class="custom-control-label form-control-label" for="is_paymentwall_enabled"><?php echo e(__('Enable PaymentWall')); ?></label>
                                                                            </div>



                                                        </div>
                                                        <div class="row mt-2">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="paymentwall_public_key" class="form-label"><?php echo e(__('Public Key')); ?></label>
                                                                                <input type="text" name="paymentwall_public_key" id="paymentwall_public_key" class="form-control" value="<?php echo e(isset($payment_detail['paymentwall_public_key'])?$payment_detail['paymentwall_public_key']:''); ?>" placeholder="<?php echo e(__('Public Key')); ?>"/>
                                                                                <?php if($errors->has('paymentwall_public_key')): ?>
                                                                                    <span class="invalid-feedback d-block">
                                                                                        <?php echo e($errors->first('paymentwall_public_key')); ?>

                                                                                    </span>
                                                                                <?php endif; ?>

                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                     <label for="paymentwall_private_key" class="form-label"><?php echo e(__('Private Key')); ?></label>
                                                                                <input type="text" name="paymentwall_private_key" id="paymentwall_private_key" class="form-control form-control-label" value="<?php echo e(isset($payment_detail['paymentwall_private_key'])?$payment_detail['paymentwall_private_key']:''); ?>" placeholder="<?php echo e(__('Private Key')); ?>"/>
                                                                                <?php if($errors->has('paymentwall_private_key')): ?>
                                                                                    <span class="invalid-feedback d-block">
                                                                                        <?php echo e($errors->first('paymentwall_private_key')); ?>

                                                                                    </span>
                                                                                <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>




                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                            <button type="submit" class="btn-submit btn btn-primary"><?php echo e(__('Save Changes')); ?></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="invoices-settings" class="tab-pane">
                        <div class="">
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="">
                                            <?php echo e(__('Invoice Footer Details')); ?>


                                        </h5>
                                         <small class="d-block mt-2"><?php echo e(__('This detail will be displayed into invoice footer.')); ?></small>
                                    </div>
                                    <div class="card-body p-4">
                                        <form method="post" action="<?php echo e(route('workspace.settings.store',$currentWorkspace->slug)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label for="invoice_footer_title" class="form-label"><?php echo e(__('Invoice Footer Title')); ?></label>
                                                    <input class="form-control" name="invoice_footer_title" type="text" value="<?php echo e($currentWorkspace->invoice_footer_title); ?>" id="invoice_footer_title">
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="invoice_footer_notes" class="form-label"><?php echo e(__('Invoice Footer Notes')); ?></label>
                                                    <textarea class="form-control" name="invoice_footer_notes"><?php echo e($currentWorkspace->invoice_footer_notes); ?></textarea>
                                                </div>
                                                <div class=" text-end">
                                                    <button type="submit" class="btn-submit btn btn-primary">
                                                        <?php echo e(__('Save Changes')); ?>

                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="">
                                            <?php echo e(__('Invoice')); ?>

                                        </h5>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="invoice_color_pallate">
                                            <div class="">
                                                <form action="<?php echo e(route('workspace.settings.store',$currentWorkspace->slug)); ?>" method="post">
                                                    <?php echo csrf_field(); ?>
                                                    <div class="form-group">
                                                        <label for="address" class="form-label"><?php echo e(__('Invoice Template')); ?></label>
                                                        <select class="form-control select2" name="invoice_template">
                                                            <option value="template1" <?php if($currentWorkspace->invoice_template == 'template1'): ?> selected <?php endif; ?>>New York</option>
                                                            <option value="template2" <?php if($currentWorkspace->invoice_template == 'template2'): ?> selected <?php endif; ?>>Toronto</option>
                                                            <option value="template3" <?php if($currentWorkspace->invoice_template == 'template3'): ?> selected <?php endif; ?>>Rio</option>
                                                            <option value="template4" <?php if($currentWorkspace->invoice_template == 'template4'): ?> selected <?php endif; ?>>London</option>
                                                            <option value="template5" <?php if($currentWorkspace->invoice_template == 'template5'): ?> selected <?php endif; ?>>Istanbul</option>
                                                            <option value="template6" <?php if($currentWorkspace->invoice_template == 'template6'): ?> selected <?php endif; ?>>Mumbai</option>
                                                            <option value="template7" <?php if($currentWorkspace->invoice_template == 'template7'): ?> selected <?php endif; ?>>Hong Kong</option>
                                                            <option value="template8" <?php if($currentWorkspace->invoice_template == 'template8'): ?> selected <?php endif; ?>>Tokyo</option>
                                                            <option value="template9" <?php if($currentWorkspace->invoice_template == 'template9'): ?> selected <?php endif; ?>>Sydney</option>
                                                            <option value="template10" <?php if($currentWorkspace->invoice_template == 'template10'): ?> selected <?php endif; ?>>Paris</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-control-label"><?php echo e(__('Color')); ?></label>
                                                        <div class="row gutters-xs">
                                                            <?php $__currentLoopData = $colors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <div class="col-auto">
                                                                    <label class="colorinput">
                                                                        <input name="invoice_color" type="radio" value="<?php echo e($color); ?>" class="colorinput-input" <?php if($currentWorkspace->invoice_color == $color): ?> checked <?php endif; ?>>
                                                                        <span class="colorinput-color mb-1" style="background: #<?php echo e($color); ?>"></span>
                                                                    </label>
                                                                </div>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </div>
                                                    </div>
                                                     <div class="text-end">
                                                    <button class="btn-submit btn btn-primary" type="submit">
                                                        <?php echo e(__('Save Changes')); ?>

                                                    </button>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                            <div class="main_invoice">
                                                <iframe frameborder="0" width="100%" height="1080px" src="<?php echo e(route('invoice.preview',[$currentWorkspace->slug,($currentWorkspace->invoice_template)?$currentWorkspace->invoice_template:'template1',($currentWorkspace->invoice_color)?$currentWorkspace->invoice_color:'fff'])); ?>"></iframe>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>



                       <div class="" id="email-notification">
                        <div class="card">
                            <div class="card-header">
                                <h5><?php echo e(__('Email Notification')); ?></h5>
                            </div>
                            <div class="card-body table-border-style ">
                                <div class="table-responsive">
                                    <table class="table dataTable">
                                        <thead>
                                        <tr>
                                            <th class="w-75"> <?php echo e(__('Name')); ?></th>
                                            <th class="text-center"> <?php echo e(__('On/Off')); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php $__currentLoopData = $EmailTemplates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $EmailTemplate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="">
                                                <td><?php echo e($EmailTemplate->name); ?></td>
                                                <td class="text-center">

                                                        <div class="form-group col-md-12">
                                                            <label class="form-check form-switch d-inline-block">
                                                                <input type="checkbox" class="email-template-checkbox form-check-input" name="is_active" id="email_tempalte_<?php echo e(($EmailTemplate->template)?$EmailTemplate->template->id:''); ?>" <?php if(($EmailTemplate->template)?($EmailTemplate->template->is_active == 1):''): ?> checked="checked" <?php endif; ?> type="checkbox" value="<?php echo e(($EmailTemplate->template)?$EmailTemplate->template->is_active:''); ?>" data-url="<?php echo e(route('status.email.language',[($EmailTemplate->template)?$EmailTemplate->template->id:'0',$currentWorkspace->slug])); ?>">
                                                                <label class="col-form-label" for="email_tempalte_<?php echo e(($EmailTemplate->template)?$EmailTemplate->template->id:''); ?>"></label>
                                                            </label>
                                                        </div>

                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>












                    <div id="zoom-settings" class="">
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="">
                                            <?php echo e(__('Zoom Details')); ?>

                                        </h5>
                                    </div>
                                    <div class="card-body p-4">
                                        <form method="post" action="<?php echo e(route('workspace.settings.store',$currentWorkspace->slug)); ?>" class="payment-form">
                                            <?php echo csrf_field(); ?>
                                            <div class="row mt-3">
                                                <div class="form-group col-md-12">
                                                    <label for="company" class="form-label"><?php echo e(__('Zoom API Key')); ?></label>
                                                    <input type="text" name="zoom_api_key" id="zoom_api_key" class="form-control" value="<?php echo e($currentWorkspace->zoom_api_key); ?>" required="required"/>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="address" class="form-label"><?php echo e(__('Zoom API Secret')); ?></label>
                                                    <input type="text" name="zoom_api_secret" id="zoom_api_secret" class="form-control" value="<?php echo e($currentWorkspace->zoom_api_secret); ?>" required="required"/>
                                                </div>

                                            </div>
                                            <div class="text-end">
                                            <button type="submit" class="btn-submit btn btn-primary"><?php echo e(__('Save Changes')); ?></button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <?php if(Auth::user()->type == 'user'): ?>
                      <div class="" id="slack-setting">
                          <?php echo e(Form::open(array('route'=>['workspace.settings.Slack', $currentWorkspace->slug],'method'=>'post','class'=>'d-contents'))); ?>

                           <div class="row justify-content-center">
                            <div class="col-md-12">
                                <div class="card">
                                     <div class="card-header">
                                    <h5 class="">
                                        <?php echo e(__('Slack Setting')); ?>

                                    </h5>
                                    </div>
                                    <div class="card-body">
                                    <div class="row company-setting">
                                        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                             <?php echo e(Form::label('Slack Webhook URL',__('Slack Webhook URL'),['class'=>'form-label'])); ?>

                                           <?php echo e(Form::text('slack_webhook', isset($payment_detail['slack_webhook']) ?$payment_detail['slack_webhook'] :'', ['class' => 'form-control', 'placeholder' => __('Enter Slack Webhook URL'), 'required' => 'required'])); ?>


                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 form-group mb-3">
                                             <?php echo e(Form::label('Module Setting',__('Module Setting'),['class'=>'form-label'])); ?>

                                           </div>


                                        <div class="col-md-4">
                                               <div class="d-flex align-items-center justify-content-between list_colume_notifi">
                                                    <div class="mb-3 mb-sm-0">
                                                        <h6> <?php echo e(Form::label('Project create',__('Project create'),['class'=>'form-label'])); ?></h6>
                                                    </div>
                                                   <div class="text-end">
                                                    <div class="form-check form-switch d-inline-block">
                                                            <?php echo e(Form::checkbox('project_notificaation', '1',isset($payment_detail['project_notificaation']) && $payment_detail['project_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'project_notificaation'))); ?>

                                                      </div>
                                                  </div>
                                                </div>

                                                <div class="d-flex align-items-center justify-content-between list_colume_notifi">
                                                    <div class="mb-3 mb-sm-0">
                                                        <h6>  <?php echo e(Form::label('Task create',__('Task create'),['class'=>'form-label'])); ?></h6>
                                                    </div>
                                                   <div class="text-end">
                                                    <div class="form-check form-switch d-inline-block">
                                                           <?php echo e(Form::checkbox('task_notificaation', '1',isset($payment_detail['task_notificaation']) && $payment_detail['task_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'task_notificaation'))); ?>

                                                      </div>
                                                  </div>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between list_colume_notifi">
                                                    <div class="mb-3 mb-sm-0">
                                                        <h6><?php echo e(Form::label('Task move',__('Task move'),['class'=>'form-label'])); ?></h6>
                                                    </div>
                                                   <div class="text-end">
                                                    <div class="form-check form-switch d-inline-block">
                                                            <?php echo e(Form::checkbox('taskmove_notificaation', '1',isset($payment_detail['taskmove_notificaation']) && $payment_detail['taskmove_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'taskmove_notificaation'))); ?>

                                                      </div>
                                                  </div>
                                                </div>
                                            </div>

                                                 <div class="col-md-4">
                                                        <div class="d-flex align-items-center justify-content-between list_colume_notifi">
                                                            <div class="mb-3 mb-sm-0">
                                                                <h6> <?php echo e(Form::label('Milestone create',__('Milestone create'),['class'=>'form-label'])); ?></h6>
                                                            </div>
                                                           <div class="text-end">
                                                            <div class="form-check form-switch d-inline-block">
                                                                     <?php echo e(Form::checkbox('milestone_notificaation', '1',isset($payment_detail['milestone_notificaation']) && $payment_detail['milestone_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'milestone_notificaation'))); ?>

                                                              </div>
                                                          </div>
                                                        </div>

                                                           <div class="d-flex align-items-center justify-content-between list_colume_notifi">
                                                            <div class="mb-3 mb-sm-0">
                                                                <h6> <?php echo e(Form::label('Milestone status',__('Milestone status'),['class'=>'form-label'])); ?></h6>
                                                            </div>
                                                           <div class="text-end">
                                                            <div class="form-check form-switch d-inline-block">
                                                                     <?php echo e(Form::checkbox('milestonest_notificaation', '1',isset($payment_detail['milestonest_notificaation']) && $payment_detail['milestonest_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'milestonest_notificaation'))); ?>

                                                              </div>
                                                          </div>
                                                        </div>

                                                           <div class="d-flex align-items-center justify-content-between list_colume_notifi">
                                                            <div class="mb-3 mb-sm-0">
                                                                <h6>  <?php echo e(Form::label('Task comment',__('Task comment'),['class'=>'form-label'])); ?></h6>
                                                            </div>
                                                           <div class="text-end">
                                                            <div class="form-check form-switch d-inline-block">
                                                                    <?php echo e(Form::checkbox('taskcom_notificaation', '1',isset($payment_detail['taskcom_notificaation']) && $payment_detail['taskcom_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'taskcom_notificaation'))); ?>

                                                              </div>
                                                          </div>
                                                        </div>
                                                     </div>

                                                 <div class="col-md-4">
                                                        <div class="d-flex align-items-center justify-content-between list_colume_notifi">
                                                            <div class="mb-3 mb-sm-0">
                                                                <h6><?php echo e(Form::label('Invoice create',__('Invoice create'),['class'=>'form-label'])); ?></h6>
                                                            </div>
                                                           <div class="text-end">
                                                            <div class="form-check form-switch d-inline-block">
                                                               <?php echo e(Form::checkbox('invoice_notificaation', '1',isset($payment_detail['invoice_notificaation']) && $payment_detail['invoice_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'invoice_notificaation'))); ?>

                                                              </div>
                                                          </div>
                                                        </div>

                                                        <div class="d-flex align-items-center justify-content-between list_colume_notifi">
                                                            <div class="mb-3 mb-sm-0">
                                                                <h6> <?php echo e(Form::label('Invoice status updated',__('Invoice status updated'),['class'=>'form-label'])); ?></h6>
                                                            </div>
                                                           <div class="text-end">
                                                            <div class="form-check form-switch d-inline-block">
                                                                <?php echo e(Form::checkbox('invoicest_notificaation', '1',isset($payment_detail['invoicest_notificaation']) && $payment_detail['invoicest_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'invoicest_notificaation'))); ?>

                                                              </div>
                                                          </div>
                                                        </div>
                                                   </div>
                                        <div class=" text-end">
                                            <?php echo e(Form::submit(__('Save Changes'),array('class'=>'btn btn-primary'))); ?>

                                        </div>
                                    </div>
                                </div>
                             </div>
                       </div>
                    </div>
                <?php echo e(Form::close()); ?>

            </div>
         <?php endif; ?>
                <?php if(Auth::user()->type == 'user'): ?>
                      <div class="" id="telegram-setting">
                          <?php echo e(Form::open(array('route'=>['workspace.settings.telegram', $currentWorkspace->slug],'method'=>'post','class'=>'d-contents'))); ?>

                           <div class="row justify-content-center">
                            <div class="col-md-12">
                                <div class="card">
                                        <div class="card-header">
                                        <h5 class="">
                                            <?php echo e(__('Telegram Setting')); ?>

                                        </h5>
                                    </div>
                                    <div class="card-body">
                                <div class="row company-setting">
                                    <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                         <?php echo e(Form::label('Telegram Access Token',__('Telegram Access Token'),['class'=>'form-label'])); ?>

                                       <?php echo e(Form::text('telegram_token', isset($payment_detail['telegram_token']) ?$payment_detail['telegram_token'] :'', ['class' => 'form-control', 'placeholder' => __('Enter Telegram Access Token'), 'required' => 'required'])); ?>


                                    </div>

                                      <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                         <?php echo e(Form::label('Telegram ChatID',__('Telegram ChatID'),['class'=>'form-label'])); ?>

                                       <?php echo e(Form::text('telegram_chatid',  isset($payment_detail['telegram_chatid']) ?$payment_detail['telegram_chatid'] :'', ['class' => 'form-control', 'placeholder' => __('Enter Telegram ChatID'), 'required' => 'required'])); ?>

                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 form-group mb-3">
                                         <?php echo e(Form::label('Module Setting',__('Module Setting'),['class'=>'form-control-label'])); ?>

                                       </div>


                                    <div class="col-md-4 ">
                                              <div class="d-flex align-items-center justify-content-between list_colume_notifi">
                                                    <div class="mb-3 mb-sm-0">
                                                        <h6>  <?php echo e(Form::label('Project create',__('Project create'),['class'=>'form-label'])); ?></h6>
                                                    </div>
                                                   <div class="text-end">
                                                    <div class="form-check form-switch d-inline-block">
                                                          <?php echo e(Form::checkbox('telegram_project_notificaation', '1',isset($payment_detail['telegram_project_notificaation']) && $payment_detail['telegram_project_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_project_notificaation'))); ?></div>
                                                </div>
                                                </div>

                                                <div class="d-flex align-items-center justify-content-between list_colume_notifi">
                                                    <div class="mb-3 mb-sm-0">
                                                        <h6>   <?php echo e(Form::label('Task create',__('Task create'),['class'=>'form-label'])); ?></h6>
                                                    </div>
                                                   <div class="text-end">
                                                    <div class="form-check form-switch d-inline-block">
                                                          <?php echo e(Form::checkbox('telegram_task_notificaation', '1',isset($payment_detail['telegram_task_notificaation']) && $payment_detail['telegram_task_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_task_notificaation'))); ?></div>
                                                </div>
                                                </div>

                                                <div class="d-flex align-items-center justify-content-between list_colume_notifi">
                                                    <div class="mb-3 mb-sm-0">
                                                        <h6>      <?php echo e(Form::label('Task move',__('Task move'),['class'=>'form-label'])); ?></h6>
                                                    </div>
                                                   <div class="text-end">
                                                    <div class="form-check form-switch d-inline-block">
                                                           <?php echo e(Form::checkbox('telegram_taskmove_notificaation', '1',isset($payment_detail['telegram_taskmove_notificaation']) && $payment_detail['telegram_taskmove_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_taskmove_notificaation'))); ?></div>
                                                    </div>
                                                   </div>
                                                </div>

                                             <div class="col-md-4">
                                                <div class="d-flex align-items-center justify-content-between list_colume_notifi">
                                                    <div class="mb-3 mb-sm-0">
                                                        <h6><?php echo e(Form::label('Milestone create',__('Milestone create'),['class'=>'form-label'])); ?></h6>
                                                    </div>
                                                   <div class="text-end">
                                                    <div class="form-check form-switch d-inline-block">
                                                          <?php echo e(Form::checkbox('telegram_milestone_notificaation', '1',isset($payment_detail['telegram_milestone_notificaation']) && $payment_detail['telegram_milestone_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_milestone_notificaation'))); ?></div>
                                                    </div>
                                                   </div>
                                                   <div class="d-flex align-items-center justify-content-between list_colume_notifi">
                                                    <div class="mb-3 mb-sm-0">
                                                        <h6><?php echo e(Form::label('Milestone status',__('Milestone status'),['class'=>'form-label'])); ?></h6>
                                                    </div>
                                                   <div class="text-end">
                                                    <div class="form-check form-switch d-inline-block">
                                                           <?php echo e(Form::checkbox('telegram_milestonest_notificaation', '1',isset($payment_detail['telegram_milestonest_notificaation']) && $payment_detail['telegram_milestonest_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_milestonest_notificaation'))); ?></div>
                                                </div>
                                                </div>

                                                 <div class="d-flex align-items-center justify-content-between list_colume_notifi">
                                                    <div class="mb-3 mb-sm-0">
                                                        <h6><?php echo e(Form::label('Task comment',__('Task comment'),['class'=>'form-label'])); ?></h6>
                                                    </div>
                                                   <div class="text-end">
                                                    <div class="form-check form-switch d-inline-block">
                                                            <?php echo e(Form::checkbox('telegram_taskcom_notificaation', '1',isset($payment_detail['telegram_taskcom_notificaation']) && $payment_detail['telegram_taskcom_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_taskcom_notificaation'))); ?></div>
                                                </div>
                                                </div>
                                        </div>
                                             <div class="col-md-4">
                                                   <div class="d-flex align-items-center justify-content-between list_colume_notifi">
                                                    <div class="mb-3 mb-sm-0">
                                                        <h6>  <?php echo e(Form::label('Invoice create',__('Invoice create'),['class'=>'form-label'])); ?></h6>
                                                    </div>
                                                   <div class="text-end">
                                                    <div class="form-check form-switch d-inline-block">
                                                            <?php echo e(Form::checkbox('telegram_invoice_notificaation', '1',isset($payment_detail['telegram_invoice_notificaation']) && $payment_detail['telegram_invoice_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_invoice_notificaation'))); ?></div>
                                                </div>
                                                </div>
                                                 <div class="d-flex align-items-center justify-content-between list_colume_notifi">
                                                    <div class="mb-3 mb-sm-0">
                                                        <h6> <?php echo e(Form::label('Invoice status updated',__('Invoice status updated'),['class'=>'form-label'])); ?></h6>
                                                    </div>
                                                   <div class="text-end">
                                                    <div class="form-check form-switch d-inline-block">
                                                           <?php echo e(Form::checkbox('telegram_invoicest_notificaation', '1',isset($payment_detail['telegram_invoicest_notificaation']) && $payment_detail['telegram_invoicest_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_invoicest_notificaation'))); ?></div>
                                                </div>
                                                </div>
                                        </div>
                                    <div class=" text-end">
                                        <?php echo e(Form::submit(__('Save Changes'),array('class'=>'btn btn-primary'))); ?>

                               </div>
                           </div>
                        </div>
                    </div>
                </div>
            </div>
                    <?php echo e(Form::close()); ?>

                </div>
                <?php endif; ?>
                    </div>
                </div>
                <!-- [ sample-page ] end -->
            </div>
            <!-- [ Main Content ] end -->
        </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('custom/js/jquery-ui.min.js')); ?>"></script>
    <script src="<?php echo e(asset('custom/js/repeater.js')); ?>"></script>
    <script src="<?php echo e(asset('custom/js/colorPick.js')); ?>"></script>
<script>
       var scrollSpy = new bootstrap.ScrollSpy(document.body, {
        target: '#useradd-sidenav',
        offset: 300
    })
</script>

<script src="<?php echo e(asset('assets/js/pages/wow.min.js')); ?>"></script>
    <script>
      // Start [ Menu hide/show on scroll ]
      let ost = 0;
      document.addEventListener("scroll", function () {
        let cOst = document.documentElement.scrollTop;
        if (cOst == 0) {
          document.querySelector(".navbar").classList.add("top-nav-collapse");
        } else if (cOst > ost) {
          document.querySelector(".navbar").classList.add("top-nav-collapse");
          document.querySelector(".navbar").classList.remove("default");
        } else {
          document.querySelector(".navbar").classList.add("default");
          document
            .querySelector(".navbar")
            .classList.remove("top-nav-collapse");
        }
        ost = cOst;
      });
      // End [ Menu hide/show on scroll ]
      var wow = new WOW({
        animateClass: "animate__animated", // animation css class (default is animated)
      });
      wow.init();
      var scrollSpy = new bootstrap.ScrollSpy(document.body, {
        target: "#navbar-example",
      });
    </script>
    <script>

        $(document).on("change", "select[name='invoice_template'], input[name='invoice_color']", function () {
            var template = $("select[name='invoice_template']").val();
            var color = $("input[name='invoice_color']:checked").val();
            $('iframe').attr('src', '<?php echo e(url($currentWorkspace->slug.'/invoices/preview')); ?>/' + template + '/' + color);
        });

        $(document).ready(function () {

            var $dragAndDrop = $("body .task-stages tbody").sortable({
                handle: '.sort-handler'
            });

            var $repeater = $('.task-stages').repeater({
                initEmpty: true,
                defaultValues: {},
                show: function () {
                    $(this).slideDown();
                },
                hide: function (deleteElement) {
                    if (confirm('<?php echo e(__('Are you sure ?')); ?>')) {
                        $(this).slideUp(deleteElement);
                    }
                },
                ready: function (setIndexes) {
                    $dragAndDrop.on('drop', setIndexes);
                },
                isFirstItemUndeletable: true
            });


            var value = $(".task-stages").attr('data-value');
            if (typeof value != 'undefined' && value.length != 0) {
                value = JSON.parse(value);
                $repeater.setList(value);
            }

            var $dragAndDropBug = $("body .bug-stages tbody").sortable({
                handle: '.sort-handler'
            });

            var $repeaterBug = $('.bug-stages').repeater({
                initEmpty: true,
                defaultValues: {},
                show: function () {
                    $(this).slideDown();
                },
                hide: function (deleteElement) {
                    if (confirm('<?php echo e(__('Are you sure ?')); ?>')) {
                        $(this).slideUp(deleteElement);
                    }
                },
                ready: function (setIndexes) {
                    $dragAndDropBug.on('drop', setIndexes);
                },
                isFirstItemUndeletable: true
            });


            var valuebug = $(".bug-stages").attr('data-value');
            if (typeof valuebug != 'undefined' && valuebug.length != 0) {
                valuebug = JSON.parse(valuebug);
                $repeaterBug.setList(valuebug);
            }
            $(document).on('click', '.list-group-item', function() {
                $('.list-group-item').removeClass('active');
                $('.list-group-item').removeClass('text-primary');
                setTimeout(() => {
                    $(this).addClass('active').removeClass('text-primary');
                }, 10);
            });

                   var type = window.location.hash.substr(1);
            $('.list-group-item').removeClass('active');
            $('.list-group-item').removeClass('text-primary');
            if (type != '') {
                $('a[href="#' + type + '"]').addClass('active').removeClass('text-primary');
            } else {
                $('.list-group-item:eq(0)').addClass('active').removeClass('text-primary');
            }
        });
    </script>


    <script>
$('#logo').change(function(){

    let reader = new FileReader();
    reader.onload = (e) => {
      $('#dark_logo').attr('src', e.target.result);
    }
    reader.readAsDataURL(this.files[0]);

   });

    $('#logo_white').change(function(){

    let reader = new FileReader();
    reader.onload = (e) => {
      $('#image').attr('src', e.target.result);
    }
    reader.readAsDataURL(this.files[0]);

   });
</script>

<script>

$(document).ready(function() {
    if ($('.gdpr_fulltime').is(':checked')) {
        $('.fulltime').show();
    } else {
        $('.fulltime').hide();
    }
    $('#gdpr_cookie').on('change', function() {
        if ($('.gdpr_fulltime').is(':checked')) {
            $('.fulltime').show();
        } else {
            $('.fulltime').hide();
        }
    });
});

var scrollSpy = new bootstrap.ScrollSpy(document.body, {
    target: '#useradd-sidenav',
    offset: 300
})

$('.themes-color-change').on('click',function(){
    var color_val = $(this).data('value');
    $('.theme-color').prop('checked', false);
    $('.themes-color-change').removeClass('active_color');
    $(this).addClass('active_color');
    $(`input[value=${color_val}]`).prop('checked', true);

});

function check_theme(color_val) {
            $('.theme-color').prop('checked', false);
            $('input[value="'+color_val+'"]').prop('checked', true);
            $('#color_value').val(color_val);
        }

</script>



<script>
        $(document).on("click", ".email-template-checkbox", function () {
            var chbox = $(this);
            $.ajax({
                url: chbox.attr('data-url'),
                data: {_token: $('meta[name="csrf-token"]').attr('content'), status: chbox.val()},
                type: 'POST',
                success: function (response) {
                    if (response.is_success) {
                        show_toastr('<?php echo e(__("Success")); ?>', response.success, 'success');
                        if (chbox.val() == 1) {
                            $('#' + chbox.attr('id')).val(0);
                        } else {
                            $('#' + chbox.attr('id')).val(1);
                        }
                    } else {
                        show_toastr('<?php echo e(__("Error")); ?>', response.error, 'error');
                    }
                },
                error: function (response) {
                    response = response.responseJSON;
                    if (response.is_success) {
                        show_toastr('<?php echo e(__("Error")); ?>', response.error, 'error');
                    } else {
                        show_toastr('<?php echo e(__("Error")); ?>', response, 'error');
                    }
                }
            })
        });

</script>


<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/heritage/SHINE/LATEST/projectmanagement/resources/views/users/setting.blade.php ENDPATH**/ ?>