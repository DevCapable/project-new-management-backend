
<div class="row">

    <div class="form-group col-md-12">
        <label class="col-form-label">{{ __('Date / Time Schedule')}}</label>
        <input type="text" class="form-control form-control-light" id="date_schedule" name="date_schedule"
               required autocomplete="off">
        <input type="hidden" name="start_date">
        <input type="hidden" name="due_date">
    </div>
    <div class="form-group col-md-12">
        <label for="appointment" class="col-form-label">{{ __('Title') }}</label>
        <input class="form-control" type="text" id="appointmentTitle" name="title" required=""
               placeholder="{{ __('Title') }}">
    </div>
    <div class="form-group col-md-12">
        <label for="description" class="col-form-label">{{ __('Description') }}</label>
        <textarea class="form-control" id="description" name="description" required=""
                  placeholder="{{ __('Add Description') }}"></textarea>
    </div>

{{--    <div class="form-group col-md-12">--}}
{{--        <label class="col-form-label">{{ __('Request Zoom')}}</label>--}}
{{--        <input type="checkbox" id="is_zoom_link" name="is_zoom_link" value="1">--}}
{{--    </div>--}}
</div>
<div class="alert alert-info alert-block">
    <i class="fa fa-check-circle-o"></i>
    <strong>{{ $notice }}</strong>
</div>
