@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1>Dashboard</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        {{-- <li class="">User Management</li> --}}
        <li class="active">Dashboard</li>
    </ol>
@stop

@section('content')
    {{-- <p>You are logged in!</p> --}}
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3 id="jmlBaru"></h3>

            <p>Pesanan Baru</p>
          </div>
          <div class="icon">
            <i class="ion ion-bag"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
          <div class="inner">
                <h3 id="jmlSelesai"></h3>

            <p>Pesanan Selesai</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <section class="col-lg-12 connectedSortable ui-sortable">
        <!-- Custom tabs (Charts with tabs)-->
        <div class="nav-tabs-custom" style="cursor: move;">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs pull-right ui-sortable-handle">
                <li class="pull-left header"><i class="fa fa-inbox"></i> Orders in {{isset($month)?$month:''}}</li>
            </ul>
            <div class="tab-content no-padding">
            <!-- Morris chart - Sales -->
                <div id="myfirstchart" style="height: 250px;"></div>
            </div>

        </div>
    </section>

@stop

@push('js')
<script type="text/javascript">
$(document).ready(function() {
    loadDataBulanan();
    getTodayPesanan();


});

function loadDataBulanan(){
    var url = "{{url('transaksi/pesanan/loaddatabulanan')}}";
    $.ajax({
        type: "GET",
        url: url,
        data:{
            ajax : 1,
        },
        success: function (response) {
          createGraph(response.pesanan);
        //   $('.judul-graph').val(response.month)
        },
        error: function (response) {
            //console.log('Error:', data);
        }
    });
}

function getTodayPesanan(){
    var url = "{{url('transaksi/pesanan/gettodaypesanan')}}";
    $.ajax({
        type: "GET",
        url: url,
        data:{
            ajax : 1,
        },
        success: function (response) {
            $('#jmlBaru').text(response.jmlBaru);
            $('#jmlSelesai').text(response.jmlSelesai);
            console.log(response);
        },
        error: function (response) {
            //console.log('Error:', data);
        }
    });
}

function createGraph(response){
    // var num = response.num_date;

    new Morris.Line({
    // ID of the element in which to draw the chart.
    element: 'myfirstchart',
    // Chart data records -- each entry in this array corresponds to a point on
    // the chart.
    data: response,
//     data: [
//     { year: '2008', value: 20 },
//     { year: '2009', value: 10 },
//     { year: '2010', value: 5 },
//     { year: '2011', value: 5 },
//     { year: '2012', value: 20 }
//   ],
  // 
    // The name of the data record attribute that contains x-values.
    xkey: 'date',
    // A list of names of data record attributes that contain y-values.
    ykeys: ['order'],
    // Labels for the ykeys -- will be displayed when you hover over the
    // chart.
    labels: ['order']
    });

}

</script>
@endpush