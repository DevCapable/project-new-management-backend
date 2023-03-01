@extends('layouts.admin')
@section('page-title')
{{__('Zoom Meeting')}}
@endsection
 @section('links')
 @if(\Auth::guard('client')->check())   
<li class="breadcrumb-item"><a href="{{route('client.home')}}">{{__('Home')}}</a></li>
 @else
 <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('Home')}}</a></li>
 @endif
<li class="breadcrumb-item"> {{ __('Zoom Meeting') }}</li>
 @endsection
@push('css-page')
<style>
 
.avatar-group .avatar{
    width: 2rem !important;
    height: 2rem !important;
}
.user-group img:hover {
    z-index: 5;
}
table .user-group img {
    position: relative;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    z-index: 2;
    transition: all 0.1s ease-in-out;
    border: 2px solid #ffffff;
}
</style>

@endpush


@section('action-button')
    @auth('web')
        @if(isset($currentWorkspace) && $currentWorkspace->creater->id == Auth::id())
           
             <a href="{{route('zoommeeting.Calender',$currentWorkspace->slug)}}" class="btn btn-sm btn-primary mx-1" id="" data-toggle="tooltip" title="{{__('calendar')}}">  <i class="ti ti-calendar"></i></a>

              <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="lg" data-title="{{ __('Create New Meeting') }}" data-toggle="tooltip" title="{{__('Add Meeting')}}" data-url="{{route('zoom-meeting.create',$currentWorkspace->slug)}}">
                <i class="ti ti-plus "></i>
            </a>  

        @endif
    @endauth

    @auth("client")
    <a href="{{route('zoommeetings.Calender',$currentWorkspace->slug)}}" data-toggle="tooltip" title="{{__('calendar')}}" class="btn btn-sm btn-primary mx-1" id=""> <i class="ti ti-calendar"></i> </a>
    @endauth
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table  dataTable" id="selection-datatable">
                            <thead>
                            <tr>
                                <th> {{ __('Title') }} </th>
                                 
                                 
                                <th> {{ __('Project') }}  </th>
                                <th> {{ __('Members') }}  </th>
                                @if(Auth::user()->type == 'user'&& $currentWorkspace->creater->id == Auth::user()->id)  
                                  <th> {{ __('Client') }}  </th>
                                @endif
                                <th> {{ __('Meeting Time') }} </th>
                                <th> {{ __('Duration') }} </th>
                                <th> {{ __('Join URL') }} </th>
                                <th> {{ __('Status') }} </th>
                                  @if(Auth::user()->type == 'user'&& $currentWorkspace->creater->id == Auth::user()->id)  
                                <th class="text-right"> {{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                                @forelse ($meetings as $item)
                                <tr>
                                    <td>{{$item->title}}</td>
                                    <td>{{$item->project_name}}</td>
                                    <td>
                                        <div class="user-group">
                                            @foreach($item->getMembers() as $user)
                                                <a href="#" class="img_group" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{$user->name}}">
                                                        <img alt="{{$user->name}}" @if($user->avatar) src="{{asset('/storage/avatars/'.$user->avatar)}}" @else avatar="{{ $user->name }}" @endif>
                                                </a>
                                            @endforeach
                                        </div>
                                    </td>
                                    @if(Auth::user()->type == 'user'&& $currentWorkspace->creater->id == Auth::user()->id)  
                                    <td>
                                        <div class="avatar-group hover-avatar-ungroup mb-3">
                                            @foreach($item->getClients() as $user)
                                                <a href="#" class="avatar rounded-circle avatar-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{$user->name}}">
                                                        <img alt="{{$user->name}}" @if($user->avatar) src="{{asset('/storage/avatars/'.$user->avatar)}}" @else avatar="{{ $user->name }}" @endif style="border-radius: 50%; max-hight: 40px; max-width: 30px;">
                                                </a>
                                            @endforeach
                                        </div>
                                    </td>
                                    @endif
                                    <td>{{$item->start_date}}</td>
                                    <td>{{$item->duration}} {{__("Minutes")}}</td>
                                
                                    <td>
                                        @if($item->created_by == \Auth::user()->id && $item->checkDateTime())
                                        <a href="{{$item->join_url}}" target="_blank"> {{__('Start meeting')}} <i class="fas fa-external-link-square-alt "></i></a>
                                        @elseif($item->checkDateTime())
                                            <a href="{{$item->join_url}}" target="_blank"> {{__('Join meeting')}} <i class="fas fa-external-link-square-alt "></i></a>
                                        @else
                                            -
                                        @endif
            
                                    </td>
                                    <td>
                                        @if($item->checkDateTime())
                                            @if($item->status == 'waiting')
                                                <span class="badge badge-info p-2 px-3 rounded">{{ucfirst($item->status)}}</span>
                                            @else
                                                <span class="badge badge-success p-2 px-3 rounded">{{ucfirst($item->status)}}</span>
                                            @endif
                                        @else
                                            <span class="badge badge-danger p-2 px-3 rounded">{{__("End")}}</span>
                                        @endif
                                    </td>
                                      @if(Auth::user()->type == 'user'&& $currentWorkspace->creater->id == Auth::user()->id)  
                                    <td class="text-right">
                                      
                                        <a href="#" class="action-btn btn-danger  btn btn-sm d-inline-flex align-items-center bs-pass-para" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="delete-form-{{$item->id}}" data-toggle="tooltip" title="{{__('Delete')}}">
                                            <i class="ti ti-trash"></i>
                                        </a>
                                        <form id="delete-form-{{$item->id}}" action="{{ route('zoom-meeting.destroy',[$currentWorkspace->slug,$item->id]) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg ss_modale" role="document">
            <div class="modal-content image_sider_div">
            
            </div>
        </div>
    </div>

@endsection

@push('scripts')


@endpush