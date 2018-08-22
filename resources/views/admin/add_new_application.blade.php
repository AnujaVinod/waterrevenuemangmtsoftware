@extends('layouts.admin_master')
@section('content')
<section class="content-header">
  <h1>
  New  Application
  </h1>
</section>

<!-- Main content -->
    <section class="content container-fluid">
                    <div id="success-msg1" class="hide">
                                    <div class="alert alert-info alert-dismissible fade in" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <strong>Record Added Successfully!!</strong> 
                                    </div>
                                </div>
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom well">
            <ul class="nav nav-tabs" style="background:#e5ebf3;" id="applictaion_types">
			<li><a href="" data-toggle="tab"></a></li>
                  <li id="tap" class="active"><a href="#tab_1"  data-toggle="tab"><strong>Tap</strong></a></li>
                  <li id="ugd"><a href="#tab_2" data-toggle="tab"><strong>UGD</strong></a></li>
                  <li id="jalabhagya"><a href="#tab_3" data-toggle="tab"><strong>Jalabhagya</strong></a></li>
                  <li id="seven"><a href="#tab_4" data-toggle="tab"><strong>Domestic 7.25</strong></a></li>
            </ul>
            <div class="tab-content no-padding">
				<div class="tab-pane active" id="tab_1">
		
            <!-- /.box-header -->
        <form name="add_tap_applictaion" id="add_tap_applictaion" method="POST" >
                                     {{ csrf_field() }}
                              <input type="hidden" id="application_type_id" name="application_type_id" value="1">
					<table class="table table-bordered table-hover form-group">
						<tr>
							<td>Customer name <span class="rq">*</span></td>
							<td><input type="" class="form-control" id="customer_name" name="customer_name"  placeholder="Name"> <span class="text-danger">
                                                        <strong id="customer_name_error_tap"></strong>
                                                    </span></td>
							<td>Door No <span class="rq">*</span></td>
							<td><input type="" class="form-control" id="door_no" name="door_no"  placeholder="Door No"> <span class="text-danger">
                                                        <strong id="door_no-error_tap"></strong>
                                                    </span></td>
						</tr>
						<tr>
							<td>Phone Number <span class="rq">*</span></td>
							<td width="30%">	
								<input type="" class="form-control" id="phone_number" name="phone_number"  placeholder="Phone Number">
                                                                 <span class="text-danger">
                                                        <strong id="mobile_no-error_tap"></strong>
                                                    </span>
							</td>
							<td width="20%">Ward <span class="rq">*</span></td>
							<td>
								  <select class="form-control select2 wardname" name="ward_id" value="{{ old('ward_id') }}" name="" style="width: 100%;">
                                                                    <option value="">Select</option>
                                                                   @foreach($wards as $ward)
                                                                    <option value="{{ $ward->id }}">{{ $ward->ward_name }}</option>
                                                                   @endforeach
                                                            </select>
                                                             <span class="text-danger">
                                                        <strong id="ward-error_tap"></strong>
                                                    </span>
							</td>
                                                        	
						</tr>
						<tr>
                                                    <td width="20%">Corp Ward Name <span class="rq">*</span></td>
                                                                <td>
                                                                           <select class="form-control select2 corpwardname" name="corp_ward_id" required="required">
                                                                            <option value="">select</option>

                                                                        </select>
                                                                                <span class="text-danger">
                                                                           <strong id="corp-ward-error_tap"></strong>
                                                                       </span>
                                                                </td>
    
							<td width="20%">Khata Number</td>
							<td width="30%">
								<input type="" class="form-control" id="khata_no" name="khata_no" placeholder="Number">
							</td>
						 
						</tr>
						</tbody>
					</table>
					<table class="table table-bordered table-hover ">
						<thead>
							<tr>
								<th colspan="4">Location of Premises / place where connection required </th>
							</tr>
						</thead>
						<tr>
							<td width="20%">Name of the Premises Owner <span class="rq">*</span></td>
							<td width="30%"><input type="text" class="form-control" id="premises_owner_name" name="premises_owner_name" placeholder="Name">
                                                           <span class="text-danger">
                                                        <strong id="owner-error_tap"></strong>
                                                    </span></td>
							<td width="20%">
								Address <span class="rq">*</span>
							</td>
							<td width="30%">    <textarea id="premises_address" name="premises_address" class="form-control" rows="3" placeholder="Enter ..."></textarea><span class="text-danger">
                                                        <strong id="address-error_tap"></strong>
                                                    </span></td>
						</tr>
						<tr>
							<td width="20%">Street Name</td>
							<td width="30%"><input type="text" class="form-control" id="premises_street" name="premises_street" placeholder="Street Name"></td>
							<td width="20%">
								City
							</td>
							<td width="30%"><input type="text" class="form-control" id="premises_city" name="premises_city" placeholder="City"></td>
						</tr>
						<tr>
							<td width="20%">State</td>
							<td width="30%">
								<select class="form-control select2" name="premises_state" id="premises_state" style="width: 100%;">
									<option>Select</option>
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
									<option  selected="selected">Karnataka</option>
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
							<td width="30%"><input type="text" class="form-control" id="premises_zip" name="premises_zip" placeholder="Zip"></td>
						</tr>
					</table>
			
					<table class="table table-bordered table-hover ">
						<thead>
							<tr>
								<th colspan="4">Other Details</th>
							</tr>
						</thead>
						<tr>
							<td width="20%">Service applied for </td>
							<td width="30%">
								<select class="form-control select2" id="service_id" name="service_id" style="width: 100%;">
									<option selected="selected">Select</option>
									<option>New Connection</option>
									<option>Service Applied for 2</option>
									<option>Service Applied for 3</option>
									<option>Service Applied for 4</option>
								</select>
							</td>
							<td width="20%">
								Connection Type <span class="rq">*</span>
							</td>
							<td width="30%">
								        <select class="form-control select2" id="connection_type_id" name="connection_type_id" value="{{ old('connection_type') }}"  name="" style="width: 100%;">
                                                                    <option value="">Select</option>
                                                                    @foreach($connTypes as $connType)
                                                                        <option value="{{ $connType->id }}" >{{ $connType->connection_name }}</option>
                                                                    @endforeach
                                                            </select>
                                                             <span class="text-danger">
                                                        <strong id="connection_type-error_tap"></strong>
                                                    </span>
							</td>
						</tr>
						<tr>
							<td width="20%">Plumber Name</td>
							<td width="30%">
								        <select class="form-control select2" id="plumber_id" name="plumber_id" value="{{ old('connection_type') }}" name="connection_type" name="" style="width: 100%;">
                                                                    <option value="">Select</option>
                                                                    @foreach($plumberNames as $plumberName)
                                                                        <option value="{{ $plumberName->id }}" >{{ $plumberName->plumber_name }}</option>
                                                                    @endforeach
                                                            </select>
							</td>
						</tr>
					</table>
					<table class="table table-bordered table-hover">
						<thead>
							<tr>
								<th colspan="4">List of Document To Be Uploaded</th>
							</tr>
						</thead>
						<tr>
							<td width="20%">Blueprint of the premises where connection is required ff <span class="rq">*</span></td>
							<td width="30%">
                                                           
                       
                                                    <input type="file" name="documents[]" id="printfile" multiple onchange="validateImage('printfile')" required="required">
                                                    <span class="text-danger">
                                                        <strong id="printfile-error"></strong>
                                                    </span> </td>
                                                
							<td width="20%">Comments</td>
							<td width="30%"><textarea name="remarks" id="remarks" class="form-control" rows="3" placeholder="Enter ..."></textarea></td>
						</tr>
					</table>
					
					<table class="table table-bordered table-hover" >
						<thead>
							<tr>    
								<th>Recommended by</th>
                                                                <th>Application Number <span class="rq">*</span></th>
							</tr>
						</thead>
						<tr>
							<td width="50%"><input type="text" class="form-control" id="recommended_by" name="recommended_by" placeholder="Recommended by"></td>
                                                        <td  width="50%"><input type="text" class="form-control" id="application_number" name="application_number" placeholder="Application Number"> <span class="text-danger">
                                                        <strong id="application_no-error_tap"></strong>
                                                    </span></td>
						</tr>
					</table>
                     <div class="btn-group">
                    <button type="button" class="btn btn-danger" id="tap_save_button">Save</button>
                    <!--<button type="reset" class="btn btn-success" onclick="printContent('print_application')">Print Application</button>-->
                <button type="reset" class="btn btn-danger">Cancel</button>
            </div>
                                    </form>
					<div class="clearfix"></div>
            <!-- /.box-body -->

				</div>
              <!-- /.tab-pane -->
              
				<div class="tab-pane" id="tab_2">
                                      <form name="add_ugd_applictaion" id="add_ugd_applictaion" method="POST" >
                                            {{ csrf_field() }}
                                     <input type="hidden" id="application_type_id" name="application_type_id" value="2">
					<table class="table table-bordered table-hover form-group">
						<tbody>
							<tr>
								<td>Customer name <span class="rq">*</span></td>
								<td><input type="" class="form-control" id="customer_name" name="customer_name" placeholder="Name"> <span class="text-danger">
                                                        <strong id="customer_name_error_ugd"></strong>
                                                    </span></td>
								<td>Door No <span class="rq">*</span></td>
								<td><input type="" class="form-control" id="door_no" name="door_no" placeholder="Door No"> <span class="text-danger">
                                                        <strong id="door_no-error_ugd"></strong>
                                                    </span></td>
							</tr>
							<tr>
								<td>Phone Number <span class="rq">*</span></td>
								<td width="30%">	
									<input type="" class="form-control" id="phone_number" name="phone_number" placeholder="Phone Number"> <span class="text-danger">
                                                        <strong id="mobile_no-error_ugd"></strong>
                                                    </span>
								</td>
								<td width="20%">Ward <span class="rq">*</span></td>
								<td>
									  <select class="form-control select2 wardname" id="ward_id" name="ward_id" value="{{ old('ward_id') }}" name="" style="width: 100%;">
                                                                            <option value="">Select</option>
                                                                           @foreach($wards as $ward)
                                                                            <option value="{{ $ward->id }}">{{ $ward->ward_name }}</option>
                                                                           @endforeach
                                                                    </select>
                                                                     <span class="text-danger">
                                                        <strong id="ward-error_ugd"></strong>
                                                    </span>
								</td>
							</tr>
							<tr>
                                                               <td width="20%">Corp Ward Name <span class="rq">*</span></td>
                                                                <td>
                                                                           <select class="form-control select2 corpwardname" name="corp_ward_id" required="required">
                                                                            <option value="">select</option>

                                                                        </select>
                                                                                <span class="text-danger">
                                                                           <strong id="corp-ward-error_ugd"></strong>
                                                                       </span>
                                                                </td>
								<td width="20%">Khataa Number</td>
								<td width="30%">
									<input type="" class="form-control" id="khata_no" name="khata_no" placeholder="Number">
								</td>
								
							</tr>
						</tbody>
					</table>
					<table class="table table-bordered table-hover ">
						<thead>
							<tr>
								<th colspan="4">Location of Premises / place where connection required </th>
							</tr>
						</thead>
						<tr>
							<td width="20%">Name of the Premises Owner <span class="rq">*</span></td>
							<td width="30%"><input type="text" class="form-control" id="premises_owner_name" name="premises_owner_name" placeholder="Name"> <span class="text-danger">
                                                        <strong id="owner-error_ugd"></strong>
                                                    </span></td>
							<td width="20%">
								Address <span class="rq">*</span>
							</td>
							<td width="30%">    <textarea id="premises_address" name="premises_address" class="form-control" rows="3" placeholder="Enter ..."></textarea><span class="text-danger">
                                                        <strong id="address-error_ugd"></strong>
                                                    </span></td>
						</tr>
						<tr>
							<td width="20%">Street Name</td>
							<td width="30%"><input type="text" class="form-control" id="premises_street" name="premises_street" placeholder="Street Name"></td>
							<td width="20%">
								City
							</td>
							<td width="30%"><input type="text" class="form-control" id="premises_city" name="premises_city" placeholder="City"></td>
						</tr>
						<tr>
							<td width="20%">State</td>
							<td width="30%">
								<select class="form-control select2" id="premises_state" name="premises_state" style="width: 100%;">
									<option selected="selected">Select</option>
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
							<td width="30%"><input type="text" class="form-control" id="premises_zip" name="premises_zip" placeholder="Zip"></td>
						</tr>
					</table>

				<table class="table table-bordered table-hover ">
					<thead>
						<tr>
							<th colspan="4">Other Details</th>
						</tr>
					</thead>
					<tr>
						<td width="20%">Service applied for </td>
						<td width="30%">
							<select class="form-control select2" id="service_id" name="service_id" style="width: 100%;">
								<option value="1" selected="selected">Select</option>
								<option value="2">Service Applied New Connection</option>
								<option  value="3" >Service Applied for 2</option>
								<option  value="4" >Service Applied for 3</option>
								<option  value="5" >Service Applied for 4</option>
							</select>
						</td>
						<td width="20%">
							Connection Type <span class="rq">*</span>
						</td>
						<td width="30%">
							        <select class="form-control select2" value="{{ old('connection_type') }}" name="connection_type_id" id="connection_type_id" style="width: 100%;">
                                                                    <option value="">Select</option>
                                                                    @foreach($connTypes as $connType)
                                                                        <option value="{{ $connType->id }}" >{{ $connType->connection_name }}</option>
                                                                    @endforeach
                                                            </select>
                                                     <span class="text-danger">
                                                        <strong id="connection_type-error_ugd"></strong>
                                                    </span>
						</td>
					</tr>
					<tr>
						<td width="20%">No Of House existing in the building</td>
						<td width="30%"><input type="text" class="form-control" id="no_of_house" name="no_of_house" placeholder="No Of House "></td>
						<td width="20%">Plumber Name</td>
							<td width="30%">
								        <select class="form-control select2" id="plumber_id" name="plumber_id" value="{{ old('connection_type') }}" name="connection_type" name="" style="width: 100%;">
                                                                    <option value="">Select</option>
                                                                    @foreach($plumberNames as $plumberName)
                                                                        <option value="{{ $plumberName->id }}" >{{ $plumberName->plumber_name }}</option>
                                                                    @endforeach
                                                            </select>
                                                            
							</td>
					</tr>
				</table>
				<table class="table table-bordered table-hover ">
					<thead>
						<tr>
							<th colspan="4">List of Document To Be Uploaded</th>
						</tr>
					</thead>
					<tr>
						<td width="20%">Blueprint of the premises where UGD is required <span class="rq">*</span></td>
						<td width="30%"><input type="file" name="documents[]" id="ugdprint" multiple onchange="validateImage('ugdprint')">
                                                 <span class="text-danger">
                                                        <strong id="ugdprint-error"></strong>
                                                    </span></td>
						<td width="20%">Comments</td>
						<td width="30%"><textarea class="form-control" rows="3" id="remarks" name="remarks" placeholder="Enter ..."></textarea></td>
					</tr>
				</table>
                                         	<table class="table table-bordered table-hover" style="width:500px;">
						<thead>
							<tr>    
								<th>Application Number <span class="rq">*</span></th>
							</tr>
						</thead>
						<tr>
							<td><input type="text" class="form-control" id="application_number" name="application_number" placeholder="Application Number">
                                                         <span class="text-danger">
                                                        <strong id="application_no-error_ugd"></strong>
                                                    </span></td>
						</tr>
                                       </table>
                                     <div class="btn-group">
          
                                     <button type="button" class="btn btn-danger" id="ugd_save_button">Save</button>
                                    <!--<button type="reset" class="btn btn-success" onclick="printContent('print_application')">Print Application</button>-->
                                  <button type="reset" class="btn btn-danger">Cancel</button>
                             </div>
                                       </form>
            </div>
                    
                 
			<div class="tab-pane" id="tab_3">
                                <form name="add_jala_applictaion" id="add_jala_applictaion" method="POST" >
                                    {{ csrf_field() }}
                             <input type="hidden" id="application_type_id" name="application_type_id" value="3">
				<table class="table table-bordered table-hover form-group">
					<tr>
						<td>Customer Name <span class="rq">*</span></td>
						<td><input type="" class="form-control" id="customer_name" name="customer_name" placeholder="Name"> <span class="text-danger">
                                                        <strong id="customer_name_error_jala"></strong>
                                                    </span></td>
						<td>Phone Number <span class="rq">*</span></td>
						<td width="30%">	
							<input type="" class="form-control" id="phone_number" name="phone_number" placeholder="Phone Number">
                                                         <span class="text-danger">
                                                        <strong id="mobile_no-error_jala"></strong>
                                                    </span>
						</td>
					</tr>
					<tr>
					<td width="20%">Ward <span class="rq">*</span></td>
						<td>
							  <select class="form-control select2 wardname" name="ward_id" id="ward_id" value="{{ old('ward_id') }}" name="" style="width: 100%;">
                                                                    <option value="">Select</option>
                                                                   @foreach($wards as $ward)
                                                                    <option value="{{ $ward->id }}">{{ $ward->ward_name }}</option>
                                                                   @endforeach
                                                            </select>
                                                     <span class="text-danger">
                                                        <strong id="ward-error_jala"></strong>
                                                    </span>
						</td>
                                                   <td width="20%">Corp Ward Name <span class="rq">*</span></td>
                                                                <td>
                                                                           <select class="form-control select2 corpwardname" name="corp_ward_id" required="required">
                                                                            <option value="">select</option>

                                                                        </select>
                                                                                <span class="text-danger">
                                                                           <strong id="corp-ward-error_jala"></strong>
                                                                       </span>
                                                                </td>
						
					</tr>					
					<tr>
                                            <td width="20%">TS No</td>
						<td width="30%">
							<input type="" class="form-control" id="ts_no" name="ts_no" placeholder="TS No">
						</td>
						<td width="20%">RS No</td>
						<td>
							<input type="" class="form-control" id="rs_no" name="rs_no" placeholder="RS No">
						</td>
						
					</tr>
					<tr>
                                            <td width="20%">Income Proof / BPL Card</td>
						<td width="30%">
							<input type="file" class="form-control" id="bpl_doc" name="bpl_doc[]" multiple onchange="validateImage('bpl_doc')">
                                                         <span class="text-danger">
                                                        <strong id="bpl_doc-error"></strong>
                                                    </span>
						</td>
						<td width="20%">Income Tax Paid Receipt</td>
						<td>
							<input type="file" class="form-control" id="tax_doc" name="tax_doc[]" multiple onchange="validateImage('tax_doc')">
                                                           <span class="text-danger">
                                                        <strong id="tax_doc-error"></strong>
                                                    </span>
						</td>
						
					</tr>	
                                        <tr>
                                            <td width="20%">If rented House - Affidavit</td>
						<td width="30%">
							<input type="file" class="form-control" id="affidavit_doc" multiple onchange="validateImage('affidavit_doc')" name="affidavit_doc[]">
                                                         <span class="text-danger">
                                                        <strong id="affidavit_doc-error"></strong>
                                                    </span>
						</td>
                                        </tr>
				</table>
				<table class="table table-bordered table-hover ">
					<thead>
						<tr>
							<th colspan="4">Location of Premises / place where connection required </th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td width="20%">
								Address <span class="rq">*</span>
							</td>
							<td width="30%">    <textarea id="premises_address" name="premises_address" class="form-control" rows="3" placeholder="Enter ..."></textarea><span class="text-danger">
                                                        <strong id="address-error_jala"></strong>
                                                    </span></td>
							<td width="20%">Door No <span class="rq">*</span></td>
							<td width="30%"><input type="email" class="form-control" id="door_no" name="door_no" placeholder="Door No"> <span class="text-danger">
                                                        <strong id="door_no-error_jala"></strong>
                                                    </span></td>
						</tr>
						<tr>
							<td width="20%">Street Name</td>
							<td width="30%"><input type="text" class="form-control" id="premises_street" name="premises_street" placeholder="Street Name"></td>
							<td width="20%">
								City
							</td>
							<td width="30%"><input type="text" class="form-control" id="premises_city" name="premises_city" placeholder="City"></td>
						</tr>
						<tr>
							<td width="20%">State</td>
							<td width="30%">
								<select class="form-control select2" id="premises_state" name="comm_state" style="width: 100%;">
									<option selected="selected">Select</option>
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
							<td width="30%"><input type="text" class="form-control" id="premises_zip" name="premises_zip" placeholder="Zip"></td>
						</tr>
					</tbody>
				</table>
					<table class="table table-bordered table-hover" style="width:500px;">
						<thead>
							<tr>    
								<th>Application Number <span class="rq">*</span></th>
							</tr>
						</thead>
						<tr>
                                                    
							<td ><input type="text" class="form-control" id="application_number" name="application_number" placeholder="Application Number"><span class="text-danger">
                                                        <strong id="application_no-error_jala"></strong>
                                                    </span></td>
                                                     
						</tr>
		        </table>
                             <div class="btn-group">
                                 <button type="button" class="btn btn-danger" id="jala_save_button">Save</button>
