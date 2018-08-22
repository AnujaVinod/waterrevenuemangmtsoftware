@extends('layouts.admin_master')
@section('content')
<style>
    .amount_align
    {
        text-align: right;
    }
</style>
	<section class="content-header">
		  <h1>
			Payment
		  </h1>
		  <ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
			<li class="active">Here</li>
		  </ol>
		</section>

<div id="data">
     <?php $role1 = Helper::getRole();
                                $sub_category = $role1->sub_category_name;
                                $category = $role1->category_name;
                                if((strcasecmp($sub_category,"MCC") == 0)|| (strcasecmp($category,"Super Admin") == 0) || (strcasecmp($sub_category,"Administrator") == 0)|| (strcasecmp($category,"Bank") != 0) && (strcasecmp($sub_category,"Editor") == 0))
                                {
                                    $display_val=1; //Logged in user is either MCC or Super Admin
                                }
                                else if(strcasecmp($category,"Bank" == 0))
                                {
                                     $display_val=2; //Logged in user is Bank
                                }

                                ?>    
    <input type="hidden"  id="category_name" value ="<?php echo $display_val;?>" >
		<!-- Main content -->
		<section class="content container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Billing Details</h3>
                                                         
							<!-- /.box-tools -->
						</div>
						<div class="box-body">
                                                     <span class="text-danger">
                                                                       <strong id="error-field"></strong>
                                                                      </span>
                                                  
							<div class="form-group col-md-4">
								<b>Sequence Number</b>
								<input type="text" class="form-control" id="seq_number" name="seq_number" required="required" >
							</div>
							<div class="form-group col-md-3">
								<b>Meter Number</b>
								<input type="text" class="form-control" id="meter_no" name="meter_no" required="required" >
							</div>
                                                           
                                                         	
							<div class="form-group col-md-1">
								<button type="button" class="btn btn-warning btn-flat margin pull-right btn-margin" data-toggle="collapse" data-target="" onclick="searchPayment()">Search</button>
							</div>
                                                          
                                                          
                                                   
						</div>
					</div>
				</div>
			
				<div id="payment_table_div" style="display:none;" class="col-md-12">
					<div class="box box-danger">
						<div class="box-header with-border">
							<h3 class="box-title">Billing Details</h3>
								<!-- /.box-tools -->
						</div>
						<div class="box-body">
							<div  class="box-body table-responsive no-padding ">
							
                                                               <table id="mytable"  class="table table-responsive table-bordered table-hover table-striped">
                                                                    <thead>
                                                                      <tr>
											<th>Sequence Number</th>
											<th>Name</th>
                                                                                        <th>Meter No</th>
											<th>Current Balance</th>
											<th>Bill Reading Date</th>
											<th>Due Date</th>
                                                                                        <th>Paid Date</th>
											<th>Payment Status</th>
											<th>Payment</th>
										</tr>
                                                                    </thead>                            
                                                                </table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
                </div>
   
					<div class="modal fade" id="verify" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
									<h4 class="modal-title custom_align" id="Heading">Payment Verification</h4>
								</div>
								<div class="modal-body">
                                                                      <form class="form-horizontal" method="POST" id="verify_info_form">
									<div class="box box-warning">
										<div class="box-header with-border">
											<h3 class="box-title">Update Payment Information</h3>
													<!-- /.box-tools -->
											</div>
                                                                          
											<div class="box-body bg-gray-bg">
												<div class="form-group col-md-12">
                                                                                                    <input type="hidden" class="form-control" id="hidden_mr_id" value="" name="hidden_mr_id"  >
                                                                                                    <input type="hidden" class="form-control" id="hidden_pay_id" value="" name="hidden_pay_id"  >
													<b>Name Of Bank</b>
													<input type="text" class="form-control" id="modal_bank_name" name="bank_name" required="required" >
                                                                                                          <span class="text-danger">
                                                                                                            <strong id="bank_name-error"></strong>
                                                                                                             </span>
												</div>
												<div class="form-group col-md-12">
													<b>Name Of Branch</b>
													<input type="text" class="form-control" id="modal_branch_name" name="branch_name" required="required">
                                                                                                         <span class="text-danger">
                                                                                                        <strong id="branch_name-error"></strong>
                                                                                                        </span>
												</div>					
												<div class="form-group col-md-12">
													<b>Transaction Number</b>
													<input type="text" class="form-control" id="modal_trans_number" name="transaction_number" required="required" >
                                                                                                        <span class="text-danger">
                                                                                                            <strong id="trans-error"></strong>
                                                                                                      </span>
												</div>
												<div class="form-group col-md-12">
												<b>Paid Date</b>
													<div class="input-group date">
														<div class="input-group-addon">
															<i class="fa fa-calendar"></i>
														</div>
														<input type="text" class="form-control pull-right" id="date_picker" name="payment_date">
                                                                                                                  <span class="text-danger">
                                                                                                                <strong id="date-error"></strong>
                                                                                                                </span>
													</div>
												</div>
												<div class="form-group col-md-12">
													<b>Remarks</b>
													<textarea class="form-control" rows="2" id="remarks" name="remarks" ></textarea>
                                                                                                       
												</div>
											</div>
										</div>
										<button id="verify_submit" type="button" class="btn btn-danger" data-toggle="collapse" data-target="#verify_info" aria-expanded="true">Submit</button>
										<div id="verify_info" class="out collapse" aria-expanded="true" style="display:none;">
											<center><h1 class="text-red">Thank You</h1>
											<h3 class="text-red">Payment Verified Successfully</h3></center><br>
											<center> <button data-dismiss="modal" type="button" class="btn btn-default">Close</button></center>
										</div>
                                                                                </form>
								</div>
							</div>
									<!-- /.modal-content --> 
						</div>
									  <!-- /.modal-dialog --> 
					</div>

					
					<div class="modal fade" id="payment_detail" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
									<h4 class="modal-title custom_align" id="Heading">Payment Details</h4>
								</div>
								<div class="modal-body">
								<div class="col-md-8 no-padding">
									<div class="col-md-4">
										<b>CorpWard</b>
										<input type="text" class="form-control" id="corp_name" required="required"  readonly>
									</div>
									<div class="form-group col-md-4">
										<b>Bill Date</b>
										<input type="text" class="form-control" id="date_of_reading" required="required"  readonly>
									</div>
									<div class="form-group col-md-4">
										<b>Previous Bill date</b>
										<input type="text" class="form-control" id="previous_billing_date" required="required"  readonly>
									</div>
									<div class="form-group col-md-4">
										<b>Name</b>
										<input type="text" class="form-control" id="cus_name" required="required"  readonly>
									</div>
									<div class="form-group col-md-4">
										<b>Sequence Number </b>
										<input type="text" class="form-control" id="sequence_number" required="required"  readonly>
									</div>
									
									<div class="form-group col-md-4">
										<b>Door No</b>
										<input type="text" class="form-control" id="door_no" required="required"  readonly>
									</div>
									<div class="form-group col-md-4">
										<b>Ward</b>
										<input type="text" class="form-control" id="ward_name" readonly>
									</div>
									<div class="form-group col-md-4">
										<b>Connection Type</b>
										<input type="text" class="form-control" id="connection_name" required="required"  readonly>
									</div>
									<div class="form-group col-md-4">
										<b>Meter No</b>
										<input type="text" class="form-control" id="view_meter_no"  readonly>
									</div>
									<div class="form-group col-md-4">
										<b>Bill No</b>
										<input type="text" class="form-control" id="bill_no" required="required"  readonly>
									</div>
									<div class="form-group col-md-4">
										<b>Due date</b>
										<input type="text" class="form-control" id="payment_due_date" required="required"  readonly>
									</div>
									<div class="form-group col-md-4">
										<b>Current Reading</b>
										<input type="text" class="form-control" id="current_reading" required="required" readonly>
									</div>
									<div class="form-group col-md-4">
										<b>Previous Reading</b>
										<input type="text" class="form-control" id="previous_reading" required="required"  readonly>
									</div>
									<div class="form-group col-md-4">
										<b>Total Used Units</b>
										<input type="text" class="form-control" id="total_unit_used" required="required"  readonly>
									</div>
									<div class="form-group col-md-4">
										<b>Meter Status</b>
										<input type="text" class="form-control" id="meter_status" required="required" readonly>
									</div>
									<div class="form-group col-md-4">
										<b>No Days used</b>
										<input type="text" class="form-control" id="no_of_days_used" required="required"  readonly>
									</div>
									<div class="form-group col-md-4">
										<b>Water Charges </b>
										<input type="text" class="form-control amount_align" id="water_charge" required="required"  readonly>
									</div>
									<div class="form-group col-md-4">
										<b>Supervisor Charges</b>
										<input type="text" class="form-control amount_align" id="supervisor_charge" required="required"  readonly>
									</div>
									<div class="form-group col-md-4">
										<b>Fixed Charges </b>
										<input type="text" class="form-control amount_align" id="fixed_charge" required="required"  readonly>
									</div>
									<div class="form-group col-md-4">
										<b>Other Charges</b>
										<input type="text" class="form-control amount_align" id="other_charges" required="required"  readonly>
									</div>
									<div class="form-group col-md-4">
										<b>Penalty</b>
										<input type="text" class="form-control amount_align" id="penalty" required="required"  readonly>
									</div>
									<div class="form-group col-md-4">
										<b>Returned Amount</b>
										<input type="text" class="form-control amount_align" id="returned_amount" required="required" readonly>
									</div>
									<div class="form-group col-md-4">
										<b>Cess</b>
										<input type="text" class="form-control amount_align" id="cess" required="required"  readonly>
									</div>
									<div class="form-group col-md-4">
										<b>UGD Cess</b>
										<input type="text" class="form-control amount_align" id="ugd_cess" required="required"  readonly>
									</div>
									<div class="form-group col-md-4">
										<b>Arrears</b>
										<input type="text" class="form-control amount_align" id="arrears" required="required"  readonly>
									</div>
									<div class="form-group col-md-4">
										<b>Total Due</b>
										<input type="text" class="form-control amount_align" id="total_due" required="required"  readonly>
									</div>
									<div class="form-group col-md-4">
										<b>Round Off</b>
										<input type="text" class="form-control amount_align" id="round_off" required="required"  readonly>
									</div>
                                                                    <div class="form-group col-md-4">
										<b>Total Amount</b>
										<input type="text" class="form-control amount_align" id="total_amount" required="required" readonly>
									</div>
									<!--<div class="form-group col-md-4">
										<b>Advance Amount</b>
										<input type="text" class="form-control amount_align" id="advance_amount" required="required" readonly>
									</div> -->
									
									</div>
									
									<div class="col-md-4 no-padding">
										<div class="box box-warning">
											<div class="box-header with-border">
												<h3 class="box-title">Bank Information</h3>
													<!-- /.box-tools -->
											</div>
											<div class="box-body bg-gray">
												<div class="form-group">
													<b>Challan No</b>
													<input type="text" class="form-control" id="challan_no" required="required" readonly>
												</div>
												<div class="form-group">
													<b>Name Of Bank</b>
													<input type="text" class="form-control" id="bank_name" required="required"readonly>
												</div>
												<div class="form-group">
													<b>Name Of Branch</b>
													<input type="text" class="form-control" id="branch_name" required="required" readonly>
												</div>					
												<div class="form-group">
													<b>Cheque / DD information</b>
													<input type="text" class="form-control" id="cheque_dd" required="required"  readonly>
												</div>
												<div class="form-group">
													<b>Remarks</b>
													<textarea class="form-control" rows="2" id="add_remarks"  readonly></textarea>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="clear"></div>
							</div>
									<!-- /.modal-content --> 
						</div>
									  <!-- /.modal-dialog --> 
					</div>


