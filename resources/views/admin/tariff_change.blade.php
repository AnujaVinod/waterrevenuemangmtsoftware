@extends('layouts.admin_master')
@section('content')
<section class="content-header">
  <h1>
    Tariff Change
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
                                    <input type="text" class="form-control" id="sequence_no" required="required" placeholder="Sequence Number">
                            </div>
                            <div class="col-md-7 col-sm-12">
                                    <button type="button" class="btn btn-warning btn-margin btn-flat search" data-toggle="collapse">Search</button>	
                            </div>
                    </div>
            </div>

            <div class="box box-warning tarifDiv" style="display:none">
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
                <form method="POST" name="tariffChangeForm" id="tariffChangeForm" enctype="multipart/form-data">
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
                                    <input type="text" class="form-control pull-right" onkeypress="return isNumberKey(event)"  id="tot_consumption" name="tot_consumption" >
                            </div>
                            <div class="col-md-4 col-sm-12 form-group">
                                    <b>Deposite Amount</b>
                                    <input type="text" class="form-control pull-right" readonly="readonly" id="dep_amount" name="dep_amount" >
                            </div>                        
                            <div class="col-md-4 col-sm-12 form-group">
                                    <b>Connection Date</b>
                                    <input type="text" class="form-control pull-right" readonly="readonly" id="conn_date" name="conn_date" >
                            </div>
                            <div class="col-md-4 col-sm-12 form-group">
                                    <b>No of Flats</b>
                                    <input type="text" class="form-control" onkeypress="return isNumberKey(event)" id="no_flats" name="no_flats" >
                                    <span class="text-danger">
                                        <strong id="no_flats-error"></strong>
                                    </span>
                            </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <b>Revised Deposite Amount</b>
                            <input type="text" class="form-control pull-right" onkeypress="return isNumberKey(event)"  id="revised_dep_amount" name="revised_dep_amount" value="0" >
                        </div>
                        <div class="col-md-4 col-sm-12 form-group"></div>
                        <div class="clear"></div>
                        <hr>
                         <div class="col-md-4 col-sm-12 form-group">
                                    <b>Requested Tariff</b> <span class="rq">*</span>
                                    <select class="form-control req_tariff" name="req_tariff">                                         
                                    </select>
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
                                            <input type="text" name="date" class="form-control pull-right from_date" id="date_picker1">
                                    </div>
                                    <span class="text-danger">
                                            <strong id="date_picker1-error"></strong>
                                    </span>
                                    
                            </div>
                           	<div class="col-md-4 col-sm-12 form-group">
                                    <b>Revised rate</b>
                                    <input type="text" readonly="readonly" onkeypress="return isNumberKey(event)" class="form-control pull-right" id="revised_rate" name="revised_rate">                                   
                            </div>
                                <hr>
                                <div class="clear"></div>
                                  <div class="col-md-4 col-sm-12 form-group">
                                    <b>Arrears Amount</b>
                                    <input type="text" class="form-control" onkeypress="return isNumberKey(event)" id="arreas_amt" name="arreas_amt">   
                                    <span class="text-danger">
                                        <strong id="arreas_amt-error"></strong>
                                    </span>
                            </div>
                        <div class="col-md-4 col-sm-12 form-group">
                                    <b>Excess Amount</b><span class="rq">*</span>
                                    <input type="text" class="form-control" onkeypress="return isNumberKey(event)" id="excess_amt" name="excess_amt">                                   
                                    <span class="text-danger">
                                        <strong id="excess_amt-error"></strong>
                                    </span>
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
                                            @foreach($usersDesignations as $usersDesignation)
                                                <option value="{{$usersDesignation->name}} - {{$usersDesignation->designation}}">{{$usersDesignation->name}} - <em>{{$usersDesignation->designation}}</em></option>
                                            @endforeach
                                     </select>
                                    <span class="text-danger">
                                        <strong id="approvedby-error"></strong>
                                    </span>
                            </div>
                                <!--
                            <div class="col-md-4 col-sm-12  form-group">
                                <input type="checkbox" name="deposit_amount" id="depositCheck" onchange="calculateReviseAmt()">Consider the deposit amount
                            </div> -->                           
                            <div class="col-md-6 col-sm-12">
                                    <button type="button" class="btn btn-warning btn-flat btn-margin pull-right" data-toggle="collapse" id="submitTariffChange">Submit</button>	
                            </div>
                    </div>
                </form>
            </div>
    <div class="box box-warning tarifNoRecordDiv" align="center" style="display:none">
        No matching records found
    </div>
            <div class="box box-danger tarifLogDiv" style="display:none">
                    <div class="box-header with-border">
                            <h3 class="box-title">Tariff Change Log Details</h3>
                                    <!-- /.box-tools -->
                    </div>
                    <div class="box-body">
                            <div class="box-body table-responsive no-padding ">
                                    <table id="tariff_change" class="table table-responsive table-bordered table-hover table-striped">
                                            <thead>
                                                    <tr>
                                                        <th>Sequence Number</th>
                                                        <th>Total Consumption</th>
                                                        <th>Deposit Amount </th>                                                        
                                                        <th>Existing Tariff</th>
                                                        <th>Requested Tariff</th>
                                                        <th>Required From Date</th>
                                                        <th>Flats</th>
                                                        <th>Reason</th>
                                                        <th>Order Number</th>	
                                                        <th>Order Approved By</th>
                                                        <th>Document</th>
                                                        <th>Updated By</th>
                                                    </tr>
                                            </thead>                                            
                                    </table>
                            </div>
                    </div>
            </div>
