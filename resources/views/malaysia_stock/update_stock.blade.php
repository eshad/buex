@extends('layouts.master')

@section('css')
.box-width{width: 1000px;margin:10px;}
body{width:600px;font-family:calibri;}
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
                        <h4 class="page-title float-left">Update Item Stock</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="javascript:void();">Products</a></li>
                            <li class="breadcrumb-item"><a href="{{url('/stock')}}">Stock</a></li>
                            <li class="breadcrumb-item active">Update Stock</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12">
                    <div class="card-box">
                       {!! Form::open(['url' => 'stock','id'=>'form_submit','enctype'=>'multipart/form-data','class'=>'form-horizontal']) !!}	
						{{ csrf_field() }}
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Stock Place</label>
                                <div class="col-4">
                                    <select onchange='take_current_stock()' name="stock_place" id="stock_place" class="selectpicker" data-live-search="true"  data-style="btn-light">
                                        <option selected value='1'>UK Stock</option>
										<option value='2'>Malaysia Stock</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Select Item <span class="text-danger">*</span></label>
                                <div class="col-4">
                                   <select required='' data-parsley-required-message='Please Select Item' onchange='take_current_stock()' name="item" id="item" class="selectpicker" data-live-search="true"  data-style="btn-light">
								        <option value=''>Please Select Item</option>
										@foreach($product_list as $product_lists)
                                        <option value='{{$product_lists->id}}'>{{$product_lists->product_name}}(RM) - {{$product_lists->product_price}}</option>
										@endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Current Stock</label>
                                <div class="col-4">
                                    {{ Form::text('current_stock','',['placeholder' => 'Current Stock','readonly','parsley-trigger' => 'change' , 'class' =>'form-control numeric','autocomplete' => 'off', 'id'=>'current_stock' ,'data-parsley-required-message'=>'Please Enter Current Stock','data-parsley-maxlength'=>'18','maxlength'=>'18']) }}
                                </div>
                            </div>
							
							{{ Form::hidden('old_stock','',array('id' => 'old_stock')) }}
							
                            <div class="form-group row">
                                <label class="col-2 col-form-label">You want to Increase or Decrease the Stock?</label>
                                <div class="col-4">
                                    <input type="radio" name="incdec" checked id="increase" value="increase" onchange="stock_inc_dec('increase')"/>Increase
                                    <input type="radio" name="incdec" id="decrease" value="decrease" onchange="stock_inc_dec('decrease')"/>Decrease
                                </div>
                            </div>

                            <div class="form-group row" style="display:none" id="reasons">
                                <label class="col-2 col-form-label pull-left">Reason for Decrease the Stock</label>
                                <div class="col-4 pull-left">
                                    <select class="form-control" name="reason" id="reason" required>
                                            <option value="damage">Damage</option>
                                            <option value="missing/lost">Missing/lost</option>
                                            
                                    </select>
                                </div>
                            </div>
							<div class="clearfix"></div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">New Stock <span class="text-danger">*</span></label>
                                <div class="col-4">
									
									{{ Form::text('new_stock','',['placeholder' => 'New Stock', 'class' =>'form-control numeric ','autocomplete' => 'off', 'id'=>'new_stock' ,'required','data-parsley-required-message'=>'Please Enter New Stock ','data-parsley-maxlength'=>'18','maxlength'=>'18', 'data-parsley-trigger'=>'keyup', 'data-parsley-type'=>'number']) }}
                                </div>
                            </div>
							
							
							<div class="form-row">
                                <div class="form-group col-md-12">

                                    <label disabled for="inputEmail4" class="col-form-label">Image</label>
                                   <ul class="image-lists"> 
									
									<li>
										<img style='max-width:100%;height:110px;border:2px solid #ccc;border-radius:15px;' src="../public/main_theme/images/no_image.jpg" class="img-fluid img-rounded" id="profile-img2-tag" />
										
										<input data-parsley-required-message='Please Select Category Image' style="display:none" class="form-control" accept="image/*" name="image_2" id="profile-img2" data-parsley-max-file-size="2000" data-parsley-fileextension='jpeg,jpg,png' type="file">

									</li>
									<li>
										<img style='max-width:100%;height:110px;border:2px solid #ccc;border-radius:15px;' src="../public/main_theme/images/no_image.jpg" class="img-fluid img-rounded" id="profile-img3-tag" />
										
										<input  data-parsley-required-message='Please Select Category Image' style="display:none" class="form-control" accept="image/*" name="image_3" id="profile-img3" data-parsley-max-file-size="2000" data-parsley-fileextension='jpeg,jpg,png' type="file">

									</li>	
									<li>
										<img style='max-width:100%;height:110px;border:2px solid #ccc;border-radius:15px;' src="../public/main_theme/images/no_image.jpg" class="img-fluid img-rounded" id="profile-img4-tag" />
										
										<input  data-parsley-required-message='Please Select Category Image' style="display:none" class="form-control" accept="image/*" name="image_4" id="profile-img4" data-parsley-max-file-size="2000" data-parsley-fileextension='jpeg,jpg,png' type="file">

									</li>	
									<li>	
										<img style='max-width:100%;height:110px;border:2px solid #ccc;border-radius:15px;' src="../public/main_theme/images/no_image.jpg" class="img-fluid img-rounded" id="profile-img5-tag" />
										
										<input  data-parsley-required-message='Please Select Category Image' style="display:none" class="form-control" accept="image/*" name="image_5" id="profile-img5" data-parsley-max-file-size="2000" data-parsley-fileextension='jpeg,jpg,png' type="file">

									</li>	
									<li>		
										<img style='max-width:100%;height:110px;border:2px solid #ccc;border-radius:15px;' src="../public/main_theme/images/no_image.jpg" class="img-fluid img-rounded" id="profile-img6-tag" />
										
										<input  data-parsley-required-message='Please Select Category Image' style="display:none" class="form-control" accept="image/*" name="image_6" id="profile-img6" data-parsley-max-file-size="2000" data-parsley-fileextension='jpeg,jpg,png' type="file">

									</li>	
									<li>				
										<img style='max-width:100%;height:110px;border:2px solid #ccc;border-radius:15px;' src="../public/main_theme/images/no_image.jpg" class="img-fluid img-rounded" id="profile-img7-tag" />
										
										<input  data-parsley-required-message='Please Select Category Image' style="display:none" class="form-control" accept="image/*" name="image_7" id="profile-img7" data-parsley-max-file-size="2000" data-parsley-fileextension='jpeg,jpg,png' type="file">

									</li>
									</ul>
                                </div>
                            </div>
							
							
                            <div class="form-group text-right m-b-0">
                                <button class="btn btn-primary waves-effect waves-light" type="submit">
                                Save
                                </button>
								<a href="{{url('/stock')}}" class="btn btn-light waves-effect m-l-5">
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
$(document).ready(function(){
    $("form").submit(function(){
		var new_stock = $('#new_stock').val();
		if(new_stock == '')
		{
			return false;
		}
        $("#loading").css("display", "block");
    });
});
$(document).ready(function(){
	
	
		$("#new_stock").on('change', function() {
			if($('#decrease').prop("checked") == true){
				var current_stock=$("#current_stock").val();
				var new_stock = $(this).val();
		
		   if (new_stock > current_stock){
				$("#new_stock").val(current_stock);
			}
			}
		
		});
		
	

});
function take_current_stock()
{
	var item_id = $('#item').val();
	var stock_place = $('#stock_place').val();
	var my_url = APP_URL+'/take_product_current_stock';
	if(item_id == '')
	{
		$('#item_id').val('');
		return false;
	}
	if(stock_place == '')
	{
		return false;
	}
	var formData = {
		item_id:item_id,
		stock_place:stock_place,
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
				if(stock_place == '1')
				{
					$('#current_stock').val(data[0].quantity);
					$('#old_stock').val(data[0].quantity);
				}else
				{
					$('#current_stock').val(data[0].quantity);
					$('#old_stock').val(data[0].quantity);
				}
				
				for(var j=1;j<=7;j++)
				{
					$('#profile-img'+(j+1)+'-tag').attr("src",'../public/main_theme/images/no_image.jpg');
				}
				
				for(var i=1;i<=data.length;i++)
				{
					var image_name = data[i].image_name;
					$('#profile-img'+(i+1)+'-tag').attr("src",'../public/product_image/normal_images/'+image_name);
				}
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

function stock_inc_dec(e)
{
    if(e == 'increase')
    {
        $("#reasons").css("display", "none");
    }else if(e == 'decrease'){
        $("#reasons").css("display", "block");
    }
	$('#new_stock').val('0');
}

$("#item").on('change', function() {
        $("#item").parsley().reset();  
    });
</script>

@append
