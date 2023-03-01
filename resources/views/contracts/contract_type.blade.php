@extends('layouts.admin')

@section('page-title') {{__('Contract Type')}} @endsection
@section('links')
@if(\Auth::guard('client')->check())   
<li class="breadcrumb-item"><a href="{{route('client.home')}}">{{__('Home')}}</a></li>
 @else
 <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('Home')}}</a></li>
 @endif
<li class="breadcrumb-item"> {{ __('Contract Type') }}</li>
 @endsection


@section('action-button')
    @auth('web')
        @if($currentWorkspace->creater->id == Auth::user()->id)
             <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Create Contract Type') }}" data-toggle="tooltip" title="{{__('Create Contract Type')}}" data-url="{{route('contract_type.create',$currentWorkspace->slug)}}">
                    <i class="ti ti-plus"></i>
             </a>
           
        @endif
    @endauth
@endsection

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-centered table-hover mb-0 animated" id="selection-datatable">
                                <thead>
                                
                                <th data-sortable="" style="width: 82.97%;">{{__('Contract Type')}}</th>
                                @auth('web')
                                <th width="250px" data-sortable="" style="width: 17.03%;">{{__('Action')}}</th>
                                @endauth
                                </thead>
                                <tbody>
                                  @foreach($contractTypes as $contractType)
                                    <tr>
                                      
                                         <td>
                                           {{$contractType->name}}
                                        </td>
                                 
                                        @auth('web')
                                            <td class="text-right">
                                                <a href="#" class="action-btn btn-info  btn btn-sm d-inline-flex align-items-center" data-url="{{route('contract_type.edit',[$currentWorkspace->slug,$contractType->id])}}" data-size="md" data-toggle="tooltip" title="{{ __('Edit Contract Type') }}"  data-ajax-popup="true" data-title="{{__('Edit Contract Type')}}">
                                                    <i class="ti ti-pencil"></i>
                                                </a>
                                                <a href="#" class="action-btn btn-danger  btn btn-sm d-inline-flex align-items-center bs-pass-para" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{ $contractType->id }}" data-toggle="tooltip" title="{{ __('Delete Contract Type') }}">
                                                    <i class="ti ti-trash"></i>
                                                </a>

                                                {!! Form::open(['method' => 'DELETE', 'route' => ['contract_type.destroy',[$currentWorkspace->slug,$contractType->id]],'id'=>'delete-form-'.$contractType->id]) !!}
                                                {!! Form::close() !!}
                                            </td>
                                        @endauth
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

@endsection
