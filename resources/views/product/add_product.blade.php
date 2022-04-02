@extends('layouts.master')
@section('css')



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
    <!-- Start content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title float-left">Create Product</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="javascript:void();">Products</a></li>
                            <li class="breadcrumb-item"><a href="{{url('/product')}}">Products Detail</a></li>
                            <li class="breadcrumb-item active">Create Product</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->
            
            <div class="row">
                <div class="col-12">
                    <div class="card-box">
                       {!! Form::open(['url' => 'product','id'=>'form_submit','enctype'=>'multipart/form-data','class'=>'form-horizontal']) !!}	
						{{ csrf_field() }}
                            <div class="form-row">
                                <div class="col-md-4">
                                    <label for="inputEmail4" class="col-form-label">Item ID : <strong id='item_id'></strong></label>
									<input type='hidden' value='' name='final_item_id' id='final_item_id' />
                                </div>
                            </div>
                             <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="inputEmail4" class="col-form-label">Item Category <span class="text-danger">*</span></label>
                                    <select class="selectpicker" required="" data-parsley-required-message="Please Select Item Category" data-live-search="true" parsley-trigger="change" id="item_category" name="item_category"  data-style="btn-light" onchange="get_item_id()">
                                        <option value=''>Please Select Category</option>
										@foreach ($category_detail as $categorys)
                                        <option value='{{$categorys->id}}'>{{ strtoupper($categorys->category_name)}} - ({{strtoupper($categorys->category_code)}})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
							 @if ($errors->has('item_category'))
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">{{ $errors->first('item_category') }}</li>
                                        </ul>
							@endif
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="inputEmail4" class="col-form-label">Product Name <span class="text-danger">*</span></label>
									{{ Form::text('product_name','',['placeholder' => 'Product Name','required'=>'','parsley-trigger' => 'change' , 'class' =>'form-control','autocomplete' => 'off', 'id'=>'product_name' ,'data-parsley-required-message'=>'Please Enter Product Name','data-parsley-maxlength'=>'200','maxlength'=>'200']) }}
                                </div>
                            </div>
							 @if ($errors->has('product_name'))
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">{{ $errors->first('product_name') }}</li>
                                        </ul>
							@endif
                            <div class="form-row">
                                <div class="form-group col-md-10">
                                    <label for="inputEmail4" class="col-form-label">Product Note<span class="text-danger">*</span></label>
									{{ Form::textarea('product_note','',['placeholder' => 'Product Note','required'=>'','rows'=>'2','parsley-trigger' => 'change' , 'class' =>'form-control','autocomplete' => 'off', 'id'=>'product_note' ,'data-parsley-required-message'=>'Please Enter Product Note']) }}
                                </div>
                            </div>
							@if ($errors->has('product_note'))
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">{{ $errors->first('product_note') }}</li>
                                        </ul>
							@endif
                            <h4 class="m-t-0 ">Stock In</h4>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="inputEmail4" class="col-form-label">Stock Place<span class="text-danger">*</span></label>
									<select class="selectpicker" data-parsley-required-message="Please Select Stock Place" data-live-search="true" parsley-trigger='change' id="stock_place" name="stock_place"  data-style="btn-light">
										<option selected value='1'>UK Stock</option>
										<option value='2'>Malaysia Stock</option>
									</select>
									
                                </div>
                            </div>
							@if ($errors->has('stock_place'))
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">{{ $errors->first('stock_place') }}</li>
                                        </ul>
							@endif
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="inputEmail4" class="col-form-label">Product Price (RM) <span class="text-danger">*</span></label>
									{{ Form::text('product_price','',['placeholder' => 'Full Payment','required'=>'' , 'class' =>'form-control numeric ','autocomplete' => 'off', 'id'=>'product_price' ,'data-parsley-required-message'=>'Please Enter Product Price','data-parsley-maxlength'=>'18','maxlength'=>'18', 'data-parsley-trigger'=>'keyup', 'data-parsley-type'=>'number']) }}
                                </div>
								@if ($errors->has('product_price'))
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">{{ $errors->first('product_price') }}</li>
                                        </ul>
							   @endif
                                <div class="form-group col-md-2">
                                    <label for="inputEmail4" class="col-form-label invissible">Price <span class="text-danger">*</span></label>
									{{ Form::text('price','',['placeholder' => 'Installment','required'=>'','parsley-trigger' => 'change', 'class' =>'form-control numeric','autocomplete' => 'off','data-parsley-trigger'=>'keyup','data-parsley-checkcost'=>'','data-parsley-validation-threshold'=>'1','id'=>'price','data-parsley-required-message'=>'Please Enter Installment','data-parsley-maxlength'=>'18','maxlength'=>'18']) }}
                                </div>
								@if ($errors->has('price'))
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">{{ $errors->first('price') }}</li>
                                        </ul>
							   @endif
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="inputEmail4" class="col-form-label">Local Postage</label> 
                                    {{ Form::text('local_postage_price','',['placeholder' => 'SM(RM)', 'class' =>'form-control numeric ','autocomplete' => 'off', 'id'=>'local_postage_price' ,'data-parsley-required-message'=>'Please Enter SM(RM) ','data-parsley-maxlength'=>'18','maxlength'=>'18', 'data-parsley-trigger'=>'keyup', 'data-parsley-type'=>'number']) }}
                                </div>
								@if ($errors->has('local_postage_price'))
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">{{ $errors->first('local_postage_price') }}</li>
                                        </ul>
							   @endif
                                <div class="form-group col-md-2">
                                    <label for="inputEmail4" class="col-form-label invissible">Local Postage </label>
									
									 {{ Form::text('local_postage','',['placeholder' => 'SS(RM)', 'class' =>'form-control numeric ','autocomplete' => 'off', 'id'=>'local_postage' ,'data-parsley-required-message'=>'Please Enter SS(RM) ','data-parsley-maxlength'=>'18','maxlength'=>'18', 'data-parsley-trigger'=>'keyup', 'data-parsley-type'=>'number']) }}
									 
                                </div>
								@if ($errors->has('local_postage'))
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">{{ $errors->first('local_postage') }}</li>
                                        </ul>
							   @endif
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="inputEmail4" class="col-form-label">AirFreight <span class="text-danger">*</span></label>
									{{ Form::text('airfreight','',['placeholder' => 'AirFreight', 'class' =>'form-control numeric ','autocomplete' => 'off','required'=>'', 'id'=>'airfreight' ,'data-parsley-required-message'=>'Please Enter AirFreight ','data-parsley-maxlength'=>'18','maxlength'=>'18', 'data-parsley-trigger'=>'keyup', 'data-parsley-type'=>'number']) }}
                                    
                                </div>
								@if ($errors->has('airfreight'))
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">{{ $errors->first('airfreight') }}</li>
                                        </ul>
							   @endif
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="inputEmail4" class="col-form-label">Initial Stock<span class="text-danger">*</span></label>

                                    {{ Form::text('initial_stock','',['placeholder' => 'Initial Stock', 'class' =>'form-control numeric1','autocomplete' => 'off','required'=>'', 'id'=>'initial_stock' ,'data-parsley-required-message'=>'Please Enter Initial Stock ','data-parsley-maxlength'=>'18','maxlength'=>'18', 'data-parsley-trigger'=>'change','data-parsley-pattern'=>'^[\d\+\-\.\(\)\/\s]*$', 'data-parsley-pattern-message'=>'Please Enter Only Number']) }}
                                </div>
								@if ($errors->has('initial_stock'))
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">{{ $errors->first('initial_stock') }}</li>
                                        </ul>
							   @endif
                            </div>
							
							<div class="form-row">
                                <div class="form-group col-md-8">
                                    <label for="inputEmail4" class="col-form-label">Specifics</label>
									<table class="table table-bordered">
										<thead>
											<tr>
												<th>Attributes</th>
												<th>Values</th>
											</tr>
										</thead>
										<tbody id="attr_tbody">
											
										</tbody>
									</table>
                                    
                                </div>
								@if ($errors->has(''))
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">{{ $errors->first('') }}</li>
                                        </ul>
							   @endif
                            </div>
							
							
							<div class="form-row">
                                <div class="form-group col-md-12">

                                    <label disabled for="inputEmail4" class="col-form-label">Image</label>
                                   <ul class="image-lists"> 
									
									<li>
										<img style='max-width:100%;height:110px;border:2px solid #ccc;border-radius:15px;' src="../public/main_theme/images/no_image.jpg" class="img-fluid img-rounded" id="profile-img2-tag" />
										
										<label style="display:none;color:blue;" class="lbl remove2" >Remove</label>
										
										<label class="lbl add2" style='color:blue;display:block;' for="profile-img2">Add</label>
										
										<input data-parsley-required-message='Please Select Category Image' style="display:none" class="form-control" accept="image/*" name="image_2" id="profile-img2" data-parsley-max-file-size="2000" data-parsley-fileextension='jpeg,jpg,png' type="file">

										<input type="hidden" id="checkimg2" value="0" />
										
										@if($errors->has('image_2'))
										<ul class="parsley-errors-list filled">
											<li class="parsley-required">{{ $errors->first('image_2') }}</li>
										</ul>
										@endif
									</li>
									<li>
										<img style='max-width:100%;height:110px;border:2px solid #ccc;border-radius:15px;' src="../public/main_theme/images/no_image.jpg" class="img-fluid img-rounded" id="profile-img3-tag" />
										
										<label style="display:none;color:blue;" class="lbl remove3" >Remove</label>
										
										<label class="lbl add3" style='color:blue;display:block;' for="profile-img3">Add</label>
										
										<input  data-parsley-required-message='Please Select Category Image' style="display:none" class="form-control" accept="image/*" name="image_3" id="profile-img3" data-parsley-max-file-size="2000" data-parsley-fileextension='jpeg,jpg,png' type="file">

										<input type="hidden" id="checkimg3" value="0" />
										
										@if($errors->has('image_3'))
										<ul class="parsley-errors-list filled">
											<li class="parsley-required">{{ $errors->first('image_3') }}</li>
										</ul>
										@endif
									</li>	
									<li>
										<img style='max-width:100%;height:110px;border:2px solid #ccc;border-radius:15px;' src="../public/main_theme/images/no_image.jpg" class="img-fluid img-rounded" id="profile-img4-tag" />
										
										<label style="display:none;color:blue;" class="lbl remove4" >Remove</label>
										
										<label class="lbl add4" style='color:blue;display:block;' for="profile-img4">Add</label>
										
										<input  data-parsley-required-message='Please Select Category Image' style="display:none" class="form-control" accept="image/*" name="image_4" id="profile-img4" data-parsley-max-file-size="2000" data-parsley-fileextension='jpeg,jpg,png' type="file">

										<input type="hidden" id="checkimg4" value="0" />
										
										@if($errors->has('image_4'))
										<ul class="parsley-errors-list filled">
											<li class="parsley-required">{{ $errors->first('image_4') }}</li>
										</ul>
										@endif
									</li>	
									<li>	
										<img style='max-width:100%;height:110px;border:2px solid #ccc;border-radius:15px;' src="../public/main_theme/images/no_image.jpg" class="img-fluid img-rounded" id="profile-img5-tag" />
										
										<label style="display:none;color:blue;" class="lbl remove5" >Remove</label>
										
										<label class="lbl add5" style='color:blue;display:block;' for="profile-img5">Add</label>
										
										<input  data-parsley-required-message='Please Select Category Image' style="display:none" class="form-control" accept="image/*" name="image_5" id="profile-img5" data-parsley-max-file-size="2000" data-parsley-fileextension='jpeg,jpg,png' type="file">

										<input type="hidden" id="checkimg5" value="0" />
										
										@if($errors->has('image_5'))
										<ul class="parsley-errors-list filled">
											<li class="parsley-required">{{ $errors->first('image_5') }}</li>
										</ul>
										@endif
									</li>	
									<li>		
										<img style='max-width:100%;height:110px;border:2px solid #ccc;border-radius:15px;' src="../public/main_theme/images/no_image.jpg" class="img-fluid img-rounded" id="profile-img6-tag" />
										
										<label style="display:none;color:blue;" class="lbl remove6" >Remove</label>
										
										<label class="lbl add6" style='color:blue;display:block;' for="profile-img6">Add</label>
										
										<input  data-parsley-required-message='Please Select Category Image' style="display:none" class="form-control" accept="image/*" name="image_6" id="profile-img6" data-parsley-max-file-size="2000" data-parsley-fileextension='jpeg,jpg,png' type="file">

										<input type="hidden" id="checkimg6" value="0" />
										
										@if($errors->has('image_6'))
										<ul class="parsley-errors-list filled">
											<li class="parsley-required">{{ $errors->first('image_6') }}</li>
										</ul>
										@endif
									</li>	
									<li>				
										<img style='max-width:100%;height:110px;border:2px solid #ccc;border-radius:15px;' src="../public/main_theme/images/no_image.jpg" class="img-fluid img-rounded" id="profile-img7-tag" />
										
										<label style="display:none;color:blue;" class="lbl remove7" >Remove</label>
										
										<label class="lbl add7" style='color:blue;display:block;' for="profile-img7">Add</label>
										
										<input  data-parsley-required-message='Please Select Category Image' style="display:none" class="form-control" accept="image/*" name="image_7" id="profile-img7" data-parsley-max-file-size="2000" data-parsley-fileextension='jpeg,jpg,png' type="file">

										<input type="hidden" id="checkimg7" value="0" />
										
										@if($errors->has('image_7'))
										<ul class="parsley-errors-list filled">
											<li class="parsley-required">{{ $errors->first('image_7') }}</li>
										</ul>
										@endif
									</li>
									</ul>
                                </div>
                            </div>
							

                            <div style="margin-top:10px;" class="form-group text-right m-b-0">
                                <button class="btn btn-primary waves-effect waves-light" id="submit" type="submit">
                                Save
                                </button>
								<a href="{{url('/product')}}" class="btn btn-light waves-effect m-l-5">
                                Cancel
                                </a>
                                
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                </div> <!-- end row -->
                </div> <!-- container -->
                </div> <!-- content -->
                <footer class="footer text-right">
                    2018 © UKSHOP.
                </footer>
            </div>


