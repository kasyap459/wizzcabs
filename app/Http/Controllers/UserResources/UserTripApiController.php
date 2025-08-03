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
use Setting;
use Exception;
use Notification;
use GuzzleHttp\Client;
use Carbon\Carbon;
use App\Notifications\ResetPasswordOTP;
use App\Helpers\Helper;
use Validator;
use App\Models\Complaint;
use App\Models\User;
use App\Models\Card;
use App\Models\Country;
use App\Models\ServiceType;
use App\Models\FareModel;
use App\Models\Promocode;
use App\Models\PromocodeUsage;
use App\Models\UserRequest;
use App\Models\Provider;
use App\Models\CorporateUser;
use App\Models\Corporate;
use App\Models\CorporateGroup;
use App\Models\UserRequestRating;
use App\Models\UserRequestPayment;
use App\Models\Location;
use App\Models\PoiFare;
use App\Models\LocationWiseFare;
use App\Models\RestrictLocation;
use App\Models\Token;
use App\Models\Admin;
use App\Models\MemberNotification;
use DateTimeZone;
use App\Models\RequestFilter;
use App\Models\FavouriteLocation;
use App\Models\UserNote;
use App\Models\UserCare;
use App\Models\ContactList;
use App\Models\UserRating;
use App\Models\ReferEarn;
use App\Models\WebNotify;
use App\Models\NotifiedDriver;
use App\Http\Controllers\SendPushNotification;

