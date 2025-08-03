<?php

namespace App\Http\Controllers\ProviderResources;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Log;
use Auth;
use Session;
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
use App\Models\ProviderDevice;
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

class ProviderStatusController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function status(Request $request){

        $Requests5 = UserRequest::select('user_requests.id','user_requests.current_provider_id')->join('request_filters','request_filters.request_id','=','user_requests.id')->join('notified_drivers','request_filters.request_id','=','notified_drivers.trip_id')
        ->where('user_requests.current_provider_id','=',Auth::user()->id)->where('notified_drivers.notified','=',0)->where('user_requests.status','=','SEARCHING')->first();
     
        if($Requests5)
        {
            $provider= Provider::where('id','=',$Requests5->current_provider_id)->where('trip_id','=',0)->first();
            if($provider)
            {
                $provider->trip_id=$Requests5->id;
                $provider->save(); 
            }                

            $filter=RequestFilter::where('provider_id','=',$Requests5->current_provider_id)->where('request_id','=',$Requests5->id)->first();
            if(!$filter)
            {
                $Filter = new RequestFilter;
                $Filter->request_id = $Requests5->id;
                $Filter->provider_id = $Requests5->current_provider_id; 
                $Filter->save();
            }
        }
             
        $UserRequests1=[]; $message = ''; $status =''; $trip_id =''; $paid ='';
        $user_notes=''; $user_img=''; $user_mobile='';
        $user_name=''; $s_address=''; $cancelled_by=''; $alert_status = 0; $ride ='';
        $rating=5; $user_img=url('/').'/uploads/user/profile/user.png'; $avatar =''; $user=[]; $payment_update=0; $old_login='';

