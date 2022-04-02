@extends('layouts.master')
@section('css')
<link href="{{asset('public/main_theme/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />

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
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title float-left">Manage Order</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="{{url('order')}}">Order</a></li>
                            <li class="breadcrumb-item active">Manage Order</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->
            <div class="row">
                <div class="col-md-12 text-right mb-2">
                    <div class="card-box">
                   @hasanyrole('Super-Admin|Sales-Agent')
                        <a href="{{url('order/create')}}" class="btn btn-primary waves-light waves-effect w-md">
                        <i class="mdi mdi-plus"></i> Add Order</a>
                        @endhasanyrole
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card-box">
                        <ul class="nav nav-tabs">
                            <li class="nav-item"> <a href="#home-b1" data-toggle="tab" aria-expanded="false" class="nav-link active"> <h5>General Orders</h5> </a> </li>
                            <li class="nav-item"> <a href="#profile-b1" data-toggle="tab" aria-expanded="true" class="nav-link"><h5>Default Orders </h5> </a> </li>
                            <li class="nav-item"> <a href="#messages-b1" data-toggle="tab" aria-expanded="false" class="nav-link"><h5> Take Action  </h5> </a> </li>
                            <li class="nav-item"> <a href="#cancel-b1" data-toggle="tab" aria-expanded="false" class="nav-link"><h5> Cancel Orders</h5> </a> </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade active show" id="home-b1">
                                <div class="table-responsive">
                                    <table id="general_orders" class="table table-striped table-bordered table-hover table-sm w-100">
                                        <thead>
                                            <tr>
                                            	<th>S.No.</th>
                                                <th>Order Date</th>
                                                <th>Created by</th>
                                                <th>Order ID</th>
                                                <th>Customer Name</th>
                                                <th>Total Items</th>
                                               <!-- <th>Ship Qty.</th>-->
                                                <th>Total Price</th>
                                                <th>Payment</th>
                                                <th>Available</th>
                                                <th >Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i=1;?>
                                        @foreach($general_orders as $general_order)
                                            <tr>
                                            	<td>{{$i++}}</td>
                                                <td>{{date('d/m/Y',strtotime($general_order->order_date))}}</td>
                                                <td>{{$general_order->created_name}}</td>
                                                <td>{{$general_order->order_code}}</td>
                                                <td>{{$general_order->customer_name}}</td>
                                                <td>{{$general_order->total_item}}</td>
                                                <!--<td>{{$general_order->total_ship_quantity}}</td>-->
                                                <td>(RM) {{$general_order->order_total}}</td>
                                                <?php
												$is_ready = 0;
                                             $total_paid = DB::select("SELECT SUM(`amount`) as `myamount` FROM `payment_lines` as `pl` JOIN `payments` as `p`  ON `p`.`id` = `pl`.`payment_id` AND `p`.`payment_status` ='Verified'  WHERE `order_id` =$general_order->id");
											 if($total_paid[0]->myamount==null){
												$total_paid=0.00; 
											 }else{
												 $total_paid=$total_paid[0]->myamount;
											 }
											
                                             $check_dispatch_count = \App\OrderItem::where('order_id',$general_order->id)->where(function ($q) {
    $q->where('dispatch_ready',0)->orWhere('dispatch_ready',2);
})->count();
											 ?>  
                                                <!--Payment status-->
                                                <td>
                                                @if($total_paid>0)
                                                 	<span class="badge badge-success badge-pill">Paid {{number_format($total_paid, 2, '.', ',')}}</span>
                                                @endif
                                                
                                                <span class="badge badge-danger badge-pill">Due {{number_format(($general_order->order_total + $general_order->amount_penalty )- $total_paid, 2, '.', ',')}}</span></td>
                                                
                                                 
                                               <!--shipping status-->	
                                                 <td>
                                                <?php if($general_order->s_from=='my_stock' || $general_order->shipping_type_id==3 || $general_order->shipping_location_id!=129){
													echo '<span class="badge badge-success badge-pill">Ready</span>';$is_ready = 1;
												}else{
                                                 
												  $total_arrive =  DB::table('shipment_order_item')->select(DB::raw('if( SUM(`shipment_order_item`.`ship_quantity`) IS NULL ,"0",SUM(`shipment_order_item`.`ship_quantity`))as `total_ship_quantity`'))->join('order_items', 'shipment_order_item.order_items_id', '=', 'order_items.id') ->where('order_items.order_id',$general_order->id)->where('shipment_order_item.is_arrived',1)->get();
										$final_arrive =  $total_arrive[0]->total_ship_quantity-$general_order->total_pending_quantity;
			                                      if($final_arrive ==$general_order->total_item){
												  echo '<span class="badge badge-success badge-pill">Ready</span>';                        $is_ready = 1; 
												  
												  }
												  elseif($final_arrive>0)
												  {
													echo '<span class="badge badge-info badge-pill">Partially-Ready</span> ';  				
													if($general_order->partial_ship==1){
														 $is_ready = 1;
													 }
												  }else{
													 echo '<span class="badge badge-danger badge-pill">Not Ready</span>'; 
												  }
												}
												  ?>
                                                                                         
                                                </td>
                                                 <!--Delivery type-->
                                                <td>@if($general_order->order_status=='new')
                                                @if(($total_paid==($general_order->order_total + $general_order->amount_penalty ) && $is_ready == 1) || $general_order->is_force_active==1)
                                                @hasanyrole('Super-Admin|Dispatch-Manager')<a href="{{url('dispatch_order')}}/{{encrypt($general_order->id)}}"><span class="pulse pulse-green mt-2">RTS</span></a>
                                                @else
                                                <a href="javascript:void(0)"><span class="pulse pulse-green mt-2">RTS</span></a>
                                                @endhasanyrole
                                                
                                                @else<span class="pulse pulse-new mt-2">NEW</span>@endif
                                                @elseif($general_order->order_status=='hold')<span class="pulse pulse-hold mt-2" style="animation:unset;">HOLD</span>
                                                @elseif($general_order->order_status=='rtc')
                                                	@if(($total_paid==($general_order->order_total + $general_order->amount_penalty ) && $is_ready == 1) || $general_order->is_force_active==1)
                                                    								@hasanyrole('Super-Admin|Dispatch-Manager')
                                                    <a href="{{url('dispatch_collect_order')}}/{{encrypt($general_order->id)}}"><span class="pulse pulse-turquoise mt-2">RTC</span></a>
                                                    @else
                                                    <a href="javascript:void(0)"><span class="pulse pulse-turquoise mt-2">RTC</span></a>
