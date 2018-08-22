<?php 
$billDetails = $billDetails;
//echo "<pre>";
//print_r($billDetails->toArray());
$role1 = Helper::getRole();
$sub_category = $role1->sub_category_name;
$category = $role1->category_name;
?>

<table id="input_elements" class="table table-responsive table-bordered table-striped dataTable no-footer">
						<thead>
							<tr>
								<th colspan="4">Bill</th>
							</tr>
						</thead>
						<tbody><tr>
							<td>
                                                             <input type="hidden" class="form-control no-bg-input" readonly="readonly" name="bill_type" id="bill_type" required="required" value="2">
								<b>CorpWard</b>
                                                                <input type="text" class="form-control no-bg-input" readonly="readonly" id="txt_corpward_name" required="required" value="{{$billDetails->corp_name}}">
                                                                 <input type="hidden" class="form-control no-bg-input" readonly="readonly" name="corpward_id" id="corpward_id" required="required" value="27">
                                                                 <input type="hidden" class="form-control no-bg-input" readonly="readonly" name="mnr_count" id="mnr_count" required="required" value="0">
							</td>
						
							<td>
								<b>Previous Bill date</b>
								<input type="text" class="form-control no-bg-input date_class" readonly="readonly" name="previous_billing_date" id="txt_previous_bill_date" required="required" value="{{date("Y-m-d",strtotime($billDetails->previous_billing_date))}}">
							</td>
                                                        <td>
								<b>Date of Reading</b>
								<input type="text" class="form-control no-bg-input date_class" readonly="readonly" name="date_of_reading" id="txt_date_of_reading" required="required" value="{{date("Y-m-d",strtotime($billDetails->date_of_reading))}}">
                                                                <span class="text-danger"><strong id="date_error"></strong></span>
							</td>
							<td>
								<b>Name</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="consumer_name" id="txt_consumer_name" required="required" value="{{$billDetails->consumer_name}}">
							</td>
						</tr>
						<tr>
							<td>
								<b>Sequence Number </b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="sequence_number" id="txt_sequence_number" required="required" value="{{$billDetails->sequence_number}}">
							</td>
							
							<td>
								<b>Door No</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="door_no" id="txt_door_number" required="required" value="{{$billDetails->door_no}}">
							</td>
							<td>
								<b>Ward</b>
                                                                <input type="text" class="form-control no-bg-input" readonly="readonly" id="txt_ward" placeholder="Ward" value="{{$billDetails->ward}}">
                                                                <input type="hidden" class="form-control no-bg-input" readonly="readonly" name="ward_id" id="txt_ward_id" placeholder="Ward" value="28">
							</td>
							<td>
								<b>Connection Type</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" id="txt_connection_type" required="required" value="{{$billDetails->connection_name}}">
                                                                <input type="hidden" class="form-control no-bg-input" readonly="readonly" name="connection_type_id" id="txt_connection_type_id" required="required" value="{{$billDetails->connection_name}}">
							</td>
						</tr>
						<tr>
                                                    <td>
								<b>No of Flats</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="no_of_flats" id="no_of_flats" value="{{$billDetails->no_of_flats}}">
							</td>
							<td>
								<b>Meter No</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="meter_no" id="txt_meter_number" value="{{$billDetails->meter_no}}">
							</td>
							<td>
								<b>Bill No</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="bill_no" id="txt_bill_number" required="required" value="{{$billDetails->bill_no}}">
							</td>
							<td>
								<b>Due date</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="payment_due_date" id="txt_due_date" required="required" value=" {{date("Y-m-d",strtotime($billDetails->payment_due_date))}}">
							</td>
							
						</tr>
						<tr>	
                                                    <td>
								<b>Previous Reading</b>
                                                                <input type="text" class="form-control no-bg-input" name="previous_reading" id="txt_previous_reading" required="required" value="{{$billDetails->previous_reading}}" disabled="disabled">
                                                                <span class="text-danger"><strong id="large_error"></strong></span>
                                                                <span class="text-danger">
                                                                <strong id="previous_reading-error"></strong>
                                                                </span>
							</td>
                                                    	<td>
								<b>Current Reading</b>
								<input type="text" class="form-control no-bg-input" name="current_reading" id="txt_current_reading" required="required" value="{{$billDetails->current_reading}}" disabled="disabled">
                                                                <span class="text-danger"><strong id="large_error"></strong></span>
                                                                <span class="text-danger">
                                                                <strong id="current_reading-error"></strong>
                                                                </span>
							</td>
						
							<td>
								<b>Total Used Units</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="total_unit_used" id="txt_total_units" required="required" value="{{$billDetails->total_unit_used}}">
                                                                <span class="text-danger">
                                                            <strong id="total_unit_used-error"></strong>
                                                            </span>
							</td>
							<td>
                                                            <b>Meter Status</b>
                                                          
								  <input type="text" class="form-control no-bg-input" readonly="readonly" name="total_unit_used" id="txt_total_units" required="required" value="{{$billDetails->meter_status}}">
                                                                    <span class="text-danger">
                                                               <strong id="ward-error_tap"></strong>
                                                           </span>	
							</td>
							
						</tr>
						<tr>	
                                                    <td>
								<b>No Days used</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="no_of_days_used" id="txt_days_used" required="required" value="{{$billDetails->no_of_days_used}}">
                                                                <span class="text-danger">
                                                            <strong id="no_of_days-error"></strong>
                                                            </span>
							</td>
							<td>
								<b>Water Charges </b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="water_charge" id="txt_water_charges" required="required" value="{{$billDetails->water_charge}}">
							</td>
							<td>
								<b>Supervisor Charges</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="supervisor_charge" id="txt_supervisor_charges" required="required" value="{{$billDetails->supervisor_charge}}">
							</td>
							<td>
								<b>Fixed Charges </b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="fixed_charge" id="txt_fixed_charges" required="required" value="{{$billDetails->fixed_charge}}">
							</td>
							
						</tr>
						<tr>
                                                    <td>
								<b>Other Charges</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="other_charges" id="txt_other_charges" required="required" value="{{$billDetails->other_charges}}">
							</td>
							<td>
								<b>Penalty</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="penalty" id="txt_penalty" required="required" value="{{$billDetails->penalty}}">
							</td>
							<td>
								<b>Returned Amount</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="refund_amount" id="txt_returned_amount" required="required" value="{{$billDetails->refund_amount}}">
							</td>
							<td>
								<b>Cess</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="cess" id="txt_cess" required="required" value="{{$billDetails->cess}}">
							</td>
							
						</tr>
						<tr>	
                                                        <td>
								<b>UGD Cess</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="ugd_cess" id="txt_ugd_cess" required="required" value="{{$billDetails->ugd_cess}}">
							</td>
							<td>
								<b>Arrears</b>
								<input type="text" class="form-control no-bg-input" name="arrears" id="txt_arrears" required="required" value="{{$billDetails->arrears}}">
							</td>
							<td>
								<b>Total Due</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="total_due" id="txt_total_due" required="required" value="{{$billDetails->total_due}}">
							</td>
							<td>
								<b>Round Off</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="round_off" id="txt_roundoff" required="required" value="{{$billDetails->round_off}}">
							</td>
							
						</tr>
                                                <tr>
                                                    <td>
								<b>Total Amount</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="total_amount" id="txt_total_amount" required="required" value="{{$billDetails->total_amount}}">
                                                                <span class="text-danger">
                                                            <strong id="total_amount-error"></strong>
                                                            </span>
							</td>
                                                </tr>
                                             
					</tbody></table>