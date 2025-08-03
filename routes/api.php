<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserResources\UserApiController;
use App\Http\Controllers\UserResources\UserLoginApiController;
use App\Http\Controllers\UserResources\UserProfileController;
use App\Http\Controllers\Resource\CardResource;
use App\Http\Controllers\UserResources\UserTripApiController;

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
Route::post('register/otp',[UserLoginApiController::class, 'send_otp']);
Route::get('user-notes',[UserApiController::class, 'usernotes']);
Route::get('/mapkey',[UserApiController::class, 'map_key']);
Route::post('/login',[UserLoginApiController::class, 'login']);
Route::post('/signup',[UserLoginApiController::class, 'signup']);

Route::post('/mobile',[UserLoginApiController::class, 'send_mobile']);
Route::post('/otp',[UserLoginApiController::class, 'otp_verify']);

Route::post('/resend',[UserLoginApiController::class, 'resend_otp']);
Route::post('/forgot/password',[UserLoginApiController::class, 'forgot_password']);
Route::post('/reset/password',[UserLoginApiController::class, 'reset_password']);

Route::get('/cardkey',[CardResource::class, 'customer_key']);
Route::get('/help',[UserLoginApiController::class, 'help_details']);

Route::group(['middleware' => ['auth:api']], function () {		
	
	Route::get('logout',[UserLoginApiController::class, 'logout']);
	Route::post('/change/password',[UserProfileController::class, 'change_password']);
	Route::post('/update/profile',[UserProfileController::class, 'update_profile']);
	Route::get('wallet/history',[UserProfileController::class, 'user_wallet_history']);
	Route::post('/add/money' , 'PaymentController@add_money');
	
	Route::resource('card', 'Resource\CardResource');

	Route::get('/estimated/fare',[UserApiController::class, 'estimated_fare']);
	Route::get('details',[UserApiController::class, 'details']);
	
	Route::get('user-ratings',[UserApiController::class, 'userratings']);

	// Trip based Apis
	// Route::post('/send/request', 'UserApiController@send_request');
	Route::post('/send/request',[UserTripApiController::class, 'send_request']);	
	Route::post('/cancel/request', 'UserResources\UserApiController@cancel_request');
	Route::post('/status' , 'UserResources\UserApiController@status');	
	Route::post('/rate/provider' , 'UserResources\UserApiController@rate_provider');	
	Route::post('/all/trips',[UserApiController::class, 'all_trips']);
	Route::post('/past/detail/{id}', 'UserResources\UserApiController@past_details');		
	Route::post('/invoice-copy',[UserApiController::class, 'invoice_copy']);
	
	// payment
	Route::get('/payment_mode', 'PaymentController@payment_mode');
	Route::post('/add/money' , 'PaymentController@add_money');
	
	// promocode	
	Route::get('/promocode/list' , 'UserResources\UserApiController@list_promocode');
	Route::post('/promocode/add' , 'UserResources\UserApiController@add_promocode');
	Route::get('/clear/status' , 'UserResources\UserApiController@clear_status');
	Route::get('/pushnotification',[UserApiController::class, 'pushnotification']);

	// address
    Route::get('/address/list' , 'UserResources\UserApiController@address_list');	
	Route::post('/address/add' , 'UserResources\UserApiController@address_add');
	Route::post('/address/update' , 'UserResources\UserApiController@address_update');
	Route::get('/address/delete/{id}' , 'UserResources\UserApiController@address_delete');	

	Route::post('/user-care',[UserApiController::class, 'usercare']);
	Route::get('/user-care',[UserApiController::class, 'getusercare']);

	Route::get('/contact/list' , 'UserResources\UserApiController@contact_list');
	Route::post('/contact/add' , 'UserResources\UserApiController@add_contact');
	Route::post('/contact/delete' , 'UserResources\UserApiController@delete_contact');

	Route::post('/fav/provider',[UserApiController::class, 'fav_driver']);
	Route::get('/tips',[UserApiController::class, 'tips']);
	Route::get('/stops',[UserApiController::class, 'stops']);
	Route::post('/delete',[UserApiController::class, 'delete']);
});