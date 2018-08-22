@extends('layouts.admin_master')
@section('content')
<section class="content-header">
  <h1>
   Consumer Billing Report Details

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
			<div class="box-header with-border">
				<h3 class="box-title">Consumer Billing Report</h3>
				<!-- /.box-tools -->
			</div>
                      <span class="text-danger">
                                                                       <strong id="error-field"></strong>
                                                                      </span>
			<div class="box-body">
                          <div class="col-md-12 form-group">
                          <label class="radio-inline">
                              <input type="radio" name="optradio" id="new_data_check"  value="0" checked>New Data
                            </label>
                           <label class="radio-inline">
                              <input type="radio" name="optradio" id="old_data_check" value="1">Old Data  
                            </label> 
  
                           </div>
                           <input type="hidden" id="page_no" value="">
                      
				<div class="col-md-3 form-group">
                                    <b>Sequence Number</b>
					<input id="sequence_number" name="sequence_number" type="text" class="form-control" id="" required="required" placeholder="Sequence Number">
                                        
				</div>
				<div class="col-md-3 form-group">
					<b>Meter Number</b>
					<input type="text" id="meter_no" name="meter_no" class="form-control" id="" required="required" placeholder="Meter Number">
				</div>				
				
			
                                <div class="col-md-3 form-group">
							<b>Corp Ward</b>
                                                        <select class="form-control select2 wardname" name="corp_ward_id" required="required" style="width: 100%;">
                                                                                  <option value="">Select</option>
                                                                                 @foreach($corpwards as $corpward)
                                                                                  <option value="{{ $corpward->id }}">{{ $corpward->corp_name }}</option>
                                                                                 @endforeach
                                                           </select>
				</div>
              
						
				<div class="col-md-3 form-group">
                                     <b>Connection Type</b>
					 <select class="form-control select2" id="conn_type" name="conn_type" style="width: 100%;">
                                            <option value="">Select</option>
                                          @foreach($connTypes as $connType)
                                            <option value="{{ $connType->id }}">{{ $connType->connection_name }}</option>                                                                         @endforeach
                                    </select>
				</div>
				<div class="col-md-3 form-group">
					<b>From</b>
					<div class="input-group date">
						<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</div>
						<input type="text" class="form-control pull-right" id="datepicker_billing_report_from">
					</div>
				</div>
				<div class="col-md-3 form-group">
					<b>To</b>
					<div class="input-group date">
						<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</div>
						<input type="text" class="form-control pull-right" id="datepicker_billing_report_to">
					</div>
				</div>
				<div class="col-md-12 form-group">
					<div class="pull-right">
						<button type="button" id="view_bill_report" class="btn btn-warning btn-flat btn-margin pull-left" >View Report</button>
						<button type="button" id="reset" class="btn btn-danger btn-flat btn-margin pull-left">Reset</button>
					</div>
				</div>
                           
			</div>
		</div>
		 <div id="consummer_billing_report" style="display:none;">
                    
			<div class="box box-danger">
				<div class="box-header with-border">
					<h3 class="box-title">Consumer Billing Search Result</h3>
                                         <a id="generateexcel" href="" target="_blank" id="printPageLink" onclick="generate_excel_report()">   <button type="button" class="btn btn-danger pull-right" formtarget="_blank" ><i class="fa fa-file-excel-o">Export Excel</i></button></a>
                                         <a id="printPageUrl" href="" target="_blank" id="printPageLink" onclick="print_data()">   <button type="button" class="btn btn-danger pull-right" formtarget="_blank" ><i class="fa fa-print"></i></button></a>
				</div>
                             <div id="loader_img" style="display:none;"> <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> </div>
				<div class="box-body table-responsive" id="datable_div">
                                    
				</div>
			</div>
                 </div>
        <div id="print_bill_report_data" style="display:none;"></div>
		<!-- </div> -->
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
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
     $('#datepicker_billing_report_from').datepicker({
      autoclose: true,
          dateFormat: 'dd/mm/yy',
          orientation: "bottom auto"
    });

        $('#datepicker_billing_report_to').datepicker({
      autoclose: true,
       dateFormat: 'dd/mm/yy',
      orientation: "bottom auto"
    });
     $('body').on('click', '#reset', function () { 
       
       $('#error-field').text('');
      var sequence_number=$("#sequence_number").val('');
      var meter_no=$("#meter_no").val('');
      //var ward=$('select[name=ward_id]').val('');
      var corp_ward_id=$('select[name=corp_ward_id]').val('');
      var con_type= $('select[name=conn_type]').val('');
      var from_date= $('#datepicker_billing_report_from').val('');
      var to_date= $('#datepicker_billing_report_to').val('');
     
    });
    
    $('body').on('click', '#view_bill_report', function () {
     
      var datacheck = $("input[name='optradio']:checked").val();
     $('#datable_div').html('');
      $('#error-field').text('');
      var sequence_number=$("#sequence_number").val();
      var meter_no=$("#meter_no").val();
     // var ward=$('select[name=ward_id]').val();
      var corp_ward=$('select[name=corp_ward_id]').val();
      var con_type= $('select[name=conn_type]').val();
      var from_date= $('#datepicker_billing_report_from').val();
      var to_date= $('#datepicker_billing_report_to').val();
      var search_key = $("#report_search").val();
      var eDate = new Date(to_date);
      var sDate = new Date(from_date);
      if(datacheck==1)
      {
          if(sequence_number==''&& meter_no==''){
              $('#error-field').text('Please Enter sequence number or meter no');
              return false;
          }
      }
      else 
      {
          if(sequence_number==''&& meter_no=='' && corp_ward==''&& con_type=='' && from_date=='' && to_date=='' )
            {
             $('#error-field').text('Please Enter Any Search Criteria');
             return false;
            }
      }
      if(to_date!= '' && from_date!= '' && sDate> eDate)
      {
           $('#error-field').text("Please ensure that the To Date is greater than or equal to the From Date.");
           return false;
       }
          
    
      
          
          $('#consummer_billing_report').show();
          $('#loader_img').show();
          var sequence_number = sequence_number=='' ? 0 : sequence_number;
          var meter_no = meter_no=='' ? 0 : meter_no;
          var corp_ward = corp_ward=='' ? 0 : corp_ward;
          var con_type = con_type=='' ? 0 : con_type;
  
            $.ajax({
                        url:siteUrl + "/admin/billing_report_search",
                        type: 'POST',
                        data: {
                                    seq_number: sequence_number,
                                    meter_no: meter_no,
                                    corp_ward:corp_ward,
                                    con_type:con_type,
                                    from_date:from_date,
                                    to_date:to_date,
                                    length:10,
                                    page:1,
                                    datacheck:datacheck,
                                    search_key:search_key,
                               },
                        async: true,

                        success: function (data) {
                                         $('#loader_img').hide();
                                        $('#datable_div').html(data);
                                               }
                      });

      
        
     });

        
  });
