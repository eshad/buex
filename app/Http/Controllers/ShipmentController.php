<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\OrderItem;
use App\Order;
use App\Shipment;
use App\ProductStock;
use App\Product;
use App\Images;
use DB;
use Image;
use Auth;
use PDF;
class ShipmentController extends Controller
{
	public function index()
	{	
		$shipment_data = DB::table('shipment_line AS sl')
				->select('sl.*','p.item_uniq_id','p.product_name','s.shipment_number','s.id as ship_id')
				->where('sl.pending_quantity','>',0 )
				
				->leftJoin('products AS p', 
					function($join) {
						$join->on('p.id', '=', 'sl.item_id');
				})
				->leftJoin('shipments AS s', 
					function($join) {
						$join->on('s.id', '=', 'sl.shipment_id');
				})->get();
		return view('shipment/pending_history',compact('shipment_data'));
	}

	public function create()
	{
		$count = Shipment::count();
		 $order_data =  DB::select('select product.*,img.image_name,img.thumb_image_name as thumb_image_name from products as product Left join images as img ON img.source_id=product.id and img.source_type="product"');
		return view('shipment/create_shipment',['order_data'=>$order_data],['count'=>$count]);
	}

	public function store(Request $request)
	{
		$user = Auth::user();
		if(Session::get('admin_id')){
			$created_by = Session::get('admin_id');
		}else{
			$created_by = $user->id;
		}
		
		if($request->shipment_type=='Air'){
			$shipping_type_id = 1;
			$ship_quantity_sum = $request->total_ship_quantity_value;
		}else{
			$shipping_type_id = 2;
			$ship_quantity_sum = $request->total_ship_quantity_value;
		}
		
		$shipment = Shipment::create(['shipment_date'=>date("Y-m-d", strtotime($request->estimate_arrivable) ),'shipment_number'=>$request->shipment_name,'bl_awb_number'=>$request->bl_awb_number,'carrier_details'=>$request->carrier_details,'shipment_type'=>$request->shipment_type,'ship_quantity_sum'=>$ship_quantity_sum,'created_by'=>$created_by]);
	
		if(count($request->item_id)>0){
			
			for($i=0;$i<count($request->item_id);$i++){
				$total_item_ship_quantity = $request->ship_qty[$i];
				
				if($total_item_ship_quantity > 0){
					
					$orders=DB::select("select `oi`.`ship_quantity`, `oi`.`quantity`,`oi`.`id` from `order_items` as `oi` left join `orders` as `o` on `o`.`id` = `oi`.`order_id` where oi.s_from='uk_stock' and `oi`.`product_id` = '".$request->item_id[$i]."' and `oi`.`ship_quantity` <> oi.quantity and `o`.`shipping_location_id` = '129' and `o`.`is_cancel` = 0 and `o`.`is_done` = 0 and `o`.`shipping_type_id` = '".$shipping_type_id."' order by `id` asc");
					
					
					foreach($orders as $order){
						
						$diff = ($order->quantity - $order->ship_quantity);
						if($diff <= $total_item_ship_quantity){
							
					
							$order_item = OrderItem::find($order->id);
							$order_item->shipment_id = $shipment->id;
							$order_item->dispatch_ready = '2';
							$order_item->ship_quantity = DB::raw('ship_quantity+'.$diff);
							$order_item->save();
							DB::table('shipment_order_item')->insert(
								array('order_items_id' => $order_item->id,
									  'shipment_id' => $shipment->id,
									  'item_id'=>$request->item_id[$i],
									  'ship_quantity' => $diff)
							);
							$total_item_ship_quantity =  $total_item_ship_quantity - $diff;	
						}else if($total_item_ship_quantity>0){
							
							$order_item = OrderItem::find($order->id);
							$order_item->shipment_id = $shipment->id;
							$order_item->dispatch_ready = '2';
							$order_item->ship_quantity = DB::raw('ship_quantity+'.$total_item_ship_quantity);
							$order_item->save();
							DB::table('shipment_order_item')->insert(
								array('order_items_id' => $order_item->id,
									  'shipment_id' => $shipment->id,
									  'item_id'=>$request->item_id[$i],
									  'ship_quantity' => $total_item_ship_quantity)
							);
							
							$total_item_ship_quantity = 0;
							
						}
					}
					if($total_item_ship_quantity > 0){
						
						ProductStock::create([
							'product_id'	=>$request->item_id[$i],
							'location_id'	=>1,
							'quantity'		=>'-'.$total_item_ship_quantity,
							'created_by' => $created_by,
							'user_account_id' => $user->id,
						]);
			
						Product::where('id',$request->item_id[$i])->decrement('uk_stock', $total_item_ship_quantity);
				
					}
					
					DB::table('shipment_line')->insert(
								array('shipment_id' => $shipment->id,
									  'item_id' => $request->item_id[$i],
									  'shipment_quantity' => $request->ship_qty[$i],
									  'created_by' => $created_by)
							);
					
				}
			}
			
		}
		
		Session::flash('title', 'Shipment creat success'); 
		Session::flash('success-toast-message', 'Your new Shipment created successfully ');
		return redirect('shipment/show');
	}

