@extends('layouts.admin_master')
@section('content')
<section class="content-header">
  <h1>
  Manage Application Details
  </h1>
</section>
    <?php $role1 = Helper::getRole();
       $sub_category = $role1->sub_category_name;
       $category = $role1->category_name;
     
       ?>   
<!-- Main content -->

    <section class="content container-fluid">
        	<div class="box box-info">
				<div class="box-header with-border">
					<h3 class="box-title"> Application Details / Edit</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
					</div>
				  <!-- /.box-tools -->
				</div>
                    <input type="hidden" id="check_cate" value="<?php echo $category;?>">
                    <input type="hidden" value="{{ Auth::user()->id }}" id="current_user">
				<!-- /.box-header -->
				<div class="box-body table-responsive" id="display_div">
					<table id="application_list_table" class="table table-responsive table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th>Application Number</th>
								<th>Application Date</th>
								<th>Applicant Name</th>
								<th>Khata Number</th>
								<th>Contact NO</th>
								<th>View Certificate</th>
								<th>Attached Documents </th>
								<th>File Note</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						
					</table>
				</div>
			</div>
				<!-- /.box-body -->
		</section>
    <div class="modal modal-default fade" id="modal-Edit-tap">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Edit Tap Application</h4>
				</div>
				<div class="modal-body">
					<div class="box-group" id="accordion">
                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
						<div class="panel box box-default">
							<div class="box-header with-border">
								<h4 class="box-title">
									<a data-toggle="collapse" data-parent="#accordion" href="#ward_AC_info">
										Ward / Account Info
									</a>
								</h4>
							</div>
							<div id="ward_AC_info" class="panel-collapse collapse in">
								<div class="box-body">
									<table class="table table-bordered table-hover form-group">
										<tr>
										<td>Consumer Name</td>
											<td><input type="" class="form-control" id="customer_name" name="customer_name"  required="required" placeholder="Cicilia Lobo" readonly></td>
											<td>Application ID</td>
											<td>
                                                                                            <input type="" class="form-control" id="application_number" name="application_number"   readonly></td>
											
										</tr>
										<tr>
											<td width="20%">Ward</td>
											
                                                                                            <td><input type="hidden" class="form-control" id="ward_id" name="ward_id">
                                                                                            <input type="" class="form-control" id="ward_name" name="ward_name" readonly></td>
                                                                                         
											<td>Corp Ward</td>
										 
                                                                                        <td><input type="hidden" class="form-control" id="corp_ward_id" name="corp_ward_id">
                                                                                         <input type="" class="form-control" id="corp_ward_name" name="corp_ward_name" readonly>
                                                                                        </td>
                                                                                
										</tr>
																				<tr>
											<td width="20%">Premises Owner Name </td>
											<td width="30%"><input type="text" class="form-control" id="premises_owner_name" name="premises_owner_name" readonly></td>
                                                                                        <td width="20%">Phone Number </td>
                                                                                    <td width="30%">	
                                                                                        <input type="" class="form-control" id="phone_number" name="phone_number"  readonly>
                                                                                         <span class="text-danger">
                                                                                <strong id="mobile_no-error_tap"></strong>
                                                                            </span>
							</td>
											
	
										</tr>
										<tr>
											<td width="20%">Khata Number</td>
											<td width="30%">
												<input type="" class="form-control" id="khata_no" name="khata_no" readonly>
											</td>
                                                                                        <td width="20%">
												Address
											</td>
											<td width="30%">    <textarea id="premises_address" name="premises_address" class="form-control" rows="3" placeholder="Enter ..." readonly></textarea><span class="text-danger">
                                                                                        <strong id="address-error_tap"></strong>
                                                                                    </span></td>

										</tr>
									
										<tr>
											<td width="20%">Street Name</td>
                                                                                        <td width="30%"><input type="text" class="form-control" id="premises_street" name="premises_street" placeholder="Street Name" readonly></td>											<td width="20%">
												City
											</td>
											<td width="30%"><input type="text" class="form-control" id="premises_city" name="premises_city" required="required" readonly></td>
										</tr>
										<tr>
											<td width="20%">State</td>
											<td width="30%">
												<select class="form-control" name="premises_state" id="premises_state" style="width: 100%;" disabled="disabled">
													<option selected="selected">Karnataka</option>
													<option>Andaman and Nicobar Islands</option>
													<option>Andhra Pradesh</option>
													<option>Arunachal Pradesh</option>
													<option>Assam</option>
													<option>Bihar</option>
													<option>Chandigarh</option>
													<option>Chhattisgarh</option>
													<option>Dadra and Nagar Haveli</option>
													<option>Daman and Diu</option>
													<option>Delhi</option>
													<option>Goa</option>
													<option>Gujarat</option>
													<option>Haryana</option>
													<option>Himachal Pradesh</option>
													<option>Jammu and Kashmir</option>
													<option>Jharkhand</option>
													<option>Karnataka</option>
													<option>Kerala</option>
													<option>Lakshadweep</option>
													<option>Madhya Pradesh</option>
													<option>Maharashtra</option>
													<option>Manipur</option>
													<option>Meghalaya</option>
													<option>Mizoram</option>
													<option>Nagaland</option>
													<option>Odisha</option>
													<option>Puducherry</option>
													<option>Punjab</option>
													<option>Rajasthan</option>
													<option>Sikkim</option>
													<option>Tamil Nadu</option>
													<option>Tripura</option>
													<option>Uttar Pradesh</option>
													<option>Uttarakhand</option>
													<option>West Bengal</option>
												</select>
											</td>
											<td width="20%">
												Zip
											</td>
											<td width="30%"><input type="text" class="form-control" id="premises_zip" name="premises_zip" readonly></td>
										</tr>
									</table>
									<table class="table table-bordered table-hover ">
										<thead>
											<tr>
												<th colspan="4">List of Document To Be Uploaded</th>
											</tr>
										</thead>
										<tr>
											<td width="20%">Blueprint of the premises where connection is required</td>
											<td width="30%"><input type="file" name="pic" id="edit_file"></td>
											<td width="20%">Comments</td>
											<td width="30%"><textarea id="con_remarks" class="form-control" rows="3" readonly></textarea></td>
										</tr>
									</table>
								</div>
							</div>
						</div>						
						
						 <div class="panel box box-default">
							<div class="box-header with-border">
								<h4 class="box-title">
									<a data-toggle="collapse" data-parent="#accordion" id="ledger_tab" href="#Ledger_info">
										Update Ledger Information
									</a>
								</h4>
							</div>
							<div id="Ledger_info" class="panel-collapse collapse">
								<div class="box-body">
									<table class="table table-bordered table-hover form-group">										
										<tr>
											<td width="10%">
												Connection Type
											</td>
											<td width="15%">
                                                                                            <input type="hidden" class="form-control" id="application_id" name="application_id" >
                                                                                            <input type="hidden" class="form-control" id="connection_type_id" name="connection_type_id">
                                                                                            <input type="hidden" class="form-control" id="application_status_id" name="application_status_id">

												<input type="text" class="form-control" id="connection_name" name="connection_name" readonly >
											</td>
											
                                                                                        <td width="10%">
												Inspector Name
											</td>
											<td width="25%">
                                                                                        <select class="form-control inspectorname" id="inspector_id" name="inspector_id" required="required">
                                                                                            <option value="">select</option>

                                                                                        </select>
                                                                                               <span class="text-danger">
                                                                                                    <strong id="inspector_id-error"></strong>
                                                                                                </span>
											</td>
											
										</tr>
										<tr>
                                                                                    <td width="10%">
												Agent Name 
											</td>
                                                                                    <td width="15%">
												<select class="form-control select2 agent_code_id" id="agent_code_id" name="agent_code_id" style="width: 100%;">
													
												</select>
                                                                                           <span class="text-danger">
                                                                                                <strong id="agentcode-error"></strong>
                                                                                            </span>
											</td>
											
											<td width="15%">
												NO of Flats
											</td>
											<td width="25%">
												<input type="text" class="form-control" id="no_of_flats" name="no_of_flats" required="required" placeholder="">
                                                                                                   <span class="text-danger">
                                                                                                        <strong id="flat_data_error"></strong>
                                                                                                    </span>
											</td>
										</tr>
	
										<tr>
											<td width="10%">
												Tap Diameter
											</td>
											<td width="15%">
												<input type="text" class="form-control" id="tap_diameter" name="tap_diameter">
											</td>
											<td width="10%">
												Connection Date
											</td>
											<td width="25%">
                                                                                             <div class="input-group date">
                                                                                                <div class="input-group-addon">
                                                                                                        <i class="fa fa-calendar"></i>
                                                                                                </div>
												<input type="text" class="form-control datepicker" id="connection_date" name="connection_date">
                                                                                                   <span class="text-danger">
                                                                                                        <strong id="connection_date_error"></strong>
                                                                                                    </span>
											</td>
                                                                                        
										</tr>
										<tr>
											<td width="15%">
												Application Date
											</td>
											<td width="25%">
												<input type="text" class="form-control" id="application_date" name="application_date" readonly>
											</td>
											<td width="10%">
												Deposit Amount
											</td>
											<td width="15%">
												<input type="text" class="form-control" id="deposit_amount"  name="deposit_amount">
											</td>
										</tr>
										<tr>
											<td width="10%">
												Deposit Date
											</td>
											<td width="15%">
                                                                                            <div class="input-group date">
                                                                                            <div class="input-group-addon">
                                                                                                    <i class="fa fa-calendar"></i>
                                                                                            </div>
												<input type="text" class="form-control datepicker" id="deposit_date" name="deposit_date" >
											</td>
											<td width="10%">
												Application Status
											</td>
											<td width="25%">
                                                                                            <input type="text" class="form-control" data-application="" id="application_status" name="application_status" readonly>
												
											</td>
										</tr>
										<tr>
											<td width="15%">
												Order No
											</td>
											<td width="25%">
												<input type="text" class="form-control" id="order_no" name="order_no"  placeholder="">
											</td>
											<td width="10%">
												Deposit Challan No
											</td>
											<td width="15%">
												<input type="text" class="form-control" id="deposit_challan_no" name="deposit_challan_no">
											</td>
										</tr>
										<tr>
											<td width="15%">
												Connection Charge
											</td>
											<td width="25%">
												<input type="text" class="form-control" id="connection_charge" name="connection_charge"  placeholder="" >
											</td>
											<td width="10%">
												
											</td>
											<td width="15%">
												
											</td>
										</tr>
										<tr>
											<td width="10%">
												Remarks
											</td>
											<td width="90%" colspan="3">
												<textarea class="form-control" rows="3" id="remarks" name="remarks"></textarea>
											</td>
										</tr>
										<tr>
											<td colspan="4">
												<div id="save_ledger_info" style="display: none;">
													<div class="alert alert-warning alert-dismissible">
														Ledger Information Saved
													</div>
												</div>
												<button type="button" data-toggle="collapse" id="ledger_save_btn" aria-expanded="true" href=""  class="btn btn-danger">Save</button>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>

						<div class="panel box box-danger">
						  <div class="box-header with-border">
							<h4 class="box-title">
							  <a data-toggle="collapse" data-parent="#accordion" id="meter_tab" href="#meter_number">
									Meter Number Details
							  </a>
							</h4>
						  </div>
						  <div id="meter_number" class="panel-collapse collapse">
								<div class="box-body">
									<table class="table table-bordered table-hover ">
										<thead>
											<tr>
												<th colspan="4">Meter Number Details</th>
											</tr>
										</thead>
										<tr>
											<td width="20%">Meter Number</td>
											<td width="30%"><input type="text" class="form-control" id="meter_no" name="meter_no" required="required" placeholder="">
                                                                                          <span class="text-danger">
                                                                                            <strong id="meter-error"></strong>
                                                                                        </span>
                                                                                        </td>
											<td width="20%">Sanctioned Date</td>
											<td width="30%"><div class="input-group date">
                                                                    <div class="input-group-addon">
                                                                            <i class="fa fa-calendar"></i>
                                                                    </div><input type="text" class="form-control datepicker" id="meter_sanctioned_date" name="meter_sanctioned_date" required="required" placeholder="">
                                                                      <span class="text-danger">
                                                                       <strong id="meter_date-error"></strong>
                                                                      </span>
                                                                    </td>
										</tr>
									</table>
									<br>
                                                                        	<div id="save_meter_info" style="display: none;">
													<div class="alert alert-warning alert-dismissible">
														Meter Information Saved
													</div>
												</div>
									<button type="button" id="meter_no_save" data-toggle="collapse" aria-expanded="true" href="" class="btn btn-danger">Save</button>
								</div>
							</div>
						</div>
						
						<div class="panel box box-danger">
						  <div class="box-header with-border">
							<h4 class="box-title">
							  <a data-toggle="collapse" data-parent="#accordion" id="approval_tab" href="#connection_approval">
									Connection Approval
							  </a>
							</h4>
						  </div>
						  <div id="connection_approval" class="panel-collapse collapse">
								<div class="box-body">
									<table class="table table-bordered table-hover ">
										<thead>
											<tr>
												<th colspan="4">Connection Approval Details</th>
											</tr>
										</thead>
										<tr>
											<td width="20%">Connection Approved By</td>
											<td width="30%">
                                                                                            <div id="approve_list_div">
                                                                                                <select class="form-control select3" id="approved_by" name="approved_by" style="width: 100%;">
													
													
												</select>
                                                                                                
                                                                                            </div>
												
											</td>
                                                                                         <span class="text-danger">
                                                                                            <strong id="approved_by-error"></strong>
                                                                                        </span>
											<td width="50%" colspan="2"></td>
										</tr>
									</table>
									<br>
                                                                        <div id="approval_info" style="display: none;">
													<div class="alert alert-warning alert-dismissible">
														Approval Information Saved
													</div>
												</div>
									<button type="button" id="save_approval_info" data-toggle="collapse"  aria-expanded="true" href="" class="btn btn-danger">Save</button>
								</div>
							</div>
						</div>
						
					</div>
			
					<div class="pull-right" id="approval_div">
                                             <span class="text-danger">
                                                <strong id="missing-error"></strong>
                                                
                                            </span>
                                               <span style="color:green;"><strong id="messagebox"></strong></span>
                        <button id="approve_app" class="btn btn-success">Approve</button>
						<a href="" id="reject_app" class="btn btn-danger">Reject</a>
						<a href="" id="on_hold_app" class="btn btn-warning">On Hold</a>
					</div>	
                     
					<div class="clear"></div>					
				</div>
			</div>
            <!-- /.modal-content -->
        </div>
    </div>

	<div class="modal fade" id="attach-doc">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Attached Documents</h4>
              </div>
              <div class="modal-body">
				<img class="certiimage" height="500" width="550">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
              </div>
            </div>
            <!-- /.modal-content -->
        </div>
          <!-- /.modal-dialog -->
    </div>
	<div class="modal fade" id="attach-certificate">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Attached Documents</h4>
              </div>
              <div class="modal-body">
				<img id="certiimage" height="500" width="550">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
              </div>
            </div>
            <!-- /.modal-content -->
        </div>
          <!-- /.modal-dialog -->
    </div>
	<div class="modal fade" id="view-comment">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Comments</h4>
              </div>
              <div class="modal-body">
                  <span id="remarks_data"></span>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
              </div>
            </div>
            <!-- /.modal-content -->
        </div>
          <!-- /.modal-dialog -->
    </div>
   <form id="file-form" action="{{ URL::to('admin/appdownloadFile') }}" method="POST" style="display: none;">
    <input type="hidden" id="filename" name="filename">
    <input type="hidden" name="fileType" value="appdoc">
    {{ csrf_field() }}
