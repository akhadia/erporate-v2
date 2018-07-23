@extends('adminlte::page')

@section('title', 'User')

@section('content_header')
    <h1>User</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="">User Management</li>
        <li class="active">Users</li>
    </ol>
@stop
 

@section('content')

@include('flash::message')

@permission('create-acl')
    <a href="{{ URL::to('user/create') }}" class="btn btn-primary btn-lg" user="button"><i class="fa fa-plus-circle"></i> Add New User</a>
@endpermission

<div class="row">&nbsp;</div>

@include('User.form-search')  

<div class="row">
    <div class="box">
        {{-- <div class="box-header with-border">
            <h3 class="box-title">Bordered Table</h3>
        </div> --}}
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered" id="table-user">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Email</th>
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

var userTable;
$(function() {
    userTable = $('#table-user').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
                url:'{{ url("user/loaddata") }}',
                data: function (d) {
                    return $.extend( {}, d, {
                        "nama_user": $("#nama_user").val(),
                        "username" : $("#username").val(),
                        "email"    : $("#email").val(),
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
            {data: 'username', name: 'username', orderable: false},
            {data: 'name', name: 'name', orderable: false},
            {data: 'email', name: 'email', orderable: false},
            //{data: 'status', name: 'status', orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        bFilter : false,
    });
});

$('#filter-user-table').click(function(){
    userTable.ajax.reload();
});

 $('#reset-filter-user-table').click(function(event) {
    $("#nama_user").val(null);
    $("#username").val(null),
    $("#email").val(null),
    // $("#status").val('all');
    userTable.ajax.reload();
});

//$(".datepicker").datepicker({dateFormat: 'dd-mm-yy'});
$('#table-user').on('click','.hapus-user',function(event){
    //event.preventDefault();
    var id_user = $(this).attr('val');

    if(confirm("Anda yakin akan hapus user ini?")){
        //return true;
        
        var url = "{{url('user/delete')}}";
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: url,
            data:{
                'id_user': id_user,
            },
            success: function (response) {
                if(response == true){
                    toastr.success('Data user berhasil dihapus',"Success");
                    userTable.ajax.reload();
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



//== /document.ready ===//
});

</script>
@endpush

