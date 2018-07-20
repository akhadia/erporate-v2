@extends('adminlte::page')

@section('title', 'Pembayaran')

@section('content_header')
    <h1>Pembayaran</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        {{-- <li class="">User Management</li> --}}
        <li class="active">Pembayaran</li>
    </ol>
@stop
 

@section('content')

{{-- @include('flash::message') --}}
{{-- @permission('create-pembayaran') --}}
{{-- <button type="button" class="btn btn-primary btn-lg open-modal">
    <i class="fa fa-plus-circle"></i> Add New Pembayaran
</button> --}}
{{-- <a href="{{ URL::to('transaksi/pembayaran/create') }}" class="btn btn-primary btn-lg" pembayaran="button" ><i class="fa fa-plus-circle"></i> Add New Pembayaran</a> --}}
{{-- @endpermission --}}


<div class="row">&nbsp;</div>

@include('transaksi::Pembayaran.form-search')  

<div class="row">
    <div class="box">
        {{-- <img src="{{ URL::to('/') }}/images/Screenshot from 2018-06-20 19-51-00.png">' --}}
        {{-- <div class="box-header with-border">
            <h3 class="box-title">Bordered Table</h3>
        </div> --}}
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered" id="table-pembayaran">
            <thead>
                <tr>
                    <th width="10%">No</th>
                    <th>No Pesanan</th>
                    <th>Meja</th>
                    <th>Tgl Pesanan</th>
                    <th>Status</th>
                    <th>Total Tagihan</th>
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

var pembayaranTable;
$(function() {
    pembayaranTable = $('#table-pembayaran').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
                url:'{{ url("transaksi/pembayaran/loaddata") }}',
                data: function (d) {
                    return $.extend( {}, d, {
                        "no_pesanan"    : $("#no_pesanan").val(),
                        "status"        : $("#status").val(),
                        "date_from"     : $("#date_from").val(),
                        "date_to"       : $("#date_to").val(),
                    } );
                }
        },
        columns: [
            {data: 'nomor', name: 'nomor'},
            {data: 'no_pesanan', name: 'no_pesanan', orderable: false},
            {data: 'meja', name: 'meja', orderable: false},
            {data: 'tgl_pesanan', name: 'tgl_pesanan', orderable: false},
            {data: 'status', name: 'status', orderable: false},
            {data: 'total', name: 'total'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        bFilter : false,
    });
});

$('#filter-pembayaran-table').click(function(){
    pembayaranTable.ajax.reload();
});

$('#reset-filter-pembayaran-table').click(function(event) {
    $("#no_pesanan").val(null);
    $("#date_from").val(null);
    $('#date_from').datepicker('setDate', null);
    $("#date_to").val(null);
    $('#date_to').datepicker('setDate', null);
    $("#status").val('All');
    pembayaranTable.ajax.reload();
});

// $('#btn-submit').click(function(){
//     var nama_pembayaran = $('#nama_pembayaran').val();
//     console.log(nama_pembayaran);
//     pembayaranTable.ajax.reload();
// });

$(".datepicker").datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
});

$('#table-pembayaran').on('click','.hapus-pembayaran',function(event){
    //event.preventDefault();
    var id_pembayaran = $(this).attr('val');

    if(confirm("Anda yakin akan hapus pembayaran ini?")){
        //return true;
        
        var url = "{{url('master/pembayaran/delete')}}";
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: url,
            data:{
                'id_pembayaran': id_pembayaran,
            },
            success: function (response) {

                if(response == true){
                    toastr.success('Data pembayaran berhasil dihapus',"Success");
                    pembayaranTable.ajax.reload();
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

$('#table-pembayaran').on('click','.pembayaran-selesai',function(event){
    //event.preventDefault();
    var id_pembayaran = $(this).attr('val');

    if(confirm("Anda yakin akan menutup pembayaran ini?")){
        //return true;
        
        var url = "{{url('transaksi/pembayaran/pembayaranselesai')}}";
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: url,
            data:{
                'id_pembayaran': id_pembayaran,
            },
            success: function (response) {

                if(response == true){
                    toastr.success('Pembayaran telah selesai',"Success");
                    pembayaranTable.ajax.reload();
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

$('#table-pembayaran').on('click','.edit-pembayaran',function(event){
    //event.preventDefault();
    var id_pembayaran = $(this).attr('val');
    var url = '{{ url("master/pembayaran/edit") }}';

    $('#myModalDialog').html('');

    $.ajax({
            url: url,
            data:{
            'ajax':1,
            'id_pembayaran':id_pembayaran,
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
    var url = '{{ url("master/pembayaran/create") }}';
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

