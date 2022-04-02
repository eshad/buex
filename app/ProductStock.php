<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    use Notifiable;

    protected $fillable = [
        'product_id', 'location_id','quantity','reason','created_by','updated_by','user_account_id'
    ];
}
