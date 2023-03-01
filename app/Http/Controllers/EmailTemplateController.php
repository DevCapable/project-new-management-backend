<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\EmailTemplateLang;
use App\Models\UserEmailTemplate;
use App\Models\Utility;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lang = 'en';
//        $currentWorkspace = Utility::getWorkspaceBySlug('');
        $currentWorkspace = Utility::getAdminWorkspaceBySlug($slug='en');

        $EmailTemplates = EmailTemplate::all();

         $languages      = Utility::languages();
         $emailTemplate  = EmailTemplate::where('id', '=', 1)->first();
         $currEmailTempLang = EmailTemplateLang::where('parent_id', '=', 1)->where('lang', 'en')->first();

            if(!isset($currEmailTempLang) || empty($currEmailTempLang))
            {
                $currEmailTempLang       = EmailTemplateLang::where('parent_id', '=', $id)->where('lang', 'en')->first();
                $currEmailTempLang->lang = $lang;
            }

          return view('email_templates.show', compact('EmailTemplates','emailTemplate', 'languages', 'currEmailTempLang','currentWorkspace'));

    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show(EmailTemplate $emailTemplate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmailTemplate  $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function edit(EmailTemplate $emailTemplate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmailTemplate  $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = \Validator::make(
                $request->all(), [
                                   'subject' => 'required',
                                   'content' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $emailLangTemplate = EmailTemplateLang::where('parent_id', '=', $id)->where('lang', '=', $request->lang)->first();

            // if record not found then create new record else update it.
            if(empty($emailLangTemplate))
            {
                $emailLangTemplate            = new EmailTemplateLang();
                $emailLangTemplate->parent_id = $id;
                $emailLangTemplate->lang      = $request['lang'];
                $emailLangTemplate->subject   = $request['subject'];
                $emailLangTemplate->content   = $request['content'];
                $emailLangTemplate->from   = $request['from'];
                $emailLangTemplate->save();
            }
            else
            {
                $emailLangTemplate->subject = $request['subject'];
                $emailLangTemplate->content = $request['content'];
                $emailLangTemplate->from   = $request['from'];
                $emailLangTemplate->save();
            }

            return redirect()->route(
                'manage.email.language', [
                                           $id,
                                           $request->lang,
                                       ]
            )->with('success', __('Email Template Detail successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmailTemplate  $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmailTemplate $emailTemplate)
    {
        //
    }

    public function manageEmailLang($id, $lang = 'en')
    {
             $EmailTemplates = EmailTemplate::all();
            $languages         = Utility::languages();
            $emailTemplate     = EmailTemplate::where('id', '=', $id)->first();
            $currEmailTempLang = EmailTemplateLang::where('parent_id', '=', $id)->where('lang', $lang)->first();

            if(!isset($currEmailTempLang) || empty($currEmailTempLang))
            {
                $currEmailTempLang       = EmailTemplateLang::where('parent_id', '=', $id)->where('lang', 'en')->first();
                $currEmailTempLang->lang = $lang;
            }

            return view('email_templates.show', compact('EmailTemplates','emailTemplate', 'languages', 'currEmailTempLang'));

    }




    // Used For Store Email Template Language Wise
    public function storeEmailLang(Request $request, $id)
    {

            $validator = \Validator::make(
                $request->all(), [
                                   'subject' => 'required',
                                   'content' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $emailLangTemplate = EmailTemplateLang::where('parent_id', '=', $id)->where('lang', '=', $request->lang)->first();

            // if record not found then create new record else update it.
            if(empty($emailLangTemplate))
            {
                $emailLangTemplate            = new EmailTemplateLang();
                $emailLangTemplate->parent_id = $id;
                $emailLangTemplate->lang      = $request['lang'];
                $emailLangTemplate->subject   = $request['subject'];
                $emailLangTemplate->content   = $request['content'];
                $emailLangTemplate->save();
            }
            else
            {
                $emailLangTemplate->subject = $request['subject'];
                $emailLangTemplate->content = $request['content'];
                $emailLangTemplate->save();
            }

            return redirect()->route(
                'manage.email.language', [
                                           $id,
                                           $request->lang,
                                       ]
            )->with('success', __('Email Template Detail successfully updated.'));

    }

    // Used For Update Status Company Wise.
    public function updateStatus(Request $request, $id, $slug)
    {

        $usr = \Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

            $user_email = UserEmailTemplate::where('id', '=', $id)->where('user_id', '=', $usr->id)->first();


            if( $user_email =="" ||  $user_email == null){
            $user_email = UserEmailTemplate::create(
                                        [
                                            'template_id' => 1,
                                            'user_id' =>$usr->id,
                                            'workspace_id'=>$currentWorkspace->id,
                                            'is_active' => 0,
                                        ]
                                    );

             $user_email = UserEmailTemplate::create(
                                        [
                                            'template_id' => 2,
                                            'user_id' =>$usr->id,
                                            'workspace_id'=>$currentWorkspace->id,
                                            'is_active' => 0,
                                        ]
                                    );

            $user_email = UserEmailTemplate::create(
                                        [
                                            'template_id' => 3,
                                            'user_id' =>$usr->id,
                                            'workspace_id'=>$currentWorkspace->id,
                                            'is_active' => 0,
                                        ]
                                    );

              $user_email = UserEmailTemplate::create(
                                        [
                                            'template_id' => 4,
                                            'user_id' =>$usr->id,
                                            'workspace_id'=>$currentWorkspace->id,
                                            'is_active' => 0,
                                        ]
                                    );

            }

            if(!empty($user_email))
            {
                if($request->status == 1)
                {
                    $user_email->is_active = 0;
                }
                else
                {
                    $user_email->is_active = 1;
                }

                $user_email->save();

                return response()->json(
                    [
                        'is_success' => true,
                        'success' => __('Status successfully updated!'),
                    ], 200
                );

        }
    }


}
