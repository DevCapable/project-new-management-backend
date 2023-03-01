<!DOCTYPE html>
@php
    if(Auth::user()->type == 'admin')
    {
    $setting = App\Models\Utility::getAdminPaymentSettings();
        if ($setting['color']) {
            $color = $setting['color'];
        }
        else{
        $color = 'theme-3';
        }
        $dark_mode = $setting['cust_darklayout'];
        $cust_theme_bg =$setting['cust_theme_bg'];
        $SITE_RTL = env('SITE_RTL');
    }
    else {
        $setting = App\Models\Utility::getcompanySettings($currentWorkspace->id);
        $color = $setting->theme_color;
        $dark_mode = $setting->cust_darklayout;
        $SITE_RTL = $setting->site_rtl;
        $cust_theme_bg = $setting->cust_theme_bg;
    }

       if($color == '' || $color == null){
          $settings = App\Models\Utility::getAdminPaymentSettings();
          $color = $settings['color'];
       }

       if($dark_mode == '' || $dark_mode == null){
          $dark_mode = $settings['cust_darklayout'];
       }

       if($cust_theme_bg == '' || $dark_mode == null){
          $cust_theme_bg = $settings['cust_theme_bg'];
       }

        if($SITE_RTL == '' || $SITE_RTL == null){
          $SITE_RTL = env('SITE_RTL');
       }
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $SITE_RTL == 'on'?'rtl':''}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @if(trim($__env->yieldContent('page-title')) && Auth::user()->type == 'admin')
            {{ config('app.name', 'Taskly') }} -@yield('page-title')
        @else
            {{ isset($currentWorkspace->company) && $currentWorkspace->company != '' ? $currentWorkspace->company : config('app.name', 'Taskly') }}
            -@yield('page-title')
        @endif
    </title>

    <link rel="shortcut icon" href="{{asset(Storage::url('logo/favicon.png'))}}">
    <link rel="stylesheet" href="{{asset('assets/css/plugins/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/plugins/flatpickr.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/plugins/main.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/plugins/datepicker-bs5.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/plugins/bootstrap-switch-button.min.css') }}">
    <link rel="stylesheet" href="{{asset('assets/css/customizer.css')}}">
    <link rel="stylesheet" href="{{asset('custom/css/custom.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/plugins/dragula.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/landing.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/css/plugins/animate.min.css')}}"/>

    <!-- vendor css -->
    @stack('css-page')

    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/dropzone.css') }}" />

    <!--     @if($SITE_RTL =='on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css')}}" id="main-style-link">

    @else

        @if($dark_mode =='on')
            <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css')}}">

        @else
            <link rel="stylesheet" href="{{ asset('assets/css/style.css')}}" id="main-style-link">

        @endif
    @endif -->

    @if ($SITE_RTL == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}">
    @endif
    @if ($dark_mode == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    @endif


    <meta name="url" content="{{ url('').'/'.config('chatify.routes.prefix') }}" data-user="{{ Auth::user()->id }}">
    <script src='https://unpkg.com/nprogress@0.2.0/nprogress.js'></script>
</head>

@if($dark_mode == 'on')
    <style type="text/css">
        .list-group-item.active {
            border-color: #000 !important;
        }
    </style>
@else
    <style type="text/css">
        .list-group-item.active {
            border-color: #ffff !important;
        }

    </style>
@endif

<style type="text/css">
    [dir="rtl"] .dash-sidebar {
        left: auto !important;
    }

    [dir="rtl"] .dash-header {
        left: 0;
        right: 280px;
    }

    [dir="rtl"] .dash-header:not(.transprent-bg) .header-wrapper {
        padding: 0 0 0 30px;
    }

    [dir="rtl"] .dash-header:not(.transprent-bg):not(.dash-mob-header) ~ .dash-container {
        margin-left: 0px;
    }

    [dir="rtl"] .me-auto.dash-mob-drp {
        margin-right: 10px !important;
    }

    [dir="rtl"] .me-auto {
        margin-left: 10px !important;
    }

    [dir="rtl"] .header-wrapper .ms-auto {
        margin-left: 0 !important;
    }

    [dir="rtl"] .dash-header {
        left: 0 !important;
        right: 280px !important;
    }
</style>

<body class="{{ $color }}">

<!-- <div class="container-fluid container-application"> -->

<script>
    var dataTableLang = {
        paginate: {previous: "<i class='fas fa-angle-left'>", next: "<i class='fas fa-angle-right'>"},
        lengthMenu: "{{__('Show')}} _MENU_ {{__('entries')}}",
        zeroRecords: "{{__('No data available in table.')}}",
        info: "{{__('Showing')}} _START_ {{__('to')}} _END_ {{__('of')}} _TOTAL_ {{__('entries')}}",
        infoEmpty: "{{ __('Showing 0 to 0 of 0 entries') }}",
        infoFiltered: "{{ __('(filtered from _MAX_ total entries)') }}",
        search: "{{__('Search:')}}",
        thousands: ",",
        loadingRecords: "{{ __('Loading...') }}",
        processing: "{{ __('Processing...') }}"
    }

</script>
@include('partials.sidebar')

@include('partials.topnav')
<div class="dash-container">
    <div class="dash-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="row mb-1">
                            <div class="col-xl-5">
                                @if(trim($__env->yieldContent('page-title')))
                                    <div class="page-header-title">
                                        <h4 class="m-b-10">@yield('page-title')</h4>
                                    </div>
                                @endif
                                <ul class="breadcrumb mt-1">
                                    @yield('links')
                                </ul>
                            </div>
                            <div class="col-xl-7">
                                @if(trim($__env->yieldContent('action-button')))
                                    <!-- <div class="col-xl-6 col-lg-2 col-md-4 col-sm-6 col-6 pt-lg-3 pt-xl-2"> -->
                                    <div
                                        class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
                                        @yield('action-button')
                                    </div>
                                    <!-- </div> -->
                                @elseif(trim($__env->yieldContent('multiple-action-button')))
                                    <div
                                        class=" row text-end row d-flex justify-content-end col-auto">  @yield('multiple-action-button')</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @yield('content')

    </div>
</div>

<div class="modal fade" id="commonModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="body">
            </div>

        </div>
    </div>
</div>
@if(Auth::user()->type != 'admin')
    @include('partials._welcome_modal')
{{--    @include('partials._changePassword_modal')--}}

    <div class="modal fade" id="modelCreateWorkspace" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h5 class="modal-title" id="exampleModalLabel"> {{ __('Create Your Workspace') }}</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="body">
                    <div class="modal-body">
                        <form class="" method="post" action="{{ route('add-workspace') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="workspacename" class="col-form-label">{{ __('Name') }}</label>
                                    <input class="form-control" type="text" id="workspacename" name="name" required=""
                                           placeholder="{{ __('Workspace Name') }}">
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn  btn-light text-end"
                                data-dismiss="modal">{{ __('Close')}}</button>
                        <!-- <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close')}}</button> -->
                        <input type="submit" value="{{ __('Create')}}" class="btn  btn-primary">
                    </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

@php
    \App::setLocale(env('DEFAULT_LANG'));
    $currantLang = 'en'
@endphp


<script src="{{ asset('custom/js/site.core.js') }}"></script>
<script src="{{ asset('custom/libs/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/popper.min.js')}}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap.min.js')}}"></script>
<script src="{{asset('custom/js/main.min.js')}}"></script>
<script src="{{ asset('custom/libs/bootstrap-notify/bootstrap-notify.min.js') }}"></script>

<script src="{{ asset('custom/js/custom.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap-switch-button.min.js') }}"></script>
<script src="{{asset('assets/js/plugins/choices.min.js')}}"></script>
<script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js')}}"></script>
<script src="{{ asset('assets/js/plugins/datepicker-full.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/feather.min.js')}}"></script>
<script src="{{ asset('assets/js/dash.js')}}"></script>

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
    // var myModal = new bootstrap.Modal(document.getElementById('myModal'), {})
    // myModal.toggle()
    //
    // var changePassword = new bootstrap.Modal(document.getElementById('changePassword'), {})
    // changePassword.toggle()
    var changePassword = new bootstrap.Modal(document.getElementById('changePassword'), {})
    changePassword.toggle()
    var policyModal = new bootstrap.Modal(document.getElementById('policyModal'), {})
    policyModal.toggle()

    (function () {
        const d_week = new Datepicker(document.querySelector('.datepicker'), {
            buttonClass: 'btn',
            todayBtn: true,
            clearBtn: true
        });
    })();

</script>

@if(env('CHAT_MODULE') == 'on' && isset($currentWorkspace) && $currentWorkspace)
    @auth('web')
        {{-- Pusher JS--}}
        <script src="https://js.pusher.com/5.0/pusher.min.js"></script>
        <script>
            $(document).ready(function () {
                pushNotification('{{ Auth::id() }}');
            });

            function pushNotification(id) {

                // ajax setup form csrf token
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // Enable pusher logging - don't include this in production
                Pusher.logToConsole = false;

                var pusher = new Pusher('{{env('PUSHER_APP_KEY')}}', {
                    cluster: '{{env('PUSHER_APP_CLUSTER')}}',
                    forceTLS: true
                });

                var channel = pusher.subscribe('{{$currentWorkspace->slug}}');
                channel.bind('notification', function (data) {

                    if (id == data.user_id) {
                        $(".notification-toggle").addClass('beep');
                        $(".notification-dropdown .dropdown-list-icons").prepend(data.html);
                    }
                });
                channel.bind('chat', function (data) {
                    if (id == data.to) {
                        getChat();
                    }
                });
            }

            function getChat() {
                $.ajax({
                    url: '{{route('message.data')}}',
                    cache: false,
                    dataType: 'html',
                    success: function (data) {
                        if (data.length) {
                            $(".message-toggle").addClass('beep');
                            $(".dropdown-list-message").html(data);
                            LetterAvatar.transform();
                        }
                    }
                })
            }

            getChat();

            $(document).on("click", ".mark_all_as_read", function () {
                $.ajax({
                    url: '{{route('notification.seen',$currentWorkspace->slug)}}',
                    type: "get",
                    cache: false,
                    success: function (data) {
                        $('.notification-dropdown .dropdown-list-icons').html('');
                        $(".notification-toggle").removeClass('beep');
                    }
                })
            });
            $(document).on("click", ".mark_all_as_read_message", function () {
                $.ajax({
                    url: '{{route('message.seen',$currentWorkspace->slug)}}',
                    type: "get",
                    cache: false,
                    success: function (data) {
                        $('.dropdown-list-message').html('');
                        $(".message-toggle").removeClass('beep');
                    }
                })
            });
        </script>
        {{-- End  Pusher JS--}}
    @endauth
@endif
<script>
    feather.replace();
    var pctoggle = document.querySelector("#pct-toggler");
    if (pctoggle) {
        pctoggle.addEventListener("click", function () {
            if (
                !document.querySelector(".pct-customizer").classList.contains("active")
            ) {
                document.querySelector(".pct-customizer").classList.add("active");
            } else {
                document.querySelector(".pct-customizer").classList.remove("active");
            }
        });
    }

    var themescolors = document.querySelectorAll(".themes-color > a");
    for (var h = 0; h < themescolors.length; h++) {
        var c = themescolors[h];

        c.addEventListener("click", function (event) {
            var targetElement = event.target;
            if (targetElement.tagName == "SPAN") {
                targetElement = targetElement.parentNode;
            }
            var temp = targetElement.getAttribute("data-value");
            removeClassByPrefix(document.querySelector("body"), "theme-");
            document.querySelector("body").classList.add(temp);
        });
    }

    var custthemebg = document.querySelector("#cust-theme-bg");
    custthemebg.addEventListener("click", function () {
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
    });

    var custdarklayout = document.querySelector("#cust-darklayout");
    custdarklayout.addEventListener("click", function () {
        if (custdarklayout.checked) {
            document
                .querySelector("#main-style-link")
                .setAttribute("href", "{{ asset('assets/css/style-dark.css')}}");
            document
                .querySelector(".m-header > .b-brand > .logo-lg")
                .setAttribute("src", "{{asset('assets/images/logo.svg')}}");
        } else {
            document
                .querySelector("#main-style-link")
                .setAttribute("href", "{{ asset('assets/css/style.css')}}");
            document
                .querySelector(".m-header > .b-brand > .logo-lg")
                .setAttribute("src", "{{ asset('assets/images/logo-dark.svg')}}");
        }
    });

    function removeClassByPrefix(node, prefix) {
        for (let i = 0; i < node.classList.length; i++) {
            let value = node.classList[i];
            if (value.startsWith(prefix)) {
                node.classList.remove(value);
            }
        }
    }
</script>
<!-- Site JS -->

<script src="{{ asset('assets/js/plugins/sweetalert2.all.min.js')}}"></script>
<script src="{{ asset('custom/js/ac-alert.js')}}"></script>
<script src="{{ asset('custom/js/letter.avatar.js') }}"></script>
<script src="{{ asset('custom/js/fire.modal.js') }}"></script>

<script src="{{asset('assets/js/plugins/simple-datatables.js')}}"></script>
<script>
    const dataTable = new simpleDatatables.DataTable("#selection-datatable");
</script>

<!-- Demo JS - remove it when starting your project -->
{{--<script src="{{ asset('assets/js/demo.js') }}"></script>--}}

<script>
    var date_picker_locale = {
        format: 'YYYY-MM-DD',
        daysOfWeek: [
            "{{__('Sun')}}",
            "{{__('Mon')}}",
            "{{__('Tue')}}",
            "{{__('Wed')}}",
            "{{__('Thu')}}",
            "{{__('Fri')}}",
            "{{__('Sat')}}"
        ],
        monthNames: [
            "{{__('January')}}",
            "{{__('February')}}",
            "{{__('March')}}",
            "{{__('April')}}",
            "{{__('May')}}",
            "{{__('June')}}",
            "{{__('July')}}",
            "{{__('August')}}",
            "{{__('September')}}",
            "{{__('October')}}",
            "{{__('November')}}",
            "{{__('December')}}"
        ],
    };
    var calender_header = {
        today: "{{__('today')}}",
        month: '{{__('month')}}',
        week: '{{__('week')}}',
        day: '{{__('day')}}',
        list: '{{__('list')}}'
    };
</script>

@if(env('gdpr_cookie')=='on')

    <script type="text/javascript">

        var defaults = {
            'messageLocales': {
                /*'en': 'We use cookies to make sure you can have the best experience on our website. If you continue to use this site we assume that you will be happy with it.'*/
                'en': '{{env('cookie_text')}}'

            },
            'buttonLocales': {
                'en': 'Ok'
            },
            'cookieNoticePosition': 'bottom',
            'learnMoreLinkEnabled': false,
            'learnMoreLinkHref': '/cookie-banner-information.html',
            'learnMoreLinkText': {
                'it': 'Saperne di più',
                'en': 'Learn more',
                'de': 'Mehr erfahren',
                'fr': 'En savoir plus'
            },
            'buttonLocales': {
                'en': 'Ok'
            },
            'expiresIn': 30,
            'buttonBgColor': '#d35400',
            'buttonTextColor': '#fff',
            'noticeBgColor': '#000000',
            'noticeTextColor': '#fff',
            'linkColor': '#009fdd'
        };
    </script>
    <script src="{{ asset('custom/js/cookie.notice.js')}}"></script>
@endif

@if(isset($currentWorkspace) && $currentWorkspace)
    <script src="{{ asset('custom/js/jquery.easy-autocomplete.min.js') }}"></script>
    <script>
        var options = {
            url: function (phrase) {
                return "@auth('web'){{route('search.json',$currentWorkspace->slug)}}@elseauth{{route('client.search.json',$currentWorkspace->slug)}}@endauth/" + phrase;
            },
            categories: [
                {
                    listLocation: "Projects",
                    header: "{{ __('Projects') }}"
                },
                {
                    listLocation: "Tasks",
                    header: "{{ __('Tasks') }}"
                }
            ],
            getValue: "text",
            template: {
                type: "links",
                fields: {
                    link: "link"
                }
            }
        };
        $(".search-element input").easyAutocomplete(options);
    </script>
@endif

<!--  for setting scroling Active -->
<script>
    var multipleCancelButton = new Choices('#choices-multiple-remove-button', {
        removeItemButton: true,
    });
    var scrollSpy = new bootstrap.ScrollSpy(document.body, {
        target: '#useradd-sidenav',
        offset: 300
    })
</script>
<script>
    (function () {
        var switch_event = document.querySelector("#switch_event");

        switch_event.addEventListener('change', function () {
            if (switch_event.checked) {
                document.querySelector("#console_event").innerHTML = "Switch Button Checked";
            } else {
                document.querySelector("#console_event").innerHTML = "Switch Button Unchecked";
            }
        });
    })();
</script>
@stack('scripts')
@if(Session::has('success'))
    <script>
        show_toastr('{{__('Success')}}', '{!! session('success') !!}', 'success');
    </script>
@endif
@if(Session::has('error'))
    <script>
        show_toastr('{{__('Error')}}', '{!! session('error') !!}', 'error');
    </script>
@endif
<script>

</script>
@include('partials.footer')
@include('Chatify::layouts.footerLinks')
</body>
</html>
