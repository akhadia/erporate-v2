@extends('adminlte::page')

@section('title', 'User')

@section('content_header')
    <h1>User</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="">User Management</li>
        <li class=""><a href="{{ URL::to('user') }}" >User</a></li>
        <li class="active">{{isset($user->id)?'Edit':'Create'}} User</li>
    </ol>
@stop

@section('content')
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">{{isset($user->id)?'Edit':'Create'}} User</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    {{ Form::model($user,array('route' => array((!$user->exists)?'user.store':'user.update',$user->id),
    'class'=>'form-horizontal','id'=>'user-form','method'=>(!$user->exists)?'POST':'PUT')) }}

        <div class="box-body">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name" class="col-sm-4 control-label">Name</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="name" name="name" value="{{(isset($user->id))?$user->name:''}}"  placeholder="">
               
                    </div>
                </div>

                <div class="form-group">
                    <label for="username" class="col-sm-4 control-label">Username</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="username" name="username" value="{{(isset($user->id))?$user->username:''}}" autocomplete="off" placeholder="">
                        <input type="hidden" id="username_state" name="username_state" value="">
                        <span class="help-block"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="col-sm-4 control-label">Email</label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" id="email" name="email" value="{{(isset($user->id))?$user->email:''}}" placeholder="">
                        <span class="help-block"></span>
                    </div>
                </div>

                <?php
                    $display_none = "";
                    $change_pass = "display:none";
                    if(isset($user->id)){
                        $display_none = "display:none";
                        $change_pass = "";
                    }
                ?>

                <div class="form-group" style="{{$change_pass}}">
                    <label for="email" class="col-sm-4 control-label">Change Password</label>
                    <div class="col-sm-8 checkbox">
                        <label>
                            <input id="changePwd" type="checkbox">
                        </label> 
                    </div>
                </div>

                <div id="userPassword" style="{{$display_none}}">
                    <div class="form-group" >
                        <label for="password" class="col-sm-4 control-label">Password</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" id="password" name="password" value="" placeholder="">
                        </div>
                    </div>

                    <div class="form-group" >
                        <label for="c_password" class="col-sm-4 control-label">Confirm Password</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" id="c_password" name="c_password" value="" placeholder="">
                        </div>
                    </div>
                </div>
                
                <!-- checkbox -->
                <div class="form-group">
                    <label for="status" class="col-sm-4 control-label">Role</label>
                    <div class="col-sm-8">
                    @foreach ($role as $val)
                        <?php
                            $checked = "";
                            if(isset($role_user) && !empty($role_user)){
                                foreach($role_user as $val2){
                                    if($val->id == $val2->role_id){
                                        $checked = "checked";
                                    }
                                }
                            }
                         
                        ?>
                        <div class="checkbox">
                            <label>
                            <input {{$checked}} type="checkbox" name="role[]" value="{{$val->id}}">
                                {{$val->name}}
                            </label>
                        </div>
                    @endforeach
                    </div>
                </div>
               
            </div>
        </div>
        <div class="row">&nbsp;</div>
        <!-- /.box-body -->
        <div class="box-footer">
            <div class="col-md-12">
                {{-- <button type="submit" class="btn btn-default">Cancel</button> --}}
                <button id="btn_submit" class="btn btn-info pull-right">Submit</button>
            </div>
        </div>
        <!-- /.box-footer -->
    {{ Form::close() }}
</div>
 
@stop

@push('js')
<script type="text/javascript">
$(document).ready(function() {
    var error=0;
    var username_state;
    var email_state;

    //remove style span & text
    $('#username').on('keyup', function(){
        var text_span = $('#username').siblings("span").text().replace(/\s+/g, '').length;
        if(text_span > 0 ){
            $('#username').parent().parent().removeClass("has-success");
            $('#username').parent().parent().removeClass("has-warning");
            $('#username').siblings("span").text('');
        }
    });

    //remove style span & text
    $('#email').on('keyup', function(){
        var text_span = $('#email').siblings("span").text().replace(/\s+/g, '').length;
        if(text_span > 0 ){
            $('#email').parent().parent().removeClass("has-success");
            $('#email').parent().parent().removeClass("has-warning");
            $('#email').siblings("span").text('');
        }
    });

    //cek apakah username sudah digunakan
    $('#username').on('blur', function(){
        var username = $('#username').val().replace(/\s+/g, '').length;
        if(username >= 5){
            cekUsername();
            username_state = $('#username_state').val();
        }

        if(username_state == 'taken'){
            error++;
        }
    });

    //cek apakah email sudah digunakan
    $('#email').on('blur', function(){
        var email = $('#email').val();
        var status = validateEmail(email);

        if(status == true){
            email_state = cekEmail();
        }

        if(email_state == false){
            error++;
        }
    });

    //validasi error
    $('#btn_submit').on('click', function(){
        if(error > 0){
            return false;
        }else{
            return true;
        }
   
    });

    $('#user-form').validate({ // initialize the plugin
        rules: {
            name: {
                required: true,
                minlength: 5
            },
            username: {
                  required: true,
                  minlength: 5,
                  
            },
            email: {
                  required: true,
                  email: true
            },
            password: {
                  required: true,
                  minlength: 4
            },
            c_password: {
                minlength: 4,
                equalTo: "#password"
            }
            
        }
    });

    


}); //=== /document.ready ====//

$("#changePwd" ).click(function () 
{
    if (this.checked) {
        $('#userPassword').fadeIn('slow');
    }else{
        $('#userPassword').fadeOut('slow');

    }
});

function cekUsername(){
    var username = $('#username').val();
    var url = "{{url('user/cekusername')}}";

    $.ajax({
        async:false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: url,
        data:{
            username : username,
        },
        success: function (response) {
            if (response == 'taken' ) {
                $('#username').parent().parent().addClass("has-warning");
                $('#username').siblings("span").text('Sorry... Username already taken');
                $('#username_state').val(response);
                
            }else if (response == 'not_taken') {
                $('#username').parent().parent().removeClass("has-warning");
                $('#username').parent().parent().addClass("has-success");
                $('#username').siblings("span").text('Username available');
                $('#username_state').val(response);
        
            }
        },
        error: function (xhr, err) {
            // console.log("readyState: " + xhr.readyState + "\nstatus: " + xhr.status);
            // console.log("responseText: " + xhr.responseText);
        }
    });

    // return username_state;

}



function validateEmail(sEmail){
    var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    if (filter.test(sEmail)) {
        return true;
    }
    else {
        return false;
    }
}

function cekEmail(){

    var email = $('#email').val();
    var url = "{{url('user/cekemail')}}";

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: url,
        data:{
            email : email,
        },
        success: function (response) {
            var email_state = false;

            if (response == 'taken' ) {
                $('#email').parent().parent().addClass("has-warning");
                $('#email').siblings("span").text('Sorry... Email already taken');

            }else if (response == 'not_taken') {
                email_state = true;
                $('#email').parent().parent().removeClass("has-warning");
                $('#email').parent().parent().addClass("has-success");
                $('#email').siblings("span").text('Username available');
        
            }

            return email_state;
        },
        error: function (xhr, err) {
            // console.log("readyState: " + xhr.readyState + "\nstatus: " + xhr.status);
            // console.log("responseText: " + xhr.responseText);
        }
    });

}

</script>
@endpush