@extends('layouts.admin')
@section('page-title')
    {{__('Appointment Detail')}}
@endsection
@section('links')
    @if(\Auth::guard('client')->check())
        <li class="breadcrumb-item"><a href="{{route('client.home')}}">{{__('Home')}}</a></li>
    @else
        <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('Home')}}</a></li>
    @endif
    @if(\Auth::guard('client')->check())
        <li class="breadcrumb-item"><a
                href="{{ route('client-appointment-index',$currentWorkspace->slug) }}">{{__('Appointment')}}</a></li>
    @else
        <li class="breadcrumb-item"><a href="{{ route('client-appointment-index',$currentWorkspace->slug)}}">{{__('Appointment')}}</a>
        </li>
    @endif
    <li class="breadcrumb-item">{{__('Appointment Details')}}</li>
@endsection
{{--@php--}}
{{--    $permissions = Auth::user()->getPermission($project->id);--}}
{{--    $client_keyword = Auth::user()->getGuard() == 'client' ? 'client.' : '';--}}
{{--@endphp--}}

<style type="text/css">
    .fix_img {
        width: 40px !important;
        border-radius: 50%;
    }
</style>
@section('content')
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="row">

                <div class="col-xxl-4">


                    {{--                        COMMENT--}}
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0">{{ __('Appointment') }}</h5>
                                </div>
                                <a href="javascript:history.back()"><i class="ti ti-arrow-back"
                                                                       style="float: right; font-size: 40px; padding-right: 10px"></i></a>

                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="timeline timeline-one-side " data-timeline-content="axis"
                                 data-timeline-axis-style="dashed">
                                @if((isset($permissions) && in_array('show activity',$permissions)) || $currentWorkspace->permission == 'Owner')

                                    <div class="timeline-block px-2 pt-3">
                                        <table class="table table-responsive">
                                            <tr>
                                                <th> Title</th>
                                                <td> {{$appointment->title}}</td>
                                            </tr>
                                            <tr>
                                                <th> Description</th>
                                                <td> {{$appointment->description}}</td>
                                            </tr>
                                            <tr>
                                                <th> Date</th>
                                                <td> {{$appointment->date_schedule}}</td>
                                            </tr>
                                        </table>
                                        <div class="card-footer">
                                            {{--                                                <a  href="{{route('client-edit-appointment',[$currentWorkspace->slug, $appointment->id])}}" ><i class="fa fa-edit" style="float: right; font-size: 30px; padding-right: 10px" ></i></a>--}}
                                            @if($appointment->status == 'Submitted')
                                                {!! get_appointment_status_label('Submitted') !!}
                                            @else
                                                <a style="float: right; font-size: 30px; padding-right: 10px" href="#"
                                                   class="action-btn btn-info mx-1  btn btn-sm d-inline-flex align-items-center"
                                                   data-ajax-popup="true" data-size="lg"
                                                   data-toggle="popover" title="{{__('Update')}}"
                                                   data-title="{{__('Update Appointment')}}"
                                                   data-url="{{route('client-edit-appointment',[$currentWorkspace->slug, $appointment->id])}}"><i
                                                        class="ti ti-edit"></i></a>
                                            @endif

                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{--                    END COMMEN --}}

                </div>

            </div>
            <!-- [ sample-page ] end -->
        </div>
        <!-- [ Main Content ] end -->
    </div>
@endsection


@push('css-page')
    <link rel="stylesheet" href="{{asset('custom/css/dropzone.min.css')}}">
@endpush
@push('scripts')

    <!--
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

 -->
    <script src="{{asset('assets/js/plugins/apexcharts.min.js')}}"></script>
    <script>


    </script>






    <script>
        $(document).ready(function () {
            if ($(".top-10-scroll").length) {
                $(".top-10-scroll").css({
                    "max-height": 300
                }).niceScroll();
            }
        });

    </script>

@endpush