@endhasanyrole                                                   
                                                    @else<span class="pulse pulse-turquoise mt-2" style="animation:unset;">RTC</span>@endif
                                                
                                                @elseif($general_order->order_status=='rts')
                                                
                                                	@if(($total_paid==($general_order->order_total + $general_order->amount_penalty ) && $is_ready == 1) || $general_order->is_force_active==1)
                                                    @hasanyrole('Super-Admin|Dispatch-Manager')
                                                    <a href="{{url('dispatch_order')}}/{{encrypt($general_order->id)}}"><span class="pulse pulse-green mt-2">RTS</span></a> @else
                                                    <a href="javascript:void(0)"><span class="pulse pulse-green mt-2">RTS</span></a>
@endhasanyrole 

@else<span class="pulse pulse-green mt-2" style="animation:unset;">RTS</span>@endif
                                                
                                                @elseif($general_order->order_status=='cod')
                                                	@if($is_ready == 1 || $general_order->s_from=='my_stock' || $general_order->shipping_type_id==3 || $general_order->shipping_location_id!=129)
                                                    @hasanyrole('Super-Admin|Dispatch-Manager')
                                                    <a href="{{url('order_cash_on_delivery')}}/{{encrypt($general_order->id)}}"><span class="pulse pulse-red mt-2">COD</span></a>
                                                    @else
                                                   <a href="javascript:void(0)"><span class="pulse pulse-red mt-2">COD</span></a>
