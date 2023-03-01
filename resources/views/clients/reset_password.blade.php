<form class="" method="post" action="{{ route('client.change.password',[$currentWorkspace->slug,$client->id]) }}">
    @csrf
     <div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <label for="password" class="col-form-label">{{ __('Password')}}</label>
            <input type="password" class="form-control" id="password" name="password"/>
        </div>
        <div class="col-md-12">
            <label for="password_confirmation" class="col-form-label">{{ __('Confirm Password')}}</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"/>
        </div>
    </div>
</div>
        <div class=" modal-footer">
             <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close')}}</button>
             <input type="submit" value="{{ __('Save Changes')}}" class="btn  btn-primary">
            
        </div>
    
</form>
