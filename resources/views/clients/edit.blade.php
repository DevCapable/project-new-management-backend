<form class="" method="post" action="{{ route('clients.update',[$currentWorkspace->slug,$client->id]) }}">
    @csrf
    <div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <label for="name" class="col-form-label">{{ __('Name') }}</label>
            <input class="form-control" type="text" id="name" name="name" required="" placeholder="{{ __('Enter Name') }}" value="{{$client->name}}">
        </div>
        <div class="col-md-12">
            <label for="password" class="col-form-label">{{ __('Password') }}</label>
            <input class="form-control" type="text" id="password" name="password" required="" placeholder="{{ __('Enter Password') }}">
        </div>
        </div>
        </div>
       <div class=" modal-footer">
             <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close')}}</button>
             <input type="submit" value="{{ __('Save Changes')}}" class="btn  btn-primary">
            
        </div>
    
</form>
