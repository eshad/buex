@extends('layouts.master')
@section('css')
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
    <!-- Start content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title float-left">Accounting</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="{{ url('/account')}}">Accounting</a></li>
                            <li class="breadcrumb-item active">Add Account</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->
            <div class="row">
                <div class="col-12">
                    <div class="card-box">
                        {!! Form::open(['url' => 'account','id'=>'form_submit','Method'=>'POST','enctype'=>'multipart/form-data','class'=>'form-horizontal']) !!}	
						{{ csrf_field() }}
						
                        <div class="form-group row">
							<label class="col-2 col-form-label">Purpose <span class="text-danger">*</span></label>
								<div class="col-4">
									{{ Form::text('purpose','',['placeholder' => 'Purpose','rows'=>'2','required'=>'','parsley-trigger' => 'change' , 'class' =>'form-control','autocomplete' => 'off', 'id'=>'purpose','data-parsley-required-message'=>'Please Enter Purpose','data-parsley-maxlength'=>'50','maxlength'=>'50']) }}				
								</div>
						</div>
						@if ($errors->has('purpose'))
						<ul class="parsley-errors-list filled">
							<li class="parsley-required">{{ $errors->first('purpose') }}</li>
						</ul>
						@endif	
						
                        <div  class="form-group row">
							<label class="col-2 col-form-label">Date  <span class="text-danger">*</span></label>
							<div class="col-4">
							{{ Form::text('date',date('d-m-Y'),['placeholder' => 'Date' , 'class' =>'form-control ','required'=>'','autocomplete' => 'off', 'id'=>'datepicker' ,'data-parsley-required-message'=>'Please Enter Date', 'data-parsley-trigger'=>'change']) }}
							</div>
						</div>
						@if ($errors->has('date'))
						<ul class="parsley-errors-list filled">
							<li class="parsley-required">{{ $errors->first('date') }}</li>
						</ul>
						@endif	
						
                        <div class="form-group row">
							<label class="col-2 col-form-label">Type <span class="text-danger">*</span></label>
								<div class="col-4">
									<input type="radio" name="type" id="income_type" checked value="Income" /> Income 
									<input type="radio" name="type" id="expense_type" value="Expense" /> Expense
								</div>
						</div>
						
						@if ($errors->has('type'))
						<ul class="parsley-errors-list filled">
							<li class="parsley-required">{{ $errors->first('type') }}</li>
						</ul>
						@endif
						
                        <div class="form-group row">
							<label class="col-2 col-form-label">Amount(RM)<span class="text-danger">*</span></label>
								<div class="col-4">
									{{ Form::text('amount','',['placeholder' => 'Amount (RM)' , 'class' =>'form-control numeric ','required'=>'','autocomplete' => 'off', 'id'=>'amount' ,'data-parsley-required-message'=>'Please Enter  Amount (RM)','data-parsley-maxlength'=>'18','maxlength'=>'18', 'data-parsley-trigger'=>'change','data-parsley-trigger'=>'keyup' ,'data-parsley-type'=>'number','data-parsley-pattern-message'=>'Please Enter Only Number']) }} 
								</div>		
						</div>
			  
						@if ($errors->has('amount'))
						<ul class="parsley-errors-list filled">
							<li class="parsley-required">{{ $errors->first('amount') }}</li>
						</ul>
						@endif
						
						<div style="margin-top:10px;" class="form-group text-right m-b-0">
							<button class="btn btn-primary waves-effect waves-light" id="submit" type="submit">
							Save
							</button>
							<a href="{{url('/account')}}" class="btn btn-light waves-effect m-l-5">
							Cancel
							</a>
						</div>
                {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div> 
  </div> 
<footer class="footer text-right">2018 © UKSHOP.</footer></div>
</div>


@endsection


@section('scripts')
<script src="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script>

$(function() {
	
		$("#datepicker").datepicker(
		{format: 'dd-mm-yyyy'});
		
		
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

function isNumber(evt, element) 
{
var charCode = (evt.which) ? evt.which : event.keyCode
if (
 //(charCode != 45 || $(element).val().indexOf('-') != -1) &&      // “-” CHECK MINUS, AND ONLY ONE.
 (charCode != 46 || $(element).val().indexOf('.') != -1) &&      // “.” CHECK DOT, AND ONLY ONE.
 (charCode < 48 || charCode > 57))
 return false;

return true;
}



$("#type").on('change', function() {
        $("#type").parsley().reset();  
    });

$( "#form_submit" ).submit(function( event ) {
	
	var purpose = $("#purpose").val();
	var date = $("#date").val();
	var type = $("#type").val();
	var amount = $("#amount").val();
	if(purpose == '')
	{
		return false;
	}else if(date == '')
	{
		return false;
	}else if(type == '')
	{
		return false;
	}else if(amount == '')
	{
		return false;
	}
	$("#loading").css("display", "block");

});	


</script>
@append
