@extends('layouts.admin')
@php
    $permissions = Auth::user()->getPermission($project->id);
    $client_keyword = Auth::user()->getGuard() == 'client' ? 'client.' : '';
@endphp
 
@section('page-title') {{__('Bug Report')}} @endsection
@section('links')
@if(\Auth::guard('client')->check())   
<li class="breadcrumb-item"><a href="{{route('client.home')}}">{{__('Home')}}</a></li>
 @else
 <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('Home')}}</a></li>
 @endif
 @if(\Auth::guard('client')->check())  
<li class="breadcrumb-item"><a href="{{ route('client.projects.index',$currentWorkspace->slug) }}">{{__('Project')}}</a></li>
 @else  
<li class="breadcrumb-item"><a href="{{ route('projects.index',$currentWorkspace->slug)}}">{{__('Project')}}</a></li>
@endif
<li class="breadcrumb-item"><a href="{{route($client_keyword.'projects.show',[$currentWorkspace->slug,$project->id])}}">{{__('Project Details')}}</a></li>
<li class="breadcrumb-item">{{__('Bug Report')}}</li>
@endsection

@section('action-button')
    @if((isset($permissions) && in_array('create bug report',$permissions)) || ($currentWorkspace && $currentWorkspace->permission == 'Owner'))
        <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="lg"
           data-title="{{ __('Create New Bug') }}" data-url="{{route($client_keyword.'projects.bug.report.create',[$currentWorkspace->slug,$project->id])}}" data-toggle="tooltip" title="{{ __('Add Bug') }}"><i class="ti ti-plus"></i></a> 
        @endif

    <a href="{{route($client_keyword.'projects.show',[$currentWorkspace->slug,$project->id])}}" class="btn-submit btn btn-sm btn-primary mx-1" data-toggle="tooltip" title="{{ __('Back') }}">
        <i class="ti ti-arrow-back-up"></i> 
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
                            <div class="card-header" >
                                <div class="float-end">
                                    <button class="btn-submit btn btn-md btn-primary btn-icon px-1  py-0">
                                       <span class="badge badge-secondary rounded-pill count">{{$stage->bugs->count()}}</span>
                                    </button>
                                </div>
                                <h4 class="mb-0" >{{$stage->name}}</h4>
                         
                            </div>
                            <div id="{{'task-list-'.str_replace(' ','_',$stage->id)}}" data-status="{{$stage->id}}" class="card-body kanban-box">
                                 @foreach($stage->bugs as $bug)
                                <div class="card" id="{{$bug->id}}">
                                   <!--  <img class="img-fluid card-img-top" src=""
                                        alt=""> -->
                                    <div class="position-absolute top-0 start-0 pt-3 ps-3">
                                      @if($bug->priority =='Low')
                                            <div class="badge bg-success p-2 px-3 rounded"> {{ $bug->priority }}</div>
                                        @elseif($bug->priority =='Medium')
                                            <div class="badge bg-warning p-2 px-3 rounded"> {{ $bug->priority }}</div>
                                        @elseif($bug->priority =='High')
                                            <div class="badge bg-danger p-2 px-3 rounded"> {{ $bug->priority }}</div>
                                        @endif
                                    </div> 
                                    <div class="card-header border-0 pb-0 position-relative">

                                        <div style="padding: 30px 2px;"> <a href="#" data-url="{{route($client_keyword.'projects.bug.report.show',[$currentWorkspace->slug,$bug->project_id,$bug->id])}}" data-size="lg"  data-ajax-popup="true" data-title="{{__('Bug Detail')}}" class="h6 task-title"><h5>{{$bug->title}}</h5></a></div>

                                        <div class="card-header-right">
                                            <div class="btn-group card-option">
                                                    @if($currentWorkspace->permission == 'Owner' || isset($permissions))
                                                <button type="button" class="btn dropdown-toggle"
                                                    data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="feather icon-more-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                     <a href="#" class="dropdown-item" data-ajax-popup="true" data-size="lg" data-title="{{ __('View Bug') }}" data-url="{{route($client_keyword.'projects.bug.report.show',[$currentWorkspace->slug,$bug->project_id,$bug->id])}}"><i class="ti ti-eye"></i>
                                                            {{__('View')}}</a>
                                                    @if($currentWorkspace->permission == 'Owner')
                                                        <a href="#" class="dropdown-item" data-ajax-popup="true" data-size="lg" data-title="{{ __('Edit Bug') }}" data-url="{{route('projects.bug.report.edit',[$currentWorkspace->slug,$bug->project_id,$bug->id])}}"><i class="ti ti-edit"></i>
                                                            {{__('Edit')}}</a>
                                                        <a href="#" class="dropdown-item bs-pass-para" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$bug->id}}"><i class="ti ti-trash"></i>
                                                            {{__('Delete')}}
                                                        </a>
                                                        <form id="delete-form-{{$bug->id}}" action="{{ route('projects.bug.report.destroy',[$currentWorkspace->slug,$bug->project_id,$bug->id]) }}" method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    @elseif(isset($permissions))
                                                        @if(in_array('edit bug report',$permissions))
                                                            <a href="#" class="dropdown-item" data-ajax-popup="true" data-size="lg" data-title="{{ __('Edit Bug') }}" data-url="{{route($client_keyword.'projects.bug.report.edit',[$currentWorkspace->slug,$bug->project_id,$bug->id])}}"><i class="ti ti-edit"></i>
                                                                {{__('Edit')}}
                                                            </a>
                                                        @endif
                                                        @if(in_array('delete bug report',$permissions))
                                                            <a href="#" class="dropdown-item bs-pass-para" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="delete-form-{{$bug->id}}"><i class="ti ti-trash"></i>
                                                                {{__('Delete')}}
                                                            </a>
                                                            <form id="delete-form-{{$bug->id}}" action="{{ route($client_keyword.'projects.bug.report.destroy',[$currentWorkspace->slug,$bug->project_id,$bug->id]) }}" method="POST" style="display: none;">
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
                                            
                                        <div class="d-flex align-items-center justify-content-between">
                                            <ul class="list-inline mb-0">
                                           
                                                <li class="list-inline-item d-inline-flex align-items-center"><i
                                                        class="f-16 text-primary ti ti-message-2"></i> {{$bug->comments->count()}} {{ __('Comments') }}</li>
                                
                                            </ul>
                                              
                                               <div class="user-group">
                            

                                             @if($currentWorkspace->permission == 'Owner' || isset($permissions))
                                                                
                                                                        <a href="#" class="img_group">
                                                                            <img alt="image" data-toggle="tooltip" data-original-title="{{($bug->user)?$bug->user->name:''}}" @if(($bug->user)?$bug->user->avatar:'') src="{{asset('/storage/avatars/'.($bug->user)?$bug->user->avatar:'')}}" @else avatar="{{($bug->user)?$bug->user->name:'' }}"@endif>
                                                                        </a>
                                                                   
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
                                url:'{{route($client_keyword.'projects.bug.report.update.order',[$currentWorkspace->slug,$project->id])}}',
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
                @if(isset($permissions) && in_array('move bug report',$permissions) || $currentWorkspace->permission == 'Owner')
                    a.Dragula.init()
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
                                    "                    <img class='mr-3 avatar-sm rounded-circle img-thumbnail hight_img' width='60' " + avatar + " alt='" + data.user.name + "'>" +
                                    "                    <div class='media-body mb-2'>" +
                                    "                    <div class='float-left'>" +
                                    "                        <h5 class='mt-0 mb-1 form-control-label'>" + data.user.name + "</h5>" +
                                    "                        " + data.comment +
                                    "                           </div>" +
                                    "                           <div class='text-end'>" +
                                    "                               <a href='#' class='delete-icon delete-comment action-btn btn-danger  btn btn-sm d-inline-flex align-items-center' data-url='" + data.deleteUrl + "'>" +
                                    "                                   <i class='ti ti-trash'></i>" +
                                    "                               </a>" +
                                    "                           </div>" +
                                    "                    </div>" +
                                    "                </li>";
                            }
                            $("#comments").prepend(html);
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
                            delLink = "<a href='#' class='delete-icon delete-comment-file action-btn btn-danger  btn btn-sm d-inline-flex align-items-center delete-comment mx-1'  data-url='" + data.deleteUrl + "'>" +
                                "                                        <i class='ti ti-trash'></i>" +
                                "                                    </a>";
                        }

                        var html = "<div class='card mb-1 shadow-none border'>" +
                            "                        <div class='card-body py-2'>" +
                            "                            <div class='row align-items-center'>" +
                            "                                <div class='col-auto'>" +
                            "                                    <div class='avatar-sm'>" +
                            "                                        <span class='avatar-title rounded text-uppercase'>" +
                            "<img class='preview_img_size' " + "src='{{asset('/storage/tasks/')}}/" + data.file + "'>"+
                            "                                        </span>" +
                            "                                    </div>" +
                            "                                </div>" +
                            "                                <div class='col pl-0'>" +
                            "                                    <a href='#' class='text-muted form-control-label'>" + data.name + "</a>" +
                            "                                    <p class='mb-0'>" + data.file_size + "</p>" +
                            "                                </div>" +
                            "                                <div class='col-auto'>" +
                            "                                    <a download href='{{asset('/storage/tasks/')}}/" + data.file + "' class='action-btn btn-primary  btn btn-sm d-inline-flex align-items-center'>" +
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