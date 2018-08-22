
		<table id="dcb_report_month" border="1">
				<thead>
							<tr>
								<th>Ward No</th>
								<th>Tariff</th>
								<th>Total INST</th>
								<th>Live</th>
								<th>Billed</th>
								<th>Consumption</th>
								<th>OB</th>
								<th>WC</th>
								<th>OC</th>
								<th>Penalty</th>
								<th>Demand</th>
								<th>Collection</th>
								<th>CB</th>
							</tr>
						</thead>
					
			
        <tbody>
                    @foreach($dcb_report_array as $key => $values)
                <tr>
                        <td>{{ $values['ward_name'] }}</td>
                        <td>{{ $values['connection_name'] }}</td>
                        <td>{{ $values['total_installation'] }}</td>
                        <td>{{ $values['old_balance'] }}</td>
                         <td>{{ $values['bill_count'] }}</td>
                        <td>{{ $values['total_unit_used'] }}</td>
                        <td>{{ $values['old_balance'] }}</td>
                        <td>{{ $values['water_charge'] }}</td>
                        <td>{{ $values['other_charges'] }}</td>
                        <td>{{ $values['penalty'] }}</td>
                        <td>{{ $values['demand'] }}</td>
                        <td>{{ $values['collection'] }}</td>
                         <td>{{ $values['current_balance'] }}</td>
                      
                </tr>
                  @endforeach  

        </tbody>
</table>


    
            
            
            