<script>var siteUrl = '<?php echo url('/'); ?>';</script>
<script>
    
$(document).ready(function() {
      $('#mytable').dataTable().fnDestroy();
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
})
        var cat_id=$('#category_name').val();
        if(cat_id == 1)
        {
            $('#generate_bill').css('display', 'block');
            $('#payment_table_div').css('display', 'block');
            loadDatatable();
        }
        else
        {
            
           // $('#generate_bill').css('display', 'none');
          // $('#generate_bill').hide();
           // $('#payment_table_div').css('display', 'none');
        }
});

function loadDatatable() {

    dtTable = $('#mytable').DataTable({
       "processing": true,
       "language": {
                "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
        },
       "serverSide": true,
       "destroy": true,
       "ajax":{
                     "url": siteUrl + "/admin/getpaymentdetails",
                     "dataType": "json",
                     "type": "POST"
                   },
       "columns":[         
           { "data": "sequence_number" },
           { "data": "name" },
           { "data": "meter_no"},
           { "data": "total_amount" ,className: 'amount_align'},
		   { "data": "date_of_reading"},
           { "data": "payment_due_date"},
           { "data": "payment_date"},
           { data: 'paystatus', name: 'paystatus', orderable: false, searchable: false},
           {data: 'payment', name: 'payment', orderable: false, searchable: false}
       ]
    });
}