	public function show()
	{
		$shipments = Shipment::where('status',0)->get();
		return view('shipment/shipment_status',compact('shipments'));
	}

	public function edit()
	{
		dd('edit');
	}


	public function destroy()
	{
		dd('destroy');
	}
	
	public function shipment_view($shipment_id){
		$shipment_id =decrypt($shipment_id);
		$shipment_data = DB::table('shipment_line AS sl')
				->select('sl.*','p.item_uniq_id','p.product_name')
				->where('sl.shipment_id',$shipment_id )
				
				->leftJoin('products AS p', 
					function($join) {
						$join->on('p.id', '=', 'sl.item_id');
				})->get();
		 
		return view('shipment/show_shipment',compact('shipment_data','shipment_id'));
		
	}

	function shipment_arrive(Request $request){
		$shipment_id = $request->shipment_id;
		
		$user = Auth::user();
		if(Session::get('admin_id')){
			$created_by = Session::get('admin_id');
		}else{
			$created_by = $user->id;
		}
		if(count($request->shipment_line_id)>0){
			
			for($i=0;$i<count($request->shipment_line_id);$i++){
				$total_item_receive_quntity = $request->receive_quntity[$i];
				
					$shipment_order_items=DB::table('shipment_order_item')
								
								->where('shipment_id',$shipment_id)
								->where('item_id',$request->shipment_item_id[$i])
								->orderBy('id', 'ASC')->get();
						
					foreach($shipment_order_items as $shipment_order_item){
						if($shipment_order_item->ship_quantity <= $total_item_receive_quntity){
							$order_item = OrderItem::find($shipment_order_item->order_items_id);
							$order_is = Order::find($order_item->order_id);			
							if($order_is->is_cancel==0){
							$total_item_receive_quntity =  $total_item_receive_quntity - $shipment_order_item->ship_quantity;
							OrderItem::where('id',$shipment_order_item->order_items_id)->update(['dispatch_ready'=>'1',]);
							Product::where('id',$request->shipment_item_id[$i])->increment('malaysia_sold_stock', $shipment_order_item->ship_quantity);
							}
							
						}else if($total_item_receive_quntity>0){
							$dif =$shipment_order_item->ship_quantity - $total_item_receive_quntity;
							
							OrderItem::where('id',$shipment_order_item->order_items_id)->update(['pending_quantity'=>$dif]);
							
							Product::where('id',$request->shipment_item_id[$i])->increment('malaysia_sold_stock', $total_item_receive_quntity);
							/*DB::table('shipment_order_item')
							->where('id',$shipment_order_item->id)
							->update(['ship_quantity'=>$total_item_receive_quntity]);*/	
							$total_item_receive_quntity = 0;
						}
						
					}
					
					DB::table('shipment_order_item')
							->where('shipment_id',$shipment_id)
							->where('item_id',$request->shipment_item_id[$i])
							->update(['is_arrived'=>'1','arrive_date'=>date('y-m-d')]);	
					
					if($request->receive_quntity[$i] < $request->shipment_quantity[$i]){
						$pending = $request->shipment_quantity[$i] - $request->receive_quntity[$i];
					//DB::enableQueryLog();
						DB::table('shipment_line')
							->where('id',$request->shipment_line_id[$i])
							->where('item_id',$request->shipment_item_id[$i])
							->update(['pending_quantity'=>$pending]);
						//$query = DB::getQueryLog();
//print_r($query);
					}
					
					if($total_item_receive_quntity>0){
							
							ProductStock::create([
							'product_id'	=>$request->shipment_item_id[$i],
							'location_id'	=>2,
							'quantity'		=>$total_item_receive_quntity,
							'created_by' => $created_by,
							'user_account_id' => $user->id,
						]);
			
						Product::where('id',$request->shipment_item_id[$i])->increment('malaysia_stock', $total_item_receive_quntity);
						
						}
			}
			
		}
		Shipment::where('id',$request->shipment_id)->update(['status'=>1]);
		Session::flash('title', 'Shipment arrive success'); 
		Session::flash('success-toast-message', 'Your Shipment arrive successfully ');
		return redirect('shipment/show');
	}
	
