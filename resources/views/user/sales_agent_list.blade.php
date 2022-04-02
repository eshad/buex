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
                        <h4 class="page-title float-left">Settings</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="#">Setting</a></li>
                            <li class="breadcrumb-item active">Users</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->
           
            <div class="row">
                <div class="col-12">
                    <div class="card-box table-responsive">
                        <table id="datatable" class="table table-bordered table-hover table-sm" style="width: 100%;">
                           <thead>
                                <tr>
                                    <th>SN.</th>
                                    <th>Role</th>
                                    <th>Name</th>
                                  	<th>Email Address</th>
                                    <th>Contact</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                               @foreach($user as $item)
                               
                               @if($item->hasrole('Sales-Agent'))
                                <tr>
                                	
                                     <td>{{ $loop->iteration or $item->id }}</td>
                                     <td>{{  $item->roles()->pluck('name')->implode(' ') }}</td>
                                    
                                     <td>{{ $item->name }}</td>
                                     <td>{{ $item->email }}</td>
                                     <td>{{ $item->contact }}</td>
                                    <td><a href="{{url('loginasuser')}}/{{$item->id}}" title="Login User">Login</a>&nbsp;&nbsp;
                                    
                                            <?php /*?>{!! Form::open([
                                                'method'=>'DELETE',
                                                'url' => ['/user', $item->id],
                                                'style' => 'display:inline',
                                                'id'=>'delete_form_'. $item->id
                                            ]) !!}
                                           <a href="javascript:void('0');" onclick="detele_user_row({{$item->id}})" title="Delete User"><img src="{{ asset('public/icons/delete.png')}}" alt=""></a><?php */?>
                                           
                                            {!! Form::close() !!}
                                            
                                             
                                        </td>
                                </tr>
                                @endif
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
            $(document).ready(function() {
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
            "width": "15%"
            },
            {
            "targets":2,
            "width": "25%"
            },
            {
            "targets":3,
            "width": "25%"
            },{
            "targets":4,
            "width": "15%"
            }],
            fixedColumns: true
            } );
            } );
			

            </script>
@append