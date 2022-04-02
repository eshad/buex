@extends('layouts.master')
@section('css')
<link href="{{asset('public/main_theme/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
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
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title float-left">View Shipment</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item">Shipment</li>
                            <li class="breadcrumb-item active">View Shipment</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-right mb-2">
                    <div class="card-box">
                        <div class="d-flex" style="justify-content: right;">
                           <!--<div class="ml-2"><a href="{{url('/shipment_view_pdf')}}/{{$shipment_id}}/sendemail" class="btn btn-light waves-light waves-effect w-md">Send Email</a></div>-->
                            <div class="ml-2"><a href="{{url('/shipment_view_pdf')}}/{{$shipment_id}}/export" class="btn btn-light waves-light waves-effect w-md">Export</a></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card-box">
                       {!! Form::open(['url' => 'shipment_arrive','id'=>'form_submit','enctype'=>'multipart/form-data','class'=>'form-horizontal']) !!}	
						
                           <input type="hidden" name="shipment_id" value="{{$shipment_id}}" />
                            <div class="table-responsive">
                                <table id="shipment" class="table table-striped table-bordered table-hover table-sm w-100">
                                    <thead>
                                        <tr>
                                           	<th>Sno</th>
                                            <th>Image</th>
                                            <th>Item Code</th>
                                            <th>Item Name</th>
                                            <th>Ship Qty.</th>
                                            @hasrole('Super-Admin')<th>arrive</th>@endhasrole
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
										
                                            
											<input type="hidden" name="shipment_line_id[]" value="<?= $shipment->id?>" />
                                            <input type="hidden" name="shipment_item_id[]" value="<?= $shipment->item_id?>" />
                                            <input type="hidden" name="shipment_quantity[]" value="<?= $shipment->shipment_quantity?>" />
											
                                            <td></td>
                                            <td><img src="{{asset('public/product_image/thumbnail_images')}}/{{$p_image}}" alt="" width="100"></td>
											
                                            <td>{{$shipment->item_uniq_id}}</td>
											
                                            <td>{{$shipment->product_name}}</td>
											
                                            <td >{{$shipment->shipment_quantity}}</td>
											
                                            @hasrole('Super-Admin')<td id="receive_stock<?=$i?>" ><input   parsley-trigger="change" required="" class="form-control qty1" data-parsley-required-message="Please Enter Receive Quantity" max="{{$shipment->shipment_quantity}}" data-parsley-max-message="Receive Quantity should be less then shipment quantity"  data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" data-parsley-pattern-message="Please Enter Only Number" value="{{$shipment->shipment_quantity}}"  name="receive_quntity[]" type="text" ></td>@endhasrole
											
                                            
										
										</tr>
									@endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="clearfix"></div>
                            <hr>
                            <div class="form-group text-right m-b-0">
                                @hasrole('Super-Admin')
                                <ul class="parsley-errors-list filled" id="atleast_item" style="display:none;"><li class="parsley-required">Please Recieve Atleast 1 Item</li></ul>
                                <button class="btn btn-primary waves-effect waves-light" type="button" id="submit_ship">
                                Arrive
                                </button>@endhasrole
                            </div>
                         {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div> 

<input type="hidden" id="sum" name="sum" value="0" />	
<footer class="footer text-right">2018 © UKSHOP.</footer>
</div>


@endsection

@section('scripts')
<script src="{{asset('public/main_theme/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('public/main_theme/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('public/main_theme/js/sweetalert2.min.js')}}"></script>

<script type="text/javascript">

var table = $('#shipment').DataTable( {
scrollX:        true,
scrollCollapse: true,
paging:         true,
colReorder: true,
"columnDefs": [ {
"targets": 0,
"width": "2.5%"
},
{
"targets": 1,
"width": "20%"
},
{
"targets": 2,
"width": "30%"
},
{
"targets": 3,
"width": "15%"
}
@hasrole('Super-Admin'),
{
"targets": 4,
"width": "15%"
}@endhasrole],
fixedColumns: true
});

table.on( 'order.dt search.dt', function () {
	table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
		cell.innerHTML = i+1;
	} );
} ).draw();


$('#submit_ship').on('click',function(){
	if($('#form_submit').parsley().validate()){
		var sum = 0;
		$(".qty1").each(function(){
			sum += +$(this).val();
		});
		if(sum<1){
			$('#atleast_item').show();
			return false;
		}else{
			$('#atleast_item').hide();
			swal({
				title: 'Are you sure?',
				text: "You won't be able to revert this!",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#4fa7f3',
				cancelButtonColor: '#d57171',
				confirmButtonText: 'Yes, sure!'
			}).then(function () {
				$('#form_submit').submit();
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
		}
	}
	
});
$(document).ready(function(){
    $("form").submit(function(){
        $("#loading").css("display", "block");
    });
});
</script>
@append
