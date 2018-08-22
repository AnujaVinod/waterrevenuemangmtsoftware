@extends('layouts.admin_master')
@section('content')
<section class="content-header">
    <h1>
        Disconnect Reconnect
    </h1> 
</section>

<!-- Main content -->
<section class="content container-fluid">
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Disconnection/Reconnection</h3>
            <!-- /.box-tools -->
        </div>
        <div class="box-body">
            <div class="alert alert-danger error_class hide" >                            
                Please Enter any search criteria                     
            </div>
            <div class="col-md-4 col-sm-12">
                <b>Sequence Number</b>
                <input type="text" class="form-control" name="seq_no" id="sequence_no" required="required" placeholder="12587468">
            </div>
            <div class="col-md-4 col-sm-12">
                <b>Meter Number</b>
                <input type="text" class="form-control" id="meter_no" name="meter_no" required="required" placeholder="257/12">
            </div>            
            <div class="col-md-1 col-sm-12">
                <button type="button" class="btn btn-warning btn-flat btn-margin margin pull-right search" data-toggle="collapse">Search</button>	
            </div>	
        </div>
    </div>

    <div class="box box-danger">
        <div class="box-header with-border">
            <div id="success-msg" class="hide">
                <div class="alert alert-info alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <strong>Connection updated Successfully!!</strong> 
                </div>
            </div>
            <h3 class="box-title">Connected Search Result</h3>
        </div>
        <div class="box-body">
            <div class="box-body table-responsive no-padding ">
                <table id="disconnect_reconnect_result" class="table table-responsive table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone Number</th>
                            <th>Sequence Number</th>
                            <th>Meter Number</th>
                            <th>Connection Type</th>
                            <th>status</th>
                            <th>Operation</th>
                        </tr>
                    </thead>                                            
                </table>
            </div>
        </div>
    </div>
    <div class="box box-danger">
        @if (session('error'))
        
        <div>
            <div class="alert alert-danger alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <strong>{{ session('error') }}</strong> 
            </div>
        </div>
           <!-- <div class="alert alert-danger">
                {{ session('error') }}
            </div> -->
        @endif
            <div class="box-header with-border">
                    <h3 class="box-title">Disconnect / Reconnect Log Details</h3>                         
            </div>
            <div class="box-body">
                <div class="box-body table-responsive no-padding ">
                    <table id="disconnect_reconnect_log" class="table table-responsive table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Sequence Number</th>
                                <th>Meter Number</th>
                                <th>Order Number</th>
                                <th>Date</th>                                
                                <th>Reason</th>
                                <th>Order Approved By</th>
                                <th>Operation</th>
                                <th>Documents</th>								
                            </tr>
                        </thead>                                    
                    </table>
                </div>
            </div>
    </div>
    <div class="modal modal-default fade" id="reconnect">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Reconnect</h4>
                </div>
                <div class="modal-body">
                    <form method="POST" name="ReconnectForm" id="ReconnectForm">
                        <input type="hidden" id="reconnect_consumer_id" name="consumer_id">
                        <input type="hidden" id="seq_no" name="seq_no">
                        <input type="hidden" id="remeter_no" name="meter_no">
                        
                        <div class="form-group">
                            <b>Order Number</b> <span class="rq">*</span>
                            <input type="text" class="form-control" id="order_no" name="order_no" required="required" placeholder="Enter Number">
                            <span class="text-danger">
                                <strong id="orderno-error"></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <b>Reconnected Date</b>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" name="reconnected_date" id="date_picker" onchange="checkDOB('date_picker')">
                                <!--<input type="text" id="redate" name="redate" class="form-control pull-right" id="date_picker1"> -->
                            </div>
                            <span class="text-danger">
                                <strong id="date_picker-error"></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <b>Reason</b> <span class="rq">*</span>
                            <textarea class="form-control" required="required" id="reason" name="reason" rows="3" placeholder="Enter Reconnect Reason..."></textarea>
                            <span class="text-danger">
                                <strong id="reason-error"></strong>
                            </span>
                        </div>	

                        <div class="form-group">
                            <b>Order Approved by </b><span class="rq">*</span>
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
                        <button type="button" class="btn btn-danger" id="submitReconnectForm">Save</button>
                        <button type="button" class="btn btn-warning pull-right" data-dismiss="modal">Close</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-default fade" id="disconnect">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Disconnect</h4>
                </div>
                <div class="modal-body">
                    <form method="POST" name="DisconnectForm" id="DisconnectForm" enctype="multipart/form-data">
                        <input type="hidden" id="disconnect_consumer_id" name="consumer_id">
                        <input type="hidden" id="disseq_no" name="seq_no">
                        <input type="hidden" id="dismeter_no" name="meter_no">
                        <div class="form-group">
                            <b>Order Number</b> <span class="rq">*</span>
                            <input type="text" class="form-control" id="order_number" name="order_number" required="required" placeholder="Enter Number">
                            <span class="text-danger">
                                <strong id="disorderno-error"></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <b>Disconnected Date</b>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" name="disconnected_date" id="date_picker1" onchange="checkDOB('date_picker1')">
                                
                            </div>
                            <span class="text-danger">
                                <strong id="date_picker1-error"></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <b>Reason</b><span class="rq">*</span>
                            <select class="form-control" id="reason" name="reason">
                                <option value="">Select</option>
                                <option>PERMANENT DISCONNECTION</option>
                                <option>TEMPORARY DISCONNECTION</option>
                                <option>BILL NOT PAID</option>                                
                            </select>
                            <span class="text-danger">
                                <strong id="disreason-error"></strong>
                            </span>
                        </div>	
                        <b>Upload document</b><span class="rq">*</span>
                        <div class="form-group">
                            <input type="file" class="form-control" id="disfile" name="documents[]" multiple onchange="validateImage('disfile')" required="required"> <span>(Only PDF, JPG, PNG and GIF)</span><br>
                            <span class="text-danger">
                                <strong id="disfile-error"></strong>
                            </span>
                        </div>
                        
                        <div class="form-group">
                            <b>Order Approved by </b><span class="rq">*</span>
                            <select class="form-control" name="approved_by" id="approved_by">
                                <option value="">Select</option>
                          @foreach($usersDesignations as $usersDesignation)
                                <option value="{{$usersDesignation->name}} - {{$usersDesignation->designation}}">{{$usersDesignation->name}} - <em>{{$usersDesignation->designation}}</em></option>
                                @endforeach
                            </select>
                            <span class="text-danger">
                                <strong id="disapprovedby-error"></strong>
                            </span>
                        </div>	
                        <button type="button" class="btn btn-danger" id="submitDisconnectForm">Save</button>
                        <button type="button" class="btn btn-warning pull-right" data-dismiss="modal">Close</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <form id="file-form" action="{{ URL::to('admin/downloadFile') }}" method="POST" style="display: none;">
        <input type="hidden" id="filename" name="filename">
        <input type="hidden" name="fileType" value="disconnectReconnect">
        {{ csrf_field() }}
    </form>
