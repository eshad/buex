<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
	
	protected function authenticated(Request $request)
	{
		
		
	if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'active' => 0])) {
			Auth::logout();
			Session::flash('title', 'Something went wrong'); 
			Session::flash('alert-class', 'alert-danger');
			Session::flash('message', 'Account is not activated in Ukshop. Please contact to admin first');
			return redirect()->back()->withInput($request->only($this->username(), 'remember'));
		}
		
		
		
	}
}
