<?php
$bill_details = $billinfo[0];
 ?>
<a href=""><span><img style="background-color:black;margin-top: -5px;"src="{{ asset('dist/img/logo-sidebar.png') }}" width="12%"></span></a><h2>Mangaluru City Corporation - Water Bill</h2>
<table style="width:700px;" border="1">
						<tr>
							<th colspan="2">New Bill</th>
						</tr>
						<tr>
							<td width="50%">CorpWard</td ><td id="td_corp_ward"><?php echo $bill_details->corp_name;?></td>
						</tr>
						<tr>
							<td>Bill Date</td><td id="td_reading_date"><?php echo $bill_details->date_of_reading;?></td>
						</tr>
						<tr>
							<td>Name</td><td id="td_consumer_name"><?php echo $bill_details->consumer_name;?></td>
						</tr>
						<tr>
							<td><b>Sequence Number</b></td><td id="td_sequence_number"><b><?php echo $bill_details->sequence_number;?></b></td>
						</tr>
						<tr>
							<td>Door No</td> <td id="td_door_number"><?php echo $bill_details->door_no;?></td>
						</tr>
						<tr>
							<td>Ward</td> <td id="txt_ward"><?php echo $bill_details->ward_name;?></td>
						</tr>
						<tr>
							<td>Connection Type</td> <td id="td_connection_type"><?php echo $bill_details->connection_name;?></td>
						</tr>
						<tr>
							<td>Meter No</td> <td id="td_meter_number"><?php echo $bill_details->meter_no;?></td>
						</tr>
						<tr>
							<td>Bill No</td> <td id="td_bill_number"><?php echo $bill_details->bill_no;?></td>
						</tr>
						<tr>
							<td>Due date</td> <td id="td_due_date"><?php echo $bill_details->payment_due_date;?></td>
						</tr>
						<tr>
							<td>Current Reading</td> <td id="td_current_reading"><?php echo $bill_details->current_reading;?></td>
						</tr>
						<tr>
							<td>Previous Reading</td> <td id="td_previous_reading"><?php echo $bill_details->previous_reading;?></td>
						</tr>
						<tr>
							<td>Total Used Units</td> <td id="td_total_units"><?php echo $bill_details->total_unit_used;?></td>
						</tr>
						<tr>
							<td>Meter Status</td> <td id="td_meter_status"><?php echo $bill_details->meter_status;?></td>
						</tr>
						<tr>
							<td>No Days used</td> <td id="td_days_used"><?php echo $bill_details->no_of_days_used;?></td>
						</tr>
						<tr>
							<td>Water Charges</td> <td id="td_water_charges"><?php echo $bill_details->water_charge;?></td>
						</tr>
						<tr>
							<td>Supervisor Charges</td> <td id="td_supervisor_charges"><?php echo $bill_details->supervisor_charge;?></td>
						</tr>
						<tr>
							<td>Fixed Charges</td> <td id="td_fixed_charges"><?php echo $bill_details->fixed_charge;?></td>
						</tr>
						<tr>
							<td>Other Charges</td> <td id="td_other_charges"><?php echo $bill_details->other_charges;?></td>
						</tr>
						<tr>
							<td>Penalty</td> <td id="td_penalty"><?php echo $bill_details->penalty;?></td>
						</tr>
						<tr>
							<td>Returned Amount</td> <td id="td_returned_amount"><?php echo $bill_details->refund_amount;?></td>
						</tr>
						<tr>
							<td>Cess</td> <td id="td_cess"><?php echo $bill_details->cess;?></td>
						</tr>
						<tr>
							<td>UGD Cess</td> <td id="td_ugd_cess"><?php echo $bill_details->ugd_cess;?></td>
						</tr>
						<tr>
							<td>Arrears</td> <td id="td_arrears"><?php echo $bill_details->arrears;?></td>
						</tr>
						<tr>
							<td>Total Due</td> <td id="td_total_due"><?php echo $bill_details->total_due;?></td>
						</tr>
						<tr>
							<td>Round Off</td> <td id="td_roundoff"><?php echo $bill_details->round_off;?></td>
						</tr>
						<tr>
							<td><b>Total Amount</b></td> <td id="td_total_amount"><b><?php echo $bill_details->total_amount;?></b></td>
						</tr>
					</table>