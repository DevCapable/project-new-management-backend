@extends('layouts.admin')
@section('page-title')
 {{__('Email Templates')}}
@endsection


@section('action-button')
<div class="row">
    <div class="col-lg-6">
    </div>
    <div class="col-lg-6">
        <div class="text-end">
                       <div class="d-flex justify-content-end drp-languages">
                                    <ul class="list-unstyled mb-0 m-2">
                                        <li class="dropdown dash-h-item drp-language">
                                            <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown"
                                               href="#" role="button" aria-haspopup="false" aria-expanded="false"
                                               id="dropdownLanguage">
                                                <span
                                                    class="drp-text hide-mob text-primary">{{ __('Locale: ') }}{{ Str::upper($currEmailTempLang->lang) }}</span>
                                                <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                                            </a>
                                            <div class="dropdown-menu dash-h-dropdown dropdown-menu-end"
                                                 aria-labelledby="dropdownLanguage">
                                                @foreach ($languages as $lang)
                                                    <a href="{{ route('manage.email.language', [$emailTemplate->id, $lang]) }}"
                                                       class="dropdown-item {{ $currEmailTempLang->lang == $lang ? 'text-primary' : '' }}">{{ Str::upper($lang) }}</a>
                                                @endforeach
                                            </div>
                                        </li>
                                    </ul>
                                    <ul class="list-unstyled mb-0 m-2">
                                        <li class="dropdown dash-h-item drp-language">
                                            <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown"
                                               href="#" role="button" aria-haspopup="false" aria-expanded="false"
                                               id="dropdownLanguage">
                                                <span
                                                    class="drp-text hide-mob text-primary">{{ __('Template: ') }}{{ $emailTemplate->name }}</span>
                                                <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                                            </a>
                                            <div class="dropdown-menu dash-h-dropdown dropdown-menu-end" aria-labelledby="dropdownLanguage">
                                                @foreach ($EmailTemplates as $EmailTemplate)
                                                    <a href="{{ route('manage.email.language', [$EmailTemplate->id,(Request::segment(3)?Request::segment(3):\Auth::user()->lang)]) }}"
                                                       class="dropdown-item {{$emailTemplate->name == $EmailTemplate->name ? 'text-primary' : '' }}">{{ $EmailTemplate->name }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </li>
                                    </ul>
                                </div>
        </div>
    </div>
</div>
@endsection

@section('links')
@if(\Auth::guard('client')->check())   
<li class="breadcrumb-item"><a href="{{route('client.home')}}">{{__('Home')}}</a></li>
 @else
 <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('Home')}}</a></li>
 @endif
<li class="breadcrumb-item"> <a href="{{route('email_template.index')}}">{{ __('Email Templates') }}</a></li>
<li class="breadcrumb-item">{{ $emailTemplate->name }}</li>
 @endsection

@push('css-page')
    <link rel="stylesheet" href="{{asset('custom/libs/summernote/summernote-bs4.css')}}">
@endpush
@push('scripts')
<script src="{{asset('custom/libs/summernote/summernote-bs4.js')}}"></script>
<script>
 if ($(".summernote-simple").length) {
        $('.summernote-simple').summernote({
            dialogsInBody: !0,
            minHeight: 200,
            toolbar: [
                ['style', ['style']],
                ["font", ["bold", "italic", "underline", "clear", "strikethrough"]],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ["para", ["ul", "ol", "paragraph"]],
            ]
        });
    }
