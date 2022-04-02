@extends('layouts.master')
@section('css')
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" />
<style>

.bcbtn {
    width: 100%;
    float: left;
    padding: 15px 0px;
}
.bcbtn a {
    color: rgb(255, 255, 255);
    padding: 5px;
    margin-right: 1px;
    border-radius: 8px;
    background: rgb(1, 163, 2) none repeat scroll 0% 0%;
}
.dropbtn {
    border: none;
    background: transparent;
    cursor: pointer;
}
.dropdown {
    position: relative;
    display: inline-block;
}
.dropdown-content {
    display: none;
    position: absolute;
    background-color: #fff;
    min-width: 290px;
    box-shadow: 0px 3px 7px 0px rgba(0,0,0,0.2);
    z-index: 999999;
    left: 110px;
    top: 1px;
    border: 1px solid #d4d4d4;
    padding: 10px;
}
.dropdown-content:after {
    border: solid;
    border-color: transparent;
    border-width: 12px;
    content: "";
    left: -24px;
    position: absolute;
    z-index: 99;
    top: 8px;
    border-right-color: #ede7e7;
}
.dropdown-content:before {
    border-width: 12px;
    content: "";
    left: -24px;
    position: absolute;
    z-index: 99;
    top: 8px;
    border-right-color: #d4d4d4;
}
.dropdown-content a {
    color: black;
    padding: 0px 16px !important;
    text-decoration: none;
    display: block;
}
.dropdown-content a:hover {
    background-color: #f1f1f1
}
.dropdown:hover .dropdown-content {
    display: block;
}
li {
    list-style: none;
}
ul {
    margin: 0px;
    padding: 0px;
}
.main-menu {
    margin: 0px;
}
.main-menu li {
    position: relative;
}
</style>
@append

@section('content')
<div id="loading" style="display:none">
	<div id="loading-center">
	<div id="loading-center-absolute">
	<div class="object" id="object_one"></div>
	<div class="object" id="object_two"></div>
	<div class="object" id="object_three"></div>
	<div class="object" id="object_four"></div>
	<div class="object" id="object_five"></div>
	<div class="object" id="object_six"></div>
	<div class="object" id="object_seven"></div>
	<div class="object" id="object_eight"></div>

	</div>
	</div>
 
