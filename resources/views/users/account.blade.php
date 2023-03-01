@extends('layouts.admin')

@section('page-title') {{__('User Profile')}} @endsection
@section('links')
@if(\Auth::guard('client')->check())
<li class="breadcrumb-item"><a href="{{route('client.home')}}">{{__('Home')}}</a></li>
 @else
 <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('Home')}}</a></li>
 @endif
<li class="breadcrumb-item"> {{ __('User Profile') }}</li>
@endsection
@section('content')
  <div class="row">
                    <div class="col-xl-3">
                        <div class="card sticky-top" style="top:30px">
                            <div class="list-group list-group-flush" id="useradd-sidenav">
                                <a href="#v-pills-home" class="list-group-item list-group-item-action">{{__('Account')}} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                                <a href="#v-pills-profile" class="list-group-item list-group-item-action">{{__('Change Password')}} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                                @auth('client')
                                <a href="#v-pills-billing" class="list-group-item list-group-item-action">{{__('Billing Details')}} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endauth

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9">
                        <div id="v-pills-home" class="card ">
                              <div class="card-header">
                                <h5>{{__('Avatar')}}</h5>
                            </div>
                            <div class="card-body">
                          <form method="post" action="@auth('web'){{route('update.account')}}@elseauth{{route('client.update.account')}}@endauth" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-group">

                                                        <img @if($user->avatar) src="{{asset('avatars/'.$user->avatar)}}" @else avatar="{{ $user->name }}" @endif id="myAvatar" alt="user-image" class="rounded-circle img-thumbnail img_hight w-25">
                                                        @if($user->avatar!='')
                                                       <div class=" ">
                                                            <a href="#" class=" action-btn btn-danger  btn btn-sm  mb-1 d-inline-flex align-items-center bs-pass-para" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="delete_avatar"><i class="ti ti-trash text-white"></i></a>

                                                            </div>
                                                        @endif
                                                        <div class="choose-file ">
                                                              <label for="avatar">
                                                                <div class=" bg-primary"> <i class="ti ti-upload px-1"></i>{{__('Choose file here')}}</div>
                                                                <input type="file" class="form-control" name="avatar" id="avatar" data-filename="avatar-logo">
                                                            </label>
                                                            <p class="avatar-logo"></p>
                                                            @error('avatar')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <small class="">{{ __('Please upload a valid image file. Size of image should not be more than 2MB.') }}</small>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="name" class="form-label">{{ __('Full Name') }}</label>
                                                        <input class="form-control @error('name') is-invalid @enderror" name="name" type="text" id="fullname" placeholder="{{ __('Enter Your Name') }}" value="{{ $user->name }}" required autocomplete="name">
                                                        @error('name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="email" class="form-label">{{ __('Email') }}</label>
                                                        <input readonly class="form-control @error('email') is-invalid @enderror" name="email" type="text" id="email" placeholder="{{ __('Enter Your Email Address') }}" value="{{ $user->email }}" required autocomplete="email">
                                                        @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-4">
                                                <div class=" row">
                                                  <div class="text-end">
                                                       <button type="submit" class="btn-submit btn btn-primary">
                                                            {{ __('Save Changes')}}
                                                        </button>
                                               <!--   <button class="btn btn-danger">Delete Account<i
                                                class="ti ti-chevron-right ms-1 ms-sm-2"></i></button> -->
                                                </div>

                                                    </div>
                                                </div> <!-- end col -->
                                            </div> <!-- end row -->
                                        </form>
                                         @if($user->avatar!='')
                                            <form action="@auth('web'){{route('delete.avatar')}}@elseauth{{route('client.delete.avatar')}}@endauth" method="post" id="delete_avatar">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endif
                                        @auth('web')
                                        <div class="text-end">
                                            <a href="#" class="btn btn-danger delete_btn bs-pass-para mx-5" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="delete-my-account">
                                                {{ __('Delete')}} {{__('My Account')}}<!-- <i
                                                class="ti ti-chevron-right ms-1 ms-sm-2"></i> -->
                                            </a>

                                            <form action="{{route('delete.my.account')}}" method="post" id="delete-my-account">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                        @endauth
                                    </div>





                                    <div class="card" id="v-pills-profile">
                                          <div class="card-header">
                                            <h5>{{__('Change Password')}}</h5>
                                            </div>
                                     <div class="card-body">
                                        <form method="post" action="@auth('web'){{route('update.password')}}@elseauth{{route('client.update.password')}}@endauth">
                                            @csrf

                                                <div class="col-lg-12">
                                                      <div class="row">
                                                    <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="old_password" class="form-label">{{ __('Old Password') }}</label>
                                                        <input class="form-control @error('old_password') is-invalid @enderror" name="old_password" type="password" id="old_password"  autocomplete="old_password" placeholder="{{ __('Enter Old Password') }}">
                                                        @error('old_password')
                                                        <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                        @enderror
                                                    </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="password" class="form-label">{{ __('New Password') }}</label>
                                                        <input class="form-control @error('password') is-invalid @enderror" name="password" type="password" required autocomplete="new-password" id="password" placeholder="{{ __('Enter Your Password') }}">
                                                        @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="password_confirmation" class="form-label">{{ __('Confirm New Password') }}</label>
                                                        <input class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" type="password" required autocomplete="new-password" id="password_confirmation" placeholder="{{ __('Enter Your Password') }}">
                                                    </div>
                                                </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="text-end">
                                                    <button type="submit" class="btn-submit btn btn-primary "> {{ __('Change Password') }} </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    </div>


                                                 @auth('client')
                                        <div class="card" id="v-pills-billing">

                                            <div class="card-header">
                                            <h5>{{__('Billing Details')}}</h5>
                                            </div>
                                          <div class="card-body">
                                            <form method="post" action="{{route('client.update.billing')}}">
                                                @csrf
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label for="address" class="form-label">{{__('Address')}}</label>
                                                        <input class="form-control font-style" name="address" type="text" value="{{ $user->address }}" id="address">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="city" class="form-label">{{__('City')}}</label>
                                                        <input class="form-control font-style" name="city" type="text" value="{{ $user->city }}" id="city">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="state" class="form-label">{{__('State')}}</label>
                                                        <input class="form-control font-style" name="state" type="text" value="{{ $user->state }}" id="state">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="zipcode" class="form-label">{{__('Zip/Post Code')}}</label>
                                                        <input class="form-control" name="zipcode" type="text" value="{{ $user->zipcode }}" id="zipcode">
                                                    </div>
                                                    <div class="form-group  col-md-6">
                                                        <label for="country" class="form-label">{{__('Country')}}</label>
                                                        <input class="form-control font-style" name="country" type="text" value="{{ $user->country }}" id="country">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="telephone" class="form-label">{{__('Telephone')}}</label>
                                                        <input class="form-control" name="telephone" type="text" value="{{ $user->telephone }}" id="telephone">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="text-end">
                                                        <button type="submit" class="btn-submit btn btn-primary">
                                                            {{ __('Save Changes')}}
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        </div>
                                    @endauth
                               </div>
                          </div>
@endsection
@push('scripts')


                <script type="text/javascript">
                    $('#avatar').change(function(){

                    let reader = new FileReader();
                    reader.onload = (e) => {
                      $('#myAvatar').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(this.files[0]);

                   });
                   </script>
 <script>
              $(document).on('click', '.list-group-item', function() {
                $('.list-group-item').removeClass('active');
                $('.list-group-item').removeClass('text-primary');
                setTimeout(() => {
                    $(this).addClass('active').removeClass('text-primary');
                }, 10);
            });

                   var type = window.location.hash.substr(1);
            $('.list-group-item').removeClass('active');
            $('.list-group-item').removeClass('text-primary');
            if (type != '') {
                $('a[href="#' + type + '"]').addClass('active').removeClass('text-primary');
            } else {
                $('.list-group-item:eq(0)').addClass('active').removeClass('text-primary');
            }




       var scrollSpy = new bootstrap.ScrollSpy(document.body, {
        target: '#useradd-sidenav',
        offset: 300
    })


</script>
@endpush
