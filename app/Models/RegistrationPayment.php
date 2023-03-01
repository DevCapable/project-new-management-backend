<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationPayment extends Model
{
    use HasFactory;
    protected $table = 'registration_payments';

    protected $fillable = [
        'user_id',
        'email',
        'reference',
        'payment_date',
        'channel',
        'currency',
        'ip_address',
        'appointment_chance'
    ];
}
