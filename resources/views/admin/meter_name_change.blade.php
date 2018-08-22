@extends('layouts.admin_master')
@section('content')
<section class="content-header">
  <h1>
   Meter and Name Change
  </h1> 
</section>

<!-- Main content -->
<section class="content container-fluid">
            <div class="box box-info">
                    <div class="box-header with-border">
                            <h3 class="box-title">Connection Details </h3>
                                    <!-- /.box-tools -->
                    </div>
                    <div class="box-body">
                        <div class="alert alert-danger error_class hide" >                            
                            Please Enter any search criteria                     
                        </div>
                            <div class="col-md-4 col-sm-12">
                                    <b>Sequence Number</b>
                                    <a href="../../../app/Models/Billing.php"></a>
                                    <input type="text" class="form-control" name="seq_no" id="sequence_no" required="required" placeholder="12587468">
                            </div>
                        
                            <div class="col-md-4 col-sm-12">
                                    <b>Meter Number</b>
                                    <input type="text" class="form-control" id="meter_no" name="meter_no" required="required" placeholder="257/12">
                            </div>                        
                            <div class="col-md-1 col-sm-12">
                                    <button style="margin:19px;" type="button" class="btn btn-warning btn-flat margin pull-right search">Search</button>
                            </div>	
                    </div>
            </div>
            <div id="" class="" aria-expanded="true" style="">
                    <div class="box box-danger">
                        
                            <div class="box-header with-border">
                                <div id="success-msg" class="hide">
                                    <div class="alert alert-info alert-dismissible fade in" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <strong>Record updated Successfully!!</strong> 
                                    </div>
                                </div>
                                <h3 class="box-title">Search Result</h3>                               
                            </div>
                            <div class="box-body">
                                    <div class="box-body table-responsive no-padding ">
                                            <table id="meter_name_result" class="table table-responsive table-bordered table-hover table-striped">
                                                    <thead>
                                                            <tr>
                                                                    <th>Name</th>
                                                                    <th>Phone Number</th>
                                                                    <th>Sequence Number</th>
                                                                    <th>Meter Number</th>
                                                                    <th>Connection Type</th>
                                                                    <th>Door No</th>
                                                                    <th>Ward</th>
                                                                    <th>Corp Ward</th>
                                                                    <th>Status</th>
                                                                    <th>Operation</th>
                                                            </tr>
                                                    </thead>                                                   
                                            </table>
                                    </div>
                            </div>
                    </div>
            </div>
            <div class="box box-warning">
                            <div class="box-header with-border">
                                    <h3 class="box-title">Meter/Name Change Log</h3>
                                            <!-- /.box-tools -->
                            </div>
                            <div class="box-body">
                                    <div class="box-body table-responsive no-padding ">
                                            <table id="meter_name_log" class="table table-responsive table-bordered table-hover table-striped">
                                                    <thead>
                                                            <tr>
                                                                    <th>Sequence Number</th>
                                                                    <th>Old Name/Meter No</th>
                                                                    <th>Updated Name/Meter No</th>
                                                                    <th>Order No</th>
                                                                    <th>Reason</th>
                                                                    <th>Approved By</th>
                                                                     <th>Date</th>
                                                                     <th>Meter Change Date</th>
                                                                    <th>Documents</th>
                                                                    <th>Operation</th> 
                                                            </tr>
                                                    </thead>                                                   
                                            </table>
                                    </div>
                            </div>
                    </div>
