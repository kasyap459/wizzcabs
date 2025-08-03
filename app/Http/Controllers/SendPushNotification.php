<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Corporate;
use App\Models\Provider;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\ProviderDevice;
use App\Models\UserRequest;
use Exception;
use Setting;
use Mail;
use Carbon\Carbon;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Http;

use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;

class SendPushNotification extends Controller
{
	/**
     * New Ride Accepted by a Driver.
     *
     * @return void
     */
    public function RideAccepted($request){

        /*$user = User::where('id','=',$request->user_id)->select('dial_code','mobile')->first();
        
        $mobile = $user->dial_code.$user->mobile;
        $message = trans('api.push.request_accepted');

        if($request->corporate_id !=0){
            $notify = Corporate::where('id','=',$request->corporate_id)->pluck('notify_customer')->first();
            if($notify ==1){
                $sms = $this->sendSMSUser($mobile,$message);
            }
        }else{
            $sms = $this->sendSMSUser($mobile,$message);
        }
        
    	return $this->sendPushToUser($request->user_id, trans('api.push.request_accepted'));*/
    }
    /**
     * New Ride Accepted by a Driver.
     *
     * @return void
     */
    public function RideStarted($request){

        $user = User::where('id','=',$request->user_id)->select('dial_code','mobile')->first();
        
        $mobile = $user->dial_code.$user->mobile;
        $message = trans('api.push.schedule_start');

        /*if($request->corporate_id !=0){
            $notify = Corporate::where('id','=',$request->corporate_id)->pluck('notify_customer')->first();
            if($notify ==1){
                $sms = $this->sendSMSUser($mobile,$message);
            }
        }else{
            $sms = $this->sendSMSUser($mobile,$message);
        }*/
        
        return $this->sendPushToUser($request->user_id, trans('api.push.schedule_start'));

    }

    /**
     * Driver Arrived at your location.
     *
     * @return void
     */
    public function user_schedule($user){

        return $this->sendPushToUser($user, trans('api.push.schedule_start'));
    }

    /**
     * New Incoming request
     *
     * @return void
     */
    public function provider_schedule($provider){

        return $this->sendPushToProvider($provider, trans('api.push.schedule_start'));

    }

    /**
     * New Ride Accepted by a Driver.
     *
     * @return void
     */
    public function UserCancellRide($request){

        return $this->sendPushToProvider($request->provider_id, trans('api.push.user_cancelled'));
    }


    /**
     * New Ride Accepted by a Driver.
     *
     * @return void
     */
    public function ProviderCancellRide($request){

        return $this->sendPushToUser($request->user_id, trans('api.push.provider_cancelled'));
    }

    /**
     * Driver Arrived at your location.
     *
     * @return void
     */
    public function Arrived($request){

        $user = User::where('id','=',$request->user_id)->select('dial_code','mobile')->first();
        if($user !=null){
        $provider = Provider::where('id','=',$request->provider_id)->select('name')->first();
        $vehicle = Vehicle::where('id','=',$request->vehicle_id)->select('vehicle_no','vehicle_model')->first();

        $mobile = $user->dial_code.$user->mobile;
        $message = 'Driver '.$provider->name.' (Taxi No: '.$vehicle->vehicle_no.',Taxi Model:'.$vehicle->vehicle_model.' ) arrived to your location. HAVE A HAPPY JOURNEY. Regards '.config('app.name');

        if($request->corporate_id !=0){
            $notify = Corporate::where('id','=',$request->corporate_id)->pluck('notify_customer')->first();
            if($notify ==1){
                $sms = $this->sendSMSUser($mobile,$message);
            }
        }else{
            $sms = $this->sendSMSUser($mobile,$message);
        }
        
        return $this->sendPushToUser($request->user_id, trans('api.push.arrived'));
        }
    }

