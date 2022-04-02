<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Payment;
use App\PaymentLine;
use App\CustomerCredit;
use App\Note;

use App\CustomerTransHistories;
use App\PaymentRefunds;
use App\PaymentSource;
use App\Customer;
use App\Order;
use App\Images;
use DB;
use Auth;
use Image;
use PDF;
use Mail;

class Payment_Controller extends Controller
{
    public function add_manage_payment_view($order_id='')
    {	
		$order_details='';
		$customer_id='';
		if($order_id!=''){
			$order_id = decrypt($order_id); 
			$order_details = Order::find($order_id);
		}
		/*get payment source list*/
		$payment_last = Payment::latest()->first();
		if($payment_last){
			 $payment_code = 'PAYID-'.(1001+$payment_last->id);
		}else{
			 $payment_code = 'PAYID-1001';
		}
		
		$payment_source = PaymentSource::select('payment_sources.id','payment_sources.source_name')
		->get();
		/*get customer list*/
		$customer_list = Customer::select('customers.id','customers.customer_uniq_id','customers.customer_full_name')->orderBy('id', 'DESC')
		->get();
		
		return view('payment/add_payment',['payment_source'=>$payment_source,'customer_list'=>$customer_list,'payment_code'=>$payment_code,'order_details'=>$order_details,'customer_id'=>$customer_id]);
    }
	
	public function add_customer_payment_view($customer_id)
    {	
		$order_details='';
		
		$customer_id = decrypt($customer_id); 
		
		/*get payment source list*/
		$payment_last = Payment::latest()->first();
		if($payment_last){
			 $payment_code = 'PAYID-'.(1001+$payment_last->id);
		}else{
			 $payment_code = 'PAYID-1001';
		}
		
		$payment_source = PaymentSource::select('payment_sources.id','payment_sources.source_name')
		->get();
		/*get customer list*/
		$customer_list = Customer::select('customers.id','customers.customer_uniq_id','customers.customer_full_name')
		->get();
		
		return view('payment/add_payment',['payment_source'=>$payment_source,'customer_list'=>$customer_list,'payment_code'=>$payment_code,'order_details'=>$order_details,'customer_id'=>$customer_id]);
    }
	
    public function ajax_getuser_orderlist(Request $request)
	{
		  $userid = $request->userid;
		  $source_detail = Order::select(\DB::raw('orders.*'))
          					->where('customer_id',$userid)->get();
		  
		  $order_detail=array();
		  $userCredit=$this->getUserCredit($userid);
		  //$order_detail[]= array('userCredit'=>$userCredit);
		  foreach($source_detail as $orders){
			  
			if($orders->is_cancel==1){  
			 $payment_details =  DB::select("SELECT  if(sum(`payment_lines`.`amount`) IS NULL ,'0',sum(`amount`) )   as payment_amount,  `orders`.`amount_penalty`  FROM `orders` JOIN `payment_lines` ON `payment_lines`.`order_id` = `orders`.`id` JOIN `payments` ON `payments`.`id` = `payment_lines`.`payment_id`  WHERE `payments`.`payment_status` = 'Verified' and `orders`.`id`='".$orders->id."'"); 
			
				if($payment_details[0]->payment_amount < $payment_details[0]->amount_penalty){
					
					$data['id']=$orders->id;
					 $data['order_code']=$orders->order_code;
					 $data['order_code']=$orders->order_code;
					 $data['note']=$orders->note;
					 $data['order_date']=$orders->order_date;
					 $data['order_total']=$orders->order_total;
					 $data['amount_penalty']=$orders->amount_penalty;
					 
					 //$users_amount_sum =  DB::select("SELECT  if(sum(`payment_lines`.`amount`) IS NULL ,'0',sum(`amount`) )   as payment_amount,  `orders`.`amount_penalty`  FROM `orders` JOIN `payment_lines` ON `payment_lines`.`order_id` = `orders`.`id` JOIN `payments` ON `payments`.`id` = `payment_lines`.`payment_id`  WHERE `payments`.`payment_status` = 'Verified' and `orders`.`id`='".$orders->id."'"); 	
					 $users_amount_sum = DB::table('payment_lines')
						 ->select(DB::raw('sum(amount) as amount'))
						 ->leftJoin('payments', 'payments.id', '=', 'payment_lines.payment_id')
						 ->where('payment_lines.order_id',$orders->id)
						 ->where('payments.payment_status','Verified')
						 ->get();
					 $data['open_amount']=($data['order_total']+ $data['amount_penalty'])-$users_amount_sum[0]->amount;
					  $data['open_amount2']=($data['order_total']+ $data['amount_penalty'])-($users_amount_sum[0]->amount);
					 if($data['open_amount']!=0){
					   $order_detail[]= $data;
					 }
					
				}
			  
					 
			}
			else
			{
				 $data['id']=$orders->id;
				 $data['order_code']=$orders->order_code;
				 $data['order_code']=$orders->order_code;
				 $data['note']=$orders->note;
				 $data['order_date']=$orders->order_date;
				 $data['order_total']=$orders->order_total;
				 $data['amount_penalty']=$orders->amount_penalty;
				 
				 $users_amount_sum = DB::table('payment_lines')
						 ->select(DB::raw('sum(amount) as amount'))
						 ->leftJoin('payments', 'payments.id', '=', 'payment_lines.payment_id')
						 ->where('payment_lines.order_id',$orders->id)
						 ->where('payments.payment_status','!=','Declined')
						 ->get();
				/* $users_amount_sum =  DB::select("SELECT  if(sum(`payment_lines`.`amount`) IS NULL ,'0',sum(`amount`) ) as payment_amount,  `orders`.`amount_penalty`  FROM `orders` JOIN `payment_lines` ON `payment_lines`.`order_id` = `orders`.`id` JOIN `payments` ON `payments`.`id` = `payment_lines`.`payment_id`  WHERE `payments`.`payment_status` = 'Verified' and `orders`.`id`='".$orders->id."'"); 
				 if(count($users_amount_sum)>0){$order_payment_amount=$users_amount_sum[0]->amount;}else{$order_payment_amount='';}		*/ 
						 
				  $data['open_amount']=($data['order_total']+ $data['amount_penalty'])-$users_amount_sum[0]->amount;
				  $data['open_amount2']=($data['order_total']+ $data['amount_penalty'])-($users_amount_sum[0]->amount);
				 if($data['open_amount']!=0){
				   $order_detail[]= $data;
				 }
			}
		  }
		 return response()->json($order_detail);
	}
	
