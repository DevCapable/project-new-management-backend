@component('mail::message')
# {{ __('Hello')}}, {{ $user->name != 'No Name' ? $user->name : '' }}

{{ __('You are invited into new Workspace')}} <b> {{ $workspace->name }}</b> {{ __('by')}} {{ $workspace->creater->name }}

@component('mail::button', ['url' => route('home',[$workspace->slug])])
    {{ __('Open Workspace')}}
@endcomponent

{{ __('Thanks')}},<br>
{{ config('app.name') }}
@endcomponent
