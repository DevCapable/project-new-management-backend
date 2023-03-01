<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Invoices')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('links'); ?>
    <?php if(\Auth::guard('client')->check()): ?>
        <li class="breadcrumb-item"><a href="<?php echo e(route('client.home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <?php else: ?>
        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <?php endif; ?>
    <li class="breadcrumb-item"> <?php echo e(__('Invoice')); ?></li>
<?php $__env->stopSection(); ?>
<style type="text/css">
    .on_hover:hover {
        color: #fff;
    }
</style>
<?php $__env->startSection('action-button'); ?>
    <?php if(auth()->guard('web')->check()): ?>
        <?php if($currentWorkspace->creater->id == Auth::user()->id): ?>

            <a href="<?php echo e(route('invoice.export')); ?>" class="btn btn-sm btn-primary " data-toggle="tooltip"
                title="<?php echo e(__('Export')); ?>">
                <i class="ti ti-file-x"></i>
            </a>
             <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-size="lg"
                data-title="<?php echo e(__('Add Invoice')); ?>" data-toggle="tooltip" title="<?php echo e(__(' Add Invoice')); ?>"
                data-url="<?php echo e(route('invoices.create', $currentWorkspace->slug)); ?>">
                <i class="ti ti-plus"></i>
            </a>
        <?php endif; ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-centered table-hover mb-0 animated" id="selection-datatable">
                                <thead>
                                    <th><?php echo e(__('Invoice')); ?></th>
                                    <th><?php echo e(__('Name')); ?></th>
                                    <th><?php echo e(__('Issue Date')); ?></th>
                                    <th><?php echo e(__('Due Date')); ?></th>
                                    <th><?php echo e(__('Amount')); ?></th>
                                    <th><?php echo e(__('Status')); ?></th>
                                    <?php if(auth()->guard('web')->check()): ?>
                                        <th><?php echo e(__('Action')); ?></th>
                                    <?php endif; ?>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="Id sorting_1">
                                                <a href="<?php if(auth()->guard('web')->check()): ?> <?php echo e(route('invoices.show', [$currentWorkspace->slug, $invoice->id])); ?><?php elseif(auth()->guard()->check()): ?><?php echo e(route('client.invoices.show', [$currentWorkspace->slug, $invoice->id])); ?> <?php endif; ?>"
                                                    class="btn btn-outline-primary">
                                                    <?php echo e(App\Models\Utility::invoiceNumberFormat($invoice->id)); ?>

                                                </a>
                                            </td>
                                            <td><?php echo e($invoice->payment_type); ?></td>
                                            <td><?php echo e(App\Models\Utility::dateFormat($invoice->issue_date)); ?></td>
                                            <td><?php echo e(App\Models\Utility::dateFormat($invoice->due_date)); ?></td>
                                            <td>$ <?php echo e($invoice->amount); ?></td>
                                            <td>
                                                <?php if($invoice->status == 'sent'): ?>
                                                    <span
                                                        class="badge bg-warning p-2 px-3 rounded"><?php echo e(__('Sent')); ?></span>
                                                <?php elseif($invoice->status == 'paid'): ?>
                                                    <span
                                                        class="badge bg-success p-2 px-3 rounded"><?php echo e(__('Paid')); ?></span>
                                                <?php elseif($invoice->status == 'canceled'): ?>
                                                    <span
                                                        class="badge bg-danger p-2 px-3 rounded"><?php echo e(__('Canceled')); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <?php if(auth()->guard('web')->check()): ?>
                                                <td class="text-right">
                                                    <a href="<?php if(auth()->guard('web')->check()): ?><?php echo e(route('invoices.show',[$currentWorkspace->slug,$invoice->id])); ?><?php elseif(auth()->guard()->check()): ?><?php echo e(route('client.invoices.show',[$currentWorkspace->slug,$invoice->id])); ?><?php endif; ?>" class="action-btn btn-warning  btn btn-sm d-inline-flex align-items-center" data-toggle="tooltip" title="<?php echo e(__('Show')); ?>">
                                                 <i class="ti ti-eye"></i>
                                                </a>
                                                    <a href="#"
                                                        class="action-btn btn-info  btn btn-sm d-inline-flex align-items-center"
                                                        data-url="<?php echo e(route('invoices.edit', [$currentWorkspace->slug, $invoice->id])); ?>"
                                                        data-size="lg" data-toggle="tooltip"
                                                        title="<?php echo e(__('Edit Invoices')); ?>" data-ajax-popup="true"
                                                        data-title="<?php echo e(__('Edit Invoice')); ?>">
                                                        <i class="ti ti-pencil"></i>
                                                    </a>
                                                    <a href="#"
                                                        class="action-btn btn-danger  btn btn-sm d-inline-flex align-items-center  bs-pass-para"
                                                        data-confirm="<?php echo e(__('Are You Sure?')); ?>"
                                                        data-text="<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>"
                                                        data-confirm-yes="delete-form-<?php echo e($invoice->id); ?>"
                                                        data-toggle="tooltip" title="<?php echo e(__('Delete Invoices')); ?>">
                                                        <i class="ti ti-trash"></i>
                                                    </a>

                                                    <?php echo Form::open(['method' => 'DELETE', 'route' => ['invoices.destroy', [$currentWorkspace->slug, $invoice->id]], 'id' => 'delete-form-' . $invoice->id]); ?>

                                                    <?php echo Form::close(); ?>

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

    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/heritage/SHINE/LATEST/projectmanagement/resources/views/invoices/index.blade.php ENDPATH**/ ?>