@endsection


@section('scripts')


<script>
$(document).ready(function() {
	$("#submit").on('submit', function(e){
		e.preventDefault();
		var form = $(this);

		form.parsley().validate();

		if (form.parsley().isValid()){
			alert('valid');
		}
	});
});	

function get_item_id()
{
	//$("#attr_tbody").find("tr.body_caption, tr.body_caption_top").remove();
	
	 $(".tr_remove").remove(); 
	var cat_id = $('#item_category').val();
	
	var cat_text = $("#item_category option:selected").text();
	var result = cat_text.match(/\((.*)\)/);
	var my_url = APP_URL+'/check_ajax_cate_id';
	if(cat_id == '')
	{
		$('#item_id').html('');
		return false;
	}
	var formData = {
		cat_id:cat_id,		
		}
	var type = "POST";
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		})	
		$.ajax({
			type: type,
			url: my_url,
			data: formData,
			dataType: 'json',
			success: function (data) {
				var array_length = data.product_att_data.length;
				if(array_length != 0)
				{
					for(var i=0;i<data.product_att_data.dat.length;i++)
					{
						var select_drowndown_data = data.product_att_data.dat[i];
						var attr_id = data.product_att_data.dat[i].attr_id;
						var attr_name = data.product_att_data.dat[i].attr_name;
						var attr_type = data.product_att_data.dat[i].attr_type;
						var attr_value = data.product_att_data.dat[i].attr_value;
						if(attr_type == 'Text' || attr_type == 'Number')
						{
							$('#attr_tbody').append('<tr class="tr_remove"><td class="td_remove"><input type="input" readonly value="'+attr_name+'" class="form-control" id="" name="att_name[]" /><input type="hidden" value="'+attr_id+'" class="form-control" id="" name="att_id[]" /></td><td class="td_remove"><input data-parsley-maxlength="18",maxlength="18", data-parsley-trigger="change",data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$", data-parsley-pattern-message="Please Enter Only Number" type="input" class="form-control numeric1" value="'+attr_value+'" id="" name="att_value[]" /></td></tr>');
						}else if(attr_type == 'Dropdown')
						{
							var select = '<select name="att_value[]" class="form-control">';
							select += '<option value=""></option>';
							for(key in select_drowndown_data)
							{
								if(typeof(select_drowndown_data[key].list_value) != 'undefined')
								{
									var check_select = '';
									if(select_drowndown_data[key].list_value == attr_value)
									{
										var check_select = 'Selected';
									}
									select += '<option '+check_select+' value="'+select_drowndown_data[key].list_value+'">'+select_drowndown_data[key].list_value+'</option<select>'; 
								}
								
							}
							select += '</select>';
							
							$('#attr_tbody').append('<tr class="tr_remove"><td class="td_remove"><input name="att_name[]" readonly type="input" value="'+attr_name+'" class="form-control" id="" /><input type="hidden" value="'+attr_id+'" class="form-control" id="" name="att_id[]" /></td><td class="td_remove">'+select+'</td></tr>');
							
						}else if(attr_type == 'Yes/No')
						{
							var yn_select_yes = '';
							var yn_select_no = '';
							if(attr_value == 'yes')
							{
								var yn_select_yes = 'selected';
							}else
							{
								var yn_select_no = 'selected';
							}
							var yn_drowndown = '<select name="att_value[]" class="form-control"><option '+yn_select_yes+' value="yes">YES</option><option value="no" '+yn_select_no+'>NO</option></select>'
							
							$('#attr_tbody').append('<tr class="tr_remove"><td class="td_remove"><input name="att_name[]" readonly type="input" value="'+attr_name+'" class="form-control" id="att_id[]" name="" /><input type="hidden" value="'+attr_id+'" class="form-control" id="" name="att_id[]" /></td><td class="td_remove">'+yn_drowndown+'</td></tr>');
						}
					}
				}
				
				var data = data.count[0]['count_cats'] + 1;
				var item_id_value = result[1]+-+data;
				$('#item_id').html(item_id_value);
				$('#final_item_id').val(item_id_value);
			}
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
	(charCode != 46 || $(element).val().indexOf('.') != -1) &&      // “.” CHECK DOT, AND ONLY ONE.
	(charCode < 48 || charCode > 57))
	return false;

return true;
}

