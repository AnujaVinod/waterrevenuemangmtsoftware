@extends('layouts.admin_master')
@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Delete Bill
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
		<div class="box box-info">
			<div class="box-header with-border">
				<h3 class="box-title">Delete Bill</h3>
					<!-- /.box-tools -->
			</div>
			<div class="box-body">
			<form>
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<span class="text-danger" style="display:block;margin-left:15px;margin-bottom:5px;"><strong id="error-field"></strong></span>
				<div class="col-md-4 col-sm-12">
					<b>Sequence Number</b>
					<input type="text" class="form-control" id="sequence_number"  name="sequence_number" required="required" autofocus>
				</div>
				<div class="col-md-4 col-sm-12">
					<b>Renter Sequence Number</b>
					<input type="text" class="form-control" id="re_sequence_number"  name="re_sequence_number" required="required">
				</div>
                        	<div class="col-md-4 col-sm-12">
					<button type="button" class="btn btn-warning margin btn-margin"   data-target="#delete_bill"onclick="SearchDeleteBill()">Search</button>	
				</div>
				</form>
			</div>
                     <div id="empty_box"  style="display:none;">
                         <span class="text-red" id="result_display" style="padding:20px;font-weight: bold;" ></span>
                     </div>

                    	<div id="delete_bill" class="collapse out" aria-expanded="true" style="display:none;">
			<div class="box box-danger" id="data_box" style="padding:10px;">
			
	
				<h4 class="no-padding">Sequence Number : <span class="text-red" id="span_sequence_number"></span></h4>
				<h4>Meter Number : <span class="text-red" id="span_meter_number"></span></h4>
				<h4>Name : <span class="text-red" id="span_consumer_name"></span></h4>
                                <h4>Connection Type : <span class="text-red" id="connection_type"></span></h4>
                                <h4>Ward : <span class="text-red" id="ward_name"></span></h4>
				<br>

					<button type="button" class="btn btn-danger margin btn-margin"  onclick="DeleteBill()">Delete Bill</button>	
                        </div>
                        </div>
                    
		</div>
		
    </section>
	<script>
	$(document).ready(function () {
		
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		})
	});
		

	function SearchDeleteBill()
	{
		var siteUrl = '<?php echo url('/'); ?>';
		var sequenceNumber = $('#sequence_number').val();
                var reSequenceNumber = $('#re_sequence_number').val();
		if(sequenceNumber == '' && reSequenceNumber=='') {
			$("#error-field").html('Enter Sequence number');
                          $('#delete_bill').hide();
			
		} 
                else if(sequenceNumber.toLowerCase() === reSequenceNumber.toLowerCase())
                {
                   $("#error-field").html('');
			
			var dataString = {'sequence_number':sequenceNumber};
			$.ajax({
				type: "POST",
				url: siteUrl + "/admin/getBasicInfo",
				data: dataString,
				cache: "false",
				success: function (result) {
                                 
					if(result == '1') {
                                            
                                             $('#empty_box').show();
                                             $('#result_display').html('No bill generated with in 1 week')
                                              $('#delete_bill').hide();
                                           
                                             
                                            }
                                            else if(result == '0')
                                            {
                                                 $('#empty_box').show();
                                                $('#result_display').html('Sequence Number Not found')
                                                 $('#delete_bill').hide();
                                           
                                            }
                                else
                                {
                                           $('#span_sequence_number').html(result.sequence_number);
                                           $('#span_meter_number').html(result.meter_no); 
                                           $('#span_consumer_name').html(result.name); 
                                           $('#connection_type').html(result.connection_name);
                                           $('#ward_name').html(result.ward_name); 
                                           $('#empty_box').hide();
                                           $('#delete_bill').show();
                                }
			}
                    }); 
                }
                else {
		        $('#empty_box').hide();
			$("#error-field").html('Sequence number missmatch');
                        $('#delete_bill').hide();
		} 
	}
    
   function DeleteBill()
   {
       
       var siteUrl = '<?php echo url('/'); ?>';
		var sequenceNumber = $('#sequence_number').val();
                var reSequenceNumber = $('#re_sequence_number').val();
		if(sequenceNumber == '' && reSequenceNumber=='') {
			$("#error-field").html('Enter Sequence number');
			
		} 
                else if(sequenceNumber.toLowerCase() === reSequenceNumber.toLowerCase())
                {
                   $("#error-field").html('');
			
			var dataString = {'sequence_number':sequenceNumber};
			$.ajax({
				type: "POST",
				url: siteUrl + "/admin/deleteBillInfo",
				data: dataString,
				cache: "false",
				success: function (result) {
                                 
					if(result == '1') {
                                           
                                              $('#empty_box').show();
                                              $('#sequence_number').val('');
                                              $('#re_sequence_number').val('');
                                              $('#result_display').html('Record Deleted')
                                              $('#delete_bill').hide();
						
				}
                                
			}
                    }); 
                }
                else {
			
			$("#error-field").html('Sequence number missmatch');
		} 
   }

</script>
@endsection
