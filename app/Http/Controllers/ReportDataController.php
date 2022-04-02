<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\User_commission;
use App\User;
use App\Customer;
use App\Dispatch;
use App\Order;
use App\OrderItem;
use App\PaymentRefunds;
use App\Product;
use App\Payment;
use App\PaymentLine;
use App\ProductStock;
use App\Images;
use App\DispatchOrder;
use App\Commission;
use App\UsersCommission;
use App\Account;
use PDF;
use DB;
use Auth;
use Mail;

class ReportDataController extends Controller
{
    public function user_sales_details($uid)
    {
        $uid = decrypt($uid);  
	     $sql='select `users_commissions`.`id`, `orders`.`id` as order_id,`users_commissions`.`created_at`, `users_commissions`.`quantity`, `users_commissions`.`commission_rate`, `users_commissions`.`unit_price`, `users_commissions`.`total_commission`, `products`.`product_name`, `customers`.`customer_full_name`,`orders`.`order_code`,`users_commissions`.`commission_type`,`users`.`name` as username from `users_commissions` inner join `products` on `users_commissions`.`product_id` = `products`.`id` inner join `customers` on `users_commissions`.`customer_id` = `customers`.`id` INNER JOIN orders ON orders.id=users_commissions.order_id  LEFT JOIN users ON users.id = orders.created_by  where  users_commissions.user_account_id="'.$uid.'" order by users_commissions.created_at desc';   
         $user_commission = DB::select(DB::raw($sql)); 
		 
	     return view('report/user_sales_details', compact('user_commission'));
    }
   
    public function user_list_by_role()
    {
		 $user = User::whereHas('roles', function ($query) {
			$query->where('name', '=', 'Sales-Agent');
		 })->get();
		 return view('report/userdata', compact('user'));
         //return view('account/user_list', compact('user'));
      
    }
	
	function dispatch_manager_report()
	{
		 $user = User::whereHas('roles', function ($query) {
			$query->where('name', '=', 'Dispatch-Manager');
		 })->get();
		 
		 $user_id=array();
		 foreach($user as $item){
			$user_id[]=  $item->id ;
		 }
		 $user_id=implode(',',$user_id);
		 $sql="SELECT accounts.* ,users.name FROM `dispatch_orders` inner join users ON users.id=dispatch_orders.user_aacount_id join accounts ON accounts.id=dispatch_orders.accounts_id where dispatch_orders.accounts_id !=0 ";   
         $account = DB::select(DB::raw($sql)); 
		// dd($sql );
		 return view('report/dispatch_manager_report',['account'=>$account]);
	}
	
	
	public function all_customer_list()
    {
		$order_detail=array();
		$customer_list = Customer::select('customers.id')
		->get();
		 foreach($customer_list as $customers){
		   $customer_list = DB::select(DB::raw("SELECT customer_full_name,customers.id as customers_id ,count(DISTINCT order_id) as order_total ,sum(quantity) as order_quantity,((SELECT sum( order_total) FROM orders where customer_id=o.customer_id ) ) as total,(SELECT sum(`amount`) FROM `customer_credits`where customer_id=o.customer_id ) as t_credits_amount,(SELECT sum(`payment_amount`) FROM `payments` where payment_customer=o.customer_id and payment_status='Verified' ) as t_payment_amount  FROM `order_items` oi inner join orders o on o.id= oi.order_id inner join customers on customers.id=o.customer_id WHERE customer_id='".$customers->id."'")); 
		  
		  $order_detail[]=$customer_list[0] ;
		}
		return view('report/customer_balance',['customer_list'=>$order_detail]);
    }
	
	/*public function getUsertransHistory($userid)
	{
	   $customer_credit = DB::select(DB::raw("select * from customer_trans_histories where id = (select max(`id`) from customer_trans_histories WHERE `customer_id`='".$userid."')")); 
	   return $customer_credit[0];	
	}*/
	public function getDispatchRecord($userid='')
    {
		    if($userid!=''){
		    $uid = decrypt($userid);  
            $dispatch= DB::table('dispatch_orders')
            ->leftjoin('customer_addresses', 'dispatch_orders.customer_address_id', '=', 'customer_addresses.id')
            ->leftjoin('courier_companies', 'dispatch_orders.courier_id', '=', 'courier_companies.id')
			->leftjoin('users', 'dispatch_orders.created_by', '=', 'users.id')
			  ->leftjoin('orders', 'dispatch_orders.order_id', '=', 'orders.id')
			->select('dispatch_orders.id','dispatch_orders.order_id', 'dispatch_orders.dispatch_date', 'dispatch_orders.collect_by','customer_addresses.customer_full_name','courier_companies.courier_name','courier_companies.courier_url','users.name','orders.total_item','orders.order_code','dispatch_orders.consignment_code')
			 ->where('user_aacount_id',$uid)
			
				
            ->get();
			}
			else
			{
				$dispatch= DB::table('dispatch_orders')
				->leftjoin('customer_addresses', 'dispatch_orders.customer_address_id', '=', 'customer_addresses.id')
				->leftjoin('courier_companies', 'dispatch_orders.courier_id', '=', 'courier_companies.id')
				->leftjoin('users', 'dispatch_orders.created_by', '=', 'users.id')
				->leftjoin('orders', 'dispatch_orders.order_id', '=', 'orders.id')
				->select('dispatch_orders.id','dispatch_orders.order_id', 'dispatch_orders.dispatch_date', 'dispatch_orders.collect_by','customer_addresses.customer_full_name','courier_companies.courier_name','courier_companies.courier_url','users.name','orders.total_item','orders.order_code','dispatch_orders.consignment_code')
				
				->get();	
			}
			return view('report/dispatch_show',compact('dispatch'));
    }
	
