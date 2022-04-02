<?php
namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class SendEmailChecks extends Model
{
    use Notifiable;   
    protected $fillable = ['source_type','source_id',
        'action_type'
    ];
}
