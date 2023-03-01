@php
$setting = App\Models\Utility::getAdminPaymentSettings();
if ($setting['color']) {
    $color = $setting['color'];
    
}
else{
  $color = 'theme-4';  
}
@endphp
<form class="" method="post" action="{{ route('notes.store',$currentWorkspace->slug) }}">
    @csrf 
     <div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <label for="title" class="col-form-label">{{ __('Title') }}</label>
            <input class="form-control" type="text" id="title" name="title" placeholder="{{ __('Enter Title') }}" required>
        </div>
        <div class="col-md-12">
            <label for="description" class="col-form-label">{{ __('Description') }}</label>
            <textarea class="form-control" id="description" name="text" placeholder="{{ __('Enter Description') }}" required></textarea>
        </div>
        <div class="col-md-12">
            <label for="color" class="col-form-label">{{__('Color')}}</label>
            <select class="form-control select2" name="color" id="color" required>
                <option value="bg-primary">{{ __('Primary')}}</option>
                <option value="bg-secondary">{{ __('Secondary')}}</option>
                <option value="bg-info">{{ __('Info')}}</option>
                <option value="bg-warning">{{ __('Warning')}}</option>
                <option value="bg-danger">{{ __('Danger')}}</option>
            </select>
        </div>
        <div class="col-md-12">
            <label for="type" class="col-form-label">{{__('Type')}}</label>
           <div class="selectgroup w-50 ">
                <label class="selectgroup-item">
                    <input type="radio" name="type" value="personal" class="selectgroup-input" checked="checked">
                    <span class="selectgroup-button">Personal</span>
                </label>
                <label class="selectgroup-item">
                    <input type="radio" name="type" value="shared" class="selectgroup-input">
                    <span class="selectgroup-button">Shared</span>
                </label>
            </div>
        </div>

             

        <div class="col-md-12 assign_to_selection">
            <label for="assign_to" class="col-form-label">{{__('Assign to')}}</label>
            <select     id="assign_to"    name="assign_to[]" class="multi-select" data-toggle="select2" multiple="multiple" data-placeholder="{{ __('Select Users ...') }}"  >
                @foreach($users as $u)
                    <option value="{{$u->id}}">{{$u->name}} - {{$u->email}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
        <div class="modal-footer">
          <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close')}}</button>
             <input type="submit" value="{{ __('Save Changes')}}" class="btn  btn-primary">
        </div>
    
</form>


<script type="text/javascript">
    
    if ($(".multi-select").length > 0) {
            $( $(".multi-select") ).each(function( index,element ) {
                var id = $(element).attr('id');
                   var multipleCancelButton = new Choices(
                        '#'+id, {
                            removeItemButton: true,
                        }
                    );
            });
       }

</script>

@if($color == "theme-1")
<style type="text/css">
    .selectgroup-input:checked + .selectgroup-button {
    background-color: #51459d !important;
}
.selectgroup-button {
    
    border-color: #51459d !important;
    }
</style>
@endif

@if($color == "theme-2")
<style type="text/css">
    .selectgroup-input:checked + .selectgroup-button {
    background-color: #1f3996 !important;
}
.selectgroup-button {
    
    border-color: #1f3996 !important;
    }
</style>
@endif
@if($color == "theme-3")
<style type="text/css">
    .selectgroup-input:checked + .selectgroup-button {
    background-color: #6fd943 !important;
}
.selectgroup-button {
    
    border-color: #6fd943 !important;
    }
</style>
@endif
@if($color == "theme-4")
<style type="text/css">
    .selectgroup-input:checked + .selectgroup-button {
    background-color: #584ed2 !important;
}
.selectgroup-button {
    
    border-color: #584ed2 !important;
    }
</style>
@endif