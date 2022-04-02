<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
        <meta charset="utf-8" />
        <title>Ukshop4malasiya</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <!-- CSRF Token -->
    	<meta name="csrf-token" content="{{ csrf_token() }}">
        
       <!-- Custom box css -->
        <!--<link href="plugins/custombox/css/custombox.min.css" rel="stylesheet">

        <link href="default/assets/css/style.css" rel="stylesheet" type="text/css" />
        <link href="default/assets/css/custom.css" rel="stylesheet" type="text/css" />
        <script src="default/assets/js/modernizr.min.js"></script>-->
        
        
        <title>{{ config('app.name', 'Uk shop') }}</title>
        
		<!-- App favicon -->
        <link rel="shortcut icon" href="default/assets/images/favicon.ico">
        <!-- Styles -->
        <link href="{{ asset('public/main_theme/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('public/main_theme/css/icons.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('public/main_theme/css/metismenu.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('public/main_theme/css/style.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('public/main_theme/plugins/jquery-toastr/jquery.toast.min.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('public/main_theme/css/custombox.min.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('public/main_theme/css/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('public/main_theme/css/custom.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('public/main_theme/css/ayush.css') }}" rel="stylesheet" type="text/css" />
        <script src="{{ asset('public/main_theme/js/modernizr.min.js') }}"></script>
		
		<!-- <script src="{{ asset('public/main_theme/js/parsley-fields-comparison-validators.js') }}"></script> -->
		
    @yield('css')
    <script type="text/javascript">
		var APP_URL = {!! json_encode(url('/')) !!};
		
	</script>
</head>
<body >
    <!-- ========== Begin page ========== -->
    <div id="wrapper">
    	<!-- ========== Top Bar Start ========== -->
 			@include('layouts.topbar')
        <!-- ========== Top Bar End ========== -->
        
        <!-- ========== Left Sidebar Start ========== -->
        @role('Super-Admin')
        	@include('layouts.sidebar')
        @endrole
        @role('Sales-Agent')
        	@include('layouts.sales-agent-sidebar')
        @endrole
        @role('Dispatch-Manager')
        	@include('layouts.dispatch-manager-sidebar')
        @endrole
        @role('Data-Entry-User')
        	@include('layouts.data-entry-user-sidebar')
        @endrole
        <!-- ========== Left Sidebar End ========== -->
        
  		<!-- ==========  Start right Content here ========== -->
        	@yield('content')
        <!-- ==========  End right Content here ========== -->
  		
    	
  
  
	</div>
<!-- END wrapper --> 

<!-- jQuery  --> 
<script src="{{ asset('public/main_theme/js/jquery.min.js') }}"></script> 
<script src="{{ asset('public/main_theme/js/popper.min.js') }}"></script> 
<script src="{{ asset('public/main_theme/js/bootstrap.min.js') }}"></script> 
<script src="{{ asset('public/main_theme/js/metisMenu.min.js') }}"></script> 
<script src="{{ asset('public/main_theme/js/waves.js') }}"></script> 
<script src="{{ asset('public/main_theme/js/jquery.slimscroll.js') }}"></script> 
<script src="{{ asset('public/main_theme/js/bootstrap-select.js') }}"></script> 
<script src="{{ asset('public/main_theme/js/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('public/main_theme/js/custombox.min.js') }}"></script>
<script src="{{ asset('public/main_theme/js/legacy.min.js') }}"></script>
<script src="{{ asset('public/main_theme/js/jquery.waypoints.min.js') }}"></script>
<!-- App js --> 
<script src="{{ asset('public/main_theme/js/jquery.core.js') }}"></script> 
<script src="{{ asset('public/main_theme/js/jquery.app.js') }}"></script>
<script src="{{ asset('public/main_theme/plugins/jquery-toastr/jquery.toast.min.js') }}"></script>
<script src="{{ asset('public/main_theme/js/parsley.min.js') }}"></script>
@if(!isset($parsely_disable))
<script>
$(document).ready(function() {
	$('form').parsley();
	$('[data-toggle="tooltip"]').tooltip();  
});
</script>
@endif

 @yield('scripts')
  
@if(Session::has('success-toast-message'))
<script>                  		
$(document).ready(function(){
    $.toast({
        heading: "{{ Session::get('title') }}" ,
        text: "{{ Session::get('success-toast-message') }}",
        position: 'top-right',
        loaderBg: '#3b98b5',
        icon: 'success',
        hideAfter: 2000,
        stack: 1
    });
	
});
 
</script>
@endif

@if(Session::has('error-toast-message'))
<script>                  		
$(document).ready(function(){
    $.toast({
        heading: "{{ Session::get('title') }}" ,
        text: "{{ Session::get('error-toast-message') }}",
        position: 'top-right',
        loaderBg: '#3b98b5',
        icon: 'danger',
        hideAfter: 2000,
        stack: 1
    });
});     
</script>
@endif
</body>
</html>