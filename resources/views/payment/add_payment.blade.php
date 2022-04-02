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

<div class="content-page"> 
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="page-title-box">
            <h4 class="page-title float-left">Payment</h4>
            <ol class="breadcrumb float-right">
				<li class="breadcrumb-item"><a href="customer.php">Customer</a></li>
				<li class="breadcrumb-item"><a href="addPayment.php">Payment</a></li>
				<li class="breadcrumb-item active">Manage Payment</li>
             </ol>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          {!! Form::open(['url' => 'payment','id'=>'form_submit','enctype'=>'multipart/form-data','class'=>'']) !!}	
			 {{ csrf_field() }}
          <div class="card-box">
              <div  class="form-group row" id="sandbox-container">
				<label class="col-2 col-form-label">Payment ID :</label>
				 <div class="col-4">
                  <label class="col-form-label"><strong>{{$payment_code}}</strong></label>
              
                 {{ Form::hidden('payment_code',$payment_code) }} 
                </div>
                    
               </div>
			 
              <div class="form-group row">
				<label class="col-2 col-form-label">Payment Source<span class="text-danger"> *</span></label>
					<div class="col-4">
                    <select onchange="getUserUserOrders(this.value)"  name ="payment_source" id="payment_source" class="selectpicker parsley-success" data-live-search="true" required data-parsley-required-message='Please Select Payment Source'>
                    <option selected="selected" value="">Select</option>
                    @foreach($payment_source as $payment_source)
                    <option value="{{$payment_source->id}}" data-val="{{$payment_source->source_name}}">{{$payment_source->source_name}}</option>				                                    
                       @endforeach
                    </select> 
					</div>
               </div>
			   
              <div class="form-group row">
				<label class="col-2 col-form-label">Select Customer<span class="text-danger"> *</span></label>
					<div class="col-4">
                      <select required onchange="getUserOrders(this.value)"  name ="payment_customer" id ="payment_customer" class="selectpicker parsley-success" data-live-search="true" data-parsley-required-message='Please Select customer'>
                       
					  <option selected="selected" value="">Select</option>
                       @foreach($customer_list as $customer_list)
                       <option value="{{$customer_list->id}}" data-val="{{$customer_list->customer_full_name}}">{{$customer_list->customer_full_name}}- {{$customer_list->customer_uniq_id}}</option>				                                    
                       @endforeach
                      </select>
						@if ($errors->has('payment_customer'))
							<ul class="parsley-errors-list filled">
								<li class="parsley-required">{{ $errors->first('payment_customer') }}</li>
							</ul>
						@endif
					</div>
               </div>
			  
              <div  class="form-group row" id="sandbox-container">
				<label class="col-2 col-form-label">Payment Date<span class="text-danger"> *</span></label>
					{{ Form::text('payment_date',date('d-m-Y'),['placeholder' => 'Payment Date' , 'class' =>'form-control ','style'=>'width:30%;margin-left:1.6%;','autocomplete' => 'off', 'id'=>'currentDate','required'=>'' ,'data-parsley-required-message'=>'Please Enter Payment Date', 'data-parsley-trigger'=>'change']) }}
                    
                    
                    
               </div>
			  
              <div class="form-group row">
				<label class="col-2 col-form-label">Payment Amount(RM)<span class="text-danger">*</span></label>
					<div class="col-4">
						{{ Form::text('payment_amount','',['placeholder' => 'Payment Amount (RM)' , 'class' =>'form-control numeric ','autocomplete' => 'off', 'id'=>'payment_amount' ,'required'=>'','data-parsley-required-message'=>'Please Enter Payment Amount (RM)','data-parsley-maxlength'=>'18','maxlength'=>'18', 'data-parsley-trigger'=>'change', 'data-parsley-pattern'=>'^[\d\+\-\.\(\)\/\s]*$','data-parsley-type'=>'number','onkeyup'=>'change_amm_rec();','data-parsley-pattern-message'=>'Please Enter Only Number']) }} 
						@if ($errors->has('payment_amount'))
						<ul class="parsley-errors-list filled">
							<li class="parsley-required">{{ $errors->first('payment_amount') }}</li>
						</ul>
						@endif
					</div>		
			 </div>
			  
             <div class="form-group row">
				<label class="col-2 col-form-label">Customer Note</label>
					<div class="col-4">
						{{ Form::textarea('payment_note','',['placeholder' => 'Customer Note','rows'=>'2','parsley-trigger' => 'change' , 'class' =>'form-control','autocomplete' => 'off', 'id'=>'payment_note' ,'data-parsley-required-message'=>'Please Enter payment Note','data-parsley-maxlength'=>'50','maxlength'=>'50']) }}
						@if ($errors->has('payment_note'))
						<ul class="parsley-errors-list filled">
							<li class="parsley-required">{{ $errors->first('payment_note') }}</li>
						</ul>
						@endif								
					</div>
			</div>
						
                <div class="form-group row">
				<label class="col-2 col-form-label">Ref. Number/Slip Number<span class="text-danger">*</span>:</label>
					<div class="col-4">
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
				
				
			<div class="form-row">
                  <div class="form-group col-md-12">
						<label disabled for="inputEmail4" class="col-form-label">Upload image</label>
                         <img style='max-width:100%;height:110px;border:2px solid #ccc;border-radius:15px;'src="{{URL::asset('/public/main_theme/images/no_image.jpg')}}"
						 class="img-fluid img-rounded" id="profile-img2-tag" />
						
						<label style="display:none;color:blue;" class="lbl remove2" >Remove</label>
						
						<label class="lbl add2" style='color:blue;display:block;' for="profile-img2">Add</label>
						
						<input data-parsley-required-message='Please Select Category Image' style="display:none" class="form-control" accept="image/*" name="image_2" id="profile-img2" data-parsley-max-file-size="2000" data-parsley-fileextension='jpeg,jpg,png' type="file">

						<input type="hidden" id="checkimg2" value="0" />
						
						@if($errors->has('image_2'))
						<ul class="parsley-errors-list filled">
							<li class="parsley-required">{{ $errors->first('image_2') }}</li>
						</ul>
						@endif
					</div>
            </div>			
        
          </div>
         
          <div class="card-box">
            <div class="table-responsive">
              <table  class="table table-bordered table-hover" id="orderTable">
                <thead>
                  <tr>
                    <th width="4%">S.No.</th>
                    <th width="16%">Order Number</th>
                    <th width="16%">Order Note</th>
                    <th width="16%">Order Date</th>
                    <th width="16%">Original amount</th>
                    <th width="10%">Penalty Amount</th>
                    <th width="10%">Open balance</th>
                    <th width="16%">Payment</th>
                  </tr>
                </thead>
                <tbody id="order_list">
                  <tr id="tr_0">
                    <td colspan="8" align="center">Please select customer.</td>
                   
                  </tr>
				 
                </tbody>
              </table>
              <div class="col-md-8 pull-right">
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <td style="width:15%;">
                        <p>Amount to Apply</p>
                        <input class="form-control"  readonly="readonly" id="applyAmount" name="applyAmount"  placeholder="0.00" type="text">
                        </td>
                      </tr>
                       <tr>
                        <td style="width:15%;">
                        <p>Amount to Credit</p>
                        <input class="form-control" name="creditAmount" readonly="readonly" id="creditAmount" placeholder="0.00" type="text">
                        </td>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
          </div>
        </div> 
         
		  <div class="form-group text-right m-b-0">
			<button class="btn btn-primary waves-effect waves-light" type="submit" id="submit_form_button">
			Save
			</button>
			<!--  <a href="#penalty" data-animation="blur" data-plugin="custommodal" data-overlayspeed="10" data-overlaycolor="#36404a" class="btn btn-danger waves-effect m-l-5">Penalty</a>
