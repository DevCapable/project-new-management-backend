<?php

namespace App\Http\Controllers;

use App\Exports\projectsExport;
use App\Http\Requests\Auth\taskFormRequest;
use App\Http\Requests\Auth\taskUpdateFormRequest;
use App\Imports\projectsImport;
use App\Models\ActivityLog;
use App\Models\BugComment;
use App\Models\BugFile;
use App\Models\BugReport;
use App\Models\BugStage;
use App\Models\Client;
use App\Models\ClientProject;
use App\Models\ClientWorkspace;
use App\Models\Comment;
use App\Models\GenericDocument;
use App\Models\Mail\SendClientNewProjectSubmissionNotification;
use App\Models\Mail\SendClientProjectPaymentNotification;
use App\Models\Mail\SendClientProjectRecieptNotification;
use App\Models\Mail\SendClientWorkspaceInvication;
use App\Models\Mail\SendInvication;
use App\Models\Mail\passwordReset;
use App\Models\Mail\SendNewProjectSubmissionNotification;
use App\Models\Mail\SendUserNewTaskAssignedNotification;
use App\Models\Mail\SendWorkspaceInvication;
use App\Models\Mail\ShareProjectToClient;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\ProjectFile;
use App\Models\ProjectInvoicePayments;
use App\Models\Stage;
use App\Models\SubTask;
use App\Models\Task;
use App\Models\TaskFile;
use App\Models\Timesheet;
use App\Models\TimeTracker;
use App\Models\User;
use App\Models\UserProject;
use App\Models\UserWorkspace;
use App\Models\Utility;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Jenssegers\Date\Date;
use Maatwebsite\Excel\Facades\Excel;

class ClientProjectController extends Controller
{

    public function index($slug)
    {
        $objUser = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
//        dd($currentWorkspace->id);

        $projects = Project::select('projects.*')->join('client_projects', 'projects.project_id', '=', 'client_projects.project_id')->where('client_projects.client_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->get();

        return view('projects.index', compact('currentWorkspace', 'projects'));
    }
    public function projectUnderReview($slug)
    {
        $objUser = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
//        dd($currentWorkspace->id);

        $projects = Project::select('projects.*')->join('client_projects', 'projects.project_id', '=', 'client_projects.project_id')
        ->where('client_projects.client_id', '=', $objUser->id)
            ->where('projects.status','=',get_projects_status(2))
            ->where('projects.workspace', '=', $currentWorkspace->id)->get();
        return view('projects.index', compact('currentWorkspace', 'projects'));
    }

    public function tracker($slug, $id)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $treckers = TimeTracker::where('project_id', $id)->get();
        $project = Project::where('id', $id)->first();

        if (isset($project) && $project != null) {
            return view('clients.projects.tracker', compact('currentWorkspace', 'treckers', 'id', 'project'));
        } else {
            return redirect()->back()->with('error', __('Tracker Not Found.'));
        }
    }

    public function store($slug, Request $request)
    {
        $objUser = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $check_project = Project::where('project_id', $request->project_id)->first();
        if ($request->action == 'project_page') {
            return redirect()->route('client-projects-index',[$currentWorkspace->slug,$check_project->project_id])->with(['success'=> __('Project not submitted yet, you can always go back to submit')]);
        }
        if ($request->action == 'submit') {
            $this->submit_project($request, $check_project,$currentWorkspace);
        } else {
            $user = $currentWorkspace->id;
            $request->validate(['project_name' => 'required']);
            $post = $request->only('project_name', 'project_description', 'budget', 'project_id', 'action');
            $new_project = new Project();
            $new_project->start_date = date('Y-m-d');
            $new_project->end_date = date('Y-m-d');

            $new_project->name = $post['name'] = $post['project_name'];
            $new_project->project_id = $post['project_id'];


            $new_project->workspace = $currentWorkspace->id;
            $new_project->created_by = $objUser->id;
            $new_project->description = $post['project_description'];
            $userList = [];
            if (isset($post['users_list'])) {
                $userList = $post['users_list'];
            }
            $userList[] = $objUser->email;
            $userList = array_filter($userList);
            $new_project->status = get_projects_status(1);
            $new_project->save();

            ActivityLog::create(
                [
                    'user_id' => \Auth::user()->id,
                    'user_type' => get_class(\Auth::user()),
                    'project_id' => $post['project_id'],
                    'log_type' => 'Create New Project',
                    'remark' => json_encode(['title' => $post['name'], 'time' => now()]),
                ]
            );
            $objProject = Project::where('project_id', $post['project_id'])->first();

            foreach ($userList as $email) {
                $permission = 'Member';
                $registerUsers = Client::where('email', $email)->first();
                if ($registerUsers) {
                    if ($registerUsers->id == $objUser->id) {
                        $permission = 'Owner';
                    }
                } else {
                    $arrUser = [];
                    $arrUser['name'] = 'No Name';
                    $arrUser['email'] = $email;
                    $password = Str::random(8);
                    $arrUser['password'] = Hash::make($password);
                    $arrUser['currant_workspace'] = $objProject->workspace;
                    $arrUser['verification_code'] = sha1(time());
                    $registerUsers = User::create($arrUser);
                    $registerUsers->password = $password;

                    $assignPlan = $registerUsers->assignPlan(1);

                    try {
                        Mail::to($email)->send(new passwordReset($registerUsers));
                    } catch (\Exception $e) {
                        $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
                    }
                }
                $this->inviteUser($post['action'], $registerUsers, $objProject, $permission);


            }

            $settings = Utility::getPaymentSetting($user);
            if (isset($settings['project_notificaation']) && $settings['project_notificaation'] == 1) {
                $msg = $objProject->name . " created by the " . \Auth::user()->name . '.';

                Utility::send_slack_msg($msg, $user);
            }

            if (isset($settings['telegram_project_notificaation']) && $settings['telegram_project_notificaation'] == 1) {
                $msg = $objProject->name . " created by " . \Auth::user()->name . '.';
                Utility::send_telegram_msg($msg, $user);
            }
                $info = 'You can proceed on adding more tasks and submit';
                return redirect()->route('show-client-project',[$currentWorkspace->slug,$objProject->project_id])->with(['success'=> __('Project Created Successfully!') . ((isset($smtp_error)) ? ' <br> <span class="text-danger">' . $smtp_error . '</span>' : ''),
                'info'=>$info
                ]);
            }
        if ($request->action == 'submit') {
            return redirect()->route('client-projects-index', $currentWorkspace->slug)->with('success', __('Project Submitted Successfully!') . ((isset($smtp_error)) ? ' <br> <span class="text-danger">' . $smtp_error . '</span>' : ''));
        } else {
            $info = 'You can proceed on adding more tasks and submit';
            return redirect()->route('show-client-project',[$currentWorkspace->slug,$check_project->project_id])->with(['success'=> __('Project Created Successfully!') . ((isset($smtp_error)) ? ' <br> <span class="text-danger">' . $smtp_error . '</span>' : ''),
                'info'=>$info
            ]);
        }
    }

    function submit_project($request, $check_project, $currentWorkspace)
    {
        if (isset($request->comment)) {
            Comment::create([
                'comment' => $request->comment,
                'task_id' => $check_project->project_id,
                'user_type' => get_class(\Auth::user()),
            ]);
        }
        ActivityLog::create(
            [
                'user_id' => \Auth::user()->id,
                'user_type' => get_class(\Auth::user()),
                'project_id' => $request->project_id,
                'log_type' => 'Submit New Project',
                'remark' => json_encode(['title' => $check_project->name, 'time' => now()]),
            ]
        );

        if ($check_project) {
            $check_project->status = get_projects_status(2);
            $check_project->save();
            try {
                Mail::to($check_project->createrClient->email)->send(new SendClientNewProjectSubmissionNotification($check_project->createrClient, $check_project));
            } catch (\Exception $e) {
                $smtp_error = __('E-Mail has been not sent due to SMTP configuration or check your internet connection');
                return redirect()->back()->with('error', $smtp_error);
            }
            return redirect()->back()
                ->with('danger', __('Error occurred Please try again and make sure you do not click submit button twice, if error still persisting please contact administrator thanks!') . ((isset($smtp_error)) ? ' <br> <span class="text-danger">' . $smtp_error . '</span>' : ''));
        }
        return true;
    }
    public function export()
    {
        $name = 'projects_' . date('Y-m-d i:h:s');
        $data = Excel::download(new projectsExport(), $name . '.xlsx');

        return $data;
    }

