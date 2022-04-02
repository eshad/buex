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
.footer{position: unset;}
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
        	{!! Form::open(['url' => 'submit_cash_on_delivery_order/','class'=>'form-horizontal','id'=>'cod_form']) !!}	
            <input type="hidden" name="order_id" value="{{$id}}" />
            <input type="hidden" name="customer_id" value="{{$order[0]->customer_id}}" />
            <input type="hidden" name="customer_address_id" value="{{$order[0]->customer_address_id}}" />
            <input type="hidden" name="order_total" value="{{$order[0]->order_total}}" />
            <input type="hidden" name="cod_payment" id="cod_payment" value="0" />
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
                <?php
                                             $total_paid = DB::select("SELECT SUM(`amount`) as `myamount` FROM `payment_lines` as `pl` JOIN `payments` as `p`  ON `p`.`id` = `pl`.`payment_id` AND `p`.`payment_status` ='Verified'  WHERE `order_id` ='$id'");
											 if($total_paid[0]->myamount==null){
												$total_paid=0.00; 
											 }else{
												 $total_paid=$total_paid[0]->myamount;
											 }
											 $unvarified_payment = DB::select("SELECT SUM(`amount`) as `myamount` FROM `payment_lines` as `pl` JOIN `payments` as `p`  ON `p`.`id` = `pl`.`payment_id` AND `p`.`payment_status` ='unverified'  WHERE `order_id` ='$id'");
											 ?>
               @if(($order[0]->order_total + $order[0]->amount_penalty)==$total_paid)
                <div class="alert alert-success">
 			 		<strong>Payment Completed!</strong> 
				</div>
               @else
                <div class="alert alert-danger">
 			 		<strong>Payment Due!</strong> Please collect RM <strong>{{number_format(($order[0]->order_total + $order[0]->amount_penalty) -$total_paid, 2, '.', ',')}}</strong> before handover.
                    @if($unvarified_payment[0]->myamount)
                    	<br /><strong>Payment Unverified!</strong> RM <strong>{{$unvarified_payment[0]->myamount}}</strong>
                    @endif
				</div>
               @endif
                    <div class="card-box d-flex flex-wrap">
                        <div class="col-md-6">
                            <h5 class="m-t-0"><b>Shipping Type : <small>@if($order[0]->shipping_type_id==1)Air Freight @elseif($order[0]->shipping_type_id==2) Sea Freight @else Direct Sale @endif</small></b></h5>
                            <h5 class="m-t-0">Customer Name : <small>{{$order[0]->customer_name}} ({{$order[0]->customer_code}})</small></h5>
                        </div>
                        <div class="col-md-6 text-right">
                            <h5 class="m-t-0"><b>Order Date : <small>{{date("d-m-Y", strtotime($order[0]->order_date) )}}</small></b></h5>
                            <h5 class="m-t-0">Order ID : <small>{{$order[0]->order_code}}</small></h5>
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
                                    <td> @if($order_item['s_from']=='my_stock' || $order[0]->shipping_type_id==3 || $order[0]->shipping_location_id!=129)
                                    <input type="number" name="ship_quantity[]" value="{{$order_item['quantity'] - $order_item['dispatch_quantity']}}" data-val="{{$order_item['quantity'] - $order_item['dispatch_quantity']}}" onchange="check_quantity(this)" class="form-control"  />
                                    @else
                                    <?php
									$shipment =  DB::table('shipment_order_item')->select(DB::raw('SUM(shipment_order_item.ship_quantity) as total_ship_quantity'))->where('order_items_id',$order_item['order_item_id'])->where('is_arrived','1')->get();
									
									$ready_items = $shipment[0]->total_ship_quantity - ($order_item['pending_quantity'] + $order_item['dispatch_quantity']);
									?>
                                    <input type="number" name="ship_quantity[]" value="{{$ready_items}}" data-val="{{$ready_items}}" onchange="check_quantity(this)" class="form-control"  />
                                    @endif</td>
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
                  <label for="inputEmail4" class="col-form-label">Order Collected By</label>
                  <input class="form-control" id="collect_by" type="text" name="collect_by"  parsley-trigger="change" required="required"  data-parsley-required-message="Please Enter Name" maxlength="100">
                </div>
                </div>
              </div>
              
                    	
               
                    </div>
                </div>
            </div>
            
            
            
             <!-- container -->
                    </div>
                    <div class="form-group text-right m-b-0">
                    @if(($order[0]->order_total + $order[0]->amount_penalty)==$total_paid)
                        <button  class="btn btn-primary waves-effect waves-light" type="submit" id="submit" name="without_payment">Submit</button>
                    @else
                    	<a class="btn btn-primary waves-effect waves-light" href="javascript:void();" onclick="show_payment_model();">Collect</a>
                    @endif
                                
                               <button class="btn btn-light waves-effect m-l-5" type="button" onclick="Custombox.close();">Cancel</button>
                            </div>
                            
                 <!--model-->
                 <div class="modal fade payment" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none; z-index: 9999;" id="modal_div">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button"  class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h5 class="modal-title" id="myLargeModalLabel">Collect Payment</h5>
                            </div>
                            <div class="modal-body">
                            
                                <div  class="form-group row" >
				<label class="col-3 col-form-label">Payment Date<span class="text-danger"> *</span></label>
					
                    <div class="col-9">{{ Form::date('payment_date',date('Y-m-d'),['placeholder' => 'Payment Date' , 'class' =>'form-control ','autocomplete' => 'off', 'id'=>'currentDate','required'=>'' ,'data-parsley-required-message'=>'Please Enter Payment Date', 'data-parsley-trigger'=>'change']) }}
                    </div>
                    
                    
               </div>
			   <div class="form-group row">
				<label class="col-3 col-form-label">Payment Source<span class="text-danger"> *</span></label>
                <?php $payment_source = \App\PaymentSource::select('payment_sources.id','payment_sources.source_name')
		->get();?>
					<div class="col-9">
                    <select  name ="payment_source" id="payment_source" class=" form-control parsley-success" data-live-search="true" required="" data-parsley-required-message='Please Select Payment Source'>
                    <option selected="selected" value="">Select</option>
                    @foreach($payment_source as $payment_source)
                    <option value="{{$payment_source->id}}" data-val="{{$payment_source->source_name}}">{{$payment_source->source_name}}</option>				                                    
                       @endforeach
                    </select> 
					</div>
               </div>
              <div class="form-group row">
				<label class="col-3 col-form-label">Payment Amount(RM)<span class="text-danger">*</span></label>
					<div class="col-9">
						{{ Form::text('payment_amount',($order[0]->order_total + $order[0]->amount_penalty) - $total_paid,[ 'class' =>'form-control','readonly'=>'readonly','autocomplete' => 'off', 'id'=>'payment_amount' ,'required'=>'','data-parsley-required-message'=>'Please Enter Payment Amount (RM)','data-parsley-maxlength'=>'18','maxlength'=>'18', 'data-parsley-trigger'=>'change', 'data-parsley-pattern'=>'^[\d\+\-\.\(\)\/\s]*$','data-parsley-type'=>'number','onchange'=>'change_amm_rec();','data-parsley-pattern-message'=>'Please Enter Only Number']) }} 
						@if ($errors->has('payment_amount'))
						<ul class="parsley-errors-list filled">
							<li class="parsley-required">{{ $errors->first('payment_amount') }}</li>
						</ul>
						@endif
					</div>		
			 </div>
			  
             <div class="form-group row">
				<label class="col-3 col-form-label">Customer Note</label>
					<div class="col-9">
						{{ Form::textarea('payment_note','',['placeholder' => 'Customer Note','rows'=>'2','parsley-trigger' => 'change' , 'class' =>'form-control','autocomplete' => 'off', 'id'=>'payment_note' ,'data-parsley-required-message'=>'Please Enter payment Note','data-parsley-maxlength'=>'50','maxlength'=>'50']) }}
						@if ($errors->has('payment_note'))
						<ul class="parsley-errors-list filled">
							<li class="parsley-required">{{ $errors->first('payment_note') }}</li>
						</ul>
						@endif								
					</div>
			</div>
						
                <div class="form-group row">
				<label class="col-3 col-form-label">Ref. Number/Slip Number<span class="text-danger">*</span>:</label>
					<div class="col-9">
						{{ Form::text('payment_ref_number','',['placeholder' => 'Ref. Number/Slip Number' , 'class' =>'form-control','autocomplete' => 'off', 'id'=>'payment_ref_number' ,'data-parsley-required-message'=>'Please Enter payment ref number','data-parsley-maxlength'=>'18','maxlength'=>'18', 'data-parsley-trigger'=>'keyup','required'=>'','data-parsley-refnum'=>'']) }}  
						@if ($errors->has('payment_ref_number'))
                        <i class="fa fa-spinner fa-spin" style="font-size:24px"></i>
						<ul class="parsley-errors-list filled">
							<li class="parsley-required">{{ $errors->first('payment_ref_number') }}</li>
						</ul>
						@endif
						<label id="error_pay_ref_no" ></label>
					</div>
			        <a href="javascript:void();" id="loader_spinner" class="btn btn-sm" style="display:none;"><i class="fa fa-spinner fa-spin" style="font-size:24px"></i></a>
			</div> 
                                
                             
                                	
                                    
                                    
                                    
                                <div class="w-100 d-flex">
                                    <div class="col-md-6 p-0">
                            <button  class="btn btn-primary waves-effect waves-light" type="button" id="modal_submit" name="with_payment" value="1">Submit</button>               </div>
                               
                                </div>
                              
                            </div>
                           
                        </div>
                    </div>
				</div>
                    {{ Form::close() }}
                     <!-- content -->
		             
                    <footer class="footer text-right">
                        2018 © UKSHOP .
                    </footer>
                </div>
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

 $('#modal_submit').click(function(event) {
            event.preventDefault();

            var isValid = true;

            $('#modal_div input').each(function(){
                if($(this).parsley().validate() !== true)
                    isValid = false;
            })
			$('#modal_div select').each(function(){
                if($(this).parsley().validate() == true)
                    $('#cod_payment').val('1');
                    $('#cod_form').submit();
            })

            
        });
function show_payment_model(){
	
	if($('#cod_form').parsley().validate() == true){
		$('.payment').modal('show');
	}

}
</script>
@append