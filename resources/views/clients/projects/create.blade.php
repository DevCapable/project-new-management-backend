{{--<form class="" method="post" action="{{ route('store-client-project',$currentWorkspace->slug) }}">--}}
{{--    @csrf--}}
{{--     <div class="modal-body">--}}
{{--    <div class="row">--}}
{{--        <div class="form-group col-md-12">--}}
{{--            <label for="projectname" class="col-form-label">{{ __('Name') }}</label>--}}
{{--            <input class="form-control" type="text" id="projectname" name="name" required="" placeholder="{{ __('Project Name') }}">--}}
{{--        </div>--}}
{{--        <div class="form-group col-md-12">--}}
{{--            <label for="description" class="col-form-label">{{ __('Description') }}</label>--}}
{{--            <textarea class="form-control" id="description" name="description" required="" placeholder="{{ __('Add Description') }}"></textarea>--}}
{{--        </div>--}}
{{--        <div class="col-md-12">--}}
{{--            <label for="users_list" class="col-form-label">{{ __('Users') }}</label>--}}
{{--            <select class=" multi-select" id="users_list" name="users_list[]" data-toggle="select2" multiple="multiple" data-placeholder="{{ __('Select Users ...') }}">--}}
{{--                @foreach($currentWorkspace->users($currentWorkspace->created_by) as $user)--}}
{{--                    <option value="{{$user->email}}">{{$user->name}} - {{$user->email}}</option>--}}
{{--                @endforeach--}}
{{--            </select>--}}
{{--        </div>--}}

{{--        <div class="form-group col-md-12">--}}
{{--            <label for="budget" class="form-label">{{ __('Budget') }}</label>--}}
{{--            <div class="form-icon-user ">--}}
{{--                <span class="currency-icon bg-primary ">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'}}</span>--}}
{{--                <input class="form-control currency_input" type="number" min="0" id="budget" name="budget" value="{{$currentWorkspace->budget}}" placeholder="{{ __('Project Budget') }}">--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
{{--        <div class="modal-footer">--}}
{{--            <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close')}}</button>--}}
{{--            <input type="submit" value="{{ __('Add New project')}}" class="btn  btn-primary">--}}
{{--        </div>--}}

{{--</form>--}}


@extends('layouts.admin')
@section('page-title')
    {{__('Appointments')}}
@endsection
@section('links')
    @if(\Auth::guard('client')->check())
        <li class="breadcrumb-item"><a href="{{route('client.home')}}">{{__('Home')}}</a></li>
    @else
        <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('Home')}}</a></li>
    @endif
    <li class="breadcrumb-item"> {{ __('Appointment List') }}</li>
@endsection
@section('action-button')
    <a href="#" class="btn btn-sm btn-primary filter" data-toggle="tooltip" title="{{ __('Filter') }}">
        <i class="ti ti-filter"></i>
    </a>
    @auth('client')

        <a href="{{ route('project.export') }}" class="btn btn-sm btn-primary " data-toggle="tooltip"
           title="{{ __('Export ') }}"
        > <i class="ti ti-file-x"></i></a>

        <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-title="{{__('Import Project')}}"
           data-url="{{ route('project.file.import' ,$currentWorkspace->slug) }}" data-toggle="tooltip"
           title="{{ __('Import') }}"><i class="ti ti-file-import"></i> </a>

        @if(isset($currentWorkspace) || $currentWorkspace->creater->id == Auth::id())
            <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md"
               data-title="{{ __('Create New Appointment') }}"
               data-url="{{route('client-create-appointment',$currentWorkspace->slug)}}" data-toggle="tooltip"
               title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endif
    @endauth
@endsection
@php

    $client_keyword = Auth::user()->getGuard() == 'client' ? 'client.' : '';
@endphp

@section('content')

    <!--  <div class="col-lg-12 appointmentreportdata p-0">
 </div> -->

    <form class="" method="post" action="{{ route('store-client-project',$currentWorkspace->slug) }}">
        @csrf
        <div>
            <div class="row">
                <div class="form-group col-md-12">
                    <label for="project_name" class="col-form-label">{{ __('Name') }}</label>
                    <input class="form-control" type="text" id="project_name" name="project_name" required=""
                           placeholder="{{ __('Project Name') }}">
                    <input type="hidden" value="{{generate_client_project_id('CLPRO')}}" name="project_id">
                </div>
                <div class="form-group col-md-12">
                    <label for="description" class="col-form-label">{{ __('Description') }}</label>
                    <textarea class="form-control" id="project_description" name="project_description" required=""
                              placeholder="{{ __('Add Description') }}"></textarea>
                </div>
                <div class="form-group col-md-12">
                    <label for="budget" class="form-label">{{ __('Budget') }}</label>
                    <div class="form-icon-user ">
                        <span
                            class="currency-icon bg-primary ">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'}}</span>
                        <input class="form-control currency_input" type="number" min="0" id="budget" name="budget"
                               value="{{$currentWorkspace->budget}}" placeholder="{{ __('Project Budget') }}">
                    </div>
                </div>

            </div>
            @include('clients.projects.task._task_form')

        </div>
        <div class="card-footer">
            <button type="submit"  class="btn  btn-primary right">{{ __('Add New project')}}</button>
        </div>


    </form>

@endsection

@push('scripts')
    <script src="{{ asset('custom/libs/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js">
    </script>

{{--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>--}}

    <script type="text/javascript">
        $(document).ready(function(){
            // alert('yes');
            var html = '<tr><td><input class="form-control" type="text" name="title[]"></td>'+
            '<td><input type="date" class="form-control form-control-light" id="start_date" name="start_date[]" required autocomplete="off"></td>'+
            '<td><input type="date" class="form-control form-control-light" id="due_date" name="due_date[]" required autocomplete="off"></td>'+
                '<td> <select class="form-control form-control-light select2" name="priority" id="task-priority" required>' +
                '<option value="Low">{{ __('Low')}}</option>'+
            '<option value="Medium">{{ __('Medium')}}</option>'+
            '<option value="High">{{ __('High')}}</option></select></td>'+
            '<td class="col-2"><textarea class="form-control form-control-light" id="description" rows="3" name="description[]"></textarea></td>'+
            '<td><input class="btn btn-danger" type="button" name="remove" value="remove" id="remove"></td></tr>';
            var max =3;
            var x = 1;
            $('#add').click(function (){
                if(x < max){
                    $("#table_field").append(html);
                    x++;
                }else if(x === max){
                    alert(`Sorry you can not add more than ` + max +` fields at a time`)
                }
            })
            $('#table_field').on('click','#remove',function(){
                $(this).closest('tr').remove();
                x--;
            })

        })





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

        $(function () {
            var start = moment('{{ date('Y-m-d') }}', 'YYYY-MM-DD HH:mm:ss');
            var end = moment('{{ date('Y-m-d') }}', 'YYYY-MM-DD HH:mm:ss');

            function cb(start, end) {
                $("form #duration").val(start.format('MMM D, YY hh:mm A') + ' - ' + end.format('MMM D, YY hh:mm A'));
                $('form input[name="start_date"]').val(start.format('YYYY-MM-DD HH:mm:ss'));
                $('form input[name="due_date"]').val(end.format('YYYY-MM-DD HH:mm:ss'));
            }

            $('form #duration').daterangepicker({
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

@endpush