    /**
     * Driver Arrived at your location.
     *
     * @return void
     */
    public function Completed($request){

        $user = User::where('id','=',$request->user_id)->select('dial_code','mobile')->first();
        if($user !=null){
        $mobile = $user->dial_code.$user->mobile;
        $message = 'Your trip has been completed successfully. Thanks for riding with '.config('app.name').' Pickup Location:'.$request->s_address.', Drop Location:'.$request->d_address.', Distance:'.$request->distance.', Payment Mode:'.$request->payment_mode.', Time:'.$request->finished_at.'. Regards '.config('app.name');

        if($request->corporate_id !=0){
            $notify = Corporate::where('id','=',$request->corporate_id)->pluck('notify_customer')->first();
            if($notify ==1){
                $sms = $this->sendSMSUser($mobile,$message);
            }
        }else{
            $sms = $this->sendSMSUser($mobile,$message);
        }


        if(Setting::get('mail_enable', 0) == 1) {
            Mail::send('emails.request-complete', ['user' => $request], function ($message) use ($request){
                $message->to($request->user->email, $request->user->name)->subject(config('app.name').' Trip Completed');
            });
        }
        }
    }
    /**
     * Money added to user wallet.
     *
     * @return void
     */
    public function ProviderNotAvailable($user_id){

        return $this->sendPushToUser($user_id,trans('api.push.provider_not_available'));
    }

    /**
     * New Ride Accepted by a Driver.
     *
     * @return void
     */
    public function AssignedTrip($provider_id){

        return $this->sendPushToProvider($provider_id, ' New trip assigned to you');
    }
    /**
     * New Ride Accepted by a Driver.
     *
     * @return void
     */
    public function ScheduleTime($provider_id, $booking_id){

        return $this->sendPushToProvider($provider_id, 'Scheduled time changed for your trip ID: '.$booking_id);
    }
    /**
     * New Ride Accepted by a Driver.
     *
     * @return void
     */
    public function IncomingTrip($provider_id){

        return $this->sendPushToProvider($provider_id, 'New incoming trip');
    }
    /**
     * New Incoming request
     *
     * @return void
     */
    public function IncomingRequest($provider){

        //return $this->sendPushToProvider($provider, trans('api.push.incoming_request'));
        $push_message = trans('api.push.incoming_request');
        try{

            $provider = ProviderDevice::where('provider_id',$provider)->orderBy('id','DESC')->first();

            if($provider->token != ""){

                if($provider->type == 'ios'){
                    
                    // return \PushNotification::setService('fcm')
                    //     ->setMessage(['notification' => [
                    //                  'title'=>"CAB-E",
                    //                  'body'=>$push_message,
                    //                 'sound' => 'alerttonee.mp3'
                    //                  ],
                    //          'data' => [
                    //                 'title'=>"CAB-E",
                    //                  'body'=>$push_message,
                    //                  'sound' => 'alerttonee.mp3'
                    //                  ]
                    //          ])
                    //     ->setApikey('AAAAkdWYW1w:APA91bFOhpjsjFGi0Yx4-y0_K0l28em3axNiynXjOY2Nm7yVrhZ5kY6CpGI0X1wBHdvdJ7trbpHl0e2E2H3NTvvA_4lZ-QpltVohDRw_wdWWoJ4mVo4NQC86-5pa4hugjWWd5m3KMidm')
                    //     ->setDevicesToken($provider->token)
                    //     ->send();

                        $fcm = $provider->token;

                        $title = "Wizz Cabs";
                        $description = $push_message;
                    
                        $credentialsFilePath = Http::get(asset('json/wizz-cabs-au-d9654f4c4c2f.json'));
                        $client = new GoogleClient();
                        $client->setAuthConfig($credentialsFilePath);
                        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
                        $client->refreshTokenWithAssertion();
                        $token = $client->getAccessToken();
                      
                        $access_token = $token['access_token'];
                      
                        $headers = [
                            "Authorization: Bearer $access_token",
                            'Content-Type: application/json' 
                        ];
                      
                        $data = [
                            "message" => [
                                "token" => $fcm,
                                "notification" => [
                                    "title" => $title,
                                    "body" => $description,
                                ],
                                'data' => [
                                  'title'=>"Wizz Cabs",
                                   'body'=>$description,
                                   'sound' => 'alerttonee.mp3'
                                   ]
                            ]
                        ];
                        $payload = json_encode($data);
                      
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/wizz-cabs-au/messages:send');
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                        curl_setopt($ch, CURLOPT_VERBOSE, true); // Enable verbose output for debugging
                        $response = curl_exec($ch);
                        $err = curl_error($ch);
                        curl_close($ch);
                      
                        if ($err) {
                            return response()->json([
                                'message' => 'Curl Error: ' . $err
                            ], 500);
                        } else {
                            return response()->json([
                                'message' => 'Notification has been sent',
                                'response' => json_decode($response, true)
                            ]);
                        }

                }elseif($provider->type == 'android'){
                
                    // return \PushNotification::setService('fcm')
                    //     ->setMessage([
                    //         'priority' => 'high',
                    //         'notification' => [
                    //                 'title'=>"CAB-E",
                    //                  'body'=>$push_message,
                    //                  'sound' => 'alert_tone'
                    //                  ],
                    //          'data' => [
                    //                 'title'=>"CAB-E",
                    //                  'body'=>$push_message,
                    //                  'sound' => 'alert_tone'
                    //                  ]
                    //          ])
                    //     ->setApikey('AAAAkdWYW1w:APA91bFOhpjsjFGi0Yx4-y0_K0l28em3axNiynXjOY2Nm7yVrhZ5kY6CpGI0X1wBHdvdJ7trbpHl0e2E2H3NTvvA_4lZ-QpltVohDRw_wdWWoJ4mVo4NQC86-5pa4hugjWWd5m3KMidm')
                    //     ->setDevicesToken($provider->token)
                    //     ->send();  
                    
                    $fcm = $provider->token;

                        $title = "Wizz Cabs";
                        $description = $push_message;
                    
                        $credentialsFilePath = Http::get(asset('json/wizz-cabs-au-d9654f4c4c2f.json'));
                        $client = new GoogleClient();
                        $client->setAuthConfig($credentialsFilePath);
                        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
                        $client->refreshTokenWithAssertion();
                        $token = $client->getAccessToken();
                      
                        $access_token = $token['access_token'];
                      
                        $headers = [
                            "Authorization: Bearer $access_token",
                            'Content-Type: application/json' 
                        ];
                      
                        $data = [
                            "message" => [
                                "token" => $fcm,
                                "notification" => [
                                    "title" => $title,
                                    "body" => $description,
                                ],
                                'data' => [
                                  'title'=>"Wizz Cabs",
                                   'body'=>$description,
                                   'sound' => 'alert_tone'
                                   ]
                            ]
                        ];
                        $payload = json_encode($data);
                      
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/wizz-cabs-au/messages:send');
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                        curl_setopt($ch, CURLOPT_VERBOSE, true); // Enable verbose output for debugging
                        $response = curl_exec($ch);
                        $err = curl_error($ch);
                        curl_close($ch);
                      
                        if ($err) {
                            return response()->json([
                                'message' => 'Curl Error: ' . $err
                            ], 500);
                        } else {
                            return response()->json([
                                'message' => 'Notification has been sent',
                                'response' => json_decode($response, true)
                            ]);
                        }
                }
            }

        } catch(Exception $e){
            return $e;
        }

    }
    

