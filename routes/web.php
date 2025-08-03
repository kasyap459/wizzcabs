<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\WebsiteController;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Http;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');

Auth::routes();

Route::get('/', 'WebsiteController@index');
Route::post('/booktaxi', 'WebsiteController@book_taxi');

Route::get('/about', function () {
  return view('web.about');
});

Route::get('/contact', function () {
 return view('web.contact');
});

Route::get('/faq', function () {
    return view('about');
});


//Language Routes
Route::get('/es', 'WebsiteController@sp_index');
Route::get('/about/es', function () {
  return view('web_sp.about');
});
Route::get('/contact/es', function () {
  return view('web_sp.contact');
 });
 Route::get('/book-taxi/es', 'MainController@book_taxi_sp');
 Route::get('/privacy/es', function () {
  return view('web_sp.privacy');
 });
 Route::get('/terms-conditions/es', function () {
  return view('web_sp.terms');
 });

 Route::get('/google', function () {
  return view('web.terms');
 });
/*Route::get('privacy', function () {
    return view('privacy');
});

Route::get('terms-conditions', function () {
    return view('term');
});*/

Route::get('/privacy', 'MainController@privacy');
Route::get('/terms-conditions', 'MainController@terms');
Route::get('/book-taxi', 'MainController@book_taxi');

