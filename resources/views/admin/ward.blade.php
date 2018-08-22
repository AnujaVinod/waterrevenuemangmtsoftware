@extends('layouts.admin_master')
@section('content')
<section class="content-header">
    <h1>
        Ward
    </h1> 
</section>
<!-- Main content -->
<section class="content container-fluid">
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Manage - Ward</h3>
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
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <form name="ward_form" method="POST" action="{{ URL::to('admin/saveWard') }}">
                {{ csrf_field() }}
                <div class="col-md-5 form-group">
                    <b>Zone</b>
                    <select class="form-control" id="single_cal5" placeholder="" name="zone" required="required" id="zone" aria-describedby="inputSuccess2Status3"> 
                        <option value="">select</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}" {{ (old('zone') == $zone->id ? "selected":"") }}>{{ $zone->zone_name }}</option>                                                                                   @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <b>Ward Name</b>
                    <input type="text" class="form-control" id="" name="ward_name" value="{{ old('ward_name') }}" required="required" placeholder="Corp ward">
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
            <h3 class="box-title">Ward  List</h3>
        </div>
        <div class="box-body">
            <div class="box-body table-responsive no-padding ">
                <table id="ward" class="table table-responsive table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Zone</th>
                            <th>Ward Name</th>
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
            dtTable = $('#ward').DataTable({
                "processing": true,
                "language": {
                        "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                "serverSide": true,
                "destroy": true,
                "ajax": siteUrl + "/admin/getWard",
                "columns": [
                    {"data": "zone_name"},
                    {"data": "ward_name"}
                ]
            });
        });
    }
</script>
@endsection