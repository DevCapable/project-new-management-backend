<form method="POST" action="{{ route('users.store') }}">
    @csrf
     <div class="modal-body">
    <div class="form-group">
        <label for="fullname" class="form-label">{{ __('Full Name') }}</label>
        <input class="form-control" name="name" type="text" id="fullname" placeholder="{{ __('Enter Your Name') }}" value="{{ old('name') }}" required autocomplete="name">
    </div>

    <div class="form-group">
        <label for="emailaddress" class="form-label">{{ __('Email Address') }}</label>
        <input class="form-control" name="email" type="email" id="emailaddress" required autocomplete="email" placeholder="{{ __('Enter Your Email') }}" value="{{ old('email') }}">
    </div>
    <div class="form-group">
        <label for="password" class="form-label">{{ __('Password') }}</label>
        <input class="form-control" name="password" type="password" required autocomplete="new-password" id="password" placeholder="{{ __('Enter Your Password') }}">
    </div>
</div>
    <div class="modal-footer">
           <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close')}}</button>
            <input type="submit" value="{{ __('Save Changes')}}" class="btn  btn-primary">
    </div>
</form>
