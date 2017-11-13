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

/**
 * Public site routes
 */
Route::group(['namespace' => 'Index'], function () {
    Route::get('/', ['uses' => 'IndexIndexController@showProducts']);
    Route::post('/', ['uses' => 'IndexIndexController@addProduct']);
    Route::post('delete', ['uses' => 'IndexIndexController@deleteProduct']);
    Route::post('update', ['uses' => 'IndexIndexController@updateProduct']);
});
