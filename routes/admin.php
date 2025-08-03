<?php

Route::get('/', 'AdminController@dashboard')->name('index');
Route::get('/content', 'AdminController@content')->name('content');
Route::get('/dashboard', 'AdminController@dashboard')->name('dashboard');

Route::get('profile', 'AdminController@profile')->name('profile');
Route::post('profile', 'AdminController@profile_update')->name('profile.update');

Route::get('password', 'AdminController@password')->name('password');
Route::post('password', 'AdminController@password_update')->name('password.update');

Route::resource('country', 'Resource\CountryResource');

Route::get('/heatmap', 'AdminController@heatmap')->name('heatmap');
Route::get('map', 'AdminController@map_index')->name('map.index');
Route::get('map/ajax', 'AdminController@map_ajax')->name('map.ajax');
//Route::resource('complaint', 'ComplaintController');
/*User CRUD*/
Route::resource('user', 'Resource\UserResource');
Route::post('userrow', 'Resource\UserResource@user_row')->name('user.row');
Route::get('user/{id}/active', 'Resource\UserResource@active')->name('user.active');
Route::get('user/{id}/inactive', 'Resource\UserResource@inactive')->name('user.inactive');
Route::get('review/user', 'AdminController@user_review')->name('user.review');
Route::post('reviewuser', 'AdminController@reviewuser_row')->name('user.reviewuser');
Route::get('user/{id}/request', 'Resource\UserResource@request')->name('user.request');
Route::get('user/{id}/approve', 'Resource\UserResource@approve')->name('user.approve');
Route::get('user/{id}/banned', 'Resource\UserResource@banned')->name('user.banned');
/*provider CRUD*/
Route::resource('provider', 'Resource\ProviderResource');
Route::post('providerrow', 'Resource\ProviderResource@provider_row')->name('provider.row');
Route::get('provider/{id}/approve', 'Resource\ProviderResource@approve')->name('provider.approve');
Route::get('provider/{id}/banned', 'Resource\ProviderResource@banned')->name('provider.banned');
// Route::post('providerrow', 'Resource\ProviderResource@provider_row')->name('provider.row');
Route::get('driver-status', 'Resource\ProviderResource@track')->name('provider.track');
Route::post('trackrow', 'Resource\ProviderResource@track_row')->name('provider.trackrow');
Route::post('shiftrow', 'Resource\ProviderResource@shift_row')->name('shift.row');

Route::group(['as' => 'provider.'], function () {
    Route::get('provider/{id}/statement', 'Resource\ProviderResource@statement')->name('statement');
    Route::post('provider-content', 'Resource\ProviderResource@provider_content')->name('provider-content');
    Route::get('review/provider', 'AdminController@provider_review')->name('review');
    Route::post('reviewprovider', 'AdminController@reviewprovider_row')->name('reviewprovider');
    Route::get('provider/{id}/request', 'Resource\ProviderResource@request')->name('request');
    Route::resource('provider/{provider}/document', 'Resource\ProviderDocumentResource');
    Route::post('provider/{provider}/document/{document}', 'Resource\ProviderDocumentResource@upload')->name('document.upload');
    Route::get('/listallshiftmonth', 'Resource\ProviderResource@listallshiftmonth')->name('listallshiftmonth');
    Route::get('production-management/{id}/{date_detail}/shiftdetails', 'Resource\ProviderResource@shift_details')->name('shiftdetails');
    Route::get('/listallshift', 'Resource\ProviderResource@listallshift')->name('listallshift');
    Route::post('document/remove/{id}', 'Resource\ProviderDocumentResource@service_destroy')->name('document.remove');
    Route::post('provider/{id}/logout', 'Resource\ProviderResource@logout')->name('logout');
});
Route::get('assignlist', 'Resource\ProviderResource@assign_list')->name('assignlist');
Route::post('assignrow', 'Resource\ProviderResource@assign_row')->name('assign.row');
Route::post('assignvehicle', 'Resource\ProviderResource@assign_vehicle')->name('assign.vehicle');
/*dispatch CRUD*/
Route::resource('dispatch-manager', 'Resource\DispatcherResource');
Route::get('dispatch-manager/{id}/active', 'Resource\DispatcherResource@active')->name('dispatch-manager.active');
Route::get('dispatch-manager/{id}/inactive', 'Resource\DispatcherResource@inactive')->name('dispatch-manager.inactive');

