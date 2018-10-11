<?php


Route::get('/','TestController@test');

Route::get('test','TestController@test');
Route::post('test','TestController@upload')->name('subirarchivo');
Route::post('/import-excel', 'ExcelController@importUsers');