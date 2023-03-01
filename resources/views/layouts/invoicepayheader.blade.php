  @php 

    $setting = App\Models\Utility::getcompanySettings($currentWorkspace->id);
    $color = $setting->theme_color;
    $dark_mode = $setting->cust_darklayout; 
    $SITE_RTL = $setting->site_rtl;
    $cust_theme_bg = $setting->cust_theme_bg;


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

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{env('SITE_RTL') == 'on'?'rtl':''}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
      
    </title>

    <link rel="shortcut icon" href="{{asset(Storage::url('logo/favicon.png'))}}">

    <!-- Font Awesome 5 -->
    <link rel="stylesheet" href="{{ asset('custom/libs/@fontawesome/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/libs/animate.css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/libs/bootstrap-timepicker/css/bootstrap-timepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/libs/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/libs/select2/dist/css/select2.min.css') }}">

    @stack('css-page')

    <link rel="stylesheet" href="{{ asset('assets/css/style.css')}}" id="main-style-link">
    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css')}}">
    <link rel="stylesheet" href="{{ asset('custom/css/custom.css')}}">

    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dragula.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/landing.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/plugins/animate.min.css')}}" />

      <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css')}}">


</head>


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
                      </div>
                      <div class="col-xl-7">
                          @if(trim($__env->yieldContent('action-button')))
                                <!-- <div class="col-xl-6 col-lg-2 col-md-4 col-sm-6 col-6 pt-lg-3 pt-xl-2"> -->
                                    <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
                                        @yield('action-button')
                                    </div>
                                <!-- </div> -->
                            @elseif(trim($__env->yieldContent('multiple-action-button')))
                                <div class='row text-end row d-flex justify-content-end col-auto'>  @yield('multiple-action-button')</div>
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
 </div> 




@php
    \App::setLocale(env('DEFAULT_LANG'));
    $currantLang = 'en'
@endphp

<!-- Scripts -->
<!-- Core JS - includes jquery, bootstrap, popper, in-view and sticky-kit -->

<script src="{{ asset('custom/js/site.core.js') }}"></script>

<script src="{{ asset('custom/libs/progressbar.js/dist/progressbar.min.js') }}"></script>
<script src="{{ asset('custom/libs/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('custom/libs/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
<script src="{{ asset('custom/libs/bootstrap-timepicker/js/bootstrap-timepicker.js') }}"></script>
<script src="{{ asset('custom/libs/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('custom/libs/select2/dist/js/select2.min.js') }}"></script>
<script src="{{ asset('custom/libs/nicescroll/jquery.nicescroll.min.js')}} "></script>
<script src="{{ asset('custom/libs/apexcharts/dist/apexcharts.min.js')}}"></script>

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

<!-- Site JS -->
<script src="{{ asset('custom/js/letter.avatar.js') }}"></script>
<script src="{{ asset('custom/js/fire.modal.js') }}"></script>
<script src="{{ asset('custom/js/site.js') }}"></script>
<script src="{{ asset('custom/js/jquery.dataTables.min.js') }}"></script>
<!-- Demo JS - remove it when starting your project -->
{{--<script src="{{ asset('assets/js/demo.js') }}"></script>--}}
<script src="{{ asset('custom/js/custom.js') }}"></script>
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
    'noticeBgColor': 'var(--primary)',
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

</body>
</html>
