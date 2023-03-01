<x-guest-layout>
    <x-auth-card>
    
@section('page-title') {{__('Confirm Password')}} @endsection

@section('content')

    <div class="login-form">
        <div class="page-title"><h5>{{ __('Confirm Password') }}</h5></div>
        <p class="text-muted mb-4">{{ __('Please confirm your password before continuing.') }}</p>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="form-group">
                <label for="password" class="form-control-label">{{ __('Email') }}</label>
                <input class="form-control @error('password') is-invalid @enderror" type="password" name="password" id="password" required autofocus>
                @error('password')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <button type="submit" class="btn-login">{{ __('Confirm Password') }}</button>
        </form>
    </div>

@endsection

    </x-auth-card>
</x-guest-layout>
