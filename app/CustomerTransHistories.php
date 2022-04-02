<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class CustomerTransHistories extends Model
{
    use Notifiable;

    protected $fillable = [
        'customer_id', 'trans_type', 'trans_id','amount','balance','created_by','updated_by'
		
    ];
}