-->
			<button type="reset" class="btn btn-light waves-effect m-l-5">
			Cancel
			</button>
		</div>
          {!! Form::close() !!}
      </div>
    </div>
  </div>
<footer class="footer text-right"> 2018 © UKSHOP. </footer>
</div>
</div>
@endsection

@section('scripts')
<script src="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script>
$(function() {
	
		$("#currentDate").datepicker(
		{format: 'dd-mm-yyyy'});
		
		
	});
function readURL(input,myid) 
{
	if (input.files && input.files[0]) 
	{
       var reader = new FileReader();
       reader.onload = function (e) 
	   {
        $('#'+myid +'-tag').attr('src', e.target.result);
       }
       reader.readAsDataURL(input.files[0]);
    }
}

$("#profile-img2").change(function(){		
        readURL(this,'profile-img2');
		$('.add2').css('display','none');
		$('#checkimg2').val('1');
		$('.remove2').css('display','block');
});
	
$('.remove2').click(function(){
	$('#profile-img2-tag').attr('src',"{{URL::asset('/public/main_theme/images/no_image.jpg')}}");
	$('#profile-img2').val('');
	$('#checkimg2').val('0');
	$('.add2').css('display','block');
	$('.remove2').css('display','none');
});

function getUserUserOrders(e)
{
	var cid=$('#payment_customer').val();
	if(e!=1){$('#payment_amount').prop('readonly', false);}else{$('#payment_amount').prop('readonly', true);}
	if(cid!=''){
	  getUserOrders(cid);
	}
}


