@extends('layouts.admin')
@php
    $client_keyword = Auth::user()->getGuard() == 'client' ? 'client.' : '';
@endphp
@section('page-title') {{__('Contracts')}} @endsection
@section('links')
@if(\Auth::guard('client')->check())   
<li class="breadcrumb-item"><a href="{{route('client.home')}}">{{__('Home')}}</a></li>
 @else
 <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('Home')}}</a></li>
 @endif
<li class="breadcrumb-item"> {{ __('Contracts') }}</li>
 @endsection


@section('action-button')
   @auth('web')
        @if($currentWorkspace->creater->id == Auth::user()->id)
             <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="lg" data-title="{{ __('Create Contract ') }}" data-toggle="tooltip" title="{{__('Create Contract')}}" data-url="{{route('contracts.create',$currentWorkspace->slug)}}">
                    <i class="ti ti-plus"></i>
             </a>
           
        @endif
    @endauth
@endsection


@section('content')
        <div class="row">
            <div class="col-xl-3 col-6">
                <div class="card comp-card">
                    <div class="card-body" style="min-height: 143px;">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-b-20">{{__('Total Contracts')}}</h6>
                                <h3 class="text-primary">{{ $cnt_contract['total'] }}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-handshake bg-success text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-6">
                <div class="card comp-card">
                    <div class="card-body" style="min-height: 143px;">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-b-20">{{__('This Month Total Contracts')}}</h6>
                                <h3 class="text-info">{{ $cnt_contract['this_month'] }}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-handshake bg-info text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-6">
                <div class="card comp-card">
                    <div class="card-body" style="min-height: 143px;">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-b-20">{{__('This Week Total Contracts')}}</h6>
                                <h3 class="text-warning">{{ $cnt_contract['this_week'] }}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-handshake bg-warning text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-6">
                <div class="card comp-card">
                    <div class="card-body" style="min-height: 143px;">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-b-20">{{__('Last 30 Days Total Contracts')}}</h6>
                                <h3 class="text-danger">{{ $cnt_contract['last_30days'] }}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-handshake bg-danger text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card table-card">
                    <div class="card-header card-body table-border-style">
                        <div class="table-responsive">
                           <table class="table table-centered table-hover mb-0 animated" id="selection-datatable">
                                <thead>
                                    <tr>
                                        <th>{{__('Contracts')}}</th>
                                        @if(Auth::user()->getGuard() != 'client')
                                        <th>{{__('Client')}}</th>
                                        @endif
                                        <th>{{__('Project')}}</th>
                                        <th>{{__('Subject')}}</th>
                                        <th>{{__('Value')}}</th>
                                        <th>{{__('Type')}}</th>
                                        <th>{{__('Start Date')}}</th>
                                        <th>{{__('End Date')}}</th>
                                        <th>{{__('Status')}}</th>
                                     
                                        <th width="250px">{{__('Action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($contracts as $contract)
                                        <tr>
                                            <td class="Id">
                                                <a href="@auth('web'){{ route('contracts.show',[$currentWorkspace->slug,$contract->id]) }}@elseauth{{ route('client.contracts.show',[$currentWorkspace->slug,$contract->id]) }}@endauth" class="btn btn-outline-primary">{{ App\Models\Utility::contractNumberFormat($contract->id) }}</a>
                                            </td>
                                             @if(Auth::user()->getGuard() != 'client')
                                            <td>{{!empty($contract->clients)?$contract->clients->name:'' }}</td>
                                            @endif
                                            <td>{{!empty( $contract->projects ) ?  $contract->projects->name  : ''}}</td>
                                            <td>{{ $contract->subject }}</td>
                                            <td>{{$currentWorkspace->priceFormat($contract->value) }}</td>
                                            <td>{{ $contract->contract_type->name }}</td>
                                            <td>{{App\Models\Utility::dateFormat($contract->start_date)}}</td>
                                            <td>{{App\Models\Utility::dateFormat($contract->end_date)}}</td>
                                            <td>
                                                 @if($contract->status == 'off')
                                                  <span class="badge bg-danger p-2 px-3 rounded">{{__('Close')}}</span>
                                                 @else
                                                  <span class="badge bg-success p-2 px-3 rounded">{{__('Start')}}</span>
                                                 @endif
                                            </td>
                                          
                                            <td class="Action">
                                                <span>

                                                     @if ($currentWorkspace->permission == 'Owner' || Auth::user()->getGuard() == 'client')
                                                    <div class="action-btn bg-warning ms-2">
                                                        <a href="@auth('web'){{ route('contracts.show',[$currentWorkspace->slug,$contract->id]) }}@elseauth{{ route('client.contracts.show',[$currentWorkspace->slug,$contract->id]) }}@endauth" class="mx-3 btn btn-sm d-inline-flex align-items-center" title="" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Detail" aria-label="Detail"><span class="text-white"><i class="ti ti-eye"></i></span></a>



                                                    </div>
                                                    @endif
                                                    @auth('web')
                                                    <div class="action-btn btn-secondary ms-2">
                                                        <a href="#" data-size="lg" data-url="{{route('contracts.copy',[$currentWorkspace->slug,$contract->id])}}"data-ajax-popup="true" data-title="{{__('Duplicate')}}" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Duplicate')}}" ><i class="ti ti-copy text-white"></i></a>
                                                    </div>
                                                   
                                                   
                                                    <div class="action-btn btn-info ms-2">
                                                        <a href="#" data-size="lg" data-url="{{ route('contracts.edit',[$currentWorkspace->slug,$contract->id]) }}"
                                                            data-ajax-popup="true" data-title="{{__('Edit contract')}}" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Edit')}}" ><i class="ti ti-pencil text-white"></i></a>
                                                    </div>
                                                  
                                                        <div class="action-btn bg-danger ms-2">
                                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="delete-form-{{$contract->id}}" title="{{__('Delete')}}" data-bs-toggle="tooltip" data-bs-placement="top"><span class="text-white"><i class="ti ti-trash"></i></span></a>
                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['contracts.destroy', [$currentWorkspace->slug,$contract->id]], 'id' => 'delete-form-' . $contract->id]) !!}
                                                            {!! Form::close() !!}
                                                        </div>

                                                        @endauth
                                                    
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>            
                </div>
            </div>
        </div>
@endsection
@push('scripts')
<script type="text/javascript">
    $(document).on('change', '.client_id', function() {
    //    alert('hey');
        getUsers($(this).val());
    });

    function getUsers(id) {
      
        $("#project-div").html('');
        $('#project-div').append('<select class="form-control" id="project" name="project" ></select>');
        // console.log('project');
        $.get("{{ url('get-projects') }}/" + id, function(data, status) 
        {
            var list = '';
            $('#project').empty();
            if(data.length > 0){
                list += "<option value=''> {{__('Select Projects')}} </option>";
            }else{
                list += "<option value=''> {{__('No Projects')}} </option>";
            }
            $.each(data, function(i, item) {
                list += "<option value='"+item.id+"'>"+item.name+"</option>"
            });

            var select = '<select class="form-control" id="project" name="project" >'+list+'</select>';
            $('.project-div').html(select);
            select2();
        });
    }
</script>



@endpush