<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\Utility;
use Illuminate\Support\Facades\Auth;

class CalenderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index($slug, $project_id = NULL)
    {
        $objUser          = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

        if($objUser->getGuard() == 'client')
        {
            $tasks    = Task::select('tasks.*')->join('projects', 'projects.id', '=', 'tasks.project_id')->join('client_projects', 'projects.id', '=', 'client_projects.project_id')->where('client_projects.client_id', '=', $objUser->id)->where('client_projects.permission', 'LIKE', '%show task%')->where('projects.workspace', '=', $currentWorkspace->id);
            $projects = Project::select('projects.*')->join('client_projects', 'projects.id', '=', 'client_projects.project_id')->where('client_projects.client_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->get();
        }
        elseif($currentWorkspace->permission == 'Owner')
        {
            $tasks    = Task::select('tasks.*')->join('projects', 'projects.id', '=', 'tasks.project_id')->where('projects.workspace', '=', $currentWorkspace->id);
            $projects = Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->get();
        }
        else
        {
            $tasks    = Task::select('tasks.*')->join('projects', 'projects.id', '=', 'tasks.project_id')->where('projects.workspace', '=', $currentWorkspace->id)->whereRaw("find_in_set('" . Auth::user()->id . "',tasks.assign_to)");
            $projects = Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->get();
        }


   if($objUser->getGuard() == 'client')
        {
            $events    = Task::select('tasks.*')->join('projects', 'projects.id', '=', 'tasks.project_id')->join('client_projects', 'projects.id', '=', 'client_projects.project_id')->where('client_projects.client_id', '=', $objUser->id)->where('client_projects.permission', 'LIKE', '%show task%')->where('projects.workspace', '=', $currentWorkspace->id)->limit(8);
          
        }
        elseif($currentWorkspace->permission == 'Owner')
        {
            $events    = Task::select('tasks.*')->join('projects', 'projects.id', '=', 'tasks.project_id')->where('projects.workspace', '=', $currentWorkspace->id)->limit(8);
            
        }
        else
        {
            $events    = Task::select('tasks.*')->join('projects', 'projects.id', '=', 'tasks.project_id')->where('projects.workspace', '=', $currentWorkspace->id)->whereRaw("find_in_set('" . Auth::user()->id . "',tasks.assign_to)")->limit(8);
         
        }

        if($project_id)
        {
            $tasks->where('tasks.project_id', '=', $project_id);
               $events->where('tasks.project_id', '=', $project_id);
        }
        $tasks = $tasks->get();
          $events = $events->get();

        $arrayJson = [];
        foreach($tasks as $task)
        {
            $arrayJson[] = [
                "title" => $task->title,
                "start" => $task->start_date,
                "end" => $task->due_date,
                "url" => (($objUser->getGuard() != 'client') ? route(
                    'tasks.show', [
                                    $currentWorkspace->slug,
                                    $task->project_id,
                                    $task->id,
                                ]
                ) : route(
                    'client.tasks.show', [
                                    $currentWorkspace->slug,
                                    $task->project_id,
                                    $task->id,
                                ]
                )),
                "task_id" => $task->id,
                "task_url" => (($objUser->getGuard() != 'client') ? route(
                    'tasks.drag.event', [
                                          $currentWorkspace->slug,
                                          $task->project_id,
                                          $task->id,
                                      ]
                ) : ''),
                "className" => (($task->priority == 'Medium') ? 'event-warning border-warning' : (($task->priority == 'High' || $task->priority == 'Low') ? 'event-danger border-danger' : 'event-info border-info')),
                "allDay" => true,
            ];
        }

        return view('calendar.index', compact('currentWorkspace', 'arrayJson', 'projects', 'project_id','events'));
    }
}
