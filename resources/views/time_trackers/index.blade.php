@extends('layouts.admin')
@section('page-title')
    {{__('Manage Tracker')}}
@endsection    
@section('links')
@if(\Auth::guard('client')->check())   
<li class="breadcrumb-item"><a href="{{route('client.home')}}">{{__('Home')}}</a></li>
 @else
 <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('Home')}}</a></li>
 @endif
<li class="breadcrumb-item"> {{ __('Time Tracker') }}</li>
@endsection 
@section('action-button')
 @auth('client')
        <a href="{{route('client.projects.show',[$currentWorkspace->slug,$id])}}" data-toggle="tooltip" title="{{__('Back')}}" class="btn btn-sm btn-primary">
            <i class=" ti ti-arrow-back-up"></i>
        </a> 
    @endauth

@endsection

   
@push('css-page')
    <link rel="stylesheet" href="{{url('custom/libs/swiper/dist/css/swiper.min.css')}}">
  
    
    <style>
        .product-thumbs .swiper-slide img {
            border:2px solid transparent;
            object-fit: cover;
            cursor: pointer;
        }
        .product-thumbs .swiper-slide-active img {
            border-color: #bc4f38;
        }

        .product-slider .swiper-button-next:after,
        .product-slider .swiper-button-prev:after {
            font-size: 20px;
            color: #000;
            font-weight: bold;
        }

       .modal-dialog.modal-md {
            background-color: #fff !important;
        } 
        /* .modal-backdrop {
            background:transparent !important;
        } */
        .no-image{
            min-height: 300px;
            align-items: center;
            display: flex;
            justify-content: center;
        }
      
    </style>
@endpush


@section('content')
    <div class="row">
          <div class="col-xl-12">
                <div class="card">
                    <div class="card-body table-border-style ">
                        <div class="table-responsive">
                                <table class=" table" id="selection-datatable">
                                    <thead>
                                        <tr>
                                            <th> {{__('Description')}}</th>
                                            <th> {{__('Project')}}</th>
                                            <th> {{__('Task')}}</th>
                                            <th> {{__('Workspace')}}</th>
                                            <th> {{__('Start Time')}}</th>
                                            <th> {{__('End Time')}}</th>
                                            <th>{{__('Total Time')}}</th>
                                            <th>{{__('Action')}}</th>
                                        </tr>
                                    </thead>
                                     <tbody>
                                    @foreach ($treckers as $trecker)
                                        @php
                                            $total_name = App\Models\Utility::second_to_time($trecker->total_time);
                                        @endphp
                                        <tr>
                                           <td>{{__($trecker->name)}}</td>
                                            <td>{{__($trecker->project_name)}}</td>
                                            <td>{{__($trecker->project_task)}}</td>
                                            <td>{{__($trecker->project_workspace)}}</td>
                                            <td>{{__(date("H:i:s",strtotime($trecker->start_time)))}}</td>
                                            <td>{{__(date("H:i:s",strtotime($trecker->end_time)))}}</td>
                                            <td>{{__($total_name)}}</td>
                                            <td>
                                                <img alt="Image placeholder" src="{{ asset('assets/images/gallery.png')}}" class="avatar view-images rounded-circle avatar-sm" data-toggle="tooltip" title="{{__('View Screenshot images')}}" style="height: 25px;width:24px;margin-right:10px;cursor: pointer;" data-id="{{$trecker->id}}" id="track-images-{{$trecker->id}}">


                                               <a href="#" class="action-btn btn-danger btn btn-sm d-inline-flex align-items-center bs-pass-para" data-toggle="tooltip" title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="delete-form-{{$trecker->id}}">
                                                <i class="ti ti-trash"></i>
                                            </a>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['tracker.destroy', $trecker->id],'id'=>'delete-form-'.$trecker->id]) !!}
                                            {!! Form::close() !!}
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
    </div>

    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg ss_modale" role="document">
            <div class="modal-content image_sider_div">
            
            </div>
        </div>
    </div>

@endsection

@push('scripts')

<script src="{{url('custom/libs/swiper/dist/js/swiper.min.js')}}"></script>

<script type="text/javascript">

    function init_slider(){
            if($(".product-left").length){
                    var productSlider = new Swiper('.product-slider', {
                        spaceBetween: 0,
                        centeredSlides: false,
                        loop:false,
                        direction: 'horizontal',
                        loopedSlides: 5,
                        navigation: {
                            nextEl: ".swiper-button-next",
                            prevEl: ".swiper-button-prev",
                        },
                        resizeObserver:true,
                    });
                var productThumbs = new Swiper('.product-thumbs', {
                    spaceBetween: 0,
                    centeredSlides: true,
                    loop: false,
                    slideToClickedSlide: true,
                    direction: 'horizontal',
                    slidesPerView: 7,
                    loopedSlides: 5,
                });
                productSlider.controller.control = productThumbs;
                productThumbs.controller.control = productSlider;
            }
        }


    $(document).on('click', '.view-images', function () {
        
            var p_url = "{{route('tracker.image.view',$currentWorkspace->id)}}";
            var data = {
                'id': $(this).attr('data-id')
            };
           
            postAjax(p_url, data, function (res) {
                $('.image_sider_div').html(res);
                $('#exampleModalCenter').modal('show');   
                setTimeout(function(){
                    var total = $('.product-left').find('.product-slider').length
                    if(total > 0){
                        init_slider(); 
                    }
                
                },200);

            });
            });


            // ============================ Remove Track Image ===============================//
            $(document).on("click", '.track-image-remove', function () {
            var rid = $(this).attr('data-pid');
            $('.confirm_yes').addClass('image_remove');
            $('.confirm_yes').attr('image_id', rid);
            $('#cModal').modal('show');
            var total = $('.product-left').find('.swiper-slide').length
            });

    

            function removeImage(id){
                var p_url = "{{route('tracker.image.remove')}}";
                var data = {id: id};
                deleteAjax(p_url, data, function (res) {
                    if(res.flag){
                        $('#slide-thum-'+id).remove();
                        $('#slide-'+id).remove();
                        setTimeout(function(){
                            var total = $('.product-left').find('.swiper-slide').length
                            if(total > 0){
                                init_slider();
                            }else{
                                $('.product-left').html('<div class="no-image"><h5 class="text-muted">Images Not Available .</h5></div>');
                            }
                        },200);
                    }
                    $('#cModal').modal('hide');
                    show_toastr('success', res.msg,"success");
                });
            }
            // $(document).on("click", '.remove-track', function () {
              
            // var rid = $(this).attr('data-id');
            // $('.confirm_yes').addClass('t_remove');
            // $('.confirm_yes').attr('uid', rid);
            // $('#cModal').modal('show');
        // });

      
</script>
@endpush