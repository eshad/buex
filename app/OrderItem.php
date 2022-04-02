<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;


class OrderItem extends Model
{
    use Notifiable;
	protected $fillable = [
        'order_id', 'product_id','payment_plan_id','quantity','local_postage_type','product_price','s_from','total_amount','ship_quantity','pending_quantity','dispatch_ready','shipment_id','dispatch_quantity',
    ];
}
