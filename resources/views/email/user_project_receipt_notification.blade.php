@component('mail::message')
# {{ __('Hello')}}, {{ $user->name != 'No Name' ? $user->name : '' }}

{{ __('This amount '.$invoicePayments->amount_paid.' has been paid successfuly for the execution of your project named ')}} <b> {{ $project->name }}</b> {{ __('and this payment is liable to expired as soon as theres expansion. This project was initiated  by')}} {{ $project->creater->name }}
<br>

@component('mail::button', ['url' => route('home',[$project->slug])])
    {{ __('Go to your profile')}}
@endcomponent


{{ config('app.name') }}<br>
{{ __('Regards')}},
@endcomponent
