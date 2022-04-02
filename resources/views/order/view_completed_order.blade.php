@extends('layouts.master')

@section('css')
<link href="{{asset('public/main_theme/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="{{asset('public/main_theme/css/page_css/product_detail.css')}}"/>
<link href="{{asset('public/main_theme/css/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
<style>
.unread_count{margin-left: -12px;position: relative;top: -8px;font-weight: 100;}
</style>
@append

@section('content')

<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container-fluid">
        	{!! Form::open(['url' => 'order/'.encrypt($order[0]->id),'class'=>'form-horizontal']) !!}	
			{{ Form::hidden('_method','PATCH') }}<div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title float-left">Order Detail View</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="{{url('order')}}">Order</a></li>
                            <li class="breadcrumb-item active">Order Detail View</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->
            <div class="row">
                <div class="col-md-12 mb-2">
                    <div class="card-box d-flex flex-wrap">
                        <div class="col-md-6">
                            <h5 class="m-t-0"><b>Shipping Type : <small>@if($order[0]->shipping_type_id==1)Air Freight @elseif($order[0]->shipping_type_id==2) Sea Freight @else Direct Sale @endif</small></b></h5>
                            <h5 class="m-t-0">Customer Name : <small>{{$order[0]->customer_name}} ({{$order[0]->customer_code}})</small></h5>
                        </div>
                        <div class="col-md-6 text-right">
                            <h5 class="m-t-0"><b>Order Date : <small>{{date("d-m-Y", strtotime($order[0]->order_date) )}}</small></b></h5>
                            <h5 class="m-t-0">Order ID : <small>{{$order[0]->order_code}}</small></h5>
                        </div>
                        <div class="col-md-12">
                            <hr>
                            <div class="btn-group">
                                <label class="custom-control custom-radio mb-0">
                                    <input id="radio2" name="order_status" type="radio" class="custom-control-input" value="new" {{$order[0]->order_status=='new'?'checked':''}}>
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description text-primary"><strong>No Action Required</strong></span>
                                </label>
                                <label class="custom-control custom-radio mb-0">
                                    <input id="radio2" name="order_status" type="radio" class="custom-control-input" value="rtc" {{$order[0]->order_status=='rtc'?'checked':''}}>
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description text-info"><strong>Self-Pickup</strong></span>
                                </label>
                                <label class="custom-control custom-radio mb-0">
                                    <input id="radio2" name="order_status" type="radio" class="custom-control-input" value="hold" {{$order[0]->order_status=='hold'?'checked':''}}>
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description text-warning"><strong>Freeze the Order</strong></span>
                                </label>
                                <label class="custom-control custom-radio mb-0">
                                    <input id="radio2" name="order_status" type="radio" class="custom-control-input" value="cod" {{$order[0]->order_status=='cod'?'checked':''}}>
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description text-danger"><strong>Cash On Delivery</strong></span>
                                </label>
                                
                                <div class="checkbox checkbox-purple">
                                                    <input id="partial_ship" name="partial_ship" type="checkbox" value="1" {{$order[0]->partial_ship=='1'?'checked':''}}>
                                                    <label for="checkbox6a">
                                                        <span class="text-purple"><strong>Partial ship</strong></span>
                                                    </label>
                                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12">
                    <div class="card-box table-responsive">
                        <table id="customer" class="table table-striped table-bordered table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>SN.</th>
                                    <th>Image</th>
                                    <th>Product name</th>
                                    <th>Ord/Rem Qty.</th>
                                    <th>Disp. Qty.</th>
                                    
                                    <th>Ship Qty.</th>
                                    <th>Current Position</th>
                                    <th>U.Price (Full/Ins)</th>
                                    <th>Total</th>
                                    <th>ETA</th>
                                    <th>Shipping Status</th>
                                    <th>Payment Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $subtotal = 0; $total_quantity = 0; $i=1;?>
                            @foreach($order_items as $order_item)
                            	<?php 
								
								$subtotal = $subtotal+$order_item['total_amount'];
									  $total_quantity=$total_quantity+$order_item['quantity'];
								?>
                                <tr id="item_row_{{$order_item['order_item_id']}}">
                                    <td>{{$i++}}</td>
                                    <td><a href="javascript:void();"><img src="{{asset('public/product_image/thumbnail_images')}}/{{$order_item['image_name1']}}" alt="" width="35"><input type="hidden" name="item_quantity[]" value="{{$order_item['quantity']}}" }} /><input type="hidden" name="item_total_amount[]" value="{{$order_item['total_amount']}}" }} /><input type="hidden" name="update_ship_quantity[]" value="{{$order_item['ship_quantity']}}" id="update_ship_quantity_{{$order_item['order_item_id']}}" />
                                 <input type="hidden" name="update_unit_price[]" value="{{$order_item['product_price']}}" id="update_unit_price_{{$order_item['order_item_id']}}" />   
                                    <input type="hidden" name="order_item_id[]" value="{{$order_item['order_item_id']}}" /></a>
                                    </td>
                                    <td>{{$order_item['product_name']}} ({{$order_item['product_code']}})</td>
                                    <td>{{$order_item['quantity']}} /{{$order_item['quantity'] - $order_item['dispatch_quantity']}} </td>
                                    <td>{{$order_item['dispatch_quantity']}}</td>
                                    <td id="column_ship_quantity_{{$order_item['order_item_id']}}">{{$order_item['ship_quantity']}}</td>
                                    <td>
                                    <?php
									$uk_avl = $order_item['quantity'];
									 if($order_item['s_from']=='my_stock'){
									 	echo 'MY Stock-'.$order_item['quantity'];
									 }elseif($order_item['shipping_location_id']!=129){
									 	if($order_item['s_from']=='my_stock'){
											echo 'MY Stock-'.$order_item['quantity'];
										}else{
											echo 'UK Stock-'.$order_item['quantity'];
										}
									 }else{
										$shipment_list =  DB::table('shipment_order_item')->select('*')->where('order_items_id',$order_item['order_item_id'])->get();
										$showed_dispatch='0';
										 foreach($shipment_list as $shipment_info ){
											 $uk_avl = $uk_avl - $shipment_info->ship_quantity;
										 if($shipment_info->is_arrived==0 && $shipment_info->is_dispatch==0){
										 $shipment = App\Shipment::select('shipment_number','shipment_date','bl_awb_number','carrier_details','created_by')->where('id',$shipment_info->shipment_id)->first(); ?>
										<div class="dropdown">
									   <a style="text-decoration:underline;" href="javascript:void();" class="dropbtn"><span>{{$shipment->shipment_number}}</span></a>
									<?php 
									$date2=date_create($shipment->shipment_date);
									$date3 = date_create(date('Y-m-d'));
									$diff2=date_diff($date2,$date3);
									
								?>
								
									<div class="dropdown-content">
										<ul class="main-menu text-left">
											<li><pre><h6>Tracking information</h6></pre></li>
											<li><pre>{{$shipment->shipment_number}} </pre></li>
											<li><pre>Remaining <?php echo $rem =$diff2->format("%a")-1;?> Days</pre></li>
											<li><pre>BL : {{$shipment->bl_awb_number}} </pre></li>
											<li><pre>Carrier : {{$shipment->carrier_details}} </pre></li>
											<li><pre>Quantity : {{$shipment_info->ship_quantity}} </pre></li>
										</ul>
									 </div>
									</div><br />
									
										 <?php
											 }else if($shipment_info->is_dispatch==1){
												 if($showed_dispatch=='0'){
													 echo 'Dispatch -'.$order_item['dispatch_quantity'].'<br>';
													 $showed_dispatch='1';
												 }
												 
										     }else{
												echo 'MY Stock-'.$shipment_info->ship_quantity.'<br>'; 
											 }
										 }
										 if($uk_avl>0){
											 echo 'UK Stock-'.$uk_avl;
										 }
									 }
									 ?>
                                   
                                    
                            </td>
                            
                            
                            
                                    <td id="column_unit_price_{{$order_item['order_item_id']}}">(RM) {{$order_item['product_price']}} </td>
                                    <td id="line_total_price_{{$order_item['order_item_id']}}">(RM) {{$order_item['total_amount']}} </td>
                                    <td>{{date("d-m-Y", strtotime($order[0]->est_delivery_date) )}}</td>
                                    <td>
                                   @if($order_item['s_from']=='my_stock' || $order_item['shipping_location_id']!=129)
                                   <span class="badge badge-info badge-pill">Ready</span>
                                   @elseif(count($shipment_list)>0)
                                   
                                        <?php
										 $showed_dispatch1='0';
									    foreach($shipment_list as $shipment_info ){ 
									      if($shipment_info->is_arrived!=0 &&$shipment_info->is_dispatch==0){
										   echo '<span class="badge badge-info badge-pill">Ready</span> ';	     }
										   else if($shipment_info->is_dispatch==1){
												 if($showed_dispatch1=='0'){
													 echo '<span class="badge badge-danger badge-pill">Dispatched </span> ';	 
													 $showed_dispatch1='1';
												 }
												 
										     }else{
										    echo '<span class="badge badge-danger badge-pill">In Transist </span> ';	   
										   }
										 }
										 if($uk_avl>0){
											 echo '<span class="badge badge-info badge-pill">Not Ready</span>';
										 }
										?>
                                        </td>
                                        
                                   @else
                                        <span class="badge badge-info badge-pill">Not Ready</span></td>
                                   @endif
                                    <td>@if(($order[0]->order_total - $total_paid)>0)<span class="badge badge-danger badge-pill">Due</span>@else<span class="badge badge-success badge-pill">Paid</span> @endif</td>
                                    <td>
                                    
                                    @if($order[0]->is_cancel!=1 && $order[0]->is_done!=1)
                                     	@hasanyrole('Super-Admin')
                                        <a href="javascript:void('0');" title="Edit Quantity" onclick="edit_ship_quantity({{$order_item['order_item_id']}})"><img src="{{ asset('public/icons/edit.png')}}" alt=""></a>
                                     	@endhasanyrole
                                        @if(count($order_items)>1)
                                        <a href="javascript:void('0');" onclick="detele_item_row({{$order_item['order_item_id']}})" class="delete_item_row" title="Cancel Order"><img src="{{ asset('public/icons/delete.png')}}" alt=""></a>@endif
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Cost of Product :</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><span class="badge badge-success badge-pill" id="subtotal_badge">(RM) {{number_format((float)$subtotal, 2, '.', '')}}</span></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Airfreight :</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><span class="badge badge-success badge-pill">(RM) {{$order[0]->total_airfreight_cost}}<input type="hidden" id="total_airfreight_cost" value="{{$order[0]->total_airfreight_cost}}" /></span></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Local Postage :</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><span class="badge badge-success badge-pill" id="total_local_postage_cost_badge" ondblclick="show_postage_box();">(RM) {{$order[0]->total_local_postage_cost}}</span><input type="number"   name="total_local_postage_cost_input" id="total_local_postage_cost_input" class="form-control" required="required" min="0" data-parsley-min-message="Minimum 1" data-parsley-required-message="Set cost" onblur="set_local_postage_cost(this)"  value="{{$order[0]->total_local_postage_cost}}" style="display:none; width:120px;"/></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Sub Total :</th>
                                    <th><span class="badge badge-success badge-pill" id="quantity_badge">{{$total_quantity}}</span><input type="hidden" name="total_item_quantity" id="total_item_quantity" value="{{$total_quantity}}" /><input type="hidden" name="get_amount_penalty" value="{{$order[0]->amount_penalty}}" id="get_amount_penalty" /></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><span class="badge badge-success badge-pill" id="final_total">(RM) {{number_format(($order[0]->order_total), 2, '.', ',')}}</span><input type="hidden" id="total_final_total" name="total_final_total"value="{{$order[0]->order_total}}" /></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                <?php 
									if($order[0]->amount_penalty >0){
										$mytotal = $order[0]->amount_penalty + ($subtotal+$order[0]->total_airfreight_cost+$order[0]->total_local_postage_cost);
								?>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Penalty :</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><span class="badge badge-danger badge-pill">(RM) {{$order[0]->amount_penalty}}</span></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                <?php
									}else{
										$mytotal =  ($subtotal+$order[0]->total_airfreight_cost+$order[0]->total_local_postage_cost);
									}?>
                                
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Total :</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><span class="badge badge-success badge-pill" id="new_final_total">(RM) {{number_format($mytotal, 2, '.', ',')}}</span></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Paid :</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>
                                    <span class="badge badge-success badge-pill">Paid {{number_format($total_paid, 2, '.', ',')}}</span><input type="hidden" name="total_paid" id="total_paid" value="{{$total_paid}}"  /></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Balance :</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>
                                   <span class="badge badge-danger badge-pill" id="balance">(RM) {{number_format($mytotal - $total_paid, 2, '.', ',')}}</span></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                
                             	
                                
                                
                            </tbody>
                        </table>
                    </div>
                    
                     @if($order[0]->is_cancel!=1 && $order[0]->is_done != 1)
                    <a onclick="cancelOrder({{$order[0]->id}})" href="javascript:void();" class="btn btn-danger waves-light waves-effect w-md">Cancel Order</a>
                    
					   <a  href="#Penalty" data-toggle="modal" data-target=".Penalty" class="btn btn-danger waves-light waves-effect w-md">Penalty</a>
                    <button type="submit" name="update_order" class="btn btn-success waves-light waves-effect w-md" >Update Order</button>
                    
                     <a class="noteButton" href="#historyNote" data-toggle="modal" data-target=".historyNote" title="View Note" s_id="{{$order[0]->id}}" source_id="{{ encrypt($order[0]->id) }}"source_type="order"><img src="{{asset('public/history.png')}}" alt=""></a>
			
			         @if(Auth::user()->hasRole('Super-Admin'))
                                      <?php 
									  $login_user_id= Auth::user()->id;
									  ?>
                                        <?php 
									   $unread_note_count = \App\Note::where('source_type','order')->where('source_id',$order[0]->id)->where('admin_read_status',0)->where('created_by','<>',$login_user_id)->count();
									   ?>
                                       <span class="badge badge-danger badge-pill unread_count" id="quantity_badge{{ $order[0]->id}}" style="background-color:#f60303;">{{$unread_note_count}}</span>
                                       @endif 
                                       
                                       @if(Auth::user()->hasRole('Sales-Agent'))
                                        <?php 
									  $login_user_id= Auth::user()->id;
									  ?>
                                        <?php 
									   $unread_note_count = \App\Note::where('source_type','order')->where('source_id',$order[0]->id)->where('notify','<>',0)->where('acknow_sales_agent',0)->where('created_by','<>',$login_user_id)->count();
									   ?>
                                       <span class="badge badge-danger badge-pill unread_count" id="quantity_badge{{ $order[0]->id}}" style="background-color:#f60303;">{{$unread_note_count}}</span>
                                       @endif 
                                       
                                       @if(Auth::user()->hasRole('Dispatch-Manager'))
                                      <?php 
									  $login_user_id= Auth::user()->id;
									  ?>
                                        <?php 
									   $unread_note_count = \App\Note::where('source_type','order')->where('source_id',$order[0]->id)->where('notify','<>',0)->where('acknow_dispatch',0)->where('created_by','<>',$login_user_id)->count();
									   ?>
                                       <span class="badge badge-danger badge-pill unread_count" id="quantity_badge{{ $order[0]->id}}" style="background-color:#f60303;">{{$unread_note_count}}</span>
                                       @endif 
                     
			
                     
                     <a  href="{{url('manage_payment')}}/{{encrypt($order[0]->id)}}"  class="btn btn-success waves-light waves-effect w-md" style="float:right">Payment</a>
                     
                      <a  href=".customer_address" data-toggle="modal" data-target=".customer_address" class="btn btn-danger waves-light waves-effect w-md" style="float:right; margin-right:5px;">change address</a>
                      {{--
                      @if($order[0]->order_tab==1 && $order[0]->is_cancel==0)
                      <a  href="{{'order_move_to_tab2'}}/{{encrypt($order[0]->id)}}"  class="btn btn-danger waves-light waves-effect w-md" style="float:right;margin-right:5px;">Move to default order</a>
                      @endif
                      --}}
                      @endif
                      
                </div>
                
               <div class="modal fade customer_address" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none; z-index: 9999;">
                    <div class="modal-dialog" >
                        <div class="modal-content" style="width:140%;">
                            <div class="modal-header">
                                <button type="button"  class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h5 class="modal-title" id="myLargeModalLabel">History Note</h5>
                            </div>
                            <div class="modal-body">
                            
                                <table id="multiple_add_table" class="table table-striped table-bordered table-hover table-sm" style="width: 100%;">
                            <thead>
                                <tr>
                               	 	<th>#</th>
                                    <th>Full Name</th>
                                    <th>Address</th>
                                    <th>Email</th>
                                    <th>Phone Number</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
							$customer_address = \App\CustomerAddress::where('customer_id',$order[0]->customer_id)->get();
							?>
                            @for($i=0;$i < $customer_address->count();$i++)
                            	
                                <tr id='row{{$i}}' >
                                <td >  <label class="custom-control custom-radio mb-0"><input  name="selected_customer_address" type="radio" class="custom-control-input" value="{{$customer_address[$i]->id}}" {{$order[0]->customer_address_id==$customer_address[$i]->id?'checked':''}} ><span class="custom-control-indicator"></span>
                                   </label></td>
                                <td>{{$customer_address[$i]->customer_full_name}}</td>
                                
                                <td>{{$customer_address[$i]->address_1}}, {{$customer_address[$i]->address_2}}, {{$customer_address[$i]->address_3}},{{$customer_address[$i]->city}},{{$customer_address[$i]->postal_code}},{{$customer_address[$i]->state}},{{ $customer_address[$i]->country[0]->nicename }}</td>
                                
                                
                                <td>{{$customer_address[$i]->email}}</td><td>{{$customer_address[$i]->mobile}}</tr>
                            @endfor
                                
                            </tbody>
                        </table>
                            </div>
                           
                        </div>
                    </div>
				</div>
                
                 {{ Form::close() }}
                
                <!--Edit Quantity Modal-->
                <div id="editQuantity" class="modal-demo">
                    <button type="button" class="close" onclick="Custombox.close();">
                    <span>&times;</span><span class="sr-only">Close</span>
                    </button>
                    <h4 class="custom-modal-title">Edit </h4>
                    <div class="custom-modal-text">
                       
                            
                            
                            @hasanyrole('Super-Admin')
                             <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="inputEmail4" class="col-form-label">Unit price:</label>
                                    <input class="form-control" id="field_unit_price" value="0" type="number">
                                    <input type="hidden" id="get_order_item_id" />
                                </div>
                            </div>
                            @endhasanyrole
                            <hr>
                            <div class="form-group text-right m-b-0">
                                <button class="btn btn-primary waves-effect waves-light" type="button" onclick="set_ship_quantity()">
                                Save Changes
                                </button>
                                <button type="reset" class="btn btn-light waves-effect m-l-5">
                                Close
                                </button>
                            </div>
                        
                    </div>
                </div>
                
                
           
             
                      <!--Cancel Order  Modal-->
                    <div class="modal fade show" id="cancelOrder" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"  style="display: none; z-index: 9999;">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" onclick="cancelOrderClose('cancelOrder')" data-dismiss="modal" aria-hidden="true">×</button>
                                <h5 class="modal-title" id="myLargeModalLabel">Cancel Order001</h5>
                            </div>
                            <div class="modal-body">
                                @hasanyrole('Super-Admin')
                                {!! Form::open(['url' => 'cancel_order_and_refund','id'=>'form_cancelRefund_submit','class'=>'']) !!}	
			                    
                                <div class="form-group row">
                                      
                                   <div class="col-md-6">
                                       <label for="">Order Amount</label>
                                       {{ Form::text('show_order_amount',number_format(($subtotal+$order[0]->total_airfreight_cost+$order[0]->total_local_postage_cost), 2, '.', ''),[ 'class' =>'form-control','id'=>'show_order_amount',  "readonly "]) }}
                                   </div>
                                   <div class="col-md-6">
                                   		<label for="">Penalty Amount</label>
                                        {{ Form::text('show_penalty_amount',($order[0]->amount_penalty),['class' =>'form-control','id'=>'show_penalty_amount','data-val'=>$order[0]->amount_penalty,"readonly "]) }}
                                   </div>
                            	</div>
                                
                                  @if($is_paid=='1')
                                     <div class="form-group">
                                      <label for="">Payment Amount</label>
                                        
                                         {{ Form::text('amount_penalty',$p_amount,['class' =>'form-control', "readonly"]) }}
                                     </div>
                                     <div class="form-group">
                                      <label for="">Refund Amount</label>
                                         {{ Form::text('amount_Refund',number_format(($p_amount - $order[0]->amount_penalty), 2, '.', ''),['placeholder' => ' amount','parsley-trigger' => 'change' , 'class' =>'form-control numeric','autocomplete' => 'off', 'id'=>'amount_Refund', 'data-val'=>$p_amount - $order[0]->amount_penalty, 'onchange'=>'check_refund_amount(this)','data-parsley-maxlength'=>'14','maxlength'=>'14' ,'data-parsley-required-message'=>'Please Enter amount']) }}
                                     </div>
                                     @else
                                         {{ Form::hidden('amount_penalty','0.00',[]) }}
                                    @endif 
                                   @else
                                   {!! Form::open(['url' => 'request_cancel_order_and_refund','id'=>'form_cancelRefund_submit','class'=>'']) !!}
                                   	{{ Form::hidden('amount_penalty','0.00',[]) }}
                                   @endhasanyrole
                                    <div class="form-group">
                                      <label for="">Note's<span class="text-danger"> *</span></label>
                                         {{ Form::textarea('source_comment','',['placeholder' => 'Cancel Note','rows'=>'2','parsley-trigger' => 'change' , 'class' =>'form-control','autocomplete' => 'off', 'id'=>'source_comment','data-parsley-required-message'=>'Please Enter Note','required']) }}
                                    </div>
                                
                                    <div class="w-100 d-flex">
                                    <div class="col-md-6 p-0">
                                           
                                      {{ Form::hidden('source_type','order',['placeholder' => ' amount','parsley-trigger' => 'change' , 'class' =>'form-control','autocomplete' => 'off', 'id'=>'source_type','data-parsley-required-message'=>'Please Enter amount']) }}
                                      
                                      {{ Form::hidden('source_id',$order[0]->id,['placeholder' => ' amount','parsley-trigger' => 'change' , 'class' =>'form-control','autocomplete' => 'off', 'id'=>'source_id' ,'data-parsley-required-message'=>'Please Enter amount']) }}
                                      
                                      {{ Form::hidden('source_total',$order[0]->order_total,['placeholder' => ' amount','parsley-trigger' => 'change' , 'class' =>'form-control','autocomplete' => 'off', 'id'=>'source_total' ,'data-parsley-required-message'=>'Please Enter amount']) }}
                                       
                                        <button  class="btn btn-primary waves-effect waves-light" type="submit" id="submit" >Submit</button>   
                                    </div>
                                    
                                </div> 
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                    </div>
                     <!-- end row -->
                
                    </div> <!-- container -->
                    </div>
                    
                     <!-- content -->
		             <!--penlty  Modal-->
                     <div class="modal fade Penalty" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none; z-index: 9999;">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h5 class="modal-title" id="myLargeModalLabel">Add Penalty</h5>
                            </div>
                            <div class="modal-body">
                                
                                {!! Form::open(['url' => 'add_order_penalty','id'=>'form_penalty_submit','class'=>'']) !!}	
			                     {{ csrf_field() }}
                                    <div class="form-group">
                                      <label for="">Amount<span class="text-danger"> *</span></label>
                                         {{ Form::text('amount_penalty',$order[0]->amount_penalty,['placeholder' => ' amount','parsley-trigger' => 'change' , 'class' =>'form-control','autocomplete' => 'off', 'id'=>'amount_penalty','data-parsley-maxlength'=>'14','maxlength'=>'14' ,'data-parsley-required-message'=>'Please Enter amount','required']) }}
                                    </div>
                                    <div class="form-group">
                                      <label for="">Note's</label>
                                         {{ Form::textarea('source_comment','',['placeholder' => 'Penalty Note','rows'=>'2','parsley-trigger' => 'change' , 'class' =>'form-control','autocomplete' => 'off', 'id'=>'source_comment','data-parsley-required-message'=>'Please Enter Note']) }}
                                    </div>
                                
                                    <div class="w-100 d-flex">
                                    <div class="col-md-6 p-0">
                                           
                                      {{ Form::hidden('source_type','order',['placeholder' => ' amount','parsley-trigger' => 'change' , 'class' =>'form-control','autocomplete' => 'off', 'id'=>'source_type','data-parsley-required-message'=>'Please Enter amount']) }}
                                      
                                      {{ Form::hidden('source_id',$order[0]->id,['placeholder' => ' amount','parsley-trigger' => 'change' , 'class' =>'form-control','autocomplete' => 'off', 'id'=>'source_id' ,'data-parsley-required-message'=>'Please Enter amount']) }}
                                      
                                      {{ Form::hidden('source_total',$order[0]->order_total,['placeholder' => ' amount','parsley-trigger' => 'change' , 'class' =>'form-control','autocomplete' => 'off', 'id'=>'source_total' ,'data-parsley-required-message'=>'Please Enter amount']) }}
                                     <button  class="btn btn-primary waves-effect waves-light" type="submit" id="submit" >Submit</button>   
                                    </div>
                                    
                                </div> 
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                    </div>
                    
                   <!-- end row -->
                    <footer class="footer text-right">
                        2018 © UKSHOP .
                    </footer>
                </div>
                <!-- ============================================================== -->
                <!-- End Right content here -->
                <!-- ============================================================== -->
                
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
                                        <input type="hidden"   name="source_type" id="source_type_1"  value="payment" />
                                        <input type="hidden"  name="source_id" id="source_id_1"  value="" />
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
<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script> 
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.colVis.min.js"></script> 
<script src="{{asset('public/main_theme/js/sweetalert2.min.js')}}"></script>
                
                
                <script type="text/javascript">
					
