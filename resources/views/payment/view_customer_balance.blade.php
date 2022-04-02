@extends('layouts.master')

@section('css')

<link href="{{asset('public/main_theme/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<style>
.unread_count{margin-left: -12px;position: relative;top: -8px;font-weight: 100;}
</style>

@append

@section('content')
<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
           
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title float-left">Payment History</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="#">Payment</a></li>
                            <li class="breadcrumb-item active">Customer Balance</li>
                            <li class="breadcrumb-item active">Payment History</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-right mb-2">
                <h5 style="color:blue;">Customer Name:- {{ $customer_name }}</h5>
                    <div class="card-box">
                     
                        <div class="d-flex" style="justify-content: right;">
                           
                            <div><a href="../manage_payment" class="btn btn-primary waves-light waves-effect ml-2 w-md">Manage Payment</a></div>
                            <div class="ml-2"><a href="{{url('/payment_history_download_PDF')}}/{{$customerid}}/sendemail" class="btn btn-light waves-light waves-effect w-md">Send Email</a></div>
                            <div class="ml-2"><a href="{{url('/payment_history_download_PDF')}}/{{$customerid}}/export" class="btn btn-light waves-light waves-effect w-md">Export</a></div>
                          
                            <div class="filter-area" style="display: none;">
                                <div class="d-flex">
                                    <div class="col-md-2 mt-2"><h6>Order Filter</h6></div>
                                    <div class="col-md-1 mt-2">From</div>
                                    <div class="col-2">
                                        <input class="form-control" placeholder="" type="text">
                                    </div>
                                    <div class="col-md-1 mt-2">To</div>
                                    <div class="col-2">
                                        <input class="form-control" placeholder="" type="text">
                                    </div>
                                    <div>
										<a href="javascript:void();" class="btn btn-info waves-light waves-effect btn-xs">Filter</a>
									</div>
									<div>
										<a href="javascript:void();" class="btn btn-primary waves-light waves-effect btn-xs ml-2">Clear</a>
									</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card-box table-responsive">
                          <table id="datatable" class="table table-striped table-bordered table-hover table-sm" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>SN.</th>
                                    <th>Date</th>
                                    <th>Created By</th>
                                    <th>Transaction</th>
                                    
                                    <th>Qty.</th>
                                    <th>Total</th>
                                    <th>Balance</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $n = 0; 
								//print_r($trans_histories_list);
								
								?>  
                               
                                @foreach($trans_histories_list as $trans_histories)
                                <tr> 
                                    <td> </td>
                                    <td>
                                    {{date('d-m-Y', strtotime($trans_histories['trans_date']))}}</td>
                                    <td>{{$trans_histories['created_by']}}</td>
                                    <th>
                                    @if($trans_histories['trans_type']=='order')
                                    <?php 
										$check_cancel = DB::table('orders')->select('is_cancel')->where('id',$trans_histories['trans_id'])->first();?>
										@if($check_cancel->is_cancel == 0)
                                        <a href="{{ url('order')}}/{{encrypt($trans_histories['trans_id'])}}/edit">{{$trans_histories['trans_name']}}</a>
                                        @else
                                        	<a href="{{ url('cancel_order_view')}}/{{encrypt($trans_histories['trans_id'])}}">{{$trans_histories['trans_name']}}</a>
                                        @endif
                             @elseif($trans_histories['trans_type']=='penalty')
                            <a href="{{ url('order')}}/{{encrypt($trans_histories['trans_id'])}}/edit">                               {{$trans_histories['trans_name']}} 
                            </a>
                                     
                                     
                                     
                                    @elseif($trans_histories['trans_type']=='payment')
                                          <a href="{{url('/view_payment_details')}}/{{  encrypt($trans_histories['trans_id'])}}/payment/{{encrypt($trans_histories['customer_id'])}}">   {{$trans_histories['trans_name']}} </a>
                                    @elseif($trans_histories['trans_type']=='cancel')
                                    <a href="javascript:void(0)">   {{$trans_histories['trans_name']}} </a>
                                    
                                    @elseif($trans_histories['trans_type']=='refund')
                                    <a href="javascript:void(0)">   {{$trans_histories['trans_name']}} </a>
                                    @else
                                        
                                    @endif
                                    
                                    @if($trans_histories['trans_type']=='order')
                                         <span>(Order Placed)</span>
                                    @elseif($trans_histories['trans_type']=='payment')
                                         <span style="color:red">(Payment)</span>
                                    @elseif($trans_histories['trans_type']=='cancel')
                                         <span style="color:red">(Cancel)</span>
                                     @elseif($trans_histories['trans_type']=='penalty')
                                     	
                                         <span style="color:red">(Adjustment/Penalty)</span>     
                                    @elseif($trans_histories['trans_type']=='refund')
                                         <span style="color:green">(Refund)</span>
                                    @else
                                        
                                    @endif
                                    
                                    
                                    </th>
                                    
                                    <td>{{$trans_histories['quantity']}}</td>
                                    <td>
                                    @if($trans_histories['trans_type']=='order')
                                        <p>RM {{$trans_histories['total']}}</p>
                                    @elseif($trans_histories['trans_type']=='payment')
                                        <p style="color:red">RM -{{$trans_histories['total']}} </p>
                                    @elseif($trans_histories['trans_type']=='cancel')
                                        <p style="color:red">RM -{{$trans_histories['total']}} </p>                                    @else
                                        <p style="color:green">RM {{$trans_histories['total']}} </p>                                    @endif
                                    
                                    </td>
                                    <td>
                                    <?php 
									if($trans_histories['balance']<0){$trans_balance_color="style=color:red";}else{$trans_balance_color='';}
									?>
                                    @if($trans_histories['trans_type']=='order')
                                        <p {{$trans_balance_color}}>RM  {{number_format($trans_histories['balance'], 2) }}</p>
                                    @elseif($trans_histories['trans_type']=='payment')
                                        <p {{$trans_balance_color}}>RM  {{number_format($trans_histories['balance'], 2) }}</p>
                                    @elseif($trans_histories['trans_type']=='cancel')
                                        <p {{$trans_balance_color}}>RM  {{number_format($trans_histories['balance'], 2) }}</p> 
                                   @elseif($trans_histories['trans_type']=='refund')
                                        <p {{$trans_balance_color}}>RM  {{number_format($trans_histories['balance'], 2) }}</p>   
                                    @else
                                         <p {{$trans_balance_color}}>RM  {{number_format($trans_histories['balance'], 2) }}</p>
                                    @endif
                                    
                                    </td>
                                    <td class="text-success"><p>{{$trans_histories['verified']}}</p></td>
                                    <td><p>
                                    @if($trans_histories['trans_type']=='order')
                                        @if($trans_histories['order_payment_status']=='no')
                                       <!-- <a data-toggle="tooltip" href="{{url('/delete_orders_details')}}/{{ $trans_histories['trans_id']}}/{{ $trans_histories['customer_id'] }}" title="Delete"><img src="{{asset('public/delete.png')}}" alt=""></a>  -->
                                        @endif   
                                    @elseif($trans_histories['trans_type']=='payment')
                                      <!--    <a data-toggle="tooltip" href="{{url('/delete_payment_cust_balance')}}/{{ $trans_histories['trans_id'] }}/Decline/view_customer_balance/{{ $trans_histories['customer_id'] }}" title="Delete"><img src="{{asset('public/delete.png')}}" alt=""></a>-->
                                    @else
                                        
                                    @endif
                                    
                                        
                                        
                                      <?php 
									  	if($trans_histories['trans_type']=='penalty'){
											$source_type = 'order';
										}else{
											$source_type = $trans_histories['trans_type'];
										}
										?>
                                       		
                                      <a class="noteButton" href="#historyNote" data-toggle="modal" data-target=".historyNote" title="View Note" s_id="{{$trans_histories['trans_id']}}" source_id="{{ encrypt($trans_histories['trans_id']) }}" source_type="{{ $source_type }}"><img src="{{asset('public/history.png')}}" alt=""></a>
                                      
                                      
                                         
                                      <?php 
									  $tran_type=$trans_histories['trans_type'];
									  ?> 
                                      @if(Auth::user()->hasRole('Super-Admin'))
                                      <?php 
                                      $login_user_id= Auth::user()->id;
                                      $unread_note_count = \App\Note::where('source_type',$tran_type)->where('source_id',$trans_histories['trans_id'])->
                                      where('admin_read_status',0)->where('created_by','<>',$login_user_id)->count();
									   ?>
                                       <span class="badge badge-danger badge-pill unread_count" id="quantity_badge{{ $trans_histories['trans_id']}}" style="background-color:#f60303;">{{$unread_note_count}}</span>
                                       @endif 
                                       
                                       @if(Auth::user()->hasRole('Sales-Agent'))
                                        <?php 
									   $login_user_id= Auth::user()->id;
									   $unread_note_count = \App\Note::where('source_type',$tran_type)->where('source_id',$trans_histories['trans_id'])->where('agent_read_status',0)->where('created_by','<>',$login_user_id)->count();
									   ?>
                                       <span class="badge badge-danger badge-pill unread_count" id="quantity_badge{{ $trans_histories['trans_id']}}" style="background-color:#f60303;">{{$unread_note_count}}</span>
                                       @endif 
                                       
                                       @if(Auth::user()->hasRole('Dispatch-Manager'))
                                      <?php 
									  $login_user_id= Auth::user()->id;
									
									   $unread_note_count = \App\Note::where('source_type',$tran_type)->where('source_id',$trans_histories['trans_id'])->where('dispatch_read_status',0)->where('created_by','<>',$login_user_id)->count();
									   ?>
                                       <span class="badge badge-danger badge-pill unread_count" id="quantity_badge{{ $trans_histories['trans_id']}}" style="background-color:#f60303;">{{$unread_note_count}}</span>
                                       @endif 
                                      
                                      </p>
                                    </td>
                                </tr>
                                
                                <?php $n++; ?>  
                                @endforeach   
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="setPostalCost" class="modal-demo">
                    <button type="button" class="close" onclick="Custombox.close();">
                    <span>&times;</span><span class="sr-only">Close</span>
                    </button>
                    <h4 class="custom-modal-title">Set Postal Cost/ Insurance For: CUST-2</h4>
                    <div class="custom-modal-text">
                        <form action="">
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="inputEmail4" class="col-form-label">Postal Cost:</label>
                                    <input class="form-control" id="inputEmail4" value="60" type="text">
                                </div>
                                <div class="col-md-12">
                                    <p>If Insurence (RM): Tic For Yes, Keep Un-Tic For Default.</p>
                                    <input type="checkbox">
                                </div>
                            </div>
                            <div class="form-group text-right m-b-0">
                                <button class="btn btn-primary waves-effect waves-light" type="submit">
                                Save Changes
                                </button>
                                <button type="reset" class="btn btn-light waves-effect m-l-5">
                                Close
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                
                <!-- note model-->
                <div class="modal fade historyNote" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none; z-index: 9999;">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                              <button type="button"  class="close" data-dismiss="modal" aria-hidden="true">×</button>
                              <h5 class="modal-title" id="myLargeModalLabel">History Note</h5>
                            </div>
                            <div class="modal-body">
                            <div style="height:200px; overflow:auto;">
                            <div class="table-responsive">
                                <table class="table table-striped" id="noteTable">
                                    <thead>
                                        <tr>
                                            <th width="200">Send By</th>
                                            <th>History</th>
                                            <th>Acknowledge</th>
                                        </tr>
                                    </thead>
                                    <tbody id="notes_list">
                                       
                                        
                                        
                                    </tbody>
                                </table>
                                </div>
                                </div>
                                  {!! Form::open(['id'=>'form_submit','class'=>'']) !!}	
			 {{ csrf_field() }}
                                <div class="form-group">
                              
                                        <label for="">Comment's</label>
                                        {{ Form::textarea('source_comment','',['placeholder' => 'Customer Note','rows'=>'2','parsley-trigger' => 'change' , 'class' =>'form-control','autocomplete' => 'off', 'id'=>'source_comment' ,'data-parsley-required-message'=>'Please Enter Note','required']) }}
                                        <input type="hidden"   name="source_type" id="source_type"  value="payment" />
                                        <input type="hidden"  name="source_id" id="source_id"  value="<" />
                                    </div>
                             
                                <div class="w-100 d-flex">
                                   <div class="checkbox checkbox-purple">
                                    <input id="notify" name="notify" type="checkbox" value="1" >
                                    <label for="checkbox6a">
                                        <span class="text-purple"><strong>Notify</strong></span>
                                    </label>
                                   </div>      
                                </div>
                                
                                <div style="display:none;" id="notify_option">
                                    @hasanyrole('Super-Admin')
                                       <div class="checkbox checkbox-blue" style="float:left;;margin-left:5px;">
                                    <input checked="checked" id="notify_sales_agent" name="notify_sales_agent" type="checkbox" value="1" >
                                    <label for="checkbox6a">
                                        <span class="text-blue"><strong>Sales-Agent</strong></span>
                                    </label>
                                   </div> &nbsp&nbsp&nbsp&nbsp 
                                       <div class="checkbox checkbox-blue" style="float:left;;margin-left:5px;">
                                    <input checked="checked" id="notify_dispatch" name="notify_dispatch" type="checkbox" value="1" >
                                    <label for="checkbox6a">
                                        <span class="text-blue"><strong>Dispatch</strong></span>
                                    </label>
                                   </div> 
                                    @endhasanyrole 
                                    @hasanyrole('Sales-Agent')
                                       <div class="checkbox checkbox-blue" style="float:left;margin-left::5px;">
                                    <input checked="checked" id="notify_admin" name="notify_admin" type="checkbox" value="1" >
                                    <label for="checkbox6a">
                                        <span class="text-blue"><strong>Admin</strong></span>
                                    </label>
                                   </div>&nbsp&nbsp&nbsp&nbsp 
                                       <div class="checkbox checkbox-blue" style="float:left;;margin-left:5px;">
                                    <input checked="checked" id="notify_dispatch" name="notify_dispatch" type="checkbox" value="1" >
                                    <label for="checkbox6a">
                                        <span class="text-blue"><strong>Dispatch</strong></span>
                                    </label>
                                   </div>  
                                    @endhasanyrole
                                    @hasanyrole('Dispatch-Manager')
                                       <div class="checkbox checkbox-blue" style="float:left;margin-left::5px;">
                                    <input checked="checked" id="notify_admin" name="notify_admin" type="checkbox" value="1" >
                                    <label for="checkbox6a">
                                        <span class="text-blue"><strong>Admin</strong></span>
                                    </label>
                                   </div>&nbsp&nbsp&nbsp&nbsp 
                                       <div class="checkbox checkbox-blue" style="float:left;;margin-left:5px;">
                                    <input  checked="checked" id="notify_sales_agent" name="notify_sales_agent" type="checkbox" value="1" >
                                    <label for="checkbox6a">
                                        <span class="text-blue"><strong>Sales-Agent</strong></span>
                                    </label>
                                   </div> &nbsp&nbsp&nbsp&nbsp 
                                    @endhasanyrole
                                    
                                </div>
                                
                              
                                
                                <div class="w-100 d-flex">
                                    <div class="col-md-6 p-0">
                                <button  class="btn btn-primary waves-effect waves-light" type="submit" id="submit" >Submit</button>                 </div>
                                   
                                </div>
                                </form>
                            </div>
                           
                        </div>
                    </div>
				</div> 
                <!--end note model-->
                
                
                
            </div> 
        </div> 
