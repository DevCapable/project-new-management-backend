<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffWorkspace extends Model
{
    protected $fillable = [
        'staff_id','workspace_id','permission','is_active'
    ];
}