$('.numeric1').on('keypress',function (event) {
	return isNumber1(event, this)
    if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
        event.preventDefault();
    }
    var input = $(this).val();
    if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 2)) {
        event.preventDefault();
    }
});
function isNumber1(evt, element) {

var charCode = (evt.which) ? evt.which : event.keyCode

if (
	//(charCode != 45 || $(element).val().indexOf('-') != -1) &&      // “-” CHECK MINUS, AND ONLY ONE.
	     // “.” CHECK DOT, AND ONLY ONE.
	(charCode < 48 || charCode > 57))
	return false;

return true;
}

$("#profile-img2").change(function(){		
        readURL(this,'profile-img2');
		$('.add2').css('display','none');
		$('#checkimg2').val('1');
		$('.remove2').css('display','block');
    });	
$('.remove2').click(function(){
	$('#profile-img2-tag').attr('src','../public/main_theme/images/no_image.jpg');
	$('#profile-img2').val('');
	$('#checkimg2').val('0');
	$('.add2').css('display','block');
	$('.remove2').css('display','none');
});


$("#profile-img3").change(function(){		
        readURL(this,'profile-img3');
		$('.add3').css('display','none');
		$('#checkimg3').val('1');
		$('.remove3').css('display','block');
    });
	
$('.remove3').click(function(){
	$('#profile-img3-tag').attr('src','../public/main_theme/images/no_image.jpg');
	$('#profile-img3').val('');
	$('#checkimg3').val('0');
	$('.add3').css('display','block');
	$('.remove3').css('display','none');
});


