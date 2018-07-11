<?php

namespace Modules\Master\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Produk extends Model
{
    use LogsActivity;

    protected $table = 'produk';
    protected $fillable = ['id_kategori','nama','harga','deskripsi','image','status'];
    
    protected static $logName = 'produk';
    protected static $logAttributes = ['id_kategori','nama','harga','deskripsi','image','status'];

    public $timestamps = false;

    public function kategori()
    {
        return $this->belongsTo('Modules\Master\Models\Kategori','id_kategori');
    }
}
