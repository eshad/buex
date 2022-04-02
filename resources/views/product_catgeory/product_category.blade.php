@extends('layouts.master')

@section('css')

    <link href="http://itsabacus.net/ukshop/public/main_theme/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<style>

.subbtn1{ background: transparent;border: 0px;cursor: pointer;background-image: url("http://itsabacus.net/ukshop/public/delete.png"); width:27px;height:27px;}

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
                        <h4 class="page-title float-left">Add Item Category</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="javascript:void();">Products</a></li>
                            <li class="breadcrumb-item active">Product Category</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
			{!! Form::open(['url' => 'category','enctype'=>'multipart/form-data','class'=>'form-horizontal','id'=>'form_submit','name'=>'form_submit']) !!}	
			{{ csrf_field() }}
            <div class="row">
                <div class="col-12">
                    <div class="card-box">
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Item Category Name:*</label>
                                <div class="col-4">
									{{ Form::text('category_name','',['placeholder' => 'Item Category Name','autocomplete'=>'off','parsley-trigger' => 'change','required'=>'' , 'class' =>'form-control', 'id'=>'category_name' ,'data-parsley-required-message'=>'Please Enter Item Category Name','data-parsley-maxlength'=>'30','maxlength'=>'30','onkeyup'=>'check_category_name()']) }}
									
                                    @if ($errors->has('category_name'))
									<ul class="parsley-errors-list filled">
										<li class="parsley-required">{{ $errors->first('category_name') }}</li>
									</ul>
									@endif
									 <ul class="parsley-errors-list filled" style='display:none' id="parsley-id-9">
										 <li class="parsley-required">Please Enter Unique Item Category Name</li>
									</ul>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Item Category Code:*</label>
                                <div class="col-4">
									{{ Form::text('category_code','',['placeholder' => 'Item Category Code','autocomplete'=>'off','required'=>'','parsley-trigger' => 'change' , 'class' =>'form-control', 'id'=>'category_code' ,'data-parsley-required-message'=>'Please Enter Item Category Code','data-parsley-maxlength'=>'30','maxlength'=>'30','onkeyup'=>'check_category_code()']) }}
									
									@if ($errors->has('category_code'))
									<ul class="parsley-errors-list filled">
										<li class="parsley-required">{{ $errors->first('category_code') }}</li>
									</ul>
									 @endif
									 <ul class="parsley-errors-list filled" style='display:none' id="parsley-id-10">
										 <li class="parsley-required">Please Enter Unique Item Category Code</li>
									</ul>
                                </div>
                            </div>
							
							 <div class="form-group row">
                                <a href="#model" data-animation="blur" data-plugin="custommodal"
                                data-overlaySpeed="10" data-overlayColor="#36404a" title="Add Attribute" class="col-4 col-form-label">Add Attribute for Category</a> 
                            </div>
							
                            <div class="form-group text-right m-b-0">
                                <button class="btn btn-primary waves-effect waves-light" id='submit' type="submit">
                                Save
                                </button>
								
                                 <a onclick="location.href='{{url('/category')}}';" class="btn btn-primary waves-effect waves-light">
                                Category Amendment
                                </a>
								
								<a onclick="location.href='{{url('/category/create')}}';" class="btn btn-light waves-effect m-l-5" type="button">
                                Cancel
                                </a>
                            </div>	
                    </div>
                </div>
				
				<div class="card-box">
					<div class="table-responsive">
					  <table class="table table-striped table-bordered table-hover" id="tableAddRow">
						<thead>
						  <tr>
							<th width="17%">Attribute Name</th>
							<th width="17%">Attribute Type</th>
							<th width="17%">Attribute Default Value</th>
							<th width="17%">Action</th>
						  </tr>
						</thead>
						<tbody id="attr_table">
						  
						</tbody>
					  </table>  
				  </div>
			</div>
            </div> 
			{!! Form::close() !!}
		</div> 
	</div>
                <footer class="footer text-right">2018 © UKSHOP.</footer>
</div>

