<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Account;
use App\User;
use App\Images;
use DB;
use Image;
use Auth;

class AccountController extends Controller
{
   public function index()
   {
	 $account=Account::all();
     return view('account/account_list',['account'=>$account]);
   }
   
   
	public function user_list()
    {
		
		$user = User::orderBy('id','DESC')->get(); 
		//dd($user[0]->id);
        return view('account/user_list', compact('user'));
      
    }
   
    public function create()
    {
        return view('account/add_account');
    }
    
    public function store(Request $request)
    {
		$user = Auth::user();
		if(Session::get('admin_id')){
			$created_by = Session::get('admin_id');
		}else{
			$created_by = $user->id;
		}
		
        $validator = Validator::make($request->all(), [
			'purpose'=> 'required|max:60',
			'date'=> 'required|date',
			'type'=> 'required|',
			'amount'=> 'required|max:18',
			
		 ]);
		 
		if($validator->passes()) 
	    {
			$Account= new Account();
			$Account->purpose=$request->get('purpose');
			$Account->date=$request->get('date');
			$Account->type=$request->get('type');
			$Account->amount=$request->get('amount');
			$Account->created_by=$created_by;
			$Account->updated_by=$created_by;
			$Account->user_account_id=$user->id;
			$Account->save();
		
			Session::flash('title', 'Account Added Success'); 
			Session::flash('success-toast-message', 'Your Account is successfully Added');	
			return redirect('/user_account/'.encrypt($user->id));
		}else{
		   return response()->json(['error'=>$validator->errors()->all()]);
	   }
    }
	
    public function show($user_id)
	{	
		if (decrypt($user_id)){
			$where_condition = ['user_account_id' => decrypt($user_id)];
			$account=Account::where($where_condition)->get();
			//dd($account);
			$user = User::find(decrypt($user_id));
     		return view('account/account_list',['account'=>$account,'user'=>$user]);
		}else{
			return redirect('dashboard');
		}
		//dd('show');
    }
	
    public function edit()
    {
        dd('edit');
    }

	
    public function destroy(Request $request,$id)
    {
       $Account = Account::find($id);
       $Account->delete();
	   Session::flash('title', 'Account Deleted Success'); 
	   Session::flash('success-toast-message', 'Your Account is successfully Deleted');	
	   return redirect('account');
    }

}
