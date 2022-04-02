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
use App\Commission;
use App\UsersCommission;
use App\CustomerCredit;
use App\Account;
use App\Dispatch;
use Auth;
use Mail;
use DB;
use PDF;

class OrderController extends Controller
{
    public function index()
    {
		$user = Auth::user();
		if($user->hasRole('Super-Admin|Dispatch-Manager')){
			$general_orders = Order::select(\DB::raw('orders.*,users.name as created_name,order_items.s_from,customers.customer_full_name as customer_name, SUM(order_items.ship_quantity) as total_ship_quantity, SUM(order_items.pending_quantity) as total_pending_quantity'))
         ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id')
		 ->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
		 ->leftJoin('users', 'users.id', '=', 'orders.created_by')
		 ->where('order_tab',1)
		 ->where('is_cancel',0)
		 ->where('is_done',0)
		 ->where('is_partial_done',0)
         ->groupBy('order_items.order_id')
		 ->orderBy('orders.id','DESC')
         ->get();
		 
		 $default_orders = Order::select(\DB::raw('orders.*,users.name as created_name,order_items.s_from,customers.customer_full_name as customer_name, SUM(order_items.ship_quantity) as total_ship_quantity'))
         ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id')
		 ->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
		 ->leftJoin('users', 'users.id', '=', 'orders.created_by')
		 ->where('order_tab',2)
		 ->where('is_cancel',0)
		 ->where('is_done',0)
		 ->where('is_partial_done',0)
         ->groupBy('order_items.order_id')
		 ->orderBy('orders.id','DESC')
         ->get();
		 
		 $action_orders = Order::select(\DB::raw('orders.*,users.name as created_name,order_items.s_from,customers.customer_full_name as customer_name, SUM(order_items.ship_quantity) as total_ship_quantity'))
         ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id')
		 ->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
		 ->leftJoin('users', 'users.id', '=', 'orders.created_by')
		 ->where('order_tab',3)
		 ->where('is_cancel',0)
		 ->where('is_done',0)
		 ->where('is_partial_done',0)
         ->groupBy('order_items.order_id')
		 ->orderBy('orders.id','DESC')
         ->get();
		 
		 
		 $cancel_orders = Order::select(\DB::raw('orders.*,users.name as created_name,order_items.s_from,customers.customer_full_name as customer_name, SUM(order_items.ship_quantity) as total_ship_quantity'))
         ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id')
		 ->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
		 ->leftJoin('users', 'users.id', '=', 'orders.created_by')
		 ->where('is_cancel',1)
		 ->where('is_done',0)
         ->groupBy('order_items.order_id')
		 ->orderBy('orders.id','DESC')
         ->get();
		 
		 
		}else{
			
			$general_orders = Order::select(\DB::raw('orders.*,users.name as created_name,order_items.s_from,customers.customer_full_name as customer_name, SUM(order_items.ship_quantity) as total_ship_quantity'))
			 ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id')
			 ->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
			 ->leftJoin('users', 'users.id', '=', 'orders.created_by')
			 ->where('order_tab',1)
			 ->where('is_cancel',0)
			 ->where('is_done',0)
			 ->where('is_partial_done',0)
			 ->where('orders.user_account_id',$user->id)
			 ->groupBy('order_items.order_id')
			 ->get();
			 
			 $default_orders = Order::select(\DB::raw('orders.*,users.name as created_name,order_items.s_from,customers.customer_full_name as customer_name, SUM(order_items.ship_quantity) as total_ship_quantity'))
			 ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id')
			 ->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
			 ->leftJoin('users', 'users.id', '=', 'orders.created_by')
			 ->where('order_tab',2)
			 ->where('is_cancel',0)
			 ->where('is_done',0)
			 ->where('is_partial_done',0)
			 ->where('orders.user_account_id',$user->id)
			 ->groupBy('order_items.order_id')
			 ->get();
			 
			 $action_orders = Order::select(\DB::raw('orders.*,users.name as created_name,order_items.s_from,customers.customer_full_name as customer_name, SUM(order_items.ship_quantity) as total_ship_quantity'))
         ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id')
		 ->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
		 ->leftJoin('users', 'users.id', '=', 'orders.created_by')
		 ->where('order_tab',3)
		 ->where('is_cancel',0)
		 ->where('is_done',0)
		 ->where('is_partial_done',0)
		 ->where('orders.user_account_id',$user->id)
         ->groupBy('order_items.order_id')
		 ->orderBy('orders.id','DESC')
         ->get();
		 
		 
		 $cancel_orders = Order::select(\DB::raw('orders.*,users.name as created_name,order_items.s_from,customers.customer_full_name as customer_name, SUM(order_items.ship_quantity) as total_ship_quantity'))
         ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id')
		 ->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
		 ->leftJoin('users', 'users.id', '=', 'orders.created_by')
		 ->where('is_cancel',1)
		 ->where('is_done',0)
		 ->where('orders.user_account_id',$user->id)
         ->groupBy('order_items.order_id')
		 ->orderBy('orders.id','DESC')
         ->get();
		}
		//dd($generalorders);
		return view('order/order_list',compact('general_orders','default_orders','action_orders','cancel_orders'));
    }
	
	public function completed_order(){
		$user = Auth::user();
		if($user->hasRole('Super-Admin|Dispatch-Manager')){
			$general_orders = Order::select(\DB::raw('orders.*,users.name as created_name,order_items.s_from,customers.customer_full_name as customer_name, SUM(order_items.ship_quantity) as total_ship_quantity'))
         ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id')
		 ->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
		 ->leftJoin('users', 'users.id', '=', 'orders.created_by')
		 
		 ->where('is_cancel',0)
		 ->where('is_done',1)
         ->groupBy('order_items.order_id')
		 ->orderBy('orders.id','DESC')
         ->get();
		}else{
			$general_orders = Order::select(\DB::raw('orders.*,users.name as created_name,order_items.s_from,customers.customer_full_name as customer_name, SUM(order_items.ship_quantity) as total_ship_quantity'))
			 ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id')
			 ->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
			 ->leftJoin('users', 'users.id', '=', 'orders.created_by')
			 
			 ->where('is_cancel',0)
			 ->where('is_done',1)
			 ->where('orders.user_account_id',$user->id)
			 ->groupBy('order_items.order_id')
			 ->get();
		}
		return view('order/completed_order',compact('general_orders'));
	}
	
	public function partial_completed_order(){
		$user = Auth::user();
		if($user->hasRole('Super-Admin|Dispatch-Manager')){
			$general_orders = Order::select(\DB::raw('orders.*,users.name as created_name,order_items.s_from,customers.customer_full_name as customer_name, SUM(order_items.ship_quantity) as total_ship_quantity, SUM(order_items.pending_quantity) as total_pending_quantity'))
         ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id')
		 ->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
		 ->leftJoin('users', 'users.id', '=', 'orders.created_by')
		 
		 ->where('is_cancel',0)
		 ->where('is_done',0)
		 ->where('is_partial_done',1)
         ->groupBy('order_items.order_id')
		 ->orderBy('orders.id','DESC')
         ->get();
		// dd($general_orders);
		}else{
			$general_orders = Order::select(\DB::raw('orders.*,users.name as created_name,order_items.s_from,customers.customer_full_name as customer_name, SUM(order_items.ship_quantity) as total_ship_quantity, SUM(order_items.pending_quantity) as total_pending_quantity'))
			 ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id')
			 ->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
			 ->leftJoin('users', 'users.id', '=', 'orders.created_by')
			 
			 ->where('is_cancel',0)
			 ->where('is_done',2)
			 ->where('orders.user_account_id',$user->id)
			 ->groupBy('order_items.order_id')
			 ->get();
		}
		return view('order/partial_complated',compact('general_orders'));
	}
    public function create($customer_id='')
    {
		if($customer_id!=''){
			$customer_id = decrypt($customer_id); 
		}
		$Order_last = Order::latest()->first();
		$customers = Customer::all();
		$products = Product::all();
		if($Order_last){
			 $Order_code = 'ODID-'.(1001+$Order_last->id);
		}else{
			 $Order_code = 'ODID-1001';
		}
		$parsely_disable ='1';
		$Countries = Country::all();
		return view('order/add_order',compact('customers','Order_code','products','parsely_disable','Countries','customer_id'));
    }
    