    public function import(Request $request)
    {

        $slug = $request->slug;

        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $objUser = Auth::user();

        $rules = [
            'file' => 'required|mimes:csv,txt',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $customers = (new projectsImport())->toArray(request()->file('file'))[0];

        $totalCustomer = count($customers) - 1;

        $errorArray = [];
        for ($i = 1; $i <= count($customers) - 1; $i++) {
            $customer = $customers[$i];

            $customerData = new Project();

            $customerData->name = $customer[0];
            $customerData->status = $customer[1];
            $customerData->description = $customer[2];
            $customerData->start_date = $customer[3];

            $customerData->end_date = $customer[4];
            $customerData->budget = $customer[5];
            $customerData->workspace = $currentWorkspace->id;

            $customerData->created_by = $objUser->id;

            if (empty($customerData)) {
                $errorArray[] = $customerData;

            } else {
                $customerData->save();
            }

            $Data = new ClientProject();

            $Data->client_id = $objUser->id;
            $Data->project_id = $customerData->id;
            $Data->is_active = "1";

//            dd($Data);
            if (empty($Data)) {
                $errorArray[] = $Data;

            } else {
                $Data->save();
            }

        }
        $errorRecord = [];
        if (empty($errorArray)) {
            $data['status'] = 'success';
            $data['msg'] = __('Record successfully imported');
        } else {
            $data['status'] = 'error';
            $data['msg'] = count($errorArray) . ' ' . __('Record imported fail out of' . ' ' . $totalCustomer . ' ' . 'record');

            foreach ($errorArray as $errorData) {

                $errorRecord[] = implode(',', $errorData);

            }

            \Session::put('errorArray', $errorRecord);
        }

        return redirect()->back()->with($data['status'], $data['msg']);
    }

    public function importFile($slug)
    {
        return view('clients.projects.import', compact("slug"));
    }

    public function inviteUser($action, Client $user, Project $project, $permission)
    {

        $authuser = Auth::user();
        $authusername = Client::where('id', '=', $authuser->id)->first();
        // assign workspace first
        $is_assigned = false;
        foreach ($user->workspace as $workspace) {
            if ($workspace->id == $project->workspace) {
                $is_assigned = true;
            }
        }


        if (!$is_assigned) {
            ClientWorkspace::create(
                [
                    'client_id' => $user->id,
                    'workspace_id' => $project->workspace,
                    'permission' => $permission,
                ]
            );
            if ($action == 'submit') {
                try {
                    Mail::to($user->email)->send(new SendClientNewProjectSubmissionNotification($project->createrClient, $project));
                } catch (\Exception $e) {
//                    throw $e;
                    $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
                }
            }

        }

        // assign project
        $arrData = [];
        $arrData['client_id'] = $user->id;
        $arrData['project_id'] = $project->project_id;
        $is_invited = ClientProject::where($arrData)->first();
        if (!$is_invited) {
            $arrData['permission'] = json_encode(Utility::getAllPermission());
            ClientProject::create($arrData);
            if ($permission != 'Owner') {
                try {

                    $uArr = [
                        'user_name' => $user->name,
                        'app_name' => env('APP_NAME'),
                        'owner_name' => $authusername->name,
                        'project_name' => $project->name,
                        'project_status' => $project->status,
                        'app_url' => env('APP_URL'),
                    ];


                    // Send Email
                    $resp = Utility::sendEmailTemplate('Assign Project', $user->id, $uArr);


                    // Mail::to($user->email)->send(new SendInvication($user, $project));
                } catch (\Exception $e) {
                    $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
                }
                Utility::sendNotification('project_assign', $project->workspaceData, $user->id, $project);
            }
        }
    }

    public function invite(Request $request, $slug, $projectID)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $post = $request->all();
        $userList = $post['users_list'];

        $objProject = Project::find($projectID);

        foreach ($userList as $email) {
            $permission = 'Member';
            $registerUsers = User::where('email', $email)->first();
            if ($registerUsers) {
                $this->inviteUser($registerUsers, $objProject, $permission);
            } else {
                $arrUser = [];
                $arrUser['name'] = 'No Name';
                $arrUser['email'] = $email;
                $password = Str::random(8);
                $arrUser['password'] = Hash::make($password);
                $arrUser['currant_workspace'] = $objProject->workspace;
                $registerUsers = User::create($arrUser);
                $registerUsers->password = $password;

                try {
                    Mail::to($email)->send(new passwordReset($registerUsers));
                } catch (\Exception $e) {
                    $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
                }

                $this->inviteUser($registerUsers, $objProject, $permission);
            }

            ActivityLog::create(
                [
                    'user_id' => \Auth::user()->id,
                    'user_type' => get_class(\Auth::user()),
                    'project_id' => $objProject->id,
                    'log_type' => 'Invite User',
                    'remark' => json_encode(['user_id' => $registerUsers->id]),
                ]
            );
        }

        return redirect()->back()->with('success', __('Users Invited Successfully!') . ((isset($smtp_error)) ? ' <br> <span class="text-danger">' . $smtp_error . '</span>' : ''));
    }

    public function userPermission($slug, $project_id, $user_id)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $project = Project::find($project_id);
        $user = User::find($user_id);
        $permissions = $user->getPermission($project_id);
        if (!$permissions) {
            $permissions = [];
        }

