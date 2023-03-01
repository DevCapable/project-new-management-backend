 

 <?php $__env->startSection('page-title'); ?>
     <?php echo e(__('Users')); ?>

 <?php $__env->stopSection(); ?>
 <?php $__env->startSection('links'); ?>
     <?php if(\Auth::guard('client')->check()): ?>
         <li class="breadcrumb-item"><a href="<?php echo e(route('client.home')); ?>"><?php echo e(__('Home')); ?></a></li>
     <?php else: ?>
         <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
     <?php endif; ?>
     <li class="breadcrumb-item"> <?php echo e(__('users')); ?></li>
 <?php $__env->stopSection(); ?>
 <?php $__env->startSection('action-button'); ?>
     <?php if(auth()->guard('web')->check()): ?>
         <?php if(Auth::user()->type == 'admin'): ?>
             <a href="<?php echo e(route('user.export')); ?>" class="btn btn-sm btn-primary" data-toggle="tooltip"
                 title="<?php echo e(__('Export')); ?>">
                 <i class="ti ti-file-x"></i>
             </a>
             <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-size="md"
                 data-title="<?php echo e(__('Add User')); ?>" data-url="<?php echo e(route('user.file.import')); ?>" data-toggle="tooltip"
                 title="<?php echo e(__('Import')); ?>">
                 <i class="ti ti-file-import"></i>
             </a>
             <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="<?php echo e(__('Add User')); ?>"
                 data-url="<?php echo e(route('users.create')); ?>" data-toggle="tooltip" title="<?php echo e(__('Create')); ?>">
                 <i class="ti ti-plus"></i>
             </a>
         <?php elseif(isset($currentWorkspace) && $currentWorkspace->creater->id == Auth::id()): ?>
             <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="<?php echo e(__('Invite')); ?>"
                 data-url="<?php echo e(route('users.invite', $currentWorkspace->slug)); ?>" data-toggle="tooltip"
                 title="<?php echo e(__('Invite')); ?>">
                 <i class="ti ti-plus"></i>
             </a>
         <?php endif; ?>
     <?php endif; ?>
 <?php $__env->stopSection(); ?>

 <?php $__env->startSection('content'); ?>
     <?php if((isset($currentWorkspace) && $currentWorkspace) || Auth::user()->type == 'admin'): ?>
         <div class="row">
             <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                 <?php ($workspace_id = isset($currentWorkspace) && $currentWorkspace ? $currentWorkspace->id : ''); ?>
                 <div class="col-xl-3">
                     <div class="card   text-center">
                         <div class="card-header border-0 pb-0">
                             <div class="d-flex justify-content-between align-items-center">
                                 <h6 class="mb-0">
                                     <?php if(Auth::user()->type != 'admin'): ?>
                                         <?php if($user->permission == 'Owner'): ?>
                                             <div class="badge p-2 px-3 rounded bg-success"><?php echo e(__('Owner')); ?></div>
                                         <?php else: ?>
                                             <div class="badge p-2 px-3 rounded bg-warning"><?php echo e(__('Member')); ?></div>
                                         <?php endif; ?>
                                     <?php endif; ?>
                                 </h6>
                             </div>
                             <?php if(isset($currentWorkspace) && $currentWorkspace && $currentWorkspace->permission == 'Owner' && Auth::user()->id != $user->id): ?>
                                 <div class="card-header-right">
                                     <div class="btn-group card-option">
                                         <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                             aria-haspopup="true" aria-expanded="false">
                                             <i class="feather icon-more-vertical"></i>
                                         </button>
                                         <div class="dropdown-menu dropdown-menu-end">
                                             <?php if(isset($currentWorkspace) && $currentWorkspace && $currentWorkspace->permission == 'Owner' && Auth::user()->id != $user->id ): ?>
                                                 <a href="#" class="dropdown-item" data-ajax-popup="true" data-size="md"
                                                     data-title="<?php echo e(__('Edit')); ?>"
                                                     data-url="<?php echo e(route('users.edit', [$currentWorkspace->slug, $user->id])); ?>"><i
                                                         class="ti ti-edit"></i> <span><?php echo e(__('Edit')); ?></span></a>

                                                 <a href="#" class="dropdown-item" data-ajax-popup="true" data-size="md"
                                                     data-title="<?php echo e(__('Reset Password')); ?>"
                                                     data-url="<?php echo e(route('users.reset.password', $user->id)); ?>"><i
                                                         class="ti ti-pencil"></i>
                                                     <span><?php echo e(__('Reset Password')); ?></span></a>

                                                 <a href="#" class="dropdown-item text-danger bs-pass-para"
                                                     data-confirm="<?php echo e(__('Are You Sure?')); ?>"
                                                     data-text="<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>"
                                                     data-confirm-yes="remove_user_<?php echo e($user->id); ?>"><i
                                                         class="ti ti-trash"></i>
                                                     <span><?php echo e(__('Remove User From Workspace')); ?></span></a>
                                                 <form
                                                     action="<?php echo e(route('users.remove', [$currentWorkspace->slug, $user->id])); ?>"
                                                     method="post" id="remove_user_<?php echo e($user->id); ?>"
                                                     style="display: none;">
                                                     <?php echo csrf_field(); ?>
                                                     <?php echo method_field('DELETE'); ?>
                                                 </form>
                                             <?php endif; ?>

                                         </div>
                                     </div>
                                 </div>
                             <?php endif; ?>
                         </div>























                         <div class="card-body">
                             <div class="avatar">
                                 <img alt="user-image" class=" rounded-circle img_users_fix_size"
                                     <?php if($user->avatar): ?> src="<?php echo e(asset('/storage/avatars/' . $user->avatar)); ?>" <?php else: ?> avatar="<?php echo e($user->name); ?>" <?php endif; ?>>
                             </div>
                             <h4 class="mt-2"><?php echo e($user->name); ?></h4>
                             <small><?php echo e($user->email); ?></small>

                             <div class=" mb-0 mt-3">
                                 <div class=" p-3">
                                     <div class="row px-2">
                                         <?php if(Auth::user()->type == 'admin'): ?>
                                             <div class="col-6 text-start">

                                                 <h6 class="mb-0 px-3"><?php echo e($user->countWorkspace()); ?></h6>
                                                 <p class="text-muted text-sm mb-0"><?php echo e(__('Workspaces')); ?></p>
                                             </div>
                                             <div
                                                 class="col-6 <?php echo e(Auth::user()->type == 'admin' ? 'text-end' : 'text-start'); ?>  ">
                                                 <h6 class="mb-0 px-3"><?php echo e($user->countUsers($workspace_id)); ?></h6>
                                                 <p class="text-muted text-sm mb-0"><?php echo e(__('Users')); ?></p>
                                             </div>
                                             <div class="col-6 text-start mt-2">
                                                 <h6 class="mb-0 px-3"><?php echo e($user->countClients($workspace_id)); ?></h6>
                                                 <p class="text-muted text-sm mb-0"><?php echo e(__('Clients')); ?></p>
                                             </div>
                                         <?php endif; ?>

                                         <div
                                             class="col-6  <?php echo e(Auth::user()->type == 'admin' ? 'text-end mt-2' : 'text-start'); ?> ">
                                             <h6 class="mb-0 px-3"><?php echo e($user->countProject($workspace_id)); ?></h6>
                                             <p class="text-muted text-sm mb-0"><?php echo e(__('Projects')); ?></p>
                                         </div>
                                         <?php if(Auth::user()->type != 'admin'): ?>
                                             <div class="col-6 text-end">
                                                 <h6 class="mb-0 px-3"><?php echo e($user->countTask($workspace_id)); ?></h6>
                                                 <p class="text-muted text-sm mb-0"><?php echo e(__('Tasks')); ?></p>
                                             </div>
                                         <?php endif; ?>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



             <div class="col-md-3">
                 <?php if(auth()->guard('web')->check()): ?>
                     <?php if(Auth::user()->type == 'admin'): ?>
                         <a href="#" class="btn-addnew-project" data-ajax-popup="true" data-size="md"
                             data-title="<?php echo e(__('Add User')); ?>" data-url="<?php echo e(route('users.create')); ?>">
                             <div class="bg-primary proj-add-icon">
                                 <i class="ti ti-plus"></i>
                             </div>
                             <h6 class="mt-4 mb-2">New User</h6>
                             <p class="text-muted text-center">Click here to add New User</p>
                         </a>
                     <?php elseif(isset($currentWorkspace) && $currentWorkspace->creater->id == Auth::id()): ?>
                         <a href="#" class="btn-addnew-project" data-ajax-popup="true" data-size="md"
                             data-title="<?php echo e(__('Invite New User')); ?>"
                             data-url="<?php echo e(route('users.invite', $currentWorkspace->slug)); ?>">
                             <div class="bg-primary proj-add-icon">
                                 <i class="ti ti-plus"></i>
                             </div>
                             <h6 class="mt-4 mb-2">Invite New User</h6>
                             <p class="text-muted text-center">Click here to Invite New User</p>
                         </a>
                     <?php endif; ?>
                 <?php endif; ?>
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
                                     <p class="text-muted mt-3">
                                         <?php echo e(__("It's looking like you may have taken a wrong turn. Don't worry... it happens to the best of us. Here's a little tip that might help you get back on track.")); ?>

                                     </p>
                                     <div class="mt-3">
                                         <a class="btn-return-home badge-blue" href="<?php echo e(route('home')); ?>"><i
                                                 class="fas fa-reply"></i> <?php echo e(__('Return Home')); ?></a>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
     <?php endif; ?>

     </div>
     </div>

     <!-- [ sample-page ] end -->
     </div>
 <?php $__env->stopSection(); ?>

 <?php $__env->startPush('scripts'); ?>
     <script>
         //     $(".delete-popup").click(function(){

         //     var id = $(this).data('id');

         //     const swalWithBootstrapButtons = Swal.mixin({
         //         customClass: {
         //             confirmButton: 'btn btn-success',
         //             cancelButton: 'btn btn-danger'
         //         },
         //         buttonsStyling: false
         //     })
         //     swalWithBootstrapButtons.fire({
         //         title: 'Are you sure?',
         //         text: "You won't be able to revert this!",
         //         icon: 'warning',
         //         showCancelButton: true,
         //         confirmButtonText: 'Yes, delete it!',
         //         cancelButtonText: 'No, cancel!',
         //         reverseButtons: true
         //     }).then((result) => {
         //         if (result.isConfirmed) {

         //   var id = $(this).data('id');
         //           $('#remove_user_'+id).submit();

         //          }




         //      })
         // });





         $(".fc-daygrid-event fc-daygrid-block-event fc-h-event fc-event fc-event-draggable fc-event-resizable fc-event-end fc-event-past bg-danger border-danger")
             .click(function() {
                 alert("Handler for .click() called.");
             });
     </script>
 <?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/heritage/SHINE/LATEST/projectmanagement/resources/views/users/index.blade.php ENDPATH**/ ?>