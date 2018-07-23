<div class="row">
   <div class="form-group col-md-3">
        {{-- <input class="form-control" id="no_pemesanan" name="no_pemesanan" placeholder="Enter text.."> --}}
        {{ Form::text('nama_user', '', array('id' => 'nama_user', 'class' => 'form-control', 'placeholder' => 'Nama User...')) }}
    </div> 
</div>

<div class="row">
    <div class="form-group col-md-3">
         {{-- <input class="form-control" id="no_pemesanan" name="no_pemesanan" placeholder="Enter text.."> --}}
         {{ Form::text('username', '', array('id' => 'username', 'class' => 'form-control', 'placeholder' => 'Username...')) }}
     </div> 
 </div>

 <div class="row">
    <div class="form-group col-md-3">
         {{-- <input class="form-control" id="no_pemesanan" name="no_pemesanan" placeholder="Enter text.."> --}}
         {{ Form::text('email', '', array('id' => 'email', 'class' => 'form-control', 'placeholder' => 'Email...')) }}
     </div> 
 </div>

<div class="row" style="display:none">
    <div class="form-group col-md-3">
        {{-- <label for="sel1">Status</label> --}}
        <select class="form-control" id="status" name="status">
            <option value="all">All Status</option>
            <option value="Y">Aktif</option>
            <option value="N">Non Aktif</option>
        </select>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="col-md-offset-0 col-md-12">
            <button class="btn btn-success" onclick="" id="filter-user-table"><i class="fa fa-search"></i> Search</button>
            <button class="btn btn-warning" type="reset" id="reset-filter-user-table" >Reset</button>
        </div>
    </div>
</div>
<div class="row">&nbsp;</div>