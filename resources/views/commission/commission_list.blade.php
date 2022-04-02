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
                        <h4 class="page-title float-left">Commission List</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="#">Commission</a></li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-right mb-2">
                    <div class="card-box">
                        <a href="{{url('commission/create')}}" class="btn btn-primary waves-light waves-effect w-md">
                        <i class="mdi mdi-plus"></i> Add Commission</a>
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
                                    <th style='width:22.5%'>Low Unit Value(RM)</th>
                                    <th style='width:22.5%'>High Unit Value(RM)</th>
                                    <th style='width:22.5%'>Commission value(RM)</th>
									 <th style='width:10%'>Action</th>
                                </tr>
                            </thead>
                            <tbody>
							<?php $i=0;?>
							@foreach($commission as $commissions)
							
                                <tr>
                                    <td>{{++$i}}.</td>
                                    <td>{{$commissions->low_unit_price}}</td>
									<td>{{$commissions->high_unit_price}}</td>
									<td>{{$commissions->unit_commission}}</td>
                                    <td>
                                       @can('commission') <a href="{{ url('commission')}}/{{$commissions->id}}/edit" title="Edit" style="float:left;background-image:url(public/edit.png);padding-left:11px"><i style="width:16px;float:left;height: 27px;"></i></a>
                                       @endcan
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
<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script> 
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.colVis.min.js"></script> 
<script src="{{asset('public/main_theme/js/sweetalert2.min.js')}}"></script>
<script type="text/javascript">
$(document).ready(function() {
	var table = $('#datatable').DataTable({
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
		"width": "22.5%"
	},
	{
		"targets": 1,
		"width": "22.5%"
	},
	{
		"targets": 2,
		"width": "22.5%"
	},
    {
		"targets": 3,
		"width": "22.5%"
	},
    {
		"targets": 4,
		"width": "10%"
	}],
		fixedColumns: true
	});
	});
</script>

@append
