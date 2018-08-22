
	<table style="width: 100%;border-collapse: collapse;max-width: 100%; display: inline-table; font-size: 13px" cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th style="border: 1px solid #000; text-align:left;padding: 5px;font-size: 13px">Sequence No</th>
							<th style="border: 1px solid #000; text-align:left;padding: 5px;font-size: 13px">Owner Name</th>
							<th style="border: 1px solid #000; text-align:left;padding: 5px;font-size: 13px">Door No</th>
							<th style="border: 1px solid #000; text-align:left;padding: 5px;font-size: 13px">Address</th>
							<th style="border: 1px solid #000; text-align:left;padding: 5px;font-size: 13px">Meter No</th>
							<th style="border: 1px solid #000; text-align:left;padding: 5px;font-size: 13px">Phone</th>
							<th style="border: 1px solid #000; text-align:left;padding: 5px;font-size: 13px">Connection Type</th>
							<th style="border: 1px solid #000; text-align:left;padding: 5px;font-size: 13px">No Of Flats</th>
							<th style="border: 1px solid #000; text-align:left;padding: 5px;font-size: 13px">Corp Ward</th>
							<th style="border: 1px solid #000; text-align:left;padding: 5px;font-size: 13px">Connection Status</th>
							<th style="border: 1px solid #000; text-align:left;padding: 5px;font-size: 13px">Balance</th>
						</tr>
					</thead>
					
			
        <tbody>
                    @foreach($corp_ward_report_array as $key => $values)
                <tr>
                        <td style="border: 1px solid #000; padding: 5px;font-size: 13px">{{ $values['sequence_number'] }}</td>
                        <td style="border: 1px solid #000; padding: 5px;font-size: 13px">{{ $values['consumer_name'] }}</td>
                        <td style="border: 1px solid #000; padding: 5px;font-size: 13px">{{ $values['door_no'] }}</td>
                        <td style="border: 1px solid #000; padding: 5px;font-size: 13px">{{ $values['comm_address'] }}</td>
                         <td style="border: 1px solid #000; padding: 5px;font-size: 13px">{{ $values['meter_no'] }}</td>
                        <td style="border: 1px solid #000; padding: 5px;font-size: 13px">{{ $values['comm_mobile_no'] }}</td>
                        <td style="border: 1px solid #000; padding: 5px;font-size: 13px">{{ $values['connection_name'] }}</td>
                        <td style="border: 1px solid #000; padding: 5px;font-size: 13px">{{ $values['no_of_flats'] }}</td>
                         <td style="border: 1px solid #000; padding: 5px;font-size: 13px">{{ $values['corp_name'] }}</td>
                        <td style="border: 1px solid #000; padding: 5px;font-size: 13px">{{ $values['status'] }}</td>
                        <td style="border: 1px solid #000; padding: 5px;font-size: 13px">{{ $values['total_amount'] }}</td>
                      
                </tr>
                  @endforeach  

        </tbody>
</table>


    
            
            
            