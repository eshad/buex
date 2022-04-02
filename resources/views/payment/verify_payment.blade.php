@extends('layouts.master')

@section('css')

<link href="{{asset('public/main_theme/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="{{asset('public/main_theme/js/jquery-1.10.2.min.js')}}"></script>
<link href="{{asset('public/main_theme/css/jquery.fancybox.css')}}" rel='stylesheet' type='text/css'>
<script type="text/javascript" src="{{asset('public/main_theme/js/jquery.fancybox.pack.js')}}"></script>
<style>
.fancybox-close {
    background-image: url("public/fancybox_sprite.png");
}
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
                        <h4 class="page-title float-left">Verify Payment</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="#">Payment</a></li>
                            <li class="breadcrumb-item active">Verify Payment</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
          <!--  <div class="row">
                <div class="col-md-12 text-right mb-2">
                    <div class="card-box">
                        <div class="d-flex" style="justify-content: right;">
                            <div class="col-md-2 mt-2">Ref. Number/Slip </div>
                            <div class="col-3">
                                <input class="form-control" placeholder="Ref. Number/Slip" type="text">
                            </div>
                            <div class="col-md-1 mt-2">Number</div>
                            <div><a href="javascript:void();" class="btn btn-primary waves-light waves-effect w-md">Check</a></div>
                            <div class="ml-2"><a href="javascript:void();" class="btn btn-light waves-light waves-effect w-md">Send Email</a></div>
                            <div class="ml-2"><a href="javascript:void();" class="btn btn-light waves-light waves-effect w-md">Export</a></div>
                        </div>
                    </div>
                </div>
            </div>-->
            <div class="row">
                <div class="col-12">
                    <div class="card-box table-responsive">
                        <table id="datatable" class="table table-striped table-bordered table-hover table-sm" style="width: 100%;">
                            <thead>
                                <tr>
                                	<th>Date</th>
                                    <th>Created By</th>
                                    <th>Customer Name</th>
                                    <th>Image</th>
                                    <th>Ref. Number/Slip</th>
                                    <th>Payment Source</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                               <?php $n = 1; ?>  
                             
                                @foreach($payment_list as $payment_details)
                                <tr>
                                	<td>{{date('d/m/Y',strtotime($payment_details->payment_date))}}</td>
                                    <td>{{$payment_details->name }}</td>
                                    <td>{{ $payment_details->customer_full_name}} - {{ $payment_details->customer_uniq_id}}</td>
                                    <td>
                        <?php 
						if($payment_details->payment_image != '')
						{
							$image_name1 = "payment_image/normal_images/".$payment_details->payment_image;
							//$image_name2 = "main_theme/images/no_image.jpg";
						}else
						{
							$image_name1 = "main_theme/images/no_image.jpg";
							//$image_name2 = "main_theme/images/no_image.jpg";
						}?>       
                           <div id="slider_light">
							<a class="fancybox{{$n}} " data-fancybox-group="gallery" href="{{ asset('public/')}}/{!!$image_name1!!}" >
								<img src="{{ asset('public/')}}/{!!$image_name1!!}" alt="" width="125">
							</a>
                           
			      <script>
					$(".fancybox{{$n}}").fancybox({
						fitToView: false,
							beforeShow: function () {
							// apply new size to img
							$(".fancybox-image").css({
							"width": 800,
							"height": 600
							});
							// set new values for parent container
							this.width = 800;
							this.height = 600;
						}
					});
					</script>
						</div>
                                    
                                    </td>
                                    
                                    
                                    <td>{{ $payment_details->payment_ref_number}} </td>
                                    <td>{{ $payment_details->source_name}} </td>
                                    <td>RM {{ $payment_details->payment_amount}} </td>
                                    <td>
                                        @if($payment_details->payment_status=='unverified')
                                        <a class="clickaction"  href_url="{{url('/change_payment_status')}}/{{ $payment_details->payment_id}}/Verified"  data-toggle="tooltip" title="Accept"><img src="{{asset('public/awaiting.png')}}" alt=""></a>
                                        <a class="clickaction"  href_url="{{url('/delete_payment_details')}}/{{ $payment_details->payment_id}}/Declined/verify_payment" data-toggle="tooltip" title="Decline"><img src="{{asset('public/cancel.png')}}" alt=""></a>
                                        @elseif($payment_details->payment_status=='Verified')
                                      <!-- <a pay_status="Awaiting"    href="javascript:void();" data-toggle="tooltip" title="Accepted"><img src="{{asset('public/check.png')}}" alt=""></a>-->
                                       <a style="color:green">Verified</a>
                                        @elseif($payment_details->payment_status=='Declined')
                                       <a style="color:red">Declined</a>
                                        @else
                                        
                                        @endif
                                         <a class="noteButton" href="#historyNote" data-toggle="modal" data-target=".historyNote" title="View Note" s_id="{{$payment_details->payment_id}}" source_id="{{ encrypt($payment_details->payment_id) }}"source_type="payment"><img src="{{asset('public/history.png')}}" alt=""></a>
                                            @if(Auth::user()->hasRole('Super-Admin'))
                                      <?php 
									  $login_user_id= Auth::user()->id;
									  ?>
                                        <?php 
									   $unread_note_count = \App\Note::where('source_type','payment')->where('source_id',$payment_details->payment_id)->where('admin_read_status',0)->where('created_by','<>',$login_user_id)->count();
									   ?>
                                       <span class="badge badge-danger badge-pill unread_count" id="quantity_badge{{ $payment_details->payment_id}}" style="background-color:#f60303;">{{$unread_note_count}}</span>
                                       @endif 
                                       
                                       @if(Auth::user()->hasRole('Sales-Agent'))
                                        <?php 
									  $login_user_id= Auth::user()->id;
									  ?>
                                        <?php 
									   $unread_note_count = \App\Note::where('source_type','payment')->where('source_id',$payment_details->payment_id)->where('agent_read_status',0)->where('created_by','<>',$login_user_id)->count();
									   ?>
                                       <span class="badge badge-danger badge-pill unread_count" id="quantity_badge{{ $payment_details->payment_id}}" style="background-color:#f60303;">{{$unread_note_count}}</span>
                                       @endif 
                                       
                                       @if(Auth::user()->hasRole('Dispatch-Manager'))
                                      <?php 
									  $login_user_id= Auth::user()->id;
									  ?>
                                        <?php 
									   $unread_note_count = \App\Note::where('source_type','payment')->where('source_id',$payment_details->payment_id)->where('dispatch_read_status',0)->where('created_by','<>',$login_user_id)->count();
									   ?>
                          <span class="badge badge-danger badge-pill unread_count" id="quantity_badge{{ $payment_details->payment_id}}" style="background-color:#f60303;">{{$unread_note_count}}</span>
                                       @endif   
                                    </td>
                                </tr>
                                 <?php $n++; ?>  
                                @endforeach   
                            </tbody>
                        </table>                
                    </div>
                </div>
                
           </div> 
        </div> 
