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
.subbtn{ background: transparent;border: 0px;cursor: pointer;background-image: url("../public/delete.png"); width:25px;height:27px;}
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
    <!-- Start content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title float-left">Accounting</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="#">Accounting</a></li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->
            <div class="row">
                <div class="col-md-12 text-right mb-2">
                    <div class="card-box">
                        <div class="d-flex justify-content-end">
                        	<h3 style="left: 20px;position: absolute;">{{$user->name}}</h3>
                            <div><a href="{{ url('/account/create')}}" class="btn btn-primary waves-light waves-effect ml-2 w-md">In & Out</a></div>
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
                                    <th style="min-width:120px">Purpose</th>
                                    <th style="min-width:80px">Amount</th>
									<th style="min-width:80px">Balance</th>
                                    <th style="min-width:70px">Type</th>
                                    <th style="min-width:50px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
							<?php $balance = 0;$i=0; $total_row =count($account);?>
                             @foreach($account as $accounts)
							<tr>
								<td>{{++$i}}.</td>
                                <td>{{date('d-m-Y', strtotime($accounts->date))}}</td>
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
								<td> @if($total_row==$i)
									{{ Form::open(array('url' =>'account/'.$accounts->id ,'id'=>'amount_delete_'.$accounts->id ,'class' => '')) }}
									{{ Form::hidden('_method', 'DELETE') }}
									{{ Form::submit('',array('class' => 'subbtn','id'=>'delete_submit_'.$accounts->id)) }}
									{{ Form::close() }}
                                    @endif
                                </td>
                            </tr>
							@endforeach
                            </tbody>
							
                        </table>
						<i style="float:right;margin-top:1%;" class="btn btn-primary waves-light waves-effect ml-2 w-md">Balance: <?= number_format($balance, 2, '.', ',')?></i>
                        
                    </div>
                </div>
                <!--HistoryNote  Modal-->
                    </div> <!-- container -->
                    </div> <!-- content -->
                    <footer class="footer text-right">
                        2018 © UKSHOP .
                    </footer>
                </div>
                <!-- ============================================================== -->
                <!-- End Right content here -->
                <!-- ============================================================== -->
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
	"order":[0,"desc"],
	"fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            //debugger;
            var index = iDisplayIndexFull + 1;
            $("td:first", nRow).html(index);
            return nRow;
        },
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
 var form_id = 'amount_delete_'+num;
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