/*partner CRUD*/
Route::resource('partner', 'Resource\PartnerResource');
Route::get('partner/{id}/active', 'Resource\PartnerResource@active')->name('partner.active');
Route::get('partner/{id}/inactive', 'Resource\PartnerResource@inactive')->name('partner.inactive');
Route::group(['as' => 'partner.'], function () {
    Route::get('partner/{id}/statement', 'Resource\PartnerResource@statement')->name('statement');
    Route::post('partner-content', 'Resource\PartnerResource@partner_content')->name('partner-content');
    Route::resource('partner/{partner}/document', 'Resource\PartnerDocumentResource');
    Route::post('partner/{partner}/document/{document}', 'Resource\PartnerDocumentResource@upload')->name('document.upload');
    Route::post('partner/invoice', 'PartnerInvoiceController@create')->name('invoice.create');
    Route::post('partner/invoicestore', 'PartnerInvoiceController@store')->name('invoice.store');
});

Route::get('/invoiceview/{id}', 'PartnerInvoiceController@show')->name('invoiceview');
Route::get('/invoiceedit/{id}', 'PartnerInvoiceController@edit')->name('invoiceedit');
Route::post('/invoiceupdate/{id}', 'PartnerInvoiceController@update')->name('invoiceupdate');
Route::post('/invoicedelete/{id}', 'PartnerInvoiceController@destroy')->name('invoicedelete');
Route::get('invoicelist', 'PartnerInvoiceController@index')->name('invoicelist');

/*corporate CRUD*/
Route::resource('corporate', 'Resource\CorporateResource');
Route::get('corporate/{id}/active', 'Resource\CorporateResource@active')->name('corporate.active');
Route::get('corporate/{id}/inactive', 'Resource\CorporateResource@inactive')->name('corporate.inactive');
Route::group(['as' => 'corporate.'], function () {
    Route::get('corporate/{id}/statement', 'Resource\CorporateResource@statement')->name('statement');
    Route::post('corporate-content', 'Resource\CorporateResource@corporate_content')->name('corporate-content');
    Route::resource('corporate/{corporate}/document', 'Resource\CorporateDocumentResource');
    Route::post('corporate/{corporate}/document/{document}', 'Resource\CorporateDocumentResource@upload')->name('document.upload');
    Route::post('corporate/invoice', 'CorporateInvoiceController@create')->name('invoice.create');
    Route::post('corporate/invoicestore', 'CorporateInvoiceController@store')->name('invoice.store');
});

Route::get('/corporateinvoiceview/{id}', 'CorporateInvoiceController@show')->name('corporateinvoiceview');
Route::get('/corporateinvoiceedit/{id}', 'CorporateInvoiceController@edit')->name('corporateinvoiceedit');
Route::post('/corporateinvoiceupdate/{id}', 'CorporateInvoiceController@update')->name('corporateinvoiceupdate');
Route::post('/corporateinvoicedelete/{id}', 'CorporateInvoiceController@destroy')->name('corporateinvoicedelete');
Route::get('corporateinvoicelist', 'CorporateInvoiceController@index')->name('corporateinvoicelist');

/*account-manager CRUD*/
Route::resource('account-manager', 'Resource\AccountResource');

Route::resource('hotel', 'Resource\HotelResource');
Route::get('hotel/{id}/active', 'Resource\HotelResource@active')->name('hotel.active');
Route::get('hotel/{id}/inactive', 'Resource\HotelResource@inactive')->name('hotel.inactive');

/*Vehicle Types CRUD*/
Route::resource('vehicle', 'Resource\VehicleResource');
Route::post('vehiclerow', 'Resource\VehicleResource@vehicle_row')->name('vehicle.row');
Route::get('vehicle/{id}/active', 'Resource\VehicleResource@active')->name('vehicle.active');
Route::get('vehicle/{id}/inactive', 'Resource\VehicleResource@inactive')->name('vehicle.inactive');
Route::group(['as' => 'vehicle.'], function () {
    Route::resource('vehicle/{vehicle}/document', 'Resource\VehicleDocumentResource');
    Route::post('vehicle/{vehicle}/document/{document}', 'Resource\VehicleDocumentResource@upload')->name('document.upload');
});
/*Service Types CRUD*/
Route::resource('service', 'Resource\ServiceResource');
Route::get('service/{id}/active', 'Resource\ServiceResource@active')->name('service.active');
Route::get('service/{id}/inactive', 'Resource\ServiceResource@inactive')->name('service.inactive');

