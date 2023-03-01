{{--<li class="dash-item dash-hasmenu">--}}
{{--    <a href="{{route('client-appointment-index',$currentWorkspace->slug)}}"--}}
{{--       class="dash-link  {{ (Request::route()->getName() == 'appointment' || Request::route()->getName() == 'appointment.index') ? ' active' : '' }}">--}}
{{--        @if(Auth::user()->type == 'admin')--}}
{{--            <span class="dash-micon"><i class="ti ti-calendar"></i></span>--}}
{{--            <span class="dash-mtext">{{ __('Appointments') }}</span>--}}
{{--        @else--}}
{{--            <span class="dash-micon"><i class="ti ti-calendar"></i></span>--}}
{{--            <span class="dash-mtext">{{ __('Appointments') }}</span>--}}
{{--        @endif--}}
{{--    </a>--}}
{{--</li>--}}
<li class="dash-item {{ (Request::route()->getName() == 'client-projects-index' || Request::segment(3) == 'projects') ? ' active' : '' }}">
    <a href="{{ route('client-projects-index',$currentWorkspace->slug) }}"
       class="dash-link "><span class="dash-micon"><i data-feather="briefcase"></i></span><span
            class="dash-mtext">{{ __('Projects') }}</span></a>
</li>

{{--<li class="dash-item {{ (Request::route()->getName() == 'client-task-index') ? ' active' : '' }}">--}}
{{--    <a href="{{ route('client-task-index',$currentWorkspace->slug) }}" class="dash-link "><span--}}
{{--            class="dash-micon"><i data-feather="list"></i></span><span--}}
{{--            class="dash-mtext">{{ __('Tasks') }}</span></a>--}}
{{--</li>--}}

{{--<li class="dash-item {{ (Request::route()->getName() == 'calender.index') ? ' active' : '' }}">--}}
{{--    <a href="{{route('calender.index',$currentWorkspace->slug)}}" class="dash-link "><span--}}
{{--            class="dash-micon"><i data-feather="calendar"></i></span><span--}}
{{--            class="dash-mtext">{{ __('Calendar') }}</span></a>--}}
{{--</li>--}}
{{--<li class="dash-item {{ (Request::route()->getName() == 'notes.index') ? ' active' : '' }}">--}}
{{--    <a href="{{route('notes.index',$currentWorkspace->slug)}}" class="dash-link "><span--}}
{{--            class="dash-micon"><i data-feather="clipboard"></i></span><span--}}
{{--            class="dash-mtext">{{ __('Notes') }} </span></a>--}}
{{--</li>--}}
@if(env('CHAT_MODULE') == 'on')
    <li class="dash-item {{ (Request::route()->getName() == 'chats') ? ' active' : '' }}">
        <a href="{{route('chats')}}" class="dash-link"><span class="dash-micon"><i
                    class="ti ti-message-circle"></i></span><span
                class="dash-mtext">{{ __('Messenger') }}</span></a>

    </li>
@endif


<li class="dash-item {{ (Request::route()->getName() == 'client.invoices.index') ? ' active' : '' }}">
    <a href="{{ route('client.invoices.index',$currentWorkspace->slug) }}"
       class="dash-link "><span class="dash-micon"><i
                data-feather="printer"></i></span><span
            class="dash-mtext">{{ __('Invoices') }} </span></a>
</li>
{{--<li class="dash-item {{ (Request::route()->getName() == 'timesheet.index') ? ' active' : '' }}">--}}
{{--    <a href="{{route('timesheet.index',$currentWorkspace->slug)}}" class="dash-link "><span--}}
{{--            class="dash-micon"><i data-feather="clock"></i></span><span--}}
{{--            class="dash-mtext">{{ __('Timesheet') }}</span></a>--}}
{{--</li>--}}


<li class="dash-item {{ (Request::route()->getName() == 'client.project_report.index' || Request::segment(3) == 'project_report') ? ' active' : '' }}">
    <a href="{{ route('client.project_report.index',$currentWorkspace->slug) }}"
       class="dash-link "><span class="dash-micon"><i
                class="ti ti-chart-line"></i></span><span
            class="dash-mtext">{{ __('Project Report') }}</span></a>
</li>


<li class="dash-item {{ (Request::route()->getName() == 'client.zoom-meeting.index') ? ' active' : '' }}">
    <a href="{{route('client.zoom-meeting.index',$currentWorkspace->slug)}}"
       class="dash-link "><span
            class="dash-micon"><i data-feather="video"></i></span><span
            class="dash-mtext">{{ __('Zoom Meeting') }}</span></a>

</li>
