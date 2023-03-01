<div>
    <h2>Reset Password Notification</h2>
    <h3> You are receiving this email because we received a password reset request for your account.</h3>
    <p>Please click on the link below to reset your password</p>
        <p>{{ $action_link}}</p>
        <p> {{ __('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')])}}
        <h3>If you did not request a password reset, no further action is required</h3>

        {{ __('Thanks')}}
</div>
