<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html class="html">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MANGALURU CITY CORPORATION | Water Bill E Payment</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="{{ asset('bootstrap-source/bootstrap/dist/css/bootstrap.min.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('bootstrap-source/font-awesome/css/font-awesome.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('bootstrap-source/Ionicons/css/ionicons.min.css') }}">
  <!-- Theme style -->
   <link rel="stylesheet" href="{{ asset('dist/css/custom.css') }}">
  <link rel="stylesheet" href="{{ asset('dist/css/main.css') }}">
 
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect. -->
  <link rel="stylesheet" href="{{ asset('dist/css/skins/skin-blue.min.css') }}">
  
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<!--

Theme Editor Info - Santhosh.c | 9900102363

-->
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
	<div class="col-md-6 hidden-sm hidden-xs no-padding">
		<div class="image-warap-left">
			<img src="dist/img/E-bill.png">
		</div>
	</div>
		<div class="col-md-1 col-sm-4 col-xs-12 no-padding pull-right">
		<div class="vert-middle">
			<div class="btn-group-vertical">
				<button id="login-form-link" type="button" class="btn btn-block btn-danger btn-flat">Consumer Login</button>
				<button id="register-form-link" type="button" class="btn btn-block btn btn-block btn-success btn-flat">MCC Login</button>
			</div>
		</div>
	</div>
	<div class="col-md-5 col-sm-8 col-xs-12 no-padding">
        <form id="login-form" action="{{url("consumer/login")}}" class="form-horizontal" method="post" role="form" style="display: none;">
			<div class="login-sec">
                            {{ csrf_field() }}
				<h1 class="login-sectitle">MANGALURU CITY CORPORATION</h1>
				<h1 class="login-seclab">Water Bill Payment</h1>
				<div style="margin-bottom: 25px" class="input-group">
					<span class="input-group-addon login-addon"><i class="glyphicon glyphicon-user"></i></span>
                                        <input id="login-username" autocomplete="off" type="text" class="form-control login-input" id="sequence_number" name="sequence_number" value="" placeholder="Sequence Number" />                                     
				</div>
                                
				<!--<div style="margin-bottom:5px" class="input-group">
					<span class="input-group-addon login-addon"><i class="fa fa-phone"></i></span>
                                        <input id="login-password" type="text" autocomplete="off" class="form-control login-input" id="Phone_Number" name="Phone_Number" placeholder="Phone Number">
				</div> -->
                                @include('errors.loginError')
				<div class="input-group pull-right">
					<div class="checkbox checkboxlogin">
						<label>
							<input id="login-remember" type="checkbox" name="remember" value="1" color="white"> Remember me
						</label>
					</div>
				</div></br>
				<div class="form-group">
					<div class="col-sm-12 controls">
						<center><button type="submit" class="btn btn-default-no-bg"><img class="login-btn" src="{{ asset('dist/img/login.png') }}"></button></center>
					</div>
				</div> 
				<!--<p><center><a href="#">Forgot Password</a></center></p>-->
			</div>
        </form>
        <form id="register-form" class="form-horizontal" method="POST" action="{{ route('login') }}" role="form" style="display: none;">
        {{ csrf_field() }}
			<div class="login-sec">
				<h1 class="login-sectitle">MANGALURU CITY CORPORATION</h1>
				<h1 class="login-seclab">Water Bill Payment..</h1>
                @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                @endif 
				<div style="margin-bottom: 25px" class="input-group">
                
					<span class="input-group-addon login-addon"><i class="glyphicon glyphicon-user"></i></span>
					<input id="login-username" type="text" class="form-control login-input" name="email" value="{{ old('email') }}" placeholder="User Name" required="required" autofocus>   
                                         
				</div>
                @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                @endif
				<div style="margin-bottom:5px" class="input-group">
					<span class="input-group-addon login-addon"><i class="fa fa-lock"></i></span>
					<input id="login-password" type="password" class="form-control login-input" name="password" placeholder="Password" required="required">
                    
				</div>
				<div class="input-group pull-right">
					<div class="checkbox checkboxlogin">
						<label>
							<input id="login-remember" type="checkbox" name="remember" value="1" color="white" {{ old('remember') ? 'checked' : '' }}> Remember me
						</label>
					</div>
				</div>
                
                        @if ($errors->has('loginerror'))
                            <input id="from_page" type="hidden" value="consumer">
                        @else
                            <input id="from_page" type="hidden" value="admin">
                        @endif
                                
				<div class="form-group">
					<div class="col-sm-12 controls">
						<center><button type="submit" class="btn btn-default-no-bg"><img class="login-btn" src="{{ asset('dist/img/login.png') }}"></button></center>
					</div>
				</div> 
				<!--<p><center><a href="{{ route('password.request') }}">Forgot Password</a></center></p> -->
			</div>
        </form>
	</div>    
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3 -->
<script src="{{ asset('bootstrap-source/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('bootstrap-source/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
	 
	<script>
$(function() {
    $('#login-form-link').click(function(e) {
                $("#login-form").delay(100).fadeIn(100);
                $("#from_page").val('consumer');
 		$("#register-form").fadeOut(100);
		$('#register-form-link').removeClass('active');
		$(this).addClass('active');
		e.preventDefault();
	});
	$('#register-form-link').click(function(e) {
                $("#from_page").val('admin');
		$("#register-form").delay(100).fadeIn(100);
 		$("#login-form").fadeOut(100);
		$('#login-form-link').removeClass('active');
		$(this).addClass('active');
		e.preventDefault();
	});        

});
$(document).ready(function() {
            var from_page = $("#from_page").val();
            if(from_page == "consumer"){
                $('#login-form-link').trigger('click');
            }
            if(from_page == "admin"){
                $('#register-form-link').trigger('click');
            }
        });
</script>	
	 
	 
</body>
</html>