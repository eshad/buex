<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class CustomerCredit extends Model
{
    use Notifiable;

    protected $fillable = [
        'customer_id', 'payment_id', 'amount','used_status'
		
    ];
}
