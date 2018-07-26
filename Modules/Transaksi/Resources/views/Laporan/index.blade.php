@extends('adminlte::page')

@section('title', 'Laporan')

@section('content_header')
    <h1>Laporan</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        {{-- <li class="">User Management</li> --}}
        <li class="active">Laporan</li>
    </ol>
@stop
 

@section('content')

@include('transaksi::Laporan.form-search')  

<div class="row">
    <div class="box">
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered" id="table-pesanan">
                <thead>
                    <tr>
                        <th width="10%">No</th>
                        <th>No Pesanan</th>
                        <th>Meja</th>
                        <th>Tgl Pesanan</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody id="isi_tabel_pesanan">
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-md-12">
        <div class="btn-toolbar">
            <button style="display:none" id="btnPrint" class="btn btn-medium btn-primary" ><i class="glyphicon glyphicon-print"></i> Cetak</button> 
        </div>
    </div>
</div>

@stop

@push('js')
<script type="text/javascript">
$(document).ready(function() {

    $('#btnSubmit').click(function(){
        var date_from = $('#date_from').val();
        var date_to = $('#date_to').val();
        var status = 'display';
    
        //console.log(date_from);
        $('#isi_tabel_pesanan').children("tr").remove();
        $.ajax({
                // headers: {
                //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                // },
                url: "{{url('transaksi/laporan/createlaporanpesanan')}}",
                type: 'get', //this is your method
                data: { 
                    'date_from' : date_from, 
                    'date_to'   : date_to, 
                    'status'    : status,
                },
                dataType: 'json',
                success: function(data){
                
                    var no=0;
                    $.each(data, function(key, value){
                        no++;
                        var newRow = '<tr>\n\
                                        <td class="text-center">'+no+'</td>\n\
                                        <td class="text-center">'+value.no_pesanan+'</td>\n\
                                        <td class="text-center">'+value.no_meja+'</td>\n\
                                        <td class="text-center">'+convertDate(value.tgl_pesanan)+'</td>\n\
                                        <td class="text-center">'+value.total+'</td>\n\
                                    </tr>';

                        $('#isi_tabel_pesanan').append(newRow);
                    
                    });

                    //var tombol_cetak = document.getElementById('btnPrint');
                    //tombol_cetak.style.display = null;
                    // $("#btn-print").css('display','');
                    $("#btnPrint").show();
                }
        });

    });

    $('#btnReset').click(function(event) {
        $('#date_from').val(null);
        $('#date_to').val(null);
        $('#isi_tabel_pesanan').children("tr").remove();

        //var tombol_cetak = document.getElementById('btnPrint');
        //tombol_cetak.style.display = 'none';
        // $("#btnPrint").css('display','none');
        $("#btnPrint").hide();

    });

    $(".datepicker").datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
    });

});

function convertDate(inputFormat) {
  function pad(s) { return (s < 10) ? '0' + s : s; }
  var d = new Date(inputFormat);
  return [pad(d.getDate()), pad(d.getMonth()+1), d.getFullYear()].join('-');
}

//$("#btnPrint").on("click", function () {
       // var divContents = $("#content-table").html();
        //var printWindow = window.open('', '', 'height=400,width=800');
        //printWindow.document.write('<html><head><title>DIV Contents</title>');
        //printWindow.document.write('</head><body >');
        //printWindow.document.write(divContents);
        //printWindow.document.write('</body></html>');
        //printWindow.document.close();
        //printWindow.print();
    //});

// $("#btnPrint").on("click", function () {
//     var mapForm = document.getElementById("search-form");
//     map=window.open("","Map","status=0,title=0,height=600,width=800,scrollbars=1");

//     if (map) {
//         mapForm.submit();
//     } else {
//         //alert('You must allow popups for this map to work.');
//     }
// });

$("#btnPrint").on("click", function () {
    var date_from = $('#date_from').val();
    var date_to = $('#date_to').val();
    var status = 'print';

    var mapForm = document.createElement("form");
    mapForm.target = "Map";
    mapForm.method = "get"; // or "post" if appropriate
    mapForm.action = "{{url('transaksi/laporan/createlaporanpesanan')}}";

    var mapInput = document.createElement("input");
    mapInput.type = "hidden";
    mapInput.name = "date_from";
    mapInput.value = date_from;
    mapForm.appendChild(mapInput);

    var mapInput2 = document.createElement("input");
    mapInput2.type = "hidden";
    mapInput2.name = "date_to";
    mapInput2.value = date_to;
    mapForm.appendChild(mapInput2);

    var mapInput3 = document.createElement("input");
    mapInput3.type = "hidden";
    mapInput3.name = "status";
    mapInput3.value = status;
    mapForm.appendChild(mapInput3);

    document.body.appendChild(mapForm);

    map = window.open("", "Map", "status=0,title=0,height=600,width=800,scrollbars=1");

    if (map) {
        mapForm.submit();
    } else {
        //alert('You must allow popups for this map to work.');
    }
});

</script>
@endpush


