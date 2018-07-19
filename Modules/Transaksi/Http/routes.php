<?php

Route::group(['middleware' => 'web', 'prefix' => 'transaksi', 'namespace' => 'Modules\Transaksi\Http\Controllers'], function()
{
    Route::get('/', 'TransaksiController@index');

    //== Pesanan ==//
    Route::post('pesanan/pesananselesai','PesananController@pesananSelesai');
    Route::get('pesanan/loaddata','PesananController@loadData');
    Route::get('pesanan/edit/{id}','PesananController@edit');
    Route::post('pesanan/delete','PesananController@delete');
    Route::resource('pesanan','PesananController');
});
