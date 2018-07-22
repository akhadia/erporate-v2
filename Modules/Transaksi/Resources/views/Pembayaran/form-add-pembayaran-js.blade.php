@push('js')
<script type="text/javascript">
$(document).ready(function() {
	
});



//validasi semua inputan jika tombol simpan ditekan
$('.submit_button').click(function() {
	var n=input_validation();
	if(n > 0){
		return false;
	}else{
		add_pembayaran();
	}
});

//fungsi validasi
function add_pembayaran(){
	var url;
	var status = $('#status').val();

	if(status=="create"){
		url = "{{url('transaksi/pembayaran/store')}}";
	}else{
		url = "{{url('transaksi/pembayaran/update')}}";
	}

	$.ajax({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		type: "POST",
		url: url,
		data:{
			'id_pembayaran'	: $('#id_pembayaran').val(),
			'id_pesanan'	: $('#id_pesanan').val(),
			'no_pesanan'	: $('#no_pesanan').val(),
			'total_tagihan'	: $('#total_tagihan').val(),
			'jumlah_bayar'	: $('#jumlah_bayar').val(),
			'kembalian'		: $('#kembalian').val(),
		},
		success: function (response) {

			if(response.status == 'Success'){
				$("#btn_cetak").show();

				toastr.success(response.message, response.status);
			}else{
				toastr.error(response.message,response.status);
			}
		},
		error: function (response) {
			//console.log('Error:', data);
		}
	});


}

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

$("#btn-cetak").on("click", function () {
	// console.log('oke');
	var id_pesanan = $('#id_pesanan').val();

    var mapForm = document.createElement("form");
    mapForm.target = "Map";
    mapForm.method = "get"; // or "post" if appropriate
    mapForm.action = "{{url('transaksi/pembayaran/cetaknota')}}";

    var mapInput = document.createElement("input");
    mapInput.type = "hidden";
    mapInput.name = "id_pesanan";
    mapInput.value = id_pesanan;
    mapForm.appendChild(mapInput);

    // var mapInput2 = document.createElement("input");
    // mapInput2.type = "hidden";
    // mapInput2.name = "id_pesanan";
    // mapInput2.value = id_pesanan;
    // mapForm.appendChild(mapInput2);

    document.body.appendChild(mapForm);

    map = window.open("", "Map", "status=0,title=0,height=600,width=500,scrollbars=1");

    if (map) {
        mapForm.submit();
    } else {
        //alert('You must allow popups for this map to work.');
    }
});

</script>
@endpush