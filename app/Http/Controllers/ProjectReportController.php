<?php

namespace App\Http\Controllers;

use App\Models\project_report;
use App\Models\UserProject;
use App\Models\UserWorkspace;
use App\Models\Workspace;
use App\Models\Utility;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\Task;
use App\Models\Timesheet;
use App\Models\Stage;
use App\Models\User;
use App\Models\Client;
use App\Exports\task_reportExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use ZipStream\Exception;

class ProjectReportController extends Controller
{

    public function index($slug)
    {
        $objUser = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

        if ($objUser->getGuard() == 'client') {
            $projects = Project::select('projects.*')->join('client_projects', 'projects.project_id', '=', 'client_projects.project_id')->where('client_projects.client_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->get();
        } else {
            $projects = Project::select('projects.*')->join('user_projects', 'projects.project_id', '=', 'user_projects.project_id')->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->get();
        }

        $stages = Stage::where('workspace_id', '=', $currentWorkspace->id)->orderBy('order')->get();
        $users = User::select('users.*')->join('user_workspaces', 'user_workspaces.user_id', '=', 'users.id')->where('user_workspaces.workspace_id', '=', $currentWorkspace->id)->get();



        return view('project_report.index', compact('currentWorkspace','stages','users' ,'projects'));
    }


    public function create()
    {

    }


    public function store(Request $request)
    {


    }


    public function show(Request $request ,$slug,$projectID)
    {
        try {
            $objUser = Auth::user();
            $currentWorkspace = Utility::getWorkspaceBySlug($slug);

            if ($objUser->getGuard() == 'client') {
                $project = Project::select('projects.*')->join('client_projects', 'projects.project_id', '=', 'client_projects.project_id')->where('client_projects.client_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->where('projects.project_id', '=', $projectID)->first();

            } else {
                $project = Project::select('projects.*')->join('user_projects', 'projects.project_id', '=', 'user_projects.project_id')->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->where('projects.project_id', '=', $projectID)->first();
            }
            if ($project) {
                $chartData = $this->getProjectChart(
                    [
                        'workspace_id' => $currentWorkspace->id,
                        'project_id' => $projectID,
                        'duration' => 'week',
                    ]
                );
                $daysleft = round((((strtotime($project->end_date) - strtotime(date('Y-m-d'))) / 24) / 60) / 60);

                $project_status_task = stage::join("tasks", "tasks.status", "=", "stages.id")->where("workspace_id", "=", $currentWorkspace->id)->where('tasks.project_id', '=', $projectID)->groupBy('name')->selectRaw('count(tasks.id) as count, name')->pluck('count', 'name');

                $totaltask = Task::where('project_id',$projectID)->count();

                $arrProcessPer_status_task = [];
                $arrProcess_Label_status_tasks = [];
                foreach ($project_status_task as $lables => $percentage_stage) {
                    $arrProcess_Label_status_tasks[] = $lables;
                    if ($totaltask == 0) {
                        $arrProcessPer_status_task[] = 0.00;
                    } else {
                        $arrProcessPer_status_task[] = round(($percentage_stage * 100) / $totaltask, 2);
                    }
                }

                $project_priority_task = Task::where('project_id',$projectID)->groupBy('priority')->selectRaw('count(id) as count, priority')->pluck('count', 'priority');

                $arrProcessPer_priority = [];
                $arrProcess_Label_priority = [];
                foreach ($project_priority_task as $lable => $process) {
                    $arrProcess_Label_priority[] = $lable;
                    if ($totaltask == 0) {
                        $arrProcessPer_priority[] = 0.00;
                    } else {
                        $arrProcessPer_priority[] = round(($process * 100) / $totaltask, 2);
                    }
                }

                $arrProcessClass = [
                    'text-success',
                    'text-primary',
                    'text-danger',
                ];

                $chartData = app('App\Http\Controllers\ProjectController')->getProjectChart([
                    'workspace_id' => $currentWorkspace->id,
                    'duration' => 'week',
                ]);


                $stages = Stage::where('workspace_id', '=', $currentWorkspace->id)->orderBy('order')->get();
                $users = User::select('users.*')->join('user_workspaces', 'user_workspaces.user_id', '=', 'users.id')->where('user_workspaces.workspace_id', '=', $currentWorkspace->id)->get();

                $milestones = Milestone::where('project_id' ,$projectID)->get();





//Logged Hours
                $logged_hour_chart = 0;
                $total_hour = 0;
                $logged_hour = 0;


                $tasks = Task::where('project_id',$projectID)->get();

                $data = [];
                foreach ($tasks as $task) {
                    $timesheets_task = Timesheet::where('task_id',$task->id)->where('project_id',$projectID)->get();


                    foreach($timesheets_task as $timesheet){

                        $date_time = $timesheet->time;
                        $hours =  date('H', strtotime($date_time));
                        $minutes =  date('i', strtotime($date_time));
                        $total_hour = $hours + ($minutes/60) ;
                        $logged_hour += $total_hour ;

                        $logged_hour_chart = number_format($logged_hour, 2, '.', '');
                    }
                }



                //Estimated Hours

                $esti_logged_hour_chart = 0;
                $esti_total_hour = 0;
                $esti_logged_hour = 0;
                $hourdiff = 0;

                foreach ($tasks as $task) {
                    $start_date = $task->start_date;
                    $end_date = $task->due_date;

                    $hourdiff = round((strtotime($end_date) - strtotime($start_date))/3600, 1);



                    // $interval = $end_date->diff($start_date);


                    // $esti_hours =  date('H', strtotime($interval));
                    // $esti_minutes =  date('i', strtotime($interval));
                    // $esti_total_hour = $esti_hours + ($esti_minutes/60) ;
                    $esti_logged_hour += $hourdiff ;
                    $esti_logged_hour_chart = number_format($esti_logged_hour, 2, '.', '');

                }


                return view('project_report.show', compact('currentWorkspace', 'project', 'chartData', 'daysleft','arrProcessPer_priority','arrProcess_Label_priority','arrProcessClass','stages','users','milestones','arrProcess_Label_status_tasks','arrProcessPer_status_task','logged_hour_chart','esti_logged_hour_chart'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }catch (\Exception $e){
            throw $e;
        }


    }


    public function edit(project_report $project_report)
    {

    }


    public function update(Request $request, project_report $project_report)
    {

    }


    public function destroy(project_report $project_report)
    {

    }


    public function ajax_data(Request $request ,$slug)
    {

      $objUser = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        if ($objUser->getGuard() == 'client') {
             $projects = Project::select('projects.*')->join('client_projects', 'projects.project_id', '=', 'client_projects.project_id')->where('client_projects.client_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id);
        } else {
             $projects = Project::select('projects.*')->join('user_projects', 'projects.project_id', '=', 'user_projects.project_id')->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id);
        }

         if ($request->all_users) {
            unset($projects);
             $projects = Project::select('projects.*')->join('user_projects', 'projects.project_id', '=', 'user_projects.project_id')->where('user_projects.user_id', '=', $request->all_users)->where('projects.workspace', '=', $currentWorkspace->id);
        }

        if ($request->status) {
            $projects->where('status', '=', $request->status);
        }

        if ($request->start_date) {

             $projects->where('start_date', '=', $request->start_date);

        }

         if ($request->end_date) {

             $projects->where('end_date', '=', $request->end_date);

        }

        $client_keyword = Auth::user()->getGuard() == 'client' ? 'client.' : '';
        $projects = $projects->get();
        $data = [];
        foreach($projects as $project) {
            $tmp = [];

            $tmp['id'] = $project->id;
            $tmp['name'] = $project->name;
            $tmp['start_date'] = $project->start_date;
             $tmp['end_date'] = $project->end_date;

             $tmp['members'] = '<div class="user-group mx-2">';

                     foreach($project->users as $user){

                         $path = asset(\Storage::url('avatars/' . $user->avatar));

                           $avatar = $user->avatar ? 'src="'.$path.'"':'avatar="'.$user->name.'"';



                        if($user->pivot->is_active){
                                           $tmp['members'] .=
                                          '

                                                 <a href="#" class="img_group" data-toggle="tooltip" data-placement="top" title=" '.$user->name.'">
                                                    <img alt="'.$user->name.'" '.$avatar.'/>



                                                </a> ';


                                            }
                                        }
                                      $tmp['members'] .=   '</div>';
                    $percentage = $project->project_progress();

           $tmp['Progress'] =
                '<div class="progress_wrapper">
                                       <div class="progress">
                                          <div class="progress-bar" role="progressbar"
                                           style="width:'.$percentage["percentage"].'"
                                             aria-valuenow="55" aria-valuemin="0" aria-valuemax="100">
                                             </div>
                                       </div>
                                       <div class="progress_labels">
                                          <div class="total_progress">

                                             <strong>'.$percentage["percentage"].'</strong>
                                          </div>

                                       </div>
                                    </div>';


            $tmp['status'] =  get_projects_status_label($project->status);
            if (Auth::user()->getGuard() != 'client') {

                $tmp['action'] = '
                <a  class="action-btn btn-warning  btn btn-sm d-inline-flex align-items-center" data-toggle="popover"  title="' . __('view Project') . '" data-size="lg" data-title="' . __('show') . '" href="' . route(
                    $client_keyword.'project_report.show', [
                        $currentWorkspace->slug,
                        $project->id,
                    ]
                ) . '"><i class="ti ti-eye"></i></a>



                <a href="#" class="action-btn btn-info  btn btn-sm d-inline-flex align-items-center" data-toggle="popover"  title="' . __('Edit Project') . '" data-ajax-popup="true" data-size="lg" data-title="' . __('Edit') . '" data-url="' . route(
                    'projects.edit', [
                        $currentWorkspace->slug,
                        $project->id,
                    ]
                ) . '"><i class="ti ti-pencil"></i></a>';
            }else
            {

                 $tmp['action'] = '
                <a  class="action-btn btn-warning  btn btn-sm d-inline-flex align-items-center" data-toggle="popover"  title="' . __('view Project') . '" data-size="lg" data-title="' . __('show') . '" href="' . route(
                    'project-report-show', [
                        $currentWorkspace->slug,
                        $project->project_id,
                    ]
                ) . '"><i class="ti ti-eye"></i></a>';

            }

            $data[] = array_values($tmp);

        }

        return response()->json(['data' => $data], 200);
    }


        public function getProjectChart($arrParam)
    {
        $arrDuration = [];
        if ($arrParam['duration'] && $arrParam['duration'] == 'week') {
            $previous_week = Utility::getFirstSeventhWeekDay(-1);
            foreach ($previous_week['datePeriod'] as $dateObject) {
                $arrDuration[$dateObject->format('Y-m-d')] = $dateObject->format('D');
            }
        }

        $arrTask = [
            'label' => [],
            'color' => [],
        ];
        $stages = Stage::where('workspace_id', '=', $arrParam['workspace_id'])->orderBy('order');

        foreach ($arrDuration as $date => $label) {
            $objProject = Task::select('status', DB::raw('count(*) as total'))->whereDate('updated_at', '=', $date)->groupBy('status');

            if (isset($arrParam['project_id'])) {
                $objProject->where('project_id', '=', $arrParam['project_id']);
            }
            if (isset($arrParam['workspace_id'])) {
                $objProject->whereIn(
                    'project_id', function ($query) use ($arrParam) {
                        $query->select('id')->from('projects')->where('workspace', '=', $arrParam['workspace_id']);
                    }
                );
            }
            $data = $objProject->pluck('total', 'status')->all();

            foreach ($stages->pluck('name', 'id')->toArray() as $id => $stage) {
                $arrTask[$id][] = isset($data[$id]) ? $data[$id] : 0;
            }
            $arrTask['label'][] = __($label);
        }
        $arrTask['stages'] = $stages->pluck('name', 'id')->toArray();
        $arrTask['color'] = $stages->pluck('color')->toArray();

        return $arrTask;
    }


   public function ajax_tasks_report(Request $request ,$slug,$id)
    {
        $userObj = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

            $tasks = Task::select(
                [
                    'tasks.*',
                    'stages.name as stage',
                    'stages.complete',
                ]
            )->where('project_id',$id)->join("stages", "stages.id", "=", "tasks.status");


        if ($request->assign_to) {
            $tasks->whereRaw("find_in_set('" . $request->assign_to . "',assign_to)");
        }

        if ($request->priority) {
            $tasks->where('priority', '=', $request->priority);
        }

         if ($request->milestone_id) {
            $tasks->where('milestone_id', '=', $request->milestone_id);
        }
        if ($request->status) {
            $tasks->where('tasks.status', '=', $request->status);
        }

         if ($request->start_date) {
            $tasks->where('start_date', '=', $request->status);
        }

        if ($request->due_date) {
            $tasks->where('due_date', '=', $request->due_date);
        }


        $tasks = $tasks->get();
        $data = [];
        foreach ($tasks as $task) {
            $timesheets_task = Timesheet::where('project_id',$id)->where('task_id' ,$task->id)->get();

            $hour_format_number = 0;
            $total_hour = 0;
            $logged_hour = 0;
        foreach($timesheets_task as $timesheet){

          $date_time = $timesheet->time;
          $hours =  date('H', strtotime($date_time));
          $minutes =  date('i', strtotime($date_time));
          $total_hour = $hours + ($minutes/60) ;
          $logged_hour += $total_hour ;
          $hour_format_number = number_format($logged_hour, 2, '.', '');
        }


            $tmp = [];
            $tmp['title'] = '<a href="' . route(
                'projects.task.board', [
                    $currentWorkspace->slug,
                    $task->project_id,
                ]
            ) . '" class="text-body">' . $task->title . '</a>';

            $tmp['milestone'] = ($milestone = $task->milestone()) ? $milestone->title : '';
             $start_date = '<span class="text-body">' . date('Y-m-d', strtotime($task->start_date)) . '</span> ';

            $due_date = '<span class="text-' . ($task->due_date < date('Y-m-d') ? 'danger' : 'success') . '">' . date('Y-m-d', strtotime($task->due_date)) . '</span> ';
            $tmp['start_date'] = $start_date;
            $tmp['due_date'] = $due_date;

            if ($currentWorkspace->permission == 'Owner' || Auth::user()->getGuard() == 'client') {
                $tmp['user_name'] = "";
                foreach ($task->users() as $user) {
                    if (isset($user) && $user) {
                        $tmp['user_name'] .= '<span class="badge bg-secondary p-2 px-3 rounded">' . $user->name . '</span> ';
                    }
                }
            }
             $tmp['logged_hours'] = $hour_format_number;



            if ($task->priority == "High") {
                $tmp['priority'] = '<span class="priority_badge badge bg-danger p-2 px-3 rounded">' . __('High') . '</span>';
            } elseif ($task->priority == "Medium") {
                $tmp['priority'] = '<span class="priority_badge badge bg-info p-2 px-3 rounded">' . __('Medium') . '</span>';
            } else {
                $tmp['priority'] = '<span class="priority_badge badge bg-success p-2 px-3 rounded">' . __('Low') . '</span>';
            }

             if ($task->complete == 1) {
                $tmp['status'] = '<span class="status_badge badge bg-success p-2 px-3 rounded">' . __($task->stage) . '</span>';
            } else {
                $tmp['status'] = '<span class="status_badge badge bg-primary p-2 px-3 rounded">' . __($task->stage) . '</span>';
            }


            $data[] = array_values($tmp);

        }

        return response()->json(['data' => $data], 200);
}


public function export($id)
    {

        $name = 'task_report_' . date('Y-m-d i:h:s');
        $data = Excel::download(new task_reportExport($id), $name . '.xlsx');

        return $data;
    }







}

