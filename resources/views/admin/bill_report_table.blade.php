<style>;
    .amount_align
    {
        text-align: right;
    }
  td[colspan="18"] {
    text-align: center;
}
.pagination
{
    float:right;    
}
b.fontsize
{
    font-size: 12px;
}
span.fontdata
{
     font-size: 12px;
}

</style>

<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
   <div class="dataTables_length" id="bill_report_month_length" style="margin:10px;" >
         <label>Show <select name="bill_report_month_length" id="bill_report_month_length" aria-controls="dcb_report_month" class="">
               <option value="10">10</option><option value="25">25</option><option value="50">50</option>
                 <option value="100">100</option></select> entries</label>
<input type="hidden" id="listcount" value="{{ $list_count }}">
<input type="hidden" id="keysearch" value="{{ $keysearch }}">
<input type="hidden" id="result" value="{{ $result }}">
<div id="searchbox"style="float:right;">
    <label>Search:<input type="search" id="report_search"  class="" placeholder="" aria-controls="connection_wise_reporttable" ></label>
</div>
   </div>
<table id="consumer_billing_report_table" class="table table-responsive table-bordered table-hover table-striped" border="1" style="width:2075px;">
    <thead>
            	<tr>
								<th>Owner Details</th>
								<th>Reading-Y/M</th>
								<th>Previous Reading Date</th>
								<th>Bill Date</th>
								<th>Previous Reading</th>
								<th>Current reading</th>
								<th>Consumption</th>
								<th>Water Charges</th>
								<th>Other Charges</th>
								<th>Penalty</th>
								<th>Status</th>
								<th>Bill No</th>
								<th>Advance Amount</th>
								<th>Demand</th>
                                <th>OBA<br><small>(Opening Balance)</small></th>
								<th>CBA<br><small>(Closing Balance)</small></th>
								<th>Paid Amount</th>
								<th>Pay Date</th>
								<th>Payment Number</th>
						
							</tr>
    </thead>
    @if($result==0)
    <tbody><tr class="odd">  <td colspan="18">No data available in table</td></tr></tbody>
    @else
    
    <tbody>
        <?php
       
         $myNewArray = [];

           $i = 0;
           $j=0;
          
            foreach($report_search_data as $key => $values )
            {
                $values =(array)$values;
            
                $seqNumber = $values['sequence_number'];

                if($j  == 0)
                {
                    $myNewArray[$i]['basic']['name'] = $values['name'];
                    $myNewArray[$i]['basic']['sequence_number'] = $values['sequence_number'];
                    $myNewArray[$i]['basic']['connection_date'] = $values['connection_date'];
                    $myNewArray[$i]['basic']['comm_address'] = $values['premises_address'];
                    $myNewArray[$i]['basic']['meter_no'] = $values['meter_no'];
                    $myNewArray[$i]['basic']['connection_name'] = $values['connection_name'];
                    $myNewArray[$i]['basic']['ward_name'] = $values['ward_name'];
                    $myNewArray[$i]['basic']['corp_name'] = $values['corp_name'];
                    $get_agent_name = Helper::get_agent_name($values['sequence_number']);
                  
                    $myNewArray[$i]['basic']['agent_name'] = $get_agent_name;
                    $myNewArray[$i]['details'][] = $values;    
                    $j++;
                }
                else if($myNewArray[$i]['basic']['sequence_number'] == $values['sequence_number'])
                {

                    $myNewArray[$i]['details'][] = $values;
                }
                else
                {
                    $i++;
                    $myNewArray[$i]['basic']['name'] = $values['name'];
                    $myNewArray[$i]['basic']['sequence_number'] = $values['sequence_number'];
                    $myNewArray[$i]['basic']['connection_date'] = $values['connection_date'];
                    $myNewArray[$i]['basic']['comm_address'] = $values['premises_address'];
                    $myNewArray[$i]['basic']['meter_no'] = $values['meter_no'];
                    $myNewArray[$i]['basic']['connection_name'] = $values['connection_name'];
                    $myNewArray[$i]['basic']['ward_name'] = $values['ward_name'];
                    $myNewArray[$i]['basic']['corp_name'] = $values['corp_name'];
                    $get_agent_name = Helper::get_agent_name($values['sequence_number']);       
                    $myNewArray[$i]['basic']['agent_name'] = $get_agent_name;
                    $myNewArray[$i]['details'][] = $values;   

                } 

            }
            ?>

            @foreach($myNewArray as $key => $values)
            
             
               <tr>
                  <td width="200" rowspan="<?php echo count($values['details']);?>" class="vert-top">
                      <b class="fontsize">Owner </b>:   <span class="fontdata">{{  $values['basic']['name'] }}</span> <br><hr>
                        <b class="fontsize">Connection Date</b> :    {{  $values['basic']['connection_date'] }}
                        <hr>
                        <b class="fontsize">Address </b>: <span class="fontdata"> {{  $values['basic']['comm_address'] }} </span> 

                        <hr>
                        <b class="fontsize">Meter No</b> : <span class="fontdata">  {{  $values['basic']['meter_no'] }} </span>
                        <hr>
                        <b class="fontsize">Tariff</b> : <span class="fontdata">  {{  $values['basic']['connection_name'] }} </span>
                        <hr>
                        <b class="fontsize">Ward</b> :  <span class="fontdata"> {{  $values['basic']['ward_name'] }} </span>
                        <hr>
                        <b class="fontsize">Corp Ward</b> : <span class="fontdata">  {{  $values['basic']['corp_name'] }} </span>
                        <hr>
                        <b class="fontsize">Agent</b> : <span class="fontdata">  {{  $values['basic']['agent_name'] }} </span>
                        <hr>
                        <b class="fontsize">Sequence Number</b> : <span class="fontdata">  {{  $values['basic']['sequence_number'] }} </span>
                </td>
				
              @foreach($values['details'] as $key1 => $value1)
            
                  @if ($key1 == 0) 
                 
                <td> {{ $value1['year'] }} / {{$value1['month'] }}</td> 
                <td> {{ $value1['previous_billing_date'] }}</td> 
                <td> {{ $value1['date_of_reading'] }}</td> 
                <td> {{ $value1['previous_reading'] }}</td> 
                <td> {{ $value1['current_reading'] }}</td> 
                <td> {{ $value1['total_unit_used'] }}</td> 
                <td> {{ $value1['water_charge'] }}</td> 
                <td> {{ $value1['other_charges'] }}</td> 
                <td> {{ $value1['penalty'] }}</td> 
			
                <td> {{ $value1['meter_status'] }}</td> 
                <td> {{ $value1['bill_no'] }}</td>
                <td> {{ $value1['advance_amount'] }}</td>
                @if($value1['bill_no']=='')	
                    <td> {{ $value1['total_amount'] }}</td> 
                @else
                    <td> {{ $value1['demand'] }}</td> 
                @endif				
              <!--  <td> {{ $value1['total_amount']+$value1['extra_amount']}}</td> 	-->
                @if($datacheck==1)
                    <td> {{ 0 }}</td> 
                @else
                    <?php $oba = Helper::calculateOba($values['basic']['sequence_number'],$value1['id']);?> 	
                    <td> {{ $oba }}</td> 
                @endif
                
                
                <td>
                    <!-- closing balance -->
                    <?php
                        $demand = $value1['demand'];
                        if($value1['bill_no']==''){
                            $demand = $value1['total_amount']-$value1['advance_amount'];
                        }
                        echo round($oba+$demand)-$value1['paid_amount'];
                    ?>
                    
                </td>
			 
                
                                  
               <td> {{ $value1['paid_amount'] }}</td> 
               <td> {{ $value1['payment_date'] }}</td> 
               <td> {{ $value1['transaction_number'] }}</td> 

               </tr>  
                 @else
		<?php $oba = Helper::calculateOba($values['basic']['sequence_number'],$value1['id']);
				
				?> 
               <tr>
                <td> {{ $value1['year'] }} /  {{ $value1['month'] }}</td> 
                <td> {{ $value1['previous_billing_date'] }}</td> 
                <td> {{ $value1['date_of_reading'] }}</td> 
                <td> {{ $value1['previous_reading'] }}</td> 
                <td> {{ $value1['current_reading'] }}</td> 
                <td> {{ $value1['total_unit_used'] }}</td> 
                <td> {{ $value1['water_charge'] }}</td> 
                <td> {{ $value1['other_charges'] }}</td> 
                <td> {{ $value1['penalty'] }}</td> 
               
                <td> {{ $value1['meter_status'] }}</td> 
                <td> {{ $value1['bill_no'] }}</td> 
		<td> {{ $value1['advance_amount'] }}</td>
				 @if($value1['bill_no']=='')	
				 <td> {{ $value1['total_amount'] }}</td> 
				 @else
                <td> {{ $value1['demand'] }}</td> 
				@endif
                <td> {{ $oba }}</td>  

                <td>
                    <!-- closing balance -->
                    <?php
                        $demand = $value1['demand'];
                        if($value1['bill_no']==''){
                            $demand = $value1['total_amount']-$value1['advance_amount'];
                        }
                        //dd($oba);
                        //dd($demand);
                        //dd($value1['paid_amount']);
                        try {
                             echo ($oba+$demand)-$value1['paid_amount'];
                              //echo "==".$oba." ".$demand." ".$value1['paid_amount'];
                        }
                        catch (Exception $e){
                            echo $oba." ".$demand." ".$value1['paid_amount'];
                            //dd($e);
                        }
                    ?>
                    
                </td>
		
                <td> {{ $value1['paid_amount'] }}</td> 
                <td> {{ $value1['payment_date'] }}</td> 
                <td> {{ $value1['transaction_number'] }}</td> 
               </tr>
               @endif

               @endforeach  

             @endforeach
    </tbody>
    @endif
