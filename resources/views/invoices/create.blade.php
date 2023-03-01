<form class="" method="post" action="{{ route('invoices.store',[$currentWorkspace->slug]) }}">
    @csrf
     <div class="modal-body">
    <div class="row">
        <div class="form-group col-md-6">
            <label for="project_id" class="col-form-label">{{__('Projects')}}</label>
            <select class="form-control select2" name="project_id" id="project_id">
                <option value="">{{__('Select Project')}}</option>
                @foreach($projects as $p)
                    <option value="{{$p->id}}">{{$p->name}}</option>
                @endforeach
            </select>
        </div> 
        <div class="form-group col-md-6">
            <label for="discount" class="col-form-label">{{ __('Discount') }}</label>
            <div class="form-icon-user">
                <span class="currency-icon bg-primary">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$' }}</span>
                <input class="form-control currency_input" type="number" min="0" id="discount" name="discount" placeholder="{{ __('Enter Discount') }}" value="0">
            </div>
        </div>
    <!--     <div class="form-group col-md-6">
            <label for="issue_date" class="col-form-label">{{ __('Issue Date') }}</label>
            <input class="form-control datepicker" type="text" id="issue_date" name="issue_date" autocomplete="off" required="required">
        </div>
 -->
        <div class="form-group col-md-6">
              <label for="issue_date" class="col-form-label">{{ __('Issue Date') }}</label>  
            <div class="input-group date ">
            <input class="form-control datepicker" type="text" id="issue_date" name="issue_date"  autocomplete="off" required="required">
             <span class="input-group-text">
                 <i class="feather icon-calendar"></i>
            </span>
        </div>
      </div>

        <div class="form-group col-md-6">
              <label for="due_date" class="col-form-label">{{ __('Due Date') }}</label>  
            <div class="input-group date ">
            <input class="form-control datepicker2" type="text" id="due_date" name="due_date"  autocomplete="off" required="required">
             <span class="input-group-text">
                 <i class="feather icon-calendar"></i>
            </span>
        </div>
      </div>

        <div class="form-group col-md-6">
            <label for="tax_id" class="col-form-label">{{__('Tax')}}%</label>
            <select class="form-control select2" name="tax_id" id="tax_id">
                <option value="">{{__('Select Tax')}}</option>
                @foreach($taxes as $p)
                    <option value="{{$p->id}}">{{$p->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-6">
            <label for="client_id" class="col-form-label">{{__('Client')}}</label>
            <select class="form-control select2" name="client_id" id="client_id">
                <option value="">{{__('Select Client')}}</option>
                @foreach($clients as $p)
                    <option value="{{$p->id}}">{{$p->name}} - {{$p->email}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
        <div class="modal-footer">
            <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close')}}</button>
            <input type="submit" value="{{ __('Save Changes')}}" class="btn  btn-primary">

        </div>
  
</form>

<script>
     (function () {
        const d_week = new Datepicker(document.querySelector('.datepicker2'), {
            buttonClass: 'btn',
            todayBtn: true,
            clearBtn: true,
             format: 'yyyy-mm-dd',
        });
    })();
</script>

<script>
     (function () {
        const d_week = new Datepicker(document.querySelector('.datepicker'), {
            buttonClass: 'btn',
            todayBtn: true,
            clearBtn: true,
             format: 'yyyy-mm-dd',
        });
    })();
</script>