    /**
     * Driver Documents verfied.
     *
     * @return void
     */
    public function DocumentsVerfied($provider_id){

        return $this->sendPushToProvider($provider_id, trans('api.push.document_verfied'));
    }


    /**
     * Money added to user wallet.
     *
     * @return void
     */
    public function WalletMoney($user_id, $money){

        return $this->sendPushToUser($user_id, $money.' '.'Credited to your wallet');
    }

    public function DebitWalletMoney($user_id, $money){
        return $this->sendPushToUser($user_id, $money.' '.trans('api.push.debited_money_to_wallet'));
    }


    public function drviverWalletMoney($user_id, $money){

        return $this->sendPushToProvider($user_id, $money.' '.trans('api.push.added_money_to_wallet'));
    }

    public function drviverDebitMoney($user_id, $money){

        return $this->sendPushToProvider($user_id, $money.' '.trans('api.push.debited_money_to_wallet'));
    }

    /**
     * Money charged from user wallet.
     *
     * @return void
     */
    public function ChargedWalletMoney($user_id, $money){

        return $this->sendPushToUser($user_id, $money.' '.trans('api.push.charged_from_wallet'));
    }

    /**
     * send message to provider.
     *
     * @return void
     */
    public function PushMessageToProvider($provider_id, $message){

        return $this->sendPushToProvider($provider_id, $message);
    }
    /**
     * send message to provider.
     *
     * @return void
     */
    public function PushMessageToUser($user, $message){

        return $this->sendPushToUser($user, $message);
    }
    /**
     * Money added to user wallet.
     *
     * @return void
     */
    public function DueMoney($user_id, $money){

        return $this->sendPushToUser($user_id, 'Due Amount '.$money.' amount debited from your account');
    }
    /**
     * Sending Push to a user Device.
     *
     * @return void
     */
    public function sendPushToUser($user_id, $push_message){

    	try{

	    	$user = User::findOrFail($user_id);

            if($user->device_token != ""){

    	    	if($user->device_type == 'ios'){

    	    		// return \PushNotification::setService('fcm')
                    //     ->setMessage(['notification' => [
                    //                 'title'=>"CAB-E",
                    //                  'body'=>$push_message,
                    //                  'sound' => 'Default'
                    //                  ],
                    //          'data' => [
                    //             'title'=>"CAB-E",
                    //                  'body'=>$push_message,
                    //                  'sound' => 'Default'
                    //                  ]
                    //          ])
                    //     ->setApikey('AAAAkdWYW1w:APA91bFOhpjsjFGi0Yx4-y0_K0l28em3axNiynXjOY2Nm7yVrhZ5kY6CpGI0X1wBHdvdJ7trbpHl0e2E2H3NTvvA_4lZ-QpltVohDRw_wdWWoJ4mVo4NQC86-5pa4hugjWWd5m3KMidm')
                    //     ->setDevicesToken($user->device_token)
                    //     ->send();

                $fcm = $user->device_token;

                $title = "Wizz Cabs";
                $description = $push_message;
            
                $credentialsFilePath = Http::get(asset('json/wizz-cabs-au-d9654f4c4c2f.json'));
                $client = new GoogleClient();
                $client->setAuthConfig($credentialsFilePath);
                $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
                $client->refreshTokenWithAssertion();
                $token = $client->getAccessToken();
              
                $access_token = $token['access_token'];
              
                $headers = [
                    "Authorization: Bearer $access_token",
                    'Content-Type: application/json' 
                ];
              
                $data = [
                    "message" => [
                        "token" => $fcm,
                        "notification" => [
                            "title" => $title,
                            "body" => $description,
                        ],
                        'data' => [
                          'title'=>"Wizz Cabs",
                           'body'=>$description,
                           'sound' => 'Default'
                           ]
                    ]
                ];
                $payload = json_encode($data);
              
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/wizz-cabs-au/messages:send');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                curl_setopt($ch, CURLOPT_VERBOSE, true); // Enable verbose output for debugging
                $response = curl_exec($ch);
                $err = curl_error($ch);
                curl_close($ch);
              
                if ($err) {
                    return response()->json([
                        'message' => 'Curl Error: ' . $err
                    ], 500);
                } else {
                    return response()->json([
                        'message' => 'Notification has been sent',
                        'response' => json_decode($response, true)
                    ]);
                }

    	    	}elseif($user->device_type == 'android'){
    	    	
                    // return \PushNotification::setService('fcm')
                    //     ->setMessage(['notification' => [
                    //                 'title'=>"CAB-E",
                    //                  'body'=>$push_message,
                    //                  // 'sound' => 'default'
                    //                  ],
                    //          'data' => [
                    //                 'title'=>"CAB-E",
                    //                  'body'=>$push_message,
                    //                  'sound' => 'alert_tone'
                    //                  ]
                    //          ])
                    //     ->setApikey('AAAAkdWYW1w:APA91bFOhpjsjFGi0Yx4-y0_K0l28em3axNiynXjOY2Nm7yVrhZ5kY6CpGI0X1wBHdvdJ7trbpHl0e2E2H3NTvvA_4lZ-QpltVohDRw_wdWWoJ4mVo4NQC86-5pa4hugjWWd5m3KMidm')
                    //     ->setDevicesToken($user->device_token)
                    //     ->send();    

                    $fcm = $user->device_token;

                    $title = "Wizz Cabs";
                    $description = $push_message;
                
                    $credentialsFilePath = Http::get(asset('json/wizz-cabs-au-d9654f4c4c2f.json'));
                    $client = new GoogleClient();
                    $client->setAuthConfig($credentialsFilePath);
                    $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
                    $client->refreshTokenWithAssertion();
                    $token = $client->getAccessToken();
                  
                    $access_token = $token['access_token'];
                  
                    $headers = [
                        "Authorization: Bearer $access_token",
                        'Content-Type: application/json' 
                    ];
                  
                    $data = [
                        "message" => [
                            "token" => $fcm,
                            "notification" => [
                                "title" => $title,
                                "body" => $description,
                            ],
                            'data' => [
                              'title'=>"Wizz Cabs",
                               'body'=>$description,
                               'sound' => 'alert_tone'
                               ]
                        ]
                    ];
                    $payload = json_encode($data);
                  
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/wizz-cabs-au/messages:send');
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                    curl_setopt($ch, CURLOPT_VERBOSE, true); // Enable verbose output for debugging
                    $response = curl_exec($ch);
                    $err = curl_error($ch);
                    curl_close($ch);
                  
                    if ($err) {
                        return response()->json([
                            'message' => 'Curl Error: ' . $err
                        ], 500);
                    } else {
                        return response()->json([
                            'message' => 'Notification has been sent',
                            'response' => json_decode($response, true)
                        ]);
                    }

    	    	}
            }

    	} catch(Exception $e){
    		return $e;
    	}

    }