	public function all_customer_list()
    {
		$order_detail=array();
		$customer_list = Customer::select('customers.id')
		->get();
		 foreach($customer_list as $customers){
		
		   $customer_list = DB::select(DB::raw("SELECT customer_full_name,customer_uniq_id,balance_amount,customers.id as customers_id ,count(DISTINCT order_id) as order_total ,sum(quantity) as order_quantity,((SELECT sum( order_total) FROM orders where customer_id=o.customer_id ) ) as total,(SELECT sum(`amount`) FROM `customer_credits`where customer_id=o.customer_id ) as t_credits_amount,(SELECT sum(`payment_amount`) FROM `payments` where payment_customer=o.customer_id and payment_status='Verified' ) as t_payment_amount  FROM `order_items` oi inner join orders o on o.id= oi.order_id inner join customers on customers.id=o.customer_id WHERE customer_id='".$customers->id."'")); 
		  
		  $order_detail[]=$customer_list[0] ;
		}
		return view('payment/customer_balance',['customer_list'=>$order_detail]);
    }
	
	public function ajax_getuser_credit(Request $request)
	{
		
		  $userid = $request->userid;
		  $customer_credit = DB::select(DB::raw("SELECT `credit_amount` FROM `customers` WHERE id=".$userid."")); 
		  
		  $c_p_total=$customer_credit[0]->credit_amount ;
		  $credit_detail[]=$c_p_total ;
		  return response()->json($credit_detail);
	}
	
	

    public function store(Request $request)
    {		
	        $loginuserId =  Auth::user()->id;
	        $image_1 = $request->file('image_2');
			$image_1_name='';
			$destinationPaththumb = public_path('/payment_image/thumbnail_images');
			$destinationPath = public_path('/payment_image/normal_images');
			if($image_1)
			{
				try{
					$canvas = Image::canvas(245, 245);
					$image_1_name = time().'_'.$image_1->getClientOriginalName();
					$thumb_img1  = Image::make($image_1->getRealPath())->resize(245, 245, function($constraint)
					{
						$constraint->aspectRatio();
					});
					$canvas->insert($thumb_img1, 'center');
					$canvas->save($destinationPaththumb.'/'.$image_1_name);
					$image_1->move($destinationPath, $image_1_name);
				}catch (\Exception $e)
				{
					return Redirect::back()->withErrors(['Image file not readable']);
				}
			}
			
			    $payment = Payment::create([  
					'payment_source' => $request->payment_source,
					'payment_code' => $request->payment_code,
					'payment_customer' => $request->payment_customer,
					'payment_date' => $request->payment_date,
					'payment_amount' => ($request->applyAmount)+($request->creditAmount),
					'order_amount' => $request->applyAmount,
					'payment_ref_number' => $request->payment_ref_number,
					'payment_note' => $request->payment_note,
					'created_by' => $loginuserId,
					'updated_by' => $loginuserId,
 			    ]);
				
							
				
				if($image_1_name != '')
			    {
					if($payment->id){
						$image_save = Images::create([
							'image_name' => $image_1_name,
							'thumb_image_name' => $image_1_name,
							'source_type' => 'payment',
							'source_id' => $payment->id,
							'created_by' => 1,
							'updated_by' => 1,
						]);
						$image_save->save();
					}
				}
				
				if($payment->id){
					for($i=0;$i<count($request->order_id);$i++){
					
					if($request->pay_line_amm[$i]!=0){
						
					$payment_line = PaymentLine::create([
						  'payment_id' => $payment->id,
						  'order_id' => $request->order_id[$i],
						  'amount' => $request->pay_line_amm[$i],
						  'created_by' => '1',
						  'updated_by' => '1',
					]);	 
					}
				   if($request->pay_line_amm[$i]!=0){ 
					if($request->pay_line_amm[$i]==$request->li_amm[$i] && $request->pay_line_amm[$i]!=0)
					{
						$order_payment_status='Paid'; 
					}
					elseif(($request->pay_line_amm[$i]<$request->li_amm[$i]) && $request->pay_line_amm[$i]!=0 )
					{
					   	
						$order_payment_status='Partially_paid'; 
					}
					else
					{
						 $order_payment_status='Unpaid'; 
					}
				    DB::table('orders')
					->where('id', $request->order_id[$i])
					->update(['order_payment_status' => $order_payment_status]);	 
				   }
				  }
				  
				}
				
			
				
				
				
				if($payment->id){
					$image_save = CustomerCredit::create([
						'customer_id' => $request->payment_customer,
						'payment_id' => $payment->id,
						'amount' =>$request->creditAmount,
				    ]);
					
				}
				
				if($payment->id){   
				  $usertrans=$this->getUsertransHistory($request->payment_customer);
				  $t_amount=$request->applyAmount+$request->creditAmount;
				  
				  if($usertrans->balance<0){
					  $balance= $usertrans->balance+$t_amount;
				  }else
				  {
  				   $balance= $usertrans->balance-$t_amount;
				  }

				  $image_save = CustomerTransHistories::create([
					'customer_id' => $request->payment_customer,
					'trans_type' => 'payment',
					'trans_id' =>$payment->id,
					'amount' => $t_amount,
					'balance' =>$balance,
				  ]);	
				 }
				if($payment->id){
			   	  Session::flash('title', 'Payment added success'); 
				  Session::flash('success-toast-message', 'Your new payment added successfully ');	
				  return redirect('manage_payment');
				}
	}
	
	
	public function getCustomerDetails($userid)
	{
	   $users_details = DB::table('customers')
	             ->join ('customer_addresses', 'customer_addresses.customer_id', '=', 'customers.id')
				 ->select(DB::raw('*'))
				 ->where('customers.id',$userid)
				 ->where('customer_addresses.is_default',1)
				 ->get();
	   return $users_details[0];	
	}
	
	
		
	public function getUserCredit($userid)
	{
	   $users_credits_sum = DB::table('customer_credits')
				 ->select(DB::raw('sum(amount) as amount'))
				 ->where('customer_id',$userid)
				 ->get();
	   return $users_credits_sum[0]->amount;	
	}
	
	public function getUsertransHistory($userid)
	{
	   $customer_credit = DB::select(DB::raw("select * from customer_trans_histories where id = (select max(`id`) from customer_trans_histories WHERE `customer_id`='".$userid."')")); 
	   return $customer_credit[0];	
	}
	
	Public function delete_payment_details($id,$status,$redirectlink)   
	{
		$result=payment::where('id', $id)->update(['payment_status' => $status]);		/*$this->delete_payment_line_details($id);
		$this->delete_payment_customer_credits($id);
		$this->delete_customer_trans_histories($id);*/
		Session::flash('title', 'Payment Details Declined Success'); 
		Session::flash('success-toast-message', 'Your Payment Details is successfully Declined');	
		return redirect($redirectlink);
	}
	
	Public function delete_payment_cust_balance($id,$status,$redirectlink,$customer_id)   
	{
		$cid=encrypt($customer_id);
		Payment::destroy($id);
		$this->delete_payment_line_details($id);
		$this->delete_payment_customer_credits($id);
		$this->delete_customer_trans_histories($id,'payment');
		Session::flash('title', 'Payment Details Deleted Success'); 
		Session::flash('success-toast-message', 'Your Payment Details is successfully Deleted');	
		return redirect('view_customer_balance/'.$cid);
	}
	
	
	Public function delete_orders_details($id,$customer_id)   
	{
		$cid=encrypt($customer_id);
		Order::destroy($id);
		$this->delete_order_line_details($id);
		$this->delete_customer_trans_histories($id,'order');
		Session::flash('title', 'Order Details Deleted Success'); 
		Session::flash('success-toast-message', 'Your Order Details is successfully Deleted');	
		return redirect('view_customer_balance/'.$cid);
	}
	
