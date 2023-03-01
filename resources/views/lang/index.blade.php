@extends('layouts.admin')

@section('page-title')
    {{__('Manage Languages')}}
@endsection

@section('links')
@if(\Auth::guard('client')->check())   
<li class="breadcrumb-item"><a href="{{route('client.home')}}">{{__('Home')}}</a></li>
 @else
 <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('Home')}}</a></li>
 @endif
<li class="breadcrumb-item"> {{ __('Manage Languages') }}</li>
@endsection



@section('action-button')
    
    @if($currantLang != (env('DEFAULT_LANG') ?? 'en'))
        <div class="col-auto">
                <a href="#" class="btn btn-sm btn-danger bs-pass-para" data-toggle="tooltip" title="{{__('Delete This Language')}}" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="delete-form-{{$currantLang}}"><i class="ti ti-trash text-white"></i> </a>

                {!! Form::open(['method' => 'DELETE', 'route' => ['lang.destroy', $currantLang],'id'=>'delete-form-'.$currantLang]) !!}
                {!! Form::close() !!}
        </div> 
    @endif

    <div class="col-auto mx-1">
            <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Create Language') }}" data-toggle="tooltip" title="{{__('Create Language')}}"  data-url="{{route('create_lang_workspace')}}">
                <i class="ti ti-plus"></i> 
            </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body p-3">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        @foreach($workspace->languages() as $lang)
                            <a href="{{route('lang_workspace',$lang)}}" class="nav-link text-sm font-weight-bold @if($currantLang == $lang) active @endif">
                                <i class="d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">{{Str::upper($lang)}}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
                    <div class="p-3 card">
            <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-user-tab-1" data-bs-toggle="pill"
                        data-bs-target="#labels" type="button">{{ __('Labels')}}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-user-tab-2" data-bs-toggle="pill"
                        data-bs-target="#messages" type="button">{{ __('Messages')}}</button>
                </li>

            </ul>
        </div>
            <div class="card">
                <div class="card-body p-3">
              <!--       <ul class="nav nav-tabs  bordar_styless my-4">
                        <li>
                            <a data-toggle="tab" href="#labels" class="active">{{__('Labels')}}</a>
                        </li>
                        <li class="annual-billing">
                            <a data-toggle="tab" href="#messages" class="">{{__('Messages')}} </a>
                        </li>
                    </ul>
 -->
                    <form method="post" action="{{route('store_lang_data_workspace',$currantLang)}}">
                        @csrf
                        <div class="tab-content">
                            <div class="tab-pane active" id="labels">
                                <div class="row">
                                    @foreach($arrLabel as $label => $value)
                                        <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label text-dark">{{$label}}</label>
                                                <input type="text" class="form-control" name="label[{{$label}}]" value="{{$value}}">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="tab-pane" id="messages">
                                @foreach($arrMessage as $fileName => $fileValue)
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h6>{{ucfirst($fileName)}}</h6>
                                        </div>
                                        @foreach($fileValue as $label => $value)
                                            @if(is_array($value))
                                                @foreach($value as $label2 => $value2)
                                                    @if(is_array($value2))
                                                        @foreach($value2 as $label3 => $value3)
                                                            @if(is_array($value3))
                                                                @foreach($value3 as $label4 => $value4)
                                                                    @if(is_array($value4))
                                                                        @foreach($value4 as $label5 => $value5)
                                                                            <div class="col-lg-6">
                                                                                <div class="form-group mb-3">
                                                                                    <label class="form-label text-dark">{{$fileName}}.{{$label}}.{{$label2}}.{{$label3}}.{{$label4}}.{{$label5}}</label>
                                                                                    <input type="text" class="form-control" name="message[{{$fileName}}][{{$label}}][{{$label2}}][{{$label3}}][{{$label4}}][{{$label5}}]" value="{{$value5}}">
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    @else
                                                                        <div class="col-lg-6">
                                                                            <div class="form-group mb-3">
                                                                                <label class="form-label text-dark">{{$fileName}}.{{$label}}.{{$label2}}.{{$label3}}.{{$label4}}</label>
                                                                                <input type="text" class="form-control" name="message[{{$fileName}}][{{$label}}][{{$label2}}][{{$label3}}][{{$label4}}]" value="{{$value4}}">
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                <div class="col-lg-6">
                                                                    <div class="form-group mb-3">
                                                                        <label class="form-label text-dark">{{$fileName}}.{{$label}}.{{$label2}}.{{$label3}}</label>
                                                                        <input type="text" class="form-control" name="message[{{$fileName}}][{{$label}}][{{$label2}}][{{$label3}}]" value="{{$value3}}">
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <div class="col-lg-6">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label text-dark">{{$fileName}}.{{$label}}.{{$label2}}</label>
                                                                <input type="text" class="form-control" name="message[{{$fileName}}][{{$label}}][{{$label2}}]" value="{{$value2}}">
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @else
                                                <div class="col-lg-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label text-dark">{{$fileName}}.{{$label}}</label>
                                                        <input type="text" class="form-control" name="message[{{$fileName}}][{{$label}}]" value="{{$value}}">
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="text-end">
                            <input type="submit" value="{{__('Save Changes')}}" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
