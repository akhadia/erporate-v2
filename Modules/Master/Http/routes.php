<?php

Route::group(['middleware' => 'web', 'prefix' => 'master', 'namespace' => 'Modules\Master\Http\Controllers'], function()
{
    Route::get('/', 'MasterController@index');

    //== Kategori ==//
    Route::get('kategori/loaddata','KategoriController@loadData');
    Route::get('kategori/edit','KategoriController@edit');
    Route::post('kategori/delete','KategoriController@delete');
    Route::resource('kategori','KategoriController');

    //== Produk ==//
    Route::get('produk/loaddata','ProdukController@loadData');
    Route::get('produk/edit','ProdukController@edit');
    Route::post('produk/delete','ProdukController@delete');
    Route::resource('produk','ProdukController');

    //== Meja ==//
    Route::get('meja/loaddata','MejaController@loadData');
    Route::get('meja/edit','MejaController@edit');
    Route::post('meja/delete','MejaController@delete');
    Route::resource('meja','MejaController');










});
