<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Commission;
use App\Images;
use DB;
use Image;
use Auth;

class CommissionController extends Controller
{
   public function index()
   {
	   $commission = DB::table('commissions')->orderBy('id', 'desc')->get();
	   return view('commission/commission_list',['commission'=>$commission]);
   }
   
    public function create()
    {
		return view('commission/add_commission');
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
			'low_unit_price' => 'required|max:25',
			'high_unit_price' => 'required|max:25',
			'unit_commission' => 'required|max:25',
		 ]);
		 
	   if ($validator->passes()) 
	   {
			$low_unit_price = $request->low_unit_price;
			$high_unit_price = $request->high_unit_price;
			$unit_commission = $request->unit_commission;
			$CommissionController = Commission::create([
				'low_unit_price' => $request->low_unit_price,
				'high_unit_price' => $request->high_unit_price,
				'unit_commission' => $request->unit_commission,
				'created_by' => $created_by,
				'user_account_id' => $user->id,
				]);
			$CommissionController->save();
			Session::flash('title', 'Commission Added Success'); 
			Session::flash('success-toast-message', 'Your Commission is successfully Added');	
			return redirect('commission');
	   }else{
		   return response()->json(['error'=>$validator->errors()->all()]);
	   }
    }
    public function show()
    {
		dd('show');
    }
	
    public function edit($id)
    {
	    $edit_commission =  DB::select('select * from commissions where id="'.$id.'"');
	    return view('commission/edit_commission')->with('edit_commission', $edit_commission);
    }

    public function update(Request $request)
    {
		$edit_id = $request->edit_id;
		$user = Auth::user();
		if(Session::get('admin_id')){
			$updated_by = Session::get('admin_id');
		}else{
			$updated_by = $user->id;
		}
		
		$validator = Validator::make($request->all(), [
			'low_unit_price' => 'required|max:25',
			'high_unit_price' => 'required|max:25',
			'unit_commission' => 'required|max:25',
		 ]);
		
		  $commission = Commission::find($edit_id);
		  $commission->low_unit_price = $request->low_unit_price;
		  $commission->high_unit_price = $request->high_unit_price;
		  $commission->unit_commission = $request->unit_commission;
		  $commission->updated_by = $updated_by;
		  $commission->save();
		  Session::flash('title', 'Commission Updated Success'); 
		  Session::flash('success-toast-message', 'Your Commission is successfully Updated');	
		  return redirect('commission');
    }
	
    public function destroy()
    {
	   dd('des');
    }
	
	public function ajax_low_unit_price(Request $request)
	{
		$count = Commission::count();
		$low_unit_price = $request->low_unit_price;
		$high_unit_price = $request->high_unit_price;
		
		$status = 0;
		
			$low_unit_price_commission = DB::select('SELECT id FROM commissions WHERE low_unit_price >= "'.$low_unit_price.'" OR high_unit_price >= "'.$low_unit_price.'"');
			
			$high_unit_price_commission = DB::select('SELECT id FROM commissions WHERE low_unit_price >= "'.$high_unit_price.'" OR high_unit_price >= "'.$high_unit_price.'"');
			
			
			if(count($low_unit_price_commission) >0)
			{
				$status = 1;
			}else if(count($high_unit_price_commission) >0){
				$status = 1;
			}
		
		return $status;
		
	}
	
	public function edit_ajax_low_unit_price(Request $request)
	{
		$count = Commission::count();
		$low_unit_price = $request->low_unit_price;
		$high_unit_price = $request->high_unit_price;
		$hidden_low_unit_price = $request->hidden_low_unit_price;
		$hidden_high_unit_price = $request->hidden_high_unit_price;
		
		if($low_unit_price != $hidden_low_unit_price && $high_unit_price != $hidden_high_unit_price)
		{
			$sum = $high_unit_price - $low_unit_price;
			$status = 0;
			for($i=0;$i<=$sum;$i++)
			{
				$commission = DB::select('SELECT id FROM commissions WHERE "'.$low_unit_price.'" between low_unit_price and high_unit_price');
				$low_unit_price++;
				if(count($commission) == 1)
				{
					$status = 1;
				}
			}
			return $status;
		}
		
		if($high_unit_price == $hidden_high_unit_price)
		{
			if($hidden_low_unit_price > $low_unit_price)
			{
				$sum = $high_unit_price - $low_unit_price;
				$status = 0;
				for($i=0;$i<$sum;$i++)
				{
					if($low_unit_price != $hidden_low_unit_price)
					{
						$commission = DB::select('SELECT id FROM commissions WHERE "'.$low_unit_price.'" between low_unit_price and high_unit_price');
						$low_unit_price++;
						if(count($commission) == 1)
						{
							$status = 1;
						}
					}
				}
				return $status;
			}else 
			{
				$sum = $high_unit_price - $low_unit_price;
				$status = 0;
				return $status;
			}
		}
		
		if($low_unit_price == $hidden_low_unit_price)
		{
			if($hidden_high_unit_price < $high_unit_price)
			{
				$sum = $high_unit_price - $low_unit_price;
				$status = 0;
				for($i=0;$i<$sum;$i++)
				{
					$low_unit_price++;
					$commission = DB::select('SELECT id FROM commissions WHERE "'.$low_unit_price.'" between low_unit_price and high_unit_price');
					if(count($commission) == 1)
					{
						$status = 1;
					}
				}
				return $status;
			}else{
				$sum = $high_unit_price - $low_unit_price;
				$status = 0;
				return $status;
			}
		}
		
	}

	

}