<div class="modal modal-default fade" id="name-change">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Name Change</h4>
            </div>
            <div class="modal-body no-padding">
                    <div class="box-body">
                            <div class="form-group col-md-4">
                                    <label>Sequence No</label>
                                    <input type="text" class="form-control" readonly="readonly" id="nsequence_no">
                            </div>
                            <div class="form-group col-md-4">
                                    <label>Ward</label>
                                    <input type="text" class="form-control" id="nward" readonly="readonly">
                            </div>
                            <div class="form-group col-md-4">
                                    <label>Connection Type</label>
                                    <input type="text" class="form-control" id="nconn_type" readonly="readonly">
                            </div>

                            <div class="form-group col-md-4">
                                    <label>Door No / Khata No</label>
                                    <input type="text" class="form-control" id="ndoorno" readonly="readonly">
                            </div>
                            <div class="form-group col-md-4">
                                    <label>Connection Date</label>
                                    <input type="text" class="form-control" id="nconn_date" readonly="readonly">
                            </div>
                            <div class="form-group col-md-4">
                                    <label>Connection Status</label>
                                     <input type="text" class="form-control" id="nconn_status" readonly="readonly">
                            </div>

                            <div class="form-group col-md-4">
                                    <label>Existing Name</label>
                                    <input type="text" class="form-control" id="ncname" readonly="readonly">
                            </div>
                            <div class="form-group col-md-4">
                                    <label>Meter Number</label> <span class="rq">*</span>
                                    <input type="text" class="form-control" id="nmeter_no" readonly="readonly">
                            </div>
                            <form method="POST" name="nameChangeForm" id="nameChangeForm" enctype="multipart/form-data">
                                <input type="hidden" class="form-control" id="name_sequence_no" name="name_sequence_no">
                                <input type="hidden" class="form-control" id="name_cname" name="name_cname">
                                <input type="hidden" class="form-control" id="connection_date" name="connection_date">
                            <div class="col-md-12 no-padding bg-gray-bg"><br>
                                    <div class="form-group col-md-3">
                                            <label>New Name</label><span class="rq">*</span>
                                            <input type="text" class="form-control" name="new_name" id="new_name" required="required" placeholder="Enter New Name">
                                            <span class="text-danger">
                                                <strong id="nnewname-error"></strong>
                                            </span>
                                    </div>
                                    <div class="form-group col-md-3">
                                            <label>Order Number</label> <span class="rq">*</span>
                                            <input type="text" class="form-control" id="order_number" name="order_number" required="required" placeholder="Enter Order Number">
                                            <span class="text-danger">
                                                <strong id="nordernumber-error"></strong>
                                            </span>
                                    </div>
                                    <div class="form-group col-md-3">
                                            <label>Reason</label> <span class="rq">*</span>
                                            <textarea class="form-control" rows="1" placeholder="Enter ..." id="reason" name="reason"></textarea>
                                            <span class="text-danger">
                                                <strong id="nreason-error"></strong>
                                            </span>
                                    </div>
                                    <div class="form-group col-md-3">
                                            <b>Order Approved by </b><span class="rq">*</span>
                                            <select class="form-control" name="approved_by" id="approved_by">
                                                <option value="">Select</option>
                                          @foreach($usersDesignations as $usersDesignation)
                                                <option value="{{$usersDesignation->name}} - {{$usersDesignation->designation}}">{{$usersDesignation->name}} - <em>{{$usersDesignation->designation}}</em></option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger">
                                                <strong id="napprovedby-error"></strong>
                                            </span>
                                    </div>
                                    <div class="btn-group col-md-12">
                                        <div class="form-group col-md-3">
                                            <label>Date</label><span class="rq">*</span>
                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control pull-right" name="date" onchange="checkDOB('date_picker2')" id="date_picker2">
                                                <!--<input type="text" id="redate" name="redate" class="form-control pull-right" id="date_picker1"> -->
                                            </div>
                                            <span class="text-danger">
                                                <strong id="date_picker2-error"></strong>
                                            </span>
                                        </div>
                                         <div class="form-group col-md-3">
                                            <label>Document to be uploaded</label><span class="rq">*</span>
                                            <input type="file" class="form-control" name="documents[]" id="nfile" multiple onchange="validateImage('nfile')" required="required"><span>(Only PDF, JPG, PNG and GIF)</span><br>
                                            <span class="text-danger">
                                                <strong id="nfile-error"></strong>
                                            </span>
                                         </div>
                                    </div>
                                    <div class="btn-group col-md-12">
                                            <button type="button" class="btn btn-danger" id="submitNameChangeForm">Save</button>
                                            <button type="button" class="btn btn-default" class="close" data-dismiss="modal">Cancel</button>
                                    </div>
                                    <div class="clear">&nbsp;</div>
                            </div>
                    </form>
                    </div>
            </div>
        </div>
    </div>
