<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'item_type','item_id','price','qty','invoice_id'
    ];

    public function product(){
        return $this->hasOne('App\Models\Product','id','item_id');
    }
    public function task(){
        return $this->hasOne('App\Models\Task','id','item_id');
    }
}
