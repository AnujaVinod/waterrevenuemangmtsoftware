
<table  class="table table-responsive table-bordered table-hover table-striped" border="1">
        <thead>
                <tr>
                        <th>Connection Type</th>
                        <th>Live</th>
                        <th>Disconnection</th>
                        <th>Total</th>
                </tr>
        </thead>
        <tbody>
                    @foreach($connection_report_array as $key => $values)
                <tr>
                        <td>{{ $values['connection_name'] }}</td>
                        <td>{{ $values['live'] }}</td>
                        <td>{{ $values['disconnected'] }}</td>
                        <td>{{ $values['total'] }}</td>
                </tr>
                  @endforeach  

        </tbody>
</table>


    
            
            
            