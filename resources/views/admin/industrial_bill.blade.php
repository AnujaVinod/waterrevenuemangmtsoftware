@extends('layouts.admin_master')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
   Industrial Bill
  </h1>     
</section>
<!-- Main content -->
<section class="content container-fluid">
    <div class="box box-info">
        <div class="box-header with-border">
                <h3 class="box-title">Tariff Change</h3>
                        <!-- /.box-tools -->
        </div>
        <div class="box-body">
            <div class="alert alert-danger error_class hide" >                            
                Please Enter any search criteria                     
            </div>
            <div class="col-md-5 col-sm-12 form-group">
                    <b>Sequence Number</b>
                    <input type="text" class="form-control" id="sequence_no" placeholder="Sequence Number">
            </div>
            <div class="col-md-5 col-sm-12 form-group">
                    <b>Meter Number</b>
                    <input type="text" class="form-control" id="meter_no" placeholder="Meter Number">
            </div>
            <div class="col-md-7 col-sm-12">
                    <button type="button" class="btn btn-warning btn-margin btn-flat search" data-toggle="collapse">Search</button>	
            </div>
        </div>
    </div>
    <div class="box box-warning indusBillDiv" style="display:none">
        <div id="success-msg" class="hide">
            <div class="alert alert-info alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <strong>Record updated Successfully!!</strong> 
            </div>
        </div>
        <div class="box-header with-border">
                <h3 class="box-title">Tariff Change Request</h3>
                        <!-- /.box-tools -->
        </div>
        <form method="POST" name="indistrialBillForm" id="indistrialBillForm" enctype="multipart/form-data">
            <input type="hidden" class="form-control" id="connection_date" name="connection_date">
                <div class="box-body">
                    <div class="col-md-4 col-sm-12 form-group">
                            <b>Sequence Number</b>
                            <input type="text" class="form-control" id="seq_no" readonly="readonly" name="sequence_no">
                    </div>
                    <div class="col-md-4 col-sm-12 form-group">
                            <b>Name</b>
                            <input type="text" class="form-control" id="c_name" readonly="readonly" name="c_name">
                    </div>
                    <div class="col-md-4 col-sm-12 form-group">
                            <b>Existing Tariff</b>
                            <input type="text" class="form-control" id="ex_tariff" readonly="readonly"  name="ex_tariff">
                            <input type="hidden" class="form-control" id="ex_tariff_id" name="ex_tariff_id">
                    </div>
                    <div class="col-md-4 col-sm-12 form-group">
                            <b> Current rate </b>
                                    <input type="text" readonly="readonly" class="form-control pull-right" id="current_rate" name="current_rate" readonly>                                    
                    </div>
                    <div class="col-md-4 col-sm-12 form-group">
                            <b>Water Consumption till date</b>
                            <input type="text" class="form-control pull-right" readonly="readonly" id="tot_consumption" name="tot_consumption" >
                    </div>
                    <div class="col-md-4 col-sm-12 form-group">
                            <b>Deposite Amount</b>
                            <input type="text" class="form-control pull-right" readonly="readonly" id="dep_amount" name="dep_amount" >
                    </div>
                    <div class="col-md-4 col-sm-12 form-group">
                            <b>Connection Date</b>
                            <input type="text" class="form-control pull-right" readonly="readonly" id="conn_date" name="conn_date" >
                    </div>
                    <div class="col-md-4 col-sm-12 form-group"></div>
                    <div class="clear"></div>
                    <hr>
                    <div class="col-md-4 col-sm-12 form-group">
                        <b>Requested Tariff</b> <span class="rq">*</span>
                        <select class="form-control req_tariff" name="req_tariff" onchange="calculateReviseAmt()">                                                    </select>
                        <span class="text-danger">
                            <strong id="req_tariff-error"></strong>
                        </span>
                    </div>
                    <div class="col-md-4 col-sm-12 form-group">
                        <b>Required From Date</b> <span class="rq">*</span>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" name="date" class="form-control pull-right from_date" onchange="calculateReviseAmt()" id="date_picker1">
                        </div>
                        <span class="text-danger">
                                <strong id="date_picker1-error"></strong>
                        </span>

                    </div>
                    <div class="col-md-4 col-sm-12 form-group">
                        <b>Revised rate</b>
                        <input type="text" readonly="readonly" class="form-control pull-right" id="revised_rate" name="revised_rate">                                   
                    </div>
                            <hr>
                            <div class="clear"></div>
                              <div class="col-md-4 col-sm-12 form-group">
                                <b>Arrears Amount</b>
                                <input type="text" readonly="readonly" class="form-control pull-right" id="arreas_amt" name="arreas_amt">                                   
                        </div>
                    <div class="col-md-4 col-sm-12 form-group">
                                <b>Excess Amount</b>
                                <input type="text" readonly="readonly" class="form-control pull-right" id="excess_amt" name="excess_amt">                                   
                        </div>

                        <div class="col-md-4 col-sm-12 form-group">
                                <b>Reason</b> <span class="rq">*</span>
                                <textarea class="form-control" rows="1" placeholder="Enter ..." id="reason" name="reason"></textarea>
                                <span class="text-danger">
                                    <strong id="reason-error"></strong>
                                </span>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                                <b>Order Number</b> <span class="rq">*</span>
                                <input type="text" class="form-control" id="order_number" name="order_number" required="required" placeholder="Order Number">
                                <span class="text-danger">
                                    <strong id="ordernumber-error"></strong>
                                </span>
                        </div>
                        <div class="col-md-4 col-sm-12">
                                <b>Upload Sanction approval document</b> <span class="rq">*</span>
                                <input type="file" class="form-control" name="documents[]" multiple id="app_file" onchange="validateFile('app_file')" required="required">
                                <span class="text-danger">
                                    <strong id="app_file-error"></strong>
                                </span>
                        </div>
                        <div class="col-md-4 col-sm-12  form-group">
                        <b>Order Approved by</b> <span class="rq">*</span>
                                <select class="form-control" name="approved_by" id="approved_by">
                                        <option value="">Select</option>
                                       
                                        
                                        
                                 </select>
                                <span class="text-danger">
                                    <strong id="approvedby-error"></strong>
                                </span>
                        </div>
                        <div class="col-md-4 col-sm-12  form-group">
                            <input type="checkbox" name="deposit_amount" id="depositCheck" onchange="calculateReviseAmt()">Consider the deposit amount
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                                <b>Construction Period in days</b>
                                <input type="text" readonly="readonly" class="form-control pull-right" id="extrafld1">   
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                                <b>Domestic Period in days</b>
                                <input type="text" readonly="readonly" class="form-control pull-right" id="extrafld2">   
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                                <b>Already paid amount</b>
                                <input type="text" readonly="readonly" class="form-control pull-right" id="extrafld3">   
                        </div>
                        <div class="col-md-2 col-sm-12">
                                <button type="button" class="btn btn-warning btn-flat btn-margin pull-right" data-toggle="collapse" id="submitTariffChange">Submit</button>	
                        </div>
                </div>
            </form>
        </div>
    <div class="box box-warning tarifNoRecordDiv" align="center" style="display:none">
        No matching records found
    </div>
