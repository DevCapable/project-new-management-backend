<form class="" method="post" action="{{ route('update-client-project',[$currentWorkspace->slug,$project->project_id]) }}">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="form-group col-md-12">
                <label for="projectname" class="form-label">{{ __('Name') }}</label>
                <input class="form-control" type="text" id="projectname" name="name" required="" placeholder="{{ __('Project Name') }}" value="{{$project->name}}">
            </div>
            <div class="form-group col-md-12">
                <label for="description" class="form-label">{{ __('Description') }}</label>
                <textarea class="form-control" id="description" name="description" required="" placeholder="{{ __('Add Description') }}">{{$project->description}}</textarea>
            </div>
            {{--        <div class="form-group col-md-6">--}}
            {{--            <label for="status" class="form-label">{{ __('Status') }}</label>--}}
            {{--            <select id="status" name="status" class="form-control select2">--}}
            {{--                <option value="Ongoing">{{ __('Ongoing') }}</option>--}}
            {{--                <option value="Finished" @if($project->status == 'Finished') selected @endif>{{ __('Finished') }}</option>--}}
            {{--                <option value="OnHold" @if($project->status == 'OnHold') selected @endif>{{ __('OnHold') }}</option>--}}
            {{--            </select>--}}
            {{--        </div>--}}

            <div class="form-group col-md-12">
                <label for="budget" class="form-label">{{ __('Budget') }}</label>
                <div class="form-icon-user ">
                    <span class="currency-icon bg-primary ">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'}}</span>
                    <input class="form-control currency_input" type="number" min="0" id="budget" name="budget" value="{{$project->budget}}" placeholder="{{ __('Project Budget') }}">
                </div>
            </div>
            <div class="form-group col-md-6">
                <label class="form-label">{{ __('Start Date') }}</label>


                <div class="input-group date ">
                    <input class="form-control datepicker2" type="text" id="start_date" name="start_date" value="{{$project->start_date}}" autocomplete="off" required="required">
                    <span class="input-group-text">
                 <i class="feather icon-calendar"></i>
            </span>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label class="form-label">{{ __('End Date') }}</label>
                <div class="input-group date ">
                    <input class="form-control datepicker3" type="text" id="end_date" name="end_date" value="{{$project->end_date}}" autocomplete="off" required="required">
                    <span class="input-group-text">
                 <i class="feather icon-calendar"></i>
            </span>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close')}}</button>
        <button type="submit" name="action" value="submit" class="btn  btn-danger right">{{ __('Submit project for review')}}</button>
        <button type="submit" name="action" value="save" class="btn  btn-primary right">{{ __('Save changes')}}</button>
    </div>

</form>

<script>
    (function () {
        const d_week = new Datepicker(document.querySelector('.datepicker2'), {
            buttonClass: 'btn',
            todayBtn: true,
            clearBtn: true,
            format: 'yyyy-mm-dd',
        });
    })();
</script>

<script>
    (function () {
        const d_week = new Datepicker(document.querySelector('.datepicker3'), {
            buttonClass: 'btn',
            todayBtn: true,
            clearBtn: true,
            format: 'yyyy-mm-dd',
        });
    })();
</script>
