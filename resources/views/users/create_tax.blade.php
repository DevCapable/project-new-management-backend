<form class="" method="post" action="{{ route('tax.store',[$currentWorkspace->slug]) }}">
    @csrf
     <div class="modal-body">
    <div class="form-group">
        <label for="name" class="col-form-label">{{ __('Name') }}</label>
        <input type="text" class="form-control" id="name" name="name" required/>
    </div>
    <div class="form-group">
        <label for="rate" class="col-form-label">{{ __('Rate') }}</label>
        <input type="number" class="form-control" id="rate" name="rate" min="0" step=".01" required/>
    </div>
</div>
    <div class=" modal-footer">
      
         <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close')}}</button>
         <input type="submit" value="{{ __('Save Changes')}}" class="btn  btn-primary">
    </div>
</form>
 