<?php

namespace App;

use Laratrust\Models\LaratrustPermission;
use Spatie\Activitylog\Traits\LogsActivity;

class Permission extends LaratrustPermission
{
    use LogsActivity;

    protected $fillable = [
        'name', 'display_name', 'description'
    ];

    protected static $logName = 'acl';
    protected static $logAttributes = ['name', 'display_name', 'description'];

}
