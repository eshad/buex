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
    <div class="content">
        <div class="container-fluid">
		@if($errors->any())
			<div class="alert alert-danger alert-dismissible">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Failed!</strong> {{$errors->first()}}
			</div>
		@endif
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title float-left">Edit Attribute</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="javascript:void();">Products</a></li>
                            <li class="breadcrumb-item active">Edit Product Attribute</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card-box">

						{!! Form::open(['url' => 'update_attribute' ,'enctype'=>'multipart/form-data','Method'=>'POST','class'=>'form-horizontal','id'=>'form_submit','name'=>'form_submit']) !!}	
						{{ csrf_field() }}
						<input type="hidden" id="edit_id" name="edit_id" value="<?= $product_attr[0]->id?>"/>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Attribute Name:*</label>
                                <div class="col-4">
									{{ Form::text('attribute_name',$product_attr[0]->attribute_name,['placeholder' => 'Attribute Name','autocomplete'=>'off','parsley-trigger' => 'change','required'=>'' , 'class' =>'form-control', 'id'=>'attribute_name' ,'data-parsley-required-message'=>'Please Enter Attribute Name','data-parsley-maxlength'=>'30','maxlength'=>'30']) }}
									
                                    @if ($errors->has('attribute_name'))
									<ul class="parsley-errors-list filled">
										<li class="parsley-required">{{ $errors->first('attribute_name') }}</li>
									</ul>
									@endif
                                </div>
                            </div>
							
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Attribute Type:*</label>
                                <div class="col-4">
									 <select onchange="check_attribue_type()" class="selectpicker" required="" data-parsley-required-message="Please Select Attribute Type" data-live-search="true" parsley-trigger="change" id="attribute_type" name="attribute_type"  data-style="btn-light">
                                        <option value=''>Please Select Attribute Type</option>
                                        <option <?php if($product_attr[0]->type == 'Text'){echo 'selected';} ?> value='Text'>Text</option>
										<option <?php if($product_attr[0]->type == 'Number'){echo 'selected';} ?> value='Number'>Number</option>
										<option <?php if($product_attr[0]->type == 'Yes/No'){echo 'selected';} ?> value='Yes/No'>Yes/No</option>
										<option <?php if($product_attr[0]->type == 'Dropdown'){echo 'selected';} ?> value='Dropdown'>Dropdown</option>
                                    </select>
									
									@if ($errors->has('attribute_type'))
									<ul class="parsley-errors-list filled">
										<li class="parsley-required">{{ $errors->first('attribute_type') }}</li>
									</ul>
									 @endif
									 
                                </div>
                            </div>
							
							<div class="form-group row">
                                <label style="visibility: visible" id="label" class="col-2 col-form-label">Attribute Value:*</label>
                                <div class="col-4">
								
								<?php
								if($product_attr[0]->type == 'Text')
								{
									$style_text = "display:block";
									$value_text = $product_attr[0]->value;
								}
								else
								{
									$style_text = "display:none";
									$value_text = '';
								}
								?>
									{{ Form::textarea('attribute_value_text',$value_text,['placeholder' => 'textarea','rows'=>'2','parsley-trigger' => 'change' ,'style'=>$style_text, 'class' =>'form-control','autocomplete' => 'off', 'id'=>'attribute_value_text' ,'data-parsley-required-message'=>'Please Enter Attribute Value','data-parsley-maxlength'=>'250','maxlength'=>'250','onkeyup'=>'disable_erroe()']) }}
								
								<?php
								if($product_attr[0]->type == 'Number')
								{
									$style_number = "display:block";
									$value_number = $product_attr[0]->value;
								}
								else
								{
									$style_number = "display:none";
									$value_number = '';
								}
								?>
									{{ Form::text('attribute_value_number',$value_number,['placeholder' => 'Number', 'class' =>'form-control numeric ','autocomplete' => 'off','style'=>$style_number,'id'=>'attribute_value_number' ,'data-parsley-required-message'=>'Please Enter Number ','data-parsley-maxlength'=>'18','maxlength'=>'18', 'data-parsley-trigger'=>'keyup', 'data-parsley-type'=>'number','onkeyup'=>'disable_erroe()']) }}
									
								<?php
								if($product_attr[0]->type == 'Yes/No')
								{
									$style_yn = "display:block";
									$value_yn = $product_attr[0]->value;
								}
								else
								{
									$style_yn = "display:none";
									$value_yn = '';
								}
								?>
									<select style="<?= $style_yn?>" id='attribute_value_yn' name='attribute_value_yn' class='form-control'>
										<option <?php if($value_yn == 'yes'){echo 'selected';} ?> value='yes'>YES</option>
										<option <?php if($value_yn == 'no'){echo 'selected';} ?> value='no'>NO</option>
									</select>
									
								<?php
								if($product_attr[0]->type == 'Dropdown')
								{
									$style_drowpdown = "display:block";
									$value_drowpdown = $product_attr[0]->value;
								}
								else
								{
									$style_drowpdown = "display:none";
									$value_drowpdown = '';
								}
								?>
								
									{{ Form::text('attribute_value_drowpdown',$value_drowpdown,['placeholder' => 'Drowpdown','autocomplete'=>'off','parsley-trigger' => 'change' ,'style'=>$style_drowpdown,'class' =>'form-control', 'id'=>'attribute_value_drowpdown' ,'data-parsley-required-message'=>'Please Enter Attribute Value','data-parsley-maxlength'=>'250','maxlength'=>'250','onkeyup'=>'disable_erroe()']) }}
								</div>
							 </div>
							 
									
							<div class="form-group row">
							<label style="" class="col-2 col-form-label"></label>
									<div class="col-8">
										<div class="table-responsive">
											<table id="table" style="<?= $style_drowpdown ?> ;border:none;" class="table table-bordered table-hover table-sm ">
												<tbody id="tbody" style="width: 100%;display: inline-table;border: none;">
												<?php $i=1 ; ?>
												@foreach($product_attr as $product_attrs)
													<tr id="dropdown<?=$i?>" >
															<th><?= $product_attrs->list_value ?><input type="hidden" name="att_row_val[]" id="att_row_val<?=$i?>" value="<?= $product_attrs->list_value ?>"/></th>
															
															<th><input <?php if($product_attrs->list_value == $product_attr[0]->value){echo 'checked';} ?> value="<?= $product_attrs->list_value ?>"  type="radio" name="radio">Default</th>
															
															<th><a onclick="delete_table(this)" href="javascript:void(0)" title="View" id="delete_table<?=$i?>"><img src="{{asset('public/cancel.png')}}" alt=""></a></th>
															
													</tr>
												<?php $i++; ?>
												@endforeach
												</tbody>
											</table>
									</div>
								</div>
							</div>
							
							<ul id="attr_val_error" style="display:none" class="parsley-errors-list filled"><li class="parsley-required">Please Enter Attribute Value</li></ul>
							
                            <div class="form-group text-right m-b-0">
                                <button class="btn btn-primary waves-effect waves-light" id='submit' type="submit">
                                Save
                                </button>
								
								<a onclick="location.href='{{url('/attribute')}}';" class="btn btn-light waves-effect m-l-5" type="button">
                                Cancel
                                </a>
                                
                            </div>
							{!! Form::close() !!}
                    </div>
                </div>
            </div> 
		</div> 
	</div>
                <footer class="footer text-right">2018 © UKSHOP.</footer>
