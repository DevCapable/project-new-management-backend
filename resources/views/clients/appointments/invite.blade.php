<form class="" method="post" action="{{ route('projects.invite.update',[$currentWorkspace->slug,$project->id]) }}">
    @csrf
     <div class="modal-body">
    <div class="form-group col-md-12 mb-0">
        <label for="users_list" class="form-label">{{ __('Users') }}</label>
        <select class=" multi-select" required id="users_list" name="users_list[]" data-toggle="select2" multiple="multiple" data-placeholder="{{ __('Select Users ...') }}">
            @foreach($currentWorkspace->users($currentWorkspace->created_by) as $user)
                @if($user->pivot->is_active)
                    <option value="{{$user->email}}">{{$user->name}} - {{$user->email}}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>
    <div class="modal-footer">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close')}}</button>
        <input type="submit" value="{{ __('Invite')}}" class="btn  btn-primary">
    </div>
</form>