</div>
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
            <h4 class="page-title float-left">Add Order</h4>
            <ol class="breadcrumb float-right">
              <li class="breadcrumb-item"><a href="{{url('order/create')}}">Order</a></li>
              <li class="breadcrumb-item active">Add Order</li>
            </ol>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
      <!-- end row -->
      <div class="row">
        <div class="col-12">
         {!! Form::open(['url' => 'order','class'=>'form-horizontal','id'=>'myform']) !!}
          <div class="card-box">
           
              <div class="form-group row">
                <label class="col-2 col-form-label">Order ID :</label>
                <div class="col-4">
                  <label class="col-form-label"><strong>{{$Order_code}}</strong></label>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-2 col-form-label">Customer <span class="text-danger">*</span></label>
                <div class="col-4">
                  <select class="selectpicker" data-live-search="true" name="customer_id" id="customer_id"  data-style="btn-light" required="" parsley-trigger = "change" data-parsley-required-message="Please select Customer" data-parsley-group="first">
                    <option value="">Please select customer</option>
                    @foreach($customers as $customer)
                     	<option value="{{$customer->id}}">{{$customer->customer_uniq_id}} - ({{$customer->customer_full_name}})</option>
                    @endforeach
                  </select>
                  
                </div>
              </div>
              <div class="form-group row">
                <label class="col-2 col-form-label">Shipping Location <span class="text-danger">*</span></label>
                <div class="col-4">
                  <select class="selectpicker" data-live-search="true" name="location_id"  data-style="btn-light"  required="" parsley-trigger = "change" data-parsley-required-message="Please select location" data-parsley-group="first">
                     @foreach($Countries as $country)
                                       		@if($country->id==129)
                                        		<option value="{{$country->id}}" data-val="{{$country->phonecode}}" selected="selected">{{$country->name}}</option>				@else
                                        		<option value="{{$country->id}}" data-val="{{$country->phonecode}}">{{$country->name}}</option>				@endif
                                       @endforeach
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-2 col-form-label">Shipping Type <span class="text-danger">*</span></label>
                <div class="col-4">
                  <select class="selectpicker shipping-mode" name="shipping_type" id="shipping_type" data-live-search="true"  data-style="btn-light"  required="" parsley-trigger = "change" data-parsley-required-message="Please select shipping Type" data-parsley-group="first" onchange="set_estimate()">
                    <option value="">Select Shipping Type</option>
                    <option value="1">AIR Freight</option>
                    <option value="2">SEA Freight</option>
                    <option value="3">Direct Sale/Ready Stock</option>
                  </select>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-3">
                  <label for="inputEmail4" class="col-form-label">Order Date</label>
                  <input class="form-control" id="currentDate" type="text" value="{{date('d-m-Y')}}" name="order_date" required="" parsley-trigger = "change" data-parsley-required-message="Please Enter Order Date" data-parsley-group="first">
                </div>
                <div class="form-group col-md-3">
                  <label for="inputEmail4" class="col-form-label">Estimated Delivery Date</label>
                  <input class="form-control" id="estimate_date" type="text" name="estimate_date" value="" required="" parsley-trigger = "change" data-parsley-required-message="Please Enter Estimated Delivery" data-parsley-group="first">
                </div>
              </div>
              <div class="col-md-12 text-right"> <span class="mode1 freightBox"> <img src="{{ asset('public/main_theme/images/air.png')}}" alt=""> </span> <span class="mode2 freightBox"> <img src="{{ asset('public/main_theme/images/ship.png')}}" alt=""> </span> <span class="mode3 freightBox"> <img src="{{ asset('public/main_theme/images/direct.png')}}" alt=""> </span> </div>
           
          </div>
          <div class="card-box itemDetail I-1">
            <div class="table-responsive" style="min-height: 220px;">
              <table class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <td>UK Stock</td>
                    <td id="colspan_change" colspan="4">In Transit Stock</td>
                    
                    <td>My Stock</td>
                  </tr>
                  <tr id="for_shipment_details">
                    
                      
                  </tr>
                </thead>
                <tbody>
                  <tr id="stock_quantity">
                   
                  </tr>
                </tbody>
              </table>
            </div>
            <img id="product_datail_image" src="{{asset('public/main_theme/images/logo.png')}}" alt=""> 
            <!-- The Modal -->
            <div id="myModal" class="modal"> <span class="close">&times;</span>
             <img class="modal-content" id="img01">
              <div id="caption"></div>
            </div>
          </div>
          <div class="card-box">
            <div class="table-responsive">
              <table class="table table-bordered table-hover" id="tableAddRow">
                <thead>
                  <tr>
                    <th></th>
                    <th width="25%">Item Name</th>
                    <th width="13%">Payment Plan</th>
                    <th width="10%">Quantity</th>
                    <th width="13%">Local Postage</th>
                    <th width="10%">Price(RM)</th>
                    <th width="10%">S. From</th>
                    <th width="18%">Amount</th>
                    <th width="3%"></th>
                  </tr>
                </thead>
                <tbody>
                
                  <tr id="tr_0">
                    <td></td>
                    <td>
                    	<select class="selectpicker ItemTable" data-live-search="true" id="select_item" data-style="btn-light" required="" parsley-trigger = "change" data-parsley-group="second" data-parsley-required-message ="Please select Item" >
                            <option value="">Select Item</option>
                            @foreach($products as $product)
                            <option value="{{$product->id}}">{{$product->product_name}} - {{$product->item_uniq_id}}</option>
                            @endforeach
						</select>
                        <ul class="parsley-errors-list filled" id="item_already_selected" style="display:none;"><li class="parsley-required">Item Already Selected</li></ul>
                        </td>
                    <td><div class="onoffswitch">
                        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="payment_plan" checked>
                        <label class="onoffswitch-label" for="payment_plan"> <span class="onoffswitch-inner"></span> <span class="onoffswitch-switch"></span> </label>
                      </div></td>
                    <td><input type="text" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" class="form-control" id="show_quantity" value="1" onchange="change_quantity();" required="required" data-parsley-group="second" data-parsley-pattern-message="Please Enter Only Number" data-parsley-min="1" data-parsley-min-message="minimum quantity is 1 "/> <ul class="parsley-errors-list filled" id="quantity_insufficient" style="display:none;"><li class="parsley-required">With selected stock Quantity insufficient</li></ul></td>
                    <td><div class="onoffswitch1">
                        <input type="checkbox" name="onoffswitch1" class="onoffswitch1-checkbox" id="local_postage" checked>
                        <label class="onoffswitch1-label" for="local_postage"> <span class="onoffswitch1-inner"></span> <span class="onoffswitch1-switch"></span> </label>
                      </div></td>
                    <td><input type="text" class="form-control"  id="show_product_price" required="" data-parsley-group="second"   parsley-trigger = "change" onchange="change_quantity();" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" data-parsley-pattern-message="Please Enter Only Number" data-parsley-min="1" data-parsley-min-message="minimum price is 1 " value="0.00" data-val="0.00" @hasrole('Sales-Agent') readonly="readonly" @endhasrole /></td>
                    <td><select class="form-control" data-live-search="true" onchange="set_estimate()" id="show_s_from" data-style="btn-light">
                        <option value="" >Select Item First</option>
                        
                      </select>
                      <ul class="parsley-errors-list filled" id="different_s_from" style="display:none;"><li class="parsley-required">stock from is different</li></ul>
                      </td>
                    <td><input type="text" class="form-control"  id="show_product_amount" data-parsley-group="second" required  parsley-trigger = "change" readonly="readonly" value="0.00"/>
                      <div class="dropdown d-block"> 
                           <a href="javascript:void('0');" class="dropbtn" style="position: absolute;top: -30px; right: 6px; display: block;">
                            <span class="dripicons-italic"></span>
                          </a>
                        <div class="dropdown-content" id="popuptol" style="left:0px; min-width: auto;">
                          <ul class="main-menu text-left">
                            <li>
                              <h6>Local Postage Cost</h6>
                            </li>
                            <li>
                              <pre id="show_sm">SM : RM 00.00 </pre>
                            </li>
                            <li>
                              <pre id="show_ss">SS : RM 00.00 </pre>
                            </li>
                            <li>
                              <pre id="show_airfreight">Airfreight : RM 00.00 </pre>
                            </li>
                          </ul>
                        </div>
                      </div>
                      <input type="hidden" name="set_ss" id="set_ss" />
                      <input type="hidden" name="set_sm" id="set_sm" />
                      <input type="hidden" name="set_airfreight" id="set_airfreight" />
                      </td>
                    <td><a href="javascript:void('0');" class="addBtn" id="addBtn_0"> <img src="{{asset('public/main_theme/images/plus.png')}}" alt=""> </a></td>
                  </tr>
                  
                </tbody>
              </table>
              <div class="col-md-8 pull-right">
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <td style="width:15%;">
                          <p>Total Item</p>
                          <input class="form-control" type="text" required="" name="total_item" parsley-trigger = "change" id="total_item" data-parsley-group="first" readonly="readonly">
                        </td>
                        <td style="width: 30%;">
                        <p> Manage Local Postage Cost(RM)</p>
                        <?php $readonly='readonly';?>
                        @hasanyrole('Super-Admin')
                        	 <?php $readonly='';?>
                        @endhasanyrole
                        <input class="form-control numeric" type="text" id="local_pos" name="local_pos" data-parsley-group="first" onchange="change_local_freight();" {{$readonly}}>
                        </td>
                        <td style="width: 30%;">
                        <p>Total Airfreight(RM)</p>
                        <input class="form-control" placeholder="00.00" type="text" name="total_airfreight" id="total_airfreight" data-parsley-group="first" readonly="readonly">
                        </td>
                      </tr>
                      <tr>
                        <td colspan="2"></td>
                        <td style="width: 30%;">
                        <p>Total Local Postage(RM)</p>
                        <input class="form-control" placeholder="00.00" type="text" name="total_local_postage" id="total_local_postage" data-parsley-group="first" readonly="readonly">
                        </td>
                      </tr>
                      <tr>
                        <td colspan="2"></td>
                        <td style="width: 30%;">
                        <p>Total(RM)</p>
                        <input class="form-control" placeholder="00.00" type="text" name="final_total" id="final_total" data-parsley-group="first" readonly="readonly">
                        </td>
                      </tr>
                      
                    </thead>
                  </table>
                </div>
              </div>
              <div class="form-row w-100">
                <h5 class="col-md-12">Note</h5>
                <div class="form-group col-md-12">
                  <textarea rows="3" class="form-control" data-parsley-group="first" name="note"></textarea>
                </div>
              </div>
              <ul class="parsley-errors-list filled" id="atleast_item" style="display:none;"><li class="parsley-required">Please add Atleast 1 Item</li></ul>
              <button type="button" class="btn btn-primary waves-light waves-effect w-md" id="create_order">Create Order</button> </div>
              {{ Form::close() }}
          </div>
        </div>
       
        
      </div>
      <!-- end row --> 
    </div>
    <!-- container --> 
  </div>
  <!-- content -->
  <footer class="footer text-right"> 2018 © UKSHOP. </footer>