@endhasanyrole 
@else<span class="pulse pulse-red mt-2" style="animation:unset;">COD</span>
                                                    @endif
                                                    @endif
                                                @if($general_order->cancel_request=='1')
                                                	<span class="badge badge-danger badge-pill">Cancel Request</span>
                                                @endif
                                                    </td>
                                                 <!--actions-->
                                                <td>
                                               <a href="{{ url('order')}}/{{encrypt($general_order->id)}}/edit" title="View Order Details"><img src="{{ asset('public/icons/eye.png')}}" alt=""></a>
                                               
                                               @hasanyrole('Super-Admin|Sales-Agent')
                                              
                                              @if($general_order->is_force_active==1)
                                              <a href="javascript:void('0');"  title="Force Order Activated"><img src="{{ asset('public/icons/activeDot.png')}}" alt=""></a>
                                              @elseif($is_ready == 1 || $general_order->s_from=='my_stock' || $general_order->shipping_type_id==3)
                                               
                                               
                                                 {!! Form::open([
                                                'method'=>'POST',
                                                'url' => '/force_active',
                                                'style' => 'display:inline',
                                                'id'=>'force_active_form_'. $general_order->id
                                            ]) !!}
                                           {{ Form::hidden('force_order_id', $general_order->id) }}
                                           <a href="javascript:void('0');" onclick="force_active({{$general_order->id}})" title="Force Order Active"><img src="{{ asset('public/icons/forceActive.png')}}" alt=""></a>
                                           
                                            {!! Form::close() !!}
                                               @endif
                                               @endhasanyrole
                                                                                             
                                                <a class="noteButton" href="#historyNote" data-toggle="modal" data-target=".historyNote" title="View Note" s_id="{{$general_order->id}}" source_id="{{encrypt($general_order->id)}}"source_type="order"><img src="{{asset('public/history.png')}}" alt=""></a>
													
									 @if(Auth::user()->hasRole('Super-Admin'))
                                      <?php 
									  $login_user_id= Auth::user()->id;
									  ?>
                                        <?php 
									   $unread_note_count = \App\Note::where('source_type','order')->where('source_id',$general_order->id)->where('admin_read_status',0)->where('created_by','<>',$login_user_id)->count();
									   ?>
                                       <span class="badge badge-danger badge-pill unread_count" id="quantity_badge{{ $general_order->id}}" style="background-color:#f60303;">{{$unread_note_count}}</span>
                                       @endif 
                                       
                                       @if(Auth::user()->hasRole('Sales-Agent'))
                                        <?php 
									  $login_user_id= Auth::user()->id;
									  ?>
                                        <?php 
									   $unread_note_count = \App\Note::where('source_type','order')->where('source_id',$general_order->id)->where('agent_read_status',0)->where('created_by','<>',$login_user_id)->count();
									   ?>
                                       <span class="badge badge-danger badge-pill unread_count" id="quantity_badge{{ $general_order->id}}" style="background-color:#f60303;">{{$unread_note_count}}</span>
                                       @endif 
                                       
                                       @if(Auth::user()->hasRole('Dispatch-Manager'))
                                      <?php 
									  $login_user_id= Auth::user()->id;
									  ?>
                                        <?php 
									   $unread_note_count = \App\Note::where('source_type','order')->where('source_id',$general_order->id)->where('dispatch_read_status',0)->where('created_by','<>',$login_user_id)->count();
									   ?>
                                       <span class="badge badge-danger badge-pill unread_count" id="quantity_badge{{ $general_order->id}}" style="background-color:#f60303;">{{$unread_note_count}}</span>
                                       @endif 
									  
                                                
                                                </td>
                                            </tr>
                                          @endforeach
                                            
                                        </tbody>
                                    </table>
                                    <div class="alert alert-primary mt-4 text-center" role="alert"> <strong>COD : Cash on Delivery | RTC : Ready to Collect | RTS : Ready to Ship</strong> </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="profile-b1">
                                <div class="table-responsive">
                                    <table id="default_orders" class="table table-striped table-bordered table-hover table-sm w-100">
                                        <thead>
                                            <tr>
                                                
                                               <tr>
                                               	<th>S.No.</th>
                                                <th>Order Date</th>
                                                <th>Created by</th>
                                                <th>Order ID</th>
                                                <th>Customer Name</th>
                                                <th>Total Items</th>
                                                <!--<th>Ship Qty.</th>-->
                                                <th>Total Price</th>
                                                <th>Payment</th>
                                                <th>Available</th>
                                                <th >Status</th>
                                                <th>Action</th>
                                            </tr>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i=1;?>
                                        @foreach($default_orders as $default_order)
                                            <tr>
                                            	<td>{{$i++}}</td>
                                                <td>{{date('d/m/Y',strtotime($default_order->order_date))}}</td>
                                                <td>{{$default_order->created_name}}</td>
                                                <td>{{$default_order->order_code}}</td>
                                                <td>{{$default_order->customer_name}}</td>
                                                <td>{{$default_order->total_item}}</td>
                                               <!-- <td>{{$default_order->total_ship_quantity}}</td>-->
                                                <td>(RM) {{$default_order->order_total}}</td>
                                                <?php
                                             $total_paid = DB::select("SELECT SUM(`amount`) as `myamount` FROM `payment_lines` as `pl` JOIN `payments` as `p`  ON `p`.`id` = `pl`.`payment_id` AND `p`.`payment_status` ='Verified'  WHERE `order_id` =$default_order->id");
											 if($total_paid[0]->myamount==null){
												$total_paid=0.00; 
											 }else{
												 $total_paid=$total_paid[0]->myamount;
											 }
											
                                             $check_dispatch_count = \App\OrderItem::where('order_id',$default_order->id)->where(function ($q) {
    $q->where('dispatch_ready',0)->orWhere('dispatch_ready',2);
})->count();
											 ?>  
                                                <!--Payment status-->
                                                <td>
                                                @if($total_paid>0)
                                                 	<span class="badge badge-success badge-pill">Paid {{number_format($total_paid, 2, '.', ',')}}</span>
                                                @endif
                                                
                                                <span class="badge badge-danger badge-pill">Due {{number_format(($default_order->order_total + $default_order->amount_penalty )- $total_paid, 2, '.', ',')}}</span></td>
                                                
                                                 <!--shipping status-->	
                                                 <td>
                                                <?php if($default_order->s_from=='my_stock' || $default_order->shipping_type_id==3 || $default_order->shipping_location_id!=129){
													echo '<span class="badge badge-success badge-pill">Ready</span>';$is_ready = 1;
												}else{
                                                 
												  $total_arrive =  DB::table('shipment_order_item')->select(DB::raw('if( SUM(`shipment_order_item`.`ship_quantity`) IS NULL ,"0",SUM(`shipment_order_item`.`ship_quantity`))as `total_ship_quantity`'))->join('order_items', 'shipment_order_item.order_items_id', '=', 'order_items.id') ->where('order_items.order_id',$default_order->id)->where('shipment_order_item.is_arrived',1)->get();
										$final_arrive =  $total_arrive[0]->total_ship_quantity-$default_order->total_pending_quantity;
			                                      if($final_arrive ==$default_order->total_item){
												  echo '<span class="badge badge-success badge-pill">Ready</span>';                        $is_ready = 1; 
												  
												  }
												  elseif($final_arrive>0)
												  {
													echo '<span class="badge badge-info badge-pill">Partially-Ready</span> ';  				
													if($default_order->partial_ship==1){
														 $is_ready = 1;
													 }
												  }else{
													 echo '<span class="badge badge-danger badge-pill">Not Ready</span>'; 
												  }
												}
												  ?>
                                                                                         
                                                </td>
                                                 <!--Delivery type-->
                                                 
                                                <td>@if($default_order->order_status=='new')
                                                @if(($total_paid==($default_order->order_total + $default_order->amount_penalty ) && $check_dispatch_count < 1) || ($total_paid==($default_order->order_total + $default_order->amount_penalty ) && $default_order->s_from=='my_stock') || ($total_paid==($default_order->order_total + $default_order->amount_penalty ) && $default_order->shipping_location_id!=129) || $default_order->is_force_active==1)<a href="{{url('dispatch_order')}}/{{encrypt($default_order->id)}}"><span class="pulse pulse-green mt-2">RTS</span></a>@else<span class="pulse pulse-new mt-2">NEW</span>@endif
                                                @elseif($default_order->order_status=='hold')<span class="pulse pulse-hold mt-2" style="animation:unset;">HOLD</span>
                                                @elseif($default_order->order_status=='rtc')
                                                	@if(($total_paid==($default_order->order_total + $default_order->amount_penalty ) && $check_dispatch_count < 1) || ($total_paid==($default_order->order_total + $default_order->amount_penalty ) && $default_order->s_from=='my_stock') || $default_order->is_force_active==1)<a href="{{url('dispatch_collect_order')}}/{{encrypt($default_order->id)}}"><span class="pulse pulse-turquoise mt-2">RTC</span></a>@else<span class="pulse pulse-turquoise mt-2" style="animation:unset;">RTC</span>@endif
                                                
                                                @elseif($default_order->order_status=='rts')
                                                
                                                	@if(($total_paid==($default_order->order_total + $default_order->amount_penalty ) && $check_dispatch_count < 1) || ($total_paid==($default_order->order_total + $default_order->amount_penalty ) && $default_order->s_from=='my_stock') || $default_order->is_force_active==1)<a href="{{url('dispatch_order')}}/{{encrypt($default_order->id)}}"><span class="pulse pulse-green mt-2">RTS</span></a>@else<span class="pulse pulse-green mt-2" style="animation:unset;">RTS</span>@endif
                                                
                                                @elseif($default_order->order_status=='cod')
                                                	@if($check_dispatch_count < 1 || $default_order->s_from=='my_stock' || $default_order->shipping_type_id==3 || $default_order->shipping_location_id!=129)<a href="{{url('order_cash_on_delivery')}}/{{encrypt($default_order->id)}}"><span class="pulse pulse-red mt-2">COD</span></a>@else<span class="pulse pulse-red mt-2" style="animation:unset;">COD</span>
                                                    @endif
                                                    @endif
                                                @if($default_order->cancel_request=='1')
                                                	<span class="badge badge-danger badge-pill">Cancel Request</span>
                                                @endif
                                                    </td>
                                                 <!--actions-->
                                                <td>
												

                                               <a href="{{ url('order')}}/{{encrypt($default_order->id)}}/edit" title="View Order Details"><img src="{{ asset('public/icons/eye.png')}}" alt=""></a>
                                               
                                               @hasanyrole('Super-Admin|Sales-Agent')
                                              
                                              @if($default_order->is_force_active==1)
                                              <a href="javascript:void('0');"  title="Force Order Activated"><img src="{{ asset('public/icons/activeDot.png')}}" alt=""></a>
                                              @elseif($is_ready == 1 || $default_order->s_from=='my_stock' || $default_order->shipping_type_id==3)
                                               
                                               
                                                 {!! Form::open([
                                                'method'=>'POST',
                                                'url' => '/force_active',
                                                'style' => 'display:inline',
                                                'id'=>'force_active_form_'. $default_order->id
                                            ]) !!}
                                           {{ Form::hidden('force_order_id', $default_order->id) }}
                                           <a href="javascript:void('0');" onclick="force_active({{$default_order->id}})" title="Force Order Active"><img src="{{ asset('public/icons/forceActive.png')}}" alt=""></a>
                                           
                                            {!! Form::close() !!}
                                               @endif
                                               @endhasanyrole
                                                                                             
                                                <a class="noteButton" href="#historyNote" data-toggle="modal" data-target=".historyNote" title="View Note" s_id="{{$default_order->id}}" source_id="{{encrypt($default_order->id)}}"source_type="order"><img src="{{asset('public/history.png')}}" alt=""></a>
                                               <a href="{{url('get_default_order_download_PDF')}}/{{$default_order->id}}/sendemail" data-toggle="tooltip" title="Send Email"><img src="{{ asset('public/icons/email.png')}}" alt=""></a>
												
												<a href="#" title="Send SMS" data-toggle="tooltip"><img src="{{ asset('public/icons/sms.png')}}" alt=""></a>
													
									 @if(Auth::user()->hasRole('Super-Admin'))
                                      <?php 
									  $login_user_id= Auth::user()->id;
									
									   $unread_note_count = \App\Note::where('source_type','order')->where('source_id',$default_order->id)->where('admin_read_status',0)->where('created_by','<>',$login_user_id)->count();
									   ?>
                                       <span class="badge badge-danger badge-pill unread_count" id="quantity_badge{{ $default_order->id}}" style="background-color:#f60303;">{{$unread_note_count}}</span>
                                       @endif 
                                       
                                       @if(Auth::user()->hasRole('Sales-Agent'))
                                        <?php 
									  $login_user_id= Auth::user()->id;
									
									   $unread_note_count = \App\Note::where('source_type','order')->where('source_id',$default_order->id)->where('notify','<>',0)->where('acknow_sales_agent',0)->where('created_by','<>',$login_user_id)->count();
									   ?>
                                       <span class="badge badge-danger badge-pill unread_count" id="quantity_badge{{ $default_order->id}}" style="background-color:#f60303;">{{$unread_note_count}}</span>
                                       @endif 
                                       
                                       @if(Auth::user()->hasRole('Dispatch-Manager'))
                                      <?php 
									  $login_user_id= Auth::user()->id;
								
									   $unread_note_count = \App\Note::where('source_type','order')->where('source_id',$default_order->id)->where('notify','<>',0)->where('acknow_dispatch',0)->where('created_by','<>',$login_user_id)->count();
									   ?>
                                       <span class="badge badge-danger badge-pill unread_count" id="quantity_badge{{ $default_order->id}}" style="background-color:#f60303;">{{$unread_note_count}}</span>
                                       @endif 
									  
                                                
                                                </td>
                                            </tr>
                                          @endforeach  
                                        </tbody>
                                    </table>
                                    <div class="alert alert-primary mt-4 text-center" role="alert"> <strong>COD : Cash on Delivery | RTC : Ready to Collect | RTS : Ready to Ship</strong> </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="messages-b1">
                                <div class="table-responsive">
                                    <table id="action_orders" class="table table-striped table-bordered table-hover table-sm w-100">
                                        <thead>
                                            <tr>
											<th>S.No.</th>
                                                <th>Order Date</th>
                                                <th>Created by</th>
                                                <th>Order ID</th>
                                                <th>Customer Name</th>
                                                <th>Total Items</th>
                                               <!-- <th>Ship Qty.</th>-->
                                                <th>Total Price</th>
                                                <th>Payment</th>
                                                <th>Available</th>
                                                <th >Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i=1;?>
                                        @foreach($action_orders as $action_order)
                                            <tr>
                                            	<td>{{$i++}}</td>
                                                <td>{{date('d/m/Y',strtotime($action_order->order_date))}}</td>
                                                <td>{{$action_order->created_name}}</td>
                                                <td>{{$action_order->order_code}}</td>
                                                <td>{{$action_order->customer_name}}</td>
                                                <td>{{$action_order->total_item}}</td>
                                                <!--<td>{{$action_order->total_ship_quantity}}</td>-->
                                                <td>(RM) {{$action_order->order_total}}</td>
                                                <?php
                                             $total_paid = DB::select("SELECT SUM(`amount`) as `myamount` FROM `payment_lines` as `pl` JOIN `payments` as `p`  ON `p`.`id` = `pl`.`payment_id` AND `p`.`payment_status` ='Verified'  WHERE `order_id` =$action_order->id");
											 if($total_paid[0]->myamount==null){
												$total_paid=0.00; 
											 }else{
												 $total_paid=$total_paid[0]->myamount;
											 }
											
                                             $check_dispatch_count = \App\OrderItem::where('order_id',$action_order->id)->where(function ($q) {
    $q->where('dispatch_ready',0)->orWhere('dispatch_ready',2);
})->count();
											 ?>  
                                                <!--Payment status-->
                                                <td>
                                                @if($total_paid>0)
                                                 	<span class="badge badge-success badge-pill">Paid {{number_format($total_paid, 2, '.', ',')}}</span>
                                                @endif
                                                
                                                <span class="badge badge-danger badge-pill">Due {{number_format(($action_order->order_total + $action_order->amount_penalty )- $total_paid, 2, '.', ',')}}</span></td>
                                                
                                                 <!--shipping status-->	
                                                 <td>
                                                <?php if($action_order->s_from=='my_stock' || $action_order->shipping_type_id==3 || $action_order->shipping_location_id!=129){
													echo '<span class="badge badge-success badge-pill">Ready</span>';$is_ready = 1;
												}else{
                                                 
												  $total_arrive =  DB::table('shipment_order_item')->select(DB::raw('if( SUM(`shipment_order_item`.`ship_quantity`) IS NULL ,"0",SUM(`shipment_order_item`.`ship_quantity`))as `total_ship_quantity`'))->join('order_items', 'shipment_order_item.order_items_id', '=', 'order_items.id') ->where('order_items.order_id',$action_order->id)->where('shipment_order_item.is_arrived',1)->get();
										$final_arrive =  $total_arrive[0]->total_ship_quantity-$action_order->total_pending_quantity;
			                                      if($final_arrive ==$action_order->total_item){
												  echo '<span class="badge badge-success badge-pill">Ready</span>';                        $is_ready = 1; 
												  
												  }
												  elseif($final_arrive>0)
												  {
													echo '<span class="badge badge-info badge-pill">Partially-Ready</span> ';  				
													if($action_order->partial_ship==1){
														 $is_ready = 1;
													 }
												  }else{
													 echo '<span class="badge badge-danger badge-pill">Not Ready</span>'; 
												  }
												}
												  ?>
                                                                                         
                                                </td>
                                                 <!--Delivery type-->
                                                <td>@if($action_order->order_status=='new')
                                                @if(($total_paid==($action_order->order_total + $action_order->amount_penalty ) && $check_dispatch_count < 1) || ($total_paid==($action_order->order_total + $action_order->amount_penalty ) && $action_order->s_from=='my_stock') || ($total_paid==($action_order->order_total + $action_order->amount_penalty ) && $action_order->shipping_location_id!=129) || $action_order->is_force_active==1)<a href="{{url('dispatch_order')}}/{{encrypt($action_order->id)}}"><span class="pulse pulse-green mt-2">RTS</span></a>@else<span class="pulse pulse-new mt-2">NEW</span>@endif
                                                @elseif($action_order->order_status=='hold')<span class="pulse pulse-hold mt-2" style="animation:unset;">HOLD</span>
                                                @elseif($action_order->order_status=='rtc')
                                                	@if(($total_paid==($action_order->order_total + $action_order->amount_penalty ) && $check_dispatch_count < 1) || ($total_paid==($action_order->order_total + $action_order->amount_penalty ) && $action_order->s_from=='my_stock') || $action_order->is_force_active==1)<a href="{{url('dispatch_collect_order')}}/{{encrypt($action_order->id)}}"><span class="pulse pulse-turquoise mt-2">RTC</span></a>@else<span class="pulse pulse-turquoise mt-2" style="animation:unset;">RTC</span>@endif
                                                
                                                @elseif($action_order->order_status=='rts')
                                                
                                                	@if(($total_paid==($action_order->order_total + $action_order->amount_penalty ) && $check_dispatch_count < 1) || ($total_paid==($action_order->order_total + $action_order->amount_penalty ) && $action_order->s_from=='my_stock') || $action_order->is_force_active==1)<a href="{{url('dispatch_order')}}/{{encrypt($action_order->id)}}"><span class="pulse pulse-green mt-2">RTS</span></a>@else<span class="pulse pulse-green mt-2" style="animation:unset;">RTS</span>@endif
                                                
                                                @elseif($action_order->order_status=='cod')
                                                	@if($check_dispatch_count < 1 || $action_order->s_from=='my_stock' || $action_order->shipping_type_id==3 || $action_order->shipping_location_id!=129)<a href="{{url('order_cash_on_delivery')}}/{{encrypt($action_order->id)}}"><span class="pulse pulse-red mt-2">COD</span></a>@else<span class="pulse pulse-red mt-2" style="animation:unset;">COD</span>
                                                    @endif
                                                    @endif
                                                @if($action_order->cancel_request=='1')
                                                	<span class="badge badge-danger badge-pill">Cancel Request</span>
                                                @endif
                                                    </td>
                                                 <!--actions-->
                                                <td>
                                               <a href="{{ url('order')}}/{{encrypt($action_order->id)}}/edit" title="View Order Details"><img src="{{ asset('public/icons/eye.png')}}" alt=""></a>
                                               
                                               @hasanyrole('Super-Admin|Sales-Agent')
                                              
                                              @if($action_order->is_force_active==1)
                                              <a href="javascript:void('0');"  title="Force Order Activated"><img src="{{ asset('public/icons/activeDot.png')}}" alt=""></a>
                                              @elseif($is_ready == 1 || $action_order->s_from=='my_stock' || $action_order->shipping_type_id==3)
                                               
                                               
                                                 {!! Form::open([
                                                'method'=>'POST',
                                                'url' => '/force_active',
                                                'style' => 'display:inline',
                                                'id'=>'force_active_form_'. $action_order->id
                                            ]) !!}
                                           {{ Form::hidden('force_order_id', $action_order->id) }}
                                           <a href="javascript:void('0');" onclick="force_active({{$action_order->id}})" title="Force Order Active"><img src="{{ asset('public/icons/forceActive.png')}}" alt=""></a>
                                           
                                           <a href="{{url('get_default_order_download_PDF')}}/{{$default_order->id}}/sendemail" data-toggle="tooltip" title="Send Email"><img src="{{ asset('public/icons/email.png')}}" alt=""></a>
												
												<a href="#" title="Send SMS" data-toggle="tooltip"><img src="{{ asset('public/icons/sms.png')}}" alt=""></a>
                                            {!! Form::close() !!}
                                               @endif
                                               @endhasanyrole
                                                                                             
                                                <a class="noteButton" href="#historyNote" data-toggle="modal" data-target=".historyNote" title="View Note" s_id="{{$action_order->id}}" source_id="{{encrypt($action_order->id)}}"source_type="order"><img src="{{asset('public/history.png')}}" alt=""></a>
													
									 @if(Auth::user()->hasRole('Super-Admin'))
                                      <?php 
									  $login_user_id= Auth::user()->id;
									  ?>
                                        <?php 
									   $unread_note_count = \App\Note::where('source_type','order')->where('source_id',$action_order->id)->where('admin_read_status',0)->where('created_by','<>',$login_user_id)->count();
									   ?>
                                       <span class="badge badge-danger badge-pill unread_count" id="quantity_badge{{ $action_order->id}}" style="background-color:#f60303;">{{$unread_note_count}}</span>
                                       @endif 
                                       
                                       @if(Auth::user()->hasRole('Sales-Agent'))
                                        <?php 
									  $login_user_id= Auth::user()->id;
									  ?>
                                        <?php 
									   $unread_note_count = \App\Note::where('source_type','order')->where('source_id',$action_order->id)->where('notify','<>',0)->where('acknow_sales_agent',0)->where('created_by','<>',$login_user_id)->count();
									   ?>
                                       <span class="badge badge-danger badge-pill unread_count" id="quantity_badge{{ $action_order->id}}" style="background-color:#f60303;">{{$unread_note_count}}</span>
                                       @endif 
                                       
                                       @if(Auth::user()->hasRole('Dispatch-Manager'))
                                      <?php 
									  $login_user_id= Auth::user()->id;
									  ?>
                                        <?php 
									   $unread_note_count = \App\Note::where('source_type','order')->where('source_id',$action_order->id)->where('notify','<>',0)->where('acknow_dispatch',0)->where('created_by','<>',$login_user_id)->count();
									   ?>
                                       <span class="badge badge-danger badge-pill unread_count" id="quantity_badge{{ $action_order->id}}" style="background-color:#f60303;">{{$unread_note_count}}</span>
                                       @endif 
									  
                                                
                                                </td>
                                            </tr>
                                          @endforeach
                                        </tbody>
                                    </table>
                                    <div class="alert alert-primary mt-4 text-center" role="alert"> <strong>COD : Cash on Delivery | RTC : Ready to Collect | RTS : Ready to Ship</strong> </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="cancel-b1">
                                <div class="table-responsive">
                                    <table id="cancel_orders" class="table table-striped table-bordered table-hover table-sm w-100">
                                        <thead>
                                           <tr>
                                                <th style="width:8%;">Order Date</th>
                                                <th style="width:10%;">Created by</th>
                                                <th style="width:8%;">Order ID</th>
                                                <th style="width:14%;">Customer Name</th>
                                                <th style="width:8%;">Total Items</th>
                                                <!--<th style="width:7%;">Ship Qty.</th>-->
                                                <th style="width:8%;">Total Price</th>
                                                <th style="width:6%;">Payment</th>
                                               
                                                <th style="width:6%;">Status</th>
                                                <th style="width:7%;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i=1;?>
                                        @foreach($cancel_orders as $cancel_order)
                                             <tr>
                                                <td>{{date('d/m/Y',strtotime($cancel_order->order_date))}}</td>
                                                <td>{{$cancel_order->created_name}}</td>
                                                <td>{{$cancel_order->order_code}}</td>
                                                <td>{{$cancel_order->customer_name}}</td>
                                                <td>{{$cancel_order->total_item}}</td>
                                                <!--<td>{{$cancel_order->total_ship_quantity}}</td>-->
                                                <td>(RM) {{$cancel_order->order_total}}</td>
                                                <?php
                                             $total_paid = DB::select("SELECT SUM(`amount`) as `myamount` FROM `payment_lines` as `pl` JOIN `payments` as `p`  ON `p`.`id` = `pl`.`payment_id` AND `p`.`payment_status` ='Verified'  WHERE `order_id` =$cancel_order->id");
											 if($total_paid[0]->myamount==null){
												$total_paid=0.00; 
											 }else{
												 $total_paid=$total_paid[0]->myamount;
											 }
											
                                             $check_dispatch_count = \App\OrderItem::where('order_id',$cancel_order->id)->where(function ($q) {
    $q->where('dispatch_ready',0)->orWhere('dispatch_ready',2);
})->count();
											 ?>  
                                                <!--Payment status-->
                                                <td>
                                                @if($total_paid>0)
                                                 	<span class="badge badge-success badge-pill">Paid {{number_format($total_paid, 2, '.', ',')}}</span>
                                                @endif
                                                
                                                <span class="badge badge-danger badge-pill">Due {{number_format($cancel_order->order_total - $total_paid, 2, '.', ',')}}</span></td>
                                                
                                                 
                                                 <!--Delivery type-->
                                                <td>                                     			@if($cancel_order->cancel_request=='1')
