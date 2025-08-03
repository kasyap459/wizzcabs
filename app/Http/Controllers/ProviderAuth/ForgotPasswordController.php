<?php

namespace App\Http\Controllers\ProviderAuth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Validator;
use App\Models\Provider;
use App\Http\Controllers\SendPushNotification;
class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

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
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        return view('provider.auth.passwords.new_email');
    }

    public function showLinkRequestForm_sp()
    {
        return view('provider.auth.passwords.new_email_sp');
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('providers');
    }

    public function sendResetLinkEmails(Request $request, $token = null){
        // dd($request->all($token));
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|exists:providers,mobile'
        ]);
        if($validator->fails()) { 
            return response()->json(['success'=>0,'message'=>'This Mobile Number not Register By Mama Taxi.'], 200);            
        }
        try{              
            $provider = Provider::where('mobile' , $request->mobile)->first();
            // $otp=123456;
            $otp = mt_rand(1000, 9999);
            $provider->otp = $otp;
            $provider->save();
            $message = "DO NOT SHARE:".$otp." is the OTP for your account. Keep this OTP to yourself for account safety.";
            $number = $provider->dial_code.$provider->mobile;
            (new SendPushNotification)->sendSMSUser($number,$message);

            return view('provider.auth.passwords.reset')->with(
                ['token' => $token, 'mobile' => $request->mobile]
            );

        }catch(Exception $e){
                return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }
}
