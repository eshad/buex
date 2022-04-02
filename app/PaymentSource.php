<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;


class PaymentSource extends Model
{
    use Notifiable;

   protected $fillable = [
        'source_name', 'created_by','updated_by', 'created_at','updated_at',
    ];
	
	
}
