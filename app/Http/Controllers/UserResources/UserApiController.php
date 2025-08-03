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

class UserApiController extends Controller
{  
    public function map_key(){
    
        $android_key = Setting::get('android_user_map');
        $ios_key = Setting::get('ios_user_map');

        return response()->json(['android_key' => $android_key,'ios_key'=>$ios_key]);
    }    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

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

    public function details(Request $request){

        $this->validate($request, [
            'device_type' => 'in:android,ios',
        ]);

        try{
            if($user = User::find(Auth::user()->id)){

                if($request->has('device_token')){
                    $user->device_token = $request->device_token;
                }

                if($request->has('device_type')){
                    $user->device_type = $request->device_type;
                }

                if($request->has('device_id')){
                    $user->device_id = $request->device_id;
                }
                $user->save();

                if($user->fav_provider){
                    $provider=Provider::where('id','=',$user->fav_provider)->first();
                    $service=ServiceType::where('id','=',$user->fav_service_type)->first();
                    $user->fav_driver = $provider->name;
                    $user->fav_vehicle = $service->name;
                }
                if($user->corporate_user_id !=null && $user->corporate_user_id !=0){
                    
                    $corporate_user = CorporateUser::where('corporate_id','=',$user->corporate_user_id)->first();

                    $user->corporate = Corporate::where('id','=',$corporate_user->corporate_id)->pluck('display_name')->first();

                            $corporate_ridedays = CorporateGroup::where('id','=',$corporate_user->corporate_group_id)->pluck('allowed_days')->first();
                    $user->corporate_ridedays = '';
                    $user->corporate_email = $corporate_user->emp_email;
                    $user->corporate_phone = $corporate_user->emp_phone;
                }
                //$user->picture = asset('storage/'.$user->picture);
                $user->picture =url('/').'/uploads/user/profile/'.$user->picture; 
                $user->currency = Setting::get('currency');
                $user->contact_number = Setting::get('contact_number');
                $user->sos_number = Setting::get('sos_number');
                return $user;

            } else {
                return response()->json(['error' => trans('api.user.user_not_found')], 500);
            }
        }
        catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    public function list_promocode(){

        try{
            $this->check_expiry();
            $new_user = Promocode::where('status','ADDED')
                ->where('user_type','new')
                ->where('updated_at','<=', Auth::user()->created_at)
                ->pluck('id')->toArray();
            $all_user = Promocode::where('status','ADDED')
                ->where('user_type','all')
                ->pluck('id')->toArray();
            $promocode_usage=PromocodeUsage::where('user_id',Auth::user()->id)
                ->pluck('promocode_id')->toArray();


            $promo_id=array_merge($new_user,$all_user);
            $all = Promocode::whereIn('id',$promo_id)->get();
            if($new_user){
            $all = Promocode::whereNotIn('id',$promocode_usage)
            ->where('status','ADDED')
            ->where('user_type','new')
            ->where('updated_at','<=', Auth::user()->created_at)
            ->get();
            }
            elseif($all_user){
            $all = Promocode::whereNotIn('id',$promocode_usage)
            ->where('status','ADDED')
            ->where('user_type','all')
            ->get();
            }
            else{
            $all = Promocode::whereNotIn('id',$promocode_usage)->get();
            }
            foreach($all as $key => $code) {
                $all[$key]->currency =Setting::get('currency');
            }
            return response()->json(['promocode'=>$all, ]);    
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    public function check_expiry(){
        try{ 
            if(Auth::user()->admin_id !=  null){
            $admin = Admin::where('id','=',Auth::user()->admin_id)->first();
            if($admin->admin_type != 0 && $admin->time_zone != null){
                 date_default_timezone_set($admin->time_zone);
             }
         }
            $Promocode = Promocode::all();
            foreach ($Promocode as $index => $promo) {
                if(date("Y-m-d") > $promo->expiration){
                    $promo->status = 'EXPIRED';
                    $promo->save();
                    PromocodeUsage::where('promocode_id', $promo->id)->update(['status' => 'EXPIRED']);
                }
            }
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }


    /**
     * add promo code.
     *
     * @return \Illuminate\Http\Response
     */

    public function add_promocode(Request $request) {

        $validator = Validator::make($request->all(), [
                'promocode' => 'required|exists:promocodes,promo_code',
            ]);

            if($validator->fails()) { 
                return response()->json(['success' => 0, "message"=> $validator->errors()->first()]);
            }

        try{
            if(Auth::user()->admin_id !=  null){            
                $admin = Admin::where('id','=',Auth::user()->admin_id)->first();           
                if($admin->admin_type != 0 && $admin->time_zone != null){
                    date_default_timezone_set($admin->time_zone);                    
                }
             }

            $this->check_expiry();

            $new_user = Promocode::where('status','ADDED')
                ->where('user_type','new')
                ->where('updated_at','<=', Auth::user()->created_at)
                ->pluck('id')->toArray();

            $all_user = Promocode::where('status','ADDED')
                ->where('user_type','all')
                ->pluck('id')->toArray();

            $promocode_usage=PromocodeUsage::where('user_id',Auth::user()->id)
                ->pluck('promocode_id')->toArray();


            $promo_id=array_merge($new_user,$all_user);
            
            $all = Promocode::whereIn('id',$promo_id)->get();
            // dd($all);
            if($new_user){
            $all = Promocode::whereNotIn('id',$promocode_usage)
            ->where('status','ADDED')
            ->where('user_type','new')
            ->where('updated_at','<=', Auth::user()->created_at)
            ->get();
            }
            elseif($all_user){
            $all = Promocode::whereNotIn('id',$promocode_usage)
            ->where('status','ADDED')
            ->where('user_type','all')
            ->get();
            }
            else{
            $all = Promocode::whereNotIn('id',$promocode_usage)->get();
            }

            $procode_status = 0;
            foreach($all as $key => $code) {
                if($code->promo_code == $request->promocode )
                {
                    $procode_status = 1;
                }
            }

            // return $procode_status;

            if($procode_status == 0)
            {

                    return response()->json([
                        'message' => 'Invalid promo code'
                    ]);
            }



            $find_promo = Promocode::where('promo_code',$request->promocode)->first();
            if($find_promo->status == 'EXPIRED' || (date("Y-m-d") > $find_promo->expiration)){

                if($request->ajax()){
                    return response()->json([
                        'message' => 'Promo code Expired', 
                        'code' => 'promocode_expired'
                    ]);
                }else{
                    return back()->with('flash_error', 'Promo code Expired');
                }
            }elseif(PromocodeUsage::where('promocode_id',$find_promo->id)->where('user_id', Auth::user()->id)->count() > 1){

                if($request->ajax()){
                    return response()->json([
                        'message' => 'Promo code already in use', 
                        'code' => 'promocode_already_in_use',
                       
                        ]);
                }else{
                    return back()->with('flash_error', 'Promocode Already in use');
                }
            }else{
                $promo = new PromocodeUsage;
                $promo->promocode_id = $find_promo->id;
                $promo->user_id = Auth::user()->id;
                $promo->usage = $find_promo->use_count;
                $promo->status = 'ADDED';
                $promo->save();

                // if($request->ajax()){
                    return response()->json([
                            'message' => 'Promo code already applied' ,
                            'code' => 'promocode_applied',
                            'discount' => $find_promo->discount,
                            'discount_type' => $find_promo->discount_type,
                            'currency' => Setting::get('currency'),
                         ]);
                // }else{
                //     return back()->with('flash_success', trans('api.promocode_applied'));
                // }
            }
        }
        catch (Exception $e) {
            if($request->ajax()){
                return response()->json(['error' => 'Something Went Wrong'], 500);
            }else{
                return back()->with('flash_error', 'Something Went Wrong');
            }
        }
    }

    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function estimated_fare(Request $request){
        
        $this->validate($request,[
            's_latitude' => 'required|numeric',
            's_longitude' => 'required|numeric',
            'd_latitude' => 'required|numeric',
            'd_longitude' => 'required|numeric',
            'distance' => 'required',
            'minutes' => 'required',
        ]);

        try{

            $unit =Setting::get('distance_unit');
            
            if($unit =='km'){
                $kilometer = $request->distance;
            }else{
                $base = $request->distance;
                $kilometer = $base * 0.62137119;
            }

            $kilometer = round($kilometer,2);
            $minutes = $request->minutes;
            $estimated_time=Carbon::now()->addMinutes($minutes);
            $datas = array();            
            $distance=Setting::get('provider_search_radius', '10');

        if($serviceList = ServiceType::where('status','=',1)->get()) {
            foreach($serviceList as $key => $service_type) {

                $Providers = Provider::selectRaw("id , (1.609344 * 3956 * acos( cos( radians('$request->s_latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$request->s_longitude') ) + sin( radians('$request->s_latitude') ) * sin( radians(latitude) ) ) ) AS distance, latitude, longitude")
                ->having('distance', '<', $distance)
                ->orderBy('distance', 'asc')
                ->where('status','=','active')
                ->where('allowed_service', 'LIKE', '%'.$service_type->id.'%')
                ->first();
                $provider_minutes = 'Not available';
                if($Providers != Null)
                {
                $provider_minutes =  round($Providers->distance/50 * 60,2);
                }

                $fare_calc = Helper::fare_calc($service_type->id, $request->s_latitude, $request->s_longitude, $request->d_latitude,$request->d_longitude,$kilometer, $minutes);
                $data = array(
                       'id' => $key,
                        'estimated_fare' => round($fare_calc['fare_flat'],2), 
                        'distance' => $kilometer,
                        'distance_unit' => $unit,
                        'fare_type' => $fare_calc['fare_type'],
                        'km_fare' => $fare_calc['km_fare'],
                        'fare_base' => $fare_calc['fare_base'],
                        'base_dist' => $fare_calc['base_dist'],
                        'distance_fare' => round($fare_calc['distance_fare'],2),
                        'min_fare' =>round($fare_calc['min_fare'],2),
                        'fare_waiting' => round($fare_calc['fare_waiting'],2),
                        'estimated_time' => date("h:i:a", strtotime($estimated_time)),
                        'time' => $minutes,
                        'service_id' => $service_type->id,
                        'service_name' => $service_type->name,
                        'service_image' => $service_type->image,
                        'description_image' => $service_type->description_image,
                        'url1' => 'https://demo.unicotaxi.com/uploads/',
                        'service_image_description1' => $service_type->service_image_description,
                        'service_image1' => $service_type->service_image,
                        'currency' => Setting::get('currency'),
                        'description' => $service_type->description,
                        'seats_available'=>$service_type->seats_available,
                        'payment_description'=>Setting::get('payment_description'),
                        'provider_minutes' =>  $provider_minutes,
                    );
                $datas[] = $data;
            }
        }    
        return response()->json(['data'=>$datas, 'success' =>1], 200);
            
        } catch(Exception $e) {
            dd($e);
            return response()->json(['message' => trans('api.something_went_wrong'), 'success' =>0], 200);
        }
    }
    
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function cancel_request(Request $request) {

        $this->validate($request, [
            'request_id' => 'required|numeric|exists:user_requests,id,user_id,'.Auth::user()->id,
        ]);

        try{
            if(Auth::user()->admin_id !=  null){
            $admin = Admin::where('id','=',Auth::user()->admin_id)->first();
            if($admin->admin_type != 0 && $admin->time_zone != null){
                 date_default_timezone_set($admin->time_zone);
             }
         }
            $UserRequest = UserRequest::findOrFail($request->request_id);
            if($UserRequest->status == 'CANCELLED')
            {
                return response()->json(['error' => trans('api.ride.already_cancelled')], 500);   
            }

            if(in_array($UserRequest->status, ['SEARCHING','STARTED','ARRIVED','SCHEDULED','ACCEPTED'])) {

                RequestFilter::where('request_id','=', $UserRequest->id)->delete();

                if($UserRequest->status != 'SEARCHING'){
                    $this->validate($request, [
                        'cancel_reason'=> 'max:255',
                    ]);
                }

                if($UserRequest->status != 'SCHEDULED'){
                    if($UserRequest->provider_id != 0){
                        Provider::where('id','=',$UserRequest->provider_id)->update(['status' => 'active','active_from' =>Carbon::now()]);
                    }
                }

               //  $user_wallet =  Auth::user()->wallet_balance - Setting::get('user_cancel_fee');
              //  User::where('id',Auth::user()->id)->update(['trip_id' => 0 , 'wallet_balance' => $user_wallet]);
                User::where('id',Auth::user()->id)->update(['trip_id' => 0]);
                //Provider::where('id','=', $UserRequest->current_provider_id)->update(['trip_id' => 0]);
                //Provider::where('trip_id','=', $UserRequest->id)->update(['trip_id' => 0]);
                $UserRequest->status = 'CANCELLED';
                $UserRequest->cancel_reason = $request->cancel_reason;
                $UserRequest->cancelled_by = 'USER';
                $UserRequest->save();

                 // Send Push Notification to User
                (new SendPushNotification)->UserCancellRide($UserRequest);
                return response()->json(['message' => trans('api.ride.ride_cancelled')]); 
                
            } else {
                return response()->json(['error' => trans('api.ride.already_onride')], 500); 
            }
        }
        catch (ModelNotFoundException $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        }

    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

     public function status(Request $request){        

        //try {
             if(Auth::user()->admin_id !=  null){
                 $admin = Admin::where('id','=',Auth::user()->admin_id)->first();
                 if($admin->admin_type != 0 && $admin->time_zone != null){
                     date_default_timezone_set($admin->time_zone);
                 }
             }
 
             // $Requests1 = UserRequest::select('id','user_id')->whereIn('status',['STARTED', 'ARRIVED','PICKEDUP','DROPPED'])->orderBy('created_at','desc')->get();
             // if(count($Requests1) >0){
             //     foreach($Requests1 as $Request3)
             //     {
             //         $user=User::where('id','=',$Request3->user_id)->where('trip_id','=',0)->update(['trip_id'=>$Request3->id]);
             //     }
             // }
             
 
             $UserRequests1=[]; $provider_id = ''; $service_type_id = ''; $provider_name =''; $provider_img =''; $cancelled_by =''; $cancel_reason ='';
             $status =''; $trip_id =''; $latitude = ''; $longitude = '';
             if($user = User::find(Auth::user()->id)){
                 if($request->has('latitude')){
                     $user->latitude = $request->latitude;
                     $user->longitude = $request->longitude;
                     $user->save();
                 }
         
                 if($user->trip_id !=0){
 
                     $UserRequest = UserRequest::where('id','=',$user->trip_id)->select('id','status','provider_id','cancelled_by','cancel_reason','payment_mode')->first();
                     $UserRequests1 = UserRequest::where(function ($query) {
                         $query->whereIn('user_requests.status', ['STARTED', 'ARRIVED','PICKEDUP','DROPPED','COMPLETED']);
                     })
                     ->where('user_requests.user_id', '=', Auth::user()->id)
                     ->join('providers', 'user_requests.provider_id', '=', 'providers.id')
                     ->join('vehicles', 'providers.mapping_id', '=', 'vehicles.id')
                     ->join('service_types', 'vehicles.service_type_id', '=', 'service_types.id')
                     ->leftJoin('user_request_payments', 'user_requests.id', '=', 'user_request_payments.request_id')
                     ->select('providers.name as driver_name','user_requests.payment_mode','providers.mobile','providers.avatar','providers.rating','vehicles.vehicle_no','service_types.name as service_name','service_types.image as service_image','user_requests.booking_id','user_requests.distance','user_requests.s_address','user_requests.s_latitude','user_requests.s_longitude','user_requests.d_address','user_requests.d_latitude','user_requests.d_longitude','user_requests.payment_update','user_requests.booking_id','user_requests.corporate_id','user_requests.stop1_address','user_requests.stop1_latitude','user_requests.stop1_longitude','user_requests.stop2_address','user_requests.stop2_latitude','user_requests.stop2_longitude','user_request_payments.total','user_request_payments.discount','user_request_payments.currency','user_request_payments.payment_id','user_request_payments.base_fare','user_request_payments.flat_fare','user_request_payments.distance_fare','user_request_payments.min_fare','user_request_payments.waiting_fare','user_request_payments.stop_waiting_fare','user_request_payments.vat','user_request_payments.discount','user_request_payments.toll','user_request_payments.extra_fare','user_request_payments.due_pending','user_request_payments.extra_desc','user_request_payments.cash','user_request_payments.total')
                     ->orderBy('user_requests.created_at','desc')
                     ->first();
 
                     // return $UserRequests1; die;
                     if($UserRequests1)
                     {
                         if($UserRequests1->avatar){
                         $avatar =url('/').'/uploads/provider/profile/'.$UserRequests1->avatar; 
                         }
                         else{
                         $UserRequests1->avatar = ''; 
                         }
                         $UserRequests1=$UserRequests1;
                     }
                     if($UserRequest)
                     {
                         $status =$UserRequest->status;
                         $trip_id =$UserRequest->id;
                         $cancelled_by =$UserRequest->cancelled_by;
                         $cancel_reason =$UserRequest->cancel_reason;                    
 
                         if($UserRequest->provider_id !=0){                        
 
                             $provider = Provider::where('id','=',$UserRequest->provider_id)->select('id','name','latitude','longitude','service_type_id','avatar')->first();
                             $latitude = $provider->latitude;
                             $longitude = $provider->longitude;
                             $provider_id =$provider->id;
                             $provider_name =$provider->name;
                             if($provider->avatar){$provider_img =url('/').'/uploads/provider/profile/'.$provider->avatar;}
                             $service_type_id =$provider->service_type_id;
                         }
                     }
                  }
 
                     if($UserRequests1==null || $UserRequests1=="<null>")
                     {
                         $UserRequests1=[];
                     }
 
                     return response()->json([
                         'data' => $status, 
                         'trip_id' => $trip_id,
                         'latitude' => $latitude,
                         'longitude' => $longitude,
                         'provider_id'=>$provider_id,
                         'service_type_id'=>$service_type_id,
                         'provider_name'=>$provider_name,
                         'avatar'=> $provider_img,
                         'cancelled_by'=>$cancelled_by,
                         'cancel_reason'=>$cancel_reason,
                         'trip_info' => $UserRequests1,
                         "account_status" => $user->account_status,
                     ]);
                 //  else{
                 //     return response()->json(['error' => 'check check error'], 500);
                 //  }
             }else{
                 return response()->json(['error' => trans('api.user.user_not_found')], 200);
             }
         // }catch (Exception $e){
         //     return response()->json(['error' => 'Something Went Wrong'], 200);
         // }
     }
    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */


    public function rate_provider(Request $request) {

        $validator = Validator::make($request->all(), [
                'request_id' => 'required|integer|exists:user_requests,id,user_id,'.Auth::user()->id,
                'rating' => 'required',
                'comment' => 'max:255',
            ]);

            if($validator->fails()) { 
                return response()->json(['message'=>$validator->errors()->first(), 'success' =>0], 200);
            }
            //  return response()->json(['message' => trans('api.ride.provider_rated')]); 
    
        $UserRequests = UserRequest::where('id','=' ,$request->request_id)
                ->where('status' ,'=','COMPLETED')
                ->where('paid','=', 0)
                ->first();

        if ($UserRequests) {
            return response()->json(['error' => trans('api.user.not_paid')], 500);
        }

        try{

            $UserRequest = UserRequest::findOrFail($request->request_id);
            if($UserRequest->rating == null) {
                UserRequestRating::create([
                        'provider_id' => $UserRequest->provider_id,
                        'user_id' => $UserRequest->user_id,
                        'request_id' => $UserRequest->id,
                        'user_rating' => $request->rating,
                        'user_comment' => $request->comment,
                    ]);
            } else {
                $UserRequest->rating->update([
                        'user_rating' => $request->rating,
                        'user_comment' => $request->comment,
                    ]);
            }

            $UserRequest->user_rated = 1;
            $UserRequest->user_rates = $request->user_rates;

            $UserRequest->save();
            if($request->tip_fare){
            $Payment = UserRequestPayment::where('request_id','=',$request->request_id)->first();
            $Payment->tip_fare=$request->tip_fare; 
            $Payment->total  = $Payment->total + $Payment->tip_fare;
            $Payment->save();

            $tip_fare =$Payment->tip_fare;
            if($UserRequest->payment_mode == 'CARD'){
               app(\App\Http\Controllers\PaymentController::class)->trip_payment_tips($UserRequest->id,$UserRequest->user->id,$tip_fare);
            }

            if($UserRequest->payment_mode == 'WALLET'){
                $User = User::find($UserRequest->user_id);
                $Wallet = $User->wallet_balance;
                $tip_fare =$Payment->tip_fare;
                $WalletBalance = $Wallet-$tip_fare;
               User::where('id',$UserRequest->user_id)->update(['wallet_balance' => $WalletBalance]);

               $provider = Provider::find($UserRequest->provider_id);
               $provider_Wallet = $provider->wallet_balance;
               $tip_fare =$Payment->tip_fare;
               $WalletBalance = $provider_Wallet+$tip_fare;
               Provider::where('id',$UserRequest->provider_id)->update(['wallet_balance' => $WalletBalance]);

            }
            }

            $average = UserRequestRating::where('provider_id','=', $UserRequest->provider_id)->avg('user_rating');

            Provider::where('id','=',$UserRequest->provider_id)->update(['rating' => $average]);
            if($UserRequest->status=="COMPLETED"){
            $user_inf=User::where('id','=', $UserRequest->user_id)->first();
            if($user_inf)
            {
                $user_inf->trip_id=0;
                $user_inf->save();
            }
            }
            // Send Push Notification to Provider 
            return response()->json(['message' => trans('api.ride.provider_rated')]); 
            
        } catch (Exception $e){
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }

    }    

    public function all_trips(Request $request) {

        try{
            $data = array();
            Carbon::setWeekStartsAt(Carbon::SUNDAY);
            $type = $request->type;
            if($type==0){
            $UserRequests = UserRequest::where('user_id', '=', Auth::user()->id)
                    // ->whereIn('user_requests.status', ['COMPLETED', 'CANCELLED'])
                    ->join('service_types', 'user_requests.service_type_id', '=', 'service_types.id')
                    // ->join('user_request_payments', 'user_requests.id', '=', 'user_request_payments.request_id')
                    ->select('user_requests.id','user_requests.booking_id','user_requests.provider_id','service_types.name','user_requests.estimated_fare','user_requests.status','user_requests.s_address','user_requests.d_address','user_requests.stop1_address','user_requests.stop2_address','user_requests.distance','user_requests.minutes','user_requests.corporate_id','user_requests.started_at','user_requests.finished_at','user_requests.created_at')
                    ->where('user_requests.created_at', '>=', Carbon::today())
                    ->orderBy('created_at','desc')
                    ->get();
            }
            elseif($type==1){
            $UserRequests = UserRequest::where('user_id', '=', Auth::user()->id)
                    ->join('service_types', 'user_requests.service_type_id', '=', 'service_types.id')
                    ->select('user_requests.id','user_requests.booking_id','user_requests.provider_id','service_types.name','user_requests.estimated_fare','user_requests.status','user_requests.s_address','user_requests.d_address','user_requests.stop1_address','user_requests.stop2_address','user_requests.distance','user_requests.minutes','user_requests.corporate_id','user_requests.started_at','user_requests.finished_at','user_requests.created_at')
                    ->whereBetween('user_requests.created_at', [Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()])
                    ->orderBy('created_at','desc')
                    ->get();
            }
            elseif($type==2){
            $UserRequests = UserRequest::where('user_id', '=', Auth::user()->id)
                    ->join('service_types', 'user_requests.service_type_id', '=', 'service_types.id')
                    ->select('user_requests.id','user_requests.booking_id','user_requests.provider_id','service_types.name','user_requests.estimated_fare','user_requests.status','user_requests.s_address','user_requests.d_address','user_requests.stop1_address','user_requests.stop2_address','user_requests.distance','user_requests.minutes','user_requests.corporate_id','user_requests.started_at','user_requests.finished_at','user_requests.created_at')
                    ->whereMonth('user_requests.created_at', Carbon::now()->month)
                    ->orderBy('created_at','desc')
                    ->get();
            }
            elseif($type==3){
            $UserRequests = UserRequest::where('user_id', '=', Auth::user()->id)
                    ->join('service_types', 'user_requests.service_type_id', '=', 'service_types.id')
                    ->select('user_requests.id','user_requests.booking_id','user_requests.provider_id','service_types.name','user_requests.estimated_fare','user_requests.status','user_requests.s_address','user_requests.d_address','user_requests.stop1_address','user_requests.stop2_address','user_requests.distance','user_requests.minutes','user_requests.corporate_id','user_requests.started_at','user_requests.finished_at','user_requests.created_at')
                    ->where('user_requests.created_at', '>=', Carbon::now()->year)
                    ->orderBy('created_at','desc')
                    ->get();
            }
            elseif($type==4){
            $UserRequests = UserRequest::where('user_id', '=', Auth::user()->id)
                    ->join('service_types', 'user_requests.service_type_id', '=', 'service_types.id')
                    ->select('user_requests.id','user_requests.booking_id','user_requests.provider_id','service_types.name','user_requests.estimated_fare','user_requests.status','user_requests.s_address','user_requests.d_address','user_requests.stop1_address','user_requests.stop2_address','user_requests.distance','user_requests.minutes','user_requests.corporate_id','user_requests.started_at','user_requests.finished_at','user_requests.created_at')
                    ->orderBy('created_at','desc')
                    ->get();
            }

                foreach($UserRequests as $key=>$UserRequest){
                $profile='';
                if($UserRequest->provider_id!=0){
                    $provider = Provider::where('id',$UserRequest->provider_id)->first();
                    if(isset($provider->avatar)){
                    //$profile=asset('storage/'.$provider->avatar);
                    $profile=url('/').'/uploads/provider/profile/'.$provider->avatar; 
                    }
                    else{
                    $profile='';
                    }
                }

                    $data[] =[
                    'id' => $UserRequest->id,
                    'booking_id'=>$UserRequest->booking_id,
                    'name' => $UserRequest->name,
                    'status' => $UserRequest->status,
                    'profile'=>$profile,
                    'estimated_fare'=>$UserRequest->estimated_fare,
                    's_address' => $UserRequest->s_address,
                    'd_address' => $UserRequest->d_address,
                    'stop1_address' => $UserRequest->stop1_address,
                    'stop2_address' => $UserRequest->stop2_address,
                    'distance' => $UserRequest->distance,
                    'minutes' => $UserRequest->minutes,
                    'corporate_id' => $UserRequest->corporate_id,
                    'started_at' =>date("Y-m-d h:i A", strtotime($UserRequest->started_at)),
                    'finished_at' =>date("Y-m-d h:i A", strtotime($UserRequest->finished_at)),
                    'created_at' =>date("Y-m-d h:i A", strtotime($UserRequest->created_at)),
                    'currency'=>Setting::get('currency')
                    ];

                }

            return $data;

            // return $UserRequests;
        }
        catch (Exception $e){
             // return $e;
            return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }

    /**
     * Show the Offered Trips.
     *
     * @return \Illuminate\Http\Response
     */

    public function past_details(Request $request, $id) {
    
        try{
            $UserRequests = UserRequest::where('user_requests.id','=', $id)
                    ->where('user_id', '=', Auth::user()->id)
                    ->join('service_types', 'user_requests.service_type_id', '=', 'service_types.id')
                    ->select('user_requests.id','booking_id','provider_id','s_latitude','s_longitude','stop1_latitude','stop1_longitude','stop2_latitude','stop2_longitude','d_latitude','d_longitude','service_types.name','user_requests.status','payment_mode','s_address','d_address','started_at','stop1_address','stop2_address','distance','minutes','provider_rated','corporate_id','finished_at','user_requests.created_at')
                    ->first();
            
                if(!empty($UserRequests)){
                    $UserRequests->created_at = date("Y-m-d h:i A", strtotime($UserRequests->created_at));

                    $UserRequests->created_at1 = date("Y-m-d h:i A", strtotime($UserRequests->created_at));



                    $UserRequests->started_at = date("Y-m-d h:i A", strtotime($UserRequests->started_at));
                    $UserRequests->finished_at = date("Y-m-d h:i A", strtotime($UserRequests->finished_at));

                    $UserRequests->distance = $UserRequests->distance.Setting::get('distance_unit');
                    if($UserRequests->status =='COMPLETED'){
                        $provider = Provider::where('id',$UserRequests->provider_id)->select('name','avatar')->first();
                        $UserRequests->driver_name = $provider->name;
                        //$UserRequests->avatar = asset('storage/'.$provider->avatar);
                        $UserRequests->avatar =url('/').'/uploads/provider/profile/'.$provider->avatar; 
                        $UserRequests->payment = UserRequestPayment::where('request_id',$UserRequests->id)->first();
                        $UserRequests->comment = UserRequestRating::where('request_id',$UserRequests->id)->pluck('provider_comment')->first();          
                    }    
                }
           
            return $UserRequests;
        }
        catch (Exception $e){
            return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }

    public function clear_status(Request $request) {
       
        if($request->request_id){           
        $UserRequest = UserRequest::findOrFail($request->request_id);

        if($UserRequest){
            if($UserRequest->status=="CANCELLED")
            {
                User::where('id','=', Auth::user()->id)->update(['trip_id' => 0]);
                //Provider::where('id','=', $UserRequest->current_provider_id)->update(['trip_id' => 0]);
                //Provider::where('trip_id','=', $UserRequest->id)->update(['trip_id' => 0]);
                $UserRequest->provider_id=0;
                $UserRequest->save();
            }
        }
        }
        return response()->json(['message'=>'status cleared!']);
    }

    public function pushnotification(Request $request)
    {
        try {
            $passenger = "passenger";
            $pushMsg = MemberNotification::where('person_id', Auth::user()->id)->where('member',$passenger)->get();
            return response()->json(['success' => "1", "message"=>$pushMsg], 200); 
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    public function address_list(Request $request)
    {
        try{
            $address = FavouriteLocation::where('user_id','=',Auth::user()->id)->get();
            return response()->json(['data' => $address, 'success' =>1], 200);      
        }
        catch (Exception $e){
            return response()->json(['message' => $e->getMessage(), 'success' =>0], 200);
        }
    }    
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function address_add(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'address' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'save_as' => 'required'
        ]);

        if($validator->fails()) { 
            return response()->json(['message'=>$validator->errors()->first(), 'success' =>0], 200);
        }

        try{

            FavouriteLocation::where('user_id',Auth::user()->id)->update(['is_default' => 0]);
            $input = $request->all();
            $input['user_id'] = Auth::user()->id;
            $input['is_default'] = 1;
            $input = FavouriteLocation::create($input);

            return response()->json(['message' => 'Address added successfully', 'success' =>1], 200);
        }
        catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Something went wrong', 'success' =>0], 200);
        }

    }

    public function address_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'address' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'id' => 'required',
        ]);

        if($validator->fails()) { 
            return response()->json(['message'=>$validator->errors()->first(), 'success' =>0], 200);
        }

        try{

            FavouriteLocation::where('user_id',Auth::user()->id)->where('id',$request->id)->update(['address' => $request->address,'lat' => $request->lat,'lng' => $request->lng,'house_no' => $request->house_no,'land_mark' => $request->land_mark,'save_as' => $request->save_as]);
            

            return response()->json(['message' => 'Address successfully updated', 'success' =>1], 200);
        }
        catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Something went Wrong', 'success' =>0], 200);
        }

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function address_delete(Request $request, $id)
    {
        try{
            $address = FavouriteLocation::where('user_id',Auth::user()->id)->where('id','=',$id)->delete();
            return response()->json(['message' => 'Address Deleted', 'success' =>1], 200);
       }
        catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Something went Wrong', 'success' =>0], 200);
        }
    }

    public function usernotes()
    {
        try{
            $usernotes = UserNote::where('status','=',1)->orderBy('created_at' , 'desc')->get();
            return response()->json(['success' =>1,'message' =>$usernotes], 200);
        }
        catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Something went Wrong', 'success' =>0], 200);
        }
       
    }

    public function userratings()
    {
        try{
            $userratings = UserRating::where('status','=',1)->orderBy('created_at' , 'desc')->get();
            return response()->json(['success' =>1,'message' =>$userratings], 200);
        }
        catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Something went Wrong', 'success' =>0], 200);
        }
       
    }

