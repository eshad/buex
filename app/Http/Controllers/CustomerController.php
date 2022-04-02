<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Customer;
use App\CustomerAddress;
use App\Country;
use Auth;
use DB;
use Illuminate\Contracts\Encryption\DecryptException;
class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::orderBy('id','DESC')->get();
		return view('customer.customer_list',compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {	
		$customer_count = Customer::latest()->first();
		if($customer_count){
			 $customer_uniq_id = 'cust-'.(1001+$customer_count->id);
		}else{
			 $customer_uniq_id = 'cust-1001';
		}
		$Countries = Country::all();
        return view('customer.add_customer',compact('customer_uniq_id','Countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {	
		 $request->validate([
			'customer_full_name' 	=>  'required|max:100',
			'address_1' 			=>  'required|max:100',
			'address_2' 			=>  'max:100',
			'address_3' 			=>  'max:100',
			'city' 					=>  'required|max:50',
			'postal_code' 			=>  'required|max:100',
			'state' 				=>  'required|max:30',
			'country_id' 			=>  'required|numeric',
			'email' 				=>  'email|max:100',
			'mobile' 				=>  'required|numeric|digits_between:9,12',
		 ]);
        
		 $customer_count = Customer::latest()->first();
		 if($customer_count){
			 $customer_uniq_id = 'cust-'.(1001+$customer_count->id);
		 }else{
			 $customer_uniq_id = 'cust-1001';
		 }
		 
		 $user = Auth::user();
		 if(Session::get('admin_id')){
			$created_by = Session::get('admin_id');
		}else{
			$created_by = $user->id;
		}
		 $customer = Customer::create([
				'customer_uniq_id' => $customer_uniq_id,
				'customer_full_name' => $request->customer_full_name,
				'created_by' => $created_by,
				'user_account_id' => $user->id,
 			]);
		if($customer->id){
			$CustomerAddress = CustomerAddress::create([
				'customer_id' 		 => $customer->id,
				'customer_full_name' => $request->customer_full_name,
				'address_1' 		 => $request->address_1,
				'address_2' 		 => $request->address_2,
				'address_3' 		 => $request->address_3,
				'postal_code' 		 => $request->postal_code,
				'city' 				 => $request->city,
				'state' 			 => $request->state,
				'country_id' 		 => $request->country_id,
				'email' 			 => $request->email,
				'mobile' 			 => ltrim($request->mobile,'0'),
				'is_default' 		 => '1',
			 ]);
			 
			 for($i=0;$i<count($request->other_customer_full_name);$i++){
				$CustomerAddress = CustomerAddress::create([
					'customer_id' 		 => $customer->id,
					'customer_full_name' => $request->other_customer_full_name[$i],
					'address_1' 		 => $request->other_address_1[$i],
					'address_2' 		 => $request->other_address_2[$i],
					'address_3' 		 => $request->other_address_3[$i],
					'postal_code' 		 => $request->other_postal_code[$i],
					'city' 				 => $request->other_city[$i],
					'state' 			 => $request->other_state[$i],
					'country_id' 		 => $request->other_country_id[$i],
					'email' 			 => $request->other_email[$i],
					'mobile' 			 => ltrim($request->other_mobile[$i],'0'),
					'is_default' 		 => '0',
			 	]);	 
			 }
			 if($CustomerAddress){
				 Session::flash('title', 'Customer added success'); 
				 Session::flash('success-toast-message', 'Your new customer added successfully ');	
			 
				 return redirect('customer');
			 }else{
				Customer::destroy($customer->id);
				Session::flash('title', 'Customer not added'); 
				Session::flash('error-toast-message', 'Please try again , Customer not added yet');	
			 	return redirect('customer');
			 }
		}else{
			Session::flash('title', 'Customer not added'); 
			Session::flash('error-toast-message', 'Please try again , Customer not added yet');	
			return redirect('customer');
		}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = decrypt($id);
	    $customer = Customer::find($id);
		$customer_address= $customer->customer_address()->get();
		$Countries = Country::all();
		return view('customer.edit_customer',compact('customer','customer_address','Countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       $id = decrypt($id);
	  
	   $request->validate([
			'customer_full_name' 	=>  'required|max:100',
			'address_1' 			=>  'required|max:100',
			'address_2' 			=>  'max:100',
			'address_3' 			=>  'max:100',
			'city' 					=>  'required|max:50',
			'postal_code' 			=>  'required|max:100',
			'state' 				=>  'required|max:30',
			'country_id' 			=>  'required|numeric',
			'email' 				=>  'email|max:100',
			'mobile' 				=>  'required|numeric|digits_between:9,12',
		 ]);
        
		$Customer = Customer::findOrFail($id);
		$user = Auth::user();
		 if(Session::get('admin_id')){
			$updated_by = Session::get('admin_id');
		}else{
			$updated_by = $user->id;
		}
        $Customer->update([		
			'customer_full_name' => $request->customer_full_name,
			'updated_by'=>$updated_by,
 		]);
		
		$updateCustomerAddress = CustomerAddress::where('customer_id',$id)->where('is_default','1');
		
		$updateCustomerAddress->update([
			'customer_full_name' => $request->customer_full_name,
			'address_1' 		 => $request->address_1,
			'address_2' 		 => $request->address_2,
			'address_3' 		 => $request->address_3,
			'postal_code' 		 => $request->postal_code,
			'city' 				 => $request->city,
			'state' 			 => $request->state,
			'country_id' 		 => $request->country_id,
			'email' 			 => $request->email,
			'mobile' 			 => ltrim($request->mobile,'0'),
		 ]);
		$isdeleted = CustomerAddress::where('customer_id',$id)->where('is_default','0')->delete();
		if($request->other_customer_full_name){
			 for($i=0;$i<count($request->other_customer_full_name);$i++){
				$addCustomerAddress = CustomerAddress::create([
					'customer_id' 		 => $id,
					'customer_full_name' => $request->other_customer_full_name[$i],
					'address_1' 		 => $request->other_address_1[$i],
					'address_2' 		 => $request->other_address_2[$i],
					'address_3' 		 => $request->other_address_3[$i],
					'postal_code' 		 => $request->other_postal_code[$i],
					'city' 				 => $request->other_city[$i],
					'state' 			 => $request->other_state[$i],
					'country_id' 		 => $request->other_country_id[$i],
					'email' 			 => $request->other_email[$i],
					'mobile' 			 => ltrim($request->other_mobile[$i],'0'),
					'is_default' 		 => '0',
			 	]);	 
			 }
		}
			
				 Session::flash('title', 'Customer Update success'); 
				 Session::flash('success-toast-message', 'Your customer updated successfully ');	
			 
				 return redirect('customer');
			 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $id = decrypt($id);
		 
		CustomerAddress::where('customer_id',$id)->delete();
		 $isdeleted = Customer::destroy($id);
		 if($isdeleted){
			 Session::flash('title', 'Customer delete success'); 
			 Session::flash('success-toast-message', 'Your customer deleted successfully ');	
		 
			 return redirect('customer');
		 }else{
			Customer::destroy($customer->id);
			Session::flash('title', 'Customer not deleted'); 
			Session::flash('error-toast-message', 'Please try again , Customer not deleted yet');	
			return redirect('customer');
		 }
    }
	
	public function ajax_view_customer(Request $request){
		$customer_id = $request->customer_id;
		$customer_addresses = DB::table('customer_addresses')->select('customer_addresses.*','countries.nicename','countries.phonecode')->where('customer_addresses.customer_id',$customer_id)->join('countries', 'countries.id', '=', 'customer_addresses.country_id')->get();
		/*$customer = Customer::with(['is_default'=>function($query){
            $query->where('is_default' , '1');
          }])->where('id',$customer_id)->get();
		$Country = Country::find($customer[0]->is_default->country_id);*/
		 return response()->json(['customer_addresses'=>$customer_addresses]);
	}
	
	public function ajax_check_mobile_duplicate(Request $request) {
		$mobile_no = $request->mobile_no;
		$count = CustomerAddress::where('is_default','1')->where('mobile',$mobile_no)->get()->count();
		return response()->json(['count'=>$count]);
	}
	
	public function ajax_check_customer_email_duplicate(Request $request) {
		$email = $request->email;
		$count = CustomerAddress::where('is_default','1')->where('email',$email)->get()->count();
		return response()->json(['count'=>$count]);
	}
	public function ajax_check_edit_customer_email_duplicate(Request $request) {
		$email = $request->email;
		$customer_id = $request->customer_id;
		$count = CustomerAddress::where('is_default','1')->where('email',$email)->where('customer_id','!=',$customer_id)->get()->count();
		return response()->json(['count'=>$count]);
	}
}