/*Service Types CRUD*/
Route::resource('location', 'Resource\LocationResource');

Route::resource('restrict-location', 'Resource\RestrictLocationResource');
Route::get('restrict-location/{id}/active', 'Resource\RestrictLocationResource@active')->name('restrict-location.active');
Route::get('restrict-location/{id}/inactive', 'Resource\RestrictLocationResource@inactive')->name('restrict-location.inactive');

Route::get('shifts', 'Resource\ProviderResource@shifts')->name('shifts');
Route::get('production-management/{id}/shift', 'Resource\ProviderResource@shift')->name('shift');

/*Documents CRUD*/
Route::resource('document', 'Resource\DocListResource');
Route::get('vehicle-document', 'Resource\DocListResource@vehicleindex')->name('vehicledocument.index');
Route::get('partner-document', 'Resource\DocListResource@partnerindex')->name('partnerdocument.index');
Route::get('corporate-document', 'Resource\DocListResource@corporateindex')->name('corporatedocument.index');


/*Documents CRUD*/
Route::resource('customer-care', 'Resource\CustomerResource');
Route::get('customer-care/{id}/active', 'Resource\CustomerResource@active')->name('customer-care.active');
Route::get('customer-care/{id}/inactive', 'Resource\CustomerResource@inactive')->name('customer-care.inactive');

/*User Rates CRUD*/
Route::resource('user-rating', 'Resource\UserratingResource');
Route::get('user-rating/{id}/active', 'Resource\UserratingResource@active')->name('user-rating.active');
Route::get('user-rating/{id}/inactive', 'Resource\UserratingResource@inactive')->name('user-rating.inactive');

/*Usernotes CRUD*/
Route::resource('user-note', 'Resource\UsernotesResource');
Route::get('user-note/{id}/active', 'Resource\UsernotesResource@active')->name('user-note.active');
Route::get('user-note/{id}/inactive', 'Resource\UsernotesResource@inactive')->name('user-note.inactive');

Route::post('vehicledocstore', 'Resource\DocListResource@vehiclestore')->name('document.vehiclestore');
Route::post('carrierdocstore', 'Resource\DocListResource@carrierstore')->name('document.carrierstore');
Route::post('corporatedocstore', 'Resource\DocListResource@corporatestore')->name('document.corporatestore');

Route::post('vehicle/{id}/destroy', 'Resource\DocListResource@vehicledestroy')->name('document.vehicledestroy');
Route::post('carrier/{id}/destroy', 'Resource\DocListResource@carrierdestroy')->name('document.carrierdestroy');
Route::post('corporate/{id}/destroy', 'Resource\DocListResource@corporatedestroy')->name('document.corporatedestroy');

Route::post('driverdocupdate', 'Resource\DocListResource@driverupdate')->name('document.driverupdate');
Route::post('vehicledocupdate', 'Resource\DocListResource@vehicleupdate')->name('document.vehicleupdate');
Route::post('carrierdocupdate', 'Resource\DocListResource@carrierupdate')->name('document.carrierupdate');
Route::post('corporatedocupdate', 'Resource\DocListResource@corporateupdate')->name('document.corporateupdate');



Route::resource('faremodel', 'Resource\FareModelResource');
Route::get('faremodel/{id}/active', 'Resource\FareModelResource@active')->name('faremodel.active');
Route::get('faremodel/{id}/inactive', 'Resource\FareModelResource@inactive')->name('faremodel.inactive');

Route::resource('locationfare', 'Resource\LocationWiseFareResource');
Route::get('locationfare/{id}/active', 'Resource\LocationWiseFareResource@active')->name('locationfare.active');
Route::get('locationfare/{id}/inactive', 'Resource\LocationWiseFareResource@inactive')->name('locationfare.inactive');

Route::resource('poifare', 'Resource\PoiFareResource');
Route::get('poifare/{id}/active', 'Resource\PoiFareResource@active')->name('poifare.active');
Route::get('poifare/{id}/inactive', 'Resource\PoiFareResource@inactive')->name('poifare.inactive');

Route::get('settings', 'AdminController@settings')->name('settings');  
Route::post('settings/store', 'AdminController@settings_store')->name('settings.store');
Route::post('settings/store_business', 'AdminController@store_business')->name('settings.store_business');
Route::get('settings/payment', 'AdminController@settings_payment')->name('settings.payment');
Route::post('settings/payment', 'AdminController@settings_payment_store')->name('settings.payment.store');
Route::get('business', 'AdminController@business')->name('business');
Route::get('refferal', 'AdminController@refferal')->name('refferal');

