@extends('layouts.master')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('public/main_theme/css/page_css/dashboard.css')}}"/> 
@append

@section('content')

<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title float-left">Dashboard</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="#">UKSHOP </a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->
            <div class="row">
                <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                    <div class="card-box tilebox-one">
                        <i class="mdi mdi-account float-right"></i>
                        <h6 class="text-muted text-uppercase mb-3"><a href="{{url('customer')}}">Total Customers</a></h6>
						@foreach($desktop as $desktops)
						<h4 class="mb-3" data-plugin="counterup">{{$desktops->total}}</h4>
						@endforeach
                        <span class="badge badge-primary"> +11% </span> <span class="text-muted ml-2 vertical-middle">From previous period</span>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                    <div class="card-box tilebox-one">
                        <i class="mdi mdi-barcode  float-right"></i>
                        <h6 class="text-muted text-uppercase mb-3"><a href="{{url('product')}}">Item in Stock</a></h6>

						
                        <h4 class="mb-3"><span data-plugin="counterup">{{$abnc}}</span></h4>
					
						
                        <span class="badge badge-primary"> -29% </span> <span class="text-muted ml-2 vertical-middle">From previous period</span>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                    <div class="card-box tilebox-one">
                        <i class="fi-box float-right"></i>
                        <h6 class="text-muted text-uppercase mb-3"><a href="{{url('order')}}">Amount/Orders</a></h6>
				
                        <h4 class="mb-3"><span>@if($abc[0]->ord){{$abc[0]->ord}}@else 0.00 @endif/{{$pqr[0]->total_ord}}</</span></h4>
						
                        <span class="badge badge-primary"> 0% </span> <span class="text-muted ml-2 vertical-middle">For Current Month</span>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                    <div class="card-box tilebox-one">
                        <i class="mdi mdi-truck-delivery float-right"></i>
                        <h6 class="text-muted text-uppercase mb-3"><a href="{{url('report/dispatch_manager_report')}}">Despatch</a></h6>
										@foreach($dis as $diss)
                        <h4 class="mb-3" data-plugin="counterup">{{$diss->record}}</h4>
						@endforeach
                        <span class="badge badge-primary"> +89% </span> <span class="text-muted ml-2 vertical-middle">Last year</span>
                    </div>
                </div>
            </div>
			<div class="row mt-3">
                <div class="col-md-12">
                    <div class="card-box">
                        <h4 class="m-t-0 mb-4">Shipments In Progess</h4>
						@foreach($var as $shipment)
                        <h5 class="text-custom text-uppercase font-15 mb-2">
							<div class="dropdown">
								<a href="{{url('shipment_view')}}/{{encrypt($shipment->id)}}" class="dropbtn"><span>{{$shipment->shipment_number}}</span></a>
								<div class="dropdown-content">
									<ul class="main-menu">
										<li><h6>Tracking information</h6></li>
										<li><pre>@if($shipment->shipment_type=='Sea') BL @else AWB @endif : {{$shipment->bl_awb_number}}</pre></li>
										<li><pre>Carrier Details : {{$shipment->carrier_details}}</pre></li>
									</ul>
								</div>
							</div>
                            <?php 
								$date1=($shipment->created_at);
								
									$dt= date_create(date("Y-m-d",strtotime($date1)));
								
								$date2=date_create($shipment->shipment_date);
								
								$date3 = date_create(date('Y-m-d'));
								
							
								$diff=date_diff($dt,$date2);

								$diff2=date_diff($date2,$date3);
$rem =$diff2->format("%a");
								
							?>
								<strong class="font-13 text-dark">ETA: <?php echo $eta = $diff->format("%a"); if($eta==0){$eta=1;}?>days</strong> 
								<strong class="font-13 text-dark pull-right">Remaining <?php if($rem==-1){echo $rem=1;} else {echo $rem;}?> <?php $foo = number_format((float)( ($eta-$rem)*100)/$eta, 2, '.', ''); ?> days</strong>
                        </h5>
						<div class="progress progress-lg m-b-20">
                            <div class="progress-bar" style="width: <?php echo  $foo; if($foo==200){$foo=100;}?>%" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"><?php echo  round($foo);?>%</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
		</div>
			
     </div> 
            <footer class="footer text-right">2018 © UKSHOP.</footer>
</div>

@endsection


@section('scripts')
<script src="{{asset('public/main_theme/js/chart.bundle.js')}}"></script>
<script src="{{asset('public/main_theme/js/jquery.dashboard.init.js')}}"></script>
@append
