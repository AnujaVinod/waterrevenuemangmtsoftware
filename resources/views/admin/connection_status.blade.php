@extends('layouts.admin_master')
@section('content')
<section class="content-header">
  <h1>
    Connection Status
  </h1>
</section>

<!-- Main content -->
    <section class="content container-fluid">
            <div class="box box-info">
                    <div class="box-header with-border">
                            <h3 class="box-title">Manage Connection Status</h3>
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
                        <form name="connect_status" method="POST" action="{{ URL::to('admin/saveConnectionStatus') }}">
                            {{ csrf_field() }}
                           
                            <div class="col-md-5">
                                    <b>Status</b>
                                    <input type="text" name="status" class="form-control" value="{{ old('status') }}" required="required" placeholder="Status">
                            </div>
                            <div class="col-md-2 no-padding">
                                    <button type="submit" class="btn btn-warning btn-margin btn-flat pull-left">Save</button>
                                    <button type="reset" class="btn btn-danger btn-margin btn-flat pull-left">Reset</button>
                            </div>
                        </form>
                    </div>
            </div>

            <div class="box box-danger">
                    <div class="box-header with-border">
                            <h3 class="box-title">Connection Status List</h3>
                    </div>
                    <div class="box-body">
                            <div class="box-body table-responsive no-padding ">
                                    <table id="connection_status" class="table table-responsive table-bordered table-hover table-striped">
                                            <thead>
                                                    <tr>
                                                            <th>Sl No</th>
                                                            <th>Status</th>
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
            dtTable = $('#connection_status').DataTable({
                "processing": true,
                "language": {
                        "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                "serverSide": true,
                "destroy": true,
                "ajax": siteUrl + "/admin/getConnectionStatus",
                "columns": [
                    {"data": "id"},
                    {"data": "status"}
                ]
            });
        });
    }
</script>
@endsection