<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use Notifiable;

    protected $fillable = ['payment_code',
        'payment_customer', 'payment_date', 'payment_amount','order_amount','payment_source','payment_note','payment_ref_number','created_by','updated_by','payment_status',
		
    ];
}
