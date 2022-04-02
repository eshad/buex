@extends('layouts.master')

@section('css')

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
<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title float-left">Add Customer</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="{{url('/customer')}}">Customer</a></li>
                            <li class="breadcrumb-item active">Add Customer</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->
            
            <div class="row">
                <div class="col-12">
                    <div class="card-box">
                        {!! Form::open(['url' => 'customer','class'=>'form-horizontal']) !!}	
						
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Customer ID :</label>
                                <div class="col-4">
                                    <label class="col-form-label"><strong>{{$customer_uniq_id}}</strong></label>
                                 
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Full Name <span class="text-danger">*</span></label>
                                <div class="col-4">
                                   {{ Form::text('customer_full_name','',['placeholder' => 'Full Name','autocomplete'=>'off','parsley-trigger' => 'change','required'=>'' , 'class' =>'form-control', 'id'=>'customer_full_name' ,'data-parsley-required-message'=>'Please Enter Full Name','data-parsley-maxlength'=>'100','maxlength'=>'100']) }}
                                   @if ($errors->has('customer_full_name'))
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">{{ $errors->first('customer_full_name') }}</li>
                                        </ul>
									 @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Address 1 <span class="text-danger">*</span></label>
                                <div class="col-4">
                                    {{ Form::text('address_1','',['placeholder' => 'Address 1','autocomplete'=>'off','parsley-trigger' => 'change','required'=>'' , 'class' =>'form-control', 'id'=>'address_1' ,'data-parsley-required-message'=>'Please Enter Address 1','data-parsley-maxlength'=>'100','maxlength'=>'100']) }}
                               		@if ($errors->has('address_1'))
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">{{ $errors->first('address_1') }}</li>
                                        </ul>
									 @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Address 2</label>
                                <div class="col-4">
                                    {{ Form::text('address_2','',['placeholder' => 'Address 2','autocomplete'=>'off','parsley-trigger' => 'change' , 'class' =>'form-control', 'id'=>'address_2' ,'data-parsley-maxlength'=>'100','maxlength'=>'100']) }}
                                	@if ($errors->has('address_2'))
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">{{ $errors->first('address_2') }}</li>
                                        </ul>
									 @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Address 3</label>
                                <div class="col-4">
                                    {{ Form::text('address_3','',['placeholder' => 'Address 3','autocomplete'=>'off','parsley-trigger' => 'change' , 'class' =>'form-control', 'id'=>'address_3' ,'data-parsley-maxlength'=>'100','maxlength'=>'100']) }}
                                	@if ($errors->has('address_3'))
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">{{ $errors->first('address_3') }}</li>
                                        </ul>
									 @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">City/Town <span class="text-danger">*</span></label>
                                <div class="col-4">
                                    {{ Form::text('city','',['placeholder' => 'City/Town','autocomplete'=>'off','parsley-trigger' => 'change','required'=>'' , 'class' =>'form-control', 'id'=>'city' ,'data-parsley-required-message'=>'Please Enter City/Town','data-parsley-maxlength'=>'50','maxlength'=>'50']) }}
                                	@if ($errors->has('city'))
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">{{ $errors->first('city') }}</li>
                                        </ul>
									 @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Post Code <span class="text-danger">*</span></label>
                                <div class="col-4">
                                    {{ Form::text('postal_code','',['placeholder' => 'Post Code','autocomplete'=>'off','parsley-trigger' => 'change','required'=>'' , 'class' =>'form-control', 'id'=>'postal_code' ,'data-parsley-required-message'=>'Please Enter Post Code','data-parsley-maxlength'=>'100','maxlength'=>'100','data-parsley-minlength'=>'1']) }}
                                	@if ($errors->has('postal_code'))
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">{{ $errors->first('postal_code') }}</li>
                                        </ul>
									 @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">State <span class="text-danger">*</span></label>
                                <div class="col-4">
                                    {{ Form::text('state','',['placeholder' => 'State','autocomplete'=>'off','parsley-trigger' => 'change','required'=>'' , 'class' =>'form-control', 'id'=>'state' ,'data-parsley-required-message'=>'Please Enter State','data-parsley-maxlength'=>'30','maxlength'=>'30']) }}
                                	@if ($errors->has('state'))
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">{{ $errors->first('state') }}</li>
                                        </ul>
									 @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Country <span class="text-danger">*</span></label>
                                <div class="col-4">
                                    <select class="selectpicker" name="country_id" id="country_id" data-live-search="true"  data-style="btn-light">
                                       @foreach($Countries as $country)
                                       		@if($country->id==129)
                                        		<option value="{{$country->id}}" data-val="{{$country->phonecode}}" selected="selected">{{$country->name}}</option>				@else
                                        		<option value="{{$country->id}}" data-val="{{$country->phonecode}}">{{$country->name}}</option>				@endif
                                       @endforeach
                                    </select>
                                    @if ($errors->has('country_id'))
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">{{ $errors->first('country_id') }}</li>
                                        </ul>
									 @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Email <span class="text-danger">*</span></label>
                                <div class="col-4">
                                    {{ Form::email('email','',['placeholder' => 'Email','autocomplete'=>'off','parsley-trigger' => 'change' ,'required'=>'' ,'data-parsley-required-message'=>'Please Enter Email', 'class' =>'form-control', 'id'=>'email' ,'data-parsley-maxlength'=>'100','maxlength'=>'100']) }}
                                 	@if ($errors->has('email'))
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">{{ $errors->first('email') }}</li>
                                        </ul>
									 @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Mobile/Phone Number<span class="text-danger">*</span></label>
                                <div class="col-4">
                                    <div class="input-group">
                                        <button type="button" class="btn btn-light waves-effect" id="number_prefix"> +60 </button>
                                        {{ Form::text('mobile','',['placeholder' => 'Mobile/Phone Number','autocomplete'=>'off','parsley-trigger' => 'change','required'=>'' , 'class' =>'form-control', 'id'=>'mobile' ,'data-parsley-required-message'=>'Please Enter Mobile/Phone Number','data-parsley-minlength'=>'9','data-parsley-minlength-message'=>'Mobile/Phone Number should be greater then 9 digit','maxlength'=>'12','data-parsley-pattern'=>'^[\d\+\-\.\(\)\/\s]*$','data-parsley-pattern-message'=>'Please Enter Only Number','data-parsley-errors-container'=>'#err_msg_mobile_no']) }}
                                       
                                       
                                    </div>
                                    <div id="err_msg_mobile_no"> @if ($errors->has('mobile'))
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">{{ $errors->first('mobile') }}</li>
                                        </ul>
									 @endif
                                     </div>
                                </div>
                            </div>
                            <div class="alert alert-custom col-md-6" role="alert">
                                If Shipping Address is Different Please 
                                <a href="#newAddress" data-animation="blur" data-plugin="custommodal" data-overlayspeed="10" data-overlaycolor="#36404a" title="Edit"><strong>Click Here for Different Address</strong></a>
                            </div>
                            <div class="table-responsive">
                        <table id="multiple_add_table" class="table table-striped table-bordered table-hover table-sm" style="width: 100%; display:none">
                            <thead>
                                <tr>
                                    <th>Full Name</th>
                                    <th>Address</th>
                                    <th>City</th>
                                    <th>Post Code</th>
                                    <th>State</th>
                                    <th>Country</th>
                                    <th>Email</th>
                                    <th>Phone Number</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                        
                    </div>
                            <hr>
                            <div class="form-group text-right m-b-0">
                                <button class="btn btn-primary waves-effect waves-light" type="submit">
                                Save
                                </button>
                                 <a href="{{url('/customer')}}" class="btn btn-light waves-effect m-l-5">
                                Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                <!--Edit Product Modal-->
                <div id="newAddress" class="modal-demo">
                    <button type="button" class="close" onclick="Custombox.close();">
                    <span>&times;</span><span class="sr-only">Close</span>
                    </button>
                    <h4 class="custom-modal-title">For Different Delivery Address:</h4>
                    <div class="custom-modal-text">
                        <form id="another_address">
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="inputEmail4" class="col-form-label">Full Name</label>
                                    {{ Form::text('temp_customer_full_name','',['placeholder' => 'Full Name','autocomplete'=>'off','parsley-trigger' => 'change','required'=>'' , 'class' =>'form-control', 'id'=>'temp_customer_full_name' ,'data-parsley-required-message'=>'Please Enter Full Name','data-parsley-maxlength'=>'100','maxlength'=>'100']) }}
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="inputEmail4" class="col-form-label">Address Line 1</label>
                                   {{ Form::text('temp_address_1','',['placeholder' => 'Address 1','autocomplete'=>'off','parsley-trigger' => 'change','required'=>'' , 'class' =>'form-control', 'id'=>'temp_address_1' ,'data-parsley-required-message'=>'Please Enter Address 1','data-parsley-maxlength'=>'100','maxlength'=>'100']) }}
                                </div>
                            </div>
                             <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="inputEmail4" class="col-form-label">Address Line 2</label>
                                    {{ Form::text('temp_address_2','',['placeholder' => 'Address 2','autocomplete'=>'off','parsley-trigger' => 'change', 'class' =>'form-control', 'id'=>'temp_address_2' ,'data-parsley-maxlength'=>'100','maxlength'=>'100']) }}
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="inputEmail4" class="col-form-label">Address Line 3</label>
                                     {{ Form::text('temp_address_3','',['placeholder' => 'Address 3','autocomplete'=>'off','parsley-trigger' => 'change', 'class' =>'form-control', 'id'=>'temp_address_3' ,'data-parsley-maxlength'=>'100','maxlength'=>'100']) }}
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="inputEmail4" class="col-form-label">City/Town</label>
                                    {{ Form::text('temp_city','',['placeholder' => 'City/Town','autocomplete'=>'off','parsley-trigger' => 'change','required'=>'' , 'class' =>'form-control', 'id'=>'temp_city' ,'data-parsley-required-message'=>'Please Enter City/Town','data-parsley-maxlength'=>'50','maxlength'=>'50']) }}
                                </div>
                                 <div class="form-group col-md-6">
                                    <label for="inputEmail4" class="col-form-label">Post Code</label>
                                    {{ Form::text('temp_postal_code','',['placeholder' => 'Post Code','autocomplete'=>'off','parsley-trigger' => 'change','required'=>'' , 'class' =>'form-control', 'id'=>'temp_postal_code','data-parsley-required-message'=>'Please Enter Post Code','data-parsley-maxlength'=>'100','data-parsley-minlength'=>'1','maxlength'=>'100']) }}
                                </div>
                            </div>
                           
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="inputEmail4" class="col-form-label">State</label>
                                     {{ Form::text('temp_state','',['placeholder' => 'State','autocomplete'=>'off','parsley-trigger' => 'change','required'=>'' , 'class' =>'form-control', 'id'=>'temp_state' ,'data-parsley-required-message'=>'Please Enter State','data-parsley-maxlength'=>'30','maxlength'=>'30']) }}
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="inputEmail4" class="col-form-label">Country</label>
                                    <select class="selectpicker" name="temp_country_id" id="temp_country_id" data-live-search="true"  data-style="btn-light">
                                       @foreach($Countries as $country)
                                       		@if($country->id==129)
                                        		<option value="{{$country->id}}" data-val="{{$country->phonecode}}" selected="selected">{{$country->name}}</option>				@else
                                        		<option value="{{$country->id}}" data-val="{{$country->phonecode}}">{{$country->name}}</option>				@endif
                                       @endforeach
                                    </select>
                                </div>
                            </div>
                           
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="inputEmail4" class="col-form-label">Email</label>
                                     {{ Form::email('temp_email','',['placeholder' => 'Email','autocomplete'=>'off','parsley-trigger' => 'change', 'required'=>'' ,'data-parsley-required-message'=>'Please Enter Email','class' =>'form-control', 'id'=>'temp_email' ,'data-parsley-maxlength'=>'100','maxlength'=>'100']) }}
                                </div>
                                <div class="form-group col-md-6">
                                    <div class="form-group row">
                                <label class="col-12 col-form-label">Mobile/Phone Number</label>
                                <div class="col-12">
                                    <div class="input-group">
                                       <button type="button" class="btn btn-light waves-effect" id="temp_number_prefix"> +60 </button>
                                        {{ Form::text('temp_mobile','',['placeholder' => 'Mobile/Phone Number','autocomplete'=>'off','parsley-trigger' => 'change','required'=>'' , 'class' =>'form-control', 'id'=>'temp_mobile' ,'data-parsley-required-message'=>'Please Enter Mobile/Phone Number','data-parsley-minlength'=>'9','data-parsley-minlength-message'=>'Mobile/Phone Number should be greater then 9 digit','maxlength'=>'12','data-parsley-pattern'=>'^[\d\+\-\.\(\)\/\s]*$','data-parsley-pattern-message'=>'Please Enter Only Number','data-parsley-errors-container'=>'#temp_err_msg_mobile_no']) }}
                                    </div>
                                    <div id="temp_err_msg_mobile_no"></div>
                                </div>
                            </div>
                                </div>
                            </div>
                           <div class="form-group text-right m-b-0">
                                <button class="btn btn-primary waves-effect waves-light" type="submit">
                                Save
                                </button>
                               <button class="btn btn-light waves-effect m-l-5" type="button" onclick="Custombox.close();">Cancel</button>
                            </div>
                       {{ Form::close() }}
                    </div>
                </div>
                </div> <!-- end row -->
                </div> <!-- container -->
                </div> <!-- content -->
                <footer class="footer text-right">
                    2018 © UKSHOP.
                </footer>
            </div>
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->

