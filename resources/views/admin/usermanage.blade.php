@extends('layouts.admin_master')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Manage User
        <small>Optional description</small>
    </h1>
</section>
<!-- Main content -->
<section class="content container-fluid">
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">User Management</h3>
            <div class="pull-right">
                <button type="button" class="btn btn-danger addbtn" data-toggle="modal" type="button">Add User</button>
            </div>
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">  
                        <table id="mytable" class="table table-hover table-bordred table-striped">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Category</th>
                                    <th>Role</th>
                                    <th>Contact</th>
                                    <th>Status</th>
                                    <th>Edit</th>                                  
                                </tr>
                            </thead>                            
                        </table>
                        <div class="clearfix"></div>
                    </div>  
                </div>
                <div class="modal fade" id="add-user" tabindex="-1" role="dialog" aria-labelledby="add-user" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
                                <h4 class="modal-title custom_align" id="Heading">Add User Details</h4>
                            </div>
                            <div class="modal-body">
                                <div id="success-msg" class="hide">
                                    <div class="alert alert-info alert-dismissible fade in" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <strong>User Registered Successfully!!</strong> 
                                    </div>
                                </div>
                                <form class="form-horizontal" method="POST" id="Register">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="">Category</label>
                                        <div class="col-md-7 col-sm-7 col-xs-12">
                                            <select class="form-control estatus" id="category" name="category" placeholder="" aria-describedby="inputSuccess2Status3"> 
                                                <option value="">select</option>
                                                <option value="1">MCC</option>
                                                <option value="5">BANK</option>
                                                <option value="7">EXECUTIVE</option>
                                            </select>
                                            <span class="text-danger">
                                                <strong id="category-error"></strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                        <label for="name" class="control-label col-md-4 col-sm-4 col-xs-12">Username</label>

                                        <div class="col-md-7 col-sm-7 col-xs-12">
                                            <input id="username" type="text" class="form-control" name="name" value="{{ old('username') }}" required autofocus>

                                            <span class="text-danger">
                                                <strong id="name-error"></strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="">Password </label>
                                        <div class="col-md-7 col-sm-7 col-xs-12">
                                            <input type="password" id="password" pattern="[^\s]+"  title="please dont use the white space :)" name="password" required="required" placeholder="" class="form-control col-md-7 col-xs-12"> 
                                            <span class="text-danger">
                                                <strong id="password-error"></strong>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="last-name">Re-Enter Password </label>
                                        <div class="col-md-7 col-sm-7 col-xs-12">
                                            <input type="password" id="confirmpassword" name="password_confirmation" required="required" class="form-control col-md-7 col-xs-12"> 
                                            <span class="text-danger">
                                                <strong id="confirmpassword-error"></strong>
                                            </span>
                                        </div>
                                    </div>	

                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="">Email</label>
                                        <div class="col-md-7 col-sm-7 col-xs-12">
                                            <input type="email" id="email" name="email" class="form-control col-md-7 col-xs-12"> 
                                            <span class="text-danger">
                                                <strong id="email-error"></strong>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="">Contact Number</label>
                                        <div class="col-md-7 col-sm-7 col-xs-12">
                                            <input type="text" id="contact_no" name="contact_no" class="form-control col-md-7 col-xs-12"> 
                                            <span class="text-danger">
                                                <strong id="contactnumber-error"></strong>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="form-group bank_name">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="">Bank Name</label>
                                        <div class="col-md-7 col-sm-7 col-xs-12">
                                            <input type="text" id="bank_name" name="bank_name" class="form-control col-md-7 col-xs-12"> 
                                            <span class="text-danger">
                                                <strong id="bankname-error"></strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group bank_branch">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="">Bank Branch</label>
                                        <div class="col-md-7 col-sm-7 col-xs-12">
                                            <input type="text" id="bank_branch" name="bank_branch" class="form-control col-md-7 col-xs-12"> 
                                            <span class="text-danger">
                                                <strong id="bankbranch-error"></strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="">Designation</label>
                                        <div class="col-md-7 col-sm-7 col-xs-12"> 
                                           <!-- <input type="text" id="designation" name="designation" required="required" class="form-control col-md-7 col-xs-12">  -->
                                            <select class="form-control designation" id="single_cal5" placeholder="" name="designation" id="designation" aria-describedby="inputSuccess2Status3"> 
                                                
                                            </select> 
                                            <span class="text-danger">
                                                <strong id="designation-error"></strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="">Role</label>
                                        <div class="col-md-7 col-sm-7 col-xs-12">
                                            <select class="form-control role" id="single_cal5" placeholder="" name="role" id="role" aria-describedby="inputSuccess2Status3"> 
                                                <option value="">select</option>
                                                @foreach($subCategory as $category)
                                                <option value="{{ $category->id }}">{{ $category->sub_category_name }}</option>                                                                           @endforeach
                                            </select> 
                                            <span class="text-danger">
                                                <strong id="role-error"></strong>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="">Status</label>
                                        <div class="col-md-7 col-sm-7 col-xs-12">
                                            <select class="form-control" id="single_cal5" placeholder="" name="status" id="status" aria-describedby="inputSuccess2Status3"> 
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select> 
                                            <span class="text-danger">
                                                <strong id="status-error"></strong>
                                            </span>
                                        </div>
                                    </div>	

                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-4">
                                            <button type="button" id="submitForm" class="btn btn-primary">
                                                Register
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                        <!-- /.modal-content --> 
                    </div>
                    <!-- /.modal-dialog --> 
                </div>
                <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
                                <h4 class="modal-title custom_align" id="Heading">Edit User Details</h4>
                            </div>
                            <div class="modal-body">
                                <div id="success-msg1" class="hide">
                                    <div class="alert alert-info alert-dismissible fade in" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <strong>User Updated Successfully!!</strong> 
                                    </div>
                                </div>
                                <form method="POST" id="editForm" data-parsley-validate="" class="form-horizontal form-label-left">
                                    <input type="hidden" id="ecatid" name="catid"> 
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="first-name">Username</label>
                                        <div class="col-md-7 col-sm-7 col-xs-12">
                                            <input type="text" id="ename" name="name" required="required" readonly class="form-control col-md-7 col-xs-12"> 
                                            <input type="hidden" id="euserid" name="userId"> 
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="">Email</label>
                                        <div class="col-md-7 col-sm-7 col-xs-12">
                                            <input type="email" id="eemail" name="email" class="form-control col-md-7 col-xs-12">
                                            <span class="text-danger">
                                                <strong id="eemail-error"></strong>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="">Contact Number</label>
                                        <div class="col-md-7 col-sm-7 col-xs-12">
                                            <input type="text" id="econtactnumber" name="contact_no" class="form-control col-md-7 col-xs-12">
                                            <span class="text-danger">
                                                <strong id="econtactnumber-error"></strong>
                                            </span>
                                        </div>
                                    </div>
                                   <div class="form-group ebank_name">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="">Bank Name</label>
                                        <div class="col-md-7 col-sm-7 col-xs-12">
                                            <input type="text" id="ebank_name" name="bank_name" class="form-control col-md-7 col-xs-12"> 
                                            <span class="text-danger">
                                                <strong id="ebankname-error"></strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group ebank_branch">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="">Bank Branch</label>
                                        <div class="col-md-7 col-sm-7 col-xs-12">
                                            <input type="text" id="ebank_branch" name="bank_branch" class="form-control col-md-7 col-xs-12"> 
                                            <span class="text-danger">
                                                <strong id="ebankbranch-error"></strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="">Designation</label>
                                        <div class="col-md-7 col-sm-7 col-xs-12">
                                            <select class="form-control edesignation" id="single_cal5" name="designation" id="edesignation" aria-describedby="inputSuccess2Status3">                                                 
                                            </select>
                                            <span class="text-danger">
                                                <strong id="edesignation-error"></strong>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="">Role</label>
                                        <div class="col-md-7 col-sm-7 col-xs-12">
                                            <select class="form-control erole" id="single_cal5"  name="role" id="erole" aria-describedby="inputSuccess2Status3"> 
                                                <option value="">select</option>
                                                @foreach($subCategory as $category)
                                                <option value="{{ $category->id }}">{{ $category->sub_category_name }}</option>

                                                @endforeach
                                            </select>
                                            <span class="text-danger">
                                                <strong id="erole-error"></strong>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="">Status</label>
                                        <div class="col-md-7 col-sm-7 col-xs-12">
                                            <select class="form-control estatus" id="single_cal5" name="status" placeholder="" aria-describedby="inputSuccess2Status3"> 
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select> 
                                        </div>
                                    </div>
                                    <div class="modal-footer ">
                                        <button type="button" id="updateForm" class="btn btn-default btn-lg" style="width: 100%;"><span class="glyphicon glyphicon-ok-sign"></span> Update</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                        <!-- /.modal-content --> 
                    </div>
                    <!-- /.modal-dialog --> 
                </div>

                <div class="modal modal-default fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
                                <h4 class="modal-title custom_align" id="Heading">Delete this entry</h4>
                            </div>

                            <div class="modal-body">
                                <div class="alert"><h4><span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to delete this Record?</h4></div>

                            </div>

                            <div class="modal-footer ">
                                <form name="delete_form" id="delete_form" method="POST" style="display: none;">                                 
                                    <input type="hidden" name="userid" id="deleteuserid">
                                </form>
                                <button type="button" class="btn btn-success" id="deleteConfirmBtn"><span class="glyphicon glyphicon-ok-sign"></span> Yes</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> No</button>

                            </div>

                        </div>
                        <!-- /.modal-content --> 
                    </div>
                    <!-- /.modal-dialog -->  
                </div>
            </div>
        </div>
        <!-- /.box -->
    </div>
