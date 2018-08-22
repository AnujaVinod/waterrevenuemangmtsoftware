@extends('layouts.admin_master')
@section('content')
<style>
    table, th, td {
   border: 1px solid black;
}
    
</style>
<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Test Payment Import
      </h1>   
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
		<div class="box box-info">
			<div class="box-header with-border">
				<h3 class="box-title">Test Payment Import</h3>
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
					<button type="button" class="btn btn-warning margin btn-margin"   data-target="#delete_bill"onclick="SearchDeleteBill()">Search</button>	
				</div>
				</form>
			</div>
                     <div id="empty_box"  style="display:none;">
                         <span class="text-red" id="result_display" style="padding:20px;font-weight: bold;" ></span>
                     </div>

                    	
                    
		</div>
                <div>
                    <div style="padding:10px;">
                        <table id="results"></table>
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
                   $("#error-field").html('');
			
			var dataString = {'sequence_number':sequenceNumber};
			$.ajax({
				type: "POST",
				url: siteUrl + "/admin/getPaymentInfo",
				data: dataString,
				cache: "false",
				success: function (result) {
                                if (result.errors) {
                                alert('No data Found');
                                //if (data.errors.name) {
                                //    $('#name-error').html(data.errors.name[0]);
                                // }            
                            }
                            if (result.success) {
                                $("#results tr:has(td)").remove();
                               console.log(result.records);
                               var $tr = $('<tr>').append(
                                       $('<td>').text('Date of reading'),
                                        $('<td>').text('Total Amount'),
                                        $('<td>').text('Advance Amount'),
                                        $('<td>').text('Extra Amount'),
                                        $('<td>').text('Payment Status'),
                                        $('<td>').text('Active Record')
                                    ).appendTo('#results'); 
                               $.each(result.records, function(i, item) {
                                    var $tr = $('<tr>').append(
                                            $('<td>').text(item.date_of_reading),    
                                        $('<td>').text(item.total_amount),    
                                        $('<td>').text(item.advance_amount),
                                        $('<td>').text(item.extra_amount),
                                        $('<td>').text(item.payment_status),
                                        $('<td>').text(item.active_record)
                                    ).appendTo('#results');                                   
                                });                               
                            }
			}
                    }); 
                 
	}   


</script>
@endsection
