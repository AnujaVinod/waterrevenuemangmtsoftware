@extends('layouts.admin_master')
@section('content')
<section class="content-header">
  <h1>
    Import
  </h1>
</section>

<!-- Main content -->
<section class="content container-fluid">
          
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                    {{ session('data') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            
            @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li> {{ $error }} </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
    <div class="box box-info">
        <div class="box-header with-border">
                <h3 class="box-title">Import Consumer Info</h3>
                        <!-- /.box-tools -->
        </div>
        <div class="box-body">
          
            <form method="POST" enctype="multipart/form-data" action="{{ URL::to('admin\import-consumer-info') }}">
                    {{ csrf_field() }}
                <div class="row">
                   <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <div class="col-md-9">                                          
                                <input type="file" class="form-control" id="consumer_file" name="consumer_file" required="required">
                                <span class="text-danger">
                                    <strong id="consumer_file-error"></strong>
                                </span>
                                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                    <input type="submit">
                                </div>
                            </div>                                        
                        </div>
                    </div>                                
                </div>
            </form>
            <div class="col-xs-12 col-sm-12 col-md-12" align="right">
                <a href="#"><button class="btn btn-success">Download Excel xls</button></a>
                <a href="#"><button class="btn btn-success">Download Excel xlsx</button></a>
                <a href="#"><button class="btn btn-success">Download CSV</button></a>
            </div>
        </div>
    </div>
    <!--<div class="box box-info">
        <div class="box-header with-border">
                <h3 class="box-title">Import Reading Info</h3>
                        
        </div>
        <div class="box-body">            
            <form method="POST" enctype="multipart/form-data" action="{{ URL::to('admin\import-reading-info') }}">
                    {{ csrf_field() }}
                <div class="row">
                   <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <div class="col-md-9">                                          
                                <input type="file" class="form-control" id="reading_file" name="reading_file" required="required">
                                <span class="text-danger">
                                    <strong id="consumer_file-error"></strong>
                                </span>
                                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                    <input type="submit">
                                </div>
                            </div>                                        
                        </div>
                    </div>                                
                </div>
            </form>
            <div class="col-xs-12 col-sm-12 col-md-12" align="right">
                <a href="{{ URL::to('admin/downloadExcel/xls') }}"><button class="btn btn-success">Download Excel xls</button></a>
                <a href="{{ URL::to('admin/downloadExcel/xlsx') }}"><button class="btn btn-success">Download Excel xlsx</button></a>
                <a href="{{ URL::to('admin/downloadExcel/csv') }}"><button class="btn btn-success">Download CSV</button></a>
            </div>
        </div>
    </div> -->
                        
    <div class="box box-info">
        <div class="box-header with-border">
                <h3 class="box-title">Import Payment Info</h3>
                        <!-- /.box-tools -->
        </div>
        <div class="box-body">            
            <form method="POST" enctype="multipart/form-data" action="{{ URL::to('admin\import-payment-info') }}">
                    {{ csrf_field() }}
                <div class="row">
                   <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <div class="col-md-9">                                          
                                <input type="file" class="form-control" id="payment_file" name="payment_file" required="required">
                                <span class="text-danger">
                                    <strong id="consumer_file-error"></strong>
                                </span>
                                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                    <input type="submit">
                                </div>
                            </div>                                        
                        </div>
                    </div>                                
                </div>
            </form>            
        </div>
    </div>

    <!--<div class="box box-info">
        <div class="box-header with-border">
                <h3 class="box-title">Import Ward Info</h3>
                  
        </div>
        <div class="box-body">            
            <form method="POST" enctype="multipart/form-data" action="{{ URL::to('admin\import-ward-info') }}">
                    {{ csrf_field() }}
                <div class="row">
                   <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <div class="col-md-9">                                          
                                <input type="file" class="form-control" id="ward_file" name="ward_file" required="required">
                                <span class="text-danger">
                                    <strong id="consumer_file-error"></strong>
                                </span>
                                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                    <input type="submit">
                                </div>
                            </div>                                        
                        </div>
                    </div>                                
                </div>
            </form>            
        </div>
    </div> -->
    <hr/>
 <!--   <h2>Old Data Import</h2>
   <div class="box box-info">
        <div class="box-header with-border">
                <h3 class="box-title">Import Old Reading Info</h3>
                       
        </div>
        <div class="box-body">            
            <form method="POST" enctype="multipart/form-data" action="{{ URL::to('admin\import-old-reading-info') }}">
                    {{ csrf_field() }}
                <div class="row">
                   <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <div class="col-md-9">                                          
                                <input type="file" class="form-control" id="reading_old_file" name="reading_old_file" required="required">
                                <span class="text-danger">
                                    <strong id="consumer_file-error"></strong>
                                </span>
                                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                    <input type="submit">
                                </div>
                            </div>                                        
                        </div>
                    </div>                                
                </div>
            </form>
        </div>
    </div>
    
   <div class="box box-info">
        <div class="box-header with-border">
                <h3 class="box-title">Import Old Payment Info</h3>
                        
        </div>
        <div class="box-body">            
            <form method="POST" enctype="multipart/form-data" action="{{ URL::to('admin\import-oldpayment-info') }}">
                    {{ csrf_field() }}
                <div class="row">
                   <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <div class="col-md-9">                                          
                                <input type="file" class="form-control" id="payment_file" name="payment_file" required="required">
                                <span class="text-danger">
                                    <strong id="consumer_file-error"></strong>
                                </span>
                                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                    <input type="submit">
                                </div>
                            </div>                                        
                        </div>
                    </div>                                
                </div>
            </form>
        </div>
    </div> -->
</section>
<script>
   function validateFile(id) {
        var allowedFiles = [".xlsx", ".xls", ".csv"];
        var fileUpload = $('#'+id);
        $('#'+id+'-error').html('');
        var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
        if (!regex.test(fileUpload.val().toLowerCase())) {
            //lblError.html("Please upload files having extensions: <b>" + allowedFiles.join(', ') + "</b> only.");
            $('#'+id+'-error').html('Please select a valid file format');
            document.getElementById(id).value = '';
            return false;
        }
       // lblError.html('');
        return true;
}
</script>
@endsection