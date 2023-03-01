@extends('layouts.admin')

@section('page-title') {{__('Timesheet')}} @endsection

@php $client_keyword = Auth::user()->getGuard() == 'client' ? 'client.' : ''; @endphp

@section('links')
@if(\Auth::guard('client')->check())   
<li class="breadcrumb-item"><a href="{{route('client.home')}}">{{__('Home')}}</a></li>
 @else
 <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('Home')}}</a></li>
 @endif
@if($project_id != '-1')
 @if(\Auth::guard('client')->check())  
<li class="breadcrumb-item"><a href="{{ route('client.projects.index',$currentWorkspace->slug) }}">{{__('Project')}}</a></li>
 @else  
<li class="breadcrumb-item"><a href="{{ route('projects.index',$currentWorkspace->slug)}}">{{__('Project')}}</a></li>
@endif
<li class="breadcrumb-item"><a href="{{route($client_keyword.'projects.show',[$currentWorkspace->slug,$project_id ])}}">{{__('Project Details')}}</a></li>

<li class="breadcrumb-item"> {{ __('Timesheet') }}</li>
@else
<li class="breadcrumb-item"> {{ __('Timesheet') }}</li>
@endif
@endsection

@section('action-button')
<div class="d-flex justify-content-end">
        @if(isset($currentWorkspace) && $currentWorkspace)
        @if($project_id != '-1' && Auth::user()->getGuard() != 'client')
            <div class="form-group col-auto mx-2">
                            <select class="form-select" id="project_tasks">
                                   <option value="">{{ __('Add Task on Timesheet') }}</option>
                            </select>
                        </div>
        @endif
    @endif 
    <div class="col-auto">
        <div class="weekly-dates-div">
            <i class="fa fa-arrow-left previous"></i>

            <span class="weekly-dates"></span>

            <input type="hidden" id="weeknumber" value="0">
            <input type="hidden" id="selected_dates">

            <i class="fa fa-arrow-right next"></i>
        </div>
    </div>

           
    @if($project_id != '-1')
        <div class="col-auto">
            <a href="{{route($client_keyword.'projects.show',[$currentWorkspace->slug,$project_id])}}" class="btn btn-sm btn-primary">
                 <i class=" ti ti-arrow-back-up"></i> 
            </a>
        </div>
    @endif
</div>
@endsection

@section('content')
    <section class="section">
        @if($currentWorkspace)

            <div class="row">
                <div class="col-md-12">

                    <div id="timesheet-table-view"></div>
                    <div class="card notfound-timesheet text-center">
                        <div class="card-body p-3">
                            <div class="page-error">
                                <div class="page-inner">
                                    <div class="page-description">
                                        {{ __("We couldn't find any data") }}
                                    </div>
                                    <div class="page-search">
                                        <p class="text-muted mt-3">
                                            {{ __("Sorry we can't find any timesheet records on this week.") }}
                                            <br>
                                            @if($project_id != '-1' && Auth::user()->getGuard() != 'client')
                                                {{ __('To add record go to ') }} <b>{{ __('Add Task on Timesheet.') }}</b>
                                            @else
                                                {{ __('To add timesheet record go to ') }}
                                                <a class="btn-return-home badge-blue" href="{{ route($client_keyword.'projects.index', $currentWorkspace->slug) }}"><i class="fas fa-reply"></i> {{ __('Projects')}}</a>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @endif
    </section>
@endsection