/*get user order list*/
function getUserOrders(id){
	 var formData = {
	  userid: id
     }
	 
	 var payment_source =$('#payment_source').val();
	 if(payment_source==1){var read='readonly';}else{var read='';}
	 
	 var my_url = APP_URL+'/ajax_getuser_orderlist';
	 $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		 }
   	 })	
	 
	 if(payment_source==1){
	  	$.ajax({
			type: "POST",
			url: APP_URL+'/ajax_getuser_credit',
			data: formData,
			dataType: 'json',
			success: function (data) {
				if(data[0]>0 && data[0]!=null ){
				  var e=parseFloat(data[0]).toFixed(2);
				  $('#payment_amount').val(e);
				  //change_amm_rec(e)
				}
				else
				{
				   $('#payment_amount').val('0.00');
				}
			}
		});
	 }
	 else{
		 $('#payment_amount').val('');
	 }
	 
	 
	    $.ajax({
			type: "POST",
			url: my_url,
			data: formData,
			dataType: 'json',
			success: function (data) {
	             var order_list = '';
				 var sn=1;
				 $('#orderTable #order_list tr').remove();
				 
				 if(data.length>0){
					 for(key in data)   
					 {
						 $('#submit_form_button').prop('disabled', false); 
						 order_list += '<tr id="row_'+sn+'"><td>' + sn + '</td><td>' + data[key].order_code + '</td><td>' + data[key].note + '</td><td>' + data[key].order_date + '</td><td>' + data[key].order_total + '</td><td><input type="hidden" id="openBalance" name="li_amm[]" value="' + data[key].amount_penalty + '" />' + data[key].amount_penalty + '</td><td><input type="hidden" id="openBalance" name="li_penalty_amm[]" value="' + data[key].open_amount + '" />' + data[key].open_amount2 + '</td><td><input type="text" ' + read + '  placeholder="0.00"  class="form-control ordervalue orderValue1" value=""  name="pay_line_amm[]" data-val="'+data[key].open_amount+'" onchange="change_pay_line_amm(this);"  /><input type="hidden" name="order_id[]" value="' + data[key].id + '" /></td></tr>';
						 sn++;
					 }
					 var payment_amount= $('#payment_amount').val();
				 }
				 else
				 {
					$('#submit_form_button').prop('disabled', true); 
					 
					order_list += '<tr><td colspan="7" style="color:red;" align="center">Record not available. </td></tr>'   
				 }
	             $('#orderTable #order_list').append(order_list);
				 change_amm_rec()
			},
			error: function (data) {
				console.log('Error:', data);
			}
		});
}

$(document).on("blur",".ordervalue", function (event) { 
  var open_amount=$(this).data('val'); 
	var ordervalue=$(this).val(); 
var add = 0;
	if(open_amount < ordervalue){$(this).val(open_amount);}
	$(".orderValue1").each(function() {
		add += Number($(this).val());
	});
	//$("#").val(add);
	$("#payment_amount").val(parseFloat(add).toFixed(2)); 
	$("#applyAmount").val(parseFloat(add).toFixed(2)); 
	//$("#creditAmount").val(parseFloat('0.00').toFixed(2)); 
});
	
	
$(document).on("keyup",".ordervalue", function (event) {
	var add = 0;
	$(".orderValue1").each(function() {
		add += Number($(this).val());
	});
	//$("#").val(add);
	$("#payment_amount").val(parseFloat(add).toFixed(2)); 
	$("#applyAmount").val(parseFloat(add).toFixed(2)); 
	$("#creditAmount").val(parseFloat('0.00').toFixed(2)); 
});


