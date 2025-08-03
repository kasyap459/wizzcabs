<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Log;
use Setting;
use Session;
use Auth;
use Exception;
use Carbon\Carbon;
use App\Helpers\Helper;
use App\Http\Controllers\SendPushNotification;
use App\Models\User;
use App\Models\Dispatcher;
use App\Models\Provider;
use App\Models\ServiceType;
use App\Models\UserRequest;
use App\Models\RequestFilter;
use App\Models\ProviderService;
use App\Models\CorporateUser;
use App\Models\Corporate;
use App\Models\Admin;
use App\Models\WebNotify;

class DispatchController extends Controller
{

    public function __construct()
    {
        //$this->middleware('admin');

        $this->middleware(function ($request, $next) {
        $this->id = Auth::user()->id;
        $this->email = Auth::user()->email;
        $this->admin_type = Auth::user()->admin_type;
        $this->admin_id = Auth::user()->admin_id;
        //dd($this->admin_type);
        if($this->admin_id == null){
                //dd($this->admin_id);
             $admin = Admin::where('id','=',$this->id)->first();
           
            //  if($admin->admin_type != 0 && $admin->time_zone != null){
            //      date_default_timezone_set($admin->time_zone);
                
            //  }
        //  } else {

        //     $admin = Admin::where('id','=',$this->admin_id)->first();
         
        //      if($admin->admin_type != 0 && $admin->time_zone != null){
        //          date_default_timezone_set($admin->time_zone);
                 
        //      }
          }
            
        return $next($request);
    });
        

    }
    /**
     * Dispatcher Panel.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        //dd(Carbon::now());
        if(Auth::guard('admin')->user()){
            $services = ServiceType::where('status','=',1)->get();
            $corporates = Corporate::select('id','display_name')->where('status',1)->get();
	    $notifies = WebNotify::where('status',0)->get();
            return view('admin.dispatch.dispatcher', compact('services','corporates','notifies'));
        }elseif(Auth::guard('dispatcher')->user()){
             $services = ServiceType::where('status','=',1)->get();
	     $notifies = WebNotify::where('status',0)->get();
             $corporates = Corporate::select('id','display_name')->where('status',1)->get();
            return view('dispatcher.dispatch.dispatcher', compact('services','corporates','notifies'));
        }
        elseif(Auth::guard('corporate')->user()){

            $services = ServiceType::where('status','=',1)->get();
	       $notifies = WebNotify::where('status',0)->get();
            $corporates = Corporate::select('id','display_name')->where('status',1)->get();
           return view('corporate.dispatch.dispatcher', compact('services','corporates','notifies'));
       }
        elseif(Auth::guard('partner')->user()){

            $services = ServiceType::where('status','=',1)->get();
           $notifies = WebNotify::where('status',0)->get();
            $corporates = Corporate::select('id','display_name')->where('status',1)->get();
           return view('partner.dispatch.dispatcher', compact('services','corporates','notifies'));
       }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        return view('dispatcher.account.profile');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function profile_update(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|max:255',
            'mobile' => 'required|digits_between:6,13',
        ]);

        try{
            $dispatcher = Auth::guard('dispatcher')->user();
            $dispatcher->name = $request->name;
            $dispatcher->mobile = $request->mobile;
            $dispatcher->save();

            return redirect()->back()->with('flash_success','Profile Updated');
        }

        catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function password()
    {
        return view('dispatcher.account.change-password');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function password_update(Request $request)
    {
        $this->validate($request,[
            'old_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        try {

           $Dispatcher = Dispatcher::find(Auth::guard('dispatcher')->user()->id);

            if(password_verify($request->old_password, $Dispatcher->password))
            {
                $Dispatcher->password = bcrypt($request->password);
                $Dispatcher->save();

                return redirect()->back()->with('flash_success','Password Updated');
            }
        } catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
    }
    /**
     * Display a listing of the users in the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function users_phone(Request $request)
    {
        $term=$request->term;
        $data = User::where('mobile','LIKE', '%'.$term.'%')
        ->take(10)->select('first_name','mobile','email')->get();
        $results=array();
        foreach ($data as $key => $value){
            $v = $value->mobile;
            $results[]=['value' => $v, 'email' => $value->email, 'name' => $value->first_name];
        }
        return response()->json($results);
    }
    /**
     * Display a listing of the users in the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function users_email(Request $request)
    {
        $term=$request->term;
        $data = User::where('email','LIKE', $term.'%')
        ->take(10)
        ->select('first_name','mobile','email')->get();
        $results=array();
        foreach ($data as $key => $value){
            $v = $value->mobile;
            $results[]=['value' => $value->email, 'phone' => $v, 'name' => $value->first_name];
        }
        return response()->json($results);
    }

    public function users_corporate(Request $request)
    {
        $term=$request->term;
        $data = User::where('corporate_status',1)->where('first_name','LIKE', $term.'%')
        ->take(10)
        ->select('first_name','mobile','email')->get();
        $results=array();
        foreach ($data as $key => $value){
            $v = $value->mobile;
            $results[]=['value' => $value->email, 'phone' => $v, 'name' => $value->first_name];
        }
        return response()->json($results);
    }

    /**
     * Display a listing of the users in the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function corporate_user(Request $request)
    {
        $term=$request->term;
        $data = CorporateUser::where('emp_code','LIKE', $term.'%')
        ->join('users', 'corporate_users.id', '=', 'users.corporate_user_id')
        ->take(10)
        ->select('users.name','users.mobile','users.email','corporate_users.emp_code')->get();
        $results=array();
        foreach ($data as $key => $value){
            $v = $value->mobile;
            $results[]=['value' => $value->emp_code, 'email' => $value->email,'phone' => $v, 'name' => $value->name];
        }
        return response()->json($results);
    }

    /**
     * Map of all Users and Drivers.
     *
     * @return \Illuminate\Http\Response
     */
    public function map_ajax(Request $request)
    {
        try {
            $filters = explode(',', $request->filters);

            $admin_id = null;
            if(Auth::guard('admin')->user()){
                if(Auth::guard('admin')->user()->admin_type !=0){
                    $admin_id = Auth::guard('admin')->user()->id;
                }
            }elseif(Auth::guard('dispatcher')->user()){
                $admin_id = Auth::guard('dispatcher')->user()->admin_id;
            }
            elseif(Auth::guard('corporate')->user()){
                $admin_id = Auth::guard('corporate')->user()->admin_id;
            }
            elseif(Auth::guard('partner')->user()){
                $admin_id = Auth::guard('partner')->user()->admin_id;
            }
            $Providers = Provider::withoutGlobalScopes()->join('service_types', 'providers.service_type_id', '=', 'service_types.id')
                    ->join('vehicles', 'providers.mapping_id', '=', 'vehicles.id')
                        ->where('providers.mapping_id','!=',0)
                        ->where('providers.account_status','=','approved')
                        ->whereIn('providers.status', $filters);

            if($admin_id !=null){
                $Providers->where('providers.admin_id', '=', $admin_id);
            }              

            $Providers = $Providers->select('providers.id','providers.name','providers.dial_code','providers.mobile','providers.latitude','providers.longitude','providers.status','vehicles.vehicle_no','vehicles.vehicle_model')
                        ->getQuery()
                        ->get();
            
            return $Providers;

        } catch (Exception $e) {
            return [];
        }
    }
    /**
     * Create manual request.
     *
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request) {

        $this->validate($request, [
                's_latitude' => 'required|numeric',
                's_longitude' => 'required|numeric',
                'd_latitude' => 'required|numeric',
                'd_longitude' => 'required|numeric',
                'service_type' => 'required|numeric|exists:service_types,id',
                'distance' => 'required|numeric',
                // 'seconds' => 'required',
                'first_name' =>'required',
                'mobile' =>'required',
            ]);

        $corporate_id = 0;
        $corporate_group_id =0;
        if($request->corporate_name !=''){
            $corporate_user = CorporateUser::where('emp_code','=',$request->corporate_name)->first();
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
        if($request->corporate_id){
            $corporate_id=$request->corporate_id;
        }
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
            $user_name = $currentuser->first_name;
            $user_mobile = $currentuser->dial_code.$currentuser->mobile;
            $guest =0;
        }else{
            $user_id = 0;
            $user_name = $request->first_name;
            $user_mobile = $request->mobile;
            $guest =1;
        }
        $admin_id = null;
        if(Auth::guard('admin')->user()){
            if(Auth::guard('admin')->user()->admin_type !=0){
                $admin_id = Auth::guard('admin')->user()->id;
            }
        }elseif(Auth::guard('dispatcher')->user()){
            $admin_id = Auth::guard('dispatcher')->user()->admin_id;
        }
        elseif(Auth::guard('corporate')->user()){
            $admin_id = Auth::guard('corporate')->user()->admin_id;
        }
        elseif(Auth::guard('partner')->user()){
            $admin_id = Auth::guard('partner')->user()->admin_id;
        }
        if($request->has('schedule_time')){
            try {
                $CheckScheduling = UserRequest::where('status', 'SCHEDULED')
                        ->where('user_id', $User->id)
                        ->where('schedule_at', '>', strtotime($request->schedule_time." - 1 hour"))
                        ->where('schedule_at', '<', strtotime($request->schedule_time." + 1 hour"))
                        ->firstOrFail();
                
                return response()->json(['message' => trans('api.ride.request_scheduled'), 'success' =>1]);
                
            } catch (Exception $e) {
                // Do Nothing
            }
        }

        if($request->filled('schedule_time') && $request->schedule_time !=''){
            // $details = "https://maps.googleapis.com/maps/api/directions/json?origin=".$request->s_latitude.",".$request->s_longitude."&destination=".$request->d_latitude.",".$request->d_longitude."&mode=driving&key=".Setting::get('map_key');

            // $json = curl($details);

            // $details = json_decode($json, TRUE);
            // $meter = $details['routes'][0]['legs'][0]['distance']['value'];
            // $seconds = $details['routes'][0]['legs'][0]['duration']['value'];
            // $route_key = $details['routes'][0]['overview_polyline']['points'];
            $unit =Setting::get('distance_unit');
            
            if($unit =='km'){
                $kilometer = $request->distance;
            }else{
                $kilometer = $request->distance * 0.62137119;
            }
            $kilometer = round($kilometer,2);
            $minutes =10;

            $fare_calc = Helper::fare_calc($request->service_type, $request->s_latitude, $request->s_longitude, $request->d_latitude, $request->d_longitude,$kilometer, $minutes);
            
            $UserRequest = new UserRequest;
            $UserRequest->booking_id = 100;
            $UserRequest->admin_id = $admin_id;
            $UserRequest->user_id = $user_id;
            $UserRequest->user_name = $user_name;
            $UserRequest->user_mobile = $user_mobile;
            $UserRequest->partner_id =0;
            $UserRequest->guest = $guest;
            $UserRequest->service_type_id = $request->service_type;
            $UserRequest->corporate_id = $corporate_id;
            $UserRequest->group_id = $corporate_group_id;
            if($request->corporate_id){
                $UserRequest->payment_mode = 'CORPORATE';
                $UserRequest->booking_by = 'CORPORATE';
            }else{
                $UserRequest->payment_mode = 'CASH';
            $UserRequest->booking_by = 'DISPATCHER';
            }
            $UserRequest->status = 'SCHEDULED';
            $UserRequest->push = 'AUTO';

            // $UserRequest->s_address = $request->s_address ? : "";
            // $UserRequest->s_latitude = $request->s_latitude;
            // $UserRequest->s_longitude = $request->s_longitude;

            // $UserRequest->d_address = $request->d_address ? : "";
            // $UserRequest->d_latitude = $request->d_latitude;
            // $UserRequest->d_longitude = $request->d_longitude;

            $UserRequest->s_address = $request->s_address ? : "";
            $UserRequest->s_latitude = $request->s_latitude? : "";
            $UserRequest->s_longitude = $request->s_longitude? : "";
            $UserRequest->d_address = $request->d_address ? : "";
            $UserRequest->d_latitude = $request->d_latitude? : "";
            $UserRequest->d_longitude = $request->d_longitude? : "";
            $UserRequest->stop1_latitude = $request->stop1_latitude ? : "";
            $UserRequest->stop1_longitude = $request->stop1_longitude? : "";
            $UserRequest->stop1_address = $request->stop1_address? : "";
            $UserRequest->stop2_latitude = $request->stop2_latitude ? : "";
            $UserRequest->stop2_longitude = $request->stop2_longitude? : "";
            $UserRequest->stop2_address = $request->stop2_address? : "";
            $UserRequest->message = $request->message ? : "";
            $UserRequest->user_notes = $request->message ? : "";
            $UserRequest->handicap = $request->handicap ? : 0;
            $UserRequest->pet = $request->pet ? : 0;
            $UserRequest->wagon = $request->wagon ? : 0;
            $UserRequest->booster = $request->booster ? : 0;
            $UserRequest->fixed_rate = $request->fixed_rate ? : 0;
            $UserRequest->child_seat = $request->childseat ? : 0;
            $UserRequest->route_key = ' ';

            $UserRequest->distance = $kilometer;
            $UserRequest->minutes = $minutes;
            $UserRequest->fare_type = $fare_calc['fare_type'];
            $UserRequest->estimated_fare = round($fare_calc['fare_flat'],2);
            $UserRequest->assigned_at = Carbon::now();

            $UserRequest->use_wallet = 0;
            $UserRequest->surge = 0;        // Surge is not necessary while adding a manual dispatch
            $UserRequest->schedule_at = Carbon::parse($request->schedule_time);

            $UserRequest->save();

            $UserRequest->booking_id = '100'.$UserRequest->id;

            $UserRequest->save();
            if($UserRequest->schedule_at < Carbon::now()->addHour(Setting::get('feature_time'))){
                app(\App\Http\Controllers\UserResources\UserTripApiController::class)->notify_driver($UserRequest->id);
            }
            $WebNotify = new WebNotify;
            $WebNotify->type = "Schedule Trip";
            $WebNotify->title = "Schedule Trip Created";
            $WebNotify->status = 0;
	        $WebNotify->save();
            return response()->json(['message' => 'Scheduled Trip created Successfully','id'=>$UserRequest->id, 'success' =>1]);
        }

        try{

            // $details = "https://maps.googleapis.com/maps/api/directions/json?origin=".$request->s_latitude.",".$request->s_longitude."&destination=".$request->d_latitude.",".$request->d_longitude."&mode=driving&key=".Setting::get('map_key');

            // $json = curl($details);

            // $details = json_decode($json, TRUE);
            // $meter = $details['routes'][0]['legs'][0]['distance']['value'];
            // $seconds = $details['routes'][0]['legs'][0]['duration']['value'];
            // $route_key = $details['routes'][0]['overview_polyline']['points'];
            $unit =Setting::get('distance_unit');
            
            if($unit =='km'){
                $kilometer = $request->distance;
            }else{
                $kilometer = $request->distance * 0.62137119;
            }
            $kilometer = round($kilometer,2);
            $minutes =10;

            $fare_calc = Helper::fare_calc($request->service_type, $request->s_latitude, $request->s_longitude, $request->d_latitude, $request->d_longitude,$kilometer, $minutes);

            $UserRequest = new UserRequest;
            $UserRequest->booking_id = 100;
            $UserRequest->admin_id = $admin_id;
            $UserRequest->user_id = $user_id;
            $UserRequest->user_name = $user_name;
            $UserRequest->user_mobile = $user_mobile;
            $UserRequest->guest = $guest;
            $UserRequest->service_type_id = $request->service_type;
            $UserRequest->corporate_id = $corporate_id;
            $UserRequest->group_id = $corporate_group_id;
            if($request->corporate_id){
                $UserRequest->payment_mode = 'CORPORATE';
                $UserRequest->booking_by = 'CORPORATE';
            }else{
                $UserRequest->payment_mode = 'CASH';
                 $UserRequest->booking_by = 'DISPATCHER';
            }
            $UserRequest->status = 'SEARCHING';
            $UserRequest->push = 'AUTO';
            // $UserRequest->s_address = $request->s_address ? : "";
            // $UserRequest->s_latitude = $request->s_latitude;
            // $UserRequest->s_longitude = $request->s_longitude;

            // $UserRequest->d_address = $request->d_address ? : "";
            // $UserRequest->d_latitude = $request->d_latitude;
            // $UserRequest->d_longitude = $request->d_longitude;

            $UserRequest->s_address = $request->s_address ? : "";
            $UserRequest->s_latitude = $request->s_latitude? : "";
            $UserRequest->s_longitude = $request->s_longitude? : "";
            $UserRequest->d_address = $request->d_address ? : "";
            $UserRequest->d_latitude = $request->d_latitude? : "";
            $UserRequest->d_longitude = $request->d_longitude? : "";
            $UserRequest->stop1_latitude = $request->stop1_latitude ? : "";
            $UserRequest->stop1_longitude = $request->stop1_longitude? : "";
            $UserRequest->stop1_address = $request->stop1_address? : "";
            $UserRequest->stop2_latitude = $request->stop2_latitude ? : "";
            $UserRequest->stop2_longitude = $request->stop2_longitude? : "";
            $UserRequest->stop2_address = $request->stop2_address? : "";

            $UserRequest->message = $request->message ? : "";
            $UserRequest->handicap = $request->handicap ? : 0;
            $UserRequest->pet = $request->pet ? : 0;
            $UserRequest->wagon = $request->wagon ? : 0;
            $UserRequest->booster = $request->booster ? : 0;
            $UserRequest->fixed_rate = $request->fixed_rate ? : 0;
            $UserRequest->child_seat = $request->childseat ? : 0;
            $UserRequest->route_key = 'null';

            $UserRequest->distance = $kilometer;
            $UserRequest->minutes = $minutes;
            $UserRequest->fare_type = $fare_calc['fare_type'];
            $UserRequest->estimated_fare = round($fare_calc['fare_flat'],2);
            $UserRequest->assigned_at = Carbon::now();
            $UserRequest->user_notes = $request->message ? : "";

            $UserRequest->use_wallet = 0;
            $UserRequest->surge = 0;        // Surge is not necessary while adding a manual dispatch
	        $UserRequest->current_provider_id = 0;
            
            $UserRequest->schedule_at = null;		

            $UserRequest->save();

            $UserRequest->booking_id = '100'.$UserRequest->id;
            
            $UserRequest->save();
	        $note = new WebNotify;
            $note->type = "Normal Trip";
            $note->title = "Normal Trip Created";
            $note->status = 0;
	        $note->save();
              
    	   if( $request->has('provider_auto_assign') ){
    		app(\App\Http\Controllers\UserResources\UserTripApiController::class)->notify_driver($UserRequest->id);
    	    }
        
            return response()->json(['message' => 'New Trip Created Successfully','id'=>$UserRequest->id, 'current'=>$UserRequest->current_provider_id,'success' =>1]);

        } catch (Exception $e) {
            if($request->ajax()) {
                return response()->json(['message' => trans('api.something_went_wrong'), 'message' => $e, 'success' =>0], 500);
            }else{
                return back()->with('flash_error', 'Something went wrong while sending request. Please try again.');
            }
        }
    }
    public function corporate_booking(Request $request) {

        $this->validate($request, [
                'c_latitude' => 'required|numeric',
                'c_longitude' => 'required|numeric',
                'cd_latitude' => 'required|numeric',
                'cd_longitude' => 'required|numeric',
                'c_service_type' => 'required|numeric|exists:service_types,id',
                'corporate_id' =>'required',
                'c_count' =>'required',
            ]);

        try {
            $corporates = Corporate::where('id','=',$request->corporate_id)->select('id','display_name','dial_code','mobile')->first();
            $corporate_group_id = 0;

            $details = "https://maps.googleapis.com/maps/api/directions/json?origin=".$request->c_latitude.",".$request->c_longitude."&destination=".$request->cd_latitude.",".$request->cd_longitude."&mode=driving&key=".Setting::get('map_key');

            $json = curl($details);

            $details = json_decode($json, TRUE);
            $meter = $details['routes'][0]['legs'][0]['distance']['value'];
            $seconds = $details['routes'][0]['legs'][0]['duration']['value'];
            $route_key = $details['routes'][0]['overview_polyline']['points'];
            $unit =Setting::get('distance_unit');
            
            if($unit =='km'){
                $kilometer = $meter/1000;
            }else{
                $base = $meter/1000;
                $kilometer = $base * 0.62137119;
            }
            $kilometer = round($kilometer,2);
            $minutes = $seconds/60;

            $fare_calc = Helper::fare_calc($request->c_service_type, $request->c_latitude, $request->c_longitude, $request->cd_latitude, $request->cd_longitude,$kilometer, $minutes);

            $c_count = $request->c_count;
            if($request->filled('c_name')){
                $user_name = $request->c_name;
            }else{
                 $user_name = $corporates->display_name;
            }
            if($request->filled('c_mobile')){
                $user_mobile = $request->c_mobile;
            }else{
                $user_mobile =$corporates->dial_code.$corporates->mobile;
            }

            if($request->filled('c_schedule_time')) {
                $schedule_at = Carbon::parse($request->c_schedule_time);
                $status = 'SCHEDULED';
            }else{
                $status = 'SEARCHING';
                $schedule_at = null;
            }

            $admin_id = null;
            if(Auth::guard('admin')->user()){
                if(Auth::guard('admin')->user()->admin_type !=0){
                    $admin_id = Auth::guard('admin')->user()->id;
                }
            }elseif(Auth::guard('dispatcher')->user()){
                $admin_id = Auth::guard('dispatcher')->user()->admin_id;
            }
            elseif(Auth::guard('corporate')->user()){
                $admin_id = Auth::guard('corporate')->user()->admin_id;
            }
            elseif(Auth::guard('partner')->user()){
                $admin_id = Auth::guard('partner')->user()->admin_id;
            }
            for($i=0; $i<$c_count; $i++){
                $UserRequest = new UserRequest;
                $UserRequest->booking_id =100;
                $UserRequest->admin_id = $admin_id;
                $UserRequest->user_id = 0;
                $UserRequest->user_name = $user_name;
                $UserRequest->user_mobile = $user_mobile;
                $UserRequest->guest =0;
                $UserRequest->service_type_id = $request->c_service_type;
                $UserRequest->corporate_id = $request->corporate_id;
                $UserRequest->group_id = $corporate_group_id;
                $UserRequest->payment_mode = 'CASH';
                
                $UserRequest->booking_by = 'DISPATCHER';
                $UserRequest->trip_status = 'corporate';
                $UserRequest->s_address = $request->c_address ? : "";
                $UserRequest->s_latitude = $request->c_latitude;
                $UserRequest->s_longitude = $request->c_longitude;

                $UserRequest->d_address = $request->cd_address ? : "";
                $UserRequest->d_latitude = $request->cd_latitude;
                $UserRequest->d_longitude = $request->cd_longitude;
                $UserRequest->stop1_latitude = $request->stop1_latitude ? : "";
                $UserRequest->stop1_longitude = $request->stop1_longitude;
                $UserRequest->stop1_address = $request->stop1_address;
                $UserRequest->stop2_latitude = $request->stop2_latitude ? : "";
                $UserRequest->stop2_longitude = $request->stop2_longitude;
                $UserRequest->stop2_address = $request->stop2_address;
                $UserRequest->message = $request->c_message ? : "";
                $UserRequest->handicap = $request->c_handicap ? : 0;
                $UserRequest->pet = $request->c_pet ? : 0;
                $UserRequest->wagon = $request->c_wagon ? : 0;
                $UserRequest->booster = $request->c_booster ? : 0;
                $UserRequest->fixed_rate = $request->c_fixed_rate ? : 0;
                $UserRequest->child_seat = $request->c_childseat ? : 0;

                $UserRequest->route_key = '';

                $UserRequest->distance = $kilometer;
                $UserRequest->minutes = $minutes;
                $UserRequest->fare_type = $fare_calc['fare_type'];
                $UserRequest->estimated_fare = round($fare_calc['fare_flat'],2);
                $UserRequest->assigned_at = Carbon::now();

                $UserRequest->use_wallet = 0;
                $UserRequest->surge = 0;        // Surge is not necessary while adding a manual dispatch
                $UserRequest->status = $status;
                $UserRequest->schedule_at = $schedule_at;
        
                $UserRequest->save();
                $UserRequest->booking_id = '100'.$UserRequest->id;
                $UserRequest->save();
            }

            app(\App\Http\Controllers\UserResources\UserTripApiController::class)->notify_driver($UserRequest->id);
            
            return response()->json(['message' => 'Trips Created Successfully','success' =>1]);

        } catch (Exception $e) {
            if($request->ajax()) {
                return response()->json(['message' => trans('api.something_went_wrong'), 'message' => $e, 'success' =>0], 500);
            }else{
                return back()->with('flash_error', 'Something went wrong while sending request. Please try again.');
            }
        }
    }

    public function viewtrip($request_id)
    {
           /*$trip = UserRequests::with('user', 'service_type')->findOrFail($request_id);*/
           $trip = UserRequest::withoutGlobalScopes()->with('service_type')->leftJoin('users', 'user_requests.user_id', '=', 'users.id')->select('user_requests.user_name','user_requests.user_mobile','user_requests.id','user_requests.s_address','user_requests.d_address','user_requests.s_latitude','user_requests.s_longitude','user_requests.service_type_id','user_requests.status','user_requests.provider_id','user_requests.distance','user_requests.corporate_id','user_requests.payment_mode','user_requests.cancelled_by','user_requests.schedule_at','user_requests.created_at')->findOrFail($request_id);

