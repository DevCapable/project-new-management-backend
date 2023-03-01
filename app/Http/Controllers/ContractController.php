<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\User;
use App\Models\Project;
use App\Models\ContractsType;
use App\Models\Utility;
use App\Models\Client;
use App\Models\ContractsAttachment;
use App\Models\ContractsComment;
use App\Models\ContractsNote;
use App\Models\ClientWorkspace;
use Illuminate\Http\Request;

class ContractController extends Controller
{

    public function index($slug)
    {
       $objUser          = \Auth::user();
       $currentWorkspace = Utility::getWorkspaceBySlug($slug);



         if ($objUser->getGuard() == 'client') {

                $contracts   = Contract::where('workspace_id', '=', $currentWorkspace->id)->where('client_id',$objUser->id)->get();

               $curr_month  = Contract::where('workspace_id', '=', $currentWorkspace->id)->where('client_id',$objUser->id)->whereMonth('start_date', '=', date('m'))->get();
                        $curr_week   = Contract::where('workspace_id', '=', $currentWorkspace->id)->where('client_id',$objUser->id)->whereBetween(
                            'start_date', [
                                            \Carbon\Carbon::now()->startOfWeek(),
                                            \Carbon\Carbon::now()->endOfWeek(),
                                        ]
                        )->get();
                        $last_30days = Contract::where('workspace_id', '=', $currentWorkspace->id)->where('client_id',$objUser->id)->whereDate('start_date', '>', \Carbon\Carbon::now()->subDays(30))->get();

                        // Contracts Summary
                        $cnt_contract                = [];
                        $cnt_contract['total']       = \App\Models\Contract::getContractSummary($currentWorkspace,$contracts);
                        $cnt_contract['this_month']  = \App\Models\Contract::getContractSummary($currentWorkspace,$curr_month);
                        $cnt_contract['this_week']   = \App\Models\Contract::getContractSummary($currentWorkspace,$curr_week);
                        $cnt_contract['last_30days'] = \App\Models\Contract::getContractSummary($currentWorkspace,$last_30days);

                return view('contracts.index', compact('contracts','currentWorkspace','cnt_contract'));


             }

             else{
               $contracts   = Contract::where('workspace_id', '=', $currentWorkspace->id)->get();
               $curr_month  = Contract::where('workspace_id', '=', $currentWorkspace->id)->whereMonth('start_date', '=', date('m'))->get();
                        $curr_week   = Contract::where('workspace_id', '=', $currentWorkspace->id)->whereBetween(
                            'start_date', [
                                            \Carbon\Carbon::now()->startOfWeek(),
                                            \Carbon\Carbon::now()->endOfWeek(),
                                        ]
                        )->get();
                        $last_30days = Contract::where('workspace_id', '=', $currentWorkspace->id)->whereDate('start_date', '>', \Carbon\Carbon::now()->subDays(30))->get();

                        // Contracts Summary
                        $cnt_contract                = [];
                        $cnt_contract['total']       = \App\Models\Contract::getContractSummary($currentWorkspace,$contracts);
                        $cnt_contract['this_month']  = \App\Models\Contract::getContractSummary($currentWorkspace,$curr_month);
                        $cnt_contract['this_week']   = \App\Models\Contract::getContractSummary($currentWorkspace,$curr_week);
                        $cnt_contract['last_30days'] = \App\Models\Contract::getContractSummary($currentWorkspace,$last_30days);

                return view('contracts.index', compact('contracts','currentWorkspace','cnt_contract'));
            }

    }


