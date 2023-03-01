@extends('layouts.invoicepayheader')
@section('page-title') {{__('Invoices')}} @endsection
@section('action-button')                       
<button type="button"
class="btn btn-sm btn-primary" style="    margin: 0px 90px 0px;"> <a href="{{route('client.invoice.print',[$currentWorkspace->slug,\Illuminate\Support\Facades\Crypt::encryptString($invoice->id)])}}" class="text_white">
<i class="ti ti-printer text-white"></i> 
</a></button>
@endsection
@section('content')
                 <div class="row">
                    <div class="card col-11" id="printTable">
                           <div class="card-header" >
            <h5 class="" style=" left: -12px !important;">{{ App\Models\Utility::invoiceNumberFormat($invoice->invoice_id) }}</h5>                                    
          </div>
                        <div class="card-body">
                           
                                     <div class="row ">
                    
                    <div class="col-md-4 ">
                        <div class="invoice-contact">
                        <div class="invoice-box row">
                            <div class="col-sm-12">
                                <h6>{{ __('From') }}:</h6>
                                @if ($currentWorkspace->company)
                                    <h6 class="m-0">{{ $currentWorkspace->company }}</h6>
                                @endif

                                @if ($currentWorkspace->address)
                                    {{ $currentWorkspace->address }},
                                    <br>
                                @endif

                                @if ($currentWorkspace->city)
                                    {{ $currentWorkspace->city }},
                                @endif
                                @if ($currentWorkspace->state)
                                    {{ $currentWorkspace->state }},
                                @endif

                                @if ($currentWorkspace->zipcode)
                                    -{{ $currentWorkspace->zipcode }},<br>
                                @endif
                                @if ($currentWorkspace->country)
                                    {{ $currentWorkspace->country }},<br>
                                @endif
                                @if ($currentWorkspace->telephone)
                                    {{ $currentWorkspace->telephone }}
                                @endif
                            </div>
                        </div>
                    </div>
                    </div>


                       <div class="col-md-4  invoice-client-info">
                         <div class="invoice-contact">
                        <div class="invoice-box row">
                            <div class="col-sm-12">
                        <h6>{{ __('To') }}:</h6>
                           @if ($invoice->client)
                                    <h6 class="m-0">{{ $invoice->client->name }}</h6>

                                    {{ $invoice->client->email }}<br>
                               
                                @if($invoice->client)

                                 @if ($invoice->client->address)
                                   {{ $invoice->client->address }},
                                    <br>
                                @endif

                                @if ($invoice->client->city)
                                   {{ $invoice->client->city }},
                                @endif
                                @if ($invoice->client->state)
                                    {{ $invoice->client->state }},
                                @endif

                               @if ($invoice->client->zipcode)
                                    -{{ $invoice->client->zipcode }},<br>
                                @endif
                               @if ($invoice->client->country)
                                    {{ $invoice->client->country }},<br>
                                @endif
                                
                                 @if ($invoice->client->telephone)
                                    {{ $invoice->client->telephone }}
                                    
                                @endif

                                 @endif
                                 @endif
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
                                   
                                    <td style="padding-bottom: 0px  !important; font-size: 15px !important;"> <b>{{ __('Project') }}</b>  : {{ $invoice->project->name }}</td>
                                </tr>
                                <tr>
                                    
                                    <td style="padding-bottom: 0px  !important; font-size: 15px !important;"><b>{{ __('Issue Date') }}</b> :{{ App\Models\Utility::dateFormat($invoice->issue_date) }}</td>
                                </tr>
                                <tr>
                                    
                                    @if ($invoice->status == 'sent')
                                        <td style="padding-bottom: 0px  !important; font-size: 15px !important;">
                                            <b>{{ __('Status') }} :</b>
                                            <span class="p-2 px-3 rounded badge bg-warning">{{ __('Sent') }}</span>
                                        </td>
                                    @elseif($invoice->status == 'paid')
                                        <td style="padding-bottom: 0px  !important; font-size: 15px !important;">
                                            <b>{{ __('Status') }} :</b>
                                            <span class="p-2 px-3 rounded badge bg-success">{{ __('Paid') }}</span>
                                        </td>
                                    @elseif($invoice->status == 'canceled')
                                        <td style="padding-bottom: 0px  !important; font-size: 15px !important;">
                                             <b>{{ __('Status') }} :</b>
                                            <span class="p-2 px-3 rounded badge bg-danger">{{ __('Canceled') }}</span>
                                        </td>
                                    @endif
                                </tr>
                                <tr>
                                   
                                    <td style="padding-bottom: 0px  !important; font-size: 15px !important;">
                                       <b> {{ __('Due Date') }}:</b>
                                        {{ App\Models\Utility::dateFormat($invoice->due_date) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                </div>

                    <div class="col-md-1  qr_code">
                        <div class="text-end" style="margin: 0px 0px 0px -30px;">
                            {!! DNS2D::getBarcodeHTML(route('pay.invoice', [$currentWorkspace->slug, \Illuminate\Support\Facades\Crypt::encrypt($invoice->id)]), 'QRCODE', 2, 2) !!}
                        </div>
                    </div>
                
                </div>
                            <div class="row">
                                <div class="col-sm-12">
                                        <h5 class="px-2 py-2 mb-4"><b>{{__('Order Summary')}}</b></h5>
                                    <div class="table-responsive">
                                        <table class="table invoice-detail-table">
                                            <thead>
                                                <tr class="thead-default">
                                                    <th>#</th>
                                                    <th>{{__('Item')}}</th>
                                                    <th>{{__('Totals')}}</th>
                                                   
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($invoice->items as $key => $item)
                                                                                       <tr>
                                            <td>{{$key+1}}</td>
                                           <td>{{($item->task)? $item->task->title:""}}- <b>{{($item->task)?$item->task->project->name:""}}</b></td>
                                            <td>{{$currentWorkspace->priceFormat($item->price * $item->qty)}}</td>
                                           
                                        </tr>
                                    @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="invoice-total">
                                        <table class="table table-responsive invoice-table ">
                                            <tbody>
                                                <tr>
                                        <th>{{ __('Subtotal') }} :</th>
                                        <td>{{ $currentWorkspace->priceFormat($invoice->getSubTotal()) }}</td>
                                    </tr>
                                    @if ($invoice->discount)
                                        <tr>
                                            <th>{{ __('Discount') }} :</th>
                                            <td>{{ $currentWorkspace->priceFormat($invoice->discount) }}</td>
                                        </tr>
                                    @endif

                                    @if ($invoice->tax)
                                        <tr>
                                            <th>{{ __('Tax') }} {{ $invoice->tax->name }}
                                                ({{ $invoice->tax->rate }}%):</th>
                                            <td>{{ $currentWorkspace->priceFormat($invoice->getTaxTotal()) }}</td>
                                        </tr>
                                    @endif

                                     <tr>
                                            <th class="text-primary m-r-10 ">{{ __('Total') }} : </th>
                                            <td class="text-primary m-r-10 px-2"> {{ $currentWorkspace->priceFormat($invoice->getTotal()) }}</td>
                                        </tr>
                                     <tr>
                                            <th class="text-primary m-r-10 ">{{ __('Due Amount') }} : </th>
                                            <td class="text-primary m-r-10 px-2"> {{ $currentWorkspace->priceFormat($invoice->getDueAmount()) }}</td>
                                        </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @if($payments = $invoice->payments)
                             <div class="row">
                                <div class="col-sm-12">
                                        <h5 class="px-2 py-2 mb-4"><b>{{__('Payments')}}</b></h5>
                                    <div class="table-responsive">
                                        <table class="table invoice-detail-table">
                                            <thead>
                                                <tr class="thead-default">
                                                    <th>#</th>
                                                    <th>{{__('Amount')}}</th>
                                                    <th>{{__('Currency')}}</th>
                                                    <th>{{__('Status')}}</th>
                                                    <th>{{__('Payment Type')}}</th>
                                                    <th>{{__('Date')}}</th>
                                                   
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                   @foreach($payments as $key => $payment)
                                                    <tr>
                                                        <td>{{$payment->order_id}}</td>
                                                        <td>{{$currentWorkspace->priceFormat($payment->amount)}}</td>
                                                        <td>{{strtoupper($payment->currency)}}</td>
                                                        <td>
                                                            @if($payment->payment_status == 'succeeded' || $payment->payment_status == 'approved')
                                                                <i class="fas fa-circle text-success"></i> {{__(ucfirst($payment->payment_status))}}
                                                            @else
                                                                <i class="fas fa-circle text-danger"></i> {{__(ucfirst($payment->payment_status))}}
                                                            @endif
                                                        </td>
                                                        <td>{{ __($payment->payment_type) }}</td>
                                                        <td>{{App\Models\Utility::dateFormat($payment->created_at)}}</td>
                                                      
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>                
            <!-- [ Invoice ] end -->
        </div>


 
@endsection
  @if($invoice->getDueAmount() > 0 && $currentWorkspace->is_stripe_enabled == 1 || $currentWorkspace->is_paypal_enabled == 1  || (isset($paymentSetting['is_paypal_enabled']) && $paymentSetting['is_paypal_enabled'] == 'on') || (isset($paymentSetting['is_paystack_enabled']) && $paymentSetting['is_paystack_enabled'] == 'on') || (isset($paymentSetting['is_flutterwave_enabled']) && $paymentSetting['is_flutterwave_enabled'] == 'on') || (isset($paymentSetting['is_razorpay_enabled']) &&
    $paymentSetting['is_razorpay_enabled'] == 'on') || (isset($paymentSetting['is_mercado_enabled']) && $paymentSetting['is_mercado_enabled'] == 'on') || (isset($paymentSetting['is_paytm_enabled']) && $paymentSetting['is_paytm_enabled'] == 'on') || (isset($paymentSetting['is_mollie_enabled']) && $paymentSetting['is_mollie_enabled'] == 'on') || (isset($paymentSetting['is_skrill_enabled']) && $paymentSetting['is_skrill_enabled'] == 'on') || (isset($paymentSetting['is_coingate_enabled']) && $paymentSetting['is_coingate_enabled'] == 'on'))
        @push('css-page')
            <style>
                #card-element {
                    border: 1px solid #e4e6fc;
                    border-radius: 5px;
                    padding: 10px;
                }
            </style>
        @endpush
        @push('scripts')
            <script src="https://js.stripe.com/v3/"></script>
            <script type="text/javascript">
                var stripe = Stripe('{{ $currentWorkspace->stripe_key }}');
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
            <script src="{{url('custom/js/jquery.form.js')}}"></script>

            @if(isset($paymentSetting['is_paystack_enabled']) && $paymentSetting['is_paystack_enabled'] == 'on')
                <script src="https://js.paystack.co/v1/inline.js"></script>
                <script>
                    //    Paystack Payment
                    $(document).on("click", "#pay_with_paystack", function () {
                        $('#paystack-payment-form').ajaxForm(function (res) {
                            if (res.flag == 1) {
                                var coupon_id = res.coupon;
                                var paystack_callback = "{{ url('client/'.$currentWorkspace->slug.'/invoice/paystack') }}";
                                var order_id = '{{time()}}';
                                var handler = PaystackPop.setup({
                                    key: '{{ $paymentSetting['paystack_public_key']  }}',
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
                                        window.location.href = paystack_callback + '/' + response.reference + '/' + '{{encrypt($invoice->id)}}';
                                        {{--window.location.href = paystack_callback + '/' + '{{$invoice->id}}';--}}
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
            @endif

            @if(isset($paymentSetting['is_flutterwave_enabled']) && $paymentSetting['is_flutterwave_enabled'] == 'on')
                <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
                <script>
                    //    Flaterwave Payment
                    $(document).on("click", "#pay_with_flaterwave", function () {
                        $('#flaterwave-payment-form').ajaxForm(function (res) {
                            if (res.flag == 1) {
                                var coupon_id = res.coupon;

                                var API_publicKey = '{{ $paymentSetting['flutterwave_public_key']  }}';
                                var nowTim = "{{ date('d-m-Y-h-i-a') }}";
                                var flutter_callback = "{{ url('client/'.$currentWorkspace->slug.'/invoice/flaterwave') }}";
                                var x = getpaidSetup({
                                    PBFPubKey: API_publicKey,
                                    customer_email: @if(Auth::check()) ?  '{{Auth::user()->email}}' :' {{$client->email}}' @endif,
                                    amount: res.total_price,
                                    currency: res.currency,
                                    txref: nowTim + '__' + Math.floor((Math.random() * 1000000000)) + 'fluttpay_online-' +
                                        {{ date('Y-m-d') }},
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
                                            window.location.href = flutter_callback + '/' + txref + '/' + '{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}}';
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
            @endif

            @if(isset($paymentSetting['is_razorpay_enabled']) && $paymentSetting['is_razorpay_enabled'] == 'on')
                <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
                <script>
                    // Razorpay Payment
                    $(document).on("click", "#pay_with_razerpay", function () {
                        $('#razorpay-payment-form').ajaxForm(function (res) {
                            if (res.flag == 1) {
                                var razorPay_callback = '{{url('client/'.$currentWorkspace->slug.'/invoice/razorpay')}}';
                                var totalAmount = res.total_price * 100;
                                var coupon_id = res.coupon;
                                var options = {
                                    "key": "{{ $paymentSetting['razorpay_public_key']  }}", // your Razorpay Key Id
                                    "amount": totalAmount,
                                    "name": 'Plan',
                                    "currency": res.currency,
                                    "description": "",
                                    "handler": function (response) {
                                        window.location.href = razorPay_callback + '/' + response.razorpay_payment_id + '/' + '{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}}?coupon_id=' + coupon_id + '&payment_frequency=' + res.payment_frequency;
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
            @endif
        @endpush
    @endif

