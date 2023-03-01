<?php if (isset($component)) { $__componentOriginalc3251b308c33b100480ddc8862d4f9c79f6df015 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\GuestLayout::class, []); ?>
<?php $component->withName('guest-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.auth-card','data' => []]); ?>
<?php $component->withName('auth-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>

        <?php $__env->startSection('page-title'); ?>
            <?php echo e(__('Register')); ?>

        <?php $__env->stopSection(); ?>

        <?php $__env->startSection('content'); ?>
            
            <?php echo $__env->make('partials._payment_terms', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            
            <div class="card">
                <div class="row align-items-center text-start">
                    <div class="col-xl-6">
                        <div class="card-body">
                            <?php echo $__env->make('partials._notifications', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <div class="">
                                <h2 class="mb-3 f-w-600"><?php echo e(__('Payment')); ?></h2>
                            </div>
                            <div class="row" style="margin-bottom:40px;">
                                <form id="makePaymentForm" method="POST" action="<?php echo e(route('pay')); ?>"
                                      accept-charset="UTF-8"
                                      class="form-horizontal" role="form">
                                    <div class="col-md-12 col-md-offset-4">
                                        <?php echo e(env('APP_NAME')); ?>

                                        <div>
                                            <?php echo e('$'.$payment_data->amount ?? env('REG_FEE' )); ?>

                                        </div>

                                        <input type="hidden" name="metadata" value="">
                                        <div class="form-group mb-3">
                                            <label for="email" class="form-label"><?php echo e(__('Email')); ?></label>
                                            <input type="email"
                                                   class="form-control  <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                   name="email" id="email"
                                                   value="<?php if(isset($client->email)): ?><?php echo e($client->email); ?> <?php endif; ?>"
                                                   readonly required autocomplete="email" autofocus
                                                   placeholder="<?php echo e(__('Enter Your Email')); ?>">
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
                                        <input type="hidden" id="name" name="name"
                                               value="<?php if(isset($client->name)): ?><?php echo e($client->name); ?><?php endif; ?>">

                                        <input type="hidden" name="orderID" value="<?php echo e('Reg'.sha1(time())); ?>">

                                        <input type="hidden" name="metadata"
                                               value="<?php if(isset($client->email)): ?><?php echo e(json_encode($array = ['email' => $client->email, 'user_id'=>$client->id,'payment_type'=>'appointment'])); ?><?php endif; ?>">
                                        <input type="hidden" name="amount"
                                               value=" <?php echo e(($payment_data->amount * 100) ?? env('REG_FEE' )); ?>"> 
                                        <input type="hidden" id="amount"
                                               value=" <?php echo e(($payment_data->amount) ?? env('REG_FEE' )); ?>">
                                        <input type="hidden" id="currency" name="currency" value="NGN">

                                        <input type="hidden" name="reference" value="<?php echo e(Paystack::genTranxRef()); ?>">
                                        <?php echo e(csrf_field()); ?>


                                        <div class="card">
                                            <div class="card-shadow">
                                                <div class="card-body">
                                                    <div class="card-head">PAYMENT GATEWAYS</div>

                                                            <button class="btn btn-success btn-lg btn-block"
                                                                    type="submit"
                                                                    value="Pay Now!">
                                                                <i class="fa fa-plus-circle fa-lg"></i> Pay Now [Pay
                                                                Stack]
                                                            </button>
                                                            <br>

                                                            <button type="button"
                                                                    class="btn btn-success btn-lg btn-block"
                                                                    id="start-payment-button"><i
                                                                    class="fa fa-plus-circle fa-lg"></i> Pay Now
                                                                [Flutter
                                                                Wave]
                                                            </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>


                                <p class="mb-2 mt-2 ">ill do this latter <a href="/client"
                                                                                  class="f-w-400 text-primary"><?php echo e(__('Dashboard')); ?></a>
                                </p>

                                <div class="">
                                    <?php $__env->startSection('language-bar'); ?>
                                        <a href="#" class="monthly-btn btn-primary ">

                                            <select name="language" id="language" class="btn-primary btn"
                                                    onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                                                <?php $__currentLoopData = App\Models\Utility::languages(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option class="login_lang" <?php if($lang == $language): ?> selected
                                                            <?php endif; ?> value="<?php echo e(route('register',$language)); ?>"><?php echo e(Str::upper($language)); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </a>
                                    <?php $__env->stopSection(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 img-card-side">
                        <div class="auth-img-content">
                            <img src="<?php echo e(asset('assets/images/auth/img-auth-3.svg')); ?>" alt="" class="img-fluid">
                            <h3 class="text-white mb-4 mt-5">“Attention is the new currency”</h3>
                            <p class="text-white">The more effortless the writing looks, the more effort the writer
                                actually put into the process.</p>
                        </div>
                    </div>
                </div>
        <?php $__env->stopSection(); ?>
        <?php $__env->startPush('custom-scripts'); ?>
            <?php if(env('RECAPTCHA_MODULE') == 'on'): ?>
                <?php echo NoCaptcha::renderJs(); ?>

            <?php endif; ?>
        <?php $__env->stopPush(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc3251b308c33b100480ddc8862d4f9c79f6df015)): ?>
<?php $component = $__componentOriginalc3251b308c33b100480ddc8862d4f9c79f6df015; ?>
<?php unset($__componentOriginalc3251b308c33b100480ddc8862d4f9c79f6df015); ?>
<?php endif; ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://checkout.flutterwave.com/v3.js"></script>
<script>
    $('#reOpenModal').click(function () {
        $('#policyModal').modal('show')
        $('#reOpenModal').onFocus()
    });
    var policyModal = new bootstrap.Modal(document.getElementById('policyModal'), {})
    policyModal.toggle()
</script>

<script>
    $(function () {
        $("#start-payment-button").click(function (e) {
            e.preventDefault();
            var name = $("#name").val()
            var email = $("#email").val()
            var amount = $("#amount").val()
            var currency = $("#currency").val()
            makePayment(amount, currency, email, name)
        })
    })

    function makePayment(amount, currency, email, name) {
        FlutterwaveCheckout({
            public_key: "<?php echo e(getenv('FLUTTERWAVE_PUBLIC_KEY')); ?>",
            tx_ref: "<?php echo e(generate_pament_txn_id('REGPAY_')); ?>",
            amount,
            currency,
            payment_options: "card, banktransfer, ussd",
            redirect_url: "<?php echo e(\Illuminate\Support\Facades\URL::to('/client')); ?>",
            meta: {
                consumer_id: <?php echo e($client->id); ?>,
                // consumer_mac: "92a3-912ba-1192a",
            },
            customer: {
                email,
                // phone_number: "08102909304",
                name,
            },
            callback: function (data) {
                console.log(data)
                let request;
                var _token = $("input[name='_token']").val()
                $.ajax({
                    type: "POST",
                    url: "<?php echo e(route('appointment-pay-with-flutter')); ?>",
                    data: {
                        data,
                        _token
                    },
                    success: function (response) {
                        console.log(response);
                    }
                })
            },
            enclose: function () {
            },
            customizations: {
                title: "<?php echo e(env('APP_NAME')); ?>",
                description: "Registration Payment",
                logo: "https://www.logolynx.com/images/logolynx/22/2239ca38f5505fbfce7e55bbc0604386.jpeg",
            },
        });
    }
</script>
<?php /**PATH /home/heritage/SHINE/LATEST/projectmanagement/resources/views/clients/appointments/_client_renew_appointment_page.blade.php ENDPATH**/ ?>