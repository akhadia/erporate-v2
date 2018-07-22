@extends('adminlte::page')

@section('title', 'Pesanan')

@section('content_header')
    <h1>Pesanan</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        {{-- <li class="">User Management</li> --}}
        <li class="active">Pesanan</li>
    </ol>
@stop
 

@section('content')

{{-- @include('flash::message') --}}
@permission('create-pesanan')
{{-- <button type="button" class="btn btn-primary btn-lg open-modal">
    <i class="fa fa-plus-circle"></i> Add New Pesanan
</button> --}}
<a href="{{ URL::to('transaksi/pesanan/create') }}" class="btn btn-primary btn-lg" pesanan="button" ><i class="fa fa-plus-circle"></i> Add New Pesanan</a>
@endpermission


<div class="row">&nbsp;</div>

@include('transaksi::Pesanan.form-search')  

<div class="row">
    <div class="box">
        {{-- <img src="{{ URL::to('/') }}/images/Screenshot from 2018-06-20 19-51-00.png">' --}}
        {{-- <div class="box-header with-border">
            <h3 class="box-title">Bordered Table</h3>
        </div> --}}
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered" id="table-pesanan">
            <thead>
                <tr>
                    <th width="10%">No</th>
                    <th>No Pesanan</th>
                    <th>Meja</th>
                    <th>Tgl Pesanan</th>
                    <th>Status</th>
                    <th>Total</th>
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

var pesananTable;
$(function() {
    pesananTable = $('#table-pesanan').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
                url:'{{ url("transaksi/pesanan/loaddata") }}',
                data: function (d) {
                    return $.extend( {}, d, {
                        "no_pesanan"    : $("#no_pesanan").val(),
                        "status"        : $("#status").val(),
                        "date_from"     : $("#date_from").val(),
                        "date_to"       : $("#date_to").val(),
                    } );
                }
        },
        columnDefs: [
            {"className": "dt-center", "targets": '_all'},
            // {"className": "dt-center", "targets": [0, 1, 2, 3, 4, 5, 6]},
            // {"className": "dt-right", "targets": [5]}

        ],
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

$('#filter-pesanan-table').click(function(){
    pesananTable.ajax.reload();
});

$('#reset-filter-pesanan-table').click(function(event) {
    $("#no_pesanan").val(null);
    $("#date_from").val(null);
    $('#date_from').datepicker('setDate', null);
    $("#date_to").val(null);
    $('#date_to').datepicker('setDate', null);
    $("#status").val('All');
    pesananTable.ajax.reload();
});

// $('#btn-submit').click(function(){
//     var nama_pesanan = $('#nama_pesanan').val();
//     console.log(nama_pesanan);
//     pesananTable.ajax.reload();
// });

$(".datepicker").datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
});

$('#table-pesanan').on('click','.hapus-pesanan',function(event){
    //event.preventDefault();
    var id_pesanan = $(this).attr('val');

    if(confirm("Anda yakin akan hapus pesanan ini?")){
        //return true;
        
        var url = "{{url('master/pesanan/delete')}}";
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: url,
            data:{
                'id_pesanan': id_pesanan,
            },
            success: function (response) {

                if(response == true){
                    toastr.success('Data pesanan berhasil dihapus',"Success");
                    pesananTable.ajax.reload();
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

$('#table-pesanan').on('click','.pesanan-selesai',function(event){
    //event.preventDefault();
    var id_pesanan = $(this).attr('val');

    if(confirm("Anda yakin akan menutup pesanan ini?")){
        //return true;
        
        var url = "{{url('transaksi/pesanan/pesananselesai')}}";
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: url,
            data:{
                'id_pesanan': id_pesanan,
            },
            success: function (response) {

                if(response == true){
                    toastr.success('Pesanan telah selesai',"Success");
                    pesananTable.ajax.reload();
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

$('#table-pesanan').on('click','.edit-pesanan',function(event){
    //event.preventDefault();
    var id_pesanan = $(this).attr('val');
    var url = '{{ url("master/pesanan/edit") }}';

    $('#myModalDialog').html('');

    $.ajax({
            url: url,
            data:{
            'ajax':1,
            'id_pesanan':id_pesanan,
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
    var url = '{{ url("master/pesanan/create") }}';
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