	function shipment_pending_arrive(Request $request){
		
		$user = Auth::user();
		if(Session::get('admin_id')){
			$created_by = Session::get('admin_id');
		}else{
			$created_by = $user->id;
		}
		if(count($request->shipment_line_id)>0){
			for($i=0;$i<count($request->shipment_line_id);$i++){
				$total_item_receive_quntity = $request->pending_arrive[$i];
				if($total_item_receive_quntity > 0){
					
					/*ProductStock::create([
							'product_id'	=>$request->shipment_item_id[$i],
							'location_id'	=>2,
							'quantity'		=>$request->pending_arrive[$i],
							'created_by' => $created_by,
							'user_account_id' => $user->id,
						]);*/
			
						
						/*DB::table('shipment_line')
							->where('id',$request->shipment_line_id[$i])
							->decrement('pending_quantity',$request->pending_arrive[$i]);*/
						$shipment_order_items=DB::table('shipment_order_item')
								->select('order_items_id')
								->where('shipment_id',$request->shipment_id[$i])
								->where('item_id',$request->shipment_item_id[$i])
								->orderBy('id', 'ASC')->get();
						
					foreach($shipment_order_items as $shipment_order_item){
						$order_item_check = OrderItem::select('orders.is_cancel')->where('order_items.id',$shipment_order_item->order_items_id)->leftJoin('orders', 
					function($join) {
						$join->on('orders.id', '=', 'order_items.order_id');
				})->get();
						if($order_item_check[0]->is_cancel==0){
						OrderItem::where('id',$shipment_order_item->order_items_id)->decrement('pending_quantity',$request->pending_arrive[$i]);
						}else{
							Product::where('id',$request->shipment_item_id[$i])->increment('malaysia_stock', $request->pending_arrive[$i]);
						}
					}
				}
			}
		}
		Session::flash('title', 'Shipment Pending arrive success'); 
		Session::flash('success-toast-message', 'Your Shipment Pending arrive successfully ');
		return redirect('shipment/show');
	}
	
	public function delete_pending_stock($shipment_line_id){
		DB::table('shipment_line')
							->where('id',$shipment_line_id)
							->update(['pending_quantity'=>0]);
		Session::flash('title', 'Shipment Pending remove success'); 
		Session::flash('success-toast-message', 'Your Shipment Pending remove successfully ');
		return redirect('shipment');
	}
	
	public function shipmentViewdownloadpdf($shipment_id,$pdftype)
    {
		
	 $shipment_id =$shipment_id;	
	 $shipment_details = $this->shipmentDetails($shipment_id);
	// dd($shipment_details);exit();	
	
	 $shipment_data = DB::table('shipment_line AS sl')
			->select('sl.*','p.item_uniq_id','p.product_name')
			->where('sl.shipment_id',$shipment_id )
			
			->leftJoin('products AS p', 
				function($join) {
					$join->on('p.id', '=', 'sl.item_id');
			})->get();	
	  $pdf = PDF::loadView('shipment/shipment_view_pdf',['shipment_data'=>$shipment_data,'shipment_number'=>$shipment_details->shipment_number]);
	  
	  if($pdftype=='sendemail'){
		    $destination_path = public_path('/pdf/shipment');
	        $pdf->save($destination_path.'/shipment_view.pdf'); 
		    $email='dummy@gmail.com';
			Mail::send('mail.refund_history', [], function ($message) use($email,$destination_path)
			{
				$message->to($email)->subject('Ukshop: Register as');
				$message->attach($destination_path.'/shipment_view.pdf');
			}); 
			
			Session::flash('title', 'Shipment History'); 
			Session::flash('success-toast-message', 'Shipment history pdf send via email');	
			return redirect('shipment/show');
	  }
	  else{
		   return $pdf->download('shipment_view.pdf'); 
	  }
	  
    }
	
	
	public function shipmentDetails($id)
	{
	   $shipments_details = DB::table('shipments')
	             ->select(DB::raw('*'))
				 ->where('shipments.id',$id)
				 ->get();
	   return $shipments_details[0];	
	}
	
}
