@extends('layouts.print-nota')
@section('content')

{{-- Head nota --}}
<table id="tabel_header" width="100%" >
    <tr>
        <td colspan="4"><center><h3><b>Nota Pembayaran</b></h3></td>
    </tr>
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
        <td width="1%">&nbsp</td>
        <td width="20%">No Pesanan </td>
        <td >:</td>
        <td >{{isset($pesanan->no_pesanan)?$pesanan->no_pesanan:''}}</td>
    </tr>
    <tr>
        <td width="1%">&nbsp</td>
        <td width="20%">No Meja </td>
        <td >:</td>
        <td >{{isset($pesanan->meja->no_meja)?$pesanan->meja->no_meja:''}}</td>
    </tr>
    <tr>
        <td width="1%">&nbsp</td>
        <td width="20%">Tanggal </td>
        <td >:</td>
        <?php
            if(isset($pembayaran->tgl_bayar)){
                $originalDate = $pembayaran->tgl_bayar;
                $newDate = date("d-m-Y", strtotime($originalDate));  
            }else {
                $newDate = '';
            }
        ?> 
        <td >{{$newDate}}</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
</table>

{{-- Isi Nota --}}
<table width="100%" id="tabel_isi" >
<thead>
    <tr>
        <td colspan="5"><hr id="hrmargin"></td>
    </tr>
    <tr>
            <td valign="top" width="3%"><center>No</center></td>
            <td valign="top"><center>Nama Produk</center></td>
            <td valign="top"><center>Harga Satuan</center></td>
            <td valign="top"><center>Jumlah</center></td> 
            <td valign="top"><center>Subtotal </center></td>
    </tr>
    <tr>
        <td colspan="5"><hr id="hrmargin"></td>
    </tr>
</thead>
<tbody>
    
     @if (isset($detailPesanan) && !empty($detailPesanan))
        @foreach ($detailPesanan as $num => $row)
            <tr class="isi_data_produk">
                <td align="center">{{$num+1}}</td>
                <td align="center">{{isset($row->produk->nama)?$row->produk->nama:''}}</td>
                <td align="center">{{isset($row->produk->harga)?$row->produk->harga:''}}</td>
                <td align="center">{{isset($row->qty_pesanan)?$row->qty_pesanan:''}}</td>
                <td align="right">{{isset($row->subtotal)?$row->subtotal:''}}</td>
            </tr>
            
        @endforeach
    @endif
    <tr>
        <td colspan="5"><hr id="hrmargin"></td>
    </tr>
    <tr>
        <td colspan="4" align="right"> Total </td>
        <td colspan="" align="right">{{isset($pembayaran->total_tagihan)?$pembayaran->total_tagihan:''}}</td>
    </tr>
    <tr>
        <td colspan="4" align="right"> Bayar </td>
        <td colspan="" align="right">{{isset($pembayaran->jumlah_bayar)?$pembayaran->jumlah_bayar:''}}</td>
    </tr>
    <tr>
        <td colspan="4" align="right"> Kembalian </td>
        <td colspan="" align="right">{{isset($pembayaran->kembalian)?$pembayaran->kembalian:''}}</td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <tr><td>&nbsp;</td></tr>
    <tr>
        <td colspan="5"><center>Terimakasih Atas Kunjungan Anda</center></td>
    </tr>
</tbody>
</table>
<br /><br /><br /><br /><br /><br />
@endsection