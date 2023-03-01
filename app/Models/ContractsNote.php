<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractsNote extends Model
{
     protected $fillable = [
        'contract_id','user_id','client_id','notes',' workspace_id'
    ];



     public function user(){
        return $this->hasOne('App\Models\User','id','user_id');
    }
    public function client(){
        return $this->hasOne('App\Models\Client','id','client_id');
    }
}