	Public function delete_order_line_details($id)   
	{
        DB::table('order_items')->where('order_id', '=',$id)->delete();
	}
	
	
	Public function delete_payment_line_details($id)   
	{
        DB::table('payment_lines')->where('payment_id', '=',$id)->delete();
	}
	
	Public function delete_payment_customer_credits($id)   
	{
        DB::table('customer_credits')->where('payment_id', '=',$id)->delete();
	}
	Public function delete_customer_trans_histories($id,$type)   
	{
        DB::table('customer_trans_histories')->where('trans_id', '=',$id)->where('trans_type', '=',$type)->delete();
	}
	
	
	
	Public function get_payment_details($id)   
	{
		$payment_details = DB::select(DB::raw("SELECT * FROM `payments` WHERE `id`=".$id."")); 
	    return $payment_details[0];	
	}
	Public function get_refund_payment_details($id)   
	{
		$refund_details = DB::select(DB::raw("SELECT payment_refunds.*,orders.order_total,orders.customer_id,(SELECT SUM(amount) from  payment_lines where order_id=payment_refunds.`order_id`) as order_payment_amount FROM `payment_refunds` INNER join orders ON orders.id=payment_refunds.order_id  WHERE payment_refunds.`id`=".$id."")); 
	    return $refund_details[0];	
	}
	
	
	Public function change_payment_status($id,$status)   
	{
		$result=payment::where('id', $id)->update(['payment_status' => $status]);
		if($result)
		{   
			$payment_details=$this->get_payment_details($id);
			$payment_amount=$payment_details->payment_amount;
			$credit_amount=$payment_details->payment_amount - $payment_details->order_amount;
			$payment_customer=$payment_details->payment_customer;
				
		    $cust_details = $this->getCustomerDetails($payment_customer);
			$customer_credit=$cust_details->credit_amount+$credit_amount;
			$balance_amount=$cust_details->balance_amount-$payment_amount;
			
			DB::table('customers')
			->where('id', $payment_customer)
			->update(['credit_amount' => $customer_credit,'balance_amount' => $balance_amount]);
		}
		Session::flash('title', 'Payment Details Updated Success'); 
		Session::flash('success-toast-message', 'Your Payment Details is successfully Updated');	
		return redirect('verify_payment');
	}
	
	
	Public function change_refund_payment_status($id,$status)   
	{
		$result=PaymentRefunds::where('id', $id)->update(['refund_status' => $status]);
		if($result)
		{   
			$refund_details=$this->get_refund_payment_details($id);
			$refund_amount=$refund_details->amount;
			$order_total=$refund_details->order_total;
			$order_payment_amount=$refund_details->order_payment_amount;
		    $refund_customer_id=$refund_details->customer_id;
			/*update customer balance*/
			$customer_details = $this->getCustomerDetails($refund_customer_id);
	 
			$customer_balance=$customer_details->balance_amount-$order_total;
			
			$customer_balance=$customer_balance+$order_payment_amount;
			
			DB::table('customers')
					->where('id', $refund_customer_id)
					->update(['balance_amount' => $customer_balance]);
		}
		Session::flash('title', 'Refund Status changed Success'); 
		Session::flash('success-toast-message', 'Your Payment Refund Details is successfully Updated');	
		return redirect('refund');
	}
	