	/*get Dispatch Pdf */
	public function getDispatchPdf($id,$type)
    {
	   $order = Order::select(\DB::raw('orders.*,customers.customer_full_name as customer_name, customers.customer_uniq_id as customer_code'
	   ))
        				->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
	 					->where('orders.id',$id)
     					->get();
		$order_items = array();				
	   	$temp_order_items = OrderItem::select(\DB::raw('order_items.*,products.product_name as product_name,products.id as product_id, products.item_uniq_id as product_code'))->leftJoin('products', 'products.id', '=', 'order_items.product_id')
	  ->where('order_items.order_id',$id)->get();
	  
	    foreach($temp_order_items as $order_item)
		{
			$product_image = DB::select('select * from images where source_id="'.$order_item->product_id.'" and source_type="product"');
			$order_items[] = array(
				   'order_item_id'=>$order_item->id,
			       'product_code'=>$order_item->product_code, 
				   'product_id'=>$order_item->product_id,
				   'product_name'=>$order_item->product_name,
				   'quantity'=>$order_item->quantity,
				   'ship_quantity'=>$order_item->ship_quantity,
				   'product_price'=>$order_item->product_price,
				   'total_amount'=>$order_item->total_amount,
				   'dispatch_ready'=>$order_item->dispatch_ready,
				   'shipment_id'=>$order_item->shipment_id,
				   's_from'=>$order_item->s_from,
				   'image_name1'=>$product_image[0]->thumb_image_name,
				   
			);
		}
		/*check order payment staus*/
		$payment_check=$this->check_order_payment($id);
		
		if(($payment_check->p_count)>0){
		   $is_paid=1;
		 $p_amount=number_format($payment_check->p_amount,2);
		    $p_amount=number_format($payment_check->p_amount, 2, '.', '');
		   
		}
		else{
		   $is_paid='0';
		   $p_amount='0.00';
		}
		
		$parsely_disable ='1';
		$total_paid = DB::select("SELECT SUM(`amount`) as `myamount` FROM `payment_lines` as `pl` JOIN `payments` as `p`  ON `p`.`id` = `pl`.`payment_id` AND `p`.`payment_status` ='Verified'  WHERE `order_id` =$id");
		if($total_paid[0]->myamount==null){
		  $total_paid=0.00; 
		}else{
		   $total_paid=$total_paid[0]->myamount;
		}
											 

//$pdf = PDF::loadView('report/hello');
              $pdf = PDF::loadView('report/dispatch_order_details_pdf',compact('order','order_items','is_paid','p_amount','parsely_disable','total_paid'));
              $pdf->setPaper('A4', 'landscape');
			  if($type=='sendemail'){
				 $destination_path = public_path('/pdf/dispatch_order_details');
				 $pdf->save($destination_path.'/dispatch_order_details_pdf.pdf'); 
				 $customer_details = $this->getCustomerDetails($order[0]->customer_id);
		       	 $email='dineshsuryawanshi11@gmail.com';
				 Mail::send('mail.refund_history', [], function ($message) use($email,$destination_path)
				{
				 $message->to($email)->subject('Ukshop: Order Details');
				 $message->attach($destination_path.'/dispatch_order_details_pdf.pdf');
				}); 
				
				
				Session::flash('title', 'dispatch History'); 
				Session::flash('success-toast-message', 'Refund history pdf send via email');	
				return redirect('report/dispatch_manager_report');
			  }
			  else{
				   return $pdf->download('dispatch_order_details.pdf'); 
			  }
    }
	
	Public function check_order_payment($id)   
	{
		$order_details = DB::select(DB::raw("SELECT count(id) as p_count, sum(amount) as p_amount FROM `payment_lines` WHERE `order_id` =".$id."")); 
	    return $order_details[0];	
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
	
   
}