<span class="badge badge-danger badge-pill">cancel requested</span>
@else <span class="badge badge-danger badge-pill">canceled</span>@endif
                                                    </td>
                                                 <!--actions-->
                                                <td>
                                               <a href="{{ url('cancel_order_view')}}/{{encrypt($cancel_order->id)}}" title="View Order Details"><img src="{{ asset('public/icons/eye.png')}}" alt=""></a>
                                               
                                               
                                                                                             
                                                <a class="noteButton" href="#historyNote" data-toggle="modal" data-target=".historyNote" title="View Note" s_id="{{$cancel_order->id}}" source_id="{{encrypt($cancel_order->id)}}"source_type="order"><img src="{{asset('public/history.png')}}" alt=""></a>
                                         	 @if(Auth::user()->hasRole('Super-Admin'))
                                      <?php 
									  $login_user_id= Auth::user()->id;
									  ?>
                                        <?php 
									   $unread_note_count = \App\Note::where('source_type','order')->where('source_id',$cancel_order->id)->where('admin_read_status',0)->where('created_by','<>',$login_user_id)->count();
									   ?>
                                       <span class="badge badge-danger badge-pill unread_count" id="quantity_badge{{ $cancel_order->id}}" style="background-color:#f60303;">{{$unread_note_count}}</span>
                                       @endif 
                                       
                                       @if(Auth::user()->hasRole('Sales-Agent'))
                                        <?php 
									  $login_user_id= Auth::user()->id;
									  ?>
                                        <?php 
									   $unread_note_count = \App\Note::where('source_type','order')->where('source_id',$cancel_order->id)->where('notify','<>',0)->where('acknow_sales_agent',0)->where('created_by','<>',$login_user_id)->count();
									   ?>
                                       <span class="badge badge-danger badge-pill unread_count" id="quantity_badge{{ $cancel_order->id}}" style="background-color:#f60303;">{{$unread_note_count}}</span>
                                       @endif 
                                       
                                       @if(Auth::user()->hasRole('Dispatch-Manager'))
                                      <?php 
									  $login_user_id= Auth::user()->id;
									  ?>
                                        <?php 
									   $unread_note_count = \App\Note::where('source_type','order')->where('source_id',$cancel_order->id)->where('notify','<>',0)->where('acknow_dispatch',0)->where('created_by','<>',$login_user_id)->count();
									   ?>
                                       <span class="badge badge-danger badge-pill unread_count" id="quantity_badge{{ $cancel_order->id}}" style="background-color:#f60303;">{{$unread_note_count}}</span>
                                       @endif 
                                                
                                                </td>
                                            </tr>
                                          @endforeach
                                            
                                        </tbody>
                                    </table>
                                    <div class="alert alert-primary mt-4 text-center" role="alert"> <strong>COD : Cash on Delivery | RTC : Ready to Collect | RTS : Ready to Ship</strong> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--setPostalCost Modal-->
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
                
                </div> <!-- end row -->
                </div> <!-- container -->
                </div> <!-- content -->
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
<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script> 
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.colVis.min.js"></script> 
<script src="{{asset('public/main_theme/js/sweetalert2.min.js')}}"></script>
            
            
            <script type="text/javascript">
             $(document).ready(function() {
   
          
            // scrollY:        "400px",
           
     var options = {
   scrollX:        true,
            scrollCollapse: true,
            paging:         true,
   "order": [],
            "columnDefs": [ 
            {
            "targets": 0,
            "width": "3%"
            },
			{
            "targets": 1,
            "width": "8%"
            },
            {
            "targets": 2,
            "width": "10%"
            },
            {
            "targets": 3,
            "width": "8%"
            },
            {
            "targets": 4,
            "width": "14%"
            },
            {
            "targets": 5,
            "width": "8%"
            },
            {
            "targets": 6,
            "width": "8%"
            },
            {
            "targets":7,
            "width": "6%"
            },
            {
            "targets":8,
            "width": "8%"
            },
            {
            "targets":9,
            "width": "6%"
            },
            {
            "targets":10,
            "width": "7%"
            }],
            fixedColumns: true
            };
   
   $('#general_orders').DataTable(options);
   
  table = $('#cancel_orders').DataTable({
 // scrollY:        "400px",
 
 
 paging:         true,
 
 "columnDefs": [{
  "targets": 0,
  "width": "4.5%"
 },
 {
  "targets": 1,
  "width": "10.3%"
 },
 {
  "targets": 2,
  "width": "8.5%"
 },
    {
  "targets": 3,
  "width": "9.5%"
 },
    {
  "targets": 4,
  "width": "8.5%"
 },
    {
  "targets": 5,
  "width": "8.5%"
 },
    {
  "targets": 6,
  "width": "5%"
 },
    {
  "targets": 7,
  "width": "6%"
 },
 {
  "targets": 8,
  "width": "6%"
 },
 {
  "targets": 9,
  "width": "8%"
 }],
  fixedColumns: true
});

$('#action_orders').DataTable({});
$('#default_orders').DataTable({});
   
   
            } );
			
			

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

function force_active(id) {
	
	swal({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#4fa7f3',
			cancelButtonColor: '#d57171',
			confirmButtonText: 'Yes, Active it!'
		}).then(function () {
			
			$('#force_active_form_'+id).submit();
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
		
            </script>
@append