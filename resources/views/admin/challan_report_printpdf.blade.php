<table id="dcb_report_month" width="100%">
    <thead>
        <tr>
            <th style="border:1px #000 solid;padding: 5px">SL No</th>            
            <th style="border:1px #000 solid;padding: 5px">BANK NAME</th>            
            <th style="border:1px #000 solid;padding: 5px">BRANCH NAME</th>
            <th style="border:1px #000 solid;padding: 5px">PAYMENT DATE</th>            
            <th style="border:1px #000 solid;padding: 5px">TOTAL AMOUNT</th>            
        </tr>
    </thead>
    <tbody>
        @if(!empty($data))
        @foreach($data as $key => $values)
        <tr>
            <td style="border:1px #000 solid;padding: 5px">{{ $values['rowNumber'] }}</td>         
            <td style="border:1px #000 solid;padding: 5px">{{ $values['bank_name'] }}</td>
            <td style="border:1px #000 solid;padding: 5px">{{ $values['branch_name'] }}</td>
            <td style="border:1px #000 solid;padding: 5px">{{ date("d-m-Y",strtotime($values['payment_date'])) }}</td>        
            <td style="border:1px #000 solid;padding: 5px">{{ $values['total_collection'] }}</td>          
        </tr>
        @endforeach  
        @endif
    </tbody>
</table>

<table id="total" width="100%">
    <tbody>
        @if(!empty($branchWiseCollection))
        @foreach($branchWiseCollection as $value)
        <tr>
            <td style="padding: 5px;width:20%"></td>         
            <td style="padding: 5px;width:20%"></td>
            <td style="padding: 5px;width:20%"></td>
            <td style="padding: 5px;width:20%">{{$value['branch_name']}}</td>        
            <td style="padding: 5px;width:20%">{{$value['total_collection']}}</td>          
        </tr>
        @endforeach  
        <tr>
            <td style="padding: 5px;width:20%"></td>         
            <td style="padding: 5px;width:20%"></td>
            <td style="padding: 5px;width:20%"></td>
            <td style="padding: 5px;width:20%">GRAND TOTAL</td>        
            <td style="padding: 5px;width:20%">{{$total_collection}}</td>          
        </tr>
        @endif
    </tbody>
</table>











