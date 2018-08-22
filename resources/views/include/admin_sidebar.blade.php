<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
    <div class="user-panel">
        <div class="pull-left image">
            <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
            <p>{{{ isset(Auth::user()->name) ? Auth::user()->name : Auth::user()->email }}}</p>
            <!-- Status -->
            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
    </div>
    <!-- Sidebar Menu -->
    <ul class="sidebar-menu" data-widget="tree">
        <!--<li class="header">DASHBOARD</li>-->
    <li><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i> <span>DASHBOARD</span></a></li>
       <?php $role1 = Helper::getRole();
       $sub_category = $role1->sub_category_name;
       $category = $role1->category_name;
       
         // dump($role1); 
       ?>       
        <!-- Optionally, you can add icons to the links -->
        @if((strcasecmp($sub_category,"Administrator") == 0)|| (strcasecmp($category,"Super Admin") == 0))
        <li class="treeview">
            <a href="#"><i class="fa fa-newspaper-o"></i> <span>Application Management</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu"> 
                @if((!strcasecmp($category,"EXECUTIVE") == 0))
                <li><a href="{{ URL::to('admin/addNewApplication')}}">New Application</a></li>
                 @endif
                <li><a href="{{ URL::to('admin/addApplicationDetail')}}">Application Details</a></li>
               
            </ul>
        </li>
        @endif
       @if((strcasecmp($sub_category,"Administrator") == 0)|| (strcasecmp($category,"Super Admin") == 0))
        <li class="treeview">
              @if((!strcasecmp($category,"EXECUTIVE") == 0))
            <a href="#"><i class="fa fa-newspaper-o"></i> <span>Connection Management</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
              
            <ul class="treeview-menu">
                @if((strcasecmp($category,"Super Admin") == 0))
                <li><a href="{{ URL::to('admin/addConnectionDetail') }}">Add Connection Details</a></li>
                @endif
                @if((strcasecmp($sub_category,"Administrator") == 0)|| (strcasecmp($category,"Super Admin") == 0))
                <li><a href="{{ URL::to('admin/connectionDetail') }}">Connection Detail</a></li>
                @endif
            </ul>
              @endif
        </li>
       @endif 
        @if((strcasecmp($sub_category,"Administrator") == 0 && strcasecmp($category,"EXECUTIVE") != 0) || (strcasecmp($category,"Super Admin") == 0))
        <li class="treeview">
            <a href="#"><i class="fa fa-cogs"></i> <span>Service Request</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">				  
                <li><a href="{{ URL::to('admin/connectionStatusChange') }}">Disconnect / Reconnect</a></li>
                <li><a href="{{ URL::to('admin/meterNameChange') }}">Meter / Name Change</a></li>
                <li><a href="{{ URL::to('admin/tariffChange') }}">Tariff Change</a></li>
            </ul>
        </li>
        @endif
       
        @if ((strcasecmp($category,"Bank") == 0) || (strcasecmp($category,"Super Admin") == 0) )
        <li><a href="{{URL::to('/admin/billpayment')}}"><i class="fa fa-paypal"></i> <span>Bill Payment</span></a></li>
       
        @endif
          <li class="treeview">
            <a href="#"><i class="fa fa-calculator"></i> <span>Billing</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">	
                  @if((strcasecmp($sub_category,"Administrator") == 0) || (strcasecmp($category,"Super Admin") == 0 ))
                <li><a href="{{URL::to('/admin/duplicateBill')}}"><span>Generate Duplicate Bill</span></a></li>
                  @endif
                   @if((strcasecmp($category,"Super Admin") == 0 ) || (strcasecmp($category,"MCC") == 0) && (strcasecmp($sub_category,"Administrator") == 0))
                    <li><a href="{{URL::to('/admin/newbill')}}"><span>Generate Bill</span></a></li>
                  @endif
                   @if((strcasecmp($category,"EXECUTIVE") == 0 ))
                  <li><a href="{{URL::to('/admin/approvebill')}}"><span>Approve Bill</span></a></li>
                  @endif
                  @if((strcasecmp($category,"MCC") == 0) && (strcasecmp($sub_category,"Administrator") == 0))
                  <li><a href="{{URL::to('/admin/approvebill')}}"><span>Print Bill</span></a></li>
                  @endif
            </ul>
        </li>

             
           <!-- <li><a href="{{URL::to('/admin/industrialbill')}}"><i class="fa fa-calculator"></i> <span>Generate Industrial Bill</span></a></li> -->
       
        @if((strcasecmp($category,"Super Admin") == 0))
        <li class="treeview">
            <a href="#"><i class="fa fa-table"></i> <span>Manage Master</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="{{ URL::to('admin/ward') }}">Ward</a></li>
                <li><a href="{{ URL::to('admin/corpward') }}">Corp Ward</a></li>				
                <li><a href="{{ URL::to('admin/connectionrate') }}">Connection Rates</a></li>
                <li><a href="{{ URL::to('admin/connectiontype') }}">Connection Type</a></li>
                <li><a href="{{ URL::to('admin/inspector') }}">Manage Inspector</a></li>
                <li><a href="{{ URL::to('admin/applicationstatus') }}">Application Status</a></li>
                <li><a href="{{ URL::to('admin/connectionstatus') }}">Connection Status</a></li>
                <li><a href="{{ URL::to('admin/agent') }}">Manage Agent</a></li>
                <li><a href="{{ URL::to('admin/plumber') }}">Plumber Details</a></li>				
            </ul>
        </li>
        <li><a href="{{ url('admin/usermanage') }}"><i class="fa  fa-users"></i> <span>User Management</span></a></li>
        @endif
        @if((strcasecmp($sub_category,"Administrator") == 0) || (strcasecmp($sub_category,"Viewer") == 0) || (strcasecmp($category,"Super Admin") == 0))
        <li class="treeview">
            <a href="#"><i class="fa fa-list-alt"></i> <span>Reports</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="{{ URL::to('admin/consumer_billing_report') }}">Consumer Billing Report</a></li>
                <li><a href="{{ URL::to('admin/connection_report') }}">Connection Report</a></li>
                <li><a href="{{ URL::to('admin/corp_ward_pending_report') }}">Corp Wardwise Pending Report</a></li>
                <li><a href="{{ URL::to('admin/wardwise_dcb_report') }}">Wardwise DCB Report</a></li>
                <li><a href="{{ URL::to('admin/collection_report') }}">Collection Report</a></li>
            </ul>
        </li>
        @endif

   

        @if((strcasecmp($sub_category,"Administrator") == 0  && strcasecmp($category,"EXECUTIVE") != 0) || (strcasecmp($category,"Super Admin") == 0))

        <li><a href="{{ URL::to('admin/import_data') }}"><i class="fa fa-database"></i> <span>Import Data</span></a></li>
        @endif
        
        @if((strcasecmp($category,"Super Admin") == 0))
        <li><a href="{{ URL::to('admin/deletebill') }}"><i class="fa fa-database"></i> <span>Delete Data</span></a></li>
        @endif
        @if((strcasecmp($sub_category,"Administrator") == 0  && strcasecmp($category,"EXECUTIVE") != 0) || (strcasecmp($category,"Super Admin") == 0))
        <!--<li><a href="{{ URL::to('admin/test_payment_import') }}"><i class="fa fa-database"></i> <span>Test Payment Import</span></a></li>-->
        <br><br><br>
        @endif
	<li><a href=""><span><img src="{{ asset('dist/img/logo-sidebar.png') }}" width="85%"></span></a></li>
    </ul>
    <!-- /.sidebar-menu -->   
</section>
<!-- /.sidebar -->