</div>
<!-- ============================================================== --> 
<!-- End Right content here --> 
<!-- ============================================================== -->

@endsection


@section('scripts')
<script src="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script>
/*function set_estimated_date()
{
	var shipping_type = $('#shipping_type').val();
	var s_from = $('#show_s_from').val();
	if(shipping_type == 1)
	{
		
		var estimated_date = '<?php echo date("d-m-Y", strtotime('+4 week Saturday'));?>'; 
		
	}else if(shipping_type == 2)
	{
		var estimated_date = '<?php echo date("d-m-Y", strtotime('+2 month'));?>';
	}else if(shipping_type == 3)
	{
		var estimated_date = '<?php echo date("d-m-Y", strtotime('+4 days'));?>';  
	}else
	{
		var estimated_date = '<?php echo date("d-m-y");?>';  
	}
	$('#estimate_date').val(estimated_date);
}*/
$(function() {
	
		$("#currentDate").datepicker(
		{format: 'dd-mm-yyyy'});
		$("#estimate_date").datepicker(
		{format: 'dd-mm-yyyy'
		})
		
	});
function set_estimate()
{
	var shipping_type = $('#shipping_type').val();
	var s_from = $('#show_s_from').val();
   
	if(shipping_type == 1)
	{
		if(s_from == 'uk_stock')
		{
			var estimated_date = '<?php echo date("d-m-Y", strtotime('+4 week Saturday'));?>'; 
		}else if(s_from == 'my_stock')
		{
			var estimated_date = '<?php echo date("d-m-Y", strtotime('+4 days'));?>'; 
		}else if(s_from != ''){
			 var estimated_date = $('#show_s_from').children('option:selected').attr('data-estimate');
		}else{
			var estimated_date = '<?php echo date("d-m-Y", strtotime('+4 week Saturday'));?>'; 
		}			
	}else if(shipping_type == 2)
	{
		if(s_from == 'uk_stock')
		{
			var estimated_date ='<?php echo date("d-m-Y", strtotime('+2 month'));?>';
		}else if(s_from == 'my_stock')
		{
			var estimated_date = '<?php echo date("d-m-Y", strtotime('+4 days'));?>'; 
		}else if(s_from != ''){
			 var estimated_date = $('#show_s_from').children('option:selected').attr('data-estimate');
		}else{
			var estimated_date = '<?php echo date("d-m-Y", strtotime('+2 month'));?>';
		}	
	}else if(shipping_type == 3)
	{
		var estimated_date = '<?php echo date("d-m-Y", strtotime('+4 days'));?>';  
	}else
	{
		var estimated_date = '<?php echo date("d-m-Y");?>';  
	
	}
	$('#estimate_date').val(estimated_date);
}


