@extends('layouts.admin')
@php
//    $permissions = Auth::user()->getPermission($project->id);
    $client_keyword = Auth::user()->getGuard() == 'client' ? 'client.' : '';
@endphp
@section('page-title') {{__('Task Board')}} @endsection
@section('links')
@if(\Auth::guard('client')->check())
<li class="breadcrumb-item"><a href="{{route('client.home')}}">{{__('Home')}}</a></li>
 @else
 <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('Home')}}</a></li>
 @endif
 @if(\Auth::guard('client')->check())
<li class="breadcrumb-item"><a href="{{ route('client-projects-index',$currentWorkspace->slug) }}">{{__('Project')}}</a></li>
 @else
<li class="breadcrumb-item"><a href="{{ route('projects.index',$currentWorkspace->slug)}}">{{__('Project')}}</a></li>
@endif
<li class="breadcrumb-item"><a href="{{route($client_keyword.'projects.show',[$currentWorkspace->slug,$project->id])}}">{{__('Project Details')}}</a></li>
<li class="breadcrumb-item">{{__('Task Board')}}</li>
@endsection


@section('action-button')
{{--    @if((isset($permissions) && in_array('create task',$permissions)) || ($currentWorkspace && $currentWorkspace->permission == 'Owner'))--}}

{{--    @endif--}}
    <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="lg" data-title="{{ __('Create New Task') }}" data-url="{{route('client-tasks-create',[$currentWorkspace->slug,$project->id])}}" data-toggle="tooltip" title="{{ __('Add Task') }}"><i class="ti ti-plus"></i></a>

    <a href="{{route($client_keyword.'projects.show',[$currentWorkspace->slug,$project->id])}}" class="btn-submit btn btn-sm btn-primary mx-1" data-toggle="tooltip" title="{{ __('Back') }}">
        <i class=" ti ti-arrow-back-up"></i>
    </a>
@endsection

