<?php $__env->startSection('page-title'); ?> <?php echo e(__('Clients')); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('links'); ?>
<?php if(\Auth::guard('client')->check()): ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('client.home')); ?>"><?php echo e(__('Home')); ?></a></li>
 <?php else: ?>
 <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
 <?php endif; ?>
<li class="breadcrumb-item"> <?php echo e(__('Clients')); ?></li>
 <?php $__env->stopSection(); ?>
<?php $__env->startSection('action-button'); ?>
    <?php if(auth()->guard('web')->check()): ?>
            <a href="<?php echo e(route('client.export' )); ?>" class="btn btn-sm btn-primary" data-toggle="tooltip" title="<?php echo e(__('Export')); ?>" >
                <i class="ti ti-file-x"></i>
            </a>
            <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-size="md" data-title="<?php echo e(__('Import Client')); ?>" data-url="<?php echo e(route('client.file.import',$currentWorkspace->slug)); ?>" data-toggle="tooltip" title="<?php echo e(__('Import ')); ?>" >
                <i class="ti ti-file-import"></i>
            </a>
             <?php if(isset($currentWorkspace) && $currentWorkspace->creater->id == Auth::id()): ?>
               <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="<?php echo e(__('Add Client')); ?>" data-url="<?php echo e(route('clients.create',$currentWorkspace->slug)); ?>" data-toggle="tooltip" title="<?php echo e(__('Add ')); ?>" >
                <i class="ti ti-plus"></i>
            </a>
           <?php endif; ?>



    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
       <?php if($currentWorkspace): ?>
                <div class="row">
                  <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-xl-3">

                        <div class="card   text-center">
                               <div class="card-header border-0 pb-0">
                                        <div class="card-header-right">
                                            <div class="btn-group card-option">
                                                <button type="button" class="btn dropdown-toggle"
                                                    data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="feather icon-more-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">

                                             <?php if($client->is_active): ?>
                                            <a href="#" class="dropdown-item" data-ajax-popup="true" data-size="md" data-title="<?php echo e(__('Reset Password')); ?>" data-url="<?php echo e(route('client.reset.password',[$currentWorkspace->slug,$client->id])); ?>"><i class="ti ti-pencil"></i> <span><?php echo e(__('Reset Password')); ?></span></a>

                                            <a href="#" class="dropdown-item" data-ajax-popup="true" data-size="md" data-title="<?php echo e(__('Edit Client')); ?>" data-url="<?php echo e(route('clients.edit',[$currentWorkspace->slug,$client->id])); ?>"><i class="ti ti-edit"></i><?php echo e(__('Edit')); ?></span></a>

                                            <a href="#" class="dropdown-item bs-pass-para text-danger"  data-confirm="<?php echo e(__('Are You Sure?')); ?>" data-text="<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="delete-form-<?php echo e($client->id); ?>" ><i class="ti ti-trash"></i> <span><?php echo e(__('Delete')); ?></span></a>

                                           <?php echo Form::open(['method' => 'DELETE', 'route' => ['clients.destroy',[$currentWorkspace->slug,$client->id]],'id'=>'delete-form-'.$client->id]); ?>

                                           <?php echo Form::close(); ?>


                                            <?php else: ?>
                                                <a href="#" class="dropdown-item" title="<?php echo e(__('Locked')); ?>">
                                                    <i class="fas fa-lock"></i>
                                                </a>
                                           <?php endif; ?>

                                                </div>
                                            </div>
                                        </div>

                               </div>
                            <div class="card-body">
                                <img alt="user-image"
                                    class="img-fluid rounded-circle"  <?php if($client->avatar): ?> src="<?php echo e(asset('/storage/avatars/'.$client->avatar)); ?>" <?php else: ?> avatar="<?php echo e($client->name); ?>" <?php endif; ?>>
                                <h4 class="mt-2"><?php echo e($client->name); ?></h4>
                                <small><?php echo e($client->email); ?></small>
                            </div>
                        </div>

                    </div>
                   <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



                                <div class="col-md-3">
                                 <?php if(auth()->guard('web')->check()): ?>
                                     <?php if(isset($currentWorkspace) && $currentWorkspace->creater->id == Auth::id()): ?>

                                <a href="#" class="btn-addnew-project"  data-ajax-popup="true" data-size="md" data-title="<?php echo e(__('Add Client')); ?>" data-url="<?php echo e(route('clients.create',$currentWorkspace->slug)); ?>">
                                    <div class="bg-primary proj-add-icon">
                                        <i class="ti ti-plus"></i>
                                    </div>
                                    <h6 class="mt-4 mb-2">New Client</h6>
                                    <p class="text-muted text-center">Click here to add New Client</p>
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

                        </div>
                    </div>

                <!-- [ sample-page ] end -->
            </div>
            <?php $__env->stopSection(); ?>


 <?php $__env->startPush('scripts'); ?>
<!--   <script>

    $(".delete-popup").click(function(){

    var id = $(this).data('id');

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {

  var id = $(this).data('id');
          $('#delete-form-'+id).submit();

         }




     })
});

 </script> -->
 <?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/heritage/SHINE/LATEST/projectmanagement/resources/views/clients/index.blade.php ENDPATH**/ ?>