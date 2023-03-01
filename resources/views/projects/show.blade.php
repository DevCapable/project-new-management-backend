@extends('layouts.admin')
@section('page-title')
    {{__('Project Detail')}}
@endsection
@section('links')
    @if(\Auth::guard('client')->check())
        <li class="breadcrumb-item"><a href="{{route('client.home')}}">{{__('Home')}}</a></li>
    @else
        <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('Home')}}</a></li>
    @endif
    @if(\Auth::guard('client')->check())
        <li class="breadcrumb-item"><a
                href="{{ route('client-projects-index',$currentWorkspace->slug) }}">{{__('Project')}}</a></li>
    @else
        <li class="breadcrumb-item"><a href="{{ route('projects.index',$currentWorkspace->slug)}}">{{__('Project')}}</a>
        </li>
    @endif
    <li class="breadcrumb-item">{{__('Project Details')}}</li>
@endsection
@if(Auth::user()->type == 'user')
    @php
        $permissions = Auth::user()->getPermission($project->project_id);
        $client_keyword = Auth::user()->getGuard() == 'client' ? 'client.' : '';
    @endphp
@endif

@section('multiple-action-button')
    {{--    @if((isset($permissions) && in_array('show timesheet',$permissions)) || (isset($currentWorkspace) && $currentWorkspace->permission == 'Owner'))--}}
    {{--        <div class="col-sm-auto  ">--}}
    {{--            <a href="{{route($client_keyword.'projects.timesheet.index',[$currentWorkspace->slug,$project->id])}}"--}}
    {{--               class="btn btn-xs btn-primary btn-icon-only width-auto ">{{ __('Timesheet')}}</a>--}}
    {{--        </div>--}}
    {{--    @endif--}}

@endsection
<style type="text/css">
    .fix_img {
        width: 40px !important;
        border-radius: 50%;
    }
