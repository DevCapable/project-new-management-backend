@extends('layouts.admin')

@section('page-title') {{__('Clients')}} @endsection
@section('links')
@if(\Auth::guard('client')->check())
<li class="breadcrumb-item"><a href="{{route('client.home')}}">{{__('Home')}}</a></li>
 @else
 <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('Home')}}</a></li>
 @endif
<li class="breadcrumb-item"> {{ __('Clients') }}</li>
 @endsection
@section('action-button')
    @auth('web')
            <a href="{{route('client.export' )}}" class="btn btn-sm btn-primary" data-toggle="tooltip" title="{{ __('Export') }}" >
                <i class="ti ti-file-x"></i>
            </a>
            <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-size="md" data-title="{{ __('Import Client') }}" data-url="{{route('client.file.import',$currentWorkspace->slug)}}" data-toggle="tooltip" title="{{ __('Import ') }}" >
                <i class="ti ti-file-import"></i>
            </a>
             @if(isset($currentWorkspace) && $currentWorkspace->creater->id == Auth::id())
               <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Add Client') }}" data-url="{{route('clients.create',$currentWorkspace->slug)}}" data-toggle="tooltip" title="{{ __('Add ') }}" >
                <i class="ti ti-plus"></i>
            </a>
           @endif



    @endauth
@endsection

@section('content')
       @if($currentWorkspace)
                <div class="row">
                  @foreach ($clients as $client)
                    <div class="col-xl-3">

                        <div class="card   text-center">
                               <div class="card-header border-0 pb-0">
                                        <div class="card-header-right">
                                            <div class="btn-group card-option">
                                                <button type="button" class="btn dropdown-toggle"
                                                    data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="feather icon-more-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">

                                             @if($client->is_active)
                                            <a href="#" class="dropdown-item" data-ajax-popup="true" data-size="md" data-title="{{ __('Reset Password') }}" data-url="{{route('client.reset.password',[$currentWorkspace->slug,$client->id])}}"><i class="ti ti-pencil"></i> <span>{{ __('Reset Password') }}</span></a>

                                            <a href="#" class="dropdown-item" data-ajax-popup="true" data-size="md" data-title="{{__('Edit Client')}}" data-url="{{route('clients.edit',[$currentWorkspace->slug,$client->id])}}"><i class="ti ti-edit"></i>{{ __('Edit') }}</span></a>

                                            <a href="#" class="dropdown-item bs-pass-para text-danger"  data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="delete-form-{{$client->id}}" ><i class="ti ti-trash"></i> <span>{{ __('Delete') }}</span></a>

                                           {!! Form::open(['method' => 'DELETE', 'route' => ['clients.destroy',[$currentWorkspace->slug,$client->id]],'id'=>'delete-form-'.$client->id]) !!}
                                           {!! Form::close() !!}

                                            @else
                                                <a href="#" class="dropdown-item" title="{{__('Locked')}}">
                                                    <i class="fas fa-lock"></i>
                                                </a>
                                           @endif

                                                </div>
                                            </div>
                                        </div>

                               </div>
                            <div class="card-body">
                                <img alt="user-image"
                                    class="img-fluid rounded-circle"  @if($client->avatar) src="{{asset('/storage/avatars/'.$client->avatar)}}" @else avatar="{{ $client->name }}" @endif>
                                <h4 class="mt-2">{{ $client->name }}</h4>
                                <small>{{$client->email}}</small>
                            </div>
                        </div>

                    </div>
                   @endforeach



                                <div class="col-md-3">
                                 @auth('web')
                                     @if(isset($currentWorkspace) && $currentWorkspace->creater->id == Auth::id())

                                <a href="#" class="btn-addnew-project"  data-ajax-popup="true" data-size="md" data-title="{{ __('Add Client') }}" data-url="{{route('clients.create',$currentWorkspace->slug)}}">
                                    <div class="bg-primary proj-add-icon">
                                        <i class="ti ti-plus"></i>
                                    </div>
                                    <h6 class="mt-4 mb-2">New Client</h6>
                                    <p class="text-muted text-center">Click here to add New Client</p>
                                </a>

                               @endif
                            @endauth
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

                        </div>
                    </div>

                <!-- [ sample-page ] end -->
            </div>
            @endsection


 @push('scripts')
<!--   <script>

    $(".delete-popup").click(function(){

    var id = $(this).data('id');

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {

  var id = $(this).data('id');
          $('#delete-form-'+id).submit();

         }




     })
});

 </script> -->
 @endpush
