<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@if(trim($__env->yieldContent('page-title')) && Auth::user()->type == 'admin')
            {{ config('app.name', 'Taskly') }} -@yield('page-title') 
        @else
             {{ isset($currentWorkspace->company) && $currentWorkspace->company != '' ? $currentWorkspace->company : config('app.name', 'Taskly') }} -@yield('page-title')
        @endif</title>
    <link rel="icon" href="{{asset(Storage::url('logo/favicon.png'))}}" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
  </head>
  <script src="https://api.paymentwall.com/brick/build/brick-default.1.5.0.min.js"> </script>
  <div id="payment-form-container"> </div>
  <script>
    var brick = new Brick({
      public_key: '{{ $payment_detail['paymentwall_public_key']  }}', // please update it to Brick live key before launch your project
      amount: {{$data['amount']}},
      currency: '{{ (!empty($currentWorkspace->currency_code)) ? $currentWorkspace->currency_code : 'USD'}}',
      container: 'payment-form-container',
      action: '{{route("invoice.pay.with.paymentwall",[$slug,$invoice_id,"amount" => $data["amount"]])}}',
      form: {
        merchant: 'Paymentwall',
        product:  '{{App\Models\Utility::invoiceNumberFormat($invoice->invoice_id)}}',
        pay_button: 'Pay',
        show_zip: true, // show zip code 
        show_cardholder: true // show card holder name 
      }
    });

    brick.showPaymentForm(function(data) {
        if(data.flag == 1){
           window.location.href ='{{route("invoice.callback.error",[1,"_slug",$invoice_id])}}'.replace('_slug', data.slug);
        }else{
          window.location.href ='{{route("invoice.callback.error",[2,"_slug",$invoice_id])}}'.replace('_slug', data.slug);
        }
    }, function(errors) {
        if(errors.flag == 1){
        window.location.href ='{{route("invoice.callback.error",[1,"_slug",$invoice_id])}}'.replace('_slug', errors.slug);
        }else{
          window.location.href ='{{route("invoice.callback.error",[2,"_slug",$invoice_id])}}'.replace('_slug', errors.slug);
        }
    });
    
  </script>