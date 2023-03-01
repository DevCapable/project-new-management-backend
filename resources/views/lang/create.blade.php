<form class="" method="post" action="{{ route('store_lang_workspace') }}">
    @csrf
     <div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <label for="code" class="col-form-label">{{ __('Language Code') }}</label>
            <input class="form-control" type="text" id="code" name="code" required="" placeholder="{{ __('Language Code') }}">
        </div>
    </div>
</div>
        <div class="modal-footer">
            <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close')}}</button>
            <input type="submit" value="{{ __('Save')}}" class="btn  btn-primary">
        </div>
    
</form>
