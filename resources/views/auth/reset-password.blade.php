<x-guest-layout>
    <x-auth-card>


@section('page-title') {{__('Reset Password')}} @endsection

@section('content')

     <div class="card" style="margin-bottom: 180px !important">
                <div class="row align-items-center text-start">
                    <div class="col-xl-6">
                        <div class="card-body">
                            <div class="">
                                <h2 class="mb-3 f-w-600">{{ __('Reset Password') }}</h2>
                            </div>

                            <form method="POST" action="{{ route('password.update') }}">
                             @csrf
                              <input type="hidden" name="token" value="{{$request->route('token')}}">
                            <div class="">
                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">{{ __('Email') }}</label>
                                    <input type="email" class="form-control  @error('email') is-invalid @enderror" name="email" id="emailaddress" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="{{ __('Enter Your Email') }}">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="password" class="form-label">{{ __('Password') }}</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" id="password" placeholder="{{ __('Enter Your Password') }}">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                    <div class="form-group mb-3">
                                    <label for="password" class="form-label">{{ __('Confirm Password') }}</label>
                                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password" id="password_confirmation" placeholder="{{ __('Enter Your Password') }}">

                                </div>

                                <div class="d-grid">
                                    <button type="submit" id="login_button" class="btn btn-primary btn-block mt-2">{{ __('Reset Password') }}</button>
                                </div>

                            </form>


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

    </x-auth-card>
</x-guest-layout>
