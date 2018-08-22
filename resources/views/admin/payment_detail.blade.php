<style>
    .amount_align
    {
        text-align: right;
    }
</style>
  <?php $role1 = Helper::getRole();
        $sub_category = $role1->sub_category_name;
        $category = $role1->category_name;
?>
@foreach ($pay_info as $list)
    <!-- Main content -->
    <section class="content container-fluid">
		<div class="col-md-8 no-padding">
			<div class="box box-info">
				<div class="box-header with-border">
					<h3 class="box-title">Billing & Payment</h3>
						<!-- /.box-tools -->
				</div>
				<div class="box-body no-padding">
				<br>
                              
					<div class="col-md-4">
						<b>CorpWard</b>
                                                <input type="text" class="form-control" id="" value="{{ $list->corp_name }}"  name="" required="required" readonly>
					</div>
					<div class="form-group col-md-4">
						<b>Bill Date</b>
						<input type="text" class="form-control" id="date_of_reading" value="{{ $list->date_of_reading }}" name="date_of_reading" required="required"  readonly>
					</div>
					<div class="form-group col-md-4">
						<b>Previous Bill date</b>
						<input type="text" class="form-control" id="previous_billing_date" value="{{ $list->previous_billing_date }}" name="previous_billing_date" required="required" readonly>
					</div>
					<div class="form-group col-md-4">
						<b>Name</b>
						<input type="text" class="form-control" id="name" value="{{ $list->name }}" name="name" required="required"  readonly>
					</div>
					<div class="form-group col-md-4">
						<b>Sequence Number </b>
						<input type="text" class="form-control" id="sequence_number" value="{{ $list->sequence_number }}" name="sequence_number" required="required"  readonly>
					</div>
					
					<div class="form-group col-md-4">
						<b>Door No</b>
						<input type="text" class="form-control" id="door_no" value="{{ $list->door_no }}" name="door_no" required="required" readonly>
					</div>
					<div class="form-group col-md-4">
						<b>Ward</b>
						<input type="text" class="form-control" id="ward_name" value="{{ $list->ward_name }}" name="ward_name" readonly>
					</div>
					<div class="form-group col-md-4">
						<b>Connection Type</b>
						<input type="text" class="form-control" id="connection_type_name" value="{{ $list->connection_name }}" name="connection_name" required="required"  readonly>
					</div>
					<div class="form-group col-md-4">
						<b>Meter No</b>
						<input type="text" class="form-control" id="meter_no" value="{{ $list->meter_no }}" name="meter_no"  readonly>
					</div>
					<div class="form-group col-md-4">
						<b>Bill No</b>
						<input type="text" class="form-control" id="bill_no" value="{{ $list->bill_no }}" name="bill_no" required="required"  readonly>
					</div>
					<div class="form-group col-md-4">
						<b>Payment Due date</b>
						<input type="text" class="form-control" id="payment_due_date" value="{{ $list->payment_due_date }}" name="payment_due_date" required="required"  readonly>
					</div>
					<div class="form-group col-md-4">
						<b>Current Reading</b>
						<input type="text" class="form-control" id="current_reading" value="{{ $list->current_reading }}" name="current_reading" required="required"  readonly>
					</div>
					<div class="form-group col-md-4">
						<b>Previous Reading</b>
						<input type="text" class="form-control" id="previous_reading"  value="{{ $list->previous_reading }}" name="previous_reading" required="required"  readonly>
					</div>
					<div class="form-group col-md-4">
						<b>Total Used Units</b>
						<input type="text" class="form-control" id="total_unit_used" value="{{ $list->total_unit_used }}" name="total_unit_used" required="required"  readonly>
					</div>
					<div class="form-group col-md-4">
						<b>Meter Status</b>
						<input type="text" class="form-control" id="meter_status" value="{{ $list->meter_status }}" name="meter_status" required="required"  readonly>
					</div>
					<div class="form-group col-md-4">
						<b>No Days used</b>
						<input type="text" class="form-control" id="no_of_days_used" value="{{ $list->no_of_days_used }}" name="no_of_days_used" required="required"  readonly>
					</div>
					<div class="form-group col-md-4">
						<b>Water Charges </b>
						<input type="text" class="form-control amount_align" id="water_charge" value="{{ $list->water_charge }}" name="water_charge" required="required"  readonly>
					</div>
					<div class="form-group col-md-4">
						<b>Supervisor Charges</b>
						<input type="text" class="form-control amount_align" id="supervisor_charge" value="{{ $list->supervisor_charge }}" name="supervisor_charge" required="required"  readonly>
					</div>
					<div class="form-group col-md-4">
						<b>Fixed Charges </b>
						<input type="text" class="form-control amount_align" id="fixed_charge" value="{{ $list->fixed_charge }}" name="fixed_charge" required="required"  readonly>
					</div>
					<div class="form-group col-md-4">
						<b>Other Charges</b>
						<input type="text" class="form-control amount_align" id="other_charges" value="{{ $list->other_charges }}" name="other_charges" required="required"  readonly>
					</div>
					<div class="form-group col-md-4">
						<b>Penalty</b>
						<input type="text" class="form-control amount_align" id="penalty" value="{{ $list->penalty }}" name="penalty" required="required"  readonly>
					</div>
					<div class="form-group col-md-4">
						<b>Returned Amount</b>
						<input type="text" class="form-control amount_align" id="refund_amount" value="{{ $list->refund_amount }}" name="" required="required" readonly>
					</div>
					<div class="form-group col-md-4">
						<b>Cess</b>
						<input type="text" class="form-control amount_align" id="cess" value="{{ $list->cess }}" name="cess" required="required" readonly>
					</div>
					<div class="form-group col-md-4">
						<b>UGD Cess</b>
						<input type="text" class="form-control amount_align" id="ugd_cess" value="{{ $list->ugd_cess }}" name="ugd_cess" required="required" readonly>
					</div>
					<div class="form-group col-md-4">
						<b>Arrears</b>
						<input type="text" class="form-control amount_align" id="arrears" value="{{ $list->arrears }}" name="arrears" required="required"  readonly>
					</div>
					<div class="form-group col-md-4">
						<b>Total Due</b>
						<input type="text" class="form-control amount_align" id="total_due" value="{{ $list->total_due }}" name="total_due" required="required"  readonly>
					</div>
					<div class="form-group col-md-4">
						<b>Round Off</b>
						<input type="text" class="form-control amount_align" id="round_off"  value="{{ $list->round_off }}" name="round_off" required="required"  readonly>
					</div>
                                       <div class="form-group col-md-4">
                                            <b>Total Amount</b>
                                            <input type="text" class="form-control amount_align" id="total_amount" value="{{ $list->total_amount }}" required="required" readonly>
                                    </div>
                                  <!--  <div class="form-group col-md-4">
                                            <b>Advance Amount</b>
                                            <input type="text" class="form-control amount_align" id="advance_amount" value="{{ $list->advance_amount }}" required="required" readonly>
                                    </div> -->
					
				</div>
			</div>
		</div>
		<div class="col-md-4 no-padding">
			<div class="box box-warning">
				<div class="box-header with-border">
					<h3 class="box-title">Account Information</h3>
						<!-- /.box-tools -->
				</div>
                             <span class="text-success">
                                  <strong id="success-msg_pay"></strong>
                                            </span>
                            <div class="clear"></div>
                              <form class="form-horizontal" method="POST" id="pay_bill_form">
                                    <input type="hidden" class="form-control" id="seq_number" value="{{ $list->sequence_number }}" name="seq_number" required="required"  readonly>
                               <input type="hidden" class="form-control" id="meter_reading_id" value="{{ $list->id }}" name="meter_reading_id"  >
                                        <div class="box-body bg-gray">
					<div class="form-group col-md-12">
						<b>Name Of Bank</b>
                                                 @if((strcasecmp($category,"Bank") == 0))
						<input type="text" class="form-control" id="bank_name" name ="bank_name" value="{{ $bank_name  }}" required="required" readonly>
                                                @else
                                                <input type="text" class="form-control" id="bank_name" name ="bank_name" required="required" placeholder="Bank Name">
                                                  
                                                  @endif
                                                 <span class="text-danger">
                                                <strong id="bank_name-error"></strong>
                                            </span>
					</div>
					<div class="form-group col-md-12">
						<b>Name Of Branch</b>
                                                 @if((strcasecmp($category,"Bank") == 0))
						<input type="text" class="form-control" id="branch_name" name="branch_name" value="{{ $branch_name  }}" required="required" readonly>
                                                  @else
                                                 <input type="text" class="form-control" id="branch_name" name="branch_name" required="required" placeholder="Branch Name">
                                                @endif
                                                 <span class="text-danger">
                                                <strong id="branch_name-error"></strong>
                                            </span>
					</div>					
					<div class="form-group col-md-12">
						<b>Cheque / DD information</b>
						<input type="text" class="form-control" id="cheque_dd" name="cheque_dd" required="required" placeholder="Enter Cheque Number">
                                                 <span class="text-danger">
                                                <strong id="cheque-error"></strong>
                                            </span>
					</div>
                                        <div class="form-group col-md-12">
						<b>Amount</b>
						<input type="text" class="form-control amount_align" id="total_amount"  name="total_amount" value="{{ $list->total_amount }}"  required="required" placeholder="Amount" readonly>
                                                  <span class="text-danger">
                                                <strong id="amount-error"></strong>
                                            </span>
					</div>
                                        <div class="form-group col-md-12">
						<b>Advance Amount</b>
						<input type="text" class="form-control amount_align amnt_class" id="advance_amount"  name="advance_amount" value="{{ $list->advance_amount }}"   required="required">
                                                  <span class="text-danger">
                                                <strong id="advance-error"></strong>
                                            </span>
					</div>
					<div class="form-group col-md-12">
						<b>Date</b>
                                                <input type="text" class="form-control" id="payment_date"  name="payment_date" value="{{ $date }}"  required="required"  readonly>
					</div>
					<div class="form-group col-md-12">
						<b>Remarks</b>
						<textarea class="form-control" id="remarks" name="remarks" rows="3" placeholder="Enter ..."></textarea>
					</div>
				
                              </div>
			
			<div class="col-md-12">
                             <?php $role1 = Helper::getRole();
                                $sub_category = $role1->sub_category_name;
                                $category = $role1->category_name;

                                ?>    
                            <div class="form-group col-md-12" style="margin-top: 20px;">

                                  @if((strcasecmp($sub_category,"MCC") == 0)|| (strcasecmp($category,"Super Admin") == 0) || (strcasecmp($sub_category,"Administrator") == 0)|| (strcasecmp($category,"Bank") != 0) && (strcasecmp($sub_category,"Editor") == 0))
                                   <input type="hidden" name="payment_section" value="2">
				<button type="button" id="generate_bank_challan" class="btn btn-danger pull-right" formtarget="_blank" >Generate Challan</button>
                                 @endif
                                 @if((strcasecmp($category,"Bank") == 0))
                                  <input type="hidden" name="payment_section" value="1">
				<button type="button" id="pay_bill" class="btn btn-danger" >Pay  BIll / Generate Challan </button>
                                 
                                @endif
			   </div>
			</div>
                      </form>
                    </div>
		</div>
    </section>

