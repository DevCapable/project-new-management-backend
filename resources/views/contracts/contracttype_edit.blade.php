<form class="" method="post" action="{{ route('contract_type.update',[$currentWorkspace->slug,$contractsType->id]) }}">
    @csrf
    @method('PUT')
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            <label for="name" class="col-form-label">{{ __('Contract Type Name') }}</label>
            <input class="form-control" type="text"  name="name"  value="{{$contractsType->name}}">
        </div>
    </div>             
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{__('Close')}}</button>
    <button type="submit" class="btn  btn-primary">{{__('Update')}}</button>
</div>
    
</form>