    public function usercare(Request $request){

        $this->validate($request, [
                'enquiry' => 'required',
            ]);

        try{

            $usercare = new UserCare;
            $usercare->admin_id = Auth::user()->admin_id;
            $usercare->ticket_id = 100;
            $usercare->user_id = Auth::user()->id;
            $usercare->user_name = Auth::user()->first_name;
            $usercare->mobile = Auth::user()->mobile;
            $usercare->enquiry = $request->enquiry;
            $usercare->status = 0;
            $usercare->save();
            $usercare->ticket_id = '100'.$usercare->id;
            $usercare->save();

            return response()->json(['success' => "1", "message"=>"Enquiry Sent Successfully"], 200); 

        }
        catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Something went Wrong', 'success' =>0], 500);
        }
    }

    public function getusercare(Request $request){

        try{

            $usercare = UserCare::where('user_id',Auth::user()->id)->get();

            return response()->json(['success' => "1", "message"=>$usercare], 200); 

        }
        catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Something went Wrong', 'success' =>0], 500);
        }
    }

    public function contact_list(Request $request){

        try{

            $Contacts = ContactList::where('user_id', Auth::user()->id)->where('type','user')->get();
            return $Contacts;
            /*if($request->ajax()) {
                return response()->json([$Contacts]);
            }*/
        }catch (Exception $e) {
            if($request->ajax()) {
                return response()->json(['error' => 'Something went Wrong']);
            }
        }
    }
    /**
     * help Details.
     *
     * @return \Illuminate\Http\Response
     */

    public function add_contact(Request $request){

        $this->validate($request, [
                'contact_name' => 'required',
                'contact_number' => 'required',
            ]);

        try{
            $checklist = ContactList::where('user_id',Auth::user()->id)
                        ->where('type','user')
                        ->where('contact_number',$request->contact_number)
                        ->get();
            if(count($checklist) ==0){
                $Contact = new ContactList;
                $Contact->user_id = Auth::user()->id;
                $Contact->contact_name = $request->contact_name;
                $Contact->contact_number = $request->contact_number;
                $Contact->type = 'user';
                $Contact->save();
                return response()->json([
                    'message' => 'Contact successfully added',
                    'contact_name' => $Contact->contact_name,
                    'contact_number' => $Contact->contact_number,
                    'success' =>1

                ]);
            }else{
                return response()->json([
                    'message' => 'The contact already exists',
                    'success' =>1
                ]);
            }

        }catch (Exception $e) {
            if($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')],500);
            }
        }
    }

    /**
     * help Details.
     *
     * @return \Illuminate\Http\Response
     */

    public function delete_contact(Request $request){

        $this->validate($request, [
                'contact_number' => 'required',
            ]);

        try{

            $Contact = ContactList::where('user_id',Auth::user()->id)
                        ->where('type','user')
                        ->where('contact_number',$request->contact_number)
                        ->delete();
            
                        return response()->json([
                            'message' => "Contact deleted"
                        ]);

        }catch (Exception $e) {
            if($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')]);
            }
        }
    }


    public function invoice_copy(Request $request){
        
        //return response()->json(['success' => 1,'message'=> trans('api.invoice_send')]);
        $this->validate($request, [
                'request_id' => 'required',
            ]);

        try{
            $UserRequest = UserRequestPayment::where('request_id',$request->request_id)->first();
            $UserRequest->name=Auth::user()->name;
            if(Setting::get('mail_enable', 0) == 1) {

                Mail::send('emails.invoice-copy', ['UserRequest' => $UserRequest], function ($message) use ($UserRequest){
                    $message->to(Auth::user()->email, Auth::user()->name)->subject(config('app.name').' Invoice Copy');
                });
                
            }
            return response()->json(['success' => 1,'message'=> trans('api.invoice_send')]);

        }catch (Exception $e) {
                return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }


    public function fav_driver(Request $request){

        $this->validate($request, [
                'fav_provider' => 'required',
                'fav_service_type'=>'required'
            ]);

        try{

            $User = User::where('id','=',Auth::user()->id)->first();
            $User->fav_provider=$request->fav_provider;
            $User->fav_service_type=$request->fav_service_type;
            $User->save();
            return response()->json(['success' => 1,'message'=>'Favorite Driver added']);

        }catch (Exception $e) {
                return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }

    public function tips(){

    $tips_0=  Setting::get('tip_0'); 
    $tips_1=  Setting::get('tip_1'); 
    $tips_2=  Setting::get('tip_2'); 
    $tips_3=  Setting::get('tip_3'); 
    $tips_4=  Setting::get('tip_4');
    $currency=  Setting::get('currency'); 
    $User = User::where('id','=',Auth::user()->id)->first();
    if($User->fav_provider){
     $fav_driver=1;
    }else{
     $fav_driver=0;
    }

    return response()->json([
        'success' =>1,
        'message' => 'Tips available',
        'tips_0' => $tips_0,
        'tips_1' => $tips_1,
        'tips_2' => $tips_2,
        'tips_3' => $tips_3,
        'tips_4' => $tips_4,
        'currency' => $currency,
        'fav_driver' => $fav_driver,
    ]);
   }

    public function stops()
    {
        $stop_title=  Setting::get('stop_title'); 
        $stop_description=  Setting::get('stop_description'); 

        return response()->json([
            'success' =>1,
            'message' => 'Stop Contents',
            'stop_title' => $stop_title,
            'stop_description' => $stop_description,
        ]);
   }

   public function delete()
   {
    try{

        User::where('id',Auth::user()->id)->delete();
        
        return response()->json(['message' => 'User successfully deleted', 'success' =>1], 200);
    }
    catch (ModelNotFoundException $e) {
        return response()->json(['message' => 'Something went Wrong', 'success' =>0], 200);
    }
  }
}
