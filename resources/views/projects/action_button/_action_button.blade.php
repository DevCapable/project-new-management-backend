<div class="card-body p-3">
    <div class="timeline timeline-one-side " data-timeline-content="axis" data-timeline-axis-style="dashed">
        <form method="post" action="@auth('client'){{ route('store-client-project',$currentWorkspace->slug) }}
        @elseauth('web'){{ route('store-admin-project',$currentWorkspace->slug) }} @endauth">
            @csrf
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">{{ __('Comment') }}</h5>
                        </div>
                    </div>
                </div>
                <textarea id="summernote" name="comment"></textarea>
            </div>
            <input type="hidden" name="project_id" value="{{$project->project_id}}">

            <br>


            <div style="float: right">
                @auth('client')
                    @if($currentWorkspace->permission == 'Owner')
                        @if ($project->status == 'NotSubmitted')
                            <div class="d-flex align-items-center ">

                                <button type="submit" name="action" value="submit"
                                        class="btn  btn-danger btn-lg btn-block float-md-right">
                                    <i class="ti ti-send fa-lg"></i>{{ __('Submit project for review')}}
                                </button>

                                <button type="submit" name="action" value="project_page"
                                        class="btn btn-success btn-lg btn-block float-md-right">
                                    <i class="fa fa-envelope-open-text fa-lg"></i>{{ __('Dashboard')}}
                                </button>

                                <button class="btn btn-light d-flex align-items-between me-3">
                                    <a href="#" class=""
                                       data-url="{{ route('edit-client-project',[$currentWorkspace->slug,$project->project_id]) }}"
                                       data-ajax-popup="true" data-title="{{__('Edit Project')}}"
                                       data-toggle="popover" title="{{__('Edit')}}">
                                        <i class="ti ti-edit"> </i>Edit
                                    </a>
                                </button>

                        @else
                            <span class="badge rounded-pill bg-danger">Submitted</span>

                        @endif

            @endif

            @elseauth('web')
                    @if($currentWorkspace->permission == 'Owner' && !$project->creater)
                        @if ($project->status == 'NotSubmitted')
                            <div class="d-flex align-items-center ">

                                <button type="submit" name="action" value="submit"
                                        class="btn  btn-danger btn-lg btn-block float-md-right">
                                    <i class="ti ti-send fa-lg"></i>{{ __('Submit project for review')}}
                                </button>

                                <button type="submit" name="action" value="submit"
                                        class="btn btn-success btn-lg btn-block float-md-right">
                                    <i class="fa fa-envelope-open-text fa-lg"></i>{{ __('Dashboard')}}
                                </button>

                                <div class="d-flex align-items-center ">
                                    <button class="btn btn-outline-success d-flex align-items-between me-3">
                                        <a href="#" class=""
                                           data-url="{{ route('admin-project-review',[$currentWorkspace->slug,$project->project_id]) }}"
                                           data-ajax-popup="true" data-title="{{__('Review Project')}}"
                                           data-toggle="popover" title="{{__('Review')}}">
                                            <i class="ti ti-box-multiple-7"> Review </i>
                                        </a>
                                    </button>
                                </div>


                                @else
                                    <span class="badge rounded-pill bg-danger">Submitted</span>

                                @endif

                                @else
            @endif
            @endauth
        </form>
    </div>
</div>