$(document).ready(function() {
	$('#form_cancelRefund_submit').parsley();
	$('#form_penalty_submit').parsley();
});

                $(document).ready(function() {
                var table = $('#customer').DataTable( {
                // scrollY:        "400px",
                scrollX:        true,
                scrollCollapse: true,
                paging:         true,
                "ordering": false,
                "columnDefs": [ {
                "targets": 0,
                "width": "20px"
                },
                {
                "targets": 1,
                "width": "50px"
                },
                {
                "targets": 2,
                "width": "110px"
                },
                {
                "targets": 3,
                "width": "50px"
                },
                {
                "targets": 4,
                "width": "30px"
                },
                {
                "targets": 5,
                "width": "30px"
                },
                {
                "targets": 6,
                "width": "140px"
                },
                {
                "targets":7,
                "width": "70px"
                },
                {
                "targets":8,
                "width": "80px"
                },
                {
                "targets":9,
                "width": "90px"
                },
                {
                "targets":10,
                "width": "70px"
                },
                {
                "targets":11,
                "width": "10px"
                },
                {
                "targets":12,
                "width": "50px"
                }],
                fixedColumns: true
                } );
                } );
				
				
function  edit_ship_quantity(order_item_id){
	
			$('#field_ship_quantity').val($('#update_ship_quantity_'+order_item_id).val());
			$('#field_unit_price').val($('#update_unit_price_'+order_item_id).val());
			$('#get_order_item_id').val(order_item_id);
  Custombox.open({
                target: '#editQuantity',
                effect: 'fadein',
				width: '800'
            });
            
}

