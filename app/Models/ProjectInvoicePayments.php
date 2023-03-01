<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectInvoicePayments extends Model
{
    use HasFactory;
    protected $table = 'project_invoice_payments';

    protected $fillable = [
        'currency',
        'amount_paid',
        'txn_id',
        'payment_type',
        'payment_status',
        'payment_by',
        'project_id'
    ];


    public function clientPaymentInvoice()
    {
        return $this->hasOne('App\Models\Project', 'project_id', 'project_id');
    }
}
