<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TermsAndCondition extends Model
{
    use HasFactory;
    protected $table = 'terms_and_conditions';
    protected  $fillable = [
        'user_id',
        'name',
        'slug',
        'info'
    ];

    public  function  terms(){
        return $this->belongsTo(User::class,'user_id');
    }

    public  function  clients(){
        return $this->belongsTo(Client::class,'client_id');
    }
}