    /**
     * Sending Push to a user Device.
     *
     * @return void
     */
    public function sendPushToProvider($provider_id, $push_message){

    	try{

	    	$provider = ProviderDevice::where('provider_id',$provider_id)->orderBy('id','DESC')->first();

            if($provider->token != ""){

            	if($provider->type == 'ios'){
            		
            		// return \PushNotification::setService('fcm')
                    //     ->setMessage(['notification' => [
                    //                  'title'=>'CAB-E',
                    //                  'body'=>$push_message,
                    //                  'sound' => 'alerttonee.mp3'
                    //                  ],
                    //          'data' => [
                    //                  'title'=>"CAB-E",
                    //                  'body'=>$push_message,
                    //                  ]
                    //          ])
                    //     ->setApikey('AAAAkdWYW1w:APA91bFOhpjsjFGi0Yx4-y0_K0l28em3axNiynXjOY2Nm7yVrhZ5kY6CpGI0X1wBHdvdJ7trbpHl0e2E2H3NTvvA_4lZ-QpltVohDRw_wdWWoJ4mVo4NQC86-5pa4hugjWWd5m3KMidm')
                    //     ->setDevicesToken($provider->token)
                    //     ->send();

                    $fcm = $provider->token;

                    $title = "Wizz Cabs";
                    $description = $push_message;
                
                    $credentialsFilePath = Http::get(asset('json/wizz-cabs-au-d9654f4c4c2f.json'));
                    $client = new GoogleClient();
                    $client->setAuthConfig($credentialsFilePath);
                    $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
                    $client->refreshTokenWithAssertion();
                    $token = $client->getAccessToken();
                  
                    $access_token = $token['access_token'];
                  
                    $headers = [
                        "Authorization: Bearer $access_token",
                        'Content-Type: application/json' 
                    ];
                  
                    $data = [
                        "message" => [
                            "token" => $fcm,
                            "notification" => [
                                "title" => $title,
                                "body" => $description,
                            ],
                            'data' => [
                              'title'=>"Wizz Cabs",
                               'body'=>$description,
                               'sound' => 'alerttonee.mp3'
                               ]
                        ]
                    ];
                    $payload = json_encode($data);
                  
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/wizz-cabs-au/messages:send');
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                    curl_setopt($ch, CURLOPT_VERBOSE, true); // Enable verbose output for debugging
                    $response = curl_exec($ch);
                    $err = curl_error($ch);
                    curl_close($ch);
                  
                    if ($err) {
                        return response()->json([
                            'message' => 'Curl Error: ' . $err
                        ], 500);
                    } else {
                        return response()->json([
                            'message' => 'Notification has been sent',
                            'response' => json_decode($response, true)
                        ]);
                    }

            	}elseif($provider->type == 'android'){
            	
                    // return \PushNotification::setService('fcm')
                    //     ->setMessage(['notification' => [
                    //                  'title'=>'CAB-E',
                    //                  'body'=>$push_message,
                    //                  'sound' => 'alert_tone.mp3'
                    //                  ],
                    //          'data' => [
                    //                 'title'=>"CAB-E",
                    //                  'body'=>$push_message,
                    //                  'sound' => 'alert_tone'
                    //                  ]
                    //          ])
                    //     ->setApikey('AAAAkdWYW1w:APA91bFOhpjsjFGi0Yx4-y0_K0l28em3axNiynXjOY2Nm7yVrhZ5kY6CpGI0X1wBHdvdJ7trbpHl0e2E2H3NTvvA_4lZ-QpltVohDRw_wdWWoJ4mVo4NQC86-5pa4hugjWWd5m3KMidm')
                    //     ->setDevicesToken($provider->token)
                    //     ->send();    

                    $fcm = $provider->token;

                    $title = "Wizz Cabs";
                    $description = $push_message;
                
                    $credentialsFilePath = Http::get(asset('json/wizz-cabs-au-d9654f4c4c2f.json'));
                    $client = new GoogleClient();
                    $client->setAuthConfig($credentialsFilePath);
                    $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
                    $client->refreshTokenWithAssertion();
                    $token = $client->getAccessToken();
                  
                    $access_token = $token['access_token'];
                  
                    $headers = [
                        "Authorization: Bearer $access_token",
                        'Content-Type: application/json' 
                    ];
                  
                    $data = [
                        "message" => [
                            "token" => $fcm,
                            "notification" => [
                                "title" => $title,
                                "body" => $description,
                            ],
                            'data' => [
                              'title'=>"Wizz Cabs", 
                               'body'=>$description,
                               'sound' => 'alert_tone'
                               ]
                        ]
                    ];
                    $payload = json_encode($data);
                  
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/wizz-cabs-au/messages:send');
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                    curl_setopt($ch, CURLOPT_VERBOSE, true); // Enable verbose output for debugging
                    $response = curl_exec($ch);
                    $err = curl_error($ch);
                    curl_close($ch);
                  
                    if ($err) {
                        return response()->json([
                            'message' => 'Curl Error: ' . $err
                        ], 500);
                    } else {
                        return response()->json([
                            'message' => 'Notification has been sent',
                            'response' => json_decode($response, true)
                        ]);
                    }
            	}
            }

    	} catch(Exception $e){
    		return $e;
    	}

    }

