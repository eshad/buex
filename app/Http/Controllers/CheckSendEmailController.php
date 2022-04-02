<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Order;
use App\Note;
use App\CustomerTransHistories;
use App\CustomerAddress;
use App\PaymentRefunds;
use App\Country;
use App\OrderItem;
use App\Customer;
use App\Product;
use App\Payment;
use App\PaymentLine;
use App\ProductStock;
use App\Images;
use App\DispatchOrder;

use App\Dispatch;
use Auth;
use Mail;
use DB;
use PDF;

class CheckSendEmailController extends Controller
{
    public function sendeCronOrdersEmail()
    {
		 
		  /*send new order email*/
		  $order_details = DB::table('orders')
		->select('id as order_id')
		->whereNotIn('id', DB::table('send_email_checks')->select('source_id')->where('action_type','add_order'))->get();
          
          if(count($order_details)>0)
		  {
			 foreach($order_details as $order_detail)
			 {
			  $order_id=$order_detail->order_id;
			 
			  $this->getorderPdf($order_id,"add_order");
			 }
		  }
		  /*Complete order email*/
		  
		 
$dispatch_details = DB::table('orders')
		->select('id as order_id')
		->where('is_done','1')
		->whereNotIn('id', DB::table('send_email_checks')->select('source_id')->where('action_type','complete_order'))->get();
       
          if(count($dispatch_details)>0)
		  {
			 
			 foreach($dispatch_details as $dispatch_detail)
			 {
			  $order_id=$dispatch_detail->order_id;
			  $this->getorderPdf($order_id,"complete_order");
			 }
			
		  }
		  
		  
		  
		  
	}	
	// dd($order_id);
	Public function check_order_payment($id)   
	{
		//$id=19;
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
	
	public function getorderPdf($id,$email_type)
	{  
	$order = Order::select(\DB::raw('orders.*,customers.customer_full_name as customer_name, customers.customer_uniq_id as customer_code','orders.customer_address_id'
	))
			->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
			->where('orders.id',$id)
			->get();
			 // dd($order);
			 
			  $final_result[] = array(
				'total_airfreight_cost'=>$order[0]->total_airfreight_cost,
				'total_local_postage_cost'=>$order[0]->total_local_postage_cost,
			);
			$total1=$order[0]->total_airfreight_cost;
			$total2=$order[0]->total_local_postage_cost;
			$total_sum=$total1+$total2;
			// dd($total_sum);
	 $final_result[] = array(
			'customer_address_id'=>$order[0]->customer_address_id,
			// 'email'=>$customer_details->email,
			);
			$c_id =$order[0]->customer_address_id;
			// dd($c_id);
	$order_items = array();				
	$temp_order_items = OrderItem::select(\DB::raw('order_items.*,products.product_name as product_name,products.id as product_id, products.item_uniq_id as product_code'))->leftJoin('products', 'products.id', '=', 'order_items.product_id')
	->where('order_items.order_id',$id)->get();
	
	 
			 // 
	$coustmer_address= DB::table('customer_addresses')
            ->leftJoin('countries', 'countries.id', '=', 'customer_addresses.country_id')
           // ->leftJoin('orders', 'orders.customer_address_id','=','customer_addresses.id')
			->select('customer_addresses.customer_full_name', 'customer_addresses.address_1', 'customer_addresses.address_2','customer_addresses.address_3','customer_addresses.postal_code','customer_addresses.city','customer_addresses.mobile','customer_addresses.state','countries.name','countries.phonecode')
			 ->where('customer_addresses.id','=',$c_id)
			->get();
	$order_detail= DB::table('order_items')
            ->leftJoin('products', 'products.id', '=', 'order_items.product_id')
           // ->leftJoin('orders', 'orders.customer_address_id','=','customer_addresses.id')
			->select('order_items.quantity', 'order_items.total_amount','order_items.product_price','products.product_name','products.air_freight_cost','order_items.local_postage_type')
			->where('order_items.order_id',$id)
			->get();
		$sum= DB::table('order_items')
				->select(DB::raw('SUM(total_amount) as ord'))
				->get();
				
$dispatch_order = DB::table('dispatch_orders')
				->leftJoin('courier_companies', 'courier_companies.id', '=', 'dispatch_orders.courier_id')
          		->select('dispatch_orders.dispatch_date','dispatch_orders.collect_by', 'dispatch_orders.consignment_code','courier_companies.courier_name','courier_companies.courier_url')
				->where('dispatch_orders.order_id',$id)->get()->last();
				
			 
	
	foreach($temp_order_items as $order_item)
	{
	$product_image = DB::select('select * from images where source_id="'.$order_item->product_id.'" and source_type="product"');
	if(count($product_image)>0){$image = $product_image[0]->thumb_image_name;}else{$image ='no_image.jpg';}
	
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
	   'image_name1'=>$image,
	   
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
	
	$total_paid = DB::select("SELECT SUM(`amount`) as `myamount` FROM `payment_lines` as `pl` JOIN `payments` as `p`  ON `p`.`id` = `pl`.`payment_id` AND `p`.`payment_status` ='Verified'  WHERE `order_id` =$id");
	if($total_paid[0]->myamount==null){
	$total_paid=0.00; 
	}else{
	$total_paid=$total_paid[0]->myamount;
	}
	 if($email_type!='complete_order'){ 				 
	$pdf = PDF::loadView('cron_emails/order_details_pdf',compact('order','order_items','is_paid','p_amount','parsely_disable','total_paid','dispatch_order'));
	$pdf->setPaper('A4', 'landscape');
	}else{
		$pdf = PDF::loadView('cron_emails/order_details_pdf2',compact('order','order_items','is_paid','p_amount','parsely_disable','total_paid','dispatch_order'));
	$pdf->setPaper('A4', 'landscape');
	}
	 $destination_path = public_path('/pdf/cron_emails');
	 $pdf->save($destination_path.'/order_details_pdf.pdf'); 
	 $customer_details = $this->getCustomerDetails($order[0]->customer_id);
	    // dd($customer_details);
	 $final_result[] = array(
			'customer_id'=>$customer_details->customer_id,
			'email'=>$customer_details->email,
			);
			$mailer=$customer_details->email;
			$c_id=$customer_details->customer_id;
	
	  $email=$customer_details->email;
	  if($email_type!='complete_order'){ 
	  
	  Mail::send('mail.billing',compact('order','order_items','is_paid','p_amount','parsely_disable','total_paid','coustmer_address','order_detail','sum','total_sum'), function ($message) use($email,$destination_path)
	  {
		   $message->to($email)->subject('Ukshop:Order Details');
		   $message->attach($destination_path.'/order_details_pdf.pdf'); 
	  });
	  }
	  else
	  {
		$dispatch_order = DB::table('dispatch_orders')->where('order_id',$id)->first();
	if($dispatch_order->consignment_code !='NA'){
		 $courier_companies = DB::table('courier_companies')->where('id',$dispatch_order->courier_id)->first();
		 
	  $mobile = $coustmer_address[0]->phonecode . $coustmer_address[0]->mobile;
	  $sms = "UKSHOP: Order #".$order[0]->order_code." has shipped- ".$courier_companies->courier_name.",".$dispatch_order->consignment_code.", pls check your mail for more info or WhatsApp http://ukshop.biz/whatsapp";
	  
	  $this->gw_send_sms("API7TJSL2GLSL", "API7TJSL2GLSL281ZD", "UKSHOP", $mobile, $sms);
	  
	  Mail::send('mail.nreport',compact('order','order_items','is_paid','p_amount','parsely_disable','total_paid','coustmer_address','order_detail','sum','total_sum','dispatch_order','courier_companies'), function ($message) use($email,$destination_path){
		  $message->to($email)->subject('Ukshop:Order Details');
		 $message->attach($destination_path.'/order_details_pdf.pdf'); 
	    });
	}else{ 
		Mail::send('mail.nreport',compact('order','order_items','is_paid','p_amount','parsely_disable','total_paid','coustmer_address','order_detail','sum','total_sum','dispatch_order'), function ($message) use($email,$destination_path){
		  $message->to($email)->subject('Ukshop:Order Details');
		 $message->attach($destination_path.'/order_details_pdf.pdf'); 
	    });  
	}
	  }
	  //return view('mail/billing',compact('order','order_items','is_paid','p_amount','parsely_disable','total_paid','coustmer_address','order_detail','sum','total_sum'));
	 /*Mail::send('mail.nreport',compact('order','order_items','is_paid','p_amount','parsely_disable','total_paid','coustmer_address','order_detail','sum','total_sum','dispatch_order'), function ($message) use($email,$destination_path)
	 {
		  $message->to($email)->subject('Ukshop:Order Details');
		 $message->attach($destination_path.'/order_details_pdf.pdf'); 
	 });
*/	
	
	 if ($email==true)
	 {
		 DB::table('send_email_checks')->insert(
								array(
									  
									  'source_type' => "order",
									  'source_id' => $id,
									
									  'action_type' => $email_type)
							);
	
	
	 echo "Email send successfully";
	
	 }
	 else{
		 
		 echo "email send unsucessfully";
		 
	 }
		// Session::flash('title', 'Default Order'); 
		//Session::flash('success-toast-message', 'Default Order pdf send via email');	
		//return redirect('order');
	
	
	}  
	
	public function sendOrdersEmail()
    {
		  $dispatch_detail = DB::select(DB::raw("SELECT o.id as order_id
FROM orders o LEFT OUTER JOIN send_email_checks sec ON o.id = sec.source_id WHERE sec.source_id IS NULL and is_done ='1'"));

           

 
         if(count($dispatch_detail)>0)
		  {
			 
			  $order_id=$dispatch_detail[0]->order_id;
			 
			  $this->getorderPdf($order_id,"complete_order");
			
			
		  }
		    
		  
	}	

	 
	public function send_arrival_emails(){
		$new_arrive_shipments = DB::table('shipments')
		->select('shipments.id')
		->join ('order_items', 'order_items.shipment_id', '=', 'shipments.id')
		->where('shipments.status','1')
		->whereNotIn('order_items.order_id', DB::table('send_email_checks')->select('source_id')->where('action_type','arrive_shipment'))->get();
		
		foreach($new_arrive_shipments as $new_arrive_shipment){
			
				$order_datials = DB::table('order_items')
				->select(DB::raw('group_concat(id) as line_ids'),DB::raw("SUM(quantity) as quantity"),'order_id')
				->where('shipment_id',$new_arrive_shipment->id)
				->groupBy('order_id')->get();
				
				if(count($order_datials)>0){
					
					foreach($order_datials as $order_datial){
						
						$shipment = DB::table('shipment_order_item')
						->select(DB::raw("SUM(ship_quantity) as arrive_quantity"))
						->where('is_arrived',1)
						->whereIn('order_items_id',array($order_datial->line_ids))->get();
						
						if($shipment[0]->arrive_quantity==$order_datial->quantity){
							
							$check = DB::table('send_email_checks')->where('action_type','arrive_shipment')->where('source_id',$order_datial->order_id)->get();
							
							if(count($check)<1){
								$this->send_arrival_notice_email($order_datial->order_id);
								DB::table('send_email_checks')->insert(
									array(
										'source_type' => "order",
										'source_id' => $order_datial->order_id,
										'action_type' => 'arrive_shipment'
										)
								);
								}
						}
					}
				}
				
		}
		
	}
	
	function send_arrival_notice_email($order_id){
			$order = Order::select(\DB::raw('customer_addresses.customer_full_name as customer_name,customer_addresses.email as email,customer_addresses.mobile as mobile,countries.phonecode,orders.order_code,orders.order_date'))
        				->join('customer_addresses', function($join)
						{
							$join->on('customer_addresses.customer_id', '=', 'orders.customer_id');
							$join->where('customer_addresses.is_default', '=', 1);
						})
						->leftJoin('countries', 'countries.id', '=', 'customer_addresses.country_id')
	 					->where('orders.id',$order_id)
						->get();
						$email = $order[0]->email;
						
						$mobile = $order[0]->phonecode.$order[0]->mobile;
						
						$sms = "UKSHOP: Order #".$order[0]->order_code." has arrived at Malaysia warehouse & ready to post, pls check your mail for more info or WhatsApp http://ukshop.biz/whatsapp";
		$this->gw_send_sms("API7TJSL2GLSL", "API7TJSL2GLSL281ZD", "UKSHOP", $mobile, $sms);
		$customer_name = $order[0]->customer_name;
				Mail::send('mail.order_arrival', ['customer_name' => $customer_name,'email'=>$email,'order_code'=>$order[0]->order_code,'order_date'=>$order[0]->order_date], function ($message) use($email)
				{
					$message->to($email)->subject('Ukshop: Order Ready to ship!!! ');
				
				});
	}
	
	public function gw_send_sms($user,$pass,$sms_from,$sms_to,$sms_msg)  
            {           
                        $query_string = "api.aspx?apiusername=".$user."&apipassword=".$pass;
                        $query_string .= "&senderid=".rawurlencode($sms_from)."&mobileno=".rawurlencode($sms_to);
                        $query_string .= "&message=".rawurlencode(stripslashes($sms_msg)) . "&languagetype=1";        
                        $url = "http://gateway.onewaysms.com.au:10001/".$query_string;       
                        $fd = @implode ('', file ($url));      
                        if ($fd)  
                        {                       
				    if ($fd > 0) {
					Print("MT ID : " . $fd);
					$ok = "success";
				    }        
				    else {
					print("Please refer to API on Error : " . $fd);
					$ok = "fail";
				    }
                        }           
                        else      
                        {                       
                                    // no contact with gateway                      
                                    $ok = "fail";       
                        }           
                        return $ok;  
			}  
			
	function test2(){
		$email = 'sunilyadav.acs@gmail.com';
		Mail::send('mail.order_arrival', ['customer_name' => 'test','email'=>'sunilyadav.acs@gmail.com','order_code'=>'123','order_date'=>'123'], function ($message) use($email)
				{
					$message->to($email)->subject('Ukshop: Order Ready to ship!!! ');
				
				});
	}
}