@endforeach

	<div class="modal modal-default fade in" id="challan-genrate">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-body" id="add_modal_data">
         
				</div>
			</div>
		</div>
	</div>


<div id="div1" style="display:none;">				
                                 
</div>
<input type="hidden" id="meter_reading_id">

<script>
        function printContent(el){
            
         var restorepage = document.body.innerHTML;
        var printcontent = document.getElementById(el).innerHTML;
        document.body.innerHTML = printcontent;
        window.print();
         document.body.innerHTML = restorepage;
          var meter_reading_id = $('#meter_reading_id').val();
   
$.ajax({
        url: siteUrl + "/admin/paybill",
        type: 'POST',
        data: {'meter_reading_id':meter_reading_id},
        success: function (data) {
            $('#data').empty();
            $('#data').html(data);
            $('#pay_bill').prop('disabled', true);
            $('.amnt_class').attr('readonly', true);
            $('#generate_bank_challan').prop('disabled', true);
        },
    });
        $("#add_modal_data").modal("hide"); 
       

}

$(document).ready( function() {

     $('#date_picker').datepicker({
      autoclose: true,
          dateFormat: 'dd/mm/yy'
    })

        $('#date_picker1').datepicker({
      autoclose: true
    })

  $('body').on('click', '#pay_bill', function () {
                    var paybillForm = $("#pay_bill_form");
                  var formData = paybillForm.serialize();    
                     $('#bank_name-error').html("");
                     $('#branch_name-error').html("");
                     $('#cheque-error').html("");
                     $('#amount-error').html("");
                     $('#date-error').html("");
      
                         $.ajax({
                        url: siteUrl + "/admin/addnewpayment",
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
                                 if (data.errors.cheque_dd) {
                                    $('#cheque-error').html(data.errors.cheque_dd[0]);
                                }
                                 if (data.errors.total_amount) {
                                    $('#amount-error').html(data.errors.total_amount[0]);
                                }
                                 if (data.errors.payment_date) {
                                    $('#date-error').html(data.errors.payment_date[0]);
                                }
                            }
                            
                            if (data.success) {
                              var trHTML = '';
                              $('#add_modal_data').html("");
                   
                                                 trHTML +='<table class="table table-bordered table-hover"><tr><td colspan="3" rowspan="2"><h2>Mangaluru City Corporation - Water Bill payment</h2></td>';
                                                 trHTML +='<td><b>Challan No</b></td><td colspan="2">' + data.filldata.challan_no + '</td></tr>'; 
                                                 trHTML +='<tr><td><b>Date</b></td><td colspan="2">' + data.filldata.payment_date + '</td></tr>';  
                                                 trHTML +='<tr><td colspan="3">Bank Name / Branch</td><td colspan="3">' + data.filldata.bank_name + ' /  ' + data.filldata.branch_name + '</td></tr>';
                                                 trHTML +='<tr><td colspan="3">Water Supply Period</td> <td colspan="3">' + data.filldata.previous_billing_date + '  To  ' + data.filldata.date_of_reading + '</td></tr>';
                                                 trHTML +='<tr><td><b>Building Owner Name</b></td><td colspan="2"><b>Ward / Block Door #,Address</b></td><td><b>Bill Details</b></td><td><b>RS</b></td><td><b>00</b></td></tr>';
                                                 trHTML +='<tr>';
                                                 trHTML +='<td rowspan="8">'+ data.filldata.name +'<br>SEQ No -'+ data.filldata.sequence_number+'</td>';
                                                 trHTML +='<td colspan="2" rowspan="3">'+data.filldata.comm_address+'</td>';
                                                 trHTML +='<td>Water Charge</td>';
                                                 trHTML +='<td>'+data.filldata.water_charge+'</td>';
                                                 trHTML +='<td>'+data.filldata.water_fraction+'</td>';
                                                 trHTML +='</tr>';
                                                 trHTML +='<tr>';
                                                 trHTML +='<td>Supervisor Charge</td>';
                                                 trHTML +='<td>'+data.filldata.supervisor_charge+'</td>';
                                                 trHTML +='<td>'+data.filldata.supervisor_fraction+'</td>';
                                                 trHTML +='</tr>';
                                                 trHTML +='<tr>';
                                                 trHTML +='<td>Other Charge</td>';
                                                 trHTML +='<td>'+data.filldata.other_charges+'</td>';
                                                 trHTML +='<td>'+data.filldata.other_fraction+'</td>';
                                                 trHTML +='</tr>';
                                                 trHTML +='<tr>';
                                                 trHTML +=' <td><b>SEQ No</b></td>';
                                                trHTML +=' <td>'+data.filldata.sequence_number+'</td>';
                                                trHTML +=' <td>Penalty</td>';
                                                trHTML +=' <td>'+data.filldata.penalty+'</td>';
                                                trHTML +='<td>'+data.filldata.penalty_fraction+'</td>';
                                                trHTML +='</tr>';
                                                trHTML +='<tr>';
                                                trHTML +='<td><b>Meter No</b></td>';
                                                trHTML +='<td>'+data.filldata.meter_no+'</td>';
                                                trHTML +='<td>Returned Amount</td>';
                                                trHTML +=' <td>'+data.filldata.returned_amount+'</td>';
                                                trHTML +='<td>'+data.filldata.return_fraction+'</td>';
                                                trHTML +='</tr>';
                                                trHTML +='<tr>';
                                                trHTML +='<td><b>Bill No</b></td>';
                                                trHTML +='<td>'+data.filldata.bill_no+'</td>';
                                                trHTML +='<td>Other CESS</td>';
                                                trHTML +='<td>'+data.filldata.cess+'</td>';
                                                trHTML +='<td>'+data.filldata.cess_fraction+'</td>';
                                                trHTML +='</tr>';
                                                
                                                trHTML +='<tr>';
                                                trHTML +='<td></td>';
                                                trHTML +='<td></td>';
                                                trHTML +='<td>Arrears</td>';
                                                trHTML +='<td>'+data.filldata.arrears+'</td>';
                                                trHTML +='<td>'+data.filldata.arrears_fraction+'</td>';
                                                trHTML +='</tr>';
                                                trHTML +='<tr>';
                                                trHTML +='<td colspan="2" rowspan="2"></td>';
                                                trHTML +='<td>Round off</td>';
                                                trHTML +='<td>'+data.filldata.round_off+'</td>';
                                                trHTML +='<td>'+data.filldata.round_fraction+'</td>';
                                                 trHTML +='</tr>';
                                                trHTML +='<tr>';
                                                trHTML +='<tr>';
                                                 trHTML +='<td></td>';
                                                trHTML +='<td></td>';
                                               trHTML +='<td></td>';
                                                trHTML +='<td><b>Advance Amount</b></td>';
                                                trHTML +='<td><b>'+data.filldata.advance_amount+'</b></td>';
                                                 trHTML +='<td><b>'+data.filldata.advance_fraction+'</b></td>';
                                                trHTML +='</tr>';
                                                trHTML +='<tr>';
                                                 trHTML +='<td></td>';
                                                trHTML +='<td></td>';
                                                 trHTML +='<td></td>';
                                                trHTML +='<td><b>Payable Amount</b></td>';
                                                trHTML +='<td><b>'+data.filldata.payable_amount+'</b></td>';
                                                 trHTML +='<td><b>'+data.filldata.payable_fraction+'</b></td>';
                                                trHTML +='</tr>';
                                                trHTML +='<tr>';
                                                trHTML +='<td colspan="6"><b>Amount in words</b> - '+data.filldata.amount_in_words+'  only</td>';
                                                trHTML +='</tr>';
                                                trHTML +='<tr>';
                                                trHTML +='<td><b>Depositer Signature</b></td>';
                                                trHTML +='<td colspan="2"><b>Account # </b></td>';
                                                trHTML +='<td><b>Office Manager Signature</b></td>';
                                                trHTML +='<td colspan="2"><b>Cashier Signature</b></td>';
                                                trHTML +='</tr>';
                                                trHTML +='<tr>';
                                                trHTML +='<td height="75"></td>';
                                                trHTML +='<td colspan="2"></td>';
                                                trHTML +='<td></td>';
                                                trHTML +='<td colspan="2"></td>';
                                                trHTML +='</tr> </table><div class="btn-group pull-right">';
                                                trHTML +='<a id="printPageUrl" href="" target="_blank" style="float:left;" id="printPageLink" onclick="print_data_challan()"><button type="button"  class="btn btn-danger pull-right hidden-print" formtarget="_blank" ><i class="fa fa-print"></i></button></a>';
                                                trHTML +='<button type="button" class="btn btn-danger hidden-print custom-close"   aria-label="close" data-dismiss="modal"><i class="fa fa-close"></i></button>';
                                                trHTML +='</div><div class="clear"></div>';
                                              
                                                 $('#add_modal_data').append(trHTML);    
                                                 $('#div1').append(trHTML); 
                                                 $('#success-msg_pay').html('Bill Generated Successfully');
                                                 $('#pay_bill').prop('disabled', true);
                                                 $("#challan-genrate").modal('show');
                                                 
                                                  
                               
                               }
                            },
                       
                    }); 
        });
