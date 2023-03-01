@component('mail::message')
# {{ __('Hello')}}, {{ $user->name != 'No Name' ? $user->name : '' }}

{{ __('You have been assigned to a new task')}} <b> {{ $task->title }}</b> {{ __('attached to a project, ')}} <b>{{ $task->project->name }}
<br>
{{ __('Please react to this as soon as possible')}}
@component('mail::button', ['url' => route('home',[$task->slug])])
    {{ __('Open Workspace')}}
@endcomponent

{{ __('Thanks')}},<br>
{{ config('app.name') }}
@endcomponent
