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
use DateTimeZone;
use App\Models\User;
use App\Models\Admin;
use App\Models\ProviderCashout;
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
use App\Models\GpsHistory;
use App\Models\Location;
use App\Models\RequestFilter;
use App\Models\MemberNotification;
use PushNotification;
use App\Models\ProviderWallet;
use App\Models\ContactList;
use Validator;
use App\Models\NotifiedDriver;

class TripController extends Controller
{

    /**
     * Toggle service availability of the provider.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function map_key(){
    
        $android_key = Setting::get('android_driver_map');
        $ios_key = Setting::get('ios_driver_map');

        return response()->json(['android_key' => $android_key,'ios_key'=>$ios_key]);
    } 
   

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function accept_trips(Request $request, $id)
    {
        //try {
            // if(Auth::user()->admin_id !=  null){
            //     $admin = Admin::where('id','=',Auth::user()->admin_id)->first();
            //     if($admin->admin_type != 0 && $admin->time_zone != null){
            //         date_default_timezone_set($admin->time_zone);
            //     }
            // }

            if(Auth::user()->account_status !='approved'){
                return response()->json(['error' => 'You account has not been approved for driving', 'success' =>0]);
            }

            $UserRequest = UserRequest::findOrFail($id);

            if($UserRequest->status != "SEARCHING" && $UserRequest->status != "SCHEDULED") {
                return response()->json(['error' => 'Trip already in progress', 'success' =>0]);
            }
            
            if(Auth::user()->status !='active'){
                return response()->json(['message' => 'Please go to Online to take Trip', 'success' =>0]);
            }
            if($UserRequest->schedule_at !=null){
                $manual_time =Setting::get('manual_time');
                $now = Carbon::now()->addMinutes($manual_time);
                // if($UserRequest->schedule_at > $now){
                //     return response()->json(['message' => 'You cannot start trip Before 1 Hour.', 'success' =>0]);
                // }
            }

            $UserRequest->provider_id = Auth::user()->id;           
            $UserRequest->status = "STARTED";
            $UserRequest->vehicle_id = Auth::user()->mapping_id;
            $UserRequest->partner_id = Auth::user()->partner_id;
            $UserRequest->accepted_at = Carbon::now();
            $UserRequest->save();
            RequestFilter::where('request_id','=', $id)->delete();
            Provider::where('id','=',Auth::user()->id)->update(['status' =>'riding','trip_id' => $UserRequest->id]);
            
            // $provider = Provider::findOrFail(Auth::user()->id);
            // $provider->wallet_balance = $provider->wallet_balance - Setting::get('commision_trip_accept');
            // $provider->save();
            if($UserRequest->user_id !=0){
                (new SendPushNotification)->RideAccepted($UserRequest);
            }
            return response()->json([
                'message' => 'Trip successfully accepted',
                'request_id' => $UserRequest->id,
                'booking_by' => $UserRequest->booking_by,
                'user_notes' => $UserRequest->message,
                'success' =>1
            ]); 

        // }catch (Exception $e){
        //     return response()->json(['error' => trans('api.something_went_wrong'), 'success' =>0]);
        // }
    }

    public function delete()
    {
     try{

        dd(Auth::user()); die;
 
    // Provider::where('id',Auth::guard('providerapi')->user()->id)->delete();
         
         return response()->json(['message' => 'Driver Deleted successfully', 'success' =>1], 200);
     }
     catch (ModelNotFoundException $e) {
         return response()->json(['message' => 'Something went Wrong', 'success' =>0], 200);
     }
   }
    /**
     * Cancel given request.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel_trips(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'request_id' =>'required',
            'cancel_reason'=> 'max:255',
            'cancel_request'=> 'required|numeric',
        ]);
        if($validator->fails()) { 
            return response()->json(['message'=>$validator->errors()->first(), 'success'=>0], 200);
        }
        //try{

        //     if(Auth::user()->admin_id !=  null){
        //     $admin = Admin::where('id','=',Auth::user()->admin_id)->first();
        //     if($admin->admin_type != 0 && $admin->time_zone != null){
        //          date_default_timezone_set($admin->time_zone);
        //      }
        //  }

        $UserRequest = UserRequest::where('id','=', $request->request_id)->where('provider_id','=', Auth::user()->id)->first();
            if($UserRequest->status != "SEARCHING" || $UserRequest->status != "SCHEDULED"){
                if($UserRequest->status =='STARTED' || $UserRequest->status =='ARRIVED'){   
                   // $driver_wallet =  Auth::user()->wallet_balance - Setting::get('driver_cancel_fee');                 
                    Provider::where('id','=',Auth::user()->id)->update(['status' =>'active','trip_id' => 0, 'active_from' =>Carbon::now()]);
                }
                //Driver can cancell trip before i hour
                $now = Carbon::now()->subMinutes(60);
                if($UserRequest->schedule_at !=Null && $UserRequest->schedule_at < $now){
                    $UserRequest->provider_id = 0;
                    $UserRequest->partner_id = 0;
                    $UserRequest->cancelled_by ="NONE";
                    $UserRequest->cancel_reason ="";
                    $UserRequest->booking_by ="APP";
                    $UserRequest->paid =0;
                    $UserRequest->assigned_at = Carbon::now();
                    $UserRequest->push = 'AUTO';
                    $UserRequest->status ="SCHEDULED";
                    $UserRequest->save();

                    return response()->json([
                        'message' => 'Trip successfully cancelled',
                        'request_id' => $UserRequest->id,
                        'success'=>1
                    ]);
                }    
                
                $UserRequest->status = "CANCELLED";
                $UserRequest->cancel_reason = $request->cancel_reason;
                $UserRequest->cancel_request = $request->cancel_request;
                $UserRequest->cancelled_by = "PROVIDER";
                $UserRequest->save();
                $driver_wallet =  Auth::user()->wallet_balance - Setting::get('driver_cancel_fee');
                Provider::where('id',Auth::user()->id)->update(['status' =>'active','trip_id' => 0, 'active_from' =>Carbon::now(),'wallet_balance' => $driver_wallet ]);
              

                // $UserRequest->provider_id = 0;
                // $UserRequest->partner_id = 0;
                // $UserRequest->cancelled_by ="NONE";
                // $UserRequest->cancel_reason ="";
                // $UserRequest->booking_by ="APP";
                // $UserRequest->paid =0;
                // $UserRequest->assigned_at = Carbon::now();
                // $UserRequest->push = 'AUTO';
                // $UserRequest->status ="SEARCHING";
                // $UserRequest->save();

                return response()->json([
                    'message' => 'Trip successfully cancelled',
                    'request_id' => $UserRequest->id,
                    'success'=>1
                ]);

            }else{
                return response()->json(['message' => 'Trip cannot be cancelled at this time!', 'success'=>0]);
            }

        // } catch (ModelNotFoundException $e) {
        //     return response()->json(['message' => 'Something went wrong', 'success'=>0]);
        // }
    }
    /**
     * Show the Offered Trips.
     *
     * @return \Illuminate\Http\Response
     */