	Public function change_refund_status(Request $request)   
	{
		        $user = Auth::user();
				if(Session::get('admin_id')){
					$created_by = Session::get('admin_id');
				}else{
					$created_by = $user->id;
				}
				$user_account_id =  $user->id;
		$id=$request->action_source_id;
		$status=$request->action_source_status;
		$result=PaymentRefunds::where('id', $id)->update(['refund_status' => $status]);
		if($result)
		{   
				$refund_details=$this->get_refund_payment_details($id);
				$refund_amount=$refund_details->amount;
				$order_total=$refund_details->order_total;
				$order_payment_amount=$refund_details->order_payment_amount;
				$refund_customer_id=$refund_details->customer_id;
				/*update customer balance*/
				$customer_details = $this->getCustomerDetails($refund_customer_id);
				$customer_balance=$customer_details->balance_amount-$order_total;
				if($customer_details->balance_amount>0){
				$customer_balance=$customer_details->balance_amount-$order_total;
				}
				else{
				  $customer_balance=$customer_details->balance_amount+$order_total;	
				}
				$response=DB::table('customers')
					->where('id', $refund_customer_id)
					->update(['balance_amount' => $customer_balance]);
			 
						$image_1 = $request->file('refund_image');
						$image_1_name='';
						$destinationPaththumb = public_path('/refund_image/thumbnail_images');
						$destinationPath = public_path('/refund_image/normal_images');
						if($image_1)
						{
							try{
								$canvas = Image::canvas(245, 245);
								$image_1_name = time().'_'.$image_1->getClientOriginalName();
								$thumb_img1  = Image::make($image_1->getRealPath())->resize(245, 245, function($constraint)
								{
									$constraint->aspectRatio();
								});
								$canvas->insert($thumb_img1, 'center');
								$canvas->save($destinationPaththumb.'/'.$image_1_name);
								$image_1->move($destinationPath, $image_1_name);
							}catch (\Exception $e)
							{
								return Redirect::back()->withErrors(['Image file not readable']);
							}
						}
						
						if($image_1_name != '')
						{
								$image_save = Images::create([
									'image_name' => $image_1_name,
									'thumb_image_name' => $image_1_name,
									'source_type' => 'refund',
									'source_id' => $id,
									'created_by' => $created_by,
					                'updated_by' => $created_by,
								]);
								$image_save->save();
						}
						
				$note = Note::create([    
					'source_comment' => $request->source_comment,
					'source_type' => 'order',
					'source_id' => $request->action_order_id,
					'notes_time' => date('Y-m-d H:i:s'),
					'user_account_id' => $user_account_id,
					'created_by' => $created_by,
					'updated_by' => $created_by,
 			    ]);
						
						
		}
		Session::flash('title', 'Refund Status changed Success'); 
		Session::flash('success-toast-message', 'Your Payment Refund Details is successfully Updated');	
		return redirect('refund');
	}
	
	
	public function view_customer_balance($customer_id)
    {
		$user = Auth::user();
		$customer_id = decrypt($customer_id); 
		$trans_histories_list = DB::table('customer_trans_histories')
		->where('customer_id',$customer_id)->orderBy('id', 'ASC')
		->get();
		$trans_record=array(); $balance_total=0.00;
		for($i=0;$i<count($trans_histories_list);$i++){
			$trans_id=$trans_histories_list[$i]->trans_id;
			$trans_type=$trans_histories_list[$i]->trans_type;
			if($trans_type=='order')
			{
			    $order_details = DB::select(DB::raw("SELECT orders.*,(select sum(quantity) from order_items where order_id=orders.id) as t_o_quantity,users.name as created_by FROM `orders` inner join users on users.id=orders.created_by  WHERE orders.`id`=".$trans_id."")); 
				if(count($order_details)>0){
				$data['created_by']=$order_details[0]->created_by;
				$data['trans_name']=$order_details[0]->order_code; 
				$data['trans_date']=$order_details[0]->order_date;
				$data['quantity']=$order_details[0]->t_o_quantity;
				$data['total']=$order_details[0]->order_total;
				$balance_total=$balance_total+$order_details[0]->order_total;
				$data['balance']=$balance_total;
				$data['trans_type']='order';
                $data['verified']='';
				$data['trans_id']=$order_details[0]->id;
				$data['customer_id']=$customer_id;
				
				
				if($user->hasRole('Super-Admin')){
				$notes_count = DB::table('notes')
				->where('source_id', '=', $trans_id)
				->where('source_type', '=', $trans_type)
				->where('acknow_admin', '=', 0)
				->where('created_by', '<>', Auth::id()) 
				->count();
				}
				elseif($user->hasRole('Dispatch-Manager')){
				$notes_count = DB::table('notes')
				->where('source_id', '=', $trans_id)
				->where('source_type', '=', $trans_type)
				->where('acknow_dispatch', '=', 0)
				->where('created_by', '<>', Auth::id()) 
				->count();
				}
				else{
				$notes_count = DB::table('notes')
				->where('source_id', '=', $trans_id)
				->where('source_type', '=', $trans_type)
				->where('acknow_sales_agent', '=', 0)
				->where('created_by', '<>', Auth::id()) 
				->count();
				}
				
				
				if($notes_count>0)
				{
				  $data['notes_no']=$notes_count;
				}
				else{
				  $data['notes_no']=0;	
				}
				$payment_count = DB::table('payment_lines')
				->where('order_id', '=', $trans_id)
				->count();
				if($payment_count>0){
				  $data['order_payment_status']='yes';	
				}
				else{
				  $data['order_payment_status']='no';		
				}
				
	            $trans_record[]=$data;
				}
			}
			elseif($trans_type=='cancel')
			{
				 $sql="SELECT orders.*,(select sum(quantity) from order_items where order_id=orders.id) as t_o_quantity,users.name as created_by FROM `orders` inner join users on users.id=orders.created_by  WHERE orders.`id`=".$trans_id.""; 
			    $order_details = DB::select(DB::raw($sql)); 
				if(count($order_details)>0){
				$data['created_by']=$order_details[0]->created_by;
				$data['trans_name']=$order_details[0]->order_code; 
				$data['trans_date']=$order_details[0]->order_date;
				$data['quantity']=$order_details[0]->t_o_quantity;
				$data['total']=$order_details[0]->order_total;
				$balance_total=$balance_total-$order_details[0]->order_total;
				$data['balance']=$balance_total;
				$data['trans_type']='cancel';
                $data['verified']='';
				$data['trans_id']=$order_details[0]->id;
				$data['customer_id']=$customer_id;
				//print_r($data);
				$payment_count = DB::table('payment_lines')
				->where('order_id', '=', $trans_id)
				->count();
				if($payment_count>0){
				  $data['order_payment_status']='yes';	
				}
				else{
				  $data['order_payment_status']='no';		
				}
				
				if($user->hasRole('Super-Admin')){
				$notes_count = DB::table('notes')
				->where('source_id', '=', $trans_id)
				->where('source_type', '=', $trans_type)
				->where('acknow_admin', '=', 0)
				->where('created_by', '<>', Auth::id()) 
				->count();
				}
				elseif($user->hasRole('Dispatch-Manager')){
				$notes_count = DB::table('notes')
				->where('source_id', '=', $trans_id)
				->where('source_type', '=', $trans_type)
				->where('acknow_dispatch', '=', 0)
				->where('created_by', '<>', Auth::id()) 
				->count();
				}
				else{
				$notes_count = DB::table('notes')
				->where('source_id', '=', $trans_id)
				->where('source_type', '=', $trans_type)
				->where('acknow_sales_agent', '=', 0)
				->where('created_by', '<>', Auth::id()) 
				->count();
				}
				
				if($notes_count>0)
				{
				  $data['notes_no']=$notes_count;
				}
				else{
				  $data['notes_no']=0;	
				}
				
	            $trans_record[]=$data;
				}
			}
			elseif($trans_type=='refund')
			{
				$refund_details = DB::select(DB::raw("SELECT payment_refunds.*,users.name as created_by,orders.order_code as order_code FROM `payment_refunds` inner join users ON users.id=payment_refunds.created_by inner join customer_trans_histories ON customer_trans_histories.trans_id=payment_refunds.id INNER JOIN orders ON orders.id=payment_refunds.order_id where customer_trans_histories.trans_id=".$trans_id."")); 
				if(count($refund_details)>0){
				$refund_status = DB::select(DB::raw("SELECT `updated_at` FROM `payment_refunds` WHERE  `id` ='".$trans_id."'"));
				
				 
				$data['created_by']=$refund_details[0]->created_by;
				$data['trans_name']=$refund_details[0]->order_code; 
				$data['trans_date']=$refund_status [0]->updated_at;
				$data['quantity']='';
				$data['total']=$refund_details[0]->amount;
				if($refund_details[0]->refund_status=='1')
				{
					 $data['verified']='Verified';
				     $balance_total=$balance_total+$refund_details[0]->amount;	
				}
				else{
					 $data['verified']='Un-verified';
					 $balance_total=$balance_total;
				}
				$data['customer_id']=$customer_id;
				$data['order_payment_status']='no';
				$data['balance']=$balance_total;
				$data['trans_type']='refund';
				$data['trans_id']=$refund_details[0]->id;
				
			    if($user->hasRole('Super-Admin')){
				$notes_count = DB::table('notes')
				->where('source_id', '=', $trans_id)
				->where('source_type', '=', $trans_type)
				->where('acknow_admin', '=', 0)     
				->where('created_by', '<>', Auth::id()) 
				->count();
				}
				elseif($user->hasRole('Dispatch-Manager')){
				$notes_count = DB::table('notes')
				->where('source_id', '=', $trans_id)
				->where('source_type', '=', $trans_type)
				->where('acknow_dispatch', '=', 0)
				->where('created_by', '<>', Auth::id()) 
				->count();
				}
				else{
				$notes_count = DB::table('notes')
				->where('source_id', '=', $trans_id)
				->where('source_type', '=', $trans_type)
				->where('acknow_sales_agent', '=', 0)
				->where('created_by', '<>', Auth::id()) 
				->count();
				}
				
				if($notes_count>0)
				{
				  $data['notes_no']=$notes_count;
				}
				else{
				  $data['notes_no']=0;	
				}
				
				//print_r($data);
	            $trans_record[]=$data;
			 }
			}
			elseif($trans_type=='penalty')
			{
		
			   $sql="SELECT orders.*,customer_trans_histories.amount,(select sum(quantity) from order_items where order_id=orders.id) as t_o_quantity,users.name as created_by FROM `orders` inner join users on users.id=orders.created_by INNER JOIN customer_trans_histories ON customer_trans_histories.trans_id=orders.id  WHERE orders.`id`='".$trans_id."' and `trans_type`='penalty' "; 
				 
			    $penalty_details = DB::select(DB::raw($sql)); 
				if(count($penalty_details)>0){
				$data['created_by']=$penalty_details[0]->created_by;
				$data['trans_name']=$penalty_details[0]->order_code; 
				$data['trans_date']=$penalty_details[0]->order_date;
				$data['quantity']='';
				$data['total']=$penalty_details[0]->amount;
				$balance_total=$balance_total+$penalty_details[0]->amount;
				$data['balance']=$balance_total;
				$data['trans_type']='penalty';
                $data['verified']='';
				$data['trans_id']=$penalty_details[0]->id;
				$data['customer_id']=$customer_id;
				//print_r($data);
				$payment_count = DB::table('payment_lines')
				->where('order_id', '=', $trans_id)
				->count();
				if($payment_count>0){
				  $data['order_payment_status']='yes';	
				}
				else{
				  $data['order_payment_status']='no';		
				}
				
				if($user->hasRole('Super-Admin')){
				$notes_count = DB::table('notes')
				->where('source_id', '=', $trans_id)
				->where('source_type', '=', $trans_type)
				->where('acknow_admin', '=', 0)
				->where('created_by', '<>', Auth::id()) 
				->count();
				}
				elseif($user->hasRole('Dispatch-Manager')){
				$notes_count = DB::table('notes')
				->where('source_id', '=', $trans_id)
				->where('source_type', '=', $trans_type)
				->where('acknow_dispatch', '=', 0)
				->where('created_by', '<>', Auth::id()) 
				->count();
				}
				else{
				$notes_count = DB::table('notes')
				->where('source_id', '=', $trans_id)
				->where('source_type', '=', $trans_type)
				->where('acknow_sales_agent', '=', 0)
				->where('created_by', '<>', Auth::id()) 
				->count();
				}
				
				if($notes_count>0)
				{
				  $data['notes_no']=$notes_count;
				}
				else{
				  $data['notes_no']=0;	
				}
				
	            $trans_record[]=$data;
				}
				
			}
			else if($trans_type=='payment')
			{
				$payment_details = DB::select(DB::raw("SELECT payments.*,users.name as created_by FROM `payments` inner join users ON users.id=payments.created_by WHERE payments.`id`=".$trans_id.""));
				//dd($payment_details); 
				if(count($payment_details)>0){
				
				$data['created_by']=$payment_details[0]->created_by;
				$data['trans_name']=$payment_details[0]->payment_code; 
				$data['trans_date']=$payment_details[0]->payment_date;
				$data['quantity']=' ';
				$data['total']=$payment_details[0]->payment_amount;
				if($payment_details[0]->payment_status=='Verified')
				{
				   $balance_total=$balance_total-$payment_details[0]->payment_amount;	
				}
				else{
					$balance_total=$balance_total;
				}
				$data['customer_id']=$customer_id;
				$data['order_payment_status']='no';
				$data['balance']=$balance_total;
				$data['trans_type']='payment';
                $data['verified']=$payment_details[0]->payment_status;
				$data['trans_id']=$payment_details[0]->id;
				
				if($user->hasRole('Super-Admin')){
				$notes_count = DB::table('notes')
				->where('source_id', '=', $trans_id)
				->where('source_type', '=', $trans_type)
				->where('acknow_admin', '=', 0)
				->where('created_by', '<>', Auth::id()) 
				->count();
				}
				elseif($user->hasRole('Dispatch-Manager')){
				$notes_count = DB::table('notes')
				->where('source_id', '=', $trans_id)
				->where('source_type', '=', $trans_type)
				->where('acknow_dispatch', '=', 0)
				->where('created_by', '<>', Auth::id()) 
				->count();
				}
				else{
				$notes_count = DB::table('notes')
				->where('source_id', '=', $trans_id)
				->where('source_type', '=', $trans_type)
				->where('acknow_sales_agent', '=', 0)
				->where('created_by', '<>', Auth::id()) 
				->count();
				}
				
				
				if($notes_count>0)
				{
				  $data['notes_no']=$notes_count;
				}
				else{
				  $data['notes_no']=0;	
				}
				
	            $trans_record[]=$data;
				}
			}
		}
		$customer_details = $this->getCustomerDetails($customer_id);
		$customer_name=$customer_details->customer_full_name;
		
        return view('payment/view_customer_balance',['trans_histories_list'=>$trans_record,'customerid'=>encrypt($customer_id),'customer_name'=>$customer_name]);
    }
	
