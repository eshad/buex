<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Category;
use App\Product;
use App\Images;
use DB;
use Image;
use Auth;

class Category_controller extends Controller
{
    public function index()
    {
		$categories = DB::table('categories')
		->select('categories.id','categories.category_name','categories.category_code')
		->orderBy('categories.id', 'desc')
		->get();
		
		$category = array();
		foreach($categories as $cat)
		{
			$check_subcategory = Product::where('category_id',$cat->id)->first();
			if(count($check_subcategory)>0){
				$product_status = 0;
			}else{
				$product_status = 1;
			}
			$category[] = array(
			'id' => $cat->id,
			'category_name' => $cat->category_name,
			'category_code' => $cat->category_code,
			'product_status' => $product_status,
			);
		}
		return view('product_catgeory/category_amendment',['category'=>$category]);
    }

    public function create()
    {
		 $attributes = DB::table('product_attributes')->get();
		return view('product_catgeory/product_category',['attributes'=>$attributes]);
    }
    
    public function store(Request $request)
    {
		$user = Auth::user();
		if(Session::get('admin_id')){
			$created_by = Session::get('admin_id');
		}else{
			$created_by = $user->id;
		}
		
		$arr_id = array();
		for($i=0;$i<count($request->attr_id);$i++)
		{
			$arr_id[] = $request->attr_id[$i];
		}
		$attribute_id1 = implode(',',$arr_id); 
		$attribute_id = str_replace(array( '"', '"' ), '', $attribute_id1);

		$validator = Validator::make($request->all(), [
			'category_name' => 'required|max:100',
			'category_code' => 'required|max:100',
		 ]);
		
	   if ($validator->passes()) 
	   {
			$Category = Category::create([
				'category_name' => $request->category_name,
				'category_code' => $request->category_code,
				'category_attributes' => $attribute_id,
				'created_by' => $created_by,
				'user_account_id' => $user->id,
			]);
			$Category->save();
			$lastInsertedId = $Category->id;

			Session::flash('title', 'Category Added Success'); 
			Session::flash('success-toast-message', 'Your Category is successfully Added');	
			return redirect('category');
	   }else{
		   return response()->json(['error'=>$validator->errors()->all()]);
	   }
   }

    public function show()
    {
		dd('show');
    }
	
    public function edit(Request $request,$id)
    {
        $edit_attr_data =  DB::select('select * from categories where id="'.$id.'"');
		$attributes = DB::table('product_attributes')->get();
		$att_data = DB::table('product_attributes')->get();
	    return view('product_catgeory/edit_product_category')->with('edit_attr_data', $edit_attr_data)->with('attributes', $attributes)->with('att_data', $att_data);
    }

    public function update(Request $request)
    {
		$user = Auth::user();
		if(Session::get('admin_id')){
			$updated_by = Session::get('admin_id');
		}else{
			$updated_by = $user->id;
		}
		
		$arr_id = array();
		for($i=0;$i<count($request->attr_id);$i++)
		{
			$arr_id[] = $request->attr_id[$i];
		}
		$attribute_id2 = implode(',',$arr_id); 
		$attribute_id = str_replace(array( '"', '"' ), '', $attribute_id2);
		
		$validator = Validator::make($request->all(), [
			'cat_name_show' => 'required|max:100',
			'cat_code_show' => 'required|max:100',
		 ]);

	   if ($validator->passes()) 
	   {
		 $edit_cat_id =  $request->hidden_edit_cat_id;
		 $edit_cat_name = $request->cat_name_show;
		 $edit_cat_code = $request->cat_code_show;
		 $category = Category::find($edit_cat_id);
		 $category->category_name = $edit_cat_name;
		 $category->category_code = $edit_cat_code ;
		 $category->category_attributes = $attribute_id ;
		 $category->updated_by = $updated_by;
		 $category->user_account_id = $user->id;
         $category->save();
		 $lastInsertedId = $category->id;

		 Session::flash('title', 'Category Updated Success'); 
		 Session::flash('success-toast-message', 'Your Category is successfully Updated');	
		 
		return redirect('category');
	   }else{
		   return response()->json(['error'=>$validator->errors()->all()]);
	   }
    }
	
    public function destroy(Request $request,$id)
    {
        $categories = Category::find($id)->delete();
		
		Session::flash('title', 'Category Deleted Success'); 
		Session::flash('success-toast-message', 'Your Category is successfully Deleted');		
		return redirect('category');
    }
	
	public function ajax_category_name(Request $request)
    {
		$cat_name = $request->cat_name;
		$cat_id = $request->cat_id;
		$hidden_cat_name = $request->cat_hidden_name;
		if($cat_id == 0)
		{
			$category = DB::table('categories')->where('category_name',$cat_name)->first();
		}else
		{
			$category = DB::table('categories')->where('category_name',$cat_name)->where('category_name','!=',$hidden_cat_name)->first();
		}
		
		if(count($category) == 0)
		{
			$response = 0;
		}else
		{
			$response = 1;
		}
		return $response;
    }
	
	public function ajax_category_code(Request $request)
    {
		$category_code = $request->category_code;
		$cat_id = $request->cat_id;
		$hidden_cat_code = $request->cat_hidden_code;
		if($cat_id == 0)
		{
			$category = DB::table('categories')->where('category_code',$category_code)->first();
		}else{
			$category = DB::table('categories')->where('category_code',$category_code)->where('category_code','!=',$hidden_cat_code)->first();
		}
		
		if(count($category) == 0)
		{
			$response = 0;
		}else
		{
			$response = 1;
		}
		return $response;
    }

}