</section>
<script>var siteUrl = '<?php echo url('/'); ?>';</script>
<script>
    $(document).ready(function () {
        var currentDate = new Date();
        $('#date_picker').datepicker({
            autoclose: true,
            dateFormat: 'dd/mm/yy',
            maxDate: currentDate
        })
        $("#date_picker").datepicker("setDate", currentDate);
        $('#date_picker1').datepicker({          
            autoclose: true,
            dateFormat: 'dd/mm/yy',    
             maxDate: currentDate
        })
        $("#date_picker1").datepicker("setDate", currentDate);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        
        dtTable = $('#disconnect_reconnect_result').DataTable({
                "processing": true,
                "language": {
                        "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                "serverSide": true,
                "destroy": true,
                "ajax": {
                     "url": siteUrl + "/admin/disconnect_reconnect_search_all",
                     "dataType": "json",
                     "type": "POST"
                },
                "columns": [
                    {"data": "name"},
                    {"data": "mobile_no"},
                    {"data": "sequence_number"},
                    {"data": "meter_no"},
                    {"data": "connection_name"},
                    {"data": "status"},
                    {"data": "action" , orderable: false, searchable: false}
                ]
            });
        
    });


// function to load DataTable 
    function loadDatatable(seq_no,meter_no) {    
            dtTable = $('#disconnect_reconnect_result').DataTable({
                "processing": true,
                "language": {
                        "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                "serverSide": true,
                "destroy": true,
                "ajax": {
                    'url': siteUrl + "/admin/disconnect_reconnect_search",
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
                    {"data": "status"},
                    {"data": "action" , orderable: false, searchable: false}
                ]
            });
            
            dtTable1 = $('#disconnect_reconnect_log').DataTable({
                "processing": true,
                "language": {
                        "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                "serverSide": true,
                "destroy": true,
                "ajax": {
                    'url': siteUrl + "/admin/disconnect_reconnect_log_search",
                    'type': "POST",
                    'data': {
                        seq_no: seq_no,
                        meter_no: meter_no
                    },
                },
                "columns": [
                    {"data": "sequence_no"},
                    {"data": "meter_no"},
                    {"data": "order_no"},
                    {"data": "date"},
                    {"data": "reason"},
                    {"data": "approved_by"},
                    {"data": "operation"},
                    {"data": "document"}
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
    $('body').on('click', '.changestatus', function () {
        clearDisconnectModalError();
        clearReconnectModalError();
        $("#ReconnectForm")[0].reset();
        $("#DisconnectForm")[0].reset();
        var connId = $(this).data("value");
        var title = $(this).data("title");
        var seq_no = $(this).data("sequence");
        var meter_no = $(this).data("meterno");
        if (title == 'reconnect') {
            $('#reconnect_consumer_id').val(connId);
            $('#seq_no').val(seq_no);
            $('#remeter_no').val(meter_no);
            $('#reconnect').modal('show');
        } else if (title == 'disconnect') {
            $('#disconnect_consumer_id').val(connId);
            $('#disseq_no').val(seq_no);
            $('#dismeter_no').val(meter_no);
            $('#disconnect').modal('show');
        }
    });

    $('body').on('click', '#submitReconnectForm', function () {
        var ReconnectForm = $("#ReconnectForm");
        var formData = ReconnectForm.serialize();      
        clearReconnectModalError();
        $.ajax({
            url: siteUrl + "/admin/reconnectConnection",
            type: 'POST',
            data: formData,
            async: true,
            success: function (data) {
                console.log(data);
                if (data.errors) {
                    if (data.errors.order_no) {
                        $('#orderno-error').html(data.errors.order_no[0]);
                    }
                    if (data.errors.reconnected_date) {
                        $('#date_picker-error').html(data.errors.reconnected_date[0]);
                    }
                    if (data.errors.reason) {
                        $('#reason-error').html(data.errors.reason[0]);
                    }
                    if (data.errors.approved_by) {
                        $('#approvedby-error').html(data.errors.approved_by[0]);
                    }                   
                }
                if (data.success) {                    
                    $('#success-msg').removeClass('hide');
                    $("#ReconnectForm")[0].reset();
                    setInterval(function () {
                        $('#success-msg').addClass('hide');
                    }, 3000);
                     $('#reconnect').modal('hide'); 
                    var seq_no = $("#sequence_no").val();
                    var meter_no = $("#meter_no").val();
                    if(seq_no == '' && meter_no == ''){
                        loadDatatableLog(data.sequence_numb,meter_no);
                    }else {
                        loadDatatable(seq_no,meter_no);
                    }
                }
            },
        });
    });
    
    $('body').on('click', '#submitDisconnectForm', function () {
       //  var form = $("#DisconnectForm");

    // you can't pass Jquery form it has to be javascript form object
    //var formData1 = new FormData(form[0]);

      //  var DisconnectForm = $("#DisconnectForm");
       // console.log(DisconnectForm);
        var formData = new FormData($("#DisconnectForm")[0]);
      clearDisconnectModalError();      
        $.ajax({
            url: siteUrl + "/admin/disconnectConnection",
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
                        $('#disorderno-error').html(data.errors.order_number[0]);
                    }
                    if (data.errors.disconnected_date) {
                        $('#date_picker1-error').html(data.errors.disconnected_date[0]);
                    }
                    if (data.errors.reason) {
                        $('#disreason-error').html(data.errors.reason[0]);
                    }
                    if (data.errors.approved_by) {
                        $('#disapprovedby-error').html(data.errors.approved_by[0]);
                    }
                    if (data.errors.documents) {
                        $('#disfile-error').html(data.errors.documents[0]);
                    }                    
                }
                if (data.success) {                   
                    $('#success-msg').removeClass('hide');
                    $("#DisconnectForm")[0].reset();
                    setInterval(function () {
                        $('#success-msg').addClass('hide');
                    }, 3000);
                    $('#disconnect').modal('hide');                     
                    var seq_no = $("#sequence_no").val();
                    var meter_no = $("#meter_no").val();
                    if(seq_no == '' && meter_no == ''){
                        loadDatatableLog(data.sequence_numb,meter_no);
                    }else {
                        loadDatatable(seq_no,meter_no);
                    }
                }
            },
        });
    });
    
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
    
   function printFile(identifier){
      // alert("data-id:"+$(identifier).data('value'));
       $('#filename').val($(identifier).data('value'));
       document.getElementById('file-form').submit();       
   }
   
   function clearDisconnectModalError(){
        $('#disorderno-error').html("");
        $('#date_picker1-error').html("");
        $('#disreason-error').html("");
        $('#disapprovedby-error').html("");
        $('#disfile-error').html("");
   }
   
   function clearReconnectModalError() {
       $('#orderno-error').html("");
        $('#date_picker-error').html("");
        $('#reason-error').html("");
        $('#approvedby-error').html("");
   }
   
    function loadDatatableLog(seq_no,meter_no) {  
               
            dtTable = $('#disconnect_reconnect_result').DataTable({
                "processing": true,
                "language": {
                        "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                "serverSide": true,
                "destroy": true,
                "ajax": {
                    'url': siteUrl + "/admin/disconnect_reconnect_search_all",
                    'type': "POST",  
                    'dataType': "json",
                },
                "columns": [
                    {"data": "name"},
                    {"data": "mobile_no"},
                    {"data": "sequence_number"},
                    {"data": "meter_no"},
                    {"data": "connection_name"},
                    {"data": "status"},
                    {"data": "action" , orderable: false, searchable: false}
                ]
            });
            
            dtTable1 = $('#disconnect_reconnect_log').DataTable({
                "processing": true,
                "language": {
                        "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                "serverSide": true,
                "destroy": true,
                "ajax": {
                    'url': siteUrl + "/admin/disconnect_reconnect_log_search",
                    'type': "POST",
                    'data': {
                        seq_no: seq_no,
                        meter_no: meter_no
                    },
                },
                "columns": [
                    {"data": "sequence_no"},
                    {"data": "meter_no"},
                    {"data": "order_no"},
                    {"data": "date"},
                    {"data": "reason"},
                    {"data": "approved_by"},
                    {"data": "operation"},
                    {"data": "document"}
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
</script>
@endsection