<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use Notifiable;

    protected $fillable = [
        'purpose', 'date','type','amount','updated_by','created_by','user_account_id'
    ];
}