<footer class="footer text-right"> 2018 © UKSHOP .</footer>
</div>
</div>
@endsection


@section('scripts')
<script src="{{asset('public/main_theme/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('public/main_theme/js/dataTables.bootstrap4.min.js')}}"></script>
<script type="text/javascript">

	var table = $('#datatable').DataTable( {
	scrollX:        true,
	scrollCollapse: true,
	paging:         true,
	"columnDefs": [{
	"targets":0,
	"width": "10px"
	},
	{
	"targets":1,
	"width": "60px"
	},
	{
	"targets":2,
	"width": "110px"
	},
	{
	"targets":3,
	"width": "200px"
	},
	{
	"targets":4,
	"width": "20px"
	},
	{
	"targets":5,
	"width": "100px"
	},
	{
	 "targets":6,
	  "width":"100px"
	},
	{
	 "targets":7,
	  "width":"60px"
	},
	{
	 "targets":8,
	  "width":"20px"
	}],
	fixedColumns: true
	});


table.on( 'order.dt search.dt', function () {
	table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
		cell.innerHTML = i+1;
	} );
} ).draw();

/*cut and paste*/
$(document).on('click','.noteButton',function(event){
	var source_id =$(this).attr('source_id');
	var s_id =$(this).attr('s_id');
	$('#quantity_badge'+s_id).html(0);	
	var source_type =$(this).attr('source_type');
	$('#source_id').val(source_id);	
	$('#source_type').val(source_type);	
	getNotesHistory(source_id,source_type);
	
});
$(document).ready(function() {
$('#form_submit').on('submit',function(e){ 
		 $.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 }
		 })	
		 var url= APP_URL+'/ajax_add_source_notes';
		 e.preventDefault(e);
			$.ajax({
			type:"POST",
			url: url,
			data:$(this).serialize(),
			//dataType: 'json',
			success: function(data){  // alert(data);  console.log(data); exit();
				$('#source_comment').val('');
			    var source_id =$('#source_id').val();	
	            var source_type = $('#source_type').val();	
				//alert(source_id);alert(source_type);
				getNotesHistory(source_id,source_type);	
				console.log(data);
			},
			error: function(data){
	
			}
		})
    });
});	
function getNotesHistory(source_id,source_type)
{
	$('#noteTable #notes_list').find('tr').remove();
	$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 }
	})
	$.ajax({
		type: "POST",
		url: APP_URL+'/ajax_list_source_notes',
		data: {source_id:source_id, source_type:source_type},
		success: function (data) {  
			         console.log(data);     
			         var sn=1;
					 var note_list='';
					 if(data.length>0){
						 for(key in data)   
						 {
							//alert(data[key].user_role);
							 @hasanyrole('Super-Admin')
								 if(data[key].notify!=0){ 
 								   if(data[key].create_mode!='yes'){ 
								       if(data[key].user_role!='Dispatch-Manager'){  
								       note_list += '<tr id="row_'+sn+'"><td><p>' + data[key].user_name + '<br>' + data[key].notes_time + '</p></td><td><p>' + data[key].history + '</p></td><td><p> <input type="checkbox"  note_id="' + data[key].note_id + '" acknowledge_type="acknow_admin" acknowledge_value="' + data[key].acknow_admin + '"   name="acknowledge" ' + data[key].checkbox_admin + ' class="acknowledge" value="1" /><span>admin&nbsp;&nbsp;&nbsp;</span> <input ' + data[key].disabled + ' type="checkbox" note_id="' + data[key].note_id + '" acknowledge_type="acknow_dispatch" acknowledge_value="' + data[key].acknow_dispatch + '" disabled="disabled" name="acknowledge" id="acknowledge" value="1" ' + data[key].checkbox_dispatch + ' /><span ' + data[key].dispatch_read_status + '>Dispatch</span></p></td></tr>';
									   }
									   else
									   {
										   note_list += '<tr id="row_'+sn+'"><td><p>' + data[key].user_name + '<br>' + data[key].notes_time + '</p></td><td><p>' + data[key].history + '</p></td><td><p> <input type="checkbox"  note_id="' + data[key].note_id + '" acknowledge_type="acknow_admin" acknowledge_value="' + data[key].acknow_admin + '"   name="acknowledge" ' + data[key].checkbox_admin + ' class="acknowledge" value="1" /><span ' + data[key].admin_read_status + '>admin&nbsp;&nbsp;&nbsp; </span><input ' + data[key].disabled + ' type="checkbox" note_id="' + data[key].note_id + '" acknowledge_type="acknow_dispatch" acknowledge_value="' + data[key].acknow_sales_agent + '" disabled="disabled" name="acknowledge" id="acknowledge" value="1" ' + data[key].checkbox_sales_agent + ' /><span ' + data[key].agent_read_status + '>Sales-Agent</span></p></td></tr>';   
									   }
									   
									}
									else{
									  note_list += '<tr id="row_'+sn+'"><td><p>' + data[key].user_name + '<br>' + data[key].notes_time + '</p></td><td><p>' + data[key].history + '</p></td><td><p> <input type="checkbox" ' + data[key].disabled + ' name="acknowledge" id="acknowledge" ' + data[key].checkbox_sales_agent + ' value="1" /><span ' + data[key].agent_read_status + '>Sales-Agent&nbsp;&nbsp;&nbsp;</span> <input ' + data[key].disabled + ' type="checkbox" name="acknowledge" id="acknowledge" value="1" ' + data[key].checkbox_dispatch + ' /><span ' + data[key].dispatch_read_status + '>Dispatch</span></p></td></tr>';   
									}  
								 }
								 else
								 {
									note_list += '<tr id="row_'+sn+'"><td><p>' + data[key].user_name + '<br>' + data[key].notes_time + '</p></td><td><p>' + data[key].history + '</p></td><td></td></tr>'; 
								 }
							 @endhasanyrole	 
							 @hasanyrole('Sales-Agent')	 
							  
								if(data[key].notify!=0){ 
								    if(data[key].create_mode!='yes'){ 
								      note_list += '<tr id="row_'+sn+'"><td><p>' + data[key].user_name + '<br>' + data[key].notes_time + '</p></td><td><p>' + data[key].history + '</p></td><td><p> <input type="checkbox"  name="acknowledge" ' + data[key].checkbox_sales_agent + ' class="acknowledge" note_id="' + data[key].note_id + '" acknowledge_type="acknow_sales_agent" acknowledge_value="' + data[key].acknow_sales_agent + '" value="1" /><span>Sales-Agent&nbsp;&nbsp;&nbsp;</span><input ' + data[key].disabled + ' type="checkbox" disabled="disabled" name="acknowledge" id="acknowledge" value="1" ' + data[key].checkbox_dispatch + ' /><span ' + data[key].dispatch_read_status + '>Dispatch</span></p></td></tr>';
									}
									else{
									  note_list += '<tr id="row_'+sn+'"><td><p>' + data[key].user_name + '<br>' + data[key].notes_time + '</p></td><td><p>' + data[key].history + '</p></td><td><p> <input type="checkbox" ' + data[key].disabled + ' name="acknowledge" class="acknowledge" ' + data[key].checkbox_admin + ' value="1" /><span ' + data[key].admin_read_status + '>admin&nbsp;&nbsp;&nbsp; </span><input ' + data[key].disabled + ' type="checkbox" name="acknowledge" id="acknowledge" value="1" ' + data[key].checkbox_dispatch + ' /><span ' + data[key].dispatch_read_status + '>Dispatch</span></p></td></tr>';
									}
								 }
								 else
								 {
									note_list += '<tr id="row_'+sn+'"><td><p>' + data[key].user_name + '<br>' + data[key].notes_time + '</p></td><td><p>' + data[key].history + '</p></td><td></td></tr>'; 
								 } 
								@endhasanyrole	  
								@hasanyrole('Dispatch-Manager')	 
								if(data[key].notify!=0){ 
								    if(data[key].create_mode!='yes'){ 
								    note_list += '<tr id="row_'+sn+'"><td><p>' + data[key].user_name + '<br>' + data[key].notes_time + '</p></td><td><p>' + data[key].history + '</p></td><td><p> <input type="checkbox"  disabled="disabled"  name="acknowledge" ' + data[key].checkbox_sales_agent + ' id="acknowledge" value="1" /><span>Sales-Agent&nbsp;&nbsp;&nbsp;</span> <input ' + data[key].disabled + ' type="checkbox" name="acknowledge"  note_id="' + data[key].note_id + '" acknowledge_type="acknow_dispatch" acknowledge_value="' + data[key].acknow_dispatch + '" class="acknowledge" id="acknowledge" value="1" ' + data[key].checkbox_dispatch + ' /><span ' + data[key].dispatch_read_status + '>Dispatch</span></p></td></tr>';
									}
									else{
									  note_list += '<tr id="row_'+sn+'"><td><p>' + data[key].user_name + '<br>' + data[key].notes_time + '</p></td><td><p>' + data[key].history + '</p></td><td><p> <input type="checkbox" ' + data[key].disabled + ' name="acknowledge" id="acknowledge" ' + data[key].checkbox_admin + ' value="1" />Admin&nbsp;&nbsp;&nbsp; <input ' + data[key].disabled + ' type="checkbox" name="acknowledge"  value="1" ' + data[key].checkbox_sales_agent + ' />Sales-Agent</p></td></tr>';
									} 
									
									
								 }
								 else
								 {
									note_list += '<tr id="row_'+sn+'"><td><p>' + data[key].user_name + '<br>' + data[key].notes_time + '</p></td><td><p>' + data[key].history + '</p></td><td></td></tr>'; 
								 } 
								 @endhasanyrole
						
							 
							 sn++;
						 }
						 $('#noteTable #notes_list').append(note_list);
					 }
					 else{
						 $('#noteTable #notes_list').find('tr').remove();
					 }
					 
		}
	});
	
}
$('#notify').click(function() { 
    if ($(this).is(':checked')) {
         $("#notify_option").css("display", "block");
    } else {
         $("#notify_option").css("display", "none");
    }
});
$(document).on('click','.acknowledge',function(event){
    if ($(this).is(':checked')) {
		 var note_id =$(this).attr('note_id');
		 var acknowledge_type =$(this).attr('acknowledge_type');
		 var acknowledge_value =$(this).attr('acknowledge_value');
		 var source_type =$('#source_type').val();
		 var source_id =$('#source_id').val();
		  $.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 }
		 })	
			$.ajax({
			type: "POST",
			url: APP_URL+'/ajax_change_acknowledge_notes',
			data: {note_id:note_id,acknowledge_type:acknowledge_type,acknowledge_value:acknowledge_value,source_id:source_id,source_type:source_type},
			success: function (data) {  //alert(source_id); 
			    //getNotesHistory(source_id,source_type);	
				console.log(data);
			},
			error: function(data){
	
			}
		});
    } else {
		var note_id =$(this).attr('note_id');
		 var acknowledge_type =$(this).attr('acknowledge_type');
		 var acknowledge_value =$(this).attr('acknowledge_value');
		 var source_type =$('#source_type').val();
		 var source_id =$('#source_id').val();
		  $.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 }
		 })	
			$.ajax({
			type: "POST",
			url: APP_URL+'/ajax_change_acknowledge_notes',
			data: {note_id:note_id,acknowledge_type:acknowledge_type,acknowledge_value:acknowledge_value,source_id:source_id,source_type:source_type},
			success: function (data) { //alert(source_id); 
			   // getNotesHistory(source_id,source_type);	
				console.log(data);
			},
			error: function(data){
			}
		});
    }
});
/* end cut and paste*/
</script>
@append

