@extends('layouts.admin_master')
@section('content')
<section class="content-header">
  <h1>
   Manage Inspector
  </h1>
</section>

<!-- Main content -->
<section class="content container-fluid">
            <div class="box box-info">
                    <div class="box-header with-border">
                            <h3 class="box-title">Manage Inspector</h3>
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
                        <form name="inspector" method="POST" action="{{ URL::to('admin/saveInspector') }}">
                            {{ csrf_field() }} 
                            <input type="hidden" name="cat_id" value="{{ $data }}">
                            <div class="col-md-3 col-sm-12 form-group">
                                    <b>Inspector Code</b>
                                    <input type="text" class="form-control" id="inspector_code" value="{{ old('inspector_code') }}" name="inspector_code" required="required" placeholder="Inspector Code">
                            </div>
                            <div class="col-md-3 col-sm-12 form-group">
                                    <b>Inspector Name</b>
                                    <input type="text" class="form-control" id="inspector_name" value="{{ old('inspector_name') }}" name="inspector_name" required="required" placeholder="Inspector Name">
                            </div>
                            <div class="col-md-3 col-sm-12 form-group">
                                    <b>Password</b>
                                    <input type="password" name="password" class="form-control" value="{{ old('password') }}" required="required">
                            </div>
                            <div class="col-md-3 col-sm-12 form-group">
                                    <b>Confirm Password</b>
                                    <input type="password" name="confirm_password" class="form-control" value="{{ old('confirm_password') }}" required="required">
                            </div>
                            <div class="col-md-3 col-sm-12 form-group">
                                    <b>Ward</b>
                                    <select multiple class="form-control select2" name="ward_name[]" required="required"  >
                                        @foreach($wards as $ward)
                                            <option value="{{ $ward->id }}" {{ (collect(old('ward_name'))->contains($ward->id)) ? 'selected':'' }}>{{ $ward->ward_name }}</option>                                                                            @endforeach
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
                        <strong>Inspector Updated Successfully!!</strong> 
                    </div>
                </div>
                    <div class="box-header with-border">
                            <h3 class="box-title">Inspectors List</h3>
                    </div>
                    <div class="box-body">
                            <div class="box-body table-responsive no-padding ">
                                    <table id="inspectortb" class="table table-responsive table-bordered table-hover table-striped">
                                            <thead>
                                                    <tr>
                                                            <th>Inspector Code</th>
                                                            <th>Inspector Name</th>
                                                            <th>Assigned Ward</th>
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
            dtTable = $('#inspectortb').DataTable({
                "processing": true,
                "language": {
                        "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                "serverSide": true,
                "destroy": true,
                "ajax": siteUrl + "/admin/getInspector",
                "columns": [
                    {"data": "inspector_code"},
                    {"data": "inspector_name"},
                    {"data": "ward"},
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
            url: siteUrl + "/admin/changeInspectorStatus",
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
                                    
                    loadDatatable();
                }
            },
        });
    });
</script>
@endsection