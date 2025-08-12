<?php

namespace App\Http\Controllers\ProviderResources;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Controller;

use App\Notifications\ResetPasswordOTP;
use \Carbon\Carbon;

use Auth;
use Config;
use JWTAuth;
use Setting;
use Notification;
use Str;
use Validator;
use Socialite;
use Twilio;
use Mail;
use App\Models\Admin;
use DateTimeZone;
use App\Models\Provider;
use App\Models\ProviderDevice;
use App\Models\ProviderToken;
use App\Models\Vehicle;
use App\Models\Country;
use App\Http\Controllers\SendPushNotification;

class TokenController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required',
            'device_type' => 'required|in:android,ios',
            'device_token' => 'required',
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:providers,email,NULL,id,deleted_at,NULL',
            'mobile' => 'required|unique:providers,mobile,NULL,id,deleted_at,NULL',
            'dial_code' => 'required',
            'otp' => 'required|exists:provider_tokens,code,mobile,' . $request->mobile,
            'avatar' => 'required',
            'dob' => 'required',
        ]);

        if ($validator->fails()) {
            $mail = Provider::where('email', '=', $request->email)->first();
            $mobile = Provider::where('mobile', '=', $request->mobile)->first();
            $check_otp = ProviderToken::where('mobile', '=', $request->mobile)->first();
            if ($mail) {
                return response()->json(['success' => "0", "message" => "The email has already been taken."], 200);
            }
            if ($mobile) {
                return response()->json(['success' => "0", "message" => "The mobile has already been taken."], 200);
            }
            if ($check_otp->code != $request->otp) {
                return response()->json(['success' => "0", "message" => "Please enter the correct OTP."], 200);
            }
            return response()->json(['success' => "0", "message" => $validator->errors()->first()], 200);
        }

        try {
            $country = Country::where('dial_code', '=', $request->dial_code)->first();
            $token_user = ProviderToken::where('mobile', '=', $request->mobile)->first();
            $Provider = $request->all();

            if ($country != null) {
                $Provider['country_id'] = $country->countryid;
            }
            $Provider['account_status'] = 'onboarding';
            $Provider['wallet_balance'] = 0;

            if ($request->hasFile('avatar')) {

                // File::delete(public_path('uploads/provider/profile/'.$Provider->avatar));
                // Storage::delete($Provider->avatar);
                // $Provider->avatar = $request->avatar->store('public/provider/profile');
                // $Provider->avatar = $request->avatar->store('provider/profile');
                $picture = $request->avatar;
                $file_name = time();
                $file_name = rand();
                $file_name = sha1($file_name);
                if ($picture) {
                    $ext = $picture->getClientOriginalExtension();
                    $picture->move(public_path() . "/uploads/provider/profile/", $file_name . "." . $ext);
                    $local_url = $file_name . "." . $ext;
                    $Provider['avatar'] = url('/') . "/uploads/provider/profile/" . $local_url;
                }
            }

            $Provider = Provider::create($Provider);
            $password = Str::random(10);
            $Provider->password = bcrypt($password);
            $Provider->save();

            Config::set('auth.providers.users.model', 'App\Models\Provider');

            $credentials = [];
            $credentials['email'] = $request->email;
            $credentials['password'] = $password;
            if (!$token = auth('providerapi')->attempt($credentials)) {
                return response()->json(['message' => 'Email or Password incorrect', 'success' => 0], 200);
            }

            $Provider = Auth::guard('providerapi')->user();

            if ($Provider->admin_id) {
                $now = Carbon::now();
                $expired = $Provider->expires_at;
                if (Carbon::now() > $Provider->expires_at) {
                    return response()->json(['message' => 'Your Account has been expired.Please contact your administrator', 'success' => 0], 200);
                }
            }

            $Provider = Provider::with('device')->find(Auth::guard('providerapi')->user()->id);
            if ($Provider->device) {
                ProviderDevice::where('id', $Provider->device->id)->update([
                    'udid' => $request->device_id,
                    'token' => $request->device_token,
                    'type' => $request->device_type,
                ]);
            } else {
                ProviderDevice::create([
                    'provider_id' => $Provider->id,
                    'udid' => $request->device_id,
                    'token' => $request->device_token,
                    'type' => $request->device_type,
                ]);
            }
            Provider::where('id', $Provider->id)->update(['status' => 'active', 'active_from' => Carbon::now()]);
            return response()->json([
                'token' => $token,
                'expires' => auth('providerapi')->factory()->getTTL() * 60,
                'success' => 1,
                'token_type' => 'Bearer'
            ]);

            return response()->json(['data' => $Provider, 'success' => 1], 200);
            //return $Provider;

        } catch (QueryException $e) {
            // return $e;
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Algo salió mal. ¡Vuelve a intentarlo más tarde!', 'success' => 0], 200);
            }
            return abort(500);
        }
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function authenticate(Request $request)
    {
        $this->validate($request, [
            'device_id' => 'required',
            'device_type' => 'required|in:android,ios',
            'device_token' => 'required',
            'mobile' => 'required',
            'otp' => 'required',
            // 'vehicle_no' =>'required',
        ]);

        $token_user = ProviderToken::where('mobile', '=', $request->mobile)->first();
        $partner = Provider::where('mobile', '=', $request->mobile)->first();

        if ($token_user->code != $request->otp) {
            return response()->json(['message' => 'The OTP Is Incorrect', 'success' => 0], 200);
            // return response()->json(['message' => 'number 1', 'success' => 0], 200);

        }

        if ($partner == null) {
            return response()->json(['message' => 'Please register first', 'success' => 0], 200);
        }

        $password = 0;

        if ($partner->partner_id != "") {
            $password = 123456;

        } else {
            $password = 1234567;

        }

        Config::set('auth.providers.users.model', 'App\Models\Provider');
        $credentials = [];
        $credentials['mobile'] = $request->mobile;
        $credentials['password'] = $password;
        if (!$token = auth('providerapi')->attempt($credentials)) {
            return response()->json(['message' => 'The OTP Is Incorrect', 'success' => 0], 200);

        }

        $Provider = Auth::guard('providerapi')->user();

        if ($Provider->admin_id) {
            $now = Carbon::now();
            $expired = $Provider->expires_at;
            if (Carbon::now() > $Provider->expires_at) {
                return response()->json(['message' => 'Your Account has been expired.Please contact your administrator', 'success' => 0], 200);
            }
        }

        $Provider = Provider::with('device')->find(Auth::guard('providerapi')->user()->id);
        if ($Provider->device) {
            ProviderDevice::where('id', $Provider->device->id)->update([
                'udid' => $request->device_id,
                'token' => $request->device_token,
                'type' => $request->device_type,
            ]);
        } else {
            ProviderDevice::create([
                'provider_id' => $Provider->id,
                'udid' => $request->device_id,
                'token' => $request->device_token,
                'type' => $request->device_type,
            ]);
        }
        Provider::where('id', $Provider->id)->update(['status' => 'active', 'active_from' => Carbon::now()]);
        return response()->json([
            'token' => $token,
            'expires' => auth('providerapi')->factory()->getTTL() * 60,
            'success' => 1,
            'token_type' => 'Bearer'
        ]);
    }



    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function send_mobile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required',
            'dial_code' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => $validator->errors()->first(), "message" => 0]);
        }

        // $mobile = Provider::where('mobile', '=', $request->mobile)->first();
        // if ($mobile) {
        //     return response()->json(['success' => "0", "message" => "The mobile number has been registered."], 200);
        // }
        try {

            // $provider = Provider::where('mobile','=',$request->mobile)->first();
            // if($provider == null){
            //     return response()->json(['success' => 0, "message"=>"Account details not found. Please register with Prontotaxi"], 200);
            // }

            $token_user = ProviderToken::where('mobile', '=', $request->mobile)->first();
            $newUser = true;

            if ($token_user != null) {
                //    $otp = rand(pow(10, 4-1), pow(10, 4)-1);
                $otp = 123456;
                $token_user->code = $otp;
                $token_user->updated_at = Carbon::now();
                $token_user->save();

                $newUser = false;

                $number = $token_user->dial_code . $token_user->mobile;
                // $message = "<#> Prontotaxi: Your verification code is ".$otp." PUJWhJxn7T+";
                //$message = "DO NOT SHARE:".$otp." is the OTP for your account. Keep this OTP to yourself for account safety.";
                // (new SendPushNotification)->sendSMSUser($number,$message);

            } else {
                //    $otp = rand(pow(10, 4-1), pow(10, 4)-1);
                $otp = 123456;
                ProviderToken::create([
                    'code' => $otp,
                    'user_id' => null,
                    'mobile' => $request->mobile,
                    'dial_code' => $request->dial_code,
                    'used' => 0,
                ]);
                $number = $request->dial_code . $request->mobile;
                $message = "<#> Wizz-Cabs: Your verification code is " . $otp . " PUJWhJxn7T+";
                //$message = "DO NOT SHARE:".$otp." is the OTP for your account. Keep this OTP to yourself for account safety.";
                // (new SendPushNotification)->sendSMSUser($number,$message);
            }

            // $provider['password'] = bcrypt($otp);
            // $provider->save(); 

            return response()->json([
                'new_user' => $newUser,
                'success' => 1,
                "message" => "OTP Send Successful",
                'otp' => $otp
            ], 200);

        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }


    public function logout(Request $request)
    {
        try {
            // if(Auth::guard('providerapi')->user()->admin_id !=  null){
            // $admin = Admin::where('id','=',Auth::guard('providerapi')->user()->admin_id)->first();
            // if($admin->admin_type != 0 && $admin->time_zone != null){
            //      date_default_timezone_set($admin->time_zone);
            //  }
            // }
            ProviderDevice::where('provider_id', Auth::guard('providerapi')->user()->id)->orderBy('id', 'DESC')->update(['udid' => '', 'token' => '']);
            Provider::where('id', Auth::guard('providerapi')->user()->id)->update(['logout_at' => Carbon::now(), 'login_status' => 0, 'status' => 'offline']);

            Auth::guard('providerapi')->logout();
            return response()->json(['message' => trans('api.logout_success')]);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    /**
     * Forgot Password.
     *
     * @return \Illuminate\Http\Response
     */


    public function forgot_password(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'mobile' => 'required|exists:providers,mobile'
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => 0, 'message' => 'This mobile number is not registered in Wizz Cabs.'], 200);
        }
        try {
            $provider = Provider::where('mobile', $request->mobile)->first();
            $otp = 123456;
            // $otp = mt_rand(1000, 9999);
            // $otp = rand(pow(10, 4-1), pow(10, 4)-1);

            $provider->otp = $otp;
            $provider->save();
            $message = "DO NOT SHARE:" . $otp . " is the OTP for your account. Keep this OTP to yourself for account safety.";
            $number = $provider->dial_code . $provider->mobile;
            // Notification::send($provider, new ResetPasswordOTP($otp));
            // (new SendPushNotification)->sendSMSUser($number,$message);

            return response()->json([
                'success' => 1,
                'message' => "OTP sent to your mobile!",
                'provider' => $provider
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }


    /**
     * Reset Password.
     *
     * @return \Illuminate\Http\Response
     */

    public function reset_password(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'password' => 'min:6',
            'password_confirmation' => 'required_with:password|same:password|min:6',
            'id' => 'required|numeric|exists:providers,id'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'success' => 0], 200);
        }

        try {
            $Provider = Provider::findOrFail($request->id);
            $Provider->password = bcrypt($request->password);
            $Provider->save();
            return response()->json(['message' => 'Your Password Updated']);
        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')]);
            }
        }
    }

    /**
     * help Details.
     *
     * @return \Illuminate\Http\Response
     */

    public function help_details(Request $request)
    {

        try {

            if ($request->ajax()) {
                return response()->json([
                    'contact_number' => Setting::get('contact_number', ''),
                    'contact_email' => Setting::get('contact_email', ''),
                    'contact_name' => Setting::get('contact_name', ''),
                    'privacy' => url("/privacy"),
                    'terms' => url("/terms-conditions"),
                    'faq' => url("/faq"),
                    'website' => url("/"),
                ]);
            } else {
                return response()->json([
                    'contact_number' => Setting::get('contact_number', ''),
                    'contact_email' => Setting::get('contact_email', ''),
                    'contact_name' => Setting::get('contact_name', ''),
                    'privacy' => url("/privacy"),
                    'terms' => url("/terms-conditions"),
                    'faq' => url("/faq"),
                    'website' => url("/"),
                ]);
            }
        } catch (Exception $e) {
            if ($request->ajax()) {
                return $e;
            }
        }
    }

    public function send_otp(Request $request)
    {

        $validator = Validator::make($request->all(), [

            // 'email' => 'required|email|max:255|unique:providers,email,NULL,id,deleted_at,NULL',
            'mobile' => 'required',
            'dial_code' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => "0", "message" => $validator->errors()->first()], 200);
        }
        // $new_user = Provider::where('mobile', '=', $request->mobile)->get();
        $new_user = Provider::where('mobile', '=', $request->mobile)->first();

        if ($new_user != null) {
            //   $otp = rand(pow(10, 4-1), pow(10, 4)-1);
            $otp = 123456;
            // $provider = Provider::where('id',Auth::user()->id)->update(['otp' => $otp]);

            $number = $request->dial_code . $request->mobile;
            // $message = "<#> Prontotaxi: Your verification code is ".$otp." PUJWhJxn7T+";


            $token_user = ProviderToken::where('mobile', '=', $request->mobile)->first();
            /*before
            // if ($token_user != null) {
            //     $otp = 123456;
            //     $token_user->code = $otp;
            //     $token_user->updated_at = Carbon::now();
            //     $token_user->save();
            // }else {
            //     return response()->json(['success' => "0", "message" => "Por favor regístrese en Wizz Cabs"], 200);

            // }   */


            //after
            if ($token_user != null) {
                $otp = 123456;
                $token_user->code = $otp;
                $token_user->updated_at = Carbon::now();
                $token_user->save();
            } else {
                // $otp = 123456;
                // $token_user->code = $otp;
                // $token_user->updated_at = Carbon::now();
                // $token_user->save();

                $otp = 123456;
                ProviderToken::create([
                    'code' => $otp,
                    'user_id' => null,
                    'mobile' => $request->mobile,
                    'dial_code' => $request->dial_code,
                    'used' => 0,
                ]);
            }

            // $token =  ProviderToken::create([
            //     'code' => $otp,
            //     'user_id' =>null,
            //     'mobile' => $request->mobile,
            //     'dial_code' => $request->dial_code,
            //     'email' => $request->email,
            //     'used' => 0,
            // ]);

            //   Mail::send('emails.otp-register', ['user' => $token ], function ($message) use ($token){
            //             $message->to($token->email,'test')->subject(config('app.name').' '.'Driver Email Verification O T P');
            //         });
            $message = "DO NOT SHARE:" . $otp . " is the OTP for your account. Keep this OTP to yourself for account safety.";
            // (new SendPushNotification)->sendSMSUser($number,$message);

            return response()->json(['otp' => $otp, 'success' => 1, "message" => "OTP Send Successful"], 200);
        } else {
            return response()->json(['success' => "0", "message" => "Please register with Wizz Cabs"], 200);
        }
    }

    public function delete()
    {
        try {

            //  dd(Auth::guard('providerapi')->user()->id);

            Provider::where('id', Auth::guard('providerapi')->user()->id)->delete();

            return response()->json(['message' => 'Driver Deleted Successfully', 'success' => 1], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Something went Wrong', 'success' => 0], 200);
        }
    }
}