</section>
<!-- /.content -->   

<script>var siteUrl = '<?php echo url('/'); ?>';</script>
<script>
    $(document).ready(function () {
        $('.bank_branch').hide();
        $('.bank_name').hide();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        loadDatatable();
    });

// function to load DataTable 
    function loadDatatable() {
        $(function () {
            dtTable = $('#mytable').DataTable({
                "processing": true,
                "language": {
                        "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                "serverSide": true,
                "destroy": true,
                "ajax": siteUrl + "/admin/getUsers",
                "columns": [
                    {"data": "name"},
                     {"data": "cat_id"},
                    {"data": "sub_category_name"},                   
                    {"data": "contact_no"},
                    {"data": "status"},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        });
    }

$('input:password').keypress(function( e ) {
    if(e.which === 32) 
        return false;
});
//Register User
    $('body').on('click', '#submitForm', function () {
        var registerForm = $("#Register");
        var formData = registerForm.serialize();
        $('#name-error').html("");
        $('#password-error').html("");
        $('#confirmpassword-error').html("");
        $('#email-error').html("");
        $('#contactnumber-error').html("");
        $('#category-error').html("");
        $('#role-error').html("");
        $('#status-error').html("");
        $('#designation-error').html("");
        $('#bankname-error').html("");
        $('#bankbranch-error').html("");
        $.ajax({
            url: siteUrl + "/admin/register",
            type: 'POST',
            data: formData,
            async: true,
            success: function (data) {
                console.log(data);
                if (data.errors) {
                    if (data.errors.name) {
                        $('#name-error').html(data.errors.name[0]);
                    }
                    if (data.errors.email) {
                        $('#email-error').html(data.errors.email[0]);
                    }
                    if (data.errors.password) {
                        $('#password-error').html(data.errors.password[0]);
                    }
                    if (data.errors.contact_no) {
                        $('#contactnumber-error').html(data.errors.contact_no[0]);
                    }
                    if (data.errors.category) {
                        $('#category-error').html(data.errors.category[0]);
                    }
                    if (data.errors.role) {
                        $('#role-error').html(data.errors.role[0]);
                    }
                    if (data.errors.status) {
                        $('#status-error').html(data.errors.status[0]);
                    }
                    if (data.errors.designation) {
                        $('#designation-error').html(data.errors.designation[0]);
                    }
                    if (data.errors.bank_name) {
                        $('#bankname-error').html(data.errors.bank_name[0]);
                    }
                    if (data.errors.bank_branch) {
                        $('#bankbranch-error').html(data.errors.bank_branch[0]);
                    }
                }
                if (data.success) {
                    $('#success-msg').removeClass('hide');
                    $("#Register")[0].reset();
                    setInterval(function () {
                        $('#success-msg').addClass('hide');
                    }, 3000);
                    // $('#add-user').modal('hide'); 
                    $('#add-user').modal('toggle');
                    loadDatatable();
                }
            },
        });
    });


//Edit User
    $('body').on('click', '#editBtn', function () {
        
        $("#editForm").trigger( "reset" );
        $('#eemail-error').html("");
        $('#econtactnumber-error').html("");
        $('#erole-error').html("");
        $('#edesignation-error').html("");
        $('#ebankname-error').html("");
        $('#ebankbranch-error').html("");
        $('.edesignation').prop('disabled', false);
        $(".erole option:contains('Administrator')").prop('disabled', false);
        $(".erole option:contains('Viewer')").prop('disabled', false);
        $('.ebank_name').hide();
        $('.ebank_branch').hide();
        var userId = $(this).data("value");
        var catId = $(this).data("catid");
        $.ajax({
            url: siteUrl + "/admin/getUserInfo",
            type: 'POST',
            data: {'userId': userId, catId: catId},
            // async: true,   
            success: function (data) {
                console.log(data);
                if (data.errors) {
                    alert('error');
                    //if (data.errors.name) {
                    //    $('#name-error').html(data.errors.name[0]);
                    // }            
                }
                if (data.success) {
                    if(data.user.cat_id == '5'){
                        $('.ebank_name').show();
                        $('.ebank_branch').show();
                        $('#ename').val(data.user.name);
                        $('#eemail').val(data.user.email);
                        $('#econtactnumber').val(data.user.contact_no);
                        $('#ebank_name').val(data.user.bank_name);
                        $('#ebank_branch').val(data.user.bank_branch);
                       // $('.edesignation').val(data.user.designation_id);
                       // $('.erole').val(data.user.sub_category_id);                      
                        
                        $('.edesignation').prop('disabled', 'disabled');
                        $(".edesignation").prop("selectedIndex",-1)
                        $(".erole option:contains('Administrator')").attr("disabled","disabled");
                        $(".erole option:contains('Viewer')").attr("disabled","disabled");
                        $(".erole option:contains('Editor')").attr("selected","selected");
                        $('.estatus').val(data.user.status);
                        $('#euserid').val(data.user.user_id);
                        $('#ecatid').val(catId);
                        $('#edit').modal('show');
                    }else{
                        var i = 0;
                        $('.edesignation').html('');
                        $('.edesignation')
                        .append($("<option></option>")
                        .attr("value",'')
                        .text("select"));                      
                        $(data.designation).each(function(){
                            $('.edesignation')
                            .append($("<option></option>")
                            .attr("value",data.designation[i].id)
                            .text(data.designation[i].designation));                         
                            i++;
                        })   
                        $('.ebank_name').hide();
                        $('.ebank_branch').hide();
                        $('#ename').val(data.user.name);
                        $('#eemail').val(data.user.email);
                        $('#econtactnumber').val(data.user.contact_no);
                        $('.edesignation').val(data.user.designation_id);
                        $('.erole').val(data.user.sub_category_id);
                        $('.estatus').val(data.user.status);
                        $('#euserid').val(data.user.user_id);
                        $('#ecatid').val(catId);
                        $('#edit').modal('show');
                    }
                }
            },
        });
    });

//Function to update user info
    $('body').on('click', '#updateForm', function () {
        var editForm = $("#editForm");
        var formData = editForm.serialize();
        $('#eemail-error').html("");
        $('#econtactnumber-error').html("");
        $('#erole-error').html("");
        $('#edesignation-error').html("");
        $('#ebankbranch-error').html("");
        $('#ebankname-error').html("");
        $.ajax({
            url: siteUrl + "/admin/updateUser",
            type: 'POST',
            data: formData,
            async: true,
            // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                if (data.errors) {
                    if (data.errors.email) {
                        $('#eemail-error').html(data.errors.email[0]);
                    }
                    if (data.errors.contact_no) {
                        $('#econtactnumber-error').html(data.errors.contact_no[0]);
                    }
                    if (data.errors.role) {
                        $('#erole-error').html(data.errors.role[0]);
                    }
                    if (data.errors.designation) {
                        $('#edesignation-error').html(data.errors.designation[0]);
                    }
                    if (data.errors.bank_branch) {
                        $('#ebankbranch-error').html(data.errors.bank_branch[0]);
                    }
                    if (data.errors.bank_name) {
                        $('#ebankname-error').html(data.errors.bank_name[0]);
                    }
                }
                if (data.success) {
                    $('#success-msg1').removeClass('hide');
                    setInterval(function () {
                        $('#success-msg1').addClass('hide');
                    }, 3000);
                    $('#edit').modal('toggle');
                    loadDatatable();
                }
            },
        });
    });
    
    $('body').on('change', '#category', function () {
        var val = $(this).val();
        if(val == '5'){
            $('.bank_branch').show();
            $('.bank_name').show();
            $("#bank_name").prop('required',true);
            $("#bank_branch").prop('required',true);
            $('.designation').prop('disabled', 'disabled');
            $(".role option:contains('Administrator')").attr("disabled","disabled");
            $(".role option:contains('Viewer')").attr("disabled","disabled");
            $(".role option:contains('Editor')").prop("selected","selected");        
        }else {
        $.ajax({
            url: siteUrl + "/admin/getUserDesignation",
            type: 'POST',
            data: {cat_id:val},
            async: true,
            // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                if (data.success == 'success') {
                    console.log(data.designation);
                    var i = 0;
                    $('.designation').html('');
                        $('.designation')
                        .append($("<option></option>")
                        .attr("value",'')
                        .text("select"));                      
                    $(data.designation).each(function(){
                        $('.designation')
                        .append($("<option></option>")
                        .attr("value",data.designation[i].id)
                        .text(data.designation[i].designation));                         
                        i++;
                    })                  
                }
                if (data.success == 'errors') {
                    $('.designation').html('');
                    $('.designation')
                        .append($("<option></option>")
                        .attr("value",'')
                        .text("No Designation available"));  
                }
            },
            });
            $('.bank_branch').hide();
            $('.bank_name').hide();
            $("#bank_name").prop('required',false);
            $("#bank_branch").prop('required',false);
            $('.designation').prop('disabled', false);
            $(".role option:contains('Administrator')").attr('disabled', false);
            $(".role option:contains('Viewer')").attr('disabled', false);
            $(".role option:first").prop('selected',true);
           // $(".role option:contains('')").attr("selected","selected");        
        }
    });

  
    $('body').on('click', '.addbtn', function () {       
        $("#Register").trigger( "reset" );
        $('#name-error').html("");
        $('#password-error').html("");
        $('#confirmpassword-error').html("");
        $('#email-error').html("");
        $('#contactnumber-error').html("");
        $('#category-error').html("");
        $('#role-error').html("");
        $('#status-error').html("");
        $('#designation-error').html("");
        $('#bankname-error').html("");
        $('#bankbranch-error').html("");
        $('.bank_branch').hide();
        $('.bank_name').hide();
        $('.designation').prop('disabled', false);
        $(".role option:contains('Administrator')").attr('disabled', false);
        $(".role option:contains('Viewer')").attr('disabled', false);
        $('#add-user').modal('show');
    });
    /* 
     //Confirm Delete
     $('#deleteConfirmBtn').on('click', function(){    
     var deleteForm = $("#delete_form");
     var formData = deleteForm.serialize();
     $.ajax({
     url: siteUrl + "/admin/deleteUser",
     type: 'POST',
     data: formData,
     async: true,      
     success: function (data) {            
     if (data.errors) {
     alert('Error Occured');
     }
     if (data.success) {
     $('#delete').modal('toggle'); 
     // setInterval(function () {
     //     $('#success-msg1').addClass('hide');                 
     
     //  }, 3000);
     loadDatatable();
     }
     },
     });    
     
     });
     
     */
</script>
@endsection