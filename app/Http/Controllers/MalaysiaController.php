<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Category;
use App\Product;
use App\ProductStock;
use App\Images;
use DB;
use Image;
use Auth;

class MalaysiaController extends Controller
{
    public function index()
    {
		$final_result = array();
		$products = DB::select('select * from products order by id desc');
		foreach($products as $product)
		{
			$product_image = DB::select('select * from images where source_id="'.$product->id.'" and source_type="product"');
			
			$product_images = array();
			
			for($i=0;$i<count($product_image);$i++)
			{
				$product_images[] = $product_image[$i]->thumb_image_name;
			}
			for($i=0;$i<(6-(count($product_image)));$i++)
			{
				$product_images[] = '';
			}
			
			$final_result[] = array(
				   'id'=>$product->id,
			       'item_uniq_id'=>$product->item_uniq_id, 
				   'product_name'=>$product->product_name,
				   'malaysia_stock'=>$product->malaysia_stock,
				   'malaysia_sold_stock'=>$product->malaysia_sold_stock,
				   'image_name1'=>$product_images[0],
				   'image_name2'=>$product_images[1],
				   'image_name3'=>$product_images[2],
				   'image_name4'=>$product_images[3],
				   'image_name5'=>$product_images[4],
				   'image_name6'=>$product_images[5],
			);
		}
		return view('malaysia_stock/stock_detail',['products'=>$final_result]);
    }

    public function create()
    {
		$products = DB::select('select product_name,product_price,id from products');
		return view('malaysia_stock/update_stock',['product_list'=>$products]);
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
			'stock_place' =>  'required',
			'item' 	=>  'required',
			'new_stock' 	=>  'required|max:50',
		 ]);
		 
		$stock_place = $request->stock_place;
		$stock_item_id = $request->item;
		$old_stock = $request->old_stock;
		$new_stock = $request->new_stock;
		$increase_or_decrease = $request->incdec;
		$reasons = $request->reason;
		
		if($increase_or_decrease == 'increase')
		{
			$current_stocks = $old_stock+$new_stock;
		}else{
			$current_stocks = $old_stock-$new_stock;
		}

		if($increase_or_decrease == 'increase')	
		{
			$current_stock = $new_stock;
			$reason = '';
		}else
		{
			$current_stock = "-".$new_stock;
			$reason = $reasons;
		}

	   if ($validator->passes()) 
	   {
			if($stock_place == '1')
			{
				 $product = Product::find($stock_item_id);
				 $product->uk_stock = $current_stocks;;
				 
			}else{
				 $product = Product::find($stock_item_id);
				 $product->malaysia_stock = $current_stocks;;
			}
			$product->save();
			$lastInsertedId = $product->id;
			$ProductStock = ProductStock::create([
				'product_id' => $lastInsertedId,
				 'location_id' => $stock_place,
				 'quantity' => $current_stock,
				 'reason' => $reason,
				 'created_by' => $created_by,
				 'updated_by' => $created_by,
				 'user_account_id' => $user->id,
				 ]);
		$ProductStock->save();
		 Session::flash('title', 'Stock Updated Success'); 
		 Session::flash('success-toast-message', 'Your Stock is successfully Updated');	 
		return redirect('stock');
	   }else{
		   return response()->json(['error'=>$validator->errors()->all()]);
	   }
    }

    public function show()
    {
		
    }
	
    public function edit()
    {
      
    }

    public function update(Request $request,$id)
    {
		
    }
	
    public function destroy(Request $request,$id)
    {

    }
	
	public function ajax_category_name(Request $request)
    {
		
    }
	
	public function ajax_category_code(Request $request)
    {
		
    }
	
	public function ajax_product_stock(Request $request)
	{
		$product_id = $request->item_id;
		$stock = $request->stock_place;
		$category_images = DB::select('select pro.uk_stock,pro.malaysia_stock,img.image_name from products as pro left Join images as img ON img.source_id = "'.$product_id.'" where pro.id="'.$product_id.'" and img.source_type = "product"');
		$category = DB::select('select pro.uk_stock,pro.malaysia_stock,sum(ps.quantity)as quantity from products as pro left Join product_stocks as ps ON ps.product_id = "'.$product_id.'" where pro.id="'.$product_id.'" and ps.location_id ="'.$stock.'" ');
		return array_merge($category,$category_images);
	}

}
