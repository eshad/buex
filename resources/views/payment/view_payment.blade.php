@extends('layouts.master')

@section('css')
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
				<li class="breadcrumb-item active">View Payment</li>
             </ol>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
        
          <div class="card-box">
              <div  class="form-group row" id="sandbox-container">
				<label class="col-2 col-form-label">Payment ID :</label>
				 <div class="col-4">
                  <label class="col-form-label"><strong>{{ $payment_details->payment_code}}</strong></label>
              
               
                </div>
                    
               </div>
			 
              <div class="form-group row">
				<label class="col-2 col-form-label">Payment Source{{$payment_details->payment_source}}</label>
					<div class="col-4">
                    
                    <select disabled onchange="getUserUserOrders(this.value)"  name ="payment_source" id="payment_source" class="selectpicker parsley-success" data-live-search="true">
                    <option selected="selected" value="">Select</option>
                    @foreach($payment_source as $payment_source)
                       @if($payment_source->id==$payment_details->payment_source)
                       <option  selected="selected"  value="{{$payment_source->id}}" data-val="{{$payment_source->source_name}}">{{$payment_source->source_name}}</option>
                       else
                        <option  value="{{$payment_source->id}}" data-val="{{$payment_source->source_name}}">{{$payment_source->source_name}}</option>
                         @endif                
                       @endforeach
                    </select> 
					</div>
               </div>
			   
              <div class="form-group row">
				<label class="col-2 col-form-label">Select Customer</label>
					<div class="col-4">
                      <select disabled required onchange="getUserOrders(this.value)"  name ="payment_customer" id ="payment_customer" class="selectpicker parsley-success" data-live-search="true" data-parsley-required-message='Please Select customer'>
                       
					  <option selected="selected" value="">Select</option>
                       @foreach($customer_list as $customer_list)
                        @if($customer_list->id==$payment_details->payment_customer)
                       <option selected="selected"  value="{{$customer_list->id}}" data-val="{{$customer_list->customer_full_name}}">{{$customer_list->customer_full_name}}</option>
                         else
                       	<option  value="{{$customer_list->id}}" data-val="{{$customer_list->customer_full_name}}">{{$customer_list->customer_full_name}}</option>			                         @endif           
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
				<label class="col-2 col-form-label">Payment Date<span class="text-danger"> *</span></label>              <?php  $payment_date=date('d-m-Y', strtotime($payment_details->payment_date));?> 
					{{ Form::text('payment_date',$payment_date,['placeholder' => 'Payment Date' , 'class' =>'form-control ','style'=>'width:30%;margin-left:1.6%;','autocomplete' => 'off', 'id'=>'currentDate','required'=>'' ,'data-parsley-required-message'=>'Please Enter Payment Date', 'data-parsley-trigger'=>'change','readonly'=>'readonly']) }}
                    
               </div>
			  
               <div class="form-group row">
			 	<label class="col-2 col-form-label">Payment Amount(RM)<span class="text-danger">*</span></label>
					<div class="col-4">
						{{ Form::text('payment_amount',$payment_details->payment_amount,['placeholder' => 'Payment Amount (RM)' , 'class' =>'form-control numeric ','autocomplete' => 'off', 'id'=>'payment_amount' ,'required'=>'','data-parsley-required-message'=>'Please Enter Payment Amount (RM)','data-parsley-maxlength'=>'18','maxlength'=>'18', 'data-parsley-trigger'=>'change', 'data-parsley-pattern'=>'^[\d\+\-\.\(\)\/\s]*$','data-parsley-type'=>'number','onchange'=>'change_amm_rec();','data-parsley-pattern-message'=>'Please Enter Only Number','readonly'=>'readonly']) }} 
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
						{{ Form::textarea('payment_note',$payment_details->payment_note,['placeholder' => 'Customer Note','rows'=>'2','parsley-trigger' => 'change' , 'class' =>'form-control','autocomplete' => 'off', 'id'=>'payment_note' ,'data-parsley-required-message'=>'Please Enter payment Note','data-parsley-maxlength'=>'50','maxlength'=>'50','readonly'=>'readonly']) }}
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
						{{ Form::text('payment_ref_number',$payment_details->payment_ref_number,['placeholder' => 'Ref. Number/Slip Number' , 'class' =>'form-control','autocomplete' => 'off', 'id'=>'payment_ref_number' ,'data-parsley-required-message'=>'Please Enter payment ref number','data-parsley-maxlength'=>'18','maxlength'=>'18', 'data-parsley-trigger'=>'keyup','required'=>'','data-parsley-refnum'=>'','readonly'=>'readonly']) }}  
						@if ($errors->has('payment_ref_number'))
                        <i class="fa fa-spinner fa-spin" style="font-size:24px"></i>
						<ul class="parsley-errors-list filled">
							<li class="parsley-required">{{ $errors->first('payment_ref_number') }}</li>
						</ul>
						@endif
					
					</div>
			        <a href="javascript:void();" id="loader_spinner" class="btn btn-sm" style="display:none;"><i class="fa fa-spinner fa-spin" style="font-size:24px"></i></a>
			</div>
				
				
			<div class="form-row">
                  <div class="form-group col-md-12">
						<label disabled for="inputEmail4" class="col-form-label">Upload image</label>
                        <?php
						if(count($payment_image_name)>0)
						{
							$image_name = "payment_image/normal_images/".$payment_image_name[0]->image_name ;
							
						}
						else
						{
							$image_name = "main_theme/images/no_image.jpg";
						}
						?>
                        
                         <img style='max-width:100%;height:110px;border:2px solid #ccc;border-radius:15px;'src="{{ asset('public/')}}/{!!$image_name!!}" class="img-fluid img-rounded" id="profile-img2-tag" />
						
						
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
                    <th width="16%">Payment</th>
                  </tr>
                </thead>
                <tbody id="order_list">
                <?php 
				$n=1;
				?>
                 @foreach($payment_lines as $payment_lines)
                 <?php 
				// print_r($payment_lines);
				 ?>
                  <tr id="tr_0">
                    <td width="4%">{{$n}}</td>
                    <td width="16%">{{$payment_lines->order_code}}</td>
                    <td width="16%">{{$payment_lines->note}}</td>
                    <td width="16%">{{date('d-m-Y', strtotime($payment_lines->order_date))}}</td>
                    <td width="16%">{{$payment_lines->order_total}}</td>
                    <td width="16%">{{$payment_lines->payment_amount}}</td>
                  </tr>
                  <?php 
				$n++;
				?>
				 @endforeach
                </tbody>
              </table>
              <div class="col-md-8 pull-right">
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <td style="width:15%;">
                        <p>Amount to Apply</p>
                        <label>{{$payment_details->payment_amount}}</label>
                       <!-- <input class="form-control"  readonly="readonly" id="applyAmount" name="applyAmount"  placeholder="0.00" type="text" >-->
                        </td>
                      </tr>
                       <tr>
                        <td style="width:15%;">
                        <p>Amount to Credit</p>  
                        <label>{{ 
                        number_format($payment_details->payment_amount-$payment_details->order_amount,2)
                         }}</label>
                        </td>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
          </div>
        </div> 
         
		  <div class="form-group text-right m-b-0">
		
		   <a href="{{url('/view_customer_balance')}}/{{ $customer_id}}" class="btn btn-primary waves-effect waves-light">
			Back
			</a>
		</div>
        
      </div>
    </div>
  </div>
<footer class="footer text-right"> 2018 © UKSHOP. </footer>
</div>
</div>
@endsection

@section('scripts')


@append