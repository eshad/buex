@extends('layouts.master')
@section('css')
<style>

.bcbtn{width: 100%;
float: left;
padding: 15px 0px;}
.bcbtn a {
color: rgb(255, 255, 255);
padding: 5px;
margin-right: 1px;
border-radius: 8px;
background: rgb(1, 163, 2) none repeat scroll 0% 0%;
}
.dropbtn {
border: none;
background: transparent;
cursor: pointer;
}
.dropdown {
position: relative;
display: inline-block;
}
.dropdown-content {
display: none;
position: absolute;
background-color: #fff;
min-width: 290px;
box-shadow: 0px 3px 7px 0px rgba(0,0,0,0.2);
z-index: 999999;
left: 110px;
top: 1px;
border: 1px solid #d4d4d4;
padding: 10px;
}
.dropdown-content:after{
border: solid;
border-color: transparent;
border-width: 12px;
content: "";
left: -24px;
position: absolute;
z-index: 99;
top: 8px;
border-right-color: #fff;
}
.dropdown-content:before{
border-width: 12px;
content: "";
left: -24px;
position: absolute;
z-index: 99;
top: 8px;
border-right-color: #d4d4d4;
}
.dropdown-content a {
color: black;
padding: 0px 16px !important;
text-decoration: none;
display: block;
}
.dropdown-content a:hover {background-color: #f1f1f1}
.dropdown:hover .dropdown-content {
display: block;
}
.dropdown:hover .dropbtn {
}
.form-inline {
overflow: visible !important;
width: 100%;
}
.dataTables_wrapper.no-footer {
overflow: visible !important;
}
.sab-menu{
display:none;
position:relative;
background-color: #f9f9f9;
min-width: 150px;
box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);}
.main-menu li:hover .sab-menu {
display: block;
position: absolute;
left: -379px;
border: 2px solid #bdbaba;
top: -2px;
}
.sab-menu:after{
border: solid;
border-color: transparent;
border-width: 12px;
content: "";
right: -21px;
position: absolute;
z-index: 99;
top: 0px;
border-left-color: #fff;
}
.sab-menu:before{
border: solid;
border-color: transparent;
border-width: 12px;
content: "";
right: -24px;
position: absolute;
z-index: 99;
top: 1px;
border-left-color: #bdbaba;
}
li{
list-style:none;}
ul{
margin:0px;
padding:0px;}
.main-menu{
margin:0px;}
.main-menu li{
position:relative;}

</style>
@append

@section('content')

<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title float-left">Shipment Status</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="#">Shipment </a></li>
                            <li class="breadcrumb-item active">Shipment Status</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card-box">
                        <h4 class="m-t-0 mb-4">Shipments In Progess</h4>
						@foreach($shipments as $shipment)
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

<script>

</script>
@append
