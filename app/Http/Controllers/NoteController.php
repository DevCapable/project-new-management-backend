<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\User;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{

    public function index($slug)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $personal_notes = Note::where('type','=','personal')->where('workspace','=',$currentWorkspace->id)->where('created_by','=',Auth::user()->id)->get();
        $shared_notes = Note::where('type','=','shared')->where('workspace','=',$currentWorkspace->id)
                            ->whereRaw("find_in_set('".Auth::user()->id."',notes.assign_to)")
                            ->get();

        return view('notes.index',compact('currentWorkspace','personal_notes', 'shared_notes'));
    }


    public function create($slug)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        if($currentWorkspace)
        {
            $users = User::select('users.*', 'user_workspaces.permission', 'user_workspaces.is_active')
                         ->join('user_workspaces', 'user_workspaces.user_id', '=', 'users.id')
                         ->where('user_workspaces.workspace_id', '=', $currentWorkspace->id)
                         ->where('users.id', '!=', Auth::user()->id)
                         ->get();
        }
        else
        {
            $users = User::where('type', '!=', 'admin')->get();
        }

        return view('notes.create',compact('currentWorkspace', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($slug,Request $request)
    {
        $request->validate([
                               'title' => 'required',
                               'text' => 'required',
                               'color' => 'required',
                           ]);
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $objUser = Auth::user();
        $post = $request->all();
        $post['type'] = $request->type;

        $assign_to = $request->assign_to;
        $assign_to[] = Auth::user()->id;
        $post['assign_to'] = implode(',', $assign_to);
        $post['workspace'] = $currentWorkspace->id;
        $post['created_by'] = $objUser->id;
        Note::create($post);
        return redirect()->route('notes.index',$currentWorkspace->slug)->with('success',__('Note Created Successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function show(Note $note)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function edit($slug,$noteID)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $note = Note::where('workspace','=',$currentWorkspace->id)->where('created_by','=',Auth::user()->id)->where('id','=',$noteID)->first();
        $note->assign_to = explode(',', $note->assign_to);

        if($currentWorkspace)
        {
            $users = User::select('users.*', 'user_workspaces.permission', 'user_workspaces.is_active')
                         ->join('user_workspaces', 'user_workspaces.user_id', '=', 'users.id')
                         ->where('user_workspaces.workspace_id', '=', $currentWorkspace->id)
                         ->where('users.id', '!=', Auth::user()->id)
                         ->get();
        }
        else
        {
            $users = User::where('type', '!=', 'admin')->get();
        }

        return view('notes.edit',compact('currentWorkspace','note', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug,$noteID)
    {
        $request->validate([
                               'title' => 'required',
                               'text' => 'required',
                               'color' => 'required',
                           ]);
        $objUser = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $notes = Note::where('workspace','=',$currentWorkspace->id)->where('created_by','=',Auth::user()->id)->where('id','=',$noteID)->first();

        $post = $request->all();
        $post['type'] = $request->type;

        $assign_to = $request->assign_to;
        $assign_to[] = Auth::user()->id;
        $post['assign_to'] = implode(',', $assign_to);

        $notes->update($post);
        return redirect()->route('notes.index',$slug)->with('success',__('Note Updated Successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug,$noteID)
    {
        $objUser = Auth::user();
        $note = Note::find($noteID);
        if($note->created_by == $objUser->id) {
            $note->delete();
            return redirect()->route('notes.index',$slug)->with('success',__('Note Deleted Successfully!'));
        }
        else{
            return redirect()->route('notes.index',$slug)->with('error',__("You can't delete Note!"));
        }
    }
}