</style>
@section('content')
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="row">
                @include('partials._notifications')
                <div class="col-xxl-8">
                    <div class="card bg-primary">
                        <div class="card-body">
                            <div class="d-block d-sm-flex align-items-center justify-content-between">
                                <h4 class="text-white">  {{$project->name}}</h4>
                                <div class="d-flex  align-items-center">
                                    <div class="px-3">
                                        <span class="text-white text-sm">{{ __('Start Date') }}:</span>
                                        <h5 class="text-white text-nowrap">{{App\Models\Utility::dateFormat($project->start_date)}}</h5>
                                    </div>
                                    <div class="px-3">
                                        <span class="text-white text-sm">{{ __('Due Date') }}:</span>
                                        <h5 class="text-white">{{App\Models\Utility::dateFormat($project->end_date)}}</h5>
                                    </div>
                                    <div class="px-3">
                                        <span class="text-white text-sm">{{ __('Total Members') }}:</span>
                                        <h5 class="text-white text-nowrap">{{ (int) $project->users->count() + (int) $project->clients->count() }}</h5>
                                    </div>
                                    <div class="px-3">

                                        <div class="col-auto">
                                            @if ($project->status == 'Processing')
                                                <span class="badge rounded-pill bg-danger">Processing</span>

                                            @else
                                                {!!  get_projects_status_label($project->status) !!}
                                            @endif
                                            @if($project->payment_links !== NULL)
                                                {{--                                                    <span class="badge rounded-pill bg-danger"><a href="{{$project->payment_links}}">Click to pay</a></span>--}}

                                                <span
                                                    class="badge rounded-pill bg-danger">{!! $project->payment_links !!}</span>
                                            @endif
                                        </div>

                                    </div>
                                </div>

                                @if(!$project->is_active)
                                    <button class="btn btn-light d"><a href="#" class="" title="{{__('Locked')}}">
                                            <i data-feather="lock"> </i>
                                        </a></button>

                                @else
                                    @auth('web')
                                        @if($currentWorkspace || $currentWorkspace->permission == 'Owner')

                                            <div class="d-flex align-items-center ">
                                                <button class="btn btn-light d-flex align-items-between me-3">
                                                    <a href="#" class=""
                                                       data-url="{{ route('admin-project-review',[$currentWorkspace->slug,$project->project_id]) }}"
                                                       data-ajax-popup="true" data-title="{{__('Review Project')}}"
                                                       data-toggle="popover" title="{{__('Review')}}">
                                                        <i class="ti ti-box-multiple-7"> Review </i>
                                                    </a>
                                                </button>
                                                <button class="btn btn-light d"><a href="#" class="bs-pass-para"
                                                                                   data-confirm="{{__('Are You Sure?')}}"
                                                                                   data-text="{{__('This action can not be undone. Do you want to continue?')}}"
                                                                                   data-confirm-yes="delete-form-{{$project->id}}"
                                                                                   data-toggle="popover"
                                                                                   title="{{__('Delete')}}"><i
                                                            class="ti ti-trash"> </i></a></button>

                                            </div>
                                            <form id="delete-form-{{$project->id}}"
                                                  action="{{ route('projects.destroy',[$currentWorkspace->slug,$project->id]) }}"
                                                  method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>

                                        @else

                                            <button class="btn btn-light d"><a href="#" class="bs-pass-para"
                                                                               data-confirm="{{__('Are You Sure?')}}"
                                                                               data-text="{{__('This action can not be undone. Do you want to continue?')}}"
                                                                               data-confirm-yes="leave-form-{{ $project->id }}"><i
                                                        class="ti ti-trash"> </i> </i></a></button>

                                            <form id="leave-form-{{$project->id}}"
                                                  action="{{ route('projects.leave',[$currentWorkspace->slug,$project->id]) }}"
                                                  method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endif
                                    @endauth
                                @endif
                                {{--                                    FOR CLIENT END USER--}}
                                @auth('client')
                                    @if($currentWorkspace->permission == 'Owner')
                                        @if ($project->status == 'NotSubmitted')
                                            <div class="d-flex align-items-center ">
                                                <button class="btn btn-light d-flex align-items-between me-3">
                                                    <a href="#" class=""
                                                       data-url="{{ route('edit-client-project',[$currentWorkspace->slug,$project->project_id]) }}"
                                                       data-ajax-popup="true" data-title="{{__('Edit Project')}}"
                                                       data-toggle="popover" title="{{__('Edit')}}">
                                                        <i class="ti ti-edit"> </i>
                                                    </a>
                                                </button>
                                                <button class="btn btn-light d"><a href="#" class="bs-pass-para"
                                                                                   data-confirm="{{__('Are You Sure?')}}"
                                                                                   data-text="{{__('This action can not be undone. Do you want to continue?')}}"
                                                                                   data-confirm-yes="delete-form-{{$project->id}}"
                                                                                   data-toggle="popover"
                                                                                   title="{{__('Delete')}}"><i
                                                            class="ti ti-trash" style="color: red"> </i></a></button>

                                            </div>
                                            <form id="delete-form-{{$project->id}}"
                                                  action="{{ route('delete-client-project',[$currentWorkspace->slug,$project->id]) }}"
                                                  method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>

                                        @else
                                            <span class="badge rounded-pill bg-danger">Submitted</span>

                                        @endif

                                    @else

                                        <button class="btn btn-light d"><a href="#" class="bs-pass-para"
                                                                           data-confirm="{{__('Are You Sure?')}}"
                                                                           data-text="{{__('This action can not be undone. Do you want to continue?')}}"
                                                                           data-confirm-yes="leave-form-{{ $project->id }}"><i
                                                    class="ti ti-trash"> </i> </i>ddd</a></button>

                                        <form id="leave-form-{{$project->id}}"
                                              action="{{ route('projects.leave',[$currentWorkspace->slug,$project->id]) }}"
                                              method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endif
                                @endauth

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="theme-avtar bg-primary">
                                            <i class="fas fas fa-calendar-day"></i>
                                        </div>
                                        <div class="col text-end">
                                            <h6 class="text-muted mb-1">{{ __('Days left') }}</h6>
                                            <span class="h6 font-weight-bold mb-0 ">{{ $daysleft }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="theme-avtar bg-info">
                                            <i class="fas fa-money-bill-alt"></i>
                                        </div>
                                        <div class="col text-end">
                                            <h6 class="text-muted mb-1">{{ __('Budget') }}</h6>
                                            <span
                                                class="h6 font-weight-bold mb-0 ">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'}}{{ number_format($project->budget) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="theme-avtar bg-danger">
                                            <i class="fas fa-check-double"></i>
                                        </div>
                                        <div class="col text-end">
                                            <h6 class="text-muted mb-1">{{ __('Total Task') }}</h6>
                                            <span class="h6 font-weight-bold mb-0 ">{{ $project->countTask() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="theme-avtar bg-success">
                                            <i class="fas fa-comments"></i>
                                        </div>
                                        <div class="col text-end">
                                            <h6 class="text-muted mb-1">{{ __('Comment') }}</h6>
                                            <span
                                                class="h6 font-weight-bold mb-0 ">{{$project->countTaskComments()}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card" style="min-height:350;">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-0">{{ __('Staff(s) incharge') }} ({{count($project->clients)}}
                                                )</h5>
                                        </div>

                                        <div class="float-end">
                                            <p class="text-muted d-none d-sm-flex align-items-center mb-0"> @if($currentWorkspace->permission == 'Owner')
                                                    <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true"
                                                       data-title="{{ __('Share to Client') }}" data-toggle="popover"
                                                       title="{{__('Share to Client')}}"
                                                       data-url="{{route('projects.share.popup',[$currentWorkspace->slug,$project->id])}}"><i
                                                            class="ti ti-share"></i></a>
                                                @endif </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">

                                    @foreach($project->clients as $client)
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item px-0">
                                                <div class="row align-items-center justify-content-between">
                                                    <div class="col-sm-auto mb-3 mb-sm-0">
                                                        <div class="d-flex align-items-center px-2">
                                                            <a href="#" class=" text-start">
                                                                <img class="fix_img"
                                                                     @if($client->avatar) src="{{asset('/storage/avatars/'.$client->avatar)}}"
                                                                     @else avatar="{{ $client->name }}"@endif>
                                                            </a>
                                                            <div class="px-2">
                                                                <h5 class="m-0">{{$client->name}}</h5>
                                                                <small class="text-muted">{{$client->email}}</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-auto text-sm-end d-flex align-items-center">
                                                        @auth('web')
                                                            @if($currentWorkspace->permission == 'Owner')
                                                                <a href="#"
                                                                   class="action-btn btn-primary mx-1  btn btn-sm d-inline-flex align-items-center"
                                                                   data-ajax-popup="true" data-toggle="popover"
                                                                   title="{{__('Permission')}}" data-size="lg"
                                                                   data-title="{{__('Edit Permission')}}"
                                                                   data-url="{{route('projects.client.permission',[$currentWorkspace->slug,$project->id,$client->id])}}"><i
                                                                        class="ti ti-lock"></i></a>

                                                                <a href="#"
                                                                   class="action-btn btn-danger mx-1  btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                                   data-confirm="{{__('Are You Sure?')}}"
                                                                   data-toggle="popover" title="{{__('Delete')}}"
                                                                   data-text="{{__('This action can not be undone. Do you want to continue?')}}"
                                                                   data-confirm-yes="delete-client-{{$client->id}}"><i
                                                                        class="ti ti-trash"></i></a>

                                                                <form id="delete-client-{{$client->id}}"
                                                                      action="{{ route('projects.client.delete',[$currentWorkspace->slug,$project->id,$client->id]) }}"
                                                                      method="POST" style="display: none;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                            @endif
                                                        @endauth
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-xxl-4">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header" style="padding: 25px 35px !important;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="row">
                                            <h5 class="mb-0">{{ __('Progress') }}<span class="text-end">  (Last Week Tasks) </span>
                                            </h5>

                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-start">
                                    </div>
                                </div>
                                <div id="task-chart"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0">{{ __('Activity') }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="timeline timeline-one-side top-10-scroll" data-timeline-content="axis"
                                 data-timeline-axis-style="dashed">
                                @if((isset($permissions) && in_array('show activity',$permissions)) || $currentWorkspace->permission == 'Owner')
                                    @foreach($project->activities as $activity)
                                        <div class="timeline-block px-2 pt-3">
                                            @if($activity->log_type == 'Upload File')
                                                <span
                                                    class="timeline-step timeline-step-sm border border-primary text-white"> <i
                                                        class="fas fa-file"></i></span>
                                            @elseif($activity->log_type == 'Create Milestone')
                                                <span
                                                    class="timeline-step timeline-step-sm border border-info text-white"> <i
                                                        class="fas fa-cubes"></i></span>
                                            @elseif($activity->log_type == 'Create Task')
                                                <span
                                                    class="timeline-step timeline-step-sm border border-success text-white"> <i
                                                        class="fas fa-tasks"></i></span>

                                            @elseif($activity->log_type == 'Update Project')
                                                <span
                                                    class="timeline-step timeline-step-sm border border-success text-white"> <i
                                                        class="fas fa-tasks"></i></span>
                                            @elseif($activity->log_type == 'Submit Project')
                                                <span
                                                    class="timeline-step timeline-step-sm border border-success text-white"> <i
                                                        class="fas fa-tasks"></i></span>

                                            @elseif($activity->log_type == 'Create New Project')
                                                <span
                                                    class="timeline-step timeline-step-sm border border-success text-white"> <i
                                                        class="fas fa-tasks"></i></span>

                                            @elseif($activity->log_type == 'Create Bug')
                                                <span
                                                    class="timeline-step timeline-step-sm border border-warning text-white"> <i
                                                        class="fas fa-bug"></i></span>
                                            @elseif($activity->log_type == 'Move' || $activity->log_type == 'Move Bug')
                                                <span
                                                    class="timeline-step timeline-step-sm border round border-danger text-white"> <i
                                                        class="fas fa-align-justify"></i></span>
                                            @elseif($activity->log_type == 'Create Invoice')
                                                <span
                                                    class="timeline-step timeline-step-sm border border-bg-dark text-white"> <i
                                                        class="fas fa-file-invoice"></i></span>
                                            @elseif($activity->log_type == 'Invite User')
                                                <span
                                                    class="timeline-step timeline-step-sm border border-success text-white"> <i
                                                        class="fas fa-plus"></i></span>
                                            @elseif($activity->log_type == 'Share with Client')
                                                <span
                                                    class="timeline-step timeline-step-sm border border-info text-white"> <i
                                                        class="fas fa-share"></i></span>
                                            @elseif($activity->log_type == 'Create Timesheet')
                                                <span
                                                    class="timeline-step timeline-step-sm border border-success text-white"> <i
                                                        class="fas fa-clock-o"></i></span>
                                            @endif

                                            <div class="last_notification_text">
                                                <p class="m-0"><span>{{ $activity->log_type }} </span></p> <br>
                                                <p> {!! $activity->getRemark() !!} </p>
                                                <div class="notification_time_main">
                                                    <p>{{ $activity->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>

                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>


                    {{--                        COMMENT--}}
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0">{{ __('Comments') }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="timeline timeline-one-side top-10-scroll" data-timeline-content="axis"
                                 data-timeline-axis-style="dashed">
                                @if((isset($permissions) && in_array('show activity',$permissions)) || $currentWorkspace->permission == 'Owner')
                                    @foreach($project->activities as $activity)
                                        <div class="timeline-block px-2 pt-3">


                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    {{--                    END COMMEN --}}

                </div>

                {{--                TASK--}}

                <div class="col-md-12">
                    @if((isset($permissions) && in_array('show task', $permissions)) || $currentWorkspace->permission == 'Owner')

                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-0">{{__('Task(s)')}} ({{count($project->tasks())}})</h5>
                                    </div>
                                    <div class="float-end">
                                        @if((isset($permissions) && in_array('create task',$permissions)) || $currentWorkspace->permission == 'Owner')
                                            @auth('web')
                                                <a href="{{route('admin-create-new-task',[$currentWorkspace->slug,$project->id])}}"
                                                   class="btn btn-sm btn-primary"
                                                   data-title="{{ __('Create Task') }}"
                                                   title="{{__('Create')}}"><i class="ti ti-plus"></i></a>
                                            @elseauth('client')
                                                <a href="{{route('client-create-new-task',[$currentWorkspace->slug,$project->project_id])}}"
                                                   class="btn btn-sm btn-primary"
                                                   data-title="{{ __('Create Task') }}"
                                                   title="{{__('Create')}}"><i class="ti ti-plus"></i></a>
                                            @endauth
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="" class="table table-bordered px-2">
                                        <thead>
                                        <tr>
                                            <th>{{__('Name')}}</th>
                                            <th>{{__('Status')}}</th>
                                            <th>{{__('Start Date')}}</th>
                                            <th>{{__('End Date')}}</th>
                                            <th>{{__('Assignee')}}</th>
                                            <th>{{__('Cost')}}</th>
                                            <th>{{__('Progress')}}</th>
                                            <th> {{__('Files')}}</th>
                                            <th>{{__('Action')}} </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($project->tasks() as $key => $task)
                                            <tr>
                                                <td><a href="#" class="d-block font-weight-500 mb-0"
                                                       data-ajax-popup="true" data-title="{{ __('Milestone Details') }}"
                                                       data-url="{{route('admin-tasks-edit',[$currentWorkspace->slug,$task->project_id,$task->id,])}}">
                                                        <h5 class="m-0">  {{$task->title}} </h5>
                                                    </a></td>
                                                <td> @if($task->status == 'complete')
                                                        <label
                                                            class="badge bg-success p-2 px-3 rounded">{{__('Complete')}}</label>
                                                    @else
                                                        <label
                                                            class="badge bg-warning p-2 px-3 rounded">{{__('Incomplete')}}</label>
                                                    @endif</td>
                                                <td>{{format_date($task->start_date)}}</td>
                                                <td>{{format_date($task->due_date)}}</td>
                                                <td>
                                                    {{$task->assign_to? : 'N/A'}}

                                                </td>
                                                <td>{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$' }}{{$task->cost}}</td>
                                                <td>
                                                    <div class="progress_wrapper">
                                                        <div class="progress">
                                                            <div class="progress-bar" role="progressbar"
                                                                 style="width: {{ $task->progress }};"
                                                                 aria-valuenow="55" aria-valuemin="0"
                                                                 aria-valuemax="100"></div>
                                                        </div>
                                                        <div class="progress_labels">
                                                            <div class="total_progress">

                                                                <strong> {{ $task->progress }}%</strong>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                        @include('projects.task._task_uploaded_files')
                                                </td>

                                                <td class="text-right">
                                                    <div class="col-auto">
                                                        @if($currentWorkspace->permission == 'Owner')
                                                            @auth('client')
                                                                <a href="#"
                                                                   class="action-btn btn-info mx-1  btn btn-sm d-inline-flex align-items-center"
                                                                   data-ajax-popup="true" data-size="lg"
                                                                   data-toggle="popover" title="{{__('Edit')}}"
                                                                   data-title="{{__('Edit Task')}}"
                                                                   data-url="{{route('client-tasks-edit',[$currentWorkspace->slug,$task->project_id,$task->id,])}}"><i
                                                                        class="ti ti-edit"></i></a>
                                                            @elseauth('web')

                                                                <a href="#"
                                                                   class="action-btn btn-info mx-1  btn btn-sm d-inline-flex align-items-center"
                                                                   data-ajax-popup="true" data-size="lg"
                                                                   data-toggle="popover" title="{{__('Edit')}}"
                                                                   data-title="{{__('Edit Task')}}"
                                                                   data-url="{{route('admin-tasks-edit',[$currentWorkspace->slug,$task->project_id,$task->id,])}}"><i
                                                                        class="ti ti-edit"></i></a>
                                                            @endauth

                                                            <a href="#"
                                                               class="action-btn btn-danger mx-1  btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                               data-confirm="{{__('Are You Sure?')}}"
                                                               data-toggle="popover" title="{{__('Delete')}}"
                                                               data-text="{{__('This action can not be undone. Do you want to continue?')}}"
                                                               data-confirm-yes="delete-form1-{{$task->id}}"><i
                                                                    class="ti ti-trash"></i></a>
                                                            <form id="delete-form1-{{$task->id}}"
                                                                  action="@auth('web'){{ route('admin-tasks-destroy',[$currentWorkspace->slug,$task->project_id,$task->id]) }}
                                                                  @elseauth('client'){{ route('client-tasks-destroy',[$currentWorkspace->slug,$task->project_id,$task->id]) }} @endauth"
                                                                  method="POST" style="display: none;">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        @elseif(isset($permissions))
                                                            @if(in_array('edit task',$permissions))
                                                                <a href="#"
                                                                   class="action-btn btn-info mx-1  btn btn-sm d-inline-flex align-items-center"
                                                                   data-ajax-popup="true" data-size="lg"
                                                                   data-toggle="popover" title="{{__('Edit')}}"
                                                                   data-title="{{__('Edit Task')}}"
                                                                   data-url="{{route('admin-tasks-edit',[$currentWorkspace->slug,$task->project_id,$task->id,])}}"><i
                                                                        class="ti ti-edit"></i></a>
                                                                @auth('web')
                                                                    {{route('admin-tasks-edit',[$currentWorkspace->slug,$task->project_id,$task->id,])}}
                                                                @endauth
                                                            @endif

                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    @endif
                </div>


                {{--                FILES UPLOADS --}}
                <div class="col-md-12">
                    @if((isset($permissions) && in_array('show task', $permissions)) || $currentWorkspace->permission == 'Owner')

                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        @if(isset($uploadedFiles))
                                            <h5 class="mb-0">{{__('Project File(s)')}} ({{count($uploadedFiles)}})</h5>
                                        @else
                                            <h5 class="mb-0">{{__('Project File(s)')}} (0)</h5>

                                        @endif
                                    </div>

                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="" class="table table-bordered px-2">
                                        <thead>
                                        <tr>
                                            <th>{{__('Files')}}</th>

                                            <th>{{__('Action')}} </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($uploadedFiles))
                                            @foreach($uploadedFiles as  $key => $file)
                                                <tr>

                                                    <td>{{$file->name}}</td>

                                                    <td class="text-right">
                                                        <div class="col-auto">
                                                            @if($currentWorkspace->permission == 'Owner')
                                                                @auth('client')
                                                                    <a class="action-btn btn-info mx-1  btn btn-sm d-inline-flex align-items-center"
                                                                       href="{{route('client-tasks-download',[$currentWorkspace->slug,$file->id,])}}"><i
                                                                            class="ti ti-download"></i></a>
                                                                @elseauth('web')
                                                                    <a class="action-btn btn-info mx-1  btn btn-sm d-inline-flex align-items-center"
                                                                       href="{{route('admin-tasks-download',[$currentWorkspace->slug,$file->id,])}}"><i
                                                                            class="ti ti-download"></i></a>
                                                                @endauth

                                                                <a href="#"
                                                                   class="action-btn btn-danger mx-1  btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                                   data-confirm="{{__('Are You Sure?')}}"
                                                                   data-toggle="popover" title="{{__('Delete')}}"
                                                                   data-text="{{__('This action can not be undone. Do you want to continue?')}}"
                                                                   data-confirm-yes="delete-form1-{{$file->id}}"><i
                                                                        class="ti ti-trash"></i></a>
                                                                <form id="delete-form1-{{$file->id}}"
                                                                      action="{{ route('tasks-file-destroy',[$currentWorkspace->slug,$file->id]) }}"
                                                                      method="POST" style="display: none;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                            @elseif(isset($permissions))
                                                                @if(in_array('edit task',$permissions))
                                                                    <a href="#"
                                                                       class="action-btn btn-info mx-1  btn btn-sm d-inline-flex align-items-center"
                                                                       data-ajax-popup="true" data-size="lg"
                                                                       data-toggle="popover" title="{{__('Edit')}}"
                                                                       data-title="{{__('Edit Task')}}"
                                                                       data-url="{{route('admin-tasks-edit',[$currentWorkspace->slug,$task->project_id,$task->id,])}}"><i
                                                                            class="ti ti-edit"></i></a>
                                                                    @auth('web')
                                                                        {{route('admin-tasks-edit',[$currentWorkspace->slug,$task->project_id,$task->id,])}}
                                                                    @endauth
                                                                @endif
                                                                @if(in_array('delete milestone',$permissions))
                                                                    <a href="#"
                                                                       class="action-btn btn-danger mx-1  btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                                       data-confirm="{{__('Are You Sure?')}}"
                                                                       data-toggle="popover" title="{{__('Delete')}}"
                                                                       data-text="{{__('This action can not be undone. Do you want to continue?')}}"
                                                                       data-confirm-yes="delete-form1-{{$task->id}}"><i
                                                                            class="ti ti-trash"></i></a>
                                                                    <form id="delete-form1-{{$task->id}}"
                                                                          action="{{ route($client_keyword.'projects.milestone.destroy',[$currentWorkspace->slug,$task->id]) }}"
                                                                          method="POST" style="display: none;">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                    </form>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    @endif
                </div>

                {{--                FIELS UPLOADS ENDS HERE--}}
            </div>
            <!-- [ sample-page ] end -->
        </div>
        <!-- [ Main Content ] end -->
    </div>

    {{--TEXT AREA --}}

{{--    @include('comment._comment_form')--}}
    @include('projects.action_button._action_button')
@endsection


@push('css-page')
    <link rel="stylesheet" href="{{asset('custom/css/dropzone.min.css')}}">
@endpush
@push('scripts')

    <!--
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

 -->
    <script src="{{asset('assets/js/plugins/apexcharts.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#summernote').summernote();
        });
        (function () {
            var options = {
                chart: {
                    type: 'area',
                    height: 60,
                    sparkline: {
                        enabled: true,
                    },
                },
                colors: {!! json_encode($chartData['color']) !!},
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 2,
                },
                series: [@foreach($chartData['stages'] as $id => $name)
                {
                    name: "{{ __($name)}}",
                    // data:
                    data: {!! json_encode($chartData[$id]) !!},
                },
                    @endforeach],
                xaxis: {
                    type: "category",
                    categories: {!! json_encode($chartData['label']) !!},
                    title: {
                        text: '{{ __("Days") }}'
                    },
                    tooltip: {
                        enabled: false,
                    }
                },
                yaxis: {
                    show: true,
                    position: "left",
                    title: {
                        text: '{{ __("Tasks") }}'
                    },
                },
                grid: {
                    show: true,
                    borderColor: "#EBEBEB",
                    strokeDashArray: 0,
                    position: "back",
                    xaxis: {
                        show: true,
                        lines: {
                            show: true,
                        },
                    },
                    yaxis: {
                        show: false,
                        lines: {
                            show: false,
                        },
                    },
                    row: {
                        colors: undefined,
                        opacity: 0.5,
                    },
                    column: {
                        position: "back",
                        colors: undefined,
                        opacity: 0.5,
                    },
                    padding: {
                        top: 0,
                        right: 0,
                        bottom: 0,
                        left: 0,
                    },
                },
                tooltip: {
                    followCursor: false,
                    fixed: {
                        enabled: false
                    },
                    x: {
                        format: 'dd/MM/yy HH:mm'
                    },

                    marker: {
                        show: false
                    }
                }
            }
            var chart = new ApexCharts(document.querySelector("#task-chart"), options);
            chart.render();
        })();

    </script>






    <script>
        $(document).ready(function () {
            if ($(".top-10-scroll").length) {
                $(".top-10-scroll").css({
                    "max-height": 300
                }).niceScroll();
            }
        });

    </script>

    <script src="{{asset('custom/js/dropzone.min.js')}}"></script>
    <script>
        Dropzone.autoDiscover = false;
        myDropzone = new Dropzone("#dropzonewidget", {
            maxFiles: 20,
            maxFilesize: 20,
            parallelUploads: 1,
            acceptedFiles: ".jpeg,.jpg,.png,.gif,.svg,.pdf,.txt,.doc,.docx,.zip,.rar",

            url: "@auth('client') {{route('projects-file-upload',[$currentWorkspace->slug,$project->project_id])}}
                @elseauth('web') {{route('admin-file-upload',[$currentWorkspace->slug,$project->project_id])}}@endauth",
            success: function (file, response) {
                if (response.is_success) {
                    dropzoneBtn(file, response);
                } else {
                    myDropzone.removeFile(file);
                    toastr('{{__('Error')}}', response.error, 'error');
                }
            },
            error: function (file, response) {
                myDropzone.removeFile(file);
                if (response.error) {
                    toastr('{{__('Error')}}', response.error, 'error');
                } else {
                    toastr('{{__('Error')}}', response, 'error');
                }
            }
        });
        myDropzone.on("sending", function (file, xhr, formData) {
            formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
            formData.append("project_id", {{$project->id}});
        });

        @if(isset($permisions) && in_array('show uploading',$permisions))
        $(".dz-hidden-input").prop("disabled", true);
        myDropzone.removeEventListeners();
        @endif

        function dropzoneBtn(file, response) {

            var html = document.createElement('span');
            var download = document.createElement('a');
            download.setAttribute('href', response.download);
            download.setAttribute('class', "action-btn btn-primary mx-1  btn btn-sm d-inline-flex align-items-center");
            download.setAttribute('data-toggle', "popover");
            download.setAttribute('title', "{{__('Download')}}");
            // download.innerHTML = "<i class='fas fa-download mt-2'></i>";
            download.innerHTML = "<i class='ti ti-download'> </i>";
            html.appendChild(download);

            @if(isset($permisions) && in_array('show uploading',$permisions))
            @else
            var del = document.createElement('a');
            del.setAttribute('href', response.delete);
            del.setAttribute('class', "action-btn btn-danger mx-1  btn btn-sm d-inline-flex align-items-center");
            del.setAttribute('data-toggle', "popover");
            del.setAttribute('title', "{{__('Delete')}}");
            del.innerHTML = "<i class='ti ti-trash '></i>";

            del.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                if (confirm("Are you sure ?")) {
                    var btn = $(this);
                    $.ajax({
                        url: btn.attr('href'),
                        type: 'DELETE',
                        success: function (response) {
                            if (response.is_success) {
                                btn.closest('.dz-image-preview').remove();
                            } else {
                                toastr('{{__('Error')}}', response.error, 'error');
                            }
                        },
                        error: function (response) {
                            response = response.responseJSON;
                            if (response.is_success) {
                                toastr('{{__('Error')}}', response.error, 'error');
                            } else {
                                toastr('{{__('Error')}}', response, 'error');
                            }
                        }
                    })
                }
            });
            html.appendChild(del);
            @endif

            file.previewTemplate.appendChild(html);
        }

        @php($files = $project->files)
        @foreach($files as $file)

        @php($storage_file = storage_path('project_files/'.$file->file_path))
        // Create the mock file:
        var mockFile = {
            name: "{{$file->file_name}}",
            size: {{ file_exists($storage_file) ? filesize($storage_file) : 0 }}
        };
        // Call the default addedfile event handler
        myDropzone.emit("addedfile", mockFile);
        // And optionally show the thumbnail of the file:
        myDropzone.emit("thumbnail", mockFile, "{{asset('storage/project_files/'.$file->file_path)}}");
        myDropzone.emit("complete", mockFile);

        dropzoneBtn(mockFile, {
            download: "@auth('client') {{route('projects-file-download',[$currentWorkspace->slug,$project->id,$file->id])}}
                @elseauth('web') {{route('admin-file-download',[$currentWorkspace->slug,$project->project_id])}}@endauth",


            delete: "@auth('client') {{route('projects-file-delete',[$currentWorkspace->slug,$project->id,$file->id])}}
                @elseauth('web') {{route('admin-file-delete',[$currentWorkspace->slug,$project->project_id])}}@endauth",
        });

        @endforeach
    </script>

@endpush