	/*code d k*/
	
	
	public function payment_source_list()
    {
		$product_list = DB::table('payment_sources')
		->select('payment_sources.id','payment_sources.source_name')
		->where('payment_sources.id', '<>',  1 )	
		->get();
		return view('payment/payment_source',['product_list'=>$product_list]);
    }
	
	public function verify_payment_list()
    {
		
			$payment_list = DB::select('select `payments`.*, `payments`.`id` as `payment_id`,`payment_sources`.`source_name` as `source_name`, `customers`.`id`, `customers`.`customer_uniq_id`,`customers`.`customer_full_name`,`users`.`name`, (select image_name from images where source_id=payments.id and source_type="payment" limit 1 ) as `payment_image` from `payments` inner join `customers` on `customers`.`id` = `payments`.`payment_customer` left join `users` on `users`.`id` = `payments`.`created_by` inner join payment_sources on payment_sources.id=payments.payment_source ORDER BY `payments`.`id` DESC');
		
		return view('payment/verify_payment',['payment_list'=>$payment_list]);
    }
	
	public function refund_list()
    {
		$refunds_payment_list = DB::select('select `payment_refunds`.*, (select image_name from images where source_id=payment_refunds.`id` and source_type="refund" ) as `refund_image`, `orders`.`order_code`,`orders`.`id` as order_id, `orders`.`order_date`, `payment_refunds`.`id` as `payment_refunds_id`, `customers`.`customer_full_name`, `customers`.`customer_uniq_id`, `users`.`name` as `created_by` from `payment_refunds` inner join `users` on `users`.`id` = `payment_refunds`.`created_by` inner join `orders` on `orders`.`id` = `payment_refunds`.`order_id` inner join `customers` on `customers`.`id` = `orders`.`customer_id` order by `payment_refunds`.`id` desc');
		
		return view('payment/refund',['refunds_payment_list'=>$refunds_payment_list]);
    }
	
	public function ajax_save_payment_source(Request $request)
	{
		$source_name = $request->payment_source;
		$payment_source = PaymentSource::create([
		'source_name' => $source_name,
		'created_by' => 1,
		'updated_by' => 1,
		]);
		$payment_source->save();
		$last_inser_id = $payment_source->id;
		return $last_inser_id;
	}
	
