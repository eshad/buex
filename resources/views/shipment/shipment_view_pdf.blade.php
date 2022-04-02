<!DOCTYPE html>
<html>
  <head>
   <style>
@page {
  size: auto;  /* auto is the initial value */
  margin: 0mm; /* this affects the margin in the printer settings */
}
html {
  background-color: #FFFFFF;
  margin: 0px; /* this affects the margin on the HTML before sending to printer */
}
body {
    border: solid 1px #FFFFFF;
   margin: 10mm 15mm 10mm 15mm; /* margin you want for the content */
}
table {
    border-collapse: collapse;
}

table, th, td {
    border: 1px solid black;
}

</style>
  </head>
  <body>
     <div class="col-12">
		<div style="text-align:center;">
          <h3>Shipment View History</h3>
          <p>Shipment:-   <?php echo $shipment_number; ?></p>
          <p><?php echo date("m-d-Y"); ?></p>
        </div>
        <div class="table-responsive">
                                <table id="shipment" class="table table-striped table-bordered table-hover table-sm w-100" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th align="center">Sno</th>
                                            <th align="center">Image</th>
                                            <th align="center">Item Code</th>
                                            <th align="center">Item Name</th>
                                            <th align="center">Ship Qty.</th>
                                          
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php $i=1;?>
									@foreach($shipment_data as $shipment)
										<?php 
										$images = DB::table('images')->where('source_id',$shipment->item_id)->where('source_type','product')->first();
										?>
                                        <tr id="{{$shipment->id}}" align="center">
                                        <td>{{$i}}</td>
										<td align="center"><img src="{{asset('public/product_image/thumbnail_images')}}/{{$images->thumb_image_name}}" alt="" width="100"></td>
											
                                            <td align="center">{{$shipment->item_uniq_id}}</td>
											
                                            <td align="center">{{$shipment->product_name}}</td>
											
                                            <td align="center">{{$shipment->shipment_quantity}}</td>
										</tr>
                                        <?php
                                        $i++;
                                        ?>
									@endforeach
                                    </tbody>
                                </table>
            </div>
     </div>
  </body>
</html>