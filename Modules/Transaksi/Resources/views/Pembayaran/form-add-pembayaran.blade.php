@extends('adminlte::page')

@section('title', 'Pembayaran')

@section('content_header')
    <h1>Pembayaran</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="">Transaksi</li>
        <li class=""><a href="{{ URL::to('transaksi/pembayaran')  }}" >Pembayaran</a></li>
        <li class="active">{{isset($pembayaran->id)?'Edit':'Create'}} Pembayaran</li>
    </ol>
@stop

@section('content')
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">{{isset($pembayaran->id)?'Edit':'Create'}} Pembayaran</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    {{ Form::model($pembayaran,array('route' => array((!$pembayaran->exists)?'pembayaran.store':'pembayaran.update',$pembayaran->id),
    'class'=>'form-horizontal','id'=>'pembayaran-form','method'=>(!$pembayaran->exists)?'POST':'PUT')) }}

    <input type="hidden" class="" id="id_pembayaran" name="id_pembayaran" value="{{(isset($pembayaran->id))?$pembayaran->id:''}}" placeholder="">
    <input type="hidden" class="" id="id_pesanan" name="id_pesanan" value="{{(isset($pesanan->id))?$pesanan->id:''}}" placeholder="">
    <input type="hidden" class="" id="no_pesanan" name="no_pesanan" value="{{(isset($pesanan->no_pesanan))?$pesanan->no_pesanan:''}}" placeholder="">
    <input type="hidden" class="" id="status" name="status" value="{{(isset($status))?$status:''}}" placeholder="">

    <div class="box-body">
    
        <div class="col-md-6">
            <!--##### No Pesanan #####-->
            <div class="form-group ">
                <div class="col-sm-3 text-right">
                    <span><b>No Pesanan</b></span>
                </div>
                <div class="col-sm-9">
                    <span>: {{isset($pesanan->no_pesanan)?$pesanan->no_pesanan:''}}</span>
                </div>
            </div>

            <!--##### No Meja #####-->
            <div class="form-group ">
                <div class="col-sm-3 text-right">
                    <span><b>No Meja</b></span>
                </div>
                <div class="col-sm-9">
                    <span > : {{isset($pesanan->id_meja)?$pesanan->meja->no_meja:''}}</span>
                </div>
            </div>

            <!--##### Tgl Pesanan #####-->
            <div class="form-group ">
                <div class="col-sm-3 text-right" >
                    <span><b>Tgl Pesanan</b></span>
                </div>
                <div class="col-sm-9">
                    <span >: 
                        <?php
                            $originalDate = $pesanan->tgl_pesanan;
                            $newDate = date("d-m-Y", strtotime($originalDate));  
                            
                            echo $newDate;
                        ?> 
                    </span>
                </div>
            </div>
            
        </div>

        <div class="row">&nbsp;</div>
        <div class="row">&nbsp;</div>

        <div class="col-md-12">
        <!--#####  Detail Pesanan #####-->
        <table class="table table-condensed">
            <tbody id="isi_tabel_produk">
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Produk</th>
                <th class="text-center">Harga</th>
                <th class="text-center">Qty</th>
                <th class="text-center">Subtotal</th>
            </tr>
            @if (isset($detailPesanan) && !empty($detailPesanan))
                <?php $total_all = 0;$n=0;?>
                @foreach ($detailPesanan as $num => $row)
                    <?php $n++;?>
                    <tr class="isi_data_produk" >
                        <td class="text-center">
                            <span>{{$n}}</span>
                        </td>
                        <td class="text-center">
                            <span>{{isset($row->produk->nama)?$row->produk->nama:''}}</span>
                        </td>
                        <td class="text-right">
                            <span>{{isset($row->produk->harga)?$row->produk->harga:0}}</span>
                        </td>
                        <td class="text-center">
                            <span>{{isset($row->qty_pesanan)?$row->qty_pesanan:0}}</span>
                        </td>
                        <td class="text-right">
                            <span>{{isset($row->subtotal)?$row->subtotal:0}}</span>
                        </td>
                    </tr>
                
                @endforeach
            @endif
            </tbody>

            <tfoot id="kaki_tabel_produk">
                <tr>
                    <td class="text-left"><b>Total</b></td>
                    <td colspan="3">&nbsp;</td>
                    <td class="text-right">
                        <span id="all_total_produk_place">{{ (isset($pesanan->total) && !empty($pesanan->total)) ? $pesanan->total : 0 }}</span>
                    </td>
                </tr>
            </tfoot> 
        </table>
        </div>

        <div class="col-md-8">&nbsp;</div>
        <div class="col-md-4">

            <div class="form-group">
                <label for="inputTotTagihan" class="col-sm-5 control-label">Total Tagihan</label>
                <div class="col-sm-7">
                    <input readonly type="text" class="form-control text-right" id="total_tagihan" name="total_tagihan" value="{{(isset($pesanan->total))?$pesanan->total:0}}" placeholder="">
                </div>
            </div>
            <div class="form-group">
                <label for="inputJmlBayar" class="col-sm-5 control-label">Jumlah Bayar</label>
                <div class="col-sm-7">
                    <input autocomplete="off" type="text" class="form-control text-right" id="jumlah_bayar" name="jumlah_bayar" value="{{(isset($pembayaran->jumlah_bayar))?$pembayaran->jumlah_bayar:''}}" placeholder="">
                </div>
            </div>
            <div class="form-group">
                <label for="inputKembalian" class="col-sm-5 control-label">Kembalian</label>
                <div class="col-sm-7">
                    <input readonly type="text" class="form-control text-right" id="kembalian" name="kembalian" value="{{(isset($pembayaran->kembalian))?$pembayaran->kembalian:''}}" placeholder="">
                </div>
            </div>

        </div>
        
    </div>
    {{ Form::close() }}
    <div class="row">&nbsp;</div>
    <!-- /.box-body -->
    <?php
        $display_none="display:none";
        if(isset($pembayaran->id)){
            $display_none = "";
        }
    ?>
    
    <div class="box-footer">
        <div class="btn-toolbar col-md-12">
            <button class="btn btn-medium btn-primary pull-right submit_button" id="simpan_pembayaran" status-form="simpan"><i class="glyphicon glyphicon-ok"></i> Submit</button>
            <button style="{{$display_none}}" class="btn btn-medium btn-info pull-right btn-cetak" id="btn-cetak" ><i class="glyphicon glyphicon-print"></i> Cetak</button>
        </div>
    </div>
    <!-- /.box-footer -->
   
</div>
 
@include('transaksi::Pembayaran.form-add-pembayaran-js')

@stop


{{-- @push('css')
<style type="text/css"> 
    .form-control{
        text-align:right !important; 
    }
</style>
@endpush --}}



