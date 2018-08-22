@extends('layouts.admin_master')
@section('content')
<section class="content-header">
  <h1>
    Manage Agent
  </h1>
</section>

<!-- Main content -->
    <section class="content container-fluid">
            <div class="box box-info">
                    <div class="box-header with-border">
                            <h3 class="box-title">Manage Agent</h3>
                            <!-- /.box-tools -->
                    </div>
                    <div class="box-body">
                        @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
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
                        <form name="agent" method="POST" action="{{ URL::to('admin/saveAgent') }}">
                            {{ csrf_field() }} 
                            <input type="hidden" name="cat_id" value="{{ $categoryId }}">
                            <div class="col-md-3 col-sm-12 form-group">
                                    <b>Agent Code</b>
                                    <input type="text" class="form-control" id="" name="agent_code" value="{{ old('agent_code') }}" required="required" placeholder="Agent Code">
                            </div>
                            <div class="col-md-3 col-sm-12 form-group">
                                    <b>Agent Name</b>
                                    <input type="text" class="form-control" id="" name="agent_name" value="{{ old('agent_name') }}" required="required" placeholder="Agent Name">
                            </div>
                            <div class="col-md-3 col-sm-12 form-group">
                                    <b>Password</b>
                                    <input type="password" class="form-control" id="" name="password" value="{{ old('password') }}" required="required">
                            </div>
                            <div class="col-md-3 col-sm-12 form-group">
                                    <b>Confirm Password</b>
                                    <input type="password" class="form-control" id="" name="confirm_password" value="{{ old('confirm_password') }}" required="required">
                            </div>
                            <div class="col-md-3 col-sm-12 form-group">
                                <b>Corp Ward Name</b>
                                <select class="form-control select2 corpwardname" name="corp_ward" required="required">
                                    <option value="">select</option>
                                         
                                       @foreach($corpwards as $corpward)
                                            <option value="{{ $corpward->id }}" {{ (old('corp_ward') == $corpward->id ? "selected":"") }}>{{ $corpward->corp_name }}</option>                                                                     @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-12 form-group"></div>
                            <div class="clear"></div>
                            <hr> 
                            <div class="col-md-3 col-sm-12 form-group">
                                <b>Ward Name</b>
                                <select class="form-control select2 wardname" name="ward_name" required="required"  >
                                    <option value="">select</option>
                                        @foreach($wards as $ward)
                                            <option value="{{ $ward->id }}" {{ (old('ward_name') == $ward->id ? "selected":"") }}>{{ $ward->ward_name }}</option>                                                                     @endforeach
                                            <a href="tariff_change.blade.php"></a>
                                </select>
                            </div>
                            
                            <div class="col-md-3 col-sm-12 form-group">
                                <b>Inspector Name</b>
                                <select class="form-control select2 inspectorname" name="inspector_name" required="required">
                                    <option value="">select</option>
                                                                                     
                                </select>
                            </div>
                            <div class="col-md-12">
                                    <button type="reset" class="btn btn-danger btn-flat pull-right">Reset</button>
                                    <button type="submit" class="btn btn-warning btn-flat pull-right">Save</button>
                            </div>
                        </form>
                    </div>
            </div>

            <div class="box box-danger">
                <div id="success-msg1" class="hide">
                    <div class="alert alert-info alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <strong>Agent Updated Successfully!!</strong> 
                    </div>
                </div>
                <div class="box-header with-border">
                    <h3 class="box-title">Agents List</h3>
                </div>
                <div class="box-body">
                    <div class="box-body table-responsive no-padding ">
                        <table id="agenttbl" class="table table-responsive table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Agent Code</th>
                                    <th>Agent Name</th>
                                    <th>Corp Ward Name</th>
                                    <th>Inspector Name</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>                                            
                        </table>
                    </div>
                </div>
            </div>
</section>
<script>var siteUrl = '<?php echo url('/'); ?>';</script>
<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        loadDatatable();
    });

$('input:password').keypress(function( e ) {
    if(e.which === 32) 
        return false;
});
// function to load DataTable 
    function loadDatatable() {
        $(function () {
            dtTable = $('#agenttbl').DataTable({
                "processing": true,
                "language": {
                        "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                "serverSide": true,
                "destroy": true,
                "ajax": siteUrl + "/admin/getAgents",
                "columns": [
                    {"data": "agent_code"},
                    {"data": "name"},
                    {"data": "corp_name"},
                    {"data": "inspector_name"},
                    {"data": "status"},
                    {"data": "action"}
                ]
            });
        });
    }
    
    $('body').on('click', '.changestatus', function () {       
        var userId = $(this).data("value");
        var userName = $(this).data("name");
        var action = $(this).data("action");        
            $.ajax({
            url: siteUrl + "/admin/changeAgentStatus",
            type: 'POST',
            data: {'userId': userId, 'action':action,'username':userName},
            async: true,
            // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                if (data.errors) {
                    alert(data.errors);
                }
                if (data.success) {
                    $('#success-msg1').removeClass('hide');
                    setInterval(function () {
                        $('#success-msg1').addClass('hide');
                    }, 2000);                
                    loadDatatable();
                }
            },
        });
    });
    
    $('body').on('change', '.wardname', function () {
        var val = $(this).val();
        $.ajax({
            url: siteUrl + "/admin/getInspectorWard",
            type: 'POST',
            data: {'wardId': val},
            async: true,
            // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {                
                if (data.success == '1') {
                    console.log(data.inspectors);
                    var i = 0;
                    $('.inspectorname').html('');
                        $('.inspectorname')
                        .append($("<option></option>")
                        .attr("value",'')
                        .text("select"));                      
                    $(data.inspectors).each(function(){
                        $('.inspectorname')
                        .append($("<option></option>")
                        .attr("value",data.inspectors[i].id)
                        .text(data.inspectors[i].inspector_name));                         
                        i++;
                    });
                    
                  /*  var j = 0;
                    $('.corpwardname').html('');
                        $('.corpwardname')
                        .append($("<option></option>")
                        .attr("value",'')
                        .text("select"));                      
                    $(data.corpwards).each(function(){
                        $('.corpwardname')
                        .append($("<option></option>")
                        .attr("value",data.corpwards[j].id)
                        .text(data.corpwards[j].corp_name));                         
                        j++;
                    }); */
                }
                if (data.success == '0') {
                  /*  var j = 0;
                    $('.corpwardname').html('');
                        $('.corpwardname')
                        .append($("<option></option>")
                        .attr("value",'')
                        .text("select"));                      
                    $(data.corpwards).each(function(){
                        $('.corpwardname')
                        .append($("<option></option>")
                        .attr("value",data.corpwards[j].id)
                        .text(data.corpwards[j].corp_name));                         
                        j++;
                    }); */
                    $('.inspectorname').html('');
                    $('.inspectorname')
                        .append($("<option></option>")
                        .attr("value",'')
                        .text("No inspector available. Kindly add inspector for this ward"));  
                }
            },
        });
    });
     
</script>
@endsection