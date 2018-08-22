<table style="width: 100%;border-collapse: collapse;max-width: 100%; display: inline-table; " cellpadding="0" cellspacing="0">
    <thead>
    <th colspan="11" style="text-align: center; font-weight: bold; text-transform: uppercase">Corp Wardwise Pending Balance Report</th>
    <th colspan="11"></th>
    </thead>
</table>
	<table style="width: 100%;border-collapse: collapse;max-width: 100%; display: inline-table; " cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th style="border: 1px solid #000; text-align:left;padding: 5px;width: 15;">Sequence No</th>
							<th style="border: 1px solid #000; text-align:left;padding: 5px;width: 35;">Owner Name</th>
							<th style="border: 1px solid #000; text-align:left;padding: 5px;width: 25;">Door No</th>
							<th style="border: 1px solid #000; text-align:left;padding: 5px;width: 20;">Address</th>
							<th style="border: 1px solid #000; text-align:left;padding: 5px;width: 20;">Meter No</th>
							<th style="border: 1px solid #000; text-align:left;padding: 5px;width: 20;">Phone</th>
							<th style="border: 1px solid #000; text-align:left;padding: 5px;width: 20;">Connection Type</th>
							<th style="border: 1px solid #000; text-align:left;padding: 5px;width: 20;">No Of Flats</th>
							<th style="border: 1px solid #000; text-align:left;padding: 5px;width: 25;">Corp Ward</th>
							<th style="border: 1px solid #000; text-align:left;padding: 5px;width: 20;">Connection Status</th>
							<th style="border: 1px solid #000; text-align:left;padding: 5px;width: 20;">Balance</th>
						</tr>
					</thead>
					
			
        <tbody>
                    @foreach($corp_ward_report_array as $key => $values)
                <tr>
                        <td style="border: 1px solid #000; padding: 5px;">{{ $values['sequence_number'] }}</td>
                        <td style="border: 1px solid #000; padding: 5px;">{{ $values['consumer_name'] }}</td>
                        <td style="border: 1px solid #000; padding: 5px;">{{ $values['door_no'] }}</td>
                        <td style="border: 1px solid #000; padding: 5px;">{{ $values['comm_address'] }}</td>
                         <td style="border: 1px solid #000; padding: 5px;">{{ $values['meter_no'] }}</td>
                        <td style="border: 1px solid #000; padding: 5px;">{{ $values['comm_mobile_no'] }}</td>
                        <td style="border: 1px solid #000; padding: 5px;">{{ $values['connection_name'] }}</td>
                        <td style="border: 1px solid #000; padding: 5px;">{{ $values['no_of_flats'] }}</td>
                         <td style="border: 1px solid #000; padding: 5px;">{{ $values['corp_name'] }}</td>
                        <td style="border: 1px solid #000; padding: 5px;">{{ $values['status'] }}</td>
                        <td style="border: 1px solid #000; padding: 5px; text-align: right">{{ $values['total_amount'] }}</td>
                      
                </tr>
                  @endforeach  

        </tbody>
</table>


    
            
            
            