<x-guest-layout>
    <x-auth-card>

        @section('page-title') {{__('Register')}} @endsection

        @section('content')

            <div class="card">
                <div class="row align-items-center text-start">

                    <div class="col-xl-6">
                        <div class="card-body">
                            @include('partials._notifications')

                            <div class="">
                                <h2 class="mb-3 f-w-600">{{ __('Registration form') }}</h2>
                            </div>
                            <form method="POST" action="{{ route('register') }}">
                                @csrf
                                <div class="">
                                    <div class="form-group mb-3">
                                        <label for="fullname" class="form-label">{{ __('Full Name') }}</label>
                                        <input type="text" class="form-control  @error('name') is-invalid @enderror" name="name" id="fullname" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="{{ __('Enter Your Name') }}">
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    {{--                                  <div class="form-group mb-3">--}}
                                    {{--                                    <label for="workspace_name" class="form-label">{{ __('Workspace Name') }}</label>--}}
                                                                        <input type="hidden" class="form-control  @error('workspace_name') is-invalid @enderror" name="workspace" id="workspace_name" value="default">
                                    {{--                                       @error('company')--}}
                                    {{--                                        <span class="invalid-feedback" role="alert">--}}
                                    {{--                                                <strong>{{ $message }}</strong>--}}
                                    {{--                                            </span>--}}
                                    {{--                                        @enderror--}}
                                    {{--                                </div>--}}
                                    <div class="form-group mb-3">
                                        <label for="emailaddress" class="form-label">{{ __('Email') }}</label>
                                        <input type="email" class="form-control  @error('email') is-invalid @enderror" name="email" id="emailaddress" value="{{ old('email') }}" required autocomplete="email" placeholder="{{ __('Enter Your Email') }}">
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
                                        <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password" id="password_confirmation" placeholder="{{ __('Confirm Your Password') }}">

                                    </div>

                                    @if(env('RECAPTCHA_MODULE') == 'on')
                                        <div class="form-group col-lg-12 col-md-12 mt-3">
                                            {!! NoCaptcha::display() !!}
                                            @error('g-recaptcha-response')
                                            <span class="small text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                            @enderror
                                        </div>
                                    @endif
                                    <div class="d-grid">
                                        <button type="submit" id="login_button" class="btn btn-primary btn-block mt-2">{{ __('Register') }}</button>
                                    </div>
                                    <!--  <p class="my-4 text-center">or register with</p> -->
                            </form>
                            <p class="mb-2 mt-2 ">Already have an account? <a href="{{ route('login', $lang) }}" class="f-w-400 text-primary">{{ __('Sign In') }}</a></p>

                            <div class="">
                                @section('language-bar')
                                    <a href="#" class="monthly-btn btn-primary ">

                                        <select name="language" id="language" class="btn-primary btn" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                                            @foreach(App\Models\Utility::languages() as $language)
                                                <option class="login_lang" @if($lang == $language) selected @endif value="{{ route('register',$language) }}">{{Str::upper($language)}}</option>
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
