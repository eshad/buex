<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Notifiable;

    protected $fillable = [
        'item_uniq_id', 'category_id','product_name','product_note','stock_place','product_price','installment_cost','sm_cost','ss_cost','air_freight_cost','initial_stock','created_by','updated_by','uk_stock','malaysia_stock','user_account_id'
    ];
}
