@extends('adminlte::page')

@section('title', 'Kategori')

@section('content_header')
    <h1>Kategori</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        {{-- <li class="">User Management</li> --}}
        <li class="active">Kategori</li>
    </ol>
@stop
 

@section('content')

{{-- @include('flash::message') --}}
@permission('create-kategori')
<button type="button" class="btn btn-primary btn-lg open-modal">
    <i class="fa fa-plus-circle"></i> Add New Kategori
</button>
@endpermission
{{-- <a href="{{ URL::to('master/kategori/create') }}" class="btn btn-primary btn-lg" kategori="button" data-toggle="modal" data-target="#myModelDialog"><i class="fa fa-plus-circle"></i> Add New Kategori</a> --}}


<div class="row">&nbsp;</div>

{{-- @include('master::Kategori.form-search')   --}}

<div class="row">
    <div class="box">
        {{-- <div class="box-header with-border">
            <h3 class="box-title">Bordered Table</h3>
        </div> --}}
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered" id="table-kategori">
            <thead>
                <tr>
                    <th width="10%">No</th>
                    <th>Kategori</th>
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

var kategoriTable;
$(function() {
    kategoriTable = $('#table-kategori').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
                url:'{{ url("master/kategori/loaddata") }}',
                data: function (d) {
                    return $.extend( {}, d, {
                        "nama_kategori": $("#nama_kategori").val(),
                    } );
                }
        },
        columnDefs: [
            {"className": "dt-center", "targets": '_all'},
            // {"className": "dt-center", "targets": [0, 1, 2, 3, 4, 5, 6]},
            // {"className": "dt-right", "targets": [5]}

        ],
        columns: [
            {data: 'nomor', name: 'nomor', searchable: false},
            {data: 'nama', name: 'nama', orderable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        // bFilter : false,
    });
});

$('#filter-kategori-table').click(function(){
    kategoriTable.ajax.reload();
});

$('#reset-filter-kategori-table').click(function(event) {
    $("#nama_kategori").val(null);
    kategoriTable.ajax.reload();
});

// $('#btn-submit').click(function(){
//     var nama_kategori = $('#nama_kategori').val();
//     console.log(nama_kategori);
//     kategoriTable.ajax.reload();
// });

//$(".datepicker").datepicker({dateFormat: 'dd-mm-yy'});
$('#table-kategori').on('click','.hapus-kategori',function(event){
    //event.preventDefault();
    var id_kategori = $(this).attr('val');

    if(confirm("Anda yakin akan hapus kategori ini?")){
        //return true;
        
        var url = "{{url('master/kategori/delete')}}";
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: url,
            data:{
                'id_kategori': id_kategori,
            },
            success: function (response) {

                if(response == true){
                    toastr.success('Data kategori berhasil dihapus',"Success");
                    kategoriTable.ajax.reload();
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

$('#table-kategori').on('click','.edit-kategori',function(event){
    //event.preventDefault();
    var id_kategori = $(this).attr('val');
    var url = '{{ url("master/kategori/edit") }}';

    $('#myModalDialog').html('');

    $.ajax({
            url: url,
            data:{
            'ajax':1,
            'id_kategori':id_kategori,
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
    var url = '{{ url("master/kategori/create") }}';
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

