@extends('layouts.master')
@section('css')
<link href="{{asset('public/main_theme/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />

@append

@section('content')
@include('html/gallery');


<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title float-left">Pending History</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item">Shipment</li>
                            <li class="breadcrumb-item active">Pending History</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card-box">
                    {!! Form::open(['url' => 'shipment_pending_arrive','id'=>'form_submit','class'=>'form-horizontal']) !!}	
						
                           
                            <div class="table-responsive">
                                <table id="shipment" class="table table-striped table-bordered table-hover table-sm w-100">
                                    <thead>
                                        <tr>
                                            
                                            <th>Image</th>
                                            <th>Item Code</th>
                                            <th>Item Name</th>
                                            <th>Shipment Number</th>
                                            <th>Pending Qty.</th>
                                            @hasrole('Super-Admin')<th>arrive</th><th>Action</th>@endhasrole
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i=1;?>
									@foreach($shipment_data as $shipment)
                                    <?php 
										
										$images = DB::table('images')->where('source_id',$shipment->item_id)->where('source_type','product')->first();
										if($images){
											$p_image = $images->thumb_image_name;
										}else{
											$p_image = 'no_image.jpg';
										}
										?>
                                        <tr id="{{$shipment->id}}">
                                            <input type="hidden" name="shipment_line_id[]" value="{{$shipment->id}}" />
                                            <input type="hidden" name="shipment_id[]" value="{{$shipment->ship_id}}" />
                                            <input type="hidden" name="shipment_item_id[]" value="{{$shipment->item_id}}" />
                                            <input type="hidden" name="shipment_quantity[]" value="{{ $shipment->shipment_quantity}}" />
                                            <td><img src="{{asset('public/product_image/thumbnail_images')}}/{{$p_image}}" alt="" width="100"></td>
                                            <td>{{$shipment->item_uniq_id}}</td>
											<td>{{$shipment->product_name}}</td>
											<td>{{$shipment->shipment_number}}</td>
                                            <td >{{$shipment->pending_quantity}}</td>
                                            @hasrole('Super-Admin')<td><input type="text" name="pending_arrive[]" class="form-control " value="{{$shipment->pending_quantity}}"/></td>
                                            <td><a href="javascript:void(0)"  data-toggle="tooltip" data-original-title="Delete" data-id="{{$shipment->id}}" class="delete"><img src="http://itsabacus.net/ukshop/public/icons/delete.png" alt=""></a></td>
                                            @endhasrole
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <hr>
                            <div class="form-group text-right m-b-0">
                                 @hasrole('Super-Admin')<button class="btn btn-primary waves-effect waves-light" type="submit">
                                Arrive
                                </button>@endhasrole
                                {!! Form::close() !!}
                            </div>
                    </div>
                </div>
           </div>
       </div> 
    </div> 
</div> 
<footer class="footer text-right">2018 © UKSHOP.</footer>

@endsection


@section('scripts')
<script src="{{asset('public/main_theme/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('public/main_theme/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('public/main_theme/js/sweetalert2.min.js')}}"></script>
<script type="text/javascript">
$(document).ready(function() {
var table = $('#shipment').DataTable( {
scrollX:        true,
scrollCollapse: true,
paging:         true,
"columnDefs": [ {
"targets": 0,
"width": "10%"
},
{
"targets": 1,
"width": "15%"
},
{
"targets": 2,
"width": "25%"
},
{
"targets": 3,
"width": "15%"
},
{
"targets": 4,
"width": "15%"
}@hasrole('Super-Admin'),
{
"targets": 5,
"width": "15%"
}@endhasrole],
fixedColumns: true
} );
} );

$('.delete').click(function(event) {
	 
	 var ship_line_id = $(this).data('id');
	swal({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#4fa7f3',
			cancelButtonColor: '#d57171',
			confirmButtonText: 'Yes, delete it!'
		}).then(function () {
			window.location.href ="{{url('delete_pending_stock')}}/"+ship_line_id;
			},
	 function (dismiss) {		 
		if (dismiss === 'cancel') {
			swal(
				'Cancelled',
				'Your  file is safe :)',
				'error'
			)
		}
		return false;
		
	})	
});	
</script>
@append
