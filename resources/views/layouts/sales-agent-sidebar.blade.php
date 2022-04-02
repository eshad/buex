<!-- Top Bar End -->
<!-- ========== Left Sidebar Start ========== -->
<div class="left side-menu">
    <div class="slimscroll-menu" id="remove-scroll">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu" id="side-menu">
                <li>
                    <a href="{{ url('/dashboard')}}">
                        <i class="fi-air-play"></i><span> Dashboard </span>
                    </a>
                </li>
                 <li>
                    <a href="javascript: void(0);"><i class="mdi mdi-barcode "></i> <span> My Accounts </span> <span class="menu-arrow"></span></a>
                    <ul class="nav-second-level" aria-expanded="false">
						<li><a href="{{url('report/sales_details')}}/{{encrypt(Auth::id())}}">My Sales Reports</a></li>
                        <li><a href="{{ url('/commission')}}">Commission Settings</a></li>
					</ul>
                </li>
                 <li>
                    <a href="javascript: void(0);"><i class="mdi mdi-barcode "></i> <span> Products </span> <span class="menu-arrow"></span></a>
                    <ul class="nav-second-level" aria-expanded="false">
						<li><a href="{{ url('/product')}}">Product Detail</a></li>
                        <li><a href="{{ url('/stock')}}">Malaysia Stock</a></li>
					</ul>
                </li>
                 <li>
                    <a href="javascript: void(0);"><i class="mdi mdi-account "></i> <span> Customer </span> <span class="menu-arrow"></span></a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="{{ url('/customer')}}">Customer Info</a></li>
						 <li><a href="{{ url('/customer/create')}}">Create Customer </a></li>
                    </ul>
                </li>
				<li>
                    <a href="javascript: void(0);"><i class="fi-box"></i> <span> Order </span> <span class="menu-arrow"></span></a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="{{ url('/order')}}">Manage Order</a></li>
						 <li><a href="{{ url('/order/create')}}">Add New Order</a></li>
                         <li><a href="{{ url('/partial_completed_order')}}">Partial Dispatch</a></li>
                         <li><a href="{{ url('/completed_order')}}">Completed Order</a></li>
                    </ul>
                </li>
                
                <li>
                    <a href="javascript: void(0);"><i class="mdi mdi-currency-usd "></i> <span> Payment </span> <span class="menu-arrow"></span></a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="{{ url('/manage_payment')}}">Manage Payment</a></li>
                        <li><a href="{{ url('/customer_balance')}}">Customer Balance</a></li>
                        <li><a href="{{ url('/refund')}}">Refund</a></li>
          
                    </ul>
                </li>
               <li>
					<a href="javascript: void(0);" aria-expanded="false"><i class="mdi mdi-note"></i> <span>Accounting<span class="menu-arrow"></span></span></a>
					<ul class="nav-second-level" aria-expanded="false">
                          <li><a href="{{url('user_account')}}/{{encrypt(Auth::id())}}">Statement</a></li>
                        <li><a href="{{ url('/account/create')}}">In & Out</a></li>
                    </ul>
			   </li>
                <li class="">
                    <a href="javascript: void(0);" aria-expanded="false"><i class="mdi mdi-chart-bar"></i> <span> Reports </span> <span class="menu-arrow"></span></a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="{{ url('report/customer_balance')}}">Customer Statements</a></li>
                        <li><a href="{{ url('/report')}}">Sales Report</a></li>
                        <li><a href="{{ url('report/dispatch_collection_report')}}">Dispatch COD</a></li>
                        <li><a href="{{ url('report/dispatch_manager_report')}}">Dispatch Reports</a></li>
                    </ul>
                </li>
                <li>
					<a href="javascript: void(0);" aria-expanded="false"><i class="mdi mdi-note"></i> <span>Shipments<span class="menu-arrow"></span></span></a>
					<ul class="nav-second-level" aria-expanded="false">
                      
                        <li><a href="{{ url('/shipment/show')}}">Shipment Status</a></li>
                        <li><a href="{{ url('/shipment')}}">Pending History</a></li>
                    </ul>
			   </li>


        </ul>
    </div>
    <!-- Sidebar -->
    <div class="clearfix"></div>
</div>
<!-- Sidebar -left -->
</div>
<!-- Left Sidebar End -->