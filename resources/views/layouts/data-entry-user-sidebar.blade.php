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
                    <a href="javascript: void(0);"><i class="mdi mdi-barcode "></i> <span> Products </span> <span class="menu-arrow"></span></a>
                    <ul class="nav-second-level" aria-expanded="false">
						<li><a href="{{ url('/product')}}">Product Detail</a></li>
					<li><a href="{{ url('/product/create')}}">Add New Product</a></li>
					<li><a href="{{ url('/stock')}}">Malaysia Stock</a></li>
					 <li><a href="{{ url('/stock/create')}}">Update Stock</a></li>
					 <li><a href="{{ url('/category')}}">Product Category</a></li>
                     <li><a href="{{ url('/attribute')}}">Product Attribute</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);"><i class="fi-cog"></i> <span> Setting </span> <span class="menu-arrow"></span></a>
                    <ul class="nav-second-level collapse" aria-expanded="false">
                       
                        <li><a href="{{url('sales_agent_list')}}">Sales Agents</a></li>
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