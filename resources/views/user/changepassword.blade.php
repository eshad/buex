@extends('layouts.master')

@section('css')
<link href="{{ asset('public/main_theme/css/slim.min.css') }}" rel="stylesheet" type="text/css" />
<style>
.warning {
color: #9F6000;
background-color: #FEEFB3;
background-image: url('warning.png');
}
</style>
@append

@section('content')
<div id="loading" style="display:none">
	<div id="loading-center">
	<div id="loading-center-absolute">
	<div class="object" id="object_one"></div>
	<div class="object" id="object_two"></div>
	<div class="object" id="object_three"></div>
	<div class="object" id="object_four"></div>
	<div class="object" id="object_five"></div>
	<div class="object" id="object_six"></div>
	<div class="object" id="object_seven"></div>
	<div class="object" id="object_eight"></div>

	</div>
	</div>
 
</div>

<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title float-left">My Account</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="javascript:void();">My Account</a></li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-6 col-md-4">
                   <div class="card-box text-center p-0">
                        <form action="{{ url('/updateprofileimage')}}" method="post" enctype="multipart/form-data" class="avatar">{{ csrf_field() }} 
                            <div class="slim browsePhoto" data-label="Drop your Image here" data-ratio="1:1" data-size="240,240" >
							
							<?php 
							if($user_detail[0]->image_name)
							{
								$img_name = './public/user_images/normal_images/'.$user_detail[0]->image_name;
							}else
							{
								$img_name = './public/user.png';
							}
							?>
                                <img src=<?=$img_name?> class="profile-user-img img-fluid rounded-circle"/>

                                <input type="file" name="avatar" required data-parsley-errors-container="#msg_box_cover_img" data-parsley-max-file-size="2000" data-parsley-fileextension='jpeg,jpg,png' data-parsley-required-message="Please select image"/>
                            </div>
                            <div id="msg_box_cover_img"></div>
                            <div class="p-3">
                                <button class="btn btn-gradient waves-effect waves-light" type="submit">Upload now!</button>
                            </div>
                        </form> 
                    </div>
                </div>
           
              
                <div class="col-8">
                    <div class="card-box">
                        <h5 class="m-t-0 m-b-30">Profile Detail</h5>
						  @if($errors->any())
						 <div class="warning">{{$errors->first()}}</div>
						@endif
                       {!! Form::open(['url' => 'change_my_password_save','id'=>'password','files' => true,]) !!}
					   <input type="hidden" name="edit_id" id="edit_id" value="<?php echo Auth::user()->id; ?>" />
                            <div class="form-group row">
                                <label class="col-4 col-form-label">Name</label>
                                <div class="col-6">
                                   {{ Form::text('user_name',$user_detail[0]->name,['placeholder' => 'Full Name','required'=>'','parsley-trigger' => 'change' , 'class' =>'form-control','autocomplete' => 'off', 'id'=>'user_name' ,'data-parsley-required-message'=>'Please Enter Name','data-parsley-maxlength'=>'30','maxlength'=>'30']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-4 col-form-label">Email</label>
                                <div class="col-6">
                                   {{ Form::text('user_email',$user_detail[0]->email,['placeholder' => 'Email','required'=>'','parsley-trigger' => 'change' , 'class' =>'form-control','readonly','autocomplete' => 'off', 'id'=>'user_email' ,'data-parsley-required-message'=>'Please Enter Email','data-parsley-maxlength'=>'30','maxlength'=>'30']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-4 col-form-label">Current Password</label>
                                <div class="col-6">
                                    
									
									 <input id="current_password" name="current_password" placeholder= "Current Password" type="password" class="form-control"data-parsley-minlength="6" data-parsley-maxlength="30" data-parsley-minlength-message="Please enter more than 6 character" data-parsley-maxlength-message="Do not enter more than 30 character" data-parsley-required-message="Please Enter Current Password."  />
									 
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-4 col-form-label">Enter New Password</label>
                                <div class="col-6">
                                    <input id="new_password" name="new_password" placeholder= "Enter New Password" type="password" class="form-control"data-parsley-minlength="6" data-parsley-maxlength="30" data-parsley-errors-container=".errorspannewpassinput" data-parsley-minlength-message="Please enter more than 6 character" data-parsley-maxlength-message="Do not enter more than 30 character" data-parsley-required-message="Please enter your new password."  />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-4 col-form-label">Confirm New Password</label>
                                <div class="col-6">
                                   <input name="con_password" id="con_password" placeholder= "Repeat New Password" type="password" class="form-control"data-parsley-minlength="6" data-parsley-maxlength="30" data-parsley-errors-container=".errorspanconfirmnewpassinput" data-parsley-required-message="Please re-enter your new password." data-parsley-equalto="#new_password" data-parsley-minlength-message="Please enter more than 6 character" data-parsley-maxlength-message="Do not enter more than 30 character"  />
                                </div>
                            </div>
                            <hr>
                            <div class="form-group text-right m-b-0">
                                <button class="btn btn-primary waves-effect waves-light" type="Save">
                                Save
                                </button>
                                <button type="reset" class="btn btn-light waves-effect m-l-5">
                                Cancel
                                </button>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
			</div>
           </div> 
       </div> 
   </div> 
<footer class="footer text-right"> 2018 © UKSHOP.</footer>
</div>

@endsection


@section('scripts')
<script src="{{ asset('public/main_theme/js/slim.kickstart.js') }}"></script> 
<script>

</script>
@append