var k = 1;
$(".ItemTable").change(function(){
	$('#item_already_selected').hide();
	$('#different_s_from').hide();
	$('#quantity_insufficient').hide();
	$(this).find("option:selected").each(function(){	
		var optionValue = $(this).attr("value");
		if(optionValue){
				var my_url = APP_URL+'/ajax_get_item_details_on_order_page';
				var formData = {item_id:optionValue}
				var shipping_type = $('#shipping_type').val();
				if(shipping_type == 1)
				{
					var estimated_date = '<?php echo date("d-m-Y", strtotime('+4 week Saturday'));?>'; 
					
				}else if(shipping_type == 2)
				{
					var estimated_date = '<?php echo date("d-m-Y", strtotime('+2 month'));?>';
				}else if(shipping_type == 3)
				{
					var estimated_date = '<?php echo date("d-m-Y", strtotime('+4 days'));?>';  
				}else
				{
					var estimated_date = '<?php echo date("Y-m-d");?>';  
				}

				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				})	
				$.ajax({
					type: "POST",
					url: my_url,
					data: formData,
					success: function (data) {
						//alert(data);
						console.log(data);
						
						//$('#stock_quantity').html('<td id="product_datail_uk_stock">'+data.stock.uk_stock+'</td><td id="product_datail_pending_stock">0</td><td id="product_datail_my_stock">'+data.stock.malaysia_stock+'</td>');
						 
						$('#product_datail_image').attr('src',APP_URL+'/public/product_image/thumbnail_images/'+data.product_image);
						$('#show_sm').text('SM : RM '+ data.stock.sm_cost);
						$('#show_ss').text('SS : RM '+ data.stock.ss_cost);
						$('#show_airfreight').text('Airfreight : RM '+ data.stock.air_freight_cost);
						
						$('#set_sm').val(data.stock.sm_cost);
						$('#set_ss').val(data.stock.ss_cost);
						$('#set_airfreight').val(data.stock.air_freight_cost);
						if($('#payment_plan').is(':checked')){
							$('#show_product_price').val(data.stock.product_price);
							$('#show_product_price').data('val',data.stock.installment_cost);
						}else{
							$('#show_product_price').val(data.stock.installment_cost);
							$('#show_product_price').data('val',data.stock.product_price);
						}
						
						var show_quantity = $('#show_quantity').val();
						var amount = show_quantity*data.stock.product_price;
						$('#show_product_amount').val(amount.toFixed(2));
						console.log(data);
						var shipdata = '';var selectdata = ''; var shipvalue='';var colspan=0;
						var shipping_type = $("#shipping_type").val();
						if(data.shipment.length>0 && shipping_type!='3'){
							for($k=0;$k<data.shipment.length;$k++){
								if(data.shipment[$k].shipment_quantity>0){
								var shipname= data.shipment[$k].shipment_number;
								var myarr = shipname.split("-");
								shipdata += '<td><div class="dropdown"><a href="javascript:void(0);" class="dropbtn"><span>'+myarr[0]+'-'+myarr[2]+'</span></a><div class="dropdown-content" style="min-width: auto; left: 50px;"><ul class="main-menu text-left"><li><h6>Tracking information</h6></li><li><pre>'+data.shipment[$k].shipment_number+'</pre></li><li><pre>Remaining '+data.shipment[$k].remaining+' Days</pre></li><li><pre>BL : '+data.shipment[$k].bl_awb_number+'</pre></li><li><pre>Carrier : '+data.shipment[$k].carrier_details+'</pre></li></ul></div></div></td>';
								shipvalue +='<td>'+data.shipment[$k].shipment_quantity+'</td>';
								selectdata +='<option data-estimate="'+data.shipment[$k].shipment_date+'" value="'+data.shipment[$k].id+'" data-val="'+data.shipment[$k].shipment_quantity+'">'+data.shipment[$k].shipment_number+'</option>';
								colspan++;
								}
							}
						}else{
							
							shipdata = '<td>No shipment found</td>';
							shipvalue +='<td>NA</td>';
						}
						$('#stock_quantity').html('<td id="product_datail_uk_stock">'+data.stock.uk_stock+'</td>'+shipvalue+'<td id="product_datail_my_stock">'+data.stock.malaysia_stock+'</td>');
						
						$("#for_shipment_details").html('<td></td>'+shipdata+'<td colspan="2"></td>');
						$("#show_s_from").html('<option data-estimate="'+estimated_date+'" value="uk_stock" data-val="'+data.stock.uk_stock+'">UK stock</option>'+selectdata+'<option value="my_stock" data-estimate="'+estimated_date+'" data-val="'+data.stock.malaysia_stock+'">MY stock</option>');
						$("input[name='show_s_from[]']").map(function(){
							
							$('#show_s_from option[value='+$(this).val()+']').attr('selected','selected');
							 
						}).get();
						$('#colspan_change').attr('colspan',colspan);
						$(".itemDetail").show(1000);
						$('#estimate_date').val(estimated_date);
					}
					
				});
		}
	});
}).change();

