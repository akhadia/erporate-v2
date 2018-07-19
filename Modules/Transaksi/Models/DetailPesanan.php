<?php

namespace Modules\Transaksi\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class DetailPesanan extends Model
{
    use LogsActivity;

    protected $table = 'detail_pesanan';
    protected $fillable = ['id_pesanan','id_produk','qty_pesanan','harga','subtotal'];
    
    protected static $logName = 'detail_pesanan';
    protected static $logAttributes = ['id_pesanan','id_produk','qty_pesanan','harga','subtotal'];

    public $timestamps = false;

    public function pesanan()
    {
        return $this->belongsTo('Modules\Transaksi\Models\Pesanan','id_pesanan');
    }

    public function produk()
    {
        return $this->belongsTo('Modules\Master\Models\Produk','id_produk');
    }
}
