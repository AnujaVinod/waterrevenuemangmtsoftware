@extends('layouts.admin_master')
@section('content')
<section class="content-header">
  <h1>
   Manage Connection Details
  </h1>
</section>

<!-- Main content -->
<section class="content container-fluid">
            <div class="box box-info">
                    <div class="box-header with-border">
                            <h3 class="box-title">Manage Connection</h3>
                            <!-- /.box-tools -->
                    </div>
                    <div class="box-body">
                        <div class="alert alert-danger error_class hide" >                            
                                  Please Enter any search criteria                     
                        </div>
                            <div class="col-md-3 col-sm-12">
                                    <b>Sequence Number</b>
                                    <input type="text" class="form-control" name="seq_no" id="sequence_number" required="required" placeholder="Sequence Number">
                            </div>
                            <div class="col-md-3 col-sm-12">
                                    <b>Meter Number</b>
                                    <input type="text" class="form-control" name="meter_no" id="meter_no" required="required" placeholder="Meter Number">
                            </div>
                            <div class="col-md-3 col-sm-12">
                                    <b>Connection Type</b>
                                    <select class="form-control select2" id="conn_type" name="conn_type" style="width: 100%;">
                                            <option value="">Select</option>
                                          @foreach($connTypes as $connType)
                                            <option value="{{ $connType->id }}">{{ $connType->connection_name }}</option>                                                                         @endforeach
                                    </select>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                    <b>Ward</b>
                                    <select class="form-control select2 wardname" name="ward" id="ward" style="width: 100%;">
                                            <option value="">Select</option>
                                          @foreach($wards as $ward)
                                            <option value="{{ $ward->id }}">{{ $ward->ward_name }}</option>                                                                         @endforeach
                                    </select>
                            </div>                            
                            <div class="col-md-3 col-sm-12 form-group">
                                <b>Corp Ward</b>
                                <select class="form-control select2 corpwardname" name="corp_ward" id="corp_ward"  style="width: 100%;">
                                    <option value="">select</option>
                                                                                     
                                </select>
                            </div>
                            <div class="col-md-1 col-sm-12">
                                    <button type="button" class="btn btn-warning btn-flat btn-margin margin pull-right search">Search</button>
                            </div>
                    </div>
            </div>
            <div class="box box-danger">
                    <div class="box-header with-border">
                            <h3 class="box-title">Connected Search Result</h3>
                                    <!-- /.box-tools -->
                    </div>
                    <div class="box-body">
                            <div class="box-body table-responsive no-padding ">
                                    <table id="conn_details" class="table table-responsive table-bordered table-hover table-striped">
                                            <thead>
                                                    <tr>
                                                            <th>Name</th>
                                                            <th>Phone Number</th>
                                                            <th>Sequence Number</th>
                                                            <th>Meter Number</th>
                                                            <th>Connection Type</th>
                                                            <th>Meter Status</th>
                                                            <th>Status</th>
                                                            <th>Operation</th>
                                                    </tr>
                                            </thead>                                         
                                    </table>
                            </div>
                    </div>
            </div>
    </section>
    <div class="modal modal-default fade" id="connection_details">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">View Connection Details</h4>
				</div>
				<div class="modal-body">
					<table id="example1" class="table table-responsive table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th>Name</th>
								<th>Phone Number</th>
								<th>Sequence Number</th>
								<th>Meter Number</th>
								<th>Connection Type</th>
								<th>Connection Status</th>
                                                                <th>Meter Status</th>
							</tr>
						</thead>
							<tr>
								<td id="name"></td>
								<td id="phone_no"></td>
								<td id="seq_no"></td>
								<td id="met_no"></td>
								<td id="conn_types"></td>
								<td id="status"></td>
                                                                <td id="meter_stat"></td>
							</tr>
						<thead>
							<tr>
								<th>Meter Sanctioned Date</th>
								<th>Connection Date</th>
                                                                <th>Door No</th>
								<th>Ward</th>
								<th>Corp  Ward</th>
								<th>Address</th>
                                                                <th></th>
							</tr>
						</thead>
                                                <tr>
                                                    <td id="app_date"></td>
                                                    <td id="conn_date"></td>
                                                    <td id="door_numb"></td>
                                                    <td id="wardnm"></td>
                                                    <td id="corp"></td>
                                                    <td id="address"></td>
                                                    <th></th>
                                                </tr>						
							
					</table>
				</div>
			</div>
		</div>
	</div>
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
            dtTable = $('#conn_details').DataTable({
                "processing": true,
                "language": {
                        "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                "serverSide": true,
                "destroy": true,
                "ajax":{
                     "url": siteUrl + "/admin/getConnectionDetail",
                     "dataType": "json",
                     "type": "POST"
                   },
              //  "ajax": siteUrl + "/admin/getConnectionDetail",
                "columns": [
                    {"data": "name"},
                    {"data": "mobile_no"},
                    {"data": "sequence_number"},
                    {"data": "meter_no"},
                    {"data": "connection_name"},
                    {"data": "meter_status"},
                    {"data": "status"},
                    {"data": "action", orderable: false, searchable: false}
                ]
            });
        });
    }
    
