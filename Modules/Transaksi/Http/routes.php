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

    //== Pembayaran ==//
    // Route::post('pesanan/pesananselesai','PembayaranController@pesananSelesai');
    Route::get('pembayaran/loaddata','PembayaranController@loadData');
    Route::get('pembayaran/edit/{id}','PembayaranController@edit');
    Route::get('pembayaran/create/{id}','PembayaranController@create');
    Route::post('pembayaran/delete','PembayaranController@delete');
    Route::resource('pembayaran','PembayaranController');
});