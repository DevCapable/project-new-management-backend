<?php

namespace App\Http\Controllers;

use App\Models\Mail\EmailTest;
use App\Models\Workspace;
use Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\Utility;

class SettingsController extends Controller
{
    public function index()
    {
        $user = \Auth::user();
        if($user->type == 'admin')
        {
            $currentWorkspace = Utility::getAdminWorkspaceBySlug($slug='en');
            $workspace = new Workspace();
            return view('setting', compact('workspace','currentWorkspace'));
        }
        else
        {
            return redirect()->back()->with('error', __('Something is wrong'));
        }
    }

    public function store(Request $request)
    {

        $user = Auth::user();
        if($user->type == 'admin')
        {
            if($request->favicon)
            {
                $request->validate(['favicon' => 'required|image|mimes:png|max:204800']);
                $request->favicon->storeAs('logo', 'favicon.png');
            }
            if($request->logo_blue)
            {
                $request->validate(['logo_blue' => 'required|image|mimes:png|max:204800']);
                $request->logo_blue->storeAs('logo', 'logo-light.png');
            }
            if($request->logo_white)
            {
                $request->validate(['logo_white' => 'required|image|mimes:png|max:204800']);
                $request->logo_white->storeAs('logo', 'logo-dark.png');
            }

            $rules = [
                'app_name' => 'required|string|max:50',
                'default_language' => 'required|string|max:50',
                'footer_text' => 'required|string|max:50',
            ];

            $request->validate($rules);
             $cookie_text =   (!isset($request->cookie_text) && empty($request->cookie_text)) ? '' : $request->cookie_text;


            $arrEnv = [

                'APP_NAME' => $request->app_name,
                'DEFAULT_LANG' => $request->default_language,
                'FOOTER_TEXT' => $request->footer_text,
                'DISPLAY_LANDING' => $request->display_landing ? 'on':'off',
                'SITE_RTL' => !isset($request->SITE_RTL) ? 'off' : 'on',
                'gdpr_cookie' => !isset($request->gdpr_cookie) ? 'off' : 'on',
                'cookie_text'=>  $cookie_text,
                'signup_button' => !isset($request->signup_button) ? 'off' : 'on',

            ];
            Utility::setEnvironmentValue($arrEnv);
            Artisan::call('config:cache');
            Artisan::call('config:clear');

            $color =(!empty($request->theme_color)) ? $request->theme_color : 'theme-4';
            $post['color'] = $color;

            $cust_theme_bg = (!empty($request->cust_theme_bg)) ? 'on' : 'off';
            $post['cust_theme_bg'] = $cust_theme_bg;


            $cust_darklayout = !empty($request->cust_darklayout) ? 'on' : 'off';
            $post['cust_darklayout'] = $cust_darklayout;


        if(isset($post) && !empty($post) && count($post) > 0)
            {
             $created_at = date('Y-m-d H:i:s');
            $updated_at = date('Y-m-d H:i:s');
           $created_by = 2 ;
            foreach($post as $key => $data)
            {
                \DB::insert('insert into settings (`value`, `name`,`created_at`,`updated_at`) values (?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = VALUES(`updated_at`)', [
                    $data,
                    $key,
                    $created_at,
                    $updated_at,
                ]);
            }
        }


            if($this->setEnvironmentValue($arrEnv))
            {
                return redirect()->back()->with('success', __('Setting updated successfully'));
            }
            else
            {
                return redirect()->back()->with('error', __('Something is wrong'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Something is wrong'));
        }
    }
    public function emailSettingStore(Request $request)
    {
        $user = \Auth::user();
        if($user->type == 'admin')
        {
            $rules = [
                'mail_driver' => 'required|string|max:50',
                'mail_host' => 'required|string|max:50',
                'mail_port' => 'required|string|max:50',
                'mail_username' => 'required|string|max:50',
                'mail_password' => 'required|string|max:255',
                'mail_encryption' => 'required|string|max:50',
                'mail_from_address' => 'required|string|max:50',
                'mail_from_name' => 'required|string|max:50',
            ];

            $request->validate($rules);

            $arrEnv = [

                'MAIL_DRIVER'=>$request->mail_driver,
                'MAIL_HOST' => $request->mail_host,
                'MAIL_PORT' => $request->mail_port,
                'MAIL_USERNAME' => $request->mail_username,
                'MAIL_PASSWORD' => $request->mail_password,
                'MAIL_ENCRYPTION' => $request->mail_encryption,
                'MAIL_FROM_ADDRESS' => $request->mail_from_address,
                'MAIL_FROM_NAME' => $request->mail_from_name,
            ];

            Artisan::call('config:cache');
            Artisan::call('config:clear');

            if($this->setEnvironmentValue($arrEnv))
            {
                return redirect()->back()->with('success', __('Setting updated successfully'));
            }
            else
            {
                return redirect()->back()->with('error', __('Something is wrong'));
            }


        }
        else
        {
            return redirect()->back()->with('error', __('Something is wrong'));
        }
    }

    public function pusherSettingStore(Request $request)
    {
        $user = \Auth::user();
        if($user->type == 'admin')
        {
            $rules = [];

            if($request->enable_chat == 'on')
            {
                $rules['pusher_app_id']      = 'required|string|max:50';
                $rules['pusher_app_key']     = 'required|string|max:50';
                $rules['pusher_app_secret']  = 'required|string|max:50';
                $rules['pusher_app_cluster'] = 'required|string|max:50';
            }

            $request->validate($rules);

            $arrEnv = [
                'CHAT_MODULE' => $request->enable_chat,
                'PUSHER_APP_ID' => $request->pusher_app_id,
                'PUSHER_APP_KEY' => $request->pusher_app_key,
                'PUSHER_APP_SECRET' => $request->pusher_app_secret,
                'PUSHER_APP_CLUSTER' => $request->pusher_app_cluster,
            ];

                  Artisan::call('config:cache');
                  Artisan::call('config:clear');

            if($this->setEnvironmentValue($arrEnv))
            {
                return redirect()->back()->with('success', __('Setting updated successfully'));
            }
            else
            {
                return redirect()->back()->with('error', __('Something is wrong'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Something is wrong'));
        }
    }

    public static function setEnvironmentValue(array $values)
    {
        $envFile = app()->environmentFilePath();
        $str     = file_get_contents($envFile);
        if(count($values) > 0)
        {
            foreach($values as $envKey => $envValue)
            {
                $keyPosition       = strpos($str, "{$envKey}=");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
                $oldLine           = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

                if($keyPosition!=0 && !$endOfLinePosition && !$oldLine)
                {
                    $str .= "{$envKey}='{$envValue}'\n";
                }
                else
                {
                    $str = str_replace($oldLine, "{$envKey}='{$envValue}'", $str);
                }
            }
        }
        $str = substr($str, 0, -1);
        $str .= "\n";

        return file_put_contents($envFile, $str) ? true : false;
    }

    public function testEmail(Request $request)
    {
        $user = \Auth::user();

        if($user->type == 'admin')
        {
            $data                      = [];
            $data['mail_driver']     = !($request->mail_driver)? env('MAIL_DRIVER'): $request->mail_driver ;
            $data['mail_host']         = !($request->mail_host) ? env('MAIL_HOST'): $request->mail_host ;
            $data['mail_port']         = !($request->mail_port) ? env('MAIL_PORT'): $request->mail_port ;
            $data['mail_username']     = !($request->mail_username) ? env('MAIL_USERNAME'): $request->mail_username ;
            $data['mail_password']     = !($request->mail_password) ? env('MAIL_PASSWORD'): $request->mail_password ;
            $data['mail_encryption']   = !($request->mail_encryption) ? env('MAIL_ENCRYPTION'): $request->mail_encryption ;
            $data['mail_from_address'] = !($request->mail_from_address)? env('MAIL_FROM_ADDRESS'): $request->mail_from_address ;
            $data['mail_from_name']    = !($request->mail_from_name) ? env('MAIL_FROM_NAME'): $request->mail_from_name ;

            return view('users.test_email', compact('data'));
        }
        else
        {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function testEmailSend(Request $request)
    {

        $validator = \Validator::make($request->all(), [
            'email' => 'required|email',
            'mail_driver' => 'required',
            'mail_host' => 'required',
            'mail_port' => 'required',
            'mail_username' => 'required',
            'mail_password' => 'required',
            'mail_from_address' => 'required',
            'mail_from_name' => 'required',
        ]);
        if($validator->fails())
        {

            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        try
        {
            config([
                       'mail.driver' => $request->mail_driver,
                       'mail.host' => $request->mail_host,
                       'mail.port' => $request->mail_port,
                       'mail.encryption' => $request->mail_encryption,
                       'mail.username' => $request->mail_username,
                       'mail.password' => $request->mail_password,
                       'mail.from.address' => $request->mail_from_address,
                       'mail.from.name' => $request->mail_from_name,
                   ]);
            Mail::to($request->email)->send(new EmailTest());
        }
        catch(\Exception $e)
        {

            return response()->json([
                                        'is_success' => false,
                                        'message' => $e->getMessage(),
                                    ]);
        }

        return response()->json([
                                    'is_success' => true,
                                    'message' => __('Email send Successfully'),
                                ]);
    }
    public function recaptchaSettingStore(Request $request)
    {
        $user = \Auth::user();
        $rules = [];

        if($request->recaptcha_module == 'on')
        {
            $rules['google_recaptcha_key'] = 'required|string|max:50';
            $rules['google_recaptcha_secret'] = 'required|string|max:50';
        }

        $validator = \Validator::make(
            $request->all(), $rules
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $arrEnv = [
            'RECAPTCHA_MODULE' => $request->recaptcha_module ,
            'NOCAPTCHA_SITEKEY' => $request->google_recaptcha_key,
            'NOCAPTCHA_SECRET' => $request->google_recaptcha_secret,
        ];

        if($this->setEnvironmentValue($arrEnv))
        {
            return redirect()->back()->with('success', __('Recaptcha Settings updated successfully'));
        }
        else
        {
            return redirect()->back()->with('error', __('Something is wrong'));
        }
    }

}
