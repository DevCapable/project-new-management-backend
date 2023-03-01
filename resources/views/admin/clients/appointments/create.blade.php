<form class="" method="post" action="{{ route('store-client-appointment',$currentWorkspace->slug) }}">
    @csrf
    <div class="modal-body">

            <div class="row">
            <div class="form-group col-md-12">
                <label for="appointment" class="col-form-label">{{ __('Title') }}</label>
                <input class="form-control" type="text" id="appointmentTitle" name="title" required=""
                       placeholder="{{ __('Title') }}">
            </div>
            <div class="form-group col-md-12">
                <label for="description" class="col-form-label">{{ __('Description') }}</label>
                <textarea class="form-control" id="description" name="description" required=""
                          placeholder="{{ __('Add Description') }}"></textarea>
            </div>
            {{--        <div class="col-md-12">--}}
            {{--            <label for="users_list" class="col-form-label">{{ __('Users') }}</label>--}}
            {{--            <select class=" multi-select" id="users_list" name="users_list[]" data-toggle="select2" multiple="multiple" data-placeholder="{{ __('Select Users ...') }}">--}}
            {{--                @foreach($currentWorkspace->users($currentWorkspace->created_by) as $user)--}}
            {{--                    <option value="{{$user->email}}">{{$user->name}} - {{$user->email}}</option>--}}
            {{--                @endforeach--}}
            {{--            </select>--}}
            {{--        </div>--}}
            <div class="form-group col-md-12">
                <label class="col-form-label">{{ __('Date / Time Schedule')}}</label>
                <input type="text" class="form-control form-control-light" id="date_schedule" name="date_schedule"
                       required autocomplete="off">
                <input type="hidden" name="start_date">
                <input type="hidden" name="due_date">
            </div>
            <div class="form-group col-md-12">
                <label class="col-form-label">{{ __('Request Zoom')}}</label>
                <input type="checkbox" id="is_zoom_link" name="is_zoom_link" value="1">
            </div>

            <div class="form-group col-md-12">
                <label for="budget" class="form-label">{{ __('Amount') }}</label>
                <div class="form-icon-user ">
                    <span
                        class="currency-icon bg-primary ">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'}}</span>
                    <input class="form-control currency_input" type="number" min="0" id="budget" name="budget"
                           value="{{$currentWorkspace->budget}}" placeholder="{{ __('500') }}">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        @if($check_chance_left->appointment_chance >2)
            <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close')}}</button>

        @else
            <input type="submit" value="{{ __('Add New Appointment')}}" class="btn  btn-primary">

        @endif
    </div>

</form>
<link rel="stylesheet" href="{{ asset('custom/libs/bootstrap-daterangepicker/daterangepicker.css') }}">
<script src="{{ asset('custom/libs/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

<script>
    $(document).on("click", ".delete-me", function () {
        confirm('{{__('Are you sure ?')}}')
        if (confirm('{{__('Are you sure ?')}}')) {
            var btn = $(this);
            $.ajax({
                url: 'client-appointment-delete',
                type: 'DELETE',
                dataType: 'JSON',
                success: function (data) {
                    show_toastr('{{__('Success')}}', '{{ __("Appointment Deleted Successfully!")}}', 'success');
                    btn.closest('.media').remove();
                },
                error: function (data) {
                    data = data.responseJSON;
                    if (data.message) {
                        show_toastr('{{__('Error')}}', data.message, 'error');
                    } else {
                        show_toastr('{{__('Error')}}', '{{ __("Some Thing Is Wrong!")}}', 'error');
                    }
                }
            });
        }
    });
    $(function () {
        var start = moment('{{ date('Y-m-d') }}', 'YYYY-MM-DD HH:mm:ss');
        var end = moment('{{ date('Y-m-d') }}', 'YYYY-MM-DD HH:mm:ss');

        function cb(start, end) {
            $("form #date_schedule").val(start.format('MMM D, YY hh:mm A') + ' - ' + end.format('MMM D, YY hh:mm A'));
            $('form input[name="start_date"]').val(start.format('YYYY-MM-DD HH:mm:ss'));
            $('form input[name="due_date"]').val(end.format('YYYY-MM-DD HH:mm:ss'));
        }

        $('form #date_schedule').daterangepicker({
            /*autoApply: true,
            autoclose: true,*/
            autoApply: true,
            timePicker: true,
            autoUpdateInput: false,
            startDate: start,
            endDate: end,
            /*startDate: start,
            endDate: end,
            autoApply: true,
            autoclose: true,
            autoUpdateInput: false,*/
            locale: {
                format: 'MMMM D, YYYY hh:mm A',
                applyLabel: "{{__('Apply')}}",
                cancelLabel: "{{__('Cancel')}}",
                fromLabel: "{{__('From')}}",
                toLabel: "{{__('To')}}",
                daysOfWeek: [
                    "{{__('Sun')}}",
                    "{{__('Mon')}}",
                    "{{__('Tue')}}",
                    "{{__('Wed')}}",
                    "{{__('Thu')}}",
                    "{{__('Fri')}}",
                    "{{__('Sat')}}"
                ],
                monthNames: [
                    "{{__('January')}}",
                    "{{__('February')}}",
                    "{{__('March')}}",
                    "{{__('April')}}",
                    "{{__('May')}}",
                    "{{__('June')}}",
                    "{{__('July')}}",
                    "{{__('August')}}",
                    "{{__('September')}}",
                    "{{__('October')}}",
                    "{{__('November')}}",
                    "{{__('December')}}"
                ],
            }
        }, cb);

        cb(start, end);
    });


</script>
