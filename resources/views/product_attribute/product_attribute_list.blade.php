@extends('layouts.master')

@section('css')
<link href="{{asset('public/main_theme/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
<style>
.subbtn{ background: transparent;border: 0px;cursor: pointer;background-image: url("public/delete.png"); width:27px;height:27px;}
.subbtn1{ background: transparent;border: 0px;cursor: pointer;background-image: url("public/delete.png"); width:27px;height:27px;}

</style>
@append

@section('content')

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
                        <h4 class="page-title float-left">Attribute List</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="#">Products</a></li>
                            <li class="breadcrumb-item active">Product Attribute</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-right mb-2">
                    <div class="card-box">
                        <a href="{{url('attribute/create')}}" class="btn btn-primary waves-light waves-effect w-md">
                        <i class="mdi mdi-plus"></i> Add Attribute</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card-box table-responsive">
                        <table style='width:100%' id="datatable" class="table table-striped table-bordered table-hover table-sm">
                            <thead>
                                <tr>
                                    <th style='width:30%'>Attribute Name</th>
                                    <th style='width:30%'>Attribute Type</th>
                                    <th style='width:10%'>Default Value</th>
									 <th style='width:10%'>Action</th>
                                </tr>
                            </thead>
                            <tbody>
								@foreach($attributes as $attributes_detail)
                                <tr id="{{ $attributes_detail['id']}}">
								
                                    <td>{{ $attributes_detail['attribute_name']}}</td>
									
                                    <td>{{ $attributes_detail['type'] }}</td>
									
									<td>{{ $attributes_detail['value'] }}</td>

                                    <td>
                                        <a href="{{ URL::to('attribute/' .$attributes_detail['id']. '/edit') }}" title="Edit" style="float:left;background-image:url(public/edit.png);padding-left:11px"><i style="width:16px;float:left;height: 27px;"></i></a>
										
										
										@if($attributes_detail['att_delete_status'] == 0)
										<form action="{{url('/attribute')}}/{{ $attributes_detail['id']}}" id="cat_delete_{{$attributes_detail['id']}}" class='delete_sub' method="post" style="float:left">
											@csrf
											<input name="_method" type="hidden" value="DELETE">
											<button title="Delete"  class="subbtn" id="delete_submit_{{$attributes_detail['id']}}"  type="submit"></button>
										</form>   
										@endif
										@if($attributes_detail['att_delete_status'] == 1)
											<button title="Delete"  class="subbtn1" id="delete_submit_{{$attributes_detail['id']}}"  type="submit"></button>
										@endif
										
										 
										
											
                                    </td>
                                </tr>
								@endforeach
                            </tbody>
						</table>
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
<script type="text/javascript">
$('#datatable').dataTable({
  "order": [[ 3, "desc" ]]
});

$('.subbtn').click(function(event) {
 event.preventDefault();
 var submit_button_id = event.target.id;
 var num = submit_button_id.replace(/[^0-9]/g,'');
 var form_id = 'cat_delete_'+num;
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

$('.subbtn1').click(function(event) {
 event.preventDefault();
 var submit_button_id = event.target.id;
 var num = submit_button_id.replace(/[^0-9]/g,'');
 var form_id = 'cat_delete_'+num;
swal({
		title: 'This Attribute used in Product/Category?',
		text: "You won't be able to delete this",
		type: 'warning',
		showCancelButton: true,
		cancelButtonColor: '#d57171',
	}).then(function () {
		
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

</script>
@append