$("#addBtn_0").on('click', function() {
     
    if ($('#myform').parsley().validate("second")) {
		$('#atleast_item').hide();
		$('#quantity_insufficient').hide();
		var row = $('#tableAddRow tr').length;
		var rowcount = row-1;
		var product_datail_image = $('#product_datail_image').attr('src');
		var product_datail_name = $('#select_item option:selected').text();
		var product_datail_id = $('#select_item').val();
		 var isRedirect=0;
		 
		if($('#show_s_from option:selected').data('val') < $('#show_quantity').val()){
			 isRedirect=1;
			 $('#quantity_insufficient').show();
		}
		$("input[name='item_id[]']").map(function(){
                if($(this).val() == product_datail_id){
                    $('#item_already_selected').show();
					 isRedirect=1;
                }
            }).get();
		if(isRedirect){return false;}
		$("input[name='show_s_from[]']").map(function(){
                if($(this).val() != $('#show_s_from').val()){
                    $('#different_s_from').show();
					 isRedirect=1;
                }
            }).get();
		if(isRedirect){return false;}
		var show_s_from = $('#show_s_from option:selected').text();
		var s_from = $('#show_s_from').val();
		//var est = $('#show_s_from').find(':selected').attr('data-est')
		//$('#estimate_date').val(est);
		var payment_plan = $('#payment_plan').is(':checked')?'FP/OPT-1':'INS/OPT-2';
		var payment_plan_id = $('#payment_plan').is(':checked')?'1':'2';
		var local_postage = $('#local_postage').is(':checked')?'SM':'SS';
		var show_quantity =$('#show_quantity').val();
		var show_product_price =$('#show_product_price').val();
		var show_product_amount =$('#show_product_amount').val();
		var item_local_postage = '';
		if(local_postage=='SM'){item_local_postage = (parseInt($('#set_sm').val())*parseInt(show_quantity)).toFixed(2);}else{item_local_postage = (parseInt($('#set_ss').val())*parseInt(show_quantity)).toFixed(2);}
		var set_airfreight = (parseInt($('#set_airfreight').val())*parseInt(show_quantity)).toFixed(2);
		var popuptol = $('#popuptol').html();
        var tempTr = $('<tr id="row'+k+'"><td>'+rowcount+'. <img src="'+product_datail_image+'" width="100"></td><td>'+product_datail_name+'<input type="hidden" name="item_id[]" value="'+product_datail_id+'"></td><td>'+payment_plan+'<input type="hidden" name="payment_plan[]" value="'+payment_plan_id+'"></td><td>'+show_quantity+'<input type="hidden" name="show_quantity[]" value="'+show_quantity+'"></td><td>'+local_postage+'<input type="hidden" name="local_postage[]" value="'+local_postage+'"></td><td>RM '+show_product_price+'<input type="hidden" name="show_product_price[]" value="'+show_product_price+'"></td><td>'+show_s_from+'<input type="hidden" name="show_s_from[]" value="'+s_from+'"></td><td>'+show_product_amount+'<input type="hidden" name="show_product_amount[]" value="'+show_product_amount+'"><div class="dropdown d-block"><a href="javascript:void();" class="dropbtn" style="position: absolute;top: -30px; right: 6px; display: block;"><span class="dripicons-italic"></span></a><div class="dropdown-content" style="left:0px; min-width: auto;">'+ popuptol+'</div></div><input type="hidden" name="item_local_postage[]" value="'+item_local_postage+'"><input type="hidden" name="item_set_airfreight[]" value="'+set_airfreight+'"></td><td><span class="addBtnRemove" id="addBtn_' + k + '"><img src="../public/icons/minus.png"></span></td></tr>');
        $("#tableAddRow").append(tempTr);
		$(".itemDetail").hide(1000);
		$('#different_s_from').hide();
		$('#show_s_from').prop('disabled', true);
		$('#payment_plan').prop('disabled', true);
		$('#local_postage').prop('disabled', true);
		calculate_total();
		reset_row_form();
        k++;
    } else {
        console.log('not valid');
    }
});

