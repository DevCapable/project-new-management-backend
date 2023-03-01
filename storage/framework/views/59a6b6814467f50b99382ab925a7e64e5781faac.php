<?php $__env->startSection('page-title'); ?>
<?php echo e(__('Zoom Meeting')); ?>

<?php $__env->stopSection(); ?>
 <?php $__env->startSection('links'); ?>
 <?php if(\Auth::guard('client')->check()): ?>   
<li class="breadcrumb-item"><a href="<?php echo e(route('client.home')); ?>"><?php echo e(__('Home')); ?></a></li>
 <?php else: ?>
 <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
 <?php endif; ?>
<li class="breadcrumb-item"> <?php echo e(__('Zoom Meeting')); ?></li>
 <?php $__env->stopSection(); ?>
<?php $__env->startPush('css-page'); ?>
<style>
 
.avatar-group .avatar{
    width: 2rem !important;
    height: 2rem !important;
}
.user-group img:hover {
    z-index: 5;
}
table .user-group img {
    position: relative;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    z-index: 2;
    transition: all 0.1s ease-in-out;
    border: 2px solid #ffffff;
}
</style>

<?php $__env->stopPush(); ?>


<?php $__env->startSection('action-button'); ?>
    <?php if(auth()->guard('web')->check()): ?>
        <?php if(isset($currentWorkspace) && $currentWorkspace->creater->id == Auth::id()): ?>
           
             <a href="<?php echo e(route('zoommeeting.Calender',$currentWorkspace->slug)); ?>" class="btn btn-sm btn-primary mx-1" id="" data-toggle="tooltip" title="<?php echo e(__('calendar')); ?>">  <i class="ti ti-calendar"></i></a>

              <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="lg" data-title="<?php echo e(__('Create New Meeting')); ?>" data-toggle="tooltip" title="<?php echo e(__('Add Meeting')); ?>" data-url="<?php echo e(route('zoom-meeting.create',$currentWorkspace->slug)); ?>">
                <i class="ti ti-plus "></i>
            </a>  

        <?php endif; ?>
    <?php endif; ?>

    <?php if(auth()->guard("client")->check()): ?>
    <a href="<?php echo e(route('zoommeetings.Calender',$currentWorkspace->slug)); ?>" data-toggle="tooltip" title="<?php echo e(__('calendar')); ?>" class="btn btn-sm btn-primary mx-1" id=""> <i class="ti ti-calendar"></i> </a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table  dataTable" id="selection-datatable">
                            <thead>
                            <tr>
                                <th> <?php echo e(__('Title')); ?> </th>
                                 
                                 
                                <th> <?php echo e(__('Project')); ?>  </th>
                                <th> <?php echo e(__('Members')); ?>  </th>
                                <?php if(Auth::user()->type == 'user'&& $currentWorkspace->creater->id == Auth::user()->id): ?>  
                                  <th> <?php echo e(__('Client')); ?>  </th>
                                <?php endif; ?>
                                <th> <?php echo e(__('Meeting Time')); ?> </th>
                                <th> <?php echo e(__('Duration')); ?> </th>
                                <th> <?php echo e(__('Join URL')); ?> </th>
                                <th> <?php echo e(__('Status')); ?> </th>
                                  <?php if(Auth::user()->type == 'user'&& $currentWorkspace->creater->id == Auth::user()->id): ?>  
                                <th class="text-right"> <?php echo e(__('Action')); ?></th>
                                <?php endif; ?>
                            </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $meetings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($item->title); ?></td>
                                    <td><?php echo e($item->project_name); ?></td>
                                    <td>
                                        <div class="user-group">
                                            <?php $__currentLoopData = $item->getMembers(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <a href="#" class="img_group" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo e($user->name); ?>">
                                                        <img alt="<?php echo e($user->name); ?>" <?php if($user->avatar): ?> src="<?php echo e(asset('/storage/avatars/'.$user->avatar)); ?>" <?php else: ?> avatar="<?php echo e($user->name); ?>" <?php endif; ?>>
                                                </a>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </td>
                                    <?php if(Auth::user()->type == 'user'&& $currentWorkspace->creater->id == Auth::user()->id): ?>  
                                    <td>
                                        <div class="avatar-group hover-avatar-ungroup mb-3">
                                            <?php $__currentLoopData = $item->getClients(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <a href="#" class="avatar rounded-circle avatar-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo e($user->name); ?>">
                                                        <img alt="<?php echo e($user->name); ?>" <?php if($user->avatar): ?> src="<?php echo e(asset('/storage/avatars/'.$user->avatar)); ?>" <?php else: ?> avatar="<?php echo e($user->name); ?>" <?php endif; ?> style="border-radius: 50%; max-hight: 40px; max-width: 30px;">
                                                </a>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </td>
                                    <?php endif; ?>
                                    <td><?php echo e($item->start_date); ?></td>
                                    <td><?php echo e($item->duration); ?> <?php echo e(__("Minutes")); ?></td>
                                
                                    <td>
                                        <?php if($item->created_by == \Auth::user()->id && $item->checkDateTime()): ?>
                                        <a href="<?php echo e($item->join_url); ?>" target="_blank"> <?php echo e(__('Start meeting')); ?> <i class="fas fa-external-link-square-alt "></i></a>
                                        <?php elseif($item->checkDateTime()): ?>
                                            <a href="<?php echo e($item->join_url); ?>" target="_blank"> <?php echo e(__('Join meeting')); ?> <i class="fas fa-external-link-square-alt "></i></a>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
            
                                    </td>
                                    <td>
                                        <?php if($item->checkDateTime()): ?>
                                            <?php if($item->status == 'waiting'): ?>
                                                <span class="badge badge-info p-2 px-3 rounded"><?php echo e(ucfirst($item->status)); ?></span>
                                            <?php else: ?>
                                                <span class="badge badge-success p-2 px-3 rounded"><?php echo e(ucfirst($item->status)); ?></span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="badge badge-danger p-2 px-3 rounded"><?php echo e(__("End")); ?></span>
                                        <?php endif; ?>
                                    </td>
                                      <?php if(Auth::user()->type == 'user'&& $currentWorkspace->creater->id == Auth::user()->id): ?>  
                                    <td class="text-right">
                                      
                                        <a href="#" class="action-btn btn-danger  btn btn-sm d-inline-flex align-items-center bs-pass-para" data-confirm="<?php echo e(__('Are You Sure?')); ?>" data-text="<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="delete-form-<?php echo e($item->id); ?>" data-toggle="tooltip" title="<?php echo e(__('Delete')); ?>">
                                            <i class="ti ti-trash"></i>
                                        </a>
                                        <form id="delete-form-<?php echo e($item->id); ?>" action="<?php echo e(route('zoom-meeting.destroy',[$currentWorkspace->slug,$item->id])); ?>" method="POST" style="display: none;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                        </form>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg ss_modale" role="document">
            <div class="modal-content image_sider_div">
            
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>


<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/heritage/SHINE/LATEST/projectmanagement/resources/views/zoom_meeting/index.blade.php ENDPATH**/ ?>