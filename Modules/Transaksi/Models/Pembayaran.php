<?php

namespace Modules\Transaksi\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Pembayaran extends Model
{
    use LogsActivity;

    protected $table = 'pembayaran';

    protected $fillable = ['id_pesanan','tgl_bayar', 'total_tagihan', 'jumlah_bayar', 'sisa_tagihan',
        'kembalian', 'keterangan', 'user_input', 'user_update'];

    protected static $logName = 'pembayaran';
    protected static $logAttributes = ['id_pesanan','tgl_bayar', 'total_tagihan', 'jumlah_bayar', 'sisa_tagihan',
        'kembalian', 'keterangan', 'user_input', 'user_update'];

    public static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            if(isset(\Auth::User()->id)){
                $model->user_update = \Auth::User()->id;
            }
        });

        static::creating(function ($model) {
            if(isset(\Auth::User()->id)){
                $model->user_input = \Auth::User()->id;
            }
        });
    }
    
}