/*Service Types CRUD*/
Route::resource('page', 'Resource\PageResource');

Route::resource('push', 'PushController');
Route::get('driver-push', 'PushController@driver_index')->name('push.driver.index');
Route::post('driver-store', 'PushController@driver_store')->name('push.driverstore');
Route::post('passenger-destroy', 'PushController@destroy')->name('push.destroy');

Route::resource('sms', 'SmsController');
Route::get('driver-sms', 'SmsController@driver_index')->name('sms.driver.index');
Route::post('driver-smsstore', 'SmsController@driver_store')->name('sms.driverstore');
Route::post('passenger-smsdestroy', 'SmsController@destroy')->name('sms.destroy');

Route::resource('mail', 'MailController');
Route::get('driver-mail', 'MailController@driver_index')->name('mail.driver.index');
Route::post('driver-mailstore', 'MailController@driver_store')->name('mail.driverstore');
Route::post('passenger-maildestroy', 'MailController@destroy')->name('mail.destroy');

Route::resource('promocode', 'Resource\PromocodeResource');



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
    // Route::get('/ride-list', 'DispatchController@ride_list')->name('ride-list');
    Route::get('/assign/{trip}/{provider}', 'DispatchController@assign')->name('assign');
    Route::post('/fare', 'DispatchController@fare_calculate')->name('fare');
});

Route::get('/driver-movement', 'DispatchController@driver_movement')->name('drivermovement');
Route::get('/main', 'MainController@index')->name('main');
Route::get('/listall', 'MainController@listall')->name('listall');
Route::get('/showdetail', 'MainController@showdetail')->name('showdetail');
Route::get('/editdetail/{trip}', 'MainController@editdetail')->name('editdetail');
Route::patch('/storedetail/{trip}', 'MainController@storedetail')->name('storedetail');
// Route::get('/canceldetail/{trip}', 'MainController@canceldetail')->name('canceldetail');
Route::get('/canceldetail/{trip}', 'MainController@canceldetail')->name('canceldetail');
Route::post('/completedetail', 'MainController@completedetail')->name('completedetail');
Route::get('/autotrip/{trip}', 'MainController@autotrip')->name('autotrip');
Route::get('/schedule', 'MainController@schedule')->name('schedule');
Route::get('/schedulelistall', 'MainController@schedule_listall')->name('schedulelistall');
Route::get('/providers', 'MainController@providers')->name('providers');
Route::get('/trips/{trip}/{provider}', 'MainController@assign')->name('assign');

Route::get('/statement', 'AdminController@statement')->name('ride.statement');
Route::post('/statement-content', 'AdminController@statement_content')->name('statement-content');
Route::get('/statement/today', 'AdminController@statement_today')->name('ride.statement.today');
Route::get('/statement/monthly', 'AdminController@statement_monthly')->name('ride.statement.monthly');
Route::get('/statement/yearly', 'AdminController@statement_yearly')->name('ride.statement.yearly');

Route::get('/statement/provider', 'AdminController@statement_provider')->name('ride.statement.provider');
Route::post('/statement-providerlist', 'AdminController@statement_providerlist')->name('statement-providerlist');

Route::get('/statement/corporate', 'AdminController@statement_corporate')->name('ride.statement.corporate');
Route::post('/statement-corporatelist', 'AdminController@statement_corporatelist')->name('statement-corporatelist');

Route::get('/statement/partner', 'AdminController@statement_partner')->name('ride.statement.partner');
Route::post('/statement-partnerlist', 'AdminController@statement_partnerlist')->name('statement-partnerlist');

Route::resource('requests', 'Resource\TripResource');
Route::post('requestsrow', 'Resource\TripResource@requests_row')->name('requests.row');
Route::get('scheduled', 'Resource\TripResource@scheduled')->name('scheduled');

Route::get('cancelled', 'Resource\TripResource@cancelled')->name('cancelled');
Route::post('cancelledrow', 'Resource\TripResource@cancelled_row')->name('cancelled.row');
Route::get('cancelled/{id}/approve', 'Resource\TripResource@cancel_approve')->name('cancelled.approve');
Route::get('cancelled/{id}/disapprove', 'Resource\TripResource@cancel_disapprove')->name('cancelled.disapprove');


