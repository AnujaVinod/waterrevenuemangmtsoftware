<style>
    .amount_align
    {
        text-align: right;
    }
  td[colspan="18"] {
    text-align: center;
}

</style>
<table  class="table table-responsive  table-hover table-striped" border="1" style="">
    <thead>
            	<tr>
								<th>Sequence Number</th>
								<th>Consumer Name</th>
								<th>Connection Type</th>
								<th>Door No</th>
								<th>Date of Reading</th>
								<th>Bill No</th>
								<th>Previous Reading</th>
								<th>Current Reading</th>
								<th>Total Amount</th>
								<th>Corp Ward</th>
								<th>Agent Name</th>
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
           
           //echo print_r($report_search_data['meter_reading']); exit()
            ?>
        
            @foreach($report_search_data['meter_reading'] as $key => $values ) 
            <tr>
                
               
                
                <td style="width: 20"> {{ $values['sequence_number'] }}</td> 
               <td style="width: 20"> {{ $values['name'] }}</td> 
               <td style="width: 20"> {{ $values['connection_name'] }}</td> 
               <td style="width: 15"> {{ $values['door_no'] }}</td> 
               <td style="width: 15">@if($values['date_of_reading']==!null) {{ date("d-m-Y",strtotime($values['date_of_reading']))}}  @endif</td> 
               <td style="width: 15"> {{ $values['bill_no'] }}</td> 
               <td style="width: 20"> {{ $values['previous_reading'] }}</td> 
               <td style="width: 20"> {{ $values['current_reading'] }}</td> 
               <td style="width: 20"> {{ $values['total_amount'] }}</td> 
               <td style="width: 20"> {{ $values['ward_name'] }}</td> 
               <td style="width: 15"> {{ $values['agent_name'] }}</td>
               
                     
            </tr>
                
            @endforeach
            
            

           
				
            
              
               </tr>  
               
           
               
                

             
    </tbody>
    @endif
</table>

@if($datacheck==1)
<table class="table table-responsive  table-hover table-striped" border="1" style="margin-top:20px;" >
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
             			
              
            
                 
                 
            
                   <td style="width: 20"> {{ $value1['payment_date'] }}</td> 
                <td style="width: 20"> {{ $value1['total_amount'] }}</td> 
                <td style="width: 20"> {{ $value1['transaction_number'] }}</td>          
                <td style="width: 20"> {{ $value1['bank_name'] }}</td> 
                <td style="width: 20"> {{ $value1['branch_name'] }}</td>          
               </tr>  
               
               </tr>
              
               
             @endforeach
    </tbody>
    @endif
</table>
@endif
