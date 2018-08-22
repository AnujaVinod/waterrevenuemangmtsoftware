@extends('layouts.admin_master')
@section('content')
<section class="content-header">
    
     <?php $role = Helper::getRole();
       $sub_category = $role->sub_category_name;
       $category = $role->category_name;
     
       
       if($role->category_name=="EXECUTIVE") {
            $pageTitle1 = "Approve Bills";
            $pageTitle2 = "Approve / Print Bills";
           
        }
        if($role->category_name=="MCC" && $role->sub_category_name=="Administrator") {
            
            $pageTitle1 = "Print Bills";
            $pageTitle2 = "Review / Print Bills";
        }
       
       
       ?>  
    
  <h1>
  
      {{$pageTitle1}}
      
      
  </h1>
</section>
    
<!-- Main content -->

    <section class="content container-fluid">
        	<div class="box box-info">
				<div class="box-header with-border">
					<h3 class="box-title"> {{ $pageTitle2 }}</h3>
					
				  <!-- /.box-tools -->
				</div>
                    <input type="hidden" id="check_cate" value="<?php echo $category;?>">
                    <input type="hidden" value="{{ Auth::user()->id }}" id="current_user">
				<!-- /.box-header -->
				<div class="box-body table-responsive" id="display_div">
					<table id="application_list_table" class="table table-responsive table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th>Sequence No.</th>
								<th>Date Of Reading</th>
								<th>Name</th>
                                                                <th>Meter No</th>
								<th>Total Amount</th>
								<th>Status </th>
								<th>Action</th>
							</tr>
						</thead>
						
					</table>
				</div>
			</div>
				<!-- /.box-body -->
		</section>
    

          <!-- /.modal-dialog -->
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
  $('.datepicker').datepicker({
      autoclose: true,
          dateFormat: 'dd/mm/yy',
          orientation: "bottom auto"
    });

     
      });
   
   function loadDatatable() {
                

    dtTable = $('#application_list_table').DataTable({
       "processing": true,
       "serverSide": true,
        ajax: {
            url: siteUrl+'/admin/getBillListForReview',
            method: 'POST', 
        },
       "columns":[         
           { "data": "sequence_number" },
           { "data": "date_of_reading"},
           { "data": "consumer_name" },
           { "data": "meter_no"},
           { "data": "total_amount"},
           { "data": "status"},
           { data: 'edit_option', name: 'edit_option', orderable: false, searchable: false},
       ]
    });
}

function reviewBill(id,sequenceNumber) {
    BootstrapDialog.show({
            title: 'Approve Bill',
            size:"size-wide",
            message: $('<div></div>').load(siteUrl+'/admin/getBillDetails?id='+id),
            buttons: [
                @if($category == "EXECUTIVE")
                {
                    label: 'Approve',
                    cssClass: 'btn-success',
                    action: function(dialog) {
                        approveBill(id,sequenceNumber);
                        dialog.close();

                    }
                },
                @endif
                {
                    label: 'Close',
                    action: function(dialog) {
                        dialog.close();
                    }
                }
            ]
            
        });
}

function approveBill(id,sequenceNumber){
    
                   
                    $.ajax({
                    url: siteUrl + "/admin/approve_bill_details",
                    type: 'POST',
                    data: { id:id,
                            sequenceNumber:sequenceNumber
                          },
                    async: true,
                    success: function (data) {										
                                        
                                        //$("#meter_sanctioned_date").prop("readonly", true);
                                       dtTable.draw(false);
                                      
                                }
                });
}

function printBill(sequenceNumber,meterNumber){
    
    window.open(siteUrl+'/admin/print_new_bill' + '?sn=' + sequenceNumber+'&mn='+meterNumber+'','_blank'); //'/admin/print_new_bill' + '?sn=' + sequenceNumber+'&mn='+meterNumber;
}

</script>
@endsection
