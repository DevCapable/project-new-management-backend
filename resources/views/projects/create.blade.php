
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
@php

    $client_keyword = Auth::user()->getGuard() == 'client' ? 'client.' : '';
@endphp

@section('content')

    <!--  <div class="col-lg-12 appointmentreportdata p-0">
 </div> -->

    <form class="" method="post" action="@auth('client'){{ route('store-client-project',$currentWorkspace->slug) }}@elseauth('web'){{ route('store-admin-project',$currentWorkspace->slug) }} @endauth">
        @csrf
        <div>
            <div class="row">
                <div class="form-group col-md-12">
                    <label for="project_name" class="col-form-label">{{ __('Name') }}</label>
                    <input class="form-control" type="text" id="project_name" name="project_name" required=""
                           placeholder="{{ __('Project Name') }}">
                    @auth('client')
                        <input type="hidden" value="{{generate_project_id('CLI')}}" name="project_id">
                    @endauth
                </div>
                <div class="form-group col-md-12">
                    <label for="description" class="col-form-label">{{ __('Description') }}</label>
                    <textarea class="form-control" id="project_description" name="project_description" required=""
                              placeholder="{{ __('Add Description') }}"></textarea>
                </div>
                @auth('web')
                    <input type="hidden" value="{{generate_project_id('ADM')}}" name="project_id">
                    <div class="col-md-12">
                        <label for="users_list" class="col-form-label">{{ __('Users') }}</label>
                        <select class=" multi-select" id="users_list" name="users_list[]" data-toggle="select2" multiple="multiple" data-placeholder="{{ __('Select Users ...') }}">
                            @foreach($currentWorkspace->users($currentWorkspace->created_by) as $user)
                                <option value="{{$user->email}}">{{$user->name}} - {{$user->email}}</option>
                            @endforeach
                        </select>
                    </div>
                @endauth
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
            @include('projects.task._admin_task_form')

        </div>
        <div class="card-footer">
            <button type="submit" name="action" value="save" class="btn  btn-primary right">{{ __('Continue')}}</button>
        </div>


    </form>

@endsection

@push('scripts')
    <script src="{{ asset('custom/libs/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    @include('projects.task._add_more_fields_js')

@endpush

