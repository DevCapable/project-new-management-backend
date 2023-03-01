<x-guest-layout>
    <x-auth-card>

        @section('page-title')
            {{__('Register')}}
        @endsection

        @section('content')
            {{--                @if(isset($client->payment_policy) && $client->payment_policy == null)--}}
            @include('partials._payment_terms')
            {{--                @endif--}}
            <div class="card">
                <div class="row align-items-center text-start">
                    <div class="col-xl-6">
                        <div class="card-body">
                            @include('partials._notifications')
                            <div class="">
                                <h2 class="mb-3 f-w-600">{{ __('Payment') }}</h2>
                            </div>
                            <div class="row" style="margin-bottom:40px;">
                                <form id="makePaymentForm" method="POST" action="{{ route('pay') }}"
                                      accept-charset="UTF-8"
                                      class="form-horizontal" role="form">
                                    <div class="col-md-12 col-md-offset-4">
                                        {{env('APP_NAME')}}
                                        <div>
                                            @php($payment_data =  App\Models\PaymentLists::where('slug','registration_payment')->first())
                                            {{'$'.$payment_data->amount ?? env('REG_FEE' )}}
                                        </div>

                                        <input type="hidden" name="metadata" value="">
                                        <div class="form-group mb-3">
                                            <label for="email" class="form-label">{{ __('Email') }}</label>
                                            <input type="email"
                                                   class="form-control  @error('email') is-invalid @enderror"
                                                   name="email" id="email"
                                                   value="@if(isset($client->email)){{  $client->email }} @endif"
                                                   readonly required autocomplete="email" autofocus
                                                   placeholder="{{ __('Enter Your Email') }}">
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                            @enderror
                                        </div>
                                        <input type="hidden" id="name" name="name"
                                               value="@if(isset($client->name)){{  $client->name }}@endif">

                                        <input type="hidden" name="orderID" value="{{'Reg'.sha1(time())}}">

                                        <input type="hidden" name="metadata"
                                               value="@if(isset($client->email)){{ json_encode($array = ['email' => $client->email, 'user_id'=>$client->id]) }}@endif">
                                        <input type="hidden" name="amount"
                                               value=" {{($payment_data->amount * 100) ?? env('REG_FEE' )}}"> {{-- required in kobo --}}
                                        <input type="hidden" id="amount"
                                               value=" {{($payment_data->amount) ?? env('REG_FEE' )}}">
                                        <input type="hidden" id="currency" name="currency" value="NGN">

                                        <input type="hidden" name="reference" value="{{ Paystack::genTranxRef() }}">
                                        {{ csrf_field() }}

                                        <div class="card">
                                            <div class="card-shadow">
                                                <div class="card-head">PAYMENT GATEWAYS</div>
                                                <div class="card-body">
                                                    <p>
                                                        @if($client->payment_policy === NULL)
                                                            <a href="#"><i class="fa fa-check-circle" id="reOpenModal">
                                                                    click here to Accept terms
                                                                    and conditions</i></a>
                                                            <br><br>
                                                            <button disabled class="btn btn-success btn-lg btn-block"
                                                                    type="submit"
                                                                    value="Pay Now!">
                                                                <i class="fa fa-plus-circle fa-lg"></i> Pay Now [Pay
                                                                Stack]
                                                            </button> <br><br>

                                                            <button disabled type="button"
                                                                    class="btn btn-success btn-lg btn-block"
                                                                    id="start-payment-button"><i
                                                                    class="fa fa-plus-circle fa-lg"></i> Pay Now
                                                                [Flutter
                                                                Wave]
                                                            </button>

                                                        @else
                                                            <button class="btn btn-success btn-lg btn-block"
                                                                    type="submit"
                                                                    value="Pay Now!">
                                                                <i class="fa fa-plus-circle fa-lg"></i> Pay Now [Pay
                                                                Stack]
                                                            </button>
                                                            <br><br>

                                                            <button type="button"
                                                                    class="btn btn-success btn-lg btn-block"
                                                                    id="start-payment-button"><i
                                                                    class="fa fa-plus-circle fa-lg"></i> Pay Now
                                                                [Flutter
                                                                Wave]
                                                            </button>

                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>


                                <p class="mb-2 mt-2 ">Already have an account? <a href="{{ route('login', $lang) }}"
                                                                                  class="f-w-400 text-primary">{{ __('Sign In') }}</a>
                                </p>

                                <div class="">
                                    @section('language-bar')
                                        <a href="#" class="monthly-btn btn-primary ">

                                            <select name="language" id="language" class="btn-primary btn"
                                                    onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                                                @foreach(App\Models\Utility::languages() as $language)
                                                    <option class="login_lang" @if($lang == $language) selected
                                                            @endif value="{{ route('register',$language) }}">{{Str::upper($language)}}</option>
                                                @endforeach
                                            </select>
                                        </a>
                                    @endsection
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 img-card-side">
                        <div class="auth-img-content">
                            <img src="{{ asset('assets/images/auth/img-auth-3.svg')}}" alt="" class="img-fluid">
                            <h3 class="text-white mb-4 mt-5">“Attention is the new currency”</h3>
                            <p class="text-white">The more effortless the writing looks, the more effort the writer
                                actually put into the process.</p>
                        </div>
                    </div>
                </div>
        @endsection
        @push('custom-scripts')
            @if(env('RECAPTCHA_MODULE') == 'on')
                {!! NoCaptcha::renderJs() !!}
            @endif
        @endpush
    </x-auth-card>
</x-guest-layout>
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
            public_key: "{{getenv('FLUTTERWAVE_PUBLIC_KEY')}}",
            tx_ref: "{{generate_pament_txn_id('REGPAY_')}}",
            amount,
            currency,
            payment_options: "card, banktransfer, ussd",
            redirect_url: "{{\Illuminate\Support\Facades\URL::to('/client/login')}}",
            meta: {
                consumer_id: {{$client->id}},
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
                    url: "{{route('payWithFlutter')}}",
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
                title: "{{env('APP_NAME')}}",
                description: "Registration Payment",
                logo: "https://www.logolynx.com/images/logolynx/22/2239ca38f5505fbfce7e55bbc0604386.jpeg",
            },
        });
    }
</script>
