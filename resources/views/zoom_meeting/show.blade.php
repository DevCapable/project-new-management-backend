<div class="form-body px-5">
    <div class="row">
        
        <div class="col-md-6 ">
            <div class="form-group">
                <label><b>{{__('Zoom Meeting Title')}}</b></label>
                <p> {{$ZoomMeeting->title}} </p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label><b>{{__('Zoom Meeting ID')}}</b></label>
                <p> {{$ZoomMeeting->meeting_id}} </p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 ">
            <div class="form-group">
                <label><b>{{__('Project Name')}}</b></label>
                <p>{{ !empty($ZoomMeeting->project_id)?$ZoomMeeting->projectName:'' }}</p>
            </div>
        </div>
    
        <div class="col-md-6 ">
            <div class="form-group">
                <label><b>{{__('User Name')}}</b></label>

                <p>{{!empty($ZoomMeeting->member_ids)?$ZoomMeeting->getUserName->name:'' }}</p>
            </div>
        </div>
        </div>
         <div class="row">
        <div class="col-md-6 ">
            <div class="form-group">
                <label><b>{{__('Client Name')}}</b></label>
                <p>{{!empty($ZoomMeeting->client_id)?$ZoomMeeting->getclientname->name:'' }}  </p>
            </div>
        </div>
    
  
        <div class="col-md-6">
            <div class="form-group">
                <label><b>{{__('Date')}}</b></label>
                <p>{{App\Models\Utility::dateFormat($ZoomMeeting->start_date)}}</p>
            </div>
        </div>
        </div>
        <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label><b>{{__('Time')}}</b></label>
                <p>{{$ZoomMeeting->start_date}}</p>
            </div>
        </div>
    
    
        <div class="col-md-6">
            <div class="form-group">
                <label><b>{{__('Duration')}}</b></label>
                <p> {{$ZoomMeeting->duration }} Minutes</p>
            </div>
        </div>
    </div>
</div>