<form id="file-form" action="{{ URL::to('admin/downloadFile') }}" method="POST" style="display: none;">
    <input type="hidden" id="filename" name="filename">
    <input type="hidden" name="fileType" value="tariffChange">
    {{ csrf_field() }}
</form> 
</section>
<script>var siteUrl = '<?php echo url('/'); ?>';</script>
<script>
$(document).ready(function () {
   // $(".tarifDiv").hide();
   $(".tarifNoRecordDiv").hide();
  // $(".tarifLogDiv").hide();
   
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
    })
    
    var currentDate = new Date();
        $('#date_picker1').datepicker({           
           autoclose: true,
           dateFormat: 'dd/mm/yy'
    });
   // $("#date_picker1").datepicker("setDate", currentDate);
    
    $('body').on('click', '.search', function () {
        $("#tariffChangeForm").trigger( "reset" );
        claerError();
        //$(".tarifDiv").hide();
        // $(".tarifDiv").slideUp("slow");
        //  $(".tarifNoRecordDiv").hide();
         $('.error_class').addClass('hide');
        var seq_no = $("#sequence_no").val();            
        if (seq_no == '')
        {
            $(".tarifDiv").slideUp("slow");
            $('.error_class').removeClass('hide'); 
            loadDatatable(0);
            $(".tarifLogDiv").slideUp("slow");
        } else {
            $.ajax({
                    url: siteUrl + "/admin/getTariffInfo",
                    type: 'POST',
                    data: {seq_no : seq_no},
                    async: true,
                    success: function (data) {                        
                        console.log(data);
                         if (data.failure) {
                            $(".tarifDiv").slideUp("slow");
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
                                $('#no_flats').val(data.connDetails[0].no_of_flats);
                                $('#revised_dep_amount').val(data.connDetails[0].revised_deposit_amount);
                                
                                if(data.connDetails[0].total_unit_used == null){
                                    $('#tot_consumption').val(0);
                                }else{
                                    $('#tot_consumption').val(data.connDetails[0].total_unit_used);
                                } 
                                
                                if(data.connDetails[0].deposit_amount == null){
                                    $('#dep_amount').val(0);
                                   // $('#depositCheck').val(0);
                                   // $('#depositCheck').attr("disabled", true);
                                }else if(data.connDetails[0].is_considered_dep_amount == '1'){
                                    $('#dep_amount').val(data.connDetails[0].deposit_amount);
                                   // $('#depositCheck').val(0);
                                   // $('#depositCheck').attr("disabled", true);
                                }else {
                                    $('#dep_amount').val(data.connDetails[0].deposit_amount);
                                  //  $('#depositCheck').val(data.connDetails[0].deposit_amount);
                                  //  $('#depositCheck').removeAttr("disabled");
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
                                $(".tarifDiv").slideDown("slow");
                                loadDatatable(seq_no);
                                $(".tarifLogDiv").slideDown("slow");
                        }
                    },
                });
            //loadDatatable(seq_no);
        }
    });
    

    
    
  $('.req_tariff').change(function(){
        var extConnTypeId = $('#ex_tariff_id').val();
        var connTypeId = $(this).val();
        var req_from_date = $('.from_date').val();
        var seq_no = $('#seq_no').val();
        var conn_date = $('#connection_date').val();
        var tot_consumption = $('#tot_consumption').val();
        $.ajax({
            url: siteUrl + "/admin/getConnectionMinRate",
            type: 'POST',
            data: { newConnTypeId : connTypeId},
            async: true,
            success: function (data) {
                console.log(data);                        
                if (data.success == 1) { 
                    $('#revised_rate').val('');
                    $('#revised_rate').val(data.rate[0].min_price);                              
                }else{
                    $('#revised_rate').val('');
                    $('#revised_rate').val(0);  
                }
            },
        });
    }); 
    


});

    function printFile(identifier){
      // alert("data-id:"+$(identifier).data('value'));
       $('#filename').val($(identifier).data('value'));
       document.getElementById('file-form').submit()
       
   }
   
   //this function needs to be called onchange of tarrif, onchange="calculateReviseAmt()
   function calculateReviseAmt(){       
        var extConnTypeId = $('#ex_tariff_id').val();
        var connTypeId = $('.req_tariff').val();
        var req_from_date = $('.from_date').val();
        var seq_no = $('#seq_no').val();
        var conn_date = $('#connection_date').val();
        var tot_consumption = $('#tot_consumption').val();
        var ischecked = 0;
       // var atLeastOneIsChecked = $('#depositCheck').is(':checked');
       // if(atLeastOneIsChecked){
       //     ischecked = 1;
       // }else{
       //     ischecked = 0;
       // }
       // var deposite_amount = $('#depositCheck').val();
        if(connTypeId != '' && req_from_date != ''){
            $('#date_picker1-error').html('');
            var dateString = $('#date_picker1').val();
            var myDate = new Date(dateString);
            var today = new Date();
            if ( myDate > today ) {
                $('#date_picker1-error').html('Please select a valid Date');
                document.getElementById(id).value = '';
               // $('#id_dateOfBirth').after('<p>You cannot enter a date in the future!.</p>');
                return false;
            }
            $.ajax({
                url: siteUrl + "/admin/getConnectionMinRate",
                type: 'POST',
                data: { newConnTypeId : connTypeId, seq_no: seq_no , req_from_date: req_from_date,conn_date:conn_date, tot_consumption: tot_consumption, extConnTypeId: extConnTypeId, deposite_amount: deposite_amount //,ischecked :ischecked
                    },
                async: true,
                success: function (data) {
                    console.log(data);                        
                    if (data.success == 1) { 
                        $('#revised_rate').val('');                        
                        $('#arreas_amt').val('');
                        $('#excess_amt').val('');
                        $('#extrafld1').val('');
                        $('#extrafld2').val('');
                        $('#revised_rate').val(data.rate[0].min_price); 
                        $('#arreas_amt').val(data.arreas_amt);
                        $('#excess_amt').val(data.excess_amt);
                        $('#extrafld1').val(data.constuction_period_in_days);
                        $('#extrafld2').val(data.domestic_period_in_days);
                        $('#extrafld3').val(data.paid_amount);
                    }else if(data.error){
                        if (data.error.req_from_date) {
                            $('#date_picker1-error').html(data.error.req_from_date[0]);
                        }                         
                        //    console.log(data.error);
                       
                    }else{
                        $('#revised_rate').val('');
                        $('#arreas_amt').val('');
                        $('#excess_amt').val('');
                        $('#extrafld1').val('');
                        $('#extrafld2').val('');
                        
                        $('#revised_rate').val(0);  
                        $('#arreas_amt').val(0);
                        $('#excess_amt').val(0);
                        $('#extrafld1').val(0);
                        $('#extrafld2').val(0);
                        $('#extrafld3').val(0);
                    }
                },
            });
        }
   }
   
   $('body').on('click', '#submitTariffChange', function () {
        var selectedTaariff = $('.req_tariff').val();
        var flats = $('#no_flats').val();
        if(selectedTaariff == '4' && flats <= '1' ){
           alert('Please enter valid Flat value');
        }else if(selectedTaariff != '4' && flats > '1' ){
           alert('Please enter valid Flat value');
        }else{
            var formData = new FormData($("#tariffChangeForm")[0]);
            claerError();
            $.ajax({
                url: siteUrl + "/admin/saveTariffChange",
                type: 'POST',
                enctype: 'multipart/form-data',
                data: formData,
                //async: true,
                processData: false,
                contentType: false,
                cache: false,
                success: function (data) {
                    console.log(data);
                    if (data.errors) {
                        if (data.errors.date) {
                            $('#date_picker1-error').html(data.errors.date[0]);
                        }
                        if (data.errors.req_tariff) {
                            $('#req_tariff-error').html(data.errors.req_tariff[0]);
                        }
                        if (data.errors.reason) {
                            $('#reason-error').html(data.errors.reason[0]);
                        }
                        if (data.errors.order_number) {
                            $('#ordernumber-error').html(data.errors.order_number[0]);
                        }  
                        if (data.errors.documents) {
                            $('#app_file-error').html(data.errors.documents[0]);
                        }
                        if (data.errors.approved_by) {                      
                            $('#approvedby-error').html(data.errors.approved_by[0]);
                        }                    
                        if (data.errors.no_flats) {
                            $('#no_flats-error').html(data.errors.no_flats[0]);
                        }  
                        if (data.errors.arreas_amt) {
                            $('#arreas_amt-error').html(data.errors.arreas_amt[0]);
                        }
                        if (data.errors.excess_amt) {                      
                            $('#excess_amt-error').html(data.errors.excess_amt[0]);
                        }
                    }
                    if (data.success) {                   
                        $('#success-msg').removeClass('hide');
                        $("#tariffChangeForm")[0].reset();
                        setInterval(function () {
                            $('#success-msg').addClass('hide');
                        }, 3000);                                      
                        var seq_no = $("#sequence_no").val();                   
                        if(seq_no == ''){
                            seq_no = data.sequence_numb;
                        }
                        loadDatatable(seq_no);

                    }
                },
            });
        }
    });
    
    function claerError(){
        $('#req_tariff-error').html("");
        $('#date_picker1-error').html("");     
        $('#ordernumber-error').html("");
        $('#reason-error').html("");
        $('#approvedby-error').html("");
        $('#app_file-error').html("");        
        $('#no_flats-error').html("");
        $('#arreas_amt-error').html("");
        $('#excess_amt-error').html("");
    }  
   
    function validateFile(id) {
        $('#'+id+'-error').html('');
        var formData = new FormData();
        var total_file = document.getElementById(id).files.length;
        for(i=0;i<=total_file;i++){
            var file = document.getElementById(id).files[i];
            formData.append("Filedata", file);
            var t = file.type.split('/').pop().toLowerCase();
            if (t != "jpeg" && t != "jpg" && t != "png" && t != "bmp" && t != "gif" && t != "pdf") {
                //alert('Please select a valid image file');
                $('#'+id+'-error').html('Please select a valid file format');
                document.getElementById(id).value = '';
                return false;
            }
        }
        //if (file.size > 1024000) {
        //    alert('Max Upload size is 1MB only');
        //    $('#'.id.'-error').html('Max Upload size is 1MB only');
        //    document.getElementById(id).value = '';
        //    return false;
       // }
        return true;
    }

    function loadDatatable(seq_no) {    

            dtTable1 = $('#tariff_change').DataTable({
                "processing": true,
                "language": {
                        "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                "serverSide": true,
                "destroy": true,
                "ajax": {
                    'url': siteUrl + "/admin/tariffchange_log_search",
                    'type': "POST",
                    'data': {
                        seq_no: seq_no
                    },
                },
                "columns": [
                    {"data": "sequence_no"},
                    {"data": "total_consumption"},
                    {"data": "deposit_amount"},
                    {"data": "old_connection_type"},
                    {"data": "new_connection_type"},
                    {"data": "required_from_date"},
                    {"data": "no_of_flats"},
                    {"data": "reason"},
                    {"data": "order_no"},
                    {"data": "approved_by"},                   
                    {"data": "document"},
                    {"data": "name"}
                ]
            });
        
        
    }
    
    function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }
}
</script>
@endsection