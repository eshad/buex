@extends('layouts.master')
@section('css')


  
@append

@section('content')
@include('html/gallery');

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
    <!-- Start content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title float-left">Edit Product</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="javascript:void();">Products</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/product')}}">Products Detail</a></li>
                            <li class="breadcrumb-item active">Edit Product</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->
            
            <div class="row">
                <div class="col-12">
                    <div class="card-box">
                       {!! Form::open(['url' => 'product/update','id'=>'form_submit','enctype'=>'multipart/form-data','class'=>'form-horizontal']) !!}	
						  {{ csrf_field() }}
						  {{ method_field('PATCH') }}
						  
						  <input type='hidden' name='form_id' id='form_id' value='<?= $edit_products[0]->id ?>' /> 
						  
                            <div class="form-row">
                                <div class="col-md-4">
                                    <label for="inputEmail4" class="col-form-label">Item ID : <strong id='item_id'>{{$edit_products[0]->item_uniq_id}}</strong></label>
									<input type='hidden' value='{{$edit_products[0]->item_uniq_id}}' name='final_item_id' id='final_item_id' />
                                </div>
                            </div>
							{{ Form::hidden('hidden_cat_code',$edit_products[0]->item_uniq_id,['id'=>'hidden_cat_code']) }}
							
                             <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="inputEmail4" class="col-form-label">Item Category <span class="text-danger">*</span></label>
                                    <select class="selectpicker" required="" data-parsley-required-message="Please Select Item Category" data-live-search="true" parsley-trigger="change" id="item_category" name="item_category" onchange="get_item_id()"  data-style="btn-light">
                                        <option value=''>Please Select Category</option>
										@foreach ($category_detail as $categorys)
                                        <option <?php if($edit_products[0]->category_id==$categorys->id){echo 'selected';} ?> value='{{$categorys->id}}'>{{ strtoupper($categorys->category_name)}} - ({{strtoupper($categorys->category_code)}})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
							{{ Form::hidden('hidden_cat_id',$edit_products[0]->category_id,['id'=>'hidden_cat_id']) }}
							
							 @if ($errors->has('item_category'))
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">{{ $errors->first('item_category') }}</li>
                                        </ul>
							@endif
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="inputEmail4" class="col-form-label">Product Name <span class="text-danger">*</span></label>
									{{ Form::text('product_name',$edit_products[0]->product_name,['placeholder' => 'Product Name','required'=>'','parsley-trigger' => 'change' , 'class' =>'form-control','autocomplete' => 'off', 'id'=>'product_name' ,'data-parsley-required-message'=>'Please Enter Product Name','data-parsley-maxlength'=>'200','maxlength'=>'200']) }}
                                </div>
                            </div>
							 @if ($errors->has('product_name'))
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">{{ $errors->first('product_name') }}</li>
                                        </ul>
							@endif
                            <div class="form-row">
                                <div class="form-group col-md-10">
                                    <label for="inputEmail4" class="col-form-label">Product Note <span class="text-danger">*</span></label>
									{{ Form::textarea('product_note',$edit_products[0]->product_note,['placeholder' => 'Product Note','required'=>'','rows'=>'2','parsley-trigger' => 'change' , 'class' =>'form-control','autocomplete' => 'off', 'id'=>'product_note' ,'data-parsley-required-message'=>'Please Enter Product Note']) }}
                                </div>
                            </div>
							@if ($errors->has('product_note'))
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">{{ $errors->first('product_note') }}</li>
                                        </ul>
							@endif
                            
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="inputEmail4" class="col-form-label">Product Price (RM) <span class="text-danger">*</span></label>
									
									{{ Form::text('product_price',$edit_products[0]->product_price,['placeholder' => 'Full Payment','required'=>'' , 'class' =>'form-control numeric ','autocomplete' => 'off', 'id'=>'product_price' ,'data-parsley-required-message'=>'Please Enter Product Price','data-parsley-maxlength'=>'18','maxlength'=>'18', 'data-parsley-trigger'=>'keyup', 'data-parsley-type'=>'number']) }}
									
                                </div>
								@if ($errors->has('product_price'))
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">{{ $errors->first('product_price') }}</li>
                                        </ul>
							   @endif
                                <div class="form-group col-md-2">
                                    <label for="inputEmail4" class="col-form-label invissible">Price <span class="text-danger">*</span></label>
									{{ Form::text('price',$edit_products[0]->installment_cost,['placeholder' => 'Installment','required'=>'','parsley-trigger' => 'change', 'class' =>'form-control numeric','autocomplete' => 'off','data-parsley-trigger'=>'keyup','data-parsley-checkcost'=>'','data-parsley-validation-threshold'=>'1','id'=>'price','data-parsley-required-message'=>'Please Enter Installment','data-parsley-maxlength'=>'18','maxlength'=>'18']) }}
									
                                </div>
								@if ($errors->has('price'))
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">{{ $errors->first('price') }}</li>
                                        </ul>
							   @endif
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="inputEmail4" class="col-form-label">Local Postage</label> 
									
                                    {{ Form::text('local_postage_price',$edit_products[0]->sm_cost,['placeholder' => 'SM(RM)','class' =>'form-control numeric ','autocomplete' => 'off', 'id'=>'local_postage_price' ,'data-parsley-required-message'=>'Please Enter SM(RM)','data-parsley-maxlength'=>'18','maxlength'=>'18', 'data-parsley-trigger'=>'keyup', 'data-parsley-type'=>'number']) }}
                                </div>
								@if ($errors->has('local_postage_price'))
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">{{ $errors->first('local_postage_price') }}</li>
                                        </ul>
							   @endif
                                <div class="form-group col-md-2">
                                    <label for="inputEmail4" class="col-form-label invissible">Local Postage</label>

									{{ Form::text('local_postage',$edit_products[0]->ss_cost,['placeholder' => 'SS(RM)','class' =>'form-control numeric ','autocomplete' => 'off', 'id'=>'local_postage' ,'data-parsley-required-message'=>'Please Enter SM(RM)','data-parsley-maxlength'=>'18','maxlength'=>'18', 'data-parsley-trigger'=>'keyup', 'data-parsley-type'=>'number']) }}
                                </div>
								@if ($errors->has('local_postage'))
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">{{ $errors->first('local_postage') }}</li>
                                        </ul>
							   @endif
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="inputEmail4" class="col-form-label">AirFreight <span class="text-danger">*</span></label>
                                    {{ Form::text('airfreight',$edit_products[0]->air_freight_cost,['placeholder' => 'AirFreight','class' =>'form-control numeric ','autocomplete' => 'off', 'id'=>'airfreight' ,'data-parsley-required-message'=>'Please Enter AirFreight','data-parsley-maxlength'=>'18','maxlength'=>'18', 'data-parsley-trigger'=>'keyup', 'data-parsley-type'=>'number']) }}
                                </div>
								@if ($errors->has('airfreight'))
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">{{ $errors->first('airfreight') }}</li>
                                        </ul>
							   @endif
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="inputEmail4" class="col-form-label">UK Stock <span class="text-danger">*</span></label>
									{{ Form::text('uk_stock',$edit_products[0]->uk_stock,['placeholder' => 'UK Stock','required'=>'','readonly','parsley-trigger' => 'change' , 'class' =>'form-control numeric','autocomplete' => 'off', 'id'=>'uk_stock' ,'data-parsley-required-message'=>'Please Enter UK Stock','data-parsley-maxlength'=>'18','maxlength'=>'18']) }}
                                    
                                </div>
								@if ($errors->has('uk_stock'))
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">{{ $errors->first('uk_stock') }}</li>
                                        </ul>
							   @endif
							   
							   <div class="form-group col-md-4">
                                    <label for="inputEmail4" class="col-form-label">Malaysia Stock <span class="text-danger">*</span></label>
                                   
									{{ Form::text('malaysia_stock',$edit_products[0]->malaysia_stock,['placeholder' => 'Malaysia Stock','required'=>'','readonly','parsley-trigger' => 'change' , 'class' =>'form-control numeric','autocomplete' => 'off', 'id'=>'malaysia_stock' ,'data-parsley-required-message'=>'Please Enter  Initial Stock','data-parsley-maxlength'=>'18','maxlength'=>'18']) }}
                                    
                                </div>
								@if ($errors->has('malaysia_stock'))
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">{{ $errors->first('malaysia_stock') }}</li>
                                        </ul>
							   @endif
                            </div>

							<div class="form-row">
                                <div class="form-group col-md-8">
                                    <label for="inputEmail4" class="col-form-label">Specifics</label>
									<table class="table table-bordered">
										<thead>
											<tr>
												<th>Attributes</th>
												<th>Values</th>
											</tr>
										</thead>
										<tbody id="attr_tbody">
										<?php 
										
										foreach($products_list as $products_lists)
										{
											foreach($products_lists as $product_list)
											{
												if(count($product_list)>=6)
												{
													
													$select = '<select class="form-control" name="att_value[]" id="">';
													$select .= '<option value=""></option>';	
													for($n=0;$n<(count($product_list)-5);$n++)
													{
														$checked= '';
														if($product_list[$n]['list_value'] == $product_list['att_value'])
														{
															$checked = 'selected';
														}
														$select .= '<option '.$checked.' value="'.$product_list[$n]['list_value'].'">'.$product_list[$n]['list_value'].'</option>';
													}
													$select .= '</select>';	
										?>

											<tr class="tr_remove">
												<td class="td_remove"><input type="input" readonly value="<?= $product_list['att_name'] ?>" class="form-control" id="" name="att_name[]" /><input type="hidden" value="<?= $product_list['attr_id'] ?>" class="form-control" id="" name="att_id[]" /></td>
												<td class="td_remove"><?= $select ?></td>
											</tr>
											
										<?php  } elseif($product_list['att_type'] == 'Yes/No'){
											$select_drowndown_yn = '<select class="form-control" name="att_value[]" id="" >';
											$sel_yes = '';
											$sel_no = '';
											if($product_list['att_value'] == 'yes')
											{
												$sel_yes = 'selected';
											}else{
												$sel_no = 'selected';
											}
											$select_drowndown_yn .= '<option '.$sel_yes.' value="yes">YES</option>';
											$select_drowndown_yn .= '<option '.$sel_no.' value="no">NO</option>';
											$select_drowndown_yn .= '</select>';		
										?>

											<tr class="tr_remove">
												<td class="td_remove"><input type="input" readonly value="<?= $product_list['att_name'] ?>" class="form-control" id="" name="att_name[]" /><input type="hidden" value="<?= $product_list['attr_id'] ?>" class="form-control" id="" name="att_id[]" /></td>
												<td class="td_remove"><?= $select_drowndown_yn ?></td>
											</tr>
		
										<?php } else { ?>
											<tr class="tr_remove">
												<td class="td_remove"><input type="input" readonly value="<?= $product_list['att_name'] ?>" class="form-control" id="" name="att_name[]" /><input type="hidden" value="<?= $product_list['attr_id'] ?>" class="form-control" id="" name="att_id[]" /></td>
												<td class="td_remove"><input type="input" class="form-control" value="<?= $product_list['att_value'] ?>" id="" name="att_value[]" /></td>
											</tr>

										<?php } } }?>
										</tbody>
									</table>
                                    
                                </div>
								@if ($errors->has(''))
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">{{ $errors->first('') }}</li>
                                        </ul>
							   @endif
                            </div>
							
							
							{{ Form::hidden('hidden_asset_url',asset('public/'),['id'=>'hidden_asset_url']) }}
							
							<div class="form-row">
                                <div class="form-group col-md-12">

                                    <label disabled for="inputEmail4" class="col-form-label">Image </label>
                                   <ul class="image-lists"> 
								   
									<li>
									<?php 
									if(count($product_image)>=1)
									{
									if($product_image[0]->thumb_image_name != '')
									{
										$image_name = "product_image/thumbnail_images/".$product_image[0]->thumb_image_name;
										$style1 = "display:block;color:blue;";
										$style2 = "display:none;color:blue;";
										$no = 1;
										$i_name = $product_image[0]->thumb_image_name;
									}}else
									{
										$image_name = "main_theme/images/no_image.jpg";
										$style1 = "display:none;color:blue;";
										$style2 = "display:block;color:blue;";
										$no = 0;
										$i_name = "";
									}
									?>
										<img style='max-width:100%;height:110px;border:2px solid #ccc;border-radius:15px;' src="{{ asset('public/')}}/{!!$image_name!!}" class="img-fluid img-rounded" id="profile-img2-tag" />
										
										<label style="<?= $style1 ?>" class="lbl remove2" >Remove</label>
										
										<label class="lbl add2" style='<?= $style2 ?>' for="profile-img2">Add</label>
										
										<input  data-parsley-required-message='Please Select Category Image' style="display:none" class="form-control" accept="image/*" name="image_2" id="profile-img2" data-parsley-max-file-size="2000" data-parsley-fileextension='jpeg,jpg,png' type="file">

										<input type="hidden" name="checkimg2" id="checkimg2" value="<?= $no ?>" />
										<input type="hidden" name="checkimage2" id="checkimage2" value="<?= $i_name ?>" />
									
										
										@if($errors->has('image_2'))
										<ul class="parsley-errors-list filled">
											<li class="parsley-required">{{ $errors->first('image_2') }}</li>
										</ul>
										@endif
									</li>
									<li>
									<?php 
									if(count($product_image)>=2)
									{
									if($product_image[1]->thumb_image_name != '')
									{
										$image_name = "product_image/thumbnail_images/".$product_image[1]->thumb_image_name;
										$style1 = "display:block;color:blue;";
										$style2 = "display:none;color:blue;";
										$no = 1;
										$i_name = $product_image[1]->thumb_image_name;
									}}else
									{
										$image_name = "main_theme/images/no_image.jpg";
										$style1 = "display:none;color:blue;";
										$style2 = "display:block;color:blue;";
										$no = 0;
										$i_name = "";
									}
									?>
										<img style='max-width:100%;height:110px;border:2px solid #ccc;border-radius:15px;' src="{{ asset('public/')}}/{!!$image_name!!}" class="img-fluid img-rounded" id="profile-img3-tag" />
										
										<label style="<?= $style1 ?>" class="lbl remove3" >Remove</label>
										
										<label class="lbl add3" style='<?= $style2 ?>' for="profile-img3">Add</label>
										
										<input data-parsley-required-message='Please Select Category Image' style="display:none" class="form-control" accept="image/*" name="image_3" id="profile-img3" data-parsley-max-file-size="2000" data-parsley-fileextension='jpeg,jpg,png' type="file">

										<input type="hidden" name="checkimg3" id="checkimg3" value="<?= $no ?>" />
										<input type="hidden" name="checkimage3" id="checkimage3" value="<?= $i_name ?>" />
										
										@if($errors->has('image_3'))
										<ul class="parsley-errors-list filled">
											<li class="parsley-required">{{ $errors->first('image_3') }}</li>
										</ul>
										@endif
									</li>	
									<li>
									<?php
									if(count($product_image)>=3)
									{									
									if($product_image[2]->thumb_image_name != '')
									{
										$image_name = "product_image/thumbnail_images/".$product_image[2]->thumb_image_name;
										$style1 = "display:block;color:blue;";
										$style2 = "display:none;color:blue;";
										$no = 1;
										$i_name = $product_image[2]->thumb_image_name;
									}}else
									{
										$image_name = "main_theme/images/no_image.jpg";
										$style1 = "display:none;color:blue;";
										$style2 = "display:block;color:blue;";
										$no = 0;
										$i_name = "";
									}
									?>
										<img style='max-width:100%;height:110px;border:2px solid #ccc;border-radius:15px;' src="{{ asset('public/')}}/{!!$image_name!!}" class="img-fluid img-rounded" id="profile-img4-tag" />
										
										<label style="<?= $style1 ?>" class="lbl remove4" >Remove</label>
										
										<label class="lbl add4" style='<?= $style2 ?>' for="profile-img4">Add</label>
										
										<input  data-parsley-required-message='Please Select Category Image' style="display:none" class="form-control" accept="image/*" name="image_4" id="profile-img4" data-parsley-max-file-size="2000" data-parsley-fileextension='jpeg,jpg,png' type="file">

										<input type="hidden" name="checkimg4" id="checkimg4" value="<?= $no ?>" />
										<input type="hidden" name="checkimage4" id="checkimage4" value="<?= $i_name ?>" />
										
										@if($errors->has('image_4'))
										<ul class="parsley-errors-list filled">
											<li class="parsley-required">{{ $errors->first('image_4') }}</li>
										</ul>
										@endif
									</li>	
									<li>
									<?php
									if(count($product_image)>=4)
									{									
									if($product_image[3]->thumb_image_name != '')
									{
										$image_name = "product_image/thumbnail_images/".$product_image[3]->thumb_image_name;
										$style1 = "display:block;color:blue;";
										$style2 = "display:none;color:blue;";
										$no = 1;
										$i_name = $product_image[3]->thumb_image_name;
									}}else
									{
										$image_name = "main_theme/images/no_image.jpg";
										$style1 = "display:none;color:blue;";
										$style2 = "display:block;color:blue;";
										$no = 0;
										$i_name = "";
									}
									?>									
										<img style='max-width:100%;height:110px;border:2px solid #ccc;border-radius:15px;' src="{{ asset('public/')}}/{!!$image_name!!}" class="img-fluid img-rounded" id="profile-img5-tag" />
										
										<label style="<?= $style1 ?>" class="lbl remove5" >Remove</label>
										
										<label class="lbl add5" style='<?= $style2 ?>' for="profile-img5">Add</label>
										
										<input  data-parsley-required-message='Please Select Category Image' style="display:none" class="form-control" accept="image/*" name="image_5" id="profile-img5" data-parsley-max-file-size="2000" data-parsley-fileextension='jpeg,jpg,png' type="file">

										<input type="hidden" name="checkimg5" id="checkimg5" value="<?= $no ?>" />
										<input type="hidden" name="checkimage5" id="checkimage5" value="<?= $i_name ?>" />
										
										@if($errors->has('image_5'))
										<ul class="parsley-errors-list filled">
											<li class="parsley-required">{{ $errors->first('image_5') }}</li>
										</ul>
										@endif
									</li>	
									<li>
									<?php
									if(count($product_image)>=5)
									{
									if($product_image[4]->thumb_image_name != '')
									{
										$image_name = "product_image/thumbnail_images/".$product_image[4]->thumb_image_name;
										$style1 = "display:block;color:blue;";
										$style2 = "display:none;color:blue;";
										$no = 1;
										$i_name = $product_image[4]->thumb_image_name;
									}}else
									{
										$image_name = "main_theme/images/no_image.jpg";
										$style1 = "display:none;color:blue;";
										$style2 = "display:block;color:blue;";
										$no = 0;
										$i_name = "";
									}
									?>											
										<img style='max-width:100%;height:110px;border:2px solid #ccc;border-radius:15px;' src="{{ asset('public/')}}/{!!$image_name!!}" class="img-fluid img-rounded" id="profile-img6-tag" />
										
										<label style="<?= $style1 ?>" class="lbl remove6" >Remove</label>
										
										<label class="lbl add6" style='<?= $style2 ?>' for="profile-img6">Add</label>
										
										<input data-parsley-required-message='Please Select Category Image' style="display:none" class="form-control" accept="image/*" name="image_6" id="profile-img6" data-parsley-max-file-size="2000" data-parsley-fileextension='jpeg,jpg,png' type="file">

										<input type="hidden" name="checkimg6" id="checkimg6" value="<?= $no ?>" />
										<input type="hidden" name="checkimage6" id="checkimage6" value="<?= $i_name ?>" />
										
										@if($errors->has('image_6'))
										<ul class="parsley-errors-list filled">
											<li class="parsley-required">{{ $errors->first('image_6') }}</li>
										</ul>
										@endif
									</li>	
									<li>
									<?php 
									if(count($product_image)>=6)
									{
									if($product_image[5]->thumb_image_name != '')
									{
										$image_name = "product_image/thumbnail_images/".$product_image[5]->thumb_image_name;
										$style1 = "display:block;color:blue;";
										$style2 = "display:none;color:blue;";
										$no = 1;
										$i_name = $product_image[5]->thumb_image_name;
									}}else
									{
										$image_name = "main_theme/images/no_image.jpg";
										$style1 = "display:none;color:blue;";
										$style2 = "display:block;color:blue;";
										$no = 0;
										$i_name = "";
									}
									?>										
										<img style='max-width:100%;height:110px;border:2px solid #ccc;border-radius:15px;' src="{{ asset('public/')}}/{!!$image_name!!}" class="img-fluid img-rounded" id="profile-img7-tag" />
										
										<label style="<?= $style1 ?>" class="lbl remove7" >Remove</label>
										
										<label class="lbl add7" style='<?= $style2 ?>' for="profile-img7">Add</label>
										
										<input  data-parsley-required-message='Please Select Category Image' style="display:none" class="form-control" accept="image/*" name="image_7" id="profile-img7" data-parsley-max-file-size="2000" data-parsley-fileextension='jpeg,jpg,png' type="file">

										<input type="hidden" name="checkimg7" id="checkimg7" value="<?= $no ?>" />
										<input type="hidden" name="checkimage7" id="checkimage7" value="<?= $i_name ?>" />
										
										@if($errors->has('image_7'))
										<ul class="parsley-errors-list filled">
											<li class="parsley-required">{{ $errors->first('image_7') }}</li>
										</ul>
										@endif
									</li>
									
									
									</ul>
                                </div>
                            </div>

                            <div style="margin-top:10px;" class="form-group text-right m-b-0">
                                <button id="submit" class="btn btn-primary waves-effect waves-light" type="submit">
                                Save
                                </button>
                               <a href="{{url('/product')}}" class="btn btn-light waves-effect m-l-5">
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


