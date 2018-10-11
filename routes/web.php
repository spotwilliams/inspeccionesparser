<?php


Route::get('/','TestController@test');

Route::get('test','TestController@test')->name('index');
Route::post('importar','TestController@upload')->name('subirarchivo');
Route::get('exportar/transaction/{transactionId}','TestController@exportView')->name('exportarView');
Route::post('exportar','TestController@exportExcel')->name('exportarFile');