    public function create($slug)
    {
          $objUser = \Auth::user();
           $currentWorkspace = Utility::getWorkspaceBySlug($slug);
            $client = Client::select(
                [
                    'clients.*',
                    'client_workspaces.is_active',
                ]
            )->join('client_workspaces', 'client_workspaces.client_id', '=', 'clients.id')->where('client_workspaces.workspace_id', '=', $currentWorkspace->id)->get()->pluck('name', 'id');

            $contractType = ContractsType::where('workspace_id', '=', $currentWorkspace->id)->get()->pluck('name', 'id');

          if ($objUser->getGuard() == 'client') {
            $projects = Project::select('projects.*')->join('client_projects', 'projects.id', '=', 'client_projects.project_id')->where('client_projects.client_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->get()->pluck('name','id');
           } else {
            $projects = Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->get()->pluck('name','id');
          }

        return view('contracts.create', compact('client','contractType','projects','currentWorkspace'));
    }


    public function store(Request $request ,$slug)
    {

        $objUser          = \Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

         if($currentWorkspace->created_by == $objUser->id)
       {
         $rules     = [
                'client_id'=>'required',
                'type'=>'required',
                'start_date'=>'required',
                'end_date'=>'required',
                'status'=> 'required',
            ];
            $validator = \Validator::make($request->all(), $rules);

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }


            $contract              = new Contract();
            $contract->client_id   = $request->client_id;
            $contract->project_id = $request->project;
            $contract->subject     = $request->subject;
            $contract->value       = $request->value;
            $contract->type        = $request->type;
            $contract->start_date  = $request->start_date;
            $contract->end_date    = $request->end_date;
            $contract->status      = $request->status;
            $contract->description = $request->description;
            $contract->workspace_id= $currentWorkspace->id;
            $contract->save();


             return redirect()->back()->with('success', __('Contract Save Successfully.!'));


        }

        else{

            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }


    public function show($slug,$id)
    {
          $currentWorkspace = Utility::getWorkspaceBySlug($slug);
           $contract = Contract::find($id);

            if(isset($contract) && $contract != null)
           {
                return view('contracts.show', compact('contract', 'currentWorkspace'));
           }
           else
           {
            return redirect()->back()->with('error', __('Permission denied.'));
           }

    }


    public function edit($slug,$id)
    {

      $objUser          = \Auth::user();
      $currentWorkspace = Utility::getWorkspaceBySlug($slug);

       $client = Client::select(
                [
                    'clients.*',
                    'client_workspaces.is_active',
                ]
            )->join('client_workspaces', 'client_workspaces.client_id', '=', 'clients.id')->where('client_workspaces.workspace_id', '=', $currentWorkspace->id)->get()->pluck('name', 'id');

        $contractType = ContractsType::where('workspace_id', '=', $currentWorkspace->id)->get()->pluck('name', 'id');


        if ($objUser->getGuard() == 'client') {
            $projects = Project::select('projects.*')->join('client_projects', 'projects.id', '=', 'client_projects.project_id')->where('client_projects.client_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->get()->pluck('name','id');
           } else {
            $projects = Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->get()->pluck('name','id');
          }

         if($currentWorkspace->created_by == $objUser->id)
           {
            $contracts = Contract::find($id);
            return view('contracts.edit', compact('currentWorkspace', 'contracts','projects','contractType','client'));

           }
          else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }


    }


    public function update(Request $request,$slug,$id)
    {
       $contract = Contract::find($id);
          $objUser          = \Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        if($currentWorkspace->created_by == $objUser->id)
        {

            $rules     = [
                 'client_id'=>'required',
                'type'=>'required',
                'start_date'=>'required',
                'end_date'=>'required',
                'status'=> 'required',
            ];
            $validator = \Validator::make($request->all(), $rules);

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $contract->client_id   = $request->client_id;
            $contract->project_id  =  $request->project;
            $contract->subject     = $request->subject;
            $contract->value       = $request->value;
            $contract->type        = $request->type;
            $contract->start_date  = $request->start_date;
            $contract->end_date    = $request->end_date;
            $contract->status      = $request->status;
            $contract->description = $request->description;
            $contract->workspace_id= $currentWorkspace->id;
            $contract->save();

            return redirect()->back()->with('success', __('Contract Type Update Successfully.!'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }


    public function destroy($slug,$id)
      {
           $contract = Contract::find($id);
            $objUser          = \Auth::user();
            $currentWorkspace = Utility::getWorkspaceBySlug($slug);
            if($currentWorkspace->created_by == $objUser->id)
            {
                $contract->delete();

                return redirect()->back()->with('success', __('Contract Deleted Successfully.!'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }

    }


    public function copycontract($slug,$id)
    {

    $objUser          = \Auth::user();
      $currentWorkspace = Utility::getWorkspaceBySlug($slug);

       $client = Client::select(
                [
                    'clients.*',
                    'client_workspaces.is_active',
                ]
            )->join('client_workspaces', 'client_workspaces.client_id', '=', 'clients.id')->where('client_workspaces.workspace_id', '=', $currentWorkspace->id)->get()->pluck('name', 'id');

        $contractType = ContractsType::where('workspace_id', '=', $currentWorkspace->id)->get()->pluck('name', 'id');


        if ($objUser->getGuard() == 'client') {
            $projects = Project::select('projects.*')->join('client_projects', 'projects.id', '=', 'client_projects.project_id')->where('client_projects.client_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->get()->pluck('name','id');
           } else {
            $projects = Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->get()->pluck('name','id');
          }

         if($currentWorkspace->created_by == $objUser->id)
           {
            $contracts = Contract::find($id);
            return view('contracts.copy', compact('currentWorkspace', 'contracts','projects','contractType','client'));

           }
          else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
    }


     public function copycontractstore(Request $request,$slug,$id)
     {

        $objUser          = \Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

         if($currentWorkspace->created_by == $objUser->id)
       {
         $rules     = [
                'client_id'=>'required',
                'type'=>'required',
                'start_date'=>'required',
                'end_date'=>'required',
                'status'=> 'required',
            ];
            $validator = \Validator::make($request->all(), $rules);

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }


            $contract              = new Contract();
            $contract->client_id   = $request->client_id;
            $contract->project_id  = $request->project;
            $contract->subject     = $request->subject;
            $contract->value       = $request->value;
            $contract->type        = $request->type;
            $contract->start_date  = $request->start_date;
            $contract->end_date    = $request->end_date;
            $contract->status      = $request->status;
            $contract->description = $request->description;
            $contract->workspace_id= $currentWorkspace->id;
            $contract->save();


             return redirect()->back()->with('success', __('Contract Copy Successfully.!'));


        }

        else{

            return redirect()->back()->with('error', __('Permission denied.'));
        }

     }

      public function clientByProject($id)
    {
       $projects = Project::select('projects.*')->join('client_projects', 'projects.id', '=', 'client_projects.project_id')->where('client_projects.client_id', '=', $id)->get();

        $users=[];
        foreach($projects as $key => $value )
        {
            $users[]=[
                'id' => $value->id,
                'name' => $value->name,
            ];
        }
        // dd($projects);
        return \Response::json($users);
    }


 public function contract_descriptionStore(Request $request,$slug,$id){



        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $contract        = Contract::find($id);
        $contract->contract_description = $request->contract_description;
        $contract->save();
        return redirect()->back()->with('success', __('Description successfully saved.'));

 }

  public function fileUpload( Request $request ,$slug,$id)
    {

        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $contract = Contract::find($id);
        $request->validate(['file' => 'required|mimes:zip,rar,jpeg,jpg,png,gif,svg,pdf,txt,doc,docx,application/octet-stream,audio/mpeg,mpga,mp3,wav|max:204800']);


        // $file_name = $request->file->getClientOriginalName();
        // $file_path = $contract->id . "_" . md5(time()) . "_" . $request->file->getClientOriginalName();
        // $request->file->storeAs('contract_files', $file_path);


         $files = $request->file->getClientOriginalName();
            $request->file->storeAs('contract_attechment', $files);

            $client_keyword = \Auth::user()->getGuard() == 'client' ? 'client.' : '';



 if (\Auth::user()->getGuard() == 'client'){

     $file         = ContractsAttachment::create(
                [
                    'contract_id' => $contract->id,
                     'client_id' => \Auth::user()->id,
                    'files' => $files,
                    'workspace_id'=>$currentWorkspace->id,
                ]
            );


 }

 else{


     $file         = ContractsAttachment::create(
                [
                    'contract_id' => $contract->id,
                     'user_id' => \Auth::user()->id,
                    'files' => $files,
                    'workspace_id'=>$currentWorkspace->id,
                ]
            );

 }




        $return = [];
        $return['is_success'] = true;
        $return['download'] = route($client_keyword.
            'contracts.file.download', [
                $slug,
                $contract->id,
                $file->id,
            ]
        );
        $return['delete'] = route($client_keyword.
            'contracts.file.delete', [
                $slug,
                $contract->id,
                $file->id,
            ]
        );

        return response()->json($return);
    }



        public function fileDelete($slug,$file_id)
    {
        // $contract = Contracts::find($id);
      $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $file =  ContractsAttachment::find($file_id);
        if($file)
        {
            $path = storage_path('contract_attechment/' . $file->files);
            if(file_exists($path))
            {
                \File::delete($path);
            }
            $file->delete();
            return redirect()->back()->with('success', __('contract file successfully deleted.'));
        }
        else
        {
            return response()->json(
                [
                    'is_success' => false,
                    'error' => __('File is not exist.'),
                ], 200
            );
        }
    }


        public function commentStore(Request $request ,$slug,$id)
    {
            $objUser          = \Auth::user();
            $currentWorkspace = Utility::getWorkspaceBySlug($slug);
            $contract              = new ContractsComment();
            $contract->comment     = $request->comment;
            $contract->contract_id = $id;
            if($objUser->getGuard() == 'client'){
                 $contract->client_id     = \Auth::user()->id;
            }
            else{
                $contract->user_id     = \Auth::user()->id;
            }

            $contract->workspace_id = $currentWorkspace->id;
            $contract->save();

             return redirect()->back()->with('success', __('comment successfully created!'));


    }



        public function commentDestroy( $slug, $id)
    {

        $objUser          = \Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $contract = ContractsComment::where('id',$id)->where('workspace_id',$currentWorkspace->id)->first();


            $contract->delete();

            return redirect()->back()->with('success', __('Comment successfully deleted!'));

    }



     public function noteStore(Request $request ,$slug,$id)
    {
            $objUser          = \Auth::user();
            $currentWorkspace = Utility::getWorkspaceBySlug($slug);
            $notes                 = new ContractsNote();
            $notes->contract_id    = $id;
            $notes->notes           = $request->notes;
            if($objUser->getGuard() == 'client'){
                 $notes->client_id     = \Auth::user()->id;
            }
            else{
                $notes->user_id     = \Auth::user()->id;
            }
            $notes->workspace_id = $currentWorkspace->id;


            $notes->save();
            return redirect()->back()->with('success', __('Note successfully saved.'));


    }


    public function noteDestroy($slug,$id)
    {
        $objUser          = \Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $contract = ContractsNote::where('id',$id)->where('workspace_id',$currentWorkspace->id)->first();


            $contract->delete();

            return redirect()->back()->with('success', __('Note successfully deleted!'));
    }




     public function signature($slug,$id)
    {
        $objUser          = \Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $contract = Contract::where('id',$id)->where('workspace_id',$currentWorkspace->id)->first();

        return view('contracts.signature', compact('contract','currentWorkspace'));
    }


    public function signatureStore(Request $request,$slug)
    {
        $objUser          = \Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $contract              = Contract::find($request->contract_id);

        if($currentWorkspace->permission == 'Owner'){
            $contract->company_signature       = $request->company_signature;
        }
        if(\Auth::user()->getGuard() == 'client'){
            $contract->client_signature       = $request->client_signature;
        }

        $contract->save();

        return response()->json(
            [
                'success' => true,
                'message' => __('Contract Signed successfully'),
            ], 200
        );

    }
       public function pdffromcontract($slug,$contract_id)
    {
        $id = \Illuminate\Support\Facades\Crypt::decrypt($contract_id);

         $objUser          = \Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

        $contract  = Contract::findOrFail($id);

        //Set your logo
         $client   = $contract->client_id;
        $company_logo = Utility::getcompanylogo($currentWorkspace->id);

        return view('contracts.template', compact('contract','client','company_logo','currentWorkspace'));
    }

    public function printContract($slug,$id)
    {

        $objUser          = \Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $contract  = Contract::findOrFail($id);

        $client   = $contract->client_id;
        $company_logo = Utility::getcompanylogo($currentWorkspace->id);
        return view('contracts.contract_view', compact('contract','client','company_logo','currentWorkspace'));

    }


        public function sendmailContract(Request $request,$slug,$id)
    {
        $contract              = Contract::find($id);
        $contractArr = [
            'contract_id' => $contract->id,
        ];
        $client = Client::find($contract->client_id);
        $project = Project::find($contract->project_id);
        $contractType = ContractsType::find($contract->type);

         try
       {
        $estArr = [
            'client_name' => $client->name,
            'contract_subject' => $contract->subject,
            'project_name' => $project->name,
            'contract_type' => $contractType->name,
            'value' => $contract->value,
            'start_date' =>$contract->start_date ,
            'end_date' =>$contract->end_date ,
        ];



        // Send Email
        $resp = Utility::sendclientEmailTemplate('Contract Share', $client->id, $estArr);
          return redirect()->back()->with('success', __('Mail Send successfully!'));

         }
                    catch(\Exception $e)
                    {

                        $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
                        return redirect()->back()->with('error', __('E-Mail has been not sent due to SMTP configuration'));
                    }

    }












}
