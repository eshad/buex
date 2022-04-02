@extends('layouts.master')

@section('css')
<link href="{{asset('public/main_theme/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
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
                        <h4 class="page-title float-left">Category List</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="#">Products</a></li>
                            <li class="breadcrumb-item active">Product Category</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-right mb-2">
                    <div class="card-box">
                        <a href="{{url('category/create')}}" class="btn btn-primary waves-light waves-effect w-md">
                        <i class="mdi mdi-plus"></i> Add Category</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card-box table-responsive">
                        <table style='width:100%' id="datatable" class="table table-bordered table-hover table-sm">
                            <thead>
                                <tr>
                                    <th style='width:30%'>Category Code</th>
                                    <th style='width:30%'>Category Name</th>
                                    <th style='width:10%'>Action</th>
                                </tr>
                            </thead>
                            <tbody>
								@foreach($category as $category_detail)
                                <tr id="{{ $category_detail['id']}}">
								
                                    <td id="cat_code{{$category_detail['id']}}">{{ $category_detail['category_code']}}</td>
									
                                    <td id="cat_name{{$category_detail['id']}}">{{ $category_detail['category_name'] }}</td>

                                    <td>
                                        <a href="{{ URL::to('category/' .$category_detail['id']. '/edit') }}" title="Edit" style="float:left;background-image:url(public/edit.png);padding-left:11px"><i style="width:16px;float:left;height: 27px;"></i></a>
										
										@if($category_detail['product_status'] == 1)
										<form action="{{url('/category')}}/{{ $category_detail['id']}}" id="cat_delete_{{$category_detail['id']}}" class='delete_sub' method="post" style="float:left">
											@csrf
											<input name="_method" type="hidden" value="DELETE">
											<button title="Delete"  class="subbtn" id="delete_submit_{{$category_detail['id']}}"  type="submit"></button>
										</form>  
										@endif
										@if($category_detail['product_status'] == 0)
											<button title="Delete"  class="subbtn1" id="delete_submit_{{$category_detail['id']}}"  type="submit"></button>
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
<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script> 
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.colVis.min.js"></script> 
<script src="{{asset('public/main_theme/js/sweetalert2.min.js')}}"></script>
<script type="text/javascript">
$(document).ready(function() {
	var table = $('#datatable').DataTable({
	// scrollY:        "400px",
	dom: '&lt;Bfrtip',
	columnDefs: [
			{
				targets: 2,
				className: 'noVis'
			}
	],
	buttons: [
		{
			extend: 'colvis',
			columns: ':not(.noVis)'
		}
	],
	
	paging:         true,
	
	"columnDefs": [{
		"targets": 0,
		"width": "30%"
	},
	{
		"targets": 1,
		"width": "30%"
	},
	{
		"targets": 2,
		"width": "10%"
	}],
		fixedColumns: true
	});
	});
</script>

<script>

$(document).on('click','.delete_category',function(){
		 var category_id = $(this).data('val');
	 $.ajax({
				type: 'GET',
				url: APP_URL + '/check_subcategory_of_category/' + category_id,
				
				success: function (data) {
					if(data==1){
						
						delete_category2(category_id);
					}else{
						
						inactive_category(category_id);
					}
				}
			});
            
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
		title: 'This Category used in Product?',
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
@append
