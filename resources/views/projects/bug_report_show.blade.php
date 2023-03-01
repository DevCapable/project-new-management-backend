@if($currentWorkspace && $bug)

    <div class="p-2">
        <div class="form-control-label">{{ __('Description')}}:</div>

        <p class="text-muted mb-4">
            {{$bug->description}}
        </p>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="form-control-label">{{ __('Create Date')}}</div>
                <p class="mt-1">{{ \App\Models\Utility::dateFormat($bug->created_at) }}</p>
            </div>
            <div class="col-md-3">
                <div class="form-control-label">{{ __('Assigned')}}</div>
                <img @if($bug->user->avatar) src="{{asset('/storage/avatars/'.$bug->user->avatar)}}" @else avatar="{{ $bug->user->name }}" @endif class="rounded-circle mt-1 w-20">
            </div>
        </div>

        <ul class="nav nav-tabs bordar_styless mb-3" id="myTab" role="tablist">
            <li>
                <a class=" active" id="comments-tab" data-toggle="tab" href="#comments-data" role="tab" aria-controls="home" aria-selected="false"> {{ __('Comments') }} </a>
            </li>
            <li class="annual-billing">
                <a id="file-tab" data-toggle="tab" href="#file-data" role="tab" aria-controls="profile" aria-selected="false"> {{ __('Files') }} </a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade active show" id="comments-data" role="tabpanel" aria-labelledby="comments-tab">
                <form method="post" id="form-comment" data-action="{{route('bug.comment.store',[$currentWorkspace->slug,$bug->project_id,$bug->id,$clientID])}}">
                    <textarea class="form-control form-control-light mb-2" name="comment" placeholder="{{ __('Write message')}}" id="example-textarea" rows="3" required></textarea>
                    <div class="text-end">
                        <div class="btn-group mb-2 ml-2 d-sm-inline-block">
                            <button type="button" class="btn btn btn-primary">{{ __('Submit')}}</button>
                        </div>
                    </div>
                </form>

                <div class="card-body">
                    <ul class="list-unstyled list-unstyled-border mt-3" id="comments">

                        @foreach($bug->comments as $comment)
                            <li class="media border-bottom mb-3">
                                <img class="mr-3 avatar-sm rounded-circle img-thumbnail" width="" style="max-width: 30px; max-height: 30px;"
                                     @if($comment->user_type != 'Client') @if($comment->user->avatar) src="{{asset('/storage/avatars/'.$comment->user->avatar)}}" @else avatar="{{ $comment->user->name }}" @endif alt="{{ $comment->user->name }}"
                                     @else avatar="{{ $comment->client->name }}" alt="{{ $comment->client->name }}" @endif />
                                <div class="media-body mb-2">
                                    <div class="float-left">
                                        <h5 class="mt-0 mb-1 form-control-label">@if($comment->user_type!='Client'){{$comment->user->name}}@else {{$comment->client->name}} @endif</h5>
                                        {{$comment->comment}}
                                    
                                    @auth('web')
                                        <div class="text-end row_line_style">
                                            <a href="#" class="action-btn btn-danger  btn btn-sm d-inline-flex align-items-center delete-comment" data-url="{{route('bug.comment.destroy',[$currentWorkspace->slug,$bug->project_id,$bug->id,$comment->id])}}">
                                                <i class="ti ti-trash"></i>
                                            </a>
                                        </div>
                                    @endauth
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="tab-pane fade" id="file-data" role="tabpanel" aria-labelledby="file-tab">
                <form method="post" id="form-file" enctype="multipart/form-data" data-url="{{ route('bug.comment.store.file',[$currentWorkspace->slug,$bug->project_id,$bug->id,$clientID]) }}">
                      @csrf
                    <div class="choose-file mt-3">
                        <label for="file" class="">
                             <div class="logo-content">
                                    <img src="{{asset('/storage/tasks/'.'sample.jpg')}}" class="preview_img_size" id="task_file"/>
                                </div>
                            <div class=" bg-primary"> <i class="ti ti-upload px-1"></i>{{__('Choose file here')}}</div>
                            <input type="file" class="form-control" name="file" id="file" data-filename="file_create">
                            <span class="invalid-feedback" id="file-error" role="alert">
                                <strong></strong>
                            </span>
                        </label>
                        <p class="file_create"></p>
                    </div>
                    <div class="text-end">
                        <div class="">
                            <button type="submit" class="btn btn-primary">{{ __('Upload') }}</button>
                        </div>
                    </div>
                </form>

                <div id="comments-file" class="mt-3">
                    @foreach($bug->bugFiles as $file)
                        <div class="card pb-0 mb-1 shadow-none border">
                            <div class="card-body p-3">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="avatar-sm">
                                        <span class="avatar-title rounded text-uppercase">
                                           <img src="{{asset('/storage/tasks/'.$file->file)}}" class="preview_img_size" id=""/>
                                        </span>
                                        </div>
                                    </div>
                                    <div class="col pl-0">
                                        <a href="#" class="text-muted form-control-label">{{$file->name}}</a>
                                        <p class="mb-0">{{$file->file_size}}</p>
                                    </div>
                                    <div class="col-auto">
                                        <a download href="{{asset('/storage/tasks/'.$file->file)}}" class="action-btn btn-primary  btn btn-sm d-inline-flex align-items-center">
                                            <i class="ti ti-download"  data-toggle="tooltip" title="{{ __('Download') }}"></i>
                                        </a>

                                          <a class="action-btn btn-secondary  btn btn-sm d-inline-flex align-items-center" href="{{asset('/storage/tasks/'.$file->file)}}" target="_blank"  >
                                        <i class="ti ti-crosshair text-white" data-toggle="tooltip" title="{{ __('Preview') }}"></i>
                                       </a>
                                        <a href="#" class="action-btn btn-danger  btn btn-sm d-inline-flex align-items-center delete-comment-file" data-url="{{route('bug.comment.destroy.file',[$currentWorkspace->slug,$bug->project_id,$bug->id,$file->id])}}">
                                            <i class="ti ti-trash"  data-toggle="tooltip" title="{{ __('Delete') }}"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
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
<script type="text/javascript">
$('#file').change(function(){
   
let reader = new FileReader();
reader.onload = (e) => { 
  $('#task_file').attr('src', e.target.result); 
}
reader.readAsDataURL(this.files[0]); 

});
</script>