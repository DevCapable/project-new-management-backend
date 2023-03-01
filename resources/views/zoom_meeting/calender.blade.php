@extends('layouts.admin')

@section('page-title') {{__('Calendar')}} @endsection
 @section('links')
 @if(\Auth::guard('client')->check())   
<li class="breadcrumb-item"><a href="{{route('client.home')}}">{{__('Home')}}</a></li>
 @else
 <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('Home')}}</a></li>
 @endif
 @if(\Auth::guard('client')->check())   
<li class="breadcrumb-item"><a href="{{route('client.zoom-meeting.index',$currentWorkspace->slug)}}">{{__('Zoom Meeting')}}</a></li>
 @else
 <li class="breadcrumb-item"><a href="{{route('zoom-meeting.index',$currentWorkspace->slug)}}">{{__('Zoom Meeting')}}</a></li>
  @endif
<li class="breadcrumb-item"> {{ __('Calendar') }}</li>
  @endsection
@section('content')
      
<div class="row">
             <!-- [ sample-page] start -->
             <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Calendar</h5>
                                </div>
                                <div class="card-body">
                                    <div id='calendar' class='calendar'></div>
                                </div>
                            </div>
                        </div>

                         <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-4">Meetings</h4>
                                        <ul class="event-cards list-group list-group-flush mt-3 w-100">
                                            @php
                                            $date = Carbon\Carbon::now()->format('m');
                                            $this_month_meeting = App\Models\ZoomMeeting::get();
                                            @endphp

                                            @foreach($this_month_meeting as $meeting)
                                             @php
                                             $month =date('m', strtotime($meeting->start_date));
                                            @endphp
                                            @if($date == $month)
                                            <li class="list-group-item card mb-3">
                                                <div class="row align-items-center justify-content-between">
                                                    <div class="col-auto mb-3 mb-sm-0">
                                                        <div class="d-flex align-items-center">
                                                            <div class="theme-avtar bg-primary">
                                                                <i class="fa fa-tasks"></i>
                                                            </div>
                                                            <div class="ms-3">
                                                            <h6 class="m-0">{{$meeting->title}}</h6>
                                                            <small class="text-muted">{{$meeting->start_date}} </small>
                                                            </div>
                                                           
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </li>  
                                            @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                           </div>
                       </div>

@endsection

@if($currentWorkspace)
    @push('css-page')
   
    @endpush
    @push('scripts')
    
        <script>
 (function () {
        var etitle;
        var etype;
        var etypeclass;
        var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
             buttonText: {
            timeGridDay: "{{__('Day')}}",
            timeGridWeek: "{{__('Week')}}",
            dayGridMonth: "{{__('Month')}}"
        },
            themeSystem: 'bootstrap',
            slotDuration: '00:10:00',
            navLinks: true,
            droppable: true,
            selectable: true,
            selectMirror: true,
            editable: true,
            dayMaxEvents: true,
            handleWindowResize: true,
            events: {!! ($calandar) !!},
        });
        calendar.render();
    })();

        </script>
    @endpush
@endif
