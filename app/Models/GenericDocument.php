<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenericDocument extends Model
{
    use HasFactory;

    protected $table = 'generic_documents';

    protected $fillable = ['name', 'slug', 'description', 'type_id','is_required','format','created_at','updated_at'];

    protected $keepRevisionOf = ['name'];

    public $timestamps = false;

    public function type(){
        return $this->belongsTo('App\Models\GenericDocumentType','type_id');
    }

    public function files(){
        return $this->hasMany('App\Models\GenericDocument','document_id');
    }

    public function taskFiles()
    {
        return $this->hasMany('App\Models\Task', 'task_id', 'type_id');
    }
}