           $currency =setting::get('currency');
           $diskm =setting::get('distance_unit');
        if($trip->provider_id ==0 || $trip->status =='CANCELLED'){

            $admin_id = null;
            if(Auth::guard('admin')->user()){
                if(Auth::guard('admin')->user()->admin_type !=0){
                    $admin_id = Auth::guard('admin')->user()->id;
                }
            }elseif(Auth::guard('dispatcher')->user()){
                $admin_id = Auth::guard('dispatcher')->user()->admin_id;
            }
            elseif(Auth::guard('corporate')->user()){
                $admin_id = Auth::guard('corporate')->user()->admin_id;
            }
            elseif(Auth::guard('partner')->user()){
                $admin_id = Auth::guard('partner')->user()->admin_id;
            }
            $Providers = Provider::withoutGlobalScopes()->join('vehicles', 'providers.mapping_id', '=', 'vehicles.id')
                    ->join('service_types', 'vehicles.service_type_id', '=', 'service_types.id')
                    ->where('providers.status','=','active')
                    ->where('providers.service_type_id','=',$trip->service_type_id)
                    ->where('providers.account_status', 'approved');
            if($admin_id !=null){
                $Providers->where('providers.admin_id', '=', $admin_id);
            }
            $Providers = $Providers->select('providers.id','providers.name','providers.mobile','vehicles.vehicle_no','service_types.name as service_name')
                    ->get();

    
            if(Auth::guard('admin')->user()){
                return view('admin.dispatch.view', compact('trip','currency','diskm','Providers'));
            }elseif(Auth::guard('dispatcher')->user()){
                return view('dispatcher.dispatch.view', compact('trip','currency','diskm','Providers'));
            }
            elseif(Auth::guard('corporate')->user()){
                return view('corporate.dispatch.view', compact('trip','currency','diskm','Providers'));
            }
            elseif(Auth::guard('partner')->user()){
                return view('partner.dispatch.view', compact('trip','currency','diskm','Providers'));
            }
        }else{
            if(Auth::guard('admin')->user()){
                return view('admin.dispatch.view', compact('trip','currency','diskm'));
            }elseif(Auth::guard('dispatcher')->user()){
                return view('dispatcher.dispatch.view', compact('trip','currency','diskm'));
            }
            elseif(Auth::guard('corporate')->user()){
                return view('corporate.dispatch.view', compact('trip','currency','diskm'));
            }
            elseif(Auth::guard('partner')->user()){
                return view('partner.dispatch.view', compact('trip','currency','diskm'));
            }
        }
        
    }
    /**
     * Create manual request.
     *
     * @return \Illuminate\Http\Response
     */
    public function assign($request_id, $provider_id)
    {
        try {
            $Request = UserRequest::findOrFail($request_id);
            $Provider = Provider::findOrFail($provider_id);
            $user = User::where('id',$Request->user_id)->first();
            if($user != NULL)
            {
                $user->trip_id = $Request->id;
                $user->save(); 
            }
            if($Request->status =='CANCELLED'){
                $Request->cancelled_by ="NONE";
                $Request->cancel_reason ="";
                $Request->booking_by ="DISPATCHER";
                $Request->paid =0;
            }
            $Request->assigned_at = Carbon::now();
            $Request->status ="SEARCHING";
            $Request->vehicle_id = $Provider->mapping_id;
            $Request->provider_id = 0;
            $Request->partner_id = $Provider->partner_id ? : 0;
            $Request->current_provider_id = $Provider->id;
            $Request->assigned_at = Carbon::now();
            $Request->save();
            (new SendPushNotification)->AssignedTrip($provider_id);
            return response()->json(['message' => 'Request Assigned to Provider!']);

        } catch (Exception $e) {
            return response()->json(['message' => $e]);
        }
    }
    /**
     * Map of all Users and Drivers.
     *
     * @return \Illuminate\Http\Response
     */
    public function driver_list(Request $request)
    {
        // return $request->choose_service;
        try {
            if($request->status ==''){
                $filter = ['active', 'riding'];
            }else{
                $filter =[];
                $filter[] = $request->status;
            }
            // if(!$request->choose_service){
            //     $choose_service=$request->choose_service;
            // }
            $admin_id = null;
            if(Auth::guard('admin')->user()){
                if(Auth::guard('admin')->user()->admin_type !=0){
                    $admin_id = Auth::guard('admin')->user()->id;
                }
            }elseif(Auth::guard('dispatcher')->user()){
                $admin_id = Auth::guard('dispatcher')->user()->admin_id;
            }
            elseif(Auth::guard('corporate')->user()){
                $admin_id = Auth::guard('corporate')->user()->admin_id;
            }
            elseif(Auth::guard('partner')->user()){
               
                $admin_id = Auth::guard('partner')->user()->admin_id;
            }
            $drivers = Provider::withoutGlobalScopes()->join('service_types', 'providers.service_type_id', '=', 'service_types.id')
                    ->join('vehicles', 'providers.mapping_id', '=', 'vehicles.id')
                    ->leftJoin('provider_devices', 'providers.id', '=', 'provider_devices.provider_id')
                    ->where('providers.mapping_id','!=',0)
                    ->where('providers.account_status','=','approved')
                    ->whereIn('providers.status', array('active','riding'))
                    ->whereIn('providers.status', $filter);
            if($request->choose_service){
            $drivers = Provider::withoutGlobalScopes()->join('service_types', 'providers.service_type_id', '=', 'service_types.id')
                    ->join('vehicles', 'providers.mapping_id', '=', 'vehicles.id')
                    ->leftJoin('provider_devices', 'providers.id', '=', 'provider_devices.provider_id')
                    ->where('providers.mapping_id','!=',0)
                    ->where('providers.account_status','=','approved')
                    ->where('service_types.id','=',$request->choose_service)
                    ->whereIn('providers.status', $filter);
            }

    
            if($request->choose_corporate){
            $drivers = Provider::withoutGlobalScopes()->join('service_types', 'providers.service_type_id', '=', 'service_types.id')
                    ->join('vehicles', 'providers.mapping_id', '=', 'vehicles.id')
                    ->leftJoin('provider_devices', 'providers.id', '=', 'provider_devices.provider_id')
                    ->where('providers.mapping_id','!=',0)
                    ->where('providers.account_status','=','approved')
                    ->where('providers.partner_id','=',$request->choose_corporate)
                    ->whereIn('providers.status', $filter);
            }
            if($request->choose_service && $request->choose_corporate){
            $drivers = Provider::withoutGlobalScopes()->join('service_types', 'providers.service_type_id', '=', 'service_types.id')
                    ->join('vehicles', 'providers.mapping_id', '=', 'vehicles.id')
                    ->leftJoin('provider_devices', 'providers.id', '=', 'provider_devices.provider_id')
                    ->where('providers.mapping_id','!=',0)
                    ->where('providers.account_status','=','approved')
                    ->where('providers.partner_id','=',$request->choose_corporate)
                    ->where('service_types.id','=',$request->choose_service)
                    ->whereIn('providers.status', $filter);
            }
            if($admin_id !=null){
                $drivers->where('providers.admin_id', '=', $admin_id);
            }        
            // if($request->driver_category !=''){
            //     $driver_category = 'vehicles.'.$request->driver_category;
            //     $drivers->where($driver_category, '=', 1);
            // }
            // if($request->seat_count !=''){
            //     $drivers->where('vehicles.seat', '=', $request->seat_count);
            // }

            if(Auth::guard('partner')->user())
            {
                 $drivers = Provider::withoutGlobalScopes()->join('service_types', 'providers.service_type_id', '=', 'service_types.id')
                    ->join('vehicles', 'providers.mapping_id', '=', 'vehicles.id')
                    ->leftJoin('provider_devices', 'providers.id', '=', 'provider_devices.provider_id')
                    ->where('providers.mapping_id','!=',0)
                    ->where('providers.account_status','=','approved')
                    ->where('providers.partner_id','=',Auth::guard('partner')->user()->id);
                     // ->whereIn('providers.status', $filter);
            }
            $drivers = $drivers->select('providers.id AS id','providers.partner_id','providers.name AS name','providers.status AS status','service_types.name AS servicename','provider_devices.type AS providertype','vehicles.vehicle_no AS servicenumber')
                    ->getQuery()
                    ->get();
                 //  dd($drivers); die;
            if(Auth::guard('admin')->user()){
                return view('admin.dispatch.driver', compact('drivers'));
            }elseif(Auth::guard('dispatcher')->user()){
                return view('dispatcher.dispatch.driver', compact('drivers'));
            }
            elseif(Auth::guard('corporate')->user()){
                return view('corporate.dispatch.driver', compact('drivers'));
            }
            elseif(Auth::guard('partner')->user()){
               // dd($drivers); die;
                return view('partner.dispatch.driver', compact('drivers'));
            }
        } catch (Exception $e) {
            return [];
        }
    }
    /**
     * Map of all Users and Drivers.
     *
     * @return \Illuminate\Http\Response
     */
    public function driver_movement()
    {
        try {
            $drivers = Provider::where('status','=','riding')
            ->where('ride_from','<=',Carbon::now()->subMinutes(3))
            ->get()->pluck('name');
            return $drivers;
        } catch (Exception $e) {
            return [];
        }
    }
    /**
     * Map of all Users and Drivers.
     *
     * @return \Illuminate\Http\Response
     */
    public function ride_list()
    {
        try {
            $trips = UserRequest::orderBy('assigned_at','desc')->paginate(100);
            if(Auth::guard('admin')->user()){
                return view('admin.dispatch.ride', compact('trips'));
            }elseif(Auth::guard('dispatcher')->user()){
                return view('dispatcher.dispatch.ride', compact('trips'));
            }
            elseif(Auth::guard('corporate')->user()){
                $trips = UserRequest::where('corporate_id',Auth::guard('corporate')->user()->id)->orderBy('assigned_at','desc')->paginate(100);
                return view('corporate.dispatch.ride', compact('trips'));
            }
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * web fare calculate.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function fare_calculate(Request $request)
    {
       
        try{
        //     $details = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$request->s_latitude.",".$request->s_longitude."&destinations=".$request->d_latitude.",".$request->d_longitude."&mode=driving&sensor=false&key=".Setting::get('map_key');
        //     // $details = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$request->s_latitude.",".$request->s_longitude."&destinations=".$request->d_latitude.",".$request->d_longitude."&key=".Setting::get('map_key');
        $details = "https://maps.googleapis.com/maps/api/directions/json?origin=".$request->s_latitude.",".$request->s_longitude."&destination=".$request->d_latitude.",".$request->d_longitude."&mode=driving&key=".Setting::get('map_key');
        //     $json = curl($details);

        //     $details = json_decode($json, TRUE);

        //     $meter = $details['rows'][0]['elements'][0]['distance']['value'];
        //     $time = $details['rows'][0]['elements'][0]['duration']['text'];
        //     $seconds = $details['rows'][0]['elements'][0]['duration']['value'];

            // $details = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$request->s_latitude.",".$request->s_longitude."&destinations=".$request->d_latitude.",".$request->d_longitude."&mode=driving&sensor=false&key=".Setting::get('map_key');

            $json = curl($details);

            $details = json_decode($json, TRUE);
            $meter = $details['routes'][0]['legs'][0]['distance']['value'];
            $seconds = $details['routes'][0]['legs'][0]['duration']['value'];
            $route_key = $details['routes'][0]['overview_polyline']['points'];
            $unit =Setting::get('distance_unit');
            
            if($unit =='km'){
                $kilometer = $meter/1000;
            }else{
                $base = $meter/1000;
                $kilometer = $base * 0.62137119;
            }
            $kilometer = round($kilometer,2);
            $minutes = $seconds/60;

            $currency =setting::get('currency');

            // $unit =Setting::get('distance_unit');
            // $currency =setting::get('currency');
            // if($unit =='km'){
            //     $kilometer = $request->distance;
            // }else{
            //     $kilometer =  $request->distance * 0.62137119;
            // }
            // $kilometer = round($kilometer,2);
            // $minutes = round( $request->seconds,2);
            $estimated_time=Carbon::now()->addMinutes($minutes);
            $fare_calc = Helper::fare_calc($request->service_type, $request->s_latitude, $request->s_longitude, $request->d_latitude, $request->d_longitude,$kilometer, $minutes);

            if($fare_calc['fare_type'] ==3){
                $fare_type = 'Distance fare';
            }else if($fare_calc['fare_type'] ==2 || $fare_calc['fare_type']==1){
                $fare_type = 'Fixed fare';
            }else{
                $fare_type = 'Not found';
            }
            $price  = round($fare_calc['fare_flat'],2);
            $service_type = ServiceType::where('id','=',$request->service_type)->first();
            $tax_price = $price * $service_type->vat_percent/100;
            $total = round(($price + $tax_price),2);
            
            $result = array();
            $result[0]=$kilometer;
            $result[1]=$minutes;
            $result[2]=$total;
            $result[3]=$unit;
            $result[4]=$currency;
            $result[5]=$fare_type;
            $result[6]=date("h:i:a", strtotime($estimated_time));
            $result[7]=$service_type->name;
            return $result;

        }catch (Exception $e) {
            return $e;
        }
    }
}
