@extends('layouts.admin')
@section('page-title')
    {{ __('Settings') }}
@endsection
@php
$setting = App\Models\Utility::getAdminPaymentSettings();
$color = isset($settings['theme_color']) ? $settings['theme_color'] : 'theme-4';
$is_sidebar_transperent = isset($settings['is_sidebar_transperent']) ?  $settings['is_sidebar_transperent'] : '';
$dark_mode = isset($settings['dark_mode']) ?  $settings['dark_mode'] : '';

if ($setting['color']) {
    $color = $setting['color'];
} else {
    $color = 'theme-4';
}


@endphp

@section('links')
    @if (\Auth::guard('client')->check())
        <li class="breadcrumb-item"><a href="{{ route('client.home') }}">{{ __('Home') }}</a></li>
    @else
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    @endif
    <li class="breadcrumb-item"> {{ __('Setting') }}</li>
@endsection

@section('content')
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card sticky-top" style="top:30px">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            <a href="#site-settings" class="list-group-item list-group-item-action border-0 ">{{ __('Site Setting') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#email-settings"
                                class="list-group-item list-group-item-action dash-link border-0">{{ __('Email Setting') }} <div
                                    class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#pusher-settings"
                                class="list-group-item list-group-item-action border-0">{{ __('Pusher Setting') }} <div
                                    class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#recaptcha-settings"
                                class="list-group-item list-group-item-action border-0">{{ __('ReCaptcha Setting') }} <div
                                    class="float-end"><i class="ti ti-chevron-right"></i></div></a>


                        </div>
                    </div>
                </div>
                <div class="col-xl-9">

                    <div id="site-settings" class="">

                        {{ Form::open(['route' => 'settings.store', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                        <div class="row">
                            <div class="col-4">
                                <div class="card ">
                                    <div class="card-header">
                                        <h5>{{ __('Favicon') }}</h5>

                                    </div>
                                    <div class="card-body favicon">
                                        <div class="logo-content">
                                            <img src="{{ asset(Storage::url('logo/favicon.png')) }}" class="small_logo"
                                                id="favicon" style="width: 70px !important; height:70px" />
                                        </div>
                                        <div class="choose-file mt-5 ">
                                            <label for="small-favicon">

                                                <div class=" bg-primary"> <i
                                                        class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</div>
                                                <input type="file" class="form-control" name="favicon" id="small-favicon"
                                                    data-filename="edit-favicon">
                                            </label>
                                            <p class="edit-favicon"></p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card ">
                                    <div class="card-header">
                                        <h5>{{ __('Dark Logo') }}</h5>

                                    </div>
                                    <div class="card-body">
                                        <div class="logo-content">
{{--                                            <img src="{{ asset(Storage::url('logo/logo-light.png')) }}" id="dark_logo"--}}
{{--                                                class="small_logo" />--}}
                                        </div>
                                        <div class="choose-file mt-5 ">
                                            <label for="logo_blue">

                                                <div class=" bg-primary"> <i
                                                        class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</div>
                                                <input type="file" class="form-control" name="logo_blue" id="logo_blue"
                                                    data-filename="edit-logo_blue">
                                            </label>
                                            <p class="edit-logo_blue"></p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card ">
                                    <div class="card-header">
                                        <h5>{{ __('Light Logo') }}</h5>

                                    </div>
                                    <div class="card-body">
                                        <div class="logo-content">
                                            <img src="{{ asset(Storage::url('logo/logo-dark.png')) }}" id="image"
                                                class="small_logo" />
                                        </div>
                                        <div class="choose-file mt-5 ">
                                            <label for="logo_white">

                                                <div class=" bg-primary"> <i
                                                        class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</div>
                                                <input type="file" class="form-control" name="logo_white" id="logo_white"
                                                    data-filename="edit-logo_white">
                                            </label>
                                            <p class="edit-logo_white"></p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card ">
                                    <div class="card-header">
                                        <h5>{{ __('Settings') }}</h5>

                                    </div>
                                    <div class="card-body">
                                        <div class="row mt-2">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    {{ Form::label('app_name', __('App Name'), ['class' => 'form-label']) }}
                                                    {{ Form::text('app_name', env('APP_NAME'), ['class' => 'form-control', 'placeholder' => __('App Name')]) }}
                                                    @error('app_name')
                                                        <span class="invalid-app_name" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    {{ Form::label('footer_text', __('Footer Text'), ['class' => 'form-label']) }}
                                                    {{ Form::text('footer_text', env('FOOTER_TEXT'), ['class' => 'form-control', 'placeholder' => __('Footer Text')]) }}
                                                    @error('footer_text')
                                                        <span class="invalid-footer_text" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    {{ Form::label('default_language', __('Default Language'), ['class' => 'form-label']) }}
                                                    <div class="changeLanguage">
                                                        <select name="default_language" id="default_language"
                                                            class="form-control select2">
                                                            @foreach ($workspace->languages() as $lang)
                                                                <option value="{{ $lang }}"
                                                                    @if (env('DEFAULT_LANG') == $lang) selected @endif>
                                                                    {{ Str::upper($lang) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-3 ">
                                                    <div class="col switch-width">
                                                        <div class="form-group ml-2 mr-3">
                                                            <label
                                                                class="form-label mb-1">{{ __('Landing Page Display') }}</label>
                                                            <div class="custom-control custom-switch">
                                                                <input type="checkbox" data-toggle="switchbutton"
                                                                    data-onstyle="primary" class=""
                                                                    name="display_landing" id="display_landing"
                                                                    {{ !empty(env('DISPLAY_LANDING')) && env('DISPLAY_LANDING') == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label mb-1"
                                                                    for="status"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div class="col switch-width">
                                                        <div class="form-group ml-2 mr-3 ">
                                                            <label class="form-label mb-1">{{ __('RTL') }}</label>
                                                            <div class="custom-control custom-switch">
                                                                <input type="checkbox" data-toggle="switchbutton"
                                                                    data-onstyle="primary" class=""
                                                                    name="SITE_RTL" id="SITE_RTL"
                                                                    {{ !empty(env('SITE_RTL')) && env('SITE_RTL') == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label" for="site_rtl"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div class="col switch-width">
                                                        <div class="form-group ml-2 mr-3 ">
                                                            <label class="form-label mb-1">{{ __('Sign Up') }}</label>
                                                            <div class="custom-control custom-switch">
                                                                <input type="checkbox" data-toggle="switchbutton"
                                                                    data-onstyle="primary" class=""
                                                                    name="signup_button" id="signup_button"
                                                                    {{ !empty(env('signup_button')) && env('signup_button') == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label"
                                                                    for="signup_button"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div class="col switch-width">
                                                        <div class="form-group ml-2 mr-3 ">
                                                            <label class="form-label mb-1">{{ __('GDPR Cookie') }}</label>
                                                            <div class="custom-control custom-switch">
                                                                <input type="checkbox" data-toggle="switchbutton"
                                                                    data-onstyle="primary" class="gdpr_fulltime"
                                                                    name="gdpr_cookie" id="gdpr_cookie"
                                                                    {{ !empty(env('gdpr_cookie')) && env('gdpr_cookie') == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label"
                                                                    for="gdpr_cookie"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-12">
                                                    @if (env('gdpr_cookie') == 'on')
                                                        {{ Form::label('cookie_text', __('GDPR Cookie Text'), ['class' => 'fulltime form-label ']) }}
                                                        {!! Form::textarea('cookie_text', env('cookie_text'), ['class' => 'form-control fulltime', 'rows' => '2', 'style' => 'display: hidden;height: auto !important;resize:none;']) !!}
                                                    @endif
                                                </div>
                                            </div>
                                            <h4 class="small-title mb-4">Theme Customizer</h4>
                                            <div class="col-12">
                                                <div class="pct-body">
                                                    <div class="row">
                                                        <div class="col-4">
                                                            <h6 class="">
                                                                <i data-feather="credit-card"
                                                                    class="me-2"></i>Primary color settings
                                                            </h6>
                                                            <hr class="my-2" />
                                                            <div class="theme-color themes-color">
                                                                <input type="hidden" name="theme_color" id="color_value" value="{{ $color }}">
                                                                <a href="#!" class="{{ $color == 'theme-1' ? 'active_color' : '' }}" data-value="theme-1" onclick="check_theme('theme-1')"></a>
                                                                <input type="radio" class="theme_color " name="theme_color" value="theme-1" style="display: none;">
                                                                <a href="#!" class="{{ $color == 'theme-2' ? 'active_color' : '' }}" data-value="theme-2" onclick="check_theme('theme-2')"></a>
                                                                <input type="radio" class="theme_color" name="theme_color" value="theme-2" style="display: none;">
                                                                <a href="#!" class="{{ $color == 'theme-3' ? 'active_color' : '' }}" data-value="theme-3" onclick="check_theme('theme-3')"></a>
                                                                <input type="radio" class="theme_color" name="theme_color" value="theme-3" style="display: none;">
                                                                <a href="#!" class="{{ $color == 'theme-4' ? 'active_color' : '' }}" data-value="theme-4" onclick="check_theme('theme-4')"></a>
                                                                <input type="radio" class="theme_color" name="theme_color" value="theme-4" style="display: none;">
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <h6 class="">
                                                                <i data-feather="layout" class="me-2"></i>Sidebar
                                                                settings
                                                            </h6>
                                                            <hr class="my-2 " />
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" class="form-check-input"
                                                                    id="cust-theme-bg" name="cust_theme_bg"
                                                                    @if ($setting['cust_theme_bg'] == 'on') checked @endif />

                                                                <label class="form-check-label f-w-600 pl-1"
                                                                    for="cust-theme-bg">Transparent layout</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <h6 class="">
                                                                <i data-feather="sun" class=""></i>Layout
                                                                settings
                                                            </h6>
                                                            <hr class=" my-2" />
                                                            <div class="form-check form-switch mt-2 ">
                                                                <input type="checkbox" class="form-check-input"
                                                                    id="cust-darklayout" name="cust_darklayout"
                                                                    @if ($setting['cust_darklayout'] == 'on') checked @endif />

                                                                <label class="form-check-label f-w-600 pl-1"
                                                                    for="cust-darklayout">Dark Layout</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <input type="submit" value="{{ __('Save Changes') }}"
                                                    class="btn btn-primary">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        {{ Form::close() }}
                        <div id="email-settings" class="tab-pane">
                            <div class="col-md-12">

                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="">
                                            {{ __('Email settings') }}
                                        </h5>
                                    </div>
                                    <div class="card-body p-4">
                                        {{ Form::open(['route' => 'email.settings.store', 'method' => 'post']) }}
                                        <div class="row">
                                            <div class="col-lg-3 col-md-6 col-sm-6 form-group">
                                                {{ Form::label('mail_driver', __('Mail Driver'), ['class' => 'form-label']) }}
                                                {{ Form::text('mail_driver', env('MAIL_DRIVER'), ['class' => 'form-control', 'placeholder' => __('Enter Mail Driver')]) }}
                                                @error('mail_driver')
                                                    <span class="invalid-mail_driver" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-6 form-group">
                                                {{ Form::label('mail_host', __('Mail Host'), ['class' => 'form-label']) }}
                                                {{ Form::text('mail_host', env('MAIL_HOST'), ['class' => 'form-control ', 'placeholder' => __('Enter Mail Host')]) }}
                                                @error('mail_host')
                                                    <span class="invalid-mail_driver" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-6 form-group">
                                                {{ Form::label('mail_port', __('Mail Port'), ['class' => 'form-label']) }}
                                                {{ Form::text('mail_port', env('MAIL_PORT'), ['class' => 'form-control', 'placeholder' => __('Enter Mail Port')]) }}
                                                @error('mail_port')
                                                    <span class="invalid-mail_port" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-6 form-group">
                                                {{ Form::label('mail_username', __('Mail Username'), ['class' => 'form-label']) }}
                                                {{ Form::text('mail_username', env('MAIL_USERNAME'), ['class' => 'form-control', 'placeholder' => __('Enter Mail Username')]) }}
                                                @error('mail_username')
                                                    <span class="invalid-mail_username" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-6 form-group">
                                                {{ Form::label('mail_password', __('Mail Password'), ['class' => 'form-label']) }}
                                                {{ Form::text('mail_password', env('MAIL_PASSWORD'), ['class' => 'form-control', 'placeholder' => __('Enter Mail Password')]) }}
                                                @error('mail_password')
                                                    <span class="invalid-mail_password" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-6 form-group">
                                                {{ Form::label('mail_encryption', __('Mail Encryption'), ['class' => 'form-label']) }}
                                                {{ Form::text('mail_encryption', env('MAIL_ENCRYPTION'), ['class' => 'form-control', 'placeholder' => __('Enter Mail Encryption')]) }}
                                                @error('mail_encryption')
                                                    <span class="invalid-mail_encryption" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-6 form-group">
                                                {{ Form::label('mail_from_address', __('Mail From Address'), ['class' => 'form-label']) }}
                                                {{ Form::text('mail_from_address', env('MAIL_FROM_ADDRESS'), ['class' => 'form-control', 'placeholder' => __('Enter Mail From Address')]) }}
                                                @error('mail_from_address')
                                                    <span class="invalid-mail_from_address" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-6 form-group">
                                                {{ Form::label('mail_from_name', __('Mail From Name'), ['class' => 'form-label']) }}
                                                {{ Form::text('mail_from_name', env('MAIL_FROM_NAME'), ['class' => 'form-control', 'placeholder' => __('Enter Mail From Name')]) }}
                                                @error('mail_from_name')
                                                    <span class="invalid-mail_from_name" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                        </div>
                                        <div class="col-lg-12 ">
                                            <div class="row">

                                                <div class="text-start col-6">
                                                    <a href="#" data-ajax-popup="true" data-size="md"
                                                        data-url="{{ route('test.email') }}"
                                                        data-title="{{ __('Send Test Mail') }}"
                                                        class="btn  btn-primary send_email">
                                                        {{ __('Send Test Mail') }}
                                                    </a>
                                                </div>
                                                <div class="text-end col-6">
                                                    <input type="submit" value="{{ __('Save Changes') }}"
                                                        class="btn-submit btn btn-primary">
                                                </div>

                                            </div>
                                        </div>
                                        {{ Form::close() }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="pusher-settings" class="card">
                            <div class="col-md-12">
                                <form method="POST" action="{{ route('pusher.settings.store') }}" accept-charset="UTF-8">
                                    @csrf
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-6">
                                                <h5 class="">{{ __('Pusher settings') }}</h5>
                                            </div>
                                            <div class=" col-6 text-end">
                                                <div class="col switch-width">
                                                    <div class="form-group ml-2 mr-3 ">
                                                        <div class="custom-control custom-switch">
                                                            <label class="custom-control-label form-control-label px-2"
                                                                for="enable_chat ">{{ __('Enable Chat') }}</label>
                                                            <input type="checkbox" data-toggle="switchbutton"
                                                                data-onstyle="primary" class=""
                                                                name="enable_chat" id="enable_chat"
                                                                {{ !empty(env('CHAT_MODULE')) && env('CHAT_MODULE') == 'on' ? 'checked="checked"' : '' }}>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body p-3">

                                        <div class="row">

                                            <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                                <label for="pusher_app_id"
                                                    class="form-label">{{ __('Pusher App Id') }}</label>
                                                <input class="form-control" placeholder="Enter Pusher App Id"
                                                    name="pusher_app_id" type="text" value="{{ env('PUSHER_APP_ID') }}"
                                                    id="pusher_app_id">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                                <label for="pusher_app_key"
                                                    class="form-label">{{ __('Pusher App Key') }}</label>
                                                <input class="form-control " placeholder="Enter Pusher App Key"
                                                    name="pusher_app_key" type="text" value="{{ env('PUSHER_APP_KEY') }}"
                                                    id="pusher_app_key">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                                <label for="pusher_app_secret"
                                                    class="form-label">{{ __('Pusher App Secret') }}</label>
                                                <input class="form-control " placeholder="Enter Pusher App Secret"
                                                    name="pusher_app_secret" type="text"
                                                    value="{{ env('PUSHER_APP_SECRET') }}" id="pusher_app_secret">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                                <label for="pusher_app_cluster"
                                                    class="form-label">{{ __('Pusher App Cluster') }}</label>
                                                <input class="form-control " placeholder="Enter Pusher App Cluster"
                                                    name="pusher_app_cluster" type="text"
                                                    value="{{ env('PUSHER_APP_CLUSTER') }}" id="pusher_app_cluster">
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <input type="submit" value="{{ __('Save Changes') }}"
                                                class="btn-submit btn btn-primary">
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                        <div id="recaptcha-settings" class="card">
                            <div class="col-md-12">
                                <form method="POST" action="{{ route('recaptcha.settings.store') }}"
                                    accept-charset="UTF-8">
                                    @csrf
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-6">
                                                <h5 class="">{{ __('ReCaptcha settings') }}</h5>
                                                <a href="https://phppot.com/php/how-to-get-google-recaptcha-site-and-secret-key/"
                                                    target="_blank" class="text-blue ">
                                                    <small
                                                        class="d-block mt-2">({{ __('How to Get Google reCaptcha Site and Secret key') }})</small>
                                                </a>
                                            </div>
                                            <div class="col-6 text-end">
                                                <div class="col switch-width">
                                                    <div class="form-group ml-2 mr-3 ">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" data-toggle="switchbutton"
                                                                data-onstyle="primary" class=""
                                                                name="recaptcha_module" id="recaptcha_module"
                                                                {{ !empty(env('RECAPTCHA_MODULE')) && env('RECAPTCHA_MODULE') == 'on' ? 'checked="checked"' : '' }}>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                                <label for="google_recaptcha_key"
                                                    class="form-label">{{ __('Google Recaptcha Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="{{ __('Enter Google Recaptcha Key') }}"
                                                    name="google_recaptcha_key" type="text"
                                                    value="{{ env('NOCAPTCHA_SITEKEY') }}" id="google_recaptcha_key">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                                <label for="google_recaptcha_secret"
                                                    class="form-label">{{ __('Google Recaptcha Secret') }}</label>
                                                <input class="form-control "
                                                    placeholder="{{ __('Enter Google Recaptcha Secret') }}"
                                                    name="google_recaptcha_secret" type="text"
                                                    value="{{ env('NOCAPTCHA_SECRET') }}" id="google_recaptcha_secret">
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <input type="submit" value="{{ __('Save Changes') }}"
                                                class="btn-submit btn btn-primary">
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- [ sample-page ] end -->
            </div>
            <!-- [ Main Content ] end -->
        </div>
    @endsection

    <style>
        .active_color {
            background-color: #ffffff !important;
            border: 2px solid #000 !important;
        }
    </style>
    @push('scripts')
        <script>
            function check_theme(color_val) {
                $('.theme-color').prop('checked', false);
                $('input[value="' + color_val + '"]').prop('checked', true);
            }
            var scrollSpy = new bootstrap.ScrollSpy(document.body, {
                target: '#useradd-sidenav',
                offset: 300
            })
        </script>
        <script>
            $(document).on("click", '.send_email', function(e) {
                e.preventDefault();
                var title = $(this).attr('data-title');

                var size = 'md';
                var url = $(this).attr('data-url');
                if (typeof url != 'undefined') {
                    $("#commonModal .modal-title").html(title);
                    $("#commonModal .modal-dialog").addClass('modal-' + size);
                    $("#commonModal").modal('show');

                    $.post(url, {
                        mail_driver: $("#mail_driver").val(),
                        mail_host: $("#mail_host").val(),
                        mail_port: $("#mail_port").val(),
                        mail_username: $("#mail_username").val(),
                        mail_password: $("#mail_password").val(),
                        mail_encryption: $("#mail_encryption").val(),
                        mail_from_address: $("#mail_from_address").val(),
                        mail_from_name: $("#mail_from_name").val(),
                    }, function(data) {
                        $('#commonModal .modal-body .card-box').html(data);
                    });
                }
            });
            $(document).on('submit', '#test_email', function(e) {
                e.preventDefault();
                $("#email_sending").show();
                var post = $(this).serialize();
                var url = $(this).attr('action');
                $.ajax({
                    type: "post",
                    url: url,
                    data: post,
                    cache: false,
                    beforeSend: function() {
                        $('#test_email .btn-create').attr('disabled', 'disabled');
                    },
                    success: function(data) {
                        if (data.is_success) {
                            show_toastr('Success', data.message, 'success');
                        } else {
                            show_toastr('Error', data.message, 'error');
                        }
                        $("#email_sending").hide();
                    },
                    complete: function() {
                        $('#test_email .btn-create').removeAttr('disabled');
                    },
                });
            })
        </script>


        <script>
            $(document).ready(function() {
                if ($('.gdpr_fulltime').is(':checked')) {

                    $('.fulltime').show();
                } else {
                    $('.fulltime').hide();
                }
                $('#gdpr_cookie').on('change', function() {

                    if ($('.gdpr_fulltime').is(':checked')) {

                        $('.fulltime').show();
                    } else {

                        $('.fulltime').hide();
                    }
                });

                cust_theme_bg();
                cust_darklayout();


                $(document).on('click', '.list-group-item', function() {
                    $('.list-group-item').removeClass('active');
                    $('.list-group-item').removeClass('text-primary');
                    setTimeout(() => {
                        $(this).addClass('active').removeClass('text-primary');
                    }, 10);
                });

                var type = window.location.hash.substr(1);
                $('.list-group-item').removeClass('active');
                $('.list-group-item').removeClass('text-primary');
                if (type != '') {
                    $('a[href="#' + type + '"]').addClass('active').removeClass('text-primary');
                } else {
                    $('.list-group-item:eq(0)').addClass('active').removeClass('text-primary');
                }
            });
        </script>


        <script>
            function cust_theme_bg() {
                var custthemebg = document.querySelector("#cust-theme-bg");

                if (custthemebg.checked) {
                    document.querySelector(".dash-sidebar").classList.add("transprent-bg");
                    document
                        .querySelector(".dash-header:not(.dash-mob-header)")
                        .classList.add("transprent-bg");
                } else {
                    document.querySelector(".dash-sidebar").classList.remove("transprent-bg");
                    document
                        .querySelector(".dash-header:not(.dash-mob-header)")
                        .classList.remove("transprent-bg");
                }

            }



            function cust_darklayout() {
                var custdarklayout = document.querySelector("#cust-darklayout");

                if (custdarklayout.checked) {
                    document
                        .querySelector(".m-header > .b-brand > .logo-lg")
                        .setAttribute("src", "{{ asset('assets/images/logo.svg') }}");
                    document
                        .querySelector("#main-style-link")
                        .setAttribute("href", "{{ asset('assets/css/style-dark.css') }}");
                } else {
                    document
                        .querySelector(".m-header > .b-brand > .logo-lg")
                        .setAttribute("src", "{{ asset('assets/images/logo-dark.svg') }}");
                    document
                        .querySelector("#main-style-link")
                        .setAttribute("href", "{{ asset('assets/css/style.css') }}");
                }

            }
        </script>


        <script type="text/javascript">
            $('#small-favicon').change(function() {

                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#favicon').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);

            });


            $('#logo_blue').change(function() {

                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#dark_logo').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);

            });

            $('#logo_white').change(function() {

                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#image').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);

            });
        </script>

        <script>

            $(document).ready(function() {
                if ($('.gdpr_fulltime').is(':checked')) {
                    $('.fulltime').show();
                } else {
                    $('.fulltime').hide();
                }
                $('#gdpr_cookie').on('change', function() {
                    if ($('.gdpr_fulltime').is(':checked')) {
                        $('.fulltime').show();
                    } else {
                        $('.fulltime').hide();
                    }
                });
            });

            var scrollSpy = new bootstrap.ScrollSpy(document.body, {
                target: '#useradd-sidenav',
                offset: 300
            })

            $('.themes-color-change').on('click',function(){
                var color_val = $(this).data('value');
                $('.theme-color').prop('checked', false);
                $('.themes-color-change').removeClass('active_color');
                $(this).addClass('active_color');
                $(`input[value=${color_val}]`).prop('checked', true);

            });
        </script>

    @endpush
