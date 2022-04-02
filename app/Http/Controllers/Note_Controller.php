<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Note;
use App\Customer;
use App\User;
use DB;
use Auth;
use Image;


class Note_Controller extends Controller
{
    public function ajax_addSource_note(Request $request)
    {
			if($request->notify==1){$notify=$request->notify;}else{$notify=0;}
			if($request->notify_admin==1){$notify_admin=$request->notify_admin;}else{$notify_admin=0;}
			if($request->notify_sales_agent==1){$notify_sales_agent=$request->notify_sales_agent;}else{$notify_sales_agent=0;}
			if($request->notify_dispatch==1){$notify_dispatch=$request->notify_dispatch;}else{$notify_dispatch=0;}
		    // echo $notify_dispatch;echo $notify_sales_agent;echo $notify_admin;
		  //  exit();
		    
		    $created_by =  Auth::user()->id;
				if(Session::get('admin_id')){
				   $user_account_id = Session::get('admin_id');
				}else{
				   $user_account_id =  Auth::user()->id;
				}
		        $notes_time= date('Y-m-d H:i:s');
	            $note = Note::create([  
					'source_comment' => $request->source_comment,
					'source_type' => $request->source_type,
					'source_id' => decrypt($request->source_id),
					'notify' => $notify,
					'notify_admin' => $notify_admin,
					'notify_sales_agent' => $notify_sales_agent,
					'notify_dispatch' => $notify_dispatch,
					'notes_time' => $notes_time,
					'user_account_id' => $user_account_id,
					'created_by' => $created_by,
					'updated_by' => $created_by,
 			    ]);
				if($note){
				   $return=array('status'=>'success');	
				}
				else{
				   $return=array('status'=>'failed');	
				}
				return json_encode($return);
			  
				   
    }
	public function ajax_getlist_source_notes(Request $request)
    {
	    $source_id = decrypt($request->source_id);
		$source_type = $request->source_type;
		if(Auth::user()->hasRole('Super-Admin')){
			DB::table('notes')
			->where('source_id', $source_id )
			->where('source_type', $source_type)
			->update(['admin_read_status' => 1]);	 	
		}
		if(Auth::user()->hasRole('Sales-Agent')){
			DB::table('notes')
			->where('source_id', $source_id )
			->where('source_type', $source_type)
			->update(['agent_read_status' => 1]);	 	
		}
		if(Auth::user()->hasRole('Dispatch-Manager')){
			DB::table('notes')
			->where('source_id', $source_id )
			->where('source_type', $source_type)
			->update(['dispatch_read_status' => 1]);	 	
		}
	
		
		$source_notes_list = DB::table('notes')
		->join('users', 'users.id', '=', 'notes.user_account_id')
		->select('notes.*', 'notes.id as note_id', 'users.id','users.name')
		->where('source_type',$source_type)->where('source_id',$source_id)->orderBy('notes.id', 'DESC')
		->get();
		$history=array();
		$sno=1;   
		foreach($source_notes_list as $source_notes){
			$date=date_create($source_notes->notes_time);
            $note_date=date_format($date,"l, F d y h:i:s");
			$checkuser = User::find($source_notes->created_by);
			$userd =$checkuser->getRoleNames();
			$roles = $userd[0];
			 
			if(Auth::id()==$source_notes->created_by){$display_mode='disabled="disabled"';$create_mode='yes';}else{$display_mode='';$create_mode='no';}
			
			if($source_notes->acknow_admin==1){$acknow_admin='checked="checked"';}else{$acknow_admin='123';}
		    if($source_notes->acknow_sales_agent==1){$acknow_sales_agent='checked="checked"';}else{$acknow_sales_agent='';}
			if($source_notes->acknow_dispatch==1){$acknow_dispatch='checked="checked"';}else{$acknow_dispatch='';}
			if($source_notes->admin_read_status==1){
				if($source_notes->acknow_admin==1){
					$admin_read_status='style="color:green;"';
				}else{
					$admin_read_status='style="color:red;"';
				}
			}
			else{
					$admin_read_status='';
			}
			
			if($source_notes->agent_read_status==1){
				if($source_notes->acknow_sales_agent==1){
					$agent_read_status='style="color:green;"';
				}else{
					$agent_read_status='style="color:red;"';
				}
			}
			else{
					$agent_read_status='';
			}
			if($source_notes->dispatch_read_status==1){
				if($source_notes->acknow_dispatch==1){
					$dispatch_read_status='style="color:green;"';
				}else{
					$dispatch_read_status='style="color:red;"';
				}
			}
			else{
					$dispatch_read_status='';
			}
			
			
			$history[] =array('admin_read_status'=>$admin_read_status,'agent_read_status'=>$agent_read_status,'dispatch_read_status'=>$dispatch_read_status,'sno'=>$sno,'user_name'=>$source_notes->name,'history'=>$source_notes->source_comment,'notes_time'=>$note_date,'notify'=>$source_notes->notify,'disabled'=>$display_mode,'disabled'=>$display_mode,'checkbox_admin'=>$acknow_admin,'checkbox_sales_agent'=>$acknow_sales_agent,'checkbox_dispatch'=>$acknow_dispatch,'create_mode'=>$create_mode,'note_id'=>$source_notes->note_id,'acknow_sales_agent'=>$source_notes->acknow_sales_agent,'acknow_admin'=>$source_notes->acknow_admin,'acknow_dispatch'=>$source_notes->acknow_dispatch,'user_role'=>$roles); 
			
			
          $sno++;
		}
		return $history;

    }
	
	function ajax_change_acknowledge_notes(Request $request)
	{
	    if($request->acknowledge_value==0){$acknowledge_value=1;}else{$acknowledge_value=0;}	
	    $acknowledge_type=$request->acknowledge_type;
	    $note_id=$request->note_id;
		 $result=Note::where('id', $note_id)->update([$acknowledge_type => $acknowledge_value]);
		 //print_r($result);
	  // echo "hello dosto";	
	}
}
