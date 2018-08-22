@extends('layouts.admin_master')
@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Generate New Bill
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol>
    </section>
   <?php $role1 = Helper::getRole();
       $sub_category = $role1->sub_category_name;
       $category = $role1->category_name;
       ?> 
    <!-- Main content -->
    <section class="content container-fluid"> 
        

		<div class="box box-info">
			<div class="box-header with-border">
				<h3 class="box-title">New Bill</h3>
					<!-- /.box-tools -->
			</div>
			<div class="box-body">
                          <input type="hidden" id="check_cate" value="<?php echo $category;?>">    
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
                           <div id="success-msg1" class="hide">
                                    <div class="alert alert-info alert-dismissible fade in" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <strong>Bill Details Updated Sucessfully</strong> 
                                    </div>
                                </div>
                             <div id="success-msg2" class="hide">
                                    <div class="alert alert-info alert-dismissible fade in" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <strong>Bill Details Added Sucessfully</span></strong> 
                                    </div>
                                </div>
			<form>
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
					<b>Door Number</b>
					<input type="text" class="form-control" id="door_no"  name=door_no" required="required" placeholder="Door Number">
				</div>
				<div class="col-md-4 col-sm-12">
					<button type="button" class="btn btn-warning margin btn-margin" data-toggle="collapse" data-target="#generate_bill" onclick="searchBill()">Search</button>	
				</div>	
				</form>
			</div>
		</div>
		 <div id="empty_box"  style="display:none;">
                     <div class="box box-danger">
				<div class="box-header with-border">
                         <span class="text-red" id="result_display" style="padding:20px;font-weight: bold;" ></span>
                     </div>
                     </div>
                 </div>
        <div class="loading" style="display: none">
            <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
        </div>
		<div id="generate_bill" class="collapse out" aria-expanded="true" style="display:none;">
                    
			<div class="box box-danger">
				<div class="box-header with-border">
					<h3 class="box-title">Bill</h3>
                                        
					<div class="btn-group pull-right" id="div_print_button1" style="display:none;">
                                 </div>	
				</div>
                               <form class="form-horizontal" method="POST" id="pay_bill_form">
				<div class="box-body no-padding" id="generate_bill_result1" style="display:none;">
				<h2 class="no-padding">Sequence Number : <span class="text-red" id="span_sequence_number"></span></h2>
				<h3>Meter Number : <span id="span_meter_number"></span></h3>
				<h4>Name : <span id="span_consumer_name"></span></h4>
				<br>
                                <div class="alert alert-info" style="display:none;">
                                <strong id="bill_info"></strong>   <strong id="status_bill"></strong> 
                                </div>
					<table id="input_elements" class="table table-responsive table-bordered table-striped dataTable no-footer">
						<thead>
							<tr>
								<th colspan="4">Bill</th>
							</tr>
						</thead>
						<tr>
							<td>
                                                             <input type="hidden" class="form-control no-bg-input" readonly="readonly"  name="bill_type" id="bill_type" required="required"  value="">
								<b>CorpWard</b>
                                                                <input type="text" class="form-control no-bg-input" readonly="readonly" id="txt_corpward_name" required="required"  value="">
                                                                 <input type="hidden" class="form-control no-bg-input" readonly="readonly" name="corpward_id" id="corpward_id" required="required"  value="">
                                                                 <input type="hidden" class="form-control no-bg-input" readonly="readonly" name="mnr_count" id="mnr_count" required="required"  value="">
							</td>
						
							<td>
								<b>Previous Bill date</b>
								<input type="text" class="form-control no-bg-input " readonly="readonly" name="previous_billing_date" id="txt_previous_bill_date" required="required" value="" >
							</td>
                                                        <td>
								<b>Date of Reading</b>
								<input type="text" class="form-control no-bg-input date_class" readonly="readonly" name="date_of_reading" id="txt_date_of_reading" required="required" value="">
                                                                <span class="text-danger"><strong id="date_error"></strong></span>
							</td>
							<td>
								<b>Name</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="consumer_name" id="txt_consumer_name" required="required" value="">
							</td>
						</tr>
						<tr>
							<td>
								<b>Sequence Number </b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="sequence_number" id="txt_sequence_number" required="required" value="">
							</td>
							
							<td>
								<b>Door No</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="door_no" id="txt_door_number" required="required" value="">
							</td>
							<td>
								<b>Ward</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" id="txt_ward" placeholder="Ward">
                                                                <input type="hidden" class="form-control no-bg-input" readonly="readonly" name="ward_id" id="txt_ward_id" placeholder="Ward">
							</td>
							<td>
								<b>Connection Type</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" id="txt_connection_type" required="required" value="">
                                                                <input type="hidden" class="form-control no-bg-input" readonly="readonly" name="connection_type_id" id="txt_connection_type_id" required="required" value="">
							</td>
						</tr>
						<tr>
                                                    <td>
								<b>No of Flats</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="no_of_flats" id="no_of_flats" value="">
							</td>
							<td>
								<b>Meter No</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="meter_no" id="txt_meter_number" value="">
							</td>
							<td>
								<b>Bill No</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="bill_no" id="txt_bill_number" required="required" value="">
							</td>
							<td>
								<b>Due date</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="payment_due_date" id="txt_due_date" required="required" value="">
							</td>
							
						</tr>
						<tr>	
                                                    <td>
								<b>Previous Reading</b>
                                                                <input type="text" class="form-control no-bg-input" name="previous_reading" id="txt_previous_reading" required="required" value="" disabled="disabled">
                                                                <span class="text-danger"><strong id="large_error"></strong></span>
                                                                <span class="text-danger">
                                                                <strong id="previous_reading-error"></strong>
                                                                </span>
							</td>
                                                    	<td>
								<b>Current Reading</b>
								<input type="text" class="form-control no-bg-input" name="current_reading" id="txt_current_reading" required="required" value="" disabled="disabled">
                                                                <span class="text-danger"><strong id="large_error"></strong></span>
                                                                <span class="text-danger">
                                                                <strong id="current_reading-error"></strong>
                                                                </span>
							</td>
						
							<td>
								<b>Total Used Units</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="total_unit_used" id="txt_total_units" required="required" value="">
                                                                <span class="text-danger">
                                                            <strong id="total_unit_used-error"></strong>
                                                            </span>
							</td>
							<td>
                                                            <b>Meter Status</b>
                                                          
								  <select class="form-control " name="meter_status" value=""  id="txt_meter_status" style="width: 100%;" >
                                                                    <option value="">Select</option>
                                                                   @foreach($meter_status as $meterdata)
                                                                    <option value="{{ $meterdata->id }}" disabled>{{ $meterdata->meter_status }}</option>
                                                                   @endforeach
                                                            </select>
                                                                    <span class="text-danger">
                                                               <strong id="ward-error_tap"></strong>
                                                           </span>	
							</td>
							
						</tr>
						<tr>	
                                                    <td>
								<b>No Days used</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="no_of_days_used" id="txt_days_used" required="required" value="">
                                                                <span class="text-danger">
                                                            <strong id="no_of_days-error"></strong>
                                                            </span>
							</td>
							<td>
								<b>Water Charges </b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="water_charge" id="txt_water_charges" required="required" value="">
							</td>
							<td>
								<b>Supervisor Charges</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="supervisor_charge" id="txt_supervisor_charges" required="required" value="">
							</td>
							<td>
								<b>Fixed Charges </b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="fixed_charge" id="txt_fixed_charges" required="required" value="">
							</td>
							
						</tr>
						<tr>
                                                    <td>
								<b>Other Charges</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="other_charges" id="txt_other_charges" required="required" value="">
							</td>
							<td>
								<b>Penalty</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="penalty" id="txt_penalty" required="required" value="">
							</td>
							<td>
								<b>Returned Amount</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="refund_amount"  id="txt_returned_amount" required="required" value="">
							</td>
							<td>
								<b>Cess</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="cess" id="txt_cess" required="required" value="">
							</td>
							
						</tr>
						<tr>	
                                                        <td>
								<b>UGD Cess</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="ugd_cess" id="txt_ugd_cess" required="required" value="">
							</td>
							<td>
								<b>Arrears</b>
								<input type="text" class="form-control no-bg-input" name="arrears" id="txt_arrears" required="required" value="">
							</td>
							<td>
								<b>Total Due</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="total_due" id="txt_total_due" required="required" value="">
							</td>
							<td>
								<b>Round Off</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="round_off"  id="txt_roundoff" required="required" value="">
							</td>
							
						</tr>
                                                <tr>
                                                    <td>
								<b>Total Amount</b>
								<input type="text" class="form-control no-bg-input" readonly="readonly" name="total_amount" id="txt_total_amount" required="required" value="">
                                                                <span class="text-danger">
                                                            <strong id="total_amount-error"></strong>
                                                            </span>
							</td>
                                                </tr>
                                             
					</table>
                                <!-- if  admin -->
                                
                                @if($category=="MCC" && $sub_category=="Administrator")
                                <table class="table table-bordered table-hover " style="margin-top: 20px;">
                                    <thead>
                                        <tr>
                                            <th colspan="4">Bill Reviewer Details</th>
                                        </tr>
                                    </thead>
                                    <tr>
                                        <td width="20%">Reviewer</td>
                                        <td width="30%">
                                            <div id="approve_list_div">
                                                <select class="form-control select2" id="reviewer" name="reviewer" style="width: 100%;">

                                                    <option value="">Select</option>
                                                    @foreach($approval as $generatedby)
                                                    <option value="{{ $generatedby->id }}">{{ $generatedby->name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger"><strong id="large_error"></strong></span>
                                                <span class="text-danger">
                                                    <strong id="reviewer-error"></strong>
                                                </span>

                                            </div>

                                        </td>
                                    <span class="text-danger">
                                        <strong id="reviewer-error"></strong>
                                    </span>
                                    <td width="50%" colspan="2"></td>
                                    </tr>
                                </table>
                                @endif
                                <!-- if  admin end here -->
                                
                                <!-- if super admin -->
                                @if($category=="SUPER ADMIN")
                                <table class="table table-bordered table-hover " style="margin-top: 20px;">
                                    <thead>
                                        <tr>
                                            <th colspan="4">Bill Approval Details</th>
                                        </tr>
                                    </thead>
                                    <tr>
                                        <td width="20%">Approved By</td>
                                        <td width="30%">
                                            <div id="approve_list_div">
                                                <select class="form-control select2" id="generated_by" name="generated_by" style="width: 100%;">

                                                    <option value="">Select</option>
                                                    @foreach($approval as $generatedby)
                                                    <option value="{{ $generatedby->id }}">{{ $generatedby->name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger"><strong id="large_error"></strong></span>
                                                <span class="text-danger">
                                                    <strong id="gen_by-error"></strong>
                                                </span>

                                            </div>

                                        </td>
                                    <span class="text-danger">
                                        <strong id="approved_by-error"></strong>
                                    </span>
                                    <td width="50%" colspan="2"></td>
                                    </tr>
                                </table>
                                @endif
                                <!-- if super admin end here -->
                                <span class="text-danger" style="float: right;">
                                        <strong id="generate_bill_status"></strong>
                                    </span>
                                <div class="clear"></div>
                                <button type="button" class="btn btn-warning margin btn-margin" id="bill_btn" style="float:right;"  data-toggle="modal" onclick="generateNewBill()"></button>	
					<div class="clear"></div>
					<br>						
					<div class="clear"></div>
					<br>					
				</div>
                   
                               </form>
				<div style="display:none;margin:40px;padding-bottom: 50px;align:center;" id="generate_bill_result2"></div>
			</div>
		</div>
		<div class="modal modal-default fade in" id="challan-genrate">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-body">
					<table class="table table-bordered table-hover">
						<tr>
							<th colspan="2">New Bill</th>
						</tr>
						<tr>
							<td>CorpWard</td><td id="td_corp_ward"></td>
						</tr>
						<tr>
							<td>Date of Reading</td><td id="td_reading_date"></td>
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
                                                <tr>
							<td><b>Bill Approved By</b></td> <td id="approved_by_name"><b></b></td>
						</tr>
					</table>
					
					<div class="btn-group pull-right">
                    
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
		$('#generate_bill').hide();
              
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		})
                 $('.date_class').datepicker({
                    autoclose: true,
                       dateFormat: 'dd/mm/yy'}).on("changeDate", function (e) {
                          
                        day_calculate();
                        waterChargeCalculate();
                        orientation: "bottom auto"
                  });

                     $('.date_class').datepicker({
                      autoclose: true,
                       dateFormat: 'dd/mm/yy'}).on("changeDate", function (e) {
                          
                        day_calculate();
                        waterChargeCalculate()
                      orientation: "bottom auto"
                    });	
              
           var check_cate=$('#check_cate').val();
            if(check_cate=='EXECUTIVE')
            {
             $("#input_elements :input").attr("disabled", true);  
             
            }
            else
            {
                 $("#approval_div :input").attr("disabled", true); 
            }
             $('#txt_meter_status').on("change",function(event) {
               
                var meter_status_check= $("#txt_meter_status option:selected").val();
                var siteUrl = '<?php echo url('/'); ?>';
                $('#bill_btn').prop('disabled', true);
		var sequenceNumber = $('#sequence_number').val();
		var bill_type=$('#bill_type').val()
                var dataString = {'sequence_number':sequenceNumber,'bill_type':bill_type};
                  $.ajax({
				type: "POST",
				url: siteUrl + "/admin/get_meter_reading_info",
				data: dataString,
				cache: "false",
				success: function (result) {
                                     if(meter_status_check==4)
                                     {
                                    
                                    $('#txt_current_reading').val(result.current_reading);
			            $('#txt_previous_reading').val(result.previous_reading);
                                    $('#txt_total_units').val(result.total_unit_used);
                                    }
                                    else  if(meter_status_check==2)
                                    {
                                        $('#txt_current_reading').val('0');
                                        $('#txt_previous_reading').val(result.previous_reading);
                                        $('#txt_total_units').val('0');
                                    }
                                    else
                                    {
                                        $('#txt_current_reading').val(result.previous_reading);
                                        $('#txt_previous_reading').val(result.previous_reading);
                                        $('#txt_total_units').val('0');
                                    }
                                }
                            });
                if(meter_status_check==4)
                        {
                        
                         $('#txt_previous_reading').prop('disabled',false);
                         $("#txt_current_reading").prop('disabled',false);
                          waterChargeCalculate();
                          
                        }
                        else
                        {
                          $('#txt_previous_reading').prop('disabled',true);
                          $("#txt_current_reading").prop('disabled',true); 
                         // day_calculate();
                          waterChargeCalculate();
                          
                        }
             });
          $('#generated_by').on("change",function(event) {
                    
                     $('#bill_btn').removeAttr('data-target');
                    var meter_status_check= $("#txt_meter_status option:selected").val();
                    if(meter_status_check!=4)
                    {
                      
                      waterChargeCalculate();
                    }
             });
    
        
    
	});
      
             

	
    function searchBill()
	{
                
                $("#generate_bill").hide();
                $('#bill_info').html('');
                $('#status_bill').html('');
                 $('#bill_btn').html('');
                 $('#pay_bill_form')[0].reset();
		var siteUrl = '<?php echo url('/'); ?>';
                $('#bill_btn').prop('disabled', true);
		var sequenceNumber = $('#sequence_number').val();
		var meterNumber = $('#meter_number').val();
                var door_no = $('#door_no').val();
		if(sequenceNumber == '' && meterNumber=='' && door_no=='') {
			$("#error-field").html('Please enter atleast one search criteria');
			$("#generate_bill").hide();
		} else {
                        $('#result_display').html("");
			$("#error-field").html('');
			$(".loading").show();
			var dataString = {'meter_number':meterNumber,'sequence_number':sequenceNumber,'door_no':door_no};
			$.ajax({
				type: "POST",
				url: siteUrl + "/admin/customer_bill_Info",
				data: dataString,
				cache: "false",
				success: function (result) {
                                    $(".loading").hide();
                                       if(result != '') {
                                              $(document).ready(function() {
                                                        
                                                        day_calculate();
                                                    });
                                           
						$('#span_sequence_number').html(result.sequence_number);
						$('#span_meter_number').html(result.meter_no);
						$('#span_consumer_name').html(result.consumer_name);
						$('#txt_corpward_name').val(result.corp_name);
                                                $('#corpward_id').val(result.corpward_id);
                                                $('#bill_type').val(result.billtype)

						if(result.date_of_reading != '') {
							$('#txt_date_of_reading').val(result.date_of_reading);
						} else 
						{
							$('#txt_date_of_reading').val('');
						}
						if(result.previous_billing_date != '') {
							$('#txt_previous_bill_date').val(result.previous_billing_date);
						} else 
						{
							$('#txt_previous_bill_date').val('');
						}
						
						$('#txt_consumer_name').val(result.consumer_name);
						$('#txt_sequence_number').val(result.sequence_number);
						$('#txt_door_number').val(result.door_no);
						$('#txt_ward').val(result.ward_name);
                                                $('#txt_ward_id').val(result.ward_id);
						$('#txt_connection_type').val(result.connection_name);
                                                $('#txt_connection_type_id').val(result.connection_type_id);
                                                $('#no_of_flats').val(result.no_of_flats);
						$('#txt_meter_number').val(result.meter_no);
						$('#txt_bill_number').val(result.bill_no);
                                                $('#mnr_count').val(result.mnr_count); 
						if(result.payment_due_date != "") {
							$('#txt_due_date').val(result.payment_due_date);
						} else 
						{
							$('#txt_due_date').val('');
						}
						
                                                if(result.billtype==1)
                                                {
                                                   /* $('#bill_info').html('Bill Already generated for this month !');
                                                    $('#status_bill').html('Update the bill');
                                                    $('#txt_total_units').val(result.total_unit_used);
                                                    $('#txt_days_used').val(result.no_of_days_used);
                                                    $("#bill_btn").html('Update Bill'); */
                                                    $("#generate_bill").hide();
                                                   

                                                    $('#empty_box').show();
                                                    $('#result_display').html(result.message);
                                                    return;
		
                                                }
                                                else
                                                {   
                                                    $("#generate_bill").show();
                                                    $('#empty_box').hide();
                                                   // $('#bill_info').html('Bill generated 30 days before !');
                                                    // $('#status_bill').html('Generate New Bill');
                                                     $("#bill_btn").html('Save Bill'); 
                                                }
						
						$('#txt_previous_reading').val(result.previous_reading);
						
						$('#txt_meter_status').val(result.meter_status);
                                                var meter_status_check=result.meter_status;

                                                if(meter_status_check=='1')
                                                {
                                                     anystatus();
                                                     $('#txt_current_reading').val(result.previous_reading);
                                                     $('#txt_total_units').val('0');
                                                    
                                                }
                                               else if(meter_status_check=='2')
                                                {
                                                    
                                                    $("select option:contains(MNR)").prop("disabled",false);
                                                    $("select option:contains(NORMAL)").prop("disabled",false);
                                                    $('#txt_current_reading').val('0');
                                                    $('#txt_total_units').val('0');
                                            
                                                }
                                                else if(meter_status_check=='3')
                                                {
                                                  
                                                    anystatus();
                                                    $('#txt_current_reading').val(result.previous_reading);
                                                    $('#txt_total_units').val('0');
                                                }
                                                
                                                else if(meter_status_check=='4')
                                                {
                                                   
                                                    anystatus();
                                                    $("#txt_previous_reading").prop("disabled", "");
                                                    $('#txt_current_reading').prop('disabled', "");
                                                    $('#txt_total_units').val('0');
                                                   
                                                }
                                                else if(meter_status_check=='5')
                                                {
                                                    anystatus();
                                                   // $("select option:contains(MNR)").prop("disabled",false);
                                                    $('#txt_current_reading').val(result.previous_reading);
                                                    $('#txt_total_units').val('0');
                                                }
                                                else if(meter_status_check=='6')
                                                {
                                                    $("select option:contains(DL)").prop("disabled",false);
                                                    $("select option:contains(NORMAL)").prop("disabled",false);
                                                    $("select option:contains(NOT LEGIBLE)").prop("disabled",false);
                                                    $("select option:contains(MNR)").prop("disabled",false);
                                                    $('#txt_current_reading').val(result.previous_reading);
                                                    $('#txt_total_units').val('0');
                                                }
                                                else if(meter_status_check=='7')
                                                {
                                                   anystatus();
                                                    $('#txt_current_reading').val(result.previous_reading);
                                                    $('#txt_total_units').val('0');
                                                }
                                                else if(meter_status_check=='8')
                                                {
                                                  
                                                   anystatus();
                                                    $('#txt_current_reading').val(result.previous_reading);
                                                    $('#txt_total_units').val('0');
                                                }
                                     
                                                
						$('#txt_supervisor_charges').val(result.supervisor_charge);
						$('#txt_fixed_charges').val(result.fixed_charge);
						$('#txt_other_charges').val(result.other_charge);
						$('#txt_penalty').val(result.penalty);
						$('#txt_returned_amount').val(result.refund_amount);
						$('#txt_cess').val(result.cess);
						$('#txt_ugd_cess').val(result.ugd_cess);
						$('#txt_arrears').val(result.arrears);
                                                
				
						$('#generate_bill').show();
						$('#generate_bill_result1').show();
						$('#generate_bill_result2').hide();
						$('#generate_bill_result2').html('');
						$('#div_print_button1').show();
						$('#div_print_button2').show(); 
                                                setTimeout(function(){ waterChargeCalculate();},200)
                                               
					} else {
						$('#generate_bill').show();
						$('#generate_bill_result1').hide();
						$('#generate_bill_result2').show();
						$('#generate_bill_result2').html('No results found');
						$('#div_print_button1').hide();
						$('#div_print_button2').hide();
					}
				}
			});
			
		} 
	} 
	
	function printNewBill() {
	
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
                $('#approved_by_name').html($("#generated_by option:selected").text());

	}
	
	function print_data()
	{
		var siteUrl = '<?php echo url('/'); ?>';
		var sequenceNumber  = $('#sequence_number').val();		
		var meterNumber = $('#meter_number').val();
              printPageUrl = siteUrl + '/admin/print_new_bill' + '?sn=' + sequenceNumber+'&mn='+meterNumber;
               $("#printPageUrl").attr("href", printPageUrl);
	}
        
        function anystatus()
        {
            
             $("select option:contains(MNR)").prop("disabled",false);
             $("select option:contains(NOT LEGIBLE)").prop("disabled",false);
             $("select option:contains(NORMAL)").prop("disabled",false);
             $("select option:contains(NA)").prop("disabled",false);
             $("select option:contains(DC)").prop("disabled",false);
             $("select option:contains(ML)").prop("disabled",false);
             $("select option:contains(DL)").prop("disabled",false);
             $("select option:contains(MNR)").prop("disabled",false);
        }
        

