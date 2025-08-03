<?php

Route::get('/', 'CustomercareController@dashboard')->name('index');
Route::get('/dashboard', 'CustomercareController@dashboard')->name('dashboard');
Route::get('/usercare', 'UsercareController@index')->name('usercare');
Route::get('/inprogress/{id}', 'UsercareController@inprogress')->name('inprogress');
Route::get('/closed/{id}', 'UsercareController@closed')->name('closed');
