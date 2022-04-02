@extends('layouts.master')
@section('css')

.box-width{width: 1000px;margin:10px;}
body{width:600px;font-family:calibri;}   
<style>
 h6>b{
        font-weight: 600;
    }
</style>
  
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
                        <h4 class="page-title float-left">View Product</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="javascript:void();">Products</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/product')}}">Products Detail</a></li>
                            <li class="breadcrumb-item active">View Product</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card-box">
                        <div class="row">
                            <div class="col-6">
                                <div class="pull-left mt-3">
								
                                    <h6 style="padding-bottom:15px;" class="mb-0"><b>Product Code :</b> {{$edit_products[0]->item_uniq_id}}</h6>
									
                                    <h6><b>Product Name : </b> {{$edit_products[0]->product_name}}</h6>

                                    <h6 class="mb-2 mt-4"><b>Price :</h6>
                                    <span><b>Option 1/ Full Payment : RM {{$edit_products[0]->product_price }}</b></span>
                                    <span class="ml-4"><b>Option 2/ Installment : RM {{$edit_products[0]->installment_cost}}</b></span>

                                    <h6 class="mb-2 mt-4"><b>Postal Cost :</h6>
                                    <span><b>SM : RM {{$edit_products[0]->sm_cost}}</b></span>
                                    <span class="ml-4"><b>SS : RM {{$edit_products[0]->ss_cost}}</b></span>
                                    <span class="ml-4"><b>Air Freight : RM {{$edit_products[0]->air_freight_cost}}</b></span>
                                </div>
                                </div><!-- end col -->
                                </div>
                                <!-- end row -->
                        <div class="row">
                            <div class="col-12">
                                <div class="clearfix mt-4">
                                    <p><h6 class="mb-0"><b>Product Detail:</b></h6></p>
                                   {{ Form::textarea('product_note',$edit_products[0]->product_note,['placeholder' => 'Product Note','required'=>'','rows'=>'2','parsley-trigger' => 'change' , 'class' =>'form-control','autocomplete' => 'off', 'id'=>'product_note' ,'readonly','data-parsley-required-message'=>'Please Enter Product Note','style'=>'height:140px;background-color:#fff']) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="clearfix mt-3">
                                    <h6><b>Attribute :</b></h6>
									<div class="form-row" style="display:block">
                                <div class="form-group col-md-10">
                                   
									<table class="table table-bordered">
										<thead>
											<tr>
												<th>Attributes</th>
												<th>Values</th>
											</tr>
										</thead>
										<tbody id="attr_tbody">
										<?php 
										
										foreach($products_list as $products_lists)
										{
											foreach($products_lists as $product_list)
											{
												if(count($product_list)>=6)
												{
													
													$select = '<select disabled class="form-control" name="att_value[]" id="">';
													$select .= '<option value=""></option>';	
													for($n=0;$n<(count($product_list)-5);$n++)
													{
														$checked= '';
														if($product_list[$n]['list_value'] == $product_list['att_value'])
														{
															$checked = 'selected';
														}
														$select .= '<option '.$checked.' value="'.$product_list[$n]['list_value'].'">'.$product_list[$n]['list_value'].'</option>';
													}
													$select .= '</select>';	
										?>

											<tr class="tr_remove">
												<td class="td_remove"><input type="input" readonly value="<?= $product_list['att_name'] ?>" class="form-control" id="" name="att_name[]" /><input type="hidden" value="<?= $product_list['attr_id'] ?>" class="form-control" id="" name="att_id[]" /></td>
												<td class="td_remove"><?= $select ?></td>
											</tr>
											
										<?php  } elseif($product_list['att_type'] == 'Yes/No'){
											$select_drowndown_yn = '<select class="form-control" disabled  name="att_value[]" id="" >';
											$sel_yes = '';
											$sel_no = '';
											if($product_list['att_value'] == 'yes')
											{
												$sel_yes = 'selected';
											}else{
												$sel_no = 'selected';
											}
											$select_drowndown_yn .= '<option '.$sel_yes.' value="yes">YES</option>';
											$select_drowndown_yn .= '<option '.$sel_no.' value="no">NO</option>';
											$select_drowndown_yn .= '</select>';		
										?>

											<tr class="tr_remove">
												<td class="td_remove"><input type="input" readonly value="<?= $product_list['att_name'] ?>" class="form-control" id="" name="att_name[]" /><input type="hidden" value="<?= $product_list['attr_id'] ?>" class="form-control" id="" name="att_id[]" /></td>
												<td class="td_remove"><?= $select_drowndown_yn ?></td>
											</tr>
		
										<?php } else { ?>
											<tr class="tr_remove">
												<td class="td_remove"><input type="input" readonly value="<?= $product_list['att_name'] ?>" class="form-control" id="" name="att_name[]" /><input type="hidden" value="<?= $product_list['attr_id'] ?>" class="form-control" id="" name="att_id[]" /></td>
												<td class="td_remove"><input disabled type="input" class="form-control" value="<?= $product_list['att_value'] ?>" id="" name="att_value[]" /></td>
											</tr>

										<?php } } }?>
										</tbody>
									</table>
                                    
                                </div>
									</div>
									
									
                                </div>
                            </div>
                        </div>
						 <div style="margin-top:10px;" class="form-group text-right m-b-0">
                               
                               <a href="{{url('/product')}}" class="btn btn-light waves-effect m-l-5">
                                Cancel
                                </a>
                            </div>
                    </div>
                </div>
            </div>
            </div> <!-- container -->
            </div> <!-- content -->
            <footer class="footer text-right">
                2018 © UKSHOP .
            </footer>
        </div>
        <!-- ============================================================== -->
        <!-- End Right content here -->
        <!-- ============================================================== -->
        </div>


@endsection


@section('scripts')


<script>

$("#profile-img2").change(function(){		
        readURL(this,'profile-img2');
		$('.add2').css('display','none');
		$('#checkimg2').val('1');
		$('.remove2').css('display','block');
    });	
$('.remove2').click(function(){
	var hidden_url = $('#hidden_asset_url').val();
	$('#profile-img2-tag').attr('src',hidden_url+'/main_theme/images/no_image.jpg');
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


function get_item_id()
{
	var cat_id = $('#item_category').val();
	var hidden_cat_id = $('#hidden_cat_id').val();
	var hidden_cat_code = $('#hidden_cat_code').val();
	if(cat_id == hidden_cat_id)
	{
		$('#item_id').html(hidden_cat_code);
		return false;
	}
	var cat_text = $("#item_category option:selected").text();
	var result = cat_text.match(/\((.*)\)/);
	var my_url = APP_URL+'/check_ajax_cate_id';
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
				var data = data + 1;
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
</script>


@append
