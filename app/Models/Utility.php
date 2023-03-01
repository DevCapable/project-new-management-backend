<?php

namespace App\Models;

use App\Models\Workspace;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Mail\CommonEmailTemplate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Jenssegers\Date\Date;
use Illuminate\Support\Facades\Mail;
use Pusher\Pusher;

class Utility
{
    public function createSlug($table, $title, $id = 0)
    {
        // Normalize the title
        $slug = Str::slug($title, '-');
        // Get any that could possibly be related.
        // This cuts the queries down by doing it once.
        $allSlugs = $this->getRelatedSlugs($table, $slug, $id);
        // If we haven't used it before then we are all good.
        if(!$allSlugs->contains('slug', $slug))
        {
            return $slug;
        }
        // Just append numbers like a savage until we find not used.
        for($i = 1; $i <= 100; $i++)
        {
            $newSlug = $slug . '-' . $i;
            if(!$allSlugs->contains('slug', $newSlug))
            {
                return $newSlug;
            }
        }
        throw new \Exception(__('Can not create a unique slug'));
    }

    protected function getRelatedSlugs($table, $slug, $id = 0)
    {
        return DB::table($table)->select()->where('slug', 'like', $slug . '%')->where('id', '<>', $id)->get();
    }

        public static function success_res($msg = "", $args = array())
    {
        $msg       = $msg == "" ? "success" : $msg;
        $msg_id    = 'success.' . $msg;
        $converted = \Lang::get($msg_id, $args);
        $msg       = $msg_id == $converted ? $msg : $converted;
        $json      = array(
            'flag' => 1,
            'msg' => $msg,
        );

        return $json;
    }

    public static function error_res($msg = "", $args = array())
    {
        $msg       = $msg == "" ? "error" : $msg;
        $msg_id    = 'error.' . $msg;
        $converted = \Lang::get($msg_id, $args);
        $msg       = $msg_id == $converted ? $msg : $converted;
        $json      = array(
            'flag' => 0,
            'msg' => $msg,
        );

        return $json;
    }

     public static function second_to_time($seconds = 0)
    {
        $H = floor($seconds / 3600);
        $i = ($seconds / 60) % 60;
        $s = $seconds % 60;

        $time = sprintf("%02d:%02d:%02d", $H, $i, $s);

        return $time;
    }

      public static function diffance_to_time($start, $end)
    {
        $start         = new Carbon($start);
        $end           = new Carbon($end);
        $totalDuration = $start->diffInSeconds($end);

        return $totalDuration;
    }

    public static function getWorkspaceBySlug($slug)
    {
        $objUser = Auth::user();

        if($objUser && $objUser->current_workspace)
        {
            if($objUser->getGuard() == 'client')
            {
                $rs = Workspace::select(['workspaces.*',
                'client_workspaces.permission'
                ])->join('client_workspaces', 'workspaces.id', '=', 'client_workspaces.workspace_id')->where('workspaces.id', '=', $objUser->current_workspace)->where('client_id', '=', $objUser->id)->first();
            }
            else
            {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    $rs = Workspace::select([
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                'workspaces.*',
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                'user_workspaces.permission',
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ])->join('user_workspaces', 'workspaces.id', '=', 'user_workspaces.workspace_id')->where('workspaces.id', '=', $objUser->current_workspace)->where('user_id', '=', $objUser->id)->first();
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                }
        }
        elseif($objUser && !empty($slug))
        {
            if($objUser->getGuard() == 'client')
            {
                $rs = Workspace::select(['workspaces.*',
                    'client_workspaces.permission',])->join('client_workspaces', 'workspaces.id', '=', 'client_workspaces.workspace_id')->where('slug', '=', $slug)->where('client_id', '=', $objUser->id)->first();
            }
            else
            {
                $rs = Workspace::select([
                                            'workspaces.*',
                                            'user_workspaces.permission',
                                        ])->join('user_workspaces', 'workspaces.id', '=', 'user_workspaces.workspace_id')->where('slug', '=', $slug)->where('user_id', '=', $objUser->id)->first();
            }
        }
        elseif($objUser)
        {
            if($objUser->getGuard() == 'client')
            {
                $rs                         = Workspace::select(['workspaces.*'])->join('client_workspaces', 'workspaces.id', '=', 'client_workspaces.workspace_id')->where('client_id', '=', $objUser->id)->orderBy('workspaces.id', 'desc')->limit(1)->first();
                $objUser->current_workspace = $rs->id;
                $objUser->save();
            }
            else
            {
                $rs = Workspace::select([
                                            'workspaces.*',
                                            'user_workspaces.permission',
                                        ])->join('user_workspaces', 'workspaces.id', '=', 'user_workspaces.workspace_id')->where('user_id', '=', $objUser->id)->orderBy('workspaces.id', 'desc')->limit(1)->first();
            }
        }
        else
        {
            $rs = Workspace::select(['workspaces.*'])->where('slug', '=', $slug)->limit(1)->first();
        }
        if($rs)
        {
            Utility::setLang($rs);

            return $rs;
        }
    }

