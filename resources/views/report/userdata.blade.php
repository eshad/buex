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
                            <li class="breadcrumb-item"><a href="#">Sales Report</a></li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card-box table-responsive">
					   <table id="datatable" class="table table-striped table-bordered table-hover table-sm" style="width: 100%;">
                           <thead>
                                <tr>
                                    <th>SN.</th>
                                    <th style="min-width:80px">Role</th>
                                    <th style="min-width:80px">Name</th>
                                  	<th style="min-width:80px">Email Address</th>
                                    <th style="min-width:40px">Contact</th>
                                    <th style="min-width:10px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                 <?php $n=1; ?>
                                 @foreach($user as $item)
                               
                                <tr>
                                     <td>{{ $n }}</td>
                                     <td>{{  $item->roles()->pluck('name')->implode(' ') }}</td>
                                    
                                     <td>{{ $item->name }}</td>
                                     <td>{{ $item->email }}</td>
                                     <td>{{ $item->contact }}</td>
                                    <td>
                                    <a href="{{url('report/sales_details')}}/{{encrypt($item->id)}}"  title="User Account"><img src="{{ asset('public/icons/eye.png')}}" alt=""></a>
                                    </td>
                                </tr>
									<?php 
                                    $n++;
                                    ?>
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
		"width": "25%"
	},
	{
		"targets": 2,
		"width": "20%"
	},
    {
		"targets": 3,
		"width": "25%"
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
	
	
</script>

@append
