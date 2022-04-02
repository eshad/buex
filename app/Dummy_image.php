<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Dummy_image extends Model
{
    use Notifiable;

    protected $fillable = [
        'image_name',
    ];
}
