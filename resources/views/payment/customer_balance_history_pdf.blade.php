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
          <h3>Customer Balance History</h3>
          <p><?php echo date("m-d-Y"); ?></p>
        </div>
        <div class="card-box table-responsive">
              <table border="1"  class="table table-striped table-bordered table-hover table-sm" style="width: 100%;">               <thead>
                    <tr>
                        <th>SN.</th>
                        <th>Created By</th>
                        <th>Transaction</th>
                        <th>Date</th>
                        <th>Qty.</th>
                        <th>Total</th>
                        <th>Balance</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $n = 0; 
                    //print_r($trans_histories_list);
                    
                    ?>  
                   
                    @foreach($trans_histories_list as $trans_histories)
                    <tr> 
                        <td>{{$n+1}}
                        </td>
                        <td>{{$trans_histories['created_by']}}</td>
                        <th>
                       
                              {{$trans_histories['trans_name']}}
                        
                        @if($trans_histories['trans_type']=='order')
                            <br> <span>(Order Placed)</span>
                        @elseif($trans_histories['trans_type']=='payment')
                            <br> <span>(Payment)</span>
                        @elseif($trans_histories['trans_type']=='cancel')
                            <br> <span style="color:red">(Cancel)</span>
                        @elseif($trans_histories['trans_type']=='refund')
                            <br> <span style="color:red">(Refund)</span>
                        @elseif($trans_histories['trans_type']=='penalty')
                             <br> <span style="color:red">(Penalty)</span>
                        @endif
                        
                        
                        </th>
                        <td>
                        {{date('d-m-Y', strtotime($trans_histories['trans_date']))}}
                       
                        
                        
                        </td>
                        <td>{{$trans_histories['quantity']}}</td>
                        <td>
                        @if($trans_histories['trans_type']=='order')
                             <p>RM {{$trans_histories['total']}}</p>
                        @elseif($trans_histories['trans_type']=='payment')
                             <p style="color:red">RM -{{$trans_histories['total']}} </p>
                        @else
                             RM {{$trans_histories['total']}}
                        @endif
                        
                        </td>
                        <td>
                        @if($trans_histories['trans_type']=='order')
                            <p>RM  {{number_format($trans_histories['balance'], 2) }}</p>
                        @elseif($trans_histories['trans_type']=='payment')
                            <p style="color:red">RM  {{number_format($trans_histories['balance'], 2) }}</p>
                        @elseif($trans_histories['trans_type']=='cancel')
                            <p style="color:red">RM  {{number_format($trans_histories['balance'], 2) }}</p> 
                       @elseif($trans_histories['trans_type']=='refund')
                            <p style="color:red">RM  {{number_format($trans_histories['balance'], 2) }}</p>   
                        @else
                             <p>RM  {{number_format($trans_histories['balance'], 2) }}</p>
                        @endif
                        
                        </td>
                        <td class="text-success"><p>{{$trans_histories['verified']}}</p></td>
                      
                    </tr>
                    
                    <?php $n++; ?>  
                    @endforeach   
                </tbody>
            </table>
        </div>
     </div>
  </body>
</html>