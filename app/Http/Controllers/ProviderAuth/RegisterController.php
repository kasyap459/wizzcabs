<?php

namespace App\Http\Controllers\ProviderAuth;

use Illuminate\Http\Request;
use App\Models\Provider;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use App\Models\Country;
use App\Models\User;
use App\Models\ProviderToken;
use Session;
use Mail;
use App\Http\Controllers\SendPushNotification;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/provider/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('provider.guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:providers,email,NULL,id,deleted_at,NULL',
           'password' => 'required|min:6|confirmed',
            'gender' => 'required',
            'license_no' => 'required',
            'license_expire' => 'required',
            'address' => 'required',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return Provider
     */
    protected function create(array $data)
    {
        return Provider::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'gender' => $data['gender'],
            'license_no' => $data['license_no'],
            'license_expire' => $data['license_expire'],
            'address' => $data['address'],
            'mobile' => Session::get('mobile'),
            'dial_code' => '+1',
            'country_id' => Session::get('country_code'),
        ]);
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        $countries = Country::all();
        return view('provider.auth.register', compact('countries'));
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('provider');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function send_otp(Request $request)
    {
        $user = Provider::where('mobile',$request->mobile)->first();
        if($user == NULL)
        {
        //$otp = rand(pow(10, 4-1), pow(10, 4)-1);
        $otp = 123456;
        Session::put('otp',$otp);
        Session::put('country_code',$request->country_code);
        Session::put('mobile',$request->mobile);
        $number = $request->country_code.$request->mobile;
        $message = "DO NOT SHARE:".$otp." is the OTP for your account. Keep this OTP to yourself for account safety.";
        return response()->json(['data' => 'OTP send to your mobile number', 'success' => 1]);
        (new SendPushNotification)->sendSMSUser($number,$message);
        }else{
            return response()->json(['data' => 'Mobile Number Already exists', 'success' => 0]); 
        }
    }
    

    public function send_otp_login(Request $request)
    {

        $user = Provider::where('mobile',$request->mobile)->first();
        if($user != NULL)
        {
        //$otp = rand(pow(10, 4-1), pow(10, 4)-1);
        $otp = 123456;
        $user->password = bcrypt($otp);
        $user->save();
        Session::put('otp',$otp);
        Session::put('country_code',$request->country_code);
        Session::put('mobile',$request->mobile);
        $number = $request->country_code.$request->mobile;
        $message = "DO NOT SHARE:".$otp." is the OTP for your account. Keep this OTP to yourself for account safety.";
        return response()->json(['data' => 'OTP send to your mobile number','success' => 1,'mobile' => $request->mobile ]);
        (new SendPushNotification)->sendSMSUser($number,$message);
        }else{
            return response()->json(['data' => 'Please Register Your Mobile Number','success' => 0]);
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
        if($otp ==$session_otp){
            return response()->json(['data' => 'OTP verified successfully','success'=>1]);
        }else{
            return response()->json(['data' => 'OTP not matched!','success'=>0]);
        }
    }
}
