@extends('layouts.admin')
@php
    $client_keyword = Auth::user()->getGuard() == 'client' ? 'client.' : '';
@endphp

@section('page-title')
    {{$contract->subject}}
@endsection

@push('css-page')
    <link rel="stylesheet" href="{{ asset('custom/libs/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{asset('custom/css/dropzone.min.css')}}">
@endpush

@section('links')
@if(\Auth::guard('client')->check())   
<li class="breadcrumb-item"><a href="{{route('client.home')}}">{{__('Home')}}</a></li>
 @else
 <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('Home')}}</a></li>
 @endif
 @if(\Auth::guard('client')->check())  
<li class="breadcrumb-item"><a href="{{route($client_keyword.'contracts.index',$currentWorkspace->slug)}}">{{__('Contracts')}}</a></li>
 @else
 <li class="breadcrumb-item"><a href="{{route($client_keyword.'contracts.index',$currentWorkspace->slug)}}">{{__('Contracts')}}</a></li> @endif 
<li class="breadcrumb-item">{{__('Contract Detail')}}</li>


@endsection

@section('action-button')
    <div class="row align-items-center m-1">
        @if ($currentWorkspace->permission == 'Owner')
            <div class="col-auto p-0 w-auto ">
                <a href="{{route('send.mail.contract',[$currentWorkspace->slug,$contract->id])}}" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" data-bs-original-title="{{__('Send Email')}}">
                    <i class="ti ti-mail text-white"></i>
                </a>
            </div>
        @endif
         @if ($currentWorkspace->permission == 'Owner')
            <div class="col-auto pe-0">
                <a href="#" class="btn btn-sm btn-primary btn-icon" data-url="{{ route('contracts.copy',[$currentWorkspace->slug,$contract->id]) }}" data-ajax-popup="true" data-title="{{__('Duplicate Contract')}}" data-size="lg" title="{{__('Duplicate')}}" data-bs-toggle="tooltip" data-bs-placement="top">
                    <i class="ti ti-files"></i>
                </a>
            </div>
        @endif
         @if ($currentWorkspace->permission == 'Owner' || \Auth::user()->getGuard() == 'client')
            <div class="col-auto pe-0">
                <a href="{{route('contract.download.pdf',[$currentWorkspace->slug,\Crypt::encrypt($contract->id)])}}" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Download')}}" target="_blanks"><i class="ti ti-download"></i></a>
            </div>
        @endif
        @if ($currentWorkspace->permission == 'Owner' || \Auth::user()->getGuard() == 'client')
            <div class="col-auto pe-0">
                <a href="{{route('get.contract',[$currentWorkspace->slug,$contract->id])}}" target="_blank" class="btn btn-sm btn-primary btn-icon" title="{{__('Preview')}}" data-bs-toggle="tooltip" data-bs-placement="top">
                    <i class="ti ti-eye"></i>
                </a>
            </div>
        @endif

        @if($currentWorkspace->permission == 'Owner' && $contract->company_signature == null ||\Auth::user()->getGuard() == 'client' && $contract->client_signature == null)

            <div class="col-auto pe-0">
                <a href="#" class="btn btn-sm btn-primary btn-icon" data-url="{{ route($client_keyword.'signature',[$currentWorkspace->slug,$contract->id]) }}" data-ajax-popup="true" data-title="{{__('Create Signature')}}" data-size="md" title="{{__('Signature')}}" data-bs-toggle="tooltip" data-bs-placement="top">
                    <i class="ti ti-pencil"></i>
                </a>
            </div>
        @endif
    </div>
@endsection

