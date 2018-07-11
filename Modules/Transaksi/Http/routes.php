<?php

Route::group(['middleware' => 'web', 'prefix' => 'transaksi', 'namespace' => 'Modules\Transaksi\Http\Controllers'], function()
{
    Route::get('/', 'TransaksiController@index');
});
