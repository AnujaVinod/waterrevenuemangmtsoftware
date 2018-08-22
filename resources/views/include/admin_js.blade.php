<!-- REQUIRED JS SCRIPTS -->
<!-- jQuery 3 -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="{{ asset('bootstrap-source/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('bootstrap-source/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('bootstrap-source/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
   <script src="{{ asset('bootstrap3-dialog/js/bootstrap-dialog.min.js') }}"></script> 
<script>
$(document).ready( function() {
    $('#myCarousel').carousel({
    	interval:   4000
	});	
	var clickEvent = false;
	$('#myCarousel').on('click', '.nav a', function() {
			clickEvent = true;
			$('.nav li').removeClass('active');
			$(this).parent().addClass('active');		
	}).on('slid.bs.carousel', function(e) {
		if(!clickEvent) {
			var count = $('.nav').children().length -1;
			var current = $('.nav li.active');
			current.removeClass('active').next().addClass('active');
			var id = parseInt(current.data('slide-to'));
			if(count == id) {
				$('.nav li').first().addClass('active');	
			}
		}
		clickEvent = false;
	});
     $('#date_picker').datepicker({
      autoclose: true,
	  dateFormat: 'dd/mm/yy'
    })

	$('#date_picker1').datepicker({
      autoclose: true
    })
});
</script>