<?php

namespace Modules\Master\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Kategori extends Model
{
    use LogsActivity;

    protected $table = 'kategori';
    protected $fillable = ['nama'];

    protected static $logName = 'kategori';
    protected static $logAttributes = ['nama'];

    public $timestamps = false;

}
