@extends('layouts.admin')

@section('page-title') {{__('Invoices')}} @endsection
@section('links')
@if(\Auth::guard('client')->check())
<li class="breadcrumb-item"><a href="{{route('client.home')}}">{{__('Home')}}</a></li>
 @else
 <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('Home')}}</a></li>
 @endif
 @if(\Auth::guard('client')->check())
<li class="breadcrumb-item"><a href="{{ route('client.invoices.index',$currentWorkspace->slug) }}">{{__('Invoice')}}</a></li>
 @else
<li class="breadcrumb-item"><a href="{{ route('invoices.index',$currentWorkspace->slug) }}">{{__('Invoice')}}</a></li>
@endif
<li class="breadcrumb-item">{{__('Invoice Detail')}}</li>
 @endsection
   @push('scripts')
   <script>
         $('.cp_link').on('click', function () {
            console.log("hii");
            var value = $(this).attr('data-link');
            var $temp = $("<input>");

            $("body").append($temp);
            $temp.val(value).select();
            document.execCommand("copy");
            $temp.remove();
            show_toastr('Success', '{{__('Link Copy on Clipboard')}}', 'success')
        });
</script>
@endpush
@section('action-button')
@auth('client')

@if($currentWorkspace->is_stripe_enabled == 1 || $currentWorkspace->is_paypal_enabled == 1  || (isset($paymentSetting['is_paypal_enabled']) && $paymentSetting['is_paypal_enabled'] == 'on') || (isset($paymentSetting['is_paystack_enabled']) && $paymentSetting['is_paystack_enabled'] == 'on') || (isset($paymentSetting['is_flutterwave_enabled']) && $paymentSetting['is_flutterwave_enabled'] == 'on') || (isset($paymentSetting['is_razorpay_enabled']) &&
$paymentSetting['is_razorpay_enabled'] == 'on') || (isset($paymentSetting['is_mercado_enabled']) && $paymentSetting['is_mercado_enabled'] == 'on') || (isset($paymentSetting['is_paytm_enabled']) && $paymentSetting['is_paytm_enabled'] == 'on') || (isset($paymentSetting['is_mollie_enabled']) && $paymentSetting['is_mollie_enabled'] == 'on') || (isset($paymentSetting['is_skrill_enabled']) && $paymentSetting['is_skrill_enabled'] == 'on') || (isset($paymentSetting['is_coingate_enabled']) && $paymentSetting['is_coingate_enabled'] == 'on') || (isset($paymentSetting['is_paymentwall_enabled']) && $paymentSetting['is_paymentwall_enabled'] == 'on'))
       -->
                 <a href="#" data-toggle="modal" data-bs-toggle="tooltip" data-bs-original-title="{{__('Pay Now')}}" data-size="lg" data-target="#paymentModal" class="btn btn-sm mx-1   btn-primary">
                 <i class="ti ti-doller px-1"> $ </i>
                </a>


 <!--        @endif
   @endauth
    @auth('web')

        @if($currentWorkspace->creater->id == Auth::user()->id)
                <a href="#" class="btn btn-sm mx-1  btn-primary" data-size="lg" data-bs-toggle="tooltip" data-bs-original-title="{{__('Edit')}}"  data-ajax-popup="true" data-title="{{ __('Edit Invoice') }}" data-url="{{route('invoices.edit',[$currentWorkspace->slug,$invoice->id])}}">
                <i class="ti ti-edit"></i>
                </a>
                  <a href="#" class="btn btn-sm  mx-1 btn-primary cp_link " data-bs-toggle="tooltip" data-bs-original-title="{{__('Copy')}}" data-link="{{route('pay.invoice',[$currentWorkspace->slug,\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)])}}" data-toggle="tooltip" data-original-title="{{__('Click to copy invoice link')}}"><span class="btn-inner--icon text-white"></span><span class="btn-inner--text text-white"><i class="ti ti-copy"></i></span></a>

        @endif
    @endauth
            <a href="@auth('web'){{route('invoice.print',[$currentWorkspace->slug,\Illuminate\Support\Facades\Crypt::encryptString($invoice->id)])}}@elseauth{{route('client.invoice.print',[$currentWorkspace->slug,\Illuminate\Support\Facades\Crypt::encryptString($invoice->id)])}}@endauth"  data-bs-toggle="tooltip" data-bs-original-title="{{__('Print')}}" class="btn btn-sm btn-primary">
           <i class="ti ti-printer text-white"></i>
            </a>
           @auth('web')
            @if($currentWorkspace->creater->id == Auth::user()->id)
{{--              @if ( $invoice->getDueAmount() > 0)--}}
                 <a href="#" data-toggle="modal" data-target="#addPaymentModal" data-bs-toggle="tooltip" data-bs-original-title="{{__('Add Payment')}}" class="btn btn-sm  mx-1  btn-primary" type="button">
                     <i class="ti ti-plus"></i>
                </a>
                 @endif

                 @endauth

