<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
<!-- jQuery library -->
<div class="wrapper">
<div class="content-page">
    <!-- Start content -->
    <div class="content">
          <div class="container-fluid">
        	<!-- end row -->
            <div class="col-md-12"><h3 align="center">Order Details</h3></div>
            <div class="row">
               		    
                    	<div class="col-md-6">
                            <h5 class="m-t-0"><b>Shipping Type : <small>@if($order[0]->shipping_type_id==1)Air Freight @elseif($order[0]->shipping_type_id==2) Sea Freight @else Direct Sale @endif</small></b></h5>
                            <h5 class="m-t-0">Customer Name : <small>{{$order[0]->customer_name}} ({{$order[0]->customer_code}})</small></h5>
                            <h5 class="m-t-0">Order Status : <small>
                          
                            <?php if($order[0]->order_status=='new'){ 
									    ?>
                                        No Action Required
                                        <?php
									 }
									 else if($order[0]->order_status=='rtc')
									 {
										?>
                                      Self-Pickup
                                        <?php 
								     }
									  else if($order[0]->order_status=='hold')
									 {
										?>
                                     Freeze the Order
                                        <?php 
								     }
									 else if($order[0]->order_status=='cod')
									 {
										?>
                                     Cash On Delivery
                                        <?php 
								     }
									 else if($order[0]->order_status=='rts')
									 {
										?>
                                        Remove Restrictions
                                        <?php 
								     }
									 else
									 {
										?>
                                         Partial ship
                                        <?php 
									 }
									  ?>  
                            </small></h5>
                        </div>
                    	<div class="col-md-6 text-right">
                            <h5 class="m-t-0"><b>Order Date : <small>{{date("d-m-Y", strtotime($order[0]->order_date) )}}</small></b></h5>
                            <h5 class="m-t-0">Order ID : <small>{{$order[0]->order_code}}</small></h5>
                        </div>
             </div>
           
            
            <div class="row">
                <div class="col-12">
                    
                        <table id="customer" class="table table-striped table-bordered table-hover table-sm">
                            <thead>
                              
                                <tr>
                                    <th>SN.</th>
                                    <th>Image</th>
                                    <th>Product name</th>
                                    <th>Ord/Rem Qty.</th>
                                    
                                    <th>Ship Qty.</th>
                                    <th>Current Position</th>
                                    <th>U.Price (Full/Ins)</th>
                                    <th>Total</th>
                                    <th>ETA</th>
                                    <th>Shipping Status</th>
                                    <th>Payment Status</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                            <?php $subtotal = 0; $total_quantity = 0; $i=1;?>
                            @foreach($order_items as $order_item)
                            	<?php $subtotal = $subtotal+$order_item['total_amount'];
									  $total_quantity=$total_quantity+$order_item['quantity'];
								?>
                                <tr id="item_row_{{$order_item['order_item_id']}}">
                                    <td>{{$i++}}</td>
                                    <td><a href="javascript:void();"><img src="{{asset('public/product_image/thumbnail_images')}}/{{$order_item['image_name1']}}" alt="" width="35"><input type="hidden" name="item_quantity[]" value="{{$order_item['quantity']}}" }} /><input type="hidden" name="item_total_amount[]" value="{{$order_item['total_amount']}}" }} /><input type="hidden" name="update_ship_quantity[]" value="{{$order_item['ship_quantity']}}" id="update_ship_quantity_{{$order_item['order_item_id']}}" />
                                 <input type="hidden" name="update_unit_price[]" value="{{$order_item['product_price']}}" id="update_unit_price_{{$order_item['order_item_id']}}" />   
                                    <input type="hidden" name="order_item_id[]" value="{{$order_item['order_item_id']}}" /></a>
                                    </td>
                                    <td>{{$order_item['product_name']}} ({{$order_item['product_code']}})</td>
                                    <td>{{$order_item['quantity']}}</td>
                                   
                                    <td id="column_ship_quantity_{{$order_item['order_item_id']}}">{{$order_item['ship_quantity']}}</td>
                                    <td>
                                    @if($order_item['dispatch_ready']==2)
                                    	
                                    <?php $shipment = App\Shipment::select('shipment_number','shipment_date','bl_awb_number','carrier_details','created_by')->where('id',$order_item['shipment_id'])->first(); ?>
                                    <div class="dropdown">
								<a href="javascript:void();" class="dropbtn"><span>{{$shipment->shipment_number}}</span></a>
                                <?php 
								$date2=date_create($shipment->shipment_date);
								$date3 = date_create(date('Y-m-d'));
								$diff2=date_diff($date2,$date3);
								
							?>
							
								<div class="dropdown-content">
									<ul class="main-menu text-left">
										<li><pre><h6>Tracking information</h6></pre></li>
										<li><pre>{{$shipment->shipment_number}} </pre></li>
										<li><pre>Remaining <?php echo $rem =$diff2->format("%a")-1;?> Days</pre></li>
										<li><pre>BL : {{$shipment->bl_awb_number}} </pre></li>
										<li><pre>Carrier : {{$shipment->carrier_details}} </pre></li>
									</ul>
								</div>
							</div>
                            @elseif($order_item['dispatch_ready']==1)
                            	MY Stock
                            @else
                            		{{$order_item['s_from']}}
                            @endif</td>
                                    <td id="column_unit_price_{{$order_item['order_item_id']}}">(RM) {{$order_item['product_price']}} </td>
                                    <td id="line_total_price_{{$order_item['order_item_id']}}">(RM) {{$order_item['total_amount']}} </td>
                                    <td>{{date("d-m-Y", strtotime($order[0]->est_delivery_date) )}}</td>
                                    <td>
                                   @if($order_item['s_from']=='my_stock')
                                   <span class="badge badge-info badge-pill">Ready</span>
                                   @elseif($order_item['dispatch_ready'] ==1 )
                                    	@if($order_item['quantity']  == $order_item['ship_quantity'])
                                   			<span class="badge badge-info badge-pill">Ready</span>
                                        @else
                                        <span class="badge badge-info badge-pill">Partial Ready</span>
                                        @endif
                                   @elseif($order_item['dispatch_ready'] ==2)
                                        <span class="badge badge-danger badge-pill">In Transist</span></td>
                                   @else
                                        <span class="badge badge-info badge-pill">Not Ready</span></td>
                                   @endif
                                    <td>@if(($order[0]->order_total - $total_paid)>0)<span class="badge badge-danger badge-pill">Due</span>@else<span class="badge badge-success badge-pill">Paid</span> @endif</td>
                                    
                                </tr>
                                @endforeach
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Sub Total :</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><span class="badge badge-success badge-pill" id="subtotal_badge">(RM) {{number_format((float)$subtotal, 2, '.', '')}}</span></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    
                                </tr>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Airfreight :</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><span class="badge badge-success badge-pill">(RM) {{$order[0]->total_airfreight_cost}}<input type="hidden" id="total_airfreight_cost" value="{{$order[0]->total_airfreight_cost}}" /></span></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                  
                                </tr>
                                
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Local Postage :</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><span class="badge badge-success badge-pill" id="total_local_postage_cost_badge" ondblclick="show_postage_box();">(RM) {{$order[0]->total_local_postage_cost}}</span><input type="number" name="total_local_postage_cost_input" id="total_local_postage_cost_input" class="form-control" required="required" min="1" data-parsley-min-message="Minimum 1" data-parsley-required-message="Set cost" onblur="set_local_postage_cost(this)"  value="{{$order[0]->total_local_postage_cost}}" style="display:none; width:120px;"/></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                   
                                </tr>
                                @if($order[0]->amount_penalty >0)
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Penalty :</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><span class="badge badge-danger badge-pill">(RM) {{$order[0]->amount_penalty}}</span></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                   
                                </tr>
                                @endif
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Paid Payment :</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>
                                    <span class="badge badge-success badge-pill">Paid {{number_format($total_paid, 2, '.', ',')}}</span></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                   
                                </tr>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Due Payment :</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>
                                   <span class="badge badge-danger badge-pill">(RM) {{number_format($order[0]->order_total - $total_paid, 2, '.', ',')}}</span></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    
                                </tr>
                                
                             	
                                
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Total</th>
                                    <th><span class="badge badge-success badge-pill" id="quantity_badge">{{$total_quantity}}</span><input type="hidden" name="total_item_quantity" id="total_item_quantity" value="{{$total_quantity}}" /><input type="hidden" name="get_amount_penalty" value="{{$order[0]->amount_penalty}}" id="get_amount_penalty" /></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><span class="badge badge-success badge-pill" id="final_total">(RM) {{$order[0]->order_total}}</span><input type="hidden" id="total_final_total" name="total_final_total"value="{{$order[0]->order_total}}" /></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    
                                </tr>
                            </tbody>
                        </table>
                   
                </div>
            </div> 
            
            
          </div>
    </div>
</div>
</div>

               