$('body').on('click', '#generate_bank_challan', function () {
                    var paybillForm = $("#pay_bill_form");
                    var formData = paybillForm.serialize();
                     $('#bank_name-error').html("");
                     $('#branch_name-error').html("");
                     $('#cheque-error').html("");
                     $('#amount-error').html("");
                     $('#date-error').html("");
      
                         $.ajax({
                        url: siteUrl + "/admin/addnewpayment",
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
                                 if (data.errors.cheque_dd) {
                                    $('#cheque-error').html(data.errors.cheque_dd[0]);
                                }
                                 if (data.errors.total_amount) {
                                    $('#amount-error').html(data.errors.total_amount[0]);
                                }
                                 if (data.errors.payment_date) {
                                    $('#date-error').html(data.errors.payment_date[0]);
                                }
                            }
                            
                            if (data.success) {
                               
                               
                              var trHTML = '';
                              $('#add_modal_data').html("");
                                                
                                                $('#meter_reading_id').val(data.filldata.meter_reading_id);
                                                trHTML +='<table class="table table-bordered table-hover"><tr><td colspan="3" rowspan="2"><h2>Mangaluru City Corporation - Water Bill payment</h2></td>';
                                                 trHTML +='<td><b>Challan No</b></td><td colspan="2">' + data.filldata.challan_no + '</td></tr>'; 
                                                 trHTML +='<tr><td><b>Date</b></td><td colspan="2">' + data.filldata.payment_date + '</td></tr>';  
                                                 trHTML +='<tr><td colspan="3">Bank Name / Branch</td><td colspan="3">' + data.filldata.bank_name + ' /  ' + data.filldata.branch_name + '</td></tr>';
                                                 trHTML +='<tr><td colspan="3">Water Supply Period</td> <td colspan="3">' + data.filldata.previous_billing_date + '  To  ' + data.filldata.date_of_reading + '</td></tr>';
                                                 trHTML +='<tr><td><b>Building Owner Name</b></td><td colspan="2"><b>Ward / Block Door #, Sequence #</b></td><td><b>Bill Details</b></td><td><b>RS</b></td><td><b>00</b></td></tr>';
                                                 trHTML +='<tr>';
                                                 trHTML +='<td rowspan="8">'+ data.filldata.name +'<br>SEQ No -'+ data.filldata.sequence_number+'</td>';
                                                 trHTML +='<td colspan="2" rowspan="3">'+data.filldata.comm_address+'</td>';
                                                  trHTML +='<td>Water Charge</td>';
                                                 trHTML +='<td>'+data.filldata.water_charge+'</td>';
                                                 trHTML +='<td>'+data.filldata.water_fraction+'</td>';
                                                 trHTML +='</tr>';
                                                 trHTML +='<tr>';
                                                 trHTML +='<td>Supervisor Charge</td>';
                                                 trHTML +='<td>'+data.filldata.supervisor_charge+'</td>';
                                                 trHTML +='<td>'+data.filldata.supervisor_fraction+'</td>';
                                                 trHTML +='</tr>';
                                                 trHTML +='<tr>';
                                                 trHTML +='<td>Other Charge</td>';
                                                 trHTML +='<td>'+data.filldata.other_charges+'</td>';
                                                 trHTML +='<td>'+data.filldata.other_fraction+'</td>';
                                                 trHTML +='</tr>';
                                                 trHTML +='<tr>';
                                                 trHTML +=' <td><b>SEQ No</b></td>';
                                                trHTML +=' <td>'+data.filldata.sequence_number+'</td>';
                                                trHTML +=' <td>Penalty</td>';
                                                trHTML +=' <td>'+data.filldata.penalty+'</td>';
                                                trHTML +='<td>'+data.filldata.penalty_fraction+'</td>';
                                                trHTML +='</tr>';
                                                trHTML +='<tr>';
                                                trHTML +='<td><b>Meter No</b></td>';
                                                trHTML +='<td>'+data.filldata.meter_no+'</td>';
                                                trHTML +='<td>Returned Amount</td>';
                                                trHTML +=' <td>'+data.filldata.returned_amount+'</td>';
                                                trHTML +='<td>'+data.filldata.return_fraction+'</td>';
                                                trHTML +='</tr>';
                                                trHTML +='<tr>';
                                                trHTML +='<td><b>Bill No</b></td>';
                                                trHTML +='<td>'+data.filldata.bill_no+'</td>';
                                                trHTML +='<td>Other CESS</td>';
                                                trHTML +='<td>'+data.filldata.cess+'</td>';
                                                trHTML +='<td>'+data.filldata.cess_fraction+'</td>';
                                                trHTML +='</tr>';
                                                
                                                trHTML +='<tr>';
                                                trHTML +='<td></td>';
                                                trHTML +='<td></td>';
                                                trHTML +='<td>Arrears</td>';
                                                trHTML +='<td>'+data.filldata.arrears+'</td>';
                                                trHTML +='<td>'+data.filldata.arrears_fraction+'</td>';
                                                trHTML +='</tr>';
                                                trHTML +='<tr>';
                                                trHTML +='<td colspan="2" rowspan="2"></td>';
                                                trHTML +='<td>Round off</td>';
                                                trHTML +='<td>'+data.filldata.round_off+'</td>';
                                                trHTML +='<td>'+data.filldata.round_fraction+'</td>';
                                                trHTML +='</tr>';
                                                trHTML +='<tr>';    
                                                trHTML +='<td></td>';
                                                trHTML +='<td><b>Total Amount</b></td>';
                                                trHTML +='<td><b>'+data.filldata.total_amount+'</b></td>';
                                                trHTML +='<td><b>'+data.filldata.total_fraction+'</b></td>';
                                                trHTML +='</tr>';
                                                trHTML +='<tr>';
                                                trHTML +='<tr>';
                                                 trHTML +='<td></td>';
                                                trHTML +='<td></td>';
                                               trHTML +='<td></td>';
                                                trHTML +='<td><b>Advance Amount</b></td>';
                                                trHTML +='<td><b>'+data.filldata.advance_amount+'</b></td>';
                                                 trHTML +='<td><b>'+data.filldata.advance_fraction+'</b></td>';
                                                trHTML +='</tr>';
                                                trHTML +='<tr>';
                                                 trHTML +='<td></td>';
                                                trHTML +='<td></td>';
                                                 trHTML +='<td></td>';
                                                trHTML +='<td><b>Payable Amount</b></td>';
                                                trHTML +='<td><b>'+data.filldata.payable_amount+'</b></td>';
                                                 trHTML +='<td><b>'+data.filldata.payable_fraction+'</b></td>';
                                                trHTML +='</tr>';
                                                trHTML +='<tr>';
                                                trHTML +='<td colspan="6"><b>Amount in words</b> - '+data.filldata.amount_in_words+'  only</td>';
                                                trHTML +='</tr>';
                                                trHTML +='<tr>';
                                                trHTML +='<td><b>Depositer Signature</b></td>';
                                                trHTML +='<td colspan="2"><b>Account # </b></td>';
                                                trHTML +='<td><b>Office Manager Signature</b></td>';
                                                trHTML +='<td colspan="2"><b>Cashier Signature</b></td>';
                                                trHTML +='</tr>';
                                                trHTML +='<tr>';
                                                trHTML +='<td height="75"></td>';
                                                trHTML +='<td colspan="2"></td>';
                                                trHTML +='<td></td>';
                                                trHTML +='<td colspan="2"></td>';
                                                trHTML +='</tr> </table><div class="btn-group pull-right">';
                                                trHTML +='  <a id="printPageUrl" href="" target="_blank"  style="float:left;"  onclick="print_data_challan()"><button type="button"  class="btn btn-danger hidden-print"  formtarget="_blank" ><i class="fa fa-print"></i></button></a>';
                                                trHTML +='<button type="button" class="btn btn-danger hidden-print custom-close"   aria-label="close" data-dismiss="modal"><i class="fa fa-close"></i></button>';
                                                trHTML +='</div><div class="clear"></div>';
                                                 $('#add_modal_data').append(trHTML);    
                                                 $('#div1').append(trHTML); 
                                                 $('#success-msg_pay').html('Bill Generated Successfully');
                                                 $('#generate_bank_challan').prop('disabled', true);
                                                 $("#challan-genrate").modal('show');
                                                 //printContent('div1');
         

                               }
                            },
                       
                    }); 
        });
 
});
 function print_data_challan()
  {
    
          var meter_reading_id=$('#meter_reading_id').val();
          var sequence_number=$('#sequence_number').val();  
          var siteUrl = '<?php echo url('/'); ?>';
           printPageUrl = siteUrl + '/admin/print_challan_data' + '?meter_reading_id=' + meter_reading_id +'&sequence_number='+sequence_number;
           $("#printPageUrl").attr("href", printPageUrl);
                
  }
</script>
