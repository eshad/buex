<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class PaymentLine extends Model
{
    use Notifiable;

    protected $fillable = [
        'payment_id', 'order_id', 'amount','created_by','updated_by'
		
    ];
}
