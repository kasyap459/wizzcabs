<?php

/*
|--------------------------------------------------------------------------
| Hotel Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', 'HotelController@index');
Route::get('/dashboard', 'HotelController@index');
Route::post('/create/ride', 'HotelController@create_ride');
// trips
Route::get('/trips', 'HotelController@trips');
Route::get('/upcoming/trips', 'HotelController@upcoming_trips');
Route::post('/cancel/ride', 'HotelController@cancel_request');
// user profiles
Route::get('/profile', 'HotelController@profile');
Route::get('/edit/profile', 'HotelController@edit_profile');
Route::post('/profile', 'HotelController@update_profile');
// update password
Route::get('/change/password', 'HotelController@change_password');
Route::post('/change/password', 'HotelController@update_password');

Route::post('/fare', 'HotelController@fare_calculate');