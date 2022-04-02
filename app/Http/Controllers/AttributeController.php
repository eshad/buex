<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\ProductAttribute;
use App\ProductAttributeLists;
use App\Product;
use App\Images;
use DB;
use Image;
use Auth;

class AttributeController extends Controller
{
   public function index()
   {
	   $attribute = DB::table('product_attributes')->orderBy('id', 'desc')->get();
	   
	   $attributes = array();
	   foreach($attribute as $attribute)
	   {
		   $ids = $attribute->id;
		   $check_cat_use_att = DB::select('select * from categories where find_in_set("'.$ids.'",category_attributes) <> 0');
			if(count($check_cat_use_att)>0)
			{
				$att_delete_status = 1;
			}else{
				$att_delete_status = 0;
			}
			
			$attributes[] = array(
				'id' => $attribute->id,
				'attribute_name' => $attribute->attribute_name,
				'type' => $attribute->type,
				'value' => $attribute->value,
				'att_delete_status' => $att_delete_status,
				);
	   }
	   
		return view('product_attribute/product_attribute_list',['attributes'=>$attributes]);
   }
   
    public function create()
    {
		return view('product_attribute/add_product_attribute');
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
			'attribute_name' => 'required|max:100',
			'attribute_type' => 'required|max:100',
		 ]);
		if ($validator->passes()) 
		{
			$att_type = $request->attribute_type;
			if($att_type == 'Text')
			{
				$value = $request->attribute_value_text;
			}else if($att_type == 'Number')
			{
				$value = $request->attribute_value_number;
			}else if($att_type == 'Yes/No')
			{
				$value = $request->attribute_value_yn;
			}else if($att_type == 'Dropdown')
			{
				$value = $request->radio;
			}
			
			$ProductAttribute = ProductAttribute::create([
				'attribute_name' => $request->attribute_name,
				'type' => $att_type,
				'value' => $value,
				'created_by' => $created_by,
				'user_account_id' => $user->id,
			]);
			$ProductAttribute->save();
			$last_insert_id = $ProductAttribute->id;
			for($i=0;$i<count($request->att_row_val);$i++)
			{
				$ProductAttributeLists = ProductAttributeLists::create([
				'product_attribute_id' => $last_insert_id,
				'list_value' => $request->att_row_val[$i],
				'created_by' => $created_by,
				]);
				$ProductAttributeLists->save();
			}
			
			Session::flash('title', 'Attribute Added Success'); 
			Session::flash('success-toast-message', 'Your Attribute is successfully Added');	
			return redirect('attribute');
	   }else{
		   return response()->json(['error'=>$validator->errors()->all()]);
	   }
    }
    public function show()
    {
		
    }
	
    public function edit($id)
    {
	   $product_attr =  DB::select('select pa.id,pa.attribute_name,pa.type,pa.value,pal.list_value from product_attributes as pa left join  product_attribute_lists as pal ON pal.product_attribute_id = pa.id where pa.id="'.$id.'"');
	   
	   return view('product_attribute/edit_product_attribute')->with('product_attr', $product_attr);
    }

    public function update_attribute(Request $request)
    {
		$user = Auth::user();
		if(Session::get('admin_id')){
			$updated_by = Session::get('admin_id');
		}else{
			$updated_by = $user->id;
		}
		
		$id = $request->edit_id;
		$validator = Validator::make($request->all(), [
			'attribute_name' => 'required|max:100',
			'attribute_type' => 'required|max:100',
		 ]);
		if ($validator->passes()) 
		{
			$att_type = $request->attribute_type;
			if($att_type == 'Text')
			{
				$value = $request->attribute_value_text;
			}else if($att_type == 'Number')
			{
				$value = $request->attribute_value_number;
			}else if($att_type == 'Yes/No')
			{
				$value = $request->attribute_value_yn;
			}else if($att_type == 'Dropdown')
			{
				$value = $request->radio;
			}
			$productattribute = ProductAttribute::find($id);
		    $productattribute->attribute_name = $request->attribute_name;
			$productattribute->type = $att_type;
			$productattribute->value = $value;
			$productattribute->updated_by = $updated_by;
			$productattribute->save();

			ProductAttributeLists::where('product_attribute_id',$id)->delete();
			
			for($i=0;$i<count($request->att_row_val);$i++)
			{
				if($request->att_row_val[0] != '')
				{
					$ProductAttributeLists = ProductAttributeLists::create([
					'product_attribute_id' => $id,
					'list_value' => $request->att_row_val[$i],
					'updated_by' => $updated_by,
					]);
					$ProductAttributeLists->save();
				}
			}
			
			Session::flash('title', 'Attribute Updated Success'); 
			Session::flash('success-toast-message', 'Your Attribute is successfully Updated');	
			return redirect('attribute');
	   }else{
		   return response()->json(['error'=>$validator->errors()->all()]);
	   }

    }
	
    public function destroy(Request $request,$id)
    {
	   ProductAttribute::where('id',$id)->delete();
       ProductAttributeLists::where('product_attribute_id',$id)->delete();
	   return view('product_attribute/product_attribute_list',['attributes'=>$attributes]);
    }
	
	

}
