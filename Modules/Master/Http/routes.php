<?php

Route::group(['middleware' => ['web','role:superadministrator'], 'prefix' => 'master', 'namespace' => 'Modules\Master\Http\Controllers'], function()
{
    Route::get('/', 'MasterController@index');
    
    //=== Autocomplete ===//
    Route::get('autocomplete/{method}','AutocompleteController@search');

    //== Kategori ==//
    Route::get('kategori/loaddata','KategoriController@loadData');
    Route::get('kategori/edit','KategoriController@edit');
    Route::post('kategori/delete','KategoriController@delete');
    Route::resource('kategori','KategoriController');

    //== Produk ==//
    Route::get('produk/loaddata','ProdukController@loadData');
    Route::get('produk/edit','ProdukController@edit');
    Route::post('produk/delete','ProdukController@delete');
    Route::get('produk/getproduk/{id}','ProdukController@getProduk');
    Route::get('produk/loaddatapopup','ProdukController@loadDataPopup');
    Route::get('produk/popupproduk','ProdukController@popupProduk');
    // Route::get('produk/loaddatapopup',['middleware' => ['role:pelayan'], 'uses' => 'ProdukController@loadDataPopup']);
    // Route::get('produk/popupproduk',['middleware' => ['role:pelayan'], 'uses' => 'ProdukController@popupProduk']);
    Route::resource('produk','ProdukController');

    //== Meja ==//
    Route::get('meja/loaddata','MejaController@loadData');
    Route::get('meja/edit','MejaController@edit');
    Route::post('meja/delete','MejaController@delete');
    Route::resource('meja','MejaController');










});