	public function ajax_take_payment_source(Request $request)
	{
		$source_id = $request->source_id;
		$source_detail = DB::select('select source_name from payment_sources where id="'.$source_id.'"');
		return $source_detail[0]->source_name;
	}
	
	public function ajax_update_payment_source(Request $request)
	{
		$source_id = $request->source_id;
		$source_name = $request->source_name;
		$payment = PaymentSource::find($source_id);
		$payment->source_name = $source_name;
		$payment->save();
		$last_insert_id = $payment->id;
		return $last_insert_id;
	}
	
	Public function delete_payment_source(Request $request,$id)
	{
		PaymentSource::find($id)->delete();
		Session::flash('title', 'Payment Source Deleted Success'); 
		Session::flash('success-toast-message', 'Your Payment Source is successfully Deleted');	
		return redirect('payment_source');
	}
	
	
	Public function ajax_payment_ref_number(Request $request)
	{
		$pay_ref_id = $request->pay_ref_id;
		$pay_ref = Payment::all()->where('payment_ref_number', $pay_ref_id)->first();
		if ($pay_ref) {
		  return 'false';
		} 
		else {
		  return 'true';
		}
	}
	
	Public function view_payment_details($payment_id,$type,$user_id)
	{
		 $payment_id = decrypt($payment_id); 
		 $user_id = decrypt($user_id); 
		
		
		$payment_details = DB::select('SELECT * FROM `payments` WHERE `id`='.$payment_id.'');
		$image_details = DB::select("SELECT  `image_name`,`thumb_image_name` FROM `images` WHERE `source_id`=$payment_id and source_type='payment'");
		$payment_line_row = DB::select('SELECT orders.*,payment_lines.amount as payment_amount FROM `payment_lines` inner join orders ON orders.id=payment_lines.order_id  WHERE `payment_id`='.$payment_id.'');
		$payment_source = PaymentSource::select('payment_sources.id','payment_sources.source_name')
		->get();
		/*get customer list*/
		$customer_list = Customer::select('customers.id','customers.customer_uniq_id','customers.customer_full_name')
		->get();
		
		//print_r($payment_details[0]);
		return view('payment/view_payment',['payment_source'=>$payment_source,'customer_list'=>$customer_list,'payment_details'=>$payment_details[0],'payment_lines'=>$payment_line_row,'payment_image_name'=>$image_details,'customer_id'=>encrypt($user_id)]);
		
	}
	
