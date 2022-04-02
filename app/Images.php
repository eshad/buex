<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    use Notifiable;

    protected $fillable = [
        'image_name', 'thumb_image_name','source_type','source_id','created_by','updated_by',
    ];
}
