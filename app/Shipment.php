<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use Notifiable;

    protected $fillable = [
        'shipment_date','bl_awb_number','carrier_details','shipment_number','shipment_type','total_stock_sum','sold_item_sum','ship_quantity_sum','created_by','created_by','status'
    ];
}
