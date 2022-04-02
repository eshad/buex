<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Mail;
use Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
	 
	  public function __construct()
    {
        
        $this->middleware('permission:view-user',   ['only' => ['show','create']]);
    }
	
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        $user = User::orderBy('id','DESC')->get(); 
		
        return view('user.index', compact('user'));
    }
	
	public function sales_agent_list(Request $request)
    {
        

        $user = User::orderBy('id','DESC')->get(); 
		
        return view('user.sales_agent_list', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
		$request->validate([
			'name' 		=>  'required|max:100',
			'email' 	=>  'email|unique:users|required|max:100',
			'contact' 	=>  'required|numeric|digits_between:9,12',
			'address' 	=>  'required|max:500',
			'ic_number' =>  'required|max:100',
			
		 ]);
       	$password = rand(100000,999999);
		
        $requestData = $request->except('roles');
		$requestData['password'] =$password;
		
        $roles=$request->roles;
		$role = $roles[0];
        $user =  User::create($requestData);

        $user->assignRole($roles);
		$email = $request->email;
		Mail::send('mail.register_user', ['password' => $password,'username' =>$request->name,'email'=>$email], function ($message) use($email,$role)
        {
            $message->to($email)->subject('Ukshop: Register as '.$role);
        
        });
		Session::flash('title', 'User added success'); 
		Session::flash('success-toast-message', 'Your new user added successfully ');
        return redirect('user');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        
        $requestData = $request->all();
        
        $user = User::findOrFail($id);
        $user->update($requestData);

        //$user->syncRoles($request->roles);

       	Session::flash('title', 'User Updated success'); 
		Session::flash('success-toast-message', 'Your new user Updated successfully ');
        return redirect('user');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        User::destroy($id);

        return redirect('user')->with('flash_message', 'User deleted!');
    }
	
	public function loginasuser($userid){
		Session::put('admin_id', Auth::user()->id);
		Auth::loginUsingId($userid);
		
		return redirect('dashboard');
	}
	
	public function backtoadmin(){
		$userid = Session::get('admin_id');
		Session::forget('admin_id');
		Auth::loginUsingId($userid);
		
		return redirect('dashboard');
	}
	
	public function inactive_user($userid){
		 $user = User::findOrFail($userid);
        $user->active='0';
		$user->save();
		Session::flash('title', 'User Updated success'); 
		Session::flash('success-toast-message', 'Your new user Updated successfully ');
        return redirect('user');
	}
	
	public function active_user($userid){
		 $user = User::findOrFail($userid);
        $user->active='1';
		$user->save();
		Session::flash('title', 'User Updated success'); 
		Session::flash('success-toast-message', 'Your new user Updated successfully ');
        return redirect('user');
	}
	
	public function test(){
		$checkuser = User::find(3);
		$roles = $checkuser->getRoleNames();
		dd($roles[0]);
	}
}
