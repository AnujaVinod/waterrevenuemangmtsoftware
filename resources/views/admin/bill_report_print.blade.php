<style>
    .amount_align
    {
        text-align: right;
    }
  td[colspan="18"] {
    text-align: center;
}

</style>
<table  class="table table-responsive  table-hover table-striped" border="1" style="width:2075px;">
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
                                                                @if($datacheck==0)
                                                                
                                                                <th>OBA<br><small>(Opening Balance)</small></th>
								<th>CBA<br><small>(Closing Balance)</small></th>
								<th>Paid Amount</th>
								<th>Pay Date</th>
								<th>Payment Number</th>
                                                                @else
                                                                <th>Total</th>
                                                                @endif
						
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
       
        
            foreach($report_search_data['meter_reading'] as $key => $values )
            {
               if($datacheck==0)
               {   
                $values =(array)$values;
               }
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
                @if($datacheck==0)
				
			 <?php $oba = Helper::calculateOba($values['basic']['sequence_number'],$value1['id']);
				
				?> 	
                     <td> {{ $oba }}</td> 
                
			  <!-- <td> {{ $value1['total_amount']-$value1['paid_amount'] }}</td> -->
              @if($value1['advance_amount']>'0')
				 @if($value1['paid_amount'] > '0')
			   <td>{{ (-$value1['advance_amount']+(-$value1['paid_amount']))}}</td>
				@else
				  <td>{{ $value1['extra_amount']+(-$value1['advance_amount'])}}</td>
				  @endif
			@elseif($value1['bill_no']=='' && $value1['total_amount'] >'0')	
				<td>{{ $value1['total_amount']-$value1['paid_amount'] }}</td> 
			   @elseif($value1['payment_status']=='0' && $value1['total_amount'] > '0')	
			   <td>{{ $oba+$value1['demand']}}</td> 
			   @elseif($value1['payment_status']=='1' && $value1['import_flag'] == '1' && $value1['advance_amount']>'0')
			   <td>{{ $value1['extra_amount']+(-$value1['advance_amount']) }}</td>
			 @elseif($value1['payment_status']=='1' && $value1['import_flag'] == '1' && $value1['active_record'] ==1)
			  <td>{{ $value1['extra_amount']}}</td>
		     @elseif($value1['payment_status']=='1' && $value1['import_flag'] == '0' && $value1['active_record'] ==1)
                         <td>  {{  $oba+$value1['demand']}}</td> 
				  @else		
			   <td> {{ ($value1['water_charge']+$value1['other_charges']+$value1['penalty'])-$value1['paid_amount']}}</td> 
				  @endif
               <td> {{ $value1['paid_amount'] }}</td> 
               <td> {{ $value1['payment_date'] }}</td> 
               <td> {{ $value1['transaction_number'] }}</td> 
               @else
                <td> {{ $value1['total_amount'] }}</td> 
               @endif
               </tr>  
               
                 @else
		<?php  $oba = Helper::calculateOba($values['basic']['sequence_number'],$value1['id']);
				
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
                @if($datacheck==0)
                <td> {{ $oba }}</td>  
			  <!--<td> {{ $value1['total_amount']-$value1['paid_amount'] }}</td> -->
			  @if($value1['advance_amount']>'0')
				 @if($value1['paid_amount'] > '0')
			   <td>{{ (-$value1['advance_amount']+(-$value1['paid_amount']))}}</td>
				@else
				  <td>{{ $value1['extra_amount']+(-$value1['advance_amount'])}}</td>
				  @endif
			@elseif($value1['bill_no']=='' && $value1['total_amount'] >'0')	
                            <td> {{ $value1['total_amount']-$value1['paid_amount'] }}</td> 
                        @elseif($value1['payment_status']=='0' && $value1['total_amount'] > '0')	
			   <td> {{ $oba+$value1['demand']}}</td> 
                        @elseif($value1['payment_status']=='1' && $value1['import_flag'] == '1' && $value1['advance_amount']>'0')
		 	   <td>{{ $value1['extra_amount']+(-$value1['advance_amount']) }}</td>
			@elseif($value1['payment_status']=='1' && $value1['import_flag'] == '1' && $value1['active_record'] ==1)
			  <td>{{ $value1['extra_amount']}}</td>
			 @elseif($value1['payment_status']=='1' && $value1['import_flag'] == '0' && $value1['active_record'] ==1)
                         <td>  {{  $oba+$value1['demand']}}</td> 
                        @else		
			   <td>{{ ($value1['water_charge']+$value1['other_charges']+$value1['penalty'])-$value1['paid_amount']}}</td> 
			@endif
            
				 <!--<td> {{ $value1['total_amount']+$value1['extra_amount']+(-$value1['water_charge'])+(-$value1['other_charges'])}}</td> -->
		
            
                <td> {{ $value1['paid_amount'] }}</td> 
                <td> {{ $value1['payment_date'] }}</td> 
                <td> {{ $value1['transaction_number'] }}</td> 
                @else
                 <td> {{ $value1['total_amount'] }}</td> 
                @endif
               </tr>
               @endif

               @endforeach  

             @endforeach
    </tbody>
    @endif
</table>

@if($datacheck==1)
<table class="table table-responsive  table-hover table-striped" border="1" style="margin-top:20px;width:2075px;" >
    <thead>
            	<tr>
								
								<th>Payment Date</th>
								<th>Total Amount</th>
								<th>Transaction Number</th>
                                                                <th>Bank Name</th>
                                                                <th>Branch Name</th>
								
					
							</tr>
    </thead>
    @if(count($report_search_data['old_pay_data'])==0)
    <tbody><tr class="odd">  <td colspan="18">No data available in table</td></tr></tbody>
    @else
    
    <tbody>
  
           
            
             @foreach($report_search_data['old_pay_data'] as $key1 => $value1)
               <tr>
             			
              
            
                 
                 
            
                <td> {{ $value1['payment_date'] }}</td> 
                <td> {{ $value1['total_amount'] }}</td> 
                <td> {{ $value1['transaction_number'] }}</td>          
                <td> {{ $value1['bank_name'] }}</td> 
                <td> {{ $value1['branch_name'] }}</td>          
               </tr>  
               
               </tr>
              
               
             @endforeach
    </tbody>
    @endif
</table>
@endif
