@extends('layouts.master')

@section('css')

<link href="{{asset('public/main_theme/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
<style>
.subbtn{ background: transparent;border: 0px;cursor: pointer;background-image: url("public/delete.png"); width:27px;height:27px;}
</style>
@append

@section('content')
<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title float-left">Add Payment Source</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="#">Payment</a></li>
                            <li class="breadcrumb-item active">Payment Source</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-right mb-2">
                    <div class="card-box">
                        <div class="d-flex" style="justify-content: right;">
							<div class="col-md-2"><h5><b>Payment Source :</b></h5></div>
                            <div class="col-3">
                                {{ Form::text('payment_source','',['placeholder' => 'Payment Source','autocomplete'=>'off','required'=>'','parsley-trigger' => 'change' , 'class' =>'form-control', 'id'=>'payment_source' ,'data-parsley-required-message'=>'Please Enter Payment Source','data-parsley-maxlength'=>'30','maxlength'=>'30']) }}
                                <label id="error_payment_source" style="color:red;"></label>
                            </div>
                            <div><a href="javascript:void();" onclick="source_submit()" class="btn btn-primary waves-light waves-effect w-md">Add</a></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card-box table-responsive">
                        <table id="datatable" class="table table-striped table-bordered table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Source</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
							<?php $i=0; ?>
							@foreach($product_list as $product_lists)
                                <tr>
                                    <td>{{++$i}}.</td>
                                    <td>{{$product_lists->source_name}}</td>
                                    <td>
                                        <a style="float:left" href="#paymentSource" onclick="edit_source_name({{$product_lists->id}})" data-animation="blur" data-plugin="custommodal"
                                        data-overlaySpeed="10" data-overlayColor="#36404a" title="Edit">
                                            <img src="{{asset('public/edit.png')}}" alt="">
                                        </a>
										
										<form action="{{url('/delete_payment_source')}}/{{ $product_lists->id}}" id="delete_submit_{{$product_lists->id}}" class='delete_sub' method="POST" style="float:left">
											@csrf
											<button title="Delete"  class="subbtn" id="delete_submit_{{$product_lists->id}}"  type="submit"></button>
										</form>  

                                    </td>
                                </tr> 
							@endforeach
                            </tbody>
                        </table>
                        
                    </div>
                </div>
				
                <div id="paymentSource" class="modal-demo">
                    <button id="closedbutton"  type="button" class="close" onclick="Custombox.close();">
                    <span>&times;</span><span class="sr-only">Close</span>
                    </button>
                    <h4 class="custom-modal-title">Edit Payment Source</h4>
                    <div class="custom-modal-text">
                        <form action="" >
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="inputEmail4" class="col-form-label">Source</label>
                                    {{ Form::text('edit_payment_source','',['placeholder' => 'Payment Source','autocomplete'=>'off','required'=>'','parsley-trigger' => 'change' , 'class' =>'form-control', 'id'=>'edit_payment_source' ,'data-parsley-required-message'=>'Please Enter Payment Source','data-parsley-maxlength'=>'30','maxlength'=>'30']) }}
                                     <label style="color:red;" id="error_edit_payment_source"></label>
                                </div>
                              
                            </div>
                           
							{{ Form::hidden('edit_payment_id','',['id'=>'edit_payment_id']) }}
                            <div class="form-group text-right m-b-0">
                                <button onclick="update_source_name()" class="btn btn-primary waves-effect waves-light" type="button">
                                Save Changes
                                </button>
                                <button onclick="Custombox.close();" type="reset" class="btn btn-light waves-effect m-l-5">
                                Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
				
           </div> 
        </div> 
    </div> 
<footer class="footer text-right">2018 © UKSHOP .</footer>
</div>
@endsection


@section('scripts')
<script src="{{asset('public/main_theme/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('public/main_theme/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('public/main_theme/js/sweetalert2.min.js')}}"></script>

<script>
function source_submit()
{
	var payment_source = $('#payment_source').val();
	
	
	
	var last_row_ids = $("#datatable tr:last").find("td:first").last().text();
	if(last_row_ids != '')
	{
		var last_row_id = parseInt(last_row_ids) + parseInt(1);
	}else
	{
		var last_row_id = 1;
	}
	if(payment_source == '')
	{
		$('#error_payment_source').html('Please enter payment source');
		return false;
	}else{
     $('#error_payment_source').html('');}
	var my_url = APP_URL+'/ajax_save_payment_source';
	var formData = {
		payment_source:payment_source,	
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
				$('#payment_source').val('');
				$('#datatable').append('<tr><td>'+last_row_id+'.</td><td>'+payment_source+'</td><td><a href="#paymentSource" onclick="edit_source_name(+data+)" data-animation="blur" data-plugin="custommodal"data-overlaySpeed="10" data-overlayColor="#36404a" title="Edit"><img src="{{asset('public/edit.png')}}" alt=""></a><a href="javascript:void();" onclick="delete_source_name(+data+)" title="Delete"><img src="{{asset('public/delete.png')}}" alt=""></a></td></tr>');
				$.toast({
					heading: "Success" ,
					text: "Payment Source Successfully Added",
					position: 'top-right',
					loaderBg: '#3b98b5',
					icon: 'success',
					hideAfter: 3000,
					stack: 1
				});
			}
			
		});
}

function edit_source_name(e)
{
	var my_url = APP_URL+'/ajax_take_payment_source';
	var formData = {
		source_id:e,	
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
				$('#edit_payment_source').val(data);
				$('#edit_payment_id').val(e);
			}
			
		});
}

function update_source_name()
{
	var source_id = $('#edit_payment_id').val();
	var source_name = $('#edit_payment_source').val();
	if(source_name == '')
	{
		$('#error_edit_payment_source').html('Please enter payment source');
		return false;
	}else{
     $('#error_edit_payment_source').html('');}
	
	var my_url = APP_URL+'/ajax_update_payment_source';
	var formData = {
		source_id:source_id,
		source_name:source_name,		
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
			    $("#closedbutton").click();
				
			  $.toast({
					heading: "Success" ,
					text: "Payment Source Successfully Updated",
					position: 'top-right',
					loaderBg: '#3b98b5',
					icon: 'success',
					hideAfter: 3000,
					stack: 1
			  });
			  window.setTimeout(function(){location.reload()},2000)
			}
		});
}

$('.subbtn').click(function(event) {
 event.preventDefault();
 var submit_button_id = event.target.id;
 var num = submit_button_id.replace(/[^0-9]/g,'');
 var form_id = 'delete_submit_'+num;
swal({
		title: 'Are you sure?',
		text: "You won't be able to revert this!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#4fa7f3',
		cancelButtonColor: '#d57171',
		confirmButtonText: 'Yes, delete it!'
	}).then(function () {
		$('#'+form_id).submit();
		},
	 function (dismiss) {		 
		if (dismiss === 'cancel') {
			swal(
				'Cancelled',
				'Your  file is safe :)',
				'error'
			)
		}
		
	})	
});

</script>

@append

