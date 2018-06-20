<?php

Route::group(['middleware' => 'web', 'prefix' => 'master', 'namespace' => 'Modules\Master\Http\Controllers'], function()
{
    Route::get('/', 'MasterController@index');
});
