<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class ProductList extends Model
{
    use Notifiable;

    protected $fillable = [
       'product_id','attr_id','att_name','att_value','created_by','updated_by'
    ];
}
