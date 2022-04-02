<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;


class Customer extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_uniq_id', 'customer_full_name','created_by','user_account_id','updated_by',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
   /* protected $hidden = [
        'password', 'remember_token',
    ];*/
	
	public function customer_address()
    {
        return $this->hasMany('App\CustomerAddress');
    }
	public function is_default()
    {
        return $this->check_default()->where('is_default', 1);
    }
	
	public function check_default()
    {
        return $this->hasOne('App\CustomerAddress');
    }
	
	public function country()
    {
        return $this->hasOne('App\Country');
    }
	
	
}
