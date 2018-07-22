<div class="row">
   <div class="form-group col-md-3">
        {{-- <input class="form-control" id="no_pemesanan" name="no_pemesanan" placeholder="Enter text.."> --}}
        {{ Form::text('nama_produk', '', array('id' => 'nama_produk', 'class' => 'form-control', 'placeholder' => 'Nama produk...')) }}
    </div> 
</div>

<div class="row">
    <div class="form-group col-md-3">
        {{-- <label for="sel1">Status</label> --}}
        <select class="form-control" id="kategori" name="kategori">
            @foreach($kategori as $key=>$val)
                <option value="{{$key}}">{{$val}}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-3">
        {{-- <label for="sel1">Status</label> --}}
        <select class="form-control" id="status" name="status">
            <option value="All">All Status</option>
            <option value="Y">Ready</option>
            <option value="N">Unready</option>
        </select>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="col-md-offset-0 col-md-12">
            <button class="btn btn-success" onclick="" id="filter-produk-table"><i class="fa fa-search"></i> Search</button>
            <button class="btn btn-warning" type="reset" id="reset-filter-produk-table" >Reset</button>
        </div>
    </div>
</div>
<div class="row">&nbsp;</div>