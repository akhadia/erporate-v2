{{-- {{ Form::open(array('method'=>'get', 'id'=>'search-form', 'route'=>'transaksi.laporanPemesanan', 'target'=>'Map')) }} --}}


<div class="col-md-12">

    <div class="form-group">
        <label for="">Range Tanggal</label>
        <div class="input-group col-md-5">
            <input type="text" class="form-control datepicker" id="date_from" value="" placeholder="Tanggal awal.." style="text-align:left">
            <span class="input-group-addon"> to </span>
            <input type="text" class="form-control datepicker" id="date_to" value="" placeholder="Tanggal akhir.." style="text-align:left">
        </div>
    </div>

    <div class="form-group">
        <div class="btn-toolbar">
            <button class="btn btn-success" onclick="" id="btnSubmit"><i class="fa fa-search"></i> Submit</button>
            <button class="btn btn-warning" type="reset" id="btnReset" >Reset</button>
        </div>
    </div>

</div>

<div class="row">&nbsp;</div>
