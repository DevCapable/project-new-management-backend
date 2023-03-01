<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    protected $fillable = [
        'title','className','start','end','allDay','workspace','created_by'
    ];
}
