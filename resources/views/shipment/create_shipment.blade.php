@extends('layouts.master')
@section('css')
<link href="{{asset('public/main_theme/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
 <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/base/jquery-ui.css" type="text/css" media="all">
<link href="{{asset('public/main_theme/css/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" />

@append

@section('content')
@include('html/gallery');
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
<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title float-left">Create Shipment</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item">Shipment</li>
                            <li class="breadcrumb-item active">Create Shipment</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card-box">
                       {!! Form::open(['url' => 'shipment','id'=>'form_submit','enctype'=>'multipart/form-data','class'=>'form-horizontal']) !!}	
						{{ csrf_field() }}
                            <div class="form-group row">
                                <label class="col-3 col-form-label">Estimated Arrivable Date<span class="text-danger">*</span></label>
                                <div class="col-4">
                                    {{ Form::text('estimate_arrivable','',['placeholder' => 'Estimated Arrivable Date','required'=>'','parsley-trigger' => 'change' , 'class' =>'form-control date-range-filter','autocomplete' => 'off', 'id'=>'max_date' ,'data-parsley-required-message'=>'Please Enter Estimated Arrivable Date','data-date-format'=>'dd/mm/yyyy','data-parsley-maxlength'=>'30','maxlength'=>'30']) }}
                                     
                                </div>
                            </div>
							<?php $shipment_num = 'AIR-'.date('dmY').'-'.($count+1); ?>
                            <div class="form-group row">
                                <label class="col-3 col-form-label">Shipment Name</label>
                                <div class="col-4">
								<input type="text" name="shipment_name" value="<?=$shipment_num?>" id="shipment_name" placeholder="Shipment Name" parsley-trigger ="change" class="form-control" readonly />
									
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label">Shipment Type</label>
                                <div class="col-4">
                                    <div class="radio form-check-inline">
										{{ Form::radio('shipment_type','Air', true,['placeholder' => 'Shipment Type','parsley-trigger' => 'change','autocomplete' => 'off', 'id'=>'shipment_type_air' ,'data-parsley-required-message'=>'Please Select Shipment Type','data-parsley-maxlength'=>'30','maxlength'=>'30','checked','onchange'=>'change_type("air")']) }}
                                        <label for="inlineRadio1"> Air Shipment </label>
                                    </div>
                                    <div class="radio form-check-inline">
                                        {{ Form::radio('shipment_type','Sea', false,['placeholder' => 'Shipment Type','parsley-trigger' => 'change' ,'autocomplete' => 'off', 'id'=>'shipment_type_sea' ,'data-parsley-required-message'=>'Please Select Shipment Type','data-parsley-maxlength'=>'30','maxlength'=>'30','onchange'=>'change_type("sea")']) }}
                                        <label for="inlineRadio2"> Sea Shipment </label>
                                    </div>
                                </div>
                            </div>
                             <div class="form-group row">
                                <label class="col-3 col-form-label forshipmenttype" >AWB<span class="text-danger">*</span></label>
                                <div class="col-4">
                                    {{ Form::text('bl_awb_number','',['placeholder' => 'Enter Number ','required'=>'','parsley-trigger' => 'change' , 'class' =>'form-control','autocomplete' => 'off', 'id'=>'ship_number' ,'data-parsley-required-message'=>'Please Enter Number','data-parsley-maxlength'=>'100','maxlength'=>'100']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label">CARRIER DETAILS<span class="text-danger">*</span></label>
                                <div class="col-4">
                                    {{ Form::text('carrier_details','',['placeholder' => 'Enter CARRIER DETAILS ','required'=>'','parsley-trigger' => 'change' , 'class' =>'form-control','autocomplete' => 'off', 'id'=>'ship_number' ,'data-parsley-required-message'=>'Please Enter CARRIER DETAILS','data-parsley-maxlength'=>'100','maxlength'=>'100']) }}
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="shipment" class="table table-striped table-bordered table-hover table-sm w-100">
                                    <thead>
                                        <tr>
                                            <th>SN.</th>
                                            <th>Image</th>
                                            <th>Item Code</th>
                                            <th>Item Name</th>
                                            <th>Total Stock</th>
                                            <th>UK Stock</th>
                                            <th>Sold Item Sea</th>
                                            <th>Air Order</th>
                                            <th>Ship Qty.</th>
                                            <th>Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php $i=1;$total_stock_sum = 0;$total_ship_qty = 0;$total_air=0;$total_sea=0; ?>
									@foreach($order_data as $order_datas)
										<?php 
										
										if($order_datas->thumb_image_name == '')
										$image_name = "";
										else
										$image_name = "product_image/thumbnail_images/".$order_datas->thumb_image_name;
										?>
                                        <tr id="{{$order_datas->id}}">
										
                                            <td>{{$i++}}.</td>
											<input type="hidden" name="item_id[]" value="<?= $order_datas->id?>" />
											
                                            <td><img src="{{ asset('public/')}}/{!!$image_name!!}" alt="" width="35"></td>
											
                                            <td>{{$order_datas->item_uniq_id}}</td>
											
                                            <td>{{$order_datas->product_name}}</td>
											
											 <?php
											 
                                               $count_sold_item_sea = DB::select("select sum(oi.quantity - oi.ship_quantity) as qut from order_items as oi Left join orders as o ON o.id=oi.order_id where oi.s_from='uk_stock' and o.shipping_type_id='2' and `oi`.`ship_quantity` <> oi.quantity and o.is_cancel='0' and o.is_done='0' and o.shipping_location_id='129' and oi.product_id='".$order_datas->id."'");
											   if($count_sold_item_sea[0]->qut !="")
											   {
												   $count_sold_item_sea = $count_sold_item_sea[0]->qut;
											   }else{
												    $count_sold_item_sea = 0;
											   }
											    $count_sold_item_air = DB::select("select sum(oi.quantity - oi.ship_quantity) as qut from order_items as oi Left join orders as o ON o.id=oi.order_id where oi.s_from='uk_stock' and o.shipping_type_id='1' and `oi`.`ship_quantity` <> oi.quantity and o.is_cancel='0' and o.is_done='0' and o.shipping_location_id='129' and oi.product_id='".$order_datas->id."'");
												
												
												if($count_sold_item_air[0]->qut !="")
											   {
												   $count_sold_item_air = $count_sold_item_air[0]->qut;
											   }else{
												    $count_sold_item_air = 0;
											   }
											?> 
											
                                            <td id="total_stock<?=$order_datas->id?>">{{($order_datas->uk_stock+$count_sold_item_sea+$count_sold_item_air)}}</td>
											
                                            <td class="uk_stock<?=$order_datas->id?>" id="uk_stock<?=$order_datas->id?>">{{$order_datas->uk_stock}}</td>
											
                                            <td class="sold_sea<?=$order_datas->id?>" id="sold_item_sea<?=$order_datas->id?>">{{$count_sold_item_sea}}</td>
											
                                            <td class="sold_air<?=$order_datas->id?>" id="sold_item_air<?=$order_datas->id?>">{{$count_sold_item_air}}</td>
											
                                             <td id="ship_quantity<?=$order_datas->id?>" ><input class="form-control numeric textSum" e="<?=$order_datas->id?>" ship_qy_val="<?= $count_sold_item_air ?>"  value="<?= $count_sold_item_air ?>" id="ship_qty<?=$order_datas->id?>" name="ship_qty[]" type="text" data-parsley-maxlength='18' maxlength='18' onblur="return check_ship_qty(<?=$order_datas->id?>)"></td>
											
											
                                            <td>
                                                (RM) {{$order_datas->product_price}}
                                            </td>
											
										<?php $total_stock_sum = $total_stock_sum +$order_datas->uk_stock+$count_sold_item_sea+$count_sold_item_air;
										$total_ship_qty = $total_ship_qty + $count_sold_item_air;
										
										$total_air = $total_air +$count_sold_item_air;
										$total_sea = $total_sea +$count_sold_item_sea;
										?>
										</tr>
									@endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-2 pull-right text-center">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead>
                                            <tr>
                                                <td>
                                                    <p class="invisible">Total</p>
                                                    <p class="m-0">Total</p>
                                                </td>
                                                <td>
                                                    <p>Total Stock</p>
                                                    <span class="badge badge-success badge-pill">{{$total_stock_sum}}</span>
                                                </td>
                                                <td>
                                                    <p class="sold_air_sea_header"> Sold Item (Air)</p>
                                                    <span class="badge badge-success badge-pill sold_air_sea_value"><?= $total_air?></span>
                                                </td>
                                                <td>
                                                    <p>Ship Qty.</p>
                                                    <span class="badge badge-success badge-pill" id="total_ship_quantity">{{$total_ship_qty}}</span>
                                                    <input type="hidden" id="total_ship_quantity_value" name="total_ship_quantity_value" value="<?= $total_air ?>" />
                                                </td>
                                            </tr>
                                        </thead>
                                    </table>
									<input type="hidden" id="sold_item_air" name="sold_item_air" value="<?= $total_air ?>" />
									<input type="hidden" id="sold_item_sea" name="sold_item_sea" value="<?=$total_sea ?>" />
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <hr>
                            <div class="form-group text-right m-b-0">
                             <ul class="parsley-errors-list filled" id="atleast_item" style="display:none;"><li class="parsley-required">Please add Atleast 1 Item</li></ul>
                                <button class="btn btn-primary waves-effect waves-light" type="button" id="submit_ship">
                                Ship
                                </button>
                            </div>
                         {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div> 

<input type="hidden" id="sum" name="sum" value="0" />	
<footer class="footer text-right">2018 © UKSHOP.</footer>
</div>


@endsection

@section('scripts')
<script src="{{asset('public/main_theme/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('public/main_theme/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('public/main_theme/js/sweetalert2.min.js')}}"></script>
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 <script src="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
$(function() {
		$("#max_date").datepicker();
		
	});

var dateToday = new Date();
var dates = $("#max_date").datepicker({
    defaultDate: "+1w",
	 format: "dd-mm-yyyy",
    changeMonth: true,
    numberOfMonths: 1,
    minDate: dateToday,
    onSelect: function(selectedDate) {
        var option = this.id == "max_date" ? "minDate" : "maxDate",
            instance = $(this).data("datepicker"),
            date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
        dates.not(this).datepicker("option", option, date);
    }
});

$(document).ready(function() {
var table = $('#shipment').DataTable( {
scrollX:        true,
scrollCollapse: true,
paging:         false,
colReorder: true,
"order": [[7, "desc" ]],
"columnDefs": [ {
"targets": 0,
"width": "10px"
},
{
"targets": 1,
"width": "50px"
},
{
"targets": 2,
"width": "100px"
},
{
"targets": 3,
"width": "230px"
},
{
"targets": 4,
"width": "100px"
},
{
"targets": 5,
"width": "80px"
},
{
"targets": 6,
"width": "100px"
},
{
"targets":7,
"width": "100px"
},
{
"targets":8,
"width": "80px"
},
{
"targets":9,
"width": "100px"
}],
fixedColumns: true
} );

/*$('#form_submit').on('submit', function(e){
      var form = $('#form_submit');

      // Encode a set of form elements from all pages as an array of names and values
      var params = table.$('input,select,textarea').serializeArray();

      // Iterate over all form elements
      $.each(params, function(){
         // If element doesn't exist in DOM
         if(!$.contains(document, form[this.name])){
            // Create a hidden element
            $(form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', this.name)
                  .val(this.value)
            );
         }
      });
});*/
} );

jQuery.moveColumn = function (table, from, to) {
    var rows = jQuery('tr', table);
    var cols;
    rows.each(function() {
        cols = jQuery(this).children('th, td');
        cols.eq(from).detach().insertBefore(cols.eq(to));
    });
}

function change_type(e)
{
	/*$(function(){
		jQuery.each($("table tr"), function() 
		{ 
			$(this).children(":eq(7)").after($(this).children(":eq(6)"));
		});
	});*/
	var tbl = jQuery('#shipment');
	jQuery.moveColumn(tbl, 7, 6);
	$(function(){
		jQuery.each($("table tr"), function() 
		{ 
			 var row_id = $(this).attr("id");
			if($.isNumeric(row_id))
			{
				if(e == 'sea')
				{
					var quantity = $("td.sold_sea"+row_id).html();
					$("#ship_qty"+row_id).val(quantity);
					var sum = $("#sum").val();
					sum = parseFloat(sum) + parseFloat(quantity);
					$("#sum").val(sum);
					var sold_item_sea = $("#sold_item_sea").val();
					 $(".sold_air_sea_value").html(sold_item_sea);
					 $(".sold_air_sea_header").html('Sold Item (Sea)');
					 $(".forshipmenttype").html('BL<span class="text-danger">*</span>');
					 var restr = $("#shipment_name").val();
					 $("#shipment_name").val(restr.replace("AIR", "SEA"));
					 $("table tr>th:eq(7)").text('Sold Item Sea');
					 $("table tr>th:eq(6)").text('Air Order');
				}else if(e == 'air')
				{
					var quantity = $("td.sold_air"+row_id).html();
					$("#ship_qty"+row_id).val(quantity);
					var sum = $("#sum").val();
					sum = parseFloat(sum) + parseFloat(quantity);
					$("#sum").val(sum);
					var sold_item_air = $("#sold_item_air").val();
					 $(".sold_air_sea_value").html(sold_item_air);
					 $(".sold_air_sea_header").html('Sold Item (Air)');
					 $(".forshipmenttype").html('AWB<span class="text-danger">*</span>');
					  var restr = $("#shipment_name").val();
					 $("#shipment_name").val(restr.replace("SEA", "AIR"));
					 $("table tr>th:eq(6)").text('Sold Item Sea');
					 $("table tr>th:eq(7)").text('Air Order');
				}
				
			}
		});
		var total_ship_qty = $("#sum").val();
		$("#total_ship_quantity").html(total_ship_qty);
		$("#total_ship_quantity_value").val(total_ship_qty);
		$("#sum").val(0);
	});
	
}

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



$(document).ready(function(){
$("#shipment").on('input', '.textSum', function () {
	var e= $(this).attr("e");
	//alert(e);
	var ukstock = $(".uk_stock"+e).html();
	var ship_qty = $("#ship_qty"+e).val();
	var sum=0;
	$('#atleast_item').hide();
	if($("#shipment_type_air").prop('checked') == true)
	{
	    var sold_air = $(".sold_air"+e).html();
		var sum = parseFloat(ukstock) + parseFloat(sold_air);
		if(ship_qty > sum)
		{
			$("#ship_qty"+e).val(sum);
		}
       var calculated_total_sum = 0;
       $("#shipment .textSum").each(function () {
           var get_textbox_value = $(this).val();
           if ($.isNumeric(get_textbox_value)) {
              calculated_total_sum += parseFloat(get_textbox_value);
              }                  
       });
       $("#total_ship_quantity").html(calculated_total_sum);
	   $("#total_ship_quantity_value").val(calculated_total_sum);
    }
	if($("#shipment_type_sea").prop('checked') == true)
	{
	    var sold_sea = $(".sold_sea"+e).html();
		var sum = parseFloat(ukstock) + parseFloat(sold_sea);
		if(ship_qty > sum)
		{
			$("#ship_qty"+e).val(sum);
		}
		var calculated_total_sum = 0;
        $("#shipment .textSum").each(function () {
           var get_textbox_value = $(this).val();
           if ($.isNumeric(get_textbox_value)) {
              calculated_total_sum += parseFloat(get_textbox_value);
              }                  
       });
       $("#total_ship_quantity").html(calculated_total_sum);
	   $("#total_ship_quantity_value").val(calculated_total_sum);	
	}
  });   
});



$('#submit_ship').on('click',function(){
	
		var total_ship_quantity = $('#total_ship_quantity').text();
		if(total_ship_quantity<1){
			$('#atleast_item').show();
			return false;
		}else{
			$('#atleast_item').hide();
			 if($('#form_submit').parsley().validate()){
				 
			 
				swal({
					title: 'Are you sure?',
					text: "You won't be able to revert this!",
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#4fa7f3',
					cancelButtonColor: '#d57171',
					confirmButtonText: 'Yes, sure!'
				}).then(function () {
					$('#form_submit').submit();
					},
				 function (dismiss) {		 
					if (dismiss === 'cancel') {
						swal(
							'Cancelled',
							'Your  file is safe :)',
							'error'
						)
					}
					return false;
			
				})
			 }
		}
	
});

$(document).ready(function(){
    $("form").submit(function(){
        $("#loading").css("display", "block");
    });
});
</script>
@append
