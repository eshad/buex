<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class UsersCommission extends Model
{
    use Notifiable;

    protected $fillable = [    
        'product_id', 'commission_rate','commission_type', 'customer_id','quantity','total_commission','commission_rate','unit_price','created_by','updated_by','user_account_id','order_id',
    ];
}
