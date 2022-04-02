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
          <h3>Refund History</h3>
          <p><?php echo date("m-d-Y"); ?></p>
        </div>
        <div class="card-box table-responsive">
             <table id="datatable" class="table table-striped table-bordered table-hover table-sm" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>SN.</th>
                                    <th>Customer Name</th>
                                    <th>Order#</th>
                                    <th>Created By</th>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            	<?php $i=1;
								// print_r($refunds_payment_list);
								
								 ?>
							    @foreach($refunds_payment_list as $refunds_payment_list)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{$refunds_payment_list->customer_full_name}}({{$refunds_payment_list->customer_uniq_id}})</td>
                                    <td>{{$refunds_payment_list->order_code}}</td>
                                    <td>{{$refunds_payment_list->created_by}}</td>
                                    <td>
                                   {{date('d-m-Y', strtotime($refunds_payment_list->created_at))}}
                                                                        
                                    </td>
                                    <td>{{$refunds_payment_list->description}}</td>
                                    <td>RM <?php echo number_format($refunds_payment_list->amount,2) ?></td>
                                   <td>
                                   @if($refunds_payment_list->refund_status==0)
                                   <a>Un-Verified</a>
                                   @elseif($refunds_payment_list->refund_status==1)
                                   <a style="color:green;">Verified</a>
                                   @elseif($refunds_payment_list->refund_status==2)
                                   <a style="color:red;">Decline</a>
                                   @else
                                   @endif  
                                    </td>
                                </tr>
                                <?php $i++; ?>
                                @endforeach
                            </tbody>
                        </table>
        </div>
     </div>
  </body>
</html>