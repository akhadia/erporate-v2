@extends('layouts.modal')
@section('title', $title=(isset($produk->id)?'Edit':'Create').' Produk')

@section('sub-content')

    {{ Form::model($produk,array('route' => array((!$produk->exists)?'produk.store':'produk.update',$produk->id),
    'class'=>'form-horizontal','files' => 'true','id'=>'produk-form','method'=>(!$produk->exists)?'POST':'PUT')) }}

    <div class="modal-body">
            {{-- {{ Form::model(null,array('class'=>'form-horizontal','id'=>'produk-form','method'=>'POST','onsubmit'=>'return false')) }} --}}
            <div class="box-body">
            
                <div class="form-group ">
                    <label for="inputKategori" class="col-sm-2 control-label">Kategori</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="kategori" name="kategori">
                            @foreach($kategori as $key=>$value)
                                <?php
                                    $selected='';
                                    if(isset($produk) && !empty($produk)){
                                        if($produk->id_kategori == $key){
                                            $selected='selected';
                                        }
                                    }
                                ?>
                                <option  value="{{$key}}" {{$selected}}>{{$value}}</option>
                            @endforeach
                        </select>
                    </div>  
                </div>
                <div class="form-group">
                    <label for="inputNama" class="col-sm-2 control-label">Nama</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nama_prod" name="nama_prod" value="{{(isset($produk->id))?$produk->nama:''}}"  placeholder="Nama">
                    </div>
                </div>   
                <div class="form-group">
                    <label for="inputHarga" class="col-sm-2 control-label">Harga</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="harga_prod" name="harga_prod" value="{{(isset($produk->id))?$produk->harga:''}}"  placeholder="Harga">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputDeskripsi" class="col-sm-2 control-label">Deskripsi</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="3" id="deskripsi_prod" name="deskripsi_prod" placeholder="Deskripsi">{{(isset($produk->id))?$produk->deskripsi:''}}</textarea>  
                    </div>
                </div>
                <?php
                    $display_none = "display:none";
                    if($produk->id && !empty($produk)){
                        $display_none = "";
                    }
                ?>
                <div class="form-group" style="{{$display_none}}">
                    <label for="inputStatus" class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-10">
                    <div class="radio">
                        <label>
                        <input type="radio" name="status" id="status" value="Y" {{($produk->status == 'Y') ? "checked" : '' }}>
                        Ready
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                        <input type="radio" name="status" id="status" value="N" {{($produk->status == 'N') ? "checked" : '' }}>
                        Unready
                        </label>
                    </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputDeskripsi" class="col-sm-2 control-label">Gambar</label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <label class="input-group-btn">
                                <span class="btn btn-info">
                                    Browse <input type="file" name="image_prod" style="display: none;" multiple="">
                                </span>
                            </label>
                            <input id="text_file" type="text" class="form-control" readonly="">
                        </div>
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

$('#produk-form').validate({ // initialize the plugin
    rules: {
        kategori: {
            required: true
        },
        nama_prod: {
            required: true
        },
        harga_prod: {
            required: true,
            number: true
        },
    }
});

$(document).on('change', ':file', function() {
    var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [numFiles, label]);
});

$(document).ready( function() {
    $(':file').on('fileselect', function(event, numFiles, label) {
        console.log(numFiles);
        console.log(label);
        $('#text_file').val(label);
    });
});

$('#modal-btn-submit').click(function(){
    // var nama_produk = $('#nama_produk').val();
    
    // $('#myModalDialog').modal('toggle');
    
    // window.location.reload();
});

</script>
@endpush
