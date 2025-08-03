<?php

/*
|--------------------------------------------------------------------------
| Provider Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'ProviderController@index')->name('index');
Route::get('/dashboard', 'ProviderController@index')->name('index');
Route::get('/earnings', 'ProviderController@earnings')->name('earnings');
Route::get('/upcoming', 'ProviderController@upcoming_trips')->name('upcoming');

Route::get('/documents', 'ProviderController@document_index')->name('documents');
Route::post('/upload/{id}', 'ProviderController@document_upload')->name('upload');
Route::post('/destroy/{id}', 'ProviderController@document_destroy')->name('destroy');
// Route::post('login', 'ProvideAuth/LoginController@authenticate')->name('login');
Route::get('/profile', 'ProviderController@profile');
Route::get('/edit/profile', 'ProviderController@edit_profile');
Route::post('/profile', 'ProviderController@update_profile');

Route::get('/location', 'ProviderController@location_edit')->name('location.index');
Route::post('/location', 'ProviderController@location_update')->name('location.update');

Route::get('/profile/password', 'ProviderController@change_password')->name('change.password');
Route::post('/change/password', 'ProviderController@update_password')->name('password.update');
