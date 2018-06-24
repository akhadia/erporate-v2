<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//=== Permission ===//
Route::get('permission/loaddata','PermissionController@loadData');
Route::post('permission/delete','PermissionController@delete');
Route::resource('permission','PermissionController');

//=== Role ===//
Route::get('role/loaddata','RoleController@loadData');
Route::post('role/delete','RoleController@delete');
Route::resource('role','RoleController');

//=== User ===//
Route::get('user/loaddata','UserController@loadData');
Route::post('user/delete','UserController@delete');
Route::post('user/cekusername','UserController@cekUsername');
Route::post('user/cekemail','UserController@cekEmail');
Route::resource('user','UserController');
