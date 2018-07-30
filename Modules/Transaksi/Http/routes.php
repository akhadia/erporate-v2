<?php

Route::group(['middleware' => 'web', 'prefix' => 'transaksi', 'namespace' => 'Modules\Transaksi\Http\Controllers'], function()
{
    Route::get('/', 'TransaksiController@index');

    //== Pesanan ==//
    Route::get('pesanan/gettodaypesanan','PesananController@getTodayPesanan');
    Route::post('pesanan/pesananselesai','PesananController@pesananSelesai');
    Route::get('pesanan/loaddatabulanan','PesananController@loadDataBulanan');
    Route::get('pesanan/loaddata','PesananController@loadData');
    Route::get('pesanan/edit/{id}','PesananController@edit');
    Route::post('pesanan/delete','PesananController@delete');
    Route::get('pesanan/index2/{status}','PesananController@index2');
    Route::resource('pesanan','PesananController');

    //== Pembayaran ==//
    // Route::post('pesanan/pesananselesai','PembayaranController@pesananSelesai');
    Route::get('pembayaran/loaddata','PembayaranController@loadData');
    Route::get('pembayaran/cetaknota','PembayaranController@cetakNota');
    // Route::get('pembayaran/edit/{id}','PembayaranController@edit');
    // Route::get('pembayaran/create/{id}','PembayaranController@create');
    Route::get('pembayaran/addeditpembayaran/{id}','PembayaranController@addEditPembayaran');
    Route::post('pembayaran/update','PembayaranController@update');
    Route::post('pembayaran/store','PembayaranController@store');
    Route::post('pembayaran/delete','PembayaranController@delete');
    Route::resource('pembayaran','PembayaranController');

    //== Laporan ==//
    Route::get('laporan/createlaporanpesanan','LaporanController@createLaporanPesanan');
    Route::resource('laporan','LaporanController');
});
