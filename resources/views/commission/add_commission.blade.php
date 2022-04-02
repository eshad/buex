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
                        <h4 class="page-title float-left">Add Commission</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="javascript:void();">Commission</a></li>
                            <li class="breadcrumb-item active">Add Commission</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card-box">
						{!! Form::open(['url' => 'commission','enctype'=>'multipart/form-data','class'=>'form-horizontal','id'=>'myform','name'=>'myform']) !!}	
						{{ csrf_field() }}
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Low Unit Price(RM):<span class="text-danger">*</span></label>
                                <div class="col-4">
									{{ Form::text('low_unit_price','',['placeholder' => 'Low Unit Price(RM)','autocomplete'=>'off','parsley-trigger' => 'change','required'=>'' , 'class' =>'form-control numeric', 'id'=>'low_unit_price' ,'data-parsley-required-message'=>'Please Enter Attribute Low Unit Price (RM)','data-parsley-maxlength'=>'18','maxlength'=>'18','data-parsley-trigger'=>'keyup', 'data-parsley-type'=>'number','onkeyup'=>'lup()']) }}
									
                                    @if ($errors->has('low_unit_price'))
									<ul class="parsley-errors-list filled">
										<li class="parsley-required">{{ $errors->first('low_unit_price') }}</li>
									</ul>
									@endif
									
                                </div>
                            </div>
							
							 <div class="form-group row">
                                <label class="col-2 col-form-label">High Unit Price(RM):<span class="text-danger">*</span></label>
                                <div class="col-4">
									{{ Form::text('high_unit_price','',['placeholder' => 'High Unit Price(RM)','autocomplete'=>'off','required'=>'' , 'class' =>'form-control numeric', 'id'=>'high_unit_price' ,'data-parsley-required-message'=>'Please Enter High Unit Price(RM)','data-parsley-maxlength'=>'18','maxlength'=>'18','data-parsley-trigger'=>'keyup', 'data-parsley-type'=>'number','data-parsley-checkcost'=>'','data-parsley-validation-threshold'=>'1','onkeyup'=>'hup()']) }}
									
                                    @if ($errors->has('high_unit_price'))
									<ul class="parsley-errors-list filled">
										<li class="parsley-required">{{ $errors->first('high_unit_price') }}</li>
									</ul>
									@endif
									
                                </div>
                            </div>
							
							 <div class="form-group row">
                                <label class="col-2 col-form-label">Unit Commission(RM):<span class="text-danger">*</span></label>
                                <div class="col-4">
									{{ Form::text('unit_commission','',['placeholder' => 'Unit Commission(RM)','autocomplete'=>'off','parsley-trigger' => 'change','required'=>'' , 'class' =>'form-control numeric1', 'id'=>'unit_commission' ,'data-parsley-required-message'=>'Please Enter Unit Commission(RM) ','data-parsley-maxlength'=>'18','maxlength'=>'18','data-parsley-trigger'=>'keyup', 'data-parsley-type'=>'number']) }}
									
                                    @if ($errors->has('unit_commission'))
									<ul class="parsley-errors-list filled">
										<li class="parsley-required">{{ $errors->first('unit_commission') }}</li>
									</ul>
									@endif
                                </div>
                            </div>
							
							<ul class="parsley-errors-list filled" style='display:none' id='errors'>
								<li class="parsley-required">This Range is Already in System</li>
							</ul>
							
                            <div class="form-group text-right m-b-0">
                                <button class="btn btn-primary waves-effect waves-light" id='submit22' type="button">
                                Save
                                </button>
								
								<a onclick="location.href='{{url('/commission')}}';" class="btn btn-light waves-effect m-l-5" type="button">
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

function lup()
{
	$('#errors').css('display','none');
	$('#submit22').prop('disabled', false);
}

function hup()
{
	$('#errors').css('display','none');
	$('#submit22').prop('disabled', false);
}

$( "#submit22" ).click(function() {
	var low_unit_price = $("#low_unit_price").val();
	var high_unit_price = $("#high_unit_price").val();
	var unit_commission = $("#unit_commission").val();
	//alert(low_unit_price);alert(high_unit_price);
	$("#low_unit_price").parsley().validate();
	$("#high_unit_price").parsley().validate();
	$("#unit_commission").parsley().validate();

	if(low_unit_price == '')
	{
		return false;
	}else if(high_unit_price == '')
	{
		return false;
	}else if(unit_commission == '')
	{
		return false;
	}
	
	if(parseFloat(low_unit_price) > parseFloat(high_unit_price))
	{
		$('#errors').css('display','block');
		return false;
	}

	var my_url = APP_URL+'/ajax_low_unit_price';
	var formData = {
		low_unit_price:low_unit_price,
		high_unit_price:high_unit_price,	
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
			success: function (data) {
				console.log(data);
				
				if(data == 1)
				{
					//alert();
					$("#errors").css('display','block');
					$("#submit22").prop('disabled', true);
					return false;
				}
				else
				{
					$("#errors").css('display','none');
					$("#submit22").prop('disabled', false);
					$("#loading").css("display", "block");
					event.stopPropagation();
					$("#myform").submit(); 
				}
			}
		});
	
});


$('.numeric1').keypress(function(event) {  
	  if ((event.which != 46 || $(this).val().indexOf('.') != -1) &&
		((event.which < 48 || event.which > 57) && event.which !=45 && 
		  (event.which != 0 && event.which != 8))) {
		event.preventDefault();
	  }
	  var text = $(this).val();
	  if ((text.indexOf('.') != -1) &&
		(text.substring(text.indexOf('.')).length > 2) &&
		(event.which != 0 && event.which != 8) &&
		($(this)[0].selectionStart >= text.length - 2)) {
		event.preventDefault();
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
	//(charCode != 46 || $(element).val().indexOf('.') != -1) &&      // “.” CHECK DOT, AND ONLY ONE.
	(charCode < 48 || charCode > 57))
	return false;

return true;
}



window.Parsley.addValidator('checkcost', {
  validateNumber: function(value, requirement) {
	  var low_unit_price = $('#low_unit_price').val();
	  if(low_unit_price){
		  if(low_unit_price>value){
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
    en: 'This value should be a greater then Low Unit Price',
  }
});



</script>
@append