<!--				<button type="reset" class="btn btn-success" onclick="printContent('print_application')">Print Application</button>-->
                               <button type="reset" class="btn btn-danger">Cancel</button>
            </div>
				  </form>
			</div>
              
			<div class="tab-pane" id="tab_4">   
                     <form name="add_seven_applictaion" id="add_seven_applictaion" method="POST" >
                       {{ csrf_field() }}
                <input type="hidden" id="application_type_id" name="application_type_id" value="4">
				<table class="table table-bordered table-hover form-group">
					<tr>
						<td>Customer name <span class="rq">*</span></td>
						<td><input type="" class="form-control" id="customer_name" name="customer_name" placeholder="Name"><span class="text-danger">
                                                        <strong id="customer_name_error_seven"></strong>
                                                    </span></td>
						<td>Father Name</td>
						<td><input type="" class="form-control" id="father_name" name="father_name" placeholder="Father Name"></td>
					</tr>
					<tr>
						<td>Phone Number <span class="rq">*</span></td>
						<td width="30%">	
							<input type="" class="form-control" id="phone_number" name="phone_number" placeholder="Phone Number">
                                                        <span class="text-danger">
                                                        <strong id="mobile_no-error_seven"></strong>
                                                    </span>
						</td>
						<td width="20%">Ward <span class="rq">*</span></td>
						<td>
							  <select class="form-control select2 wardname" name="ward_id" id="ward_id" value="{{ old('ward_id') }}" name="" style="width: 100%;">
                                                                    <option value="">Select</option>
                                                                   @foreach($wards as $ward)
                                                                    <option value="{{ $ward->id }}">{{ $ward->ward_name }}</option>
                                                                   @endforeach
                                                            </select>
                                                    <span class="text-danger">
                                                        <strong id="ward-error_seven"></strong>
                                                    </span>
						</td>
					</tr>
                                        <tr>        
                                            <td width="20%">Corp Ward Name <span class="rq">*</span></td>
                                                        <td>
                                                                   <select class="form-control select2 corpwardname" name="corp_ward_id" required="required">
                                                                    <option value="">select</option>

                                                                </select>
                                                                        <span class="text-danger">
                                                                   <strong id="corp-ward-error_seven"></strong>
                                                               </span>
                                            </td></tr>
				</table>
				<table class="table table-bordered table-hover form-group">
					<thead>
						<tr>
							<th colspan="4">Location of Premises / place where connection required </th>
						</tr>
					</thead>
					<tr>
						<td width="20%">
							Address <span class="rq">*</span>
						</td>
						<td width="30%">
                                                <textarea id="premises_address" name="premises_address" class="form-control" rows="3" placeholder="Enter ..."></textarea>
                                                   <span class="text-danger">
                                                        <strong id="address-error_seven"></strong>
                                                    </span></td>
						<td width="20%">Door No <span class="rq">*</span></td>
						<td width="30%"><input type="text" class="form-control" id="door_no" name="door_no" placeholder="Door No"> <span class="text-danger">
                                                        <strong id="door_no-error_seven"></strong>
                                                    </span></td>
					</tr>
					<tr>
						<td width="20%">Street Name</td>
						<td width="30%"><input type="text" class="form-control" id="premises_street" name="premises_street" placeholder="Street Name"></td>
						<td width="20%">
							City
						</td>
						<td width="30%"><input type="text" class="form-control" id="premises_city" name="premises_city" placeholder="City"></td>
					</tr>
					<tr>
						<td width="20%">State</td>
						<td width="30%">
							<select class="form-control select2" id="premises_state" name="premises_state" style="width: 100%;">
								<option selected="selected">Select</option>
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
						<td width="30%"><input type="text" class="form-control" id="premises_zip" name="premises_zip" placeholder="Zip"></td>
					</tr>
				</table>
				<table class="table table-bordered table-hover form-group">
					<thead>
						<tr>
							<th colspan="4">Other Details</th>
						</tr>
					</thead>
					<tr>
						<td width="20%">Caste Certificate</td>
						<td width="30%"><input type="file" class="form-control" id="caste_doc" name="caste_doc[]"  multiple onchange="validateImage('caste_doc')">
                                                 <span class="text-danger">
                                                        <strong id="caste_doc-error"></strong>
                                                    </span></td>
						<td width="20%">
							Caste
						</td>
						<td width="30%">
							<label class="radio-inline"><input type="radio" name="caste">Scheduled Caste</label>
							<label class="radio-inline"><input type="radio" name="caste">Scheduled Tribe</label>
						</td>
					</tr>
					<tr>
						<td>Sub Caste</td>
						<td><input type="text" class="form-control" id="sub_caste" name="sub_caste" placeholder="Sub Caste"></td>
						<td width="20%">BPL Card Copy</td>
						<td width="30%"><input type="file" class="form-control" id="bpl_card_doc" name="bpl_card_doc[]" multiple onchange="validateImage('bpl_card_doc')">
                                                  <span class="text-danger">
                                                        <strong id="bpl_card_doc-error"></strong>
                                                    </span></td>
					</tr>
					<tr>
						<td>BPL Card No</td>
						<td><input type="text" class="form-control" id="bpl_card_no" name="bpl_card_no" placeholder="BPL Card No"></td>
						<td width="20%">Income Certificate</td>
                                                    <td width="30%"><input type="file" class="form-control" id="income_doc" name="income_doc[]" multiple onchange="validateImage('income_doc')">
                                                     <span class="text-danger">
                                                        <strong id="income_doc-error"></strong>
                                                    </span></td>
					</tr>		
					<tr>
						<td>Annual Income</td>
						<td><input type="text" class="form-control" id="annual_income" name="annual_income" placeholder="Annual Income"></td>
						<td width="20%">Annual Income Verified Date</td>
						<td width="30%"><div class="input-group date">
                                                                    <div class="input-group-addon">
                                                                            <i class="fa fa-calendar"></i>
                                                                    </div><input type="text" class="form-control datepicker" id="annual_income_verified_date" name="annual_income_verified_date" placeholder="DD/MM/YY"></td>
					</tr>	
					<tr>
						<td>Income Tax Paid Receipt</td>
						<td><input type="file" class="form-control" id="tax_paid_doc" name="tax_paid_doc[]" multiple onchange="validateImage('tax_paid_doc')" >
                                                 <span class="text-danger">
                                                        <strong id="tax_paid_doc-error"></strong>
                                                    </span></td>
						<td width="20%">Income Tax Paid Date</td>
						<td width="30%"><div class="input-group date">
                                                                    <div class="input-group-addon">
                                                                            <i class="fa fa-calendar"></i>
                                                                    </div>
                <input type="text" class="form-control datepicker" id="income_tax_paid_date" name="income_tax_paid_date" placeholder="DD/MM/YY" required="">                                                            </div></td>
					</tr>	
					<tr>
						<td>Current drinking water system</td>
						<td>
							<label class="radio-inline"><input type="radio" name="water_system">Yes</label>
							<label class="radio-inline"><input type="radio" name="water_system">No</label>
						</td>
						<td width="20%">Blueprint of the premises where connection is required</td>
						<td width="30%"><input type="file" class="form-control" id="blueprint_doc" name="blueprint_doc[]" multiple onchange="validateImage('blueprint_doc')">
                                                <span class="text-danger">
                                                        <strong id="blueprint_doc-error"></strong>
                                                    </span></td>
					</tr>	
				</table>
                	<table class="table table-bordered table-hover" style="width:500px;">
						<thead>
							<tr>    
								<th>Application Number <span class="rq">*</span></th>
							</tr>
						</thead>
						<tr>
							<td  width="20%"><input type="text" class="form-control" id="application_number" name="application_number" placeholder="Application Number"> <span class="text-danger">
                                                        <strong id="application_no-error_seven"></strong>
                                                    </span></td>
						</tr>
		        </table>
                <div class="btn-group">
                    <button type="button" class="btn btn-danger" id="seven_save_button">Save</button>
                    <!--<button type="reset" class="btn btn-success" onclick="printContent('print_application')">Print Application</button>-->
                <button type="reset" class="btn btn-danger">Cancel</button>
            </div>
                    </form>
			</div>
                 
			<div class="clearfix">
		
        
			
			</div>
              </div>
              <!-- /.tab-pane -->
            </div>
			
		</div>
            <!-- /.tab-content -->
          <!-- nav-tabs-custom -->

    </section>