@endsection


@section('scripts')


<script>

$("#profile-img2").change(function(){		
        readURL(this,'profile-img2');
		$('.add2').css('display','none');
		$('#checkimg2').val('1');
		$('.remove2').css('display','block');
    });	
$('.remove2').click(function(){
	var hidden_url = $('#hidden_asset_url').val();
	$('#profile-img2-tag').attr('src',hidden_url+'/main_theme/images/no_image.jpg');
	$('#profile-img2').val('');
	$('#checkimg2').val('0');
	$('.add2').css('display','block');
	$('.remove2').css('display','none');
});

$("#profile-img3").change(function(){		
        readURL(this,'profile-img3');
		$('.add3').css('display','none');
		$('#checkimg3').val('1');
		$('.remove3').css('display','block');
    });
	
$('.remove3').click(function(){
	$('#profile-img3-tag').attr('src','../public/main_theme/images/no_image.jpg');
	$('#profile-img3').val('');
	$('#checkimg3').val('0');
	$('.add3').css('display','block');
	$('.remove3').css('display','none');
});


$("#profile-img4").change(function(){		
        readURL(this,'profile-img4');
		$('.add4').css('display','none');
		$('#checkimg4').val('1');
		$('.remove4').css('display','block');
    });
	
$('.remove4').click(function(){
	$('#profile-img4-tag').attr('src','../public/main_theme/images/no_image.jpg');
	$('#profile-img4').val('');
	$('#checkimg4').val('0');
	$('.add4').css('display','block');
	$('.remove4').css('display','none');
});


