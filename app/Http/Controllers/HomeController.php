<?php

namespace App\Http\Controllers;

use App\Models\ClientProject;
use App\Models\Stage;
use App\Models\Task;
use App\Models\User;
use App\Models\UserProject;
use App\Models\UserWorkspace;
use App\Models\Utility;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function landingPage()
    {
        if (!file_exists(storage_path() . "/installed")) {
            header('location:install');
            die;
        }

        if (env('DISPLAY_LANDING') == 'on' || env('DISPLAY_LANDING') == '') {

            return view('layouts.landing');
        } else {
            return redirect('login');
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($slug = '')
    {
        try {
            $userObj = Auth::user();
            if ($userObj->type == 'admin') {
                $users = User::where('type', '!=', 'admin')->get();
                $currentWorkspace = Utility::getAdminWorkspaceBySlug($slug);
                return view('users.index', compact('users','currentWorkspace'));
            }
            $currentWorkspace = Utility::getWorkspaceBySlug($slug);
            if ($currentWorkspace) {
                $doneStage = Stage::where('workspace_id', '=', $currentWorkspace->id)->where('complete', '=', '1')->first();
                if ($userObj->getGuard() == 'client') {

                    $totalProject = ClientProject::join("projects", "projects.project_id", "=", "client_projects.project_id")->where("client_id", "=", $userObj->id)->where('projects.workspace', '=', $currentWorkspace->id)->count();
                    $totalBugs = ClientProject::join("bug_reports", "bug_reports.project_id", "=", "client_projects.project_id")->join("projects", "projects.project_id", "=", "client_projects.project_id")->where('projects.workspace', '=', $currentWorkspace->id)->count();
                    $totalTask = ClientProject::join("tasks", "tasks.project_id", "=", "client_projects.project_id")->join("projects", "projects.project_id", "=", "client_projects.project_id")->where('projects.workspace', '=', $currentWorkspace->id)->where("client_id", "=", $userObj->id)->count();
                    $completeTask = ClientProject::join("tasks", "tasks.project_id", "=", "client_projects.project_id")->join("projects", "projects.project_id", "=", "client_projects.project_id")->where('projects.workspace', '=', $currentWorkspace->id)->where("client_id", "=", $userObj->id)->where('tasks.status', '=', $doneStage->id)->count();
                    $tasks = Task::select([
                        'tasks.*',
                        'stages.name as status',
                        'stages.complete',
                    ])->join("client_projects", "tasks.project_id", "=", "client_projects.project_id")
                        ->join("projects", "projects.project_id", "=", "client_projects.project_id")
                        ->join("stages", "stages.id", "=", "tasks.status")->where('projects.workspace', '=', $currentWorkspace->id)
                        ->where("client_id", "=", $userObj->id)->orderBy('tasks.id', 'desc')->limit(5)->get();

                    $projects = ClientProject::join("projects", "projects.project_id", "=", "client_projects.project_id")->where("client_id", "=", $userObj->id)->where('projects.workspace', '=', $currentWorkspace->id)->get();

                    $totalMembers = UserWorkspace::where('workspace_id', '=', $currentWorkspace->id)->count();
                    $projectProcess = ClientProject::join("projects", "projects.project_id", "=", "client_projects.project_id")->where('projects.workspace', '=', $currentWorkspace->id)->where("client_id", "=", $userObj->id)->groupBy('projects.status')->selectRaw('count(projects.id) as count, projects.status')->pluck('count', 'projects.status');

                    $arrProcessPer = [];
                    $arrProcessLabel = [];
                    foreach ($projectProcess as $lable => $process) {
                        $arrProcessLabel[] = $lable;
                        if ($totalProject == 0) {
                            $arrProcessPer[] = 0.00;
                        } else {
                            $arrProcessPer[] = round(($process * 100) / $totalProject, 2);
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
                    return view('home', compact('currentWorkspace',
                        'totalProject',
                        'totalBugs',
                        'totalTask',
                        'totalMembers',
                        'arrProcessLabel',
                        'arrProcessPer',
                        'arrProcessClass',
                        'completeTask',
                        'tasks',
                        'chartData',
                    ));

                } else {
                    $totalProject = UserProject::join("projects", "projects.project_id", "=", "user_projects.project_id")->where("user_id", "=", $userObj->id)->where('projects.workspace', '=', $currentWorkspace->id)->count();
                    $totalProjectUnderReview = UserProject::join("projects", "projects.project_id", "=", "user_projects.project_id")
                        ->where("user_id", "=", $userObj->id)
                        ->where('projects.workspace', '=', $currentWorkspace->id)
                        ->where('projects.status', '=',get_projects_status(2))
                        ->count();

                    if ($currentWorkspace->permission == 'Owner') {
                        $totalBugs = UserProject::join("bug_reports", "bug_reports.project_id", "=", "user_projects.project_id")->join("projects", "projects.project_id", "=", "user_projects.project_id")->where("user_id", "=", $userObj->id)->where('projects.workspace', '=', $currentWorkspace->id)->count();
                        $totalTask = UserProject::join("tasks", "tasks.project_id", "=", "user_projects.project_id")->join("projects", "projects.project_id", "=", "user_projects.project_id")->where("user_id", "=", $userObj->id)->where('projects.workspace', '=', $currentWorkspace->id)->count();
                        $completeTask = UserProject::join("tasks", "tasks.project_id", "=", "user_projects.project_id")->join("projects", "projects.project_id", "=", "user_projects.project_id")->where("user_id", "=", $userObj->id)->where('projects.workspace', '=', $currentWorkspace->id)->where('tasks.status', '=', $doneStage->id)->count();
                        $tasks = Task::select([
                            'tasks.*',
                            'stages.name as status',
                            'stages.complete',
                        ])->join("user_projects", "tasks.project_id", "=", "user_projects.project_id")
                            ->join("projects", "projects.project_id", "=", "user_projects.project_id")
                            ->join("stages", "stages.id", "=", "tasks.status")->where("user_id", "=", $userObj->id)
                            ->where('projects.workspace', '=', $currentWorkspace->id)->orderBy('tasks.id', 'desc')->limit(5)->get();
                    } else {
                        $totalBugs = UserProject::join("bug_reports", "bug_reports.project_id", "=", "user_projects.project_id")->join("projects", "projects.project_id", "=", "user_projects.project_id")->where("user_id", "=", $userObj->id)->where('projects.workspace', '=', $currentWorkspace->id)->where('bug_reports.assign_to', '=', $userObj->id)->count();
                        $totalTask = UserProject::join("tasks", "tasks.project_id", "=", "user_projects.project_id")->join("projects", "projects.project_id", "=", "user_projects.project_id")->where("user_id", "=", $userObj->id)->where('projects.workspace', '=', $currentWorkspace->id)->whereRaw("find_in_set('" . $userObj->id . "',tasks.assign_to)")->count();
                        $completeTask = UserProject::join("tasks", "tasks.project_id", "=", "user_projects.project_id")->join("projects", "projects.project_id", "=", "user_projects.project_id")->where("user_id", "=", $userObj->id)->where('projects.workspace', '=', $currentWorkspace->id)->whereRaw("find_in_set('" . $userObj->id . "',tasks.assign_to)")->where('tasks.status', '=', $doneStage->id)->count();
                        $tasks = Task::select([
                            'tasks.*',
                            'stages.name as status',
                            'stages.complete',
                        ])->join("user_projects", "tasks.project_id", "=", "user_projects.project_id")
                            ->join("projects", "projects.project_id", "=", "user_projects.project_id")
                            ->join("stages", "stages.id", "=", "tasks.status")->where("user_id", "=", $userObj->id)
                            ->where('projects.workspace', '=', $currentWorkspace->id)->whereRaw("find_in_set('" . $userObj->id . "',tasks.assign_to)")
                            ->orderBy('tasks.id', 'desc')->limit(5)->get()->paginate(10);
                    }

                    $totalMembers = UserWorkspace::where('workspace_id', '=', $currentWorkspace->id)->count();

                    $projectProcess = UserProject::join("projects", "projects.project_id", "=", "user_projects.project_id")->where("user_id", "=", $userObj->id)->where('projects.workspace', '=', $currentWorkspace->id)->groupBy('projects.status')->selectRaw('count(projects.id) as count, projects.status')->pluck('count', 'projects.status');
                    $arrProcessPer = [];
                    $arrProcessLabel = [];
                    foreach ($projectProcess as $lable => $process) {
                        $arrProcessLabel[] = $lable;
                        if ($totalProject == 0) {
                            $arrProcessPer[] = 0.00;
                        } else {
                            $arrProcessPer[] = round(($process * 100) / $totalProject, 2);
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

                    return view('home', compact(
                        'currentWorkspace',
                        'totalProject',
                        'totalBugs',
                        'totalTask',
                        'totalMembers',
                        'arrProcessLabel',
                        'arrProcessPer',
                        'arrProcessClass',
                        'completeTask',
                        'tasks',
                        'chartData',
                        'totalProjectUnderReview'
                    ));
                }
            } else {
                return view('home', compact('currentWorkspace'));
            }

        } catch (\Exception $e) {
            throw $e;
        }
    }

}
