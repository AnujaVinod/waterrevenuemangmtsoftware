<table  style="width:1024px;" border="1"><tr>
        <td colspan="3" rowspan="2"><a href=""><span><img style="background-color:black;margin-top: -5px;"src="{{ asset('dist/img/logo-sidebar.png') }}" width="12%"></span></a><h2>Mangaluru City Corporation - Water Bill Payment</h2></td>
                                                 <td><b>Challan No</b></td><td colspan="2"> {{ $filldata['challan_no'] }}  </td></tr> 
                                                 <tr><td><b>Date</b></td><td colspan="2">{{  $filldata['payment_date'] }}  </td></tr>  
                                                 <tr><td colspan="3">Bank Name / Branch</td><td colspan="3">  {{ $filldata['bank_name'] }}  /   {{ $filldata['branch_name'] }}  </td></tr>
                                                 <tr><td colspan="3">Water Supply Period</td> <td colspan="3">{{  $filldata['previous_billing_date'] }}   To  {{  $filldata['date_of_reading']  }}</td></tr>
                                                 <tr><td><b>Building Owner Name</b></td><td colspan="2"><b>Ward / Block Door #, Sequence #</b></td><td><b>Bill Details</b></td><td><b>RS</b></td><td><b>00</b></td></tr>
                                                 <tr>
                                                 <td rowspan="8"> {{ $filldata['name'] }} <br>SEQ No - {{ $filldata['sequence_number'] }}</td>
                                                 <td colspan="2" rowspan="3">{{ $filldata['comm_address'] }}</td>
                                                  <td>Water Charge</td>
                                                 <td> {{ $filldata['water_charge'] }}</td>
                                                 <td>{{ $filldata['water_fraction'] }}</td>
                                                 </tr>
                                                 <tr>
                                                 <td>Supervisor Charge</td>
                                                 <td>{{ $filldata['supervisor_charge'] }}</td>
                                                 <td>{{ $filldata['supervisor_fraction'] }}</td>
                                                 </tr>
                                                 <tr>
                                                 <td>Other Charge</td>
                                                 <td>{{ $filldata['other_charges'] }}</td>
                                                 <td>{{ $filldata['other_fraction'] }}</td>
                                                 </tr>
                                                 <tr>
                                                  <td><b>SEQ No</b></td>
                                                 <td>{{ $filldata['sequence_number'] }}</td>
                                                 <td>Penalty</td>
                                                 <td>{{ $filldata['penalty'] }}</td>
                                                <td>{{ $filldata['penalty_fraction'] }}</td>
                                                </tr>
                                                <tr>
                                                <td><b>Meter No</b></td>
                                                <td>{{ $filldata['meter_no'] }}</td>
                                                <td>Returned Amount</td>
                                                 <td>{{ $filldata['returned_amount'] }}</td>
                                                <td>{{ $filldata['return_fraction'] }}</td>
                                                </tr>
                                                <tr>
                                                <td><b>Bill No</b></td>
                                                <td>{{ $filldata['bill_no'] }}</td>
                                                <td>Other CESS</td>
                                                <td>{{ $filldata['cess'] }}</td>
                                                <td>{{ $filldata['cess_fraction'] }}</td>
                                                </tr>
                                                
                                                <tr>
                                                <td></td>
                                                <td></td>
                                                <td>Arrears</td>
                                                <td>{{ $filldata['arrears'] }}</td>
                                                <td>{{ $filldata['arrears_fraction'] }}</td>
                                                </tr>
                                                <tr>
                                                <td colspan="2" rowspan="2"></td>
                                                <td>Round off</td>
                                                <td>{{ $filldata['round_off']}}</td>
                                                <td>{{ $filldata['round_fraction'] }}</td>
                                                </tr>
                                                <tr>    
                                                <td></td>
                                                <td><b>Total Amount</b></td>
                                                <td><b>{{ $filldata['total_amount'] }}</b></td>
                                                <td><b>{{ $filldata['total_fraction'] }}</b></td>
                                                </tr>

                                                <tr>
                                                 <td></td>
                                                <td></td>
                                               <td></td>
                                                <td><b>Advance Amount</b></td>
                                                <td><b>{{ $filldata['advance_amount'] }}</b></td>
                                                 <td><b>{{ $filldata['advance_fraction']}}</b></td>
                                                </tr>
                                                <tr>
                                                 <td></td>
                                                <td></td>
                                                 <td></td>
                                                <td><b>Payable Amount</b></td>
                                                <td><b>{{ $filldata['payable_amount'] }}</b></td>
                                                 <td><b>{{ $filldata['payable_fraction'] }}</b></td>
                                                </tr>
                                                <tr>
                                                <td colspan="6"><b>Amount in words</b> - {{ $filldata['amount_in_words'] }} only</td>
                                                </tr>
                                                <tr>
                                                <td><b>Depositer Signature</b></td>
                                                <td colspan="2"><b>Account # </b></td>
                                                <td><b>Office Manager Signature</b></td>
                                                <td colspan="2"><b>Cashier Signature</b></td>
                                                </tr>
                                                <tr>
                                                <td height="75"></td>
                                                <td colspan="2"></td>
                                                <td></td>
                                                <td colspan="2"></td>
                                                </tr> </table>