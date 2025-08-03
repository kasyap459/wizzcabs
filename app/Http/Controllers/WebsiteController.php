<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mail;
use DB;
use Log;
use Auth;
use Hash;
use Storage;
use Setting;
use Exception;
use Notification;
use Carbon\Carbon;
use App\Notifications\ResetPasswordOTP;
use App\Helpers\Helper;
use Validator;

use App\Models\User;
use App\Models\Dispatcher;
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
use App\Models\RequestFilter;
use App\Models\ProviderService;
use App\Models\Admin;

class WebsiteController extends Controller
{
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countries = Country::all();
        $services = ServiceType::all();
        return view('web.home_new',compact('services','countries'));
    }

    public function sp_index()
    {
        $countries = Country::all();
        $services = ServiceType::all();
        return view('web_sp.home_new',compact('services','countries'));
    }

    /**
     * Update profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function book_taxi(Request $request)
    {
        try{
            $data = $request->all();
            $exists = 0;

            if(!empty($request->mobile) || !empty($request->email)){
                $checkmobile = User::where('mobile', $request->mobile)->first();
                $checkemail = User::where('email', $request->email)->first();
                $currentuser=null;
                if($checkmobile !=null){
                    $currentuser = $checkmobile;
                }
                if($checkemail !=null){
                    $currentuser = $checkemail;
                }
            }else{
                $currentuser=null;
            }

            if($currentuser !=null){
                $user_id = $currentuser->id;
                $user_name = $currentuser->name;
                $user_mobile = $currentuser->dial_code.$currentuser->mobile;
                $guest =0;
            }else{
                $user_id = 0;
                $user_name = $request->name;
                $user_mobile = $request->dial_code.$request->mobile;
                $guest =1;
            }

            if($currentuser !=null){
                if($currentuser->due_balance !=0.00){
                    return back()->with('flash_error','Clear Pending Dues to take trip');
                }
                if($currentuser->trip_id !=0){
                    return back()->with('flash_success',trans('api.ride.request_inprogress'));
                }
            }

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
                            return back()->with('flash_error','Pickup Location Zone Restricted');
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
                            return back()->with('flash_error','Destination Zone Restricted');
                        }
                    }
                }
            }

            $details = "https://maps.googleapis.com/maps/api/directions/json?origin=".$request->s_latitude.",".$request->s_longitude."&destination=".$request->d_latitude.",".$request->d_longitude."&mode=driving&key=".Setting::get('map_key');
            $json = curl($details);
            $details = json_decode($json, TRUE);
            $meter = $details['routes'][0]['legs'][0]['distance']['value'];
            $seconds = $details['routes'][0]['legs'][0]['duration']['value'];
            $route_key = $details['routes'][0]['overview_polyline']['points'];

            $unit = Setting::get('distance_unit');
        
            if($unit =='km'){
                $kilometer = $meter/1000;
            }else{
                $base = $meter/1000;
                $kilometer = $base * 0.62137119;
            }
            
            $kilometer = round($kilometer,2);
            $minutes = $seconds/60;
	    $fare_calc = Helper::fare_calc($request->service_type, $request->s_latitude, $request->s_longitude, $request->d_latitude, $request->d_longitude,$kilometer, $minutes);
	    //return $fare_calc;
            $UserRequest = new UserRequest;
            $UserRequest->booking_id = 100;
            $UserRequest->user_id = $user_id;
            // $UserRequest->user_name = $user_name;
            $UserRequest->user_name = 'Web Client';
            $UserRequest->user_mobile = $user_mobile;
            $UserRequest->guest = $guest;
            $UserRequest->service_type_id = $request->service_type ? : 1;
            $UserRequest->corporate_id = 0;
            $UserRequest->group_id = 0;
            $UserRequest->payment_mode = "CASH";
            $UserRequest->push = 'AUTO';
            $UserRequest->booking_by = 'WEB';

            $UserRequest->s_address = $request->s_address ? : "";
            $UserRequest->s_latitude = $request->s_latitude;
            $UserRequest->s_longitude = $request->s_longitude;
            $UserRequest->d_address = $request->d_address ? : "";
            $UserRequest->d_latitude = $request->d_latitude;
            $UserRequest->d_longitude = $request->d_longitude;
            $UserRequest->route_key = $route_key;
            $UserRequest->message = $request->message ? : "";
            $UserRequest->handicap = $request->handicap ? : 0;
            $UserRequest->pet = $request->pet ? : 0;
            $UserRequest->wagon = $request->wagon ? : 0;
            $UserRequest->booster = $request->booster ? : 0;
            $UserRequest->fixed_rate = $request->fixed_rate ? : 0;
            $UserRequest->passenger_count = $request->passenger_count ? : 0;
            $UserRequest->luggage = $request->luggage ? : 0;
            $UserRequest->distance = $kilometer;
            $UserRequest->minutes = $minutes;
            $UserRequest->user_notes = $request->message ? : "";
            //$UserRequest->estimated_fare = round($fare_calc['fare_flat'],2);
            $UserRequest->assigned_at = Carbon::now();
            $UserRequest->surge = 0;

            $now = Carbon::now()->addMinutes(1);
            $scheduled_timer = Carbon::parse($request->schedule_time);
            if($scheduled_timer > $now){
                $UserRequest->schedule_at = Carbon::parse($request->schedule_time);
                $UserRequest->status = 'SCHEDULED';
            }else{
                $UserRequest->status = 'SEARCHING';
            }
            
            $UserRequest->save();
            $UserRequest->booking_id = '100'.$UserRequest->id;
            $UserRequest->save();
            app(\App\Http\Controllers\UserResources\UserTripApiController::class)->notify_driver($UserRequest->id);
            return back()->with('flash_success','Booking Created Successfully');
        
        } catch (Exception $e) {
              return back()->with('flash_error', 'Something Went Wrong');
        }
    }
}