@section('content')
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3">
                           <div class="card sticky-top" style="top:30px">
                            <div class="list-group list-group-flush" id="useradd-sidenav">
                                <a href="#general" class="list-group-item list-group-item-action border-0">{{ __('General') }} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                                <a href="#attachments" class="list-group-item list-group-item-action border-0 ">{{ __('Attachment') }} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                                <a href="#comment" class="list-group-item list-group-item-action border-0">{{ __('Comment') }} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                                <a href="#notes" class="list-group-item list-group-item-action border-0">{{__('Notes')}} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            </div>
                        </div>
                </div>

                <div class="col-xl-9">
                    <div id="general">
                        <div class="row">
                            <div class="col-xl-7">
                                <div class="row">
                                    <div class="col-lg-4 col-6">
                                        <div class="card">
                                            <div class="card-body" style="min-height: 205px;">
                                                <div class="theme-avtar bg-primary">
                                                    <i class="ti ti-user-plus"></i>
                                                </div>
                                                <h6 class="mb-3 mt-4">{{ __('Attachment') }}</h6>
                                                    <h3 class="mb-0">{{count($contract->files)}}</h3>
                                                <h3 class="mb-0"></h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-6">
                                        <div class="card">
                                            <div class="card-body" style="min-height: 205px;">
                                                <div class="theme-avtar bg-info">
                                                    <i class="ti ti-click"></i>
                                                </div>
                                                <h6 class="mb-3 mt-4">{{ __('Comment') }}</h6>
                                                <h3 class="mb-0">{{count($contract->comment)}}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-6">
                                        <div class="card">
                                            <div class="card-body" style="min-height: 205px;">
                                                <div class="theme-avtar bg-warning">
                                                    <i class="ti ti-file"></i>
                                                </div>
                                                <h6 class="mb-3 mt-4 ">{{ __('Notes') }}</h6>
                                                <h3 class="mb-0">{{count($contract->note)}}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-5">
                                <div class="card report_card total_amount_card">
                                    <div class="card-body pt-0" style="margin-bottom: -30px; margin-top: -10px;">
                                        
                                        <address class="mb-0 text-sm">
                                            <dl class="row mt-4 align-items-center">
                                                <dt class="col-sm-4 h6 text-sm">{{ __('Name') }}</dt>
                                                <dd class="col-sm-8 text-sm"> {{  $contract->clients->name }}</dd>

                                                <dt class="col-sm-4 h6 text-sm">{{ __('Project') }}</dt>
                                                <dd class="col-sm-8 text-sm">{{!empty( $contract->projects)?$contract->projects->name:''}}</dd>

                                                <dt class="col-sm-4 h6 text-sm">{{ __('Subject') }}</dt>
                                                <dd class="col-sm-8 text-sm"> {{  $contract->subject }}</dd>

                                                <dt class="col-sm-4 h6 text-sm">{{ __('Value') }}</dt>
                                                <dd class="col-sm-8 text-sm">{{ $currentWorkspace->priceFormat($contract->value)}} </dd>

                                                <dt class="col-sm-4 h6 text-sm">{{__('Type')}}</dt>
                                                <dd class="col-sm-8 text-sm">{{$contract->contract_type->name }}</dd>

                                                <dt class="col-sm-4 h6 text-sm">{{__('Start Date')}}</dt>
                                                <dd class="col-sm-8 text-sm">{{App\Models\Utility::dateFormat($contract->start_date) }}</dd>

                                                <dt class="col-sm-4 h6 text-sm">{{__('End Date')}}</dt>
                                                <dd class="col-sm-8 text-sm">{{App\Models\Utility::dateFormat($contract->end_date) }}</dd>
                                            </dl>
                                        </address>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">{{ __('Description ') }}</h5>
                            </div>
                            <div class="card-body p-3">
                                {{ Form::open(['route' => [$client_keyword.'contract.contract_description.store', [$currentWorkspace->slug,$contract->id]]]) }}
                                    <div class="col-md-12">
                                        <div class="form-group mt-3">
                                            <textarea class="tox-target pc-tinymce-2" name="contract_description"  id="summernote" rows="8">{!! $contract->contract_description !!}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-end">
                                        <div class="form-group mt-3 me-3">

                                            @if(\Auth::user()->getGuard() == 'client')

                                              @if($contract->status == 'on')
                                                {{ Form::submit(__('Save Changes'), ['class' => 'btn  btn-primary']) }}
                                              @else
                                               -
                                              @endif

                                             @else
                                              {{ Form::submit(__('Save Changes'), ['class' => 'btn  btn-primary']) }}

                                             @endif

                                      
                                        </div>
                                    </div>

                                      {{ Form::close() }}
                                
                            </div>
                        </div>
                    </div>

                    <div id="attachments" >
                        <div class="row ">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>{{__('Attachments')}}</h5>
                                    </div>
                                    <div class="card-body">

                                      @if(\Auth::guard('client')->check()) 


                                           @if($contract->status == 'on')
                                                 <div class=" ">
                                                     <div class="col-md-12 dropzone browse-file" id="dropzonewidget">
                                                        <div class="dz-message" data-dz-message>
                                                            <span>
                                                                @if(Auth::user()->getGuard() == 'client')
                                                                    {{__('No files available')}}
                                                                @else
                                                                    {{__('Drop files here to upload')}}
                                                                @endif
                                                            </span>
                                                        </div>
                                                     </div>
                                                </div>
                                            @endif    
                                      @else
                                        <div class=" ">
                                             <div class="col-md-12 dropzone browse-file" id="dropzonewidget">
                                                <div class="dz-message" data-dz-message>
                                                    <span>
                                                        @if(Auth::user()->getGuard() == 'client')
                                                            {{__('No files available')}}
                                                        @else
                                                            {{__('Drop files here to upload')}}
                                                        @endif
                                                    </span>
                                                </div>
                                             </div>
                                        </div>
                                      @endif
                                    <div class="col-md-12 mt-3">
                                        <div class="list-group list-group-flush mb-0" id="attachments">
                                                @foreach($contract->files as $file)
                                        <div class="card mb-3 border shadow-none">
                                            <div class="px-3 py-3">
                                                <div class="row align-items-center">
                                                    <div class="col">
                                                        <h6 class="text-sm mb-0">
                                                            <a href="#!">{{ $file->files }}</a>
                                                        </h6>
                                                        <p class="card-text small text-muted">
                                                            {{ number_format(\File::size(storage_path('contract_attechment/' . $file->files)) / 1048576, 2) . ' ' . __('MB') }}
                                                        </p>
                                                    </div>
                                                    <div class="action-btn bg-warning p-0 w-auto    ">
                                                        <a href="{{ asset(Storage::url('contract_attechment')) . '/' . $file->files }}"
                                                            class=" btn btn-sm d-inline-flex align-items-center"
                                                            download="" data-bs-toggle="tooltip" title="Download">
                                                        <span class="text-white"><i class="ti ti-download"></i></span>
                                                        </a>
                                                    </div>
                                               @if(\Auth::guard('client')->check()) 

                                               @if($contract->status == 'on' && \Auth::user()->id == $file->client_id)

                                                         <div class="col-auto actions">
                                                        <div class="action-btn bg-danger ms-2">
                                                            <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="delete-form-{{$file->id}}" title="{{__('Delete')}}" data-bs-toggle="tooltip" data-bs-placement="top"><span class="text-white"><i class="ti ti-trash"></i></span></a>
            
                                                            {!! Form::open(['method' => 'DELETE', 'route' => [$client_keyword.'contracts.file.delete', [$currentWorkspace->slug,$file->id]], 'id' => 'delete-form-' . $file->id]) !!}
                                                            {!! Form::close() !!}
                                                        </div>   
                                                    </div>
                                                    @endif
                                                 
                                                @else

                                                 <div class="col-auto actions">
                                                        <div class="action-btn bg-danger ms-2">
                                                            <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="delete-form-{{$file->id}}" title="{{__('Delete')}}" data-bs-toggle="tooltip" data-bs-placement="top"><span class="text-white"><i class="ti ti-trash"></i></span></a>
            
                                                            {!! Form::open(['method' => 'DELETE', 'route' => [$client_keyword.'contracts.file.delete', [$currentWorkspace->slug,$file->id]], 'id' => 'delete-form-' . $file->id]) !!}
                                                            {!! Form::close() !!}
                                                        </div>   
                                                    </div>

                                                @endif   

                                               
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                   
                                        </div>
                                    </div>

                                </div>
                                </div>
                            </div>
                        </div>
                    </div>

                 
                    <div  id="comment" >
                        <div class="row pt-2">
                            <div class="col-12">
                                <div id="comment">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{__('Comments')}}</h5>
                                        </div>
                                        <div class="card-footer">

                                            @if(\Auth::guard('client')->check()) 
                                                 @if($contract->status == 'on')


                                                <div class="col-12 d-flex">
                                                     <div class="form-group mb-0 form-send w-100">
                                                    <form method="post" class="card-comment-box" id="form-comment" action="{{route($client_keyword.'comment_store.store', [$currentWorkspace->slug,$contract->id])}}">
                                                        @csrf
                                                        <textarea rows="1" class="form-control" name="comment" placeholder="Add a comment..." ></textarea><grammarly-extension data-grammarly-shadow-root="true" style="position: absolute; top: 0px; left: 0px; pointer-events: none; z-index: 1;" class="cGcvT"></grammarly-extension><grammarly-extension data-grammarly-shadow-root="true" style="mix-blend-mode: darken; position: absolute; top: 0px; left: 0px; pointer-events: none; z-index: 1;" class="cGcvT"></grammarly-extension>

                                                         <button id=""  type="submit" class="btn btn-send"><i class="f-16 text-primary ti ti-brand-telegram">
                                                         </i>
                                                         </button>
                                                      </form>
                                                </div>
                                               
                                                </div>

                                                 @endif

                                            @else

                                                   <div class="col-12 d-flex">
                                                <div class="form-group mb-0 form-send w-100">
                                                    <form method="post" class="card-comment-box" id="form-comment" action="{{route($client_keyword.'comment_store.store', [$currentWorkspace->slug,$contract->id])}}">
                                                        @csrf
                                                        <textarea rows="1" class="form-control" name="comment" placeholder="Add a comment..." ></textarea><grammarly-extension data-grammarly-shadow-root="true" style="position: absolute; top: 0px; left: 0px; pointer-events: none; z-index: 1;" class="cGcvT"></grammarly-extension><grammarly-extension data-grammarly-shadow-root="true" style="mix-blend-mode: darken; position: absolute; top: 0px; left: 0px; pointer-events: none; z-index: 1;" class="cGcvT"></grammarly-extension>

                                                         <button id=""  type="submit" class="btn btn-send"><i class="f-16 text-primary ti ti-brand-telegram">
                                                    </i>
                                                </button>
                                                    </form>
                                                </div>
                                               
                                            </div>

                                            @endif     
                                        
                                     
                                                <div class="">

                                                    <div class="list-group list-group-flush mb-0" id="comments">
                                                        @foreach($contract->comment as $comment)
                                                        <div class="list-group-item ">
                                                            <div class="row align-items-center">
                                                                <div class="col-auto">
                                                                    <a href="#" class="img-fluid rounded-circle card-avatar">
                                                       
                                                                  
                                                                  <img class="avatar-sm rounded-circle img-thumbnail" width="" style="max-width: 30px; max-height: 30px;"
                                                                  @if($comment->user_id != '' && $comment->user_id != null)

                                                                  @if($comment->user->avatar) src="{{asset('/storage/avatars/'.$comment->user->avatar)}}"
                                                                   @else 
                                                                   avatar="{{ $comment->user->name }}"
                                                                    @endif
                                                                    alt="{{ $comment->user->name }}"

                                                                  @else 


                                                                  @if($comment->client->avatar) src="{{asset('/storage/avatars/'.$comment->client->avatar)}}"
                                                                   @else 
                                                                   avatar="{{ $comment->client->name }}"
                                                                    @endif




                                                                   alt="{{ $comment->client->name }}" @endif />
                                                                    </a>



                                                                </div>
                                                                <div class="col ml-n2">
                                                                    <p class="d-block h6 text-sm font-weight-light mb-0 text-break">{{ $comment->comment }}</p>
                                                                    <small class="d-block">{{$comment->created_at->diffForHumans()}}</small>
                                                                </div>
                                                               
                                                                @if(\Auth::guard('client')->check()) 

                                                                     @if($contract->status == 'on' && \Auth::user()->id == $comment->client_id)
                                                                      <div class="col-auto">
                                                                                <a href="{{route($client_keyword.'comment_store.destroy',[$currentWorkspace->slug,$comment->id])}}" class="action-btn btn-danger mx-1 mt-1 btn btn-sm d-inline-flex align-items-center" title="{{__('Delete')}}"><span class="text-white"><i class="ti ti-trash"></i></span></a>
                                                                            </div>
                                                                      @endif

                                                                @else

                                                                       <div class="col-auto">
                                                                            <a href="{{route($client_keyword.'comment_store.destroy',[$currentWorkspace->slug,$comment->id])}}" class="action-btn btn-danger mx-1 mt-1 btn btn-sm d-inline-flex align-items-center" title="{{__('Delete')}}"><span class="text-white"><i class="ti ti-trash"></i></span></a>
                                                                        </div>

                                                                 @endif   
                                                             
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                           </div>
                                      </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div id="notes">
                        <div class="row pt-2">
                            <div class="col-12">
                                <div id="">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{__('Notes')}}</h5>
                                        </div>



                                        <div class="card-body">

                                        @if(\Auth::guard('client')->check()) 
                                                 @if($contract->status == 'on')
                                                    <div class="col-12 d-flex">
                                                        <div class="form-group mb-0 form-send w-100">
                                                            <form method="post" class="card-note-box" id="form-note" action="{{route($client_keyword.'note_store.store', [$currentWorkspace->slug,$contract->id])}}">
                                                                @csrf

                                                                <textarea rows="1" class="form-control" name="notes" data-toggle="autosize" placeholder="Add a note..." spellcheck="false"></textarea><grammarly-extension data-grammarly-shadow-root="true" style="position: absolute; top: 0px; left: 0px; pointer-events: none; z-index: 1;" class="cGcvT"></grammarly-extension><grammarly-extension data-grammarly-shadow-root="true" style="mix-blend-mode: darken; position: absolute; top: 0px; left: 0px; pointer-events: none; z-index: 1;" class="cGcvT"></grammarly-extension>

                                                                  <button id="" type="submit" class="btn btn-send"><i class="f-16 text-primary ti ti-brand-telegram">
                                                                    </i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                   @endif 
                                        @else

                                            <div class="col-12 d-flex">
                                                        <div class="form-group mb-0 form-send w-100">
                                                            <form method="post" class="card-note-box" id="form-note" action="{{route($client_keyword.'note_store.store', [$currentWorkspace->slug,$contract->id])}}">
                                                                @csrf

                                                                <textarea rows="1" class="form-control" name="notes" data-toggle="autosize" placeholder="Add a note..." spellcheck="false"></textarea><grammarly-extension data-grammarly-shadow-root="true" style="position: absolute; top: 0px; left: 0px; pointer-events: none; z-index: 1;" class="cGcvT"></grammarly-extension><grammarly-extension data-grammarly-shadow-root="true" style="mix-blend-mode: darken; position: absolute; top: 0px; left: 0px; pointer-events: none; z-index: 1;" class="cGcvT"></grammarly-extension>

                                                                  <button id="" type="submit" class="btn btn-send"><i class="f-16 text-primary ti ti-brand-telegram">
                                                                    </i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>

                                        @endif




                                            <div class="list-group list-group-flush mb-0" id="comments">
                                                @foreach($contract->note as $note)
    
                                                    <div class="list-group-item ">
                                                        <div class="row align-items-center">
                                                            <div class="col-auto">
                                                                <a href="#" class="img-fluid rounded-circle card-avatar">
                                                                <img class="avatar-sm rounded-circle img-thumbnail" width="" style="max-width: 30px; max-height: 30px;"
                                                                   @if($note->user_id != '' && $note->user_id != null)

                                                                  @if($note->user->avatar) src="{{asset('/storage/avatars/'.$note->user->avatar)}}"
                                                                   @else 
                                                                   avatar="{{ $note->user->name }}"
                                                                    @endif
                                                                    alt="{{ $note->user->name }}"
                                                               @else 


                                                                   @if($note->client->avatar) src="{{asset('/storage/avatars/'.$note->client->avatar)}}"
                                                                   @else 
                                                                   avatar="{{ $note->client->name }}"
                                                                    @endif
                                                                   alt="{{ $note->client->name }}" 

                                                                @endif />
                                                                </a>
                                                            </div>
                                                            <div class="col ml-n2">
                                                                <p class="d-block h6 text-sm font-weight-light mb-0 text-break">{{ $note->notes }}</p>
                                                                <small class="d-block">{{$note->created_at->diffForHumans()}}</small>
                                                            </div>
                                                           
                                                            



                                                            @if(\Auth::guard('client')->check()) 

                                                                     @if($contract->status == 'on' && \Auth::user()->id == $note->client_id)
                                                                     <div class="col-auto">
                                                                        <a href="{{route($client_keyword.'note_store.destroy',[$currentWorkspace->slug,$note->id])}}" class="action-btn btn-danger mx-1 mt-1 btn btn-sm d-inline-flex align-items-center" title="{{__('Delete')}}"><span class="text-white"><i class="ti ti-trash"></i></span></a>
                                                                    </div>
                                                                      @endif

                                                            @else

                                                                       <div class="col-auto">
                                                                            <a href="{{route($client_keyword.'note_store.destroy',[$currentWorkspace->slug,$note->id])}}" class="action-btn btn-danger mx-1 mt-1 btn btn-sm d-inline-flex align-items-center" title="{{__('Delete')}}"><span class="text-white"><i class="ti ti-trash"></i></span></a>
                                                                        </div>

                                                             @endif   




                                                         
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')


 <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300
        })
    </script>

    
