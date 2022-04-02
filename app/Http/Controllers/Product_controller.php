<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Product;
use App\ProductStock;
use App\ProductList;
use App\Images;
use DB;
use Image;
use Auth;

class Product_controller extends Controller
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
				   'category_id'=>$product->category_id,
				   'product_name'=>$product->product_name,
				   'product_note'=>$product->product_note,
				   'stock_place'=>$product->stock_place,
				   'product_price'=>$product->product_price,
				   'installment_cost'=>$product->installment_cost,
				   'sm_cost'=>$product->sm_cost,
				   'ss_cost'=>$product->ss_cost,
				   'air_freight_cost'=>$product->air_freight_cost,
				   'initial_stock'=>$product->initial_stock,
				   'uk_stock'=>$product->uk_stock,
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
		return view('product/product_detail',['products'=>$final_result]);
    }

    public function create()
    {
		$category = DB::table('categories')
		->select('categories.id','categories.category_name','categories.category_code')
		->get();
		return view('product/add_product',['category_detail'=>$category]);
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
			'item_category' =>  'required',
			'product_name' 	=>  'required|max:30',
			'stock_place' 	=>  'required|max:18',
			'product_price' =>  'required|max:18',
			'price' =>  'required|max:18',
			'local_postage_price' => 'max:18',
			'local_postage' =>  'max:30',
			'airfreight' =>  'required|max:30',
			'initial_stock' =>  'required|max:30',
			'image_2' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			'image_3' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			'image_4' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			'image_5' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			'image_6' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			'image_7' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
		 ]);
		  
		$image_1_name='';$image_2_name='';$image_3_name='';$image_4_name='';$image_5_name='';$image_6_name='';
		
		$image_1 = $request->file('image_2');
		$image_2 = $request->file('image_3');
		$image_3 = $request->file('image_4');
		$image_4 = $request->file('image_5');
		$image_5 = $request->file('image_6');
		$image_6 = $request->file('image_7');
	
	   if($validator->passes()) 
	   {
		   $stock = $request->initial_stock;
		   $stock_pla = $request->stock_place;
		   if($stock_pla == '1')
		   {
			   $uk_stock = $stock;
			   $malaysia_stock = 0;
		   }else{
			   $uk_stock = 0;
			   $malaysia_stock = $stock;
		   }
		  
			$Product = Product::create([
			   'item_uniq_id' => $request->final_item_id,
				'category_id' => $request->item_category,
				'product_name' => $request->product_name,
				'product_note' => $request->product_note,
				'stock_place' => $request->stock_place,
				'product_price' => $request->product_price,
				'installment_cost' => $request->price,
				'sm_cost' => $request->local_postage_price,
				'ss_cost' => $request->local_postage,
				'air_freight_cost' => $request->airfreight,
				'initial_stock' => $stock,
				'uk_stock' => $uk_stock,
				'malaysia_stock' => $malaysia_stock,
				'created_by' => $created_by,
				'user_account_id' => $user->id,
			]);

			$destinationPaththumb = public_path('/product_image/thumbnail_images');
			$destinationPath = public_path('/product_image/normal_images');
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
			if($image_2)
			{
				try
				{
					$canvas2 = Image::canvas(245, 245);
					$image_2_name = time().'_'.$image_2->getClientOriginalName();
					$thumb_img2  = Image::make($image_2->getRealPath())->resize(245, 245, function($constraint)
					{
						$constraint->aspectRatio();
					});
					$canvas2->insert($thumb_img2, 'center');
					$canvas2->save($destinationPaththumb.'/'.$image_2_name);
					$image_2->move($destinationPath, $image_2_name);
				}catch (\Exception $e)
				{
					return Redirect::back()->withErrors(['Image file not readable']);
				}
			}
			
			if($image_3)
			{
				try
				{
					$canvas3 = Image::canvas(245, 245);
					$image_3_name = time().'_'.$image_3->getClientOriginalName();
					$thumb_img3  = Image::make($image_3->getRealPath())->resize(245, 245, function($constraint)
					{
						$constraint->aspectRatio();
					});
					$canvas3->insert($thumb_img3, 'center');
					$canvas3->save($destinationPaththumb.'/'.$image_3_name);
					$image_3->move($destinationPath, $image_3_name);
				}catch (\Exception $e)
				{
					return Redirect::back()->withErrors(['Image file not readable']);
				}
			}
			
			if($image_4)
			{
				try
				{
					$canvas4 = Image::canvas(245, 245);
					$image_4_name = time().'_'.$image_4->getClientOriginalName();
					$thumb_img4  = Image::make($image_4->getRealPath())->resize(245, 245, function($constraint)
					{
						$constraint->aspectRatio();
					});
					$canvas4->insert($thumb_img4, 'center');
					$canvas4->save($destinationPaththumb.'/'.$image_4_name);
					$image_4->move($destinationPath, $image_4_name);
				}catch (\Exception $e)
				{
					return Redirect::back()->withErrors(['Image file not readable']);
				}
			}
			
			if($image_5)
			{
				try
				{
					$canvas5 = Image::canvas(245, 245);
					$image_5_name = time().'_'.$image_5->getClientOriginalName();
					$thumb_img5  = Image::make($image_5->getRealPath())->resize(245, 245, function($constraint)
					{
						$constraint->aspectRatio();
					});
					$canvas5->insert($thumb_img5, 'center');
					$canvas5->save($destinationPaththumb.'/'.$image_5_name);
					$image_5->move($destinationPath, $image_5_name);
				}catch (\Exception $e)
				{
					return Redirect::back()->withErrors(['Image file not readable']);
				}
			}
			
			if($image_6)
			{
				try
				{
					$canvas6 = Image::canvas(245, 245);
					$image_6_name = time().'_'.$image_6->getClientOriginalName();
					$thumb_img6  = Image::make($image_6->getRealPath())->resize(245, 245, function($constraint)
					{
						$constraint->aspectRatio();
					});
					$canvas6->insert($thumb_img6, 'center');
					$canvas6->save($destinationPaththumb.'/'.$image_6_name);
					$image_6->move($destinationPath, $image_6_name);
				}catch (\Exception $e)
				{
					return Redirect::back()->withErrors(['Image file not readable']);
				}
			}
			
			$Product->save();
			$lastInsertedId = $Product->id;
			
			for($m=0;$m<count($request->att_name);$m++)
			{
				$ProductList = ProductList::create([
				 'product_id' => $lastInsertedId,
			   'attr_id' => $request->att_id[$m],
				'att_name' => $request->att_name[$m],
				'att_value' => $request->att_value[$m],
				'created_by' => $created_by,
				]);
				$ProductList->save();
			}
			
			$ProductStock = ProductStock::create([
			   'product_id' => $lastInsertedId,
				'location_id' => $stock_pla,
				'quantity' => $stock,
				'reason' => '',
				'created_by' => $created_by,
				]);
			$ProductStock->save();
			
			if($image_1_name != '')
			{
				$image_save = Images::create([
					'image_name' => $image_1_name,
					'thumb_image_name' => $image_1_name,
					'source_type' => 'product',
					'source_id' => $lastInsertedId,
					'created_by' => $created_by,
				]);
				$image_save->save();
			}
			
			if($image_2_name != '')
			{
				$image_save = Images::create([
				'image_name' => $image_2_name,
				'thumb_image_name' => $image_2_name,
				'source_type' => 'product',
				'source_id' => $lastInsertedId,
				'created_by' => $created_by,
				]);
				$image_save->save();
			}
			
			if($image_3_name != '')
			{
				$image_save = Images::create([
				'image_name' => $image_3_name,
				'thumb_image_name' => $image_3_name,
				'source_type' => 'product',
				'source_id' => $lastInsertedId,
				'created_by' => $created_by,
				]);
				$image_save->save();
			}
			
			if($image_4_name != '')
			{
				$image_save = Images::create([
				'image_name' => $image_4_name,
				'thumb_image_name' => $image_4_name,
				'source_type' => 'product',
				'source_id' => $lastInsertedId,
				'created_by' => $created_by,
				]);
				$image_save->save();
			}
			
			if($image_5_name != '')
			{
				$image_save = Images::create([
				'image_name' => $image_5_name,
				'thumb_image_name' => $image_5_name,
				'source_type' => 'product',
				'source_id' => $lastInsertedId,
				'created_by' => $created_by,
				]);
				$image_save->save();
			}
			
			if($image_6_name != '')
			{
				$image_save = Images::create([
				'image_name' => $image_6_name,
				'thumb_image_name' => $image_6_name,
				'source_type' => 'product',
				'source_id' => $lastInsertedId,
				'created_by' => $created_by,
				]);
				$image_save->save();
			}
			
		
			Session::flash('title', 'Product Added Success'); 
			Session::flash('success-toast-message', 'Your Product is successfully Added');	
			return redirect('product');
	   }else{
		   return response()->json(['error'=>$validator->errors()->all()]);
	   }
		
    }

    public function show($id)
    {
	   $products =  DB::select('select pro.* from products as pro where pro.id="'.$id.'"');
	   
	   $products_lists = DB::select('select pl.*,pa.type from product_lists as pl left join product_attributes as pa ON pa.id = pl.attr_id where pl.product_id="'. $products[0]->id.'"');
	   
	   $products_list = array();
	   for($i=0;$i<count($products_lists);$i++)
	   {
		   $project_list_id = $products_lists[$i]->product_id;
		   $attr_id = $products_lists[$i]->attr_id;
		   $att_name = $products_lists[$i]->att_name;
		   $att_value = $products_lists[$i]->att_value;
		   $att_type = $products_lists[$i]->type;
		   if($att_type != 'Dropdown')
		   {
			   $products_list['data'][] = array(
					'project_list_id' => $project_list_id,
					'attr_id' => $attr_id,
					'att_name' => $att_name,
					'att_value' => $att_value,
					'att_type' => $att_type,
			   );
		   }else{
			   $products_list['data'][] = array(
					'project_list_id' => $project_list_id,
					'attr_id' => $attr_id,
					'att_name' => $att_name,
					'att_value' => $att_value,
					'att_type' => $att_type,
			   );
			   $attr_list = DB::select('select pal.* from product_attribute_lists as pal where pal.product_attribute_id="'.$attr_id.'"');
			   
			   for($j=0;$j<count($attr_list);$j++)
			   {
				  $products_list['data'][$i][$j] = array(
					'list_value' => $attr_list[$j]->list_value,
					); 
			   }
			   
		   }
	   }
	  
	   // echo '</pre>'; dd($products_list);
	   
	   $products_image =  DB::select('select * from images where source_type="product" and source_id = "'.$id.'"');
	
	   $category = DB::table('categories')
		->select('categories.id','categories.category_name','categories.category_code')
		->get();
		
	   return view('product/view_product')->with('edit_products', $products)->with('category_detail', $category)->with('product_image', $products_image)->with('products_list', $products_list);

    }
	
    public function edit($id)
    {

       $products =  DB::select('select pro.* from products as pro where pro.id="'.$id.'"');
	   
	   $products_lists = DB::select('select pl.*,pa.type from product_lists as pl left join product_attributes as pa ON pa.id = pl.attr_id where pl.product_id="'. $products[0]->id.'"');
	   
	   $products_list = array();
	   for($i=0;$i<count($products_lists);$i++)
	   {
		   $project_list_id = $products_lists[$i]->product_id;
		   $attr_id = $products_lists[$i]->attr_id;
		   $att_name = $products_lists[$i]->att_name;
		   $att_value = $products_lists[$i]->att_value;
		   $att_type = $products_lists[$i]->type;
		   if($att_type != 'Dropdown')
		   {
			   $products_list['data'][] = array(
					'project_list_id' => $project_list_id,
					'attr_id' => $attr_id,
					'att_name' => $att_name,
					'att_value' => $att_value,
					'att_type' => $att_type,
			   );
		   }else{
			   $products_list['data'][] = array(
					'project_list_id' => $project_list_id,
					'attr_id' => $attr_id,
					'att_name' => $att_name,
					'att_value' => $att_value,
					'att_type' => $att_type,
			   );
			   $attr_list = DB::select('select pal.* from product_attribute_lists as pal where pal.product_attribute_id="'.$attr_id.'"');
			   
			   for($j=0;$j<count($attr_list);$j++)
			   {
				  $products_list['data'][$i][$j] = array(
					'list_value' => $attr_list[$j]->list_value,
					); 
			   }
			   
		   }
	   }
	  
	   // echo '</pre>'; dd($products_list);
	   
	   $products_image =  DB::select('select * from images where source_type="product" and source_id = "'.$id.'"');
	
	   $category = DB::table('categories')
		->select('categories.id','categories.category_name','categories.category_code')
		->get();
		
	   return view('product/edit_product')->with('edit_products', $products)->with('category_detail', $category)->with('product_image', $products_image)->with('products_list', $products_list);
    }

    public function update(Request $request)
    {
		$user = Auth::user();
		if(Session::get('admin_id')){
			$updated_by = Session::get('admin_id');
		}else{
			$updated_by = $user->id;
		}
		
		$form_id = $request->form_id;
		
		$validator = Validator::make($request->all(), [
			'item_category' =>  'required',
			'product_name' 	=>  'required|max:30',
			'product_price' =>  'required|max:18',
			'price' =>  'required|max:18',
			'airfreight' =>  'required|max:30',
			'uk_stock' =>  'required|max:30',
			'malaysia_stock' =>  'required|max:30',
			'image_2' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			'image_3' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			'image_4' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			'image_5' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			'image_6' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			'image_7' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
		 ]);
		
		$image_1_name='';$image_2_name='';$image_3_name='';$image_4_name='';$image_5_name='';$image_6_name='';
		
		$image_1 = $request->file('image_2');
		$image_2 = $request->file('image_3');
		$image_3 = $request->file('image_4');
		$image_4 = $request->file('image_5');
		$image_5 = $request->file('image_6');
		$image_6 = $request->file('image_7');
		
		$old_img_name1 = $request->checkimage2;$old_img_status1 = $request->checkimg2;
		$old_img_name2 = $request->checkimage3;$old_img_status2 = $request->checkimg3;
		$old_img_name3 = $request->checkimage4;$old_img_status3= $request->checkimg4;
		$old_img_name4 = $request->checkimage5;$old_img_status4 = $request->checkimg5;
		$old_img_name5 = $request->checkimage6;$old_img_status5 = $request->checkimg6;
		$old_img_name6 = $request->checkimage7;$old_img_status6 = $request->checkimg7;
			  
	   if($validator->passes()) 
	   {
		    $product = Product::find($form_id);
		    $product->item_uniq_id = $request->final_item_id;
			$product->category_id = $request->item_category;
			$product->product_name = $request->product_name;
			$product->product_note = $request->product_note;
			$product->product_price = $request->product_price;
			$product->installment_cost = $request->price;
			$product->sm_cost = $request->local_postage_price;
			$product->ss_cost = $request->local_postage;
			$product->air_freight_cost = $request->airfreight;
			$product->uk_stock = $request->uk_stock;
			$product->malaysia_stock = $request->malaysia_stock;
			$product->updated_by = $updated_by;
			
			$destinationPaththumb = public_path('/product_image/thumbnail_images');
			$destinationPath = public_path('/product_image/normal_images');
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
				if($old_img_status1 == 1)
				{
					if($old_img_name1 != "")
					{
						$images1 =  DB::select('select thumb_image_name,id from images where source_type="product" and source_id="'.$form_id.'" and image_name="'.$old_img_name1.'"');
						for($i=0;$i<count($images1);$i++)
						{
							$img = $images1[$i]->thumb_image_name;
							$id = $images1[$i]->id;
							unlink('./public/product_image/normal_images/'.$img);
							unlink('./public/product_image/thumbnail_images/'.$img);
							DB::delete('delete from images where source_type="product" and source_id="'.$form_id.'" and id="'.$id.'"');
						}
					}
				}
			}else{
				if($old_img_status1 == 0)
				{
					if($old_img_name1 != "")
					{
						$images1 =  DB::select('select thumb_image_name,id from images where source_type="product" and source_id="'.$form_id.'" and image_name="'.$old_img_name1.'"');
						for($i=0;$i<count($images1);$i++)
						{
							$img = $images1[$i]->thumb_image_name;
							$id = $images1[$i]->id;
							unlink('./public/product_image/normal_images/'.$img);
							unlink('./public/product_image/thumbnail_images/'.$img);
							DB::delete('delete from images where source_type="product" and source_id="'.$form_id.'" and id="'.$id.'"');
						}
					}
				}
			}
			 
			if($image_2)
			{
				try
				{
					$canvas2 = Image::canvas(245, 245);
					$image_2_name = time().'_'.$image_2->getClientOriginalName();
					$thumb_img2  = Image::make($image_2->getRealPath())->resize(245, 245, function($constraint)
					{
						$constraint->aspectRatio();
					});
					$canvas2->insert($thumb_img2, 'center');
					$canvas2->save($destinationPaththumb.'/'.$image_2_name);
					$image_2->move($destinationPath, $image_2_name);
				}catch (\Exception $e)
				{
					return Redirect::back()->withErrors(['Image file not readable']);
				}
				if($old_img_status2 == 1)
				{
					if($old_img_name2 != "")
					{
						$images2 =  DB::select('select thumb_image_name,id from images where source_type="product" and source_id="'.$form_id.'" and image_name="'.$old_img_name2.'"');
						for($i=0;$i<count($images2);$i++)
						{
							$img = $images2[$i]->thumb_image_name;
							$id = $images2[$i]->id;
							unlink('./public/product_image/normal_images/'.$img);
							unlink('./public/product_image/thumbnail_images/'.$img);
							DB::delete('delete from images where source_type="product" and source_id="'.$form_id.'" and id="'.$id.'"');
						}
					}
				}
			}else{
				if($old_img_status2 == 0)
				{
					if($old_img_name2 != "")
					{
						$images2 =  DB::select('select thumb_image_name,id from images where source_type="product" and source_id="'.$form_id.'" and image_name="'.$old_img_name2.'"');
						for($i=0;$i<count($images2);$i++)
						{
							$img = $images2[$i]->thumb_image_name;
							$id = $images2[$i]->id;
							unlink('./public/product_image/normal_images/'.$img);
							unlink('./public/product_image/thumbnail_images/'.$img);
							DB::delete('delete from images where source_type="product" and source_id="'.$form_id.'" and id="'.$id.'"');
						}
					}
				}
			}
			
			if($image_3)
			{
				try
				{
					$canvas3 = Image::canvas(245, 245);
					$image_3_name = time().'_'.$image_3->getClientOriginalName();
					$thumb_img3  = Image::make($image_3->getRealPath())->resize(245, 245, function($constraint)
					{
						$constraint->aspectRatio();
					});
					$canvas3->insert($thumb_img3, 'center');
					$canvas3->save($destinationPaththumb.'/'.$image_3_name);
					$image_3->move($destinationPath, $image_3_name);
				}catch (\Exception $e)
				{
					return Redirect::back()->withErrors(['Image file not readable']);
				}
				if($old_img_status3 == 1)
				{
					if($old_img_name3 != "")
					{
						$images3 =  DB::select('select thumb_image_name,id from images where source_type="product" and source_id="'.$form_id.'" and image_name="'.$old_img_name3.'"');
						for($i=0;$i<count($images3);$i++)
						{
							$img = $images3[$i]->thumb_image_name;
							$id = $images3[$i]->id;
							unlink('./public/product_image/normal_images/'.$img);
							unlink('./public/product_image/thumbnail_images/'.$img);
							DB::delete('delete from images where source_type="product" and source_id="'.$form_id.'" and id="'.$id.'"');
						}
					}
				}
			}else{
				if($old_img_status3 == 0)
				{
					if($old_img_name3 != "")
					{
						$images3 =  DB::select('select thumb_image_name,id from images where source_type="product" and source_id="'.$form_id.'" and image_name="'.$old_img_name3.'"');
						for($i=0;$i<count($images3);$i++)
						{
							$img = $images3[$i]->thumb_image_name;
							$id = $images3[$i]->id;
							unlink('./public/product_image/normal_images/'.$img);
							unlink('./public/product_image/thumbnail_images/'.$img);
							DB::delete('delete from images where source_type="product" and source_id="'.$form_id.'" and id="'.$id.'"');
						}
					}
				}
			}
			
			if($image_4)
			{
				try
				{
					$canvas4 = Image::canvas(245, 245);
					$image_4_name = time().'_'.$image_4->getClientOriginalName();
					$thumb_img4  = Image::make($image_4->getRealPath())->resize(245, 245, function($constraint)
					{
						$constraint->aspectRatio();
					});
					$canvas4->insert($thumb_img4, 'center');
					$canvas4->save($destinationPaththumb.'/'.$image_4_name);
					$image_4->move($destinationPath, $image_4_name);
				}catch (\Exception $e)
				{
					return Redirect::back()->withErrors(['Image file not readable']);
				}
				if($old_img_status4 == 1)
				{
					if($old_img_name4 != "")
					{
						$images4 =  DB::select('select thumb_image_name,id from images where source_type="product" and source_id="'.$form_id.'" and image_name="'.$old_img_name4.'"');
						for($i=0;$i<count($images4);$i++)
						{
							$img = $images4[$i]->thumb_image_name;
							$id = $images4[$i]->id;
							unlink('./public/product_image/normal_images/'.$img);
							unlink('./public/product_image/thumbnail_images/'.$img);
							DB::delete('delete from images where source_type="product" and source_id="'.$form_id.'" and id="'.$id.'"');
						}
					}
				}
			}else{
				if($old_img_status4 == 0)
				{
					if($old_img_name4 != "")
					{
						$images4 =  DB::select('select thumb_image_name,id from images where source_type="product" and source_id="'.$form_id.'" and image_name="'.$old_img_name4.'"');
						for($i=0;$i<count($images4);$i++)
						{
							$img = $images4[$i]->thumb_image_name;
							$id = $images4[$i]->id;
							unlink('./public/product_image/normal_images/'.$img);
							unlink('./public/product_image/thumbnail_images/'.$img);
							DB::delete('delete from images where source_type="product" and source_id="'.$form_id.'" and id="'.$id.'"');
						}
					}
				}
			}
			
			if($image_5)
			{
				try
				{
					$canvas5 = Image::canvas(245, 245);
					$image_5_name = time().'_'.$image_5->getClientOriginalName();
					$thumb_img5  = Image::make($image_5->getRealPath())->resize(245, 245, function($constraint)
					{
						$constraint->aspectRatio();
					});
					$canvas5->insert($thumb_img5, 'center');
					$canvas5->save($destinationPaththumb.'/'.$image_5_name);
					$image_5->move($destinationPath, $image_5_name);
				}catch (\Exception $e)
				{
					return Redirect::back()->withErrors(['Image file not readable']);
				}
				if($old_img_status5 == 1)
				{
					if($old_img_name5 != "")
					{
						$images5 =  DB::select('select thumb_image_name,id from images where source_type="product" and source_id="'.$form_id.'" and image_name="'.$old_img_name5.'"');
						for($i=0;$i<count($images5);$i++)
						{
							$img = $images5[$i]->thumb_image_name;
							$id = $images5[$i]->id;
							unlink('./public/product_image/normal_images/'.$img);
							unlink('./public/product_image/thumbnail_images/'.$img);
							DB::delete('delete from images where source_type="product" and source_id="'.$form_id.'" and id="'.$id.'"');
						}
					}
				}
			}else{
				if($old_img_status5 == 0)
				{
					if($old_img_name5 != "")
					{
						$images5 =  DB::select('select thumb_image_name,id from images where source_type="product" and source_id="'.$form_id.'" and image_name="'.$old_img_name5.'"');
						for($i=0;$i<count($images5);$i++)
						{
							$img = $images5[$i]->thumb_image_name;
							$id = $images5[$i]->id;
							unlink('./public/product_image/normal_images/'.$img);
							unlink('./public/product_image/thumbnail_images/'.$img);
							DB::delete('delete from images where source_type="product" and source_id="'.$form_id.'" and id="'.$id.'"');
						}
					}
				}
			}
			
			if($image_6)
			{
				try
				{
					$canvas6 = Image::canvas(245, 245);
					$image_6_name = time().'_'.$image_6->getClientOriginalName();
					$thumb_img6  = Image::make($image_6->getRealPath())->resize(245, 245, function($constraint)
					{
						$constraint->aspectRatio();
					});
					$canvas6->insert($thumb_img6, 'center');
					$canvas6->save($destinationPaththumb.'/'.$image_6_name);
					$image_6->move($destinationPath, $image_6_name);
				}catch (\Exception $e)
				{
					return Redirect::back()->withErrors(['Image file not readable']);
				}
				if($old_img_status6 == 1)
				{
					if($old_img_name6 != "")
					{
						$images6 =  DB::select('select thumb_image_name,id from images where source_type="product" and source_id="'.$form_id.'" and image_name="'.$old_img_name6.'"');
						for($i=0;$i<count($images6);$i++)
						{
							$img = $images6[$i]->thumb_image_name;
							$id = $images6[$i]->id;
							unlink('./public/product_image/normal_images/'.$img);
							unlink('./public/product_image/thumbnail_images/'.$img);
							DB::delete('delete from images where source_type="product" and source_id="'.$form_id.'" and id="'.$id.'"');
						}
					}
				}
			}else{
				if($old_img_status6 == 0)
				{
					if($old_img_name6 != "")
					{
						$images6 =  DB::select('select thumb_image_name,id from images where source_type="product" and source_id="'.$form_id.'" and image_name="'.$old_img_name6.'"');
						for($i=0;$i<count($images6);$i++)
						{
							$img = $images6[$i]->thumb_image_name;
							$id = $images6[$i]->id;
							unlink('./public/product_image/normal_images/'.$img);
							unlink('./public/product_image/thumbnail_images/'.$img);
							DB::delete('delete from images where source_type="product" and source_id="'.$form_id.'" and id="'.$id.'"');
						}
					}
				}
			}

			$product->save();
			$lastInsertedId = $product->id;
			
			ProductList::where('product_id', $lastInsertedId)->delete();
			for($m=0;$m<count($request->att_name);$m++)
			{
				$ProductList = ProductList::create([
				'product_id' => $lastInsertedId,
			    'attr_id' => $request->att_id[$m],
				'att_name' => $request->att_name[$m],
				'att_value' => $request->att_value[$m],
				'updated_by' => $updated_by,
				]);
				$ProductList->save();
			}

			if($image_1_name != '')
			{
				$image_save = Images::create([
					'image_name' => $image_1_name,
					'thumb_image_name' => $image_1_name,
					'source_type' => 'product',
					'source_id' => $lastInsertedId,
					'updated_by' => $updated_by,
				]);
				$image_save->save();
			}
			
			if($image_2_name != '')
			{
				$image_save = Images::create([
				'image_name' => $image_2_name,
				'thumb_image_name' => $image_2_name,
				'source_type' => 'product',
				'source_id' => $lastInsertedId,
				'updated_by' => $updated_by,
				]);
				$image_save->save();
			}
			
			if($image_3_name != '')
			{
				$image_save = Images::create([
				'image_name' => $image_3_name,
				'thumb_image_name' => $image_3_name,
				'source_type' => 'product',
				'source_id' => $lastInsertedId,
				'updated_by' => $updated_by,
				]);
				$image_save->save();
			}
			
			if($image_4_name != '')
			{
				$image_save = Images::create([
				'image_name' => $image_4_name,
				'thumb_image_name' => $image_4_name,
				'source_type' => 'product',
				'source_id' => $lastInsertedId,
				'updated_by' => $updated_by,
				]);
				$image_save->save();
			}
			
			if($image_5_name != '')
			{
				$image_save = Images::create([
				'image_name' => $image_5_name,
				'thumb_image_name' => $image_5_name,
				'source_type' => 'product',
				'source_id' => $lastInsertedId,
				'updated_by' => $updated_by,
				]);
				$image_save->save();
			}
			
			if($image_6_name != '')
			{
				$image_save = Images::create([
				'image_name' => $image_6_name,
				'thumb_image_name' => $image_6_name,
				'source_type' => 'product',
				'source_id' => $lastInsertedId,
				'updated_by' => $updated_by,
				]);
				$image_save->save();
			}
			
		
			Session::flash('title', 'Product Updated Success'); 
			Session::flash('success-toast-message', 'Your Product is successfully Updated');	
			return redirect('product');
	   }else{
		   return response()->json(['error'=>$validator->errors()->all()]);
	   }
    }
	
    public function destroy(Request $request,$id)
    {
		Product::find($id)->delete();
		Images::where('source_id', $id)->where('source_type','product')->delete();
		Session::flash('title', 'Product Deleted Success'); 
		Session::flash('success-toast-message', 'Your Product is successfully Deleted');		
		return redirect('product');
    }
	public function ajax_category_name(Request $request)
	{
		
		$category_id = $request->cat_id;
		
		$category_sql = DB::select('select * from  categories where id="'.$category_id.'"');
		
		$take_cat_attrs = $category_sql[0]->category_attributes;
	
		$take_cat_attr = 0;
		if($take_cat_attrs != '')
		{
			$take_cat_attr = explode(',',$take_cat_attrs);
		}
		$product_att_data = array();
		if($take_cat_attr != 0 )
		{
			if(count($take_cat_attr) > 0)
			{
				for($i=0;$i<count($take_cat_attr);$i++)
				{
					$attr_id = $take_cat_attr[$i];
					$atrr_sql = DB::select('select * from product_attributes where id="'.$attr_id.'"');
					$attr_id = $atrr_sql[0]->id;
					$attr_name = $atrr_sql[0]->attribute_name;
					$attr_type = $atrr_sql[0]->type;
					$attr_value = $atrr_sql[0]->value;
					
					if($attr_type != 'Dropdown')
					{
						$product_att_data['dat'][] = array(
							'attr_id' => $attr_id,
							'attr_name' => $attr_name,
							'attr_type' => $attr_type,
							'attr_value' => $attr_value,
						);
					}else{
						$product_att_data['dat'][] = array(
							'attr_id' => $attr_id,
							'attr_name' => $attr_name,
							'attr_type' => $attr_type,
							'attr_value' => $attr_value,
						);
						
						$atrr_list_sql = DB::select('select * from product_attribute_lists where product_attribute_id="'.$attr_id.'"');
						
						for($j=0;$j<count($atrr_list_sql);$j++)
						{
							$product_attribute_id = $atrr_list_sql[$j]->product_attribute_id;
							$list_value = $atrr_list_sql[$j]->list_value;
							
							$product_att_data['dat'][$i][$j] = array(
							'product_attribute_id' => $product_attribute_id,
							'list_value' => $list_value,
							);
						}
					}
				}
			}
		}
		
		// $category_id = $request->cat_id;
		$category = DB::select('select * from products where category_id="'.$category_id.'"');
		$count_cats = count($category);
		$count_cat[] = array(
			'count_cats' => $count_cats,
		);
		$data = array('product_att_data' => $product_att_data, 'count'=>$count_cat);
		return json_encode($data);
	}
	
	public function ajax_take_images(Request $request)
	{
		$product_id = $request->product_id;
		$product_image = DB::select('select image_name from images where source_id="'.$product_id.'" and source_type="product"');
		echo json_encode($product_image);
	}
	
	
	
}
