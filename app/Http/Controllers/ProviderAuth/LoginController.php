<?php

namespace App\Http\Controllers\ProviderAuth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\SendPushNotification;
use Illuminate\Support\Facades\Auth;
use Hesto\MultiAuth\Traits\LogsoutGuard;
use Illuminate\Http\Request;
use App\Models\Provider;
use Session;
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

    use AuthenticatesUsers, LogsoutGuard {
        LogsoutGuard::logout insteadof AuthenticatesUsers;
    }

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    public $redirectTo = '/provider/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('provider.guest', ['except' => 'logout']);
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('provider.auth.new_login');
    }

    public function showLoginForm_sp()
    {
        return view('provider.auth.new_login_sp');
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
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('provider');
    }

    public function send_otp(Request $request)
    {
        // $otp = rand(pow(10, 6-1), pow(10, 6)-1);
        $otp = 123456;
        Session::put('otp',$otp);
        Session::put('country_code',$request->country_code);
        Session::put('mobile',$request->mobile);
        $user = Provider::where('mobile','=',$request->mobile)->first();
        
        if($user !=null){
            $password = bcrypt($otp);

            Provider::where('id', $user->id)->update(['password' => $password]);

            $mobile =$request->country_code . $request->mobile;
            $message_content = "<#>  FilRide : Your verification code is " . $otp . " PUJWhJxn7T+";

            (new SendPushNotification)->sendSMSUser($mobile, $message_content);
            return response()->json(['data' => 'OTP send to your mobile number','otp'=>$otp,'success'=>1]);
            
        }else{
            return response()->json(['data' => 'Mobile number not matched!, Please create an account','success'=>0]);
        }
    }

    // public function verify_otp(Request $request)
    // {
    //     $otp = $request->password;
    //     $session_otp = Session::get('otp');
    //     $session_phone = Session::get('mobile');
    //     if($otp == $session_otp){
    //         $user = Provider::where('mobile','=',$session_phone)->first();
    //         if($user !=null){
    //             // Auth::loginUsingId($user->id);
    //             return url('/provider/login');
    //         }else{
    //             return response()->json(['data' => 'User not found','success'=>0]);
    //         }
    //     }else{
    //         return response()->json(['data' => 'OTP not matched!','success'=>0]);
    //     }
    // }

}
