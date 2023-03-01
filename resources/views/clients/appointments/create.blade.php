<form class="" method="post" action="{{ route('store-client-appointment',$currentWorkspace->slug) }}">
    @csrf
    <div class="modal-body">

        @if($payment_data == null)
            @if($check_chance_left->appointment_chance == 0)

                <div class="row">
                    @include('partials._notifications')
                </div>

            @else
                @include('clients.appointments._appointment_form')

            @endif

        @else
            @if( $payment_data->chance == 0)
                <div class="row">
                    @include('partials._notifications')
                </div>
            @else
                @include('clients.appointments._appointment_form')
            @endif

        @endif
    </div>

    <div class="modal-footer">

        @if($payment_data == null)
            @if($check_chance_left->appointment_chance == 0)
                <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close')}}</button>
                <a href="{{route('client-appointment-renew',$currentWorkspace->slug)}}" class="btn  btn-danger right">
                    Click here to proceed <i class="ti ti-arrow-bar-right"
                                             style="float: right; font-size: 40px; padding-right: 10px"></i></a>
            @else
                <button type="submit" name="action" value="submit"
                        class="btn  btn-danger right">{{ __('Submit Appointment')}}</button>
                <button type="submit" name="action" value="save"
                        class="btn  btn-primary right">{{ __('Add New Appointment')}}</button>

            @endif
        @else
            @if( $payment_data->chance == 0)
                <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close')}}</button>
                <a href="{{route('client-appointment-renew',$currentWorkspace->slug)}}" class="btn  btn-danger right">
                    Click here to proceed <i class="ti ti-arrow-bar-right"
                                             style="float: right; font-size: 40px; padding-right: 10px"></i></a>
            @else
                <button type="submit" name="action" value="submit"
                        class="btn  btn-danger right">{{ __('Submit Appointment')}}</button>
                <button type="submit" name="action" value="save"
                        class="btn  btn-primary right">{{ __('Add New Appointment')}}</button>

            @endif

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