    public function completed_trips(Request $request) {
    
        //try{    

            if($request->type==0){
            $UserRequests = UserRequest::where('status', '=', 'COMPLETED')
                    ->where('provider_id', '=', Auth::user()->id)
                    ->select('id','created_at','booking_id','service_type_id','schedule_at','s_address','s_latitude','s_longitude','d_address','d_latitude','d_longitude','message','route_key','distance','status')
                    ->where('created_at', '>=', Carbon::today())         
                    ->orderBy('created_at','desc')
                    ->get();
            if(!empty($UserRequests)){
                foreach ($UserRequests as $key => $value) {
                    $UserRequests[$key]->distance = $value->distance.Setting::get('distance_unit');
                    $UserRequests[$key]->service_name = ServiceType::where('id',$value->service_type_id)->pluck('name')->first();       
                }
            }
            return $UserRequests;
            }

            $UserRequests = UserRequest::where('status', '=', 'COMPLETED')
                    ->where('provider_id', '=', Auth::user()->id)
                    ->select('id','created_at','booking_id','service_type_id','schedule_at','s_address','s_latitude','s_longitude','d_address','d_latitude','d_longitude','message','route_key','distance','status')
                    ->orderBy('created_at','desc')
                    ->get();
            if(!empty($UserRequests)){
                foreach ($UserRequests as $key => $value) {
                    $UserRequests[$key]->distance = $value->distance.Setting::get('distance_unit');
                    $UserRequests[$key]->service_name = ServiceType::where('id',$value->service_type_id)->pluck('name')->first();       
                }
            }
            return $UserRequests;
        // }
        // catch (Exception $e){
        //     return response()->json(['error' => trans('api.something_went_wrong')]);
        // }
    }