function searchPayment()
{
    var name=$("#name").val();
    var seq_no=$("#seq_number").val();
    var meter_no=$("#meter_no").val();
     $('#error-field').text('');
    if(seq_no=='' && meter_no=='')
    {
      $('#error-field').text('Atleast one field required')
    }
    else
    {
       
        $('#payment_table_div').css('display', 'block');
         $('#mytable').dataTable().fnDestroy();
                dtTable = $('#mytable').DataTable({
                   "processing": true,
                   "language": {
                        "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                    },
                   "serverSide": true,
                    "destroy": true,
                   "ajax": {
                            'url': siteUrl + "/admin/payment_search",
                            'type': "POST",
                           
                             'data': {
                                      name: name,
                                      seq_number: seq_no,
                                      meter_no: meter_no
                                  },
                             
                            },
                                           "columns":[         
                                               { "data": "sequence_number",name: 'consumer_connection.sequence_number' },
                                               { "data": "name",name: 'consumer_connection.name' },
                                               { "data": "meter_no",name: 'consumer_connection.meter_no'},
                                               { "data": "total_amount",className: 'amount_align',name: 'meter_reading.total_amount' },
					      { "data": "date_of_reading",name: 'meter_reading.date_of_reading'},
                                               { "data": "payment_due_date",name: 'meter_reading.payment_due_date'},
                                               { "data": "payment_date",name: 'payment_history.payment_date'},
                                               { data: 'paystatus', name: 'paystatus', orderable: false, searchable: false},
                                               {data: 'payment', name: 'payment', orderable: false, searchable: false}
                                           ]

                       });
               }
        }

$('body').on('click', '#verifyBtn', function () {

    var meter_reading_id = $(this).data("value");
      
                 $('#modal_bank_name').val('');
                 $('#modal_branch_name').val('');
                 $('#date_picker').val('');
    $.ajax({
        url: siteUrl + "/admin/verifypayment",
        type: 'POST',
        data: {'meter_reading_id':meter_reading_id},
        success: function (data) {
           
            if (data.success) {
                 
                  $('#hidden_mr_id').val(data.bank_info.meter_reading_id);
                 $('#hidden_pay_id').val(data.bank_info.payment_id);
                 $('#modal_bank_name').val(data.bank_info.bank_name);
                 $('#modal_branch_name').val(data.bank_info.branch_name);
                // $('#date_picker').val(data.bank_info.payment_date);
                 $("#verify").modal('show');
            }
        },
    });
 });
$('body').on('click', '#verify_submit', function (e) {

     var billverifyForm = $("#verify_info_form");
     var formData = billverifyForm.serialize();
     $('#bank_name-error').html("");
     $('#branch_name-error').html("");
     $('#trans-error').html("");
     $('#date-error').html("");

         $.ajax({
        url: siteUrl + "/admin/updateverifypayment",
        type: 'POST',
        data: formData,
        async: true,

        success: function (data) {
             if (data.errors) {

                if (data.errors.bank_name) {
                    $('#bank_name-error').html(data.errors.bank_name[0]);
                }
                 if (data.errors.branch_name) {
                    $('#branch_name-error').html(data.errors.branch_name[0]);
                }
                 if (data.errors.transaction_number) {
                    $('#trans-error').html(data.errors.transaction_number);
                }
              
                 if (data.errors.payment_date) {
                    $('#date-error').html(data.errors.payment_date[0]);
                }
             
            }
            if (data.success) {
                 
                $("#verify_info").modal('show');
                 $('#mytable').dataTable().fnDestroy();
                 $('#verify_submit').prop('disabled', true);
                 $("#verify_info").css({ 'height': 160 + "px" });
                 loadDatatable();
                
            }
        },
    });
});
$('body').on('click', '#viewBtn', function () {

    var meter_reading_id = $(this).data("value");
    $.ajax({
        url: siteUrl + "/admin/getConsumerInfo",
        type: 'POST',
        data: {'meter_reading_id':meter_reading_id},
        success: function (data) {
          
            if (data.errors) {
                alert('error');
                       
            }
            if (data.success) {

                 $('#payment_date').val(data.pay_info.payment_date);
                 $('#payment_status').val(data.pay_info.payment_status);
                 $('#sequence_number').val(data.pay_info.sequence_number);
                 $('#cus_name').val(data.pay_info.name);
                 $('#door_no').val(data.pay_info.door_no);
                 $('#view_meter_no').val(data.pay_info.meter_no);
                 $('#ward_name').val(data.pay_info.ward_name);
                 $('#corp_name').val(data.pay_info.corp_name);
                 $('#meter_status').val(data.pay_info.meter_status);
                 $('#connection_name').val(data.pay_info.connection_name);
                 $('#bank_name').val(data.pay_info.bank_name);
                 $('#branch_name').val(data.pay_info.branch_name);
                 $('#cheque_dd').val(data.pay_info.cheque_dd);
                 $('#add_remarks').val(data.pay_info.remarks);
                 $('#challan_no').val(data.pay_info.challan_no);
                 $('#date_of_reading').val(data.pay_info.date_of_reading);
                 $('#bill_no').val(data.pay_info.bill_no);
                 $('#meter_rent').val(data.pay_info.meter_rent);
                 $('#payment_due_date').val(data.pay_info.payment_due_date);
                 $('#previous_billing_date').val(data.pay_info.previous_billing_date);
                 $('#payment_last_date').val(data.pay_info.payment_last_date);
                 $('#total_unit_used').val(data.pay_info.total_unit_used);
                 $('#no_of_days_used').val(data.pay_info.no_of_days_used);
                 $('#previous_reading').val(data.pay_info.previous_reading);
                 $('#current_reading').val(data.pay_info.current_reading);
                 $('#no_of_flats').val(data.pay_info.no_of_flats);
                 $('#water_charge').val(data.pay_info.water_charge);
                 $('#supervisor_charge').val(data.pay_info.supervisor_charge);
                 $('#other_charges').val(data.pay_info.other_charges);
                 $('#returned_amount').val(data.pay_info.refund_amount);
                 $('#other_title_charge').val(data.pay_info.other_title_charge);
                 $('#fixed_charge').val(data.pay_info.fixed_charge);
                 $('#penalty').val(data.pay_info.penalty);
                 $('#cess').val(data.pay_info.cess);
                 $('#ugd_cess').val(data.pay_info.ugd_cess);
                 $('#arrears').val(data.pay_info.arrears);
                 $('#total_due').val(data.pay_info.total_due);
                 $('#round_off').val(data.pay_info.round_off);
                 $('#total_amount').val(data.pay_info.total_amount);
                // $('#advance_amount').val(data.pay_info.advance_amount);
                 $("#payment_detail").modal('show');
                     
            }
        },
    });
    });
    
 $(document).on('click','.payBtn',function(e){

 e.preventDefault();

 var meter_reading_id = $(this).data("value");
   
$.ajax({
        url: siteUrl + "/admin/paybill",
        type: 'POST',
        data: {'meter_reading_id':meter_reading_id},
        success: function (data) {
            $('#data').empty();
            $('#data').html(data);
        },
    });


});

</script>

@endsection