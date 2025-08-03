<?php

/*
|--------------------------------------------------------------------------
| Partner Routes
|--------------------------------------------------------------------------
*/

Route::get('/', 'PartnerController@dashboard')->name('index');
Route::get('/content', 'PartnerController@content')->name('content');
Route::get('/dashboard', 'PartnerController@dashboard')->name('dashboard');

/*provider CRUD*/
Route::resource('provider', 'Resource\ProviderPartnerResource');
Route::post('providerrow', 'Resource\ProviderPartnerResource@provider_row')->name('provider.row');
Route::get('provider/{id}/approve', 'Resource\ProviderPartnerResource@approve')->name('provider.approve');
Route::get('provider/{id}/banned', 'Resource\ProviderPartnerResource@banned')->name('provider.banned');
Route::group(['as' => 'provider.'], function () {
	Route::get('provider/{id}/statement', 'Resource\ProviderPartnerResource@statement')->name('statement');
    Route::post('provider-content', 'Resource\ProviderPartnerResource@provider_content')->name('provider-content');
	Route::get('review/provider', 'PartnerController@provider_review')->name('review');
    Route::post('reviewprovider', 'PartnerController@reviewprovider_row')->name('reviewprovider');
    Route::get('provider/{id}/request', 'Resource\ProviderPartnerResource@request')->name('request');
    Route::resource('provider/{provider}/document', 'Resource\ProviderPartnerDocumentResource');
    Route::post('provider/{provider}/document/{document}', 'Resource\ProviderPartnerDocumentResource@upload')->name('document.upload');
    Route::post('document/remove/{id}', 'Resource\ProviderPartnerDocumentResource@service_destroy')->name('document.remove');
    Route::post('provider/{id}/logout', 'Resource\ProviderResource@logout')->name('logout');
});
Route::get('assignlist', 'Resource\ProviderPartnerResource@assign_list')->name('assignlist');
Route::post('assignrow', 'Resource\ProviderPartnerResource@assign_row')->name('assign.row');
Route::post('assignvehicle', 'Resource\ProviderPartnerResource@assign_vehicle')->name('assign.vehicle');
/*Vehicle Types CRUD*/
Route::resource('vehicle', 'Resource\VehiclePartnerResource');
Route::post('vehiclerow', 'Resource\VehiclePartnerResource@vehicle_row')->name('vehicle.row');
Route::get('vehicle/{id}/active', 'Resource\VehiclePartnerResource@active')->name('vehicle.active');
Route::get('vehicle/{id}/inactive', 'Resource\VehiclePartnerResource@inactive')->name('vehicle.inactive');
Route::group(['as' => 'vehicle.'], function () {
    Route::resource('vehicle/{vehicle}/document', 'Resource\VehiclePartnerDocumentResource');
    Route::post('vehicle/{vehicle}/document/{document}', 'Resource\VehiclePartnerDocumentResource@upload')->name('document.upload');
});

Route::resource('providerwallet', 'Resource\ProviderPartnerWalletController');
Route::get('providerwallet/{id}/credit', 'Resource\ProviderPartnerWalletController@credited')->name('provider.credit');
Route::post('providerwallet/debited', 'Resource\ProviderPartnerWalletController@debited')->name('providerwallet.debited');

Route::get('map', 'PartnerController@map_index')->name('map.index');
Route::get('map/ajax', 'PartnerController@map_ajax')->name('map.ajax');

Route::get('profile', 'PartnerController@profile')->name('profile');
Route::post('profile', 'PartnerController@profile_update')->name('profile.update');

Route::get('password', 'PartnerController@password')->name('password');
Route::post('password', 'PartnerController@password_update')->name('password.update');

Route::get('/statement', 'PartnerController@statement')->name('ride.statement');
Route::post('/statement-content', 'PartnerController@statement_content')->name('statement-content');
Route::get('/statement/today', 'PartnerController@statement_today')->name('ride.statement.today');
Route::get('/statement/monthly', 'PartnerController@statement_monthly')->name('ride.statement.monthly');
Route::get('/statement/yearly', 'PartnerController@statement_yearly')->name('ride.statement.yearly');

Route::get('/statement/provider', 'PartnerController@statement_provider')->name('ride.statement.provider');
Route::post('/statement-providerlist', 'PartnerController@statement_providerlist')->name('statement-providerlist');

