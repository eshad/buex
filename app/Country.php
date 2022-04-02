<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;


class Country extends Model
{
    use Notifiable;

  public function user(){
    	return $this->belongsTo('App\CustomerAddress', 'country_id');
   }
	
}
