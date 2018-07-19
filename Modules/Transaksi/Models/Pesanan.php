<?php

namespace Modules\Transaksi\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Pesanan extends Model
{
    use LogsActivity;

    protected $table = 'pesanan';
    protected $fillable = ['no_pesanan','id_meja','tgl_pesanan','keterangan','status','total','user_input','user_update'];
    
    protected static $logName = 'pesanan';
    protected static $logAttributes = ['no_pesanan','id_meja','tgl_pesanan','keterangan','status','total','user_input','user_update'];

    // public $timestamps = false;

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

    public function meja()
    {
        return $this->belongsTo('Modules\Master\Models\Meja','id_meja');
    }
}