// cashout 

Route::get('/cashout', 'CashoutController@index')->name('cashout');
Route::get('/cashout_listall', 'CashoutController@listall')->name('listall');
Route::get('/reject/cashout/{id}', 'CashoutController@reject_cashout')->name('reject_cashout');
Route::get('/approve/cashout/{id}', 'CashoutController@approve_cashout')->name('approve_cashout');
Route::get('/providerlist', 'CashoutController@providerlist')->name('providerlist');
Route::post('cashoutproviderrow', 'CashoutController@provider_row')->name('cashout.provider.row');
Route::get('provider/{id}/details', 'CashoutController@provider_details')->name('provider.details');
Route::post('reset/earnings', 'CashoutController@update_earnings')->name('earnings.reset');



Route::get('payment', 'AdminController@payment')->name('payment');
Route::post('paymentrow', 'AdminController@payment_row')->name('paymentrow');

//Route::resource('gpslocation', 'GpsHistoryController');
//Route::get('timelist', 'GpsHistoryController@time_list')->name('timelist');
Route::post('/storecomment', 'MainController@storecomment')->name('storecomment');

Route::get('/routedetail/{trip}', 'MainController@routedetail')->name('routedetail');

Route::resource('demo', 'DemoController');
Route::get('/renue/{id}', 'DemoController@renue')->name('demo.renue');
Route::get('/expire/{id}', 'DemoController@expire')->name('demo.expire');

Route::get('fcm', 'AdminController@fcm')->name('fcm');

Route::get('/usercare', 'UsercareController@index')->name('usercare');
Route::get('/inprogress/{id}', 'UsercareController@inprogress')->name('inprogress');
Route::get('/closed/{id}', 'UsercareController@closed')->name('closed');

// wallets//
Route::resource('userwallet', 'Resource\UserWalletController');
Route::get('userwallet/{id}/credit', 'Resource\UserWalletController@credited')->name('credit');
Route::post('userwallet/debited', 'Resource\UserWalletController@debited')->name('userwallet.debited');

Route::resource('providerwallet', 'Resource\ProviderWalletController');
Route::get('providerwallet/{id}/credit', 'Resource\ProviderWalletController@credited')->name('provider.credit');
Route::post('providerwallet/debited', 'Resource\ProviderWalletController@debited')->name('providerwallet.debited');

Route::get('/livelocation', function () {
    return view('admin.main.test');
});

Route::get('/addlocation', function () {
    $title = "Add Locations";
    return view('admin.test',compact('title'));
});

Route::get('/listlocation', function () {
    $title = "List Locations";
    return view('admin.test',compact('title'));
});

Route::get('/restrictlocation', function () {
    $title = "Restrict Locations";
    return view('admin.test',compact('title'));
});

Route::get('/fare-manage', function () {
    $title = "Fare Management";
    return view('admin.test',compact('title'));
});

Route::get('/location-fare', function () {
    $title = "Location wise fare";
    return view('admin.test',compact('title'));
});

Route::get('/vehicle-manage', function () {
    $title = "Vehicle management";
    return view('admin.test',compact('title'));
});

/*Route::get('/sms', function () {
    $title = "SMS Notification";
    return view('admin.test',compact('title'));
});

Route::get('/mailnotification', function () {
    $title = "Mail Notification";
    return view('admin.test',compact('title'));
});*/

// Route::get('/push', function () {
//     $title = "Push notification";
//     return view('admin.test',compact('title'));
// });

/*Route::get('/promocode', function () {
    $title = "Promocode";
    return view('admin.test',compact('title'));
});*/

Route::get('/invoice', function () {
    $title = "Invoice";
    return view('admin.test',compact('title'));
});
Route::get('/requesthistory', function () {
    $title = "Request History";
    return view('admin.test',compact('title'));
});
Route::get('/payemnthistory', function () {
    $title = "Payment History";
    return view('admin.test',compact('title'));
});

Route::get('/privacy', function () {
    $title = "Privacy";
    return view('admin.test',compact('title'));
});

Route::get('/cms-settings', function () {
    return view('admin.settings.cmssettings');
});
Route::post('cms-settings/store', 'AdminController@cms_settings_store')->name('cms-settings.store');
Route::get('/test' , 'ProviderResources\TripController@test');
Route::get('/webnotify', 'WebNotifyController@index');
Route::post('/clear-notify', 'WebNotifyController@clearall');