$("#profile-img4").change(function(){		
        readURL(this,'profile-img4');
		$('.add4').css('display','none');
		$('#checkimg4').val('1');
		$('.remove4').css('display','block');
    });
	
$('.remove4').click(function(){
	$('#profile-img4-tag').attr('src','../public/main_theme/images/no_image.jpg');
	$('#profile-img4').val('');
	$('#checkimg4').val('0');
	$('.add4').css('display','block');
	$('.remove4').css('display','none');
});


$("#profile-img5").change(function(){		
        readURL(this,'profile-img5');
		$('.add5').css('display','none');
		$('#checkimg5').val('1');
		$('.remove5').css('display','block');
    });
	
$('.remove5').click(function(){
	$('#profile-img5-tag').attr('src','../public/main_theme/images/no_image.jpg');
	$('#profile-img5').val('');
	$('#checkimg5').val('0');
	$('.add5').css('display','block');
	$('.remove5').css('display','none');
});


$("#profile-img6").change(function(){		
        readURL(this,'profile-img6');
		$('.add6').css('display','none');
		$('#checkimg6').val('1');
		$('.remove6').css('display','block');
    });
	
$('.remove6').click(function(){
	$('#profile-img6-tag').attr('src','../public/main_theme/images/no_image.jpg');
	$('#profile-img6').val('');
	$('#checkimg6').val('0');
	$('.add6').css('display','block');
	$('.remove6').css('display','none');
});