</script>
@endpush
 @section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body ">
                   
                    <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                      
                   <h5>{{ __('Placeholders') }}</h5>
                      

                        <div class="card">
                                <div class="card-body">
                      <div class="row text-xs">
                                              
                                          @if($emailTemplate->name=='New Client')
                                          <div class="row">
                                                <p class="col-4">{{__('App Name')}} : <span class="pull-end text-primary">{app_name}</span></p>
                                                <p class="col-4">{{__('User Name')}} : <span class="pull-right text-primary">{user_name}</span></p>
                                                <p class="col-4">{{__('App Url')}} : <span class="pull-right text-primary">{app_url}</span></p>
                                                <p class="col-4">{{__('Email')}} : <span class="pull-right text-primary">{email}</span></p>
                                                <p class="col-4">{{__('Password')}} : <span class="pull-right text-primary">{password}</span></p>
                                            </div>

                                           @elseif($emailTemplate->name=='Invite User')  

                                              <div class="row">
                                                <p class="col-4">{{__('App Name')}} : <span class="pull-end text-primary">{app_name}</span></p>
                                                <p class="col-4">{{__('User Name')}} : <span class="pull-right text-primary">{user_name}</span></p>
                                                <p class="col-4">{{__('App Url')}} : <span class="pull-right text-primary">{app_url}</span></p>
                                                <p class="col-4">{{__('Workspace Name')}} : <span class="pull-right text-primary">{workspace_name}</span></p>
                                                <p class="col-4">{{__('Owner Name')}} : <span class="pull-right text-primary">{owner_name}</span></p>
                                            </div>


                                            @elseif($emailTemplate->name=='Assign Project')  

                                              <div class="row">
                                                <p class="col-4">{{__('App Name')}} : <span class="pull-end text-primary">{app_name}</span></p>
                                                <p class="col-4">{{__('User Name')}} : <span class="pull-right text-primary">{user_name}</span></p>
                                                <p class="col-4">{{__('App Url')}} : <span class="pull-right text-primary">{app_url}</span></p>
                                                <p class="col-4">{{__('Project Name')}} : <span class="pull-right text-primary">{project_name}</span></p>
                                                <p class="col-4">{{__('Project Status')}} : <span class="pull-right text-primary">{project_status}</span></p>
                                                <p class="col-4">{{__('Owner Name')}} : <span class="pull-right text-primary">{owner_name}</span></p>
                                            </div>


                                            @elseif($emailTemplate->name=='Contract Share')  

                                            <div class="row">
                                                <p class="col-4">{{__('Client Name')}} : <span class="pull-end text-primary">{client_name}</span></p>
                                                <p class="col-4">{{__('Contract Subject')}} : <span class="pull-right text-primary">{contract_subject}</span></p>
                                                <p class="col-4">{{__('Contract Type')}} : <span class="pull-right text-primary">{contract_type}</span></p>
                                                <p class="col-4">{{__('Contract value')}} : <span class="pull-right text-primary">{value}</span></p>
                                                <p class="col-4">{{__('Start Date')}} : <span class="pull-right text-primary">{start_date}</span></p>
                                                <p class="col-4">{{__('End Date')}} : <span class="pull-right text-primary">{end_date}</span></p>
                                            </div>
                                            @endif
                    
                    </div>
                </div>
                        </div>
                    </div>
                      {{Form::model($currEmailTempLang, array('route' => array('email_template.update', $currEmailTempLang->parent_id), 'method' => 'PUT')) }}

                      
                    </div>
               
                 <div class="row">
                        <div class="form-group col-6">
                            {{ Form::label('subject', __('Subject'), ['class' => 'col-form-label text-dark']) }}
                            {{ Form::text('subject', null, ['class' => 'form-control font-style', 'required' => 'required']) }}
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::label('from', __('From'), ['class' => 'col-form-label text-dark']) }}
                            {{ Form::text('from', $emailTemplate->from, ['class' => 'form-control font-style', 'required' => 'required']) }}
                        </div>
                        <div class="form-group col-12">
                                    {{Form::label('content',__('Email Message'),['class'=>'form-label text-dark'])}}
                                    {{Form::textarea('content',$currEmailTempLang->content,array('class'=>'summernote-simple','required'=>'required'))}}
                            </div>
                    </div>
                   
            
                    <div class="col-md-12 text-end">
                                        {{Form::hidden('lang',null)}}
                                        <input type="submit" value="{{__('Save Changes')}}" class="btn btn-print-invoice  btn-primary m-r-10">
                                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection