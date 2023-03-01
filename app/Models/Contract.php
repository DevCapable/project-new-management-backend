<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
  
      protected $fillable = [
        'client_id', 
        'project_id',
        'subject',
        'value',
        'type',
        'start_date',
        'end_date',
        'status',
        'contract_description',
        'client_signature',
        'description',
        'workspace_id',
    ];


    public function contract_type()
    {
        return $this->hasOne('App\Models\ContractsType', 'id', 'type');
    }


       public function clients()
    {
        return $this->hasOne('App\Models\Client', 'id', 'client_id');
    }

        public function projects()
    {
        return $this->hasOne('App\Models\Project', 'id', 'project_id');
    }


     public function files()
    {
        return $this->hasMany('App\Models\ContractsAttachment', 'contract_id' , 'id');
    }

    public function comment()
    {
        return $this->hasMany('App\Models\ContractsComment', 'contract_id', 'id');
    }
    public function note()
    {
        return $this->hasMany('App\Models\ContractsNote', 'contract_id', 'id');
    }

     public static function getContractSummary($currentWorkspace,$contracts)
    {


        $total = 0;

        foreach($contracts as $contract)
        {
            $total += $contract->value;
        }

        return $currentWorkspace->priceFormat($total);
    }


   
}
