<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;


class ProductAttributeLists extends Model
{
    use Notifiable;

   protected $fillable = [
        'product_attribute_id', 'list_value','created_by','updated_by','created_at','updated_at',
    ];
	
	 
}
