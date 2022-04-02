@extends('layouts.master')

@section('css')

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
                            <li class="breadcrumb-item"><a href="javascript:void();">Setting</a></li>
                            <li class="breadcrumb-item"><a href="{{url('user')}}">User</a></li>
                            <li class="breadcrumb-item active">Edit User</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div> 
                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        {!! Form::model($user, [
                            'method' => 'PATCH',
                            'url' => ['/user', $user->id],
                            'class' => 'form-horizontal',
                            'files' => true
                        ]) !!}

                        @include ('user.form', ['submitButtonText' => 'Update'])

                        {!! Form::close() !!}
            <!-- end row -->
             <!-- end row -->
                </div> <!-- container -->
                </div> <!-- content -->
                <footer class="footer text-right">
                    2018 © UKSHOP.
                </footer>
            </div>
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->

@endsection


@section('scripts')
@append


   