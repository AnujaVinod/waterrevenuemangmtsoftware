<?php
//echo "<pre>";
  //  print_r($paymentArray); exit();
?>
    <table style="width: 100%;">
        <tbody>
            <tr>
                <td colspan='12'><h3 style="text-align: center">
                        @if($type==1)
                            MANGALORE CITY CORPORATION DCB REPORT (TARIFFWISE)
                        @else
                            MANGALORE CITY CORPORATION DCB REPORT (CORP WARDWISE)
                        @endif
                       
                    </h3>
                    </td></tr>
                    
               
                
        </tbody></table>
            
                    
                    <table>
                        <thead>
                            
                         
                            <tr>
                                
                                @if($type==1)
                                    <th style="border: 1px solid #000; width: 30">TARIFF</th>
                                @else
                                    <th style="border: 1px solid #000; width: 30">CORP WARD</th>
                                @endif
                        
                                <th style="border: 1px solid #000; width: 15">TOTAL INST</th>
                                <th style="border: 1px solid #000;width: 15; text-align:right">LIVE</th>
                                <th style="border: 1px solid #000;width: 15; text-align:right">BILLED</th>
                                <th style="border: 1px solid #000;width: 20; text-align:right">OPENING BALANCE</th>
                                <th style="border: 1px solid #000;width: 20; text-align:right">WATER CHARGES</th>
                                <th style="border: 1px solid #000;width: 20; text-align:right">SERVICE CHARGES</th>
                                 <th style="border: 1px solid #000;width: 15; text-align:right">REVISED</th>
                                  <th style="border: 1px solid #000;width: 15; text-align:right">PENALTY</th>
                                  <th style="border: 1px solid #000;width: 15; text-align:right">DEMAND</th>
                                  <th style="border: 1px solid #000;width: 15; text-align:right">COLLECTION</th>
                                  <th style="border: 1px solid #000;width: 20; text-align:right">CLOSING BALANCE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($paymentArray as $data)

                           
                          

                            <tr>
                                <td style="border: 1px solid #000;font-weight: bold">{{$data['name']}}</td>
                                <td style="border: 1px solid #000;">{{$data['total_installation']}}</td>
                                <td style="border: 1px solid #000;">{{$data['live']}}</td>
                                <td style="border: 1px solid #000;">{{$data['bill_count']}}</td>
                                <td style="border: 1px solid #000;">{{$data['old_balance']}}</td> 
                                <td style="border: 1px solid #000;">{{$data['water_charge']}}</td>
                                <td style="border: 1px solid #000;">{{$data['other_charges']}}</td>
                                <td style="border: 1px solid #000;">0</td>
                                <td style="border: 1px solid #000;">{{$data['penalty']}}</td>
                                <td style="border: 1px solid #000;">{{$data['demand']}}</td>
                                <td style="border: 1px solid #000;">{{$data['collection']}}</td>
				<td style="border: 1px solid #000;">{{$data['current_balance']}}</td>
                                
                            </tr>
                            
                            @endforeach
                            
                            <tr>
                                <td style="border: 1px solid #000;font-weight: bold">TOTAL</td>
                                <td style="border: 1px solid #000;font-weight: bold">{{getTotal($paymentArray,'total_installation')}}</td>
                                <td style="border: 1px solid #000;font-weight: bold">{{getTotal($paymentArray,'live')}}</td>
                                <td style="border: 1px solid #000;font-weight: bold">{{getTotal($paymentArray,'bill_count')}}</td>
                                <td style="border: 1px solid #000;font-weight: bold">{{getTotal($paymentArray,'old_balance')}}</td>
                                <td style="border: 1px solid #000;font-weight: bold">{{getTotal($paymentArray,'water_charge')}}</td>
                                <td style="border: 1px solid #000;font-weight: bold">{{getTotal($paymentArray,'other_charges')}}</td>
                                <td style="border: 1px solid #000;font-weight: bold">0</td>
                                <td style="border: 1px solid #000;font-weight: bold">{{getTotal($paymentArray,'penalty')}}</td>
                                <td style="border: 1px solid #000;font-weight: bold">{{getTotal($paymentArray,'demand')}}</td>
                                <td style="border: 1px solid #000;font-weight: bold">{{getTotal($paymentArray,'collection')}}</td>
				<td style="border: 1px solid #000;font-weight: bold">{{getTotal($paymentArray,'current_balance')}}</td>
                                
                            </tr>
                            <tr>
                                <td style="border: 1px solid #000;font-weight: bold">SUSPENCE COLLECTION</td>
                                <td style="border: 1px solid #000;font-weight: bold" colspan="9"></td>
                                 <td style="border: 1px solid #000;font-weight: bold"></td>
                                 <td style="border: 1px solid #000;font-weight: bold"></td>
                                
                            </tr>
                            
                            <tr>
                                <td style="border: 1px solid #000;font-weight: bold">GRAND TOTAL</td>
                                <td style="border: 1px solid #000;font-weight: bold">{{getTotal($paymentArray,'total_installation')}}</td>
                                <td style="border: 1px solid #000;font-weight: bold">{{getTotal($paymentArray,'live')}}</td>
                                <td style="border: 1px solid #000;font-weight: bold">{{getTotal($paymentArray,'bill_count')}}</td>
                                <td style="border: 1px solid #000;font-weight: bold">{{getTotal($paymentArray,'old_balance')}}</td>
                                <td style="border: 1px solid #000;font-weight: bold">{{getTotal($paymentArray,'water_charge')}}</td>
                                <td style="border: 1px solid #000;font-weight: bold">{{getTotal($paymentArray,'other_charges')}}</td>
                                <td style="border: 1px solid #000;font-weight: bold">0</td>
                                <td style="border: 1px solid #000;font-weight: bold">{{getTotal($paymentArray,'penalty')}}</td>
                                <td style="border: 1px solid #000;font-weight: bold">{{getTotal($paymentArray,'demand')}}</td>
                                <td style="border: 1px solid #000;font-weight: bold">{{getTotal($paymentArray,'collection')}}</td>
				<td style="border: 1px solid #000;font-weight: bold">{{getTotal($paymentArray,'current_balance')}}</td>
                                
                            </tr>
                            
                           

  <?php
        function getTotal($array,$searchKey) {
            $total = 0;
            
            foreach ($array as $records) {
                foreach ($records as $key => $value) {
                if($key==$searchKey) {
                    $total= $total+$value;
                }
            }
            }
            
            
            return $total;
        }
        
        exit();
  ?>