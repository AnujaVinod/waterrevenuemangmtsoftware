@extends('layouts.admin_master')
@section('content')
<section class="content-header">
    <h1>
        Change Password
    </h1>
</section>
<!-- Main content -->
<section class="content container-fluid">
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Change password</h3>
            <!-- /.box-tools -->
        </div>
        <div class="box-body">
            @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    {{ $error }}
                    @endforeach
                </ul>
            </div>
            @endif
            <form name="changepassword" id="changepassword" method="POST" action="{{ url('/admin/resetpassword') }}">
                {{ csrf_field() }}
                <div class="col-md-3 form-group">
                    <b>Current Password</b><span class="rq">*</span>
                    <input type="password" class="form-control" name="current_password" required  placeholder="Current Password">
                </div>

                <div class="col-md-3 form-group">
                    <b>New Password</b><span class="rq">*</span>
                    <input type="password" class="form-control" id="" name="new_password" required placeholder="New Password">
                </div>
                <div class="col-md-3 form-group">
                    <b>Confirm Password</b><span class="rq">*</span>
                    <input type="password" class="form-control" id="" name="confirm_password" required placeholder="Confirm Password">
                </div>
                <div class="col-md-3 form-group">
                    <button type="submit" class="btn btn-danger btn-flat btn-margin pull-left">Update Password</button>
                </div>
            </form>
        </div>
    </div>
</section>
<script>
$('input:password').keypress(function( e ) {
    if(e.which === 32) 
        return false;
});
</script>
@endsection