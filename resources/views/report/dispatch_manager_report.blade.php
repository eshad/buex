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
                            <li class="breadcrumb-item"><a href="#">Dispatch Manager Report</a></li>
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
                        <table id="datatable" class="table table-striped table-bordered table-hover table-sm" style="width: 100%;">
                            <thead>
                                <tr>
                                    <!--<th>SN.</th>-->
									<th style="min-width:20px; ">S.No.</th>
                                    <th style="min-width:40px">Date</th>
                                    <th style="min-width:40px">User Name</th>
                                    <th style="min-width:120px">Description</th>
                                    <th style="min-width:80px">Amount</th>
									<th style="min-width:80px">Balance</th>
                                    <th style="min-width:70px">Type</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
							<?php $balance = 0;$i=0; $total_row =count($account);?>
                             @foreach($account as $accounts)
							<tr>
								<td>{{++$i}}.</td>
                                <td>{{date('d-m-Y', strtotime($accounts->date))}}</td>
								<td style="color:green;">{{ucfirst($accounts->name)}}</td>
                                <td>{{$accounts->purpose}}</td>
								
								
								<?php 
								if($accounts->type == 'Income')
								{
									$class="text-success";
									//dd($balance.'dsf'.$accounts->amount);
									$balance = $balance + $accounts->amount;
								
								}
								elseif($accounts->type == 'Expense')
								{
									$class="text-danger";
									$balance = $balance - $accounts->amount; 	
								}
								?>
                                <td class="<?=$class ?>">RM 	{{number_format($accounts->amount, 2, '.', ',')}}</td>
								<td class="<?php if($balance>0){echo "text-success";}else{echo "text-danger";};?>">RM {{number_format($balance, 2, '.', ',')}}</td>
								<td class="<?=$class ?>">{{$accounts->type}}</td>
								
                            </tr>
							@endforeach
                            </tbody>
							
                        </table>
						<i style="float:right;margin-top:1%;" class="btn btn-primary waves-light waves-effect ml-2 w-md">Balance: <?= number_format($balance, 2, '.', ',')?></i>
                        
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
		"width": "20%"
	},
    {
		"targets": 3,
		"width": "23%"
	},
    {
		"targets": 4,
		"width": "15%"
	},
    {
		"targets": 5,
		"width": "5%"
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