    public function store(Request $request)
    {

		$request->validate([
			'customer_id' 			=>  'required',
			'location_id' 			=>  'required',
			'shipping_type' 		=>  'required',
			'order_date' 			=>  'required',
			'estimate_date' 		=>  'required',
			'total_item' 			=>  'required',
			'local_pos' 			=>  'required',
			'total_airfreight' 		=>  'required',
			'total_local_postage' 	=>  'required',
			'final_total' 			=>  'required',
		 ]);
		$Order_last = Order::latest()->first();
		
		if($Order_last){
			 $Order_code = 'ODID-'.(1001+$Order_last->id);
		}else{
			 $Order_code = 'ODID-1001';
		}
		$user = Auth::user();
	
		if(Session::get('admin_id')){
			$created_by = Session::get('admin_id');
		}else{
			$created_by = $user->id;
		}
		$customer_address_id = CustomerAddress::select('id')->where('customer_id',$request->customer_id)->where('is_default','1')->get();
		$order = Order::create([
				'order_code' => $Order_code,
				'customer_id' => $request->customer_id,
				'customer_address_id'	=> $customer_address_id[0]->id,
				'shipping_location_id' => $request->location_id,
				'shipping_type_id' => $request->shipping_type,
				'order_date' => date('Y-m-d', strtotime($request->order_date)),
				'est_delivery_date' => date('Y-m-d', strtotime($request->estimate_date)),
				'total_item' => $request->total_item,
				'manage_local_postage_cost' => $request->local_pos,
				'total_airfreight_cost' => $request->total_airfreight,
				'total_local_postage_cost' => $request->total_local_postage,
				'order_total' => $request->final_total,
				'note' =>$request->note,
				'created_by' => $created_by,
				'user_account_id' => $user->id,
 			]);
		if($request->note){
			Note::create([
				'source_type'	=>	'order',
				'source_id'		=>	$order->id,
				'source_comment'	=>	$request->note,
				'created_by' => $created_by,
				'user_account_id' => $user->id,
			]);
		}
		DB::table('customer_trans_histories')->insert(['customer_id'=>$request->customer_id,'trans_type'=>'order','trans_id'=>$order->id,'amount'=>$request->final_total,'balance'=>'0.00','created_by' => $created_by,'user_account_id' => $user->id]);
		Customer::where('id',$request->customer_id)->increment('balance_amount',$request->final_total);	
		for($i=0;$i<count($request->item_id);$i++){
			$ship_quantity = 0;
			if($request->show_s_from[$i]=='uk_stock'){
				$dispatch_ready = 0;
				$shipment_id = 0;
				ProductStock::create([
					'product_id'	=>$request->item_id[$i],
					'location_id'	=>1,
					'quantity'		=>'-'.$request->show_quantity[$i],
					'created_by' => $created_by,
					'user_account_id' => $user->id,
				]);
				Product::where('id',$request->item_id[$i])->decrement('uk_stock', $request->show_quantity[$i]);
						
			}else if($request->show_s_from[$i]=='my_stock' ){
				$dispatch_ready = 1;
				$shipment_id = 0;
				ProductStock::create([
					'product_id'	=>$request->item_id[$i],
					'location_id'	=>2,
					'quantity'		=>'-'.$request->show_quantity[$i],
					'created_by' => $created_by,
					'user_account_id' => $user->id,
				]);
				Product::where('id',$request->item_id[$i])->update([
				   'malaysia_stock' => DB::raw('malaysia_stock - '.$request->show_quantity[$i]),
				   'malaysia_sold_stock' => DB::raw('malaysia_sold_stock + '.$request->show_quantity[$i]),
   ]);
			}else{
				$dispatch_ready = 2;
				$shipment_id = $request->show_s_from[$i];
				$ship_quantity = $request->show_quantity[$i];
			}
			
			$order_item = OrderItem::create([
				'order_id' => $order->id,
				'product_id' => $request->item_id[$i],
				'payment_plan_id' => $request->payment_plan[$i],
				'quantity' => $request->show_quantity[$i],
				'ship_quantity' => $ship_quantity,
				'local_postage_type' => $request->local_postage[$i],
				'product_price' => $request->show_product_price[$i],
				's_from' => $request->show_s_from[$i],
				'dispatch_ready' => $dispatch_ready,
				'shipment_id'  => $shipment_id,
				'total_amount' => $request->show_product_amount[$i],
			]);
			if($dispatch_ready == 2){
				DB::table('shipment_order_item')->insert(
								array('order_items_id' => $order_item->id,
									  'shipment_id' => $shipment_id,
									  'item_id'=>$request->item_id[$i],
									  'ship_quantity' => $request->show_quantity[$i])
								);	
			}
			if($user->hasRole('Sales-Agent')){
				
				
					$com=Commission::select('unit_commission')->where('low_unit_price', '<=' ,$request->show_product_price[$i])->where('high_unit_price','>=',$request->show_product_price[$i])->get();
					
				if(count($com)<1){
					$total_comission =0;
					$unit_commission=0;
				}else{
					$unit_commission = $com[0]->unit_commission;
					$total_comission = $com[0]->unit_commission * $request->show_quantity[$i];
				}
				
				UsersCommission::create([
					'product_id'=>$request->item_id[$i],
					'order_id' => $order->id,
					'customer_id'=>$request->customer_id,
					'quantity'=>$request->show_quantity[$i],
					'total_commission'=>$total_comission,
					'commission_rate'=>$unit_commission,
					'unit_price'=>$request->show_product_price[$i],
					'created_by' => $created_by,
					'user_account_id' => $user->id,
				]);
			
			}
			/*$destination_path = public_path('/pdf/dispatch_order_details');		
			$email='dineshsuryawanshi11@gmail.com';
    Mail::send('mail.refund_history', [], function ($message) use($email,$destination_path)
    {
     $message->to($email)->subject('Ukshop: Order Details');
     $message->attach($destination_path.'/dispatch_order_details_pdf.pdf');
    });*/
		}
		Session::flash('title', 'Order added success'); 
		Session::flash('success-toast-message', 'Your new Order added successfully ');	
		return redirect('order');
	}

    public function show()
    {
	
    }
	
