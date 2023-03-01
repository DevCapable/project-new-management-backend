<?php $__env->startSection('page-title'); ?> <?php echo e(__('Invoices')); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('links'); ?>
<?php if(\Auth::guard('client')->check()): ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('client.home')); ?>"><?php echo e(__('Home')); ?></a></li>
 <?php else: ?>
 <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
 <?php endif; ?>
 <?php if(\Auth::guard('client')->check()): ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('client.invoices.index',$currentWorkspace->slug)); ?>"><?php echo e(__('Invoice')); ?></a></li>
 <?php else: ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('invoices.index',$currentWorkspace->slug)); ?>"><?php echo e(__('Invoice')); ?></a></li>
<?php endif; ?>
<li class="breadcrumb-item"><?php echo e(__('Invoice Detail')); ?></li>
 <?php $__env->stopSection(); ?>
   <?php $__env->startPush('scripts'); ?>
   <script>
         $('.cp_link').on('click', function () {
            console.log("hii");
            var value = $(this).attr('data-link');
            var $temp = $("<input>");

            $("body").append($temp);
            $temp.val(value).select();
            document.execCommand("copy");
            $temp.remove();
            show_toastr('Success', '<?php echo e(__('Link Copy on Clipboard')); ?>', 'success')
        });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('action-button'); ?>
<?php if(auth()->guard('client')->check()): ?>