</section>
<script>var siteUrl = '<?php echo url('/'); ?>';</script>
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
});

$('body').on('click', '.search', function () {
    $("#indistrialBillForm").trigger( "reset" );
    claerError();
    //$(".indusBillDiv").hide();
    // $(".indusBillDiv").slideUp("slow");
    //  $(".tarifNoRecordDiv").hide();
    $('.error_class').addClass('hide');
    var seq_no = $("#sequence_no").val(); 
    var meter_no = $("#meter_no").val();
    if (seq_no == '' && meter_no == '')
    {
        $(".indusBillDiv").slideUp("slow");
        $('.error_class').removeClass('hide');     
    } else {
        $.ajax({
                url: siteUrl + "/admin/getIndustialBillInfo",
                type: 'POST',
                data: {seq_no : seq_no, meter_no: meter_no},
                async: true,
                success: function (data) {                        
                    console.log(data);
                     if (data.failure) {
                        $(".indusBillDiv").slideUp("slow");
                        $(".tarifNoRecordDiv").show();

                        loadDatatable(0);
                         $(".tarifLogDiv").slideUp("slow");
                        //if (data.errors.name) {
                        //    $('#name-error').html(data.errors.name[0]);
                        // }            
                    }
                    if (data.success) {

                            $('#seq_no').val('');
                            $('#ex_tariff').val('');                                                             
                            $('#ex_tariff_id').val('');
                            $('#current_rate').val('');
                            $('#c_name').val('');
                            $('#tot_consumption').val('');
                            $('#connection_date').val('');
                            $('#dep_amount').val('');
                            $('#conn_date').val('');

                            $('#seq_no').val(data.connDetails[0].sequence_number);
                            $('#ex_tariff').val(data.connDetails[0].connection_name);
                            $('#ex_tariff_id').val(data.connDetails[0].connection_type_id);
                            $('#current_rate').val(data.connDetails[0].min_price);
                            $('#c_name').val(data.connDetails[0].name);                                
                            if(data.connDetails[0].total_unit_used == null){
                                $('#tot_consumption').val(0);
                            }else{
                                $('#tot_consumption').val(data.connDetails[0].total_unit_used);
                            } 

                            if(data.connDetails[0].deposit_amount == null){
                                $('#dep_amount').val(0);
                                $('#depositCheck').val(0);
                                $('#depositCheck').attr("disabled", true);
                            }else if(data.connDetails[0].is_considered_dep_amount == '1'){
                                $('#dep_amount').val(data.connDetails[0].deposit_amount);
                                $('#depositCheck').val(0);
                                $('#depositCheck').attr("disabled", true);
                            }else {
                                $('#dep_amount').val(data.connDetails[0].deposit_amount);
                                $('#depositCheck').val(data.connDetails[0].deposit_amount);
                                $('#depositCheck').removeAttr("disabled");
                            }

                            $('#connection_date').val(data.connDetails[0].connection_date);
                            $('#conn_date').val(data.connDetails[0].connection_date);
                            var i = 0;
                            $('.req_tariff').html('');
                                $('.req_tariff')
                                .append($("<option></option>")
                                .attr("value",'')
                                .text("select"));                      
                            $(data.connectionTypes).each(function(){
                                if( data.connectionTypes[i].id != data.connDetails[0].connection_type_id){
                                $('.req_tariff')
                                .append($("<option></option>")
                                .attr("value",data.connectionTypes[i].id)
                                .text(data.connectionTypes[i].connection_name));
                                }
                                i++;
                            });
                            $(".tarifNoRecordDiv").hide();
                            $(".indusBillDiv").slideDown("slow");
                            loadDatatable(seq_no);
                            $(".tarifLogDiv").slideDown("slow");
                    }
                },
            });
        //loadDatatable(seq_no);
    }
});
function claerError(){
    $('#req_tariff-error').html("");

}
</script>
@endsection
