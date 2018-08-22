@extends('layouts.admin_master')
@section('content')
<section class="content-header">
  <h1>
  Connection Type
  </h1>
</section>

<!-- Main content -->
    <section class="content container-fluid">
            <div class="box box-info">
                    <div class="box-header with-border">
                            <h3 class="box-title">Manage Connection Type</h3>
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
                                <li>{{ $error }} </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <form name="connection_type" method="POST" action="{{ URL::to('admin/saveConnectionType') }}">
                            {{ csrf_field() }} 
                            <div class="col-md-5">
                                    <b>Connection Code</b>
                                    <input type="text" class="form-control" name="connection_code" value="{{ old('connection_code') }}" id="connection_code" required="required" placeholder="Connection Code">
                            </div>
                            <div class="col-md-5">
                                    <b>Connection Type</b>
                                    <input type="text" class="form-control" name="connection_type" value="{{ old('connection_type') }}" required="required" placeholder="Connection Type">
                            </div>
                            <div class="col-md-2 no-padding">
                                    <button type="submit" class="btn btn-warning btn-margin btn-flat pull-left">Save</button>
                                    <button type="reset" class="btn btn-danger btn-margin btn-flat pull-left">Reset</button>
                            </div>
                    </div>
            </div>

            <div class="box box-danger">
                    <div class="box-header with-border">
                            <h3 class="box-title">Connection Types List</h3>
                    </div>
                    <div class="box-body">
                            <div class="box-body table-responsive no-padding ">
                                    <table id="connection_type" class="table table-responsive table-bordered table-hover table-striped">
                                            <thead>
                                                    <tr>
                                                            <th>Connection Code</th>
                                                            <th>Connection Type</th>
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
            dtTable = $('#connection_type').DataTable({
                "processing": true,
                "language": {
                        "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                "serverSide": true,
                "destroy": true,
                "ajax": siteUrl + "/admin/getConnectionType",
                "columns": [
                    {"data": "connection_code"},
                    {"data": "connection_name"}
                ]
            });
        });
    }
</script>
@endsection