</table>
  @if($result==1)
  {{ $report_search_data->links('pagination.default') }}

@endif
     <script>
         var resultval=$('#result').val();
    $("#bill_report_month_length").change(function(){
       
      var datacheck = $("input[name='optradio']:checked").val();
      var sequence_number=$("#sequence_number").val();
      var meter_no=$("#meter_no").val();
      var ward=$('select[name=ward_id]').val();
      var corp_ward_id=$('select[name=corp_ward_id]').val();
      var con_type= $('select[name=conn_type]').val();
      var from_date= $('#datepicker_billing_report_from').val();
      var to_date= $('#datepicker_billing_report_to').val();
      var length=$('select[name=bill_report_month_length]').val();
      var search_key = $("#report_search").val();
      if(sequence_number=='')
      {
         var sequence_number='0';
      }
      if(meter_no=='')
      {
            var meter_no='0';
      }
      if(ward=='')
      {
           var ward='0';
      }
      if(con_type=='')
      {
          var con_type='0';
      }
      if(corp_ward_id=='')
      {
            var corp_ward_id='0';
      }
      
    
            $.ajax({
                        url:siteUrl + "/admin/billing_report_search",
                        type: 'POST',
                        data: {
                                    seq_number: sequence_number,
                                    meter_no: meter_no,
                                    ward:ward,
                                    corp_ward:corp_ward_id,
                                    con_type:con_type,
                                    from_date:from_date,
                                    to_date:to_date,
                                    page:1,
                                    length:length,
                                    search_key:search_key,
                                    datacheck:datacheck,
                               },
                        async: true,

                        success: function (data) {

                                        $('#datable_div').html(data);
                                               }
                      });
});
    
     $(window).on('hashchange', function() {
        if (window.location.hash) {
            var page = window.location.hash.replace('#', '');
            if (page == Number.NaN || page <= 0) {
                return false;
            } else {
                getPosts(page);
            }
        }
    });
    $(document).ready(function() {
        var show_count=$('#listcount').val();
        var searchkey=$('#keysearch').val();
       $("#bill_report_month_length option[value="+show_count+"]").prop('selected', true);
        $("#report_search").val(searchkey);
      
        $("#report_search").keyup(function(){
      var datacheck = $("input[name='optradio']:checked").val();
      var search_key = $("#report_search").val();
      var sequence_number=$("#sequence_number").val();
      var meter_no=$("#meter_no").val();
      var ward=$('select[name=ward_id]').val();
      var corp_ward_id=$('select[name=corp_ward_id]').val();
      var con_type= $('select[name=conn_type]').val();
      var from_date= $('#datepicker_billing_report_from').val();
      var to_date= $('#datepicker_billing_report_to').val();
      var length=$('select[name=bill_report_month_length]').val();
      var page = window.location.hash.replace('#', '');
      if(sequence_number=='')
      {
         var sequence_number='0';
      }
      if(meter_no=='')
      {
            var meter_no='0';
      }
      if(ward=='')
      {
           var ward='0';
      }
      if(con_type=='')
      {
          var con_type='0';
      }
      if(corp_ward_id=='')
      {
            var corp_ward_id='0';
      }

            $.ajax({
                        url:siteUrl + "/admin/billing_report_search",
                        type: 'POST',
                        data: {
                                    seq_number: sequence_number,
                                    meter_no: meter_no,
                                    ward:ward,
                                    con_type:con_type,
                                    from_date:from_date,
                                    to_date:to_date,
                                    page:1,
                                    corp_ward:corp_ward_id,
                                    length:length,
                                    search_key:search_key,
                                    datacheck:datacheck,
                               },
                        async: true,

                        success: function (data) {

                                        $('#datable_div').html(data);
                                               }
                      });

       });
        
        $(document).on('click', '.pagination a', function (e) {
            getPosts($(this).attr('href').split('page=')[1]);
            e.preventDefault();
        });
        
    
    $("#report_search").keypress(function () {
       
      var datacheck = $("input[name='optradio']:checked").val();
      var page = window.location.hash.replace('#', '');
      var sequence_number=$("#sequence_number").val();
      var meter_no=$("#meter_no").val();
      var ward=$('select[name=ward_id]').val();
      var corp_ward_id=$('select[name=corp_ward_id]').val();
      var con_type= $('select[name=conn_type]').val();
      var from_date= $('#datepicker_billing_report_from').val();
      var to_date= $('#datepicker_billing_report_to').val();
      var length=$('select[name=bill_report_month_length]').val();
      var search_key = $("#report_search").val();
      if(sequence_number=='')
      {
         var sequence_number='0';
      }
      if(meter_no=='')
      {
            var meter_no='0';
      }
      if(ward=='')
      {
           var ward='0';
      }
      if(con_type=='')
      {
          var con_type='0';
      }
      if(corp_ward_id=='')
      {
            var corp_ward_id='0';
      }

         $.ajax({
                type: "POST",
                url:siteUrl + "/admin/billing_report_search",
               data: {
                                    seq_number: sequence_number,
                                    meter_no: meter_no,
                                    ward:ward,
                                    con_type:con_type,
                                    from_date:from_date,
                                    to_date:to_date,
                                    page:page,
                                    length:length,
                                    corp_ward:corp_ward_id,
                                    search_key:search_key,
                                    datacheck:datacheck,
                               },
                success: function(data){
                    //we need to check if the value is the same
                    $('#datable_div').html(data);
                   
                }
            });
        
         });
     });


    function getPosts(page)
    {
       
      var datacheck = $("input[name='optradio']:checked").val();
      var sequence_number=$("#sequence_number").val();
      var meter_no=$("#meter_no").val();
      var ward=$('select[name=ward_id]').val();
      var corp_ward_id=$('select[name=corp_ward_id]').val();
      var con_type= $('select[name=conn_type]').val();
      var from_date= $('#datepicker_billing_report_from').val();
      var to_date= $('#datepicker_billing_report_to').val();
      var length=$('select[name=bill_report_month_length]').val();
      var search_key = $("#report_search").val();
      var rowcount=$("#listcount").val(); 
      if(sequence_number=='')
      {
         var sequence_number='0';
      }
      if(meter_no=='')
      {
            var meter_no='0';
      }
      if(ward=='')
      {
           var ward='0';
      }
      if(con_type=='')
      {
          var con_type='0';
      }
      if(page==1)
      {
          var start_limit=1;
      }
      if(corp_ward_id=='')
      {
            var corp_ward_id='0';
      }
      else
      {
           var start_limit=parseInt(page-1)*rowcount;
      }

            $.ajax({
                        url:siteUrl + "/admin/billing_report_search",
                        type: 'POST',
                        data: {
                                    seq_number: sequence_number,
                                    meter_no: meter_no,
                                    ward:ward,
                                    con_type:con_type,
                                    from_date:from_date,
                                    to_date:to_date,
                                    page:page,
                                    length:length,
                                    search_key:search_key,
                                    start_limit:start_limit,
                                    corp_ward:corp_ward_id,
                                    datacheck:datacheck,
                               },
                        async: true,

                        success: function (data) {
                                        
                                        $('#datable_div').html(data);
                                               }
                      });
    }



$(function() {
    var data = $('#report_search').val();
    
    $('#report_search').focus().val('').val(data);
}); 

$(window).scroll(function(){
    $('#searchbox').css({
        'left': $(this).scrollLeft() + 15 //Why this 15, because in the CSS, we have set left 15, so as we scroll, we would want this to remain at 15px left
    });
});

</script>


  