@extends('adminlte::page')

@section('title', 'Permission')

@section('content_header')
    <h1>Permission</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="">User Management</li>
        <li class=""><a href="{{ URL::to('permission') }}" >Permission</a></li>
        <li class="active">{{isset($permission->id)?'Edit':'Create'}} Permission</li>
    </ol>
@stop

@section('content')
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">{{isset($permission->id)?'Edit':'Create'}} Permission</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    {{ Form::model($permission,array('route' => array((!$permission->exists)?'permission.store':'permission.update',$permission->id),
    'class'=>'form-horizontal','id'=>'permission-form','method'=>(!$permission->exists)?'POST':'PUT')) }}

        <div class="box-body">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nama_permission" class="col-sm-3 control-label">Name</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control" id="nama_permission" name="nama_permission" value="{{(isset($permission->id))?$permission->name:''}}" placeholder="">
                    </div>
                </div>

                <div class="form-group">
                    <label for="display_name" class="col-sm-3 control-label">Display Name</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control" id="display_name" name="display_name" value="{{(isset($permission->id))?$permission->display_name:''}}" placeholder="">
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="col-sm-3 control-label">Description</label>

                    <div class="col-sm-9">
                        <textarea class="form-control" rows="4" id="description" name="description" placeholder="">{{(isset($permission->id))?$permission->description:''}}</textarea>
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

    $('#permission-form').validate({ // initialize the plugin
        rules: {
            nama_permission: {
                required: true,
                minlength: 5
            },
        }
    });
}); //=== /document.ready ====//

</script>
@endpush

