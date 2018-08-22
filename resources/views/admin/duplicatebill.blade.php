@extends('layouts.admin_master')
@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Duplicate Bill
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
		<div class="box box-info">
			<div class="box-header with-border">
				<h3 class="box-title">Duplicate Bill</h3>
					<!-- /.box-tools -->
			</div>
			<div class="box-body">
			<form>
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<span class="text-danger" style="display:block;margin-left:15px;margin-bottom:5px;"><strong id="error-field"></strong></span>
				<div class="col-md-4 col-sm-12">
					<b>Sequence Number</b>
					<input type="text" class="form-control" id="sequence_number"  name="sequence_number" required="required" placeholder="Sequence Number">
				</div>
				<div class="col-md-4 col-sm-12">
					<b>Meter Number</b>
					<input type="text" class="form-control" id="meter_number"  name="meter_number" required="required" placeholder="Meter Number">
				</div>
				<div class="col-md-4 col-sm-12">
					<button type="button" class="btn btn-warning margin btn-margin" data-toggle="collapse" data-target="#duplicate_bill" onclick="searchDuplicateBill()">Generate Duplicate Bill</button>	
				</div>	
				</form>
			</div>
		</div>
		
		
		<div id="duplicate_bill" class="collapse out" aria-expanded="true" style="display:none;">
			<div class="box box-danger">
				<div class="box-header with-border">
					<h3 class="box-title">Duplicate Bill</h3>
					<div class="btn-group pull-right" id="div_print_button1" style="display:none;">
					  <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#challan-genrate" onclick="printDuplicateBill()"><i class="fa fa-print"></i></button>
                      <!--<button type="button" class="btn btn-warning"><i class="fa fa-download"></i></button>-->
                    </div>	
				</div>
				<div class="box-body no-padding" id="duplicate_bill_result1" style="display:none;">
				<h2 class="no-padding">Sequence Number : <span class="text-red" id="span_sequence_number"></span></h2>
				<h3>Meter Number : <span id="span_meter_number"></span></h3>
				<h4>Name : <span id="span_consumer_name"></span></h4>
				<br>
					<table class="table table-responsive table-bordered table-striped dataTable no-footer">
						<thead>
							<tr>
								<th colspan="4">Duplicate Bill View</th>
							</tr>
						</thead>
						<tr>
							<td>
								<b>CorpWard</b>
                                                                <input type="text" class="form-control no-bg-input" readonly="readonly" id="txt_corpward_name" required="required"  value="">
							</td>
							<td>
								<b>Bill Date</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" id="txt_date_of_reading" required="required" value="">
							</td>
							<td>
								<b>Previous Bill date</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" id="txt_previous_bill_date" required="required" value="" >
							</td>
							<td>
								<b>Name</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" id="txt_consumer_name" required="required" value="">
							</td>
						</tr>
						<tr>
							<td>
								<b>Sequence Number </b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" id="txt_sequence_number" required="required" value="">
							</td>
							
							<td>
								<b>Door No</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" id="txt_door_number" required="required" value="">
							</td>
							<td>
								<b>Ward</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" id="txt_ward" placeholder="Ward">
							</td>
							<td>
								<b>Connection Type</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" id="txt_connection_type" required="required" value="">
							</td>
						</tr>
						<tr>
							<td>
								<b>Meter No</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" id="txt_meter_number" value="">
							</td>
							<td>
								<b>Bill No</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" id="txt_bill_number" required="required" value="">
							</td>
							<td>
								<b>Due date</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" id="txt_due_date" required="required" value="">
							</td>
							<td>
								<b>Current Reading</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" id="txt_current_reading" required="required" value="">
							</td>
						</tr>
						<tr>							
							<td>
								<b>Previous Reading</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" id="txt_previous_reading" required="required" value="">
							</td>
							<td>
								<b>Total Used Units</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" id="txt_total_units" required="required" value="">
							</td>
							<td>
								<b>Meter Status</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" id="txt_meter_status" required="required" value="">
							</td>
							<td>
								<b>No Days used</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" id="txt_days_used" required="required" value="">
							</td>
						</tr>
						<tr>								
							<td>
								<b>Water Charges </b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" id="txt_water_charges" required="required" value="">
							</td>
							<td>
								<b>Supervisor Charges</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" id="txt_supervisor_charges" required="required" value="">
							</td>
							<td>
								<b>Fixed Charges </b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" id="txt_fixed_charges" required="required" value="">
							</td>
							<td>
								<b>Other Charges</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" id="txt_other_charges" required="required" value="">
							</td>
						</tr>
						<tr>							
							<td>
								<b>Penalty</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" id="txt_penalty" required="required" value="">
							</td>
							<td>
								<b>Returned Amount</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" id="txt_returned_amount" required="required" value="">
							</td>
							<td>
								<b>Cess</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" id="txt_cess" required="required" value="">
							</td>
							<td>
								<b>UGD Cess</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" id="txt_ugd_cess" required="required" value="">
							</td>
						</tr>
						<tr>							
							<td>
								<b>Arrears</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" id="txt_arrears" required="required" value="">
							</td>
							<td>
								<b>Total Due</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" id="txt_total_due" required="required" value="">
							</td>
							<td>
								<b>Round Off</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" id="txt_roundoff" required="required" value="">
							</td>
							<td>
								<b>Total Amount</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" id="txt_total_amount" required="required" value="">
							</td>
						</tr>
					</table>
					<div class="clear"></div>
					<br>						
					<div class="clear"></div>
					<br>					
				</div>
				<div style="display:none;margin-left:10px;" id="duplicate_bill_result2"></div>
			</div>
		</div>
		<div class="modal modal-default fade in" id="challan-genrate">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-body">
					<table class="table table-bordered table-hover">
						<tr>
							<th colspan="2">Duplicate Bill</th>
						</tr>
						<tr>
							<td>CorpWard</td><td id="td_corp_ward"></td>
						</tr>
						<tr>
							<td>Bill Date</td><td id="td_reading_date"></td>
						</tr>
						<tr>
							<td>Name</td><td id="td_consumer_name"></td>
						</tr>
						<tr>
							<td><b>Sequence Number</b></td><td id="td_sequence_number"><b></b></td>
						</tr>
						<tr>
							<td>Door No</td> <td id="td_door_number"></td>
						</tr>
						<tr>
							<td>Ward</td> <td id="td_ward"></td>
						</tr>
						<tr>
							<td>Connection Type</td> <td id="td_connection_type"></td>
						</tr>
						<tr>
							<td>Meter No</td> <td id="td_meter_number"></td>
						</tr>
						<tr>
							<td>Bill No</td> <td id="td_bill_number"></td>
						</tr>
						<tr>
							<td>Due date</td> <td id="td_due_date"></td>
						</tr>
						<tr>
							<td>Current Reading</td> <td id="td_current_reading"></td>
						</tr>
						<tr>
							<td>Previous Reading</td> <td id="td_previous_reading"></td>
						</tr>
						<tr>
							<td>Total Used Units</td> <td id="td_total_units"></td>
						</tr>
						<tr>
							<td>Meter Status</td> <td id="td_meter_status"></td>
						</tr>
						<tr>
							<td>No Days used</td> <td id="td_days_used"></td>
						</tr>
						<tr>
							<td>Water Charges</td> <td id="td_water_charges"></td>
						</tr>
						<tr>
							<td>Supervisor Charges</td> <td id="td_supervisor_charges"></td>
						</tr>
						<tr>
							<td>Fixed Charges</td> <td id="td_fixed_charges"></td>
						</tr>
						<tr>
							<td>Other Charges</td> <td id="td_other_charges"></td>
						</tr>
						<tr>
							<td>Penalty</td> <td id="td_penalty"></td>
						</tr>
						<tr>
							<td>Returned Amount</td> <td id="td_returned_amount"></td>
						</tr>
						<tr>
							<td>Cess</td> <td id="td_cess"></td>
						</tr>
						<tr>
							<td>UGD Cess</td> <td id="td_ugd_cess"></td>
						</tr>
						<tr>
							<td>Arrears</td> <td id="td_arrears"></td>
						</tr>
						<tr>
							<td>Total Due</td> <td id="td_total_due"></td>
						</tr>
						<tr>
							<td>Round Off</td> <td id="td_roundoff"></td>
						</tr>
						<tr>
							<td><b>Total Amount</b></td> <td id="td_total_amount"><b></b></td>
						</tr>
					</table>
					
					<div class="btn-group pull-right">
                      <!--<button type="button" class="btn btn-warning" id="printButton" onclick="print_data()"><i class="fa fa-print"></i></button>-->
					  <div>
					  <a id="printPageUrl" href="" target="_blank" id="printPageLink" onclick="print_data()"><button type="button" class="btn btn-warning" id="printButton" ><i class="fa fa-print"></i></button></a>
                      <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i></button></div>
                    </div>
					<div class="clear"></div>
				</div>
			</div>
		</div>
	</div>
	<div id="print_table_div" style="display:none;"></div>
    </section>
	<script>
	$(document).ready(function () {
		$('#duplicate_bill').hide();
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		})
	});
		
	function formatDate (input) {
		var datePart = input.match(/\d+/g),
		year = datePart[0], // get only two digits
		month = datePart[1], 
		day = datePart[2];

		return day+'/'+month+'/'+year;
	}
	
	function searchDuplicateBill()
	{
		var siteUrl = '<?php echo url('/'); ?>';

		var sequenceNumber = $('#sequence_number').val();
		var meterNumber = $('#meter_number').val();
		if(sequenceNumber == '' && meterNumber=='') {
			$("#error-field").html('Please enter either Sequence Number or Meter Number');
			$("#duplicate_bill").hide();
		} else {
			$("#error-field").html('');
			$("#duplicate_bill").show();
			var dataString = {'meter_number':meterNumber,'sequence_number':sequenceNumber};
			$.ajax({
				type: "POST",
				url: siteUrl + "/admin/getDuplicateBillInfo",
				data: dataString,
				cache: "false",
				success: function (result) {
					if(result != '') {
                                                if(result.error) {
                                                    $('#duplicate_bill_result2').show();
                                                    $('#duplicate_bill_result2').html(result.error);
                                                    return;
                                                }
                                                
						var billinfo = result[0];
                                                
                                                
                                                
						var sequence_number = (billinfo.sequence_number === undefined || billinfo.sequence_number === null) ? "" : billinfo.sequence_number;
						var meter_no = (billinfo.meter_no === undefined || billinfo.meter_no === null) ? "" : billinfo.meter_no;
						var consumer_name = (billinfo.consumer_name === undefined || billinfo.consumer_name === null) ? "" : billinfo.consumer_name;
						var corp_name = (billinfo.corp_name === undefined || billinfo.corp_name === null) ? "" : billinfo.corp_name;
						var date_of_reading = (billinfo.date_of_reading === undefined || billinfo.date_of_reading === null) ? "" : billinfo.date_of_reading;
						var previous_billing_date = (billinfo.previous_billing_date === undefined || billinfo.previous_billing_date === null) ? "" : billinfo.previous_billing_date;
						var consumer_name = (billinfo.consumer_name === undefined || billinfo.consumer_name === null) ? "" : billinfo.consumer_name;
						var door_no = (billinfo.door_no === undefined || billinfo.door_no === null) ? "" : billinfo.door_no;
						var connection_type = (billinfo.connection_name === undefined || billinfo.connection_name === null) ? "" : billinfo.connection_name;;
						var payment_due_date = (billinfo.payment_due_date === undefined || billinfo.payment_due_date === null) ? "" : billinfo.payment_due_date;
						var current_reading = (billinfo.current_reading === undefined || billinfo.current_reading === null) ? "" : billinfo.current_reading;
						var previous_reading = (billinfo.previous_reading === undefined || billinfo.previous_reading === null) ? "" : billinfo.previous_reading;
						var total_unit_used = (billinfo.total_unit_used === undefined || billinfo.total_unit_used === null) ? "" : billinfo.total_unit_used;
						var meter_status = (billinfo.meter_status === undefined || billinfo.meter_status === null) ? "" : billinfo.meter_status;
						var no_of_days_used = (billinfo.no_of_days_used === undefined || billinfo.no_of_days_used === null) ? "" : billinfo.no_of_days_used;
						var water_charge = (billinfo.water_charge === undefined || billinfo.water_charge === null) ? "" : billinfo.water_charge;
						var supervisor_charge = (billinfo.supervisor_charge === undefined || billinfo.supervisor_charge === null) ? "" : billinfo.supervisor_charge;
						var fixed_charge = (billinfo.fixed_charge === undefined || billinfo.fixed_charge === null) ? "" : billinfo.fixed_charge;
						var ward_name = (billinfo.ward_name === undefined || billinfo.ward_name === null) ? "" : billinfo.ward_name;
						var other_title_charge = (billinfo.other_charges === undefined || billinfo.other_charges === null) ? "" : billinfo.other_charges;
						var penalty = (billinfo.penalty === undefined || billinfo.penalty === null) ? "" : billinfo.penalty;
						var refund_amount = (billinfo.refund_amount === undefined || billinfo.refund_amount === null) ? "" : billinfo.refund_amount;
						var cess = (billinfo.cess === undefined || billinfo.cess === null) ? "" : billinfo.cess;
						var ugd_cess = (billinfo.ugd_cess === undefined || billinfo.ugd_cess === null) ? "" : billinfo.ugd_cess;
						var arrears = (billinfo.arrears === undefined || billinfo.arrears === null) ? "" : billinfo.arrears;
						var total_due = (billinfo.total_due === undefined || billinfo.total_due === null) ? "" : billinfo.total_due;
						var round_off = (billinfo.round_off === undefined || billinfo.round_off === null) ? "" : billinfo.round_off;
						var total_amount = (billinfo.total_amount === undefined || billinfo.total_amount === null) ? "" : billinfo.total_amount;
						var bill_no = (billinfo.bill_no === undefined || billinfo.bill_no === null) ? "" : billinfo.bill_no;
						
						$('#span_sequence_number').html(sequence_number);
						$('#span_meter_number').html(meter_no);
						$('#span_consumer_name').html(consumer_name);
						$('#txt_corpward_name').val(corp_name);
						if(date_of_reading != '') {
							$('#txt_date_of_reading').val(date_of_reading);
						} else 
						{
							$('#txt_date_of_reading').val('');
						}
						if(previous_billing_date != '') {
							$('#txt_previous_bill_date').val(previous_billing_date);
						} else 
						{
							$('#txt_previous_bill_date').val('');
						}
						
						$('#txt_consumer_name').val(consumer_name);
						$('#txt_sequence_number').val(sequence_number);
						$('#txt_door_number').val(door_no);
						$('#txt_ward').val(ward_name);
						$('#txt_connection_type').val(connection_type);
						$('#txt_meter_number').val(meter_no);
						$('#txt_bill_number').val(bill_no);
						if(payment_due_date != "") {
							$('#txt_due_date').val(payment_due_date);
						} else 
						{
							$('#txt_due_date').val('');
						}
						
						$('#txt_current_reading').val(current_reading);
						$('#txt_previous_reading').val(previous_reading);
						$('#txt_total_units').val(total_unit_used);
						$('#txt_meter_status').val(meter_status);
						$('#txt_days_used').val(no_of_days_used);
						$('#txt_water_charges').val(water_charge);
						$('#txt_supervisor_charges').val(supervisor_charge);
						$('#txt_fixed_charges').val(fixed_charge);
						$('#txt_other_charges').val(other_title_charge);
						$('#txt_penalty').val(penalty);
						$('#txt_returned_amount').val(refund_amount);
						$('#txt_cess').val(cess);
						$('#txt_ugd_cess').val(ugd_cess);
						$('#txt_arrears').val(arrears);
						$('#txt_total_due').val(total_due);
						$('#txt_roundoff').val(round_off);
						$('#txt_total_amount').val(total_amount);
						
						$('#duplicate_bill').show();
						$('#duplicate_bill_result1').show();
						$('#duplicate_bill_result2').hide();
						$('#duplicate_bill_result2').html('');
						$('#div_print_button1').show();
						$('#div_print_button2').show();
					} else {
						$('#duplicate_bill').show();
						$('#duplicate_bill_result1').hide();
						$('#duplicate_bill_result2').show();
						$('#duplicate_bill_result2').html('No results found');
						$('#div_print_button1').hide();
						$('#div_print_button2').hide();
					}
				}
			});
			
		} 
	}
	
	function printDuplicateBill() {
	
		$('#td_corp_ward').html($('#txt_corpward_name').val());
		$('#td_reading_date').html($('#txt_date_of_reading').val());
		$('#td_consumer_name').html($('#txt_consumer_name').val());
		$('#td_door_number').html($('#txt_door_number').val());
		$('#td_sequence_number').html($('#txt_sequence_number').val());
		$('#td_ward').html($('#txt_ward').val());
		$('#td_connection_type').html($('#txt_connection_type').val());
		$('#td_meter_number').html($('#txt_meter_number').val());
		$('#td_bill_number').html($('#txt_bill_number').val());
		$('#td_due_date').html($('#txt_due_date').val());
		$('#td_current_reading').html($('#txt_current_reading').val());
		$('#td_previous_reading').html($('#txt_previous_reading').val());
		$('#td_total_units').html($('#txt_total_units').val());
		$('#td_meter_status').html($('#txt_meter_status').val());
		$('#td_days_used').html($('#txt_days_used').val());					
		$('#td_water_charges').html($('#txt_water_charges').val());
		$('#td_supervisor_charges').html($('#txt_supervisor_charges').val());
		$('#td_fixed_charges').html($('#txt_fixed_charges').val());
		$('#td_other_charges').html($('#txt_other_charges').val());
		$('#td_penalty').html($('#txt_penalty').val());
		$('#td_returned_amount').html($('#txt_returned_amount').val());
		$('#td_cess').html($('#txt_cess').val());
		$('#td_ugd_cess').html($('#txt_ugd_cess').val());
		$('#td_arrears').html($('#txt_arrears').val());
		$('#td_total_due').html($('#txt_total_due').val());
		$('#td_roundoff').html($('#txt_roundoff').val());
		$('#td_total_amount').html($('#txt_total_amount').val());
	}
	
	function print_data()
	{
		var siteUrl = '<?php echo url('/'); ?>';
		var sequenceNumber  = $('#sequence_number').val();		
		var meterNumber = $('#meter_number').val();
        printPageUrl = siteUrl + '/admin/printDuplicateBill' + '?sn=' + sequenceNumber+'&mn='+meterNumber;
        $("#printPageUrl").attr("href", printPageUrl);
	}
</script>
@endsection
