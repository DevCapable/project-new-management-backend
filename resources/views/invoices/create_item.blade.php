<form method="post" action="{{ route('invoice.item.store',[$currentWorkspace->slug,$invoice->id]) }}">
    @csrf
     <div class="modal-body">
    <div class="col-md-12">
        <label for="task" class="col-form-label">{{__('Tasks')}}</label>
        <select class="form-control select2" name="task" id="task" required>
            <option value="">{{__('Select Task')}}</option>
            @foreach($invoice->project->tasks() as $task)
                <option value="{{$task->id}}">{{$task->title}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-12">
        <label for="price" class="col-form-label">{{__('Price')}}</label>
        <div class="form-icon-user">
            <span class="currency-icon bg-primary">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$' }}</span>
            <input class="form-control currency_input" type="number" min="0" value="0" id="price" name="price" required>
        </div>
    </div>
</div>
    <div class=" modal-footer">
      <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close')}}</button>
         <input type="submit" value="{{ __('Save Changes')}}" class="btn  btn-primary">
    </div>
</form>