    public static function getAdminWorkspaceBySlug($slug)
    {


//                $rs = Workspace::select(['workspaces.*',
//                    'client_workspaces.permission'
//                ])->join('client_workspaces', 'workspaces.id', '=', 'client_workspaces.workspace_id')->where('workspaces.id', '=', $objUser->current_workspace)->where('client_id', '=', $objUser->id)->first();
//
//                $rs = Workspace::select([
//                    'workspaces.*',
//                    'user_workspaces.permission',
//                ])->join('user_workspaces', 'workspaces.id', '=', 'user_workspaces.workspace_id')->where('workspaces.id', '=', $objUser->current_workspace)->where('user_id', '=', $objUser->id)->first();
//
//
//                $rs = Workspace::select([
//                    'workspaces.*',
//                    'user_workspaces.permission',
//                ])->join('user_workspaces', 'workspaces.id', '=', 'user_workspaces.workspace_id')->where('slug', '=', $slug)->where('user_id', '=', $objUser->id)->first();
//
//
                $rs = Workspace::select([
                    'workspaces.*',
                    'user_workspaces.permission',
                ])->join('user_workspaces', 'workspaces.id', '=', 'user_workspaces.workspace_id')->orderBy('workspaces.id', 'desc')->limit(1)->first();

//            $rs = Workspace::select(['workspaces.*'])->where('slug', '=', 'new')->limit(1)->first();
        if($rs)
        {
            Utility::setLang($rs);

            return $rs;
        }
    }

    public static function languages()
    {
        $dir     = base_path() . '/resources/lang/';
        $glob    = glob($dir . "*", GLOB_ONLYDIR);
        $arrLang = array_map(
            function ($value) use ($dir){
                return str_replace($dir, '', $value);
            }, $glob
        );
        $arrLang = array_map(
            function ($value) use ($dir){
                return preg_replace('/[0-9]+/', '', $value);
            }, $arrLang
        );
        $arrLang = array_filter($arrLang);

        return $arrLang;
    }

    public static function setLang($Workspace)
    {
        $dir = base_path() . '/resources/lang/' . $Workspace->id . "/";
        if(is_dir($dir))
        {
            $lang = $Workspace->id . "/" . $Workspace->lang;
        }
        else
        {
            $lang = $Workspace->lang;
        }

        Date::setLocale(basename($lang));
        \App::setLocale($lang);
    }

    public static function get_timeago($ptime)
    {
        $estimate_time = time() - $ptime;

        $ago = true;

        if($estimate_time < 1)
        {
            $ago           = false;
            $estimate_time = abs($estimate_time);
        }

        $condition = [
            12 * 30 * 24 * 60 * 60 => 'year',
            30 * 24 * 60 * 60 => 'month',
            24 * 60 * 60 => 'day',
            60 * 60 => 'hour',
            60 => 'minute',
            1 => 'second',
        ];

        foreach($condition as $secs => $str)
        {
            $d = $estimate_time / $secs;

            if($d >= 1)
            {
                $r   = round($d);
                $str = $str . ($r > 1 ? 's' : '');

                return $r . ' ' . __($str) . ($ago ? ' ' . __('ago') : '');
            }
        }

        return $estimate_time;
    }

    public static function formatBytes($size, $precision = 2)
    {
        if($size > 0)
        {
            $size     = (int)$size;
            $base     = log($size) / log(1024);
            $suffixes = [
                ' bytes',
                ' KB',
                ' MB',
                ' GB',
                ' TB',
            ];

            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        }
        else
        {
            return $size;
        }
    }

    public static function invoiceNumberFormat($number)
    {
        return '#INV' . sprintf("%05d", $number);
    }

