@extends('layouts.master')

@section('css')
<link href="{{asset('public/main_theme/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
<style>
.dltbtn{ background: transparent;border: 0px;cursor: pointer;background-image: url("public/icons/delete.png"); width:27px;height:27px;}
.wrap {
    max-width: 120px;
    overflow: hidden;
    white-space: normal;
    text-overflow: ellipsis;
}
.panel-default {
    border-color: #ddd;
}
.panel {
    margin-bottom: 20px;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.05);
    box-shadow: 0 1px 1px rgba(0,0,0,.05);
}
.panel-heading {
    border-bottom: 1px solid transparent;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
}
.panel-default>.panel-heading {
    color: #333;
    background-color: #DDD;
    border-color: #ddd;
}
.address{
    display: flex;
    flex-wrap: wrap;
    text-align: left;}
</style>
@append

@section('content')
<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title float-left">Customer</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="#">Customer</a></li>
                            <li class="breadcrumb-item active">Customer List</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->
            <div class="row">
                <div class="col-md-12 text-right mb-2">
                    <div class="card-box">
                        <a href="{{ url('/customer/create')}}" class="btn btn-primary waves-light waves-effect w-md">
                        <i class="mdi mdi-plus"></i> Add Customer</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card-box ">
                        <table id="customer" class="table table-striped table-bordered table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Customer ID</th>
                                    <th>Customer Name</th>
                                    <th>Address</th>
                                    <th>City</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            	@foreach($customers as $customer)
                                <tr>
                                <?php $country = json_decode($customer->is_default->country);?>
                                	<td>{{$customer->customer_uniq_id}}</td>
                                    <td >{{ str_limit($customer->customer_full_name, $limit = 25, $end = '...') }}</td><?php $address = $customer->is_default->address_1;
								if($customer->is_default->address_2){
									$address .= ', '.$customer->is_default->address_2;
								}if($customer->is_default->address_3){
								$address .= ', '.$customer->is_default->address_3;
								}?>
                                    <td >{{ str_limit($address, $limit = 25, $end = '...') }}</td>
                                    <td>{{$customer->is_default->city}}</td>
                                    <td>(+{{$country[0]->phonecode}}) {{$customer->is_default->mobile}}</td>
                                    <td >{{ str_limit($customer->is_default->email, $limit = 25, $end = '...') }}</td>
                                    <td>
                                         
                                         <a href="#customerDetail" onclick="viewCustomer({{$customer->id}});" data-toggle="tooltip" >
                                        <img src="{{ asset('public/icons/eye.png')}}" alt="">
                                        </a>
                                        <a href="{{ url('customer')}}/{{encrypt($customer->id)}}/edit" title="Edit" data-toggle="tooltip">
                                        <img src="{{ asset('public/icons/edit.png')}}" alt="">
                                        </a>
                                        <a href="{{url('manage_customer_payment')}}/{{encrypt($customer->id)}}" title="Make Payment" data-toggle="tooltip">
                                        <img src="{{ asset('public/icons/dollar.png')}}" alt="">
                                        </a>
                                        <a href="{{url('order/create')}}/{{encrypt($customer->id)}}" title="Order" data-toggle="tooltip">
                                        <img src="{{ asset('public/icons/cart.png')}}" alt="">
                                        </a>
                                        <a href="{{url('view_customer_balance')}}/{{encrypt($customer->id)}}" title="Balance" data-toggle="tooltip">
                                        <img src="{{ asset('public/icons/info.png')}}" alt="">
                                        </a>
                                        @can('customer-delete')
										<form action="{{url('/customer')}}/{{encrypt($customer->id)}}" id='cust_delete_{{$customer->id}}' class='delete_sub' method="post" style="float:left;">
											@csrf
											<input name="_method" type="hidden" value="DELETE">
											<button  class="dltbtn" id='delete_submit_{{$customer->id}}'  type="submit" data-toggle="tooltip" title="Delete"></button></form>			
                                      	@endcan
                                    </td>
                                </tr>
                                @endforeach
                                
                            </tbody>
                        </table>
                        
                    </div>
                </div>
                <!--Edit Product Modal-->
                <div id="customerDetail" class="modal-demo">
                    <button type="button" class="close" onclick="Custombox.close();">
                    <span>&times;</span><span class="sr-only">Close</span>
                    </button>
                    <h4 class="custom-modal-title">View Customer Shipping Address For: <span id="view_customer_uniq_id"></span></h4>
                    <div class="custom-modal-text row" id="show_multi_address">
                        
                            
                     
                    </div>
                </div>
                </div> <!-- end row -->
                </div> <!-- container -->
                </div> <!-- content -->
                <footer class="footer text-right">
                    2018 © UKSHOP .
                </footer>
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
	var table = $('#customer').DataTable({
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
	
	paging: true,
	"order": [],
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
	//////////////////////////////////////
	
$('.dltbtn').click(function(event) {
	 event.preventDefault();
	 var submit_button_id = event.target.id;
	 var num = submit_button_id.replace(/[^0-9]/g,'');
	 var form_id = 'cust_delete_'+num;
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
function  viewCustomer(customer_id){
	
	var my_url = APP_URL+'/ajax_view_customer';
	var formData = {customer_id:customer_id}

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
				
				var htmldata='';
				var $j=1;
				for($i=0;$i<data.customer_addresses.length;$i++){
					if($i==0){$('#view_customer_uniq_id').text(data.customer_addresses[0].customer_full_name);}
					var address = data.customer_addresses[$i].address_1;
					if(data.customer_addresses[$i].address_2){
						address+= ',<br>'+data.customer_addresses[$i].address_2;
					}
					if(data.customer_addresses[$i].address_3){
						address+=',<br>'+data.customer_addresses[$i].address_3;
					}
					htmldata +='<div class="col-sm-6"><div class="panel panel-default"><div class="panel-heading"><span><h5 class="panel-title" itemprop="name">Customer address '+$j+'</h5></span></div><div class="panel-body" itemprop="reviewBody"><div class="address"><div class="col-md-3"><b>Name:</b></div><div class="col-md-9">'+data.customer_addresses[$i].customer_full_name+'</div><div class="col-md-3"><b>Address:</b></div><div class="col-md-9">'+address+',<br>'+data.customer_addresses[$i].city+',<br>'+data.customer_addresses[$i].postal_code+', <br>'+data.customer_addresses[$i].state+',<br>'+data.customer_addresses[$i].nicename+'</div><div class="col-md-3"><b>Email:</b></div><div class="col-md-9">'+data.customer_addresses[$i].email+'</div><div class="col-md-3"><b>Contact:</b></div><div class="col-md-9">'+data.customer_addresses[$i].mobile+'</div></div><span itemprop="author" itemscope="" itemtype="http://schema.org/Person"><small><span itemprop="name"></span></small></span><small><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></small></div><div class="panel-footer clearfix"></div></div></div>';
					$j++
				}
				$('#show_multi_address').html(htmldata);
				/*$('#view_customer_uniq_id').text(data.customer[0].customer_uniq_id);
				$('#view_customer_name').val(data.customer[0].customer_full_name);
				$('#view_customer_add1').val(data.customer[0]['is_default'].address_1);
				$('#view_customer_add2').val(data.customer[0]['is_default'].address_2);
				$('#view_customer_add3').val(data.customer[0]['is_default'].address_3);
				$('#view_customer_city').val(data.customer[0]['is_default'].city);
				$('#view_customer_email').val(data.customer[0]['is_default'].email);
				$('#view_customer_mobile').val(data.customer[0]['is_default'].mobile);
				$('#view_customer_postal').val(data.customer[0]['is_default'].postal_code);
				$('#view_customer_state').val(data.customer[0]['is_default'].state);
				$('#view_customer_country').val(data.Country.nicename);
				$('#view_customer_prefix').val('+'+data.Country.phonecode);*/
	
			}
		});
  Custombox.open({
                target: '#customerDetail',
                effect: 'fadein',
				width: '800'
            });
            
}
</script>
@append