</div>
    
    <div class="modal modal-default fade" id="meter-change">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Meter Change</h4>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <div class="form-group col-md-4">
                            <label>Sequence No</label>
                            <input type="text" class="form-control" id="msequence_no" readonly="readonly">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Ward</label>
                            <input type="text" class="form-control" id="mward" readonly="readonly">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Connection Type</label>
                            <input type="text" class="form-control" id="mconn_type" readonly="readonly">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Door No / Khata No</label>
                            <input type="text" class="form-control" id="mdoorno" readonly="readonly">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Connection Date</label>
                            <input type="text" class="form-control" id="mconn_date" readonly="readonly">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Connection Status</label>
                            <input type="text" class="form-control" id="mconn_status" readonly="readonly">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Name</label>
                            <input type="text" class="form-control" id="mcname" readonly="readonly">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Existing Meter Number</label> <span class="rq">*</span>
                            <input type="text" class="form-control" id="mmeter_no" readonly="readonly">
                        </div>
                        <form method="POST" name="meterChangeForm" id="meterChangeForm" enctype="multipart/form-data">
                            <input type="hidden" class="form-control" id="meter_sequence_no" name="meter_sequence_no">
                            <input type="hidden" class="form-control" id="oldmeter_no" name="oldmeter_no">
                            <input type="hidden" class="form-control" id="meter_sanction_date" name="meter_sanction_date">
                            <div class="col-md-12 bg-gray-bg no-padding"><br>
                                <div class="form-group col-md-3">
                                    <label>Meter Change Type</label> <span class="rq">*</span>
                                    <br/>
                                    <label class="radio-inline"><input type="radio" name="meter_change_type" value="new" checked="" onchange="checkType(this.value)">New</label>
                                    <label class="radio-inline"><input type="radio" name="meter_change_type" value="update" onchange="checkType(this.value)">Update</label>
                                    <span class="text-danger">
                                        <strong id="MeterChangeType-error"></strong>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-12 bg-gray-bg no-padding"><br>
                                <div class="form-group col-md-3" id="meter-number-block">
                                    <label>New Meter Number</label> <span class="rq">*</span>
                                    <input type="text" class="form-control" name="new_meterno" id="new_meterno" required="required" placeholder="Enter New Name">
                                    <span class="text-danger">
                                        <strong id="mnewmeterno-error"></strong>
                                    </span>
                                </div>
                                <div class="form-group col-md-3">
                                    <label id="mtitle1">Updated Meter Reading</label> <span class="rq">*</span>
                                    <input type="text" class="form-control" name="new_meterreading" id="new_meterreading" required="required" placeholder="Enter zero for New meter ">
                                    <span class="text-danger">
                                        <strong id="mnewmeterreading-error"></strong>
                                    </span>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Order Number</label> <span class="rq">*</span>
                                    <input type="text" class="form-control" id="order_number" name="order_number" required="required" placeholder="Enter Order Number">
                                    <span class="text-danger">
                                        <strong id="mordernumber-error"></strong>
                                    </span>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Reason</label> <span class="rq">*</span>
                                    <textarea class="form-control" rows="1" placeholder="Enter ..." id="reason" name="reason"></textarea>
                                    <span class="text-danger">
                                        <strong id="mreason-error"></strong>
                                    </span>
                                </div>
                            </div>
                             <div class="col-md-12 bg-gray-bg no-padding">
                                <div class="form-group col-md-3">
                                    <b>Order Approved by </b><span class="rq">*</span>
                                    <select class="form-control" name="approved_by" id="approved_by">
                                        <option value="">Select</option>
                                        @foreach($usersDesignations as $usersDesignation)
                                        <option value="{{$usersDesignation->name}} - {{$usersDesignation->designation}}">{{$usersDesignation->name}} - <em>{{$usersDesignation->designation}}</em></option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger">
                                        <strong id="mapprovedby-error"></strong>
                                    </span>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Order Date</label><span class="rq">*</span>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" name="order_date" onchange="checkDOB('date_picker3')" id="date_picker3">

                                    </div>
                                    <span class="text-danger">
                                        <strong id="date_picker3-error"></strong>
                                    </span>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Meter Change Date</label><span class="rq">*</span>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" name="meter_change_date" onchange="checkDOB('date_picker3')" id="date_picker4">

                                    </div>
                                    <span class="text-danger">
                                        <strong id="date_picker3-error"></strong>
                                    </span>
                                </div>
                                <div class="btn-group col-md-12">

                                    <div class="form-group col-md-3">
                                        <label>Document to be uploaded</label><span class="rq">*</span>
                                        <input type="file" class="form-control" name="documents[]" multiple id="mfile" onchange="validateImage('mfile')" required="required"><span>(Only PDF, JPG, PNG and GIF)</span><br>
                                        <span class="text-danger">
                                            <strong id="mfile-error"></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="btn-group col-md-12">
                                    <button type="button" class="btn btn-danger" id="submitMeterChangeForm">Save</button>
                                    <button type="button" class="btn btn-default" class="close" data-dismiss="modal">Cancel</button>
                                </div>
                                <div class="clear">&nbsp;</div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<form id="file-form" action="{{ URL::to('admin/downloadFile') }}" method="POST" style="display: none;">
    <input type="hidden" id="filename" name="filename">
    <input type="hidden" name="fileType" value="meterNameChange">
    {{ csrf_field() }}