</form> 
<script>var siteUrl = '<?php echo url('/'); ?>';</script>
<script>
    $(document).ready(function () {
        loadDatatable();
       
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
  $('.datepicker').datepicker({
      autoclose: true,
          dateFormat: 'dd/mm/yy',
          orientation: "bottom auto"
    });
var check_cate=$('#check_cate').val();

  if(check_cate=='EXECUTIVE')
  {
   $("#ward_AC_info :input").attr("disabled", true);  
   $("#Ledger_info :input").attr("disabled", true); 
   $("#meter_number :input").attr("disabled", true);  
   $("#connection_approval :input").attr("disabled", true); 
  }
  
    /*
          $('body').on('click', '#tap_save_button', function () {
              
              clearErrorFields();
            var get_type=  $("#applictaion_types li.active").attr('id');
            if(get_type=="tap")
            {
              var formData = new FormData($("#add_tap_applictaion")[0]);              
              $.ajax({
                    url: siteUrl + "/admin/saveNewApplication",
                    type: 'POST',
                    data: formData,
                     enctype: 'multipart/form-data',
                    processData: false,  // Important!
                    contentType: false,
                    cache: false,

                    success: function (data) {
                             if (data.errors) {
                            if (data.errors.customer_name) {
                                $('#customer_name_error_tap').html(data.errors.customer_name[0]);
                            }
                            if (data.errors.phone_number) {
                                $('#mobile_no-error_tap').html(data.errors.phone_number[0]);
                            }
                            if (data.errors.door_no) {
                                $('#door_no-error_tap').html(data.errors.door_no[0]);
                            }
                            if (data.errors.ward_id) {
                                $('#ward-error_tap').html(data.errors.ward_id[0]);
                            }
                            if (data.errors.premises_owner_name) {
                                $('#owner-error_tap').html(data.errors.premises_owner_name[0]);
                            }
                            if (data.errors.premises_address) {
                                $('#address-error_tap').html(data.errors.premises_address[0]);
                            }
                            if (data.errors.connection_type_id) {
                                $('#connection_type-error_tap').html(data.errors.connection_type_id[0]);
                            } 
                            if (data.errors.application_number) {
                                $('#application_no-error_tap').html(data.errors.application_number[0]);
                            }

                        }
                        if (data.success) {
                            $('#save_ledger_info').show();
                            $('#success-msg1').removeClass('hide');
                            setInterval(function () {
                                $('#success-msg1').addClass('hide');

                            }, 3000); 
                             $("#addconnection")[0].reset();
                        }
                    },
                });
            }
            
    }); */
     $('body').on('click', '.cretificate_popup', function () {
         clearErrorFields();
         var application_id = $(this).data("application");
         var cert_name = $(this).data("cert_name");
         $("#certiimage").attr("src", cert_name);

      });
    $('body').on('click', '.remarks_popup', function () {
         
         clearErrorFields();
         var application_id = $(this).data("application");
         var remarks = $(this).data("remarks");
         $("#remarks_data").html(remarks);

      });
     
    $('body').on('click', '.edit_pop_up', function () {
		clearApplicationInfo();
                clearErrorFields();
                hidemessage();
             
                 $('#missing-error').html('');
         var application_id = $(this).data("application");
          $.ajax({
                    url: siteUrl + "/admin/getApplicationInfo",
                    type: 'POST',
                    data: {application_id : application_id},
                    async: true,
                    success: function (data) {
                        
                         if (data.errors) {
                           
        
                        }
                        if (data.success) {
				
                                var current_user=$('#current_user').val();
                                var app_status=data.response[0].application_status_id;
                                $('#application_id').val(data.response[0].id);
                                $('#application_number').val(data.response[0].application_number);
                                $('#customer_name').val(data.response[0].customer_name);
                                $('#ward_id').val(data.response[0].ward_id);
                                $('#corp_ward_id').val(data.response[0].corp_ward_id);
                                $('#ward_name').val(data.response[0].ward_name);
                                $('#corp_ward_name').val(data.response[0].corp_name);
                                $('#connection_type_id').val(data.response[0].connection_type_id);
                                $('#application_status_id').val(data.response[0].application_status_id); 
                                $('#khata_no').val(data.response[0].khata_no);
                                $('#premises_address').val(data.response[0].premises_address);
                                $('#premises_street').val(data.response[0].premises_street);
                                $('#premises_city').val(data.response[0].premises_city);
                                $('#premises_state').val(data.response[0].premises_state);
                                $('#premises_zip').val(data.response[0].premises_zip);
                                $('#premises_owner_name').val(data.response[0].premises_owner_name);
                                $('#phone_number').val(data.response[0].phone_number);   
                                $('#con_remarks').val(data.response[0].remarks);
                                $('#connection_name').val(data.response[0].connection_name);
                                $('#application_date').val(data.response[0].application_date);
                                $('#application_status').val(data.response[0].status); 
                                $("#edit_file").prop("disabled", true); 
                                //$('#application_status').data('application','20'); //setter
                             
                                if(data.response[0].approved_by ==current_user && app_status!=1)
                                {
                                     $("#approve_app").attr("disabled", false); 
                                     $("#reject_app").attr("disabled", false); 
                                     $("#on_hold_app").attr("disabled", false); 
    
                                }
                                else
                                {
                                     $("#approve_app").attr("disabled", true); 
                                     $("#reject_app").attr("disabled", true); 
                                     $("#on_hold_app").attr("disabled", true); 
                                }
                               if(data.response[0].approved_by ==current_user && app_status==1)
                                {
                                     $("#approve_app").attr("disabled", true); 
                                     $("#reject_app").attr("disabled", true); 
                                     $("#on_hold_app").attr("disabled", true);      
                                     $('#messagebox').html('Connection Already Approved');
                                    
                                }

                            }
                            if(data.userDesignation)
                            {
                                var i = 0;
                             $('.select3').html('');
                             $('.select3').append($("<option selected='selected'>Select</option>"));
                             $(data.userDesignation).each(function(){
                             
                                 $('.select3').append($("<option></option>")
                                .attr("value",data.userDesignation[i].id)
                                .text(data.userDesignation[i].name));                         
                                i++;
                            });
                        }
                        
                    },
                });
        

      });

        $('body').on('click', '#ledger_save_btn', function () {
            
            clearErrorFields();
              var application_id=$('#application_id').val();
              var ward_id=$('#ward_id').val();
              var corp_ward_id=$('#corp_ward_id').val();
              var connection_type_id= $('#connection_type_id').val();
              var application_status_id= $('#application_status_id').val();
              var inspector_id=$('select[name=inspector_id]').val();
              var agentcode=$('select[name=agent_code_id]').val();
              var no_of_flats= $('#no_of_flats').val();
              var tap_diameter= $('#tap_diameter').val();
              var connection_date=$('#connection_date').val();
              var deposit_amount=$('#deposit_amount').val();
              var deposit_date=$('#deposit_date').val();
              var order_no=$('#order_no').val();
              var deposit_challan_no=$('#deposit_challan_no').val();
              var remarks=$('#remarks').val();
			  var connection_charge = $('#connection_charge').val();
              
             $.ajax({
                    url: siteUrl + "/admin/save_ledger_details",
                    type: 'POST',
                     'data': {
                                application_id: application_id,
                                ward_id: ward_id,
                                corp_ward_id: corp_ward_id,
                                connection_type_id: connection_type_id,
                                application_status_id:application_status_id,
                                inspector_id: inspector_id,
                                agent_code_id: agentcode,
                                no_of_flats: no_of_flats,
                                tap_diameter: tap_diameter,
                                connection_date: connection_date,
                                deposit_amount: deposit_amount,
                                deposit_date: deposit_date,
                                order_no: order_no,
                                deposit_challan_no: deposit_challan_no,
                                remarks: remarks,
				connection_charge: connection_charge
                                  },
                    async: true,

                    success: function (data) {
                        
                            if (data.errors) {
                            if (data.errors.connection_date) {
                                $('#connection_date_error').html(data.errors.connection_date[0]);
                            }
                           if (data.errors.no_of_flats) {
                                $('#flat_data_error').html(data.errors.no_of_flats[0]);
                            }
                            if (data.errors.agentcode) {
                                $('#agentcode-error').html(data.errors.agentcode[0]);
                            }
                            if (data.errors.inspector_id) {
                                $('#inspector_id-error').html(data.errors.inspector_id[0]);
                            }
                          
                        }
                        if (data.success) {
                            $('#save_ledger_info').show();
                            $('#success-msg1').removeClass('hide');
                            setInterval(function () {
                                $('#success-msg1').addClass('hide');

                            }, 3000); 
                             $("#addconnection")[0].reset();
                        }
                      
                  }
           });
              
            
        });
        $("#ledger_tab").on('click', function() {
                 clearErrorFields();
                 hidemessage();
                  var wardid= $('#ward_id').val();
                    
                $.ajax({
                    url: siteUrl + "/admin/getInspectorList",
                    type: 'POST',
                    data: {'wardid': wardid},
                    async: true,
                    success: function (data) {      
                        if (data.success == '1') {

                            var i = 0;
                            $('.inspectorname').html('');                
                            $(data.inspectorlist).each(function(){
                                $('.inspectorname')
                                .append($("<option>Select</option>")
                                .attr("value",data.inspectorlist[i].id)
                                .text(data.inspectorlist[i].inspector_name));                         
                                i++;
                            });

                        }
                        if (data.success == '0') {
                            var j = 0;
                            $('.inspectorname').html('');
                            $('.inspectorname')
                                .append($("<option></option>")
                                .attr("value",'')
                                .text("No inspector available. Kindly add inspector for this ward"));  
                        }
                    },
                });
                  var corp_ward_id= $('#corp_ward_id').val();
                    $.ajax({
                        url: siteUrl + "/admin/getInspectorAgent",
                        type: 'POST',
                        data: {'corp_ward_id': corp_ward_id},
                        async: false,
                        // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success: function (data) {                
                            if (data.success == '1') {

                                var i = 0;
                                    $('.agent_code_id').html('');                
                                $(data.agentlist).each(function(){
                                    $('.agent_code_id')
                                    .append($("<option></option>")
                                    .attr("value",data.agentlist[i].agent_user_id)
                                    .text(data.agentlist[i].agent_name));                         
                                    i++;
                                });

                            }
                            if (data.success == '0') {
                                var j = 0;
                                $('.agent_code_id').html('');
                                $('.agent_code_id')
                                    .append($("<option></option>")
                                    .attr("value",'')
                                    .text("No agents available"));  
                            }
                        },
                    }); 
                    var application_id=$('#application_id').val();
                    $.ajax({
                    url: siteUrl + "/admin/check_ledger_details",
                    type: 'POST',
                    data: { 'application_id':application_id,
                          },
                    async: true,
                    success: function (data) { 
                                if (data.success) {
                                        $('#no_of_flats').val(data.response[0].no_of_flats);
                                        $('#tap_diameter').val(data.response[0].tap_diameter);
                                        $('#connection_date').val(data.response[0].connection_date);
                                        $('#deposit_amount').val(data.response[0].deposit_amount);
                                        $('#deposit_date').val(data.response[0].deposit_date);
                                        $('#order_no').val(data.response[0].order_no);
					$('#connection_charge').val(data.response[0].connection_charge);
                                        $('#deposit_challan_no').val(data.response[0].deposit_challan_no);
                                        $('#remarks').val(data.response[0].remarks);
                                        $('#ledger_save_btn').prop('disabled', true);
                                        $("#no_of_flats").prop("readonly", true);
                                        $("#inspector_id").prop("disabled", true);
                                        $("#agent_code_id").prop("disabled", true); 
                                        $("#tap_diameter").prop("readonly", true);
                                        $("#connection_date").prop("readonly", true);
                                        $("#deposit_date").prop("readonly", true);
                                        $("#deposit_amount").prop("readonly", true);
                                        $("#order_no").prop("readonly", true);
                                        $("#deposit_challan_no").prop("readonly", true);
                                        $("#remarks").prop("readonly", true);
					$("#connection_charge").prop("readonly", true);
                                       }
                                }
                });
                    
                    
            });
             $("#meter_tab").on('click', function() {
                    clearErrorFields();
                    hidemessage();
                   var application_id=$('#application_id').val();
                    $.ajax({
                    url: siteUrl + "/admin/check_meter_details",
                    type: 'POST',
                    data: { 'application_id':application_id,
                          },
                    async: true,
                    success: function (data) {										
                                        $('#meter_no').val(data.response[0].meter_no);
                                        $('#meter_sanctioned_date').val(data.response[0].meter_sanctioned_date);
                                        $('#meter_no_save').prop('disabled', true);
                                        $("#meter_no").prop("readonly", true);
                                        $("#meter_sanctioned_date").prop("readonly", true);
                                       
                                      
                                }
                });
                 
             });
        $("#approval_tab").on('click', function() {
                  clearErrorFields();
                  hidemessage();
                   var application_id=$('#application_id').val();
                    $.ajax({
                    url: siteUrl + "/admin/check_approval_details",
                    type: 'POST',
                    data: { 'application_id':application_id,
                          },
                    async: true,
                    success: function (data) { 
                                if (data.success) {
                                    
                                        $('#approve_list_div').html('');
                                        $('#approve_list_div').append(
                                                                $('<input id="approved_by" class="form-control" >', {
                                                                    type: 'text'
                                                                }));
                                      
                                        $('#approved_by').val(data.response[0].approved_by); 
                                        $('#save_approval_info').prop('disabled', true);
                                        $("#approved_by").prop("readonly", true);
                                       
                                       }
                                }
                });
                 
             });
             $("#meter_no_save").on('click', function() {
                 
                 clearErrorFields();
                  var application_id=$('#application_id').val();
                  var meter_sanctioned_date=$('#meter_sanctioned_date').val();
                  var meter_no=$('#meter_no').val();
                    
                 $.ajax({
                    url: siteUrl + "/admin/save_meter_details",
                    type: 'POST',
                    data: { 'application_id':application_id,
                            'meter_sanctioned_date': meter_sanctioned_date,
                           'meter_no':meter_no},
                    async: true,
                    success: function (data) { 
                           if (data.errors) {
                            if (data.errors.meter_sanctioned_date) {
                                $('#meter_date-error').html(data.errors.meter_sanctioned_date[0]);
                            }
                            if (data.errors.meter_no) {
                                $('#meter-error').html(data.errors.meter_no[0]);
                            }
                           
                        }
                        if (data.success) {
                            $('#save_meter_info').show();
                            $('#success-msg1').removeClass('hide');
                            setInterval(function () {
                                $('#success-msg1').addClass('hide');

                            }, 3000); 
                             $("#addconnection")[0].reset();
                        }
                      
                    },
                });
                  
                    
            });
          $('#save_approval_info').on('click', function() {

                    clearErrorFields();
                    
                  var application_id=$('#application_id').val();
                  var approved_by=$('select[name=approved_by]').val();
                 
                    
                $.ajax({
                    url: siteUrl + "/admin/save_approval_details",
                    type: 'POST',
                    data: { 'application_id':application_id,
                            'approved_by': approved_by
                           },
                    async: true,
                    success: function (data) { 
                           if (data.errors) {
                            if (data.errors.approved_by) {
                                $('#approved_by-error').html(data.errors.approved_by[0]);
                            }

                        }
                        if (data.success) {
                            $('#approval_info').show();
                            $('#success-msg1').removeClass('hide');
                            setInterval(function () {
                                $('#success-msg1').addClass('hide');

                            }, 3000); 
                             $("#addconnection")[0].reset();
                        }
                      
                    },
                });
                  
                    
            });
            
            $('#approve_app').on('click', function() {
                
                clearErrorFields();
                hidemessage();
                  var application_id=$('#application_id').val();
                $.ajax({
                    url: siteUrl + "/admin/approve_appalication",
                    type: 'POST',
                    data: { 'application_id':application_id
                           },
                    async: true,
                    success: function (data) { 
                          
                           if (data.success == '1') 
                           {
                            $.ajax({
                                    url: siteUrl + "/admin/sequence_number_show",
                                    type: 'POST',
                                    data: { 'application_id':application_id
                                           },
                                    async: true,
                                    success: function (data) { 
                                            
                                                $('#modal-Edit-tap').modal('toggle');
                                                $('#display_div').html('');
                                                 $('#display_div').html(data);
                                           
                                        }  
                            });
                           }
                           else if(data.success == '2')
                           {
                             $('#missing-error').html('Meter Details Details Missing'); 
                           }
                           else if(data.success == '3')
                           {
                               $('#missing-error').html('Approval Details Missing'); 
                           }
                           else if(data.success == '4')
                           {
                                $('#missing-error').html('Ledger Details Missing'); 
                           }
                        }

                });     
            });
           $('#reject_app').on('click', function() {

                clearErrorFields();
                hidemessage();
               var application_id=$('#application_id').val();
                $.ajax({
                    url: siteUrl + "/admin/reject_appalication",
                    type: 'POST',
                    data: { 'application_id':application_id
                           },
                    async: true,
                    success: function (data) { 
                          
                           if (data.success == '1') 
                           {
                               loadDatatable();
                           }
                        }

                });
               
            });
            $('#on_hold_app').on('click', function() {

               clearErrorFields();
               hidemessage();
               var application_id=$('#application_id').val();
                $.ajax({
                    url: siteUrl + "/admin/hold_appalication",
                    type: 'POST',
                    data: { 'application_id':application_id
                           },
                    async: true,
                    success: function (data) { 
                          
                           if (data.success == '1') 
                           {
                               loadDatatable();
                           }
                        }

                });

            });
     
  });
   function loadDatatable() {
                

    dtTable = $('#application_list_table').DataTable({
       "processing": true,
       "serverSide": true,
       "ajax": siteUrl + "/admin/getapplicationlist",
       "columns":[         
           { "data": "application_number" },
           { "data": "application_date"},
           { "data": "customer_name" },
           { "data": "khata_no"},
           { "data": "phone_number"},
           { "data": "certificate_name"},
           { "data": "document_name"},
           { "data": "remarks"},
           { "data": "status"},
           { data: 'edit_option', name: 'edit_option', orderable: false, searchable: false},
       ]
    });
}

