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
                        <h4 class="page-title float-left">Report</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="#">Report</a></li>
                            <li class="breadcrumb-item"><a href="#">Sales Report</a></li>
                            <li class="breadcrumb-item"><a href="#">Sales Details</a></li>
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

					
                        <table style='width:100%' id="datatable1" class="table table-striped table-bordered table-hover table-sm">
                            <thead>
                                <tr>
								    <th>SN</th>
                                    <th style='width:22.5%'>Date</th>
                                    <th style='width:22.5%'>Created By</th>
									<th style='width:22.5%'>Order Code</th>
                                    <th style='width:22.5%'>Product</th>
                                    <th style='width:22.5%'>Customer</th>
                                    <th style='width:22.5%'>Qty</th>
                                    <th style='width:10%'>Unit Price</th>
									<th style='width:10%'>Unit Com.</th>
                                    
									<th style='width:10%'>Total Com.</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
						 <?php $n=1;
						    $balance_total=0;
						  ?>
							@foreach($user_commission as $commission)
                            <?php  
									if($commission->commission_type!='send'){
										?>
                                        <tr style="color:red;">
                                        <?php
									}
									else{
										?>
                                        <tr>
                                        <?php
									}
                                   ?>
                                
                                    <td></td>
                                    <td>{{date('d-m-Y', strtotime($commission->created_at))}}</td>
                                   
                                    <td>{{$commission->username}}</td>
                                    <td><a href="{{ url('order')}}/ {{encrypt($commission->order_id)}}/edit">{{$commission->order_code}}</a></td>
                                     <td>{{$commission->product_name}}</td>
                                    <td>{{$commission->customer_full_name}}</td>
                                    <td>{{$commission->quantity}}</td>
                                     
                                   
                                    <td>{{$commission->unit_price}}</td>
                                    
                                        <td>{{$commission->commission_rate}}</td>
                                       
                                    <?php  
									if($commission->commission_type!='send'){
										$balance_total-=$commission->total_commission;
										?>
                                        <td style="color:red;" class="sum">-{{$commission->total_commission}}</td>
                                        <?php
									}
									else{
										$balance_total+=$commission->total_commission;
									   ?>
                                        <td class="sum">{{$commission->total_commission}}</td>
                                        <?php	
									}
									
									
									?>
                                    
                                    
                                </tr>
                                <?php $n++ ;?>
							@endforeach
                            </tbody>
						</table>
					<i style="float:right;margin-top:1%;" class="btn btn-primary waves-light waves-effect ml-2 w-md" id="total_sum">Balance: <?= number_format($balance_total,2)?></i>
                    </div>
                </div>
             </div> 
         </div>
   </div>
<footer class="footer text-right">2018 © UKSHOP .</footer>
</div>

@endsection


@section('scripts')

<script src="{{asset('public/main_theme/js/sweetalert2.min.js')}}"></script>

<script src="http://momentjs.com/downloads/moment.js"></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">


<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
<script src="{{asset('public/main_theme/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('public/main_theme/js/dataTables.bootstrap4.min.js')}}"></script>
<script type="text/javascript">


table = $('#datatable1').DataTable({
	// scrollY:        "400px",
	 "footerCallback": function(row, data, start, end, display) {
                    
                },
	"fnDrawCallback": function( oSettings ) {
      var sum = 0;
                    $('tr').each(function() {
						$(this).children(".sum").each(function() {
							sum = parseInt(sum) + parseInt($(this).html());
						});
					});
					$('#total_sum').text('Balance:'+sum.toFixed(2));
    },
	
	paging:         false,
	
			 "columnDefs": [{
		  "targets": 0,
		  "width": "3.5%"
		 },
		 {
		  "targets": 1,
		  "width": "7.3%"
		 },
		 {
		  "targets": 2,
		  "width": "10.5%"
		 },
			{
		  "targets": 3,
		  "width": "7.5%"
		 },
			{
		  "targets": 4,
		  "width": "10.5%"
		 },
			{
		  "targets": 5,
		  "width": "10.5%"
		 },
			{
		  "targets": 6,
		  "width": "5%"
		 },
			{
		  "targets": 7,
		  "width": "8%"
		 },
		 {
		  "targets": 8,
		  "width": "7%"
		 },
		 {
		  "targets": 9,
		  "width": "7%"
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