//Edit connection
    $('body').on('click', '#viewBtn', function () {
       // $("#editForm").trigger( "reset" );
       // $('#eemail-error').html("");
       // $('#econtactnumber-error').html("");
       // $('#erole-error').html("");
       // $('#edesignation-error').html("");
       // $('.edesignation').prop('disabled', false);
       // $(".erole option:contains('Administrator')").prop('disabled', false);
       // $(".erole option:contains('Viewer')").prop('disabled', false);
        var connId = $(this).data("value");
        $.ajax({
            url: siteUrl + "/admin/getConnectionInfo",
            type: 'POST',
            data: {'connId': connId},
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
                                   
                        $('#name').html('');
                        $('#phone_no').html('');
                        $('#seq_no').html('');
                        $('#met_no').html('');
                        $('#conn_types').html('');
                        $('#status').html('');
                        $('#meter_stat').html('');
                        $('#app_date').html('');
                        $('#conn_date').html('');
                        $('#wardnm').html('');
                        $('#corp').html('');
                        $('#address').html('');
                        $('#door_numb').html('');
                        
                        var datetime = data.response[0].connection_date;
                        var conn_date = datetime.split(' ')[0];
                        var revdate = conn_date.split("-").reverse().join("-");
                        
                        $('#wardnm').html(data.response[0].ward_name);
                        $('#corp').html(data.response[0].corp_name);
                     //   $('#address').html(data.response[0].sub_category_id);
                        $('#name').html(data.response[0].name);
                        $('#phone_no').html(data.response[0].mobile_no);
                        $('#seq_no').html(data.response[0].sequence_number);
                        $('#met_no').html(data.response[0].meter_no);
                        $('#conn_types').html(data.response[0].connection_name);
                        $('#status').html(data.response[0].status);
                        $('#meter_stat').html(data.response[0].meter_status);
                        $('#app_date').html(data.response[0].meter_sanctioned_date);
                        $('#conn_date').html(revdate);
                        $('#door_numb').html(data.response[0].door_no);
                       // $('#conn_date').html({{ Carbon\Carbon::parse('2015-10-28 19:18:44')->format('d-m-Y i') }});
                     
                       var address = '<p><span>'+((data.response[0].premises_owner_name == null)? '':data.response[0].premises_owner_name)+'</span><br><span>'+((data.response[0].premises_address == null)? '':data.response[0].premises_address)+'</span><br><span>'+((data.response[0].premises_street == null)? '':data.response[0].premises_street)+'</span><span>'+((data.response[0].premises_city == null)? '':data.response[0].premises_city)+'</span><br><span>'+((data.response[0].premises_state == null)? '':data.response[0].premises_state)+'</span><span>'+((data.response[0].premises_zip == null)? '':data.response[0].premises_zip)+'</span></p>';
                        $('#address').html(address);
                       
                        $('#connection_details').modal('show');
                    
                }
            },
        });
    });
    
$('body').on('click', '.search', function () {
    $('.error_class').addClass('hide');
    var seq_no=$("#sequence_number").val();
    var conn_type=$("#conn_type").val();
    var ward=$("#ward").val();
    var corp_ward=$("#corp_ward").val();
    var meter_no = $("#meter_no").val();
    if(seq_no == '' && conn_type == '' && ward == '' && corp_ward == '' && meter_no =='')
    {        
        $('.error_class').removeClass('hide');
    }else{
    
            dtTable = $('#conn_details').DataTable({
                   "processing": true,
                   "language": {
                        "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                    },
                   "serverSide": true,
                   "destroy": true,
                   "ajax": {
                    'url': siteUrl + "/admin/connection_search",
                    'type': "POST",
                     'data': {                              
                              seq_no: seq_no,
                              conn_type: conn_type,
                              ward:ward,
                              corp_ward:corp_ward,
                              meter_no:meter_no
                          },
                    },
                    "columns": [
                        {"data": "name"},
                        {"data": "mobile_no"},
                        {"data": "sequence_number"},
                        {"data": "meter_no"},
                        {"data": "connection_name"},
                        {"data": "meter_status"},
                        {"data": "status"},
                        {"data": "action"}
                    ]

                });
            }
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
                    var j = 0;
                    $('.corpwardname').html('');
                        $('.corpwardname')
                        .append($("<option></option>")
                        .attr("value",'')
                        .text("select"));                      
                    $(data.corpWard).each(function(){
                        $('.corpwardname')
                        .append($("<option></option>")
                        .attr("value",data.corpWard[j].id)
                        .text(data.corpWard[j].corp_name));                         
                        j++;
                    });
                }
                if (data.success == '0') {
                    var j = 0;
                    $('.corpwardname').html('');
                        $('.corpwardname')
                        .append($("<option></option>")
                        .attr("value",'')
                        .text("No CorpWard available"));                     
                                       
                }
            },
        });
    });
    </script>
@endsection
