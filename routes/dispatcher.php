<?php

Route::get('/', 'DispatchController@index')->name('index');

Route::get('password', 'DispatchController@password')->name('password');
Route::post('password', 'DispatchController@password_update')->name('password.update');
Route::get('profile', 'DispatchController@profile')->name('profile');
Route::post('profile', 'DispatchController@profile_update')->name('profile.update');
Route::get('/providers', 'MainController@providers')->name('providers');

Route::group(['as' => 'dispatch.', 'prefix' => 'dispatch'], function () {
    Route::get('/', 'DispatchController@index')->name('index');
    Route::post('/', 'DispatchController@store')->name('store');
    Route::post('/corporate', 'DispatchController@corporate_booking')->name('corporate');
    Route::get('/map', 'DispatchController@map_ajax')->name('map');
    Route::get('/users-phone', 'DispatchController@users_phone')->name('users-phone');
    Route::get('/users-email', 'DispatchController@users_email')->name('users-email');
    Route::get('/corporate-user', 'DispatchController@corporate_user')->name('corporate-user');
    Route::get('/viewtrip/{trip}', 'DispatchController@viewtrip')->name('viewtrip');
    Route::get('/driver-list', 'DispatchController@driver_list')->name('driver-list');
    Route::get('/ride-list', 'DispatchController@ride_list')->name('ride-list');
    Route::get('/ride-list', 'DispatchController@ride_list')->name('ride-list');
    Route::get('/assign/{trip}/{provider}', 'DispatchController@assign')->name('assign');
    Route::post('/fare', 'DispatchController@fare_calculate')->name('fare');
});

Route::get('/driver-movement', 'DispatchController@driver_movement')->name('drivermovement');
Route::get('/main', 'MainController@index')->name('main');
Route::get('/listall', 'MainController@listall')->name('listall');
Route::get('/showdetail', 'MainController@showdetail')->name('showdetail');
Route::get('/editdetail/{trip}', 'MainController@editdetail')->name('editdetail');
Route::patch('/storedetail/{trip}', 'MainController@storedetail')->name('storedetail');
Route::get('/canceldetail/{trip}', 'MainController@canceldetail')->name('canceldetail');
Route::get('/autotrip/{trip}', 'MainController@autotrip')->name('autotrip');
Route::get('/schedule', 'MainController@schedule')->name('schedule');
Route::get('/trips/{trip}/{provider}', 'MainController@assign')->name('assign');
Route::post('/storecomment', 'MainController@storecomment')->name('storecomment');

Route::get('/routedetail/{trip}', 'MainController@routedetail')->name('routedetail');
Route::get('/webnotify', 'WebNotifyController@index');
Route::post('/clear-notify', 'WebNotifyController@clearall');