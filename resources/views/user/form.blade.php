<div class="form-group {{ $errors->has('roles') ? 'has-error' : ''}}">
    {!! Form::label('roles', 'Roles', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('roles[]', Spatie\Permission\Models\Role::get()->pluck('name','name'), isset($user)?$user->getRoleNames():null, ['class' => 'form-control', 'required' => 'required','disabled'=>'disabled'] ) !!}
        {!! $errors->first('roles', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
    {!! Form::label('name', 'Name', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
         {!! Form::text('name', null, ['placeholder'=>'Enter Name','class' => 'form-control', 'required' => 'required','maxlength'=>'100','data-parsley-maxlength-message'=>'Name should be less then 100','data-parsley-required-message'=>'Please Enter Name']) !!}
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
    {!! Form::label('email', 'Email', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::email('email', null,  ['parsley-trigger' => 'change','required'=>'' , 'class' =>'form-control','data-parsley-required-message'=>'Please Enter Address','maxlength'=>'100' ] ) !!}
        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('contact') ? 'has-error' : ''}}">
    {!! Form::label('contact', 'Contact', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
       {{ Form::text('contact',null,['placeholder' => 'Mobile/Phone Number','autocomplete'=>'off','parsley-trigger' => 'change','required'=>'' , 'class' =>'form-control','data-parsley-required-message'=>'Please Enter Mobile/Phone Number','data-parsley-minlength'=>'9','data-parsley-minlength-message'=>'Mobile/Phone Number should be greater then 9 digit','maxlength'=>'12','data-parsley-pattern'=>'^[\d\+\-\.\(\)\/\s]*$','data-parsley-pattern-message'=>'Please Enter Only Number']) }}
        {!! $errors->first('contact', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('address') ? 'has-error' : ''}}">
    {!! Form::label('Address', 'Address', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {{ Form::textarea('address',null,['placeholder' => 'Address','autocomplete'=>'off','parsley-trigger' => 'change','required'=>'' , 'class' =>'form-control','data-parsley-required-message'=>'Please Enter Address','maxlength'=>'500']) }}
        {!! $errors->first('address', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('ic_number') ? 'has-error' : ''}}">
    {!! Form::label('Ic Number', 'Ic Number', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {{ Form::text('ic_number',null,['placeholder' => 'Address','autocomplete'=>'off','parsley-trigger' => 'change','required'=>'' , 'class' =>'form-control','data-parsley-required-message'=>'Please Enter Address','maxlength'=>'50']) }}
        {!! $errors->first('ic_number', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>
</div>
