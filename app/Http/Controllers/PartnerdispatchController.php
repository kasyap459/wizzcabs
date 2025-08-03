<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Log;
use Setting;
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
use App\Models\WebNotify;

class PartnerdispatchController extends Controller
{

    /**
     * Dispatcher Panel.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = ServiceType::where('status',1)->get();
       // dd($services); die;
	$notifies = WebNotify::where('status',0)->get();
        $corporates = Corporate::select('id','display_name')->where('status',1)->get();
        return view('partner.dispatch.dispatcher', compact('services','corporates','notifies'));  
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
            $Providers = Provider::join('service_types', 'providers.service_type_id', '=', 'service_types.id')
                    ->join('vehicles', 'providers.mapping_id', '=', 'vehicles.id')
                        ->where('providers.partner_id','=', Auth::user()->id)
                        ->where('providers.mapping_id','!=',0)
                        ->where('providers.account_status','=','approved')
                        ->whereIn('providers.status', $filters)
                        ->select('providers.id','providers.name','providers.dial_code','providers.mobile','providers.latitude','providers.longitude','providers.status','vehicles.vehicle_no','vehicles.vehicle_model')
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
            $details = "https://maps.googleapis.com/maps/api/directions/json?origin=".$request->s_latitude.",".$request->s_longitude."&destination=".$request->d_latitude.",".$request->d_longitude."&mode=driving&key=".Setting::get('map_key');

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

            $fare_calc = Helper::fare_calc($request->service_type, $request->s_latitude, $request->s_longitude, $request->d_latitude, $request->d_longitude,$kilometer, $minutes);
            
            $UserRequest = new UserRequest;
            $UserRequest->booking_id = 100;
            if(Auth::user()->admin_id != 0){
            $UserRequest->admin_id = Auth::user()->admin_id;
            }
            $UserRequest->user_id = $user_id;
            $UserRequest->user_name = $user_name;
            $UserRequest->user_mobile = $user_mobile;
            $UserRequest->guest = $guest;
            $UserRequest->service_type_id = $request->service_type;
            $UserRequest->corporate_id = $corporate_id;
            $UserRequest->group_id = $corporate_group_id;
            $UserRequest->partner_id = Auth::user()->id;
            $UserRequest->payment_mode = 'CASH';
            $UserRequest->status = 'SCHEDULED';
            $UserRequest->push = 'AUTO';
            $UserRequest->booking_by = 'DISPATCHER';

            $UserRequest->s_address = $request->s_address ? : "";
            $UserRequest->s_latitude = $request->s_latitude;
            $UserRequest->s_longitude = $request->s_longitude;

            $UserRequest->d_address = $request->d_address ? : "";
            $UserRequest->d_latitude = $request->d_latitude;
            $UserRequest->d_longitude = $request->d_longitude;
            $UserRequest->message = $request->message ? : "";
            $UserRequest->handicap = $request->handicap ? : 0;
            $UserRequest->user_notes = $request->message ? : "";
            $UserRequest->pet = $request->pet ? : 0;
            $UserRequest->wagon = $request->wagon ? : 0;
            $UserRequest->booster = $request->booster ? : 0;
            $UserRequest->fixed_rate = $request->fixed_rate ? : 0;
            $UserRequest->route_key = $route_key;

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

        //try{

            $details = "https://maps.googleapis.com/maps/api/directions/json?origin=".$request->s_latitude.",".$request->s_longitude."&destination=".$request->d_latitude.",".$request->d_longitude."&mode=driving&key=".Setting::get('map_key');

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

            $fare_calc = Helper::fare_calc($request->service_type, $request->s_latitude, $request->s_longitude, $request->d_latitude, $request->d_longitude,$kilometer, $minutes);

            $UserRequest = new UserRequest;
            $UserRequest->booking_id = 100;
            if(Auth::user()->admin_id != 0){
            $UserRequest->admin_id = Auth::user()->admin_id;
            }
            $UserRequest->user_id = $user_id;
            $UserRequest->user_name = $user_name;
            $UserRequest->user_mobile = $user_mobile;
            $UserRequest->guest = $guest;
            $UserRequest->service_type_id = $request->service_type;
            $UserRequest->corporate_id = $corporate_id;
            $UserRequest->group_id = $corporate_group_id;
            $UserRequest->partner_id = Auth::user()->id;
            $UserRequest->payment_mode = 'CASH';
            $UserRequest->status = 'SEARCHING';
            $UserRequest->push = 'AUTO';
            $UserRequest->booking_by = 'DISPATCHER';
            $UserRequest->s_address = $request->s_address ? : "";
            $UserRequest->s_latitude = $request->s_latitude;
            $UserRequest->s_longitude = $request->s_longitude;

            $UserRequest->d_address = $request->d_address ? : "";
            $UserRequest->d_latitude = $request->d_latitude;
            $UserRequest->d_longitude = $request->d_longitude;
            $UserRequest->message = $request->message ? : "";
            $UserRequest->handicap = $request->handicap ? : 0;
            $UserRequest->pet = $request->pet ? : 0;
            $UserRequest->wagon = $request->wagon ? : 0;
            $UserRequest->booster = $request->booster ? : 0;
            $UserRequest->user_notes = $request->message ? : "";
            $UserRequest->fixed_rate = $request->fixed_rate ? : 0;
            $UserRequest->route_key = $route_key;

            $UserRequest->distance = $kilometer;
            $UserRequest->minutes = $minutes;
            $UserRequest->fare_type = $fare_calc['fare_type'];
            $UserRequest->estimated_fare = round($fare_calc['fare_flat'],2);
            $UserRequest->assigned_at = Carbon::now();

            $UserRequest->use_wallet = 0;
            $UserRequest->surge = 0;        // Surge is not necessary while adding a manual dispatch

            
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
            

            
            return response()->json(['message' => 'New Trip Created Successfully','id'=>$UserRequest->id,'success' =>1]);

        // } catch (Exception $e) {
        //     if($request->ajax()) {
        //         return response()->json(['message' => trans('api.something_went_wrong'), 'message' => $e, 'success' =>0], 500);
        //     }else{
        //         return back()->with('flash_error', 'Something went wrong while sending request. Please try again.');
        //     }
        // }
    }
    public function viewtrip($request_id)
    {
        $trip = UserRequest::with('service_type')->leftJoin('users', 'user_requests.user_id', '=', 'users.id')->select('user_requests.user_name','user_requests.user_mobile','user_requests.id','user_requests.s_address','user_requests.d_address','user_requests.s_latitude','user_requests.s_longitude','user_requests.service_type_id','user_requests.status','user_requests.provider_id','user_requests.distance','user_requests.corporate_id','user_requests.payment_mode','user_requests.cancelled_by','user_requests.schedule_at','user_requests.created_at')->findOrFail($request_id);

        $currency =setting::get('currency');
        $diskm =setting::get('distance_unit');
        if($trip->provider_id ==0 || $trip->status =='CANCELLED'){

            $Providers = Provider::join('vehicles', 'providers.mapping_id', '=', 'vehicles.id')
                    ->join('service_types', 'vehicles.service_type_id', '=', 'service_types.id')
                    ->where('providers.partner_id','=', Auth::user()->id)
                    ->where('providers.status','=','active')
                    ->where('providers.service_type_id','=',$trip->service_type_id)
                    ->where('providers.account_status', 'approved')
                    ->select('providers.id','providers.name','providers.mobile','vehicles.vehicle_no','service_types.name as service_name')
                    ->get();

    
            return view('partner.dispatch.view', compact('trip','currency','diskm','Providers'));
            
        }else{
            return view('partner.dispatch.view', compact('trip','currency','diskm')); 
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
            $Provider->trip_id=$request_id;
            $Provider->save();
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
            $Request->cancel_reason =null;
            $Request->cancelled_by = 'NONE';
            $Request->assigned_at = Carbon::now();
            $Request->save();

            $Filter = new RequestFilter;
            $Filter->request_id = $request_id;
            $Filter->provider_id =$provider_id; 
            $Filter->save();
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
        
        try {
            if($request->status ==''){
                $filter = ['active', 'riding', 'offline'];
            }else{
                $filter =[];
                $filter[] = $request->status;
            }

            $drivers = Provider::withoutGlobalScopes()->join('service_types', 'providers.service_type_id', '=', 'service_types.id')
                    ->join('vehicles', 'providers.mapping_id', '=', 'vehicles.id')
                    ->leftJoin('provider_devices', 'providers.id', '=', 'provider_devices.provider_id')
                    ->where('providers.partner_id','=', Auth::user()->id)
                    ->where('providers.mapping_id','!=',0)
                    ->where('providers.account_status','=','approved')
                    ->whereIn('providers.status', array('active','riding'))
                    ->whereIn('providers.status', $filter);
            $drivers = $drivers->select('providers.id AS id','providers.name AS name','providers.status AS status','service_types.name AS servicename','provider_devices.type AS providertype','vehicles.vehicle_no AS servicenumber')
                    ->getQuery()
                    ->get();
               
            return view('partner.dispatch.driver', compact('drivers'));

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
            $providers = Provider::where('partner_id','=', Auth::user()->id)->get()->pluck('id');
            $trips = UserRequest::where('partner_id',Auth::user()->id)->orderBy('assigned_at','desc')->paginate(100);
            
            return view('partner.dispatch.ride', compact('trips'));

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
            $details = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$request->s_latitude.",".$request->s_longitude."&destinations=".$request->d_latitude.",".$request->d_longitude."&mode=driving&sensor=false&key=".Setting::get('map_key');

            $json = curl($details);

            $details = json_decode($json, TRUE);

            $meter = $details['rows'][0]['elements'][0]['distance']['value'];
            $time = $details['rows'][0]['elements'][0]['duration']['text'];
            $seconds = $details['rows'][0]['elements'][0]['duration']['value'];

            $unit =Setting::get('distance_unit');
            $currency =setting::get('currency');
            if($unit =='km'){
                $kilometer = $meter/1000;
            }else{
                $base = $meter/1000;
                $kilometer = $base * 0.62137119;
            }
            $kilometer = round($kilometer,2);
            $minutes = round($seconds/60,2);
            
            $fare_calc = Helper::fare_calc($request->service_type, $request->s_latitude, $request->s_longitude, $request->d_latitude, $request->d_longitude,$kilometer, $minutes);
        
            if($fare_calc['fare_type'] ==3){
                $fare_type = "Distance fare";
            }else if($fare_calc['fare_type'] ==2 || $fare_calc['fare_type']==1){
                $fare_type = "Fixed fare";
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
            return $result;

        }catch (Exception $e) {
            return $e;
        }
    }
}
