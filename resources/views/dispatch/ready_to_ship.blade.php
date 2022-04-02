@extends('layouts.master')

@section('css')

<link rel="stylesheet" type="text/css" href="{{asset('public/main_theme/css/page_css/product_detail.css')}}"/>
<link href="{{asset('public/main_theme/css/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
<style>
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    margin: 0; 
}

/*.footer{position: unset;}*/
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
        	{!! Form::open(['url' => 'submit_dispatch_order/','class'=>'form-horizontal']) !!}	
            <input type="hidden" name="order_id" value="{{$id}}" />
            <input type="hidden" name="customer_address_id" value="{{$order[0]->customer_address_id}}" />
			<div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title float-left">Dispatch Order</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="{{url('/order')}}">Order</a></li>
                            <li class="breadcrumb-item active">Dispatch Order</li>
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
                           <div class="col-md-6">
                            <span class="pull-left"><h5>Customer Address : </h5> </span> <span class="pull-left"><h6>{{$customer_address[0]->address_1}}@if($customer_address[0]->address_2),<br />{{$customer_address[0]->address_2}}@endif @if($customer_address[0]->address_3),<br />{{$customer_address[0]->address_3}}@endif ,<br />{{$customer_address[0]->city}},<br />{{$customer_address[0]->postal_code}}<br />{{$customer_address[0]->state}}<br />
                           
                            {{$customer_address[0]->nicename}}<br />
                            Mobile : {{$customer_address[0]->mobile}}</h6></span>
                        </div>
                        <?php //print_r($customer_address); ?>
                        
                        
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
                                    
                                    <th>Dispatch Qty.</th>
                                   
                                    <th>U.Price (Full/Ins)</th>
                                    <th>Total</th>
                                    <th>ETA</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                            <?php $subtotal = 0; $total_quantity = 0; $i=1;?>
                            @foreach($order_items as $order_item)
                            	<?php $subtotal = $subtotal+$order_item['total_amount'];
									  $total_quantity=$total_quantity+$order_item['quantity'];
								?>
                                <tr id="item_row_{{$order_item['order_item_id']}}"> <input type="hidden" name="order_item_id[]" value="{{$order_item['order_item_id']}}" />
                                    <td>{{$i++}}</td>
                                    <td><a href="javascript:void();"><img src="{{asset('public/product_image/thumbnail_images')}}/{{$order_item['image_name1']}}" alt="" width="35"></a>
                                    </td>
                                    <td>{{$order_item['product_name']}} ({{$order_item['product_code']}})<input type="hidden" name="product_id[]" value="{{$order_item['product_id']}}" /></td>
                                    <td>{{$order_item['quantity']}}</td>
                                    <td>
                                    @if($order_item['s_from']=='my_stock' || $order[0]->shipping_type_id==3 || $order[0]->shipping_location_id!=129)
                                    
                                    <input type="number" name="ship_quantity[]" value="{{$order_item['quantity'] - $order_item['dispatch_quantity']}}" data-val="{{$order_item['quantity'] - $order_item['dispatch_quantity']}}" onchange="check_quantity(this)" class="form-control"  />
                                    @else
                                    <?php
									$shipment =  DB::table('shipment_order_item')->select(DB::raw('SUM(shipment_order_item.ship_quantity) as total_ship_quantity'))->where('order_items_id',$order_item['order_item_id'])->where('is_arrived','1')->get();
									
									$ready_items = $shipment[0]->total_ship_quantity - ($order_item['pending_quantity'] + $order_item['dispatch_quantity']);
									?>
                                    <input type="number" name="ship_quantity[]" value="{{$ready_items}}" data-val="{{$ready_items}}" onchange="check_quantity(this)" class="form-control"  />
                                    @endif
                                    </td>
                                   	<td>(RM) {{$order_item['product_price']}} </td>
                                    <td>(RM) {{$order_item['total_amount']}} </td>
                                    <td>{{date("d-m-Y", strtotime($order[0]->est_delivery_date) )}}</td>
                                    
                                   
                                    
                                </tr>
                                @endforeach
                                
                                
                                
                                
                                
                             	
                                
                            </tbody>
                        </table>
                    </div>
                     
                </div>
               
                
               </div>
                    
            <div class="row">
            
            	<div class="col-md-12 mb-2">
                	<div class="card-box d-flex flex-wrap">
                    <div class="col-md-12">
               <div class="row">
                <div class="col-md-4">
                  <label for="inputEmail4" class="col-form-label">Dispatch Date</label>
                  <input class="form-control" id="currentDate" type="date" name="dispatch_date" required="" parsley-trigger="change" value="{{date('Y-m-d')}}" data-parsley-required-message="Please Enter Order Date" data-parsley-group="first" >
                </div>
                <div class="col-md-4">
                  <label for="inputEmail4" class="col-form-label">Select courier</label>
                   <?php
				   $couriers = DB::table('courier_companies')->get();
				   ?>
                   <select class="form-control" name="courier" id="courier" data-live-search="true"  data-style="btn-light" required="" parsley-trigger="change" data-parsley-required-message="Please select courier">
                   <option value="">Please select Courier</option>
                  		@foreach($couriers as $courier)
                           	<option value="{{$courier->id}}" >{{$courier->courier_name}}</option>				
                        @endforeach
                                    </select>
                </div>
                <div class="col-md-4">
                  <label for="inputEmail4" class="col-form-label">Please Scan The Consignment Note</label>
                  <input class="form-control" id="consignment_no" type="text" name="consignment_no" required="" parsley-trigger="change" data-parsley-required-message="Please Scan consignment no"  >
                </div>
                </div>
              </div>
              
                    	
               
                    </div>
                </div>
            </div>
            
             <div class="form-group text-right m-b-0">
                                <button class="btn btn-primary waves-effect waves-light" type="submit">
                                Dispatch
                                </button>
                               <button class="btn btn-light waves-effect m-l-5" type="button" onclick="Custombox.close();">Cancel</button>
                            </div>
                    
                     <!-- content -->
		        </div>
            
             <!-- container -->
                    </div>
                   
                {{ Form::close() }}
                 <footer class="footer text-right">
                        2018 © UKSHOP .
                    </footer>
                <!-- ============================================================== -->
                <!-- End Right content here -->
                <!-- ============================================================== -->
@endsection


@section('scripts')

<script src="{{asset('public/main_theme/js/sweetalert2.min.js')}}"></script>
<script>
function check_quantity(e){
	var current_quantity = $(e).val();
	var max_quantity = $(e).data('val');
	if(current_quantity>max_quantity){
		$(e).val(max_quantity);
	}
}
</script>
@append