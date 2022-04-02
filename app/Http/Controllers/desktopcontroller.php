<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Desktop;
use App\Shipment;

use DB;

use Auth;

class Desktopcontroller extends Controller
{
   public function view()
   {

     $desktop=DB::table('customers')
                 ->select( DB::raw('count(id) as total'))
                 ->get();
		
		
			
					$products = DB::select('select * from products order by id ');
		//dd($products);
		$total_uk=0;
		$total_ms=0;
		
		
		foreach($products as $product)
		{  
		
			$final_result[] = array(
			'uk_stock'=>$product->uk_stock,
			
			'malaysia_stock'=>$product->malaysia_stock,
			);
			$total_uk=$total_uk+$product->uk_stock;
			
			$total_ms=$total_ms+$product->malaysia_stock;
			//dd($total_ms);
//$total_item=$total_uk+$total_ms;
			
	
			$shipments = DB::table('shipments')->where('status',0)->get();
					     	$shipment_count=0; 
							
						 if(count($shipments)>0){
						
					   
                     
                        foreach($shipments as $shipment){
                          
						 $shipments_details =  DB::select("SELECT * FROM `shipment_line` WHERE `shipment_id` =$shipment->id and `item_id`='".$product->id."'");
						 if(count($shipments_details)>0){$ship_item_no=$shipments_details[0]->shipment_quantity;}else{$ship_item_no=0;}
						   $r_quantity=DB::select("SELECT if( sum(`order_items`.`ship_quantity`) IS NULL ,'0', sum(`order_items`.`ship_quantity`) ) as remain_quantity  FROM `order_items` JOIN `orders` ON `orders`.`id`=`order_items`.`order_id` and `orders`.`is_cancel` ='0' WHERE `product_id`='".$product->id."' and `shipment_id`='".$shipment->id."'");
						   		
				$item_quantity =  DB::select("SELECT sum(`pending_quantity`) as `pending_quantity` FROM `shipment_line` WHERE `item_id`='".$product->id."'");
						if($item_quantity[0]->pending_quantity!=''){$item_no=$item_quantity[0]->pending_quantity;}else{$item_no='0';}
		  						 //dd($r_quantity);


		  
		     $ship_item_no=$ship_item_no - $r_quantity[0]->remain_quantity;
			//echo $ship_item_no;
			 $shipment_count+=$ship_item_no; 
			  //$shipment_count;
			// $shipment_count=0;
			 //$item_no=0;
			  $total_uk=$total_uk+$ship_item_no+$item_no;
			
//dd($total_item);			 

			 //$product_total_stock=$ship_item_no;
//dd($shipment_count);
						}	
						 }
						  $total_item=$total_uk+$total_ms;
			
		}
		
		
		
// $products=DB::table('products')
                 // ->select( DB::raw('SUM(uk_stock) as uk'))
				  
					// ->orderBy('id', 'desc')
					// ->get();
					
			// $product=DB::table('products')
                 // ->select( DB::raw('SUM(malaysia_stock) as ms'))
				  
					// ->orderBy('id', 'desc')
					



				
				$abc = DB::table('orders')
				->select(DB::raw('SUM(order_total) as ord'))
				->whereMonth('created_at', '=', date('m'))
				  //->where('MONTH(fecha)', '=', '06')

				->where('is_cancel', '=', 0)
				->where('is_done', '=', 0)
				->get();
				
				$pqr = DB::table('orders')
				->select(DB::raw('count(id) as total_ord'))
				->whereMonth('created_at', '=', date('m'))
				->where('is_cancel', '=', 0)
				->where('is_done', '=', 0)
				->get();
			$dis=DB::table('dispatch_orders')
                 ->select( DB::raw('count(id) as record'))
                 ->get();
		$var= DB::table('shipments')
				->where('status',0)
				->get();	
				if(isset($total_item)){
					$abnc = $total_item;
				}else{
					$abnc = $total_uk+$total_ms;	
				}
				//dd($abnc);
					
				
						
                 	
		return view('dashboard', compact('desktop','dash','abnc','abc','dis','pqr','products','var'));
			   		
   
   }
}