<form class="" method="post" action="{{ route('update-client-appointment',[$currentWorkspace->slug,$appointment->id]) }}">
    @csrf
     <div class="modal-body">

         <div class="row">
             <div class="form-group col-md-12">
                 <label class="col-form-label">{{ __('Date / Time Schedule')}}</label>
                 <input type="text" class="form-control form-control-light" id="date_schedule" value="{{$appointment->date_schedule}}" name="date_schedule" required autocomplete="off">
{{--                 <input type="hidden"   name="date_schedule" value="{{$appointment->date_schedule}}">--}}

                 <input type="hidden" value="start_date"  name="start_date">
                 <input type="hidden" value="due_date" name="due_date">
             </div>
             <div class="form-group col-md-12">
                 <label for="appointment" class="col-form-label">{{ __('Title') }}</label>
                 <input class="form-control" type="text" id="appointmentTitle" name="title" required="" placeholder="{{ __('Title') }}" value="{{$appointment->title}}">
             </div>
             <div class="form-group col-md-12">
                 <label for="description" class="col-form-label">{{ __('Description') }}</label>
                 <textarea class="form-control" id="description" name="description" required="" placeholder="{{ __('Add Description') }}">{{$appointment->description}}</textarea>
             </div>
             @include('partials._notifications')
             {{--        <div class="col-md-12">--}}
             {{--            <label for="users_list" class="col-form-label">{{ __('Users') }}</label>--}}
             {{--            <select class=" multi-select" id="users_list" name="users_list[]" data-toggle="select2" multiple="multiple" data-placeholder="{{ __('Select Users ...') }}">--}}
             {{--                @foreach($currentWorkspace->users($currentWorkspace->created_by) as $user)--}}
             {{--                    <option value="{{$user->email}}">{{$user->name}} - {{$user->email}}</option>--}}
             {{--                @endforeach--}}
             {{--            </select>--}}
             {{--        </div>--}}



{{--             <div class="form-group col-md-12">--}}
{{--                 <label for="budget" class="form-label">{{ __('Amount') }}</label>--}}
{{--                 <div class="form-icon-user ">--}}
{{--                     <span class="currency-icon bg-primary ">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'}}</span>--}}
{{--                     <input class="form-control currency_input" type="number" min="0" id="budget" name="budget" value="{{$currentWorkspace->budget}}" placeholder="{{ __('500') }}">--}}
{{--                 </div>--}}
{{--             </div>--}}
         </div>

     </div>
        <div class="modal-footer">
           <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close')}}</button>
            @if($appointment->status == 'Submitted')
                {!! get_appointment_status_label('Submitted') !!}
            @else
                <button type="submit" name="action" value="submit" Onclick="return ConfirmSubmit();" class="btn  btn-danger right">{{ __('Submit Appointment')}}</button>
                <button type="submit" name="action" value="save" class="btn  btn-primary right">{{ __('Save')}}</button>
            @endif
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
<link rel="stylesheet" href="{{ asset('custom/libs/bootstrap-daterangepicker/daterangepicker.css') }}">
<script src="{{ asset('custom/libs/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

<script>
    function ConfirmSubmit()
    {
        return confirm("Are you sure you want to submit this? note that you cant edit again once submitted");
    }
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
