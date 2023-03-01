<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $fillable = [
        'text','done','workspace','color','created_by'
    ];
    public function user(){
        return $this->hasOne('App\Models\User','id','created_by');
    }
}
