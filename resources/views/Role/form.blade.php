@extends('adminlte::page')

@section('title', 'Role')

@section('content_header')
    <h1>Role</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="">User Management</li>
        <li class=""><a href="{{ URL::to('role') }}" >Role</a></li>
        <li class="active">{{isset($role->id)?'Edit':'Create'}} Role</li>
    </ol>
@stop

@section('content')
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">{{isset($role->id)?'Edit':'Create'}} Role</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    {{ Form::model($role,array('route' => array((!$role->exists)?'role.store':'role.update',$role->id),
    'class'=>'form-horizontal','id'=>'role-form','method'=>(!$role->exists)?'POST':'PUT')) }}

        <div class="box-body">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nama_role" class="col-sm-3 control-label">Name</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control" id="nama_role" name="nama_role" value="{{(isset($role->id))?$role->name:''}}" placeholder="">
                    </div>
                </div>

                <div class="form-group">
                    <label for="display_name" class="col-sm-3 control-label">Display Name</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control" id="display_name" name="display_name" value="{{(isset($role->id))?$role->display_name:''}}" placeholder="">
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="col-sm-3 control-label">Description</label>

                    <div class="col-sm-9">
                        <textarea class="form-control" rows="4" id="description" name="description" placeholder="">{{(isset($role->id))?$role->description:''}}</textarea>
                    </div>
                </div>

                <!-- checkbox -->
                <div class="form-group">
                    <label for="status" class="col-sm-3 control-label">Permission</label>
                    <div class="col-sm-9">
                    @foreach ($permission as $val)
                        <?php
                            $checked = "";
                            if(isset($permission_role) && !empty($permission_role)){
                                foreach($permission_role as $val2){
                                    if($val->id == $val2->permission_id){
                                        $checked = "checked";
                                    }
                                }
                            }
                         
                        ?>
                        <div class="checkbox">
                            <label>
                            <input {{$checked}} type="checkbox" name="permission[]" value="{{$val->id}}">
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
                <button type="submit" class="btn btn-info pull-right">Submit</button>
            </div>
        </div>
        <!-- /.box-footer -->
    {{ Form::close() }}
</div>
 
@stop

@push('js')
<script type="text/javascript">
$(document).ready(function() {

    $('#role-form').validate({ // initialize the plugin
        rules: {
            nama_role: {
                required: true,
                minlength: 5
            },
        }
    });
}); //=== /document.ready ====//

</script>
@endpush