@endsection


@section('scripts')
<script>


$("#country_id").change(function(){
   var prefix = $(this).find(':selected').data('val');
   $("#number_prefix").html('+'+prefix);
});
$("#temp_country_id").change(function(){
   var prefix = $(this).find(':selected').data('val');
   $("#temp_number_prefix").html('+'+prefix);
});

$(document).ready(function(){
    $("#another_address").submit(function(event){
		event.preventDefault();
		$("#multiple_add_table").show();
		var temp_customer_full_name = $('#temp_customer_full_name').val();
		var temp_address_1 = $('#temp_address_1').val();
		var temp_address_2 = $('#temp_address_2').val();
		var temp_address_3 = $('#temp_address_3').val();
		var temp_city = $('#temp_city').val();
		var temp_postal_code = $('#temp_postal_code').val();
		var temp_state = $('#temp_state').val();
		var temp_country_id = $('#temp_country_id').val();
		var temp_email = $('#temp_email').val();
		var temp_mobile = $('#temp_mobile').val();
		var prefix_mobile = $("#temp_number_prefix").html();
		var country_name = $("#temp_country_id option:selected").text();
         $rowno=$("#multiple_add_table tr").length;
		 $("#multiple_add_table tr:last").after("<tr id='row"+$rowno+"' ><td>"+temp_customer_full_name+"<input type='hidden' name='other_customer_full_name[]' value='"+temp_customer_full_name+"' /></td><td>"+temp_address_1+","+temp_address_2+","+temp_address_3+"<input type='hidden' name='other_address_1[]' value='"+temp_address_1+"' /><input type='hidden' name='other_address_2[]' value='"+temp_address_2+"' /><input type='hidden' name='other_address_3[]' value='"+temp_address_3+"' /></td><td>"+temp_city+"<input type='hidden' name='other_city[]' value='"+temp_city+"' /></td><td>"+temp_postal_code+"<input type='hidden' name='other_postal_code[]' value='"+temp_postal_code+"' /></td><td>"+temp_state+"<input type='hidden' name='other_state[]' value='"+temp_state+"' /></td><td>"+country_name+"<input type='hidden' name='other_country_id[]' value='"+temp_country_id+"' /></td><td>"+temp_email+"<input type='hidden' name='other_email[]' value='"+temp_email+"' /></td><td>"+prefix_mobile+" "+temp_mobile+"<input type='hidden' name='other_mobile[]' value='"+temp_mobile+"' /><td ><a href='javascript:void();' title='Delete' onclick=delete_itemrow('row"+$rowno+"')><i class='mdi mdi-delete'></i></a></td></tr>");
		 
		 Custombox.close();
		 $('#another_address')[0].reset();
		 $("#temp_country_id").val(129);
    });
});

function delete_itemrow(rowno)
{
	$('#'+rowno).remove();
}



window.ParsleyValidator.addValidator('mobile', 
function (value, requirement) {
    var ret = '';
    var my_url = APP_URL+'/ajax_check_mobile_duplicate';
	var formData = {mobile_no:value}

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
				ret = data.count;
			}
		});
    if(ret>0){ return false;}else{ return true;}
}, 32)
.addMessage('en', 'mobile', 'This Mobile number already used for other customer');

window.ParsleyValidator.addValidator('emailcheck', 
function (value, requirement) {
    var ret = '';
    var my_url = APP_URL+'/ajax_check_customer_email_duplicate';
	var formData = {email:value}

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
				ret = data.count;
			}
		});
    if(ret>0){ return false;}else{ return true;}
}, 32).addMessage('en', 'emailcheck', 'This Email already used for other customer');


$(document).ready(function(){
    $("form").submit(function(){
        $("#loading").css("display", "block");
    });
});
</script>
@append