$("#profile-img5").change(function(){		
        readURL(this,'profile-img5');
		$('.add5').css('display','none');
		$('#checkimg5').val('1');
		$('.remove5').css('display','block');
    });
	
$('.remove5').click(function(){
	$('#profile-img5-tag').attr('src','../public/main_theme/images/no_image.jpg');
	$('#profile-img5').val('');
	$('#checkimg5').val('0');
	$('.add5').css('display','block');
	$('.remove5').css('display','none');
});


$("#profile-img6").change(function(){		
        readURL(this,'profile-img6');
		$('.add6').css('display','none');
		$('#checkimg6').val('1');
		$('.remove6').css('display','block');
    });
	
$('.remove6').click(function(){
	$('#profile-img6-tag').attr('src','../public/main_theme/images/no_image.jpg');
	$('#profile-img6').val('');
	$('#checkimg6').val('0');
	$('.add6').css('display','block');
	$('.remove6').css('display','none');
});


$("#profile-img7").change(function(){		
        readURL(this,'profile-img7');
		$('.add7').css('display','none');
		$('#checkimg7').val('1');
		$('.remove7').css('display','block');
    });
	
$('.remove7').click(function(){
	$('#profile-img7-tag').attr('src','../public/main_theme/images/no_image.jpg');
	$('#profile-img7').val('');
	$('#checkimg7').val('0');
	$('.add7').css('display','block');
	$('.remove7').css('display','none');
});	

