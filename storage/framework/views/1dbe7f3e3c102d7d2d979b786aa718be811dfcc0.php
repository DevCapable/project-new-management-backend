<?php $__env->startSection('page-title'); ?>
 <?php echo e(__('Email Templates')); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('action-button'); ?>
<div class="row">
    <div class="col-lg-6">
    </div>
    <div class="col-lg-6">
        <div class="text-end">
                       <div class="d-flex justify-content-end drp-languages">
                                    <ul class="list-unstyled mb-0 m-2">
                                        <li class="dropdown dash-h-item drp-language">
                                            <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown"
                                               href="#" role="button" aria-haspopup="false" aria-expanded="false"
                                               id="dropdownLanguage">
                                                <span
                                                    class="drp-text hide-mob text-primary"><?php echo e(__('Locale: ')); ?><?php echo e(Str::upper($currEmailTempLang->lang)); ?></span>
                                                <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                                            </a>
                                            <div class="dropdown-menu dash-h-dropdown dropdown-menu-end"
                                                 aria-labelledby="dropdownLanguage">
                                                <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <a href="<?php echo e(route('manage.email.language', [$emailTemplate->id, $lang])); ?>"
                                                       class="dropdown-item <?php echo e($currEmailTempLang->lang == $lang ? 'text-primary' : ''); ?>"><?php echo e(Str::upper($lang)); ?></a>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </li>
                                    </ul>
                                    <ul class="list-unstyled mb-0 m-2">
                                        <li class="dropdown dash-h-item drp-language">
                                            <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown"
                                               href="#" role="button" aria-haspopup="false" aria-expanded="false"
                                               id="dropdownLanguage">
                                                <span
                                                    class="drp-text hide-mob text-primary"><?php echo e(__('Template: ')); ?><?php echo e($emailTemplate->name); ?></span>
                                                <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                                            </a>
                                            <div class="dropdown-menu dash-h-dropdown dropdown-menu-end" aria-labelledby="dropdownLanguage">
                                                <?php $__currentLoopData = $EmailTemplates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $EmailTemplate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <a href="<?php echo e(route('manage.email.language', [$EmailTemplate->id,(Request::segment(3)?Request::segment(3):\Auth::user()->lang)])); ?>"
                                                       class="dropdown-item <?php echo e($emailTemplate->name == $EmailTemplate->name ? 'text-primary' : ''); ?>"><?php echo e($EmailTemplate->name); ?>

                                                    </a>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('links'); ?>
<?php if(\Auth::guard('client')->check()): ?>   
<li class="breadcrumb-item"><a href="<?php echo e(route('client.home')); ?>"><?php echo e(__('Home')); ?></a></li>
 <?php else: ?>
 <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
 <?php endif; ?>
<li class="breadcrumb-item"> <a href="<?php echo e(route('email_template.index')); ?>"><?php echo e(__('Email Templates')); ?></a></li>
<li class="breadcrumb-item"><?php echo e($emailTemplate->name); ?></li>
 <?php $__env->stopSection(); ?>

<?php $__env->startPush('css-page'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('custom/libs/summernote/summernote-bs4.css')); ?>">
<?php $__env->stopPush(); ?>
<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('custom/libs/summernote/summernote-bs4.js')); ?>"></script>
<script>
 if ($(".summernote-simple").length) {
        $('.summernote-simple').summernote({
            dialogsInBody: !0,
            minHeight: 200,
            toolbar: [
                ['style', ['style']],
                ["font", ["bold", "italic", "underline", "clear", "strikethrough"]],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ["para", ["ul", "ol", "paragraph"]],
            ]
        });
    }