<footer class="footer text-right">2018 © UKSHOP .</footer>
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
@endsection


@section('scripts')

<script src="{{asset('public/main_theme/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('public/main_theme/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('public/main_theme/js/sweetalert2.min.js')}}"></script>
 <script type="text/javascript">
$(document).ready(function(){var table=$('#datatable').DataTable({scrollX:!0,scrollCollapse:!0,paging:!0,"order": [],"columnDefs":[{"targets":0,"width":"2%"},{"targets":1,"width":"20%"},{"targets":2,"width":"10%"},{"targets":3,"width":"15%"},{"targets":4,"width":"10%"},{"targets":5,"width":"20%"},{"targets":6,"width":"10%"}],fixedColumns:!0})})

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


    
$(document).on('click','.clickaction',function(event){
	 var href_url =$(this).attr('href_url');
	 swal({
		title: 'Are you sure?',
		text: "You won't be able to revert this!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#4fa7f3',
		cancelButtonColor: '#d57171',
		confirmButtonText: 'Yes'
	}).then(function () {
          window.location.href = href_url;
		},
	 function (dismiss) {		 
		if (dismiss === 'cancel') {
			swal(
				'Cancelled',
				'Your data is safe :)',
				'error'
			)
		}
	})	
});	
	    

</script>



	
    
    		
@append

