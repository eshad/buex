<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Images;
use App\Classes\Slim;
use DB;
use Image;
use Hash;

class TextController extends Controller
{
    public function change_password()
    {
		$user_id = Auth::user()->id;
		$user_detail = DB::table('users')->leftJoin('images', function($join){ $join->on('images.source_id','=','users.id') ->where('images.source_type', '=', 'user');})->where('users.id', '=', $user_id)->get();
		return view('user/changepassword',['user_detail'=>$user_detail]);
    }
	
	Public function change_my_password_save(Request $request)
	{
		$edit_id = $request->edit_id;
		$user_name = $request->user_name;
		$new_password = $request->new_password;
		$current_password = $request->current_password;
		$user = User::find($edit_id);
		$passwordIsOk = password_verify($current_password,$user->password);
		if($current_password != '')
		{
			if($passwordIsOk)
			{
				if($new_password != '')
				{
					$user->fill([
						'password' =>bcrypt($new_password)		
							]);
						$user->save();
						Session::flash('title', 'Password Changed Success'); 
						Session::flash('success-toast-message', 'Your Password is successfully Changed');	
						return redirect('text');
				}else
				{
					return redirect()->back()->withErrors(['Please Enter New Password','Please Enter New Password']);
				}
			}else{
					return redirect()->back()->withErrors(['Please Enter correct Current Password','Please Enter correct Current Password']);
				}
		 }else
			{
				if($current_password == '')
				{
					$user->fill([
						'name' =>$user_name		
						]);
						$user->save();
						Session::flash('title', 'Name Changed Success'); 
						Session::flash('success-toast-message', 'Your Name is successfully Changed');	
						return redirect('text');
				}else{
					return redirect()->back()->withErrors(['Please Enter correct Current Password','Please Enter correct Current Password']);
				}
			}
	}

	public function profile_images_upload(Request $request)
	{
		$user_id = Auth::user()->id;
		if($request->avatar)
		{
			$image1 = Slim::getImages('avatar')[0];
			if(isset($image1['output']['data']))
			{
				$name1 = $image1['input']['name'];
				$data1 = $image1['output']['data'];
				$path1 = base_path() . '/public/user_images/normal_images/';
				$file1 = Slim::saveFile($data1, $name1, $path1);
				$imagePath1 = asset('/public/images/normal_images/' . $file1['name']);
			}
			
			$image_sql = DB::select('select * from  images where source_id="'.$user_id.'" and source_type="user"');
			if($image_sql)
			{
				unlink('./public/user_images/normal_images/'.$image_sql[0]->image_name);
				Images::where('source_id', $user_id)->where('source_type','user')->delete();
				
			}
			$image_save = Images::create([
				'image_name' => $file1['name'],
				'thumb_image_name' => $file1['name'],
				'source_type' => 'user',
				'source_id' => $user_id,
				'created_by' => 1,
				'updated_by' => 1,
			]);
			$image_save->save();
		}
		Session::flash('title', 'Success'); 
		Session::flash('success-toast-message', 'Image uploaded successfully');
		return redirect('text');
	}


}
