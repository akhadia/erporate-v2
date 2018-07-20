@push('js')
<script type="text/javascript">
$(document).ready(function() {
	
});

//validasi semua inputan jika tombol simpan ditekan
$('.submit_button').click(function() {
	var n=input_validation();
	if(n > 0){
		return false;
	}
});

//fungsi validasi
function input_validation(){
	var n=0;
	var pesan='';
	var jumlah_bayar = $('#jumlah_bayar').val();
    var total_tagihan = $('#total_tagihan').val();

	if($.isNumeric(jumlah_bayar)){
		if(parseInt(jumlah_bayar) < parseInt(total_tagihan)){
			error = "Pembayaran kurang dari total tagihan!<br/><br/>\n";
			pesan = pesan+error;
			n=n+1;
		}
	}else{
		error = "Kolom pembayaran belum diisi dengan benar!<br/><br/>\n";
		pesan = pesan+error;
		n=n+1;
	}

	if(n > 0){
		toastr.error(pesan,"Gagal","error");
	}
	return n;
}

//fungsi hitung kembalian
$('#jumlah_bayar').on('keyup blur',function(){

    var jumlah_bayar = parseInt($('#jumlah_bayar').val());
    var total_tagihan = parseInt($('#total_tagihan').val());

	var kembalian = (jumlah_bayar-total_tagihan)<0?0:(jumlah_bayar-total_tagihan);

	if(isNaN(kembalian)) {
		kembalian = 0;
	}
		
	$('#kembalian').val(kembalian);
		
});

</script>
@endpush