function set_ship_quantity(){
	var order_item_id = $('#get_order_item_id').val();
	$('#update_ship_quantity_'+order_item_id).val($('#field_ship_quantity').val());
	$('#column_ship_quantity_'+order_item_id).text($('#field_ship_quantity').val());
	var unit_price = $('#field_unit_price').val();
	$('#update_unit_price_'+order_item_id).val(unit_price);
	$('#column_unit_price_'+order_item_id).text('(RM) '+ unit_price);
	
	var quantity = $("#item_row_"+order_item_id+" input[name='item_quantity[]']" ).val();
	var line_total = parseInt(unit_price*quantity).toFixed(2);
	
	$("#item_row_"+order_item_id+" input[name='item_total_amount[]']" ).val(line_total);
	$("#line_total_price_"+order_item_id).text('(RM) '+ line_total);
	Custombox.close();
	calculate_total();
}

function detele_item_row(item_id) {
	
	swal({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#4fa7f3',
			cancelButtonColor: '#d57171',
			confirmButtonText: 'Yes, delete it!'
		}).then(function () {
			
			$('#item_row_'+item_id).replaceWith('<input type="hidden" name="delete_order_item[]" value="'+item_id+'" />');
			
			swal(
							'Removed!',
							'Item Removed.',
							'success'
						);
						if($('.delete_item_row').length < 2){
							$('.delete_item_row').hide();
						}
						calculate_total();
						show_postage_box();
			},
	 function (dismiss) {		 
		if (dismiss === 'cancel') {
			swal(
				'Cancelled',
				'Your Item is safe :)',
				'error'
			)
		}
		
	})	
}	