$("#profile-img7").change(function(){		
        readURL(this,'profile-img7');
		$('.add7').css('display','none');
		$('#checkimg7').val('1');
		$('.remove7').css('display','block');
    });
	
$('.remove7').click(function(){
	$('#profile-img7-tag').attr('src','../public/main_theme/images/no_image.jpg');
	$('#profile-img7').val('');
	$('#checkimg7').val('0');
	$('.add7').css('display','block');
	$('.remove7').css('display','none');
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

window.Parsley.addValidator('maxFileSize', {
  validateString: function(_value, maxSize, parsleyInstance) {
    if (!window.FormData) {
      alert('You are making all developpers in the world cringe. Upgrade your browser!');
      return true;
    }
    var files = parsleyInstance.$element[0].files;
    return files.length != 1  || files[0].size <= maxSize * 1024;
  },
  requirementType: 'integer',
  messages: {
    en: 'This file should not be larger than %s Kb',
  }
});

window.ParsleyValidator.addValidator('fileextension', function (value, requirement) {
	var fileExtension = value.split('.').pop();
	var arr = requirement.split(',');
	var fileExtension = value.split('.').pop();
	if(jQuery.inArray(fileExtension,arr )<0){
		return false;
	}else{ return true;}
	
}, 32)
.addMessage('en', 'fileextension', 'File type must be jpg , png , jpeg');



$(document).ready(function(){
    $("form").submit(function(){
        $("#loading").css("display", "block");
    });
});

window.Parsley.addValidator('checkcost', {
  validateNumber: function(value, requirement) {
	  var product_price = $('#product_price').val();
	  if(product_price){
		  if(product_price>value){
			  return false;
		  }else{
			return true;  
		  }
	  }else{
		return true;  
	  }
    
  },
  requirementType: 'integer',
  messages: {
    en: 'This value should be a greater then Product Price',
  }
});

$("#item_category").on('change', function() {
        $("#item_category").parsley().reset();  
    });	
</script>


@append