function printFile(identifier){
   // alert("data-id:"+$(identifier).data('value'));
    $('#filename').val($(identifier).data('value'));
    document.getElementById('file-form').submit()

}

function clearApplicationInfo() {
	$("#ward_AC_info").attr("class", "panel-collapse collapse in"); 
	$("#ward_AC_info").attr("style", ""); 
	$("#ward_AC_info").attr("aria-expanded","true");
	$("#Ledger_info").attr("class", "panel-collapse collapse"); 
	$("#Ledger_info").attr("style", "height: 0px;"); 
	$("#Ledger_info").attr("aria-expanded","false");
	$("#meter_number").attr("class", "panel-collapse collapse"); 
	$("#meter_number").attr("style", "height: 0px;"); 
	$("#meter_number").attr("aria-expanded","false");
	$("#connection_approval").attr("class", "panel-collapse collapse"); 
	$("#connection_approval").attr("style", "height: 0px;"); 
	$("#connection_approval").attr("aria-expanded","false");
	
	$('#customer_name').val('');
	$('#application_number').val('');
	$('#ward_name').val('');
	$('#corp_ward_name').val('');
	$('#premises_owner_name').val('');
	$('#phone_number').val('');
	$('#khata_no').val('');
	$('#premises_address').val('');
	$('#premises_street').val('');
	$('#premises_city').val('');
	$('#premises_state').val('');
	$('#premises_zip').val('');
	
	$('#connection_name').val('');	
	$('#inspector_id').val('');	
	$('#agent_code_id').val('');	
	$('#no_of_flats').val('');	
	$('#tap_diameter').val('');	
	$('#connection_date').val('');	
	$('#application_date').val('');	
	$('#deposit_amount').val('');	
	$('#deposit_date').val('');	
	$('#application_status').val('');	
	$('#order_no').val('');	
	$('#deposit_challan_no').val('');	
	$('#connection_charge').val('');	
	$('#remarks').val('');	
	
	$('#meter_no').val('');	
	$('#meter_sanctioned_date').val('');	
	
	$('#approved_by').val('');
}  
function clearErrorFields()
{
   $('#flat_data_error').html(''); 
   $('#connection_date_error').html('');
   $('#agentcode-error').html('');
   $('#inspector_id-error').html('');
   $('#customer_name_error_tap').html('');
   $('#mobile_no-error_tap').html('');
   $('#door_no-error_tap').html('');
   $('#ward-error_tap').html('');
   $('#owner-error_tap').html('');
   $('#address-error_tap').html('');
   $('#connection_type-error_tap').html('');
   $('#application_no-error_tap').html('');
   $('#success-msg1').html('');
   $('#meter_date-error').html('');
   $('#meter-error').html('')
   $('#missing-error').html('');
  
}
function hidemessage()
{
   $('#approval_info').hide();
   $('#save_meter_info').hide();
   $('#save_ledger_info').hide();
}


</script>
@endsection