    public static function dateFormat($date)
    {
        $lang = \App::getLocale();
        \App::setLocale(basename($lang));
        $date = Date::parse($date)->format('d M Y');

        return $date;
    }

    public static function sendNotification($type, $currentWorkspace, $user_id, $obj)
    {

        if(is_array($user_id))
        {
            foreach($user_id as $id)
            {
                $notification = Notification::create([
                                                         'workspace_id' => $currentWorkspace->id,
                                                         'user_id' => $id,
                                                         'type' => $type,
                                                         'data' => json_encode($obj),
                                                         'is_read' => 0,
                                                     ]);

                // Push Notification
                $options         = array(
                    'cluster' => env('PUSHER_APP_CLUSTER'),
                    'useTLS' => true,
                );
                try {
                    $pusher          = new Pusher(
                        env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), $options
                    );
                    $data            = [];
                    $data['html']    = $notification->toHtml();
                    $data['user_id'] = $notification->user_id;
                    // sending from and to user id when pressed enter

                    if(!empty(env('PUSHER_APP_KEY')) && !empty(env('PUSHER_APP_SECRET')) && !empty(env('PUSHER_APP_ID'))){
                        $pusher->trigger($currentWorkspace->slug, 'notification', $data);
                    }
                } catch (\Throwable $th) {
                    //throw $th;
                }


                // End Push Notification
            }
        }
        else
        {
            $notification = Notification::create([
                                                     'workspace_id' => $currentWorkspace->id,
                                                     'user_id' => $user_id,
                                                     'type' => $type,
                                                     'data' => json_encode($obj),
                                                     'is_read' => 0,
                                                 ]);

            // Push Notification
            $options         = array(
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true,
            );
            try {
                $pusher   = new Pusher(
                    env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), $options
                );
                $data            = [];
                $data['html']    = $notification->toHtml();
                $data['user_id'] = $notification->user_id;
                // sending from and to user id when pressed enter


                if(!empty(env('PUSHER_APP_KEY')) && !empty(env('PUSHER_APP_SECRET')) && !empty(env('PUSHER_APP_ID'))){
                    $pusher->trigger($currentWorkspace->slug, 'notification', $data);
                }
            } catch (\Throwable $th) {

            }
            // End Push Notification
        }
    }

    public static function getFirstSeventhWeekDay($week = null)
    {
        $first_day = $seventh_day = null;

        if(isset($week))
        {
            $first_day   = Carbon::now()->addWeeks($week)->startOfWeek();
            $seventh_day = Carbon::now()->addWeeks($week)->endOfWeek();
        }

        $dateCollection['first_day']   = $first_day;
        $dateCollection['seventh_day'] = $seventh_day;

        $period = CarbonPeriod::create($first_day, $seventh_day);

        foreach($period as $key => $dateobj)
        {
            $dateCollection['datePeriod'][$key] = $dateobj;
        }

        return $dateCollection;
    }

    public static function calculateTimesheetHours($times)
    {
        $minutes = 0;

        foreach($times as $time)
        {
            list($hour, $minute) = explode(':', $time);
            $minutes += $hour * 60;
            $minutes += $minute;
        }

        $hours   = floor($minutes / 60);
        $minutes -= $hours * 60;

        return sprintf('%02d:%02d', $hours, $minutes);
    }

    public static function delete_directory($dir)
    {
        if(!file_exists($dir))
        {
            return true;
        }
        if(!is_dir($dir))
        {
            return unlink($dir);
        }
        foreach(scandir($dir) as $item)
        {
            if($item == '.' || $item == '..')
            {
                continue;
            }
            if(!self::delete_directory($dir . DIRECTORY_SEPARATOR . $item))
            {
                return false;
            }
        }

        return rmdir($dir);
    }

    // get font-color code accourding to bg-color
    public static function hex2rgb($hex)
    {
        $hex = str_replace("#", "", $hex);
        if(strlen($hex) == 3)
        {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        }
        else
        {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        $rgb = [
            $r,
            $g,
            $b,
        ];

        //return implode(",", $rgb); // returns the rgb values separated by commas
        return $rgb; // returns an array with the rgb values
    }

    public static function getFontColor($color_code)
    {
        $rgb = self::hex2rgb($color_code);
        $R   = $G = $B = $C = $L = $color = '';
        $R   = (floor($rgb[0]));
        $G   = (floor($rgb[1]));
        $B   = (floor($rgb[2]));
        $C   = [
            $R / 255,
            $G / 255,
            $B / 255,
        ];
        for($i = 0; $i < count($C); ++$i)
        {
            if($C[$i] <= 0.03928)
            {
                $C[$i] = $C[$i] / 12.92;
            }
            else
            {
                $C[$i] = pow(($C[$i] + 0.055) / 1.055, 2.4);
            }
        }
        $L = 0.2126 * $C[0] + 0.7152 * $C[1] + 0.0722 * $C[2];

        $color = $L > 0.179 ? 'black' : 'white';

        return $color;
    }

    public static function get_messenger_packages_migration()
    {
        $totalMigration = 0;
        $messengerPath  = glob(base_path() . '/vendor/munafio/chatify/database/migrations' . DIRECTORY_SEPARATOR . '*.php');
        if(!empty($messengerPath))
        {
            $messengerMigration = str_replace('.php', '', $messengerPath);
            $totalMigration     = count($messengerMigration);
        }

        return $totalMigration;
    }

    public static function getAllPermission()
    {
        return [
            "create milestone",
            "edit milestone",
            "delete milestone",
            "show milestone",
            "create task",
            "edit task",
            "delete task",
            "show task",
            "move task",
            "show activity",
            "show uploading",
            "show timesheet",
            "show bug report",
            "create bug report",
            "edit bug report",
            "delete bug report",
            "move bug report",
            "show gantt",
        ];
    }


public static function getAdminPaymentSettings()
    {
        $data          = DB::table('settings');
        $adminSettings = [
            'cust_theme_bg' => 'on',
            'cust_darklayout'=>'off',
            'color'=>'theme-3',
            'company_email'=>'test@example.com',

        ];

        $data = $data->get();
        foreach($data as $row)
        {
            $adminSettings[$row->name] = $row->value;
        }

        return $adminSettings;
    }



public static function get_logo(){

        $setting = Utility::getAdminPaymentSettings();

        if($setting['cust_darklayout'] == 'on'){
            return 'logo-dark.png';
        }else{
            return 'logo-light.png';
        }
    }




    public static function getPaymentSetting($workspace_id)
    {
        $data     = DB::table('payment_settings');
        $settings = [
            'is_paystack_enabled' => 'off',
            'paystack_public_key' => '',
            'paystack_secret_key' => '',
            'is_flutterwave_enabled' => 'off',
            'flutterwave_public_key' => '',
            'flutterwave_secret_key' => '',
            'is_razorpay_enabled' => 'off',
            'razorpay_public_key' => '',
            'razorpay_secret_key' => '',
            'is_mercado_enabled' => 'off',
            'mercado_app_id' => '',
            'mercado_secret_key' => '',
            'is_paytm_enabled' => 'off',
            'paytm_mode' => 'local',
            'paytm_merchant_id' => '',
            'paytm_merchant_key' => '',
            'paytm_industry_type' => '',
            'is_mollie_enabled' => 'off',
            'mollie_api_key' => '',
            'mollie_profile_id' => '',
            'mollie_partner_id' => '',
            'is_skrill_enabled' => 'off',
            'skrill_email' => '',
            'is_coingate_enabled' => 'off',
            'coingate_mode' => 'sandbox',
            'coingate_auth_token' => '',
            'slack_webhook'=> "",
            'project_notificaation' => 0,
            'task_notificaation' => 0,
            'taskmove_notificaation' =>0 ,
            'milestone_notificaation' => 0,
            'milestonest_notificaation' => 0,
            'taskcom_notificaation' => 0,
            'invoice_notificaation' => 0,
            'invoicest_notificaation' => 0,
            'signup_button' => 'on',
             'cust_theme_bg' => 'on',
            'cust_darklayout'=>'off',
            'color'=>'theme-3',
        ];

        if(!empty($workspace_id))
        {
            $data = $data->where('created_by', '=', $workspace_id);
            $data = $data->get();
            foreach($data as $row)
            {
                $settings[$row->name] = $row->value;
            }
        }

        return $settings;
    }



     public static function project_nm($project_name)
    {
        $taxArr  = explode(',', $project_name);
        $lead = 0;
        foreach($taxArr as $tax)
        {
            $tax     = Project::find($tax);

            $lead = $tax->name;
        }

        return $lead;
    }



         public static function tax_nm($tax_name)
    {
        $taxArr  = explode(',', $tax_name);
        $lead = 0;
        foreach($taxArr as $tax)
        {
            $tax     = Tax::find($tax);

            $lead = $tax->name;
        }

        return $lead;
    }

    public static function taxRate($client)
    {
        $taxArr  = explode(',', $client);
        $taxRate = 0;
        foreach($taxArr as $tax)
        {
            $tax     = Client::find($tax);

            $taxRate = $tax->name;
        }

        return $taxRate;
    }


      public static function send_slack_msg($msg, $user) {

        $settings  = Utility::getPaymentSetting($user);

        if(isset($settings['slack_webhook']) && !empty($settings['slack_webhook'])){
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $settings['slack_webhook']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['text' => $msg]));

            $headers = array();
            $headers[] = 'Content-Type: application/json';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);
        }


    }

    public static function send_telegram_msg($resp,$user)
    {
        $settings  = Utility::getPaymentSetting($user);

        $msg = $resp;
        // Set your Bot ID and Chat ID.
        $telegrambot    = $settings['telegram_token'];
        $telegramchatid = $settings['telegram_chatid'];
        // Function call with your own text or variable
        $url     = 'https://api.telegram.org/bot' . $telegrambot . '/sendMessage';
        $data    = array(
            'chat_id' => $telegramchatid,
            'text' => $msg,
        );
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-Type:application/x-www-form-urlencoded\r\n",
                'content' => http_build_query($data),
            ),
        );
        $context = stream_context_create($options);
        $result  = file_get_contents($url, false, $context);
        $url     = $url;
    }


    public static function colorset(){

        $user = Auth::user();

        $setting = DB::table('payment_settings')->pluck('value','name')->toArray();

        return $setting;

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
                // If key does not exist, add it
                if(!$keyPosition || !$endOfLinePosition || !$oldLine)
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
        if(!file_put_contents($envFile, $str))
        {
            return false;
        }

        return true;
    }



      public static function getcompanylogo($slug)
    {
        $data          = Workspace::where('id' ,$slug)->first();

        if($data->cust_darklayout == 'on'){
            return 'logo-dark.png';
        }else{
            return 'logo-light.png';
        }
    }

    public static function getcompanySettings($slug)
    {
        $data          = Workspace::where('id' ,$slug)->first();
        return  $data;
    }




 // Email Template Modules Function START
    // Common Function That used to send mail with check all cases
    public static function sendEmailTemplate($emailTemplate, $user_id, $obj)
    {

        $usr = Auth::user();
        if($user_id != $usr->id)
        {
            $mailTo = User::find($user_id)->email;

            if($usr->type != 'admin')
            {
                // find template is exist or not in our record
                $template = EmailTemplate::where('name', 'LIKE', $emailTemplate)->first();

                if(isset($template) && !empty($template))
                {
                   // check template is active or not by company
                    $is_active = UserEmailTemplate::where('template_id', '=', $template->id)->where('user_id', '=', $usr->id)->first();


                    if($is_active->is_active == 1)
                    {
                        $settings = self::getAdminPaymentSettings();

                        // get email content language base

                        $user_lang = Workspace::where('id',$usr->current_workspace)->first();


                        $content       = EmailTemplateLang::where('parent_id', '=', $template->id)->where('lang', 'LIKE', $user_lang->lang)->first();

                        $content->from = $template->from;
                        if(!empty($content->content))
                        {
                            $content->content = self::replaceVariable($emailTemplate, $content->content, $obj);

                            // send email
                            try
                            {

                                Mail::to($mailTo)->send(new CommonEmailTemplate($content, $settings));
                            }
                            catch(\Exception $e)
                            {
                                $error = __('E-Mail has been not sent due to SMTP configuration');
                            }

                            if(isset($error))
                            {
                                $arReturn = [
                                    'is_success' => false,
                                    'error' => $error,
                                ];
                            }
                            else
                            {
                                $arReturn = [
                                    'is_success' => true,
                                    'error' => false,
                                ];
                            }
                        }
                        else
                        {
                            $arReturn = [
                                'is_success' => false,
                                'error' => __('Mail not send, email is empty'),
                            ];
                        }

                        return $arReturn;
                    }
                    else
                    {
                        return [
                            'is_success' => true,
                            'error' => false,
                        ];
                    }
                }
                else
                {
                    return [
                        'is_success' => false,
                        'error' => __('Mail not send, email not found'),
                    ];
                }
            }
        }
    }







     public static function sendclientEmailTemplate($emailTemplate, $user_id, $obj)
    {
        $usr = Auth::user();

        if($user_id != $usr->id)
        {
            $mailTo = Client::find($user_id)->email;

            if($usr->type != 'admin')
            {
                // find template is exist or not in our record
                $template = EmailTemplate::where('name', 'LIKE', $emailTemplate)->first();

                if(isset($template) && !empty($template))
                {
                   // check template is active or not by company
                    $is_active = UserEmailTemplate::where('template_id', '=', $template->id)->where('user_id', '=', $usr->id)->first();

                    if($is_active->is_active == 1)
                    {
                        $settings = self::getAdminPaymentSettings();
                         $user_lang = Workspace::where('id',$usr->current_workspace)->first();
                        // get email content language base
                        $content       = EmailTemplateLang::where('parent_id', '=', $template->id)->where('lang', 'LIKE', $user_lang->lang)->first();
                        $content->from = $template->from;
                        if(!empty($content->content))
                        {
                            $content->content = self::replaceVariable($emailTemplate, $content->content, $obj);

                            // send email
                            try
                            {
                                Mail::to($mailTo)->send(new CommonEmailTemplate($content, $settings));
                            }
                            catch(\Exception $e)
                            {
                                $error = __('E-Mail has been not sent due to SMTP configuration');
                            }

                            if(isset($error))
                            {
                                $arReturn = [
                                    'is_success' => false,
                                    'error' => $error,
                                ];
                            }
                            else
                            {
                                $arReturn = [
                                    'is_success' => true,
                                    'error' => false,
                                ];
                            }
                        }
                        else
                        {
                            $arReturn = [
                                'is_success' => false,
                                'error' => __('Mail not send, email is empty'),
                            ];
                        }

                        return $arReturn;
                    }
                    else
                    {
                        return [
                            'is_success' => true,
                            'error' => false,
                        ];
                    }
                }
                else
                {
                    return [
                        'is_success' => false,
                        'error' => __('Mail not send, email not found'),
                    ];
                }
            }
        }
    }

    // used for replace email variable (parameter 'template_name','id(get particular record by id for data)')
    public static function replaceVariable($name, $content, $obj)
    {
        $arrVariable = [
            '{project_name}',
            '{project_status}',
            '{app_name}',
            '{company_name}',
            '{email}',
            '{password}',
            '{app_url}',
            '{workspace_name}',
            '{user_name}',
            '{owner_name}',
            '{client_name}',
            '{contract_subject}',
            '{contract_type}',
            '{value}',
            '{start_date}',
            '{end_date}',
            '{verification_code}',
        ];
        $arrValue    = [
            'project_name' => '-',
            'project_status' => '-',
            'app_name' => '-',
            'company_name' => '-',
            'email' => '-',
            'password' => '-',
            'app_url' => '-',
            'workspace_name'=>'-',
            'user_name'=>'-',
            'owner_name'=>'-',
            'client_name'=>'-',
            'contract_subject' => '-',
            'contract_type'=>'-',
            'value'=>'-',
            'start_date'=>'-',
            'end_date'=>'-',
            'verification_code'=>'-',
        ];

        foreach($obj as $key => $val)
        {
            $arrValue[$key] = $val;
        }

        $arrValue['app_name']     = env('APP_NAME');
        // $arrValue['company_name'] = self::settings()['company_name'];
        $arrValue['app_url']      = '<a href="' . env('APP_URL') . '" target="_blank">' . env('APP_URL') . '</a>';

        return str_replace($arrVariable, array_values($arrValue), $content);
    }

    // Make Entry in email_tempalte_lang table when create new language
    public static function makeEmailLang($lang)
    {
        $template = EmailTemplate::all();
        foreach($template as $t)
        {
            $default_lang                 = EmailTemplateLang::where('parent_id', '=', $t->id)->where('lang', 'LIKE', 'en')->first();
            $emailTemplateLang            = new EmailTemplateLang();
            $emailTemplateLang->parent_id = $t->id;
            $emailTemplateLang->lang      = $lang;
            $emailTemplateLang->subject   = $default_lang->subject;
            $emailTemplateLang->from      = $default_lang->from;
            $emailTemplateLang->content   = $default_lang->content;
            $emailTemplateLang->save();
        }
    }
    // Email Template Modules Function END


     public static function contractNumberFormat($number)
    {
        return '#CON' . sprintf("%05d", $number);
    }




}