class UserTripApiController extends Controller
{  
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function send_request(Request $request) {
        
       $validator = Validator::make($request->all(), [
                's_latitude' => 'required|numeric',
                'd_latitude' => 'required|numeric',
                's_longitude' => 'required|numeric',
                'd_longitude' => 'required|numeric',
                'service_type' => 'required|numeric|exists:service_types,id',
                'payment_mode' => 'required|in:CASH,CARD,PAYPAL,CORPORATE,WALLET',
                'card_id' => ['required_if:payment_mode,CARD','exists:cards,card_id,user_id,'.Auth::user()->id],
                'distance' => 'required',
                'minutes' => 'required',
            ]);
            
            if($validator->fails()) { 
                  return response()->json(['success' => "0", "message"=>$validator->errors()->first()], 200); 
            }

        if(Auth::user()->admin_id !=  null){
            $admin = Admin::where('id','=',Auth::user()->admin_id)->first();
            if($admin->admin_type != 0 && $admin->time_zone != null){
                 date_default_timezone_set($admin->time_zone);
             }
         }



          if($request->payment_mode == 'WALLET'){
            if(Auth::user()->wallet_balance <=  Setting::get('min_wallet')) {
                return response()->json(['message' => 'Please Maintain '.Setting::get('currency') . Setting::get('min_wallet').'minimum Balance on your Wallet !! ','success'=> 0 ]);
         }
        }
        
        // if(Auth::user()->due_balance !=0.00){
        //     if($request->ajax()) {
        //         return response()->json(['message' => 'Clear Pending Dues to take trip','success'=>0]);
        //     } else {
        //         return redirect('dashboard')->with('flash_error', 'Clear Pending Dues to take trip');
        //     }
        // }

        $current = Carbon::now()->toTimeString();
        $restrict_pickup = RestrictLocation::whereIn('restrict_area',[1,2])->where('status','=',1)->get();
        foreach($restrict_pickup as $res_pickup){
            if($current > $res_pickup->s_time && $current < $res_pickup->e_time){
                $location = Location::where('id','=',$res_pickup->location_id)->select('tlatitude','tlongitude','location_name')->first();
                if($location !=null){
                    $vertices_y = array_filter(explode(',', $location->tlatitude));
                    $vertices_x = array_filter(explode(',', $location->tlongitude));
                    $points_polygon = count($vertices_x);
                    $latitude_y = $request->s_latitude;
                    $longitude_x = $request->s_longitude;
                    if(Helper::is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)){
                        return response()->json(['message' => trans('Zona de recogida restringida'),'success'=>0]);
                    }
                }
            }
        }
        $restrict_drop = RestrictLocation::whereIn('restrict_area',[1,3])->where('status','=',1)->get();
        foreach($restrict_drop as $res_drop){
            if($current > $res_drop->s_time && $current < $res_drop->e_time){
                $location = Location::where('id','=',$res_drop->location_id)->select('tlatitude','tlongitude','location_name')->first();
                if($location !=null){
                    $vertices_y = array_filter(explode(',', $location->tlatitude));
                    $vertices_x = array_filter(explode(',', $location->tlongitude));
                    $points_polygon = count($vertices_x);
                    $latitude_y = $request->d_latitude;
                    $longitude_x = $request->d_longitude;
                    if(Helper::is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)){
                        return response()->json(['message' => trans('DropOff de recogida restringida'), 'success'=>0]);
                    }
                }
            }
        }

        $corporate_id = 0;
        $corporate_group_id =0;
        if($request->corporate !=0){
            $corporate_user = CorporateUser::where('corporate_id','=',Auth::user()->corporate_user_id)->first();
            if($corporate_user !=null){
                $corporate = Corporate::where('id', $corporate_user->corporate_id)->first();
                if($corporate->status !=0){
                    $corporate_id = $corporate->id;
                    $corporate_group_id = $corporate_user->corporate_group_id;
                }else{
                    return response()->json(['message' => 'Corporate account is disabled', 'success' =>0]);
                }
            }else{
                return response()->json(['message' => 'Corporate account Not Found', 'success' =>0]);
            }
        }

        try{
            if(Auth::user()->trip_id !=0){
                return response()->json(['message' => trans('api.ride.request_inprogress'), 'success' =>0]);
            }

            if($request->has('schedule_date') && $request->has('schedule_time') && $request->schedule_date !=''){
                $beforeschedule_time = (new Carbon("$request->schedule_date $request->schedule_time"))->subMinute(15);
                $afterschedule_time = (new Carbon("$request->schedule_date $request->schedule_time"))->addMinute(15);

                $CheckScheduling = UserRequest::where('status','SCHEDULED')
                                ->where('user_id', Auth::user()->id)
                                ->whereBetween('schedule_at',[$beforeschedule_time,$afterschedule_time])
                                ->count();

                if($CheckScheduling > 0){
                    return response()->json(['message' => trans('api.ride.request_scheduled'), 'success' =>0]);
                }

            }

            $unit =Setting::get('distance_unit');

            if($unit =='km'){
                $kilometer = $request->distance;
            }else{
                $base = $request->distance;
                $kilometer = $base * 0.62137119;
            }

            $kilometer = round($kilometer,2);
            $minutes = $request->minutes;
            $fare_calc = Helper::fare_calc($request->service_type, $request->s_latitude, $request->s_longitude, $request->d_latitude, $request->d_longitude,$kilometer, $minutes);

            if($request->payment_mode == 'WALLET'){
            if(Auth::user()->wallet_balance <=  round($fare_calc['fare_flat'],2) ) {
                return response()->json(['message' => trans('api.recharge_wallet'),'success'=> 0]);
         }
        }

            $UserRequest = new UserRequest;
            $UserRequest->booking_id = 100;
            $UserRequest->admin_id = Auth::user()->admin_id;
            $UserRequest->user_id = Auth::user()->id;
            $UserRequest->user_name = Auth::user()->first_name;
            $UserRequest->user_mobile = Auth::user()->dial_code.Auth::user()->mobile;
            $UserRequest->service_type_id = $request->service_type;
            $UserRequest->base_fare = $request->base_fare ? :  0;
            $UserRequest->km_price = $request->km_fare ? :  0;
            $UserRequest->corporate_id = $corporate_id;
            $UserRequest->group_id = $corporate_group_id;
            $UserRequest->payment_mode = $request->payment_mode;
            $UserRequest->s_address = $request->s_address ? : "";
            $UserRequest->d_address = $request->d_address ? : "";
            $UserRequest->s_latitude = $request->s_latitude;
            $UserRequest->s_longitude = $request->s_longitude;
            $UserRequest->d_latitude = $request->d_latitude;
            $UserRequest->d_longitude = $request->d_longitude;
            $UserRequest->stop1_latitude = $request->stop1_latitude ? : "";
            $UserRequest->stop1_longitude = $request->stop1_longitude;
            $UserRequest->stop1_address = $request->stop1_address;
            $UserRequest->stop2_latitude = $request->stop2_latitude ? : "";
            $UserRequest->stop2_longitude = $request->stop2_longitude;
            $UserRequest->stop2_address = $request->stop2_address;
            $UserRequest->user_notes = $request->user_notes ? : "";
            $UserRequest->distance = $kilometer;
            $UserRequest->minutes = $minutes;
            $UserRequest->fare_type = $fare_calc['fare_type'];
            $UserRequest->estimated_fare = round($fare_calc['fare_flat'],2);
            $UserRequest->assigned_at = Carbon::now();
            $UserRequest->route_key ='null';
            $UserRequest->message = $request->message ? : "";
            $UserRequest->handicap = $request->handicap_access ? : 0;
            $UserRequest->pet = $request->travel_pet ? : 0;
            $UserRequest->wagon = $request->station_wagon ? : 0;
            $UserRequest->booster = $request->booster_seat ? : 0;
            $UserRequest->fixed_rate = $request->fixed_rate ? : 0;
            
            if($request->has('schedule_date') && $request->has('schedule_time') && $request->schedule_date !=''){
            $UserRequest->status = 'SCHEDULED';
            $UserRequest->trip_status = 'scheduled';
            $UserRequest->push = 'AUTO';
            $UserRequest->booking_by = 'APP';
            $UserRequest->user_rates = $request->user_rates ? : "";
            $UserRequest->surge = 0;
            $UserRequest->schedule_at = date("Y-m-d H:i:s",strtotime("$request->schedule_date $request->schedule_time"));
            }else{
                $UserRequest->status = 'SEARCHING';
                $UserRequest->push = 'AUTO';
                $UserRequest->booking_by = 'APP';
            }

            $UserRequest->save();
            $UserRequest->booking_id = '100'.$UserRequest->id;
            $UserRequest->save();

            if($request->has('schedule_date') && $request->has('schedule_time') && $request->schedule_date !='')
            {
                if($UserRequest->schedule_at < Carbon::now()->addHour(Setting::get('feature_time'))){
                    $this->notify_driver($UserRequest->id);
                }
                
                $WebNotify = new WebNotify;
                $WebNotify->type = "Schedule Trip";
                $WebNotify->title = "Schedule Trip Created";
                $WebNotify->status = 0;
                $WebNotify->save();
                return response()->json([
                    'message' => trans('api.new_request_dispatcher'),
                    'request_id' => $UserRequest->id,
                    'scheduled_status' => 1,
                    'success' =>1
                ]);
            }else{
                if($request->has('card_id')){
                    Card::where('user_id','=',Auth::user()->id)->update(['is_default' => 0]);
                    Card::where('card_id','=',$request->card_id)->update(['is_default' => 1]);
                }
                $user=User::where('id',Auth::user()->id)->first();
                if($user)
                {
                    $user->trip_id=$UserRequest->id;
                    $user->save();
                }
                $this->notify_driver($UserRequest->id);
                return response()->json([
                        'message' => trans('api.new_request_created'),
                        'request_id' => $UserRequest->id,
                        'scheduled_status' => 0,
                        'success' =>1
                    ]);
            }
            
        }catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'success' =>0], 200);
        }
    }    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function notify_driver($id) {

        try {

            // if(Auth::user()->admin_id !=  null){
            
            //  $admin = Admin::where('id','=',Auth::user()->admin_id)->first();
           
            //  if($admin->admin_type != 0 && $admin->time_zone != null){
            //      date_default_timezone_set($admin->time_zone);
                
            //  }
            // }
            $provider_min_balance =Setting::get('driver_min_wallet', '10');
            $UserRequest = UserRequest::where('id','=', $id)
                    ->select('id','created_at','booking_id','service_type_id','s_address','d_address','distance','schedule_at','assigned_at','status','s_latitude','s_longitude','booking_by')
                    ->orderBy('created_at','desc')
                    ->first();

            $start = Carbon::parse($UserRequest->assigned_at);
            $now = Carbon::now();
            $seconds = $now->diffInSeconds($start);
            $latitude = $UserRequest->s_latitude;
            $longitude=$UserRequest->s_longitude;
            $distance=Setting::get('provider_search_radius', '10');
            $data =[];
            $Providers = Provider::where('account_status','=', 'approved')
                        //->where('admin_id','=',Auth::user()->admin_id)
                        ->where('allowed_service', 'LIKE', '%,'.$UserRequest->service_type_id.',%')
                        ->where('status','=','active');
                        // ->where('trip_id', '=', 0);
                     //   ->where('wallet_balance','>=',$provider_min_balance)
                        // ->where('service_type_id','=', $UserRequest->service_type_id);

            if($UserRequest->booking_by =='SCHEDULED'){
            $Providers = Provider::where('account_status','=', 'approved')
                        //->where('admin_id','=',Auth::user()->admin_id)     
                        //->where('wallet_balance','>=',$provider_min_balance)                  
                       ->where('allowed_service', 'LIKE', '%,'.$UserRequest->service_type_id.',%');
            }  
            if($UserRequest->booking_by =='DISPATCHER'){
            $Providers = Provider::where('account_status','=', 'approved')
                        ->where('status','=','active')
                       // ->where('wallet_balance','>=',$provider_min_balance)
                       // ->where('service_type_id','=', $UserRequest->service_type_id);
                         ->where('allowed_service', 'LIKE', '%,'.$UserRequest->service_type_id.',%');
            }  
            if($UserRequest->booking_by =='CORPORATE'){
            $Providers = Provider::where('account_status','=', 'approved')
                        ->where('status','=','active')
                        // ->where('wallet_balance','>=',$provider_min_balance)
                        ->where('service_type_id','=', $UserRequest->service_type_id);
            }   
            if($UserRequest->admin_id !=null){
                $Providers->where('admin_id', '=', $UserRequest->admin_id);
            }
           
            $Providers = $Providers->selectRaw("id , (1.609344 * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) AS distance, latitude, longitude")
                        ->having('distance', '<', $distance)
                        ->orderBy('distance')
                        ->get();


            
            if($UserRequest->status !='SCHEDULED'){
                (new SendPushNotification)->IncomingTrip($Providers[0]->id);
            }
            $UserRequest->current_provider_id =  $Providers[0]->id;
            $UserRequest->save();
            $pro=Provider::where('id','=',$Providers[0]->id)->first();
            if($pro)
            {
                $pro->trip_id=$UserRequest->id;
                $pro->save();
            }

            foreach ($Providers as $key => $Provider) {
                if($Provider->id!=0)
                {
                    $Filter = new RequestFilter;
                    $Filter->request_id = $UserRequest->id;
                    $Filter->provider_id = $Provider->id; 
                    $Filter->save();

                    $notify=new NotifiedDriver;
                    $notify->provider_id=$Provider->id;
                    $notify->notified=0;
                    $notify->trip_id=$UserRequest->id;
                    $notify->save();
                }
                   return $Providers ;
            }
        } catch (Exception $e) {
            
        }
    }
}