$(document).on('click','.addBtnRemove',function(){
	
	  $(this).closest('tr').remove(); 
	  var row = $('#tableAddRow tr').length;
	  if(row<3){
		  $('#show_s_from').prop('disabled', false);
		  $('#payment_plan').prop('disabled', false);
			$('#local_postage').prop('disabled', false);
	  }
	  calculate_total();
});

function change_local_freight(){
	var old_total_local_postage =$('#total_local_postage').val();
	var after_less = (parseInt($('#final_total').val())- old_total_local_postage).toFixed(2);
	$('#final_total').val((parseInt($('#local_pos').val())+ parseInt(after_less)).toFixed(2));
	$('#total_local_postage').val($('#local_pos').val());
	
}
function calculate_total(){
	var show_quantity = 0;
	var item_local_postage = 0;
	var item_set_airfreight = 0;
	var total_freight = 0;
	var show_product_amount =0;
	var final_total = 0;
	$("input[name='show_quantity[]']").map(function(){
              show_quantity = parseInt(show_quantity)+parseInt($(this).val());
     }).get();
	 
	 $("input[name='item_local_postage[]']").map(function(){
		
			 item_local_postage = (parseInt(item_local_postage)+ parseInt($(this).val())).toFixed(2);
	}).get();
	
	 $("input[name='item_set_airfreight[]']").map(function(){
		if($('#shipping_type').val()=='1'){
			 item_set_airfreight = (parseInt(item_set_airfreight)+parseInt($(this).val())).toFixed(2);
		}else{
			item_set_airfreight ='0.00';
		}
	}).get();
	
	total_freight = (parseInt(item_set_airfreight)+parseInt(item_local_postage)).toFixed(2);
	
	$("input[name='show_product_amount[]']").map(function(){
		
			 show_product_amount = (parseInt(show_product_amount)+parseInt($(this).val())).toFixed(2);
	}).get();
	
	final_total = (parseInt(total_freight)+parseInt(show_product_amount)).toFixed(2);
	$('#total_item').val(show_quantity);
	$('#local_pos').val(item_local_postage);
	$('#total_airfreight').val(item_set_airfreight);
	$('#total_local_postage').val($('#local_pos').val());
	$('#final_total').val(final_total);
}

