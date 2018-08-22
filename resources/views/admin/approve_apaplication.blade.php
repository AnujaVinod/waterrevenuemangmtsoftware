<style>
    
    #approve_div{
        margin-left: 400px;
    }
    
</style>
<div class="col-md-4 text-center" id="approve_div">
<section class="content container-fluid">
			<div class="row col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title">Application Submit Success Report</h3>
						<!-- /.box-tools -->
					</div>
					<div class="box-body">
						<div class="alert alert-info">
							<h1>THANK YOU</h1>
							<i class="icon fa fa-check-circle-o" style="font-size:100px;"></i> 
							<h3>Your application has been APPROVED</h3>
							<div class="callout callout-warning">
							<h4>Consumer Name - {{ $customer_name }}<br>
							Sequence Number : {{ $sequence_number }}<br>
							Meter Number : {{ $meter_no }}</div>
							<a href="{{ url('admin/addNewApplication') }}" class="btn bg-info btn-warning">Ok</a>
						</div>
					</div>
				</div>
			</div>
		</section>
    </div>