</script>
<?php $__env->stopPush(); ?>
 <?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body ">
                   
                    <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                      
                   <h5><?php echo e(__('Placeholders')); ?></h5>
                      

                        <div class="card">
                                <div class="card-body">
                      <div class="row text-xs">
                                              
                                          <?php if($emailTemplate->name=='New Client'): ?>
                                          <div class="row">
                                                <p class="col-4"><?php echo e(__('App Name')); ?> : <span class="pull-end text-primary">{app_name}</span></p>
                                                <p class="col-4"><?php echo e(__('User Name')); ?> : <span class="pull-right text-primary">{user_name}</span></p>
                                                <p class="col-4"><?php echo e(__('App Url')); ?> : <span class="pull-right text-primary">{app_url}</span></p>
                                                <p class="col-4"><?php echo e(__('Email')); ?> : <span class="pull-right text-primary">{email}</span></p>
                                                <p class="col-4"><?php echo e(__('Password')); ?> : <span class="pull-right text-primary">{password}</span></p>
                                            </div>

                                           <?php elseif($emailTemplate->name=='Invite User'): ?>  

                                              <div class="row">
                                                <p class="col-4"><?php echo e(__('App Name')); ?> : <span class="pull-end text-primary">{app_name}</span></p>
                                                <p class="col-4"><?php echo e(__('User Name')); ?> : <span class="pull-right text-primary">{user_name}</span></p>
                                                <p class="col-4"><?php echo e(__('App Url')); ?> : <span class="pull-right text-primary">{app_url}</span></p>
                                                <p class="col-4"><?php echo e(__('Workspace Name')); ?> : <span class="pull-right text-primary">{workspace_name}</span></p>
                                                <p class="col-4"><?php echo e(__('Owner Name')); ?> : <span class="pull-right text-primary">{owner_name}</span></p>
                                            </div>


                                            <?php elseif($emailTemplate->name=='Assign Project'): ?>  

                                              <div class="row">
                                                <p class="col-4"><?php echo e(__('App Name')); ?> : <span class="pull-end text-primary">{app_name}</span></p>
                                                <p class="col-4"><?php echo e(__('User Name')); ?> : <span class="pull-right text-primary">{user_name}</span></p>
                                                <p class="col-4"><?php echo e(__('App Url')); ?> : <span class="pull-right text-primary">{app_url}</span></p>
                                                <p class="col-4"><?php echo e(__('Project Name')); ?> : <span class="pull-right text-primary">{project_name}</span></p>
                                                <p class="col-4"><?php echo e(__('Project Status')); ?> : <span class="pull-right text-primary">{project_status}</span></p>
                                                <p class="col-4"><?php echo e(__('Owner Name')); ?> : <span class="pull-right text-primary">{owner_name}</span></p>
                                            </div>


                                            <?php elseif($emailTemplate->name=='Contract Share'): ?>  

                                            <div class="row">
                                                <p class="col-4"><?php echo e(__('Client Name')); ?> : <span class="pull-end text-primary">{client_name}</span></p>
                                                <p class="col-4"><?php echo e(__('Contract Subject')); ?> : <span class="pull-right text-primary">{contract_subject}</span></p>
                                                <p class="col-4"><?php echo e(__('Contract Type')); ?> : <span class="pull-right text-primary">{contract_type}</span></p>
                                                <p class="col-4"><?php echo e(__('Contract value')); ?> : <span class="pull-right text-primary">{value}</span></p>
                                                <p class="col-4"><?php echo e(__('Start Date')); ?> : <span class="pull-right text-primary">{start_date}</span></p>
                                                <p class="col-4"><?php echo e(__('End Date')); ?> : <span class="pull-right text-primary">{end_date}</span></p>
                                            </div>
                                            <?php endif; ?>
                    
                    </div>
                </div>
                        </div>
                    </div>
                      <?php echo e(Form::model($currEmailTempLang, array('route' => array('email_template.update', $currEmailTempLang->parent_id), 'method' => 'PUT'))); ?>


                      
                    </div>
               
                 <div class="row">
                        <div class="form-group col-6">
                            <?php echo e(Form::label('subject', __('Subject'), ['class' => 'col-form-label text-dark'])); ?>

                            <?php echo e(Form::text('subject', null, ['class' => 'form-control font-style', 'required' => 'required'])); ?>

                        </div>
                        <div class="form-group col-md-6">
                            <?php echo e(Form::label('from', __('From'), ['class' => 'col-form-label text-dark'])); ?>

                            <?php echo e(Form::text('from', $emailTemplate->from, ['class' => 'form-control font-style', 'required' => 'required'])); ?>

                        </div>
                        <div class="form-group col-12">
                                    <?php echo e(Form::label('content',__('Email Message'),['class'=>'form-label text-dark'])); ?>

                                    <?php echo e(Form::textarea('content',$currEmailTempLang->content,array('class'=>'summernote-simple','required'=>'required'))); ?>

                            </div>
                    </div>
                   
            
                    <div class="col-md-12 text-end">
                                        <?php echo e(Form::hidden('lang',null)); ?>

                                        <input type="submit" value="<?php echo e(__('Save Changes')); ?>" class="btn btn-print-invoice  btn-primary m-r-10">
                                    </div>
                    <?php echo e(Form::close()); ?>

                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/heritage/SHINE/LATEST/projectmanagement/resources/views/email_templates/show.blade.php ENDPATH**/ ?>