$(document).on("keyup","#payment_amount", function (event) {
	 //  alert();
	   var amm_rec= $('#payment_amount').val();
	  ///console.log(amm_rec);
	   if($('#payment_amount').val().length >0){
	   var old_rec_amm = amm_rec;
	   $rowno=$("#orderTable tr").length;
	   for($i=1;$i<$rowno;$i++){
	 		var line_amm =$('#row_'+$i).find('input[name="li_penalty_amm[]"]').val()*1;
			//var line_amm = $('#row_'+$i).find('input[name="li_amm[]"]').val();
			
			if(parseFloat(amm_rec) > parseFloat(line_amm)){
			$('#row_'+$i).find('input[name="pay_line_amm[]"]').val(parseFloat(line_amm).toFixed(2));
				amm_rec = parseFloat(amm_rec) - parseFloat(line_amm);
			}
			else if(amm_rec !=0){
			$('#row_'+$i).find('input[name="pay_line_amm[]"]').val(parseFloat(amm_rec).toFixed(2));
			amm_rec =0;
			}
			else{
				$('#row_'+$i).find('input[name="pay_line_amm[]"]').val('0.00');
				//$("#creditAmount").val(parseFloat('0.00').toFixed(2)); 
			}
		}
		if($rowno> 0){ 
		    var add=0; 
			$('input[name="pay_line_amm[]"]').each(function() {
		       add += Number($(this).val());
			});
			
			$("#applyAmount").val(parseFloat(add).toFixed(2)); 
			//var amm_credit = parseFloat(amm_rec) - parseFloat(add);
			if(old_rec_amm!=0)
			{
			    //console.log($('#payment_amount').val());
			 
			    $("#creditAmount").val(parseFloat($('#payment_amount').val()-add).toFixed(2)); 
			
			}
		}
		else{
			$('#applyAmount').val('0.00');
			$('#creditAmount').val('0.00');
		}
		
	   }
	   else{
		  $('#creditAmount').val('0.00');  
		  $('.orderValue1').val('0.00');  
	   }
		
 
});


function change_amm_rec(){  //alert('hello dost');
	   /*var amm_rec= $('#payment_amount').val();
	   var old_rec_amm = amm_rec;
	   $rowno=$("#orderTable tr").length;
	   for($i=1;$i<$rowno;$i++){
	 		var line_amm =$('#row_'+$i).find('input[name="li_penalty_amm[]"]').val()*1;
			//var line_amm = $('#row_'+$i).find('input[name="li_amm[]"]').val();
			
			if(parseFloat(amm_rec) > parseFloat(line_amm)){
			$('#row_'+$i).find('input[name="pay_line_amm[]"]').val(parseFloat(line_amm).toFixed(2));
				amm_rec = parseFloat(amm_rec) - parseFloat(line_amm);
			}
			else if(amm_rec !=0){
			$('#row_'+$i).find('input[name="pay_line_amm[]"]').val(parseFloat(amm_rec).toFixed(2));
			amm_rec =0;
			}
			else{
				$('#row_'+$i).find('input[name="pay_line_amm[]"]').val('0.00');
				//$("#creditAmount").val(parseFloat('0.00').toFixed(2)); 
			}
		}
		if($rowno> 0){ 
		    var add=0; 
			$('input[name="pay_line_amm[]"]').each(function() {
		       add += Number($(this).val());
			});
			
			$("#applyAmount").val(parseFloat(add).toFixed(2)); 
			//var amm_credit = parseFloat(amm_rec) - parseFloat(add);
			if(amm_rec!='')
			{
			  $("#creditAmount").val(parseFloat(amm_rec).toFixed(2)); 
			}
		}
		else{
			$('#applyAmount').val('0.00');
			$('#creditAmount').val('0.00');
		}*/
		
 }
 
function check_ref_number(e){
	//alert(e);
	 var payref=e;
     $("#loader_spinner").css("display", "block");
	 $('#error_pay_ref_no').html('');
	 $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		 }
   	 })	
	 
	 $.ajax({
			type: "POST",
			url: APP_URL+'/ajax_payment_ref_number',
			data: {pay_ref_id:payref},
			success: function (data) {  
				$("#loader_spinner").css("display", "none");
				if(data!='true')
				{
					$('#error_pay_ref_no').html('Reference number alredy used try another number!');
					$("#error_pay_ref_no").css("color", "red");
					$('#payment_ref_number').val('');
				}
				else
				{
					$('#error_pay_ref_no').html('Reference number available');
					$("#error_pay_ref_no").css("color", "green");
				}
				
				
			}
		});
	 
}


<!--check Reference number -- >
window.ParsleyValidator.addValidator('refnum', 
function (value, requirement) {
  var ret = '';
  var my_url = APP_URL+'/ajax_payment_ref_number';
  var formData = {pay_ref_id:value}
  $.ajaxSetup({
   headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   }
  }) 
  $.ajax({
   type: "POST",
   url: my_url,
   async: false,
   data: formData,
   success: function (data) {
       ret = data;
   }
  });
    if(ret!='true'){ return false;}else{ return true;}
}, 32)
.addMessage('en', 'refnum', 'Reference number alredy used try another number!');
<!--end-- >

</script>
@if($order_details!='')
<script>
$(document).ready(function(){
$('#payment_customer').val('{{$order_details->customer_id}}').trigger('change');
});
</script>
@endif
@if($customer_id!='')
<script>
$(document).ready(function(){
$('#payment_customer').val('{{$customer_id}}').trigger('change');
});
</script>
@endif
@append