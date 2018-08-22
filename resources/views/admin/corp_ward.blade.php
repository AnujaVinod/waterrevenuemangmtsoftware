@extends('layouts.admin_master')
@section('content')
<section class="content-header">
  <h1>
    Corp Ward
  </h1>
</section>

<!-- Main content -->
<section class="content container-fluid">
            <div class="box box-info">
                    <div class="box-header with-border">
                            <h3 class="box-title">Manage - Corp Ward</h3>
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
                        <form name="corpward_form" method="POST" action="{{ URL::to('admin/saveCorpWard') }}">
                            {{ csrf_field() }}
                            <div class="col-md-5 form-group">
                                <b>Ward Name</b>
                                <select multiple class="form-control select2" name="ward_name[]" required="required"  style="width: 100%;">
                                        @foreach($wards as $ward)
                                            <option value="{{ $ward->id }}" {{ (collect(old('ward_name'))->contains($ward->id)) ? 'selected':'' }}>{{ $ward->ward_name }}</option>                                                                     @endforeach
                                </select>
                            </div>
                            <div class="col-md-5">
                                    <b>Corp Ward</b>
                                    <input type="text" class="form-control" id="corp_ward" name="corp_name" value="{{ old('corp_name') }}" required="required" placeholder="Corp ward">
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
                            <h3 class="box-title">Corp Ward List</h3>
                    </div>
                    <div class="box-body">
                            <div class="box-body table-responsive no-padding ">
                                    <table id="ward" class="table table-responsive table-bordered table-hover table-striped">
                                            <thead>
                                                    <tr>                                                         
                                                            <th>Corp Ward</th>
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
            dttTable = $('#ward').DataTable({
                "processing": true,
                "language": {
                        "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                "serverSide": true,
                "destroy": true,
                "ajax": siteUrl + "/admin/getCorpWard",
                "columns": [                   
                    {"data": "corp_name"},
                    {"data": "ward_name"}
                ]
            });
        });
    }
</script>
@endsection