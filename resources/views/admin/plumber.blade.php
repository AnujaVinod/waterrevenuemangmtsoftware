@extends('layouts.admin_master')
@section('content')
<section class="content-header">
  <h1>
    Plumber Details
  </h1>
</section>

<!-- Main content -->
<section class="content container-fluid">
            <div class="box box-info">
                    <div class="box-header with-border">
                            <h3 class="box-title">Plumber Details </h3>
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
                        <form name="application_status" method="POST" action="{{ URL::to('admin/savePlumber') }}">
                            {{ csrf_field() }} 
                            <div class="col-md-3 col-sm-12 form-group">
                                    <b>Plumber Name</b>
                                    <input type="text" class="form-control" id="plumber_name" name="plumber_name" value="{{ old('plumber_name') }}" required="required" placeholder="Plumber Name">
                            </div>
                            <div class="col-md-3 col-sm-12 form-group">
                                    <b>License Number</b>
                                    <input type="text" class="form-control" id="license_number" name="license_number" value="{{ old('license_number') }}" placeholder="License Number">
                            </div>
                            <div class="col-md-3 col-sm-12 form-group">
                                    <b>Contact number</b>
                                            <input type="text" name="contact_no" class="form-control pull-right" value="{{ old('contact_no') }}" placeholder="Contact Number">
                            </div>
                            <div class="col-md-3 col-sm-12 form-group">
                                    <button type="submit" class="btn btn-warning btn-flat pull-left btn-margin">Save</button>
                                    <button type="reset" class="btn btn-danger btn-flat pull-left btn-margin">Cancel</button>
                            </div>
                        </form>
                    </div>
            </div>



            <div class="box box-danger">
                    <div class="box-header with-border">
                            <h3 class="box-title"> Plumber List </h3>
                                    <!-- /.box-tools -->
                    </div>
                    <div class="box-body">
                            <div class="box-body table-responsive no-padding ">
                                    <table id="plumber_details" class="table table-responsive table-bordered table-hover table-striped">
                                            <thead>
                                                    <tr>
                                                            <th>Plumber Name</th>
                                                            <th>License Number</th>
                                                            <th>Contact Number</th>
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

// function to load DataTable 
    function loadDatatable() {
        $(function () {
            dtTable = $('#plumber_details').DataTable({
                "processing": true,
                "language": {
                        "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                "serverSide": true,
                "destroy": true,
                "ajax": siteUrl + "/admin/getPlumber",
                "columns": [
                    {"data": "plumber_name"},
                    {"data": "license_number"},                    
                    {"data": "contact_no"}
                ]
            });
        });
    }
</script>
@endsection