function show_postage_box(){
	$('#total_local_postage_cost_badge').hide();
	$('#total_local_postage_cost_input').show();	
}

function set_local_postage_cost(e){
	var cost = $(e).val();
	$('#total_local_postage_cost_badge').text('(RM) '+parseInt(cost).toFixed(2));
	$('#total_local_postage_cost_badge').show();
	$('#total_local_postage_cost_input').hide();
	calculate_total();
}

function calculate_total(){
	var show_quantity = 0;
	var sub_total = 0;
	var final_total = 0;
	$("input[name='item_quantity[]']").map(function(){
		
              show_quantity = parseInt(show_quantity)+parseInt($(this).val());
     }).get();
	 
	 $("input[name='item_total_amount[]']").map(function(){
		
			 sub_total = (parseInt(sub_total)+ parseInt($(this).val())).toFixed(2);
	}).get();
	
	var amount_penalty = $('#get_amount_penalty').val();
	
	var total_airfreight_cost = $('#total_airfreight_cost').val();
	var total_paid = $('#total_paid').val();
	var total_local_postage_cost_input = $('#total_local_postage_cost_input').val();
	var final_total = (parseInt(sub_total)+ parseInt(total_airfreight_cost)+ parseInt(total_local_postage_cost_input)).toFixed(2);
	var new_final_total = (parseInt(final_total) + parseInt(amount_penalty)).toFixed(2);
	var balance = (parseInt(new_final_total) - parseInt(total_paid)).toFixed(2);
	$('#quantity_badge').text(show_quantity);
	$('#total_item_quantity').val(show_quantity);
	$('#subtotal_badge').text('(RM) '+sub_total);
	$('#final_total').text('(RM) '+final_total);
	$('#total_final_total').val(final_total);
	$('#new_final_total').text('(RM) '+ new_final_total);
	$('#balance').text('(RM) '+ balance);
	
}
					
