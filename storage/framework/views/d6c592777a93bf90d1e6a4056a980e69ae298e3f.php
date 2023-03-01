<?php $__env->startSection('page-title'); ?> <?php echo e(__('User Profile')); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('links'); ?>
<?php if(\Auth::guard('client')->check()): ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('client.home')); ?>"><?php echo e(__('Home')); ?></a></li>
 <?php else: ?>
 <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
 <?php endif; ?>
<li class="breadcrumb-item"> <?php echo e(__('User Profile')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
  <div class="row">
                    <div class="col-xl-3">
                        <div class="card sticky-top" style="top:30px">
                            <div class="list-group list-group-flush" id="useradd-sidenav">
                                <a href="#v-pills-home" class="list-group-item list-group-item-action"><?php echo e(__('Account')); ?> <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                                <a href="#v-pills-profile" class="list-group-item list-group-item-action"><?php echo e(__('Change Password')); ?> <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                                <?php if(auth()->guard('client')->check()): ?>
                                <a href="#v-pills-billing" class="list-group-item list-group-item-action"><?php echo e(__('Billing Details')); ?> <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9">
                        <div id="v-pills-home" class="card ">
                              <div class="card-header">
                                <h5><?php echo e(__('Avatar')); ?></h5>
                            </div>
                            <div class="card-body">
                          <form method="post" action="<?php if(auth()->guard('web')->check()): ?><?php echo e(route('update.account')); ?><?php elseif(auth()->guard()->check()): ?><?php echo e(route('client.update.account')); ?><?php endif; ?>" enctype="multipart/form-data">
                                            <?php echo csrf_field(); ?>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-group">

                                                        <img <?php if($user->avatar): ?> src="<?php echo e(asset('avatars/'.$user->avatar)); ?>" <?php else: ?> avatar="<?php echo e($user->name); ?>" <?php endif; ?> id="myAvatar" alt="user-image" class="rounded-circle img-thumbnail img_hight w-25">
                                                        <?php if($user->avatar!=''): ?>
                                                       <div class=" ">
                                                            <a href="#" class=" action-btn btn-danger  btn btn-sm  mb-1 d-inline-flex align-items-center bs-pass-para" data-confirm="<?php echo e(__('Are You Sure?')); ?>" data-text="<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="delete_avatar"><i class="ti ti-trash text-white"></i></a>

                                                            </div>
                                                        <?php endif; ?>
                                                        <div class="choose-file ">
                                                              <label for="avatar">
                                                                <div class=" bg-primary"> <i class="ti ti-upload px-1"></i><?php echo e(__('Choose file here')); ?></div>
                                                                <input type="file" class="form-control" name="avatar" id="avatar" data-filename="avatar-logo">
                                                            </label>
                                                            <p class="avatar-logo"></p>
                                                            <?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong><?php echo e($message); ?></strong>
                                                            </span>
                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        </div>
                                                    </div>
                                                    <small class=""><?php echo e(__('Please upload a valid image file. Size of image should not be more than 2MB.')); ?></small>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="name" class="form-label"><?php echo e(__('Full Name')); ?></label>
                                                        <input class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="name" type="text" id="fullname" placeholder="<?php echo e(__('Enter Your Name')); ?>" value="<?php echo e($user->name); ?>" required autocomplete="name">
                                                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong><?php echo e($message); ?></strong>
                                                        </span>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="email" class="form-label"><?php echo e(__('Email')); ?></label>
                                                        <input readonly class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="email" type="text" id="email" placeholder="<?php echo e(__('Enter Your Email Address')); ?>" value="<?php echo e($user->email); ?>" required autocomplete="email">
                                                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong><?php echo e($message); ?></strong>
                                                        </span>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-4">
                                                <div class=" row">
                                                  <div class="text-end">
                                                       <button type="submit" class="btn-submit btn btn-primary">
                                                            <?php echo e(__('Save Changes')); ?>

                                                        </button>
                                               <!--   <button class="btn btn-danger">Delete Account<i
                                                class="ti ti-chevron-right ms-1 ms-sm-2"></i></button> -->
                                                </div>

                                                    </div>
                                                </div> <!-- end col -->
                                            </div> <!-- end row -->
                                        </form>
                                         <?php if($user->avatar!=''): ?>
                                            <form action="<?php if(auth()->guard('web')->check()): ?><?php echo e(route('delete.avatar')); ?><?php elseif(auth()->guard()->check()): ?><?php echo e(route('client.delete.avatar')); ?><?php endif; ?>" method="post" id="delete_avatar">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                            </form>
                                        <?php endif; ?>
                                        <?php if(auth()->guard('web')->check()): ?>
                                        <div class="text-end">
                                            <a href="#" class="btn btn-danger delete_btn bs-pass-para mx-5" data-confirm="<?php echo e(__('Are You Sure?')); ?>" data-text="<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="delete-my-account">
                                                <?php echo e(__('Delete')); ?> <?php echo e(__('My Account')); ?><!-- <i
                                                class="ti ti-chevron-right ms-1 ms-sm-2"></i> -->
                                            </a>

                                            <form action="<?php echo e(route('delete.my.account')); ?>" method="post" id="delete-my-account">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                            </form>
                                        </div>
                                        <?php endif; ?>
                                    </div>





                                    <div class="card" id="v-pills-profile">
                                          <div class="card-header">
                                            <h5><?php echo e(__('Change Password')); ?></h5>
                                            </div>
                                     <div class="card-body">
                                        <form method="post" action="<?php if(auth()->guard('web')->check()): ?><?php echo e(route('update.password')); ?><?php elseif(auth()->guard()->check()): ?><?php echo e(route('client.update.password')); ?><?php endif; ?>">
                                            <?php echo csrf_field(); ?>

                                                <div class="col-lg-12">
                                                      <div class="row">
                                                    <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="old_password" class="form-label"><?php echo e(__('Old Password')); ?></label>
                                                        <input class="form-control <?php $__errorArgs = ['old_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="old_password" type="password" id="old_password"  autocomplete="old_password" placeholder="<?php echo e(__('Enter Old Password')); ?>">
                                                        <?php $__errorArgs = ['old_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <span class="invalid-feedback" role="alert">
                                                        <strong><?php echo e($message); ?></strong>
                                                    </span>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="password" class="form-label"><?php echo e(__('New Password')); ?></label>
                                                        <input class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password" type="password" required autocomplete="new-password" id="password" placeholder="<?php echo e(__('Enter Your Password')); ?>">
                                                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <span class="invalid-feedback" role="alert">
                                                        <strong><?php echo e($message); ?></strong>
                                                    </span>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="password_confirmation" class="form-label"><?php echo e(__('Confirm New Password')); ?></label>
                                                        <input class="form-control <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password_confirmation" type="password" required autocomplete="new-password" id="password_confirmation" placeholder="<?php echo e(__('Enter Your Password')); ?>">
                                                    </div>
                                                </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="text-end">
                                                    <button type="submit" class="btn-submit btn btn-primary "> <?php echo e(__('Change Password')); ?> </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    </div>


                                                 <?php if(auth()->guard('client')->check()): ?>
                                        <div class="card" id="v-pills-billing">

                                            <div class="card-header">
                                            <h5><?php echo e(__('Billing Details')); ?></h5>
                                            </div>
                                          <div class="card-body">
                                            <form method="post" action="<?php echo e(route('client.update.billing')); ?>">
                                                <?php echo csrf_field(); ?>
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label for="address" class="form-label"><?php echo e(__('Address')); ?></label>
                                                        <input class="form-control font-style" name="address" type="text" value="<?php echo e($user->address); ?>" id="address">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="city" class="form-label"><?php echo e(__('City')); ?></label>
                                                        <input class="form-control font-style" name="city" type="text" value="<?php echo e($user->city); ?>" id="city">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="state" class="form-label"><?php echo e(__('State')); ?></label>
                                                        <input class="form-control font-style" name="state" type="text" value="<?php echo e($user->state); ?>" id="state">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="zipcode" class="form-label"><?php echo e(__('Zip/Post Code')); ?></label>
                                                        <input class="form-control" name="zipcode" type="text" value="<?php echo e($user->zipcode); ?>" id="zipcode">
                                                    </div>
                                                    <div class="form-group  col-md-6">
                                                        <label for="country" class="form-label"><?php echo e(__('Country')); ?></label>
                                                        <input class="form-control font-style" name="country" type="text" value="<?php echo e($user->country); ?>" id="country">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="telephone" class="form-label"><?php echo e(__('Telephone')); ?></label>
                                                        <input class="form-control" name="telephone" type="text" value="<?php echo e($user->telephone); ?>" id="telephone">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="text-end">
                                                        <button type="submit" class="btn-submit btn btn-primary">
                                                            <?php echo e(__('Save Changes')); ?>

                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        </div>
                                    <?php endif; ?>
                               </div>
                          </div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>


                <script type="text/javascript">
                    $('#avatar').change(function(){

                    let reader = new FileReader();
                    reader.onload = (e) => {
                      $('#myAvatar').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(this.files[0]);

                   });
                   </script>
 <script>
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




       var scrollSpy = new bootstrap.ScrollSpy(document.body, {
        target: '#useradd-sidenav',
        offset: 300
    })


</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/heritage/PROJECTS/NEW/management/resources/views/users/account.blade.php ENDPATH**/ ?>