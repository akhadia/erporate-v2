<div class="row">
   <div class="form-group col-md-4">
        {{-- <input class="form-control" id="no_pemesanan" name="no_pemesanan" placeholder="Enter text.."> --}}
        {{ Form::text('no_pesanan', '', array('id' => 'no_pesanan', 'class' => 'form-control', 'placeholder' => 'No pesanan..')) }}
    </div> 
</div>

<div class="row">
    <div class="form-group col-md-4" style="background-color:"> 
        <div class="input-group">
            <input type="text" class="form-control datepicker" id="date_from" value="" placeholder="Tanggal awal.." style="text-align:left">
            <span class="input-group-addon"> to </span>
            <input type="text" class="form-control datepicker" id="date_to" value="" placeholder="Tanggal akhir.." style="text-align:left">
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-4">
        {{-- <label for="sel1">Status</label> --}}
        <select class="form-control" id="status" name="status">
            <option value="All">All Status</option>
            <option value="Y">Belum Bayar</option>
            <option value="N">Sudah Bayar</option>
        </select>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="col-md-offset-0 col-md-12">
            <button class="btn btn-success" onclick="" id="filter-pembayaran-table"><i class="fa fa-search"></i> Search</button>
            <button class="btn btn-warning" type="reset" id="reset-filter-pembayaran-table" >Reset</button>
        </div>
    </div>
</div>
<div class="row">&nbsp;</div>