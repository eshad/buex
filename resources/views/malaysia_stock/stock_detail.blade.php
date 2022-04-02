@extends('layouts.master')

@section('css')
<link href="{{asset('public/main_theme/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="{{asset('public/main_theme/js/jquery-1.10.2.min.js')}}"></script>
<link href="{{asset('public/main_theme/css/jquery.fancybox.css')}}" rel='stylesheet' type='text/css'>
<script type="text/javascript" src="{{asset('public/main_theme/js/jquery.fancybox.pack.js')}}"></script>

<style>
.wrap {
    max-width: 120px;
    overflow: hidden;
    white-space: normal;
    text-overflow: ellipsis;
}
.fancybox-close, .fancybox-prev span, .fancybox-next span {
    background-image: url("public/fancybox_sprite.png");
}
</style>
@append

@section('content')

<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title float-left">Stock</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="#">Products</a></li>
                            <li class="breadcrumb-item"><a href="#">Stocks</a></li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-right mb-2">
                    <div class="card-box">
                        <a href="{{ url('/stock/create')}}" class="btn btn-primary waves-light waves-effect w-md">
                        Update Stock</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card-box table-responsive">
                        <table id="datatable" class="table table-striped table-bordered table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>SN.</th>
                                    <th>Image</th>
                                    <th>Item Code</th>
                                    <th>Item Name</th>
                                    <th>Unsold Qty.</th>
                                    <th>Sold Qty.</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
							<?php $i=0;?>
							@foreach($products as $products_detail)
                                <tr>
                                    <td>{{++$i}}.</td>
									<td class="wrap">
									<?php 
									if($products_detail['image_name1'] == '')
									{
										$image_name1 = "main_theme/images/no_image.jpg";
									}else
									{
										$image_name1 = "product_image/thumbnail_images/".$products_detail['image_name1'];
									}
									if($products_detail['image_name2'] == '')
									{
										$image_name2 = "";
									}else
									{
										$image_name2 = "product_image/thumbnail_images/".$products_detail['image_name2'];
									}
									if($products_detail['image_name3'] == '')
									{
										$image_name3 = "";
									}else
									{
										$image_name3 = "product_image/thumbnail_images/".$products_detail['image_name3'];
									}
									if($products_detail['image_name4'] == '')
									{
										$image_name4 = "";
									}else
									{
										$image_name4 = "product_image/thumbnail_images/".$products_detail['image_name4'];
									}
									if($products_detail['image_name5'] == '')
									{
										$image_name5 = "";
									}else
									{
										$image_name5 = "product_image/thumbnail_images/".$products_detail['image_name5'];
									}
									if($products_detail['image_name6'] == '')
									{
										$image_name6 = "";
									}else
									{
										$image_name6 = "product_image/thumbnail_images/".$products_detail['image_name6'];
									}
									?>

									<div id="slider_light">
										<a class="fancybox{{$i}} " data-fancybox-group="gallery" href="{{ asset('public/')}}/{!!$image_name1!!}" >
											<img src="{{ asset('public/')}}/{!!$image_name1!!}" alt="" width="35">
										</a>
										<a class="fancybox{{$i}}" data-fancybox-group="gallery" href="{{ asset('public/')}}/{!!$image_name2!!}" >
											<img style="display:none" src="{{ asset('public/')}}/{!!$image_name2!!}" alt="" width="35">
										</a>
										<a class="fancybox{{$i}}" data-fancybox-group="gallery" href="{{ asset('public/')}}/{!!$image_name3!!}" >
											<img style="display:none" src="{{ asset('public/')}}/{!!$image_name3!!}" alt="" width="35">
										</a>
										<a class="fancybox{{$i}}" data-fancybox-group="gallery" href="{{ asset('public/')}}/{!!$image_name4!!}" >
											<img style="display:none" src="{{ asset('public/')}}/{!!$image_name4!!}" alt="" width="35">
										</a>
										<a class="fancybox{{$i}}" data-fancybox-group="gallery" href="{{ asset('public/')}}/{!!$image_name5!!}" >
											<img style="display:none" src="{{ asset('public/')}}/{!!$image_name5!!}" alt="" width="35">
										</a>
										<a class="fancybox{{$i}}" data-fancybox-group="gallery" href="{{ asset('public/')}}/{!!$image_name6!!}" >
											<img style="display:none" src="{{ asset('public/')}}/{!!$image_name6!!}" alt="" width="35">
										</a>
										<script>
											$(".fancybox{{$i}}").fancybox({
											helpers: {
												title : {
													type : 'outside'
												},
												overlay : {
													speedOut : 0
												}
												
											}
											});
										</script>
									</div>

									</td>
									
                                    <td class="wrap">{{$products_detail['item_uniq_id']}}</td>
                                    <td class="wrap">{{$products_detail['product_name']}}</td>
                                    <td class="wrap">{{number_format($products_detail['malaysia_stock'])}}</td>
                         <?php 
						  $malaysia_total=$products_detail['malaysia_stock']+$products_detail['malaysia_sold_stock'];
						  /*$arrive_quantity=DB::table('shipment_order_item')->select(DB::raw("if(sum(`ship_quantity`) IS NULL ,'0',sum(`ship_quantity`)) as `ship_quantity`"))
					->where('is_arrived', '1')
					->where('order_items_id', $request->source_id)->get();*/
						  ?>
                                    <td>{{$products_detail['malaysia_sold_stock']}}</td>
                                    <td class="wrap">{{number_format($malaysia_total)}}</td>
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
<script type="text/javascript">
$(document).ready(function() {
	var table = $('#datatable').DataTable({
	// scrollY:        "400px",
	dom: '&lt;Bfrtip',
	columnDefs: [
			{
				targets: 7,
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
		"width": "10%"
	},
	{
		"targets": 1,
		"width": "17%"
	},
	{
		"targets": 2,
		"width": "20%"
	},
	{
		"targets": 3,
		"width": "10%"
	},
	{
		"targets": 4,
		"width": "14%"
	},
	{
		"targets": 5,
		"width": "19%"
	},
	{
		"targets":6,
		"width": "10%"
	}],
		fixedColumns: true
	});
	});
</script>
@append
