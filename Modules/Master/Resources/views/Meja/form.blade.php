@extends('layouts.modal')
@section('title', $title=(isset($meja->id)?'Edit':'Create').' Meja')

@section('sub-content')

    {{ Form::model($meja,array('route' => array((!$meja->exists)?'meja.store':'meja.update',$meja->id),
    'class'=>'form-horizontal','id'=>'meja-form','method'=>(!$meja->exists)?'POST':'PUT')) }}

    <div class="modal-body">
            {{-- {{ Form::model(null,array('class'=>'form-horizontal','id'=>'meja-form','method'=>'POST','onsubmit'=>'return false')) }} --}}
            <div class="box-body">
                <div class="form-group">
                    <label for="inputMeja" class="col-sm-2 control-label">Meja</label>
    
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="no_meja" name="no_meja" value="{{(isset($meja->id))?$meja->no_meja:''}}"  placeholder="No Meja">
                    </div>
                </div>   
            </div> 
    </div>

    <div class="modal-footer">
        {{-- <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button> --}}
        <button type="submit" class="btn btn-primary" id="modal-btn-submit">Submit</button>
    </div>
    {{ Form::close() }}
@endsection

@push('modal-js')
<script type="text/javascript">

$('#meja-form').validate({ // initialize the plugin
    rules: {
        no_meja: {
            required: true,
            minlength: 2
        },
    }
});

$('#modal-btn-submit').click(function(){
    // var nama_meja = $('#nama_meja').val();
    
    // $('#myModalDialog').modal('toggle');
    
    // window.location.reload();
});

</script>
@endpush