	public function downloadPDF($customer_id,$pdftype)
	{
		$user = Auth::user();
		$customer_id = decrypt($customer_id); 
		$trans_histories_list = DB::table('customer_trans_histories')
		->where('customer_id',$customer_id)->orderBy('id', 'ASC')
		->get();
		$trans_record=array(); $balance_total=0.00;
		for($i=0;$i<count($trans_histories_list);$i++){
			$trans_id=$trans_histories_list[$i]->trans_id;
			$trans_type=$trans_histories_list[$i]->trans_type;
			if($trans_type=='order')
			{
			    $order_details = DB::select(DB::raw("SELECT orders.*,(select sum(quantity) from order_items where order_id=orders.id) as t_o_quantity,users.name as created_by FROM `orders` inner join users on users.id=orders.created_by  WHERE orders.`id`=".$trans_id."")); 
				if(count($order_details)>0){
				$data['created_by']=$order_details[0]->created_by;
				$data['trans_name']=$order_details[0]->order_code; 
				$data['trans_date']=$order_details[0]->order_date;
				$data['quantity']=$order_details[0]->t_o_quantity;
				$data['total']=$order_details[0]->order_total;
				$balance_total=$balance_total+$order_details[0]->order_total;
				$data['balance']=$balance_total;
				$data['trans_type']='order';
                $data['verified']='';
				$data['trans_id']=$order_details[0]->id;
				$data['customer_id']=$customer_id;
				
				
				if($user->hasRole('Super-Admin')){
				$notes_count = DB::table('notes')
				->where('source_id', '=', $trans_id)
				->where('source_type', '=', $trans_type)
				->where('acknow_admin', '=', 0)
				->where('created_by', '<>', Auth::id()) 
				->count();
				}
				elseif($user->hasRole('Dispatch-Manager')){
				$notes_count = DB::table('notes')
				->where('source_id', '=', $trans_id)
				->where('source_type', '=', $trans_type)
				->where('acknow_dispatch', '=', 0)
				->where('created_by', '<>', Auth::id()) 
				->count();
				}
				else{
				$notes_count = DB::table('notes')
				->where('source_id', '=', $trans_id)
				->where('source_type', '=', $trans_type)
				->where('acknow_sales_agent', '=', 0)
				->where('created_by', '<>', Auth::id()) 
				->count();
				}
				
				
				if($notes_count>0)
				{
				  $data['notes_no']=$notes_count;
				}
				else{
				  $data['notes_no']=0;	
				}
				$payment_count = DB::table('payment_lines')
				->where('order_id', '=', $trans_id)
				->count();
				if($payment_count>0){
				  $data['order_payment_status']='yes';	
				}
				else{
				  $data['order_payment_status']='no';		
				}
				
	            $trans_record[]=$data;
				}
			}
			elseif($trans_type=='cancel')
			{
				 $sql="SELECT orders.*,(select sum(quantity) from order_items where order_id=orders.id) as t_o_quantity,users.name as created_by FROM `orders` inner join users on users.id=orders.created_by  WHERE orders.`id`=".$trans_id.""; 
			    $order_details = DB::select(DB::raw($sql)); 
				if(count($order_details)>0){
				$data['created_by']=$order_details[0]->created_by;
				$data['trans_name']=$order_details[0]->order_code; 
				$data['trans_date']=$order_details[0]->order_date;
				$data['quantity']=$order_details[0]->t_o_quantity;
				$data['total']=$order_details[0]->order_total;
				$balance_total=$balance_total-$order_details[0]->order_total;
				$data['balance']=$balance_total;
				$data['trans_type']='cancel';
                $data['verified']='';
				$data['trans_id']=$order_details[0]->id;
				$data['customer_id']=$customer_id;
				//print_r($data);
				$payment_count = DB::table('payment_lines')
				->where('order_id', '=', $trans_id)
				->count();
				if($payment_count>0){
				  $data['order_payment_status']='yes';	
				}
				else{
				  $data['order_payment_status']='no';		
				}
				
				if($user->hasRole('Super-Admin')){
				$notes_count = DB::table('notes')
				->where('source_id', '=', $trans_id)
				->where('source_type', '=', $trans_type)
				->where('acknow_admin', '=', 0)
				->where('created_by', '<>', Auth::id()) 
				->count();
				}
				elseif($user->hasRole('Dispatch-Manager')){
				$notes_count = DB::table('notes')
				->where('source_id', '=', $trans_id)
				->where('source_type', '=', $trans_type)
				->where('acknow_dispatch', '=', 0)
				->where('created_by', '<>', Auth::id()) 
				->count();
				}
				else{
				$notes_count = DB::table('notes')
				->where('source_id', '=', $trans_id)
				->where('source_type', '=', $trans_type)
				->where('acknow_sales_agent', '=', 0)
				->where('created_by', '<>', Auth::id()) 
				->count();
				}
				
				if($notes_count>0)
				{
				  $data['notes_no']=$notes_count;
				}
				else{
				  $data['notes_no']=0;	
				}
				
	            $trans_record[]=$data;
				}
			}
			elseif($trans_type=='refund')
			{
				$refund_details = DB::select(DB::raw("SELECT payment_refunds.*,users.name as created_by,orders.order_code as order_code FROM `payment_refunds` inner join users ON users.id=payment_refunds.created_by inner join customer_trans_histories ON customer_trans_histories.trans_id=payment_refunds.id INNER JOIN orders ON orders.id=payment_refunds.order_id where customer_trans_histories.trans_id=".$trans_id."")); 
				if(count($refund_details)>0){
				$refund_status = DB::select(DB::raw("SELECT `updated_at` FROM `payment_refunds` WHERE  `id` ='".$trans_id."'"));
				
				 
				$data['created_by']=$refund_details[0]->created_by;
				$data['trans_name']=$refund_details[0]->order_code; 
				$data['trans_date']=$refund_status [0]->updated_at;
				$data['quantity']='';
				$data['total']=$refund_details[0]->amount;
				if($refund_details[0]->refund_status=='1')
				{
					 $data['verified']='Verified';
				     $balance_total=$balance_total+$refund_details[0]->amount;	
				}
				else{
					 $data['verified']='Un-verified';
					 $balance_total=$balance_total;
				}
				$data['customer_id']=$customer_id;
				$data['order_payment_status']='no';
				$data['balance']=$balance_total;
				$data['trans_type']='refund';
				$data['trans_id']=$refund_details[0]->id;
				
			    if($user->hasRole('Super-Admin')){
				$notes_count = DB::table('notes')
				->where('source_id', '=', $trans_id)
				->where('source_type', '=', $trans_type)
				->where('acknow_admin', '=', 0)     
				->where('created_by', '<>', Auth::id()) 
				->count();
				}
				elseif($user->hasRole('Dispatch-Manager')){
				$notes_count = DB::table('notes')
				->where('source_id', '=', $trans_id)
				->where('source_type', '=', $trans_type)
				->where('acknow_dispatch', '=', 0)
				->where('created_by', '<>', Auth::id()) 
				->count();
				}
				else{
				$notes_count = DB::table('notes')
				->where('source_id', '=', $trans_id)
				->where('source_type', '=', $trans_type)
				->where('acknow_sales_agent', '=', 0)
				->where('created_by', '<>', Auth::id()) 
				->count();
				}
				
				if($notes_count>0)
				{
				  $data['notes_no']=$notes_count;
				}
				else{
				  $data['notes_no']=0;	
				}
				
				//print_r($data);
	            $trans_record[]=$data;
			 }
			}
			elseif($trans_type=='penalty')
			{
		
			   $sql="SELECT orders.*,customer_trans_histories.amount,(select sum(quantity) from order_items where order_id=orders.id) as t_o_quantity,users.name as created_by FROM `orders` inner join users on users.id=orders.created_by INNER JOIN customer_trans_histories ON customer_trans_histories.trans_id=orders.id  WHERE orders.`id`='".$trans_id."' and `trans_type`='penalty' "; 
				 
			    $penalty_details = DB::select(DB::raw($sql)); 
				if(count($penalty_details)>0){
				$data['created_by']=$penalty_details[0]->created_by;
				$data['trans_name']=$penalty_details[0]->order_code; 
				$data['trans_date']=$penalty_details[0]->order_date;
				$data['quantity']='';
				$data['total']=$penalty_details[0]->amount;
				$balance_total=$balance_total+$penalty_details[0]->amount;
				$data['balance']=$balance_total;
				$data['trans_type']='penalty';
                $data['verified']='';
				$data['trans_id']=$penalty_details[0]->id;
				$data['customer_id']=$customer_id;
				//print_r($data);
				$payment_count = DB::table('payment_lines')
				->where('order_id', '=', $trans_id)
				->count();
				if($payment_count>0){
				  $data['order_payment_status']='yes';	
				}
				else{
				  $data['order_payment_status']='no';		
				}
				
				if($user->hasRole('Super-Admin')){
				$notes_count = DB::table('notes')
				->where('source_id', '=', $trans_id)
				->where('source_type', '=', $trans_type)
				->where('acknow_admin', '=', 0)
				->where('created_by', '<>', Auth::id()) 
				->count();
				}
				elseif($user->hasRole('Dispatch-Manager')){
				$notes_count = DB::table('notes')
				->where('source_id', '=', $trans_id)
				->where('source_type', '=', $trans_type)
				->where('acknow_dispatch', '=', 0)
				->where('created_by', '<>', Auth::id()) 
				->count();
				}
				else{
				$notes_count = DB::table('notes')
				->where('source_id', '=', $trans_id)
				->where('source_type', '=', $trans_type)
				->where('acknow_sales_agent', '=', 0)
				->where('created_by', '<>', Auth::id()) 
				->count();
				}
				
				if($notes_count>0)
				{
				  $data['notes_no']=$notes_count;
				}
				else{
				  $data['notes_no']=0;	
				}
				
	            $trans_record[]=$data;
				}
				
			}
			else if($trans_type=='payment')
			{
				$payment_details = DB::select(DB::raw("SELECT payments.*,users.name as created_by FROM `payments` inner join users ON users.id=payments.created_by WHERE payments.`id`=".$trans_id.""));
				//dd($payment_details); 
				if(count($payment_details)>0){
				
				$data['created_by']=$payment_details[0]->created_by;
				$data['trans_name']=$payment_details[0]->payment_code; 
				$data['trans_date']=$payment_details[0]->payment_date;
				$data['quantity']=' ';
				$data['total']=$payment_details[0]->payment_amount;
				if($payment_details[0]->payment_status=='Verified')
				{
				   $balance_total=$balance_total-$payment_details[0]->payment_amount;	
				}
				else{
					$balance_total=$balance_total;
				}
				$data['customer_id']=$customer_id;
				$data['order_payment_status']='no';
				$data['balance']=$balance_total;
				$data['trans_type']='payment';
                $data['verified']=$payment_details[0]->payment_status;
				$data['trans_id']=$payment_details[0]->id;
				
				if($user->hasRole('Super-Admin')){
				$notes_count = DB::table('notes')
				->where('source_id', '=', $trans_id)
				->where('source_type', '=', $trans_type)
				->where('acknow_admin', '=', 0)
				->where('created_by', '<>', Auth::id()) 
				->count();
				}
				elseif($user->hasRole('Dispatch-Manager')){
				$notes_count = DB::table('notes')
				->where('source_id', '=', $trans_id)
				->where('source_type', '=', $trans_type)
				->where('acknow_dispatch', '=', 0)
				->where('created_by', '<>', Auth::id()) 
				->count();
				}
				else{
				$notes_count = DB::table('notes')
				->where('source_id', '=', $trans_id)
				->where('source_type', '=', $trans_type)
				->where('acknow_sales_agent', '=', 0)
				->where('created_by', '<>', Auth::id()) 
				->count();
				}
				
				
				if($notes_count>0)
				{
				  $data['notes_no']=$notes_count;
				}
				else{
				  $data['notes_no']=0;	
				}
				
	            $trans_record[]=$data;
				}
			}
		}
		
      $customer_details = $this->getCustomerDetails($customer_id);
      $pdf = PDF::loadView('payment/customer_balance_history_pdf',['trans_histories_list'=>$trans_record,'customer_full_name '=>$customer_details->customer_full_name ]);
	  if($pdftype=='sendemail'){
		    $destination_path = public_path('/pdf/customer_balance_history');
	        $pdf->save($destination_path.'/Customer-Balance-History-C'.$customer_id.'.pdf'); 
		    
			$email=$customer_details->email;
			Mail::send('mail.customer_balance_history_pdf', ['customer'=>$customer_details], function ($message) use($email,$destination_path,$customer_id)
			{
				$message->to($email)->subject('Ukshop: Refund History');
				$message->attach($destination_path.'/Customer-Balance-History-C'.$customer_id.'.pdf');
			}); 
			$cid=encrypt($customer_id);
		    Session::flash('title', 'Payment History'); 
			Session::flash('success-toast-message', 'Payment history pdf send via email');	
			return redirect('view_customer_balance/'.$cid);
		   
	  }
	  else{
		   return $pdf->download('Payment-History-C'.$customer_id.'.pdf'); 
	  }
    }
	
	
	public function refunddownloadpdf($pdftype)
    {
		$refunds_payment_list = DB::table('payment_refunds')
		->join ('users', 'users.id', '=', 'payment_refunds.created_by')
		->join ('orders', 'orders.id', '=', 'payment_refunds.order_id')
		->join ('customers', 'customers.id', '=', 'orders.customer_id')
		->select('payment_refunds.*','orders.order_code','orders.order_date','payment_refunds.id as payment_refunds_id','customers.customer_full_name','customers.customer_uniq_id','users.name as created_by')
		->get();
		
	  $pdf = PDF::loadView('payment/refund_history_pdf',['refunds_payment_list'=>$refunds_payment_list]);
	  if($pdftype=='sendemail'){
		    $destination_path = public_path('/pdf/refund_history');
	        $pdf->save($destination_path.'/Refund_History.pdf'); 
		    $email='dineshsuryawanshi11@gmail.com';
			Mail::send('mail.refund_history', [], function ($message) use($email,$destination_path)
			{
				$message->to($email)->subject('Ukshop: Register as');
				$message->attach($destination_path.'/Refund_History.pdf');
			}); 
			
			Session::flash('title', 'Refund History'); 
			Session::flash('success-toast-message', 'Refund history pdf send via email');	
			return redirect('refund');
	  }
	  else{
		   return $pdf->download('Refund_History.pdf'); 
	  }
		
		//return view('payment/refund',['refunds_payment_list'=>$refunds_payment_list]);
    }
	
