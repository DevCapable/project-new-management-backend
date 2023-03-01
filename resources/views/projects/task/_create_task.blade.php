@extends('layouts.admin')
@section('page-title')
    {{__('Add new Task')}}
@endsection
@section('links')
    @if(\Auth::guard('client')->check())
        <li class="breadcrumb-item"><a href="{{route('client.home')}}">{{__('Home')}}</a></li>
    @else
        <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('Home')}}</a></li>
    @endif
    <li class="breadcrumb-item"> {{ __('Add new Task List') }}</li>
@endsection
@php

    $client_keyword = Auth::user()->getGuard() == 'client' ? 'client.' : '';
@endphp

@section('content')

    <!--  <div class="col-lg-12 appointmentreportdata p-0">
 </div> -->

    <form class="" method="post" action="@auth('client'){{ route('client-task-store',[$currentWorkspace->slug,$project->project_id]) }} @elseauth('web'){{ route('admin-task-store',[$currentWorkspace->slug,$project->project_id]) }} @endauth">
        @csrf
        <div>
            <div class="row">
                <input type="hidden"  id="project_id"
                       name="project_id" value="{{$project->project_id}}" required autocomplete="off">
                @auth('web')
                <input type="hidden"  id="status"
                       name="status" value="UnderReview" required autocomplete="off">

                @endauth
{{--            @include('clients.projects.task._task_form')--}}
                @include('projects.task._admin_task_form')

            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn  btn-primary right">{{ __('Add New Task(s)')}}</button>
        </div>


    </form>

@endsection

@push('scripts')
    <script src="{{ asset('custom/libs/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    @include('projects.task._add_more_fields_js')
@endpush

