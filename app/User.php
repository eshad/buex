<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Hash;
use Auth;
use DB;

class User extends Authenticatable
{
    use Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','contact','address','ic_number'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
         'remember_token',
    ];
	
	public function setPasswordAttribute($value)
    {
        if($value){
            $this->attributes['password']= app('hash')->needsRehash($value)?Hash::make($value):$value;
        }
    }
	
	public function getProfileimageAttribute(){
		
		$user_id = Auth::user()->id;
		$image_sql = DB::select('select * from  images where source_id="'.$user_id.'" and source_type="user"');
		if($image_sql)
		{
			return $image_sql[0]->image_name;
		}else
		{
			return '';
		}
   }	
}
