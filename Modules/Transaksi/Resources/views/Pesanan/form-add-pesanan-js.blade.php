@push('js')
<script type="text/javascript">
$(document).ready(function() {
	typeahead_produk_initialize();

});

//fungsi utk qty 
$( "#isi_tabel_produk" ).on( "change", ".qty_produk", function() {
    var no = $(this).attr('tujuan');
    // var qty = $(this).val();
    // var produk_nama = $('#produk_nama'+no).val();
    subtotal_count(no);
    all_total_count();
});

//validasi semua inputan jika tombol simpan ditekan
$('.submit_button').click(function() {
	var n=input_validation();
	if(n > 0){
		return false;
	}

});

//popup cari produk
$( "#isi_tabel_produk").on( "click", ".btn_search", function() {
	var url = "{{url('master/produk/popupproduk')}}";
	var nomer = $(this).attr('tujuan');

	$('#myModalDialog').html('');

	$.ajax({
		url: url,
		data:{
			'ajax':1,
			'nomer':nomer,
		},
		cache: false,
		dataType: 'html',
		success: function(msg){
			$('#myModalDialog').html(msg);
			$('#myModalDialog').modal();
		},
		error: function(){
			//$('#myModelDialog').html("request gagal dibuka");
			//$('#myModelDialog').modal('show');
			console.log('gagal');
		}
	});

	return true;
});


//tombol add data
$('#add_data_produk').click(function() {
	$("#kaki_tabel_produk").css("display", '');

	$('.cari_produk').typeahead('destroy'); 	//destroy typeahead binding event before adding new row

    var count = $('#hide_count_produk').val();
    count = parseInt(count);
    var count2 = count+1;

    $('#hide_count_produk').val(count2);
    add_data_produk_to_table(count);
	typeahead_produk_initialize();
    return false;
});

//tambah data ke tabel
function add_data_produk_to_table(count){
    $('<tr class="isi_data_produk" id="data_produk_ke-'+count+'">\n\
	 		<td>\n\
	          <a id="'+count+'" class="delete_produk_new_detail btn btn-xs btn-danger" href="#">\n\
	          <span title="Batal" class="glyphicon glyphicon-trash"></span></a>&nbsp;&nbsp;\n\
	      	</td>\n\
			<td class="image_place" id="produk_gambar_place_'+count+'" align="">\n\
			</td>\n\
            <td id="produk_nama_place_'+count+'">\n\
				<input type="text" id="produk_nama_'+count+'" name="produk_nama[]" class="form-control cari_produk" tujuan="'+count+'" >\n\
				<input type="hidden" id="old_produk_nama_'+count+'" name="old_produk_nama_'+count+'" class="form-control" >\n\
				<button id="btn_search" type="button" class="btn btn-info btn_search" tujuan="'+count+'"><i class="fa fa-search"></i> Produk</button>\n\
				<input type="hidden" id="id_produk_'+count+'" name="id_produk[]" class="id_produk_value" value=""/>\n\
			</td>\n\
            <td width="10%" id="produk_qty_place_'+count+'" align="center">\n\
				<input type="text" id="produk_qty_'+count+'" name="produk_qty[]" class="form-control input-xsmall qty_produk" tujuan="'+count+'" value="" ></input>\n\
			</td>\n\
			<td id="produk_harga_place_'+count+'" align="right">\n\
				<span id="produk_harga_text_'+count+'"></span>\n\
				<input type="text" id="produk_harga_'+count+'" name="produk_harga[]" class="produk_harga form-control input-small" value="" tujuan="'+count+'" style="display:none"></input>\n\
			</td>\n\
			<td align="right">\n\
				<span id="subtotal_text_'+count+'"></span>\n\
				<input type="hidden" id="nilai_subtotal_'+count+'" name="nilai_subtotal[]" value="" class="for_total_produk"/>\n\
			</td>\n\
			<td>\n\
  	</tr>').appendTo('#isi_tabel_produk')
}

//delete row tabel new
$( "#isi_tabel_produk" ).on( "click", ".delete_produk_new_detail", function() {
    var no = $(this).attr('id');
    var stat ='new';
    delete_data_produk_table(no,stat);
    return false;
});

//delete row tabel edit
$( "#isi_tabel_produk" ).on( "click", ".delete_produk_edit_detail", function() {
	var no = $(this).attr('id');
	var stat ='edit';
	delete_data_produk_table(no,stat);
	return false;
});

//fungsi untuk delete row tabel
function delete_data_produk_table(no,stat){
	if(confirm("Anda yakin akan menghapus data ini?")){

		if(stat == 'edit'){
			var id_detail = $('#old_id_ps_produk_'+no).val();
        	$('<input type="hidden"  name="details_deleted[]" value="'+id_detail+'"/>').appendTo('#delete_details_produk');
		}

		$('#data_produk_ke-'+no).detach();

		if($('.isi_data_produk').length < 1){
			$('#hide_count_produk').val(1);
			$('#kaki_tabel_produk').fadeOut('fast');
		}
		all_total_count();
		return false;
	}
}