    public function edit($id)
    {
       $id = decrypt($id);
	   if(Order::find($id)){
		   $order = Order::select(\DB::raw('orders.*,customers.customer_full_name as customer_name, customers.customer_uniq_id as customer_code'))
							->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
							->where('orders.id',$id)
							->get();
							
			$order_items = array();				
			$temp_order_items = OrderItem::select(\DB::raw('order_items.*,products.product_name as product_name,products.id as product_id, products.item_uniq_id as product_code'))->leftJoin('products', 'products.id', '=', 'order_items.product_id')
		  ->where('order_items.order_id',$id)->get();
		  
		  
		 foreach($temp_order_items as $order_item)
			{
				$product_image = DB::select('select * from images where source_id="'.$order_item->product_id.'" and source_type="product"');
				if($product_image){
					$p_image = $product_image[0]->thumb_image_name;
				}else{
					$p_image = 'no_image.jpg';
				}
				$order_items[] = array(
					   'order_item_id'=>$order_item->id,
					   'product_code'=>$order_item->product_code, 
					   'product_id'=>$order_item->product_id,
					   'product_name'=>$order_item->product_name,
					   'quantity'=>$order_item->quantity,
					   'ship_quantity'=>$order_item->ship_quantity,
					   'pending_quantity'=>$order_item->pending_quantity,
					   'dispatch_quantity'=>$order_item->dispatch_quantity,
					   'product_price'=>$order_item->product_price,
					   'total_amount'=>$order_item->total_amount,
					   'dispatch_ready'=>$order_item->dispatch_ready,
					   'shipment_id'=>$order_item->shipment_id,
					   's_from'=>$order_item->s_from,
					   'shipping_location_id'=>$order[0]->shipping_location_id,
					   /*'sm_cost'=>$product->sm_cost,
					   'ss_cost'=>$product->ss_cost,
					   'air_freight_cost'=>$product->air_freight_cost,
					   'initial_stock'=>$product->initial_stock,
					   'uk_stock'=>$product->uk_stock,
					   'malaysia_stock'=>$product->malaysia_stock,*/
					   'image_name1'=>$p_image,
					   
				);
			}
			/*check order payment staus*/
			$payment_check=$this->check_order_payment($id);
			if(($payment_check->p_count)>0){
			   $is_paid=1;
			  // $p_amount=number_format($payment_check->p_amount,2);
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
			return view('order/edit_order',compact('order','order_items','is_paid','p_amount','parsely_disable','total_paid'));
	   }else{
		   
		   return redirect('order');
	   }
    }
	
	public function cancel_edit($id)
    {
       $id = decrypt($id);
	   if(Order::find($id)){
		   $order = Order::select(\DB::raw('orders.*,customers.customer_full_name as customer_name, customers.customer_uniq_id as customer_code'))
							->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
							->where('orders.id',$id)
							->get();
							
			$order_items = array();				
			$temp_order_items = OrderItem::select(\DB::raw('order_items.*,products.product_name as product_name,products.id as product_id, products.item_uniq_id as product_code'))->leftJoin('products', 'products.id', '=', 'order_items.product_id')
		  ->where('order_items.order_id',$id)->get();
		  
		  
		 foreach($temp_order_items as $order_item)
			{
				$product_image = DB::select('select * from images where source_id="'.$order_item->product_id.'" and source_type="product"');
				if($product_image){
					$p_image = $product_image[0]->thumb_image_name;
				}else{
					$p_image = 'no_image.jpg';
				}
				$order_items[] = array(
					   'order_item_id'=>$order_item->id,
					   'product_code'=>$order_item->product_code, 
					   'product_id'=>$order_item->product_id,
					   'product_name'=>$order_item->product_name,
					   'quantity'=>$order_item->quantity,
					   'ship_quantity'=>$order_item->ship_quantity,
					   'dispatch_quantity'=>$order_item->dispatch_quantity,
					   'product_price'=>$order_item->product_price,
					   'total_amount'=>$order_item->total_amount,
					   'dispatch_ready'=>$order_item->dispatch_ready,
					   'shipment_id'=>$order_item->shipment_id,
					   's_from'=>$order_item->s_from,
					   'shipping_location_id'=>$order[0]->shipping_location_id,
					   /*'sm_cost'=>$product->sm_cost,
					   'ss_cost'=>$product->ss_cost,
					   'air_freight_cost'=>$product->air_freight_cost,
					   'initial_stock'=>$product->initial_stock,
					   'uk_stock'=>$product->uk_stock,
					   'malaysia_stock'=>$product->malaysia_stock,*/
					   'image_name1'=>$p_image,
					   
				);
			}
			/*check order payment staus*/
			$payment_check = $this->check_order_payment($id);
			if(($payment_check->p_count)>0){
			   $is_paid=1;
			  // $p_amount=number_format($payment_check->p_amount,2);
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
			return view('order/edit_cancel_order',compact('order','order_items','is_paid','p_amount','parsely_disable','total_paid'));
	   }else{
		   
		   return redirect('order');
	   }
    }
	
	public function edit_partial_completed($order_id)
    {
       $id = decrypt($order_id);
	   if(Order::find($id)){
		   $order = Order::select(\DB::raw('orders.*,customers.customer_full_name as customer_name, customers.customer_uniq_id as customer_code'))
							->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
							->where('orders.id',$id)
							->get();
							
			$order_items = array();				
			$temp_order_items = OrderItem::select(\DB::raw('order_items.*,products.product_name as product_name,products.id as product_id, products.item_uniq_id as product_code'))->leftJoin('products', 'products.id', '=', 'order_items.product_id')
		  ->where('order_items.order_id',$id)->get();
		  
		  
		 foreach($temp_order_items as $order_item)
			{
				$product_image = DB::select('select * from images where source_id="'.$order_item->product_id.'" and source_type="product"');
				if($product_image){
					$p_image = $product_image[0]->thumb_image_name;
				}else{
					$p_image = 'no_image.jpg';
				}
				$order_items[] = array(
					   'order_item_id'=>$order_item->id,
					   'product_code'=>$order_item->product_code, 
					   'product_id'=>$order_item->product_id,
					   'product_name'=>$order_item->product_name,
					   'quantity'=>$order_item->quantity,
					   'ship_quantity'=>$order_item->ship_quantity,
					    'pending_quantity'=>$order_item->pending_quantity,
					   'dispatch_quantity'=>$order_item->dispatch_quantity,
					   'product_price'=>$order_item->product_price,
					   'total_amount'=>$order_item->total_amount,
					   'dispatch_ready'=>$order_item->dispatch_ready,
					   'shipment_id'=>$order_item->shipment_id,
					   's_from'=>$order_item->s_from,
					   'shipping_location_id'=>$order[0]->shipping_location_id,
					   /*'sm_cost'=>$product->sm_cost,
					   'ss_cost'=>$product->ss_cost,
					   'air_freight_cost'=>$product->air_freight_cost,
					   'initial_stock'=>$product->initial_stock,
					   'uk_stock'=>$product->uk_stock,
					   'malaysia_stock'=>$product->malaysia_stock,*/
					   'image_name1'=>$p_image,
					   
				);
			}
			/*check order payment staus*/
			$payment_check=$this->check_order_payment($id);
			if(($payment_check->p_count)>0){
			   $is_paid=1;
			  // $p_amount=number_format($payment_check->p_amount,2);
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
			return view('order/edit_partial_completed',compact('order','order_items','is_paid','p_amount','parsely_disable','total_paid'));
	   }else{
		   
		   return redirect('order');
	   }
    }
	public function view_completed_order($order_id)
    {
       $id = decrypt($order_id);
	   if(Order::find($id)){
		   $order = Order::select(\DB::raw('orders.*,customers.customer_full_name as customer_name, customers.customer_uniq_id as customer_code'))
							->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
							->where('orders.id',$id)
							->get();
							
			$order_items = array();				
			$temp_order_items = OrderItem::select(\DB::raw('order_items.*,products.product_name as product_name,products.id as product_id, products.item_uniq_id as product_code'))->leftJoin('products', 'products.id', '=', 'order_items.product_id')
		  ->where('order_items.order_id',$id)->get();
		  
		  
		 foreach($temp_order_items as $order_item)
			{
				$product_image = DB::select('select * from images where source_id="'.$order_item->product_id.'" and source_type="product"');
				if($product_image){
					$p_image = $product_image[0]->thumb_image_name;
				}else{
					$p_image = 'no_image.jpg';
				}
				$order_items[] = array(
					   'order_item_id'=>$order_item->id,
					   'product_code'=>$order_item->product_code, 
					   'product_id'=>$order_item->product_id,
					   'product_name'=>$order_item->product_name,
					   'quantity'=>$order_item->quantity,
					   'ship_quantity'=>$order_item->ship_quantity,
					   'dispatch_quantity'=>$order_item->dispatch_quantity,
					   'product_price'=>$order_item->product_price,
					   'total_amount'=>$order_item->total_amount,
					   'dispatch_ready'=>$order_item->dispatch_ready,
					   'shipment_id'=>$order_item->shipment_id,
					   's_from'=>$order_item->s_from,
					   'shipping_location_id'=>$order[0]->shipping_location_id,
					   /*'sm_cost'=>$product->sm_cost,
					   'ss_cost'=>$product->ss_cost,
					   'air_freight_cost'=>$product->air_freight_cost,
					   'initial_stock'=>$product->initial_stock,
					   'uk_stock'=>$product->uk_stock,
					   'malaysia_stock'=>$product->malaysia_stock,*/
					   'image_name1'=>$p_image,
					   
				);
			}
			/*check order payment staus*/
			$payment_check=$this->check_order_payment($id);
			if(($payment_check->p_count)>0){
			   $is_paid=1;
			  // $p_amount=number_format($payment_check->p_amount,2);
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
			return view('order/view_completed_order',compact('order','order_items','is_paid','p_amount','parsely_disable','total_paid'));
	   }else{
		   
		   return redirect('order');
	   }
    }
	
    public function update(Request $request, $id)
    {	
		$user = Auth::user();
      	if(Session::get('admin_id')){
			$updated_by = Session::get('admin_id');
		}else{
			$updated_by = $user->id;
		}
	   $id = decrypt($id);
	   $order = Order::find($id);
	   $order->total_item = $request->total_item_quantity;
	   $order->total_local_postage_cost = $request->total_local_postage_cost_input;
	   $order->order_total = $request->total_final_total;
	   $order->customer_address_id = $request->selected_customer_address;
	   $order->order_status = $request->order_status;
	   $order->partial_ship = $request->partial_ship;
	   $order->updated_by = $updated_by;
	   $order->save();
	   $user_account_id = $order->user_account_id;
	  
	   if ($request->has('delete_order_item')) {
		   for($i=0;$i<count($request->delete_order_item);$i++){
			  $orderitem = OrderItem::find($request->delete_order_item);
			  if($orderitem[0]->dispatch_ready==0){
				  Product::where('id',$orderitem[0]->product_id)->increment('uk_stock',$orderitem[0]->quantity);;
				  ProductStock::create(['product_id'=>$orderitem[0]->product_id,'location_id'=>'1','quantity'=>$orderitem[0]->quantity,'created_by'=>$updated_by,'user_account_id'=>$user->id]);
			  }
			  OrderItem::destroy($request->delete_order_item[$i]);
		   }
	   }
	   
	   for($j=0;$j<count($request->order_item_id);$j++){
		   $order_item = OrderItem::find($request->order_item_id[$j]);
		   $order_item->ship_quantity = $request->update_ship_quantity[$j];
		   $order_item->product_price = $request->update_unit_price[$j];
		   $order_item->total_amount = $request->item_total_amount[$j];
		   $order_item->save();
		  
		   $order_user = \App\User::find($user_account_id);
	   
		   if($order_user->hasRole('Sales-Agent')){
			   
		  		 $com=Commission::select('unit_commission')->where('low_unit_price', '<=' ,$request->update_unit_price[$j])->where('high_unit_price','>=',$request->update_unit_price[$j])->get();
			if(count($com)>0){
				$total_comission = $com[0]->unit_commission * $request->update_ship_quantity[$j];
	
				UsersCommission::where('product_id',$order_item->product_id)->where('order_id' , $order->id)->update([
				'total_commission'	=>	$total_comission,
				'commission_rate'	=>	$com[0]->unit_commission,
				'unit_price'		=>	$request->update_unit_price[$j],
				'updated_by' 		=> 	$updated_by,
			]);
			}
		   }
			
	   }
	   
	   return redirect('order');
		
    }
	
    public function destroy()
    {
      
    }
	
	public function ajax_get_item_details_on_order_page(Request $request){
		
		$item_id = $request->item_id;
		$stock = Product::select('uk_stock','malaysia_stock','sm_cost','ss_cost','air_freight_cost','product_price','installment_cost')->where('id',$item_id)->get();
		$product_image = Images::select('image_name','thumb_image_name')->where(['source_type'=>'product','source_id'=>$item_id])->latest()->first();
		if($product_image){
			$p_image = $product_image->thumb_image_name;
		}else{
			$p_image = 'no_image.jpg';
		}
		$shipment=DB::table('shipment_line as sl')->select('s.shipment_number','s.id','sl.item_id','s.id','s.shipment_date','s.bl_awb_number','s.carrier_details','s.created_by','sl.shipment_quantity')->join('shipments as s', function($join)
						{
							$join->on('s.id', '=', 'sl.shipment_id');
							$join->where('s.status', '=', 0);
						})->where('sl.item_id', '=' ,$item_id)->get();
						
		$shipmentDetails=array();
		$shipmentfinal=array();
		if(count($shipment)>0){				
		
		foreach($shipment as $shipment1 )
		{
			
		  $shipmentDetails['shipment_number']=$shipment1->shipment_number;
		  $shipmentDetails['id']=$shipment1->id;
		  $shipmentDetails['shipment_date']=$shipment1->shipment_date;
		  $shipmentDetails['bl_awb_number']=$shipment1->bl_awb_number;
		  $shipmentDetails['carrier_details']=$shipment1->carrier_details;
		  $shipmentDetails['created_by']=$shipment1->created_by;
		  $date1=date_create($shipment1->shipment_date);
		  $date2 = date_create(date('Y-m-d'));
		  $diff2=date_diff($date1,$date2);
		  $shipmentDetails['remaining'] = $diff2->format("%a");
		  $r_quantity = DB::select("SELECT if(sum(`order_items`.`ship_quantity`) IS NULL ,'0',sum(`order_items`.`ship_quantity`)) as remain_quantity FROM `order_items` JOIN `orders` ON `orders`.`id` = `order_items`.`order_id` and `orders`.`is_cancel`= 0  WHERE `product_id`='".$shipment1->item_id."' and `shipment_id`='".$shipment1->id."'");
		 
		  
		  $shipmentDetails['shipment_quantity']= $shipment1->shipment_quantity - $r_quantity[0]->remain_quantity;
		  $shipmentfinal[]=$shipmentDetails;	
		}	
					
			return response()->json(['stock'=>$stock[0],'product_image'=>$p_image,'shipment'=>$shipmentfinal]);
		}
		else{
		   return response()->json(['stock'=>$stock[0],'product_image'=>$p_image,'shipment'=>$shipment]);	
		}

	}
	
	public function manage_order_status(){
		$date1 = date('Y-m-d', strtotime('-4 weeks'));
		$ready_orders = Order::select('id')->where('order_date','<',$date1)->where('order_tab','1')->where('is_cancel','0')->where('is_done','0')->where('is_partial_done','0')->where('shipping_type_id', 3)->get();
		
		if(count($ready_orders)>0){
			foreach($ready_orders as $ready_order){
					$updateorder = Order::find($ready_order->id);
					$updateorder->order_tab = '2';
					$updateorder->tab2_date = date('y-m-d'); 
					$updateorder->save();
					$this->send_defualt_notice_email($ready_order->id);
			}
		}
		$d2 = date('Y-m-d', strtotime('-4 weeks'));
		$orders1 = Order::select('orders.id','order_items.s_from','orders.shipping_location_id')->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id')->where('orders.order_date','<',$d2)->where('orders.order_tab','1')->where('orders.is_cancel','0')->where('orders.is_done','0')->where('orders.is_partial_done','0')->where('shipping_type_id','!=', 3)->get();
		
		if($orders1){
			foreach($orders1 as $order){
				
				if($order->s_from=='my_stock' || $order->shipping_location_id!=129){
					$updateorder = Order::find($order->id);
					$updateorder->order_tab = '2';
					$updateorder->tab2_date = date('y-m-d'); 
					$updateorder->save();
					$this->send_defualt_notice_email($order->id);
				}
			}
		}

		$d3 = date('Y-m-d', strtotime('-4 weeks'));
		
		$sea_orders =  Order::select('orders.id','orders.total_item',DB::raw('( SELECT GROUP_CONCAT(`id`) FROM `order_items` WHERE `orders`.`id` = `order_items`.`order_id`) as `order_items_ids`'))->where('orders.order_date','<',$d3)->where('orders.order_tab','1')->where('orders.is_cancel','0')->where('orders.is_done','0')->where('orders.is_partial_done','0')->where('orders.shipping_type_id', 2)->get();
		
		
		if(count($sea_orders)>0){
			foreach($sea_orders as $sea_order){
				
				$arrive_total = DB::select(DB::raw("select substring_index(group_concat(`arrive_date` order by id desc), ',', 1) as date,SUM(ship_quantity) as total_arrive from `shipment_order_item` where `is_arrived` = 1 and `order_items_id` in ($sea_order->order_items_ids)"));
				
				if($arrive_total[0]->total_arrive==$sea_order->total_item && $arrive_total[0]->date < $d3){
					$updateorder = Order::find($sea_order->id);
					$updateorder->order_tab = '2';
					$updateorder->tab2_date = date('y-m-d'); 
					$updateorder->save();
					$this->send_defualt_notice_email($sea_order->id);
				}
				
				
			}
		}
		
		$d4 = date('Y-m-d', strtotime('-4 weeks'));
		$da4 = date('Y-m-d', strtotime('-2 weeks'));
		$air_orders = Order::select('orders.id','orders.order_date','orders.total_item',DB::raw('( SELECT GROUP_CONCAT(`id`) FROM `order_items` WHERE `orders`.`id` = `order_items`.`order_id`) as `order_items_ids`'),DB::raw('( SELECT payment_plan_id FROM `order_items` WHERE `orders`.`id` = `order_items`.`order_id` limit 1) as `payment_plan_id`'))->where('orders.order_date','<',$d4)->where('orders.order_tab','1')->where('orders.is_cancel','0')->where('orders.is_done','0')->where('orders.is_partial_done','0')->where('orders.shipping_type_id',1)->get();
		
		if(count($air_orders)>0){
			foreach($air_orders as $air_order){
				
				if($air_order->payment_plan_id=='2'){
					$date11 = date('Y-m-d', strtotime('-90 days'));
					if($air_order->order_date < $date11){
						$arrive_total = DB::select(DB::raw("select substring_index(group_concat(`arrive_date` order by id desc), ',', 1) as date,SUM(ship_quantity) as total_arrive from `shipment_order_item` where `is_arrived` = 1 and `order_items_id` in ($air_order->order_items_ids)"));
					
						if($arrive_total[0]->total_arrive !=null && $arrive_total[0]->date !=null){					
							if($arrive_total[0]->total_arrive==$air_order->total_item){
								$updateorder = Order::find($air_order->id);
								$updateorder->tab2_date = date('y-m-d'); 
								$updateorder->order_tab = '2';
								$updateorder->save();
								$this->send_defualt_notice_email($air_order->id);
							}
						}
					}
				}else{
					
					$arrive_total = DB::select(DB::raw("select substring_index(group_concat(`arrive_date` order by id desc), ',', 1) as date,SUM(ship_quantity) as total_arrive from `shipment_order_item` where `is_arrived` = 1 and `order_items_id` in ($air_order->order_items_ids)"));
					
					if($arrive_total[0]->total_arrive !=null && $arrive_total[0]->date !=null){					
						if($arrive_total[0]->total_arrive==$air_order->total_item && $arrive_total[0]->date < $da4){
							
							$product_details = DB::table('order_items')->select('products.installment_cost','order_items.quantity','order_items.id')->leftJoin('products', 'products.id', '=', 'order_items.product_id')->where('order_items.order_id',$air_order->id)->get();
							
							$new_product_total = 0;
							foreach($product_details as $product_detail){
								$updateorderitem = OrderItem::find($product_detail->id);
								$updateorderitem->payment_plan_id = '2';
								$updateorderitem->product_price = $product_detail->installment_cost;
								$updateorderitem->total_amount = $product_detail->installment_cost * $product_detail->quantity;
								$updateorderitem->save();
								
								$new_product_total = $new_product_total + ($product_detail->installment_cost * $product_detail->quantity);
							}
							
							$updateorder = Order::find($air_order->id);
							$new_order_total = $updateorder->total_airfreight_cost + $updateorder->total_local_postage_cost + $new_product_total;
							$updateorder->order_total =	$new_order_total;	
							$updateorder->order_tab = '2';
							$updateorder->tab2_date = date('y-m-d'); 
							$updateorder->save();
							$this->send_defualt_notice_email($air_order->id);
						}
						
						
					}
				}
			}
		}
		
		$d4 = date('Y-m-d', strtotime('-2 weeks'));
		$tab2orders = Order::select('id','shipping_type_id')->where('tab2_date','<',$d4)->where('order_tab','2')->where('is_cancel','0')->where('is_done','0')->where('is_partial_done','0')->get();
		
		if(count($tab2orders)>0){
			foreach($tab2orders as $tab2order){
				if($tab2order->shipping_type_id==1){
					$air_orders = Order::select('orders.id','orders.order_date',DB::raw('( SELECT payment_plan_id FROM `order_items` WHERE `orders`.`id` = `order_items`.`order_id` limit 1) as `payment_plan_id`'))->where('orders.id',$tab2order->id)->get();
					
					$date11 = date('Y-m-d', strtotime('-104 days'));
					
					if($air_orders[0]->order_date < $date11){
						
						$updateorder = Order::find($tab2order->id);
						$updateorder->order_tab = '3';
						$updateorder->save();
						$this->send_action_notice_email($tab2order->id);
					}
				}else{
					$updateorder = Order::find($tab2order->id);
					$updateorder->order_tab = '3';
					$updateorder->save();
					$this->send_action_notice_email($tab2order->id);
				}
			}
		}
	}
	
	function send_defualt_notice_email($order_id){
			$order = Order::select(\DB::raw('customer_addresses.customer_full_name as customer_name,customer_addresses.email as email,orders.order_code,orders.order_date'))
        				->join('customer_addresses', function($join)
						{
							$join->on('customer_addresses.customer_id', '=', 'orders.customer_id');
							$join->where('customer_addresses.is_default', '=', 1);
						})
	 					->where('orders.id',$order_id)
						->get();
						$email = $order[0]->email;
						$customer_name = $order[0]->customer_name;
				Mail::send('mail.default_notice', ['customer_name' => $customer_name,'email'=>$email,'order_code'=>$order[0]->order_code,'order_date'=>$order[0]->order_date], function ($message) use($email)
				{
					$message->to($email)->subject('Ukshop: Default (Penalty) Notice!!! ');
				
				});
	}
	
	function send_action_notice_email($order_id){
			$order = Order::select(\DB::raw('customer_addresses.customer_full_name as customer_name,customer_addresses.email as email,orders.order_code,orders.order_date'))
        				->join('customer_addresses', function($join)
						{
							$join->on('customer_addresses.customer_id', '=', 'orders.customer_id');
							$join->where('customer_addresses.is_default', '=', 1);
						})
	 					->where('orders.id',$order_id)
						->get();
						$email = $order[0]->email;
						$customer_name = $order[0]->customer_name;
				Mail::send('mail.action_notice', ['customer_name' => $customer_name,'email'=>$email,'order_code'=>$order[0]->order_code,'order_date'=>$order[0]->order_date], function ($message) use($email)
				{
					$message->to($email)->subject('Ukshop: Cancellation Notice!!! ');
				
				});
	}
	
	function add_order_penalty(Request $request)
	{
		        $created_by =  Auth::user()->id;
				if(Session::get('admin_id')){
				   $user_account_id = Session::get('admin_id');
				}else{
				   $user_account_id =  Auth::user()->id;
				}
				
				$order_details=$this->get_orders_details($request->source_id);
				$order_amount=$order_details->order_total-$order_details->amount_penalty;
				$order_total=$order_amount+$request->amount_penalty;  
				/*add trans histories*/  
				$result_trans=DB::table('customer_trans_histories')
					->where('trans_type', 'penalty')
					->where('trans_id', $request->source_id)
					->select('*')
				    ->get();
				if(count($result_trans)<=0){
					$image_save = CustomerTransHistories::create([
						'customer_id' => $order_details->customer_id,
						'trans_type' => 'penalty',
						'trans_id' =>$request->source_id,
						'amount' => $request->amount_penalty,
						'balance' =>'0.00',
					]);	 
				}
				else{
					$result=DB::table('customer_trans_histories')
					->where('trans_type', 'penalty')
					->where('trans_id', $request->source_id)
					->update(['amount' => $request->amount_penalty]);
				}
				/*end*/ 
				  
				$result=DB::table('orders')
					->where('id', $request->source_id)
					->update(['amount_penalty' => $request->amount_penalty]);
				if($result){
		        $notes_time= date('Y-m-d H:i:s');
				$oid=encrypt($request->source_id);
	            $note = Note::create([  
					'source_comment' => $request->source_comment,
					'source_type' => $request->source_type,
					'source_id' => $request->source_id,
					'notes_time' => $notes_time,
					'user_account_id' => $user_account_id,
					'created_by' => $created_by,
					'updated_by' => $created_by,
 			    ]);
				}
				if($result){
			   	  Session::flash('title', 'Penalty added success'); 
				  Session::flash('success-toast-message', 'Your Penalty added successfully ');	
				  return redirect('order/'.$oid.'/edit');
				}
	}
	
	
	function cancel_order_and_refund(Request $request)
	{
				$user = Auth::user();
				if(Session::get('admin_id')){
					$created_by = Session::get('admin_id');
				}else{
					$created_by = $user->id;
				}
				$user_account_id =  $user->id;
				
		        $notes_time= date('Y-m-d H:i:s');
				$oid=encrypt($request->source_id);
				
				$cancel_order=DB::table('orders')
					->where('id', $request->source_id)
					->update(['is_cancel' => 1]);
			$orderitems = OrderItem::where('order_id',$request->source_id)->get();
			
			$order_customer_id='';
			foreach($orderitems as $orderitem){
				 if($orderitem->s_from=='my_stock'){
					 $left_in_my_stock = $orderitem->quantity - $orderitem->dispatch_quantity;
						/*Product::where('id',$orderitem->product_id)->increment('malaysia_stock',$left_in_my_stock);*/
						Product::where('id',$orderitem->product_id)->update([
				   'malaysia_stock' => DB::raw('malaysia_stock +'.$left_in_my_stock),
				   'malaysia_sold_stock' => DB::raw('malaysia_sold_stock -'.$left_in_my_stock),
   ]);
						ProductStock::create(['product_id'=>$orderitem->product_id,'location_id'=>'2','quantity'=>$left_in_my_stock,'created_by'=>$created_by,'user_account_id'=>$user->id]);
				
				}elseif($orderitem->dispatch_ready==0){
					 
					  Product::where('id',$orderitem->product_id)->increment('uk_stock',$orderitem->quantity);
					  ProductStock::create(['product_id'=>$orderitem->product_id,'location_id'=>'1','quantity'=>$orderitem->quantity,'created_by'=>$created_by,'user_account_id'=>$user->id]);					
					
			  	}else{
					$pending = 0;
					if($orderitem->pending_quantity>0){
						$shipment_pending = DB::table('shipment_line')->select('pending_quantity')->where('item_id',$orderitem->product_id)->where('shipment_id',$orderitem->shipment_id)->first();	
						if($shipment_pending->pending_quantity!=0){
							$pending = $orderitem->pending_quantity;
						}else{
							$pending = 0;
						}
					}
					$arrive_quantity=DB::table('shipment_order_item')->select(DB::raw("if(sum(`ship_quantity`) IS NULL ,'0',sum(`ship_quantity`)) as `ship_quantity`"))
					->where('is_arrived', '1')
					->where('order_items_id', $orderitem->id)->get(); 
					
					$left_in_my_stock = $arrive_quantity[0]->ship_quantity - ($orderitem->dispatch_quantity + $pending );
					$left_in_uk_stock = $orderitem->quantity - $orderitem->ship_quantity;
					
					if($left_in_my_stock>0){
						/*Product::where('id',$orderitem->product_id)->increment('malaysia_stock',$left_in_my_stock);*/
						Product::where('id',$orderitem->product_id)->update([
				   'malaysia_stock' => DB::raw('malaysia_stock +'.$left_in_my_stock),
				   'malaysia_sold_stock' => DB::raw('malaysia_sold_stock -'.$left_in_my_stock),
   ]);
					  ProductStock::create(['product_id'=>$orderitem->product_id,'location_id'=>'2','quantity'=>$left_in_my_stock,'created_by'=>$created_by,'user_account_id'=>$user->id]);	
					}
					
					if($left_in_uk_stock>0){
						Product::where('id',$orderitem->product_id)->increment('uk_stock',$left_in_uk_stock);
					  ProductStock::create(['product_id'=>$orderitem->product_id,'location_id'=>'1','quantity'=>$left_in_uk_stock,'created_by'=>$created_by,'user_account_id'=>$user->id]);	
					}
			  }
				  
			  $sql_1="SELECT * FROM `orders` where orders.id='".$request->source_id."'";
			  $user_account_details = DB::select(DB::raw($sql_1)); 
			  $order_user_account_id=$user_account_details[0]->user_account_id;
			  $order_customer_id=$order_user_account_id;
			  $order_user = \App\User::find($order_user_account_id);
			  if($order_user->hasRole('Sales-Agent')){
					if($orderitem->quantity > $orderitem->dispatch_quantity) {
						$left = $orderitem->quantity - $orderitem->dispatch_quantity;
				 		$sql_2="SELECT * FROM `users_commissions` WHERE `product_id`='".$orderitem->product_id."' and `order_id`='".$request->source_id."' and `user_account_id` ='".$order_user_account_id."'";
			  			$user_commissions_details = DB::select(DB::raw($sql_2)); 
			 
					  	if(count($user_commissions_details)>0){	
						 $new_comm = ($user_commissions_details[0]->commission_rate) * ($left);
					 	 UsersCommission::create([
							'product_id'=>$user_commissions_details[0]->product_id,
							'order_id' => $request->source_id,
							'customer_id'=>$user_commissions_details[0]->customer_id,
							'quantity'=>$left,
							'total_commission'=>$new_comm,
							'commission_rate'=>$user_commissions_details[0]->commission_rate,
							'unit_price'=>$user_commissions_details[0]->unit_price,
							'commission_type' => 'return',
							'created_by' => $created_by,
							'user_account_id' => $order_user_account_id,
							]); 
					  	}
					}
			 } 
				  
			 
			}
				if($cancel_order){
				/*add trans histories*/
				$penalty_amount = 0; 
				if($request->show_penalty_amount!=0){
				$penalty_amount=$request->show_penalty_amount;
				$order_details=$this->get_orders_details($request->source_id);	
				$result=DB::table('orders')
					->where('id', $request->source_id)
					->update(['amount_penalty' => $penalty_amount]);
				
				
				
				
				}
				
				
				/*end*/ 	
					
					
	            $note = Note::create([  
					'source_comment' => $request->source_comment,
					'source_type' => $request->source_type,
					'source_id' => $request->source_id,
					'notes_time' => $notes_time,
					'user_account_id' => $user_account_id,
					'created_by' => $created_by,
					'updated_by' => $created_by,
 			    ]);
				if($note->id>0){
				  $order_details=$this->get_orders_details($request->source_id);
					$trans_cancel_save = CustomerTransHistories::create([
					'customer_id' => $order_details->customer_id,
					'trans_type' => 'cancel',
					'trans_id' =>$request->source_id,
					'amount' => $request->source_total,
					'balance' =>'0.00',
					'user_account_id' => $user_account_id,
					'created_by' => $created_by,
					'updated_by' => $created_by,
				   ]);
				   
				   
					DB::table('customer_trans_histories')
					->where('trans_type', 'penalty')
					->where('trans_id', $request->source_id)
					->delete();
				
					CustomerTransHistories::create([
						'customer_id' => $order_details->customer_id,
						'trans_type' => 'penalty',
						'trans_id' =>$request->source_id,
						'amount' =>$penalty_amount,
						'balance' =>'0.00',
					]);	 
				   
				   if($trans_cancel_save->id>0){	
						$order_details=$this->get_orders_details($request->source_id);
						$customer_details = $this->getCustomerDetails($order_details->customer_id);
						$customer_balance=$customer_details->balance_amount-$request->source_total;
						//$customer_balance=$customer_balance+$order_payment_amount;
						$response=DB::table('customers')
						->where('id', $order_details->customer_id)
						->update(['balance_amount' => $customer_balance]);
				    }
				}
				
				
				if($note->id>0){
				  if($request->amount_penalty>0)
				  {
					$refund_date=$date = date('m-d-Y');  
				    $refund_save = PaymentRefunds::create([
					'order_id' => $request->source_id,
					'description' => $request->source_comment,
					'amount' =>$request->amount_Refund,
					'refund_status' => 0,
					'refund_date' => $refund_date,
					'user_account_id' => $user_account_id,
					'created_by' => $created_by,
					'updated_by' => $created_by,
					]);
				  
				   if($refund_save->id>0){
					   $trans_save = CustomerTransHistories::create([
						'customer_id' => $order_details->customer_id,
						'trans_type' => 'refund',
						'trans_id' =>$refund_save->id,
						'amount' => $request->amount_Refund,
						'balance' =>'0.00',
						'user_account_id' => $user_account_id,
						'created_by' => $created_by,
						'updated_by' => $created_by,
					   ]);
				     }
				   }
				  
				  }
	            }
				
				if($cancel_order){
			   	  Session::flash('title', 'Cancel order success'); 
				  Session::flash('success-toast-message', 'Your order canceled successfully ');	
				  return redirect('order');
				}
				else{
				  Session::flash('title', 'Error created'); 
				  Session::flash('success-toast-message', 'Error created');	
				  return redirect('order');	
				}
	}
	
	
	public function request_cancel_order_and_refund(Request $request){
		$user = Auth::user();
		if(Session::get('admin_id')){
			$created_by = Session::get('admin_id');
		}else{
			$created_by = $user->id;
		}
		$user_account_id =  $user->id;
		$notes_time= date('Y-m-d H:i:s');
		$result=DB::table('orders')
					->where('id', $request->source_id)
					->update(['cancel_request' => 1,'updated_by'=>$created_by]);
		$note = Note::create([  
					'source_comment' => $request->source_comment,
					'source_type' => $request->source_type,
					'source_id' => $request->source_id,
					'notes_time' => $notes_time,
					'user_account_id' => $user_account_id,
					'created_by' => $created_by,
					'updated_by' => $created_by,
 			    ]);
		  Session::flash('title', 'Cancel order request success'); 
		  Session::flash('success-toast-message', 'Your order cancel request successfully ');	
		  return redirect('order');
	}
	
	public function get_orders_details($id)   
	{
		$order_details = DB::select(DB::raw("SELECT orders.* FROM `orders` WHERE `id`=".$id."")); 
	    return $order_details[0];	
	}
	
	Public function check_order_payment($id)   
	{
		//$id=19;
		$order_details = DB::select(DB::raw("SELECT count(id) as p_count, sum(amount) as p_amount FROM `payment_lines` WHERE `order_id` =".$id."")); 
	    return $order_details[0];	
	}
	
	public function force_active(Request $request){
		$order = Order::find($request->force_order_id);
	   	$order->is_force_active = '1';
	   	$order->save();
		Session::flash('title', 'order force active success'); 
		Session::flash('success-toast-message', 'Your order force active successfully ');	
		return redirect('order');
	}
	
	public function dispatch_order($order_id){
		$id = decrypt($order_id);
	   
	   $order = Order::select(\DB::raw('orders.*,customers.id as customer_id , customers.customer_full_name as customer_name, customers.customer_uniq_id as customer_code'))
        				->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
	 					->where('orders.id',$id)
     					->get();
		$order_items = array();				
	   	$temp_order_items = OrderItem::select(\DB::raw('order_items.*,products.product_name as product_name,products.id as product_id, products.item_uniq_id as product_code'))->leftJoin('products', 'products.id', '=', 'order_items.product_id')
	  ->where('order_items.order_id',$id)->get();
	  
	  $customer_address = CustomerAddress::where('customer_addresses.id',$order[0]->customer_address_id)->join('countries', 'countries.id', '=', 'customer_addresses.country_id')->get();
	 
	 foreach($temp_order_items as $order_item)
		{
			$product_image = DB::select('select * from images where source_id="'.$order_item->product_id.'" and source_type="product"');
			if($product_image){
					$p_image = $product_image[0]->thumb_image_name;
				}else{
					$p_image = 'no_image.jpg';
				}
			$order_items[] = array(
				   'order_item_id'=>$order_item->id,
			       'product_code'=>$order_item->product_code, 
				   'product_id'=>$order_item->product_id,
				   'product_name'=>$order_item->product_name,
				   'quantity'=>$order_item->quantity,
				   'ship_quantity'=>$order_item->ship_quantity,
				   'pending_quantity'=>$order_item->pending_quantity,
				   's_from'=>$order_item->s_from,
				   'dispatch_quantity'=>$order_item->dispatch_quantity, 
				   'product_price'=>$order_item->product_price,
				   'total_amount'=>$order_item->total_amount,
				   'dispatch_ready'=>$order_item->dispatch_ready,
				   'image_name1'=>$p_image,
				   
			);
		}
		return view('dispatch/ready_to_ship',compact('order','order_items','id','customer_address'));
		
	}
	
	public function submit_dispatch_order(Request $request){
		
		$user = Auth::user();
		if(Session::get('admin_id')){
			$created_by = Session::get('admin_id');
		}else{
			$created_by = $user->id;
		}
		$is_done = '1';
		$is_partial_done = '0';
		for($j=0;$j<count($request->order_item_id);$j++){
		   $OrderItem = OrderItem::find($request->order_item_id[$j]);
		   $diff = $OrderItem->quantity - $OrderItem->dispatch_quantity;
		  if($diff != $request->ship_quantity[$j]){
			  $is_done = '0';
			  $is_partial_done = '1';
		  }
		  
		   $OrderItem->dispatch_quantity = DB::raw('dispatch_quantity +'.$request->ship_quantity[$j]);
		   $OrderItem->dispatch_ready = '3';
		   $OrderItem->save();
		   DB::table('shipment_order_item')
					->where('order_items_id', $request->order_item_id[$j])
					->update(['is_dispatch' => 1]);
					
			Product::where('id',$request->product_id[$j])->decrement('malaysia_sold_stock',$request->ship_quantity[$j]);
					  	
	   }
		DispatchOrder::create([
			'order_id'=>$request->order_id, 
			'dispatch_date'=>$request->dispatch_date,
			'courier_id'=>$request->courier,
			'consignment_code'=>$request->consignment_no,
			'collect_by' =>'NA',
			'customer_address_id'=>$request->customer_address_id,
			'user_aacount_id'=>$user->id,
			'created_by'=>$created_by,
		]);
		
		$order = Order::find($request->order_id);
		$order->is_done = $is_done;
		$order->is_partial_done = $is_partial_done;
		$order->save();
		return redirect('order');	
		
	}
	
	public function dispatch_collect_order($order_id){
		$id = decrypt($order_id);
	   
	   $order = Order::select(\DB::raw('orders.*,customers.id as customer_id , customers.customer_full_name as customer_name, customers.customer_uniq_id as customer_code'))
        				->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
	 					->where('orders.id',$id)
     					->get();
		$order_items = array();				
	   	$temp_order_items = OrderItem::select(\DB::raw('order_items.*,products.product_name as product_name,products.id as product_id, products.item_uniq_id as product_code'))->leftJoin('products', 'products.id', '=', 'order_items.product_id')
	  ->where('order_items.order_id',$id)->get();
	  
	 foreach($temp_order_items as $order_item)
		{
			$product_image = DB::select('select * from images where source_id="'.$order_item->product_id.'" and source_type="product"');
			if($product_image){
					$p_image = $product_image[0]->thumb_image_name;
				}else{
					$p_image = 'no_image.jpg';
				}
			$order_items[] = array(
				   'order_item_id'=>$order_item->id,
			       'product_code'=>$order_item->product_code, 
				   'product_id'=>$order_item->product_id,
				   'product_name'=>$order_item->product_name,
				   'quantity'=>$order_item->quantity,
				   'ship_quantity'=>$order_item->ship_quantity,
				   'pending_quantity'=>$order_item->pending_quantity,
				    's_from'=>$order_item->s_from,
				   'dispatch_quantity'=>$order_item->dispatch_quantity, 
				   'product_price'=>$order_item->product_price,
				   'total_amount'=>$order_item->total_amount,
				   'dispatch_ready'=>$order_item->dispatch_ready,
				   
				   'image_name1'=>$p_image,
				   
			);
		}
		return view('dispatch/ready_to_collect',compact('order','order_items','id'));
	}
	
	public function submit_dispatch_collect_order(Request $request){
		$user = Auth::user();
		if(Session::get('admin_id')){
			$created_by = Session::get('admin_id');
		}else{
			$created_by = $user->id;
		}
		$is_done = '1';
		$is_partial_done = '0';
		for($j=0;$j<count($request->order_item_id);$j++){
		   $OrderItem = OrderItem::find($request->order_item_id[$j]);
		   $diff = $OrderItem->quantity - $OrderItem->dispatch_quantity;
		  if($diff != $request->ship_quantity[$j]){
			  $is_done = '0';
			  $is_partial_done = '1';
		  }
		   $OrderItem->dispatch_quantity = DB::raw('dispatch_quantity +'.$request->ship_quantity[$j]);
		   $OrderItem->dispatch_ready = '3';
		   $OrderItem->save();
		   DB::table('shipment_order_item')
					->where('order_items_id', $request->order_item_id[$j])
					->update(['is_dispatch' => 1]);
			Product::where('id',$request->product_id[$j])->decrement('malaysia_sold_stock',$request->ship_quantity[$j]);
					  
	   }
		DispatchOrder::create([
			'order_id'=>$request->order_id, 
			'dispatch_date'=>$request->dispatch_date,
			'courier_id'=>'0',
			'consignment_code'=>'NA',
			'customer_address_id'=>$request->customer_address_id,
			'collect_by' =>$request->collect_by,
			'user_aacount_id'=>$user->id,
			'created_by'=>$created_by,
		]);
		
		$order = Order::find($request->order_id);
		$order->is_done = $is_done;
		$order->is_partial_done = $is_partial_done;
		$order->save();
		return redirect('order');	
		
	}
	
	public function order_cash_on_delivery($order_id){
			
		$id = decrypt($order_id);
	   
	   $order = Order::select(\DB::raw('orders.*,customers.id as customer_id , customers.customer_full_name as customer_name, customers.customer_uniq_id as customer_code'))
        				->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
	 					->where('orders.id',$id)
     					->get();
		$order_items = array();				
	   	$temp_order_items = OrderItem::select(\DB::raw('order_items.*,products.product_name as product_name,products.id as product_id, products.item_uniq_id as product_code'))->leftJoin('products', 'products.id', '=', 'order_items.product_id')
	  ->where('order_items.order_id',$id)->get();
	  
	  
	 foreach($temp_order_items as $order_item)
		{
			$product_image = DB::select('select * from images where source_id="'.$order_item->product_id.'" and source_type="product"');
			if($product_image){
					$p_image = $product_image[0]->thumb_image_name;
				}else{
					$p_image = 'no_image.jpg';
				}
			$order_items[] = array(
				   'order_item_id'=>$order_item->id,
			       'product_code'=>$order_item->product_code, 
				   'product_id'=>$order_item->product_id,
				   'product_name'=>$order_item->product_name,
				   'quantity'=>$order_item->quantity,
				   'ship_quantity'=>$order_item->ship_quantity,
				   'pending_quantity'=>$order_item->pending_quantity,
				   's_from'=>$order_item->s_from,
				   'dispatch_quantity'=>$order_item->dispatch_quantity, 
				   'product_price'=>$order_item->product_price,
				   'total_amount'=>$order_item->total_amount,
				   'dispatch_ready'=>$order_item->dispatch_ready,
				   'image_name1'=>$p_image,
				   
			);
		}
		return view('dispatch/cash_on_delivery',compact('order','order_items','id'));
	}
	
	public function submit_cash_on_delivery_order(Request $request){
		
		$user = Auth::user();
		if(Session::get('admin_id')){
			$created_by = Session::get('admin_id');
		}else{
			$created_by = $user->id;
		}
		$account_id=0;
		if($request->cod_payment=='1'){
			
			$payment_last = Payment::latest()->first();
			if($payment_last){
				 $payment_code = 'PAYID-'.(1001+$payment_last->id);
			}else{
				 $payment_code = 'PAYID-1001';
			}
			$payment = Payment::create([  
						'payment_source' 	=> $request->payment_source,
						'payment_code' 	 	=> $payment_code,
						'payment_customer' 	=> $request->customer_id,
						'payment_date' 		=> $request->payment_date,
						'payment_amount' 	=> $request->payment_amount,
						'order_amount' 		=> $request->order_total,
						'payment_ref_number' => $request->payment_ref_number,
						'payment_note' 		=> $request->payment_note,
						'payment_status' 	=> 'Verified',
						'created_by' 		=> $created_by,
						'user_account_id' 	=> $user->id,
					]);
					
		
			$payment_line = PaymentLine::create([
				  'payment_id' => $payment->id,
				  'order_id' => $request->order_id,
				  'amount' => $request->payment_amount,
				  'created_by' => '1',
				  'updated_by' => '1',
			]);	
			if($payment->id){
					$image_save = CustomerCredit::create([
						'customer_id' => $request->customer_id,
						'payment_id' => $payment->id,
						'amount' =>$request->payment_amount,
				    ]);
					
				}
				
				if($payment->id){   
				  $usertrans=$this->getUsertransHistory($request->customer_id);
				  $t_amount=$request->payment_amount;
				  
				  if($usertrans->balance<0){
					  $balance= $usertrans->balance+$t_amount;
				  }else
				  {
  				   $balance= $usertrans->balance-$t_amount;
				  }

				  $image_save = CustomerTransHistories::create([
					'customer_id' => $request->customer_id,
					'trans_type' => 'payment',
					'trans_id' =>$payment->id,
					'amount' => $t_amount,
					'balance' =>$balance,
				  ]);	
				 }
			$customer = Customer::select('customer_full_name','customer_uniq_id')->where('id',$request->customer_id)->first();
			$Order = Order::select('order_code')->where('id',$request->order_id)->first();
			$acc = Account::create([
				'purpose' => 'COD payment recieve from '.$customer->customer_full_name.'('.$customer->customer_uniq_id.'), Order- '.$Order->order_code.',payment- '.$payment_code,
				'date'	=> $request->payment_date,
				'type'	=>	'Income',
				'amount'	=>	$request->payment_amount,
				'created_by' 		=> $created_by,
				'user_account_id' 	=> $user->id
			]);
			$account_id =$acc->id;
			
		}
		 
		$is_done = '1';
		$is_partial_done = '0';
		for($j=0;$j<count($request->order_item_id);$j++){
		   $OrderItem = OrderItem::find($request->order_item_id[$j]);
		   $diff = $OrderItem->quantity - $OrderItem->dispatch_quantity;
		  if($diff != $request->ship_quantity[$j]){
			  $is_done = '0';
			  $is_partial_done = '1';
		  }
		   $OrderItem->dispatch_quantity = DB::raw('dispatch_quantity +'.$request->ship_quantity[$j]);
		   $OrderItem->dispatch_ready = '3';
		   $OrderItem->save();
		   DB::table('shipment_order_item')
					->where('order_items_id', $request->order_item_id[$j])
					->update(['is_dispatch' => 1]);
			Product::where('id',$request->product_id[$j])->decrement('malaysia_sold_stock',$request->ship_quantity[$j]);
			
	   }
	  
	   	$order = Order::find($request->order_id);
		$order->is_done = $is_done;
		$order->is_partial_done = $is_partial_done;
		$order->order_payment_status='Paid';
		$order->save();	
		
		DispatchOrder::create([
			'order_id'=>$request->order_id, 
			'dispatch_date'=>$request->dispatch_date,
			'courier_id'=>'0',
			'consignment_code'=>'NA',
			'customer_address_id'=>$request->customer_address_id,
			'collect_by' =>$request->collect_by,
			'user_aacount_id'=>$user->id,
			'accounts_id' =>$account_id,
			'created_by'=>$created_by,
		]);	
		return redirect('order');
	}
	
	public function order_move_to_tab2($order_id){
		
		$id = decrypt($order_id);
		$result=DB::table('orders')
					->where('id', $id)
					->update(['order_tab' => 2,'tab2_date'=>date('Y-m-d')]);
					return redirect('order');
	}
	
	public function send_order_reminder_mail($order_id){
		
		$id = decrypt($order_id);
		$order = Order::select(\DB::raw('customer_addresses.customer_full_name as customer_name,customer_addresses.email as email'))
        				
						->join('customer_addresses', function($join)
						{
							$join->on('customer_addresses.customer_id', '=', 'orders.customer_id');
							$join->where('customer_addresses.is_default', '=', 1);
						})
	 					->where('orders.id',$id)
						->get();
						$email = $order[0]->email;
						$customer_name = $order[0]->customer_name;
		Mail::send('mail.order_reminder', ['customer_name' => $customer_name,'email'=>$email], function ($message) use($email)
        {
            $message->to($email)->subject('Ukshop: Order Reminder ');
        
        });
		Session::flash('title', 'Mail send Success'); 
		Session::flash('success-toast-message', 'order reminder Mail send successfully ');	
		return redirect('order');
	}
	
	public function test(){
		Product::where('id',1)->update([
				   'malaysia_stock' => DB::raw('malaysia_stock - 10'),
				   'malaysia_sold_stock' => DB::raw('malaysia_sold_stock + 10'),
   ]);
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
	
	
	public function getdefaultorderPdf($id,$type)
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
			if($product_image){
					$p_image = $product_image[0]->thumb_image_name;
				}else{
					$p_image = 'no_image.jpg';
				}
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
				   'image_name1'=>$p_image,
				   
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
											 
              $pdf = PDF::loadView('report/dispatch_order_details_pdf',compact('order','order_items','is_paid','p_amount','parsely_disable','total_paid'));
              $pdf->setPaper('A4', 'landscape');
			  if($type=='sendemail'){
				 $destination_path = public_path('/pdf/dispatch_order_details');
				 $pdf->save($destination_path.'/dispatch_order_details_pdf.pdf'); 
				 $customer_details = $this->getCustomerDetails($order[0]->customer_id);
		       	 $email='ayush@itsabacus.com';
				 Mail::send('mail.default_order', [], function ($message) use($email,$destination_path)
				{
				 $message->to($email)->subject('Ukshop: Default Order Details');
				 $message->attach($destination_path.'/dispatch_order_details_pdf.pdf');
				}); 
				
				
				Session::flash('title', 'Default Order'); 
				Session::flash('success-toast-message', 'Default Order pdf send via email');	
				return redirect('order');
			  }
			  else{
				   return $pdf->download('dispatch_order_details.pdf'); 
			  }
    }
	public function getUsertransHistory($userid)
	{
	   $customer_credit = DB::select(DB::raw("select * from customer_trans_histories where id = (select max(`id`) from customer_trans_histories WHERE `customer_id`='".$userid."')")); 
	   return $customer_credit[0];	
	}
}