<script src="{{asset('custom/js/dropzone.min.js')}}"></script>
  <script src="{{ asset('assets/js/plugins/tinymce/tinymce.min.js') }}"></script>
    <script>
        if ($(".pc-tinymce-2").length) {
            tinymce.init({
                selector: '.pc-tinymce-2',
                height: "400",
                content_style: 'body { font-family: "Inter", sans-serif; }'
            });
        }
    </script>
    <script>

  Dropzone.autoDiscover = true;
        myDropzone = new Dropzone("#dropzonewidget", {
            maxFiles: 20,
            maxFilesize: 209715200,
            parallelUploads: 1,
            acceptedFiles: ".jpeg,.jpg,.png,.pdf,.doc,.txt",
            url: "{{route($client_keyword.'contracts.file.upload',[$currentWorkspace->slug, $contract->id])}}",
            success: function (file, response) {
                location.reload();
                if (response.is_success) {
                    dropzoneBtn(file, response);
                } else {
                    myDropzone.removeFile(file);
                    show_toastr('{{__("Error")}}', response.error, 'error');
                }
            },
            error: function (file, response) {
                myDropzone.removeFile(file);
                if (response.error) {
                    show_toastr('{{__("Error")}}', response.error, 'error');
                } else {
                    show_toastr('{{__("Error")}}', response.error, 'error');
                }
            }
        });
        myDropzone.on("sending", function (file, xhr, formData) {
            formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
            formData.append("contract_id", {{$contract->id}});
        });

        function dropzoneBtn(file, response) {
            var download = document.createElement('a');
            download.setAttribute('href', response.download);
            download.setAttribute('class', "action-btn btn-primary mx-1 mt-1 btn btn-sm d-inline-flex align-items-center");
            download.setAttribute('data-toggle', "tooltip");
            download.setAttribute('data-original-title', "{{__('Download')}}");
            download.innerHTML = "<i class='fas fa-download'></i>";

            var del = document.createElement('a');
            del.setAttribute('href', response.delete);
            del.setAttribute('class', "action-btn btn-danger mx-1 mt-1 btn btn-sm d-inline-flex align-items-center");
            del.setAttribute('data-toggle', "tooltip");
            del.setAttribute('data-original-title', "{{__('Delete')}}");
            del.innerHTML = "<i class='ti ti-trash'></i>";

            del.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                if (confirm("Are you sure ?")) {
                    var btn = $(this);
                    $.ajax({
                        url: btn.attr('href'),
                        data: {_token: $('meta[name="csrf-token"]').attr('content')},
                        type: 'DELETE',
                        success: function (response) {
                            if (response.is_success) {
                                btn.closest('.dz-image-preview').remove();
                            } else {
                                show_toastr('{{__("Error")}}', response.error, 'error');
                            }
                        },
                        error: function (response) {
                            response = response.responseJSON;
                            if (response.is_success) {
                                show_toastr('{{__("Error")}}', response.error, 'error');
                            } else {
                                show_toastr('{{__("Error")}}', response.error, 'error');
                            }
                        }
                    })
                }
            });

            var html = document.createElement('div');
            html.setAttribute('class', "text-center mt-10");
            html.appendChild(download);
            html.appendChild(del);

            file.previewTemplate.appendChild(html);
        }
    </script> 


    <script>
        $(document).on('click', '#comment_submit', function (e) {
                    var curr = $(this);

                    var comment = $.trim($("#form-comment textarea[name='comment']").val());

                    if (comment != '') {
                        $.ajax({
                            url: $("#form-comment").data('action'),
                            data: {comment: comment, "_token": "{{ csrf_token() }}",},
                            type: 'POST',
                            success: function (data) {
                                location.reload();
                                data = JSON.parse(data);
                               
                                var html = "<div class='list-group-item px-0'>" +
                                    "                    <div class='row align-items-center'>" +
                                    "                        <div class='col-auto'>" +
                                    "                            <a href='#' class='avatar avatar-sm rounded-circle ms-2'>" +
                                    "                                <img src="+data.default_img+" alt='' class='avatar-sm rounded-circle'>" +
                                    "                            </a>" +
                                    "                        </div>" +
                                    "                        <div class='col ml-n2'>" +
                                    "                            <p class='d-block h6 text-sm font-weight-light mb-0 text-break'>" + data.comment + "</p>" +
                                    "                            <small class='d-block'>"+data.current_time+"</small>" +
                                    "                        </div>" +
                                    "                        <div class='action-btn bg-danger me-4'><div class='col-auto'><a href='#' class='mx-3 btn btn-sm  align-items-center delete-comment' data-url='" + data.deleteUrl + "'><i class='ti ti-trash text-white'></i></a></div></div>" +
                                    "                    </div>" +
                                    "                </div>";

                                $("#comments").prepend(html);
                                $("#form-comment textarea[name='comment']").val('');
                                load_task(curr.closest('.task-id').attr('id'));
                                show_toastr('success', 'Comment Added Successfully!');
                            },
                            error: function (data) {
                                show_toastr('error', 'Some Thing Is Wrong!');
                            }
                        });
                    } else {
                        show_toastr('error', 'Please write comment!');
                    }
                });
                $(document).on("click", ".delete-comment", function () {
                    var btn = $(this);

                    $.ajax({
                        url: $(this).attr('data-url'),
                        type: 'DELETE',
                        dataType: 'JSON',
                        data: {comment: comment, "_token": "{{ csrf_token() }}",},
                        success: function (data) {
                            load_task(btn.closest('.task-id').attr('id'));
                            show_toastr('success', 'Comment Deleted Successfully!');
                            btn.closest('.list-group-item').remove();
                        },
                        error: function (data) {
                            data = data.responseJSON;
                            if (data.message) {
                                show_toastr('error', data.message);
                            } else {
                                show_toastr('error', 'Some Thing Is Wrong!');
                            }
                        }
                    });
                });
    </script>


    <script>
        $(document).on('click', '#note_submit', function (e) {
                    var curr = $(this);

                    var note = $.trim($("#form-note textarea[name='notes']").val());

                    if (note != '') {
                        $.ajax({
                            url: $("#form-note").data('action'),
                            data: {note: note, "_token": "{{ csrf_token() }}",},
                            type: 'POST',
                            success: function (data) {
                                location.reload();
                                data = JSON.parse(data);
                                console.log(data);
                                var html = "<div class='list-group-item px-0'>" +
                                    "                    <div class='row align-items-center'>" +
                                    "                        <div class='col-auto'>" +
                                    "                            <a href='#' class='avatar avatar-sm rounded-circle ms-2'>" +
                                    "                                <img src="+data.default_img+" alt='' class='avatar-sm rounded-circle'>" +
                                    "                            </a>" +
                                    "                        </div>" +
                                    "                        <div class='col ml-n2'>" +
                                    "                            <p class='d-block h6 text-sm font-weight-light mb-0 text-break'>" + data.note + "</p>" +
                                    "                            <small class='d-block'>"+data.current_time+"</small>" +
                                    "                        </div>" +
                                    "                        <div class='action-btn bg-danger me-4'><div class='col-auto'><a href='#' class='mx-3 btn btn-sm  align-items-center delete-note' data-url='" + data.deleteUrl + "'><i class='ti ti-trash text-white'></i></a></div></div>" +
                                    "                    </div>" +
                                    "                </div>";

                                $("#comments").prepend(html);
                                $("#form-note textarea[name='notes']").val('');
                                load_task(curr.closest('.task-id').attr('id'));
                                show_toastr('success', 'note Added Successfully!');
                            },
                            error: function (data) {
                                show_toastr('error', 'Some Thing Is Wrong!');
                            }
                        });
                    } else {
                        show_toastr('error', 'Please write Note!');
                    }
                });
                $(document).on("click", ".delete-note", function () {
                    var btn = $(this);

                    $.ajax({
                        url: $(this).attr('data-url'),
                        type: 'DELETE',
                        dataType: 'JSON',
                        data: {note: note, "_token": "{{ csrf_token() }}",},
                        success: function (data) {
                            load_task(btn.closest('.task-id').attr('id'));
                            show_toastr('success', 'note Deleted Successfully!');
                            btn.closest('.list-group-item').remove();
                        },
                        error: function (data) {
                            data = data.responseJSON;
                            if (data.message) {
                                show_toastr('error', data.message);
                            } else {
                                show_toastr('error', 'Some Thing Is Wrong!');
                            }
                        }
                    });
                });
    </script>

@endpush

    