<?php if($currentWorkspace->is_stripe_enabled == 1 || $currentWorkspace->is_paypal_enabled == 1  || (isset($paymentSetting['is_paypal_enabled']) && $paymentSetting['is_paypal_enabled'] == 'on') || (isset($paymentSetting['is_paystack_enabled']) && $paymentSetting['is_paystack_enabled'] == 'on') || (isset($paymentSetting['is_flutterwave_enabled']) && $paymentSetting['is_flutterwave_enabled'] == 'on') || (isset($paymentSetting['is_razorpay_enabled']) &&
$paymentSetting['is_razorpay_enabled'] == 'on') || (isset($paymentSetting['is_mercado_enabled']) && $paymentSetting['is_mercado_enabled'] == 'on') || (isset($paymentSetting['is_paytm_enabled']) && $paymentSetting['is_paytm_enabled'] == 'on') || (isset($paymentSetting['is_mollie_enabled']) && $paymentSetting['is_mollie_enabled'] == 'on') || (isset($paymentSetting['is_skrill_enabled']) && $paymentSetting['is_skrill_enabled'] == 'on') || (isset($paymentSetting['is_coingate_enabled']) && $paymentSetting['is_coingate_enabled'] == 'on') || (isset($paymentSetting['is_paymentwall_enabled']) && $paymentSetting['is_paymentwall_enabled'] == 'on')): ?>
       -->
                 <a href="#" data-toggle="modal" data-bs-toggle="tooltip" data-bs-original-title="<?php echo e(__('Pay Now')); ?>" data-size="lg" data-target="#paymentModal" class="btn btn-sm mx-1   btn-primary">
                 <i class="ti ti-doller px-1"> $ </i>
                </a>


 <!--        <?php endif; ?>
   <?php endif; ?>
    <?php if(auth()->guard('web')->check()): ?>

        <?php if($currentWorkspace->creater->id == Auth::user()->id): ?>
                <a href="#" class="btn btn-sm mx-1  btn-primary" data-size="lg" data-bs-toggle="tooltip" data-bs-original-title="<?php echo e(__('Edit')); ?>"  data-ajax-popup="true" data-title="<?php echo e(__('Edit Invoice')); ?>" data-url="<?php echo e(route('invoices.edit',[$currentWorkspace->slug,$invoice->id])); ?>">
                <i class="ti ti-edit"></i>
                </a>
                  <a href="#" class="btn btn-sm  mx-1 btn-primary cp_link " data-bs-toggle="tooltip" data-bs-original-title="<?php echo e(__('Copy')); ?>" data-link="<?php echo e(route('pay.invoice',[$currentWorkspace->slug,\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)])); ?>" data-toggle="tooltip" data-original-title="<?php echo e(__('Click to copy invoice link')); ?>"><span class="btn-inner--icon text-white"></span><span class="btn-inner--text text-white"><i class="ti ti-copy"></i></span></a>

        <?php endif; ?>
    <?php endif; ?>
            <a href="<?php if(auth()->guard('web')->check()): ?><?php echo e(route('invoice.print',[$currentWorkspace->slug,\Illuminate\Support\Facades\Crypt::encryptString($invoice->id)])); ?><?php elseif(auth()->guard()->check()): ?><?php echo e(route('client.invoice.print',[$currentWorkspace->slug,\Illuminate\Support\Facades\Crypt::encryptString($invoice->id)])); ?><?php endif; ?>"  data-bs-toggle="tooltip" data-bs-original-title="<?php echo e(__('Print')); ?>" class="btn btn-sm btn-primary">
           <i class="ti ti-printer text-white"></i>
            </a>
           <?php if(auth()->guard('web')->check()): ?>
            <?php if($currentWorkspace->creater->id == Auth::user()->id): ?>

                 <a href="#" data-toggle="modal" data-target="#addPaymentModal" data-bs-toggle="tooltip" data-bs-original-title="<?php echo e(__('Add Payment')); ?>" class="btn btn-sm  mx-1  btn-primary" type="button">
                     <i class="ti ti-plus"></i>
                </a>
                 <?php endif; ?>

                 <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
                 <div class="row">
                 <div class="card" id="printTable">
            <div class="card-header" >
            <h5 class="" style=" left: -12px !important;"><?php echo e(App\Models\Utility::invoiceNumberFormat($invoice->invoice_id)); ?></h5>
          </div>
            <div class="card-body">
                <div class="row ">

                    <div class="col-md-4 ">
                        <div class="invoice-contact">
                        <div class="invoice-box row">
                            <div class="col-sm-12">
                                <h6><?php echo e(__('From')); ?>:</h6>
                                <?php if($currentWorkspace->company): ?>
                                    <h6 class="m-0"><?php echo e($currentWorkspace->company); ?></h6>
                                <?php endif; ?>

                                <?php if($currentWorkspace->address): ?>
                                    <?php echo e($currentWorkspace->address); ?>,
                                    <br>
                                <?php endif; ?>

                                <?php if($currentWorkspace->city): ?>
                                    <?php echo e($currentWorkspace->city); ?>,
                                <?php endif; ?>
                                <?php if($currentWorkspace->state): ?>
                                    <?php echo e($currentWorkspace->state); ?>,
                                <?php endif; ?>

                                <?php if($currentWorkspace->zipcode): ?>
                                    -<?php echo e($currentWorkspace->zipcode); ?>,<br>
                                <?php endif; ?>
                                <?php if($currentWorkspace->country): ?>
                                    <?php echo e($currentWorkspace->country); ?>,<br>
                                <?php endif; ?>
                                <?php if($currentWorkspace->telephone): ?>
                                    <?php echo e($currentWorkspace->telephone); ?>

                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    </div>


                       <div class="col-md-4  invoice-client-info">
                         <div class="invoice-contact">
                        <div class="invoice-box row">
                            <div class="col-sm-12">
                        <h6><?php echo e(__('To')); ?>:</h6>
                           <?php if($invoice->client): ?>
                                    <h6 class="m-0"><?php echo e($invoice->client->name); ?></h6>

                                    <?php echo e($invoice->client->email); ?><br>

                                <?php if($invoice->client): ?>

                                 <?php if($invoice->client->address): ?>
                                   <?php echo e($invoice->client->address); ?>,
                                    <br>
                                <?php endif; ?>

                                <?php if($invoice->client->city): ?>
                                   <?php echo e($invoice->client->city); ?>,
                                <?php endif; ?>
                                <?php if($invoice->client->state): ?>
                                    <?php echo e($invoice->client->state); ?>,
                                <?php endif; ?>

                               <?php if($invoice->client->zipcode): ?>
                                    -<?php echo e($invoice->client->zipcode); ?>,<br>
                                <?php endif; ?>
                               <?php if($invoice->client->country): ?>
                                    <?php echo e($invoice->client->country); ?>,<br>
                                <?php endif; ?>

                                 <?php if($invoice->client->telephone): ?>
                                    <?php echo e($invoice->client->telephone); ?>


                                <?php endif; ?>

                                 <?php endif; ?>
                                 <?php endif; ?>
                        </div>
                    </div>
                    </div>
                </div>

                 <div class="col-md-3  invoice-client-info">
                    <div class="invoice-contact">

                            <div class="col-sm-12">
                        <h6 class="pb-4" >Description :</h6>
                        <table class="table table-responsive invoice-table invoice-order table-borderless" >
                            <tbody style="padding-bottom: 0px  !important; font-size: 15px !important;">
                                <tr >

                                    <td style="padding-bottom: 0px  !important; font-size: 15px !important;"> <b><?php echo e(__('Project')); ?></b>  : <?php echo e($invoice->payment_type); ?></td>
                                </tr>
                                <tr>

                                    <td style="padding-bottom: 0px  !important; font-size: 15px !important;"><b><?php echo e(__('Issue Date')); ?></b> :<?php echo e(App\Models\Utility::dateFormat($invoice->issue_date)); ?></td>
                                </tr>
                                <tr>

                                    <?php if($invoice->status == 'sent'): ?>
                                        <td style="padding-bottom: 0px  !important; font-size: 15px !important;">
                                            <b><?php echo e(__('Status')); ?> :</b>
                                            <span class="p-2 px-3 rounded badge bg-warning"><?php echo e(__('Sent')); ?></span>
                                        </td>
                                    <?php elseif($invoice->status == 'paid'): ?>
                                        <td style="padding-bottom: 0px  !important; font-size: 15px !important;">
                                            <b><?php echo e(__('Status')); ?> :</b>
                                            <span class="p-2 px-3 rounded badge bg-success"><?php echo e(__('Paid')); ?></span>
                                        </td>
                                    <?php elseif($invoice->status == 'canceled'): ?>
                                        <td style="padding-bottom: 0px  !important; font-size: 15px !important;">
                                             <b><?php echo e(__('Status')); ?> :</b>
                                            <span class="p-2 px-3 rounded badge bg-danger"><?php echo e(__('Canceled')); ?></span>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                                <tr>

                                    <td style="padding-bottom: 0px  !important; font-size: 15px !important;">
                                       <b> <?php echo e(__('Due Date')); ?>:</b>
                                        <?php echo e(App\Models\Utility::dateFormat($invoice->due_date)); ?>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                </div>

                    <div class="col-md-1  qr_code">
                        <div class="text-end" style="margin: 0px 0px 0px -30px;">
                            <?php echo DNS2D::getBarcodeHTML(route('pay.invoice', [$currentWorkspace->slug, \Illuminate\Support\Facades\Crypt::encrypt($invoice->id)]), 'QRCODE', 2, 2); ?>

                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="justify-content-between align-items-center d-flex">
                        <h5 class="px-2 py-2"><b><?php echo e(__('Order Summary')); ?></b></h5>
                            <?php if(auth()->guard('web')->check()): ?>
                        <?php if($currentWorkspace->creater->id == Auth::user()->id): ?>
                        <a href="#" data-ajax-popup="true"  data-bs-toggle="tooltip" data-bs-original-title="<?php echo e(__('Add Item')); ?>"  data-title="<?php echo e(__('Add Item')); ?>" data-url="<?php echo e(route('invoice.item.create',[$currentWorkspace->slug,$invoice->id])); ?>" class="btn btn-sm  btn-primary " type="button">
                        <i class="ti ti-plus"></i>
                         </a>
                         <?php endif; ?>
                         <?php endif; ?>
                         </div>
                        <div class="table-responsive mt-3">
                            <table class="table invoice-detail-table">
                                <thead>
                                    <tr class="thead-default">
                                        <th>#</th>
                                        <th><?php echo e(__('Item')); ?></th>
                                        <th><?php echo e(__('Totals')); ?></th>
                                        <?php if(auth()->guard('web')->check()): ?>
                                            <th><?php echo e(__('Action')); ?></th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $invoice->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>

                                            <td><?php echo e($key + 1); ?></td>
                                            <td><?php echo e($item->task ? $item->task->title : ''); ?>-
                                                <b><?php echo e($item->task ? $item->task->project->name : ''); ?></b></td>
                                            <td><?php echo e($currentWorkspace->priceFormat($item->price * $item->qty)); ?></td>
                                            <?php if(auth()->guard('web')->check()): ?>
                                                <td class="text-right">
                                                    <a href="#"
                                                        class="action-btn btn-danger  btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                        data-confirm="<?php echo e(__('Are You Sure?')); ?>"
                                                        data-text="<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>"
                                                        data-confirm-yes="delete-form-<?php echo e($item->id); ?>"
                                                        data-toggle="tooltip" title="<?php echo e(__('Delete')); ?>">
                                                        <i class="ti ti-trash"></i>
                                                    </a>

                                                    <form id="delete-form-<?php echo e($item->id); ?>"
                                                        action="<?php echo e(route('invoice.item.destroy', [$currentWorkspace->slug, $invoice->id, $item->id])); ?>"
                                                        method="POST" style="display: none;">
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
                <div class="row mt-2">
                    <div class="col-sm-12">
                        <div class="invoice-total">
                            <table class="table table-responsive invoice-table ">
                                <tbody>
                                    <tr>
                                        <th><?php echo e(__('Subtotal')); ?> :</th>
                                    </tr>
                                    <?php if($invoice->discount): ?>
                                        <tr>
                                            <th><?php echo e(__('Discount')); ?> :</th>
                                            <td><?php echo e($currentWorkspace->priceFormat($invoice->discount)); ?></td>
                                        </tr>
                                    <?php endif; ?>

                                    <?php if($invoice->tax): ?>
                                        <tr>
                                            <th><?php echo e(__('Tax')); ?> <?php echo e($invoice->tax->name); ?>

                                                (<?php echo e($invoice->tax->rate); ?>%):</th>
                                            <td><?php echo e($currentWorkspace->priceFormat($invoice->getTaxTotal())); ?></td>
                                        </tr>
                                    <?php endif; ?>

                                     <tr>
                                            <th class="text-primary m-r-10 "><?php echo e(__('Total')); ?> : </th>
                                            <td class="text-primary m-r-10 px-2"> <?php echo e($currentWorkspace->priceFormat($invoice->getTotal())); ?></td>
                                        </tr>
                                     <tr>
                                            <th class="text-primary m-r-10 "><?php echo e(__('Due Amount')); ?> : </th>

                                        </tr>


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php if($payments = $invoice->payments): ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <h5 class="px-2 py-2"><b><?php echo e(__('Payments')); ?></b></h5>
                            <div class="table-responsive mt-3">
                                <table class="table invoice-detail-table">
                                    <thead>
                                        <tr class="thead-default">
                                            <th>#</th>
                                            <th><?php echo e(__('Id')); ?></th>
                                            <th><?php echo e(__('Amount')); ?></th>
                                            <th><?php echo e(__('Currency')); ?></th>
                                            <th><?php echo e(__('Status')); ?></th>
                                            <th><?php echo e(__('Payment Type')); ?></th>
                                            <th><?php echo e(__('Date')); ?></th>
                                            <th><?php echo e(__('Receipt')); ?></th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($key + 1); ?></td>
                                                <td><?php echo e($payment->order_id); ?></td>
                                                <td><?php echo e($currentWorkspace->priceFormat($payment->amount)); ?></td>
                                                <td><?php echo e(strtoupper($payment->currency)); ?></td>
                                                <td>
                                                    <?php if($payment->payment_status == 'succeeded' || $payment->payment_status == 'approved'): ?>
                                                        <i class="fas fa-circle text-success"></i>
                                                        <?php echo e(__(ucfirst($payment->payment_status))); ?>

                                                    <?php else: ?>
                                                        <i class="fas fa-circle text-danger"></i>
                                                        <?php echo e(__(ucfirst($payment->payment_status))); ?>

                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo e(__($payment->payment_type)); ?></td>
                                                <td><?php echo e(App\Models\Utility::dateFormat($payment->created_at)); ?></td>
                                                <td>
                                                    <?php if(!empty($payment->receipt)): ?>
                                                        <a href="<?php echo e($payment->receipt); ?>" target="_blank"
                                                            class="btn-submit"><i class="ti ti-printer"></i>
                                                            <?php echo e(__('Receipt')); ?></a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>

            <!-- [ Invoice ] end -->
        </div>


    <?php if(auth('web')): ?>
        <!-- Modal -->
        <div class="modal fade" id="addPaymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                              <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel"> <?php echo e(__('Add Manual Payment')); ?></h5>
                                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                    </div>

                    <div class="modal-body">

                            <form method="post" action="<?php echo e(route('manual.invoice.payment',[$currentWorkspace->slug,$invoice->id])); ?>" class="require-validation">
                                <?php echo csrf_field(); ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="amount" class="col-form-label"><?php echo e(__('Amount')); ?></label>
                                        <div class="form-icon-user">
                                            <span class="currency-icon bg-primary"><?php echo e((!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'); ?></span>
                                            <input class="form-control currency_input" type="number" id="amount" name="amount" value="" min="0" step="0.01" max="" placeholder="<?php echo e(__('Enter Monthly Price')); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-12 modal-footer">
                                   <button type="button" class="btn  btn-light" data-dismiss="modal"><?php echo e(__('Close')); ?></button>
                                    <input type="submit" value="<?php echo e(__('Make Payment')); ?>" class="btn  btn-primary">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if(auth()->guard('client')->check()): ?>

            <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel"> <?php echo e(__('Add Payment')); ?></h5>
                                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                    </div>
                        <div class="modal-body">
                            <div class="card-box">
                                <?php if((isset($currentWorkspace->is_stripe_enabled) && $currentWorkspace->is_stripe_enabled == 1) || (isset($currentWorkspace->is_paypal_enabled) && $currentWorkspace->is_paypal_enabled == 1) || (isset($paymentSetting['is_paystack_enabled']) && $paymentSetting['is_paystack_enabled'] == 'on') || (isset($paymentSetting['is_flutterwave_enabled']) && $paymentSetting['is_flutterwave_enabled'] == 'on') || (isset($paymentSetting['is_razorpay_enabled']) &&
                                 $paymentSetting['is_razorpay_enabled'] == 'on') || (isset($paymentSetting['is_mercado_enabled']) && $paymentSetting['is_mercado_enabled'] == 'on') || (isset($paymentSetting['is_paytm_enabled']) && $paymentSetting['is_paytm_enabled'] == 'on') || (isset($paymentSetting['is_mollie_enabled']) && $paymentSetting['is_mollie_enabled'] == 'on') || (isset($paymentSetting['is_skrill_enabled']) && $paymentSetting['is_skrill_enabled'] == 'on') || (isset($paymentSetting['is_coingate_enabled']) && $paymentSetting['is_coingate_enabled'] == 'on')): ?>
                                    <ul class="nav nav-tabs bordar_styless py-3">
                                        <?php if($currentWorkspace->is_stripe_enabled == 1): ?>
                                            <li>
                                                <a data-toggle="tab" href="#stripe-payment" class="active"><?php echo e(__('Stripe')); ?></a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if($currentWorkspace->is_paypal_enabled == 1): ?>
                                            <li>
                                                <a data-toggle="tab" href="#paypal-payment" class=""><?php echo e(__('Paypal')); ?> </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if(isset($paymentSetting['is_paystack_enabled']) && $paymentSetting['is_paystack_enabled'] == 'on'): ?>
                                            <li>
                                                <a data-toggle="tab" href="#paystack-payment" role="tab" aria-controls="paystack" aria-selected="false"><?php echo e(__('Paystack')); ?></a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if(isset($paymentSetting['is_flutterwave_enabled']) && $paymentSetting['is_flutterwave_enabled'] == 'on'): ?>
                                            <li>
                                                <a data-toggle="tab" href="#flutterwave-payment" role="tab" aria-controls="flutterwave" aria-selected="false"><?php echo e(__('Flutterwave')); ?></a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if(isset($paymentSetting['is_razorpay_enabled']) && $paymentSetting['is_razorpay_enabled'] == 'on'): ?>
                                            <li>
                                                <a data-toggle="tab" href="#razorpay-payment" role="tab" aria-controls="razorpay" aria-selected="false"><?php echo e(__('Razorpay')); ?></a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if(isset($paymentSetting['is_mercado_enabled']) && $paymentSetting['is_mercado_enabled'] == 'on'): ?>
                                            <li>
                                                <a data-toggle="tab" href="#mercado-payment" role="tab" aria-controls="mercado" aria-selected="false"><?php echo e(__('Mercado Pago')); ?></a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if(isset($paymentSetting['is_paytm_enabled']) && $paymentSetting['is_paytm_enabled'] == 'on'): ?>
                                            <li>
                                                <a data-toggle="tab" href="#paytm-payment" role="tab" aria-controls="paytm" aria-selected="false"><?php echo e(__('Paytm')); ?></a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if(isset($paymentSetting['is_mollie_enabled']) && $paymentSetting['is_mollie_enabled'] == 'on'): ?>
                                            <li class="pt-3">
                                                <a data-toggle="tab"  href="#mollie-payment" role="tab" aria-controls="mollie" aria-selected="false"><?php echo e(__('Mollie')); ?></a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if(isset($paymentSetting['is_skrill_enabled']) && $paymentSetting['is_skrill_enabled'] == 'on'): ?>
                                            <li class="pt-3">
                                                <a data-toggle="tab" href="#skrill-payment" role="tab" aria-controls="skrill" aria-selected="false"><?php echo e(__('Skrill')); ?></a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if(isset($paymentSetting['is_coingate_enabled']) && $paymentSetting['is_coingate_enabled'] == 'on'): ?>
                                            <li class="pt-3">
                                                <a data-toggle="tab" href="#coingate-payment" role="tab" aria-controls="coingate" aria-selected="false"><?php echo e(__('CoinGate')); ?></a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if(isset($paymentSetting['is_paymentwall_enabled']) && $paymentSetting['is_paymentwall_enabled'] == 'on'): ?>
                                            <li class="pt-3">
                                                <a data-toggle="tab" href="#paymentwall-payment" role="tab" aria-controls="coingate" aria-selected="false"><?php echo e(__('Paymentwall')); ?></a>
                                            </li>
                                        <?php endif; ?>

                                    </ul>
                                <?php endif; ?>

                                <div class="tab-content mt-3">
                                    <?php if($currentWorkspace->is_stripe_enabled == 1): ?>
                                        <div class="tab-pane fade <?php echo e((($currentWorkspace->is_stripe_enabled == 1 && $currentWorkspace->is_paypal_enabled == 1) || $currentWorkspace->is_stripe_enabled == 1) ? "show active" : ""); ?>" id="stripe-payment" role="tabpanel" aria-labelledby="stripe-payment">
                                            <form method="post" action="<?php echo e(route('client.invoice.payment',[$currentWorkspace->slug,$invoice->id])); ?>" class="require-validation" id="payment-form">
                                                <?php echo csrf_field(); ?>
                                                <div class="row">
                                                    <div class="col-sm-8">
                                                        <div class="custom-radio">
                                                            <label class="font-16 col-form-label"><?php echo e(__('Credit / Debit Card')); ?></label>
                                                        </div>
                                                        <p class="mb-0 pt-1 text-sm"><?php echo e(__('Safe money transfer using your bank account. We support Mastercard, Visa, Discover and American express.')); ?></p>
                                                    </div>
                                                    <div class="col-sm-4 text-sm-right mt-3 mt-sm-0">
                                                        <img src="<?php echo e(asset('assets/img/payments/master.png')); ?>" height="24" alt="master-card-img">
                                                        <img src="<?php echo e(asset('assets/img/payments/discover.png')); ?>" height="24" alt="discover-card-img">
                                                        <img src="<?php echo e(asset('assets/img/payments/visa.png')); ?>" height="24" alt="visa-card-img">
                                                        <img src="<?php echo e(asset('assets/img/payments/american express.png')); ?>" height="24" alt="american-express-card-img">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="card-name-on" class="col-form-label"><?php echo e(__('Name on card')); ?></label>
                                                            <input type="text" name="name" id="card-name-on" class="form-control required" placeholder="<?php echo e(\Auth::user()->name); ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div id="card-element">
                                                        </div>
                                                        <div id="card-errors" role="alert"></div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label for="amount" class="col-form-label"><?php echo e(__('Amount')); ?></label>
                                                        <div class="form-icon-user">
                                                            <span class="currency-icon bg-primary"><?php echo e((!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'); ?></span>
                                                            <input class="form-control currency_input" required="required" min="0" name="amount" type="number" value="" min="0" step="0.01" max="" id="amount">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="error" style="display: none;">
                                                            <div class='alert-danger alert'><?php echo e(__('Please correct the errors and try again.')); ?></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 modal-footer">
                                                        <input type="submit" class="btn btn-primary" value="<?php echo e(__('Make Payment')); ?>">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                    <?php if($currentWorkspace->is_paypal_enabled == 1): ?>
                                        <div class="tab-pane fade <?php echo e(($currentWorkspace->is_stripe_enabled == 0 && $currentWorkspace->is_paypal_enabled == 1) ? "show active" : ""); ?>" id="paypal-payment" role="tabpanel" aria-labelledby="paypal-payment">
                                            <form class="w3-container w3-display-middle w3-card-4 " method="POST" id="payment-form" action="<?php echo e(route('client.pay.with.paypal', [$currentWorkspace->slug, $invoice->id])); ?>">
                                                <?php echo csrf_field(); ?>
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label for="amount" class="col-form-label"><?php echo e(__('Amount')); ?></label>
                                                        <div class="form-icon-user">
                                                            <span class="currency-icon bg-primary"><?php echo e((!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'); ?></span>
                                                            <input class="form-control currency_input" required="required" min="0" name="amount" type="number" value="" min="0" step="0.01" max="" id="amount">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 modal-footer">
                                                    <input type="submit" class="btn btn-primary" value="<?php echo e(__('Make Payment')); ?>">
                                                </div>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                    <?php if($paymentSetting['is_paystack_enabled'] == 'on'): ?>
                                        <div class="tab-pane fade" id="paystack-payment" role="tabpanel" aria-labelledby="paystack-payment">
                                            <form method="post" action="<?php echo e(route('client.invoice.pay.with.paystack',[$currentWorkspace->slug, $invoice->id])); ?>" class="require-validation" id="paystack-payment-form">
                                                <?php echo csrf_field(); ?>
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label for="amount" class="col-form-label"><?php echo e(__('Amount')); ?></label>
                                                        <div class="form-icon-user">
                                                            <span class="currency-icon bg-primary"><?php echo e((!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'); ?></span>
                                                            <input class="form-control currency_input" required="required" min="0" name="amount" type="number" value="" min="0" step="0.01" max="" id="amount">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 modal-footer">
                                                    <button class="btn btn-primary" type="button" id="pay_with_paystack"><?php echo e(__('Make Payment')); ?></button>
                                                </div>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                    <?php if(isset($paymentSetting['is_flutterwave_enabled']) && $paymentSetting['is_flutterwave_enabled'] == 'on'): ?>
                                        <div class="tab-pane fade" id="flutterwave-payment" role="tabpanel" aria-labelledby="flutterwave-payment">
                                            <form method="post" action="<?php echo e(route('client.invoice.pay.with.flaterwave',[$currentWorkspace->slug, $invoice->id])); ?>" class="require-validation" id="flaterwave-payment-form">
                                                <?php echo csrf_field(); ?>
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label class="col-form-label" for="amount"><?php echo e(__('Amount')); ?></label>
                                                        <div class="form-icon-user">
                                                            <span class="currency-icon bg-primary"><?php echo e((!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'); ?></span>
                                                            <input class="form-control currency_input" required="required" min="0" name="amount" type="number" value="" min="0" step="0.01" max="" id="amount">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 modal-footer">
                                                    <button class="btn btn-primary" type="button" id="pay_with_flaterwave"><?php echo e(__('Make Payment')); ?></button>
                                                </div>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                    <?php if(isset($paymentSetting['is_razorpay_enabled']) && $paymentSetting['is_razorpay_enabled'] == 'on'): ?>
                                        <div class="tab-pane fade" id="razorpay-payment" role="tabpanel" aria-labelledby="razorpay-payment">
                                            <form method="post" action="<?php echo e(route('client.invoice.pay.with.razorpay',[$currentWorkspace->slug, $invoice->id])); ?>" class="require-validation" id="razorpay-payment-form">
                                                <?php echo csrf_field(); ?>
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label class="col-form-label" for="amount"><?php echo e(__('Amount')); ?></label>
                                                        <div class="form-icon-user">
                                                            <span class="currency-icon bg-primary"><?php echo e((!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'); ?></span>
                                                            <input class="form-control currency_input" required="required" min="0" name="amount" type="number" value="" min="0" step="0.01" max="" id="amount">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 modal-footer">
                                                    <button class="btn btn-primary" type="button" id="pay_with_razerpay"><?php echo e(__('Make Payment')); ?></button>
                                                </div>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                    <?php if(isset($paymentSetting['is_mercado_enabled']) && $paymentSetting['is_mercado_enabled'] == 'on'): ?>
                                        <div class="tab-pane fade" id="mercado-payment" role="tabpanel" aria-labelledby="mercado-payment">
                                            <form method="post" action="<?php echo e(route('client.invoice.pay.with.mercado',[$currentWorkspace->slug, $invoice->id])); ?>" class="require-validation" id="mercado-form">
                                                <?php echo csrf_field(); ?>
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label class="col-form-label" for="amount"><?php echo e(__('Amount')); ?></label>
                                                        <div class="form-icon-user">
                                                            <span class="currency-icon bg-primary"><?php echo e((!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'); ?></span>
                                                            <input class="form-control currency_input" required="required" min="0" name="amount" type="number" value="" min="0" step="0.01" max="" id="amount">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 modal-footer">
                                                    <button class="btn btn-primary" type="submit"><?php echo e(__('Make Payment')); ?></button>
                                                </div>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                    <?php if(isset($paymentSetting['is_paytm_enabled']) && $paymentSetting['is_paytm_enabled'] == 'on'): ?>
                                        <div class="tab-pane fade" id="paytm-payment" role="tabpanel" aria-labelledby="paytm-payment">
                                            <form method="post" action="<?php echo e(route('client.invoice.pay.with.paytm',[$currentWorkspace->slug, $invoice->id])); ?>" class="require-validation" id="paytm-form">
                                                <?php echo csrf_field(); ?>
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label class="col-form-label" for="amount"><?php echo e(__('Amount')); ?></label>
                                                        <div class="form-icon-user">
                                                            <span class="currency-icon bg-primary"><?php echo e((!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'); ?></span>
                                                            <input class="form-control currency_input" required="required" min="0" name="amount" type="number" value="" min="0" step="0.01" max="" id="amount">
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="mobile" class="col-form-label text-dark"><?php echo e(__('Mobile Number')); ?></label>
                                                            <input type="text" id="mobile" name="mobile" class="form-control mobile" data-from="mobile" placeholder="Enter Mobile Number" required="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 modal-footer">
                                                    <button class="btn btn-primary" type="submit"><?php echo e(__('Make Payment')); ?></button>
                                                </div>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                    <?php if(isset($paymentSetting['is_mollie_enabled']) && $paymentSetting['is_mollie_enabled'] == 'on'): ?>
                                        <div class="tab-pane fade" id="mollie-payment" role="tabpanel" aria-labelledby="mollie-payment">
                                            <form method="post" action="<?php echo e(route('client.invoice.pay.with.mollie',[$currentWorkspace->slug, $invoice->id])); ?>" class="require-validation" id="mollie-form">
                                                <?php echo csrf_field(); ?>
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label class="col-form-label" for="amount"><?php echo e(__('Amount')); ?></label>
                                                        <div class="form-icon-user">
                                                            <span class="currency-icon bg-primary"><?php echo e((!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'); ?></span>
                                                            <input class="form-control currency_input" required="required" min="0" name="amount" type="number" value="" min="0" step="0.01" max="" id="amount">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 modal-footer">
                                                    <button class="btn btn-primary" type="submit"><?php echo e(__('Make Payment')); ?></button>
                                                </div>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                    <?php if(isset($paymentSetting['is_skrill_enabled']) && $paymentSetting['is_skrill_enabled'] == 'on'): ?>
                                        <div class="tab-pane fade" id="skrill-payment" role="tabpanel" aria-labelledby="skrill-payment">
                                            <form method="post" action="<?php echo e(route('client.invoice.pay.with.skrill',[$currentWorkspace->slug, $invoice->id])); ?>" class="require-validation" id="skrill-form">
                                                <?php echo csrf_field(); ?>
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label class="col-form-label" for="amount"><?php echo e(__('Amount')); ?></label>
                                                        <div class="form-icon-user">
                                                            <span class="currency-icon bg-primary"><?php echo e((!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'); ?></span>
                                                            <input class="form-control currency_input" required="required" min="0" name="amount" type="number" value="" min="0" step="0.01" max="" id="amount">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 modal-footer">
                                                    <button class="btn btn-primary" type="submit"><?php echo e(__('Make Payment')); ?></button>
                                                </div>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                    <?php if(isset($paymentSetting['is_coingate_enabled']) && $paymentSetting['is_coingate_enabled'] == 'on'): ?>
                                        <div class="tab-pane fade" id="coingate-payment" role="tabpanel" aria-labelledby="coingate-payment">
                                            <form method="post" action="<?php echo e(route('client.invoice.pay.with.coingate',[$currentWorkspace->slug, $invoice->id])); ?>" class="require-validation" id="coingate-form">
                                                <?php echo csrf_field(); ?>
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label class="col-form-label" for="amount"><?php echo e(__('Amount')); ?></label>
                                                        <div class="form-icon-user">
                                                            <span class="currency-icon bg-primary"><?php echo e((!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'); ?></span>
                                                            <input class="form-control currency_input" required="required" min="0" name="amount" type="number" value="" min="0" step="0.01" max="" id="amount">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 modal-footer">
                                                    <button class="btn btn-primary" type="submit"><?php echo e(__('Make Payment')); ?></button>
                                                </div>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                    <?php if(isset($paymentSetting['is_paymentwall_enabled']) && $paymentSetting['is_paymentwall_enabled'] == 'on'): ?>
                                        <div class="tab-pane fade" id="paymentwall-payment" role="tabpanel" aria-labelledby="coingate-payment">
                                            <form method="post" action="<?php echo e(route('paymentwall.index',[$currentWorkspace->slug, $invoice->id])); ?>" class="require-validation" id="coingate-form">
                                                <?php echo csrf_field(); ?>
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label class="col-form-label" for="amount"><?php echo e(__('Amount')); ?></label>
                                                        <div class="form-icon-user">
                                                            <span class="currency-icon bg-primary"><?php echo e((!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'); ?></span>
                                                            <input class="form-control currency_input" required="required" min="0" name="amount" type="number" value="" min="0" step="0.01" max="" id="amount">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 modal-footer">
                                                    <button class="btn btn-primary" type="submit"><?php echo e(__('Make Payment')); ?></button>
                                                </div>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php if(auth()->guard('client')->check()): ?>
    <?php if( $currentWorkspace->is_stripe_enabled == 1 || $currentWorkspace->is_paypal_enabled == 1  || (isset($paymentSetting['is_paypal_enabled']) && $paymentSetting['is_paypal_enabled'] == 'on') || (isset($paymentSetting['is_paystack_enabled']) && $paymentSetting['is_paystack_enabled'] == 'on') || (isset($paymentSetting['is_flutterwave_enabled']) && $paymentSetting['is_flutterwave_enabled'] == 'on') || (isset($paymentSetting['is_razorpay_enabled']) &&
    $paymentSetting['is_razorpay_enabled'] == 'on') || (isset($paymentSetting['is_mercado_enabled']) && $paymentSetting['is_mercado_enabled'] == 'on') || (isset($paymentSetting['is_paytm_enabled']) && $paymentSetting['is_paytm_enabled'] == 'on') || (isset($paymentSetting['is_mollie_enabled']) && $paymentSetting['is_mollie_enabled'] == 'on') || (isset($paymentSetting['is_skrill_enabled']) && $paymentSetting['is_skrill_enabled'] == 'on') || (isset($paymentSetting['is_coingate_enabled']) && $paymentSetting['is_coingate_enabled'] == 'on')): ?>
        <?php $__env->startPush('css-page'); ?>
            <style>
                #card-element {
                    border: 1px solid #e4e6fc;
                    border-radius: 5px;
                    padding: 10px;
                }
            </style>
        <?php $__env->stopPush(); ?>
        <?php $__env->startPush('scripts'); ?>
        <?php if($currentWorkspace->is_stripe_enabled == 1): ?>
            <script src="https://js.stripe.com/v3/"></script>
            <script type="text/javascript">

                var stripe = Stripe('<?php echo e($currentWorkspace->stripe_key); ?>');
                var elements = stripe.elements();

                // Custom styling can be passed to options when creating an Element.
                var style = {
                    base: {
                        // Add your base input styles here. For example:
                        fontSize: '14px',
                        color: '#32325d',
                    },
                };

                // Create an instance of the card Element.
                var card = elements.create('card', {style: style});

                // Add an instance of the card Element into the `card-element` <div>.
                card.mount('#card-element');

                // Create a token or display an error when the form is submitted.
                var form = document.getElementById('payment-form');
                form.addEventListener('submit', function (event) {
                    event.preventDefault();

                    stripe.createToken(card).then(function (result) {
                        if (result.error) {
                            show_toastr('Error', result.error.message, 'error');
                        } else {
                            // Send the token to your server.
                            stripeTokenHandler(result.token);
                        }
                    });
                });



                function stripeTokenHandler(token) {
                    // Insert the token ID into the form so it gets submitted to the server
                    var form = document.getElementById('payment-form');
                    var hiddenInput = document.createElement('input');
                    hiddenInput.setAttribute('type', 'hidden');
                    hiddenInput.setAttribute('name', 'stripeToken');
                    hiddenInput.setAttribute('value', token.id);
                    form.appendChild(hiddenInput);

                    // Submit the form
                    form.submit();
                }
            </script>
        <?php endif; ?>
            <script src="<?php echo e(url('custom/js/jquery.form.js')); ?>"></script>

            <?php if(isset($paymentSetting['is_paystack_enabled']) && $paymentSetting['is_paystack_enabled'] == 'on'): ?>
                <script src="https://js.paystack.co/v1/inline.js"></script>
                <script>
                    //    Paystack Payment
                    $(document).on("click", "#pay_with_paystack", function () {
                        $('#paystack-payment-form').ajaxForm(function (res) {
                            if (res.flag == 1) {
                                var coupon_id = res.coupon;
                                var paystack_callback = "<?php echo e(url('client/'.$currentWorkspace->slug.'/invoice/paystack')); ?>";
                                var order_id = '<?php echo e(time()); ?>';
                                var handler = PaystackPop.setup({
                                    key: '<?php echo e($paymentSetting['paystack_public_key']); ?>',
                                    email: res.email,
                                    amount: res.total_price * 100,
                                    currency: res.currency,
                                    ref: 'pay_ref_id' + Math.floor((Math.random() * 1000000000) +
                                        1
                                    ), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
                                    metadata: {
                                        custom_fields: [{
                                            display_name: "Email",
                                            variable_name: "email",
                                            value: res.email,
                                        }]
                                    },

                                    callback: function (response) {
                                        console.log(response.reference, order_id);
                                        window.location.href = paystack_callback + '/' + response.reference + '/' + '<?php echo e(encrypt($invoice->id)); ?>';
                                        
                                    },
                                    onClose: function () {
                                        alert('window closed');
                                    }
                                });
                                handler.openIframe();
                            } else {
                                show_toastr('Error', data.message, 'msg');
                            }

                        }).submit();
                    });
                </script>
            <?php endif; ?>

            <?php if(isset($paymentSetting['is_flutterwave_enabled']) && $paymentSetting['is_flutterwave_enabled'] == 'on'): ?>
                <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
                <script>
                    //    Flaterwave Payment
                    $(document).on("click", "#pay_with_flaterwave", function () {
                        $('#flaterwave-payment-form').ajaxForm(function (res) {
                            if (res.flag == 1) {
                                var coupon_id = res.coupon;

                                var API_publicKey = '<?php echo e($paymentSetting['flutterwave_public_key']); ?>';
                                var nowTim = "<?php echo e(date('d-m-Y-h-i-a')); ?>";
                                var flutter_callback = "<?php echo e(url('client/'.$currentWorkspace->slug.'/invoice/flaterwave')); ?>";
                                var x = getpaidSetup({
                                    PBFPubKey: API_publicKey,
                                    customer_email: '<?php echo e(Auth::user()->email); ?>',
                                    amount: res.total_price,
                                    currency: res.currency,
                                    txref: nowTim + '__' + Math.floor((Math.random() * 1000000000)) + 'fluttpay_online-' +
                                        <?php echo e(date('Y-m-d')); ?>,
                                    meta: [{
                                        metaname: "payment_id",
                                        metavalue: "id"
                                    }],
                                    onclose: function () {
                                    },
                                    callback: function (response) {
                                        var txref = response.tx.txRef;
                                        if (
                                            response.tx.chargeResponseCode == "00" ||
                                            response.tx.chargeResponseCode == "0"
                                        ) {
                                            window.location.href = flutter_callback + '/' + txref + '/' + '<?php echo e(\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)); ?>';
                                        } else {
                                            // redirect to a failure page.
                                        }
                                        x.close(); // use this to close the modal immediately after payment.
                                    }
                                });
                            }
                        }).submit();
                    });
                </script>
            <?php endif; ?>

            <?php if(isset($paymentSetting['is_razorpay_enabled']) && $paymentSetting['is_razorpay_enabled'] == 'on'): ?>
                <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
                <script>
                    // Razorpay Payment
                    $(document).on("click", "#pay_with_razerpay", function () {
                        $('#razorpay-payment-form').ajaxForm(function (res) {
                            if (res.flag == 1) {
                                var razorPay_callback = '<?php echo e(url('client/'.$currentWorkspace->slug.'/invoice/razorpay')); ?>';
                                var totalAmount = res.total_price * 100;
                                var coupon_id = res.coupon;
                                var options = {
                                    "key": "<?php echo e($paymentSetting['razorpay_public_key']); ?>", // your Razorpay Key Id
                                    "amount": totalAmount,
                                    "name": 'Plan',
                                    "currency": res.currency,
                                    "description": "",
                                    "handler": function (response) {
                                        window.location.href = razorPay_callback + '/' + response.razorpay_payment_id + '/' + '<?php echo e(\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)); ?>?coupon_id=' + coupon_id + '&payment_frequency=' + res.payment_frequency;
                                    },
                                    "theme": {
                                        "color": "#528FF0"
                                    }
                                };
                                var rzp1 = new Razorpay(options);
                                rzp1.open();
                            } else {
                                show_toastr('Error', res.msg, 'msg');
                            }

                        }).submit();
                    });
                </script>
            <?php endif; ?>
        <?php $__env->stopPush(); ?>
    <?php endif; ?>
<?php endif; ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/heritage/SHINE/LATEST/projectmanagement/resources/views/invoices/show.blade.php ENDPATH**/ ?>