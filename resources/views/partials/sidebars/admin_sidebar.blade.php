<li class="dash-item {{ (Request::route()->getName() == 'admin-client-projects-index' || Request::segment(2) == 'projects') ? ' active' : '' }}">
    <a href="{{ route('admin-client-projects-index',$currentWorkspace->slug) }}" class="dash-link"><span
            class="dash-micon"> <i data-feather="briefcase"></i></span><span
            class="dash-mtext">{{ __('Client Projects') }}</span></a>
</li>
<li class="dash-item {{ (Request::route()->getName() == 'lang_workspace') ? ' active' : '' }}">
    <a href="{{ route('lang_workspace') }}" class="dash-link "><span class="dash-micon"><i
                class="ti ti-world nocolor"></i></span><span
            class="dash-mtext">{{ __('Languages') }}</span></a>
</li>


<li class="dash-item {{ (Request::route()->getName() == 'email_template*' || Request::segment(1) == 'email_template_lang') ? ' active' : '' }}">
    <a class="dash-link" href="{{route('email_template.index')}}">
        <span class="dash-micon"><i class="ti ti-mail"></i></span><span
            class="dash-mtext">{{__('Email Templates')}}</span>
    </a>
</li>
<li class="dash-item {{ (Request::route()->getName() == 'settings.index') ? ' active' : '' }}">
    <a href="{{ route('settings.index') }}" class="dash-link "><span class="dash-micon"><i
                data-feather="settings"></i></span><span
            class="dash-mtext"> {{ __('Settings') }}</span></a>
</li>
<li class="dash-item {{ (Request::route()->getName() == 'admin-projects-index' || Request::segment(2) == 'projects') ? ' active' : '' }}">
    <a href="{{ route('admin-projects-index',$currentWorkspace->slug) }}" class="dash-link"><span class="dash-micon"> <i data-feather="briefcase"></i></span><span  class="dash-mtext">{{ __('Projects ') }}</span></a>
</li>
