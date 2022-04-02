
<!DOCTYPE html>
<html>

<head>
        <meta charset="utf-8" />
        <title>UKshop - Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- App css -->
        <link href="{{ asset('public/main_theme/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('public/main_theme/css/icons.css')}}" rel="stylesheet" type="text/css" />
       
        <link href="{{ asset('public/main_theme/css/style.css')}}" rel="stylesheet" type="text/css" />


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
                                @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                                    <div class="account-logo-box">
                                        <h2 class="text-uppercase text-center">
                                            <a href="index.html" class="text-success">
                                                <span><img src="{{ asset('public/main_theme/images/logo.png')}}" alt="" ></span>
                                            </a>
                                        </h2>
                                        <h6 class="text-uppercase text-center font-bold mt-4">Sign In</h6>
                                    </div>
                                    <div class="account-content">
                                        <div class="text-center m-b-20">
                                            <p class="text-muted m-b-0">Enter your email address and we'll send you an email with instructions to reset your password.  </p>
                                        </div>
                                       <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                        {{ csrf_field() }}

                                            <div class="form-group row m-b-20">
                                                <div class="col-12">
                                                    <label for="emailaddress">Email address</label>
                                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required placeholder="Email">

                                @if ($errors->has('email'))
                                    <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('email') }}</li></ul>
                                @endif
                                                </div>
                                            </div>

                                            <div class="form-group row text-center m-t-10">
                                                <div class="col-12">
                                                    <button class="btn btn-block btn-gradient waves-effect waves-light" type="submit">Reset Password</button>
                                                </div>
                                            </div>

                                        </form>

                                        <div class="row m-t-50">
                                            <div class="col-sm-12 text-center">
                                                <p class="text-muted">Back to <a href="{{ url('/login') }}" class="text-dark m-l-5"><b>Sign In</b></a></p>
                                            </div>
                                        </div>

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


        

    </body>
</html>