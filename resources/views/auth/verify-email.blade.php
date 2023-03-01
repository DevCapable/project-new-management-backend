<x-guest-layout>
    <x-auth-card>      
@section('page-title') {{__('Verify')}} @endsection
@section('content')
    <div class="login-form">
        <div class="page-title"><h5>{{ __('Mail Sent') }}</h5></div>
        <p class="text-muted mb-4">{{ __('Please confirm your password before continuing.') }}</p>
        @if (session('resent'))
            <div class="alert alert-success" role="alert">
                {{ __('A fresh verification link has been sent to your email address.') }}
            </div>
        @endif

        {{ __('Please check for an email from company and click on the included link to reset your password.') }}
        {{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.
    </div>

@endsection

    </x-auth-card>
</x-guest-layout>
