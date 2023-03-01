@extends('layouts.invoicepayheader')

@section('content')
<div class="row" >
    <div class="col-lg-10">
        <div class="container">
            <div>
                <div class="card mt-5" id="printTable" style="margin-left: 180px;margin-right: -57px;">
                    <div class="card-body" id="boxes">
                        <div class="">
                            <div class="col-auto pe-0">
                              
                                 <img src="{{asset(Storage::url('logo/'.$company_logo))}}" style="max-width: 150px;"/>
                            </div>
                        </div>
                        <div class="row align-items-center mb-4">
                            <div class="col-sm-6 mb-3 mb-sm-0 mt-3">
                                <div class="col-lg-6 col-md-8">
                                    <h6 class="d-inline-block m-0 d-print-none">{{__('Contract Value :')}}</h6>
                                    <span class="col-md-8"><span class="text-md">{{$currentWorkspace->priceFormat($contract->value) }}</span></span>
                                </div>
                                <div class="col-lg-6 col-md-8 mt-3">
                                    <h6 class="d-inline-block m-0 d-print-none">{{__('Contract Number :')}}</h6>
                                    <span class="col-md-8"><span class="text-md">{{App\Models\Utility::contractNumberFormat($contract->id)}}</span></span>
                                </div>
                                <div class="col-lg-6 col-md-8 mt-3">
                                    <h6 class="d-inline-block m-0 d-print-none">{{__('Contract Type :')}}</h6>
                                    <span class="col-md-8"><span class="text-md">{{$contract->contract_type->name}}</span></span>
                                </div>
                            </div>
                            <div class="col-sm-6 text-sm-end">
                                <div>
                                    <div class="float-end">
                                        <div class="">
                                            <h6 class="d-inline-block m-0 d-print-none">{{__('Start Date :')}}</h6>
                                            <span class="col-md-8"><span class="text-md">{{App\Models\Utility::dateFormat($contract->start_date) }}</span></span>
                                        </div>
                                        <div class="mt-3">
                                            <h6 class="d-inline-block m-0 d-print-none">{{__('End Date :')}}</h6>
                                            <span class="col-md-8"><span class="text-md">{{App\Models\Utility::dateFormat($contract->end_date)}}</span></span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <p data-v-f2a183a6="">
                            <div>{!!$contract->contract_description!!}</div>
                            <div>{!!$contract->description!!}</div>
                        </p>
                        <div class="row">
                            <div class="col-6" style="width: 300px;">
                                <div>
                                    <img width="200px" src="{{$contract->company_signature}}" >
                                </div>
                                <div>
                                    <h5 class="mt-4">{{__('Company Signature')}}</h5>
                                </div>
                            </div> 
                            <div class="col-6 text-end">
                                <div>
                                    <img width="150px" src="{{$contract->client_signature}}" >
                                </div>
                                <div>
                                    <h5 class="mt-auto">{{__('Client Signature')}}</h5>
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" src="{{ asset('custom/js/html2pdf.bundle.min.js') }}"></script>
<script>
    function closeScript() {
        setTimeout(function () {
            window.open(window.location, '_self').close();
        }, 1000);
    }

    $(window).on('load', function () {
        var element = document.getElementById('boxes');
        var opt = {
            filename: '{{App\Models\Utility::contractNumberFormat($contract->contract_id)}}',
            image: {type: 'jpeg', quality: 1},
            html2canvas: {scale: 4, dpi: 72, letterRendering: true},
            jsPDF: {unit: 'in', format: 'A4'}
        };

        html2pdf().set(opt).from(element).save().then(closeScript);
    });
</script>


