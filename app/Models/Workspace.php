<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workspace extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'created_by',
        'lang',
        'currency',
        'currency_code',
        'company',
        'address',
        'city',
        'state',
        'zipcode',
        'country',
        'telephone',
        'logo',
        'is_stripe_enabled',
        'stripe_key',
        'stripe_secret',
        'is_paypal_enabled',
        'paypal_mode',
        'paypal_client_id',
        'paypal_secret_key',
        'invoice_template',
        'invoice_color',
        'invoice_footer_title',
        'invoice_footer_notes',
        'is_active',
    ];

    public static function create($data)
    {
        $obj          = new Utility();
        $table        = with(new Workspace)->getTable();
        $data['slug'] = $obj->createSlug($table, $data['name']);
        $workspace    = static::query()->create($data);

        $defaultStages = [
            '#77b6ea' => __('Todo'),
            '#545454' => __('In Progress'),
            '#3cb8d9' => __('Review'),
            '#37b37e' => __('Done'),
        ];
        $key = 0;
        $lastKey       = count($defaultStages) - 1;
        foreach($defaultStages as $color => $stage)
        {
            Stage::create([
                    'name' => $stage,
                    'color' => $color,
                    'workspace_id' => $workspace->id,
                    'complete' => ($key == $lastKey) ? true : false,
                    'order' => $key,
                ]);
            $key++;
        }

        $defaultStages = [
            '#77b6ea' => __('Unconfirmed'),
            '#6e00ff' => __('Confirmed'),
            '#3cb8d9' => __('In Progress'),
            '#37b37e' => __('Resolved'),
            '#545454' => __('Verified'),
        ];
        $key = 0;

        $lastKey       = count($defaultStages) - 1;
        foreach($defaultStages as $color => $stage)
        {
            BugStage::create([
                    'name' => $stage,
                    'color' => $color,
                    'workspace_id' => $workspace->id,
                    'complete' => ($key == $lastKey) ? true : false,
                    'order' => $key,
                ]);
            $key++;
        }

        return $workspace;
    }

    public function creater()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }

    public function clientCreater()
    {
        return $this->hasOne('App\Models\Client', 'id', 'created_by');
    }

    public function users($created_by = false)
    {
        if($created_by)
        {
            return $this->belongsToMany('App\Models\User', 'user_workspaces', 'workspace_id', 'user_id')->withPivot('is_active')->where('users.id', "!=", $created_by)->get();
        }
        else
        {
            return $this->belongsToMany('App\Models\User', 'user_workspaces', 'workspace_id', 'user_id')->withPivot('is_active');
        }
    }

    public function clients()
    {
        return $this->belongsToMany('App\Models\Client', 'client_workspaces', 'workspace_id', 'client_id')->withPivot('is_active');
    }

    public function projects()
    {
        return $this->hasMany('App\Models\Project', 'workspace', 'id');
    }

    public function languages()
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

    public function priceFormat($price)
    {
        return $this->currency . "" . number_format($price, 2);
    }
}
