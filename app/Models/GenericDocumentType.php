<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenericDocumentType extends Model
{
    use HasFactory;
    protected $table = 'generic_document_types';

    protected $fillable = ['id','title','slug','parent_id'];

    public $timestamps = false;

    public function documents()
    {
        return $this->hasMany('App\Models\GenericDocument','type_id');
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\GenericDocumentType','parent_id');
    }

    public function subTypes()
    {
        return $this->hasMany('App\Models\GenericDocumentType','parent_id');
    }
}