        return view('clients.projects.user_permission', compact('currentWorkspace', 'project', 'user', 'permissions'));
    }

    public function userPermissionStore($slug, $project_id, $user_id, Request $request)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $userProject = UserProject::where('user_id', '=', $user_id)->where('project_id', '=', $project_id)->first();
        $userProject->permission = json_encode($request->permissions);
        $userProject->save();

        return redirect()->back()->with('success', __('Permission Updated Successfully!'));
    }

    public function show($slug, $projectID)
    {
        $objUser = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

            $project = Project::select('projects.*')
                ->join('client_projects', 'projects.project_id', '=', 'client_projects.project_id')
                ->where('client_projects.client_id', '=', $objUser->id)
                ->where('projects.workspace', '=', $currentWorkspace->id)
                ->where('projects.project_id', '=', $projectID)->first();

        $chartData = $this->getProjectChart(
            [
                'workspace_id' => $currentWorkspace->id,
                'project_id' => $projectID,
                'duration' => 'week',
            ]
        );
        $uploadedFiles = GenericDocument::select('generic_documents.*')
            ->join('tasks', 'tasks.task_id', '=', 'generic_documents.type_id')
            ->join('projects', 'projects.project_id', '=', 'tasks.project_id')
            ->where('projects.workspace', '=', $currentWorkspace->id)
            ->where('projects.project_id', '=', $projectID)->get();

        $taskFiles = GenericDocument::select('generic_documents.*')
            ->join('tasks', 'tasks.task_id', '=', 'generic_documents.type_id')
            ->join('projects', 'projects.project_id', '=', 'tasks.project_id')->first();
        $route = 'client-comment';
        $daysleft = round((((strtotime($project->end_date) - strtotime(date('Y-m-d'))) / 24) / 60) / 60);

        $permissions = Auth::user()->getPermission($project->project_id);

        return view('projects.show', compact(
            'currentWorkspace',
            'project',
            'chartData',
            'daysleft',
            'permissions',
            'route',
            'taskFiles',
        'uploadedFiles'));
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
                $objProject->whereIn('project_id', function ($query) use ($arrParam) {
                    $query->select('id')->from('projects')->where('workspace', '=', $arrParam['workspace_id']);
                });
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Project $project
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit($slug, $projectID)
    {
        $objUser = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $project = Project::select('projects.*')->join('client_projects', 'projects.project_id', '=', 'client_projects.project_id')->where('client_projects.client_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->where('projects.project_id', '=', $projectID)->first();

        return view('projects.edit', compact('currentWorkspace', 'project'));
    }

    public function create($slug, $id = null)
    {
        $objUser = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

        if (!empty($id)) {
            $app = Task::select('tasks.*')->join('projects', 'projects.project_id', '=', 'tasks.project_id')->where('projects.created_by', '=', $objUser->id)->get();
            return view('clients.projects.create', compact('currentWorkspace', 'app'));
        }
//        $project = $app = ClientProject::select('client_projects.*')->join('tasks', 'client_projects.id', '=', 'tasks.id')->where('client_projects.client_id', '=', $objUser->id)->get();
        $project = Project::select('projects.*')->join('client_projects', 'projects.project_id', '=', 'client_projects.project_id')->where('client_projects.client_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->first();

//        $project = Project::all('projects');
        $route = 'show-client-project';

        return view('projects.create', compact('currentWorkspace', 'project','route'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Project $project
     *
     * @return \Illuminate\Http\Response
     */
    public function popup($slug, $projectID)
    {
        $objUser = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $project = Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->where('projects.id', '=', $projectID)->first();

        return view('clients.projects.invite', compact('currentWorkspace', 'project'));
    }

    public function userDelete($slug, $project_id, $user_id)
    {
        $objUser = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $project = Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->where('projects.id', '=', $project_id)->first();
        if ($currentWorkspace->permission == 'Owner') {
            if (count($project->user_tasks($user_id)) == 0) {
                UserProject::where('user_id', '=', $user_id)->where('project_id', '=', $project->id)->delete();

                return redirect()->back()->with('success', __('User Deleted Successfully!'));
            } else {
                return redirect()->back()->with('warning', __('Please Remove User From Tasks!'));
            }
        } else {
            return redirect()->route('client-projects-index', $slug)->with('error', __("You can't Delete Project!"));
        }
    }

    public function sharePopup($slug, $projectID)
    {
        $objUser = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $project = Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->where('projects.id', '=', $projectID)->first();

        return view('clients.projects.share', compact('currentWorkspace', 'project'));
    }

    public function clientDelete($slug, $project_id, $client_id)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $project = Project::find($project_id)->first();
        if ($currentWorkspace->permission == 'Owner') {
            ClientProject::where('client_id', '=', $client_id)->where('project_id', '=', $project->id)->delete();

            return redirect()->back()->with('success', __('Client Deleted Successfully!'));
        } else {
            return redirect()->route('client-projects-index', $slug)->with('error', __("You can't Delete Project!"));
        }
    }

    public function share($slug, $projectID, Request $request)
    {

        $authuser = Auth::user();
        $authusername = User::where('id', '=', $authuser->id)->first();
        $project = Project::find($projectID);
        foreach ($request->clients as $client_id) {
            $client = Client::find($client_id);
            $user = Client::find($client_id);

            if (ClientProject::where('client_id', '=', $client_id)->where('project_id', '=', $projectID)->count() == 0) {
                ClientProject::create(
                    [
                        'client_id' => $client_id,
                        'project_id' => $projectID,
                        'permission' => json_encode(Utility::getAllPermission()),
                    ]
                );
            }

            try {
                $uArr = [
                    'user_name' => $client->name,
                    'app_name' => env('APP_NAME'),
                    'owner_name' => $authusername->name,
                    'project_name' => $project->name,
                    'project_status' => $project->status,
                    'app_url' => env('APP_URL'),
                ];


                // Send Email
                $resp = Utility::sendclientEmailTemplate('Assign Project', $user->id, $uArr);


            } catch (\Exception $e) {
                $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
            }

            ActivityLog::create(
                [
                    'user_id' => \Auth::user()->id,
                    'user_type' => get_class(\Auth::user()),
                    'project_id' => $project->id,
                    'log_type' => 'Share with Client',
                    'remark' => json_encode(['client_id' => $client->id]),
                ]
            );

        }

        return redirect()->back()->with('success', __('Project Share Successfully!') . ((isset($smtp_error)) ? ' <br> <span class="text-danger">' . $smtp_error . '</span>' : ''));
    }

    public function update(Request $request, $slug, $projectID)
    {
        $request->validate(
            [
                'name' => 'required',
            ]
        );
        $objUser = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
//        $project = Project::select('projects.*')->join('client_projects', 'projects.id', '=', 'client_projects.project_id')->where('client_projects.client_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->where('projects.id', '=', $projectID)->first();
        $project = Project::select('projects.*')->join('client_projects', 'projects.project_id', '=', 'client_projects.project_id')->where('client_projects.client_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->where('projects.project_id', '=', $projectID)->first();

        $post = $request->only('name', 'description', 'budget', 'start_date', 'end_date', 'action');
        if ($post['action'] == 'submit') {
            $project->status = get_projects_status(2);
            try {
                Mail::to($objUser->email)->send(new SendClientNewProjectSubmissionNotification($objUser,$project));
            } catch (\Exception $e) {
                $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
            }
            ActivityLog::create(
                [
                    'user_id' => \Auth::user()->id,
                    'user_type' => get_class(\Auth::user()),
                    'project_id' => $project->project_id,
                    'log_type' => 'Submit Project',
                    'remark' => json_encode(['title' => $project->name, 'time' => now()]),
                ]
            );
        } else {
            $project->status = get_projects_status(1);
        }
        ActivityLog::create(
            [
                'user_id' => \Auth::user()->id,
                'user_type' => get_class(\Auth::user()),
                'project_id' => $project->project_id,
                'log_type' => 'Update Project',
                'remark' => json_encode(['title' => $project->name, 'time' => now()]),
            ]
        );
        $project->update($post);

//        $project->update($request->all());

        return redirect()->back()->with('success', __('Project Updated Successfully!'));
    }

    public function destroy($slug, $projectID)
    {
        $objUser = Auth::user();
        $project = Project::find($projectID);

        if ($project->created_by == $objUser->id) {
            UserProject::where('project_id', '=', $projectID)->delete();
            ProjectFile::where('project_id', '=', $projectID)->delete();
            $project->delete();

            return redirect()->route('client-projects-index', $slug)->with('success', __('Project Deleted Successfully!'));
        } else {
            return redirect()->route('client-projects-index', $slug)->with('error', __("You can't Delete Project!"));
        }
    }

    public function leave($slug, $projectID)
    {
        $objUser = Auth::user();
        $userProject = Project::find($projectID);
        UserProject::where('project_id', '=', $userProject->id)->where('user_id', '=', $objUser->id)->delete();

        return redirect()->route('client-projects-index', $slug)->with('success', __('Project Leave Successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Int $projectID
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function taskBoard($slug, $projectID)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
//dd($currentWorkspace->id);
        $objUser = Auth::user();
        if ($objUser->getGuard() == 'client') {
            $project = Project::select('projects.*')->join('client_projects', 'projects.id', '=', 'client_projects.project_id')->where('projects.workspace', '=', $currentWorkspace->id)->where('projects.project_id', '=', '$projectID')->first();
//        dd($project);
        } else {
            $project = Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->where('projects.id', '=', $projectID)->first();
        }

        $stages = $statusClass = [];

        $objUser = Auth::user();
        if ($objUser->getGuard() == 'client') {
            $permissions = $objUser->getPermission($projectID);
        } else {
            $permissions = Auth::user()->getPermission($projectID);
        }


        if ($project && (isset($permissions) && in_array('show task', $permissions)) || (isset($currentWorkspace) && $currentWorkspace->permission == 'Owner')) {
            $stages = Stage::where('workspace_id', '=', $currentWorkspace->id)->orderBy('order')->get();

            foreach ($stages as &$status) {
                $statusClass[] = 'task-list-' . str_replace(' ', '_', $status->id);
                $task = Task::where('project_id', '=', $projectID);
//                if ($currentWorkspace->permission != 'Owner' && $objUser->getGuard() != 'client') {
//                    if (isset($objUser) && $objUser) {
//                        $task->whereRaw("find_in_set('" . $objUser->id . "',assign_to)");
//                    }
//                }
//                $task->orderBy('order');
                $status['tasks'] = $task->where('status', '=', $status->id)->get();
            }
        }

        return view('clients.projects.taskboard', compact('currentWorkspace', 'project', 'stages', 'statusClass'));
    }

    public function taskCreate($slug, $projectID)
    {
        $objUser = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        if ($objUser->getGuard() == 'client') {
            $project = Project::select('projects.*')->where('projects.workspace', '=', $currentWorkspace->id)->where('projects.id', '=', $projectID)->first();
            $projects = Project::select('projects.*')->join('client_projects', 'client_projects.project_id', '=', 'projects.id')->where('client_projects.client_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->get();
        } else {
            $project = Project::select('projects.*')->join('user_projects', 'user_projects.project_id', '=', 'projects.id')->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->where('projects.id', '=', $projectID)->first();
            $projects = Project::select('projects.*')->join('user_projects', 'user_projects.project_id', '=', 'projects.id')->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->get();
        }

        $users = User::select('users.*')->join('user_projects', 'user_projects.user_id', '=', 'users.id')->where('project_id', '=', $projectID)->get();

        return view('projects.taskCreate', compact('currentWorkspace', 'project', 'projects', 'users'));
    }

    public function taskStore(Request $request, $slug, $projectID)
    {
//        dd($request->description);
        try {
//            $request->validate(
//                [
//                    'project_id' => 'required',
//                    'title' => 'required',
//                    'priority' => 'required',
//                    'assign_to' => 'required',
//                    'start_date' => 'required',
//                    'due_date' => 'required',
//                ]
//            );
            $objUser = Auth::user();
            $currentWorkspace = Utility::getWorkspaceBySlug($slug);
//            dd($currentWorkspace);
            $user = $currentWorkspace->id;
            $project_name = Project::where('id', $request->project_id)->first();

            if ($objUser->getGuard() == 'client') {
                $project = Project::where('projects.workspace', '=', $currentWorkspace->id)->where('projects.id', '=', $projectID)->first();
            } else {
                $project = Project::select('projects.*')->join('user_projects', 'user_projects.project_id', '=', 'projects.id')->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->where('projects.id', '=', $request->project_id)->first();
            }

            if ($project) {
                $post = $request->all();
                $stage = Stage::where('workspace_id', '=', $currentWorkspace->id)->orderBy('order')->first();
                if ($stage) {
                    $post['milestone_id'] = !empty($request->milestone_id) ? $request->milestone_id : 0;
                    $post['status'] = $stage->id;
//                    $post['assign_to'] = implode(",", $request->assign_to);
                    $task = Task::create($post);

                    ActivityLog::create(
                        [
                            'user_id' => \Auth::user()->id,
                            'user_type' => get_class(\Auth::user()),
                            'project_id' => $projectID,
                            'log_type' => 'Create Task',
                            'remark' => json_encode(['title' => $task->title]),
                        ]
                    );

                    $settings = Utility::getPaymentSetting($user);
                    if (isset($settings['task_notificaation']) && $settings['task_notificaation'] == 1) {
                        $msg = $request->title . " of " . $project_name->name . " created by the " . \Auth::user()->name . '.';

                        Utility::send_slack_msg($msg, $user);
                    }

                    if (isset($settings['telegram_task_notificaation']) && $settings['telegram_task_notificaation'] == 1) {
                        $msg = $request->title . " of " . $project_name->name . " created by " . \Auth::user()->name . '.';

                        Utility::send_telegram_msg($msg, $user);
                    }

//                    Utility::sendNotification('task_assign', $currentWorkspace, $request->assign_to, $task);

                    if ($objUser->getGuard() == 'client') {
                        return redirect()->route(
                            'client-projects-task-board', [
                                $currentWorkspace->slug,
                                $request->project_id,
                            ]
                        )->with('success', __('Task Create Successfully!'));
                    } else {
                        return redirect()->route(
                            'client-projects-task-board', [
                                $currentWorkspace->slug,
                                $request->project_id,
                            ]
                        )->with('success', __('Task Create Successfully!'));
                    }
                } else {
                    return redirect()->back()->with('error', __('Please add stages first.'));
                }
            } else {
                return redirect()->back()->with('error', __("You can't Add Task!"));
            }
        } catch (\Exception $e) {
            throw $e;
        }


    }

    public function taskOrderUpdate(Request $request, $slug, $projectID)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $user1 = $currentWorkspace->id;
        if (isset($request->sort)) {
            foreach ($request->sort as $index => $taskID) {
                $task = Task::find($taskID);
                $task->order = $index;
                $task->save();
            }
        }

        if ($request->new_status != $request->old_status) {
            $new_status = Stage::find($request->new_status);
            $old_status = Stage::find($request->old_status);
            $user = Auth::user();
            $task = Task::find($request->id);
            $task->status = $request->new_status;
            $task->save();

            $name = $user->name;
            $id = $user->id;

            ActivityLog::create(
                [
                    'user_id' => $id,
                    'user_type' => get_class($user),
                    'project_id' => $projectID,
                    'log_type' => 'Move',
                    'remark' => json_encode(
                        [
                            'title' => $task->title,
                            'old_status' => $old_status->name,
                            'new_status' => $new_status->name,
                        ]
                    ),
                ]
            );

            $settings = Utility::getPaymentSetting($user1);

            if (isset($settings['taskmove_notificaation']) && $settings['taskmove_notificaation'] == 1) {

                $msg = $task->title . " status changed from  " . $old_status->name . " to " . $new_status->name . '.';

                Utility::send_slack_msg($msg, $user1);
            }

            if (isset($settings['telegram_taskmove_notificaation']) && $settings['telegram_taskmove_notificaation'] == 1) {
                $msg = $task->title . " status changed from  " . $old_status->name . " to " . $new_status->name . '.';
                Utility::send_telegram_msg($msg, $user1);
            }

            return $task->toJson();
        }
    }

    public function taskEdit($slug, $projectID, $taskId)
    {
        $objUser = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);


        $project = Project::select('projects.*')->where('projects.workspace', '=', $currentWorkspace->id)->where('projects.project_id', '=', $projectID)->first();
        $projects = Project::select('projects.*')->join('client_projects', 'client_projects.project_id', '=', 'projects.project_id')->where('client_projects.client_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->get();

        $users = User::select('users.*')->join('user_projects', 'user_projects.user_id', '=', 'users.id')->where('project_id', '=', $projectID)->get();
        $task = Task::find($taskId);
//        $task->assign_to = explode(",", $task->assign_to);

        return view('projects.taskEdit', compact('currentWorkspace', 'project', 'projects', 'users', 'task'));
    }

    public function taskUpdate(taskUpdateFormRequest $request, $slug, $projectID, $taskID)
    {
        try {
            $objUser = Auth::user();
            $currentWorkspace = Utility::getWorkspaceBySlug($slug);


            $project = Project::where('projects.workspace', '=', $currentWorkspace->id)->where('projects.project_id', '=', $projectID)->first();

            if ($project) {
                $post = $request->all();
//            $post['assign_to'] = 'N/A';
                $task = Task::find($taskID);
                $task->update($post);

                return redirect()->back()->with('success', __('Task Updated Successfully!'));
            } else {
                return redirect()->back()->with('error', __("You can't Edit Task!"));
            }
        }catch (\Exception $e){
        }

    }

    public function taskDestroy($slug, $projectID, $taskID)
    {
        $objUser = Auth::user();
        $task = Task::where('id', $taskID)->delete();
        return redirect()->back()->with('success', __('Task Deleted Successfully!'));
    }

    public function taskShow($slug, $projectID, $taskID)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $task = Task::find($taskID);
        $objUser = Auth::user();

        $clientID = '';
        if ($objUser->getGuard() == 'client') {
            $clientID = $objUser->id;
        }

        return view('projects.taskShow', compact('currentWorkspace', 'task', 'clientID'));
    }

    public function taskDrag(Request $request, $slug, $projectID, $taskID)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $task = Task::find($taskID);
        $task->start_date = $request->start;
        $task->due_date = $request->end;
        $task->save();
    }

    public function document_upload(Request $request)
    {
//        dd($request->file('files'));
        if ($request->hasFile('files')) {
            $file = $request->file('files');
            $completeFileName = $file->getClientOriginalName();
            $fileNameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $randomized = rand();
            $documents = str_replace(' ', '', $fileNameOnly) . '-' . $randomized . '' . time() . '.' . $extension;
//            $path = $file->storeAs('public/users', $documents);
            $path = $file->storeAs('project_files', $documents);
            $test2 = $request->hasFile('files');

            $insert_doc = DB::table('generic_documents')->insert([
                'name' => $documents,
                'slug' => 'task_document',
                'description' => 'docs_related to task',
                'type_id' => $request->gallery_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        ActivityLog::create(
            [
                'user_id' => \Auth::user()->id,
                'user_type' => get_class(\Auth::user()),
                'project_id' => $request->gallery_id,
                'log_type' => 'Upload File',
                'remark' => json_encode(['file_name' => $path]),
            ]
        );
        die;
    }

    public function commentStore(Request $request, $slug, $projectID, $taskID, $clientID = '')
    {
        $task = Task::find($taskID);
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $user1 = $currentWorkspace->id;
        $post = [];
        $post['task_id'] = $taskID;
        $post['comment'] = $request->comment;
        if ($clientID) {
            $post['created_by'] = $clientID;
            $post['user_type'] = 'Client';
        } else {
            $post['created_by'] = Auth::user()->id;
            $post['user_type'] = 'User';
        }
        $comment = Comment::create($post);
        if ($comment->user_type == 'Client') {
            $user = $comment->client;
        } else {
            $user = $comment->user;
        }
        if (empty($clientID)) {
            $comment->deleteUrl = route(
                'comment.destroy', [
                    $currentWorkspace->slug,
                    $projectID,
                    $taskID,
                    $comment->id,
                ]
            );
        }

        $settings = Utility::getPaymentSetting($user1);

        if (isset($settings['taskcom_notificaation']) && $settings['taskcom_notificaation'] == 1) {
            $msg = "comment added in " . $task->title . ".";
            Utility::send_slack_msg($msg, $user1);
        }

        if (isset($settings['telegram_taskcom_notificaation']) && $settings['telegram_taskcom_notificaation'] == 1) {
            $msg = "comment added in " . $task->title . ".";
            Utility::send_telegram_msg($msg, $user1);
        }

        return $comment->toJson();
    }

    public function commentDestroy(Request $request, $slug, $projectID, $taskID, $commentID)
    {
        $comment = Comment::find($commentID);
        $comment->delete();

        return "true";
    }

    public function commentStoreFile(Request $request, $slug, $projectID, $taskID, $clientID = '')
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $request->validate(['file' => 'required|mimes:zip,rar,jpeg,jpg,png,gif,svg,pdf,txt,doc,docx,application/octet-stream,audio/mpeg,mpga,mp3,wav|max:204800']);
        $fileName = $taskID . time() . "_" . $request->file->getClientOriginalName();
        $request->file->storeAs('tasks', $fileName);
        $post['task_id'] = $taskID;
        $post['file'] = $fileName;
        $post['name'] = $request->file->getClientOriginalName();
        $post['extension'] = "." . $request->file->getClientOriginalExtension();
        $post['file_size'] = round(($request->file->getSize() / 1024) / 1024, 2) . ' MB';
        if ($clientID) {
            $post['created_by'] = $clientID;
            $post['user_type'] = 'Client';
        } else {
            $post['created_by'] = Auth::user()->id;
            $post['user_type'] = 'User';
        }
        $TaskFile = TaskFile::create($post);
        $user = $TaskFile->user;
        $TaskFile->deleteUrl = '';
        if (empty($clientID)) {
            $TaskFile->deleteUrl = route(
                'comment.destroy.file', [
                    $currentWorkspace->slug,
                    $projectID,
                    $taskID,
                    $TaskFile->id,
                ]
            );
        }

        return $TaskFile->toJson();
    }

    public function commentDestroyFile(Request $request, $slug, $projectID, $taskID, $fileID)
    {
        $commentFile = TaskFile::find($fileID);
        $path = storage_path('tasks/' . $commentFile->file);
        if (file_exists($path)) {
            \File::delete($path);
        }
        $commentFile->delete();

        return "true";
    }

    public function getSearchJson($slug, $search)
    {
        $user = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        if ($user->getGuard() == 'client') {
            $objProject = Project::select(
                [
                    'projects.id',
                    'projects.name',
                ]
            )->join('client_projects', 'client_projects.project_id', '=', 'projects.id')->where('client_projects.client_id', '=', $user->id)->where('projects.workspace', '=', $currentWorkspace->id)->where('projects.name', 'LIKE', $search . "%")->get();
            $arrProject = [];
            foreach ($objProject as $project) {
                $arrProject[] = [
                    'text' => $project->name,
                    'link' => route(
                        'client.projects.show', [
                            $currentWorkspace->slug,
                            $project->id,
                        ]
                    ),
                ];
            }
        } else {
            $objProject = Project::select(
                [
                    'projects.id',
                    'projects.name',
                ]
            )->join('user_projects', 'user_projects.project_id', '=', 'projects.id')->where('user_projects.user_id', '=', $user->id)->where('projects.workspace', '=', $currentWorkspace->id)->where('projects.name', 'LIKE', $search . "%")->get();
            $arrProject = [];
            foreach ($objProject as $project) {
                $arrProject[] = [
                    'text' => $project->name,
                    'link' => route(
                        'projects.show', [
                            $currentWorkspace->slug,
                            $project->id,
                        ]
                    ),
                ];
            }
        }

        if ($user->getGuard() == 'client') {
            $arrTask = [];
            $objTask = Task::select(
                [
                    'tasks.project_id',
                    'tasks.title',
                ]
            )->join('projects', 'tasks.project_id', '=', 'projects.id')->join('client_projects', 'client_projects.project_id', '=', 'projects.id')->where('client_projects.client_id', '=', $user->id)->where('projects.workspace', '=', $currentWorkspace->id)->where('tasks.title', 'LIKE', $search . "%")->get();
            foreach ($objTask as $task) {
                $arrTask[] = [
                    'text' => $task->title,
                    'link' => route(
                        'client.projects.task.board', [
                            $currentWorkspace->slug,
                            $task->project_id,
                        ]
                    ),
                ];
            }
        } else {
            $objTask = Task::select(
                [
                    'tasks.project_id',
                    'tasks.title',
                ]
            )->join('projects', 'tasks.project_id', '=', 'projects.id')->join('user_projects', 'user_projects.project_id', '=', 'projects.id')->where('user_projects.user_id', '=', $user->id)->where('projects.workspace', '=', $currentWorkspace->id)->where('tasks.title', 'LIKE', $search . "%")->get();
            $arrTask = [];
            foreach ($objTask as $task) {
                $arrTask[] = [
                    'text' => $task->title,
                    'link' => route(
                        'projects.task.board', [
                            $currentWorkspace->slug,
                            $task->project_id,
                        ]
                    ),
                ];
            }
        }

        return json_encode(
            [
                'Projects' => $arrProject,
                'Tasks' => $arrTask,
            ]
        );
    }

    public function milestone($slug, $projectID)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $project = Project::find($projectID);

        return view('clients.projects.milestone', compact('currentWorkspace', 'project'));
    }


    public function milestoneStore($slug, $projectID, Request $request)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $user1 = $currentWorkspace->id;
        $project = Project::find($projectID);
        $request->validate(
            [
                'title' => 'required',
                'status' => 'required',
                'cost' => 'required',
            ]
        );

        $milestone = new Milestone();
        $milestone->project_id = $project->id;
        $milestone->title = $request->title;
        $milestone->status = $request->status;
        $milestone->cost = $request->cost;
        $milestone->summary = $request->summary;
        $milestone->save();

        ActivityLog::create(
            [
                'user_id' => \Auth::user()->id,
                'user_type' => get_class(\Auth::user()),
                'project_id' => $project->id,
                'log_type' => 'Create Milestone',
                'remark' => json_encode(['title' => $milestone->title]),
            ]
        );

        $settings = Utility::getPaymentSetting($user1);
        if (isset($settings['milestone_notificaation']) && $settings['milestone_notificaation'] == 1) {
            $msg = "New Milestone created by " . \Auth::user()->name . '.';

            Utility::send_slack_msg($msg, $user1);
        }

        if (isset($settings['telegram_milestone_notificaation']) && $settings['telegram_milestone_notificaation'] == 1) {
            $msg = "New Milestone created by " . \Auth::user()->name . '.';
            Utility::send_telegram_msg($msg, $user1);
        }

        return redirect()->back()->with('success', __('Milestone Created Successfully!'));
    }

    public function milestoneEdit($slug, $milestoneID)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $milestone = Milestone::find($milestoneID);

        return view('clients.projects.milestoneEdit', compact('currentWorkspace', 'milestone'));
    }

    public function milestoneUpdate($slug, $milestoneID, Request $request)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $user1 = $currentWorkspace->id;
        $request->validate(
            [
                'title' => 'required',
                'status' => 'required',
                'cost' => 'required',
            ]
        );

        $milestone = Milestone::find($milestoneID);
        $milestone->title = $request->title;
        $milestone->status = $request->status;
        $milestone->cost = $request->cost;
        $milestone->summary = $request->summary;
        $milestone->progress = $request->progress;
        $milestone->start_date = $request->start_date;
        $milestone->end_date = $request->end_date;
        $milestone->save();

        $settings = Utility::getPaymentSetting($user1);
        if (isset($settings['milestonest_notificaation']) && $settings['milestonest_notificaation'] == 1) {
            $msg = " Milestone status updated by  " . \Auth::user()->name . '.';
            Utility::send_slack_msg($msg, $user1);
        }

        if (isset($settings['telegram_milestonest_notificaation']) && $settings['telegram_milestonest_notificaation'] == 1) {
            $msg = " Milestone status updated by " . \Auth::user()->name . '.';
            Utility::send_telegram_msg($msg, $user1);
        }

        return redirect()->back()->with('success', __('Milestone Updated Successfully!'));
    }



    public function addTask($slug, $projectID)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $project = Project::where('project_id',$projectID)->first();
        $info = 'Please fill all appropriate fields correctly';
        $route = 'show-client-project';
        return view('projects.task._create_task', compact('currentWorkspace', 'project', 'info','route'));
    }

    public function storeTask($slug, $projectID, taskFormRequest $request)
    {
        try {
            $currentWorkspace = Utility::getWorkspaceBySlug($slug);
            $user1 = $currentWorkspace->id;
            $project = Project::where('project_id',$projectID)->first();
            $task = $request->validated();
            $task['priority'] = 'HIGH';


            for ($count = 0; $count < count($task['title']); $count++) {
                $currentWorkspace = Utility::getWorkspaceBySlug($slug = 'en');
                $user = $currentWorkspace->id;
                $new_task = new Task();
                $new_task->title = $task['title'][$count];
                $new_task->start_date = format_date($task['start_date'][$count], 'd/m/y');
                $new_task->due_date = format_date($task['due_date'][$count], 'd/m/y');

                $new_task->priority = 'HIGH';

                $new_task->description = $task['description'][$count];
                $new_task->project_id = $project->project_id;
                $new_task->task_id = $task['task_id'];
                if (isset($request->assign_to)){
                    $new_task->assign_to = $task['assign_to'];
                }


                if (isset($request->assign_to)){
                    $task['assign_to'] = implode("|", $request->assign_to);
                    foreach ($request->assign_to as $assignee){
                        $assigneeInfor = User::where('email',$assignee)->first();
                        try {
                            Mail::to($assignee)->send(new SendUserNewTaskAssignedNotification($assigneeInfor,$task ));
                        } catch (\Exception $e) {
                            $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
                        }
                    }
                }


                $new_task->save();

                $stage = Stage::where('workspace_id', '=', $currentWorkspace->id)->orderBy('order')->first();
                if ($stage) {
//                        $task['milestone_id'] = !empty($request->milestone_id) ? $request->milestone_id : 0;
//                        $task['status'] = $stage->id;


                    ActivityLog::create(
                        [
                            'user_id' => \Auth::user()->id,
                            'user_type' => get_class(\Auth::user()),
                            'project_id' => $projectID,
                            'log_type' => 'Create Task',
                            'remark' => json_encode(['title' => $task['title'][$count]]),
                        ]
                    );


                }

                $settings = Utility::getPaymentSetting($user);
                if (isset($settings['task_notificaation']) && $settings['task_notificaation'] == 1) {
                    $msg = $request->title . " of " . $project->name . " created by the " . \Auth::user()->name . '.';
                    Utility::send_slack_msg($msg, $user);
                }
                if (isset($settings['telegram_task_notificaation']) && $settings['telegram_task_notificaation'] == 1) {
                    $msg = $request->title . " of " . $project->name . " created by " . \Auth::user()->name . '.';
                    Utility::send_telegram_msg($msg, $user);
                }


            }
            return redirect()->back()->with('success', __('Task Created Successfully!'));

        } catch (\Exception $e) {
            throw $e;
        }
    }


    public function subTaskStore(Request $request, $slug, $projectID, $taskID, $clientID = '')
    {
        $post = [];
        $post['task_id'] = $taskID;
        $post['name'] = $request->name;
        $post['due_date'] = $request->due_date;
        $post['status'] = 0;

        if ($clientID) {
            $post['created_by'] = $clientID;
            $post['user_type'] = 'Client';
        } else {
            $post['created_by'] = Auth::user()->id;
            $post['user_type'] = 'User';
        }
        $subtask = SubTask::create($post);
        if ($subtask->user_type == 'Client') {
            $user = $subtask->client;
        } else {
            $user = $subtask->user;
        }
        $subtask->updateUrl = route(
            'subtask.update', [
                $slug,
                $projectID,
                $subtask->id,
            ]
        );
        $subtask->deleteUrl = route(
            'subtask.destroy', [
                $slug,
                $projectID,
                $subtask->id,
            ]
        );

        return $subtask->toJson();
    }

    public function subTaskUpdate($slug, $projectID, $subtaskID)
    {
        $subtask = SubTask::find($subtaskID);
        $subtask->status = (int)!$subtask->status;
        $subtask->save();

        return $subtask->toJson();
    }

    public function subTaskDestroy($slug, $projectID, $subtaskID)
    {
        $subtask = SubTask::find($subtaskID);
        $subtask->delete();

        return "true";
    }

    public function fileUpload($slug, $id, Request $request)
    {
        $project = Project::find($id);
        $request->validate(['file' => 'required|mimes:zip,rar,jpeg,jpg,png,gif,svg,pdf,txt,doc,docx,application/octet-stream,audio/mpeg,mpga,mp3,wav|max:204800']);
        $file_name = $request->file->getClientOriginalName();
        $file_path = $project->id . "_" . md5(time()) . "_" . $request->file->getClientOriginalName();
        $request->file->storeAs('project_files', $file_path);

        $file = ProjectFile::create(
            [
                'project_id' => $project->id,
                'file_name' => $file_name,
                'file_path' => $file_path,
            ]
        );
        $return = [];
        $return['is_success'] = true;
        $return['download'] = route(
            'projects.file.download', [
                $slug,
                $project->id,
                $file->id,
            ]
        );
        $return['delete'] = route(
            'projects.file.delete', [
                $slug,
                $project->id,
                $file->id,
            ]
        );

        ActivityLog::create(
            [
                'user_id' => \Auth::user()->id,
                'user_type' => get_class(\Auth::user()),
                'project_id' => $project->id,
                'log_type' => 'Upload File',
                'remark' => json_encode(['file_name' => $file_name]),
            ]
        );

        return response()->json($return);
    }

    public function fileDownload($slug, $id, $file_id)
    {

        $project = Project::find($id);

        $file = ProjectFile::find($file_id);
        if ($file) {
            $file_path = storage_path('project_files/' . $file->file_path);
            $filename = $file->file_name;

            return \Response::download(
                $file_path, $filename, [
                    'Content-Length: ' . filesize($file_path),
                ]
            );
        } else {
            return redirect()->back()->with('error', __('File is not exist.'));
        }
    }

    public function fileDelete($slug, $id, $file_id)
    {
        $project = Project::find($id);

        $file = ProjectFile::find($file_id);
        if ($file) {
            $path = storage_path('project_files/' . $file->file_path);
            if (file_exists($path)) {
                \File::delete($path);
            }
            $file->delete();

            return response()->json(['is_success' => true], 200);
        } else {
            return response()->json(
                [
                    'is_success' => false,
                    'error' => __('File is not exist.'),
                ], 200
            );
        }
    }

    public function taskFileDownload($slug, $file_id)
    {

//        $project = Task::find($id);

        $file = GenericDocument::find($file_id);
        if ($file) {
            $file_path = storage_path('project_files/' . $file->name);
            $filename = $file->name;
            return \Response::download(
                $file_path, $filename, [
                    'Content-Length: ' . filesize($file_path),
                ]
            );
        } else {
            return redirect()->back()->with('error', __('File is not exist.'));
        }
    }

    public function taskFileDelete($slug, $file_id)
    {
//        $project = Task::find($id);

        $file = GenericDocument::find($file_id);
        if ($file) {
            $path = storage_path('project_files/' . $file->file_path);
            if (file_exists($path)) {
                \File::delete($path);
            }
            $file->delete();

//            return response()->json([
//                'is_success' => true,
//                'success' => __('File deleted successfully.'),
//            ], 200);
            return redirect()->back()->with('success', __('File deleted successfully.'));
        } else {
            return response()->json(
                [
                    'is_success' => false,
                    'error' => __('File is not exist.'),
                ], 200
            );
        }
    }

    // Timesheet
    public function timesheet($slug)
    {
        $project_id = '-1';

        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $objUser = Auth::user();
        if ($objUser->getGuard() == 'client') {
            $timesheets = Timesheet::select('timesheets.*')->join('projects', 'projects.id', '=', 'timesheets.project_id')->join('tasks', 'tasks.id', '=', 'timesheets.task_id')->join('client_projects', 'projects.id', '=', 'client_projects.project_id')->where('client_projects.client_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->where('client_projects.permission', 'LIKE', '%show timesheet%')->get();
        } elseif ($currentWorkspace->permission == 'Owner') {
            $timesheets = Timesheet::select('timesheets.*')->join('projects', 'projects.id', '=', 'timesheets.project_id')->join('tasks', 'tasks.id', '=', 'timesheets.task_id')->where('projects.workspace', '=', $currentWorkspace->id)->get();
        } else {
            $timesheets = Timesheet::select('timesheets.*')->join('projects', 'projects.id', '=', 'timesheets.project_id')->join('tasks', 'timesheets.task_id', '=', 'tasks.id')->where('projects.workspace', '=', $currentWorkspace->id)->whereRaw("find_in_set('" . $objUser->id . "',tasks.assign_to)")->get();
        }

        return view('clients.projects.timesheet', compact('currentWorkspace', 'timesheets', 'project_id'));

    }

    public function timesheetCreate($slug)
    {
        $objUser = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $projects = Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->get();

        return view('clients.projects.timesheetCreate', compact('currentWorkspace', 'projects'));
    }

    public function getTask($slug, $project_id = null)
    {

        if ($project_id) {
            $currentWorkspace = Utility::getWorkspaceBySlug($slug);
            $objUser = Auth::user();
            if ($currentWorkspace->permission == 'Owner') {
                $tasks = Task::where('project_id', '=', $project_id)->get();
            } else {
                $tasks = Task::where('project_id', '=', $project_id)->whereRaw("find_in_set('" . $objUser->id . "',assign_to)")->get();
            }

            return response()->json($tasks);
        }
    }

    public function timesheetStore($slug, Request $request)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $request->validate(
            [
                'task_id' => 'required',
                'date' => 'required',
                'time' => 'required',
            ]
        );

        $timesheet = new Timesheet();
        $timesheet->project_id = $request->project_id;
        $timesheet->task_id = $request->task_id;
        $timesheet->date = $request->date;
        $timesheet->time = $request->time;
        $timesheet->description = $request->description;
        $timesheet->save();

        ActivityLog::create(
            [
                'user_id' => \Auth::user()->id,
                'user_type' => get_class(\Auth::user()),
                'project_id' => $request->project_id,
                'log_type' => 'Create Timesheet',
                'remark' => json_encode(['name' => \Auth::user()->name]),
            ]
        );

        return redirect()->back()->with('success', __('Timesheet Created Successfully!'));
    }

    public function timesheetEdit($slug, $timesheetID)
    {
        $objUser = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $projects = Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->get();

        $timesheet = Timesheet::find($timesheetID);

        return view('clients.projects.timesheetEdit', compact('currentWorkspace', 'timesheet', 'projects'));
    }

    public function timesheetUpdate($slug, $timesheetID, Request $request)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

        $request->validate(
            [
                'task_id' => 'required',
                'date' => 'required',
                'time' => 'required',
            ]
        );

        $timesheet = Timesheet::find($timesheetID);
        $timesheet->project_id = $request->project_id;
        $timesheet->task_id = $request->task_id;
        $timesheet->date = $request->date;
        $timesheet->time = $request->time;
        $timesheet->description = $request->description;
        $timesheet->save();

        return redirect()->back()->with('success', __('Timesheet Updated Successfully!'));
    }

    public function timesheetDestroy($slug, $timesheetID)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $timesheet = Timesheet::find($timesheetID);
        $timesheet->delete();

        return redirect()->back()->with('success', __('Timesheet deleted Successfully!'));
    }

    public function clientPermission($slug, $project_id, $client_id)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $project = Project::find($project_id);
        $client = Client::find($client_id);
        $permissions = $client->getPermission($project_id);
        if (!$permissions) {
            $permissions = [];
        }

        return view('clients.projects.client_permission', compact('currentWorkspace', 'project', 'client', 'permissions'));
    }

    public function clientPermissionStore($slug, $project_id, $client_id, Request $request)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $clientProject = ClientProject::where('client_id', '=', $client_id)->where('project_id', '=', $project_id)->first();
        $clientProject->permission = json_encode($request->permissions);
        $clientProject->save();

        return redirect()->back()->with('success', __('Permission Updated Successfully!'));
    }

    public function bugReport($slug, $project_id)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

        $objUser = Auth::user();
        if ($objUser->getGuard() == 'client') {
            $project = Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->where('projects.workspace', '=', $currentWorkspace->id)->where('projects.id', '=', $project_id)->first();
        } else {
            $project = Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->where('projects.id', '=', $project_id)->first();
        }

        $stages = $statusClass = [];
        $permissions = Auth::user()->getPermission($project_id);

        if ($project && (isset($permissions) && in_array('show bug report', $permissions)) || (isset($currentWorkspace) && $currentWorkspace->permission == 'Owner')) {
            $stages = BugStage::where('workspace_id', '=', $currentWorkspace->id)->orderBy('order')->get();

            foreach ($stages as &$status) {
                $statusClass[] = 'task-list-' . str_replace(' ', '_', $status->id);
                $bug = BugReport::where('project_id', '=', $project_id);
                if ($currentWorkspace->permission != 'Owner' && $objUser->getGuard() != 'client') {
                    if (isset($objUser) && $objUser) {
                        $bug->where('assign_to', '=', $objUser->id);
                    }
                }
                $bug->orderBy('order');

                $status['bugs'] = $bug->where('status', '=', $status->id)->get();
            }
            return view('clients.projects.bug_report', compact('currentWorkspace', 'project', 'stages', 'statusClass'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function bugReportCreate($slug, $project_id)
    {
        $objUser = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        if ($objUser->getGuard() == 'client') {
            $project = Project::where('projects.workspace', '=', $currentWorkspace->id)->where('projects.id', '=', $project_id)->first();
        } else {
            $project = Project::select('projects.*')->join('user_projects', 'user_projects.project_id', '=', 'projects.id')->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->where('projects.id', '=', $project_id)->first();
        }
        $arrStatus = BugStage::where('workspace_id', '=', $currentWorkspace->id)->orderBy('order')->pluck('name', 'id')->all();
        $users = User::select('users.*')->join('user_projects', 'user_projects.user_id', '=', 'users.id')->where('project_id', '=', $project_id)->get();

        return view('clients.projects.bug_report_create', compact('currentWorkspace', 'project', 'users', 'arrStatus'));
    }

    public function bugReportStore(Request $request, $slug, $project_id)
    {
        $request->validate(
            [
                'title' => 'required',
                'priority' => 'required',
                'assign_to' => 'required',
                'status' => 'required',
            ]
        );
        $objUser = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        if ($objUser->getGuard() == 'client') {
            $project = Project::where('projects.workspace', '=', $currentWorkspace->id)->where('projects.id', '=', $project_id)->first();
        } else {
            $project = Project::select('projects.*')->join('user_projects', 'user_projects.project_id', '=', 'projects.id')->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->where('projects.id', '=', $project_id)->first();
        }

        if ($project) {
            $post = $request->all();
            $post['project_id'] = $project_id;
            $bug = BugReport::create($post);

            ActivityLog::create(
                [
                    'user_id' => $objUser->id,
                    'user_type' => get_class($objUser),
                    'project_id' => $project_id,
                    'log_type' => 'Create Bug',
                    'remark' => json_encode(['title' => $bug->title]),
                ]
            );
            Utility::sendNotification('bug_assign', $currentWorkspace, $request->assign_to, $bug);

            return redirect()->back()->with('success', __('Bug Create Successfully!'));
        } else {
            return redirect()->back()->with('error', __("You can't Add Bug!"));
        }
    }

    public function bugReportOrderUpdate(Request $request, $slug, $project_id)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        if (isset($request->sort)) {
            foreach ($request->sort as $index => $taskID) {
                $bug = BugReport::find($taskID);
                $bug->order = $index;
                $bug->save();
            }
        }
        if ($request->new_status != $request->old_status) {
            $new_status = BugStage::find($request->new_status);
            $old_status = BugStage::find($request->old_status);
            $user = Auth::user();
            $bug = BugReport::find($request->id);
            $bug->status = $request->new_status;
            $bug->save();

            $name = $user->name;
            $id = $user->id;

            ActivityLog::create(
                [
                    'user_id' => $id,
                    'user_type' => get_class($user),
                    'project_id' => $project_id,
                    'log_type' => 'Move Bug',
                    'remark' => json_encode(
                        [
                            'title' => $bug->title,
                            'old_status' => $old_status->name,
                            'new_status' => $new_status->name,
                        ]
                    ),
                ]
            );

            return $bug->toJson();
        }
    }

    public function bugReportEdit($slug, $project_id, $bug_id)
    {
        $objUser = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

        if ($objUser->getGuard() == 'client') {
            $project = Project::where('projects.workspace', '=', $currentWorkspace->id)->where('projects.id', '=', $project_id)->first();
        } else {
            $project = Project::select('projects.*')->join('user_projects', 'user_projects.project_id', '=', 'projects.id')->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->where('projects.id', '=', $project_id)->first();
        }
        $users = User::select('users.*')->join('user_projects', 'user_projects.user_id', '=', 'users.id')->where('project_id', '=', $project_id)->get();
        $bug = BugReport::find($bug_id);
        $arrStatus = BugStage::where('workspace_id', '=', $currentWorkspace->id)->orderBy('order')->pluck('name', 'id')->all();

        return view('clients.projects.bug_report_edit', compact('currentWorkspace', 'project', 'users', 'bug', 'arrStatus'));
    }

    public function bugReportUpdate(Request $request, $slug, $project_id, $bug_id)
    {
        $request->validate(
            [
                'title' => 'required',
                'priority' => 'required',
                'assign_to' => 'required',
                'status' => 'required',
            ]
        );
        $objUser = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

        if ($objUser->getGuard() == 'client') {
            $project = Project::where('projects.workspace', '=', $currentWorkspace->id)->where('projects.id', '=', $project_id)->first();
        } else {
            $project = Project::select('projects.*')->join('user_projects', 'user_projects.project_id', '=', 'projects.id')->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->where('projects.id', '=', $project_id)->first();
        }
        if ($project) {
            $post = $request->all();
            $bug = BugReport::find($bug_id);
            $bug->update($post);

            return redirect()->back()->with('success', __('Bug Updated Successfully!'));
        } else {
            return redirect()->back()->with('error', __("You can't Edit Bug!"));
        }
    }

    public function bugReportDestroy($slug, $project_id, $bug_id)
    {
        $objUser = Auth::user();
        $bug = BugReport::where('id', $bug_id)->delete();

        return redirect()->back()->with('success', __('Bug Deleted Successfully!'));
    }

    public function bugReportShow($slug, $project_id, $bug_id)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $bug = BugReport::find($bug_id);
        $objUser = Auth::user();

        $clientID = '';
        if ($objUser->getGuard() == 'client') {
            $clientID = $objUser->id;
        }

        return view('clients.projects.bug_report_show', compact('currentWorkspace', 'bug', 'clientID'));
    }

    public function bugCommentStore(Request $request, $slug, $project_id, $bugID, $clientID = '')
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $post = [];
        $post['bug_id'] = $bugID;
        $post['comment'] = $request->comment;
        if ($clientID) {
            $post['created_by'] = $clientID;
            $post['user_type'] = 'Client';
        } else {
            $post['created_by'] = Auth::user()->id;
            $post['user_type'] = 'User';
        }
        $comment = BugComment::create($post);
        if ($comment->user_type == 'Client') {
            $user = $comment->client;
        } else {
            $user = $comment->user;
        }
        if (empty($clientID)) {
            $comment->deleteUrl = route(
                'bug.comment.destroy', [
                    $currentWorkspace->slug,
                    $project_id,
                    $bugID,
                    $comment->id,
                ]
            );
        }

        return $comment->toJson();
    }

    public function bugCommentDestroy(Request $request, $slug, $project_id, $bug_id, $comment_id)
    {
        $comment = BugComment::find($comment_id);
        $comment->delete();

        return "true";
    }

    public function bugStoreFile(Request $request, $slug, $project_id, $bug_id, $clientID = '')
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $request->validate(['file' => 'required|mimes:zip,rar,jpeg,jpg,png,gif,svg,pdf,txt,doc,docx,application/octet-stream,audio/mpeg,mpga,mp3,wav|max:204800']);
        $fileName = $bug_id . time() . "_" . $request->file->getClientOriginalName();
        $request->file->storeAs('tasks', $fileName);
        $post['bug_id'] = $bug_id;
        $post['file'] = $fileName;
        $post['name'] = $request->file->getClientOriginalName();
        $post['extension'] = "." . $request->file->getClientOriginalExtension();
        $post['file_size'] = round(($request->file->getSize() / 1024) / 1024, 2) . ' MB';
        if ($clientID) {
            $post['created_by'] = $clientID;
            $post['user_type'] = 'Client';
        } else {
            $post['created_by'] = Auth::user()->id;
            $post['user_type'] = 'User';
        }
        $TaskFile = BugFile::create($post);
        $user = $TaskFile->user;
        $TaskFile->deleteUrl = '';
        if (empty($clientID)) {
            $TaskFile->deleteUrl = route(
                'bug.comment.destroy.file', [
                    $currentWorkspace->slug,
                    $project_id,
                    $bug_id,
                    $TaskFile->id,
                ]
            );
        }

        return $TaskFile->toJson();
    }

    public function bugDestroyFile(Request $request, $slug, $project_id, $bug_id, $file_id)
    {
        $commentFile = BugFile::find($file_id);
        $path = storage_path('tasks/' . $commentFile->file);
        if (file_exists($path)) {
            \File::delete($path);
        }
        $commentFile->delete();

        return "true";
    }

    public function allTasks($slug)
    {
        $userObj = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

        if ($userObj->getGuard() == 'client') {
            $projects = Task::select('tasks.*')->join('projects', 'tasks.project_id', '=', 'projects.project_id')->where('projects.created_by', '=', $userObj->id)->where('projects.workspace', '=', $currentWorkspace->id)->get();
        } else {
            $projects = Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->where('user_projects.user_id', '=', $userObj->id)->where('projects.workspace', '=', $currentWorkspace->id)->get();
        }
        $stages = Stage::where('workspace_id', '=', $currentWorkspace->id)->orderBy('order')->get();
        $users = User::select('users.*')->join('user_workspaces', 'user_workspaces.user_id', '=', 'users.id')->where('user_workspaces.workspace_id', '=', $currentWorkspace->id)->get();

        return view('clients.projects.tasks', compact('currentWorkspace', 'projects', 'users', 'stages'));
    }

    public function ajax_tasks($slug, Request $request)
    {
        $userObj = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        if ($currentWorkspace->permission == 'Owner') {
//            $tasks = Task::select(
//                [
//                    'tasks.*',
//                    'stages.name as stage',
//                    'stages.complete',
//                ]
//            )->join("user_projects", "tasks.project_id", "=", "user_projects.project_id")->join("projects", "projects.id", "=", "user_projects.project_id")->join("stages", "stages.id", "=", "tasks.status")->where("user_id", "=", $userObj->id)->where('projects.workspace', '=', $currentWorkspace->id);
            $tasks = Task::select(['tasks.*', 'projects.*', 'stages.name as stage',])
                ->join('projects', 'tasks.project_id', '=', 'projects.project_id')->join("stages", "stages.name", "=", "tasks.status")->where('projects.created_by', '=', $userObj->id);
        } else {
            $tasks = Task::select(
                [
                    'tasks.*',
                    'stages.name as stage',
                    'stages.complete',
                ]
            )->join("user_projects", "tasks.project_id", "=", "user_projects.project_id")->join("projects", "projects.id", "=", "user_projects.project_id")->join("stages", "stages.id", "=", "tasks.status")->where("user_id", "=", $userObj->id)->where('projects.workspace', '=', $currentWorkspace->id)->whereRaw("find_in_set('" . $userObj->id . "',tasks.assign_to)");
        }
        if ($request->project) {
            $tasks->where('tasks.project_id', '=', $request->project);
        }
        if ($request->assign_to) {
            $tasks->whereRaw("find_in_set('" . $request->assign_to . "',assign_to)");
        }
        if ($request->due_date_order) {
            if ($request->due_date_order == 'today') {

                $tasks->whereDate('due_date', Carbon::today());
            } else if ($request->due_date_order == 'expired') {

                $tasks->whereDate('due_date', '<', Carbon::today());
            } else if ($request->due_date_order == 'in_7_days') {

                $tasks->where(['due_date' => Carbon::parse()->between(Carbon::now(), Carbon::now()->addDays(7))]);
            } else {

                $sort = explode(',', $request->due_date_order);

                $tasks->orderBy($sort[0], $sort[1]);
            }
        }
        if ($request->priority) {
            $tasks->where('priority', '=', $request->priority);
        }
        if ($request->status) {
            $tasks->where('tasks.status', '=', $request->status);
        }
        if ($request->start_date && $request->end_date) {
            $tasks->whereBetween(
                'tasks.due_date', [
                    $request->start_date,
                    $request->end_date,
                ]
            );
        }
        $tasks = $tasks->get();
        $data = [];
        foreach ($tasks as $task) {
            $tmp = [];
            $tmp['title'] = '<a href="' . route(
                    'projects.task.board', [
                        $currentWorkspace->slug,
                        $task->project_id,
                    ]
                ) . '" class="text-body">' . $task->title . '</a>';
            $tmp['project_name'] = $task->name;
            $tmp['milestone'] = ($milestone = $task->milestone()) ? $milestone->title : 'N/A';

            $due_date = '<span class="text-' . ($task->due_date < date('Y-m-d') ? 'danger' : 'success') . '">' . date('Y-m-d', strtotime($task->due_date)) . '</span> ';
            $tmp['due_date'] = $due_date;

            if ($currentWorkspace->permission == 'Owner' || Auth::user()->getGuard() == 'client') {
                $tmp['assign_to'] = "";
                foreach ($task->users() as $user) {
                    if (isset($user) && $user) {
                        $tmp['assign_to'] .= '<span class="badge bg-secondary p-2 px-3 rounded">' . $user->name . '</span> ';
                    }
                }
            }

            if ($task->complete == 1) {
                $tmp['status'] = '<span class="status_badge badge bg-success p-2 px-3 rounded">' . __($task->stage) . '</span>';
            } else {
                $tmp['status'] = '<span class="status_badge badge bg-primary p-2 px-3 rounded">' . __($task->stage) . '</span>';
            }

            if ($task->priority == "High") {
                $tmp['priority'] = '<span class="priority_badge badge bg-danger p-2 px-3 rounded">' . __('High') . '</span>';
            } elseif ($task->priority == "Medium") {
                $tmp['priority'] = '<span class="priority_badge badge bg-info p-2 px-3 rounded">' . __('Medium') . '</span>';
            } else {
                $tmp['priority'] = '<span class="priority_badge badge bg-success p-2 px-3 rounded">' . __('Low') . '</span>';
            }

            if ($currentWorkspace->permission == 'Owner') {
                $tmp['action'] = '
                <a href="#" class="action-btn btn-info  btn btn-sm d-inline-flex align-items-center"  data-toggle="popover"  title="' . __('Edit Task') . '"  data-ajax-popup="true" data-size="lg" data-title="' . __('Edit Task') . '" data-url="' . route(
                        'client-tasks-edit', [
                            $currentWorkspace->slug,
                            $task->project_id,
                            $task->id,
                        ]
                    ) . '"><i class="ti ti-pencil"></i></a>
                <a href="#" class="action-btn btn-danger  btn btn-sm d-inline-flex align-items-center bs-pass-para" data-toggle="popover" title="' . __('Delete') . '" data-confirm="' . __('Are You Sure?') . '" data-confirm-yes="delete-form-' . $task->id . '">
                    <i class="ti ti-trash"></i></a>
                <form id="delete-form-' . $task->id . '" action="' . route(
                        'tasks.destroy', [
                            $currentWorkspace->slug,
                            $task->project_id,
                            $task->id,
                        ]
                    ) . '" method="POST" style="display: none;">
                                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                                            <input type="hidden" name="_method" value="DELETE">
                                        </form>';
            }
            $data[] = array_values($tmp);

        }
        return response()->json(['data' => $data], 200);
    }

    public function gantt($slug, $projectID, $duration = 'Week')
    {
        $objUser = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $is_client = '';

        if ($objUser->getGuard() == 'client') {
            $project = Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->where('projects.workspace', '=', $currentWorkspace->id)->where('projects.id', '=', $projectID)->first();
            $is_client = 'client.';
        } else {
            $project = Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->where('projects.id', '=', $projectID)->first();
        }
        $tasks = [];
        $permissions = Auth::user()->getPermission($projectID);

        if ($project && (isset($permissions) && in_array('show gantt', $permissions)) || (isset($currentWorkspace) && $currentWorkspace->permission == 'Owner')) {
            if ($objUser->getGuard() == 'client' || $currentWorkspace->permission == 'Owner') {
                $tasksobj = Task::where('project_id', '=', $project->id)->orderBy('start_date')->get();
            } else {
                $tasksobj = Task::where('project_id', '=', $project->id)->where('assign_to', '=', $objUser->id)->orderBy('start_date')->get();
            }
            foreach ($tasksobj as $task) {
                $tmp = [];
                $tmp['id'] = 'task_' . $task->id;
                $tmp['name'] = $task->title;
                $tmp['start'] = $task->start_date;
                $tmp['end'] = $task->due_date;
                $tmp['custom_class'] = strtolower($task->priority);
                $tmp['progress'] = $task->subTaskPercentage();
                $tmp['extra'] = [
                    'priority' => __($task->priority),
                    'comments' => count($task->comments),
                    'duration' => Date::parse($task->start_date)->format('d M Y H:i A') . ' - ' . Date::parse($task->due_date)->format('d M Y H:i A'),
                ];
                $tasks[] = $tmp;
            }
        }

        return view('clients.projects.gantt', compact('currentWorkspace', 'project', 'tasks', 'duration', 'is_client'));
    }

    public function ganttPost($slug, $projectID, Request $request)
    {
        $objUser = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

        if ($objUser->getGuard() == 'client') {
            $project = Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->where('projects.workspace', '=', $currentWorkspace->id)->where('projects.id', '=', $projectID)->first();
        } else {
            $project = Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->where('projects.id', '=', $projectID)->first();
        }
        if ($project) {
            if ($objUser->getGuard() == 'client' || $currentWorkspace->permission == 'Owner') {
                $id = trim($request->task_id, 'task_');
                $task = Task::find($id);
                $task->start_date = $request->start;
                $task->due_date = $request->end;
                $task->save();

                return response()->json(
                    [
                        'is_success' => true,
                        'message' => __("Time Updated"),
                    ], 200
                );
            } else {
                return response()->json(
                    [
                        'is_success' => false,
                        'message' => __("You can't change Date!"),
                    ], 400
                );
            }
        } else {
            return response()->json(
                [
                    'is_success' => false,
                    'message' => __("Something is wrong."),
                ], 400
            );
        }
    }

    public function projectsTimesheet(Request $request, $slug, $project_id = 0)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

        return view('clients.projects.timesheet', compact('currentWorkspace', 'project_id'));
    }

    public function filterTimesheetTableView(Request $request, $slug)
    {
        $tasks = [];
        $objUser = Auth::user();

        $week = $request->week;
        $project_id = $request->project_id;

        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

        if ($request->has('week')) {
            if ($objUser->getGuard() == 'client') {
                $timesheets = Timesheet::select('timesheets.*')->join('projects', 'projects.id', '=', 'timesheets.project_id')->join('tasks', 'tasks.id', '=', 'timesheets.task_id')->join('client_projects', 'projects.id', '=', 'client_projects.project_id')->where('client_projects.client_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->where('client_projects.permission', 'LIKE', '%show timesheet%');
            } elseif ($currentWorkspace->permission == 'Owner') {
                $timesheets = Timesheet::select('timesheets.*')->join('projects', 'projects.id', '=', 'timesheets.project_id')->join('tasks', 'tasks.id', '=', 'timesheets.task_id')->where('projects.workspace', '=', $currentWorkspace->id);
            } else {
                $timesheets = Timesheet::select('timesheets.*')->join('projects', 'projects.id', '=', 'timesheets.project_id')->join('tasks', 'timesheets.task_id', '=', 'tasks.id')->where('projects.workspace', '=', $currentWorkspace->id)->whereRaw("find_in_set('" . $objUser->id . "',tasks.assign_to)");
            }

            $days = Utility::getFirstSeventhWeekDay($week);

            $first_day = $days['first_day'];
            $seventh_day = $days['seventh_day'];

            $onewWeekDate = $first_day->format('M d') . ' - ' . $seventh_day->format('M d, Y');
            $selectedDate = $first_day->format('Y-m-d') . ' - ' . $seventh_day->format('Y-m-d');

            $timesheets = $timesheets->whereDate('date', '>=', $first_day->format('Y-m-d'))->whereDate('date', '<=', $seventh_day->format('Y-m-d'));

            if ($project_id == '-1') {
                $timesheets = $timesheets->get()->groupBy(
                    [
                        'project_id',
                        'task_id',
                    ]
                )->toArray();
            } else {
                $timesheets = $timesheets->where('projects.id', $project_id)->get()->groupBy('task_id')->toArray();
            }

            $task_ids = array_keys($timesheets);

            $returnHTML = Project::getProjectAssignedTimesheetHTML($currentWorkspace, $timesheets, $days, $project_id);

            $totalrecords = count($timesheets);

            if ($project_id != '-1') {
                if ($objUser->getGuard() == 'client') {
                    $projects = Project::select('projects.*')->join('client_projects', 'projects.id', '=', 'client_projects.project_id')->where('client_projects.client_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->get();
                } else {
                    $projects = Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->get();
                }

                if ($currentWorkspace->permission == 'Owner') {

                    $tasks = Task::where('project_id', '=', $project_id)->whereNotIn('id', $task_ids)->pluck('title', 'id');
                } else {
                    $tasks = Task::where('project_id', '=', $project_id)->whereNotIn('id', $task_ids)->whereRaw("find_in_set('" . $objUser->id . "',assign_to)")->pluck('title', 'id');
                }
            }

            return response()->json(
                [
                    'success' => true,
                    'totalrecords' => $totalrecords,
                    'selectedDate' => $selectedDate,
                    'tasks' => $tasks,
                    'onewWeekDate' => $onewWeekDate,
                    'html' => $returnHTML,
                ]
            );
        }
    }

    public function appendTimesheetTaskHTML(Request $request, $slug)
    {
        $project_id = $request->has('project_id') ? $request->project_id : null;
        $task_id = $request->has('task_id') ? $request->task_id : null;
        $selected_dates = $request->has('selected_dates') ? $request->selected_dates : null;

        $returnHTML = '';

        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

        $project = Project::find($project_id);

        if ($project) {
            $task = Task::find($task_id);

            if ($task && $selected_dates) {
                $twoDates = explode(' - ', $selected_dates);

                $first_day = $twoDates[0];
                $seventh_day = $twoDates[1];

                $period = CarbonPeriod::create($first_day, $seventh_day);

                $returnHTML .= '<tr><td><span class="task-name ml-3">' . $task->title . '</span></td>';

                foreach ($period as $key => $dateobj) {
                    $returnHTML .= '<td><div class="day-time" data-ajax-timesheet-popup="true" data-type="create" data-task-id="' . $task->id . '" data-date="' . $dateobj->format('Y-m-d') . '" data-url="' . route(
                            'project.timesheet.create', [
                                'slug' => $currentWorkspace->slug,
                                'project_id' => $project_id,
                            ]
                        ) . '">-</div></td>';
                }

                $returnHTML .= '<td><div class="total day-time">00:00</div></td></tr>';
            }
        }

        return response()->json(
            [
                'success' => true,
                'html' => $returnHTML,
            ]
        );
    }

    public function projectTimesheetCreate(Request $request, $slug, $project_id)
    {
        $parseArray = [];

        $objUser = Auth::user();

        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

        $project_id = $request->has('project_id') ? $request->project_id : null;
        $task_id = $request->has('task_id') ? $request->task_id : null;
        $selected_date = $request->has('date') ? $request->date : null;
        $user_id = $request->has('user_id') ? $request->user_id : null;

        $created_by = $user_id != null ? $user_id : $objUser->id;

        if ($objUser->getGuard() == 'client') {
            $projects = Project::select('projects.*')->join('client_projects', 'projects.id', '=', 'client_projects.project_id')->where('client_projects.client_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id);
        } else {
            $projects = Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id);
        }

        if ($project_id) {
            $project = $projects->where('projects.id', '=', $project_id)->pluck('projects.name', 'projects.id')->all();

            if (!empty($project) && count($project) > 0) {
                $project_id = key($project);
                $project_name = $project[$project_id];

                $task = Task::where(
                    [
                        'project_id' => $project_id,
                        'id' => $task_id,
                    ]
                )->pluck('title', 'id')->all();

                $task_id = key($task);
                $task_name = $task[$task_id];

                $tasktime = Timesheet::where('task_id', $task_id)->where('created_by', $created_by)->pluck('time')->toArray();

                $totaltasktime = Utility::calculateTimesheetHours($tasktime);

                $totalhourstimes = explode(':', $totaltasktime);

                $totaltaskhour = $totalhourstimes[0];
                $totaltaskminute = $totalhourstimes[1];

                $parseArray = [
                    'project_id' => $project_id,
                    'project_name' => $project_name,
                    'task_id' => $task_id,
                    'task_name' => $task_name,
                    'date' => $selected_date,
                    'totaltaskhour' => $totaltaskhour,
                    'totaltaskminute' => $totaltaskminute,
                ];

                return view('clients.projects.timesheet-create', compact('currentWorkspace', 'parseArray'));
            }
        } else {
            $projects = $projects->get();

            return view('clients.projects.timesheet-create', compact('currentWorkspace', 'projects', 'project_id', 'selected_date'));
        }
    }

    public function projectTimesheetStore(Request $request, $slug, $project_id)
    {
        $objUser = Auth::user();
        $project = Project::find($request->project_id);

        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

        if ($project) {
            $request->validate(
                [
                    'date' => 'required',
                    'time_hour' => 'required',
                    'time_minute' => 'required',
                ]
            );

            $hour = $request->time_hour;
            $minute = $request->time_minute;

            $time = ($hour != '' ? ($hour < 10 ? '0' + $hour : $hour) : '00') . ':' . ($minute != '' ? ($minute < 10 ? '0' + $minute : $minute) : '00');

            $timesheet = new Timesheet();
            $timesheet->project_id = $request->project_id;
            $timesheet->task_id = $request->task_id;
            $timesheet->date = $request->date;
            $timesheet->time = $time;
            $timesheet->description = $request->description;
            $timesheet->created_by = $objUser->id;
            $timesheet->save();

            return redirect()->back()->with('success', __('Timesheet Created Successfully!'));
        }
    }

    public function projectTimesheetEdit(Request $request, $slug, $timesheet_id, $project_id)
    {

        $objUser = Auth::user();

        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

        $task_id = $request->has('task_id') ? $request->task_id : null;

        $user_id = $request->has('date') ? $request->user_id : null;
        $created_by = $user_id != null ? $user_id : $objUser->id;

        $project_view = '';

        if ($request->has('project_view')) {
            $project_view = $request->project_view;
        }

        if ($objUser->getGuard() == 'client') {
            $projects = Project::select('projects.*')->join('client_projects', 'projects.id', '=', 'client_projects.project_id')->where('client_projects.client_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id);
        } else {
            $projects = Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id);
        }

        $timesheet = Timesheet::find($timesheet_id);

        if ($timesheet) {
            $project = $projects->where('projects.id', '=', $project_id)->pluck('projects.name', 'projects.id')->all();

            if (!empty($project) && count($project) > 0) {
                $project_id = key($project);
                $project_name = $project[$project_id];

                $task = Task::where(
                    [
                        'project_id' => $project_id,
                        'id' => $task_id,
                    ]
                )->pluck('title', 'id')->all();

                $task_id = key($task);
                $task_name = $task[$task_id];

                $tasktime = Timesheet::where('task_id', $task_id)->where('created_by', $created_by)->pluck('time')->toArray();

                $totaltasktime = Utility::calculateTimesheetHours($tasktime);

                $totalhourstimes = explode(':', $totaltasktime);

                $totaltaskhour = $totalhourstimes[0];
                $totaltaskminute = $totalhourstimes[1];

                $time = explode(':', $timesheet->time);

                $parseArray = [
                    'project_id' => $project_id,
                    'project_name' => $project_name,
                    'task_id' => $task_id,
                    'task_name' => $task_name,
                    'time_hour' => $time[0] < 10 ? $time[0] : $time[0],
                    'time_minute' => $time[1] < 10 ? $time[1] : $time[1],
                    'totaltaskhour' => $totaltaskhour,
                    'totaltaskminute' => $totaltaskminute,
                ];

                return view('clients.projects.timesheet-edit', compact('timesheet', 'currentWorkspace', 'parseArray', 'project_id'));
            }
        }
    }

    public function projectTimesheetUpdate(Request $request, $slug, $timesheet_id, $project_id)
    {
        $project = Project::find($request->project_id);

        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

        if ($project) {
            $request->validate(
                [
                    'date' => 'required',
                    'time_hour' => 'required',
                    'time_minute' => 'required',
                ]
            );

            $hour = $request->time_hour;
            $minute = $request->time_minute;

            $time = ($hour != '' ? ($hour < 10 ? '0' + $hour : $hour) : '00') . ':' . ($minute != '' ? ($minute < 10 ? '0' + $minute : $minute) : '00');

            $timesheet = Timesheet::find($timesheet_id);
            $timesheet->project_id = $request->project_id;
            $timesheet->task_id = $request->task_id;
            $timesheet->date = $request->date;
            $timesheet->time = $time;
            $timesheet->description = $request->description;
            $timesheet->save();

            return redirect()->back()->with('success', __('Timesheet Updated Successfully!'));
        }
    }

    public function members($slug, $id)
    {

        $project = Project::with('users')->find($id);
        $members = $project->users;
        $data = [];
        foreach ($members as $key => $member) {
            $data[$key]['id'] = $member->id;
            $data[$key]['name'] = $member->name;
        }
        return $data;
    }

}
