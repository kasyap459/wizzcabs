<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Models\Country;
use Session;
use Mail;
use App\Models\Token;
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
     * Where to redirect users after registration.
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
        $this->middleware('guest');
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,NULL,id,deleted_at,NULL'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        // dd($data->all());
        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'dial_code' => Session::get('country_code'),
          //  'gender' => $data['gender'],
           // 'country_id' => $data['country_id'],
            'mobile' => Session::get('mobile'),
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
        return view('user.auth.new_register', compact('countries'));
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function send_otp(Request $request)
    {
        $user = User::where('mobile',$request->mobile)->first();
        if($user == NULL)
        {
            // $otp = rand(pow(10, 6-1), pow(10, 6)-1);
        $otp = 123456;
        Session::put('otp',$otp);
        Session::put('country_code',$request->country_code);
        Session::put('mobile',$request->mobile);

        $mobile =$request->country_code . $request->mobile;
        $message_content = "<#>  FilRide : Your verification code is " . $otp . " PUJWhJxn7T+";
        (new SendPushNotification)->sendSMSUser($mobile, $message_content);

        return response()->json(['data' => 'OTP send to your mobile number','otp'=> $otp ,'success' => 1]);
        }else{
            return response()->json(['data' => 'Mobile Number Already exists', 'success' => 0]);
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
