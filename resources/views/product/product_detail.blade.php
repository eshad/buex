@extends('layouts.master')

@section('css')

<link rel="stylesheet" type="text/css" href="{{asset('public/main_theme/css/page_css/product_detail.css')}}"/>
<link href="{{asset('public/main_theme/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="{{asset('public/main_theme/js/jquery-1.10.2.min.js')}}"></script>
<link href="{{asset('public/main_theme/css/jquery.fancybox.css')}}" rel='stylesheet' type='text/css'>
<script type="text/javascript" src="{{asset('public/main_theme/js/jquery.fancybox.pack.js')}}"></script>

<style>
.subbtn{ background:transparent;border: 0px;cursor: pointer;background-image: url("public/delete.png"); width:25px;height:27px;}
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
                        <h4 class="page-title float-left">Product Detail</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="#">Products</a></li>
                            <li class="breadcrumb-item active">Product Detail</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-right mb-2">
                    <div class="card-box">
                        <a href="{{ url('/product/create')}}" class="btn btn-primary waves-light waves-effect w-md">
                        <i class="mdi mdi-plus"></i> Add Product</a>
                    </div>
                </div>
            </div>
			
<div class="row">
	<div class="col-12">
		<div class="card-box table-responsive">
			<table id="datatable" class="table table-striped table-bordered table-hover table-sm">
				<thead>
                                <tr>
                                    <th style="width: 100px; text-align: center">SL</th>
                                    <th style="width: 200px; text-align: center">Image</th>
                                    <th style="width: 5%; text-align: center">Item Code</th>
                                    <th style="width: 26%; text-align: center">Product Name</th>
                                    <th style="width: 5%; text-align: center">UK Stock</th>
                                   <?php $shipments = DB::table('shipments')->where('status',0)->get()->count();?>
                                    <th style="text-align: center" colspan="{{$shipments}}">In Transit Stock</th>
                                   
                                    <th style="width: 5%; text-align: center">MY Stock</th>
                                    <th style="width: 5%; text-align: center">Total Stock</th>
                                    <th style="width: 7%; text-align: center">Price (RM) - (FP/INS) </th>
                                    <th style="width: 11%; text-align: center">Action</th>
                                </tr>
                                
                            </thead>
                <tbody>
				<tr>
                                <td width="70"></td>
                                <td width="70"></td>
                                <td width="70"></td>
                                <td width="70"></td>
                                <td width="70"></td>
                     <?php $shipments = DB::table('shipments')->where('status',0)->get();
					        if(count($shipments)>0){
					       ?>
                           @foreach($shipments as $shipment)
                             <td  style="width: 5%" width="70">
							<div class="dropdown">
								<a href="javascript:void();" class="dropbtn"><span>
								<?php $shipname = explode('-', $shipment->shipment_number);echo $shipname[0].'-'.$shipname[2]; ?></span></a>
                                <?php $date1=date_create($shipment->shipment_date);
								$date2 = date_create(date('Y-m-d'));
								$diff2=date_diff($date1,$date2);?>
								<div class="dropdown-content">
									<ul class="main-menu text-left">
										<li><h6>Tracking information</h6></li>
										<li><pre>{{$shipment->shipment_number}} </pre></li>
										<li><pre>Remaining {{$diff2->format("%a")}} Days</pre></li> 
										<li><pre>BL : {{$shipment->bl_awb_number}} </pre></li>
										<li><pre>Carrier :{{$shipment->carrier_details}} </pre></li>
									</ul>
								</div>
							</div>
						    </td>
						   @endforeach
                           <?php 
							}
							else{
							  ?>
                              <td width="70">-</td>
                              <?php	
							}
						   ?>
						    
						
                                
                                <td width="70"></td>
                                <td width="70"></td>
                                <td width="70"></td>
                                <td width="70"></td>
                 </tr>	
				
				
				<?php $i=0;?>
				@foreach($products as $products_detail)
					<tr>
						<td class="wrap">{{++$i}}.</td>
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
						<td class="wrap">
                        <?php $product_total_stock = $products_detail['uk_stock']; ?>
                        {{number_format($products_detail['uk_stock'])}}</td>
					 <?php $shipments = DB::table('shipments')->where('status',0)->get();
					     	$shipment_count=0; 
						 if(count($shipments)>0){
						
					   ?>
                     
                        @foreach($shipments as $shipment)
                         <?php 
						 $shipments_details =  DB::select("SELECT * FROM `shipment_line` WHERE `shipment_id` =$shipment->id and `item_id`='".$products_detail['id']."'");
						 if(count($shipments_details)>0){$ship_item_no=$shipments_details[0]->shipment_quantity;}else{$ship_item_no=0;}
						 
						   $r_quantity=DB::select("SELECT if( sum(`shipment_order_item`.`ship_quantity`) IS NULL ,'0', sum(`shipment_order_item`.`ship_quantity`) ) as remain_quantity  FROM `shipment_order_item` JOIN `order_items` ON `order_items`.`id`=`shipment_order_item`.`order_items_id` JOIN `orders` ON `orders`.`id`=`order_items`.`order_id` and `orders`.`is_cancel` ='0'  and `orders`.`is_done` ='0' WHERE `order_items`.`product_id`='".$products_detail['id']."' and `shipment_order_item`.`shipment_id`='".$shipment->id."'");
		 
		  
		 
		  //if($r_quantity[0]->remain_quantity){
		     $ship_item_no=$ship_item_no - $r_quantity[0]->remain_quantity;
						  ?>
                        <td class="wrap">
                        <?php $shipment_count+=$ship_item_no; $product_total_stock=$product_total_stock+$ship_item_no;?>
                        {{$ship_item_no}}
                        </td>
					    @endforeach
                        <?php
						 }
						 else
						 {
						  ?>
                          <td class="wrap">-</td>
                          <?php	  
						 }
						?>
                        
                       
                        <?php
						 
						$product_total_stock=$product_total_stock+$products_detail['malaysia_stock'];?>
                        
						<td class="wrap">{{number_format($products_detail['malaysia_stock'])}}</td>
						<td class="wrap">
                        <?php
						 
						?>   {{ number_format($product_total_stock)}}
                        <?php $shipment_count=0;
						$item_no=0;
						 ?>
                        </td>
						<td align="right">
							<div class="dropdown">
								<a href="javascript:void();" class="dropbtn"><span>(RM) {{$products_detail['product_price']}}/{{$products_detail['installment_cost']}}</span></a>
								<div class="dropdown-content">
									<ul class="main-menu text-left">
										<li><pre><h6>Local Postage Cost</h6></li>
										<li><pre>SM : RM {{$products_detail['sm_cost']}} </pre></li>
										<li><pre>SS : RM {{$products_detail['ss_cost']}} </pre></li>
										<li><pre>Airfreight : {{$products_detail['air_freight_cost']}} </pre></li>
									</ul>
								</div>
							</div>
						</td>
						<td>
						<a href="{{ URL::to('product/' .$products_detail['id']) }}" title="View">
							 <img src="{{asset('public/eye.png')}}" alt="">
							 </a>
							 @hasanyrole('Super-Admin')
							 <a href="{{ URL::to('product/'.$products_detail['id'].'/edit') }}" title="Edit">
							 <img src="{{asset('public/edit.png')}}" alt="">
							 </a>
							  @can('product-delete')
							 {{ Form::open(array('url' => 'product/' . $products_detail['id'],'id'=>'prod_delete_'.$products_detail['id'] ,'class' => 'pull-right')) }}
							{{ Form::hidden('_method', 'DELETE') }}
							{{ Form::submit('',array('class' => 'subbtn','id'=>'delete_submit_'.$products_detail['id'])) }}
							{{ Form::close() }}
                            @endcan
                            @endhasanyrole
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
<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script> 
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.colVis.min.js"></script> 

<script type="text/javascript">

$(document).ready(function() {

	$('#datatable').DataTable({
	// scrollY:        "400px",
	dom: '&lt;Bfrtip',
	
	buttons: [
		{
			extend: 'colvis',
			columns: ':not(.noVis)'
		}
	],

	});
});


$('.subbtn').click(function(event) {
 event.preventDefault();
 var submit_button_id = event.target.id;
 var num = submit_button_id.replace(/[^0-9]/g,'');
 var form_id = 'prod_delete_'+num;
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

