<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
  <!-- Sidebar user panel (optional) -->
  <div class="user-panel">
    <div class="pull-left image">
      <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
    </div>
    <div class="pull-left info">
      <p>{{$user_data['consumer_name']}}</p>
      <!-- Status -->
      <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
    </div>
  </div>
  <!-- search form (Optional) -->
  <form action="#" method="get" class="sidebar-form">
    <div class="input-group">
      <input type="text" name="q" class="form-control" placeholder="Search...">
      <span class="input-group-btn">
          <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
          </button>
        </span>
    </div>
  </form>
  <!-- /.search form -->
  <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">DASHBOARD</li>        
        <li><a href="new_connection_payment.html"><i class="fa fa-user"></i> <span>New Connection Payment</span></a></li>
        <li><a href="payment_detail_mcc.html"><i class="fa fa-paypal"></i> <span>Bill Payment</span></a></li>
      </ul>
  <!-- /.sidebar-menu -->
   <br><br>
  <center><img src="{{ asset('dist/img/logo-sidebar.png') }}"></center>
</section>
<!-- /.sidebar -->