Route::get('auth/google', [WebsiteController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [WebsiteController::class, 'handleGoogleCallback']);
Route::get('auth/facebook', [WebsiteController::class, 'facebookRedirect'])->name('login.facebook');
Route::get('auth/facebook/callback', [WebsiteController::class, 'facebookCallback']);
Route::get('auth/apple', [WebsiteController::class, 'appleRedirect'])->name('login.apple');
Route::get('auth/apple/callback', [WebsiteController::class, 'appleCallback']);

Route::group(['prefix' => 'admin'], function () {
  Route::get('/login', 'AdminAuth\LoginController@showLoginForm');
  Route::post('/login', 'AdminAuth\LoginController@login');
  Route::post('/logout', 'AdminAuth\LoginController@logout');

  Route::post('/password/email', 'AdminAuth\ForgotPasswordController@sendResetLinkEmail');
  Route::post('/password/reset', 'AdminAuth\ResetPasswordController@reset');
  Route::get('/password/reset', 'AdminAuth\ForgotPasswordController@showLinkRequestForm');
  Route::get('/password/reset/{token}', 'AdminAuth\ResetPasswordController@showResetForm');
});


Route::group(['prefix' => 'corporate'], function () {
  Route::get('/login', 'CorporateAuth\LoginController@showLoginForm');
  Route::post('/login', 'CorporateAuth\LoginController@login');
  Route::post('/logout', 'CorporateAuth\LoginController@logout');

  Route::get('/register', 'CorporateAuth\RegisterController@showRegistrationForm');
  Route::post('/register', 'CorporateAuth\RegisterController@register');

  Route::post('/password/email', 'CorporateAuth\ForgotPasswordController@sendResetLinkEmail');
  Route::post('/password/reset', 'CorporateAuth\ResetPasswordController@reset');
  Route::get('/password/reset', 'CorporateAuth\ForgotPasswordController@showLinkRequestForm');
  Route::get('/password/reset/{token}', 'CorporateAuth\ResetPasswordController@showResetForm');
});

Route::group(['prefix' => 'account'], function () {
  Route::get('/login', 'AccountAuth\LoginController@showLoginForm');
  Route::post('/login', 'AccountAuth\LoginController@login');
  Route::post('/logout', 'AccountAuth\LoginController@logout');

  Route::get('/register', 'AccountAuth\RegisterController@showRegistrationForm');
  Route::post('/register', 'AccountAuth\RegisterController@register');

  Route::post('/password/email', 'AccountAuth\ForgotPasswordController@sendResetLinkEmail');
  Route::post('/password/reset', 'AccountAuth\ResetPasswordController@reset');
  Route::get('/password/reset', 'AccountAuth\ForgotPasswordController@showLinkRequestForm');
  Route::get('/password/reset/{token}', 'AccountAuth\ResetPasswordController@showResetForm');
});

Route::group(['prefix' => 'provider'], function () {
  
  Route::post('/sendotp', 'ProviderAuth\RegisterController@send_otp');
  Route::post('/verifyotp', 'ProviderAuth\RegisterController@verify_otp');
  Route::post('/sendotp/login', 'ProviderAuth\LoginController@send_otp');
  Route::post('/verifyotplogin', 'ProviderAuth\LoginController@verify_otp');

  Route::get('/login', 'ProviderAuth\LoginController@showLoginForm');
  Route::get('/login/es', 'ProviderAuth\LoginController@showLoginForm_sp');
  Route::post('/login', 'ProviderAuth\LoginController@login');
  Route::post('/logout', 'ProviderAuth\LoginController@logout');

  Route::get('/register', 'ProviderAuth\RegisterController@showRegistrationForm');
  Route::post('/register', 'ProviderAuth\RegisterController@register');

  Route::post('/password/email', 'ProviderAuth\ForgotPasswordController@sendResetLinkEmails');
  Route::post('/password/reset', 'ProviderAuth\ResetPasswordController@reset');
  Route::get('/password/reset', 'ProviderAuth\ForgotPasswordController@showLinkRequestForm');
  Route::get('/password/reset/es', 'ProviderAuth\ForgotPasswordController@showLinkRequestForm_sp');
  Route::get('/password/reset/{token}', 'ProviderAuth\ResetPasswordController@showResetForm');
});

Route::group(['prefix' => 'dispatcher'], function () {
  Route::get('/login', 'DispatcherAuth\LoginController@showLoginForm');
  Route::post('/login', 'DispatcherAuth\LoginController@login');
  Route::post('/logout', 'DispatcherAuth\LoginController@logout');

  Route::get('/register', 'DispatcherAuth\RegisterController@showRegistrationForm');
  Route::post('/register', 'DispatcherAuth\RegisterController@register');

  Route::post('/password/email', 'DispatcherAuth\ForgotPasswordController@sendResetLinkEmail');
  Route::post('/password/reset', 'DispatcherAuth\ResetPasswordController@reset');
  Route::get('/password/reset', 'DispatcherAuth\ForgotPasswordController@showLinkRequestForm');
  Route::get('/password/reset/{token}', 'DispatcherAuth\ResetPasswordController@showResetForm');
});

Route::group(['prefix' => 'partner'], function () {
  Route::get('/login', 'PartnerAuth\LoginController@showLoginForm');
  Route::post('/login', 'PartnerAuth\LoginController@login');
  Route::post('/logout', 'PartnerAuth\LoginController@logout');
  Route::get('/register', 'PartnerAuth\RegisterController@showRegistrationForm');
  Route::post('/register', 'PartnerAuth\RegisterController@register');

  Route::post('/password/email', 'PartnerAuth\ForgotPasswordController@sendResetLinkEmail');
  Route::post('/password/reset', 'PartnerAuth\ResetPasswordController@reset');
  Route::get('/password/reset', 'PartnerAuth\ForgotPasswordController@showLinkRequestForm');
  Route::get('/password/reset/{token}', 'PartnerAuth\ResetPasswordController@showResetForm');
});

Route::group(['prefix' => 'hotel'], function () {
  Route::get('/login', 'HotelAuth\LoginController@showLoginForm');
  Route::post('/login', 'HotelAuth\LoginController@login');
  Route::post('/logout', 'HotelAuth\LoginController@logout');

  Route::post('/password/email', 'HotelAuth\ForgotPasswordController@sendResetLinkEmail');
  Route::post('/password/reset', 'HotelAuth\ResetPasswordController@reset');
  Route::get('/password/reset', 'HotelAuth\ForgotPasswordController@showLinkRequestForm');
  Route::get('/password/reset/{token}', 'HotelAuth\ResetPasswordController@showResetForm');
});

Route::group(['prefix' => 'customercare'], function () {
  Route::get('/login', 'CustomercareAuth\LoginController@showLoginForm');
  Route::post('/login', 'CustomercareAuth\LoginController@login');
  Route::post('/logout', 'CustomercareAuth\LoginController@logout');

  Route::get('/register', 'CustomercareAuth\RegisterController@showRegistrationForm');
  Route::post('/register', 'CustomercareAuth\RegisterController@register');

  Route::post('/password/email', 'CustomercareAuth\ForgotPasswordController@sendResetLinkEmail');
  Route::post('/password/reset', 'CustomercareAuth\ResetPasswordController@reset');
  Route::get('/password/reset', 'CustomercareAuth\ForgotPasswordController@showLinkRequestForm');
  Route::get('/password/reset/{token}', 'CustomercareAuth\ResetPasswordController@showResetForm');
});

Route::get('/guest/verify/{id}', 'CorporateUserController@guest_verify')->name('guest.verify')->middleware('signed');
Route::post('/guest/verify/sendmobile', 'CorporateUserController@send_mobile')->name('guest.mobile');
Route::post('/guest/verify/sendotp', 'CorporateUserController@send_otp')->name('guest.otp');
Route::post('/guest/verify/sendaccount', 'CorporateUserController@send_account')->name('guest.account');


/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/
Route::post('/login2','Auth\LoginController@showLoginForm');
Route::post('/password/email2','Auth\LoginController@sendResetLinkEmails');

// Route::post('/login2',[LoginController::class,'login']);
Route::post('/sendotp', 'Auth\RegisterController@send_otp');
Route::post('/verifyotp', 'Auth\RegisterController@verify_otp');

Route::post('/sendotplogin', 'Auth\LoginController@send_otp');
Route::post('/verifyotplogin', 'Auth\LoginController@verify_otp');

Route::get('/dashboard', 'HomeController@index');
Route::post('/create/ride', 'HomeController@create_ride');
// trips
Route::get('/trips', 'HomeController@trips');
Route::get('/upcoming/trips', 'HomeController@upcoming_trips');
Route::post('/cancel/ride', 'HomeController@cancel_request');
    Route::post('/fare', 'HomeController@fare_calculate')->name('fare');

// user profiles
Route::get('/profile', 'HomeController@profile');
Route::get('/edit/profile', 'HomeController@edit_profile');
Route::post('/profile', 'HomeController@update_profile');
Route::get('/payment', 'HomeController@payment');
Route::get('/addcard', 'HomeController@add_card');
Route::resource('usercard', 'Resource\CardResource');
Route::post('/payment', 'PaymentController@payment');
Route::get('/promotions', 'HomeController@promotions_index')->name('promocodes.index');
Route::post('/promotions', 'HomeController@promotions_store')->name('promocodes.store');
Route::get('/wallet', 'HomeController@wallet');
Route::post('/add/money', 'PaymentController@add_money');
// update password
Route::get('/change/password', 'HomeController@change_password');
Route::post('/change/password', 'HomeController@update_password');


// Route::get('/testnotification', function () {

//   $fcm = "ftgU80JTjk1Lv0d2VTGGHg:APA91bFTKaLOVxNHJfcao92o36VMgSG84lN9DJlgBqy4QZjIf8j2MuuZunX7yZdtL-WSs7glN6pZp1Qi9EAFtO7hLfW92I0PXSaYMGKGsCGkxU3rx_5fM0lPogqoh8SaOYfFOBJgqZaT";

//   $title = "DPKAR";
//   $description = "Kesav";

// //    $credentialsFilePath = "json/file.json";  // local
//   $credentialsFilePath = Http::get(asset('json/dpkar-426916-92bb4c881f31.json'));
//   $client = new GoogleClient();
//   $client->setAuthConfig($credentialsFilePath);
//   $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
//   $client->refreshTokenWithAssertion();
//   $token = $client->getAccessToken();

//   $access_token = $token['access_token'];

//   $headers = [
//       "Authorization: Bearer $access_token",
//       'Content-Type: application/json' 
//   ];

//   $data = [
//       "message" => [
//           "token" => $fcm,
//           "notification" => [
//               "title" => $title,
//               "body" => $description,
//           ],
//           'data' => [
//             'title'=>"CAB-E",
//              'body'=>$description,
//              'sound' => 'alerttonee.mp3'
//              ]
//       ]
//   ];
//   $payload = json_encode($data);

//   $ch = curl_init();
//   curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/dpkar-426916/messages:send');
//   curl_setopt($ch, CURLOPT_POST, true);
//   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//   curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
//   curl_setopt($ch, CURLOPT_VERBOSE, true); // Enable verbose output for debugging
//   $response = curl_exec($ch);
//   $err = curl_error($ch);
//   curl_close($ch);

//   if ($err) {
//       return response()->json([
//           'message' => 'Curl Error: ' . $err
//       ], 500);
//   } else {
//       return response()->json([
//           'message' => 'Notification has been sent',
//           'response' => json_decode($response, true)
//       ]);
//   }
// })->name('testnotification');





