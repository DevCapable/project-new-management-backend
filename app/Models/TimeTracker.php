<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeTracker extends Model
{
    use HasFactory;

        protected $fillable = [
        'project_id',
        'task_id',
        'is_active',
        'workspace_id',
        'name',
        'start_time',
        'end_time',
        'total_time',
    ];
    
         protected $appends  = array(
        'project_name',
        'project_task',
        'project_workspace',
    );



  public function getProjectNameAttribute($value)
    {
        $project = Project::select('id', 'name')->where('id', $this->project_id)->first();

        return $project ? $project->name : '';
    }

    public function getProjectTaskAttribute($value)
    {
        $task = Task::select('id', 'title')->where('id', $this->task_id)->first();

        return $task ? $task->title : '';
    }

    public function getProjectWorkspaceAttribute($value)
    {
        $workspace = Workspace::select('id', 'name')->where('id', $this->workspace_id)->first();

       

        return $workspace ? $workspace->name : '';
    }




}



