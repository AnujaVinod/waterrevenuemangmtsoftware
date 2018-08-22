@extends('layouts.admin_master')
@section('content')
<section class="content-header">
    <h1>
        Edit Connection Details
    </h1>    
</section>

<!-- Main content -->
<section class="content container-fluid">
    <?php
    $role1 = Helper::getRole();
    $category = $role1->category_name;
    ?> 
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Edit Connection Details</h3>
            <!-- /.box-tools -->
        </div>
        <div class="box-body">
            <div id="success-msg1" class="hide">
                <div class="alert alert-info alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <strong>Record Updated Successfully!!</strong> 
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
            <form name="editconnection" id="editconnection" method="POST" >
                {{ csrf_field() }}
                <input type="hidden" name="conId" value="{{ $connDetails[0]->id }}">
                <table class="table table-bordered table-hover ">

                    <tbody>
                        <tr>
                            <td width="20%">Sequence Number</td>
                            <td width="30%"><input type="text" readonly class="form-control" name="sequence_number" id="sequence_number" required="required" value="{{ isset($connDetails) ? $connDetails[0]->sequence_number : old('sequence_number') }}">
                                <span class="text-danger">
                                    <strong id="sequence_number-error"></strong>
                                </span>
                            </td>
                            <td width="20%">
                                Name of the Owner 
                            </td>
                            <td width="30%"><input type="text" class="form-control" name="name" id="cname" required="required" placeholder="Name of the Owner" value="{{ isset($connDetails) ? $connDetails[0]->name : old('name') }}">
                                <span class="text-danger">
                                    <strong id="name-error"></strong>
                                </span></td>
                            </td>
                        </tr>
                        <tr>
                            <td width="20%">Door No</td>
                            <td width="30%"><input type="text" class="form-control" name="door_no" id="door_no" required="required" placeholder="Door No" value="{{ isset($connDetails) ? $connDetails[0]->door_no : old('door_no') }}">
                                <span class="text-danger">
                                    <strong id="door_no-error"></strong>
                                </span>
                            </td>
                            <td width="20%">
                                Khata No
                            </td>
                            <td width="30%"><input type="text" class="form-control" name="khata_no" id="khata_no" required="required" placeholder="Khata No" value="{{ isset($connDetails) ? $connDetails[0]->khata_no : old('khata_no') }}"></td>
                        </tr>
                        <tr>
                            <td width="20%">Ward</td>
                            <td width="30%">
                                @if($meterReadingCount == 1)
                                <select class="form-control select2 wardname" name="ward_id" required="required" style="width: 100%;">
                                    <option value="">Select</option>
                                    @foreach($wards as $ward)
                                    @if ($ward->ward_name == $connDetails[0]->ward_name)
                                    <option value="{{ $ward->id }}" selected="selected">{{ $ward->ward_name }}</option>
                                    @else
                                    <option value="{{ $ward->id }}">{{ $ward->ward_name }}</option>
                                    @endif
                                    @endforeach
                                </select>
                                @else
                                <input type="hidden" name="ward_id" id="ward_id" value="{{$connDetails[0]->ward_id}}">
                                @endif
                                <span class="text-danger">
                                    <strong id="ward_id-error"></strong>
                                </span>
                                <input type="hidden"  name="ward_name" id="ward_name" value="{{ isset($connDetails) ? $connDetails[0]->ward_name : '' }}">
                                <input @if($meterReadingCount == 1) type="hidden" @else type="text" class="form-control" readonly @endif name="ward_name_old" id="ward_name_old" value="{{ isset($connDetails) ? $connDetails[0]->ward_name : '' }}">
                                        <input type="hidden"  id="ward_id_old" value="{{ isset($connDetails) ? $connDetails[0]->ward_id : '' }}">
                                @if($meterReadingCount == 1)
                                <a href="#" id="reset_wardname"><i class="fa fa-check"></i>Reset Ward Name</a>
                                @endif
                            </td>
                            <td width="20%">
                                Corp Ward
                            </td>
                            <td width="30%">
                                <input type="text" readonly class="form-control" name="corp_name" id="corp_name" required="required" value="{{ isset($connDetails) ? $connDetails[0]->corp_name : old('corp_name') }}" >
                                <input type="hidden" name="corp_name_old" id="corp_name_old" value="{{ isset($connDetails) ? $connDetails[0]->corp_name : '' }}">
                                <input type="hidden"  id="corp_id_old" value="{{ isset($connDetails) ? $connDetails[0]->corp_id : '' }}">
                                <input type="hidden" readonly class="form-control" name="corp_id" id="corp_id" value="{{ isset($connDetails) ? $connDetails[0]->corp_id :'' }}" >
                                @if($meterReadingCount == 1)
                                <a href="#" id="changecorp"><i class="fa fa-check"></i> Change</a>

                                <div class="new_corp" style="display:none">
                                    <select class="form-control select2 corpname" value="" name="corp_ward_id" required="required" style="width: 100%;">                                                                
                                    </select>
                                    <a href="#" id="reset_corpname"><i class="fa fa-check"></i>Reset Corp Name</a>
                                </div>
                                @endif
                                <span class="text-danger">
                                    <strong id="corp_name-error"></strong>
                                </span>
                            </td>                                               
                        </tr>
                        <tr>
                            <td width="20%">Mobile Number</td>
                            <td width="30%"><input type="text" class="form-control" value="{{ isset($connDetails) ? $connDetails[0]->mobile_no : old('mobile_no') }}" name="mobile_no" id="mobile_no" required="required" placeholder="Mobile Number">
                                <span class="text-danger">
                                    <strong id="mobile_no-error"></strong>
                                </span></td>
                            <td width="20%">
                                No- of Flats
                            </td>
                            <td width="30%"><input type="text" class="form-control" name="no_of_flats" value="{{ isset($connDetails) ? $connDetails[0]->no_of_flats : old('no_of_flats') }}" id="no_of_flats" required="required" placeholder="No-of Flats"></td>
                        </tr>
                    </tbody>
                </table>
                <br>
                @if(strcasecmp($category,"Super Admin") == 0)
                <input type="hidden" name="edit_connection_info" id="edit_connection_info" value="1">
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
                                    @if ($connType->connection_name == $connDetails[0]->connection_name)
                                    <option value="{{ $connType->id }}" selected="selected">{{ $connType->connection_name }}</option>
                                    @else
                                    <option value="{{ $connType->id }}">{{ $connType->connection_name }}</option>
                                    @endif
                                    @endforeach

                                </select> 
                                <span class="text-danger">
                                    <strong id="connection_type-error"></strong>
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
                                    <input type="text" name="connection_date" value="{{ isset($connDetails) ? $connDetails[0]->connection_date : old('connection_date') }}" required="required" class="form-control pull-right" id="date_picker">                                                                  
                                </div>
                                <span class="text-danger">
                                    <strong id="connection_date-error"></strong>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="20%">Connection Status</td>
                            <td width="30%">                            
                                <select class="form-control select2" name="connection_status_id" style="width: 100%;">
                                    <option value="">Select</option>
                                    @if ($connDetails[0]->con_status_id == 2)
                                    <option value="2" selected="selected">Live</option>
                                    <option value="1">Disconnected</option>
                                    @endif
                                    @if ($connDetails[0]->con_status_id == 1)
                                    <option value="2">Live</option>
                                    <option value="1" selected="selected">Disconnected</option> 
                                    @endif                                                                   
                                </select> 
                                <span class="text-danger">
                                    <strong id="connection_status_id-error"></strong>
                                </span>
                            </td>
                            <td width="20%">
                                Meter Number
                            </td>
                            <td width="30%"><input type="text" class="form-control" value="{{ isset($connDetails) ? $connDetails[0]->meter_no : old('meter_no') }}" required="required" name="meter_no" id="meter_no" placeholder="Meter Number">
                                <span class="text-danger">
                                    <strong id="meter_no-error"></strong>
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
                                    <input type="text" class="form-control pull-right" value="{{ isset($connDetails) ? $connDetails[0]->meter_sanctioned_date : old('meter_sanctioned_date') }}" required="required" name="meter_sanctioned_date" id="date_picker1">
                                </div>
                            </td>
                            <td width="20%">
                                Meter Status
                            </td>
                            <td width="30%">                             
                                <select class="form-control select2" name="meter_status_id" value="{{ old('meter_status_id') }}" required="required" style="width: 100%;">
                                    <option value="">Select</option>
                                    @foreach($meterStatus as $meterStat)
                                    @if ($meterStat->meter_status == $connDetails[0]->meter_status)
                                    <option value="{{ $meterStat->id }}" selected="selected">{{ $meterStat->meter_status }}</option>
                                    @else
                                    <option value="{{ $meterStat->id }}">{{ $meterStat->meter_status }}</option>
                                    @endif
                                    @endforeach
                                </select>
                                <span class="text-danger">
                                    <strong id="meter_status_id-error"></strong>
                                </span>
                            </td>                            
                        </tr>
                    </tbody>
                </table>
                @else
                <input type="hidden" name="edit_connection_info" id="edit_connection_info" value="0">
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
                                <input type="text" class="form-control" readonly value="{{ isset($connDetails) ? $connDetails[0]->connection_name : old('connection_type') }}" required="required" name="connection_type">                              
                                <span class="text-danger">
                                    <strong id="connection_type-error"></strong>
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
                                    <input type="text" name="connection_date" readonly value="{{ isset($connDetails) ? $connDetails[0]->connection_date : old('connection_date') }}" required="required" class="form-control pull-right">                                                                  
                                </div>
                                <span class="text-danger">
                                    <strong id="connection_date-error"></strong>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="20%">Connection Status</td>
                            <td width="30%">
                                <input type="text" class="form-control" readonly value="{{ isset($connDetails) ? $connDetails[0]->status : old('connection_status_id') }}" required="required" name="connection_status_id">                            
                                <span class="text-danger">
                                    <strong id="connection_status_id-error"></strong>
                                </span>
                            </td>
                            <td width="20%">
                                Meter Number
                            </td>
                            <td width="30%"><input type="text" class="form-control" readonly value="{{ isset($connDetails) ? $connDetails[0]->meter_no : old('meter_no') }}" required="required" name="meter_no" id="meter_no" placeholder="Meter Number">
                                <span class="text-danger">
                                    <strong id="meter_no-error"></strong>
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
                                    <input type="text" class="form-control pull-right" readonly value="{{ isset($connDetails) ? $connDetails[0]->meter_sanctioned_date : old('meter_sanctioned_date') }}" required="required" name="meter_sanctioned_date">
                                </div>
                            </td>
                            <td width="20%">
                                Meter Status
                            </td>
                            <td width="30%">
                                <input type="text" class="form-control" readonly value="{{ isset($connDetails) ? $connDetails[0]->meter_status : old('meter_status_id') }}" required="required" name="meter_status_id" laceholder="Meter Status">                              
                                <span class="text-danger">
                                    <strong id="meter_status_id-error"></strong>
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
                @endif
                <br>
                <table class="table table-bordered table-hover ">
                    <thead>
                        <tr>
                            <th colspan="4">Premises Address Information </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td width="20%">Premises Owner Name </td>
                            <td width="30%">
                                <input type="text" class="form-control pull-right" value="{{ isset($connDetails) ? $connDetails[0]->premises_owner_name : old('premises_owner_name') }}" name="premises_owner_name" id="premises_owner_name">
                            </td>
                            <td width="20%">
                                Premises Address
                            </td>
                            <td width="30%">
                                <input type="text" name="premises_address" value="{{ isset($connDetails) ? $connDetails[0]->premises_address : old('premises_address') }}" class="form-control pull-right" id="premises_address">                                                                                                                   
                            </td>
                        </tr>
                        <tr>
                            <td width="20%">Premises Street</td>
                            <td width="30%">
                                <input type="text" class="form-control pull-right" value="{{ isset($connDetails) ? $connDetails[0]->premises_street : old('premises_street') }}" name="premises_street" id="premises_street">
                            </td>
                            <td width="20%">
                                Premises City
                            </td>
                            <td width="30%"><input type="text" class="form-control" value="{{ isset($connDetails) ? $connDetails[0]->premises_city : old('premises_city') }}" name="premises_city" id="premises_city"></td>
                        </tr>
                        <tr>
                            <td width="20%">Premises State</td>
                            <td width="30%">
                                <input type="text" class="form-control pull-right" value="{{ isset($connDetails) ? $connDetails[0]->premises_state : old('premises_state') }}" name="premises_state" id="premises_state">
                            </td>
                            <td width="20%">
                                Premises ZIP
                            </td>
                            <td width="30%">
                                <input type="text" class="form-control pull-right" value="{{ isset($connDetails) ? $connDetails[0]->premises_zip : old('premises_zip') }}" name="premises_zip" id="premises_zip">
                                <span class="text-danger">
                                    <strong id="premises_zip-error"></strong>
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <br>           
                <div class="clear">&nbsp;&nbsp;</div>
                <a href="{{ URL::to('admin/connectionDetail') }}" class="btn btn-danger pull-right" >Cancel</a>
                <button type="button" id="updateConnection" class="btn btn-danger pull-left">Save</button>
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
        $('#date_picker1').datepicker({
            autoclose: true,
            dateFormat: 'dd/mm/yy',
        })
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
    });

    $('body').on('click', '#changecorp', function () {
        var val = $('.wardname').val();
        $.ajax({
            url: siteUrl + "/admin/getCorpWardForWard",
            type: 'POST',
            data: {'wardId': val},
            async: true,
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
                $('.new_corp').show();
            },
        });
    });

    $('.corpname').change(function () {
        $('#changecorp').hide();
        if ($(this).val() != '') {
            $('#corp_id').val($('.corpname').val());
            $('#corp_name').val($('.corpname option:selected').text());
        }
    });

    $('#reset_corpname').click(function (e) {
        $('#corp_name').val($('#corp_name_old').val());
    });

    $('#reset_wardname').click(function (e) {
        $('.wardname').val($('#ward_id_old').val()).change();
        $('#changecorp').show();
        $('.new_corp').hide();
        $('#corp_name').val($('#corp_name_old').val());
    });

    $('.wardname').change(function () {
        var val = $(this).val();
        $('#ward_name').val($(".wardname option:selected").text());
        $.ajax({
            url: siteUrl + "/admin/getCorpWardForWard",
            type: 'POST',
            data: {'wardId': val},
            async: true,
            success: function (data) {
                if (data.success == '1') {
                    $('#corp_name').val('');
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
                    $('#corp_name').val('');
                    $('.corpname').html('');
                    $('.corpname')
                            .append($("<option></option>")
                                    .attr("value", '')
                                    .text("No CorpWard available"));
                }
            },
        });
    });

    $('body').on('click', '#updateConnection', function () {
        clearError();
        var addForm = $("#editconnection");
        var formData = addForm.serialize();
        $.ajax({
            url: siteUrl + "/admin/updateConnectionDetail",
            type: 'POST',
            data: formData,
            async: true,
            success: function (data) {
                if (data.errors) {
                    if (data.errors.mobile_no) {
                        $('#mobile_no-error').html(data.errors.mobile_no[0]);
                    }
                    if (data.errors.meter_sanctioned_date) {
                        $('#meter_sanctioned_date-error').html(data.errors.meter_sanctioned_date[0]);
                    }
                    if (data.errors.connection_date) {
                        $('#connection_date-error').html(data.errors.connection_date[0]);
                    }
                    if (data.errors.name) {
                        $('#name-error').html(data.errors.name[0]);
                    }
                    if (data.errors.door_no) {
                        $('#door_no-error').html(data.errors.door_no[0]);
                    }
                    if (data.errors.ward_id) {
                        $('#ward_id-error').html(data.errors.ward_id[0]);
                    }
                    if (data.errors.corp_name) {
                        $('#corp_name-error').html(data.errors.corp_name[0]);
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
                    if (data.errors.premises_zip) {
                        $('#premises_zip-error').html(data.errors.premises_zip[0]);
                    }
                }
                if (data.success) {
                    clearError();
                    $('#success-msg1').removeClass('hide');
                    setInterval(function () {
                        $('#success-msg1').addClass('hide');
                    }, 3000);
                    window.location.href = '{{url("admin/connectionDetail")}}';
                }
            },
        });
    });

    function clearError() {
        $('#mobile_no-error').html("");
        $('#meter_sanctioned_date-error').html("");
        $('#connection_date-error').html("");
        $('#name-error').html("");
        $('#door_no-error').html("");
        $('#ward_id-error').html("");
        $('#corp_name-error').html("");
        $('#connection_type-error').html("");
        $('#connection_status_id-error').html("");
        $('#meter_no-error').html("");
        $('#meter_status_id-error').html("");
        $('#premises_zip-error').html("");
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