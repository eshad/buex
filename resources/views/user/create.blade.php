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
                            <li class="breadcrumb-item"><a href="user.php">User</a></li>
                            <li class="breadcrumb-item active">Add User</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div> @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
            <!-- end row -->
            <div class="row">
                <div class="col-12">
                    <div class="card-box">
                        {!! Form::open(['url' => '/user', 'class' => 'form-horizontal', 'files' => true]) !!}
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Role <span class="text-danger">*</span></label>
                                <div class="col-4">
                                     {!! Form::select('roles[]', Spatie\Permission\Models\Role::get()->pluck('name','name'), isset($user)?$user->getRoleNames():null, ['class' => 'form-control', 'required' => 'required'] ) !!}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Name <span class="text-danger">*</span></label>
                                <div class="col-4">
                                   {!! Form::text('name', null, ['placeholder'=>'Enter Name','class' => 'form-control', 'required' => 'required','maxlength'=>'100','data-parsley-maxlength-message'=>'Name should be less then 100','data-parsley-required-message'=>'Please Enter Name']) !!}
                                </div>
                            </div>
                           
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Email <span class="text-danger">*</span></label>
                                <div class="col-4">
                                    {!! Form::email('email', null,  ['placeholder'=>'Enter Email','class' => 'form-control', 'required' => 'required','maxlength'=>'100','data-parsley-maxlength-message'=>'Email should be less then 100','data-parsley-required-message'=>'Please Enter Email']) !!}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Contact <span class="text-danger">*</span></label>
                                <div class="col-4">
                                     {{ Form::text('contact','',['placeholder' => 'Mobile/Phone Number','autocomplete'=>'off','parsley-trigger' => 'change','required'=>'' , 'class' =>'form-control','data-parsley-required-message'=>'Please Enter Mobile/Phone Number','data-parsley-minlength'=>'9','data-parsley-minlength-message'=>'Mobile/Phone Number should be greater then 9 digit','maxlength'=>'12','data-parsley-pattern'=>'^[\d\+\-\.\(\)\/\s]*$','data-parsley-pattern-message'=>'Please Enter Only Number']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Address <span class="text-danger">*</span></label>
                                <div class="col-4">
                                     {{ Form::textarea('address','',['placeholder' => 'Address','autocomplete'=>'off','parsley-trigger' => 'change','required'=>'' , 'class' =>'form-control','data-parsley-required-message'=>'Please Enter Address','maxlength'=>'500']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Ic Number <span class="text-danger">*</span></label>
                                <div class="col-4">
                                     {{ Form::text('ic_number','',['placeholder' => 'Address','autocomplete'=>'off','parsley-trigger' => 'change','required'=>'' , 'class' =>'form-control','data-parsley-required-message'=>'Please Enter Address','maxlength'=>'50']) }}
                                </div>
                            </div>
                            <div class="form-group text-right m-b-0">
                                 {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary']) !!}
                               
                                <a href="{{url('/user')}}" class="btn btn-light waves-effect m-l-5">
                                Cancel
                                </a>
                            </div>
                       {!! Form::close() !!}
                    </div>
                </div>
                </div> <!-- end row -->
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


   