<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentInvoicePayments extends Model
{
    use HasFactory;
    protected $table = 'appointment_invoice_payments';

    protected $fillable = [
        'currency',
        'amount_paid',
        'txn_id',
        'payment_type',
        'payment_status',
        'payment_by',
        'project_id',
        'chance'
    ];


    public function clientAppointmentPaymentInvoice()
    {
        return $this->hasOne('App\Models\Project', 'project_id', 'project_id');
    }
}
