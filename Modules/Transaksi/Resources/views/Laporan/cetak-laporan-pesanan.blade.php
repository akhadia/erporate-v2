@extends('layouts.print-laporan')
@section('content')

{{-- Head Lapiran --}}
<table id="tabel_header" width="100%" >
    <tr>
        <td colspan="4"><center><h3><b>LAPORAN PESANAN</b></h3></td>
    </tr>
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
        <td width="1%">&nbsp</td>
        <td width="20%">Tanggal</td>
        <td >:</td>
        <td >{{isset($date_from)?$date_from:''}} {{isset($date_to)?' s/d '.$date_to:''}}</td>
    </tr>
    <tr>
        <td width="1%">&nbsp</td>
        <td width="20%">Pegawai </td>
        <td >:</td>
        <td >{{isset($user_name)?$user_name:''}} </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
</table>

{{-- Isi Laporan --}}
<table width="100%" id="tabel_isi" >
<thead>
    <tr>
        <td colspan="5"><hr id="hrmargin"></td>
    </tr>
    <tr>
            <td valign="top" width="3%">No</td>
            <td valign="top"><center>Nomer Pesanan</center></td>
            <td valign="top"><center>Tanggal Pesanan</center></td>
            <td valign="top"><center>Nomer Meja</center></td> 
            <td valign="top"><center>Total</center></td>
    </tr>
    <tr>
        <td colspan="5"><hr id="hrmargin"></td>
    </tr>
</thead>
<tbody>
    <?php 
        $grand_total = 0;
    ?>
    
     @if (isset($pesanan) && !empty($pesanan))
        @foreach ($pesanan as $num => $row)
            <tr class="isi_data_pesanan">
                <td align="center">{{$num+1}}</td>
                <td align="center">{{isset($row->no_pesanan)?$row->no_pesanan:''}}</td>
                <td align="center">{{isset($row->tgl_pesanan)?$row->tgl_pesanan:''}}</td>
                <td align="center">{{isset($row->no_meja)?$row->no_meja:''}}</td>
                <td align="right">{{isset($row->total)?$row->total:''}}</td>
                <?php 
                    $subtotal = isset($row->total)&&!empty($row->total)?$row->total:0;
                    $grand_total = $grand_total+$subtotal;
                ?>
            </tr>
            
        @endforeach
    @endif
    <tr>
        <td colspan="5"><hr id="hrmargin"></td>
    </tr>
    <tr>
        <td colspan="4" align=""> Grand Total</td>
        <td colspan="" align="right">{{$grand_total}}</td>
    </tr>
    <tr><td colspan="5">&nbsp;</td></tr>
    <tr>
        <td  colspan="4">&nbsp;</td>
        <td  colspan=""><center>Yogyakarta,   {{date('d-m-Y H:i')}}</center></td>
    </tr>
    <tr>
        <td  colspan="4">&nbsp;</td>
        <td  colspan=""><center>Dibuat oleh, </center></td>
    </tr>
    <tr>
        <td colspan="5"><br><br></td>
    </tr>
    <tr>
        <td  colspan="4"></td>
        <td><center>{{isset($user_name)?$user_name:''}}</center></td>
    </tr>
</tbody>
</table>
<br /><br /><br /><br /><br /><br />
@endsection