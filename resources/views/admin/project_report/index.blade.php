@extends('layouts.admin')
@section('page-title')
    {{__('Project Report')}}
@endsection    
@section('links')
@if(\Auth::guard('client')->check())   
<li class="breadcrumb-item"><a href="{{route('client.home')}}">{{__('Home')}}</a></li>
 @else
 <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('Home')}}</a></li>
 @endif
<li class="breadcrumb-item"> {{ __('Project Report') }}</li>
@endsection 
@section('action-button')
    <a href="#" class="btn btn-sm btn-primary filter" data-toggle="tooltip" title="{{ __('Filter') }}">
        <i class="ti ti-filter"></i>
    </a>
@endsection
@php
  
    $client_keyword = Auth::user()->getGuard() == 'client' ? 'client.' : '';
@endphp

@section('content')

<!--  <div class="col-lg-12 projectreportdata p-0">  
 </div> -->

  <div class="row  display-none" id="show_filter">
  <!--       <div class=" form-group col-3">
            <select class=" form-select" name="project" id="project">
                <option value="">{{ __('All Projects') }}</option>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                @endforeach
            </select>
        </div> -->
         @if ($currentWorkspace->permission == 'Owner' || Auth::user()->getGuard() == 'client')
            <div class="col-2">
                <select class="select2 form-select" name="all_users" id="all_users">
                    <option value="" class="px-4">{{ __('All Users') }}</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
     
        <div class="col-3">
            <select class="select2 form-select" name="status" id="status">
                <option value="" class="px-4">{{ __('All Status') }}</option>

                    <option value="Ongoing">{{ __('Ongoing')}}</option>
                    <option value="Finished">{{ __('Finished')}}</option>
                       <option value="OnHold">{{ __('OnHold')}}</option>
              
            </select>
        </div>
         

        <div class="form-group col-md-3">  
            <div class="input-group date ">
            <input class="form-control datepicker5" type="text" id="start_date" name="start_date" value="" autocomplete="off" required="required"  placeholder="{{ __('Start Date') }}">
             <span class="input-group-text">
                 <i class="feather icon-calendar"></i>
            </span>
        </div>
      </div>
              <div class="form-group col-md-3">
            <div class="input-group date ">
           <input class="form-control datepicker4" type="text" id="end_date" name="end_date" value="" autocomplete="off" required="required" placeholder="{{ __('End Date') }}">
             <span class="input-group-text">
                 <i class="feather icon-calendar"></i>
            </span>
        </div>
        </div>
        <button class=" btn btn-primary col-1  apply btn-filter">{{ __('Apply') }}</button>
    </div>
    <div class="row">
          <div class="col-xl-12">
                <div class="card">
                    <div class="card-body table-border-style mt-3 mx-2">
                        <div class="table-responsive">
                                <table class="table table-centered table-hover mb-0 animated selection-datatable px-4 mt-2" id="selection-datatable1">
                                    <thead>
                                        <tr>
                                            <th> {{__('#')}}</th>
                                            <th> {{__('Project Name')}}</th>
                                            <th> {{__('Start Date')}}</th>
                                            <th> {{__('Due Date')}}</th>
                                            <th> {{__('Project Member')}}</th>
                                            <th> {{__('Progress')}}</th>
                                            <th>{{__('Project Status')}}</th>
                                            <th>{{__('Action')}}</th>
                                        </tr>
                                    </thead>
                                     <tbody>
                                   
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
       </div>



@endsection
@push('css-page')
<link rel="stylesheet" href="{{ asset('custom/css/datatables.min.css') }}">
    <style>   
    table.dataTable.no-footer {
    border-bottom: none !important;
} 
 .display-none {
            display: none !important;
        }
    </style>
@endpush

@push('scripts')
<script>
     (function () {
        const d_week = new Datepicker(document.querySelector('.datepicker4'), {
            buttonClass: 'btn',
            todayBtn: true,
            clearBtn: true,
            format: 'yyyy-mm-dd',
        });
    })();
</script>

<script>
     (function () {
        const d_week = new Datepicker(document.querySelector('.datepicker5'), {
            buttonClass: 'btn',
            todayBtn: true,
            clearBtn: true,
            format: 'yyyy-mm-dd',
        });
    })();
</script>
    <script src="{{ asset('custom/js/jquery.dataTables.min.js') }}"></script>
    <script>
        const dataTable = new simpleDatatables.DataTable("#selection-datatable1");
    </script>


    <script type="text/javascript">
        $(".filter").click(function() {
            $("#show_filter").toggleClass('display-none');
        });
    </script>
     <script>
        $(document).ready(function() {

              var table = $("#selection-datatable1").DataTable({
                order: [],
                select: {
                    style: "multi"
                },
                "language": dataTableLang,
                drawCallback: function() {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                }
            });
            $(document).on("click", ".btn-filter", function() {

                getData();
            });

            function getData() {
               table.clear().draw();
                 $("#selection-datatable1 tbody tr").html(
                    '<td colspan="11" class="text-center"> {{ __('Loading ...') }}</td>');

               var data = {
                    status: $("#status").val(),
                    start_date: $("#start_date").val(),
                    end_date  : $("#end_date").val(),
                    all_users :$("#all_users").val(),
                };
                $.ajax({
                    url: '{{ route($client_keyword.'projects.ajax', [$currentWorkspace->slug]) }}',
                    type: 'POST',
                    data: data,
                    success: function(data) {  
                      table.rows.add(data.data).draw(true);
                        loadConfirm();
                    },
                    error: function(data) {
                        show_toastr('Info', data.error, 'error')
                    }
                })
            }

            getData();

        });
    </script>

@endpush
