<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Auth::routes();
//Route::get('/', 'TestController@test')->name('home');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'TestController@test')->name('index');
    
    Route::post('importar', 'TestController@upload')->name('subirarchivo');
    Route::get('exportar/transaction/{transactionId}', 'TestController@exportView')->name('exportarView');
    Route::post('exportar', 'TestController@exportExcel')->name('exportarFile');
    
});

