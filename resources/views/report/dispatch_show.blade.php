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
                        <h4 class="page-title float-left">Report</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="#">Report</a></li>
                            <li class="breadcrumb-item active">Dispatch Report</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-right mb-2">
                    <div class="card-box">
                   	
                    <div class="d-flex justify-content-end">
                    <label class="col-form-label">From</label>
                            <div class="col-2">
	                             <input onchange="mydate1();" id="min-date"  class="form-control date-range-filter dj"  type="text" >
                            </div>
                            <label class="col-form-label">To</label>
                            <div class="col-2">
                                <input id="max-date" class="form-control date-range-filter" type="text">
                            </div>
                            <div>
                           
                            </div>
                        </div>
                   
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card-box table-responsive">

					
                        <table style='width:100%' id="datatable" class="table table-striped table-bordered table-hover table-sm">
                            <thead>
                                <tr>
									<th style='width:22.5%'>SL.No.</th>
                                    <th style='width:22.5%'>Date</th>
									<th style='width:10%'>Dispatch Manager</th>
									<th style='width:22.5%'>Order No.</th>
									<th style='width:22.5%'>Coustmer</th>
                                    <th style='width:22.5%'>Tracking Number/Collect by</th>
                                    <th style='width:22.5%'>Carrier</th>
									<th style='width:10%'>Total Items</th>
									<th style='width:10%'>Action</th>
                                </tr>
                            </thead>
                            <tbody>
						
							@foreach($dispatch as $dispatchs)
							
                                <tr>
                                      <td></td>
                                      <td>{{date('d-m-Y', strtotime($dispatchs->dispatch_date))}}</td>
									    <td>{{$dispatchs->name}}</td>
									  <td><a href="{{ url('order')}}/ {{encrypt($dispatchs->order_id)}}/edit">{{$dispatchs->order_code}}</a></td>
                                      <td>{{$dispatchs->customer_full_name}}</td>
									  <?php
if($dispatchs->consignment_code!='NA'){$consignment_code=$dispatchs->consignment_code;}else{$consignment_code='';}

?>
									  <td><?php
if($dispatchs->consignment_code=='NA')
{
	$collect_by=$dispatchs->collect_by;
	$trackandcollect= $collect_by ;
}
elseif($dispatchs->collect_by!=='')
{
	$consignment_code=$dispatchs->consignment_code;
	$trackandcollect=$consignment_code;
	
}

?>
										  {{$trackandcollect}}</td>
								      <td><a href = "{{$dispatchs->courier_url}}" target="_blank"> {{$dispatchs->courier_name}}</a></td>
									  
 
									  
									  <td>{{$dispatchs->total_item}}</td>
									
										<td><!--<a href="{{ url('order_dispatch')}}/{{($dispatchs->order_id)}}" title="View Order Details"><img src="{{ asset('public/icons/eye.png')}}" alt=""></a>-->
										  
<a href="{{url('getdispatch_download_PDF')}}/{{($dispatchs->order_id)}}/sendemail" data-toggle="tooltip" title="Send Email"><img src="{{ asset('public/icons/email.png')}}" alt=""></a>
<a href="#" title="Send SMS" data-toggle="tooltip"><img src="{{ asset('public/icons/sms.png')}}" alt=""></a></td>
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
<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script> 
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.colVis.min.js"></script> 
<script src="{{asset('public/main_theme/js/sweetalert2.min.js')}}"></script>
<script src="http://momentjs.com/downloads/moment.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
<script src="{{asset('public/main_theme/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('public/main_theme/js/dataTables.bootstrap4.min.js')}}"></script>




<script type="text/javascript">

table = $('#datatable').DataTable({
	// scrollY:        "400px",
	dom: '&lt;Bfrtip',
	columnDefs: [
			{
				targets: 5,
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
		"width": "5%"
	},
	{
		"targets": 1,
		"width": "12%"
	},
	{
		"targets": 2,
		"width": "11%"
	},
    {
		"targets": 3,
		"width": "15%"
	},
    {
		"targets": 4,
		"width": "15%"
	},
    {
		"targets": 5,
		"width": "15%"
	},
    {
		"targets": 6,
		"width": "15%"
	}],
		fixedColumns: true
});

table.on( 'order.dt search.dt', function () {
	table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
		cell.innerHTML = i+1;
	} );
} ).draw();
	
$(function(){ 
    var date = $('.date-range-filter').datepicker({ dateFormat: 'dd-mm-yy' }).val(); 
    $( ".date-range-filter" ).datepicker();
});

$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) {  //alert('ccc');
	    var mindate = $('#min-date').val();
        var maxdate = $('#max-date').val(); 
        var datecol = data[1];
        var datofini=datecol;//.substring(6,10) + datecol.substring(3,5)+ datecol.substring(0,2);
        var datoffin=datecol;//.substring(6,10) + datecol.substring(3,5)+ datecol.substring(0,2);
        if ( mindate === "" && maxdate === "" )
        {
            return true;
        }
        else if ( mindate <= datofini && maxdate === "")
        {
            return true;
        }
        else if ( maxdate >= datoffin && mindate === "")
        {
            return true;
        }
        else if (mindate <= datofini && maxdate >= datoffin)
        {
            return true;
        }
        return false;
    }
 ); 
 
$('.date-range-filter').change(function() {
  table.draw();
});	
		

	
</script>

@append
