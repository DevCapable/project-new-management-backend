@component('mail::message')
# {{ __('Hello')}}, {{ $user->name != 'No Name' ? $user->name : '' }}

{{ __('You just initiated a new project')}} <b> {{ $project->name }}</b> {{ __('by')}} {{ $project->createrClient->name }}

@component('mail::button', ['url' => route('home',[$project->slug])])
    {{ __('Open Workspace')}}
@endcomponent

{{ __('Thanks')}},<br>
{{ config('app.name') }}
@endcomponent
