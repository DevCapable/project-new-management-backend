<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\UserProject;
use App\Models\Project;
use App\Models\Utility;
use App\Models\Workspace;
use App\Models\Task;
use App\Models\UserWorkspace;
use App\Models\TimeTracker;
use App\Models\TrackPhoto;
use Illuminate\Support\Facades\Validator;


class apicontroller extends Controller
{
    use ApiResponser;

       public function login(Request $request)
    { 
        $attr = $request->validate([
            'email' => 'required|string|email|',
            'password' => 'required|string'
        ]);

        if (!\Auth::attempt($attr)) {
            return $this->error('Credentials not match', 401);
        }
        
       $user = Auth::user();
        $workspace_id =$user->currant_workspace ;
        $getworkspace=Workspace::where("id",$workspace_id)->first();
      
        $settings = [
          
               'shot_time'=> isset($getworkspace->interval_time)?$getworkspace->interval_time:10, 
         ];

        return $this->success([
            'token' => auth()->user()->createToken('API Token')->plainTextToken,
            'user'=>auth()->user(),
             'settings' =>$settings, 
        ],'Login successfully.');
    }
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return $this->success([],'Tokens Revoked');
    }

   public function getworkspace(Request $request){


       $objUser = Auth::user();
        if($objUser && $objUser->currant_workspace)
        {              
        $rs = Workspace::select([
                                            'workspaces.*',
                                            'user_workspaces.permission',
                                        ])->join('user_workspaces', 'workspaces.id', '=', 'user_workspaces.workspace_id')->where('user_id', '=', $objUser->id)->pluck('slug','id')->toArray();
            
        }

          return $this->success([
            'workspaces' =>  $rs,
        ],'Get Workspace successfully.');

     }

     public function getProjects(Request $request){
       
        $user = auth()->user();
       $workspace_id = $request->work_space;
        $user = auth()->user();
        if($user->type ="user")
        {
         $assign_pro_ids = UserProject::where('user_id',$user->id)->pluck('project_id');
            $project_s      = Project::with('task')->select(
                [
                     'name',
                     'id',
                    'workspace',
                 ]
             )->whereIn('id', $assign_pro_ids)->where('workspace',$workspace_id)->get()->toArray();
        }
          else
            {
                $project_s = Project::with('task')->select(
                    [
                        'name',
                        'id',
                        'workspace',
                    ]
                )->where('created_by', $user->id)->where('workspace',$workspace_id)->get()->toArray();
               
            }



            return $this->success([
                'projects' => $project_s,
            ],'Get Project List successfully.');


    }


   public function addTracker(Request $request){
   
        $user = auth()->user();
        if($request->has('action') && $request->action == 'start'){
         
            $validatorArray = [
                'task_id' => 'required|integer',
            ];
            $validator      = \Validator::make(
                $request->all(), $validatorArray
            );
            if($validator->fails())
            {
                return $this->error($validator->errors()->first(), 401);
            }
            $task= Task::find($request->task_id);
            if(empty($task)){
                return $this->error('Invalid task', 401);
            }
        
            $project_id = isset($task->project_id)?$task->project_id:'';
            TimeTracker::where('created_by', '=', $user->id)->where('is_active', '=', 1)->update(['end_time' => date("Y-m-d H:i:s")]);
            
            $track['name']        = $request->has('workin_on') ? $request->input('workin_on') : '';
            $track['project_id']  = $project_id;      
            $track['workspace_id']      = $request->workspaces_id;
            $track['start_time']  = $request->has('time') ?  date("Y-m-d H:i:s",strtotime($request->input('time'))) : date("Y-m-d H:i:s");
            $track['task_id']     = $request->has('task_id') ? $request->input('task_id') : '';
          
            // $track['created_by']  = $user->id;
            $track                = TimeTracker::create($track);
            $track->action        ='start';
            return $this->success( $track,'Track successfully create.');
        }else{
            $validatorArray = [
                'task_id' => 'required|integer',
                'traker_id' =>'required|integer',
            ];
            $validator      = Validator::make(
                $request->all(), $validatorArray
            );
            if($validator->fails())
            {
                return Utility::error_res($validator->errors()->first());
            }
            $tracker = TimeTracker::where('id',$request->traker_id)->first();
         
            if($tracker)
            {
                $tracker->end_time   = $request->has('time') ?  date("Y-m-d H:i:s",strtotime($request->input('time'))) : date("Y-m-d H:i:s");
                $tracker->is_active  = 0;
                $tracker->total_time = Utility::diffance_to_time($tracker->start_time, $tracker->end_time);
                $tracker->save();
                return $this->success( $tracker,'Stop time successfully.');
            }
        }
         
    }



     public function uploadImage(Request $request){
         
        $user = auth()->user();
       
        $image_base64 = base64_decode($request->img);
        $file =$request->imgName;
        if($request->has('tracker_id') && !empty($request->tracker_id)){
            $app_path = storage_path('uploads/traker_images/').$request->tracker_id.'/';
            if (!file_exists($app_path)) {
                mkdir($app_path, 0777, true);
            }

        }else{
            $app_path = storage_path('uploads/traker_images/');
            if (is_dir($app_path)) {
                mkdir($app_path, 0777, true);
            }
        }
        $file_name =  $app_path.$file;
        file_put_contents( $file_name, $image_base64);
        $new = new TrackPhoto();
        $new->track_id = $request->tracker_id;
        $new->user_id  = $user->id;
        $new->workspace_id =0;
        $new->img_path  = 'uploads/traker_images/'.$request->tracker_id.'/'.$file;
        $new->time  = $request->time;
        $new->status  = 1;
        $new->save();
        return $this->success( [],'Uploaded successfully.');
    }




 }