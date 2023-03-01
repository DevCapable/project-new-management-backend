
<li class="dash-item {{ (Request::route()->getName() == 'admin-client-projects-index' || Request::segment(2) == 'projects') ? ' active' : '' }}">
    <a href="{{ route('admin-client-projects-index',$currentWorkspace->slug) }}" class="dash-link"><span
            class="dash-micon"> <i data-feather="briefcase"></i></span><span
            class="dash-mtext">{{ __('Client Projects') }}</span></a>
</li>
<li class="dash-item {{ (Request::route()->getName() == 'admin-projects-index' || Request::segment(2) == 'projects') ? ' active' : '' }}">
    <a href="{{ route('admin-projects-index',$currentWorkspace->slug) }}" class="dash-link"><span
            class="dash-micon"> <i data-feather="briefcase"></i></span><span
            class="dash-mtext">{{ __('My Projects') }}</span></a>
</li>
@if(isset($currentWorkspace) && $currentWorkspace  && Auth::user()->getGuard() != 'client')

    <li class="dash-item {{ (Request::route()->getName() == 'workspace.settings') ? ' active' : '' }}">
        <a href="{{ route('workspace.settings',$currentWorkspace->slug) }}" class="dash-link "><span
                class="dash-micon"><i data-feather="settings"></i></span><span
                class="dash-mtext">{{ __('Settings') }}</span></a>
    </li>
@endif

