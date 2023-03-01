<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractsAttachment extends Model
{
     protected $fillable = [
        'contract_id',
        'user_id',
        'files',
        'client_id',
        'workspace_id',
    ];  
}
