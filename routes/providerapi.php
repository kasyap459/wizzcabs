<?php

use App\Http\Controllers\UserResources\UserLoginApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Authentication
Route::post('/register', 'ProviderResources\TokenController@register');
Route::post('/mobile', 'ProviderResources\TokenController@send_mobile');
Route::post('/oauth/token', 'ProviderResources\TokenController@authenticate');
Route::post('/logout', 'ProviderResources\TokenController@logout');
Route::post('/send_otp', 'ProviderResources\TokenController@send_otp');

Route::post('/upload-file', [UserLoginApiController::class, 'upload_file']);

Route::post('/forgot/password', 'ProviderResources\TokenController@forgot_password');
Route::post('/reset/password', 'ProviderResources\TokenController@reset_password');
Route::get('/help', 'ProviderResources\TokenController@help_details');
Route::get('/testpushnotification', 'ProviderResources\TripController@testpushnotificationsss');
Route::get('/mapkey', 'ProviderResources\TripController@map_key');

Route::group(['middleware' => ['provider.api']], function () {
    Route::group(['prefix' => 'profile'], function () {

        Route::get('/', 'ProviderResources\ProfileController@index');
        Route::post('/upload-document', 'ProviderResources\ProfileController@uploadDocument');
        Route::post('/save-bank-details', 'ProviderResources\ProfileController@saveBankDetails');

        Route::post('/', 'ProviderResources\ProfileController@update');
        Route::post('/password', 'ProviderResources\ProfileController@password');
        Route::post('/available', 'ProviderResources\ProfileController@available');

        Route::post('/upload', 'ProviderResources\ProfileController@upload_document');
        Route::post('/delete', 'ProviderResources\ProfileController@destroy_document');
        Route::get('/getdocuments', 'ProviderResources\ProfileController@get_documents');
    });

    Route::get('/wallet/history', 'ProviderResources\ProfileController@provider_wallet_history');
    Route::get('/pushNotification', 'ProviderResources\TripController@pushNotification');
    Route::post('/streetride/request', 'ProviderResources\StreetController@streetride_request');
    Route::post('/additional/fare', 'ProviderResources\TripController@additional_fare');
    Route::post('/payment/update', 'ProviderResources\TripController@payment_update');
    Route::post('/update/destination', 'ProviderResources\TripController@update_destination');
    Route::post('/summary', 'ProviderResources\TripController@summary');
    Route::get('/earnings', 'ProviderResources\TripController@earnings');
    Route::get('/earning/details', 'ProviderResources\TripController@earning_details');
    Route::post('/invoice-copy', 'ProviderResources\TripController@invoice_copy');
    Route::get('/contact/list', 'ProviderResources\TripController@contact_list');
    Route::post('/contact/add', 'ProviderResources\TripController@add_contact');
    Route::post('/contact/delete', 'ProviderResources\TripController@delete_contact');

    Route::post('/cashout/request', 'ProviderResources\TripController@cashout_request');
    Route::get('/cashout/list', 'ProviderResources\TripController@cashout_list');
    Route::post('/delete', 'ProviderResources\TokenController@delete');

    Route::group(['prefix' => 'requests'], function () {
        Route::post('/status', 'ProviderResources\ProviderStatusController@status');
        Route::get('/destroy/{id}', 'ProviderResources\ProviderStatusController@destroy_notification');
        Route::post('/accept/{id}', 'ProviderResources\TripController@accept_trips');
        Route::post('/cancel', 'ProviderResources\TripController@cancel_trips');
        Route::get('/completed', 'ProviderResources\TripController@completed_trips');
        Route::post('/past/detail/{id}', 'ProviderResources\TripController@past_details');
        Route::post('/started/{id}', 'ProviderResources\TripController@start_trips');
        Route::post('/arrived/{id}', 'ProviderResources\TripController@arrived_trips');
        Route::post('/pickedup/{id}', 'ProviderResources\TripController@pickedup_trips');
        Route::post('/dropped/{id}', 'ProviderResources\TripController@dropped_trips');
        Route::post('/end/{id}', 'ProviderResources\TripController@end_trips');
        Route::post('/rate/{id}', 'ProviderResources\TripController@rate');
        Route::post('/all/trips', 'ProviderResources\TripController@all_trips');
        Route::post('/clear/status', 'ProviderResources\TripController@clear_status');
    });
});