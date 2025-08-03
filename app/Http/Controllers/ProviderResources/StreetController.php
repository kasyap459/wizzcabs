<?php

namespace App\Http\Controllers\ProviderResources;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Log;
use Auth;
use Setting;
use Exception;
use Carbon\Carbon;
use App\Helpers\Helper;
use App\Http\Controllers\SendPushNotification;
use Twilio;
use Mail;

use App\Models\User;
use App\Models\Admin;
use App\Models\Provider;
use App\Models\Vehicle;
use App\Models\FareModel;
use App\Models\Promocode;
use App\Models\PromocodeUsage;
use App\Models\UserRequest;
use App\Models\UserRequestPayment;
use App\Models\UserRequestRating;
use App\Models\ServiceType;
use App\Models\Waypoint;
use Validator;

class StreetController extends Controller
{

    /**
     * Show the Street ride trip send.
     *
     * @return \Illuminate\Http\Response
     */

    public function streetride_request(Request $request) {

        $validator = Validator::make($request->all(), [
            's_latitude' => 'required|numeric',
            'd_latitude' => 'required|numeric',
            's_longitude' => 'required|numeric',
            'd_longitude' => 'required|numeric',
        ]);
        if($validator->fails()) { 
            return response()->json(['message'=>$validator->errors(), 'success'=>0], 200);
        }

        $currentuser=null; $user_mobile = '';
        if(!empty($request->mobile)){
            $checkmobile = User::where('mobile', $request->mobile)->first();
            if($checkmobile !=null){
                $currentuser = $checkmobile;
            }                
        }

        // $provider_min_balance =Setting::get('driver_min_wallet', '10');
        // if(Auth::user()->wallet_balance < $provider_min_balance  ) 
        // {
        //     return response()->json(['message'=> "Please Recharge Your Wallet", 'success'=>0], 200);
        // }

        if(Auth::user()->account_status !='approved'){
            return response()->json(['message' => 'You account has not been approved for driving', 'success' =>0]);
        }

        if(Auth::user()->status == 'offline'){
            return response()->json(['message' => 'Go Online to take trip.', 'success' =>0]);
        }

        if(Auth::user()->trip_id !=0){
             return response()->json(['message' => 'Already in trip. Cannot take multiple at a time','success' =>0]);
        }
        if($currentuser !=null){
            $user_id = $currentuser->id;
            $user_name = $currentuser->first_name;
            $user_mobile = $currentuser->dial_code.$currentuser->mobile;
            $guest =1;
        }else{
            $user_id = 0;
            $user_name = $request->first_name ? : 'Street Ride Client';            
            if($request->mobile != ""){ $user_mobile = $request->mobile; }
            $guest =1;
        }
        //try{
            $unit =Setting::get('distance_unit');

            if($unit =='km'){
                $kilometer = $request->distance;
            }else{
                $base = $request->distance;
                $kilometer = $base * 0.62137119;
            }

            $kilometer = round($kilometer,2);
            $minutes = $request->minutes;

            $fare_calc = Helper::fare_calc(Auth::user()->service_type_id, $request->s_latitude, $request->s_longitude, $request->d_latitude, $request->d_longitude,$kilometer, $minutes);

            $UserRequest = new UserRequest;
            $UserRequest->admin_id = Auth::user()->admin_id;
            $UserRequest->booking_id = 100;
            $UserRequest->user_id = $user_id;
            $UserRequest->user_name = $user_name;
            $UserRequest->user_mobile = $user_mobile;
            $UserRequest->guest = $guest;
            $UserRequest->service_type_id = Auth::user()->service_type_id;
            $UserRequest->corporate_id = 0;
            $UserRequest->group_id = 0;
            $UserRequest->provider_id = Auth::user()->id;
            $UserRequest->status = 'PICKEDUP';
            $UserRequest->vehicle_id = Auth::user()->mapping_id;
            $UserRequest->partner_id = Auth::user()->partner_id ? : 0;
            $UserRequest->accepted_at = Carbon::now();
            $UserRequest->payment_mode = 'CASH';
            $UserRequest->push = 'AUTO';
            $UserRequest->booking_by = 'STREET';
            $UserRequest->s_address = $request->s_address ? : "";
            $UserRequest->s_latitude = $request->s_latitude;
            $UserRequest->s_longitude = $request->s_longitude;

            $UserRequest->d_address = $request->d_address ? : "";
            $UserRequest->d_latitude = $request->d_latitude;
            $UserRequest->d_longitude = $request->d_longitude;
            $UserRequest->stop1_latitude = $request->stop1_latitude ? : "";
            $UserRequest->stop1_longitude = $request->stop1_longitude;
            $UserRequest->stop1_address = $request->stop1_address;
            $UserRequest->stop2_latitude = $request->stop2_latitude ? : "";
            $UserRequest->stop2_longitude = $request->stop2_longitude;
            $UserRequest->stop2_address = $request->stop2_address;
            $UserRequest->message = $request->message ? : "";
            $UserRequest->handicap = $request->handicap ? : 0;
            $UserRequest->pet = $request->pet ? : 0;
            $UserRequest->wagon = $request->wagon ? : 0;
            $UserRequest->booster = $request->booster ? : 0;
            $UserRequest->fixed_rate = $request->fixed_rate ? : 0;
            $UserRequest->route_key = '';
            $UserRequest->fare_type = $fare_calc['fare_type'];
            $UserRequest->distance = $kilometer;
            $UserRequest->user_notes = "";
            $UserRequest->minutes = $minutes;
            $UserRequest->estimated_fare = round($fare_calc['fare_flat'],2);
            $UserRequest->started_at = Carbon::now();
            $UserRequest->assigned_at = Carbon::now();

            $UserRequest->use_wallet = 0;
            $UserRequest->surge = 0; 
            $UserRequest->save();
            $UserRequest->booking_id = '100'.$UserRequest->id;
            $UserRequest->save();
            Provider::where('id',Auth::user()->id)->update(['status' =>'riding','trip_id' => $UserRequest->id]);
            $provider = Provider::findOrFail(Auth::user()->id);
            // $provider->wallet_balance = $provider->wallet_balance - Setting::get('commision_trip_accept');
            $provider->save();
            return response()->json([
                        'message' => 'New request Created!',
                        'request_id' => $UserRequest->id,
                        'success' =>1
                    ]);

        // }catch (Exception $e) {
        //     return response()->json(['message' => trans('api.something_went_wrong'), 'success' =>0], 200);
        // }
    }
}