</div>


@endsection


@section('scripts')

<script>
$( document ).ready(function() {
    $( "ul" ).removeClass( "nav-second-level" ).addClass( "nav-second-level in" );
	 $(".nav-second-level").attr("aria-expanded", "true");
});

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

$( "#form_submit" ).submit(function( event ) {
	
	var attribute_type = $("#attribute_type").val();
	var attribute_name = $("#attribute_name").val();
	if(attribute_type == '' || attribute_name == '')
	{
		return false;
	}
	if(attribute_type == 'Text')
	{
		var attribute_value_text = $('#attribute_value_text').val();
		if(attribute_value_text == '')
		{
			$("#attr_val_error").css("display", "block");
			return false;
		}
	}
	if(attribute_type == 'Number')
	{
		var attribute_value_number = $('#attribute_value_number').val();
		if(attribute_value_number == '')
		{
			$("#attr_val_error").css("display", "block");
			return false;
		}
	}
	if(attribute_type == 'Yes/No')
	{
		var attribute_value_yn = $('#attribute_value_yn').val();
		if(attribute_value_yn == '')
		{
			$("#attr_val_error").css("display", "block");
			return false;
		}
	}
	if(attribute_type == 'Dropdown')
	{
		var attribute_value_drowpdown = $('#table tr').length;
		if(attribute_value_drowpdown == '')
		{
			$("#attr_val_error").css("display", "block");
			return false;
		}
	}
    $("#loading").css("display", "block");
});	