function change_quantity(){
	var product_price = $('#show_product_price').val();
	var product_quantity = $('#show_quantity').val();
	var amount = (parseInt(product_price)*parseInt(product_quantity)).toFixed(2);
	$('#show_product_amount').val(amount);
}


$(document).ready(function(){
	
	$(".shipping-mode").change(function(){
		$(this).find("option:selected").each(function(){
			var optionValue = $(this).attr("value");
			if(optionValue){
				$(".freightBox").not("." + optionValue).hide(1000);
				$(".mode" + optionValue).show(1000);
			} else{
				$(".freightBox").hide();
			}
			calculate_total();
		});
	}).change();
});

function reset_row_form(){
	
	//$('#payment_plan').prop('checked', true);
	//$('#local_postage').prop('checked', true);
	$('#show_quantity').val('1');
	$('#show_product_price').val('00.00');
	$('#show_product_amount').val('00.00');
	//$("#show_s_from").val('default');
	//$("#show_s_from").selectpicker("refresh");
	$("#select_item").val('default');
	$("#select_item").selectpicker("refresh");
}

$("#payment_plan").change(function() {
    if(this.checked) {
		var dataval = $('#show_product_price').data('val');
		var val = $('#show_product_price').val();
		$('#show_product_price').data('val',val);
		$('#show_product_price').val(dataval);
		$('#show_product_amount').val(dataval);
		
	var product_quantity = $('#show_quantity').val();
	var amount = (parseInt(dataval)*parseInt(product_quantity)).toFixed(2);
	$('#show_product_amount').val(amount);
		
    }else{
		var dataval = $('#show_product_price').data('val');
		var val = $('#show_product_price').val();
		$('#show_product_price').data('val',val);
		$('#show_product_price').val(dataval);
		$('#show_product_amount').val(dataval);
		var product_quantity = $('#show_quantity').val();
		var amount = (parseInt(dataval)*parseInt(product_quantity)).toFixed(2);
		$('#show_product_amount').val(amount);
	}
});

$('#create_order').on('click',function(){
	if($('#myform').parsley().validate("first")){
		var row = $('#tableAddRow tr').length;
		if(row<3){
			$('#atleast_item').show();
			$('#myform').parsley().validate("second");
			return false;
		}else{
			$('#select_item').removeAttr('required');
			$('#show_product_price').removeAttr('data-parsley-min');
			$("#loading").css("display", "block");
			$('#myform').submit();
		}
	}
});

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
</script>
@if($customer_id!='')
<script>
$(document).ready(function(){
$('#customer_id').val('{{$customer_id}}').trigger('change');
});
</script>
@endif


@append