//== autocomplete produk ==//
function typeahead_produk_initialize() {
	var engine = new Bloodhound({
		remote: {
			url: '{{ url("master/autocomplete/produk") }}?q=%QUERY',
			wildcard: "%QUERY"
		},
		datumTokenizer: Bloodhound.tokenizers.whitespace('q'),
		queryTokenizer: Bloodhound.tokenizers.whitespace
	});

	$(".cari_produk").typeahead({
		hint: true,
		highlight: true,
		minLength: 1
	}, {
		source: engine.ttAdapter(),
		name: 'produk',
		displayKey: "nama",

		templates: {
			empty: [
				'<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
			],
			header: [
				'<div class="list-group search-results-dropdown">'
			],
			suggestion: function (data) {
				return '<div class="list-group-item">' + data.nama + '</div>'
			}
		}
	}).bind("typeahead:selected", function(obj, datum, name) {
		var no = $(this).attr('tujuan');
		var img;
		var url = 'http://'+window.location.host + '/images/' + datum.image;

		// console.log(url);
		$('#id_produk_'+no).val(datum.id);
		$('#produk_harga_'+no).val(datum.harga);
		$('#produk_harga_text_'+no).text(datum.harga);

		$('#produk_qty_'+no).val(0);


		// $('#data_produk_ke-'+no+' .image_place').prepend('<img src="http://127.0.0.1:8000/images/IMG14072018-001" style="height:75px;width:75px;">')
		

		$('#produk_gambar_place_'+no).children().remove();

		img = jQuery('<img style="height:75px;width:75px;">');
		img.attr('src', url );
		jQuery('#data_produk_ke-'+no+' .image_place').append(img);

		subtotal_count(no);
		all_total_count();

	}).bind("typeahead:change", function(obj, datum, name) {
		//do..
	});
}

//Fungsi hitung Subtotal
function subtotal_count(no){
	var qty = $('#produk_qty_'+no).val();
	var val_hrg = $('#produk_harga_'+no).val();
	//console.log('cek harga ksong : '+val_hrg);
	var harga = (val_hrg !== 0 && val_hrg !=='')?val_hrg:0;
	 
	if(qty !== 0 && qty !=='' && !isNaN(qty)){
		var subtot = qty*harga;
		var subtot_bulat = Math.round(subtot*100)/100;

		$('#subtotal_text_'+no).text(subtot_bulat);
		$('#nilai_subtotal_'+no).val(subtot_bulat);
	}else{
		$('#subtotal_text_'+no).text(0);
		$('#nilai_subtotal_'+no).val(0);
	}
}

//fungsi hitung total
function all_total_count(){
	// var tun = $('.for_total_produk').length;

	var all_total=0;
	var subtotal_produk = $(".for_total_produk");

	subtotal_produk.each(function() {
		all_total += Number($(this).val());
	});

	var all_total_bulat = Math.round(all_total*100)/100;

	$('#all_total_produk_place').text(all_total_bulat);
	$('#all_total_produk').val(all_total_bulat);
}

//fungsi validasi
function input_validation(status){
	var n=0;
	var pesan='';

	//jika tombol submit ditekan maka semua inputan akan dicek termasuk qty
	var cari_produk = $(".cari_produk.tt-input");
	cari_produk.each( function(i) {
		var value=$(this).val();
		var no = $(this).attr('tujuan');
		var j = i+1;
		var id_produk = $('#id_produk_'+no).val();
		// console.log('id_produk_ = '+id_produk);

		if(value.length < 1){
			n=n+1;
		}

		if(id_produk.length == '' || id_produk.length == null){
			n=n+1;
		}

		if(n > 0){
			error = "Kolom produk no "+j+" belum diisi dengan benar!<br/><br/>\n";
			pesan = pesan+error;
		}

	});

	var qty_produk = $(".qty_produk");
	qty_produk.each( function(i) {
		var value=$(this).val();
		var no = $(this).attr('tujuan');
		var j = i+1;
		if(isNaN(value) || value < 1){
			error = "Kolom qty no "+j+" belum diisi dengan benar!<br/><br/>\n";
			pesan = pesan+error;
			n=n+1;
		}
	});

	var meja = $("#meja").val();
	if(meja=='' || meja==null){
		error = "No meja belum dipilih!<br/><br/>\n";
		pesan = pesan+error;
		n=n+1;
	}
	
	if(n > 0){
		toastr.error(pesan,"Gagal","error");
		// alert(pesan);
	}
	return n;
}


</script>
@endpush