$("#attribute_value_drowpdown").keypress(function(e) {
    if(e.which == 13) 
	{
		$("#attr_val_error").css("display", "none");
		var rowCount = $('#table tr').length;
		if(rowCount == '')
		{
			var i = 1;
		}else{
			var i = parseInt(rowCount)+parseInt(1);
		}
		$('#table').css('display','block');
        var attribute_value_drowpdown = $("#attribute_value_drowpdown").val();
		if(attribute_value_drowpdown == '')
		{
			return false;
		}
		$("#tbody").append('<tr id="dropdown'+i+'"><th>'+attribute_value_drowpdown+'<input type="hidden" name="att_row_val[]" id="att_row_val'+i+'" value="'+attribute_value_drowpdown+'"/></th><th><input value="'+attribute_value_drowpdown+'" type="radio" name="radio">Default</th><th><a onclick="delete_table(this)" href="javascript:void(0)" title="View" id="delete_table'+i+'"><img src="{{asset('public/cancel.png')}}" alt=""></a></th></tr>');
		$("#attribute_value_drowpdown").val('');
		return false;
    }
});

function delete_table(e)
{
	var row_delete_id = $(e).attr('id');
	var strArray = row_delete_id.match(/(\d+)/g);
	var row_id = "dropdown"+strArray;
	$('#'+row_id).remove();
}

function disable_erroe()
{
	$("#attr_val_error").css("display", "none");
}

function check_attribue_type()
{
	var attribute_type = $("#attribute_type").val();
	$("#attr_val_error").css("display", "none");
	$('#attribute_value_text').css('display','none');
	$('#attribute_value_number').css('display','none');
	$('#attribute_value_yn').css('display','none');
	$('#attribute_value_drowpdown').css('display','none');
	$('#table').css('display','none');

	if(attribute_type == '')
	{
		$('#label').css('visibility','hidden');
		
	}else
	{
		$('#label').css('visibility','visible');
	}
	
	if(attribute_type == 'Text')
	{
		$('#attribute_value_text').css('display','block');
	}
	if(attribute_type == 'Number')
	{
		$('#attribute_value_number').css('display','block');
	}
	if(attribute_type == 'Yes/No')
	{
		$('#attribute_value_yn').css('display','block');
	}
	if(attribute_type == 'Dropdown')
	{
		$('#attribute_value_drowpdown').css('display','block');
		var att_row_val1 = $('#att_row_val1').val();
		if(att_row_val1 == '')
		{
			$('#dropdown1').remove();
		}else{
			$('#table').css('display','block');
		}
		
	}
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

$("#attribute_type").on('change', function() {
        $("#attribute_type").parsley().reset();  
    });	
</script>
@append
