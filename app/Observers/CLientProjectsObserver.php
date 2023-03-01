<?php

namespace App\Observers;


use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\Stage;
use App\Models\Task;
use App\Models\Utility;
use Illuminate\Support\Facades\Auth;

class CLientProjectsObserver
{
    /**
     * @var mixed
     */
    private $repository;

    public function __construct()
    {
        $this->repository = app('App\Repo\ClientTaskRepositoryInterface');
    }

//    public function creating($model)
//    {
//        $model->app_no = generate_code('client-');
//    }

    public function saved($model)
    {
//        $task = \Request::all();
        $task = \Request::only('title','start_date','due_date','task_id','description','project_id');

//        dd($task['task_id']);

//        dd($task);
//        if (isset($task['status'])){
//            $project = Project::where('project_id',$task['project_id'])->first();
//            $project->status ='UnderReview';
//            $project->save();
//        }else{
        if (!empty($task) && isset($task['title'])&& !isset($task['status'])) {
//            $objUser = Auth::user();

            for ($count = 0; $count < count($task['title']); $count++) {
                $currentWorkspace = Utility::getWorkspaceBySlug($slug = 'en');
                $user = $currentWorkspace->id;
                $new_task = new Task();
                $new_task->title = $task['title'][$count];
                $new_task->start_date = format_date($task['start_date'][$count], 'd/m/y');

                $new_task->due_date = format_date($task['due_date'][$count], 'd/m/y');

                $new_task->priority = "HIGH";
                $new_task->task_id = $task['task_id'];

                $new_task->description = $task['description'][$count];
                $new_task->project_id = $task['project_id'];
                $objUser = Auth::user();
                if ($objUser->type == 'user') {
//                    $new_task->assign_to = $task['users_list'];
                    if (isset($task['users_list'])){
                        $new_task->assign_to = implode(" ", $task['users_list']);
                        if (isset($new_task->assign_to)) {
                            ActivityLog::create(
                                [
                                    'user_id' => \Auth::user()->id,
                                    'user_type' => get_class(\Auth::user()),
                                    'project_id' => $task['project_id'],
                                    'log_type' => 'Assign Task',
                                    'remark' => json_encode(['title' => $task['title'][$count]]),
                                ]
                            );
                        }
                    }

                }

                $new_task->save();


                $stage = Stage::where('workspace_id', '=', $currentWorkspace->id)->orderBy('order')->first();
                if ($stage) {
                    $task['milestone_id'] = !empty($request->milestone_id) ? $request->milestone_id : 0;
                    $task['status'] = $stage->id;

                    ActivityLog::create(
                        [
                            'user_id' => \Auth::user()->id,
                            'user_type' => get_class(\Auth::user()),
                            'project_id' => $task['project_id'],
                            'log_type' => 'Create Task',
                            'remark' => json_encode(['title' => $task['title'][$count]]),
                        ]
                    );
                }

                $settings = Utility::getPaymentSetting($user);
                if (isset($settings['task_notificaation']) && $settings['task_notificaation'] == 1) {
                    $msg = $request->title . " of " . $task->name . " created by the " . \Auth::user()->name . '.';
                    Utility::send_slack_msg($msg, $user);
                }
                if (isset($settings['telegram_task_notificaation']) && $settings['telegram_task_notificaation'] == 1) {
                    $msg = $request->title . " of " . $task->name . " created by " . \Auth::user()->name . '.';
                    Utility::send_telegram_msg($msg, $user);
                }


            }
        }
//        }


//        function _assignUserNotification(){
//
//        }

    }

//    public function deleting($model)
//    {
//        $model->documentFiles()->delete();
//    }
}