@section('content')
    <section class="section">
        @if($project && $currentWorkspace)
            <div class="row">
                <div class="col-sm-12">
                <div class="row kanban-wrapper horizontal-scroll-cards" data-toggle="dragula" data-containers='{{json_encode($statusClass)}}'>
                     @foreach($stages as $stage)
                    <div class="col" id="backlog">
                        <div class="card card-list">
                            <div class="card-header">
                                <div class="float-end">
                                    <button class="btn-submit btn btn-md btn-primary btn-icon px-1  py-0">
                                       <span class="badge badge-secondary rounded-pill count">{{$stage->tasks->count()}}</span>
                                    </button>
                                </div>
                                <h4 class="mb-0">{{$stage->name}}</h4>
                               <div class="col text-right">
                                                <span class="badge badge-secondary rounded-pill count">{{$stage->tasks->count()}}</span>
                                            </div>
                            </div>
                            <div id="{{'task-list-'.str_replace(' ','_',$stage->id)}}" data-status="{{$stage->id}}" class="card-body kanban-box">
                                 @foreach($stage->tasks as $task)

                                <div class="card" id="{{$task->id}}">
                                   <!--  <img class="img-fluid card-img-top" src=""
                                        alt=""> -->
                                    <div class="position-absolute top-0 start-0 pt-3 ps-3">
                                      @if($task->priority =='Low')
                                            <div class="badge bg-success p-2 px-3 rounded"> {{ $task->priority }}</div>
                                        @elseif($task->priority =='Medium')
                                            <div class="badge bg-warning p-2 px-3 rounded"> {{ $task->priority }}</div>
                                        @elseif($task->priority =='High')
                                            <div class="badge bg-danger p-2 px-3 rounded"> {{ $task->priority }}</div>
                                        @endif
                                    </div>
                                    <div class="card-header border-0 pb-0 position-relative">

                                        <div style="padding: 30px 2px;"> <a href="#" data-url="{{route($client_keyword.'tasks.show',[$currentWorkspace->slug,$task->project_id,$task->id])}}" data-ajax-popup="true" data-title="{{__('Task Detail')}}"  data-size="lg"  class="h6 task-title"><h5>{{$task->title}}</h5></a></div>
                                        <div class="card-header-right">
                                            <div class="btn-group card-option">
                                                    @if($currentWorkspace->permission == 'Owner' || isset($permissions))
                                                <button type="button" class="btn dropdown-toggle"
                                                    data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="feather icon-more-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="#" class="dropdown-item" data-ajax-popup="true" data-size="lg" data-title="{{ __('View Task') }}" data-url="{{route($client_keyword.'tasks.show',[$currentWorkspace->slug,$task->project_id,$task->id])}}"> <i class="ti ti-eye"></i>
                                                    {{__('View')}}</a>
                                                    @if($currentWorkspace->permission != 'Owner')
                                                        <a href="#" class="dropdown-item" data-ajax-popup="true" data-size="lg" data-title="{{ __('Edit Task') }}" data-url="{{route('tasks.edit',[$currentWorkspace->slug,$task->project_id,$task->id])}}"> <i class="ti ti-edit"></i>
                                                            {{__('Edit')}}</a>
                                                        <a href="#" class="dropdown-item bs-pass-para" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$task->id}}"> <i class="ti ti-trash"></i>
                                                            {{__('Delete')}}
                                                        </a>
                                                        <form id="delete-form-{{$task->id}}" action="{{ route('tasks.destroy',[$currentWorkspace->slug,$task->project_id,$task->id]) }}" method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    @elseif(isset($permissions))
                                                        @if(in_array('edit task',$permissions))
                                                            <a href="#" class="dropdown-item" data-ajax-popup="true" data-size="lg" data-title="{{ __('Edit Task') }}" data-url="{{route($client_keyword.'tasks.edit',[$currentWorkspace->slug,$task->project_id,$task->id])}}"> <i class="ti ti-edit"></i>
                                                                {{__('Edit')}}
                                                            </a>
                                                        @endif
                                                        @if(in_array('delete task',$permissions))
                                                            <a href="#" class="dropdown-item  delete-comment bs-pass-para" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="delete-form-{{$task->id}}"> <i class="ti ti-trash"></i>
                                                                {{__('Delete')}}
                                                            </a>
                                                            <form id="delete-form-{{$task->id}}" action="{{ route($client_keyword.'tasks.destroy',[$currentWorkspace->slug,$task->project_id,$task->id]) }}" method="POST" style="display: none;">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        @endif
                                                    @endif

                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0">
                                            <div class="row">
                                                            <div class="col">
                                                                <div class="action-item">
                                                                    {{\App\Models\Utility::dateFormat($task->start_date)}}
                                                                </div>
                                                            </div>
                                                            <div class="col text-right">
                                                                <div class="action-item">
                                                                    {{\App\Models\Utility::dateFormat($task->due_date)}}
                                                                </div>
                                                            </div>
                                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <ul class="list-inline mb-0">

                                                <li class="list-inline-item d-inline-flex align-items-center"><i
                                                        class="f-16 text-primary ti ti-brand-telegram"></i> {{$task->taskCompleteSubTaskCount()}}/{{$task->taskTotalSubTaskCount()}}</li>

                                            </ul>

                                               <div class="user-group">
                                            @if($users = $task->users())
                                                @foreach($users as $key => $user)
                                                    @if($key < 3)
                                                        <a href="#" class="img_group">
                                                            <img alt="image" data-toggle="tooltip" data-original-title="{{$user->name}}" @if($user->avatar) src="{{asset('/storage/avatars/'.$user->avatar)}}" @else avatar="{{ $user->name }}"@endif>
                                                        </a>
                                                    @endif
                                                @endforeach
                                                @if(count($users) > 3)
                                                    <a href="#" class="img_group">
                                                        <img alt="image" data-toggle="tooltip" data-original-title="{{count($users)-3}} {{__('more')}}" avatar="+ {{ count($users)-3 }}">
                                                    </a>
                                                @endif
                                            @endif
                                             </div>
                                        </div>
                                    </div>
                                </div>
                             @endforeach
                             <span class="empty-container" data-placeholder="Empty"></span>
                            </div>
                        </div>
                    </div>
                    @endforeach



                </div>
                <!-- [ sample-page ] end -->
            </div>
            </div>
        @else
            <div class="container mt-5">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="page-error">
                            <div class="page-inner">
                                <h1>404</h1>
                                <div class="page-description">
                                    {{ __('Page Not Found') }}
                                </div>
                                <div class="page-search">
                                    <p class="text-muted mt-3">{{ __("It's looking like you may have taken a wrong turn. Don't worry... it happens to the best of us. Here's a little tip that might help you get back on track.")}}</p>
                                    <div class="mt-3">
                                        <a class="btn-return-home badge-blue" href="{{route('home')}}"><i class="fas fa-reply"></i> {{ __('Return Home')}}</a>
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
@if($project && $currentWorkspace)
    @push('scripts')
        <!-- third party js -->
        <script src="{{ asset('custom/js/dragula.min.js') }}"></script>
        <script>
            !function (a) {
                "use strict";
                var t = function () {
                    this.$body = a("body")
                };
                t.prototype.init = function () {
                    a('[data-toggle="dragula"]').each(function () {
                        var t = a(this).data("containers"), n = [];
                        if (t) for (var i = 0; i < t.length; i++) n.push(a("#" + t[i])[0]); else n = [a(this)[0]];
                        var r = a(this).data("handleclass");
                        r ? dragula(n, {
                            moves: function (a, t, n) {
                                return n.classList.contains(r)
                            }
                        }) : dragula(n).on('drop', function (el, target, source, sibling) {
                            var sort = [];
                            $("#" + target.id + " > div").each(function (key) {
                                sort[key] = $(this).attr('id');
                            });
                            var id = el.id;
                            var old_status = $("#" + source.id).data('status');
                            var new_status = $("#" + target.id).data('status');
                            var project_id = '{{$project->id}}';

                            $("#" + source.id).parents('.card-list').find('.count').text($("#" + source.id + " > div").length);
                            $("#" + target.id).parents('.card-list').find('.count').text($("#" + target.id + " > div").length);
                            $.ajax({
                                url: '{{route('client-task-order',[$currentWorkspace->slug,$project->id])}}',
                                type: 'POST',
                                data: {
                                    id: id,
                                    sort: sort,
                                    new_status: new_status,
                                    old_status: old_status,
                                    project_id: project_id,
                                },
                                success: function (data) {
                                    // console.log(data);
                                }
                            });
                        });
                    })
                }, a.Dragula = new t, a.Dragula.Constructor = t
            }(window.jQuery), function (a) {
                "use strict";
                @if((isset($permissions) && in_array('move task',$permissions)) || ($currentWorkspace && $currentWorkspace->permission == 'Owner'))
                    a.Dragula.init();
                @endif
            }(window.jQuery);
        </script>
        <!-- third party js ends -->
        <script>
            $(document).on('click', '#form-comment button', function (e) {
                var comment = $.trim($("#form-comment textarea[name='comment']").val());
                if (comment != '') {
                    $.ajax({
                        url: $("#form-comment").data('action'),
                        data: {comment: comment},
                        type: 'POST',
                        success: function (data) {
                            data = JSON.parse(data);

                            if (data.user_type == 'Client') {
                                var avatar = "avatar='" + data.client.name + "'";
                                var html = "<li class='media border-bottom mb-3'>" +
                                    "                    <img class='mr-3 avatar-sm rounded-circle img-thumbnail hight_img' width='60' " + avatar + " alt='" + data.client.name + "'>" +
                                    "                    <div class='media-body mb-2'>" +
                                        "                    <div class='float-left'>" +
                                        "                        <h5 class='mt-0 mb-1 form-control-label'>" + data.client.name + "</h5>" +
                                        "                        " + data.comment +
                                        "                    </div>" +
                                    "                    </div>" +
                                    "                </li>";
                            } else {
                                var avatar = (data.user.avatar) ? "src='{{asset('/storage/avatars/')}}/" + data.user.avatar + "'" : "avatar='" + data.user.name + "'";
                                var html = "<li class='media border-bottom mb-3'>" +
                                    "                    <img class='mr-3 avatar-sm rounded-circle img-thumbnail hight_img ' width='60' " + avatar + " alt='" + data.user.name + "'>" +
                                    "                    <div class='media-body mb-2'>" +
                                        "                    <div class='float-left'>" +
                                    "                        <h5 class='mt-0 mb-1 form-control-label'>" + data.user.name + "</h5>" +
                                    "                        " + data.comment +
                                    "                           </div>" +
                                    "                           <div class='text-end'>" +
                                    "                               <a href='#' class='delete-icon action-btn btn-danger  btn btn-sm d-inline-flex align-items-center delete-comment' data-url='" + data.deleteUrl + "'>" +
                                    "                                   <i class='ti ti-trash'></i>" +
                                    "                               </a>" +
                                    "                           </div>" +
                                    "                    </div>" +
                                    "                </li>";
                            }

                            $("#task-comments").prepend(html);
                            LetterAvatar.transform();
                            $("#form-comment textarea[name='comment']").val('');
                            show_toastr('{{__('Success')}}', '{{ __("Comment Added Successfully!")}}', 'success');
                        },
                        error: function (data) {
                            show_toastr('{{__('Error')}}', '{{ __("Some Thing Is Wrong!")}}', 'error');
                        }
                    });
                } else {
                    show_toastr('{{__('Error')}}', '{{ __("Please write comment!")}}', 'error');
                }
            });
            $(document).on("click", ".delete-comment", function () {
                if (confirm('{{__('Are you sure ?')}}')) {
                    var btn = $(this);
                    $.ajax({
                        url: $(this).attr('data-url'),
                        type: 'DELETE',
                        dataType: 'JSON',
                        success: function (data) {
                            show_toastr('{{__('Success')}}', '{{ __("Comment Deleted Successfully!")}}', 'success');
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
            $(document).on('click', '#form-subtask button', function (e) {
                e.preventDefault();

                var name = $.trim($("#form-subtask input[name=name]").val());
                var due_date = $.trim($("#form-subtask input[name=due_date]").val());
                if (name == '' || due_date == '') {
                    show_toastr('{{__('Error')}}', '{{ __("Please enter fields!")}}', 'error');
                    return false;
                }

                $.ajax({
                    url: $("#form-subtask").data('action'),
                    type: 'POST',
                    data: {
                        name: name,
                        due_date: due_date,
                    },
                    dataType: 'JSON',
                    success: function (data) {
                        show_toastr('{{__('Success')}}', '{{ __("Sub Task Added Successfully!")}}', 'success');

                        var html = '<li class="list-group-item py-3">' +
                            '    <div class="form-check form-switch d-inline-block">' +
                            '        <input type="checkbox" class="form-check-input" name="option" id="option' + data.id + '" value="' + data.id + '" data-url="' + data.updateUrl + '">' +
                            '        <label class="custom-control-label form-control-label" for="option' + data.id + '">' + data.name +'</label>' +
                            '    </div>' +
                            '    <div class="text-end">' +
                            '        <a href="#" class=" action-btn btn-danger  btn btn-sm d-inline-flex align-items-center delete-comment delete-icon delete-subtask" data-url="' + data.deleteUrl + '">' +
                            '            <i class="ti ti-trash"></i>' +
                            '        </a>' +
                            '    </div>' +
                            '</li>';

                        $("#subtasks").prepend(html);
                        $("#form-subtask input[name=name]").val('');
                        $("#form-subtask input[name=due_date]").val('');
                        $("#form-subtask").collapse('toggle');
                    },
                    error: function (data) {
                        data = data.responseJSON;
                        if (data.message) {
                            show_toastr('{{__('Error')}}', data.message, 'error');
                            $('#file-error').text(data.errors.file[0]).show();
                        } else {
                            show_toastr('{{__('Error')}}', '{{ __("Some Thing Is Wrong!")}}', 'error');
                        }
                    }
                });
            });
            $(document).on("change", "#subtasks input[type=checkbox]", function () {
                $.ajax({
                    url: $(this).attr('data-url'),
                    type: 'POST',
                    dataType: 'JSON',
                    success: function (data) {
                        show_toastr('{{__('Success')}}', '{{ __("Subtask Updated Successfully!")}}', 'success');
                        // console.log(data);
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
            });
            $(document).on("click", ".delete-subtask", function () {
                if (confirm('{{__('Are you sure ?')}}')) {
                    var btn = $(this);
                    $.ajax({
                        url: $(this).attr('data-url'),
                        type: 'DELETE',
                        dataType: 'JSON',
                        success: function (data) {
                            show_toastr('{{__('Success')}}', '{{ __("Subtask Deleted Successfully!")}}', 'success');
                            btn.closest('.list-group-item').remove();
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
            // $("#form-file").submit(function(e){
             $(document).on('submit', '#form-file', function (e) {
                e.preventDefault();

                $.ajax({
                    url: $("#form-file").data('url'),
                    type: 'POST',
                    data: new FormData(this),
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        show_toastr('{{__('Success')}}', '{{ __("File Upload Successfully!")}}', 'success');
                        // console.log(data);
                        var delLink = '';

                        if (data.deleteUrl.length > 0) {
                            delLink = "<a href='#' class=' action-btn btn-danger  btn btn-sm d-inline-flex align-items-center delete-comment delete-icon delete-comment-file'  data-url='" + data.deleteUrl + "'>" +
                                "                                        <i class='ti ti-trash'></i>" +
                                "                                    </a>";
                        }

                        var html = "<div class='card mb-1 shadow-none border'>" +

                            "                        <div class='card-body p-3'>" +

                            "                            <div class='row align-items-center'>" +

                            "                                <div class='col-auto'>" +

                            "                                    <div class='avatar-sm'>" +

                            "                                        <span class='avatar-title rounded text-uppercase'>" +
                             "<img class='preview_img_size' " + "src='{{asset('/storage/tasks/')}}/" + data.file + "'>"
                           +
                            "                                        </span>" +
                            "                                    </div>" +
                            "                                </div>" +
                            "                                <div class='col pl-0'>" +
                            "                                    <a href='#' class='text-muted form-control-label'>" + data.name + "</a>" +
                            "                                    <p class='mb-0'>" + data.file_size + "</p>" +
                            "                                </div>" +
                            "                                <div class='col-auto'>" +
                            "                                    <a download href='{{asset('/storage/tasks/')}}/" + data.file + "' class='edit-icon action-btn btn-primary  btn btn-sm d-inline-flex align-items-center'>" +
                            "                                        <i class='ti ti-download'></i>" +
                            "                                    </a>" +


                            "                                   <a  href='{{asset('/storage/tasks/')}}/" + data.file + "' class='edit-icon action-btn btn-secondary  btn btn-sm d-inline-flex align-items-center mx-1'>" +
                            "                                        <i class='ti ti-crosshair text-white'></i>" +
                            "                                    </a>" +









                            delLink +
                            "                                </div>" +
                            "                            </div>" +
                            "                        </div>" +
                            "                    </div>";
                        $("#comments-file").prepend(html);
                    },
                    error: function (data) {
                        data = data.responseJSON;
                        if (data.message) {
                            show_toastr('{{__('Error')}}', data.message, 'error');
                            $('#file-error').text(data.errors.file[0]).show();
                        } else {
                            show_toastr('{{__('Error')}}', '{{ __("Some Thing Is Wrong!")}}', 'error');
                        }
                    }
                });
            });
            $(document).on("click", ".delete-comment-file", function () {
                if (confirm('{{__('Are you sure ?')}}')) {
                    var btn = $(this);
                    $.ajax({
                        url: $(this).attr('data-url'),
                        type: 'DELETE',
                        dataType: 'JSON',
                        success: function (data) {
                            show_toastr('{{__('Success')}}', '{{ __("File Deleted Successfully!")}}', 'success');
                            btn.closest('.border').remove();
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
        </script>
    @endpush
@endif
<style type="text/css">
    .hight_img{
        max-width: 30px !important;
       max-height: 30px !important;
    }
</style>
