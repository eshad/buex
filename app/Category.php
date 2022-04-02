<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use Notifiable;

    protected $fillable = [
        'category_code', 'category_name','category_attributes','updated_by','created_by','user_account_id'
    ];
}