@push('css-page')
@endpush
@push('scripts')
    <script>
        function ajaxFilterTimesheetTableView() {

            var mainEle = $('#timesheet-table-view');
            var notfound = $('.notfound-timesheet');

            var week = parseInt($('#weeknumber').val());
            var project_id = '{{ $project_id }}';

            var data = {
                week: week,
                project_id: project_id,
            };

            $.ajax({
                @if(Auth::user()->getGuard() == 'client')
                url: '{{ route('client.filter.timesheet.table.view', '__slug') }}'.replace('__slug', '{{ $currentWorkspace->slug }}'),
                @else
                url: '{{ route('filter.timesheet.table.view', '__slug') }}'.replace('__slug', '{{ $currentWorkspace->slug }}'),
                @endif
                data: data,
                success: function (data) {

                    $('.weekly-dates-div .weekly-dates').text(data.onewWeekDate);
                    $('.weekly-dates-div #selected_dates').val(data.selectedDate);

                    $('#project_tasks').find('option').not(':first').remove();

                    $.each(data.tasks, function (i, item) {
                        $('#project_tasks').append($("<option></option>")
                            .attr("value", i)
                            .text(item));
                    });

                    if (data.totalrecords == 0) {
                        mainEle.hide();
                        notfound.css('display', 'block');
                    } else {
                        notfound.hide();
                        mainEle.show();
                    }

                    mainEle.html(data.html);
                }
            });
        }

        $(function () {
            ajaxFilterTimesheetTableView();
        });

        $(document).on('click', '.weekly-dates-div i', function () {

            var weeknumber = parseInt($('#weeknumber').val());

            if ($(this).hasClass('previous')) {

                weeknumber--;
                $('#weeknumber').val(weeknumber);

            } else if ($(this).hasClass('next')) {

                weeknumber++;
                $('#weeknumber').val(weeknumber);
            }

            ajaxFilterTimesheetTableView();
        });

        $(document).on('click', '[data-ajax-timesheet-popup="true"]', function (e) {
            e.preventDefault();

            var data = {};
            var url = $(this).data('url');
            var type = $(this).data('type');
            var date = $(this).data('date');
            var task_id = $(this).data('task-id');
            var user_id = $(this).data('user-id');
            var p_id = $(this).data('project-id');

            data.date = date;
            data.task_id = task_id;

            if (user_id != undefined) {
                data.user_id = user_id;
            }

            if (type == 'create') {
                var title = '{{ __("Create Timesheet") }}';
                data.p_id = '{{ $project_id }}';
                data.project_id = data.p_id != '-1' ? data.p_id : p_id;

            } else if (type == 'edit') {
                var title = '{{ __("Edit Timesheet") }}';
            }

            $("#commonModal .modal-title").html(title + ` <small>(` + moment(date).format("ddd, Do MMM YYYY") + `)</small>`);

            $.ajax({
                url: url,
                data: data,
                dataType: 'html',
                success: function (data) {
                    $('#commonModal .body').html(data);
                    // $('#commonModal .modal-body').html(data);
                    $("#commonModal").modal('show');
                    commonLoader();
                    loadConfirm();
                }
            });
        });

        $(document).on('click', '#project_tasks', function (e) {
            var mainEle = $('#timesheet-table-view');
            var notfound = $('.notfound-timesheet');

            var selectEle = $(this).children("option:selected");
            var task_id = selectEle.val();
            var selected_dates = $('#selected_dates').val();

            if (task_id != '') {

                $.ajax({
                    url: '{{ route('append.timesheet.task.html', '__slug') }}'.replace('__slug', '{{ $currentWorkspace->slug }}'),
                    data: {
                        project_id: '{{ $project_id }}',
                        task_id: task_id,
                        selected_dates: selected_dates,
                    },
                    success: function (data) {

                        notfound.hide();
                        mainEle.show();

                        $('#timesheet-table-view tbody').append(data.html);
                        selectEle.remove();
                    }
                });
            }
        });

        $(document).on('change', '#time_hour, #time_minute', function () {

            var hour = $('#time_hour').children("option:selected").val();
            var minute = $('#time_minute').children("option:selected").val();
            var total = $('#totaltasktime').val().split(':');

            if (hour == '00' && minute == '00') {
                $(this).val('');
                return;
            }

            hour = hour != '' ? hour : 0;
            hour = parseInt(hour) + parseInt(total[0]);

            minute = minute != '' ? minute : 0;
            minute = parseInt(minute) + parseInt(total[1]);

            if (minute > 50) {
                minute = minute - 60;
                hour++;
            }

            hour = hour < 10 ? '0' + hour : hour;
            minute = minute < 10 ? '0' + minute : minute;

            $('.display-total-time span').text('{{ __("Total Time") }} : ' + hour + ' {{ __("Hours") }} ' + minute + ' {{ __("Minutes") }}');
        });
    </script>
@endpush

<style type="text/css">
    
.weekly-dates-div {
    padding: 8px 12px 8px 5px !important;
}
</style>