@endsection

@section('content')
                 <div class="row">
                 <div class="card" id="printTable">
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

                                    <td style="padding-bottom: 0px  !important; font-size: 15px !important;"> <b>{{ __('Project') }}</b>  : {{ $invoice->payment_type }}</td>
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
                        <div class="justify-content-between align-items-center d-flex">
                        <h5 class="px-2 py-2"><b>{{ __('Order Summary') }}</b></h5>
                            @auth('web')
                        @if($currentWorkspace->creater->id == Auth::user()->id)
                        <a href="#" data-ajax-popup="true"  data-bs-toggle="tooltip" data-bs-original-title="{{__('Add Item')}}"  data-title="{{ __('Add Item') }}" data-url="{{route('invoice.item.create',[$currentWorkspace->slug,$invoice->id])}}" class="btn btn-sm  btn-primary " type="button">
                        <i class="ti ti-plus"></i>
                         </a>
                         @endif
                         @endauth
                         </div>
                        <div class="table-responsive mt-3">
                            <table class="table invoice-detail-table">
                                <thead>
                                    <tr class="thead-default">
                                        <th>#</th>
                                        <th>{{ __('Item') }}</th>
                                        <th>{{ __('Totals') }}</th>
                                        @auth('web')
                                            <th>{{ __('Action') }}</th>
                                        @endauth
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoice->items as $key => $item)
                                        <tr>

                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $item->task ? $item->task->title : '' }}-
                                                <b>{{ $item->task ? $item->task->project->name : '' }}</b></td>
                                            <td>{{ $currentWorkspace->priceFormat($item->price * $item->qty) }}</td>
                                            @auth('web')
                                                <td class="text-right">
                                                    <a href="#"
                                                        class="action-btn btn-danger  btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                        data-confirm="{{ __('Are You Sure?') }}"
                                                        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                        data-confirm-yes="delete-form-{{ $item->id }}"
                                                        data-toggle="tooltip" title="{{ __('Delete') }}">
                                                        <i class="ti ti-trash"></i>
                                                    </a>

                                                    <form id="delete-form-{{ $item->id }}"
                                                        action="{{ route('invoice.item.destroy', [$currentWorkspace->slug, $invoice->id, $item->id]) }}"
                                                        method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            @endauth
                                        </tr>
                                    @endforeach
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
                                        <th>{{ __('Subtotal') }} :</th>
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
{{--                                            <td class="text-primary m-r-10 px-2"> {{ $currentWorkspace->priceFormat($invoice->getDueAmount()) }}</td>--}}
                                        </tr>


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @if ($payments = $invoice->payments)
                    <div class="row">
                        <div class="col-sm-12">
                            <h5 class="px-2 py-2"><b>{{ __('Payments') }}</b></h5>
                            <div class="table-responsive mt-3">
                                <table class="table invoice-detail-table">
                                    <thead>
                                        <tr class="thead-default">
                                            <th>#</th>
                                            <th>{{ __('Id') }}</th>
                                            <th>{{ __('Amount') }}</th>
                                            <th>{{ __('Currency') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Payment Type') }}</th>
                                            <th>{{ __('Date') }}</th>
                                            <th>{{ __('Receipt') }}</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($payments as $key => $payment)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $payment->order_id }}</td>
                                                <td>{{ $currentWorkspace->priceFormat($payment->amount) }}</td>
                                                <td>{{ strtoupper($payment->currency) }}</td>
                                                <td>
                                                    @if ($payment->payment_status == 'succeeded' || $payment->payment_status == 'approved')
                                                        <i class="fas fa-circle text-success"></i>
                                                        {{ __(ucfirst($payment->payment_status)) }}
                                                    @else
                                                        <i class="fas fa-circle text-danger"></i>
                                                        {{ __(ucfirst($payment->payment_status)) }}
                                                    @endif
                                                </td>
                                                <td>{{ __($payment->payment_type) }}</td>
                                                <td>{{ App\Models\Utility::dateFormat($payment->created_at) }}</td>
                                                <td>
                                                    @if (!empty($payment->receipt))
                                                        <a href="{{ $payment->receipt }}" target="_blank"
                                                            class="btn-submit"><i class="ti ti-printer"></i>
                                                            {{ __('Receipt') }}</a>
                                                    @endif
                                                </td>
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


    @if(auth('web'))
        <!-- Modal -->
        <div class="modal fade" id="addPaymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                              <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel"> {{ __('Add Manual Payment') }}</h5>
                                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                    </div>

                    <div class="modal-body">

                            <form method="post" action="{{ route('manual.invoice.payment',[$currentWorkspace->slug,$invoice->id]) }}" class="require-validation">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="amount" class="col-form-label">{{ __('Amount') }}</label>
                                        <div class="form-icon-user">
                                            <span class="currency-icon bg-primary">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$' }}</span>
                                            <input class="form-control currency_input" type="number" id="amount" name="amount" value="" min="0" step="0.01" max="" placeholder="{{ __('Enter Monthly Price') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12 modal-footer">
                                   <button type="button" class="btn  btn-light" data-dismiss="modal">{{ __('Close')}}</button>
                                    <input type="submit" value="{{ __('Make Payment')}}" class="btn  btn-primary">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @auth('client')

            <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel"> {{ __('Add Payment') }}</h5>
                                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                    </div>
                        <div class="modal-body">
                            <div class="card-box">
                                @if((isset($currentWorkspace->is_stripe_enabled) && $currentWorkspace->is_stripe_enabled == 1) || (isset($currentWorkspace->is_paypal_enabled) && $currentWorkspace->is_paypal_enabled == 1) || (isset($paymentSetting['is_paystack_enabled']) && $paymentSetting['is_paystack_enabled'] == 'on') || (isset($paymentSetting['is_flutterwave_enabled']) && $paymentSetting['is_flutterwave_enabled'] == 'on') || (isset($paymentSetting['is_razorpay_enabled']) &&
                                 $paymentSetting['is_razorpay_enabled'] == 'on') || (isset($paymentSetting['is_mercado_enabled']) && $paymentSetting['is_mercado_enabled'] == 'on') || (isset($paymentSetting['is_paytm_enabled']) && $paymentSetting['is_paytm_enabled'] == 'on') || (isset($paymentSetting['is_mollie_enabled']) && $paymentSetting['is_mollie_enabled'] == 'on') || (isset($paymentSetting['is_skrill_enabled']) && $paymentSetting['is_skrill_enabled'] == 'on') || (isset($paymentSetting['is_coingate_enabled']) && $paymentSetting['is_coingate_enabled'] == 'on'))
                                    <ul class="nav nav-tabs bordar_styless py-3">
                                        @if($currentWorkspace->is_stripe_enabled == 1)
                                            <li>
                                                <a data-toggle="tab" href="#stripe-payment" class="active">{{__('Stripe')}}</a>
                                            </li>
                                        @endif
                                        @if($currentWorkspace->is_paypal_enabled == 1)
                                            <li>
                                                <a data-toggle="tab" href="#paypal-payment" class="">{{__('Paypal')}} </a>
                                            </li>
                                        @endif
                                        @if(isset($paymentSetting['is_paystack_enabled']) && $paymentSetting['is_paystack_enabled'] == 'on')
                                            <li>
                                                <a data-toggle="tab" href="#paystack-payment" role="tab" aria-controls="paystack" aria-selected="false">{{ __('Paystack') }}</a>
                                            </li>
                                        @endif
                                        @if(isset($paymentSetting['is_flutterwave_enabled']) && $paymentSetting['is_flutterwave_enabled'] == 'on')
                                            <li>
                                                <a data-toggle="tab" href="#flutterwave-payment" role="tab" aria-controls="flutterwave" aria-selected="false">{{ __('Flutterwave') }}</a>
                                            </li>
                                        @endif
                                        @if(isset($paymentSetting['is_razorpay_enabled']) && $paymentSetting['is_razorpay_enabled'] == 'on')
                                            <li>
                                                <a data-toggle="tab" href="#razorpay-payment" role="tab" aria-controls="razorpay" aria-selected="false">{{ __('Razorpay') }}</a>
                                            </li>
                                        @endif
                                        @if(isset($paymentSetting['is_mercado_enabled']) && $paymentSetting['is_mercado_enabled'] == 'on')
                                            <li>
                                                <a data-toggle="tab" href="#mercado-payment" role="tab" aria-controls="mercado" aria-selected="false">{{ __('Mercado Pago') }}</a>
                                            </li>
                                        @endif
                                        @if(isset($paymentSetting['is_paytm_enabled']) && $paymentSetting['is_paytm_enabled'] == 'on')
                                            <li>
                                                <a data-toggle="tab" href="#paytm-payment" role="tab" aria-controls="paytm" aria-selected="false">{{ __('Paytm') }}</a>
                                            </li>
                                        @endif

                                        @if(isset($paymentSetting['is_mollie_enabled']) && $paymentSetting['is_mollie_enabled'] == 'on')
                                            <li class="pt-3">
                                                <a data-toggle="tab"  href="#mollie-payment" role="tab" aria-controls="mollie" aria-selected="false">{{ __('Mollie') }}</a>
                                            </li>
                                        @endif
                                        @if(isset($paymentSetting['is_skrill_enabled']) && $paymentSetting['is_skrill_enabled'] == 'on')
                                            <li class="pt-3">
                                                <a data-toggle="tab" href="#skrill-payment" role="tab" aria-controls="skrill" aria-selected="false">{{ __('Skrill') }}</a>
                                            </li>
                                        @endif
                                        @if(isset($paymentSetting['is_coingate_enabled']) && $paymentSetting['is_coingate_enabled'] == 'on')
                                            <li class="pt-3">
                                                <a data-toggle="tab" href="#coingate-payment" role="tab" aria-controls="coingate" aria-selected="false">{{ __('CoinGate') }}</a>
                                            </li>
                                        @endif
                                        @if(isset($paymentSetting['is_paymentwall_enabled']) && $paymentSetting['is_paymentwall_enabled'] == 'on')
                                            <li class="pt-3">
                                                <a data-toggle="tab" href="#paymentwall-payment" role="tab" aria-controls="coingate" aria-selected="false">{{ __('Paymentwall') }}</a>
                                            </li>
                                        @endif

                                    </ul>
                                @endif

                                <div class="tab-content mt-3">
                                    @if($currentWorkspace->is_stripe_enabled == 1)
                                        <div class="tab-pane fade {{ (($currentWorkspace->is_stripe_enabled == 1 && $currentWorkspace->is_paypal_enabled == 1) || $currentWorkspace->is_stripe_enabled == 1) ? "show active" : "" }}" id="stripe-payment" role="tabpanel" aria-labelledby="stripe-payment">
                                            <form method="post" action="{{ route('client.invoice.payment',[$currentWorkspace->slug,$invoice->id]) }}" class="require-validation" id="payment-form">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-sm-8">
                                                        <div class="custom-radio">
                                                            <label class="font-16 col-form-label">{{__('Credit / Debit Card')}}</label>
                                                        </div>
                                                        <p class="mb-0 pt-1 text-sm">{{__('Safe money transfer using your bank account. We support Mastercard, Visa, Discover and American express.')}}</p>
                                                    </div>
                                                    <div class="col-sm-4 text-sm-right mt-3 mt-sm-0">
                                                        <img src="{{asset('assets/img/payments/master.png')}}" height="24" alt="master-card-img">
                                                        <img src="{{asset('assets/img/payments/discover.png')}}" height="24" alt="discover-card-img">
                                                        <img src="{{asset('assets/img/payments/visa.png')}}" height="24" alt="visa-card-img">
                                                        <img src="{{asset('assets/img/payments/american express.png')}}" height="24" alt="american-express-card-img">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="card-name-on" class="col-form-label">{{__('Name on card')}}</label>
                                                            <input type="text" name="name" id="card-name-on" class="form-control required" placeholder="{{\Auth::user()->name}}">
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
                                                        <label for="amount" class="col-form-label">{{ __('Amount') }}</label>
                                                        <div class="form-icon-user">
                                                            <span class="currency-icon bg-primary">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'}}</span>
                                                            <input class="form-control currency_input" required="required" min="0" name="amount" type="number" value="" min="0" step="0.01" max="" id="amount">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="error" style="display: none;">
                                                            <div class='alert-danger alert'>{{__('Please correct the errors and try again.')}}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 modal-footer">
                                                        <input type="submit" class="btn btn-primary" value="{{ __('Make Payment') }}">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                    @if($currentWorkspace->is_paypal_enabled == 1)
                                        <div class="tab-pane fade {{ ($currentWorkspace->is_stripe_enabled == 0 && $currentWorkspace->is_paypal_enabled == 1) ? "show active" : "" }}" id="paypal-payment" role="tabpanel" aria-labelledby="paypal-payment">
                                            <form class="w3-container w3-display-middle w3-card-4 " method="POST" id="payment-form" action="{{ route('client.pay.with.paypal', [$currentWorkspace->slug, $invoice->id]) }}">
                                                @csrf
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label for="amount" class="col-form-label">{{ __('Amount') }}</label>
                                                        <div class="form-icon-user">
                                                            <span class="currency-icon bg-primary">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'}}</span>
                                                            <input class="form-control currency_input" required="required" min="0" name="amount" type="number" value="" min="0" step="0.01" max="" id="amount">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 modal-footer">
                                                    <input type="submit" class="btn btn-primary" value="{{ __('Make Payment') }}">
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                    @if($paymentSetting['is_paystack_enabled'] == 'on')
                                        <div class="tab-pane fade" id="paystack-payment" role="tabpanel" aria-labelledby="paystack-payment">
                                            <form method="post" action="{{route('client.invoice.pay.with.paystack',[$currentWorkspace->slug, $invoice->id])}}" class="require-validation" id="paystack-payment-form">
                                                @csrf
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label for="amount" class="col-form-label">{{ __('Amount') }}</label>
                                                        <div class="form-icon-user">
                                                            <span class="currency-icon bg-primary">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'}}</span>
                                                            <input class="form-control currency_input" required="required" min="0" name="amount" type="number" value="" min="0" step="0.01" max="" id="amount">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 modal-footer">
                                                    <button class="btn btn-primary" type="button" id="pay_with_paystack">{{ __('Make Payment') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                    @if(isset($paymentSetting['is_flutterwave_enabled']) && $paymentSetting['is_flutterwave_enabled'] == 'on')
                                        <div class="tab-pane fade" id="flutterwave-payment" role="tabpanel" aria-labelledby="flutterwave-payment">
                                            <form method="post" action="{{route('client.invoice.pay.with.flaterwave',[$currentWorkspace->slug, $invoice->id])}}" class="require-validation" id="flaterwave-payment-form">
                                                @csrf
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label class="col-form-label" for="amount">{{ __('Amount') }}</label>
                                                        <div class="form-icon-user">
                                                            <span class="currency-icon bg-primary">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'}}</span>
                                                            <input class="form-control currency_input" required="required" min="0" name="amount" type="number" value="" min="0" step="0.01" max="" id="amount">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 modal-footer">
                                                    <button class="btn btn-primary" type="button" id="pay_with_flaterwave">{{ __('Make Payment') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                    @if(isset($paymentSetting['is_razorpay_enabled']) && $paymentSetting['is_razorpay_enabled'] == 'on')
                                        <div class="tab-pane fade" id="razorpay-payment" role="tabpanel" aria-labelledby="razorpay-payment">
                                            <form method="post" action="{{route('client.invoice.pay.with.razorpay',[$currentWorkspace->slug, $invoice->id])}}" class="require-validation" id="razorpay-payment-form">
                                                @csrf
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label class="col-form-label" for="amount">{{ __('Amount') }}</label>
                                                        <div class="form-icon-user">
                                                            <span class="currency-icon bg-primary">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'}}</span>
                                                            <input class="form-control currency_input" required="required" min="0" name="amount" type="number" value="" min="0" step="0.01" max="" id="amount">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 modal-footer">
                                                    <button class="btn btn-primary" type="button" id="pay_with_razerpay">{{ __('Make Payment') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                    @if(isset($paymentSetting['is_mercado_enabled']) && $paymentSetting['is_mercado_enabled'] == 'on')
                                        <div class="tab-pane fade" id="mercado-payment" role="tabpanel" aria-labelledby="mercado-payment">
                                            <form method="post" action="{{route('client.invoice.pay.with.mercado',[$currentWorkspace->slug, $invoice->id])}}" class="require-validation" id="mercado-form">
                                                @csrf
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label class="col-form-label" for="amount">{{ __('Amount') }}</label>
                                                        <div class="form-icon-user">
                                                            <span class="currency-icon bg-primary">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'}}</span>
                                                            <input class="form-control currency_input" required="required" min="0" name="amount" type="number" value="" min="0" step="0.01" max="" id="amount">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 modal-footer">
                                                    <button class="btn btn-primary" type="submit">{{ __('Make Payment') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                    @if(isset($paymentSetting['is_paytm_enabled']) && $paymentSetting['is_paytm_enabled'] == 'on')
                                        <div class="tab-pane fade" id="paytm-payment" role="tabpanel" aria-labelledby="paytm-payment">
                                            <form method="post" action="{{route('client.invoice.pay.with.paytm',[$currentWorkspace->slug, $invoice->id])}}" class="require-validation" id="paytm-form">
                                                @csrf
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label class="col-form-label" for="amount">{{ __('Amount') }}</label>
                                                        <div class="form-icon-user">
                                                            <span class="currency-icon bg-primary">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'}}</span>
                                                            <input class="form-control currency_input" required="required" min="0" name="amount" type="number" value="" min="0" step="0.01" max="" id="amount">
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="mobile" class="col-form-label text-dark">{{__('Mobile Number')}}</label>
                                                            <input type="text" id="mobile" name="mobile" class="form-control mobile" data-from="mobile" placeholder="Enter Mobile Number" required="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 modal-footer">
                                                    <button class="btn btn-primary" type="submit">{{ __('Make Payment') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                    @if(isset($paymentSetting['is_mollie_enabled']) && $paymentSetting['is_mollie_enabled'] == 'on')
                                        <div class="tab-pane fade" id="mollie-payment" role="tabpanel" aria-labelledby="mollie-payment">
                                            <form method="post" action="{{route('client.invoice.pay.with.mollie',[$currentWorkspace->slug, $invoice->id])}}" class="require-validation" id="mollie-form">
                                                @csrf
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label class="col-form-label" for="amount">{{ __('Amount') }}</label>
                                                        <div class="form-icon-user">
                                                            <span class="currency-icon bg-primary">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'}}</span>
                                                            <input class="form-control currency_input" required="required" min="0" name="amount" type="number" value="" min="0" step="0.01" max="" id="amount">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 modal-footer">
                                                    <button class="btn btn-primary" type="submit">{{ __('Make Payment') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                    @if(isset($paymentSetting['is_skrill_enabled']) && $paymentSetting['is_skrill_enabled'] == 'on')
                                        <div class="tab-pane fade" id="skrill-payment" role="tabpanel" aria-labelledby="skrill-payment">
                                            <form method="post" action="{{route('client.invoice.pay.with.skrill',[$currentWorkspace->slug, $invoice->id])}}" class="require-validation" id="skrill-form">
                                                @csrf
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label class="col-form-label" for="amount">{{ __('Amount') }}</label>
                                                        <div class="form-icon-user">
                                                            <span class="currency-icon bg-primary">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'}}</span>
                                                            <input class="form-control currency_input" required="required" min="0" name="amount" type="number" value="" min="0" step="0.01" max="" id="amount">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 modal-footer">
                                                    <button class="btn btn-primary" type="submit">{{ __('Make Payment') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                    @if(isset($paymentSetting['is_coingate_enabled']) && $paymentSetting['is_coingate_enabled'] == 'on')
                                        <div class="tab-pane fade" id="coingate-payment" role="tabpanel" aria-labelledby="coingate-payment">
                                            <form method="post" action="{{route('client.invoice.pay.with.coingate',[$currentWorkspace->slug, $invoice->id])}}" class="require-validation" id="coingate-form">
                                                @csrf
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label class="col-form-label" for="amount">{{ __('Amount') }}</label>
                                                        <div class="form-icon-user">
                                                            <span class="currency-icon bg-primary">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'}}</span>
                                                            <input class="form-control currency_input" required="required" min="0" name="amount" type="number" value="" min="0" step="0.01" max="" id="amount">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 modal-footer">
                                                    <button class="btn btn-primary" type="submit">{{ __('Make Payment') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                    @if(isset($paymentSetting['is_paymentwall_enabled']) && $paymentSetting['is_paymentwall_enabled'] == 'on')
                                        <div class="tab-pane fade" id="paymentwall-payment" role="tabpanel" aria-labelledby="coingate-payment">
                                            <form method="post" action="{{route('paymentwall.index',[$currentWorkspace->slug, $invoice->id])}}" class="require-validation" id="coingate-form">
                                                @csrf
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label class="col-form-label" for="amount">{{ __('Amount') }}</label>
                                                        <div class="form-icon-user">
                                                            <span class="currency-icon bg-primary">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'}}</span>
                                                            <input class="form-control currency_input" required="required" min="0" name="amount" type="number" value="" min="0" step="0.01" max="" id="amount">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 modal-footer">
                                                    <button class="btn btn-primary" type="submit">{{ __('Make Payment') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    @endauth
@endsection

@auth('client')
    @if( $currentWorkspace->is_stripe_enabled == 1 || $currentWorkspace->is_paypal_enabled == 1  || (isset($paymentSetting['is_paypal_enabled']) && $paymentSetting['is_paypal_enabled'] == 'on') || (isset($paymentSetting['is_paystack_enabled']) && $paymentSetting['is_paystack_enabled'] == 'on') || (isset($paymentSetting['is_flutterwave_enabled']) && $paymentSetting['is_flutterwave_enabled'] == 'on') || (isset($paymentSetting['is_razorpay_enabled']) &&
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
        @if($currentWorkspace->is_stripe_enabled == 1)
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
        @endif
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
                                    customer_email: '{{Auth::user()->email}}',
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
@endauth
