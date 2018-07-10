@extends('adminlte::page')

@section('title', 'Produk')

@section('content_header')
    <h1>Produk</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        {{-- <li class="">User Management</li> --}}
        <li class="active">Produk</li>
    </ol>
@stop
 

@section('content')

{{-- @include('flash::message') --}}
@permission('create-produk')
<button type="button" class="btn btn-primary btn-lg open-modal">
    <i class="fa fa-plus-circle"></i> Add New Produk
</button>
@endpermission
{{-- <a href="{{ URL::to('master/produk/create') }}" class="btn btn-primary btn-lg" produk="button" data-toggle="modal" data-target="#myModelDialog"><i class="fa fa-plus-circle"></i> Add New Produk</a> --}}


<div class="row">&nbsp;</div>

@include('master::Produk.form-search')  

<div class="row">
    <div class="box">
        {{-- <img src="{{ URL::to('/') }}/images/Screenshot from 2018-06-20 19-51-00.png">' --}}
        {{-- <div class="box-header with-border">
            <h3 class="box-title">Bordered Table</h3>
        </div> --}}
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered" id="table-produk">
            <thead>
                <tr>
                    <th width="10%">No</th>
                    <th>Gambar</th>
                    <th>Kategori</th>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Action</th>
                </tr>
            </thead>
            </table>
        </div>
    </div>
</div>
@stop

@push('js')
<script type="text/javascript">
$(document).ready(function() {

var produkTable;
$(function() {
    produkTable = $('#table-produk').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
                url:'{{ url("master/produk/loaddata") }}',
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
                render: function(data, type, row) {
                    if(data!=null && data!=''){
                        return "<img src="+data+" style='height:75px;width:75px;'/>";
                    }else{
                        return null;
                    }
                }
            },
            {data: 'kategori', name: 'kategori', orderable: false},
            {data: 'nama', name: 'nama', orderable: false},
            {data: 'harga', name: 'harga'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        bFilter : false,
    });
});

$('#filter-produk-table').click(function(){
    produkTable.ajax.reload();
});

$('#reset-filter-produk-table').click(function(event) {
    $("#nama_produk").val(null);
    produkTable.ajax.reload();
});

// $('#btn-submit').click(function(){
//     var nama_produk = $('#nama_produk').val();
//     console.log(nama_produk);
//     produkTable.ajax.reload();
// });

//$(".datepicker").datepicker({dateFormat: 'dd-mm-yy'});
$('#table-produk').on('click','.hapus-produk',function(event){
    //event.preventDefault();
    var id_produk = $(this).attr('val');

    if(confirm("Anda yakin akan hapus produk ini?")){
        //return true;
        
        var url = "{{url('master/produk/delete')}}";
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: url,
            data:{
                'id_produk': id_produk,
            },
            success: function (response) {

                if(response == true){
                    toastr.success('Data produk berhasil dihapus',"Success");
                    produkTable.ajax.reload();
                }
            },
            error: function (response) {
                //console.log('Error:', data);
            }
        });
         
    }else{
        return false;
    }
});

$('#table-produk').on('click','.edit-produk',function(event){
    //event.preventDefault();
    var id_produk = $(this).attr('val');
    var url = '{{ url("master/produk/edit") }}';

    $('#myModalDialog').html('');

    $.ajax({
            url: url,
            data:{
            'ajax':1,
            'id_produk':id_produk,
            },
            cache: false,
            dataType: 'html',
            success: function(data){
                $('#myModalDialog').html(data);
                $('#myModalDialog').modal();
            },
            error: function(){
                //$('#myModalDialog').html("request gagal dibuka");
                //$('#myModalDialog').modal('show');
                console.log('gagal');
            }
    });

    return true;
});


//== /document.ready ===//
});

$(document).on('click','.open-modal',function(){
    var url = '{{ url("master/produk/create") }}';
    $('#myModalDialog').html('');

    $.ajax({
            url: url,
            data:{
            'ajax':1,
            },
            cache: false,
            dataType: 'html',
            success: function(data){
                $('#myModalDialog').html(data);
                $('#myModalDialog').modal();
            },
            error: function(){
                //$('#myModalDialog').html("request gagal dibuka");
                //$('#myModalDialog').modal('show');
                console.log('gagal');
            }
    });

    return true;
});



</script>
@endpush

