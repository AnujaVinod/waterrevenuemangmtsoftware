@extends('layouts.admin_master')
@section('content')
<section class="content-header">
    <h1>
        Add Connection Details
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
            <h3 class="box-title">Add Connection Details</h3>
            <!-- /.box-tools -->
        </div>
        <div class="box-body">
            <div id="success-msg1" class="hide">
                <div class="alert alert-info alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <strong>Record Added Successfully!!</strong> 
                </div>
            </div>
            @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif

            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li> {{ $error }} </li>
                    @endforeach
                </ul>
            </div>
            @endif
            <form name="addconnection" id="addconnection" method="POST" >
                {{ csrf_field() }}
                <table class="table table-bordered table-hover ">

                    <tbody>
                        <tr>
                            <td width="20%">Sequence Number</td>
                            <td width="30%"><input type="text" class="form-control" name="sequence_number" id="sequence_number" required="required" placeholder="Sequence Number" value="{{ old('sequence_number') }}">
                                <span class="text-danger">
                                    <strong class="error_msg_disp" id="sequence_number-error"></strong>
                                </span>
                            </td>
                            <td width="20%">
                                Name of the Owner 
                            </td>
                            <td width="30%"><input type="text" class="form-control" name="cname" id="cname" required="required" placeholder="Name of the Owner" value="{{ old('cname') }}">
                                <span class="text-danger">
                                    <strong class="error_msg_disp" id="cname-error"></strong>
                                </span>
                            </td>
                             
                        </tr>
                        <tr>
                            <td width="20%">Door No</td>
                            <td width="30%"><input type="text" class="form-control" name="door_no" id="door_no" required="required" placeholder="Door No" value="{{ old('door_no') }}"></td>
                            <td width="20%">
                                Khata No
                            </td>
                            <td width="30%"><input type="text" class="form-control" name="khata_no" id="khata_no" required="required" placeholder="Khata No" value="{{ old('khata_no') }}"></td>
                        </tr>
                        <tr>
                            <td width="20%">Ward</td>
                            <td width="30%">
                                <select class="form-control select2 wardname" name="ward_id" value="{{ old('ward_id') }}" required="required" style="width: 100%;">
                                    <option value="">Select</option>
                                    @foreach($wards as $ward)
                                    <option value="{{ $ward->id }}">{{ $ward->ward_name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger">
                                    <strong class="error_msg_disp" id="ward_id-error"></strong>
                                </span>
                            </td>
                            <td width="20%">
                                Corp Ward
                            </td>
                            <td width="30%">
                                <select class="form-control select2 corpname" value="{{ old('corp_ward_id') }}" name="corp_ward_id" required="required" style="width: 100%;">
                                    <option value="">Select</option>                                                                    
                                </select>
                                <span class="text-danger">
                                    <strong class="error_msg_disp" id="corp_ward_id-error"></strong>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="20%">Mobile Number</td>
                            <td width="30%"><input type="text" class="form-control" value="{{ old('mobile_no') }}" name="mobile_no" id="mobile_no" required="required" placeholder="Mobile Number">
                                <span class="text-danger">
                                    <strong id="mobile_no-error"></strong>
                                </span></td>
                            <td width="20%">
                                No- of Flats
                            </td>
                            <td width="30%"><input type="text" class="form-control" onkeypress="return isNumberKey(event)" maxlength="3" name="no_of_flats" value="{{ old('no_of_flats') }}" id="no_of_flats" required="required" placeholder="No-of Flats"></td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <table class="table table-bordered table-hover ">
                    <thead>
                        <tr>
                            <th colspan="4">Connection Information </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td width="20%">Tariff </td>
                            <td width="30%">
                                <select class="form-control select2" name="connection_type" required="required" style="width: 100%;">
                                    <option value="">Select</option>
                                    @foreach($connTypes as $connType)
                                    <option value="{{ $connType->id }}" {{ (old("connection_type") == $connType->id ? "selected":"") }} >{{ $connType->connection_name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger">
                                    <strong class="error_msg_disp" id="connection_type-error"></strong>
                                </span>
                            </td>
                            <td width="20%">
                                Connection Date
                            </td>
                            <td width="30%">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" name="connection_date" value="{{ old('connection_date') }}" required="required" class="form-control pull-right" id="date_picker">                                                                  
                                </div>
                                <span class="text-danger">
                                    <strong class="error_msg_disp" id="connection_date-error"></strong>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="20%">Connection Status</td>
                            <td width="30%">
                                <select class="form-control select2" required="required" value="{{ old('connection_status_id') }}" name="connection_status_id" style="width: 100%;">
                                    <option value="">Select</option>
                                    <option value="2">Live</option>
                                    <option value="1">Disconnected</option>
                                </select>
                                <span class="text-danger">
                                    <strong class="error_msg_disp" id="connection_status_id-error"></strong>
                                </span>
                            </td>
                            <td width="20%">
                                Meter Number
                            </td>
                            <td width="30%"><input type="text" class="form-control" value="{{ old('meter_no') }}" required="required" name="meter_no" id="meter_no" required="required" placeholder="Meter Number">
                            <span class="text-danger">
                                    <strong class="error_msg_disp" id="meter_no-error"></strong>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="20%">Meter Sanction Date</td>
                            <td width="30%">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" value="{{ old('meter_sanctioned_date', date('m-d-Y')) }}" required="required" name="meter_sanctioned_date" id="date_picker1">
                                </div>
                            </td>
                            <td width="20%">
                                Meter Status
                            </td>
                            <td width="30%">
                                <select class="form-control select2" name="meter_status_id" value="{{ old('meter_status_id') }}" required="required" style="width: 100%;">
                                    <option value="">Select</option>
                                    @foreach($meterStatus as $meterStat)
                                    <option value="{{ $meterStat->id }}">{{ $meterStat->meter_status }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger">
                                    <strong class="error_msg_disp" id="meter_status_id-error"></strong>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Deposit Amount
                            </td>
                            <td>
                                <input type="text" class="form-control pull-right" onkeypress="return isNumberKey(event)" id="dep_amount" name="dep_amount" >
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="clear">&nbsp;&nbsp;</div>
                <button type="button" id="addConnection" class="btn btn-danger pull-right">Save</button>
            </form>
        </div>
    </div>
</section>
<script>var siteUrl = '<?php echo url('/'); ?>';</script>
<script>
    $(document).ready(function () {
        var currentDate = new Date();
        $('#date_picker').datepicker({
            autoclose: true,
            dateFormat: 'dd/mm/yy',
        })
        $("#date_picker").datepicker("setDate", currentDate);
        $('#date_picker1').datepicker({
            autoclose: true,
            dateFormat: 'dd/mm/yy',
        })
        $("#date_picker1").datepicker("setDate", currentDate);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
    });

    $('body').on('change', '.wardname', function () {
        var val = $(this).val();
        $.ajax({
            url: siteUrl + "/admin/getCorpWardForWard",
            type: 'POST',
            data: {'wardId': val},
            async: true,
            // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                if (data.success == '1') {
                    console.log(data.corpWard);
                    var i = 0;
                    $('.corpname').html('');
                    $('.corpname')
                            .append($("<option></option>")
                                    .attr("value", '')
                                    .text("select"));
                    $(data.corpWard).each(function () {
                        $('.corpname')
                                .append($("<option></option>")
                                        .attr("value", data.corpWard[i].id)
                                        .text(data.corpWard[i].corp_name));
                        i++;
                    })
                }
                if (data.success == '0') {
                    $('.corpname').html('');
                    $('.corpname')
                            .append($("<option></option>")
                                    .attr("value", '')
                                    .text("No CorpWard available"));
                }
            },
        });
    });

    $('body').on('click', '#addConnection', function () {
        var addForm = $("#addconnection");
        var formData = addForm.serialize();
        $('.error_msg_disp').html("");
        //   $('#edesignation-error').html("");
        $.ajax({
            url: siteUrl + "/admin/saveConnectionDetail",
            type: 'POST',
            data: formData,
            async: true,
            // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                if (data.errors) {
                    if (data.errors.sequence_number) {
                        $('#sequence_number-error').html(data.errors.sequence_number[0]);
                    }
                    if (data.errors.mobile_no) {
                        $('#mobile_no-error').html(data.errors.mobile_no[0]);
                    }
                    if (data.errors.connection_date) {
                        $('#connection_date-error').html(data.errors.connection_date[0]);
                    }
                    if (data.errors.ward_id) {
                        $('#ward_id-error').html(data.errors.ward_id[0]);
                    }
                    if (data.errors.corp_ward_id) {
                        $('#corp_ward_id-error').html(data.errors.corp_ward_id[0]);
                    }
                    if (data.errors.cname) {
                        $('#cname-error').html(data.errors.cname[0]);
                    }
                    if (data.errors.connection_type) {
                        $('#connection_type-error').html(data.errors.connection_type[0]);
                    }
                    if (data.errors.connection_status_id) {
                        $('#connection_status_id-error').html(data.errors.connection_status_id[0]);
                    }
                    if (data.errors.meter_no) {
                        $('#meter_no-error').html(data.errors.meter_no[0]);
                    }
                    if (data.errors.meter_status_id) {
                        $('#meter_status_id-error').html(data.errors.meter_status_id[0]);
                    }

                }
                if (data.success) {
                    $('#success-msg1').removeClass('hide');
                    setInterval(function () {
                        $('#success-msg1').addClass('hide');

                    }, 3000);
                    $("#addconnection")[0].reset();
                }
            },
        });
    });

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