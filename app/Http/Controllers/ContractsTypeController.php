<?php

namespace App\Http\Controllers;

use App\Models\ContractsType;
use App\Models\Utility;
use Illuminate\Http\Request;

class ContractsTypeController extends Controller
{
    
    public function index($slug)
    {
        $objUser          = \Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $contractTypes = ContractsType::where('workspace_id', '=', $currentWorkspace->id)->get();
        return view('contracts.contract_type', compact('currentWorkspace','contractTypes'));
 
    }

   
    public function create($slug)
    {
         $currentWorkspace = Utility::getWorkspaceBySlug($slug);
          return view('contracts.contracttype_create',compact('currentWorkspace'));
    }

   
    public function store(Request $request ,$slug)
    {
        $objUser          = \Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

         if($currentWorkspace->created_by == $objUser->id)
       {
         $rules     = [
                'name' => 'required',
            ];
            $validator = \Validator::make($request->all(), $rules);

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }


            $contract_type         = new ContractsType();
            $contract_type->name       = $request->name;
            $contract_type->workspace_id = $currentWorkspace->id;
            $contract_type->save();


             return redirect()->back()->with('success', __('Contract Type Save Successfully.!'));


        }

        else{

            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

   
    public function show(ContractsType $contractsType)
    {
       
    }

    public function edit($slug,$id)
    {
         $objUser          = \Auth::user();
      $currentWorkspace = Utility::getWorkspaceBySlug($slug);
      if($currentWorkspace->created_by == $objUser->id)
           {   
            $contractsType = ContractsType::find($id);
            return view('contracts.contracttype_edit', compact('currentWorkspace', 'contractsType'));

           }
      else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }   
    }
    public function update(Request $request,$slug,$id)
    {

         $contractsType = ContractsType::find($id);
          $objUser          = \Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        if($currentWorkspace->created_by == $objUser->id)
        {

            $rules     = [
                'name' => 'required',
            ];
            $validator = \Validator::make($request->all(), $rules);

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $contractsType->name = $request->name;
            $contractsType->workspace_id = $currentWorkspace->id;
            $contractsType->save();

            return redirect()->back()->with('success', __('Contract Type Update Successfully.!'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
        
    }

    public function destroy($slug,$id)
    {
        $contractsType = ContractsType::find($id);
        $objUser          = \Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        if($currentWorkspace->created_by == $objUser->id)
        {
            $contractsType->delete();

            return redirect()->back()->with('success', __('Contract Type Deleted Successfully.!'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }





       
    }
}
