<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Desktop extends Model
{
      use Notifiable;
    protected $fillable = [
        'total'
    ];

}
