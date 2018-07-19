@extends('adminlte::page')

@section('title', 'Pesanan')

@section('content_header')
    <h1>Pesanan</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="">Transaksi</li>
        <li class=""><a href="{{ URL::to('transaksi/pesanan')  }}" >Pesanan</a></li>
        <li class="active">{{isset($pesanan->id)?'Edit':'Create'}} Pesanan</li>
    </ol>
@stop

@section('content')
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">{{isset($pesanan->id)?'Edit':'Create'}} Pesanan</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    {{ Form::model($pesanan,array('route' => array((!$pesanan->exists)?'pesanan.store':'pesanan.update',$pesanan->id),
    'class'=>'form-horizontal','id'=>'pesanan-form','method'=>(!$pesanan->exists)?'POST':'PUT')) }}

        <div class="box-body">
            <?php
                $disabled="";
                $readonly="";
                $display_none="display:";
                $tabel_foot="display:none";

                if(isset($pesanan) && !empty($pesanan)){
                    if($pesanan->status == 'N'){
                        $disabled="disabled";
                        $readonly="readonly";
                        $display_none="display:none";
                    }
                }

                if(isset($detailPesanan) && !empty($detailPesanan)){
                    $tabel_foot="";
                }
            ?>

            <div class="col-md-6">
                <!--##### INPUT MEJA #####-->
                <div class="form-group ">
                    <label for="inputMeja" class="col-sm-3 control-label">No Meja</label>
                 
                    <div class="col-sm-9">
                        <select {{$disabled}} class="form-control" id="meja" name="meja">
                            @foreach($meja as $key=>$val)
                                <?php
                                    $selected='';
                                    if(isset($detailPesanan) && !empty($detailPesanan)){
                                        if($pesanan->id_meja == $key){
                                            $selected='selected';
                                        }
                                    }
                                ?>
                                <option  value="{{$key}}" {{$selected}}>{{$val}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!--##### INPUT CATATAN #####-->
                <div class="form-group">
                    <label for="description" class="col-sm-3 control-label">Catatan</label>

                    <div class="col-sm-9">
                        <textarea {{$readonly}} class="form-control" rows="4" id="description" name="description" placeholder="">{{(isset($pesanan->id))?$pesanan->keterangan:''}}</textarea>
                    </div>
                </div> 

{{-- 
                <div class="form-group">
                    <label for="display_name" class="col-sm-3 control-label">Display Name</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control" id="display_name" name="display_name" value="{{(isset($pesanan->id))?$pesanan->display_name:''}}" placeholder="">
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="col-sm-3 control-label">Description</label>

                    <div class="col-sm-9">
                        <textarea class="form-control" rows="4" id="description" name="description" placeholder="">{{(isset($pesanan->id))?$pesanan->description:''}}</textarea>
                    </div>
                </div> --}}
               
            </div>

            <!--##### INPUT DETAIL PESANAN #####-->
            <table class="table">
                <thead>
                    <tr>
                        <th>Aksi</th>
                        <th>Gambar</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>

                <tbody id="isi_tabel_produk">
                @if (isset($detailPesanan) && !empty($detailPesanan))
                    <?php $total_all = 0;?>
                    @foreach ($detailPesanan as $num => $row)
                        <tr class="isi_data_produk" id="data_produk_ke-{{$num+1}}">
                            <td>
                                @if ($pesanan->status == 'Y')
                                    <a id="{{$num+1}}" class="delete_produk_edit_detail btn btn-xs btn-danger" href="#">
                                        <span title="Batal" class="glyphicon glyphicon-trash"></span>
                                    </a>
                                @endif
                            </td>
                            <td class="image_place" id="produk_gambar_place_{{$num+1}}" align="">
                                <img src="{{url('images/'.$row->produk->image)}}" style='height:75px;width:75px;'/>
                            </td>
                            <td id="produk_nama_place_{{$num+1}}">
                                <input type="text" id="produk_nama_{{$num+1}}" name="produk_nama_edit[]" class="form-control cari_produk typeahead" tujuan="{{$num+1}}" 
                                    value="{{$row->produk->nama}}" {{ ($pesanan->status == 'N') ? "readonly" : '' }}>
                                {{-- <input type="hidden" id="old_produk_nama_'+count+'" name="old_produk_nama_'+count+'" class="form-control" >\n\ --}}
                                <button style="{{$display_none}}" id="btn_search" type="button" class="btn btn-info btn_search" tujuan="{{$num+1}}" ><i class="fa fa-search"></i> Produk</button>
                                <input type="hidden" id="id_produk_{{$num+1}}" name="id_produk_edit[]" class="id_produk_value" value="{{$row['id_produk']}}"/>
                            </td>
                            <td width="10%" id="produk_qty_place_{{$num+1}}" align="center">
                                <input type="text" id="produk_qty_{{$num+1}}" name="produk_qty_edit[]" class="form-control input-xsmall qty_produk" tujuan="{{$num+1}}" 
                                    value="{{$row['qty_pesanan']}}" {{ ($pesanan->status == 'N') ? "readonly" : '' }}>
                            </td>

                            <td id="produk_harga_place_{{$num+1}}" align="right">
                                <span id="produk_harga_text_{{$num+1}}">{{ ($row->produk->harga != null)?$row->produk->harga:''}}</span>
                                <input type="text" id="produk_harga_{{$num+1}}" name="produk_harga_edit[]" class="produk_harga form-control input-small" value="{{$row->produk->harga}}"  tujuan="{{$num+1}}" style="display:none">
                            </td>
                            <td align="right">
                                <span id="subtotal_text_{{$num+1}}">{{ ($row->subtotal != null)?$row->subtotal:''}}</span>
                                <input type="hidden" id="nilai_subtotal_{{$num+1}}" name="nilai_subtotal_edit[]" value="{{$row->subtotal}}" class="for_total_produk"/>
                            </td>

                            <input type="hidden" id="count_input_{{$num+1}}" name="count[]" value="{{$num+1}}"/>
                            <input type="hidden" id="old_id_ps_produk_{{$num+1}}" name="old_id_ps_produk_edit[]" class="old_id_ps_produk_input" value="{{$row['id']}}"/>
                        </tr>
                        <?php 
                            $subtot = ($row->subtotal != null)?$row->subtotal:0;
                            $total_all = $total_all+$subtot;
                        ?>
                    @endforeach
                @endif
                </tbody>

                {{-- <tfoot id="kaki_tabel_produk" {{ (isset($detailPesanan) && !empty($detailPesanan)) ? '' : "style=display:none; readonly" }}> --}}
                <tfoot id="kaki_tabel_produk" style="{{$tabel_foot}}">
                    <tr>
                        <td colspan="5"><b>Total</b></td>
                        <td colspan="" align="right">
                            <span id="all_total_produk_place">{{ (isset($total_all) && !empty($total_all)) ? $total_all : 0 }}</span>
                            <input type="hidden" id="all_total_produk" name="all_total_produk" value="{{ (isset($total_all) && !empty($total_all)) ? $total_all : 0 }}" class="all_total"/>
                        </td>
                    </tr>
                </tfoot> 
            </table>

            <div id="delete_details_produk"></div>
            <input type="hidden" value="{{ (isset($num)) ? $num+2 : 1 }}" id="hide_count_produk" name="hide_count_produk"/>

            <br />
            @if ($pesanan->status != 'N')
                <button type="button" name="add_data_produk" id="add_data_produk" class="btn btn-success">Tambah Data Pesanan</button>
            @endif	


        </div>
        <div class="row">&nbsp;</div>
        <!-- /.box-body -->
        
        <div class="box-footer">
            <div class="col-md-12">
                {{-- <button type="submit" class="btn btn-default">Cancel</button> --}}
                {{-- <button type="submit" class="btn btn-info pull-right">Submit</button> --}}
                @if ($pesanan->status != 'N')
                    <button class="btn btn-lg btn-primary pull-right submit_button" id="simpan_pesanan" status-form="simpan">Simpan</button>
                @endif
            </div>
        </div>
        <!-- /.box-footer -->
    {{ Form::close() }}
</div>
 
@include('transaksi::Pesanan.form-add-pesanan-js')

@stop