    public function past_details(Request $request, $id) {    
       // try{

            $UserRequests = UserRequest::where('user_requests.id','=', $id)
                    ->where('provider_id', '=', Auth::user()->id)
                    ->leftJoin('users', 'user_requests.user_id', '=', 'users.id')
                    ->select('user_requests.user_name','user_requests.user_mobile','user_requests.guest','users.rating','users.picture','user_requests.id','user_requests.created_at','user_requests.booking_id','user_requests.service_type_id','user_requests.schedule_at','user_requests.s_address','user_requests.s_latitude','user_requests.s_longitude','user_requests.d_address','user_requests.d_latitude','user_requests.d_longitude','user_requests.message','user_requests.route_key','user_requests.started_at','user_requests.finished_at','user_requests.distance','user_requests.status','user_requests.corporate_id','user_requests.payment_mode')
                    ->first();

                    $UserRequests->picture=url('/').'/uploads/user/profile/'.$UserRequests->picture;                    
                if(!empty($UserRequests)){
                    $UserRequests->created_at = date("Y-m-d h:i A", strtotime($UserRequests->created_at));
                    $UserRequests->started_at = date("Y-m-d h:i A", strtotime($UserRequests->started_at));
                    $UserRequests->finished_at = date("Y-m-d h:i A", strtotime($UserRequests->finished_at));
                    $UserRequests->created_at1 = date("Y-m-d h:i A", strtotime($UserRequests->created_at));
                    $UserRequests->distance = $UserRequests->distance.Setting::get('distance_unit');
                    $UserRequests->service_name = ServiceType::where('id',$UserRequests->service_type_id)->pluck('name')->first();
                    if($UserRequests->status =='COMPLETED'){
                        $UserRequests->payment = UserRequestPayment::where('request_id',$UserRequests->id)->first();         
                    }
                }else{
                    return $UserRequests;
                }
           
            return $UserRequests;
        // }
        // catch (Exception $e){
        //     return response()->json(['error' => trans('api.something_went_wrong')]);
        // }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function start_trips(Request $request, $id)
    {
        //try {            
            // if(Auth::user()->admin_id !=  null){
            //     $admin = Admin::where('id','=',Auth::user()->admin_id)->first();
            //     if($admin->admin_type != 0 && $admin->time_zone != null){
            //         date_default_timezone_set($admin->time_zone);
            //     }
            // }

            if(Auth::user()->account_status !='approved'){
                return response()->json(['message' => 'You account has not been approved for driving', 'success' =>0]);
            }

            if(Auth::user()->trip_id != 0){
                 return response()->json(['message' => "Already on the trip. I cannot take several at once", 'success' =>0]);
            }
            
            if(Auth::user()->status !='active'){
                return response()->json(['message' => 'Go Online to take the trip', 'success' =>0]);
            }

            $UserRequest = UserRequest::findOrFail($id);
            if($UserRequest->schedule_at !=null){
                $manual_time =Setting::get('manual_time');
                $now = Carbon::now()->addMinutes($manual_time);
                if($UserRequest->schedule_at > $now){
                    return response()->json(['message' => 'You cannot start the trip before 1 hour.', 'success' =>0]);
                }
            }

            if($UserRequest->status !='ACCEPTED'){
                return response()->json(['message' => "Cannot change Status", 'success' =>0]);
            }                        
            $UserRequest->status = "STARTED";
            $UserRequest->save();
            Provider::where('id','=',Auth::user()->id)->update(['status' =>'riding','trip_id' => $UserRequest->id]);
            if($UserRequest->user_id !=0){
                $user = User::where('id','=',$UserRequest->user_id)->first();
                $user->trip_id = $UserRequest->id ;
                $user->save();
                //User::where('id','=',$UserRequest->user_id)->update(['trip_id' => $UserRequest->id]);
                (new SendPushNotification)->RideStarted($UserRequest);
            }

            

            return response()->json([
                'message' => 'Trip started successfully',
                'request_id' => $UserRequest->id,
                'user_notes' => $UserRequest->message,
                'success' =>1
            ]);

        // }catch (Exception $e){
        //     return response()->json(['message' => trans('api.something_went_wrong'), 'success' =>0]);
        // }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function arrived_trips(Request $request, $id)
    {
        //try {
            $UserRequest = UserRequest::where('id','=',$id)->first();
            
            if($UserRequest->status !='STARTED'){
                return response()->json(['message' => 'Cannot change Status', 'success'=>0]);
            }
            $UserRequest->status = "ARRIVED";
            $UserRequest->save();
            
            (new SendPushNotification)->Arrived($UserRequest);            
            
            return response()->json([
                'message' => 'You have arrived to Customer Location',
                'request_id' => $UserRequest->id,
                'user_notes' => $UserRequest->message,
                'success'=>1
            ]);
           
        // }catch (Exception $e){
        //     return response()->json(['message' => trans('api.something_went_wrong'),'success'=>0]);
        // }
    }    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function pickedup_trips(Request $request, $id)
    {
        //try {
            $validator = Validator::make($request->all(), [
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'address' => 'required'
            ]);
            if($validator->fails()) { 
                return response()->json(['message'=>$validator->errors(), 'success'=>0], 200);
            }
            $rating=5; $user_img=url('/').'/uploads/user/profile/user.png'; $avatar =''; $user=[]; $user_mobile='';
            $UserRequest = UserRequest::findOrFail($id);
            if($UserRequest)
            {
                if($UserRequest->status !='ARRIVED'){
                    return response()->json(['error' => 'Cannot change Status']);
                }
                $UserRequest->status = "PICKEDUP";
                $UserRequest->s_address = $request->address;
                $UserRequest->s_latitude = $request->latitude;
                $UserRequest->s_longitude = $request->longitude;
                if(preg_match("/(?:[01]\d|2[0123]):(?:[012345]\d):(?:[012345]\d)/", $request->trip_waiting_time)) {
                    $UserRequest->waiting_time = $request->trip_waiting_time;
                }                
                $UserRequest->started_at = Carbon::now();
                $UserRequest->save();
            }else{
                return response()->json(['message' => 'Trip not found', 'success'=>0]);
            }            
            

            $UserRequests1 = UserRequest::where(function ($query) {
                $query->whereIn('user_requests.status', ['PICKEDUP']);
            })                    
            ->where('user_requests.id', '=', $UserRequest->id)
            ->where('user_requests.provider_id', '=', Auth::user()->id)
            ->select('user_requests.id','user_requests.booking_id','user_requests.corporate_id','user_requests.s_address','user_requests.s_latitude','user_requests.s_longitude','user_requests.d_address','user_requests.d_latitude','user_requests.d_longitude','user_requests.status','user_requests.stop1_address','user_requests.stop1_latitude','user_requests.stop1_longitude','user_requests.stop2_address','user_requests.stop2_latitude','user_requests.stop2_longitude')
            ->first();

            if($UserRequest)
            {
                $user_name=$UserRequest->user_name;
                $user_notes=$UserRequest->user_notes;
                if($UserRequest->user_mobile){$user_mobile=$UserRequest->user_mobile;}
                if($UserRequest->user_id !=0){
                    $user=User::where('id',$UserRequest->user_id)->select('first_name','picture')->first();
                    if($user->first_name){$user_name=$user->first_name;}
                    if($user->rating){ $rating= $user->rating; }
                    if($user->picture){ $user_img=url('/').'/uploads/user/profile/'.$UserRequest->picture;}
                    if($user->avatar){$avatar =url('/').'/uploads/provider/profile/'.Auth::user()->avatar;}                         
                }
                $user=['user_name' => $user_name,'rating' => $rating,'user_img' => $user_img,'avatar' => $avatar,'user_notes' => $user_notes,'user_mobile'=>$user_mobile];
            }         
            

            return response()->json([
                'message' => 'You have pickedup the Customer',
                'request_id' => $UserRequest->id,
                'trip_info'=>$UserRequests1,
                'user_info' => $user,
            ]);
           
        // }catch (Exception $e){
        //     return response()->json(['error' => trans('api.something_went_wrong')]);
        // }
    }    

    public function update_destination(Request $request)
    {
        //try {

            $UserRequest = UserRequest::where('id',$request->id)->first();
            
            if($UserRequest->status == 'PICKEDUP'){

            	if($request->d_address){
                $UserRequest->d_address =$request->d_address;
                $UserRequest->d_latitude =$request->d_latitude;
                $UserRequest->d_longitude =$request->d_longitude;

                $unit =Setting::get('distance_unit');
                
                if($unit =='km'){
                    $kilometer = $request->distance;
                }else{
                    $base = $request->distance;
                    $kilometer = $base * 0.62137119;
                }


                $kilometer = round($kilometer,2);
                $minutes = $request->minutes;

                $fare_calc = Helper::fare_calc($UserRequest->service_type_id, $UserRequest->s_latitude, $UserRequest->s_longitude, $request->d_latitude,$request->d_longitude,$kilometer, $minutes);
                $UserRequest->estimated_fare = round($fare_calc['fare_flat'],2);
                $UserRequest->fare_type =$fare_calc['fare_type'];
                $UserRequest->distance = $kilometer;
                $UserRequest->minutes = $minutes;
            	}
                if($request->stop1_address){
                $UserRequest->stop1_address =$request->stop1_address;
                $UserRequest->stop1_latitude =$request->stop1_latitude;
                $UserRequest->stop1_longitude =$request->stop1_longitude;
                }
                if($request->stop2_address){
                $UserRequest->stop2_address =$request->stop2_address;
                $UserRequest->stop2_latitude =$request->stop2_latitude;
                $UserRequest->stop2_longitude =$request->stop2_longitude;
                }

                $UserRequest->save();

                return response()->json([
                    
                    'request_id' => $UserRequest->id,
                    'd_address' =>$UserRequest->d_address,
                    'd_latitude' =>$UserRequest->d_latitude,
                    'd_longitude' =>$UserRequest->d_longitude,
                    'stop1_address' =>$UserRequest->stop1_address,
                    'stop1_latitude' =>$UserRequest->stop1_latitude,
                    'stop1_longitude' =>$UserRequest->stop1_longitude,
                    'stop2_address' =>$UserRequest->stop2_address,
                    'stop2_latitude' =>$UserRequest->stop2_latitude,
                    'stop2_longitude' =>$UserRequest->stop2_longitude,
                    'success'=>1,
                    'message' => 'Destination Updated to Customer Location',
                ]);
            }
            else{
                return response()->json(['message' => 'Cannot change Destination', 'success'=>0]);
            }
                       
        // }catch (Exception $e){
        //     return response()->json(['message' => trans('api.something_went_wrong'),'success'=>0]);
        // }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function dropped_trips(Request $request, $id)
    {
        //try {
            $validator = Validator::make($request->all(), [
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'address' => 'required',
                'distance' => 'required',
            ]);
            

            $rating=5; $user_img=url('/').'/uploads/user/profile/user.png'; $avatar =''; $user=[]; $payment_update=0;
            $user_mobile='';

             $UserRequest = UserRequest::where('id','=',$id)->where('status','=','PICKEDUP')->first();
             if($UserRequest == NULL)
             {
               return response()->json(['message' => 'Cannot change Statu', 'success'=>0]);
             }
            if($UserRequest)
            {
                $UserRequest->d_address = $request->address;
                $UserRequest->d_latitude = $request->latitude;
                $UserRequest->d_longitude = $request->longitude;
                $UserRequest->finished_at = Carbon::now();
    
                if(preg_match("/(?:[01]\d|2[0123]):(?:[012345]\d):(?:[012345]\d)/", $request->stop_waiting_time)) {
                    $UserRequest->stop_waiting_time = $request->stop_waiting_time;
                }
                $unit =Setting::get('distance_unit');
    
                if($unit =='km'){
                    $kilometer = $request->distance;
                }else{
                    $base = $request->distance;
                    $kilometer = $base * 0.62137119;
                }
                
                $UserRequest->distance = round($kilometer,2);
                $UserRequest->save();
    
                //$UserRequest->invoice = $this->invoice($id);
                $UserRequest->invoice =Helper::invoice($UserRequest->id);

                $UserRequests1 = UserRequest::where(function ($query) {
                    $query->whereIn('user_requests.status', ['DROPPED']);
                })                    
                ->where('user_requests.id', '=', $UserRequest->id)
                ->where('user_requests.provider_id', '=', Auth::user()->id)
                ->select('user_requests.id','user_requests.booking_id','user_requests.corporate_id')
                ->first();
    
                $payment=UserRequestPayment::where('request_id','=',$UserRequest->id)->first(); 
                    if($payment==NULL){$payment='';}
                    if($UserRequests1!=NULL){
                        $UserRequests1->payment=$payment; 
                        if($payment){$UserRequests1->payment->corporate_id=$UserRequests1->corporate_id;}
                        $UserRequests1=$UserRequests1;}
    
                $user_name=$UserRequest->user_name;
                $user_notes=$UserRequest->user_notes;
                if($UserRequest->user_mobile){$user_mobile=$UserRequest->user_mobile;}
                if($UserRequest->user_id !=0){
                    $user=User::where('id',$UserRequest->user_id)->select('first_name','picture')->first();
                    if($user->first_name){$user_name=$user->first_name;}
                    if($user->rating){ $rating= $user->rating; }
                    if($user->picture){ $user_img=url('/').'/uploads/user/profile/'.$UserRequest->picture;}
                    if($user->avatar){$avatar =url('/').'/uploads/provider/profile/'.Auth::user()->avatar;}                         
                }
                $user=['user_name' => $user_name,'rating' => $rating,'user_img' => $user_img,'avatar' => $avatar,'user_notes' => $user_notes,'user_mobile'=>$user_mobile];
                if($UserRequest->payment_update){$payment_update=$UserRequest->payment_update;}

                $test=Provider::where('id','=',Auth::user()->id)->first();
                $test->status='riding';
                $test->save();

                return response()->json([
                    'message' => 'You have Dropped the Customer',
                    'request_id' => $UserRequest->id,
                    'user_info' => $user,
                    'trip_info'=>$UserRequests1,
                    'payment_update' => $payment_update,
                ]);
            }else{
                return response()->json(['message' => 'Trip not found', 'success' =>0]);
            }
           
        // }catch (Exception $e){
        //     return response()->json(['error' => trans('api.something_went_wrong')], 500);
        // }
    }
    
    
    public function additional_fare(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'request_id' => 'required|integer|exists:user_requests,id',
        ]);
        if($validator->fails()) { 
            return response()->json(['message'=>$validator->errors(), 'success'=>0], 200);
        }

        try {
            $UserRequest = UserRequest::findOrFail($request->request_id);
            if($UserRequest->status =='DROPPED'){
                $Payment = UserRequestPayment::where('request_id','=',$request->request_id)->first();
                $total = $Payment->total;
                $base_fare = $Payment->base_fare;
                if($request->has('base_fare')){
                    $Payment->base_fare = $base_fare + $request->base_fare;
                    // $updated_base_fare = $base_fare + $request->base_fare;
                    $Payment->total = $total + $request->base_fare; 
                    if($request->has('base_desc')){
                        $Payment->base_desc = $request->base_desc;
                    }
                }
                if($request->has('toll_fee')){
                    if($Payment->toll ==0.00){
                        $Payment->toll = $request->toll_fee;
                        $Payment->total = $total + $request->toll_fee;  
                    }else{
                        $app_fare = $total - $Payment->toll;
                        $Payment->toll = $request->toll_fee;
                        $Payment->total = $app_fare + $request->toll_fee;
                    }  
                }
                if($request->has('extra_fee')){
                    if($Payment->extra_fare ==0.00){
                        $Payment->extra_fare = $request->extra_fee;
                        $Payment->total = $total + $request->extra_fee;  
                    }else{
                        $app_fare = $total - $Payment->extra_fare;
                        $Payment->extra_fare = $request->extra_fee;
                        $Payment->total = $app_fare + $request->extra_fee;
                    }
                    if($request->has('extra_desc')){
                        $Payment->extra_desc = $request->extra_desc;
                    } 
                }
    
                $Payment->save();
                return response()->json(['message' => 'Added successfully', 'success' =>1]);
            }
        }catch (Exception $e){
            return response()->json(['message' => trans('api.something_went_wrong'),'success' =>0]);
        }

    }

    public function payment_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'request_id' => 'required|integer|exists:user_requests,id',
        ]);
        if($validator->fails()) { 
            return response()->json(['message'=>$validator->errors(), 'success'=>0], 200);
        }
        try {
            $UserRequest = UserRequest::findOrFail($request->request_id);
            if($UserRequest->status =='DROPPED'){
                $UserRequest->payment_update =1;
                $UserRequest->save();
                return response()->json(['message' => 'status updated', 'success' =>1]);
            }
        }catch (Exception $e){
            return response()->json(['message' => trans('api.something_went_wrong'),'success' =>0]);
        }
    }    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required',
            'comment' => 'max:255',
        ]);
        if($validator->fails()) { 
            return response()->json(['message'=>$validator->errors(), 'success'=>0], 200);
        }
    
        try {

            // if(Auth::user()->admin_id !=  null){
            // $admin = Admin::where('id','=',Auth::user()->admin_id)->first();
            // if($admin->admin_type != 0 && $admin->time_zone != null){
            //      date_default_timezone_set($admin->time_zone);
            //  }
            // }

            $UserRequest = UserRequest::where('id','=', $id)
                ->firstOrFail();
            if($UserRequest->rating == null) {
                UserRequestRating::create([
                        'provider_id' => $UserRequest->provider_id,
                        'user_id' => $UserRequest->user_id,
                        'request_id' => $UserRequest->id,
                        'provider_rating' => $request->rating,
                        'provider_comment' => $request->comment ? : '',
                    ]);
            }else {
                $UserRequest->rating->update([
                        'provider_rating' => $request->rating,
                        'provider_comment' => $request->comment ? : '',
                    ]);
            }

            $UserRequest->update(['provider_rated' => $request->rating]);
            if($UserRequest->status=="COMPLETED"){
                $test=Provider::where('id','=',Auth::user()->id)->first();
                $test->trip_id=0;
                $test->status='active';
                $test->active_from=Carbon::now();
                $test->save();
            }
            
            // Send Push Notification to Provider 
            $average = UserRequestRating::where('provider_id', $UserRequest->provider_id)->avg('provider_rating');
            if($UserRequest->user !=null){
                $UserRequest->user->update(['rating' => $average]);
            }
            
            return response()->json(['message' => 'Ride Completed!']);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Ride not yet completed!'], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function end_trips(Request $request, $id)
    {
       
        //try {
            $rating=5; $user_img=url('/').'/uploads/user/profile/user.png'; $avatar =''; $user=[]; $payment_update=0;
            $user_mobile='';
            $UserRequest = UserRequest::with('user','service_type','payment')->findOrFail($id);

            $user_name=$UserRequest->user_name;
            $user_notes=$UserRequest->user_notes;
            if($UserRequest->user_mobile){$user_mobile=$UserRequest->user_mobile;}

            if($UserRequest->user_id !=0){
                $user=User::where('id','=',$UserRequest->user_id)->select('first_name','picture')->first();
                if($user->first_name){$user_name=$user->first_name;}
                if($user->rating){ $rating= $user->rating; }
                if($user->picture){ $user_img=url('/').'/uploads/user/profile/'.$user->picture;}
                if($user->avatar){$avatar =url('/').'/uploads/provider/profile/'.Auth::user()->avatar;}                         
            }
            $user=['user_name' => $user_name,'rating' => $rating,'user_img' => $user_img,'avatar' => $avatar,'user_notes' => $user_notes,'user_mobile'=>$user_mobile];
          
            if($UserRequest->corporate_id !=0){
                $UserRequest->status = 'COMPLETED';
                $UserRequest->paid = 1;
                $UserRequest->save();
                // (new SendPushNotification)->Completed($UserRequest);
                return response()->json([
                    'data' => $UserRequest,
                    'user_info' => $user,
                ]);
            }
             
               if($UserRequest->paid == 0)
               {
                    if($UserRequest->payment_mode == 'CASH'){
                        $UserRequest->paid = 1;
                    }
                 
                    if($UserRequest->payment_mode == 'CARD'){
                    app(\App\Http\Controllers\PaymentController::class)->trip_payment($id,$UserRequest->user->id);
                    }

                     $Payment = UserRequestPayment::where('request_id','=',$UserRequest->id)->first();
                     
                     if($UserRequest->payment_mode == 'CASH'){
                    $wallet = Provider::find(Auth::user()->id);
                    $wallet->wallet_balance += $Payment->earnings;
                    $wallet->save();
                    ProviderWallet::create([
                    'provider_id' => Auth::user()->id,
                    'trip_id' =>$UserRequest->id,
                    'amount' => $Payment->earnings,
                    'mode' => 'Added by Trips',
                    'status' => 'Credited',
                    ]);

                }
                //else{

                //     $wallet = Provider::find(Auth::user()->id);
                //     $wallet->wallet_balance -= $Payment->commision;
                //     $wallet->save();

                //     ProviderWallet::create([
                //     'provider_id' => Auth::user()->id,
                //     'trip_id' =>$UserRequest->id,
                //     'amount' => $Payment->commision,
                //     'mode' => 'Debited by Trips',
                //     'status' => 'Debited',
                //     ]);


                // }  


                    if($UserRequest->payment_mode == 'WALLET'){
                        $User = User::find($UserRequest->user_id);
                        $Wallet = $User->wallet_balance; 
                        $Total =$UserRequest->payment->total;
                        $WalletBalance = $Wallet-$Total; 
                        User::where('id',$UserRequest->user_id)->update(['wallet_balance' => $WalletBalance]);
                    }
               }
            $UserRequest->paid = 1;
            $UserRequest->status = 'COMPLETED';
            $UserRequest->save(); 

            if($UserRequest->booking_by == 'STREET'){
            Provider::where('id','=',Auth::user()->id)->update(['trip_id' => 0, 'status' =>'active', 'active_from' =>Carbon::now()]);
            }

           // (new SendPushNotification)->Completed($UserRequest);
            

            return response()->json([
                'data' => $UserRequest,
                'user_info' => $user,
            ]);
           
     
    }   

    public function summary(Request $request)
    {   

    try{
        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        $type = $request->type;
        if($type ==0){
            $rides = UserRequest::where('provider_id', Auth::user()->id)->where('created_at', '>=', Carbon::today())->count();
             $revenue=   UserRequestPayment::whereHas('request', function($query) use ($request) {
                                        $query->where('provider_id', Auth::user()->id)->where('created_at', '>=', Carbon::today());
                                    })
                                ->sum('revenue');
            // $revenue = $total;
            $cancel_rides = UserRequest::where('status','CANCELLED')->where('provider_id', Auth::user()->id)->where('created_at', '>=', Carbon::today())->count();
            $scheduled_rides = UserRequest::where('trip_status','scheduled')->where('provider_id', Auth::user()->id)->where('created_at', '>=', Carbon::today())->count();
        }elseif($type ==1){
            $rides = UserRequest::where('provider_id', Auth::user()->id)->whereBetween('created_at', [Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()])->count();
            $revenue = UserRequestPayment::whereHas('request', function($query) use ($request) {
                        $query->where('provider_id', Auth::user()->id)->whereBetween('created_at', [Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()]);
                    })
             ->sum('revenue');
            // $revenue = $total;
            $cancel_rides = UserRequest::where('status','CANCELLED')->where('provider_id', Auth::user()->id)->whereBetween('created_at', [Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()])->count();
            $scheduled_rides = UserRequest::where('trip_status','scheduled')->where('provider_id', Auth::user()->id)->whereBetween('created_at', [Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()])->count();
        }elseif($type ==2){
            $rides = UserRequest::where('provider_id', Auth::user()->id)->whereMonth('created_at', Carbon::now()->month)->count();
            $revenue = UserRequestPayment::whereHas('request', function($query) use ($request) {
                        $query->where('provider_id', Auth::user()->id)->whereMonth('created_at', Carbon::now()->month);
                    })
                ->sum('revenue');
            // $revenue = $total;    
            $cancel_rides = UserRequest::where('status','CANCELLED')->where('provider_id', Auth::user()->id)->whereMonth('created_at', Carbon::now()->month)->count();
            $scheduled_rides = UserRequest::where('trip_status','scheduled')->where('provider_id', Auth::user()->id)->whereMonth('created_at', Carbon::now()->month)->count();
        }elseif($type ==3){
            $rides = UserRequest::where('provider_id', Auth::user()->id)->where('created_at', '>=', Carbon::now()->year)->count();
            $revenue = UserRequestPayment::whereHas('request', function($query) use ($request) {
                        $query->where('provider_id', Auth::user()->id)->where('created_at', '>=', Carbon::now()->year);
                    })
                ->sum('revenue');
            // $revenue = $total;
            $cancel_rides = UserRequest::where('status','CANCELLED')->where('provider_id', Auth::user()->id)->where('created_at', '>=', Carbon::now()->year)->count();
            $scheduled_rides = UserRequest::where('trip_status','scheduled')->where('provider_id', Auth::user()->id)->where('created_at', '>=', Carbon::now()->year)->count();
        }elseif($type ==4){
            $rides = UserRequest::where('provider_id', Auth::user()->id)->count();
            $revenue = UserRequestPayment::whereHas('request', function($query) use ($request) {
                        $query->where('provider_id', Auth::user()->id);
                    })
                ->sum('revenue');
            // $revenue = $total;
            $cancel_rides = UserRequest::where('status','CANCELLED')->where('provider_id', Auth::user()->id)->count();
            $scheduled_rides = UserRequest::where('trip_status','scheduled')->where('provider_id', Auth::user()->id)->count();
        }

            return response()->json([
                'rides' => $rides, 
                'revenue' => round($revenue,2),
                'cancel_rides' => $cancel_rides,
                'scheduled_rides' => $scheduled_rides,
            ]);
    }catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }


    public function earnings(Request $request)
    {
        try {

            Carbon::setWeekStartsAt(Carbon::SUNDAY);
            $type = $request->type;
            if($type==0){
                $earnings =
                    ProviderWallet::where('provider_id', Auth::user()->id)
                    ->where('created_at', '>=', Carbon::today())         
                   ->sum('amount');
                $kilometer = UserRequest::where('provider_id', Auth::user()->id)
                ->where('created_at', '>=', Carbon::today())         
                ->where('status', '=', 'COMPLETED')->sum('distance');
                $total_fare = UserRequestPayment::whereHas('request', function($query) use ($request) {
                            $query->where('provider_id', Auth::user()->id);
                        })
                    ->where('created_at', '>=', Carbon::today())         
                    ->sum('total');
                $commision = UserRequestPayment::whereHas('request', function($query) use ($request) {
                            $query->where('provider_id', Auth::user()->id);
                        })
                    ->where('created_at', '>=', Carbon::today())         
                    ->sum('commision');

                $extra_fare = UserRequestPayment::whereHas('request', function($query) use ($request) {
                            $query->where('provider_id', Auth::user()->id);
                        })
                    ->where('created_at', '>=', Carbon::today())         
                    ->sum('extra_fare');   
          }
            elseif($type==1){
                $earnings =ProviderWallet::where('provider_id', Auth::user()->id)
                    ->whereBetween('created_at', [Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()])   
                    ->sum('amount');
                $kilometer = UserRequest::where('provider_id', Auth::user()->id)
                ->whereBetween('created_at', [Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()])   
                ->where('status', '=', 'COMPLETED')->sum('distance');
                $total_fare = UserRequestPayment::whereHas('request', function($query) use ($request) {
                            $query->where('provider_id', Auth::user()->id);
                        })
                    ->whereBetween('created_at', [Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()])
                    ->sum('total');
                $commision = UserRequestPayment::whereHas('request', function($query) use ($request) {
                            $query->where('provider_id', Auth::user()->id);
                        })
                    ->whereBetween('created_at', [Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()])
                    ->sum('commision');

                $extra_fare = UserRequestPayment::whereHas('request', function($query) use ($request) {
                            $query->where('provider_id', Auth::user()->id);
                        })
                    ->whereBetween('created_at', [Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()])
                    ->sum('extra_fare');   
          }
            elseif($type==2){
                $earnings =ProviderWallet::where('provider_id', Auth::user()->id)
                        ->whereMonth('created_at', Carbon::now()->month)
                        ->sum('amount');
                $kilometer = UserRequest::where('provider_id', Auth::user()->id)
                ->whereMonth('created_at', Carbon::now()->month)
                ->where('status', '=', 'COMPLETED')->sum('distance');
                $total_fare = UserRequestPayment::whereHas('request', function($query) use ($request) {
                            $query->where('provider_id', Auth::user()->id);
                        })
                        ->whereMonth('created_at', Carbon::now()->month)
                       ->sum('total');
                $commision = UserRequestPayment::whereHas('request', function($query) use ($request) {
                            $query->where('provider_id', Auth::user()->id);
                        })
                        ->whereMonth('created_at', Carbon::now()->month)
                        ->sum('commision');

                $extra_fare = UserRequestPayment::whereHas('request', function($query) use ($request) {
                            $query->where('provider_id', Auth::user()->id);
                        })
                        ->whereMonth('created_at', Carbon::now()->month)
                      ->sum('extra_fare');   
          }

            elseif($type==3){
                $earnings =ProviderWallet::where('provider_id', Auth::user()->id)
                    ->where('created_at', '>=', Carbon::now()->year)
                    ->sum('amount');
                $kilometer = UserRequest::where('provider_id', Auth::user()->id)
                    ->where('created_at', '>=', Carbon::now()->year)
                ->where('status', '=', 'COMPLETED')->sum('distance');
                $total_fare = UserRequestPayment::whereHas('request', function($query) use ($request) {
                            $query->where('provider_id', Auth::user()->id);
                        })
                    ->where('created_at', '>=', Carbon::now()->year)
                    ->sum('total');
                $commision = UserRequestPayment::whereHas('request', function($query) use ($request) {
                            $query->where('provider_id', Auth::user()->id);
                        })
                    ->where('created_at', '>=', Carbon::now()->year)
                    ->sum('commision');

                $extra_fare = UserRequestPayment::whereHas('request', function($query) use ($request) {
                            $query->where('provider_id', Auth::user()->id);
                        })
                    ->where('created_at', '>=', Carbon::now()->year)
                    ->sum('extra_fare');   
          }            
          elseif($type==4){
                $earnings =ProviderWallet::where('provider_id', Auth::user()->id) 
                    ->sum('amount');
                $kilometer = UserRequest::where('provider_id', Auth::user()->id)->where('status', '=', 'COMPLETED')->sum('distance');
                $total_fare = UserRequestPayment::whereHas('request', function($query) use ($request) {
                            $query->where('provider_id', Auth::user()->id);
                        })
                    ->sum('total');
                $commision = UserRequestPayment::whereHas('request', function($query) use ($request) {
                            $query->where('provider_id', Auth::user()->id);
                        })
                    ->sum('commision');

                $extra_fare = UserRequestPayment::whereHas('request', function($query) use ($request) {
                            $query->where('provider_id', Auth::user()->id);
                        })
                    ->sum('extra_fare');   
          }
                $provider =Provider::where('id',Auth::user()->id)->first();

            return response()->json([
                'earnings' =>round($provider->wallet_balance,2), 
                'kilometer' => round($kilometer,2),
                'total_fare'=>round($total_fare,2),
                'commision' => round($commision,2), 
                'extra_fare' => round($extra_fare,2) 
            ]);
        }catch (Exception $e){
            return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }

    public function earning_details(Request $request)
    {
        try {
            Carbon::setWeekStartsAt(Carbon::SUNDAY);
            $type = $request->type;

            if($type==0){
            $earnings = UserRequestPayment::whereHas('request', function($query) use ($request) {
                        $query->where('provider_id', Auth::user()->id);
                    })
                ->where('created_at', '>=', Carbon::today())
                ->orderBy('created_at','desc')
                ->get();
            if(count($earnings) > 0){
                foreach($earnings as $earning) {
                    $userRequest = UserRequest::where('id',$earning->request_id)->first();
                    $dataArray[] = [
                    'id' => $earning->id,
                    'date' =>$userRequest->created_at,
                    'distance'=>$userRequest->distance,
                    'trip_fare' =>round($earning->total,2),
                    'commision' => round($earning->commision,2),
                    'earnings' => round($earning->earnings,2),
                    'tip' => round($earning->tip_fare,2)
                    ];    
                }
                return response()->json([
                    'earnings' => $dataArray, 
                ]);
            }
            }
            elseif($type==1){
            $earnings = UserRequestPayment::whereHas('request', function($query) use ($request) {
                        $query->where('provider_id','=', Auth::user()->id);
                    })
                ->whereBetween('created_at', [Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()])       
                ->orderBy('created_at','desc')
                ->get();
                if(count($earnings) > 0){
                    foreach($earnings as $earning) {
                        $userRequest = UserRequest::where('id','=',$earning->request_id)->first();
                        $dataArray[] = [
                        'id' => $earning->id,
                        'date' =>$userRequest->created_at,
                        'distance'=>$userRequest->distance,
                        'trip_fare' =>round($earning->total,2),
                        'commision' => round($earning->commision,2),
                        'earnings' => round($earning->earnings,2),
                        'tip' => round($earning->tip_fare,2)
                        ];    
                }
                return response()->json([
                    'earnings' => $dataArray, 
                ]);
                }
            }
            elseif($type==2){
            $earnings = UserRequestPayment::whereHas('request', function($query) use ($request) {
                        $query->where('provider_id','=', Auth::user()->id);
                    })
                ->whereMonth('created_at', Carbon::now()->month)
                ->orderBy('created_at','desc')
                ->get();
            if(count($earnings) > 0){
                foreach($earnings as $earning) {
                    $userRequest = UserRequest::where('id','=',$earning->request_id)->first();
                    $dataArray[] = [
                    'id' => $earning->id,
                    'date' =>$userRequest->created_at,
                    'distance'=>$userRequest->distance,
                    'trip_fare' =>round($earning->total,2),
                    'commision' => round($earning->commision,2),
                    'earnings' => round($earning->earnings,2),
                    'tip' => round($earning->tip_fare,2)
                    ];    
                }
                return response()->json([
                    'earnings' => $dataArray, 
                ]);
            }
            }
        elseif($type==3){
            $earnings = UserRequestPayment::whereHas('request', function($query) use ($request) {
                        $query->where('provider_id','=', Auth::user()->id);
                    })
                    ->whereBetween('created_at', [Carbon::now()->startOfYear(),Carbon::now()->endOfYear(),])
                    ->orderBy('created_at','desc')
                    ->get();
            if(count($earnings) > 0){
                foreach($earnings as $earning) {
                    $userRequest = UserRequest::where('id','=',$earning->request_id)->first();
                    $dataArray[] = [
                    'id' => $earning->id,
                    'date' =>$userRequest->created_at,
                    'distance'=>$userRequest->distance,
                    'trip_fare' =>round($earning->total,2),
                    'commision' => round($earning->commision,2),
                    'earnings' => round($earning->earnings,2),
                    'tip' => round($earning->tip_fare,2)
                    ];    
                }
                return response()->json([
                    'earnings' => $dataArray, 
                ]);
            }
            }
        elseif($type==4){
            $earnings = UserRequestPayment::whereHas('request', function($query) use ($request) {
                        $query->where('provider_id','=', Auth::user()->id);
                    })
                ->orderBy('created_at','desc')
                ->get();
            if(count($earnings) > 0){
                foreach($earnings as $earning) {
                    $userRequest = UserRequest::where('id','=',$earning->request_id)->first();
                    $dataArray[] = [
                    'id' => $earning->id,
                    'date' =>$userRequest->created_at,
                    'distance'=>$userRequest->distance,
                    'trip_fare' =>round($earning->total,2),
                    'commision' => round($earning->commision,2),
                    'earnings' => round($earning->earnings,2),
                    'tip' => round($earning->tip_fare,2)
                    ];    
                }
                return response()->json([
                    'earnings' => $dataArray, 
                ]);
            }
            }
            return response()->json([
                'earnings' => null, 
            ]);
        }catch (Exception $e){
            return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }

    public function all_trips(Request $request) {
        try{

            $data = array();
            Carbon::setWeekStartsAt(Carbon::SUNDAY);
            $type = $request->type;
            if($type==0){
            $UserRequests = UserRequest::where('provider_id', '=', Auth::user()->id)
                    ->join('service_types', 'user_requests.service_type_id', '=', 'service_types.id')
                    ->select('user_requests.id','user_requests.booking_id','user_requests.user_id','service_types.name','user_requests.status','user_requests.s_address','user_requests.d_address','user_requests.stop1_address','user_requests.stop2_address','user_requests.distance','user_requests.minutes','user_requests.corporate_id','user_requests.finished_at','user_requests.created_at','user_requests.started_at')
                    ->where('user_requests.created_at', '>=', Carbon::today())
                    ->orderBy('user_requests.created_at','desc')
                    ->get();

            }
            elseif($type==1){
            $UserRequests = UserRequest::where('provider_id', '=', Auth::user()->id)
                    ->join('service_types', 'user_requests.service_type_id', '=', 'service_types.id')
                    ->select('user_requests.id','user_requests.booking_id','user_requests.user_id','service_types.name','user_requests.status','user_requests.s_address','user_requests.d_address','user_requests.stop1_address','user_requests.stop2_address','user_requests.distance','user_requests.minutes','user_requests.corporate_id','user_requests.finished_at','user_requests.created_at','user_requests.started_at')
                    ->whereBetween('user_requests.created_at', [Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()])
                    ->orderBy('user_requests.created_at','desc')
                    ->get();
            }

            elseif($type==2){
            $UserRequests = UserRequest::where('provider_id', '=', Auth::user()->id)
                    ->join('service_types', 'user_requests.service_type_id', '=', 'service_types.id')
                    ->select('user_requests.id','user_requests.booking_id','user_requests.user_id','service_types.name','user_requests.status','user_requests.s_address','user_requests.d_address','user_requests.stop1_address','user_requests.stop2_address','user_requests.distance','user_requests.minutes','user_requests.corporate_id','user_requests.finished_at','user_requests.created_at','user_requests.started_at')
                    ->whereMonth('user_requests.created_at', Carbon::now()->month)
                    ->orderBy('user_requests.created_at','desc')
                    ->get();

            }
            elseif($type==3){
            $UserRequests = UserRequest::where('provider_id', '=', Auth::user()->id)
                    ->join('service_types', 'user_requests.service_type_id', '=', 'service_types.id')
                    ->select('user_requests.id','user_requests.booking_id','user_requests.user_id','service_types.name','user_requests.status','user_requests.s_address','user_requests.d_address','user_requests.stop1_address','user_requests.stop2_address','user_requests.distance','user_requests.minutes','user_requests.corporate_id','user_requests.finished_at','user_requests.created_at','user_requests.started_at')
                    ->where('user_requests.created_at', '>=', Carbon::now()->year)
                    ->orderBy('user_requests.created_at','desc')
                    ->get();
            }
            elseif($type==4){
            $UserRequests = UserRequest::where('provider_id', '=', Auth::user()->id)
                    ->join('service_types', 'user_requests.service_type_id', '=', 'service_types.id')
                    ->select('user_requests.id','user_requests.booking_id','user_requests.user_id','service_types.name','user_requests.status','user_requests.s_address','user_requests.d_address','user_requests.stop1_address','user_requests.stop2_address','user_requests.distance','user_requests.minutes','user_requests.corporate_id','user_requests.finished_at','user_requests.created_at','user_requests.started_at')
                    ->orderBy('user_requests.created_at','desc')
                    ->get();
            }
                foreach($UserRequests as $key=>$UserRequest){
                    $profile='';
                    if($UserRequest->user_id !=0 ){
                    $user_id=User::where('id',$UserRequest->user_id)->first();
                    if($user_id){
                      $profile=url('/').'/uploads/user/profile/'.$user_id->picture;
                    }
                    else{
                      $profile='';
                    } 
                    }
                    $data[] =['id' => $UserRequest->id,'booking_id'=>$UserRequest->booking_id,'name' => $UserRequest->name,'status' => $UserRequest->status,'picture' => $profile,'s_address' => $UserRequest->s_address,'d_address' => $UserRequest->d_address,'stop1_address' => $UserRequest->stop1_address,'stop2_address' => $UserRequest->stop2_address,'distance' => $UserRequest->distance,'minutes' => $UserRequest->minutes,'corporate_id' => $UserRequest->corporate_id,'started_at' =>date("Y-m-d h:i A", strtotime($UserRequest->started_at)),
                    'finished_at' =>date("Y-m-d h:i A", strtotime($UserRequest->finished_at)),'created_at' =>date("Y-m-d h:i A", strtotime($UserRequest->created_at))];
                }

            return $data;
        }
        catch (Exception $e){
            return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }

    public function pushNotification(Request $request)
    {
        try {
            $pushMsg = MemberNotification::where('person_id','=', Auth::user()->id)->where('member', '=','driver')->get();
            return response()->json(['pushMessage' => $pushMsg]);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }


    public function invoice_copy(Request $request){

        $validator = Validator::make($request->all(), [
            'request_id' => 'required',
        ]);
        if($validator->fails()) { 
            return response()->json(['message'=>$validator->errors(), 'success'=>0], 200);
        }

        try{
            $UserRequest = UserRequestPayment::where('request_id','=',$request->request_id)->first();
            $UserRequest->name = Auth::user()->name;
            if(Setting::get('mail_enable', 0) == 1) {

                Mail::send('emails.invoice-copy', ['UserRequest' => $UserRequest], function ($message) use ($UserRequest){
                    $message->to(Auth::user()->email, Auth::user()->name)->subject(config('app.name').' Invoice Copy');
                });

            }
                return response()->json(['success' => 1,'message'=>'Invoice copy sent to registered email']);

        }catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }


    public function testpushnotificationsss(Request $request){

        $data=  PushNotification::setService('fcm')
                        ->setMessage([
                            'priority' => 'high',
                            'notification' => [
                                     'title'=>config('app.name'),
                                     'body'=>'test',
                                    'sound' => 'alerttonee.mp3'
                                     ],
                             'data' => [
                                     'title'=>config('app.name'),
                                     'body'=>'test',
                                    'sound' => 'alerttonee.mp3'
                                     ]
                             ])
            ->setApikey('AAAAgVCJ4QM:APA91bESAwNMyNoCS0_tKLeyKWXozXsOpMF5uojGCA71uyB7gxamhyQA7modbR1mzBRir_Dh7f4Gf2IOgU-O481TifaZTjdTuJnlJWTrUM8oOKK-JybK0xargrZSfbT38qY1AjW7H2jN')
             ->setDevicesToken('e6q85fz3OWI:APA91bHicBaHkYa5yk6asSAZurU3wg53MTxlqmsSjYft_vflc37uvesBAR9JFSofQzdQ-bbNCVLUR7O15q7-6zL-n0kQrCXLHqYCUGjbu610osPJuwx156bWdK5oIs1uk3dWWrywzRl1')
             ->send();  
        echo '<pre>';
        print_r($data);
        exit;

        }


    public function contact_list(Request $request){

        try{
            $Contacts = ContactList::where('user_id','=', Auth::user()->id)->where('type','=','provider')->get();
            return $Contacts;           
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

        $validator = Validator::make($request->all(), [
            'contact_name' => 'required',
            'contact_number' => 'required',
        ]);
        if($validator->fails()) { 
            return response()->json(['message'=>$validator->errors(), 'success'=>0], 200);
        }

        try{
            $checklist = ContactList::where('user_id','=',Auth::user()->id)
                        ->where('type','=','provider')
                        ->where('contact_number','=',$request->contact_number)
                        ->get();
            if(count($checklist) ==0){
                $Contact = new ContactList;
                $Contact->user_id = Auth::user()->id;
                $Contact->contact_name = $request->contact_name;
                $Contact->contact_number = $request->contact_number;
                $Contact->type = 'provider';
                $Contact->save();
                return response()->json([
                    'message' => 'Contact Added Successfully',
                    'contact_name' => $Contact->contact_name,
                    'contact_number' => $Contact->contact_number,
                    'success' =>1

                ]);
            }else{
                return response()->json([
                    'message' => 'Contact Already Exists',
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

        $validator = Validator::make($request->all(), [
            'contact_number' => 'required',
        ]);
        if($validator->fails()) { 
            return response()->json(['message'=>$validator->errors(), 'success'=>0], 200);
        }

        try{

            $Contact = ContactList::where('user_id',Auth::user()->id)
                        ->where('type','provider')
                        ->where('contact_number',$request->contact_number)
                        ->delete();
            
            return response()->json([
                'message' => "Contact Deleted"
            ]);

        }catch (Exception $e) {
            if($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')]);
            }
        }
    }

    public function clear_status(Request $request) {

        $UserRequest = UserRequest::findOrFail($request->request_id);        
        if($UserRequest){  
            if($UserRequest->status=="CANCELLED")
            {
                Provider::where('id','=',$request->provider_id)->update(['trip_id' => 0,'status' => 'active', 'active_from' =>Carbon::now()]); 
                $UserRequest->provider_id=0;
                $UserRequest->current_provider_id=0;
                $UserRequest->save();

                return response()->json([
                    'message' => 'Trip Cleared successfully',
                    'success' =>1
                ]);
            }else{
                return response()->json(['message'=>'Something went wrong', 'success' =>0]);
            } 
            
        }else{
            return response()->json(['message'=>'Something went wrong', 'success' =>0]);
        }
            
    }

    public function cashout_request(Request $request)
    {

        $this->validate($request, [
            'amount' => 'required',
        ]);

try {

   


    $day = Carbon::now()->format('l');
    //  if($day == 'Thursday' )
    //  {
    
      $cashout = ProviderCashout::where('provider_id',Auth::user()->id)->where('status','REQUESTED')->first();
      if($cashout)
      {
        return response()->json([
            'message' => 'Request already in process',
            'success' =>0 
        ]);

      }else{
      $data = $request->except('token');
      $data['provider_id'] = Auth::user()->id;
      $data['status'] ='REQUESTED';
      $provider_cashout =  ProviderCashout::create($data);
      $provider_cashout['request_id'] = 'REQ'.$provider_cashout->id;
      $provider_cashout->save(); 

    //   $stripe = new \Stripe\StripeClient(
    //     'sk_test_51KquwyLFtB2qWAYIDQ9tFrjxZ6YJFo20USvmpmY7dpQTjgUgNaggThrv507uYdQqbj4OW5K1rU0ItVXiQOd8jrYO00mnzVUjGz'
    // );

    // $payout =$stripe->payouts->create([ 'amount' => $request->amount, 'currency' => 'usd', ],
    // [ 'stripe_account' => Auth::user()->stripe_acc_id, ]);

    
   // return $payout;  

     // $provider = Provider::where('id',Auth::user()->id)->update(['wallet_balance' => 0]);

      return response()->json([
        'message' => 'Cash Out request created!!',
        'success' =>1
    ]);
}

 // }else{

  //  return response()->json([
   //     'message' => 'Cashout Request allowed only Monday!!',
  //      'success' =>2
 //   ]);

 // }
} catch (\Throwable $th) {
   // dd($th); die;
    return response()->json(['error' => trans('api.something_went_wrong')]);
}
    } 


    public function cashout_list()
    {
          try {
                $data = ProviderCashout::where('provider_id',Auth::user()->id)
                ->select('id','request_id','provider_id','amount','status','created_at as  created')
                ->orderBy('created_at','desc')
                ->get();
                return $data; 
          } catch (\Throwable $th) {
              //throw $th; 
              return response()->json(['error' => trans('api.something_went_wrong')]);
          }
      

    }



}
?>
