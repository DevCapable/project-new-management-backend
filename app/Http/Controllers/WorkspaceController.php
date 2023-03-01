<?php

namespace App\Http\Controllers;

use App\Models\BugReport;
use App\Models\BugStage;
use App\Models\Project;
use App\Models\Stage;
use App\Models\Task;
use App\Models\Tax;
use Artisan;
use App\Models\UserProject;
use App\Models\UserWorkspace;
use App\Models\Utility;
use App\Models\Workspace;
use App\Models\EmailTemplate;
use App\Models\Mail\EmailTest;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WorkspaceController extends Controller
{

    public function store(Request $request)
    {
        $objUser = Auth::user();

        $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                ],
            ]
        );

        $objWorkspace = Workspace::create(
            [
                'created_by' => $objUser->id,
                'name' => $request->name,
                'currency_code' => 'USD',
                'paypal_mode' => 'sandbox',
            ]
        );

        UserWorkspace::create(
            [
                'user_id' => $objUser->id,
                'workspace_id' => $objWorkspace->id,
                'permission' => 'Owner',
            ]
        );

        $objUser->currant_workspace = $objWorkspace->id;
        $objUser->save();

        return redirect()->route('home', $objWorkspace->slug)->with('success', __('Workspace Created Successfully!'));
    }


       public function destroy($workspaceID)
    {
        $objUser   = Auth::user();
        $workspace = Workspace::find($workspaceID);
        $all_workspaces = Workspace::get();


        if($workspace->created_by == $objUser->id)
        {

          if(count($all_workspaces) > 1)
           {
            UserWorkspace::where('workspace_id', '=', $workspaceID)->delete();
            Stage::where('workspace_id', '=', $workspaceID)->delete();
            $workspace->delete();
            $work_space = Workspace::first();

             UserWorkspace::create(
                    [
                        'user_id' => $objUser->id,
                        'workspace_id' => $work_space->id,
                        'permission' => 'Owner',
                    ]
                );
             $objUser->currant_workspace = $work_space->id;
             $objUser->save();

           }
        else
        {
          return redirect()->back()->with('error', __("You can't delete Workspace!"));
        }
            return redirect()->route('home')->with('success', __('Workspace Deleted Successfully!'));
        }
        else
        {
            return redirect()->route('home')->with('error', __("You can't delete Workspace!"));
        }
    }

    public function leave($workspaceID)
    {

        $objUser = Auth::user();
        $all_workspaces = Workspace::get();
        $userProjects = Project::where('workspace', '=', $workspaceID)->get();


        if(count($all_workspaces) > 1)
           {
              $work_space = Workspace::first();

             $user_Project = Project::where('workspace', '=', $work_space->id)->get();

             foreach($userProjects as $userProject)
            {
              UserProject::where('project_id', '=', $userProject->id)->where('user_id', '=', $objUser->id)->delete();
            }

              UserWorkspace::where('workspace_id', '=', $workspaceID)->where('user_id', '=', $objUser->id)->delete();


             foreach($user_Project as $userProject_s)
             {
              UserProject::create(
                    [
                        'user_id' => $objUser->id,
                        'project_id' => $userProject_s->id,

                    ]
                );
          }

             UserWorkspace::create(
                    [
                        'user_id' => $objUser->id,
                        'workspace_id' => $work_space->id,
                        'permission' => 'Owner',
                    ]
                );
             $objUser->currant_workspace = $work_space->id;
             $objUser->save();

           }
        else
        {
          return redirect()->back()->with('error', __("You can't delete Workspace!"));
        }

        return redirect()->route('home')->with('success', __('Workspace Leave Successfully!'));
    }

    public function changeCurrentWorkspace($workspaceID)
    {
        $objWorkspace = Workspace::find($workspaceID);
        if($objWorkspace->is_active)
        {
            $currentWorkspace           = Utility::getWorkspaceBySlug($objWorkspace->slug);
            $objUser                    = Auth::user();
            $objUser->currant_workspace = $workspaceID;
            $objUser->save();

            return redirect()->route('home')->with('success', __('Workspace Change Successfully!'));
        }
        else
        {
            return redirect()->back()->with('error', __('Workspace is locked'));
        }
    }

    public function changeLangAdmin($lang)
    {
        if(Auth::user()->type == 'admin' && app('App\Http\Controllers\SettingsController')->setEnvironmentValue(['DEFAULT_ADMIN_LANG' => $lang]))
        {
            Artisan::call('config:cache');
            Artisan::call('config:clear');
            return redirect()->back();
        }
        else
        {
            return redirect()->back()->with('error', __('Something is wrong'));
        }
    }

    public function changeLangWorkspace1($workspaceID, $lang)
    {

        $workspace       = Workspace::find($workspaceID);
        $workspace->lang = $lang;
        $workspace->save();
        return redirect()->back()->with('success', __('Workspace Language Change Successfully!'));
    }
    public function changeLangWorkspace($workspaceID, $lang)
    {

        $workspace       = Workspace::find($workspaceID);
        $workspace->lang = $lang;
        $workspace->save();

        return redirect()->back()->with('success', __('Workspace Language Change Successfully!'));
    }

    public function langWorkspace($currantLang = '')
    {
        $objUser = Auth::user();
        if($objUser->type == 'admin')
        {
            $currentWorkspace = Utility::getAdminWorkspaceBySlug($slug='en');

            $dir = base_path() . '/resources/lang/' . $currantLang;
            if(!empty($currantLang))
            {
                $dir = base_path() . '/resources/lang/' . $currantLang;
                if(!is_dir($dir))
                {
                    $dir = base_path() . '/resources/lang/en';
                }
            }
            else
            {
                $currantLang = env('DEFAULT_LANG') ?? 'en';
                $dir         = base_path() . '/resources/lang/' . $currantLang;
            }

            $arrLabel = json_decode(file_get_contents($dir . '.json'));

            $arrFiles   = array_diff(
                scandir($dir), array(
                                 '..',
                                 '.',
                             )
            );
            $arrMessage = [];
            foreach($arrFiles as $file)
            {
                $fileName = basename($file, ".php");
                $fileData = $myArray = include $dir . "/" . $file;
                if(is_array($fileData))
                {
                    $arrMessage[$fileName] = $fileData;
                }
            }
            $workspace = new Workspace();

            return view('lang.index', compact('workspace', 'currantLang', 'arrLabel', 'arrMessage','currentWorkspace'));
        }
        else
        {
            redirect()->route('home');
        }
    }

    public function storeLangDataWorkspace($currantLang, Request $request)
    {

        $objUser = Auth::user();
        if($objUser->type == 'admin')
        {
            $dir      = base_path() . '/resources/lang';
            $jsonFile = $dir . "/" . $currantLang . ".json";

            file_put_contents($jsonFile, json_encode($request->label));

            $langFolder = $dir . "/" . $currantLang;

            foreach($request->message as $fileName => $fileData)
            {
                $content = "<?php return [";
                $content .= $this->buildArray($fileData);
                $content .= "];";
                file_put_contents($langFolder . "/" . $fileName . '.php', $content);
            }

            return redirect()->route('lang_workspace', [$currantLang])->with('success', __('Language Save Successfully!'));
        }
        else
        {
            redirect()->route('home');
        }
    }

    public function buildArray($fileData)
    {
        $content = "";
        foreach($fileData as $label => $data)
        {
            if(is_array($data))
            {
                $content .= "'$label'=>[" . $this->buildArray($data) . "],";
            }
            else
            {
                $content .= "'$label'=>'" . addslashes($data) . "',";
            }
        }

        return $content;
    }

    public function createLangWorkspace()
    {
        $objUser = Auth::user();
        if($objUser->type == 'admin')
        {
            return view('lang.create');
        }
        else
        {
            redirect()->route('home');
        }
    }

    public function storeLangWorkspace(Request $request)
    {
        $objUser = Auth::user();
        if($objUser->type == 'admin')
        {

            $Filesystem = new Filesystem();
            $langCode   = strtolower($request->code);

            $langDir = base_path() . '/resources/lang/';
            $dir     = $langDir;

            $dir      = $dir . $langCode;
            $jsonFile = $dir . ".json";
            \File::copy($langDir . 'en.json', $jsonFile);

            if(!is_dir($dir))
            {
                mkdir($dir);
                chmod($dir, 0777);
            }
            $Filesystem->copyDirectory($langDir . "en", $dir . "/");

            return redirect()->route('lang_workspace', [$langCode])->with('success', __('Language Created Successfully!'));
        }
        else
        {
            redirect()->route('home');
        }
    }

    public function destroyLang($lang)
    {
        $default_lang = env('DEFAULT_LANG') ?? 'en';

        $langDir = base_path() . '/resources/lang/';
        if(is_dir($langDir))
        {
            // remove directory and file
            Utility::delete_directory($langDir . $lang);
            unlink($langDir . $lang . '.json');
            // update user that has assign deleted language.
            Workspace::where('lang', 'LIKE', $lang)->update(['lang' => $default_lang]);
        }

        return redirect()->route('lang_workspace', $default_lang)->with('success', __('Language Deleted Successfully!'));
    }

    public function rename($workspaceID)
    {
        $objUser          = Auth::user();
        $workspace        = Workspace::find($workspaceID);
        $currentWorkspace = Utility::getWorkspaceBySlug($workspace->slug);
        if($currentWorkspace && $workspace->created_by == $objUser->id)
        {
            return view('users.rename_workspace', compact('workspace'));
        }
        else
        {
            return redirect()->route('home')->with('error', __("You can't rename Workspace!"));
        }
    }

    public function update(Request $request, $id)
    {
        $objUser   = Auth::user();
        $workspace = Workspace::find($id);

        if($workspace->created_by == $objUser->id)
        {
            $workspace->name = $request->name;
            $workspace->save();

            return redirect()->route('home')->with('success', __('Rename Successfully.!'));
        }
        else
        {
            return redirect()->route('home')->with('error', __('You can\'t rename Workspace!'));
        }
    }

    public function settings($slug)
    {
        $objUser          = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

        if($currentWorkspace->created_by == $objUser->id)
        {
            $taxes     = Tax::where('workspace_id', '=', $currentWorkspace->id)->get();
            $stages    = Stage::where('workspace_id', '=', $currentWorkspace->id)->orderBy('order')->get();
            $bugStages = BugStage::where('workspace_id', '=', $currentWorkspace->id)->orderBy('order')->get();

            $colors = [
                '003580',
                '666666',
                '6677ef',
                'f50102',
                'f9b034',
                'fbdd03',
                'c1d82f',
                '37a4e4',
                '8a7966',
                '6a737b',
                '050f2c',
                '0e3666',
                '3baeff',
                '3368e6',
                'b84592',
                'f64f81',
                'f66c5f',
                'fac168',
                '46de98',
                '40c7d0',
                'be0028',
                '2f9f45',
                '371676',
                '52325d',
                '511378',
                '0f3866',
                '48c0b6',
                '297cc0',
                'ffffff',
                '000000',
            ];
              $EmailTemplates = EmailTemplate::all();

            $payment_detail = Utility::getPaymentSetting($currentWorkspace->id);

            return view('users.setting', compact('currentWorkspace','EmailTemplates', 'taxes', 'stages', 'bugStages', 'colors', 'payment_detail'));
        }
        else
        {
            return redirect()->route('home')->with('error', __("You can't access workspace settings!"));
        }
    }

    public function settingsStore($slug, Request $request)
    {

        $objUser          = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

        if($currentWorkspace->created_by == $objUser->id)
        {
            $validate      = [];
            $stripe_status = $request->is_stripe_enabled == 'on' ? 1 : 0;
            $paypal_status = $request->is_paypal_enabled == 'on' ? 1 : 0;

            if($stripe_status == 1)
            {
                $validate['stripe_key']    = 'required|string|max:255';
                $validate['stripe_secret'] = 'required|string|max:255';
            }
            if($paypal_status == 1)
            {
                $validate['paypal_client_id']  = 'required|string|max:255';
                $validate['paypal_secret_key'] = 'required|string|max:255';
            }

            if(isset($request->is_paystack_enabled) && $request->is_paystack_enabled == 'on')
            {
                $validate['paystack_public_key'] = 'required|string';
                $validate['paystack_secret_key'] = 'required|string';
            }
            if(isset($request->is_flutterwave_enabled) && $request->is_flutterwave_enabled == 'on')
            {
                $validate['flutterwave_public_key'] = 'required|string';
                $validate['flutterwave_secret_key'] = 'required|string';
            }
            if(isset($request->is_razorpay_enabled) && $request->is_razorpay_enabled == 'on')
            {
                $validate['razorpay_public_key'] = 'required|string';
                $validate['razorpay_secret_key'] = 'required|string';
            }
            if(isset($request->is_mercado_enabled) && $request->is_mercado_enabled == 'on')
            {
                  $validate['mercado_access_token']     = 'required|string';
                $validate['mercado_mode'] = 'required|string';
            }
            if(isset($request->is_paytm_enabled) && $request->is_paytm_enabled == 'on')
            {
                $validate['paytm_mode']          = 'required|string';
                $validate['paytm_merchant_id']   = 'required|string';
                $validate['paytm_merchant_key']  = 'required|string';
                $validate['paytm_industry_type'] = 'required|string';
            }
            if(isset($request->is_mollie_enabled) && $request->is_mollie_enabled == 'on')
            {
                $validate['mollie_api_key']    = 'required|string';
                $validate['mollie_profile_id'] = 'required|string';
                $validate['mollie_partner_id'] = 'required|string';
            }
            if(isset($request->is_skrill_enabled) && $request->is_skrill_enabled == 'on')
            {
                $validate['skrill_email'] = 'required|email';
            }
            if(isset($request->is_coingate_enabled) && $request->is_coingate_enabled == 'on')
            {
                $validate['coingate_mode']       = 'required|string';
                $validate['coingate_auth_token'] = 'required|string';
            }
            if($request->has('zoom_api_key')){
                $validate['zoom_api_key']       = 'required';
            }
            if($request->has('zoom_api_secret')){
                $validate['zoom_api_secret']       = 'required';
            }

            $validator = Validator::make(
                $request->all(), $validate
            );

            if($validator->fails())
            {
                return redirect()->back()->with('error', $validator->errors()->first());
            }


            if($request->name)
            {
                if($request->logo)
                {
                    $request->validate(['logo' => 'required|mimes:jpeg,jpg,png,gif,svg|max:204800']);
                    $logoName = 'logo_' . $currentWorkspace->id . '.png';
                    $request->logo->storeAs('logo', $logoName);
                    $currentWorkspace->logo = $logoName;
                }
                  if($request->logo_white)
                {
                    $request->validate(['logo_white' => 'required|mimes:jpeg,jpg,png,gif,svg|max:204800']);
                    $logoName = 'logo-dark.png';
                    $request->logo_white->storeAs('logo', $logoName);
                    $currentWorkspace->logo_white = $logoName;
                }

                $currentWorkspace->theme_color =  (!empty($request->theme_color)) ? $request->theme_color : 'theme-3';
                $currentWorkspace->site_rtl =   !empty($request->site_rtl) ? $request->site_rtl : 'off';

                $currentWorkspace->cust_darklayout = !empty($request->cust_darklayout) ? 'on' : 'off';
                $currentWorkspace->cust_theme_bg = (!empty($request->cust_theme_bg)) ? 'on' : 'off';

                $currentWorkspace->name = $request->name;
                $currentWorkspace->interval_time = $request->interval_time;

            }elseif($request->has('zoom_api_key')){
                $currentWorkspace->zoom_api_key = $request->zoom_api_key;
                $currentWorkspace->zoom_api_secret = $request->zoom_api_secret;

            }
            elseif($request->invoice_template)
            {
                $currentWorkspace->invoice_template = $request->invoice_template;
                $currentWorkspace->invoice_color    = $request->invoice_color;
            }
            elseif($request->has('invoice_footer_title'))
            {
                $currentWorkspace->invoice_footer_title = $request->invoice_footer_title;
                $currentWorkspace->invoice_footer_notes = $request->invoice_footer_notes;
            }
            elseif($request->currency)
            {
                $currentWorkspace->currency          = $request->currency;
                $currentWorkspace->currency_code     = $request->currency_code;
                $currentWorkspace->is_stripe_enabled = $stripe_status;
                $currentWorkspace->stripe_key        = $request->stripe_key;
                $currentWorkspace->stripe_secret     = $request->stripe_secret;
                $currentWorkspace->is_paypal_enabled = $paypal_status;
                $currentWorkspace->paypal_mode       = $request->paypal_mode;
                $currentWorkspace->paypal_client_id  = $request->paypal_client_id;
                $currentWorkspace->paypal_secret_key = $request->paypal_secret_key;

                $post = [];
                // Save Paystack Detail
                if(isset($request->is_paystack_enabled) && $request->is_paystack_enabled == 'on')
                {
                    $post['is_paystack_enabled'] = $request->is_paystack_enabled;
                    $post['paystack_public_key'] = $request->paystack_public_key;
                    $post['paystack_secret_key'] = $request->paystack_secret_key;
                }
                else
                {
                    $post['is_paystack_enabled'] = 'off';
                }

                // Save Fluuterwave Detail
                if(isset($request->is_flutterwave_enabled) && $request->is_flutterwave_enabled == 'on')
                {
                    $post['is_flutterwave_enabled'] = $request->is_flutterwave_enabled;
                    $post['flutterwave_public_key'] = $request->flutterwave_public_key;
                    $post['flutterwave_secret_key'] = $request->flutterwave_secret_key;
                }
                else
                {
                    $post['is_flutterwave_enabled'] = 'off';
                }

                // Save Razorpay Detail
                if(isset($request->is_razorpay_enabled) && $request->is_razorpay_enabled == 'on')
                {
                    $post['is_razorpay_enabled'] = $request->is_razorpay_enabled;
                    $post['razorpay_public_key'] = $request->razorpay_public_key;
                    $post['razorpay_secret_key'] = $request->razorpay_secret_key;
                }
                else
                {
                    $post['is_razorpay_enabled'] = 'off';
                }

                // Save Marcado Detail

                if(isset($request->is_mercado_enabled) && $request->is_mercado_enabled == 'on')
                {
                    $request->validate(
                        [
                            'mercado_access_token' => 'required|string',
                        ]
                    );
                    $post['is_mercado_enabled'] = $request->is_mercado_enabled;
                    $post['mercado_access_token']     = $request->mercado_access_token;
                    $post['mercado_mode'] = $request->mercado_mode;
                }
                else
                {
                    $post['is_mercado_enabled'] = 'off';
                }

                    // Save Paytm Detail
                    if(isset($request->is_paytm_enabled) && $request->is_paytm_enabled == 'on')
                    {
                        $post['is_paytm_enabled']    = $request->is_paytm_enabled;
                        $post['paytm_mode']          = $request->paytm_mode;
                        $post['paytm_merchant_id']   = $request->paytm_merchant_id;
                        $post['paytm_merchant_key']  = $request->paytm_merchant_key;
                        $post['paytm_industry_type'] = $request->paytm_industry_type;
                    }
                    else
                    {
                        $post['is_paytm_enabled'] = 'off';
                    }

                    // Save Mollie Detail
                    if(isset($request->is_mollie_enabled) && $request->is_mollie_enabled == 'on')
                    {
                        $post['is_mollie_enabled'] = $request->is_mollie_enabled;
                        $post['mollie_api_key']    = $request->mollie_api_key;
                        $post['mollie_profile_id'] = $request->mollie_profile_id;
                        $post['mollie_partner_id'] = $request->mollie_partner_id;
                    }
                    else
                    {
                        $post['is_mollie_enabled'] = 'off';
                    }

                    // Save Skrill Detail
                    if(isset($request->is_skrill_enabled) && $request->is_skrill_enabled == 'on')
                    {
                        $post['is_skrill_enabled'] = $request->is_skrill_enabled;
                        $post['skrill_email']      = $request->skrill_email;
                    }
                    else
                    {
                        $post['is_skrill_enabled'] = 'off';
                    }

                    // Save Coingate Detail
                    if(isset($request->is_coingate_enabled) && $request->is_coingate_enabled == 'on')
                    {
                        $post['is_coingate_enabled'] = $request->is_coingate_enabled;
                        $post['coingate_mode']       = $request->coingate_mode;
                        $post['coingate_auth_token'] = $request->coingate_auth_token;
                    }
                    else
                    {
                        $post['is_coingate_enabled'] = 'off';
                    }
                    if(isset($request->is_paymentwall_enabled) && $request->is_paymentwall_enabled == 'on')
                    {
                        $post['is_paymentwall_enabled'] = $request->is_paymentwall_enabled;
                        $post['paymentwall_public_key']       = $request->paymentwall_public_key;
                        $post['paymentwall_private_key'] = $request->paymentwall_private_key;
                    }
                    else
                    {
                        $post['is_paymentwall_enabled'] = 'off';
                    }
                    $created_at = date('Y-m-d H:i:s');
                    $updated_at = date('Y-m-d H:i:s');

                    foreach($post as $key => $data)
                    {
                        \DB::insert(
                            'insert into payment_settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = VALUES(`updated_at`)', [
                                                                                                                                                                                                                                    $data,
                                                                                                                                                                                                                                    $key,
                                                                                                                                                                                                                                    $currentWorkspace->id,
                                                                                                                                                                                                                                    $created_at,
                                                                                                                                                                                                                                    $updated_at,
                                                                                                                                                                                                                                ]
                        );
                    }
                }
                else
                {
                    $currentWorkspace->company   = $request->company;
                    $currentWorkspace->address   = $request->address;
                    $currentWorkspace->city      = $request->city;
                    $currentWorkspace->state     = $request->state;
                    $currentWorkspace->zipcode   = $request->zipcode;
                    $currentWorkspace->country   = $request->country;
                    $currentWorkspace->telephone = $request->telephone;
                }
                $currentWorkspace->save();

            return redirect()->back()->with('success', __('Settings Save Successfully.!'));
        }
        else
        {
            return redirect()->route('home')->with('error', __("You can't access workspace settings!"));
        }
    }

    public function create_tax($slug)
    {
        $objUser          = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        if($currentWorkspace->created_by == $objUser->id)
        {
            return view('users.create_tax', compact('currentWorkspace'));
        }
    }

    public function edit_tax($slug, $id)
    {
        $objUser          = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        if($currentWorkspace->created_by == $objUser->id)
        {
            $tax = Tax::find($id);

            return view('users.edit_tax', compact('currentWorkspace', 'tax'));
        }
    }

    public function store_tax($slug, Request $request)
    {
        $objUser          = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        if($currentWorkspace->created_by == $objUser->id)
        {
            $request->validate(
                [
                    'name' => [
                        'required',
                        'string',
                        'max:255',
                    ],
                    'rate' => ['required'],
                ]
            );
            $tax               = new Tax();
            $tax->name         = $request->name;
            $tax->rate         = $request->rate;
            $tax->workspace_id = $currentWorkspace->id;
            $tax->save();

            return redirect()->back()->with('success', __('Tax Save Successfully.!'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function update_tax($slug, Request $request, $id)
    {
        $objUser          = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        if($currentWorkspace->created_by == $objUser->id)
        {
            $request->validate(
                [
                    'name' => [
                        'required',
                        'string',
                        'max:255',
                    ],
                    'rate' => ['required'],
                ]
            );
            $tax       = Tax::find($id);
            $tax->name = $request->name;
            $tax->rate = $request->rate;
            $tax->save();

            return redirect()->back()->with('success', __('Tax Save Successfully.!'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy_tax($slug, $id)
    {
        $objUser          = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        if($currentWorkspace->created_by == $objUser->id)
        {
            $tax = Tax::find($id);
            $tax->delete();

            return redirect()->back()->with('success', __('Tax Delete Successfully.!'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function store_stages($slug, Request $request)
    {
        $objUser          = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        if($currentWorkspace->created_by == $objUser->id)
        {

            $rules      = [
                'stages' => 'required|present|array',
            ];
            $attributes = [];
            if($request->stages)
            {

                foreach($request->stages as $key => $val)
                {
                    $rules['stages.' . $key . '.name']      = 'required|max:255';
                    $attributes['stages.' . $key . '.name'] = __('Stage Name');
                }
            }
            $validator = \Validator::make($request->all(), $rules, [], $attributes);
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $arrStages = Stage::where('workspace_id', '=', $currentWorkspace->id)->orderBy('order')->pluck('name', 'id')->all();
            $order     = 0;
            foreach($request->stages as $key => $stage)
            {

                $obj = null;
                if($stage['id'])
                {
                    $obj = Stage::find($stage['id']);
                    unset($arrStages[$obj->id]);
                }
                else
                {
                    $obj               = new Stage();
                    $obj->workspace_id = $currentWorkspace->id;
                }
                $obj->name     = $stage['name'];
                $obj->color    = $stage['color'];
                $obj->order    = $order++;
                $obj->complete = 0;
                $obj->save();
            }

            $taskExist = [];
            if($arrStages)
            {
                foreach($arrStages as $id => $name)
                {
                    $count = Task::where('status', '=', $id)->count();
                    if($count != 0)
                    {
                        $taskExist[] = $name;
                    }
                    else
                    {
                        Stage::find($id)->delete();
                    }
                }
            }

            $lastStage = Stage::where('workspace_id', '=', $currentWorkspace->id)->orderBy('order', 'desc')->first();
            if($lastStage)
            {
                $lastStage->complete = 1;
                $lastStage->save();
            }

            if(empty($taskExist))
            {
                return redirect()->back()->with('success', __('Stage Save Successfully.!'));
            }
            else
            {
                return redirect()->back()->with('error', __('Please remove tasks from stage: ' . implode(', ', $taskExist)));
            }

        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function store_bug_stages($slug, Request $request)
    {
        $objUser          = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        if($currentWorkspace->created_by == $objUser->id)
        {
            $rules      = [
                'stages' => 'required|present|array',
            ];
            $attributes = [];
            if($request->stages)
            {

                foreach($request->stages as $key => $val)
                {
                    $rules['stages.' . $key . '.name']      = 'required|max:255';
                    $attributes['stages.' . $key . '.name'] = __('Stage Name');
                }
            }
            $validator = \Validator::make($request->all(), $rules, [], $attributes);
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $arrStages = BugStage::where('workspace_id', '=', $currentWorkspace->id)->orderBy('order')->pluck('name', 'id')->all();
            $order     = 0;
            foreach($request->stages as $key => $stage)
            {

                $obj = null;
                if($stage['id'])
                {
                    $obj = BugStage::find($stage['id']);
                    unset($arrStages[$obj->id]);
                }
                else
                {
                    $obj               = new BugStage();
                    $obj->workspace_id = $currentWorkspace->id;
                }
                $obj->name     = $stage['name'];
                $obj->color    = $stage['color'];
                $obj->order    = $order++;
                $obj->complete = 0;
                $obj->save();
            }

            $taskExist = [];
            if($arrStages)
            {
                foreach($arrStages as $id => $name)
                {
                    $count = BugReport::where('status', '=', $id)->count();
                    if($count != 0)
                    {
                        $taskExist[] = $name;
                    }
                    else
                    {
                        BugStage::find($id)->delete();
                    }
                }
            }

            $lastStage = BugStage::where('workspace_id', '=', $currentWorkspace->id)->orderBy('order', 'desc')->first();
            if($lastStage)
            {
                $lastStage->complete = 1;
                $lastStage->save();
            }

            if(empty($taskExist))
            {
                return redirect()->back()->with('success', __('Stage Save Successfully.!'));
            }
            else
            {
                return redirect()->back()->with('error', __('Please remove bugs from stage: ' . implode(', ', $taskExist)));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


      public function settingsSlack($slug, Request $request){

        $objUser          = Auth::user();

        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $created_by=$currentWorkspace->id;
         if($currentWorkspace->created_by == $objUser->id)
        {
        $post = [];
        $post['slack_webhook'] = $request->slack_webhook;
          if(isset($request->project_notificaation) && $request->project_notificaation == '1')
                {
                    $post['project_notificaation'] = $request->project_notificaation;

                }
                else
                {
                    $post['project_notificaation'] = '0';
                }

        $post['task_notificaation'] = $request->has('task_notificaation')?$request->input('task_notificaation'):0;
        $post['taskmove_notificaation'] = $request->has('taskmove_notificaation')?$request->input('taskmove_notificaation'):0;
        $post['taskcom_notificaation'] = $request->has('taskcom_notificaation')?$request->input('taskcom_notificaation'):0;
        $post['milestone_notificaation'] = $request->has('milestone_notificaation')?$request->input('milestone_notificaation'):0;
        $post['milestonest_notificaation'] = $request->has('milestonest_notificaation')?$request->input('milestonest_notificaation'):0;
        $post['invoice_notificaation'] = $request->has('invoice_notificaation')?$request->input('invoice_notificaation'):0;
        $post['invoicest_notificaation'] = $request->has('invoicest_notificaation')?$request->input('invoicest_notificaation'):0;


        if(isset($post) && !empty($post) && count($post) > 0)
        {
            $created_at = $updated_at = date('Y-m-d H:i:s');

            foreach($post as $key => $data)
            {
                \DB::insert(
                    'INSERT INTO payment_settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = VALUES(`updated_at`) ', [
                                                                                                                                                                                                                      $data,
                                                                                                                                                                                                                      $key,
                                                                                                                                                                                                                      $created_by,
                                                                                                                                                                                                                      $created_at,
                                                                                                                                                                                                                      $updated_at,
                                                                                                                                                                                                                  ]
                );
            }
        }

        return redirect()->back()->with('success', __('Settings updated successfully.'));

    }
}


public function settingstelegram($slug, Request $request){

        $objUser          = Auth::user();

        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $created_by=$currentWorkspace->id;
         if($currentWorkspace->created_by == $objUser->id)
        {
        $post = [];
        $post['telegram_token'] = $request->telegram_token;
         $post['telegram_chatid'] = $request->telegram_chatid;
          if(isset($request->telegram_project_notificaation) && $request->telegram_project_notificaation == '1')
                {
                    $post['telegram_project_notificaation'] = $request->telegram_project_notificaation;

                }
                else
                {
                    $post['telegram_project_notificaation'] = '0';
                }

        $post['telegram_task_notificaation'] = $request->has('telegram_task_notificaation')?$request->input('telegram_task_notificaation'):0;


        $post['telegram_taskmove_notificaation'] = $request->has('telegram_taskmove_notificaation')?$request->input('telegram_taskmove_notificaation'):0;


        $post['telegram_taskcom_notificaation'] = $request->has('telegram_taskcom_notificaation')?$request->input('telegram_taskcom_notificaation'):0;


        $post['telegram_milestone_notificaation'] = $request->has('telegram_milestone_notificaation')?$request->input('telegram_milestone_notificaation'):0;


        $post['telegram_milestonest_notificaation'] = $request->has('telegram_milestonest_notificaation')?$request->input('telegram_milestonest_notificaation'):0;


        $post['telegram_invoice_notificaation'] = $request->has('telegram_invoice_notificaation')?$request->input('telegram_invoice_notificaation'):0;



        $post['telegram_invoicest_notificaation'] = $request->has('telegram_invoicest_notificaation')?$request->input('telegram_invoicest_notificaation'):0;


        if(isset($post) && !empty($post) && count($post) > 0)
        {
            $created_at = $updated_at = date('Y-m-d H:i:s');

            foreach($post as $key => $data)
            {
                \DB::insert(
                    'INSERT INTO payment_settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = VALUES(`updated_at`) ', [
                                                                                                                                                                                                                      $data,
                                                                                                                                                                                                                      $key,
                                                                                                                                                                                                                      $created_by,
                                                                                                                                                                                                                      $created_at,
                                                                                                                                                                                                                      $updated_at,
                                                                                                                                                                                                                  ]
                );
            }
        }

        return redirect()->back()->with('success', __('Settings updated successfully.'));

    }
}




}
