@component('mail::message')
# {{ __('Hello')}}, {{ $user->name != 'No Name' ? $user->name : '' }}

{{ __('You are expected to make this payment '.$project->amount_required.' in order to kickstart the processing of your project named ')}} <b> {{ $project->name }}</b> {{ __('and this payment expired soon. this project was initiated  by')}} {{ $project->createrClient->name.'dont know if this is really you, if yes kindly proceed to avoid to avoid being nullified thanks' }}
<br>
{{__('Please click link below to make payment')}}<br>
{{$project->payment_links}}
{{--@component('mail::button', ['url' => $project->payment_links])--}}
{{--    {{ __('Pay Now')}}--}}
{{--@endcomponent--}}

@component('mail::button', ['url' => route('home',[$project->slug])])
    {{ __('Go to your profile')}}
@endcomponent

{{ __('Thanks')}},<br>
{{ config('app.name') }}
@endcomponent
