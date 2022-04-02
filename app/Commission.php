<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use Notifiable;

    protected $fillable = [
        'low_unit_price', 'high_unit_price','unit_commission','updated_by','created_by','user_account_id'
    ];
}
