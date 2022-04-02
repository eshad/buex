<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;


class Order extends Model
{
    use Notifiable;

  protected $fillable = [
        'order_code', 'customer_id','customer_address_id','shipping_location_id','shipping_type_id','order_date','est_delivery_date','total_item','manage_local_postage_cost','total_airfreight_cost','total_local_postage_cost','order_total','note','created_by','order_tab','order_status','is_cancel','user_account_id','is_force_active','cancel_request',
    ];
	
	
	public function ship_quantity()
	{
	  return $this->hasMany('App\OrderItem')
		->selectRaw('SUM(ship_quantity) as total_ship_quantity')
		->groupBy('order_id');
	}
}
