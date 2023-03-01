<form class="" method="post" action="{{ route('admin-client-update-project-review',[$currentWorkspace->slug,$project->project_id]) }}">
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
        <div class="form-group col-md-12">
            <label for="status" class="form-label">{{ __('Update Status') }}</label>
            <select id="status" name="status" class="form-control select2">
                <option value="CostFixed">{{ __('CostFixed') }}</option>
                <option value="NotSubmitted" @if($project->status == 'NotSubmitted') selected @endif>{{ __('NotSubmitted') }}</option>
                <option value="UnderReview" @if($project->status == 'UnderReview') selected @endif>{{ __('UnderReview') }}</option>
                <option value="Processing" @if($project->status == 'Processing') selected @endif>{{ __('Processing') }}</option>
                <option value="Finished" @if($project->status == 'Finished') selected @endif>{{ __('Finished') }}</option>
                <option value="PENDING" @if($project->status == 'PENDING') selected @endif>{{ __('PENDING') }}</option>


            </select>
        </div>

{{--        <div class="form-group col-md-12">--}}
{{--            <label class="col-form-label">{{ __('Assign To')}}</label>--}}

{{--            <select class=" multi-select" id="assign_to" name="assign_to[]" data-toggle="select2" multiple="multiple" data-placeholder="{{ __('Select Users ...') }}" required>--}}
{{--                @foreach($users as $u)--}}
{{--                    <option value="{{$u->id}}">{{$u->name}} - {{$u->email}}</option>--}}
{{--                @endforeach--}}
{{--            </select>--}}
{{--        </div>--}}

        <div class="form-group " style="background-color:limegreen; padding: 5px; border-radius: 10px; font-weight: bold">
            <h3>Required Amount</h3>
            <label for="is_paid" class="col-form-label">{{ __('Paid') }}</label>
            <input  type="radio" id="proj_fee" name="proj_fee" value="paid">
            <label for="is_paid" class="col-form-label">{{ __('Not paid') }}</label>
            <input  type="radio" id="proj_fee" name="proj_fee" value="not_paid">
            <div class="row">
                <div class="col-md-6">
                    <input class="form-control"  type="text" id="amount_required" name="amount_required" value="">
                    <input class="form-control" type="hidden" id="project_id" name="project_id" value="{{$project->project_id}}">

                </div>
                <div class="col-md-6">
                    <select class="form-control"  type="text" id="currency" name="currency">
                        <option class="form-control" value="NGN">NGN</option>
                        <option class="form-control" value="USD">USD</option>
                    </select>
                </div>
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
             <input type="submit" value="{{ __('Save Changes')}}" class="btn  btn-primary">
        </div>

</form>

<script>
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
