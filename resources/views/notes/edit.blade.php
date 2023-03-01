@php
$setting = App\Models\Utility::getAdminPaymentSettings();
if ($setting['color']) {
    $color = $setting['color'];
    
}
else{
  $color = 'theme-4';  
}
@endphp

<form class="" method="post" action="{{ route('notes.update',[$currentWorkspace->slug,$note->id]) }}">
    @csrf
     <div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <label for="title" class="col-form-label">{{ __('Title') }}</label>
            <input class="form-control" type="text" id="title" name="title" placeholder="{{ __('Enter Title') }}" value="{{$note->title}}" required>
        </div>
        <div class="col-md-12">
            <label for="description" class="col-form-label">{{ __('Description') }}</label>
            <textarea class="form-control" id="description" name="text" placeholder="{{ __('Enter Description') }}" required>{{$note->text}}</textarea>
        </div>
        <div class="col-md-12">
            <label for="color" class="col-form-label">{{__('Color')}}</label>
            <select class="form-control select2" name="color" id="color" required>
                <option value="bg-primary">{{ __('Primary')}}</option>
                <option @if($note->color == 'bg-secondary') selected @endif value="bg-secondary">{{ __('Secondary')}}</option>
                <option @if($note->color == 'bg-info') selected @endif value="bg-info">{{ __('Info')}}</option>
                <option @if($note->color == 'bg-warning') selected @endif value="bg-warning">{{ __('Warning')}}</option>
                <option @if($note->color == 'bg-danger') selected @endif value="bg-danger">{{ __('Danger')}}</option>
            </select>
        </div>


          <div class="col-md-12">
            <label for="type" class="col-form-label">{{__('Type')}}</label>
           <div class="selectgroup w-50 ">
                <label class="selectgroup-item">
                    <input type="radio" name="type" value="personal" class="selectgroup-input" {{ $note->type == 'personal' ? 'checked="checked"' : '' }}>
                    <span class="selectgroup-button">{{ __('Personal') }}</span>
                </label>
                <label class="selectgroup-item">
                    <input type="radio" name="type" value="shared" class="selectgroup-input" {{ $note->type == 'shared' ? 'checked="checked"' : '' }}>
                    <span class="selectgroup-button">{{ __('Shared') }}</span>
                </label>
            </div>
        </div>



        <div class="col-md-12 assign_to_selection">
            <label for="assign_to" class="col-form-label">{{__('Assign to')}}</label>
            <select     id="assign_to"    name="assign_to[]" class="multi-select" data-toggle="select2" multiple="multiple" data-placeholder="{{ __('Select Users ...') }}"  >
                @foreach($users as $u)
                        <option value="{{$u->id}}" @if(in_array($u->id, $note->assign_to)) selected @endif>{{$u->name}} - {{$u->email}}</option>
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
<script>
    $(document).ready(function() {
        $('#{{ $note->type }}').trigger('click');
    });
</script>
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

