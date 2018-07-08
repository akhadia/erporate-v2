@extends('layouts.modal')
@section('title', $title=(isset($kategori->id)?'Edit':'Create').' Kategori')

@section('sub-content')

    {{ Form::model($kategori,array('route' => array((!$kategori->exists)?'kategori.store':'kategori.update',$kategori->id),
    'class'=>'form-horizontal','id'=>'kategori-form','method'=>(!$kategori->exists)?'POST':'PUT')) }}

    <div class="modal-body">
            {{-- {{ Form::model(null,array('class'=>'form-horizontal','id'=>'kategori-form','method'=>'POST','onsubmit'=>'return false')) }} --}}
            <div class="box-body">
                <div class="form-group">
                    <label for="inputKategori" class="col-sm-2 control-label">Kategori</label>
    
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" value="{{(isset($kategori->id))?$kategori->nama:''}}"  placeholder="Kategori">
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

$('#kategori-form').validate({ // initialize the plugin
    rules: {
        nama_kategori: {
            required: true,
            minlength: 2
        },
    }
});

$('#modal-btn-submit').click(function(){
    // var nama_kategori = $('#nama_kategori').val();
    
    // $('#myModalDialog').modal('toggle');
    
    // window.location.reload();
});

</script>
@endpush
