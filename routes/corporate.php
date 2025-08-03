<?php

Route::get('/', 'CorporateController@dashboard')->name('index');
Route::get('/dashboard', 'CorporateController@dashboard')->name('dashboard');


Route::get('profile', 'CorporateController@profile')->name('profile');
Route::post('profile', 'CorporateController@profile_update')->name('profile.update');

Route::get('password', 'CorporateController@password')->name('password');
Route::post('password', 'CorporateController@password_update')->name('password.update');


/*Group CRUD*/
Route::resource('group', 'CorporateGroupController');

/*User CRUD*/
Route::resource('user', 'CorporateUserController');


Route::get('/statement', 'CorporateController@statement')->name('ride.statement');
Route::post('/statement-content', 'CorporateController@statement_content')->name('statement-content');
Route::get('/statement/today', 'CorporateController@statement_today')->name('ride.statement.today');
Route::get('/statement/monthly', 'CorporateController@statement_monthly')->name('ride.statement.monthly');
Route::get('/statement/yearly', 'CorporateController@statement_yearly')->name('ride.statement.yearly');

Route::get('requests/{id}', 'Resource\TripResource@corporateshow')->name('requests.show');

Route::get('/driver-movement', 'DispatchController@driver_movement')->name('drivermovement');
Route::get('/main', 'MainController@index')->name('main');
Route::get('/listall', 'MainController@listall')->name('listall');
Route::get('/showdetail', 'MainController@showdetail')->name('showdetail');
Route::get('/editdetail/{trip}', 'MainController@editdetail')->name('editdetail');
Route::patch('/storedetail/{trip}', 'MainController@storedetail')->name('storedetail');
Route::get('/canceldetail/{trip}', 'MainController@canceldetail')->name('canceldetail');
Route::get('/autotrip/{trip}', 'MainController@autotrip')->name('autotrip');
Route::get('/schedule', 'MainController@schedule')->name('schedule');
Route::get('/providers', 'MainController@providers')->name('providers');
Route::get('/trips/{trip}/{provider}', 'MainController@assign')->name('assign');
Route::post('/storecomment', 'MainController@storecomment')->name('storecomment');

Route::group(['as' => 'dispatch.', 'prefix' => 'dispatch'], function () {
    Route::get('/', 'DispatchController@index')->name('index');
    Route::post('/', 'DispatchController@store')->name('store');
    Route::post('/corporate', 'DispatchController@corporate_booking')->name('corporate');
    Route::get('/map', 'DispatchController@map_ajax')->name('map');
    Route::get('/users-corporate', 'DispatchController@users_corporate')->name('users-corporate');
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
Route::get('/webnotify', 'WebNotifyController@index');
Route::post('/clear-notify', 'WebNotifyController@clearall');
