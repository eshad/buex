<!DOCTYPE html>
<html>

<head>
        <meta charset="utf-8" />
        <title>Ukshop4malasiya | Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="google-site-verification"
             content="<meta name="google-site-verification" content="cJnEAJPGXd8ZOLiuOvWdn6grCoahIiRR0js2TNRTylw"  />
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- App css -->
        <link href="{{ asset('public/main_theme/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('public/main_theme/css/icons.css')}}" rel="stylesheet" type="text/css" />
       	<link href="{{ asset('public/main_theme/css/style.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('public/main_theme/css/custom.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('public/main_theme/css/metismenu.min.css')}}" rel="stylesheet" type="text/css" />
       
    </head>
<body class="bg-accpunt-pages">
        <!-- HOME -->
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="wrapper-page">
                            <div class="account-pages">
                                <div class="account-box">
                                    <div class="account-logo-box">
                                     @if(Session::has('message'))
                        		<div class="alert alert-dismissible fade show {{ Session::get('alert-class') }}" role="alert">
                                        {{ Session::get('message') }}
                                 </div>
                                 @endif
                                        <h2 class="text-uppercase text-center">
                                        <a href="index.html" class="text-success">
                                            <span><img src="{{ asset('public/main_theme/images/logo.png')}}" alt=""></span>
                                        </a>
                                        </h2>
                                        <h6 class="text-uppercase text-center font-bold mt-4">Sign In</h6>
                                    </div>
                                    <div class="account-content">
                                        <form class="form-horizontal" method="POST" action="{{ route('login') }}" data-parsley-validate autocomplete="off">
                        {{ csrf_field() }}
                                            <div class="form-group m-b-20 row">
                                                <div class="col-12">
                                                    <label for="emailaddress">Email address</label>
                                                     <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus placeholder="Enter your email" data-parsley-maxlength="100" data-parsley-required-message="Please enter your E-Mail">
     @if ($errors->has('email'))
<ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('email') }}</li></ul>
  @endif
                                                </div>
                                            </div>
                                            <div class="form-group row m-b-20">
                                                <div class="col-12">
                                                    <a href="{{url('password/reset')}}" class="text-muted pull-right"><small>Forgot your password?</small></a>
                                                    <label for="password">Password</label>
                                                    <input id="password" type="password" class="form-control" name="password" required placeholder="Enter your password" data-parsley-maxlength="100" data-parsley-required-message="Please enter your Password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                                                </div>
                                            </div>
                                            <div class="form-group row m-b-20">
                                                <div class="col-12">
                                                    <div class="checkbox checkbox-success">
                                                       <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                                        <label for="remember">
                                                            Remember me
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row text-center m-t-10">
                                                <div class="col-12">
                                                    <button class="btn btn-block btn-primary waves-effect waves-light" type="submit">Sign In</button>
                                                </div>
                                            </div>
                                        </form>
                                        <!--<div class="row m-t-50">
                                            <div class="col-sm-12 text-center">
                                                <p class="text-muted">Don't have an account? <a href="signup.php" class="text-dark m-l-5"><b>Sign Up</b></a></p>
                                            </div>
                                        </div>-->
                                    </div>
                                </div>
                            </div>
                            <!-- end card-box-->
                        </div>
                        <!-- end wrapper -->
                    </div>
                </div>
            </div>
        </section>

    
        <!-- END HOME -->


    <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
    <script src="{{URL::asset('public/main_theme/js/popper.min.js')}}"></script>
   <script src="{{URL::asset('public/main_theme/js/bootstrap.min.js')}}"></script>
        
        <script src="{{URL::asset('public/main_theme/plugins/parsleyjs/parsley.min.js')}}"></script>      

    </body>
</html>
