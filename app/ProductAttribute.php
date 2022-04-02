<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use Notifiable;

    protected $fillable = [
        'attribute_name', 'type', 'value','created_by','updated_by','created_at','updated_at','user_account_id',
    ];
}