/*code order cancel*/
					function cancelOrder(item_id) {
	swal({
			title: 'Are you sure?',
			text: "You won't be able to cancel this!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#4fa7f3',
			cancelButtonColor: '#d57171',
			confirmButtonText: 'Yes, cancel it!'
		}).then(function () {
			$('#cancelOrder').addClass('show'); 
			$("#cancelOrder").css("display", "block");
		
		},
	 function (dismiss) {		 
		if (dismiss === 'cancel') {
			swal(
				'Cancelled',
				'Your Order is safe :)',
				'error'
			)
		}
	})	
}	

function cancelOrderClose(modelid)
{
	$('#'+modelid).removeClass('show');
	$('#'+modelid).css("display", "none");
	
}
	
	
	
/*cut and paste*/
$(document).on('click','.noteButton',function(event){
	var source_id =$(this).attr('source_id');
	var s_id =$(this).attr('s_id');
	$('#quantity_badge'+s_id).html(0);	
	var source_type =$(this).attr('source_type');
	$('#source_id_1').val(source_id);	
	$('#source_type_1').val(source_type);	
	getNotesHistory(source_id,source_type);
	
});
$(document).ready(function() {
$('#form_submit').on('submit',function(e){  
		 $.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 }
		 })	
		// alert('vvvv');
		 var url= APP_URL+'/ajax_add_source_notes';
		 e.preventDefault(e);
			$.ajax({
			type:"POST",
			url: url,
			data:$(this).serialize(),
			//dataType: 'json',
			success: function(data){  //alert('vvv'); console.log(data); //exit();
				$('#source_comment').val('');
			    var source_id =$('#source_id_1').val();	
	            var source_type = $('#source_type_1').val();	
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

$('.numeric').on('keypress',function (event) {
	return isNumber(event, this)
    if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
        event.preventDefault();
    }
    var input = $(this).val();
    if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 2)) {
        event.preventDefault();
    }
});

function isNumber(evt, element) {

var charCode = (evt.which) ? evt.which : event.keyCode

if (
	//(charCode != 45 || $(element).val().indexOf('-') != -1) &&      // “-” CHECK MINUS, AND ONLY ONE.
	     // “.” CHECK DOT, AND ONLY ONE.
	(charCode < 48 || charCode > 57))
	return false;

return true;
}

function check_refund_amount(e){

var current_refund_val = $(e).val();
var old_refund_val = $(e).data('val');
if(current_refund_val > old_refund_val){
	
	$(e).val(parseInt(old_refund_val).toFixed(2));
}else{
	
	var old_penalty  = $("#show_penalty_amount").data('val');
	 
	
	var final = (parseInt(old_refund_val-current_refund_val) + parseInt(old_penalty)).toFixed(2);
	$('#show_penalty_amount').val(final);
	$(e).val(parseInt(current_refund_val).toFixed(2));
}

}				
</script>
@append