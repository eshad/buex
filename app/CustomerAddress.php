<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;


class CustomerAddress extends Model
{
    use Notifiable;

   protected $fillable = [
        'customer_id', 'customer_full_name','address_1', 'address_2','address_3', 'postal_code','city', 'state','country_id', 'email','mobile','is_default',
    ];
	
	 public function country_name()
    {
         return $this->hasOne('App\Country');
    }
	
	 public function country(){
 			return $this->hasMany('App\Country','id', 'country_id','name');
       }
	
	
}