$(document).on('click', '.pagination a', function (e) {
  var page_no=$(this).attr('href').split('page=')[1];
  $('#page_no').val(page_no);
   e.preventDefault();
});
   function print_data()
  {
      var rowcount=$("#listcount").val(); 
      var page_no=$('#page_no').val();
      var start_limit=page_no==1 ? 1 : parseInt(page_no-1)*rowcount;
      var datacheck = $("input[name='optradio']:checked").val();
      var sequence_number=$("#sequence_number").val();
      var meter_no=$("#meter_no").val();
      var corp_ward=$('select[name=corp_ward_id]').val();
      var con_type= $('select[name=conn_type]').val();
      var from_date= $('#datepicker_billing_report_from').val();
      var to_date= $('#datepicker_billing_report_to').val();
      var search_key = $("#report_search").val();
      var sequence_number = sequence_number=='' ? 0 : sequence_number;
      var meter_no = meter_no=='' ? 0 : meter_no;
      var corp_ward = corp_ward=='' ? 0 : corp_ward;
      var con_type = con_type=='' ? 0 : con_type;
             var siteUrl = '<?php echo url('/'); ?>';
             printPageUrl = siteUrl + '/admin/billing_report_print' + '?corp_ward=' + corp_ward+'&con_type='+con_type+'&from_date='+from_date+'&to_date='+to_date+'&sequence_number='+sequence_number+'&meter_no='+meter_no+'&rowcount='+rowcount+'&page_no='+page_no+'&search_key='+search_key+'&start_limit='+start_limit+'&datacheck='+datacheck;
             $("#printPageUrl").attr("href", printPageUrl);

  }


function generate_excel_report()
{
      var sequence_number=$("#sequence_number").val();
      var meter_no=$("#meter_no").val();
      var corp_ward=$('select[name=corp_ward_id]').val();
      var con_type= $('select[name=conn_type]').val();
      var from_date= $('#datepicker_billing_report_from').val();
      var to_date= $('#datepicker_billing_report_to').val();
      var search_key = $("#report_search").val();
      var datacheck = $("input[name='optradio']:checked").val();
      var sequence_number = sequence_number=='' ? 0 : sequence_number;
      var meter_no = meter_no=='' ? 0 : meter_no;
      var corp_ward = corp_ward=='' ? 0 : corp_ward;
      var con_type = con_type=='' ? 0 : con_type;
      var siteUrl = '<?php echo url('/'); ?>';

         printPageUrl = siteUrl + '/admin/generate_excel' + '?corp_ward=' + corp_ward+'&con_type='+con_type+'&from_date='+from_date+'&to_date='+to_date+'&sequence_number='+sequence_number+'&meter_no='+meter_no+'&datacheck='+datacheck+'&search_key='+search_key;
         $("#generateexcel").attr("href", printPageUrl);
}
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
                    console.log(data.corpWard);
                    var i = 0;
                    $('.corpname').html('');
                        $('.corpname')
                        .append($("<option></option>")
                        .attr("value",'')
                        .text("select"));                      
                    $(data.corpWard).each(function(){
                        $('.corpname')
                        .append($("<option></option>")
                        .attr("value",data.corpWard[i].id)
                        .text(data.corpWard[i].corp_name));                         
                        i++;
                    })                  
                }
                if (data.success == '0') {
                    $('.corpname').html('');
                    $('.corpname')
                        .append($("<option></option>")
                        .attr("value",'')
                        //.text("No CorpWard available")
						.text("Select")
						);  
                }
            },
        });
    });
 $('#old_data_check').change(function(){
        
         $("#old_data").toggle();
         $("#old_data").show();
    });
 $('#new_data_check').change(function(){
        
         $("#old_data").toggle();
         $("#old_data").hide();
    });
</script>
@endsection