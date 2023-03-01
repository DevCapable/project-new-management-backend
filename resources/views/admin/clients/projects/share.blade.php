<form class="" method="post" action="{{ route('projects.share',[$currentWorkspace->slug,$project->id]) }}">
    @csrf
     <div class="modal-body">
    <div class=" col-md-12 mb-0">
        <label for="users_list" class="col-form-label">{{ __('Clients') }}</label>
        <select class="multi-select" id="clients" data-toggle="select2" required name="clients[]" multiple="multiple" data-placeholder="{{ __('Select Clients ...') }}">
            @foreach($currentWorkspace->clients as $client)
                @if($client->pivot->is_active)
                    <option value="{{$client->id}}">{{$client->name}} - {{$client->email}}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>
    <div class="modal-footer">
           <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close')}}</button>
             <input type="submit" value="{{ __('Share to Client')}}" class="btn  btn-primary">
        </div>
</form>
