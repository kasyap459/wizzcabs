<?php

namespace App\Http\Controllers\UserResources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\URL;
use Mail;
use DB;
use Log;
use Auth;
use Hash;
use Storage;
use File;
use Setting;
use Exception;
use Notification;
use GuzzleHttp\Client;
use Carbon\Carbon;
use App\Notifications\ResetPasswordOTP;
use App\Helpers\Helper;
use Validator;
use App\Models\User;
use App\Models\Admin;
use App\Models\UserWallet;


class UserProfileController extends Controller
{  
    
    public function user_wallet_history()
    {
       $wallet = UserWallet::where('user_id',Auth::user()->id)->select('amount','mode','created_at as created')->orderBy('created_at', 'DESC')->get()->toArray();
       return response()->json(['data' => $wallet,'currency' => Setting::get('currency'),'wallet_balance' => Auth::user()->wallet_balance]); 
    }

    public function change_password(Request $request){

        $this->validate($request, [
                'password' => 'required|min:6',
                'old_password' => 'required',
            ]);

        $User = Auth::user();
        if(Hash::check($request->old_password, $User->password))
        {
            $User->password = bcrypt($request->password);
            $User->save();
           return response()->json(['success' => "1", "message"=>trans('api.user.password_updated')], 200);
           
        } else {           
            return response()->json(['success' => "0", "message"=>trans('api.user.incorrect_password')], 500);
        }

    }    
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function update_profile(Request $request)
    {   
        if(Auth::user()->admin_id !=  null){
            $admin = Admin::where('id','=',Auth::user()->admin_id)->first();
            if($admin->admin_type != 0 && $admin->time_zone != null){
                 date_default_timezone_set($admin->time_zone);
             }
         }
        $validator = Validator::make($request->all(), [
            // 'first_name' => 'required|max:255',
            // 'last_name' => 'required|max:255',
            // 'email' => 'required',
            // 'dial_code' => 'required',
            // 'mobile' => 'required',
            // 'picture' => 'mimes:jpeg,bmp,png',
        ]);

        if($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->first()], 422);
        }

         try {

            $user = User::findOrFail(Auth::user()->id);

            if($request->has('first_name')){ 
                $user->first_name = $request->first_name;
            }
    
            if($request->has('email')){
                $user->email = $request->email;
            }
        
            if($request->has('mobile')){
                $user->mobile = $request->mobile;
            }

            if($request->has('last_name')){
                $user->last_name = $request->last_name;
            }

            if($request->has('dial_code')){
                $user->dial_code = $request->dial_code;
            }

            if ($request->picture != "") {
                 File::delete(public_path('uploads/user/profile/'.$user->picture));
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
                $user->picture = $local_url;
            
            }

            $user->save();
            return response()->json(['success'=>$user], 200);
            
        }
        catch (ModelNotFoundException $e) {
            return $e;
             return response()->json(['error' => trans('api.user.user_not_found')], 500);
        }
    } 

}
