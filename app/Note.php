<?php
namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use Notifiable;   
  
    protected $fillable = ['source_type','source_comment',
        'source_id', 'user_account_id', 'notes_time','created_by','notify', 'notify_admin', 'notes_time','notify_sales_agent','notify_dispatch','acknow_admin','acknow_sales_agent','acknow_dispatch','admin_read_status','dispatch_read_status','agent_read_status'
		
    ];
}


