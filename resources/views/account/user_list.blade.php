@extends('layouts.master')

@section('css')
<link href="{{asset('public/main_theme/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/main_theme/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />

<link href="{{asset('public/main_theme/css/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />

@append

@section('content')
<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
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
                            <li class="breadcrumb-item active">Users</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->
            <!--<div class="row">
                <div class="col-md-12 text-right mb-2">
                    <div class="card-box">
                        <div class="d-flex justify-content-end">
                            <div><a href="{{url('user/create')}}" class="btn btn-primary waves-light waves-effect w-md">
                        <i class="mdi mdi-plus"></i> Add User</a></div>
                        </div>
                    </div>
                </div>
            </div>-->
            <div class="row">
                <div class="col-12">
                    <div class="card-box table-responsive">
                        <table id="datatable" class="table table-bordered table-hover table-sm" style="width: 100%;">
                           <thead>
                                <tr>
                                    <th>SN.</th>
                                    <th style="min-width:80px">Role</th>
                                    <th style="min-width:80px">Name</th>
                                  	<th style="min-width:80px">Email Address</th>
                                    <th style="min-width:40px">Contact</th>
                                    <th style="min-width:40px">Balance</th>
                                    <th style="min-width:10px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                               @foreach($user as $item)
                                <tr>
                                	
                                     <td></td>
                                     <td>{{  $item->roles()->pluck('name')->implode(' ') }}</td>
                                    
                                     <td>{{ $item->name }}</td>
                                     <td>{{ $item->email }}</td>
                                     <td>{{ $item->contact }}</td>
                                     <td><?php $balance =  DB::table('accounts')->select(DB::raw("SUM(IF( `type` = 'Income', amount, 0)) - SUM(IF( `type` = 'Expense', amount, 0)) AS total_expense"))->where('user_account_id',$item->id)->get();if($balance[0]->total_expense>0){$new_balance = $balance[0]->total_expense;}else{$new_balance = '0.00';};?>RM {{number_format($new_balance, 2, '.', ',')}}</td>
                                    <td>
                                    <a href="{{url('user_account')}}/{{encrypt($item->id)}}"  title="View Transactions" data-toggle="tooltip"><img src="{{ asset('public/icons/eye.png')}}" alt=""></a>
                                    </td>
                                </tr>
                                  @endforeach
                            </tbody>
                        </table>
                        
                        
                        
                        
                        
                            
                    </div>
                </div>
                </div> <!-- container -->
                </div> <!-- content -->
                <footer class="footer text-right">
                    2018 © UKSHOP .
                </footer>
            </div>
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->
@endsection


@section('scripts')
<script src="{{asset('public/main_theme/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('public/main_theme/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script> 
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.colVis.min.js"></script> 
<script src="{{asset('public/main_theme/js/sweetalert2.min.js')}}"></script>
<script type="text/javascript">
         
            var table = $('#datatable').DataTable( {
            // scrollY:        "400px",
            scrollX:        true,
            scrollCollapse: true,
            paging:         true,
            "columnDefs": [{
            "targets":0,
            "width": "4%"
            },
            {
            "targets":1,
            "width": "13%"
            },
            {
            "targets":2,
            "width": "18%"
            },
            {
            "targets":3,
            "width": "18%"
            },{
            "targets":4,
            "width": "13%"
            },{
            "targets":5,
            "width": "13%"
            },{
            "targets":6,
            "width": "4%"
            }],
            fixedColumns: true
            } );
           table.on( 'order.dt search.dt', function () {
	table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
		cell.innerHTML = i+1;
	} );
} ).draw();
			

            </script>
@append