    /** 
     * Sending Push to a user Device.
     *
     * @return void
     */
    public function sendMailUser($user){

        try{

            if(Setting::get('mail_enable', 0) == 1) {
                Mail::send('emails.sendmail', ['user' => $user], function ($message) use ($user){
                    $message->to($user['email'], $user['name'])->subject(config('app.name').' '.$user['subject']);
                });
            }

        }catch(Exception $e){
            return $e;
        }

    }

    /**
     * Sending Push to a user Device.
     *
     * @return void
     */
    public function sendSMSUser($mobile, $message_content){

        try{
            if(Setting::get('sms_enable', 0) == 1) {
                $sid    = config('services.twilio')['accountSid'];
                $token  = config('services.twilio')['authToken'];
                $from_number  = config('services.twilio')['number'];
                $twilio = new Client($sid, $token);
                $message = $twilio->messages
                      ->create($mobile, // to
                            array(
                                "from" => $from_number, 
                                "body" => $message_content
                            )
                      );
            }      
        } catch (TwilioException $e) {
            //return $e;
        }
    }

    public function tester(){

        //$provider = ProviderDevice::where('provider_id',26)->first();
        $push_message = "test";

        /*$test = \PushNotification::setService('fcm')
                        ->setMessage(['notification' => [
                                     'title'=>config('app.name'),
                                     'body'=>$push_message,
                                     'sound' => 'default'
                                     ],
                             'data' => [
                                     'title'=>config('app.name'),
                                     'body'=>$push_message,
                                     ]
                             ])
                        ->setDevicesToken($provider->token)
                        ->send();
        dd($test);*/
        /*$user = User::where('id',44)->first();
        $testuser = \PushNotification::setService('fcm')
                        ->setMessage(['notification' => [
                                     'title'=>config('app.name'),
                                     'body'=>$push_message,
                                     'sound' => 'default'
                                     ],
                             'data' => [
                                     'title'=>config('app.name'),
                                     'body'=>$push_message,
                                     ]
                             ])
                        ->setDevicesToken($user->device_token)
                        ->send();

        dd($testuser);*/  
         /*$drivers = Provider::where('status','=','riding')
            ->where('ride_from','<=',Carbon::now()->subMinutes(1))
            ->get()->pluck('name');
        dd($drivers);*/


        /*$UserRequest = UserRequest::where('id','=',62)
                    ->select('id','created_at','booking_id','service_type_id','s_address','d_address','distance','schedule_at','assigned_at','status','s_latitude','s_longitude')
                    ->orderBy('created_at','desc')
                    ->first();
                
            $start = Carbon::parse($UserRequest->assigned_at);
            $now = Carbon::now();
            $seconds = $now->diffInSeconds($start);
            $latitude = $UserRequest->s_latitude;
            $longitude=$UserRequest->s_longitude;
            $distance=$UserRequest->distance;
            $Providers = Provider::where('account_status', 'approved')
                        ->where('status', 'active')
                        ->where('service_type_id','=', $UserRequest->service_type_id)
                        ->selectRaw("id , (1.609344 * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) AS distance, latitude, longitude")
                        ->orderBy('active_from', 'asc');
                        
            $shortfilter = $Providers->having('distance', '<=', $distance)
                      ->get()->pluck('id')->toArray();
            if(count($shortfilter) >0){
                (new SendPushNotification)->IncomingTrip($shortfilter); 
            }
            
            $filter = $Providers->get();
            foreach($filter as $provider){
                $distance = Helper::distance($UserRequest->s_latitude, $UserRequest->s_longitude, $provider->latitude, $provider->longitude, "K");
                $distance_short = $distance*1000;
                if($distance_short <= Setting::get('distance_1', '500')){
                    (new SendPushNotification)->IncomingTrip($provider->id)->delay(Setting::get('time_1', '10')); 
                }else if($distance <=Setting::get('distance_2', '1')){
                    (new SendPushNotification)->IncomingTrip($provider->id)->delay(Setting::get('time_2', '20'));
                }else if($distance <=Setting::get('distance_3', '2')){
                   (new SendPushNotification)->IncomingTrip($provider->id)->delay(Setting::get('time_3', '30'));     
                }else if($distance <=Setting::get('distance_4', '3')){
                    (new SendPushNotification)->IncomingTrip($provider->id)->delay(Setting::get('time_4', '40'));
                }else if($distance <=Setting::get('distance_5', '4')){
                    (new SendPushNotification)->IncomingTrip($provider->id)->delay(Setting::get('time_5', '50'));
                }else{
                    (new SendPushNotification)->IncomingTrip($provider->id)->delay(Setting::get('time_6', '60'));
                }   
            }*/

            (new SendPushNotification)->IncomingTrip($provider->id)->delay(Setting::get('time_4', '40'));

       /* $mobile = '+919500698960';
       $sid    = config('services.twilio')['accountSid'];
                $token  = config('services.twilio')['authToken'];
                $from_number  = config('services.twilio')['number'];
                $twilio = new Client($sid, $token);
                $message = $twilio->messages
                      ->create($mobile, // to
                            array(
                                "from" => $from_number, 
                                "body" => 'Hai'
                            )
                      );
        dd($sid);*/
        /*$request = UserRequest::with('user','service_type','payment')->findOrFail(276);              
        if(Setting::get('mail_enable', 0) == 1) {
            $data = Mail::send('emails.request-complete', ['user' => $request], function ($message) use ($request){
                $message->to($request->user->email, $request->user->name)->subject(config('app.name').' Trip Completed');
            });
        }*/
    }
}
