{{ Form::model($timesheet, ['route' => ['project.timesheet.update',['slug' => $currentWorkspace->slug, 'timesheet_id' => $timesheet->id, 'project_id' => $project_id]], 'method' => 'POST']) }}
<div class="modal-body">
<input type="hidden" name="project_id" value="{{ $parseArray['project_id'] }}">
<input type="hidden" name="task_id" value="{{ $parseArray['task_id'] }}">
<input type="hidden" name="date" value="{{ $timesheet->date }}">
<input type="hidden" id="totaltasktime" value="{{ $parseArray['totaltaskhour'] . ':' . $parseArray['totaltaskminute'] }}">

<div class="form-group">
    <label class="col-form-label">{{ __('Project')}}</label>
    <input type="text" class="form-control" value="{{ $parseArray['project_name'] }}" disabled="disabled">
</div>

<div class="form-group">
    <label class="col-form-label">{{ __('Task')}}</label>
    <input type="text" class="form-control" value="{{ $parseArray['task_name'] }}" disabled="disabled">
</div>

<div class="row">
    <div class="col-md-12">
        <label for="time" class="col-form-label">{{ __('Time')}}</label>
    </div>
    <div class="col-md-6">
        <select class="form-control select2" name="time_hour" id="time_hour" required="">
            <option value="">{{ __('Hours') }}</option>

            <?php for ($i = 0; $i < 23; $i++) { $i = $i < 10 ? '0' . $i : $i; ?>

            <option value="{{ $i }}" {{ $parseArray['time_hour'] == $i ? 'selected="selected"' : '' }}>{{ $i }}</option>

            <?php } ?>

        </select>
    </div>

    <div class="col-md-6">
        <select class="form-control select2" name="time_minute" id="time_minute" required>
            <option value="">{{ __('Minutes')}}</option>

            <?php for ($i = 0; $i < 61; $i += 10) { $i = $i < 10 ? '0' . $i : $i; ?>

            <option value="{{ $i }}" {{ $parseArray['time_minute'] == $i ? 'selected="selected"' : '' }}>{{ $i }}</option>

            <?php } ?>

        </select>
    </div>

    <div class="col-md-12 mt-1">
        <label for="description" class="col-form-label">{{ __('Description')}}</label>
        <textarea class="form-control " id="description" rows="3" name="description">{{ $timesheet->description }}</textarea>
    </div>
    <div class="col-md-12">
        <div class="display-total-time">
            <i class="fas fa-clock"></i>
            <span>{{ __('Total Time') }} : {{ $parseArray['totaltaskhour'] . ' ' . __('Hours') . ' ' . $parseArray['totaltaskminute'] . ' ' . __('Minutes') }}</span>
        </div>
    </div>

    @php($id = str_replace('.', '', uniqid('', true)))
</div>
</div>

    <div class="col-md-12 py-4">
        <div class="row">
        <div class="col-6 px-6">
         <a href="#" onclick="(confirm('{{__('Are you sure ?')}}')?document.getElementById('delete-form-{{$id}}').submit(): '');" class="action-btn btn-danger  btn btn-sm d-inline-flex align-items-center text-start mx-3">
        <i class="ti ti-trash"></i>
        </a>
        </div>
        <div class="col-6">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close')}}</button>
        <input type="submit" value="{{ __('Save Changes')}}" class="btn  btn-primary">
       </div>
</div>
       
    </div>


{{ Form::close() }}

<form id="delete-form-{{$id}}" class="" action="{{ route('timesheet.destroy',[$currentWorkspace->slug,$timesheet->id]) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

