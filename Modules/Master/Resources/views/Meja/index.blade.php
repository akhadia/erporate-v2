@extends('adminlte::page')

@section('title', 'Meja')

@section('content_header')
    <h1>Meja</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        {{-- <li class="">User Management</li> --}}
        <li class="active">Meja</li>
    </ol>
@stop
 

@section('content')

{{-- @include('flash::message') --}}
@permission('create-meja')
<button type="button" class="btn btn-primary btn-lg open-modal">
    <i class="fa fa-plus-circle"></i> Add New Meja
</button>
@endpermission
{{-- <a href="{{ URL::to('master/meja/create') }}" class="btn btn-primary btn-lg" meja="button" data-toggle="modal" data-target="#myModelDialog"><i class="fa fa-plus-circle"></i> Add New Meja</a> --}}


<div class="row">&nbsp;</div>

@include('master::Meja.form-search')  

<div class="row">
    <div class="box">
        {{-- <div class="box-header with-border">
            <h3 class="box-title">Bordered Table</h3>
        </div> --}}
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered" id="table-meja">
            <thead>
                <tr>
                    <th width="10%">No</th>
                    <th>Meja</th>
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

var mejaTable;
$(function() {
    mejaTable = $('#table-meja').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
                url:'{{ url("master/meja/loaddata") }}',
                data: function (d) {
                    return $.extend( {}, d, {
                        "no_meja": $("#no_meja").val(),
                    } );
                }
        },
        columns: [
            {data: 'nomor', name: 'nomor'},
            {data: 'no_meja', name: 'no_meja', orderable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        bFilter : false,
    });
});

$('#filter-meja-table').click(function(){
    mejaTable.ajax.reload();
});

$('#reset-filter-meja-table').click(function(event) {
    $("#no_meja").val(null);
    mejaTable.ajax.reload();
});

// $('#btn-submit').click(function(){
//     var nama_meja = $('#nama_meja').val();
//     console.log(nama_meja);
//     mejaTable.ajax.reload();
// });

//$(".datepicker").datepicker({dateFormat: 'dd-mm-yy'});
$('#table-meja').on('click','.hapus-meja',function(event){
    //event.preventDefault();
    var id_meja = $(this).attr('val');

    if(confirm("Anda yakin akan hapus meja ini?")){
        //return true;
        
        var url = "{{url('master/meja/delete')}}";
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: url,
            data:{
                'id_meja': id_meja,
            },
            success: function (response) {

                if(response == true){
                    toastr.success('Data meja berhasil dihapus',"Success");
                    mejaTable.ajax.reload();
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

$('#table-meja').on('click','.edit-meja',function(event){
    //event.preventDefault();
    var id_meja = $(this).attr('val');
    var url = '{{ url("master/meja/edit") }}';

    $('#myModalDialog').html('');

    $.ajax({
            url: url,
            data:{
            'ajax':1,
            'id_meja':id_meja,
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
    var url = '{{ url("master/meja/create") }}';
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

