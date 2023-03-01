<form class="" method="post" action="{{ route('users.change.password',[$user_id]) }}">
    @csrf
     <div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <label for="password" class="col-form-label">{{ __('New Password')}}</label>
            <input type="password" class="form-control" id="password" name="password"/>
        </div>
        <div class="col-md-12">
            <label for="password_confirmation" class="col-form-label">{{ __('Confirm New Password')}}</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"/>
        </div>
    </div>
</div>
        <div class="modal-footer">
        <!--    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close')}}</button> -->
            <input type="submit" value="{{ __('Reset')}}" class="btn  btn-primary">
        </div>
    
</form>