</form> 
</section>
<script>var siteUrl = '<?php echo url('/'); ?>';</script>
<script>
    $(document).ready(function () {
        var currentDate = new Date();
        $('#date_picker2').datepicker({
           maxDate: 0,
           autoclose: true,
           dateFormat: 'dd/mm/yy'
        });
         $("#date_picker2").datepicker("setDate", currentDate);
         
        $('#date_picker3').datepicker({ 
            maxDate: 0,
            autoclose: true,
            dateFormat: 'dd/mm/yy'
        });
        $("#date_picker3").datepicker("setDate", currentDate);
        
        $("#date_picker4").datepicker({
            //  startDate: currentDate,
            endDate:currentDate,
            todayHighlight:true
        });
       $("#date_picker4").datepicker("setDate", currentDate);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
       // $("#spinner").bind("ajaxSend", function() {
       //     alert();
      //      $(this).show();
       // }).bind("ajaxStop", function() {
      //      $(this).hide();
       // }).bind("ajaxError", function() {
       //     $(this).hide();
       // });
        
        var seq_no = '';
        var meter_no = '';
        
            dtTable = $('#meter_name_result').DataTable({
                "processing": true,
                "language": {
                        "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                "serverSide": true,
                "destroy": true,
                "ajax": {
                    'url': siteUrl + "/admin/meter_name_change_search_all",
                    'type': "POST",
                    'dataType': "json",
                },
                "columns": [
                    {"data": "name"},
                    {"data": "mobile_no"},
                    {"data": "sequence_number"},
                    {"data": "meter_no"},
                    {"data": "connection_name"},
                    {"data": "door_no"},
                    {"data": "ward_name"},
                    {"data": "corp_name"},
                    {"data": "status"},
                    {"data": "action" , orderable: false, searchable: false}
                ]
            });
            
    });


// function to load DataTable 
    function loadDatatable(seq_no,meter_no) {    
            dtTable = $('#meter_name_result').DataTable({
                "processing": true,
                "serverSide": true,
                "destroy": true,
                "ajax": {
                    'url': siteUrl + "/admin/meter_name_change_search",
                    'type': "POST",
                    'data': {
                        seq_no: seq_no,
                        meter_no: meter_no
                    },
                },
                "columns": [
                    {"data": "name"},
                    {"data": "mobile_no"},
                    {"data": "sequence_number"},
                    {"data": "meter_no"},
                    {"data": "connection_name"},
                    {"data": "door_no"},
                    {"data": "ward_name"},
                    {"data": "corp_name"},
                    {"data": "status"},
                    {"data": "action" , orderable: false, searchable: false}
                ]
            });
            
            dtTable1 = $('#meter_name_log').DataTable({
                "processing": true,
                "serverSide": true,
                "destroy": true,
                "ajax": {
                    'url': siteUrl + "/admin/meter_namechange_log_search",
                    'type': "POST",
                    'data': {
                        seq_no: seq_no,
                        meter_no: meter_no
                    },
                },
                "columns": [
                    {"data": "sequence_no"},                  
                    {"data": "old_val"},
                    {"data": "updated_val"},
                    {"data": "order_no"},
                    {"data": "reason"},
                    {"data": "approved_by"},
                    {"data": "date"},
                    {"data": "meter_change_date"},
                    {"data": "document"},
                    {"data": "operation" , orderable: false, searchable: false}
                ]
            });
        
        
    }


    $('body').on('click', '.search', function () {
        $('.error_class').addClass('hide');
        var seq_no = $("#sequence_no").val();
        var meter_no = $("#meter_no").val();
        if (seq_no == '' && meter_no == '')
        {
            $('.error_class').removeClass('hide'); 
            loadDatatable(0,0);
        } else {
            loadDatatable(seq_no,meter_no);
        }
    });
    $('body').on('click', '.statuschange', function () {
        claerNameModalError();
        claerMeterModalError();
        var title = $(this).data("title");
        var connid = $(this).data("connid");
        $("#meterChangeForm")[0].reset();
        $("#nameChangeForm")[0].reset();
        //if (title == 'namechange') {
                $.ajax({
                    url: siteUrl + "/admin/getConnectionInfo",
                    type: 'POST',
                    data: {connId : connid},
                    async: true,
                    success: function (data) {
                        console.log(data);
                         if (data.errors) {
                            alert('error');
                            //if (data.errors.name) {
                            //    $('#name-error').html(data.errors.name[0]);
                            // }            
                        }
                        if (data.success) {
                            if (title == 'namechange') {
                                $('#nsequence_no').val('');
                                $('#nward').val('');
                                $('#nconn_type').val('');
                                $('#ndoorno').val('');
                                $('#nconn_date').val('');
                                $('#nconn_status').val('');
                                $('#ncname').val('');
                                $('#nmeter_no').val('');
                                   
                               // $('#ndate').val('');
                              //  var datetime = data.response[0].connection_date;
                              //  var conn_date = datetime.split(' ')[0];
                              //  var revdate = conn_date.split("-").reverse().join("-");

                                $('#nsequence_no').val(data.response[0].sequence_number);
                                $('#name_sequence_no').val(data.response[0].sequence_number);
                                $('#nward').val(data.response[0].ward_name);
                                $('#nconn_type').val(data.response[0].connection_name);
                                $('#ndoorno').val(data.response[0].door_no);
                                $('#nconn_date').val(data.response[0].connection_date);
                                $('#connection_date').val(data.response[0].connection_date);
                                $('#nconn_status').val(data.response[0].status);
                                $('#ncname').val(data.response[0].name);
                                $('#name_cname').val(data.response[0].name);
                                $('#nmeter_no').val(data.response[0].meter_no);                               
                                $('#name-change').modal('show');
                                
                                $('[name=meter_change_type][value=new]').click();  
                            }
                            if (title == 'meterchange') {
                                $('#msequence_no').val('');
                                $('#mward').val('');
                                $('#mconn_type').val('');
                                $('#mdoorno').val('');
                                $('#mconn_date').val('');
                                $('#mconn_status').val('');
                                $('#mcname').val('');
                                $('#mmeter_no').val('');                               

                               // var datetime = data.response[0].connection_date;
                               // var conn_date = datetime.split(' ')[0];
                              //  var revdate = conn_date.split("-").reverse().join("-");

                               // var datetime1 = data.response[0].meter_sanctioned_date;
                               // var m_date = datetime1.split(' ')[0];
                              //  var revmdate = m_date.split("-").reverse().join("-");
                                
                                $('#msequence_no').val(data.response[0].sequence_number);
                                $('#meter_sequence_no').val(data.response[0].sequence_number);
                                $('#mward').val(data.response[0].ward_name);
                                $('#mconn_type').val(data.response[0].connection_name);
                                $('#mdoorno').val(data.response[0].door_no);
                                $('#mconn_date').val(data.response[0].connection_date);
                                $('#mconn_status').val(data.response[0].status);
                                $('#mcname').val(data.response[0].name);
                                $('#meter_sanction_date').val(data.response[0].meter_sanctioned_date);
                                $('#mmeter_no').val(data.response[0].meter_no);
                                $('#oldmeter_no').val(data.response[0].meter_no);
                                $('#meter-change').modal('show');
                                setTimeout(function(){$('[name=meter_change_type][value=new]').change()},100)
                                
                            }
                        }
                    },
                });
        
    });

    
   $('body').on('click', '#submitMeterChangeForm', function () {
        var formData = new FormData($("#meterChangeForm")[0]);
       claerMeterModalError();
        $.ajax({
            url: siteUrl + "/admin/meterChange",
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
                    if (data.errors.order_number) {
                        $('#mordernumber-error').html(data.errors.order_number[0]);
                    }
                    if (data.errors.new_meterno) {
                        $('#mnewmeterno-error').html(data.errors.new_meterno[0]);
                    }
                    if (data.errors.new_meterreading) {
                        $('#mnewmeterreading-error').html(data.errors.new_meterreading[0]);
                    }
                    if (data.errors.reason) {
                        $('#mreason-error').html(data.errors.reason[0]);
                    }
                    if (data.errors.approved_by) {
                        $('#mapprovedby-error').html(data.errors.approved_by[0]);
                    }  
                    if (data.errors.documents) {
                        $('#mfile-error').html(data.errors.documents[0]);
                    }
                    if (data.errors.date) {
                      
                        $('#date_picker3-error').html(data.errors.date);
                    }
                    if (data.errors.date) {
                      
                        $('#MeterChangeType-error').html(data.errors.meter_change_type);
                    }
                }
                if (data.success) {                   
                    $('#success-msg').removeClass('hide');
                    $("#meterChangeForm")[0].reset();
                    setInterval(function () {
                        $('#success-msg').addClass('hide');
                    }, 3000);
                    $('#meter-change').modal('hide');                     
                    var seq_no = $("#sequence_no").val();
                    var meter_no = $("#meter_no").val();
                    if(seq_no == '' && meter_no == ''){
                        loadDatatableLog(data.sequence_numb);
                    }else {
                         loadDatatable(seq_no,meter_no);
                    }
                }
            },
        });
    });
    
    $('body').on('click', '#submitNameChangeForm', function () {
        var formData = new FormData($("#nameChangeForm")[0]);
        claerNameModalError();
        $.ajax({
            url: siteUrl + "/admin/nameChange",
            type: 'POST',
            enctype: 'multipart/form-data',
            data: formData,
            //async: true,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                console.log(data);
                // var total_file=document.getElementById("doc").files.length;
                 
                if (data.errors) {
                    if (data.errors.order_number) {
                        $('#nordernumber-error').html(data.errors.order_number[0]);
                    }
                    if (data.errors.new_name) {
                        $('#nnewname-error').html(data.errors.new_name[0]);
                    }
                    if (data.errors.reason) {
                        $('#nreason-error').html(data.errors.reason[0]);
                    }
                    if (data.errors.approved_by) {
                        $('#napprovedby-error').html(data.errors.approved_by[0]);
                    }  
                    if (data.errors.documents) {
                        $('#nfile-error').html(data.errors.documents[0]);
                    } 
                    if (data.errors.date) {
                        $('#date_picker2-error').html(data.errors.date);
                    }
                }
                if (data.success) {                   
                    $('#success-msg').removeClass('hide');
                    $("#nameChangeForm")[0].reset();
                    setInterval(function () {
                        $('#success-msg').addClass('hide');
                    }, 3000);
                    $('#name-change').modal('hide');                     
                    var seq_no = $("#sequence_no").val();
                    var meter_no = $("#meter_no").val();
                    if(seq_no == '' && meter_no == ''){
                        loadDatatableLog(data.sequence_numb);
                    }else {
                         loadDatatable(seq_no,meter_no);
                    }
                   
                }
            },
        });
    });
    
    
   function printFile(identifier){
      // alert("data-id:"+$(identifier).data('value'));
       $('#filename').val($(identifier).data('value'));
       document.getElementById('file-form').submit()
       
   }
   
   function validateImage(id) {
    $('#'+id+'-error').html('');
    var formData = new FormData();
 
    
    var total_file = document.getElementById(id).files.length;
    for(i=0;i<=total_file;i++){
        var file = document.getElementById(id).files[i];
        formData.append("Filedata", file);
        var t = file.type.split('/').pop().toLowerCase();
        if (t != "jpeg" && t != "jpg" && t != "png" && t != "bmp" && t != "gif" && t != "pdf") {
            //alert('Please select a valid image file');
            $('#'+id+'-error').html('Uploaded file is not valid. Only PDF, JPG, PNG and GIF files are allowed.');
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

   function claerMeterModalError(){
        $('#mnewmeterno-error').html("");
        $('#mnewmeterreading-error').html("");
        $('#mordernumber-error').html("");     
        $('#mreason-error').html("");
        $('#mapprovedby-error').html("");
        $('#mfile-error').html("");
        $('#date_picker3-error').html("");
   }
   
    function claerNameModalError(){
        $('#nnewname-error').html("");
        $('#nordernumber-error').html("");     
        $('#nreason-error').html("");
        $('#napprovedby-error').html("");
        $('#nfile-error').html("");
        $('#date_picker2-error').html("");
   }
   
       function loadDatatableLog(seq_no) {
                       dtTable = $('#meter_name_result').DataTable({
                "processing": true,
                "serverSide": true,
                "destroy": true,
                "ajax": {
                    'url': siteUrl + "/admin/meter_name_change_search_all",
                    'type': "POST",
                    'dataType': "json",
                },
                "columns": [
                    {"data": "name"},
                    {"data": "mobile_no"},
                    {"data": "sequence_number"},
                    {"data": "meter_no"},
                    {"data": "connection_name"},
                    {"data": "door_no"},
                    {"data": "ward_name"},
                    {"data": "corp_name"},
                    {"data": "status"},
                    {"data": "action" , orderable: false, searchable: false}
                ]
            });
           
            dtTable1 = $('#meter_name_log').DataTable({
                "processing": true,
                "serverSide": true,
                "destroy": true,
                "ajax": {
                    'url': siteUrl + "/admin/meter_namechange_log_search",
                    'type': "POST",
                    'data': {
                        seq_no: seq_no
                    },
                },
                "columns": [
                    {"data": "sequence_no"},                  
                    {"data": "old_val"},
                    {"data": "updated_val"},
                    {"data": "order_no"},
                    {"data": "reason"},
                    {"data": "approved_by"},
                    {"data": "date"},
                    {"data": "meter_change_date"},
                    {"data": "document"},
                    {"data": "operation" , orderable: false, searchable: false}
                ]
            });
    }

    function checkDOB(id) {
        $('#'+id+'-error').html('');
        var dateString = document.getElementById(id).value;
        var myDate = new Date(dateString);
        var today = new Date();
        if ( myDate > today ) {
            $('#'+id+'-error').html('Please select a valid Date');
            document.getElementById(id).value = '';
           // $('#id_dateOfBirth').after('<p>You cannot enter a date in the future!.</p>');
            return false;
        }
        return true;
    }
    
    function checkType(val) {
        if(val =="new") {
            $("#meter-number-block").show()
            $("#mtitle1").html("Meter Reading");
            $("#new_meterreading").val("0");
            $("#new_meterreading").attr('readonly', true);
            //$("#meter-number-block").css('visibility',"visible");
            
            
        }
        else {
            $("#meter-number-block").hide()
            $("#mtitle1").html("Updated Meter Reading")
            $("#new_meterreading").val("");
            $("#new_meterreading").attr("readonly",false);
            //$("#meter-number-block").css('visibility',"hidden");
        }
    }
</script>
@endsection