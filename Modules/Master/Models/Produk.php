<?php

namespace Modules\Master\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';
    protected $fillable = ['id_kategori','nama','harga','deskripsi','image'];
}