<div id="model" class="modal-demo">
	<button type="button" class="close" onclick="Custombox.close();">
	<span>&times;</span><span class="sr-only">Close</span>
	</button>
	<h4 class="custom-modal-title" id="edit_header_cat_name">Select Attributes</h4>
	<div class="custom-modal-text">
		<div class="card-box table-responsive">
                        <table style='width:100%' id="" class="table table-striped table-bordered table-hover table-sm">
                            <thead>
								<tr>
									<th style='width:4%'></th>
									<th style='width:32%'>Attribute Name</th>
									<th style='width:32%'>Attribute Type</th>
									<th style='width:32%'>Attribute Default Value</th>
								</tr>
							</thead>
                            <tbody>
								@foreach($attributes as $attributes_detail)
								<tr id="{{$attributes_detail->id}}">
								
									<td><input type="checkbox" id="model_checkbox{{$attributes_detail->id}}" onclick="add_attr({{$attributes_detail->id}})" /></td>
								 
									<td id="model_att_name{{$attributes_detail->id}}">{{ $attributes_detail->attribute_name}}</td>
									
									<td id="model_att_type{{$attributes_detail->id}}">{{ $attributes_detail->type }}</td>
									
									<td id="model_att_value{{$attributes_detail->id}}">{{ $attributes_detail->value }}</td>

								</tr>
								@endforeach
						   </tbody>
						</table>
                    </div>
	</div>
</div>
@endsection


@section('scripts')

<script>


function check_category_name()
{
	var cat_name = $('#category_name').val();
	var cat_hidden_name = '';
	var cat_id = 0;
	var my_url = APP_URL+'/ajax_category_name';
	var formData = {
		cat_name:cat_name,
		cat_id:cat_id,
		cat_hidden_name:cat_hidden_name,		
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
				if(data == '0')
				{
					$('#parsley-id-9').css('display','none');
					$('#submit').prop('disabled', false);
				}
				else
				{
					$('#parsley-id-9').css('display','inline');
					$('#submit').prop('disabled', true);
				}
			}
		});
}

function check_category_code()
{
	var category_code = $('#category_code').val();
	var cat_id = 0;
	var cat_hidden_code = '';
	var my_url = APP_URL+'/ajax_category_code';
	var formData = {
		category_code:category_code,	
		cat_id:cat_id,
		cat_hidden_code:cat_hidden_code,
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
				if(data == '0')
				{
					$('#parsley-id-10').css('display','none');
					$('#submit').prop('disabled', false);
				}
				else
				{
					$('#parsley-id-10').css('display','inline');
					$('#submit').prop('disabled', true);
				}
			}
		});
}

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
	var category_name = $("#category_name").val();
	var category_code = $("#category_code").val();
	if(category_name == '')
	{
		return false;
	}
	if(category_code == '')
	{
		return false;
	}
    $("#loading").css("display", "block");
});	

function add_attr(e)
{
	var checkbox_id = 'model_checkbox'+e;
	var attr_name_id = 'model_att_name'+e;
	var attr_type_id = 'model_att_type'+e;
	var attr_value_id = 'model_att_value'+e;
	if ($('#'+checkbox_id).prop('checked')==true)
	{ 
		var i = $('#tableAddRow tr').length;
		var attr_name = $('#'+attr_name_id).text();
		var attr_type = $('#'+attr_type_id).text();
		var attr_value = $('#'+attr_value_id).text();
        $('#attr_table').append('<tr id="att_row'+e+'"><td>'+attr_name+'</td><input type="hidden" id="attr_id'+i+'" name="attr_id[]" value="'+e+'" /><td>'+attr_type+'</td><td>'+attr_value+'</td><td><button title="Delete" onclick="delete_atte_row('+e+')" class="subbtn1" id="delete_submit"  type="submit"></button></td></tr>');
    }else if($('#'+checkbox_id).prop('checked')==false)
	{
		 $('#att_row'+e).remove();
	}
}

function delete_atte_row(e)
{
	$('#att_row'+e).remove();
	$('#model_checkbox'+e).prop('checked', false);
}
</script>
@append
