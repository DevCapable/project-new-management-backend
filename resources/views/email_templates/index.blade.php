@extends('layouts.admin')
@section('page-title')
  {{__('Email Templates')}}
@endsection

@section('links')
@if(\Auth::guard('client')->check())
<li class="breadcrumb-item"><a href="{{route('client.home')}}">{{__('Home')}}</a></li>
 @else
 <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('Home')}}</a></li>
 @endif
<li class="breadcrumb-item"> {{ __('Email Templates') }}</li>
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
                    {{Form::model($currEmailTempLang, array('route' => array('email_template.update', $currEmailTempLang->parent_id), 'method' => 'PUT')) }}
                    <div class="row">
                        <div class="col-lg-6">
                            <h3 class="m-2">{{ __($emailTemplate->name) }}</h3>
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
                 <hr>
                 <div class="row">
                        <div class="form-group col-12">
                            {{ Form::label('subject', __('Subject'), ['class' => 'col-form-label text-dark']) }}
                            {{ Form::text('subject', null, ['class' => 'form-control font-style', 'required' => 'required']) }}
                        </div>
                        <div class="form-group col-md-12">
                            {{ Form::label('from', __('From'), ['class' => 'col-form-label text-dark']) }}
                            {{ Form::text('from', $emailTemplate->from, ['class' => 'form-control font-style', 'required' => 'required']) }}
                        </div>
                        <div class="form-group col-12">
                                    {{Form::label('content',__('Email Message'),['class'=>'form-label text-dark'])}}
                                    {{Form::textarea('content',$currEmailTempLang->content,array('class'=>'summernote-simple','required'=>'required'))}}
                            </div>
                    </div>
                    <h3>{{ __('Placeholders') }}</h3>
                    <hr>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="card">
                        <div class="card-header card-body">
                    <h5></h5>
                      <div class="row text-xs">

                        <div class="col-4">
                            <h6 class="font-weight-bold">{{__('Workspace')}}</h6>
                            <p class="mb-1">{{__('Workspace Name')}} : <span class="pull-right text-primary">{workspace_name}</span></p>

                            <p class="mb-1">{{__('User Name')}} : <span class="pull-right text-primary">{user_name}</span></p>
                        </div>

                        <div class="col-4">
                            <h6 class="font-weight-bold">{{__('Project')}}</h6>
                            <p class="mb-1">{{__('Project Name')}} : <span class="pull-right text-primary">{project_name}</span></p>

                            <p class="mb-1">{{__('Project Status')}} : <span class="pull-right text-primary">{project_status}</span></p>
                        </div>

                        <div class="col-4">
                            <h6 class="font-weight-bold">{{__('Other')}}</h6>
                            <p class="mb-1">{{__('App Name')}} : <span class="pull-right text-primary">{app_name}</span></p>
                            <p class="mb-1">{{__('Company Name')}} : <span class="pull-right text-primary">{company_name}</span></p>
                            <p class="mb-1">{{__('App Url')}} : <span class="pull-right text-primary">{app_url}</span></p>
                            <p class="mb-1">{{__('Email')}} : <span class="pull-right text-primary">{email}</span></p>
                            <p class="mb-1">{{__('Password')}} : <span class="pull-right text-primary">{password}</span></p>
                        </div>
                    </div>
                </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-end">
                                        {{Form::hidden('lang',null)}}
                                        <input type="submit" value="{{__('Save')}}" class="btn btn-print-invoice  btn-primary m-r-10">
                                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
