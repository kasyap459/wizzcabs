<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use Session;
use Log;
use Auth;
use Validator;
use App\Models\ServiceType;
use Setting;
use App\Http\Controllers\SendPushNotification;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function username()
    {
        $login = request()->input('mobile');

        if(is_numeric($login)){
            $field = 'mobile';
        } elseif (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        } else {
            $field = 'username';
        }

        return $field;
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('user.auth.new_login');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function send_otp(Request $request)
    {
        // $otp = rand(pow(10, 6-1), pow(10, 6)-1);
        $otp = 123456;
        Session::put('otp',$otp);
        Session::put('country_code',$request->country_code);
        Session::put('mobile',$request->mobile);
        $user = User::where('mobile','=',$request->mobile)->first();
        
        if($user !=null){
            // Log::info('New Dispatch : ' . $user->first_name);
            // $number = $request->country_code.$request->mobile;
            $mobile =$request->country_code . $request->mobile;
            $message_content = "<#>  FilRide : Your verification code is " . $otp . " PUJWhJxn7T+";
            (new SendPushNotification)->sendSMSUser($mobile, $message_content);
            return response()->json(['data' => 'OTP send to your mobile number','Otp' => $otp,'success'=>1]);
            
        }else{
            return response()->json(['data' => 'Mobile number not matched!, Please create an account','success'=>0]);
        }
        
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function verify_otp(Request $request)
    {
        $otp = $request->otp;
        $session_otp = Session::get('otp','');
        $session_phone = Session::get('mobile','');
        if($otp ==$session_otp){
            $user = User::where('mobile','=',$session_phone)->first();
            if($user !=null){
                Auth::loginUsingId($user->id);
                return response()->json(['data' => 'OTP verified successfully','success'=>1]);
            }else{
                return response()->json(['data' => 'User not found','success'=>0]);
            }
        }else{
            return response()->json(['data' => 'OTP not matched!','success'=>0]);
        }
    
    }
    public function login(Request $request,$token = null){
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users,email'
        ]);
        if($validator->fails()) { 
            return response()->json(['success'=>0,'message'=>'This Email not Register By Filride.'], 200);            
        }
        try{              
            $provider =User::where('email' , $request->email)->first();
            $password = $request->password;
            // $otp = mt_rand(1000, 9999);
            $services = ServiceType::all();
            $countrylatlng['lat'] = Setting::get('address_lat', 0);
            $countrylatlng['lng'] = Setting::get('address_long', 0);
            if (Auth::attempt(['email' => request('email'), 'password' => $password])) {
                return view('user.dashboard')->with(
                    ['token' => $token, 'email' => $request->email,'services' => $services, 'countrylatlng' => $countrylatlng]
                );
            }
          
            $message = "DO NOT SHARE:".$otp." is the OTP for your account. Keep this OTP to yourself for account safety.";
            $number = $provider->dial_code.$provider->mobile;
            (new SendPushNotification)->sendSMSUser($number,$message);

            

        }catch(Exception $e){
                return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    
    }
     public function sendResetLinkEmails(Request $request, $token = null){
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|exists:users,mobile'
        ]);
        if($validator->fails()) { 
            return response()->json(['success'=>0,'message'=>'This Mobile Number not Register By Mama Taxi.'], 200);            
        }
        try{              
            $provider = User::where('mobile' , $request->mobile)->first();
            // $otp=123456;
            $otp = mt_rand(1000, 9999);
            $provider->otp = $otp;
            $provider->save();
            $message = "DO NOT SHARE:".$otp." is the OTP for your account. Keep this OTP to yourself for account safety.";
            $number = $provider->dial_code.$provider->mobile;
            (new SendPushNotification)->sendSMSUser($number,$message);

            return view('user.auth.passwords.reset')->with(
                ['token' => $token, 'mobile' => $request->mobile]
            );

        }catch(Exception $e){
                return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }
}
