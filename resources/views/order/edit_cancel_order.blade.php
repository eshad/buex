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
                                    <td>{{$order_item['quantity']}}</td>
                                    
                                    <td id="column_ship_quantity_{{$order_item['order_item_id']}}">{{$order_item['ship_quantity']}}</td>
                                    <td>
                                    <?php
									
									 if($order_item['dispatch_quantity']>0){
									 	echo 'Dispatched-'.$order_item['dispatch_quantity'];
										echo '<br>Canceled-'.($order_item['quantity'] - $order_item['dispatch_quantity']);
									 }else{
									 	echo '<br>Canceled-'.$order_item['quantity'];
									 }
									 ?>
                                   
                                    
                            </td>
                            
                            
                            
                                    <td id="column_unit_price_{{$order_item['order_item_id']}}">(RM) {{$order_item['product_price']}} </td>
                                    <td id="line_total_price_{{$order_item['order_item_id']}}">(RM) {{$order_item['total_amount']}} </td>
                                    <td>{{date("d-m-Y", strtotime($order[0]->est_delivery_date) )}}</td>
                                    <td>
                                    <?php
									
									 if($order_item['dispatch_quantity']>0){
									 	 echo '<span class="badge badge-danger badge-pill">Dispatched </span> ';
										 echo '<span class="badge badge-danger badge-pill">Canceled </span> ';
									 }else{
									 	echo '<span class="badge badge-danger badge-pill">Canceled </span> ';
									 }
									 ?>
                                   
                                        </td>
                                        
                                   
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
                                    <th><span class="badge badge-success badge-pill">(RM) {{$order[0]->total_airfreight_cost}}</span></th>
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
                                    <th><span class="badge badge-success badge-pill" >(RM) {{$order[0]->total_local_postage_cost}}</span></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Sub Total :</th>
                                    <th><span class="badge badge-success badge-pill" >{{$total_quantity}}</span></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><span class="badge badge-success badge-pill" >(RM) {{number_format(($order[0]->order_total), 2, '.', ',')}}</span></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Order Cancel :</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><span class="badge badge-danger badge-pill" >(RM) -{{number_format(($order[0]->order_total), 2, '.', ',')}}</span></th>
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
                                    <th>Adjustment/Penalty :</th>
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
                                    <th><span class="badge badge-success badge-pill" id="new_final_total">(RM) {{number_format($order[0]->amount_penalty, 2, '.', ',')}}</span></th>
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
                                    <th>
                                   <span class="badge badge-danger badge-pill" id="balance">(RM) @if($total_paid - $order[0]->amount_penalty > 0)-{{number_format($total_paid - $order[0]->amount_penalty, 2, '.', ',')}}@else  {{number_format($total_paid - $order[0]->amount_penalty, 2, '.', ',')}}@endif</span></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                
                             	
                                
                                
                            </tbody>
                        </table>
                    </div>
                    
                   
                </div>
                
               
                
                 {{ Form::close() }}
                
                
                    </div> <!-- container -->
                    </div>
                    
                     <!-- content -->
		            
                     
                    
                   <!-- end row -->
                    <footer class="footer text-right">
                        2018 © UKSHOP .
                    </footer>
                </div>
                <!-- ============================================================== -->
                <!-- End Right content here -->
                <!-- ============================================================== -->
                
               
@endsection


@section('scripts')
<script src="{{asset('public/main_theme/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('public/main_theme/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script> 
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.colVis.min.js"></script> 
<script src="{{asset('public/main_theme/js/sweetalert2.min.js')}}"></script>
                
                
                <script type="text/javascript">

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
				


</script>
@append