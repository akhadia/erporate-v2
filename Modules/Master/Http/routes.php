<?php

Route::group(['middleware' => 'web', 'prefix' => 'master', 'namespace' => 'Modules\Master\Http\Controllers'], function()
{
    Route::get('/', 'MasterController@index');

    //== Kategori ==//
    Route::get('kategori/loaddata','KategoriController@loadData');
    Route::get('kategori/edit','KategoriController@edit');
    Route::post('kategori/delete','KategoriController@delete');
    Route::resource('kategori','KategoriController');










});