        if($Provider = Provider::find(Auth::user()->id)){ 
            if($request->active_time)
            {
                $active_time=$request->active_time;
                $Provider->active_time=Carbon::now();
                $Provider->save();
            }

            if($request->latitude !='' && $request->longitude !=''){          
                $Provider->latitude = $request->latitude;
                $Provider->longitude = $request->longitude;
                $Provider->ride_from = Carbon::now();
                $Provider->save();
            }

            $old_login=ProviderDevice::where('provider_id','=',$Provider->id)->where('identify','=','old')->first();
            
            if($Provider->trip_id !=0){

                $UserRequest = UserRequest::where('id','=',$Provider->trip_id)
                ->select('id','user_id','user_mobile','user_name','payment_update','status','paid','s_latitude','s_longitude','d_latitude','d_longitude','stop1_latitude','stop1_longitude','stop2_latitude','s_address','stop2_longitude','user_notes','cancelled_by')->orderBy('created_at', 'desc')->first();
                
                if($UserRequest->status=="SEARCHING")
                {
                    $UserRequests1 = UserRequest::where(function ($query) {
                        $query->whereIn('user_requests.status', ['SEARCHING']);
                    })                    
                    ->where('user_requests.id', '=', $Provider->trip_id)
                    ->where('user_requests.current_provider_id', '=', Auth::user()->id)
                    ->select('user_requests.id','user_requests.booking_id','user_requests.corporate_id','user_requests.s_address','user_requests.s_latitude','user_requests.s_longitude','user_requests.d_address','user_requests.d_latitude','user_requests.d_longitude','user_requests.status','user_requests.stop1_address','user_requests.stop1_latitude','user_requests.stop1_longitude','user_requests.stop2_address','user_requests.stop2_latitude','user_requests.stop2_longitude','user_requests.km_price','user_requests.base_fare')
                    ->first();
                }else{
                    $UserRequests1 = UserRequest::where(function ($query) {
                        $query->whereIn('user_requests.status', ['STARTED', 'ARRIVED','PICKEDUP','DROPPED','COMPLETED']);
                    })                    
                    ->where('user_requests.id', '=', $Provider->trip_id)
                    ->where('user_requests.provider_id', '=', Auth::user()->id)
                    ->select('user_requests.id','user_requests.booking_id','user_requests.corporate_id','user_requests.s_address','user_requests.s_latitude','user_requests.s_longitude','user_requests.d_address','user_requests.d_latitude','user_requests.d_longitude','user_requests.status','user_requests.stop1_address','user_requests.stop1_latitude','user_requests.stop1_longitude','user_requests.stop2_address','user_requests.stop2_latitude','user_requests.stop2_longitude','user_requests.km_price','user_requests.base_fare')
                    ->first();
                }

                $payment=UserRequestPayment::where('request_id','=',$Provider->trip_id)->first(); 
                if($payment==NULL){$payment='';}
                if($UserRequests1!=NULL){
                    $UserRequests1->payment=$payment; 
                    if($payment){$UserRequests1->payment->corporate_id=$UserRequests1->corporate_id;}
                    $UserRequests1=$UserRequests1;}
                
                $user_name=$UserRequest->user_name;
                $cancelled_by=$UserRequest->cancelled_by;
                $user_notes=$UserRequest->user_notes;
                if($UserRequest->user_mobile){$user_mobile=$UserRequest->user_mobile;}
                if($UserRequest->user_id !=0){
                    $user=User::where('id',$UserRequest->user_id)->select('first_name','picture')->first();
                    if($user->first_name){$user_name=$user->first_name;}
                    if($user->rating){ $rating= $user->rating; }
                    if($user->picture){ $user_img=url('/').'/uploads/user/profile/'.$user->picture;}
                    if($user->avatar){$avatar =url('/').'/uploads/provider/profile/'.Auth::user()->avatar;}                         
                }
                $user=['user_name' => $user_name,'rating' => $rating,'user_img' => $user_img,'avatar' => $avatar,'user_notes' => $user_notes,'user_mobile'=>$user_mobile];
                if($UserRequest){
                $status =$UserRequest->status;
                $trip_id =$UserRequest->id;
                $paid = $UserRequest->paid; 
                if($UserRequest->payment_update){$payment_update=$UserRequest->payment_update;}
                }
            }
            
            $RequestFilter = RequestFilter::with('request')->where('provider_id','=', Auth::user()->id)->whereHas('request', function($query){
                    $query->where('status','=', 'SEARCHING');
                    $query->where('provider_id','=',0);
                    $query->where('current_provider_id','=',Auth::user()->id);
                })->first();                

            if($RequestFilter !=null){
                $trip = UserRequest::where('id','=',$RequestFilter->request_id)
                        ->where('status','=','SEARCHING')
                        ->where('provider_id','=',0)
                        ->select('id','user_name','user_id','provider_id','booking_id','status','booking_by','s_address','s_latitude','s_longitude','d_address','d_latitude','d_longitude','assigned_at','message','created_at')
                        ->first();

                if($trip !=null){
                $Timeout = Setting::get('provider_select_timeout', 90);
                $trip->assigned_at = Carbon::now();
                $trip->save();
                $trip1 = UserRequest::where('id','=',$RequestFilter->request_id)->where('status','=','SEARCHING')
                ->where('provider_id','=',0)->first();
                $time_left_to_respond = $Timeout - (time() - strtotime($trip1->assigned_at));

                if(Session::get($trip->id.','.Auth::user()->id))
                {
                    $time_left_to_respond = 0;
                    Session::forget($trip->id.','.Auth::user()->id);
                }
                    
                if($time_left_to_respond <=5) {
                    RequestFilter::where('provider_id', Auth::user()->id)
                                    ->where('request_id', $trip->id)
                                    ->delete();
                    $test=Provider::where('id','=',Auth::user()->id)->first();
                    $test->trip_id=0;
                    $test->status='active';
                    $test->active_from=Carbon::now();
                    $test->save();

                    $this->assign_next_provider($trip->id,0);
                }else{

                    $pro_inf=Provider::where('id','=',Auth::user()->id)->where('trip_id','!=',$trip->id)->first();
                    if($pro_inf)
                    {
                        if($pro_inf->status=='active')
                        {
                            $this->assign_next_provider($trip->id,1);
                        }else{
                            RequestFilter::where('request_id','=',$trip->id)->where('provider_id','=',Auth::user()->id)->delete();
                            $this->assign_next_provider($trip->id,0);
                        }
                        
                    }else{                        
                        Session::put($trip->id.','.Auth::user()->id, 1);
                        $notify=NotifiedDriver::where('provider_id','=',Auth::user()->id)->where('trip_id','=',$trip->id)->first();
                        if($notify)
                        {
                            $notify->notified=1;
                            $notify->save();
                        }
                        $trip->time_left_to_respond = $time_left_to_respond;
                        $alert_status = 1;
                        $ride = $trip;
                    }
                }

                }else{
                    RequestFilter::where('request_id', $trip->id)->delete();
                }
            }
            if($UserRequests1==null || $UserRequests1=="<null>")
            {
                $UserRequests1=[];
            }
            if($status=="SEARCHING")
            {
                $status="";
            }                
            // $wallet_status = 0;
            // if(Auth::user()->wallet_balance  < Setting::get('driver_min_wallet', '10') )
            // {
            //     $wallet_status = 1;
            // }
            return response()->json([
                'data' => $status, 
                'trip_id' => $trip_id,
                'account_status' => $Provider->account_status, 
                'status' => $Provider->status, 
                'vehicle_number' => $Provider->mapping_id, 
                'paid' => $paid,
                'alert_status'=>$alert_status, 
                'payment_update' => $payment_update,
                'ride'=>$ride,
                'provider_id'=>Auth::user()->id,
                'user_info' => $user,
                'cancelled_by' => $cancelled_by,
                'trip_info'=>$UserRequests1,
                'old_login' => $old_login
             ]);
        }else{
            return response()->json(['error' => trans('api.user.user_not_found')], 500);
        }
    }
    
    public function destroy_notification($id)
    {
        $trip = UserRequest::where('id','=',$id)->where('status','=','SEARCHING')->first();
        if($trip)
        {
            RequestFilter::where('provider_id','=', Auth::user()->id)
            ->where('request_id','=', $id)
            ->delete();

            $test=Provider::where('id','=',Auth::user()->id)->first();
            if($test)
            {
                $test->trip_id=0;
                $test->status='active';
                $test->active_from=Carbon::now();
                $test->save();
            }

            $this->assign_next_provider($id,0);
            return response()->json(['success'=>'1','message' => 'Rejected Successfully']);
        }else{
            return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }


    public function assign_next_provider($request_id,$check_id) {

        try {

            if(Auth::user()->admin_id !=  null){
                $admin = Admin::where('id','=',Auth::user()->admin_id)->first();
                if($admin->admin_type != 0 && $admin->time_zone != null){
                    date_default_timezone_set($admin->time_zone);
                }
            }

            $trip = UserRequest::where('id','=',$request_id)
                            ->where('status','SEARCHING')
                            ->where('provider_id','=',0)
                            ->first();
            if($trip !=null){
                if($check_id==1)
                {
                    RequestFilter::where('request_id','=',$trip->id)->where('provider_id','=',Auth::user()->id)->delete();
                    $test=Provider::where('id','=',Auth::user()->id)->first();
                    $test->trip_id=0;
                    $test->status='active';
                    $test->active_from=Carbon::now();
                    $test->save();
                    $Filter = new RequestFilter;
                    $Filter->request_id = $trip->id;
                    $Filter->provider_id = Auth::user()->id; 
                    $Filter->save();
                }

                $RequestFilter = RequestFilter::where('request_id','=', $request_id)
                    ->join('providers','request_filters.provider_id','=','providers.id')
                    ->where('providers.status','=','active')
                    ->where('providers.trip_id','=',0)
                    ->select('request_filters.*','providers.status as driver_status')
                    ->first();
                
                if($RequestFilter !=null){
                    $trip->assigned_at = Carbon::now();
                    $trip->current_provider_id = $RequestFilter->provider_id;
                    $trip->save();

                    $pro_inf=Provider::where('id','=',$RequestFilter->provider_id)->first();
                    if($pro_inf)
                    {
                        $pro_inf->trip_id=$request_id;
                        $pro_inf->save();
                    }
                    (new SendPushNotification)->IncomingTrip($RequestFilter->provider_id);
                }else{

                    RequestFilter::where('request_id', $trip->id)->delete();

                    $latitude = $trip->s_latitude;
                    $longitude=$trip->s_longitude;
                    $distance=Setting::get('provider_search_radius', '10');

                    $Providers = Provider::where('account_status','=', 'approved')
                    ->where('status','=','active')
                    ->where('trip_id', '=', 0)
                    ->where('service_type_id','=', $trip->service_type_id);
                
                    $Providers = $Providers->selectRaw("id , (1.609344 * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) AS distance, latitude, longitude")
                    ->having('distance', '<', $distance)
                    ->orderBy('distance')
                    ->get();
                    
                    if($Providers)
                    {
                        foreach ($Providers as $key => $Provider) 
                        {                            
                            $test = NotifiedDriver::where('trip_id','=', $trip->id)->where('provider_id','=',$Provider->id)->first();
                            if($test==null)
                            {
                                $Filter = new RequestFilter;
                                $Filter->request_id = $trip->id;
                                $Filter->provider_id = $Provider->id; 
                                $Filter->save();
            
                                $notify=new NotifiedDriver;
                                $notify->provider_id=$Provider->id;
                                $notify->notified=0;
                                $notify->trip_id=$trip->id;
                                $notify->save();
                            }
                        }
                    }                    

                    $test = RequestFilter::where('request_id','=', $trip->id)->first();
                    if($test)
                    {
                        $trip->assigned_at = Carbon::now();
                        $trip->current_provider_id = $test->provider_id;
                        $trip->save();
    
                        $pro_inf=Provider::where('id','=',$test->provider_id)->first();
                        if($pro_inf)
                        {
                            $pro_inf->trip_id=$trip->id;
                            $pro_inf->save();
                        }
                        (new SendPushNotification)->IncomingTrip($test->provider_id);
                    }else{

                        $trip->cancelled_by = 'PROVIDER';
                        $trip->cancel_reason='Rejected By Drivers';
                        $trip->status = 'CANCELLED';
                        $trip->save();
                        $pro_inf=Provider::where('trip_id','=',$trip->id)->update(['trip_id' => 0]);
                        RequestFilter::where('request_id', $trip->id)->delete();
                    }
                }
            }else{
                RequestFilter::where('request_id', $trip->id)->delete();
            }                
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }
}
?>