Route::resource('requests', 'Resource\PartnerTripResource');
Route::post('requestsrow', 'Resource\PartnerTripResource@requests_row')->name('requests.row');
Route::get('scheduled', 'Resource\PartnerTripResource@scheduled')->name('requests.scheduled');

Route::group(['as' => 'dispatch.', 'prefix' => 'dispatch'], function () {
    Route::get('/', 'PartnerdispatchController@index')->name('index');
    Route::post('/', 'PartnerdispatchController@store')->name('store');
    // Route::get('/map', 'PartnerdispatchController@map_ajax')->name('map');
    // Route::get('/users-phone', 'PartnerdispatchController@users_phone')->name('users-phone');
    // Route::get('/users-email', 'PartnerdispatchController@users_email')->name('users-email');
    // Route::get('/corporate-user', 'PartnerdispatchController@corporate_user')->name('corporate-user');
    // Route::get('/viewtrip/{trip}', 'PartnerdispatchController@viewtrip')->name('viewtrip');
    // Route::get('/driver-list', 'PartnerdispatchController@driver_list')->name('driver-list');
    Route::get('/ride-list', 'PartnerdispatchController@ride_list')->name('ride-list');
    Route::get('/ride-list', 'PartnerdispatchController@ride_list')->name('ride-list');
    // Route::get('/assign/{trip}/{provider}', 'PartnerdispatchController@assign')->name('assign');
    // Route::post('/fare', 'PartnerdispatchController@fare_calculate')->name('fare');
    // Route::get('/', 'DispatchController@index')->name('index');
    // Route::post('/', 'DispatchController@store')->name('store');
    Route::post('/corporate', 'DispatchController@corporate_booking')->name('corporate');
    Route::get('/map', 'DispatchController@map_ajax')->name('map');
    Route::get('/users-corporate', 'DispatchController@users_corporate')->name('users-corporate');
    Route::get('/users-phone', 'DispatchController@users_phone')->name('users-phone');
    Route::get('/users-email', 'DispatchController@users_email')->name('users-email');
    Route::get('/corporate-user', 'DispatchController@corporate_user')->name('corporate-user');
    Route::get('/viewtrip/{trip}', 'DispatchController@viewtrip')->name('viewtrip');
    Route::get('/driver-list', 'DispatchController@driver_list')->name('driver-list');
    // Route::get('/ride-list', 'DispatchController@ride_list')->name('ride-list');
    // Route::get('/ride-list', 'DispatchController@ride_list')->name('ride-list');
    Route::get('/assign/{trip}/{provider}', 'DispatchController@assign')->name('assign');
    Route::post('/fare', 'DispatchController@fare_calculate')->name('fare');
});

Route::get('/main', 'PartnermainController@index')->name('main');
Route::get('/listall', 'PartnermainController@listall')->name('listall');
Route::get('/showdetail', 'PartnermainController@showdetail')->name('showdetail');
Route::get('/editdetail/{trip}', 'PartnermainController@editdetail')->name('editdetail');
Route::patch('/storedetail/{trip}', 'PartnermainController@storedetail')->name('storedetail');
Route::get('/canceldetail/{trip}', 'PartnermainController@canceldetail')->name('canceldetail');
Route::get('/autotrip/{trip}', 'PartnermainController@autotrip')->name('autotrip');
Route::get('/schedule', 'PartnermainController@schedule')->name('schedule');
Route::get('/providers', 'PartnermainController@providers')->name('providers');
// Route::get('/providers', 'MainController@providers')->name('providers');
Route::get('/trips/{trip}/{provider}', 'PartnermainController@assign')->name('assign');

Route::get('cancelled', 'Resource\PartnerTripResource@cancelled')->name('cancelled');
Route::post('cancelledrow', 'Resource\PartnerTripResource@cancelled_row')->name('cancelled.row');
Route::post('/storecomment', 'PartnermainController@storecomment')->name('storecomment');

Route::get('invoicelist', 'PartnerController@invoice_list')->name('invoicelist');
Route::get('/invoiceview/{id}', 'PartnerController@invoice_view')->name('invoiceview');
Route::get('/webnotify', 'WebNotifyController@index');
Route::post('/clear-notify', 'WebNotifyController@clearall');