<div class="topbar"> 
    
    <!-- LOGO -->
    <div class="topbar-left"> <a href="{{ url('/dashboard')}}" class="logo"> <span> <img src="{{ asset('public/main_theme/images/logo.png')}}" alt="" height="45"> </span> <i> <img src="{{ asset('public/main_theme/images/logo.png')}}" alt="" height="28"> </i> </a> </div>
    <nav class="navbar-custom">
        <ul class="list-unstyled topbar-right-menu float-right mb-0">
         @if(Session::get('admin_id'))
         	<li><a href="{{url('backtoadmin')}}" class="btn btn-danger ">Back to admin</a>
         @endif
            <li class="dropdown notification-list">
                <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
                    aria-haspopup="false" aria-expanded="false">
					<?php
					if(Auth::user()->Profileimage)
					{
						$img_name = '/user_images/normal_images/'.Auth::user()->Profileimage;
					}else
					{
						$img_name = '/user.png';
					}
					?>
                    <img src="{{asset('public')}}/{{$img_name}}" alt="user" class="rounded-circle"> <span class="ml-1">{{Auth::user()->name}} <i class="mdi mdi-chevron-down"></i> </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                    <!-- item-->
                    <a href="{{ url('/text')}}" class="dropdown-item notify-item">
                        <i class="fi-head"></i> <span>My Profile</span>
                    </a>
                    <!-- item-->
                     <a href="{{ route('logout') }}" class="dropdown-item notify-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fi-power"></i> <span>Sign Out</span></a>
			<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
            </form>
                </div>
            </li>
        </ul>
        <ul class="list-inline menu-left mb-0">
            <li class="float-left">
                <button class="button-menu-mobile open-left waves-light waves-effect">
                <i class="dripicons-menu"></i>
                </button>
            </li>
        </ul>
    </nav>
  </div>
  
  