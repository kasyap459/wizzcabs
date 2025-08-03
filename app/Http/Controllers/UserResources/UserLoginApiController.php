<?php

namespace App\Http\Controllers\UserResources;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\URL;
use DB;
use Log;
use Auth;
use Hash;
use Storage;
use Setting;
use Exception;
use Notification;
use Mail;
use GuzzleHttp\Client;
use Carbon\Carbon;
use App\Notifications\ResetPasswordOTP;
use App\Helpers\Helper;
use Validator;
use App\Models\User;
use App\Models\Country;
use DateTimeZone;
use App\Models\Token;
use App\Http\Controllers\SendPushNotification;

class UserLoginApiController extends Controller
{  

    public function send_otp(Request $request){

        // if(Auth::user()->admin_id !=  null){
        //     $admin = Admin::where('id','=',Auth::user()->admin_id)->first();
        //     if($admin->admin_type != 0 && $admin->time_zone != null){
        //          date_default_timezone_set($admin->time_zone);
        //      }
        //  }

        $validator = Validator::make($request->all(), [
            'mobile' => 'required|numeric',
            // 'email' => 'required|email|max:255|unique:users,email,NULL,id,deleted_at,NULL',
            // 'dial_code' => 'required',
        ]);
	if ($validator->fails()) {
		return response()->json(['success' => 0, "message"=> $validator->errors()->first()]);
        }
        $mobile = User::where('mobile','=',$request->mobile)->first();
        if($mobile){
          return response()->json(['success' => "0", "message"=>"The Mobile Number Already registered."], 200);  
        }
        try {
            
            //    $otp = rand(pow(10, 4-1), pow(10, 4)-1);
                $otp = 123456;
               $token =  Token::create([
                        'code' => $otp,
                        'user_id' =>null,
                        'mobile' => $request->mobile,
                        'dial_code' => $request->dial_code,
                        'email' => $request->email,
                        'used' => 0,
                    ]);
                $number = $request->dial_code.$request->mobile;
                $message = "<#> Wizz Cabs : Your verification code is ".$otp." PUJWhJxn7T+";


                // Mail::send('emails.otp-register', ['user' => $token ], function ($message) use ($token){
                //     $message->to($token->email,'test')->subject(config('app.name').' '.'Rider Email Verification O T P');
                // });
            
                //$message = "DO NOT SHARE:".$otp." is the OTP for your account. Keep this OTP to yourself for account safety.";
                // (new SendPushNotification)->sendSMSUser($number,$message);
            
            return response()->json(['success' => 1, "message"=>"OTP Send Successful",'otp' => $otp], 200); 
        } 
        catch (Exception $e) {
         //   return $e;
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    
    public function signup(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            // 'social_unique_id' => ['required_if:login_by,facebook,google','unique:users'],
            'device_type' => 'required|in:android,ios',
            'device_token' => 'required',
            'device_id' => 'required',
            'login_by' => 'required|in:manual,facebook,google',
            'first_name' => 'required|max:255',
            // 'last_name' => 'required|max:255',
            'dial_code' => 'required',
            'mobile' => 'required|unique:users,mobile,NULL,id,deleted_at,NULL|numeric',
            'email' => 'required|email|max:255|unique:users,email,NULL,id,deleted_at,NULL',
            'otp' => 'required|exists:tokens,code,mobile,'.$request->mobile,
            'device_token' => 'required',
            'device_type' => 'required',
            'device_id' => 'required',
            // 'password' => 'required|confirmed|min:6'
        ]);
        if($validator->fails()) { 

            // $mail = User::where('email','=',$request->email)->first();
            $mobile = User::where('mobile','=',$request->mobile)->first();
            // if($mail){
            //   return response()->json(['success' => "0", "message"=>"The email has already been taken."], 200); 
            // }
            if($mobile){
              return response()->json(['success' => "0", "message"=>"The mobile number already registered."], 200); 
            }
              return response()->json(['success' => "0", "message"=>$validator->errors()->first()], 200); 
        }
        if($request->device_token == 'COULD NOT GET FCM TOKEN'){
            return response()->json(['success' => "0", "message"=> 'COULD NOT GET FCM TOKEN'], 200);
        }

            if($request->refferal_code){
                $refer_earn=User::where('refferal_code',$request->refferal_code)->first();
                if($refer_earn){
                    $referal_by=$refer_earn->id;
                }
                else{
                return response()->json(['success' => "0", "message"=>"Invalid refferel code"], 200); 
                }
            }
       try{

            $country = Country::where('dial_code','=',$request->dial_code)->first();
            $input = $request->all();
            $input['payment_mode'] = 'CASH';
            // $input['password'] = bcrypt($request->password);
            $input['refferal_code'] = Helper::generate_refferal_code();
            if($request->refferal_code){
                $refer_earn==User::where('refferal_code',$request->refferal_code)->first();
                 $input['refferal_by']=$refer_earn->id;
            }
            if($country !=null){
                $input['country_id'] = $country->countryid;
            }
            if ($request->picture != "") {
                // File::delete(public_path('uploads/user/profile/'.$user->picture));
               //Storage::delete($user->picture);
               //$user->picture = $request->picture->store('public/user/profile');
              // $user->picture = $request->picture->store('user/profile');
              $picture=$request->picture;
              $file_name = time();
              $file_name .= rand();
              $file_name = sha1($file_name);
             
               $ext = $picture->getClientOriginalExtension();
               $picture->move(public_path() . "/uploads/user/profile/", $file_name . "." . $ext);
               $local_url = $file_name . "." . $ext;                    
               $input['picture'] = $local_url;
           
           }
            $User = User::create($input);
            \Auth::login($User);
                    if($request->has('device_token')){
                        $User->device_token = $request->device_token;
                    }

                    if($request->has('device_type')){
                        $User->device_type = $request->device_type;
                    }

                    if($request->has('device_id')){
                        $User->device_id = $request->device_id;
                    }

                    $User->save();
                    $userToken = $User->token()?:$User->createToken('otpLogin');
                
                    return response()->json([
                            "success" => 1,
                            "message"=>'You have Successfully registered',
                            "admin_id"=> $User->admin_id,
                            "token_type" => "Bearer",
                            "access_token" => $userToken->accessToken
                            ]);
            
            return response()->json(['success' => "1", "message"=>'You have successfully registered on Wizz Cabs'], 200); 

        }catch (Exception $e) {
            return $e;
             return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }


    }

    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(Request $request)
    {
       $validator = Validator::make($request->all(), [
            'device_id' => 'required',
            'device_type' => 'required|in:android,ios',
            'device_token' => 'required',
            'client_id' => 'required',
            'client_secret' => 'required',
            'mobile' => 'required',
            // 'dial_code' => 'required',
            'password' => 'required'
        ]);
       if ($validator->fails()) {
        return response()->json(['success' => 0, "message"=> $validator->errors()->first()]);
        }

        try{
              $password= $request->password;
                $User = User::where('mobile','=',$request->mobile)->first();
                if($User ==null){
                    return response()->json(['success' => 0, "message"=> "Account details not found. Please contact Wizz Cabs "], 200);
                }else{
                    $password= $request->password;
                   
                }
                
                if($User->account_status !='approved'){
                    return response()->json(['error' => 'Your account has been banned Please contact Wizz Cabs.', 'success' =>0]);
                }

            if(Auth::attempt(['mobile' => request('mobile'), 'password' => $password])){ 
                $user = Auth::user(); 
                if($user->admin_id){
                $now=Carbon::now();
                $expired=$user->expires_at;
                if(Carbon::now() > $user->expires_at){
                    return response()->json(['message' => 'Your account has expired. Contact your administrator','success'=>0], 200);
                    }
                }
                // $password="qwerty";                
                $http = new \GuzzleHttp\Client();
                $response = $http->post('https://prontotaxi.unicotaxi.com/oauth/token', [
                    'form_params' => [
                        'grant_type' => 'password',
                        'client_id' => $request->client_id,
                        'client_secret' => $request->client_secret,
                        'username' => $user->email,
                        'password'=>$password,
                        'scope' => '',
                    ],
                ]);
                // dd($response);
                if($user){
                    if($request->has('device_token')){
                        $user->device_token = $request->device_token;
                    }

                    if($request->has('device_type')){
                        $user->device_type = $request->device_type;
                    }

                    if($request->has('device_id')){
                        $user->device_id = $request->device_id;
                    }
                    $user->save();
                }

                return json_decode((string) $response->getBody(), true);
            } 
            else{             
                return response()->json(['message' => 'Incorrect username or password','success'=>0], 200);
            } 
        }catch (Exception $e) {
             return $e;
                return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }
    
    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function send_mobile(Request $request){

        // if(Auth::user()->admin_id !=  null){
        //     $admin = Admin::where('id','=',Auth::user()->admin_id)->first();
        //     if($admin->admin_type != 0 && $admin->time_zone != null){
        //          date_default_timezone_set($admin->time_zone);
        //      }
        //  }

        $validator = Validator::make($request->all(), [
            'mobile' => 'required',
            'dial_code' => 'required',
        ]);

	if ($validator->fails()) {
		return response()->json(['success' => 0, "message"=> $validator->errors()->first()]);
        }

        try {

            //  $otp = rand(pow(10, 4-1), pow(10, 4)-1);
               $otp = 123456;
  
              $User = User::where('mobile','=',$request->mobile)->first();
                  
                  if($User != NULL)
                  {
                  $token_user1 = Token::where('mobile','=',$request->mobile)->first();
                  // if($token_user1)
                  // {
                  //     $token_user1->code =$otp;
                  //     $token_user1->user_id =$User->id;
                  //    // $token_user1->dial_code =$request->dial_code;
                  //     $token_user1->email =$request->email;
                  //     $token_user1->save();
                  // }else{
                  //     $token_user = new Token;
                  //     $token_user->code =$otp;
                  //     $token_user->user_id =$User->id;
                  //     //$token_user->dial_code =$request->dial_code;
                  //     $token_user->email =$request->email;
                  //     $token_user->save();
                  //     return $token_user;
                  // }

                //   $token_user1 = Token::where('mobile','=',$request->mobile)->first();
                  // return   $token_user1;
                   if($token_user1)
                   {
                       $token_user1->code =$otp;
                       //$token_user1->user_id =$User->id;
                       $token_user1->dial_code =$request->dial_code;
                       $token_user1->email = $User->email;
                       $token_user1->save();
                   }
                   else{
                    $token_user = new Token;
                      $token_user->code =$otp;
                    //  $token_user->user_id =$User->id;
                    $token_user->mobile =$User->mobile;
                      $token_user->dial_code =$request->dial_code;
                      $token_user->email =$User->email;
                      $token_user->save();
                   }
                  
                  return response()->json(['success' => 1, "message"=>$otp], 200); 
              }else{
  
                //   $token_user1 = Token::where('email','=',$request->email)->first();
                 // return   $token_user1;
                //   if($token_user1)
                //   {
                //       $token_user1->code =$otp;
                //       //$token_user1->user_id =$User->id;
                //      // $token_user1->dial_code =$request->dial_code;
                //       $token_user1->email =$request->email;
                //       $token_user1->save();
                //   }else{
                //       $token_user = new Token;
                //       $token_user->code =$otp;
                //     //  $token_user->user_id =$User->id;
                //       // $token_user->dial_code =$request->dial_code;
                //       $token_user->email =$request->email;
                //       $token_user->save();
                //   }
                   return response()->json(['success' => 0, "message"=>"Please register with Wizz Cabs"], 200);
                //    ,'is_user' => 0
  
  
              }
              // $token_user = Token::where('mobile','=',$request->mobile)->first();
              // if($token_user !=null){
              //     //$otp = rand(pow(10, 4-1), pow(10, 4)-1);
              //     $otp = 1234;
              //     $token_user->code = $otp;
              //     $token_user->updated_at = Carbon::now();
              //     $token_user->save();
  
              //     $number = $token_user->dial_code.$token_user->mobile;
              //     $message = "<#> UnicoTaxi: Your verification code is ".$otp." PUJWhJxn7T+";
              //     //$message = "DO NOT SHARE:".$otp." is the OTP for your account. Keep this OTP to yourself for account safety.";
              //     (new SendPushNotification)->sendSMSUser($number,$message);
  
              // }
          } 

        // try {

        //     $User = User::where('mobile','=',$request->mobile)->first();
        //     if($User == null){
        //         return response()->json(['success' => 0, "message"=> "Account details not found. Please register with Wizz Cabs "], 200);
        //     }
		
        //     $token_user = Token::where('mobile','=',$request->mobile)->first();
        //     if($token_user !=null){
        //     //    $otp = rand(pow(10, 4-1), pow(10, 4)-1);
        //         $otp = 123456;
        //         $token_user->code = $otp;
        //         $token_user->updated_at = Carbon::now();
        //         $token_user->save();

        //         // $number = $token_user->dial_code.$token_user->mobile;
        //         // $message = "<#> Wizz Cabs : Your verification code is ".$otp." PUJWhJxn7T+";
        //         // //$message = "DO NOT SHARE:".$otp." is the OTP for your account. Keep this OTP to yourself for account safety.";
        //         // (new SendPushNotification)->sendSMSUser($number,$message);

        //     }else{
        //     //    $otp = rand(pow(10, 4-1), pow(10, 4)-1);
        //         $otp = 123456;
        //         Token::create([
        //                 'code' => $otp,
        //                 'user_id' =>null,
        //                 'mobile' => $request->mobile,
        //                 'dial_code' => $request->dial_code,
        //                 'used' => 0,
        //             ]);
        //         // $number = $request->dial_code.$request->mobile;
        //         // $message = "<#> Wizz Cabs : Your verification code is ".$otp." PUJWhJxn7T+";
        //         // //$message = "DO NOT SHARE:".$otp." is the OTP for your account. Keep this OTP to yourself for account safety.";
        //         // (new SendPushNotification)->sendSMSUser($number,$message);
        //     }
        //     return response()->json(['success' => 1, "message"=>"OTP send Successfully",'otp' => $otp], 200); 
        // } 
        catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }
    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function resend_otp(Request $request){

        $this->validate($request, [
            'mobile' => 'required',
        ]);

        try {
            $token_user = Token::where('mobile','=',$request->mobile)->first();
            if($token_user !=null){
                // $otp = rand(pow(10, 4-1), pow(10, 4)-1);
                $otp = 123456;
                $token_user->code = $otp;
                $token_user->updated_at = Carbon::now();
                $token_user->save();
                // $number = $token_user->dial_code.$token_user->mobile;
                // $message = "<#> UnicoTaxi: Your verification code is ".$otp." PUJWhJxn7T+";
                // //$message = "DO NOT SHARE:".$otp." is the OTP for your account. Keep this OTP to yourself for account safety.";
                // (new SendPushNotification)->sendSMSUser($number,$message);

                return response()->json(['success' => "OTP resend successful", "message"=>1], 200);
            }else{
                return response()->json(['success' => "Please try again", "message"=>0], 200);
            }
        }
        catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }
    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function otp_verify(Request $request){

        $validator = Validator::make($request->all(), [
            'mobile' => 'required',
            'otp' => 'required|exists:tokens,code,mobile,'.$request->mobile,
            'device_token' => 'required',
        ]);

	if ($validator->fails()) {
          return response()->json(['success' => 0,"message"=> $validator->errors()->first()]);
        }
        $User = User::where('mobile','=',$request->mobile)->first();
        if($User == null){ 
            return response()->json(['success' => 0, "message"=> "Account details not found. Please register for Wizz Cabs "], 200);
        }
        if($request->device_token == 'COULD NOT GET FCM TOKEN'){
            return response()->json(['success' => "0", "message"=> 'COULD NOT GET FCM TOKEN'], 200);
        }
        if($User->account_status !='approved'){
            return response()->json(['success' => 0, "message"=> "Account deactivated. Please contact the administrator"], 200);
        }
        try { 
            $checker = Token::where('mobile','=',$request->mobile)->first();
            $now = Carbon::now()->addMinutes(1);
            if($checker ==null){
               return response()->json(['success' => 0, "message"=>"try again"], 200); 
            }
            // if($checker !=null  && $checker->updated_at < $now){
            //     return response()->json(['success' => 0, "message"=> "OTP Expired. Please try again"], 200);
            // }
            $token_user = Token::where('code','=',$request->otp)
                        ->where('mobile','=',$request->mobile)
                        ->first();

            if($token_user !=null){
                $User = User::where('mobile','=',$token_user->mobile)->first();
                if($User){
			\Auth::login($User);
                    if($request->has('device_token')){
                        $User->device_token = $request->device_token;
                    }

                    if($request->has('device_type')){
                        $User->device_type = $request->device_type;
                    }

                    if($request->has('device_id')){
                        $User->device_id = $request->device_id;
                    }
                    $User->save();
                    $userToken = $User->token()?:$User->createToken('otpLogin');
                    $token_user->delete();
                    return response()->json([
                            "status" => 1,
                            "message"=> "Login Successfully",
                            "admin_id"=> $User->admin_id,
                            "token_type" => "Bearer",
                            "access_token" => $userToken->accessToken
                            ]);
                }else{
                   
                    return response()->json(['success' => 0, "message"=>"Account details not found."], 200);

                }
               
            }else{
                return response()->json(['success' => 0, "message"=> "OTP Not Matched"], 200);
            }
 
        } 
        catch (Exception $e) {
            return response()->json(['success' => 0, "message"=> trans('api.something_went_wrong')], 200);
        }
    }


    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout()
    {
        $user = Auth::guard('api')->user()->token();
        $user->revoke();
        return response()->json([
            'success' => 'Successfully logged out'
        ]);
    }

    public function forgot_password(Request $request){

        $this->validate($request, [
                'mobile' => 'required|exists:users,mobile',
            ]);

        try{  
            
            $user = User::where('mobile',$request->mobile)->first();

            // $otp = mt_rand(1000, 9999);
            $otp = 123456;

            $user->otp = $otp;
            $user->save();

            // Notification::send($user, new ResetPasswordOTP($otp));
            //sms
            if(Setting::get('sms_enable', 0) == 1) {
                $mobile = $user->mobile;
                $message = 'Kindly note your OTP : '.$otp.' Regards '.config('app.name'); 
                    try {
                        Twilio::message($mobile, $message);
                    } catch ( \Services_Twilio_RestException $e ) {
                        //return $e->getMessage();  
                    }
            }
                
            return response()->json([
                'message' => 'OTP sent to your mobile!',
                'user' => $user
            ]);

        }catch(Exception $e){
                return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }
    /**
     * Reset Password.
     *
     * @return \Illuminate\Http\Response
     */

    public function reset_password(Request $request){

        $this->validate($request, [
                'password' => 'required|min:6',
                'id' => 'required|numeric|exists:users,id'
            ]);

        try{

            $User = User::findOrFail($request->id);
            $User->password = bcrypt($request->password);
            $User->save();

            if($request->ajax()) {
                return response()->json(['message' => 'Password updated']);
            }
            return response()->json([
                'message' => 'Password updated',
                'success' => 1
            ]);

        }catch (Exception $e) {
            if($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')]);
            }
        }
    }

    public function help_details(Request $request)
    {
        try{
            if($request->ajax()) {
                return response()->json([
                    'contact_number' => Setting::get('contact_number',''), 
                    'contact_email' => Setting::get('contact_email',''),
                    'contact_name' => Setting::get('contact_name',''), 
                    'sos_number'=>Setting::get('sos_number'),
                    'privacy' => url("/privacy"),
                    'terms' => url("/terms-conditions"),
                    'faq' => url("/faq"),
                    'website' => url("/"),
                     ]);
            }else{
                return response()->json([
                    'contact_number' => Setting::get('contact_number',''), 
                    'contact_email' => Setting::get('contact_email',''),
                    'contact_name' => Setting::get('contact_name',''), 
                    'sos_number'=>Setting::get('sos_number'),
                    'privacy' => url("/privacy"),
                    'terms' => url("/terms-conditions"),
                    'faq' => url("/faq"),
                    'website' => url("/"),
                     ]);
            }

        }catch (Exception $e) {
            if($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')], 500);
            }
        }
    }
}