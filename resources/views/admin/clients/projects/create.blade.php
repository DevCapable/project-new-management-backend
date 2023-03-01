@extends('layouts.admin')
@section('page-title')
    {{__('Project')}}
@endsection
@section('links')
    @if(\Auth::guard('client')->check())
        <li class="breadcrumb-item"><a href="{{route('client.home')}}">{{__('Home')}}</a></li>
    @else
        <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('Home')}}</a></li>
    @endif
    <li class="breadcrumb-item"> {{ __('Project') }}</li>
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
            @include('projects.task._task_form')

        </div>
        <div class="card-footer">
            <button type="submit" class="btn  btn-primary right">{{ __('Add New project')}}</button>
        </div>


    </form>

@endsection

@push('scripts')
    <script src="{{ asset('custom/libs/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    @include('projects.task._add_more_fields_js')
@endpush

