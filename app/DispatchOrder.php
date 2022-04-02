<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class DispatchOrder extends Model
{
    use Notifiable;

    protected $fillable = [
        'order_id', 'customer_address_id','dispatch_date','courier_id','consignment_code','user_aacount_id','created_by','collect_by','accounts_id',
    ];
}
