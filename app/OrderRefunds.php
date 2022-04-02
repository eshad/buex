<?php
namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class OrderRefunds extends Model
{
    use Notifiable;   
  
    protected $fillable = ['order_id','amount',
        'refund_status', 'user_account_id', 'created_by','updated_by'
		
    ];
}
