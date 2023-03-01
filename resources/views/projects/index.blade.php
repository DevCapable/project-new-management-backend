@extends('layouts.admin')

@section('page-title') {{__('Projects')}} @endsection
@section('links')
    @if(\Auth::guard('client')->check())
        <li class="breadcrumb-item"><a href="{{route('client.home')}}">{{__('Home')}}</a></li>
    @else
        <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('Home')}}</a></li>
    @endif
    <li class="breadcrumb-item"> {{ __('Projects') }}</li>
@endsection
@section('action-button')
    @auth('web')

        <a href="{{ route('project.export') }}"  class="btn btn-sm btn-primary "  data-toggle="tooltip" title="{{ __('Export ') }}"
        > <i class="ti ti-file-x"></i></a>

        <a href="#"  class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-title="{{__('Import Project')}}" data-url="{{ route('project.file.import' ,$currentWorkspace->slug) }}"  data-toggle="tooltip" title="{{ __('Import') }}"><i class="ti ti-file-import"></i> </a>

        @if(isset($currentWorkspace) && $currentWorkspace->creater->id == Auth::id())
            <a href="{{route('create-new-admin-projects',$currentWorkspace->slug)}}" class="btn btn-sm btn-primary" data-toggle="tooltip" title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endif
    @endauth
    @auth('client')

        <a href="{{ route('project.export') }}"  class="btn btn-sm btn-primary "  data-toggle="tooltip" title="{{ __('Export ') }}"
        > <i class="ti ti-file-x"></i></a>

        <a href="#"  class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-title="{{__('Import Project')}}" data-url="{{ route('project.file.import' ,$currentWorkspace->slug) }}"  data-toggle="tooltip" title="{{ __('Import') }}"><i class="ti ti-file-import"></i> </a>

        @if(isset($currentWorkspace) || $currentWorkspace->creater->id == Auth::id())
            <a href="{{route('create-new-client-projects',$currentWorkspace->slug)}}" class="btn btn-sm btn-primary" data-toggle="tooltip" title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endif
    @endauth
@endsection

