<?php

namespace Modules\Master\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Meja extends Model
{
    use LogsActivity;

    protected $table = 'meja';
    protected $fillable = ['no_meja'];

    protected static $logName = 'meja';
    protected static $logAttributes = ['no_meja'];

    public $timestamps = false;

}
