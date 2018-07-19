
{{-- @push('css')
<style type="text/css"> 
    table.dataTable thead th {
        border-bottom: 0;
    }
    table.dataTable.no-footer {
        border-bottom: 0;
    }
</style>
@endpush --}}

@extends('layouts.modal')
{{-- @section('title', $title=(isset($pesanan->id)?'Edit':'Create').' Pesanan') --}}
@section('title', $title='Cari Produk')

@section('sub-content')

    <div style="display:none" class="row">
        <div class="col-md-12">
            {{ Form::model(null,array('class'=>'form-inline form-bordered','id'=>'produk-form','method'=>'POST','onsubmit'=>'return false')) }}

                <input type="hidden" class="form-control" id="nomer" name="nomer" value="{{$nomer}}">
                <br />
                <div class="form-group">
                        {{-- <label for="bahan" class="col-sm-2 control-label">Bahan</label> --}}
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nama_produk" name="nama_produk" value="" placeholder="Nama produk...">
                    </div>
                </div>
                <button class="btn btn-success" onclick="" id="filter-produk-popup-table">Search</button>

            {{ Form::close() }}
        </div>
    </div>

    <div class="modal-body">
        <div class="table-responsive">
                <table width="100%" class="table table-bordered" id="produk-popup-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Produk</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                </table>
        </div>
    </div>

@endsection

@push('modal-js')
<script type="text/javascript">

var produkTable;
$(function() {
    produkTable = $('#produk-popup-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
                url:'{{ url("master/produk/loaddatapopup") }}',
                data: function (d) {
                    return $.extend( {}, d, {
                        "nama_produk": $("#nama_produk").val(),
                    } );
                }
        },
        columns: [
            {data: 'nomor', name: 'nomor'},
            {
                data: "gambar",
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    if(data!=null && data!=''){
                        return "<img src="+data+" style='height:75px;width:75px;'/>";
                    }else{
                        return null;
                    }
                }
            },
            {data: 'nama', name: 'nama', orderable: false},
            {data: 'status', name: 'status', orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        bLengthChange: false,
        // bFilter : false,
        bInfo : false,
    });
});

$('#filter-produk-popup-table').click(function(){
    produkTable.ajax.reload();
});

//$(".datepicker").datepicker({dateFormat: 'dd-mm-yy'});

$("#produk-popup-table").delegate('.select-produk-from-popup', 'click', function(event) {
    $.getJSON('{{ url('master/produk/getproduk') }}/'+$(this).data("id"), {}, function(json, textStatus) {
        $(json).each(function(idx,vl){
            var no = $("#nomer").val();
            var img;
            var url = 'http://'+window.location.host + '/images/' + vl.image;

            $("#isi_tabel_produk").find('#id_produk_'+no).val(vl.id);
            $("#isi_tabel_produk").find('#produk_nama_'+no).val(vl.nama);
            $("#isi_tabel_produk").find('#old_produk_nama_'+no).val(vl.nama);

            $("#isi_tabel_produk").find('#produk_qty_'+no).val(0);

            $("#isi_tabel_produk").find('#produk_harga_'+no).val(vl.harga);
            $("#isi_tabel_produk").find('#produk_harga_text_'+no).text(vl.harga);


            $("#isi_tabel_produk").find('#produk_gambar_place_'+no).children().remove();
            
            img = jQuery('<img style="height:75px;width:75px;">');
            img.attr('src', url );
            // jQuery('#data_produk_ke-'+no+' .image_place').append(img);
            jQuery('#isi_tabel_produk').find('#produk_gambar_place_'+no).append(img);

            subtotal_count(no);
		    all_total_count();

        });

        $("#close-popup").click();
    });
});

</script>
@endpush