function readURL(input,myid) 
{
	if (input.files && input.files[0]) 
	{
       var reader = new FileReader();
       reader.onload = function (e) 
	   {
        $('#'+myid +'-tag').attr('src', e.target.result);
       }
       reader.readAsDataURL(input.files[0]);
    }
}

window.Parsley.addValidator('maxFileSize', {
  validateString: function(_value, maxSize, parsleyInstance) {
    if (!window.FormData) {
      alert('You are making all developpers in the world cringe. Upgrade your browser!');
      return true;
    }
    var files = parsleyInstance.$element[0].files;
    return files.length != 1  || files[0].size <= maxSize * 1024;
  },
  requirementType: 'integer',
  messages: {
    en: 'This file should not be larger than %s Kb',
  }
});

window.ParsleyValidator.addValidator('fileextension', function (value, requirement) {
	var fileExtension = value.split('.').pop();
	var arr = requirement.split(',');
	var fileExtension = value.split('.').pop();
	if(jQuery.inArray(fileExtension,arr )<0){
		return false;
	}else{ return true;}
	
}, 32)
.addMessage('en', 'fileextension', 'File type must be jpg , png , jpeg');


function get_item_id()
{
	var cat_id = $('#item_category').val();
	var hidden_cat_id = $('#hidden_cat_id').val();
	var hidden_cat_code = $('#hidden_cat_code').val();
	if(cat_id == hidden_cat_id)
	{
		$('#item_id').html(hidden_cat_code);
		return false;
	}
	var cat_text = $("#item_category option:selected").text();
	var result = cat_text.match(/\((.*)\)/);
	var my_url = APP_URL+'/check_ajax_cate_id';
	var formData = {
		cat_id:cat_id,		
		}
	var type = "POST";
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		})	
		$.ajax({
			type: type,
			url: my_url,
			data: formData,
			dataType: 'json',
			success: function (data) {
				var data = data + 1;
				var item_id_value = result[1]+-+data;
				$('#item_id').html(item_id_value);
				$('#final_item_id').val(item_id_value);
			}
		}); 
}
	
$('.numeric').on('keypress',function (event) {
	return isNumber(event, this)
	if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
		event.preventDefault();
	}
	var input = $(this).val();
	if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 2)) {
		event.preventDefault();
	}
});

function isNumber(evt, element) {

var charCode = (evt.which) ? evt.which : event.keyCode

if (
	//(charCode != 45 || $(element).val().indexOf('-') != -1) &&      // “-” CHECK MINUS, AND ONLY ONE.
	(charCode != 46 || $(element).val().indexOf('.') != -1) &&      // “.” CHECK DOT, AND ONLY ONE.
	(charCode < 48 || charCode > 57))
	return false;

return true;
}

$(document).ready(function(){
    $("form").submit(function(){
        $("#loading").css("display", "block");
    });
});

window.Parsley.addValidator('checkcost', {
  validateNumber: function(value, requirement) {
	  var product_price = $('#product_price').val();
	  if(product_price){
		  if(product_price>value){
			  return false;
		  }else{
			return true;  
		  }
	  }else{
		return true;  
	  }
    
  },
  requirementType: 'integer',
  messages: {
    en: 'This value should be a greater then Product Price',
  }
});	


$("#item_category").on('change', function() {
        $("#item_category").parsley().reset();  
    });		 
</script>


@append