$('#txt_current_reading').on('input', function() {
    readingChange();
    
});

$('#txt_current_reading').on('input', function() {
    readingChange();
    
});
 $('#txt_arrears').on('input', function() {
     waterChargeCalculate();
     
});

function day_calculate()
{
    $('#date_error').html('');
    var start = $('#txt_previous_bill_date').val();
    var end   = $('#txt_date_of_reading').val();
    var eDate = new Date(end);
    var sDate = new Date(start);
    
    if(end!= '' && start!= '' && sDate> eDate)
      {
          $('#date_error').text("Please ensure that the To Date is greater than or equal to the From Date.");
          $('#bill_btn').prop('disabled', true);
        
      }
    else
     { 
        var days   = (eDate - sDate)/1000/60/60/24;
        var total_days=parseInt(days) || 0;
        $('#txt_days_used').val(total_days);
     }

}

function readingChange()
{
        $('#large_error').html('');
          $('#txt_total_units').val('');  
        var previous_reading;
        var current_reading;
        previous_reading = parseInt($('#txt_previous_reading').val()) || 0;
        current_reading = parseInt($('#txt_current_reading').val()) || 0;
       
        if(current_reading < previous_reading)
        {
            $('#large_error').html('Current reading should be greater than previous reading');
            $('#bill_btn').prop('disabled', true);
          
            
        }
        else
        {
             var result = current_reading - previous_reading;
             $('#txt_total_units').val(result);  
             waterChargeCalculate();
        }
}
    function waterChargeCalculate()
    {
        
                                    
            $('#txt_water_charges').val('');  
            $('#txt_total_due').val('');
            $('#txt_roundoff').val('')
            $('#txt_total_amount').val('');
            $('#txt_penalty').val('');
            $('#bill_btn').prop('');
            $('#txt_other_charges').val('');
            $('#txt_fixed_charges').val('');

            var siteUrl = '<?php echo url('/'); ?>';
            var total_unit_used=$('#txt_total_units').val();  
            var con_type=$('#txt_connection_type_id').val();
            var total_unit_used=$('#txt_total_units').val();
            var door_number=$('#txt_door_number').val();
            var meter_number=$('#txt_meter_number').val();
            var days_used=$('#txt_days_used').val();
            var old_water_charge=$('#txt_water_charges').val();
            var sequenceNumber=$('#txt_sequence_number').val();   
            var superv_charge=$('#txt_supervisor_charges').val();
            var fixed_charges=$('#txt_fixed_charges').val();
            var other_charges=$('#txt_other_charges').val();
            var penalty=$('#txt_penalty').val();
            var returned_amount=$('#txt_returned_amount').val();
            var cess=$('#txt_cess').val();
            var ugd_cess=$('#txt_ugd_cess').val();
            var arrears=$('#txt_arrears').val();
            var bill_type=$('#bill_type').val();
            var meter_status= $("#txt_meter_status option:selected").val();
            var mnr_count=$('#mnr_count').val(); 
             var dataString = {
                 'con_type':con_type,
                 'total_unit_used':total_unit_used,
                 'days_used':days_used,
                 'sequenceNumber':sequenceNumber,
                 'superv_charge':superv_charge,
                 'fixed_charges':fixed_charges,
                 'other_charges':other_charges,
                 'penalty':penalty,
                 'returned_amount':returned_amount,
                 'cess':cess,
                 'ugd_cess':ugd_cess,
                 'arrears':arrears,
                 'bill_type':bill_type,
                 'meter_status':meter_status,
                 'mnr_count':mnr_count
                 };
			$.ajax({
				type: "POST",
				url: siteUrl + "/admin/calculate_water_charge",
				data: dataString,
				cache: "false",
				success: function (result) { 
                                                                      
                                          $('#txt_water_charges').val(result.water_charge);  
                                          $('#txt_total_due').val(result.total_due);
                                          $('#txt_roundoff').val(result.round_off)
                                          $('#txt_total_amount').val(result.total_amount);
                                          $('#txt_penalty').val(result.penalty);
                                          $('#bill_btn').prop('disabled', false);
                                          $('#txt_other_charges').val(result.other_charges);
                                          $('#txt_fixed_charges').val(result.fixed_charges);
                                          
                                  }
                                
                         });

    }
