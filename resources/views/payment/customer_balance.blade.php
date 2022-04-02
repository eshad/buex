@extends('layouts.master')

@section('css')

<link href="{{asset('public/main_theme/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />

@append

@section('content')
<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title float-left">Customer Balance</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="#">Payment</a></li>
                            <li class="breadcrumb-item active">Customer Balance</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card-box table-responsive">
                        <table id="customerBalance" class="table table-striped table-bordered table-hover table-sm" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>SN.</th>
                                    <th>No. of Order</th>
                                    <th>Customer Name</th>
                                    <th>Total Item</th>
                                    <th>Balance</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                               <?php $n = 0; ?>
                               
                               @foreach($customer_list as $customer_details)
                                 
                                <tr>
                                    <td>{{ $n+1 }}</td>
                                    <td>{{ $customer_details->order_total }}</td>
                                    <td>{{ $customer_details->customer_full_name }}({{ $customer_details->customer_uniq_id }})</td>
                                    <td>
                                    @if($customer_details->order_quantity!='')
                                       {{ $customer_details->order_quantity }}
                                    @else
                                        {{ 0 }}
                                    @endif
                                    </td>
                                      <?php $value =  App\Http\Controllers\Payment_Controller::get_customer_current_balance($customer_details->customers_id); ?>
                                      <?php 
									    if($value<0){$colore='style="color:red"';}else{$colore='';}
									  ?>
                                      <td  <?php echo $colore; ?>>
                                     {{ number_format($value,2)}}
                                      
                                      </td>
                                   
                                     <td>
                                   
                                     <a href="{{url('/view_customer_balance')}}/{{encrypt($customer_details->customers_id)}}" data-toggle="tooltip" title="View"><img src="{{asset('public/eye.png')}}" alt=""></a>
                                     
                                    </td>
								 </tr>
                                 <?php $n++; ?>  
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

 <script type="text/javascript">
$(document).ready(function() {
	var table = $('#customerBalance').DataTable( {
	scrollX:        true,
	scrollCollapse: true,
	paging:         true,
	"columnDefs": [ {
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
	}],
	fixedColumns: true
	});
});
        </script>
@append