@section('content')
    <section class="section">
        @if($projects && $currentWorkspace)
            <div class="row mb-2">
                <div class="col-xl-12 col-lg-12 col-md-12 d-flex align-items-center justify-content-end">
                    <div class="text-sm-right status-filter">
                        <div class="btn-group mb-3">

                         @include('partials.buttons._status_butons')
                        </div>
                    </div>
                </div><!-- end col-->
            </div>

            <div class="filters-content">
                <div class="row grid">
                    @foreach ($projects as $project)
                        <div class="col-md-3 All {{ $project->status }}">
                            <div class="card">
                                <div class="card-header border-0 pb-0">
                                    <div class="d-flex align-items-center">
                                        @if($project->is_active)
                                            <a href="@auth('web'){{route('projects.show',[$currentWorkspace->slug,$project->id])}}@elseauth{{route('client.projects.show',[$currentWorkspace->slug,$project->id])}}@endauth" class="">
                                                <img alt="{{ $project->name }}" class="img-fluid wid-30 me-2 fix_img" avatar="{{ $project->name }}">
                                            </a>
                                        @else
                                            <a href="#" class="">
                                                <img alt="{{ $project->name }}" class="img-fluid wid-30 me-2 fix_img" avatar="{{ $project->name }}">
                                            </a>
                                        @endif

                                        <h5 class="mb-0">
                                            @if($project->is_active)
                                                @auth('web')
                                                    <a href="{{route('admin-show-project',[$currentWorkspace->slug,$project->project_id])}} " title="{{ $project->name }}" class="">{{ $project->name }}</a>
                                                @elseauth
                                                    <a href="{{route('show-client-project',[$currentWorkspace->slug,$project->project_id])}}" title="{{ $project->name }}" class="">{{ $project->name }}</a>
                                                @endauth
                                            @else
                                                <a href="#" title="{{ __('Locked') }}" class="">{{ $project->name }}</a>
                                            @endif
                                        </h5>
                                    </div>
                                    <div class="card-header-right">
                                        <div class="btn-group card-option">
                                            @auth('web')
                                                <button type="button" class="btn dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                    <i class="feather icon-more-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">


                                                    @if($project->is_active)

                                                        @if($currentWorkspace->permission == 'Owner')
                                                            <a href="#" class="dropdown-item" data-ajax-popup="true" data-size="md" data-title="{{ __('Invite Users') }}" data-url="{{route('admin-projects-invite-popup',[$currentWorkspace->slug,$project->project_id])}}">
                                                                <i class="ti ti-user-plus"></i> <span>{{ __('Invite Users') }}</span>
                                                            </a>
                                                            <a href="#" class="dropdown-item" data-ajax-popup="true" data-size="lg" data-title="{{ __('Edit Project') }}" data-url="{{route('admin-projects-edit',[$currentWorkspace->slug,$project->project_id])}}">
                                                                <i class="ti ti-edit"></i> <span>{{ __('Edit') }}</span>
                                                            </a>
                                                            <a href="#" class="dropdown-item" data-ajax-popup="true" data-size="md" data-title="{{ __('Share to Clients') }}" data-url="{{route('admin-projects-share-popup',[$currentWorkspace->slug,$project->project_id])}}">
                                                                <i class="ti ti-share"></i> <span>{{ __('Share to Clients')}}</span>
                                                            </a>
                                                            <a href="#" class="dropdown-item text-danger delete-popup bs-pass-para" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="delete-form-{{$project->project_id}}" >
                                                                <i class="ti ti-trash"></i>  <span>{{ __('Delete')}}</span>
                                                            </a>
                                                            <form id="delete-form-{{$project->project_id}}" action="{{ route('admin-projects-destroy',[$currentWorkspace->slug,$project->project_id]) }}" method="POST" style="display: none;">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        @else
                                                            <a href="#" class="dropdown-item text-danger bs-pass-para" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="leave-form-{{$project->id}}">
                                                                <i class="ti ti-trash"></i>  <span>{{ __('Delete')}}</span>
                                                            </a>
                                                            <form id="leave-form-{{$project->project_id}}" action="{{ route('admin-projects-leave',[$currentWorkspace->slug,$project->id]) }}" method="POST" style="display: none;">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        @endif

                                                    @else
                                                        <a href="#" class="dropdown-item" title="{{__('Locked')}}">
                                                            <i data-feather="lock"></i> <span>{{__('Locked')}}</span>
                                                        </a>
                                                    @endif

                                                </div>
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row g-2 justify-content-between">

                                        <div class="col-auto"> {!! get_projects_status_label($project->status) !!}</div>

                                        <div class="col-auto">
                                            <p class="mb-0"><b>{{ __('Due Date:')}}</b> {{$project->end_date}}</p>
                                        </div>
                                    </div>
                                    <p class="text-muted text-sm mt-3">{{ $project->description }}</p>

                                        <h6 class="text-muted">STAFF(S) INCHARGED</h6>
                                        <div class="user-group mx-2">
                                            @if(isset($project->users))
                                                @foreach($project->users as $user)
                                                    @if($user->pivot->is_active)
                                                        <!-- <img src="../assets/images/user/avatar-1.jpg" alt="image"> -->
                                                        <a href="#" class="img_group" data-toggle="tooltip" data-placement="top" title="{{$user->name}}">
                                                            <img alt="{{$user->name}}" @if($user->avatar) src="{{asset('/storage/avatars/'.$user->avatar)}}" @else avatar="{{ $user->name }}" @endif>
                                                        </a>
                                                    @endif
                                                @endforeach
                                            @endif
                                                <a href="@auth('web'){{route('projects.show',[$currentWorkspace->slug,$project->id])}}@elseauth{{route('client.projects.show',[$currentWorkspace->slug,$project->id])}}@endauth" class="">
                                                    <img alt="No staff yet" class="img-fluid wid-30 me-2 fix_img" avatar="N/A">
                                                </a>



                                        </div>

                                    <div class="card mb-0 mt-3">
                                        <div class="card-body p-3">
                                            <div class="row">
                                                <div class="col-6">
                                                    <h6 class="mb-0">{{$project->countTask()}}</h6>
                                                    <p class="text-muted text-sm mb-0">{{ __('Tasks')}}</p>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <h6 class="mb-0">{{$project->countTaskComments()}}</h6>
                                                    <p class="text-muted text-sm mb-0">{{ __('Comments')}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    @endforeach

                    @auth('web')
                        @if(isset($currentWorkspace) && $currentWorkspace->creater->id == Auth::id())
                            <div class="col-md-3 All add_project">
                                <a href="{{route('create-new-admin-projects',$currentWorkspace->slug)}}" class="btn-addnew-project " style="padding: 90px 10px;"  data-size="md" data-title="{{ __('Create New Project') }}">
                                    <div class="bg-primary proj-add-icon">
                                        <i class="ti ti-plus"></i>
                                    </div>
                                    <h6 class="mt-4 mb-2">Add Project</h6>
                                    <p class="text-muted text-center">Click here to add New Project</p>
                                </a>
                            </div>
                        @endif
{{--                            @if(isset($currentWorkspace))--}}
{{--                                <div class="col-md-3 All add_project">--}}
{{--                                    <a href="{{route('create-new-admin-projects',$currentWorkspace->slug)}}" class="btn-addnew-project " style="padding: 90px 10px;"  data-size="md" data-title="{{ __('Create New Project') }}">--}}
{{--                                        <div class="bg-primary proj-add-icon">--}}
{{--                                            <i class="ti ti-plus"></i>--}}
{{--                                        </div>--}}
{{--                                        <h6 class="mt-4 mb-2">Add Project</h6>--}}
{{--                                        <p class="text-muted text-center">Click here to add New Project {{$currentWorkspace->creater->id}}</p>--}}
{{--                                    </a>--}}
{{--                                </div>--}}
{{--                            @endif--}}
                    @endauth
                    @auth('client')
                        @if(isset($currentWorkspace) || $currentWorkspace->clientCreater->id == Auth::id())
                            <div class="col-md-3 All add_project">
                                <a href="{{route('create-new-client-projects',$currentWorkspace->slug)}}" class="btn-addnew-project " style="padding: 90px 10px;"  data-size="md" data-title="{{ __('Create New Project') }}">
                                    <div class="bg-primary proj-add-icon">
                                        <i class="ti ti-plus"></i>
                                    </div>
                                    <h6 class="mt-4 mb-2">Add Project</h6>
                                    <p class="text-muted text-center">Click here to add New Project</p>
                                </a>
                            </div>
                        @endif
                    @endauth

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

@push('css-page')
@endpush

@push('scripts')
    <script src="{{asset('custom/js/isotope.pkgd.min.js')}}"></script>
    <script>
        $(document).ready(function () {

            $('.status-filter button').click(function () {
                $('.status-filter button').removeClass('active');
                $(this).addClass('active');

                var data = $(this).attr('data-filter');
                $grid.isotope({
                    filter: data
                })
            });

            var $grid = $(".grid").isotope({
                itemSelector: ".All",
                percentPosition: true,
                masonry: {
                    columnWidth: ".All"
                }
            })
        });
    </script>

@endpush
