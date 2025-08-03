<?php

/*
|--------------------------------------------------------------------------
| Account Routes
|--------------------------------------------------------------------------
*/

Route::get('/', 'AccountController@dashboard')->name('index');
Route::get('/dashboard', 'AccountController@dashboard')->name('dashboard');
Route::get('/content', 'AccountController@content')->name('content');
Route::resource('provider', 'Resource\ProviderResource');
Route::get('requests/{id}', 'Resource\TripResource@Accountshow')->name('requests.show');

Route::get('profile', 'AccountController@profile')->name('profile');
Route::post('profile', 'AccountController@profile_update')->name('profile.update');

Route::get('password', 'AccountController@password')->name('password');
Route::post('password', 'AccountController@password_update')->name('password.update');

// statements

Route::group(['as' => 'provider.'], function () {
    Route::get('provider/{id}/statement', 'Resource\ProviderResource@Accountstatement')->name('statement');
    Route::post('provider-content', 'Resource\ProviderResource@account_content')->name('provider-content');
});

Route::get('/statement', 'AccountController@statement')->name('ride.statement');
Route::post('/statement-content', 'AccountController@statement_content')->name('statement-content');
Route::get('/statement/provider', 'AccountController@statement_provider')->name('ride.statement.provider');
Route::post('/statement-providerlist', 'AccountController@statement_providerlist')->name('statement-providerlist');
Route::get('/statement/range', 'AccountController@statement_range')->name('ride.statement.range');
Route::get('/statement/today', 'AccountController@statement_today')->name('ride.statement.today');
Route::get('/statement/monthly', 'AccountController@statement_monthly')->name('ride.statement.monthly');
Route::get('/statement/yearly', 'AccountController@statement_yearly')->name('ride.statement.yearly');