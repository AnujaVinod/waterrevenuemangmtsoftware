@extends('layouts.admin_master')
@section('content')
<section class="content-header">
  <h1>
   Connectionwise Detail Report

  </h1>
</section>
<style>
    .error_color
    {
         color: red;
    }
   
</style>
<!-- Main content -->
        <section class="content container-fluid">
		<div class="box box-info">
		  <span class="text-danger">
                        <strong id="error-field" style="margin-top:5px;margin-left:25px;"></strong>
                       </span>
			<div class="box-body">
						<div class="col-md-3 form-group">
							  <b>Ward</b></span> 
                                                        <select class="form-control select2" name="ward_select" style="width: 100%;">
                                                                   <option value="">Select</option>
                                                            @foreach($wards as $ward)
                                                            <option value="{{ $ward->id }}">{{ $ward->ward_name }}</option>   
                                                            @endforeach
                                                        </select>
						</div>
						<div class="col-md-4 form-group">
							<b>From Date</b>
							<div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" class="form-control pull-right" id="datepicker_connection_report_from1">
							</div>
						</div>
						<div class="col-md-3 form-group">
							<b>To Date</b>
							<div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" class="form-control pull-right" id="datepicker_connection_report_to1">
							</div>
						</div>	
						<div class="col-md-2">
							<button type="button" id="view_connection_report_ward" class="btn btn-warning btn-flat pull-left btn-margin" aria-expanded="true">View Report</button>
							<button type="button" id="reset" class="btn btn-danger btn-flat pull-left btn-margin">Reset</button>
						</div>
				
			
				
			</div>
		</div>
		<div id="connection_wise_report" class="collapse out" aria-expanded="true">
			<div class="box box-danger">
				<div class="box-header with-border">
					<h3 class="box-title">Connectionwise Search Result</h3>
                                        <a id="printPageUrl" href="" target="_blank" id="printPageLink" onclick="print_data()"> <button type="button" class="btn btn-danger pull-right"  formtarget="_blank" ><i class="fa fa-print"></i></button></a>
				</div>
                            <div id="print_table_div" style="display:none;"></div>
				<div class="box-body table-responsive">
					<table id="connection_wise_reporttable" class="table table-responsive table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th>Connection Type</th>
								<th>Live</th>
								<th>Disconnection</th>
								<th>Total</th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
                                </div>
                        
			</div>
		</div>

    </section>
<script>var siteUrl = '<?php echo url('/'); ?>';</script>
<script>
      function printContent(el){
                            var restorepage = document.body.innerHTML;
                            var printcontent = document.getElementById(el).innerHTML;
                            document.body.innerHTML = printcontent;
                            window.print();
                            document.body.innerHTML = restorepage;
                            location.reload();

                    }

 $(document).ready( function() {
     

      $('#connection_wise_reporttable').dataTable().fnDestroy();
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
     $('#datepicker_connection_report_from1').datepicker({
      autoclose: true,
          dateFormat: 'dd/mm/yy',
          orientation: "bottom auto"
    });

        $('#datepicker_connection_report_to1').datepicker({
      autoclose: true,
       dateFormat: 'dd/mm/yy',
      orientation: "bottom auto"
    });
    
     $('body').on('click', '#reset', function () { 
       
      $('#error-field').text('');
      var ward=$('select[name=ward_select]').val('');
      var from_date= $('#datepicker_connection_report_from1').val('');
      var to_date= $('#datepicker_connection_report_to1').val('');
    });
    
    $('body').on('click', '#view_connection_report_ward', function () {
     

      var ward=$('select[name=ward_select]').val();
      var from_date= $('#datepicker_connection_report_from1').val();
      var to_date= $('#datepicker_connection_report_to1').val();
      var eDate = new Date(to_date);
      var sDate = new Date(from_date);
       $('#error-field').text('');
      if(ward=='' && from_date=='' && to_date=='' )
      {
		$("#connection_wise_report").attr("class", "panel-collapse collapse"); 
		$("#connection_wise_report").attr("style", "height: 0px;"); 
		$("#connection_wise_report").attr("aria-expanded","false");
       $('#error-field').text('Please Enter Any Search Criteria');
	   return false;
      }
      else if(to_date!= '' && from_date!= '' && sDate> eDate)
          {
			$("#connection_wise_report").attr("class", "panel-collapse collapse"); 
		$("#connection_wise_report").attr("style", "height: 0px;"); 
		$("#connection_wise_report").attr("aria-expanded","false");
           $('#error-field').text("Please ensure that the To Date is greater than or equal to the From Date.");
           return false;
          }
          
      else
      {
		$("#connection_wise_report").attr("class", "panel-collapse collapse in"); 
		$("#connection_wise_report").attr("style", ""); 
		$("#connection_wise_report").attr("aria-expanded","true");
      $('#connection_wise_reporttable').dataTable().fnDestroy();
                dtTable = $('#connection_wise_reporttable').DataTable({
                   "processing": true,
                   "language": {
                        "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                    },
                   "serverSide": true,
                   "ajax": {
                            'url': siteUrl + "/admin/connection_report_search",
                            'type': "POST",
                             'data': {
                                      ward: ward,
                                      from_date: from_date,
                                      to_date: to_date
                                  },
                             
                            },
                                           "columns":[         
                                               { "data": "connection_name" },
                                               { "data": "live" },
                                               { "data": "disconnected"},
                                               { "data": "total"}
                                           ]

                       });
                   }
   
     });

  });
  function print_data()
  {
      var ward=$('select[name=ward_select]').val();
      var from_date= $('#datepicker_connection_report_from1').val();
      var to_date= $('#datepicker_connection_report_to1').val();
      var siteUrl = '<?php echo url('/'); ?>';
      printPageUrl = siteUrl + '/admin/connection_report_print' + '?ward=' + ward+'&from_date='+from_date+'&to_date='+to_date;
      $("#printPageUrl").attr("href", printPageUrl);
  }
</script>
@endsection