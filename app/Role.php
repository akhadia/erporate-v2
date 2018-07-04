<?php

namespace App;

use Laratrust\Models\LaratrustRole;
use Spatie\Activitylog\Traits\LogsActivity;

class Role extends LaratrustRole
{
    use LogsActivity;

    protected $fillable = [
        'name', 'display_name', 'description'
    ];

    protected static $logName = 'acl';
    protected static $logAttributes = ['name', 'display_name', 'description'];

}
