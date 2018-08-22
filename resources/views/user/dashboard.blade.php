@extends('layouts.user_master')
@section('content')
<section class="content-header">
    <h1>
        Home
        <small>Welcome to Mangaluru City Corporation Water Bill E Payment System</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard_mcc"></i> Level</a></li>
        <li class="active">Here</li>
    </ol>
</section>

<!-- Main content -->
@if($activeBill == 1)
<section class="content container-fluid">
		 <div class="row">
			<div class="col-md-6">
			    <div class="info-box bg-green">
					<span class="info-box-icon"><i class="fa fa-paypal"></i></span>
					<div class="info-box-content">
						  <span class="info-box-text">Bill Summary
						  </span>
						  <span class="info-box-number">{{$total_amount}}</span>
						  <div class="progress">
							<div class="progress-bar" style="width: 70%"></div>
						  </div>
						  <span class="progress-description">
								Due Date : {{date_format(date_create($payment_due_date),'d F Y')}}<!--28 July 2018 -->
						  </span>
					</div>
					<!-- /.info-box-content -->
				</div>
				<img src="{{ asset('dist/img/banner.png') }}" class="img-responsive">
				<br>
			</div>
			
					   <!-- quick email widget -->
			<div class="col-md-6">
				<div class="box box-danger box-solid">
					<div class="box-header with-border">
						<h3 class="box-title">Customer Details</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
						</div>
							  <!-- /.box-tools -->
					</div>
							<!-- /.box-header -->
					<div class="box-body">
						<div class="table-responsive">						
							<table class="table table-responsive table-bordered table-hover table-striped">
								<tr>
									<td>Sequence Number</td>
									<td>{{$sequence_number}}</td>
								</tr>
								<tr>
									<td>Consumer Name</td>
									<td>{{$consumer_name}}</td>
								</tr>
								<tr>
									<td>House Number</td>
									<td>{{$door_no}}</td>
								</tr>
								<tr>
									<td>Meter Number</td>
									<td>{{$meter_no}}</td>
								</tr>
								<tr>
									<td>Tariff</td>
									<td>{{$connection_name}}</td>
								</tr>
								<tr>
									<td>Status</td>
									<td>{{$status}}</td>
								</tr>
								<tr>
									<td>Billed Date</td>
									<td>{{date_format(date_create($date_of_reading),'d/m/Y')}}</td>
								</tr>
								<tr>
									<td colspan="2"><h3 class="text-red">Bill Details</h3></td>
								</tr>							
								<tr>
									<td><b>Previous Reading</b></td>
									<td><b>{{$previous_reading}}</b></td>
								</tr>
								<tr>
									<td><b>Current Reading</b></td>
									<td><b>{{$current_reading}}</b></td>
								</tr>
								<tr>
									<td><b>Total Used Units</b></td>
									<td><b>{{$total_unit_used}}</b></td>
								</tr>
								<tr>
									<td><b class="text-red">Total Amount</b></td>
									<td><h3><b class="text-red">{{$total_amount}}</b></h3></td>
								</tr>
								<tr>
									<td colspan="2"><button type="button" class="btn btn-block btn-danger">Make Payment</button></td>
								</tr>
							</table>
						</div>
					</div>
            <!-- /.box-body -->
				</div>
			</div>
		</div> 
	 </section>
@else
<section class="content container-fluid">
		 <div class="row">
                     <div class="col-md-12" align="center">
                         <span class="error" style="color:red">
                            <h3>Sorry ! We have not found any bill for the sequence number {{$sequence_number}}.</h3>
                        </span>
                     </div>
                     </div>
</section>
@endif
<!-- /.content -->

@push('script')
<script>
</script>

@endpush
@endsection