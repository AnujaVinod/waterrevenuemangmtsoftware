@extends('layouts.admin_master')
@section('content')
<section class="content-header">
   <h1>
     Water Connection Rates
   </h1>
 </section>

 <!-- Main content -->
     <section class="content container-fluid">
             <div class="box box-info">
                     <div class="box-header with-border">
                             <h3 class="box-title">Manage Connection Rates</h3>
                             <!-- /.box-tools -->
                     </div>
                     <div class="box-body">
                        @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif
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
                        <form name="connection_rate" method="POST" action="{{ URL::to('admin/saveConnectionRate') }}">
                            {{ csrf_field() }} 
                             <div class="col-md-3 form-group">
                                     <b>Connection Type</b>
                                     
                                     <select class="form-control select2" name="connection_type" required="required" style="width: 100%;">
                                         <option value="">select</option>
                                        @foreach($connectionTypes as $connectionType)
                                             <option value="{{ $connectionType->id }}" {{ (old('connection_type') == $connectionType->id ? "selected":"") }}>{{ $connectionType->connection_name }}</option>
                                        @endforeach
                                     </select>
                             </div>
                             <div class="col-md-3 form-group">
                                     <b>From Range</b>
                                     <input type="text" class="form-control" name="from_range" value="{{ old('from_range') }}" id="from_unit" required="required">
                             </div>
                             <div class="col-md-3 form-group">
                                     <b>To Range</b>(Enter -1 if value is Infinite )
                                     <input type="text" class="form-control" name="to_range" id="to_unit" value="{{ old('to_range') }}" required="required">
                             </div>
                             <div class="col-md-3 form-group">
                                     <b>Rate per 1000 ltr</b>
                                     <input type="text" class="form-control" name="price" id="price" value="{{ old('price') }}" required="required" placeholder="100.00">
                             </div>

                             <div class="col-md-12">
                                     
                                     <button type="reset" class="btn btn-danger btn-flat pull-right">Reset</button>
                                     <button type="submit" class="btn btn-warning btn-flat pull-right">Save</button>
                             </div>
                        </form>
                     </div>
             </div>

             <div class="box box-danger">
                <div class="box-header with-border">
                        <h3 class="box-title">Connection Rates List</h3>
                </div>
                <div class="row">
                    <div class="box-body">
                            <div class="box-body table-responsive no-padding ">
                                    <table id="connection_rates" class="table table-responsive table-bordered table-hover table-striped">
                                            <thead>
                                                    <tr>
                                                            <th>Connection Type</th>
                                                            <th>From Range</th>
                                                            <th>To Range</th>
                                                            <th>Rate Per 1000 ltr</th>
                                                            <th>Edit</th>
                                                    </tr>
                                            </thead>
                                    </table>
                            </div>
                    </div>

                </div>
            </div> 
         <div class="modal fade" id="Edit_rates">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit Rates</h4>
              </div>
              <div class="modal-body">
                    <div id="success-msg1" class="hide">
                                    <div class="alert alert-info alert-dismissible fade in" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <strong>Connection Rate Updated Successfully!!</strong> 
                                    </div>
                    </div>
                  <form method="POST" id="editForm" data-parsley-validate="" class="form-horizontal form-label-left" style="padding:17px;">
                       <input type="hidden" id="econnrateid" name="connRateId"> 
					<div class="form-group">
						<b>Connection Type</b>
						<select class="form-control select2 econnection_type" name="connection_type" id="econnection_type" style="width: 100%;">
                                                             <option value="">select</option>
                                        @foreach($connectionTypes as $connectionType)
                                             <option value="{{ $connectionType->id }}">{{ $connectionType->connection_name }}</option>
                                        @endforeach
                                                        </select>
                                                <span class="text-danger">
                                                    <strong id="econnectiontype-error"></strong>
                                                </span>
					</div>
					<div class="form-group">
                                                        <b>From Range</b>
                                                        <input type="text" class="form-control" name="from_range" id="efrom_unit" required="required" placeholder="0">
                                                        <span class="text-danger">
                                                            <strong id="efromunit-error"></strong>
                                                        </span>
                                                </div>
                                                <div class="form-group">
                                                        <b>To Range</b>(Enter -1 if value is Infinite )
                                                        <input type="text" class="form-control" name="to_range" id="eto_unit" required="required" placeholder="15000">
                                                        <span class="text-danger">
                                                            <strong id="etounit-error"></strong>
                                                        </span>
                                                </div>
                                                <div class="form-group">
                                                        <b>Rate per 1000 ltr</b>
                                                        <input type="text" class="form-control" name="price" id="eprice" required="required" placeholder="2.50">
                                                        <span class="text-danger">
                                                            <strong id="eprice-error"></strong>
                                                        </span>
                                                </div>
                       <div class="modal-footer">
                           <button type="reset" class="btn btn-warning btn-flat pull-right" data-dismiss="modal">Cancel</button>
					 <button type="button" id="updateForm" class="btn btn-danger btn-flat pull-left">Save</button>
                                                
				  </div>
                       
                      </form>
				  </div>
				  
            </div>
            <!-- /.modal-content -->
        </div>
          <!-- /.modal-dialog -->
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
            dtTable = $('#connection_rates').DataTable({
                "processing": true,
                "language": {
                        "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                "serverSide": true,
                "destroy": true,
                "ajax": siteUrl + "/admin/getConnectionRate",
                "columns": [
                    {"data": "connection_name"},
                    {"data": "from_unit"},
                    {"data": "to_unit"},
                    {"data": "price"},                   
                    {"data": "action"}
                ]
            });
        });
    }
    
    $('body').on('click', '#editBtn', function () {
        $("#editForm").trigger( "reset" );
        $('#efromunit-error').html("");
        $('#etounit-error').html("");
        $('#eprice-error').html("");       
        $('#econnectiontype-error').html("");
        var conId = '';
        conId = $(this).data("value");
        $.ajax({
            url: siteUrl + "/admin/getConnectionRateInfo",
            type: 'POST',
            data: {'conId': conId},
            // async: true,   
            success: function (data) {
                console.log(data);
                if (data.errors) {
                    alert('error');
                    //if (data.errors.name) {
                    //    $('#name-error').html(data.errors.name[0]);
                    // }            
                }
                if (data.success) { 
                    console.log(data.conndata);
                    $('#econnrateid').val(conId);
                    $('.econnection_type').val(data.conndata.connection_type_id);
                    $('#efrom_unit').val(data.conndata.from_unit);
                    $('#eto_unit').val(data.conndata.to_unit);
                    $('#eprice').val(data.conndata.price);
                    $('#Edit_rates').modal('show');
                }
            },
        });
    });
    
    $('body').on('click', '#updateForm', function () {
        var editForm = $("#editForm");
        var formData = editForm.serialize();
        $('#efromunit-error').html("");
        $('#etounit-error').html("");
        $('#eprice-error').html("");       
        $('#econnectiontype-error').html("");
        $('#econnectiontype-error').html("");
        $.ajax({
            url: siteUrl + "/admin/updateConnectionRate",
            type: 'POST',
            data: formData,
            async: true,
            // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                if (data.errors) {
                    if (data.errors.connection_type) {
                        $('#econnectiontype-error').html(data.errors.connection_type[0]);
                    }
                    if (data.errors.from_range) {
                        $('#efromunit-error').html(data.errors.from_range[0]);
                    }
                    if (data.errors.to_range) {
                        $('#etounit-error').html(data.errors.to_range[0]);
                    }
                    if (data.errors.price) {
                        $('#eprice-error').html(data.errors.price[0]);
                    }
                   
                }
                if (data.success) {                                 
                    $('#Edit_rates').modal('hide');
                    loadDatatable();
                }
            },
        });
    });
    
    var txt = document.getElementById('price');
    txt.addEventListener('keyup', myFunc);    
    function myFunc(e) {
        var val = this.value;
        var re = /^([0-9]+[\.]?[0-9]?[0-9]?|[0-9]+)$/g;
        var re1 = /^([0-9]+[\.]?[0-9]?[0-9]?|[0-9]+)/g;
        if (re.test(val)) {
            //do something here
    
        } else {
            val = re1.exec(val);
            if (val) {
                this.value = val[0];
            } else {
                this.value = "";
            }
        }
    }
    
    var txt = document.getElementById('eprice');
    txt.addEventListener('keyup', myFunc);    
    function myFunc(e) {
        var val = this.value;
        var re = /^([0-9]+[\.]?[0-9]?[0-9]?|[0-9]+)$/g;
        var re1 = /^([0-9]+[\.]?[0-9]?[0-9]?|[0-9]+)/g;
        if (re.test(val)) {
            //do something here
    
        } else {
            val = re1.exec(val);
            if (val) {
                this.value = val[0];
            } else {
                this.value = "";
            }
        }
    }
</script>
 @endsection