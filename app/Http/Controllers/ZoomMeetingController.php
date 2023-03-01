<?php

namespace App\Http\Controllers;

use App\Models\ZoomMeeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Utility;
use App\Models\UserProject;
use App\Models\Project;
use App\Models\ClientProject;


use App\Traits\ZoomMeetingTrait;

class ZoomMeetingController extends Controller
{
    use ZoomMeetingTrait;
    const MEETING_TYPE_INSTANT = 1;
    const MEETING_TYPE_SCHEDULE = 2;
    const MEETING_TYPE_RECURRING = 3;
    const MEETING_TYPE_FIXED_RECURRING_FIXED = 8;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug)
    {
        $objUser          = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        if($objUser->getGuard() == 'client'){
            $meetings = ZoomMeeting::whereRaw("find_in_set(".\Auth::user()->id.",client_id)")->where('workspace_id',$currentWorkspace->id)->get();
        }elseif($currentWorkspace->permission == 'Owner'){
            $meetings = ZoomMeeting::where('created_by',\Auth::user()->id)->where('workspace_id',$currentWorkspace->id)->get();
        }else{
            $meetings = ZoomMeeting::whereRaw("find_in_set(".\Auth::user()->id.",member_ids)")->where('workspace_id',$currentWorkspace->id)->get();
        }
        $this->statusUpdate($slug);
        return view('zoom_meeting.index',compact('meetings','currentWorkspace'));

    }

    public function create($slug)
    {
        $objUser          = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $assign_pro_ids = UserProject::where('user_id',$objUser->id)->pluck('project_id');
        $projects      = Project::with('task')->select(['name','id','workspace',])->whereIn('id', $assign_pro_ids)->where('workspace',$currentWorkspace->id)->pluck('name','id');
        return view('zoom_meeting.create',compact('projects','currentWorkspace'));

    }


    public function store($slug,Request $request)
    {

        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $client_ids = ClientProject::getByProjects($request->project_id);

        $data['topic'] = $request->title;
        $data['start_time'] = date('y:m:d H:i:s',strtotime($request->start_date));
        $data['duration'] = (int)$request->duration;
        $data['password'] = $request->password;
        $data['host_video'] = 0;
        $data['participant_video'] = 0;
        $data['workspace'] = $slug;
        try
        {
        $meeting_create = $this->createmitting($data);
        \Log::info('Meeting');
        \Log::info((array)$meeting_create);
        if(isset($meeting_create['success']) &&  $meeting_create['success'] == true){
            $meeting_id = isset($meeting_create['data']['id'])?$meeting_create['data']['id']:0;
            $start_url = isset($meeting_create['data']['start_url'])?$meeting_create['data']['start_url']:'';
            $join_url = isset($meeting_create['data']['join_url'])?$meeting_create['data']['join_url']:'';
            $status = isset($meeting_create['data']['status'])?$meeting_create['data']['status']:'';

            $new = new ZoomMeeting();
            $new->title = $request->title;
            $new->workspace_id = $currentWorkspace->id;
            $new->meeting_id = $meeting_id;
            $new->client_id = implode(',',$client_ids);
            $new->project_id = $request->project_id;
            $new->member_ids = implode(',',$request->members);
            $new->start_date = date('y:m:d H:i:s',strtotime($request->start_date));
            $new->duration = $request->duration;
            $new->start_url = $start_url;
            $new->password = $request->password;
            $new->join_url = $join_url;
            $new->status = $status;
            $new->created_by = \Auth::user()->id;
            $new->save();
            return redirect()->back()->with('success', __('Meeting created successfully.'));
        }else{
            return redirect()->back()->with('error', __('Meeting not created.'));
        }
        }
        catch(\Exception $e)
        {
            return redirect()->back()->with('error', __("invalide token."));
        }
    }


     public function show($slug,$id)
         {
            $objUser          = Auth::user();
            $currentWorkspace = Utility::getWorkspaceBySlug($slug);

                $ZoomMeeting = ZoomMeeting::where('id',$id)->where('workspace_id',$currentWorkspace->id)->first();

         if($ZoomMeeting->workspace_id == $currentWorkspace->id)
        {

            return view('zoom_meeting.show', compact('ZoomMeeting'));
        }
        else
        {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }


    public function edit($slug,ZoomMeeting $zoomMeeting)
    {

    }


    public function update($slug,Request $request, ZoomMeeting $zoomMeeting)
    {

    }

   public function calender($slug,Request $request)
    {

            $objUser          = Auth::user();
            $currentWorkspace = Utility::getWorkspaceBySlug($slug);

             $meetings = ZoomMeeting::where('created_by',\Auth::user()->id)->where('workspace_id',$currentWorkspace->id)->get();
            $arrMeeting = [];
            foreach($meetings as $meeting)
            {
                $arr['id']        = $meeting['id'];
                $arr['title']     = $meeting['title'];
                $arr['workspace_id']= $meeting['workspace_id'];
                $arr['meeting_id'] = $meeting['meeting_id'];
                $arr['start'] = $meeting['start_date'];
                $arr['duration'] = $meeting['duration'];
                $arr['start_url'] = $meeting['start_url'];
                $arr['className'] = 'event-warning';



                if(\Auth::guard('client')->check()) {
                    $arr['url']       = route('zoom_meetings.show', [$slug,$meeting['id']]);
                }
           else{
                    $arr['url']       = route('zoom_meeting.show', [$slug,$meeting['id']]);
                }



                $arrMeeting[] = $arr;
            }

            $calandar = array_merge( $arrMeeting);
            $calandar = str_replace('"[', '[', str_replace(']"', ']', json_encode($calandar)));



            return view('zoom_meeting.calender', compact('calandar','currentWorkspace'));
    }
    public function destroy($slug,ZoomMeeting $zoomMeeting,$id)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $zoomMeeting = ZoomMeeting::where('id',$id)->where('workspace_id',$currentWorkspace->id)->delete();
        return redirect()->back()->with('success', __('Meeting deleted successfully.'));
        //
    }
    public function statusUpdate($slug){
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $meetings = ZoomMeeting::where('workspace_id',$currentWorkspace->id)->pluck('meeting_id');

       try
        {
        foreach($meetings as $meeting){
            $data = $this->get($meeting,$slug);
            if(isset($data['data']) && !empty($data['data'])){
                $meeting = ZoomMeeting::where('meeting_id',$meeting)->update(['status'=>$data['data']['status']]);
            }
        }

         }
        catch(\Exception $e)
        {
            return redirect()->back()->with('error', __("invalide token."));
        }

    }
}
