<form class="" method="post" action="{{ route('contracts.store',[$currentWorkspace->slug]) }}">
    @csrf
<div class="modal-body">
    <div class="row">
        <div class="col-md-6 form-group">
            {{ Form::label('client_id', __('Client Name'), ['class' => 'col-form-label']) }}
            {{ Form::select('client_id', $client, null, ['class' => 'form-control client_id','id' => 'client_id', 'data-toggle' => 'select', 'required' => 'required']) }}
        </div>  
        <div class="col-md-6 form-group">
            {{ Form::label('project', __('Project'), ['class' => 'col-form-label']) }}
            <div class="project-div"> 
                {{ Form::select('project_id', $projects, null, ['class' => 'form-control', 'id' => 'project','placeholder' => __('Select Projects'), 'name' => 'project','required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-6 form-group">
            {{ Form::label('subject', __('Subject'),['class'=>'col-form-label']) }}
            {{ Form::text('subject', '', array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="col-md-6 form-group">
            {{ Form::label('value', __('Value'),['class'=>'col-form-label']) }}
            {{ Form::number('value', '', array('class' => 'form-control','required'=>'required','min' => '1')) }}
        </div>
        
        <div class="form-group col-md-6">
            {{ Form::label('start_date', __('Start Date'),['class'=>'col-form-label']) }}
            {{ Form::date('start_date', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('end_date', __('Due Date'),['class'=>'col-form-label']) }}
            {{ Form::date('end_date', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('type', __('Type'),['class'=>'col-form-label']) }}
            {!! Form::select('type', $contractType, null,array('class' => 'form-select','required'=>'required')) !!}
            @if(count($contractType) <= 0)
                <div class="text-muted text-xs">
                    {{__('Please create new contract type')}} <a href="{{route('contract_type.index')}}">{{__('here')}}</a>.
                </div>
            @endif
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('description', __('Description'),['class'=>'col-form-label']) }}
                {{ Form::textarea('description', '', array('class' => 'form-control')) }}
            </div>
        </div>

         <div class="col-md-12 form-group">
                <label class="col-form-label">{{__('Status')}}</label>
                <div class="d-flex radio-check">
                    <div class="custom-control custom-radio custom-control-inline m-1">
                        <input class="form-check-input" type="radio" id="on" value="on" name="status">
                        <label class="form-check-labe" for="pre">{{__('Start')}}</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline m-1">
                        <input class="form-check-input" type="radio" id="off" value="off" name="status">
                        <label class="form-check-labe" for="post">{{__('Close')}}</label>
                    </div>
                </div>
            </div>



    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn btn-primary ms-2">
</div>

</form>




        
