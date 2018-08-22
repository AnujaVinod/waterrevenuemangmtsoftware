@extends('layouts.admin_master')
@section('content')
	<section class="content-header">
      <h1>
        Home
        <small>Welcome to Mangaluru City Corporation Water Bill E Payment System</small>
      </h1>
    </section>

    <!-- Main content -->
	
	 <section class="content container-fluid">
	<div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>1150</h3>

              <p>Consumer</p>
            </div>
            <div class="icon">
              <i class="fa fa-user"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>53<sup style="font-size: 20px">%</sup></h3>

              <p>Bounce Rate</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>44</h3>

              <p>User Registrations</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>65</h3>

              <p>Unique Visitors</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
             <div class="row" @if($cat_id == 6)style="display:none;"@endif>
	  <div class="col-md-6	">
	  <div class="callout callout-info">
             <h1>Mangaluru City Corporation E Payment System</h1>
			<h2>One Step Payment  </h2>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
       </div>
	   </div>
	               <!-- quick email widget -->
		<div class="col-md-6">
          <div class="box box-success">
            <div class="box-header">
              <i class="fa fa-envelope"></i>

              <h3 class="box-title">Quick Email to MCC</h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
                <button type="button" class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip"
                        title="Remove">
                  <i class="fa fa-times"></i></button>
              </div>
              <!-- /. tools -->
            </div>
            <div class="box-body">
              <form action="#" method="post">
                <div class="form-group">
                  <input type="email" class="form-control" name="emailto" placeholder="Email to:">
                </div>
                <div class="form-group">
                  <input type="text" class="form-control" name="subject" placeholder="Subject">
                </div>
                <div>
                  <textarea class="textarea" placeholder="Message"
                            style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                </div>
              </form>
            </div>
            <div class="box-footer clearfix">
              <button type="button" class="pull-right btn btn-default" id="sendEmail">Send
                <i class="fa fa-arrow-circle-right"></i></button>
            </div>
          </div>
		 </div>
		 </div>
             @if($cat_id == 6)
              <div class="row">
                  <div class="col-md-12	">
                      <div class="box box-danger">
                    <div class="box-header with-border">
                        <h1 class="box-title">Activity Log</h1>
                        <form>
                        <div class="row">
                            <div class="col-md-3">
                                <b>From Date</b> 
                                <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="from_date">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <b>To Date</b>
                                <div class="input-group date">
                                        <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="to_date">
                                </div>
                            </div>	
                            <div class="col-md-12 pull-left">							
                                    <button type="reset" id="reset" class="btn btn-danger btn-flat pull-right ">Reset</button>
                                    <button type="button" class="btn btn-warning btn-flat pull-right" id="log_report_search" aria-expanded="true">View Report</button>
                            </div>
                        </div>
                        </form>                            
                    </div>
                          
                    <div class="box-body">
                        
                            <div class="box-body table-responsive no-padding ">
                                    <table id="log_details" class="table table-responsive table-bordered table-hover table-striped">
                                            <thead>
                                                    <tr>
                                                            <th>User</th>
                                                            <th>Category</th>
                                                            <th>Activity</th>
                                                            <th>Sequence Number</th>
                                                            <th>IP Address</th>
                                                            <th>Log Date</th>
                                                    </tr>
                                            </thead>                                         
                                    </table>
                            </div>
                    </div>
            </div>
                  </div>
              </div>
             @endif
	 </section>
@push('script') 

@endpush
<script>
    var siteUrl = '<?php echo url('/'); ?>';
    var cat_id = '<?php echo $cat_id; ?>';

</script>
 <script>
       $(document).ready(function () { 
      $('#from_date').datepicker({
      autoclose: true,
          dateFormat: 'dd/mm/yy',
          orientation: "bottom auto"
    });
    $('#to_date').datepicker({
      autoclose: true,
          dateFormat: 'dd/mm/yy',
          orientation: "bottom auto"
    });
    
    $('#reset').click(function(){
        $('#from_date').val('');
        $('#to_date').val('');
        loadDatatable();
    });
    
    $('#log_report_search').click(function(){
        if($('#from_date').val() && $('#to_date').val()){
            loadDatatable();
        }else{
            alert("Please select both From Date and To Date");
        }
    });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        if(cat_id == 6){
            loadDatatable();
        }
    });
    
    function loadDatatable() { 
        var from_date= $('#from_date').val();
        var to_date= $('#to_date').val();
        $(function () {
            dtTable = $('#log_details').DataTable({
                "processing": true,
                "language": {
                        "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                "serverSide": true,
                "destroy": true,
                "ajax":{
                     "url": siteUrl + "/admin/getLogData",
                     "dataType": "json",
                     "type": "POST",
                     'data': {
                              from_date: from_date,
                              to_date:to_date
                             },
                   },
                "columns": [
                    {"data": "name", name: 'users.name'},
                    {"data": "category"},
                    {"data": "action"},
                    {"data": "sequence_no"},
                    {"data": "ipaddress"},
                    {"data": "created_at"}
                ]
            });
        });
    }
    </script>
@endsection