	public static function get_customer_current_balance($customer_id)
    {
		$user = Auth::user();
		//$customer_id = decrypt($customer_id); 
		$trans_histories_list = DB::table('customer_trans_histories')
		->where('customer_id',$customer_id)->orderBy('id', 'ASC')
		->get();
		$trans_record=array(); $balance_total=0.00;
		    for($i=0;$i<count($trans_histories_list);$i++){
			$trans_id=$trans_histories_list[$i]->trans_id;
			$trans_type=$trans_histories_list[$i]->trans_type;
			if($trans_type=='order')
			{
			    $order_details = DB::select(DB::raw("SELECT orders.*,(select sum(quantity) from order_items where order_id=orders.id) as t_o_quantity,users.name as created_by FROM `orders` inner join users on users.id=orders.created_by  WHERE orders.`id`=".$trans_id."")); 
				if(count($order_details)>0){
					$data['total']=$order_details[0]->order_total;
					$balance_total=$balance_total+$order_details[0]->order_total;
					$data['balance']=$balance_total;
				}
			}
			elseif($trans_type=='cancel')
			{
				 $sql="SELECT orders.*,(select sum(quantity) from order_items where order_id=orders.id) as t_o_quantity,users.name as created_by FROM `orders` inner join users on users.id=orders.created_by  WHERE orders.`id`=".$trans_id.""; 
			    $order_details = DB::select(DB::raw($sql)); 
				if(count($order_details)>0){
					$data['total']=$order_details[0]->order_total;
					$balance_total=$balance_total-$order_details[0]->order_total;
					$data['balance']=$balance_total;
				}
			}
			elseif($trans_type=='refund')
			{
				$refund_details = DB::select(DB::raw("SELECT payment_refunds.*,users.name as created_by,orders.order_code as order_code FROM `payment_refunds` inner join users ON users.id=payment_refunds.created_by inner join customer_trans_histories ON customer_trans_histories.trans_id=payment_refunds.id INNER JOIN orders ON orders.id=payment_refunds.order_id where customer_trans_histories.trans_id=".$trans_id."")); 
				if(count($refund_details)>0){
				$data['total']=$refund_details[0]->amount;
				if($refund_details[0]->refund_status=='1')
				{
				     $balance_total=$balance_total+$refund_details[0]->amount;	
				}
				else{
					 $balance_total=$balance_total;
				}
				$data['balance']=$balance_total;
			 }
			}
			elseif($trans_type=='penalty')
			{
		
			   $sql="SELECT orders.*,customer_trans_histories.amount,(select sum(quantity) from order_items where order_id=orders.id) as t_o_quantity,users.name as created_by FROM `orders` inner join users on users.id=orders.created_by INNER JOIN customer_trans_histories ON customer_trans_histories.trans_id=orders.id  WHERE orders.`id`='".$trans_id."' and `trans_type`='penalty' "; 
				 
			    $penalty_details = DB::select(DB::raw($sql)); 
				if(count($penalty_details)>0){
					$data['total']=$penalty_details[0]->amount;
					$balance_total=$balance_total+$penalty_details[0]->amount;
					$data['balance']=$balance_total;
				}
				
			}
			else if($trans_type=='payment')
			{
				$payment_details = DB::select(DB::raw("SELECT payments.*,users.name as created_by FROM `payments` inner join users ON users.id=payments.created_by WHERE payments.`id`=".$trans_id."")); 
				if(count($payment_details)>0){
					$data['total']=$payment_details[0]->payment_amount;
					if($payment_details[0]->payment_status=='Verified')
					{
					   $balance_total=$balance_total-$payment_details[0]->payment_amount;	
					}
					else{
						$balance_total=$balance_total;
					}
					
					$data['balance']=$balance_total;
				}
			}
		}
		return $balance_total;
		
     }
	
	
	
}