<script>var siteUrl = '<?php echo url('/'); ?>';</script>
<script>
    $(document).ready(function () {
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
                              if (data.errors.corp_ward_id) {
                                $('#corp-ward-error_tap').html(data.errors.corp_ward_id[0]);
                            }
							if (data.errors.documents) {
                                $('#printfile-error').html(data.errors.documents[0]);
                            }
                            

                        }
                        if (data.success) {
                            $('html, body').animate({ scrollTop: 0 }, 0);
                            $('#success-msg1').removeClass('hide');
                            setInterval(function () {
                                $('#success-msg1').addClass('hide');

                            }, 4000); 
                             $("#add_tap_applictaion")[0].reset();
                        }
                    },
                });
            }
            
    });
      $('body').on('click', '#ugd_save_button', function () {
          
          clearErrorFields();
            var get_type=  $("#applictaion_types li.active").attr('id');
         if(get_type=="ugd")
            {
               var formData = new FormData($("#add_ugd_applictaion")[0]);              
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
                                $('#customer_name_error_ugd').html(data.errors.customer_name[0]);
                            }
                            if (data.errors.phone_number) {
                                $('#mobile_no-error_ugd').html(data.errors.phone_number[0]);
                            }
                            if (data.errors.door_no) {
                                $('#door_no-error_ugd').html(data.errors.door_no[0]);
                            }
                            if (data.errors.ward_id) {
                                $('#ward-error_ugd').html(data.errors.ward_id[0]);
                            }
                            if (data.errors.premises_owner_name) {
                                $('#owner-error_ugd').html(data.errors.premises_owner_name[0]);
                            }
                            if (data.errors.premises_address) {
                                $('#address-error_ugd').html(data.errors.premises_address[0]);
                            }
                            if (data.errors.connection_type_id) {
                                $('#connection_type-error_ugd').html(data.errors.connection_type_id[0]);
                            } 
                            if (data.errors.application_number) {
                                $('#application_no-error_ugd').html(data.errors.application_number[0]);
                            }
                           if (data.errors.corp_ward_id) {
                                $('#corp-ward-error_ugd').html(data.errors.corp_ward_id[0]);
                            }
							if (data.errors.documents) {
                                $('#ugdprint-error').html(data.errors.documents[0]);
                            }

                        }
                        if (data.success) {
                            $('html, body').animate({ scrollTop: 0 }, 0);
                            $('#success-msg1').removeClass('hide');
                            setInterval(function () {
                                $('#success-msg1').addClass('hide');

                            }, 4000); 
                             $("#add_ugd_applictaion")[0].reset();
                        }
                    },

                 
                });
            }
        });
    $('body').on('click', '#jala_save_button', function () {
        
            clearErrorFields();
           var get_type=  $("#applictaion_types li.active").attr('id');
          if(get_type=="jalabhagya")
            {
                var formData = new FormData($("#add_jala_applictaion")[0]);  
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
                                $('#customer_name_error_jala').html(data.errors.customer_name[0]);
                            }
                            if (data.errors.phone_number) {
                                $('#mobile_no-error_jala').html(data.errors.phone_number[0]);
                            }
                            if (data.errors.door_no) {
                                $('#door_no-error_jala').html(data.errors.door_no[0]);
                            }
                            if (data.errors.ward_id) {
                                $('#ward-error_jala').html(data.errors.ward_id[0]);
                            }
                          
                            if (data.errors.premises_address) {
                                $('#address-error_jala').html(data.errors.premises_address[0]);
                            }
                            if (data.errors.application_number) {
                                $('#application_no-error_jala').html(data.errors.application_number[0]);
                            }
                             if (data.errors.corp_ward_id) {
                                $('#corp-ward-error_jala').html(data.errors.corp_ward_id[0]);
                            }

                        }
                        if (data.success) {
                            $('html, body').animate({ scrollTop: 0 }, 0);
                            $('#success-msg1').removeClass('hide');
                            setInterval(function () {
                                $('#success-msg1').addClass('hide');

                            }, 4000); 
                             $("#add_jala_applictaion")[0].reset();
                        }
                    },

                });
            }
        });
         $('body').on('click', '#seven_save_button', function () {
             
             clearErrorFields();
           var get_type=  $("#applictaion_types li.active").attr('id');
            if(get_type=="seven")
                {
                    var formData = new FormData($("#add_seven_applictaion")[0]);              
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
                                $('#customer_name_error_seven').html(data.errors.customer_name[0]);
                            }
                            if (data.errors.phone_number) {
                                $('#mobile_no-error_seven').html(data.errors.phone_number[0]);
                            }
                            if (data.errors.door_no) {
                                $('#door_no-error_seven').html(data.errors.door_no[0]);
                            }
                            if (data.errors.ward_id) {
                                $('#ward-error_seven').html(data.errors.ward_id[0]);
                            }
                          
                            if (data.errors.premises_address) {
                                $('#address-error_seven').html(data.errors.premises_address[0]);
                            }
                            if (data.errors.application_number) {
                                $('#application_no-error_seven').html(data.errors.application_number[0]);
                            }
                             if (data.errors.corp_ward_id) {
                                $('#corp-ward-error_seven').html(data.errors.corp_ward_id[0]);
                            }

                        }
                        if (data.success) {
                            $('html, body').animate({ scrollTop: 0 }, 0);
                            $('#success-msg1').removeClass('hide');
                            setInterval(function () {
                                $('#success-msg1').addClass('hide');

                            }, 4000); 
                             $("#add_seven_applictaion")[0].reset();
                        }
                    },

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
                        .text("select"));                     
                                       
                }
            },
        });
    });
    });
    function validateImage(id) {
    $('#'+id+'-error').html('');
	
	var filess = document.getElementById(id).files;
    var total_file = document.getElementById(id).files.length;

    for(i=0;i<=total_file;i++){
        var file = document.getElementById(id).files[i];
        var t = file.type.split('/').pop().toLowerCase();
        if (t != "jpeg" && t != "jpg" && t != "png" && t != "bmp" && t != "gif" && t != "pdf") {
            //alert('Please select a valid image file');
            $('#'+id+'-error').html('Please select jpeg,jpg,png,bmp,gif or pdf  file format.');
            document.getElementById(id).value = '';
            return false;
        }
    }
    

    //if (file.size > 1024000) {
    //    alert('Max Upload size is 1MB only');
    //    $('#'.id.'-error').html('Max Upload size is 1MB only');
    //    document.getElementById(id).value = '';
    //    return false;
   // }
    return true;
}
 function clearErrorFields()
    {
      
        $('#customer_name_error_tap').html('');
        $('#mobile_no-error_tap').html('');
        $('#door_no-error_tap').html('');
        $('#ward-error_tap').html('');
        $('#owner-error_tap').html('');
        $('#address-error_tap').html('');
        $('#connection_type-error_tap').html('');
        $('#application_no-error_tap').html('');
        $('#corp-ward-error_tap').html('');
        
        $('#customer_name_error_ugd').html('');
        $('#mobile_no-error_ugd').html('');
        $('#door_no-error_ugd').html('');
        $('#ward-error_ugd').html('');
        $('#corp-ward-error_ugd').html(''); 
        $('#owner-error_ugd').html('');
        $('#address-error_ugd').html('');
        $('#connection_type-error_ugd').html('');
        $('#application_no-error_ugd').html('');
        
        $('#customer_name_error_jala').html('');
        $('#mobile_no-error_jala').html('');
        $('#door_no-error_jala').html('');
        $('#ward-error_jala').html('');
        $('#corp-ward-error_jala').html('');
        $('#address-error_jala').html('');
        $('#application_no-error_jala').html('');
        
        $('#customer_name_error_seven').html('');
        $('#mobile_no-error_seven').html('');
        $('#door_no-error_seven').html('');
        $('#ward-error_seven').html('');
        $('#address-error_seven').html('');
        $('#application_no-error_seven').html('');
        $('#corp-ward-error_seven').html('');

    }
</script>
@endsection