function generateNewBill()
{

    var previous_reading;
    var current_reading;
       previous_reading = parseFloat($('#txt_previous_reading').val());
       current_reading = parseFloat($('#txt_current_reading').val());
    var start = $('#txt_previous_bill_date').val();
    var end   = $('#txt_date_of_reading').val();
    var meter_status= $("#txt_meter_status option:selected").val();
    var eDate = new Date(end);
    var sDate = new Date(start);
    
       if(isNaN(current_reading))
       {
          current_reading=0; 
       }
      
       if(current_reading < previous_reading && meter_status==4)
       {
           $('#large_error').html('Current reading should be greater than previous reading');
       }

     else if(end!= '' && start!= '' && sDate> eDate)
      {
          $('#date_error').text("Please ensure that the To Date is greater than or equal to the From Date.");
         $('#bill_btn').prop('disabled', true);
        
      }
      else
      {
             errorClear();
             var siteUrl = '<?php echo url('/'); ?>';
             var paybillForm = $("#pay_bill_form");
            
             var generated_by= $("#generated_by option:selected").val();
             var reviewer= $("#reviewer option:selected").val();
             var previous_reading=$('#txt_previous_reading').val();
             var current_reading=$('#txt_current_reading').val();
             var days_used=$('#txt_days_used').val();
             var amount=$('#txt_total_amount').val();
             var units=$('#txt_total_units').val(); 
             var formData = paybillForm.serialize()+'&meter_status=' + meter_status+'&previous_reading=' +previous_reading+'&current_reading='+current_reading; 
             
            
             if($("#generated_by").is(":visible")){
                if(generated_by==='' || generated_by===undefined)
                {
                    $('#gen_by-error').html('Generated By Missing');
                    return;
                    //$('#bill_btn').removeAttr('data-target');

                }
            }
             if($("#reviewer").is(":visible")){
                if( reviewer==='' || reviewer===undefined)
                {
                    $('#reviewer-error').html('Reviewer Missing');
                    return;
                    //$('#bill_btn').removeAttr('data-target');

                }
            }
            
    
             if (current_reading==='')
             {
                   $('#current_reading-error').html('Current Reading Missing');
             }
             else if (days_used==='')
             {
                   $('#no_of_days-error').html('No of Days Missing');
             }
             else if(units==='')
             {
                 
                 $('#total_unit_used-error').html('Unit used Missing');
             } 
             else if (amount==='')
             {
                   $('#total_amount-error').html('Total Amount Missing');
             }
             
             else
             {
                 errorClear();
                   //$('#bill_btn').attr('data-target','#challan-genrate');
                     $.ajax({
				type: "POST",
				url: siteUrl + "/admin/generate_water_bill",
				data: formData,
				cache: "false",
				success: function (result) { 
                                    
                                                    $('#generate_bill_status').html("");
                                                    if(result.success == 0) {
                                                        $('#generate_bill_status').html(result.message);
                                                        return;
                                                        
                                                    }
                                                    
                                                    @if($role1->category_name=="MCC") 
                                                        window.location.reload();
                                                    @else
                                                        $("#challan-genrate").modal("show");
                                                         printNewBill();
                                                    @endif
                                                    
                                                    
                                                   

                                          
                                  }
                                
                         });  
             } 

       
         
      }
     
}

function errorClear()
{
     $('#gen_by-error').html('');
     $('#previous_reading-error').html('');
     $('#current_reading-error').html('');
     $('#no_of_days-error').html('');
     $('#total_unit_used-error').html('');
     $('#total_amount-error').html('');
       
     
}
</script>
@endsection
