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
use App\Http\Controllers\UserResources\UserApiController;
class HomeController extends Controller
{
    protected $UserAPI;

    /**
     * Create a new controller instance.
     *
     * @return void  
     */
    public function __construct(UserApiController $UserAPI)
    {
        $this->middleware('auth');
        $this->UserAPI = $UserAPI;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = ServiceType::all();
        $countrylatlng['lat'] = Setting::get('address_lat', 0);
        $countrylatlng['lng'] = Setting::get('address_long', 0);
        return view('user.dashboard',compact('services','countrylatlng'));
    }

    /**
     * Show the application profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        return view('user.account.profile');
    }

    /**
     * Show the application profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit_profile()
    {
        $countries = Country::all();
        return view('user.account.edit_profile', compact('countries'));
    }

    /**
     * Update profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function update_profile(Request $request)
    {
        $this->validate($request, [
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'email' => 'email|unique:users,email,'.Auth::user()->id,
                // 'country_id' => 'required',
                'mobile' => 'required',
                'picture' => 'mimes:jpeg,bmp,png',
            ]);

         try {

            $user = User::findOrFail(Auth::user()->id);
            
            if($request->has('first_name')){ 
                $user->first_name = $request->first_name;
            }

            if($request->has('last_name')){ 
                $user->last_name = $request->last_name;
            }
    
            if($request->has('email')){
                $user->email = $request->email;
            }
        
            if($request->has('mobile')){
                $user->mobile = $request->mobile;
            }

            if($request->has('gender')){
                $user->gender = $request->gender;
            }
                //dd((int)$request->country_id);
            if($request->has('country_id')){
                $country = Country::where('countryid','=',$request->country_id)->first();
                $user->dial_code = $country->dial_code;
                $user->country_id = (int)$request->country_id; 
                //dd($user);
                
            }

            if ($request->picture != "") {
               // dd($request->picture);
                Storage::delete($user->picture);
                $user->picture = $request->picture->store('public/user/profile');
                $user->picture = $request->picture->store('user/profile');
            }

            $user->save();

            return redirect()->back()->with('flash_success', 'Information successfully updated');
        }
        catch (ModelNotFoundException $e) {
             return redirect()->back()->with('flash_error', 'Something went wrong.');
        }
    }

    /**
     * Show the application change password.
     *
     * @return \Illuminate\Http\Response
     */
    public function change_password()
    {
        return view('user.account.change_password');
    }

    /**
     * Change Password.
     *
     * @return \Illuminate\Http\Response
     */
    public function update_password(Request $request)
    {
        $this->validate($request, [
                'password' => 'required|confirmed|min:6',
                'old_password' => 'required',
            ]);

        $User = Auth::user();
        if(Hash::check($request->old_password, $User->password))
        {
            $User->password = bcrypt($request->password);
            $User->save();
            return redirect()->back()->with('flash_success', trans('api.user.password_updated'));
        } else {
            return redirect()->back()->with('flash_error', 'Something went wrong.');
        }
    }

    /**
     * Trips.
     *
     * @return \Illuminate\Http\Response
     */
    public function trips()
    {
        $trips = UserRequest::where('user_id', '=', Auth::user()->id)
                    ->whereIn('user_requests.status', ['COMPLETED', 'CANCELLED'])
                    ->join('service_types', 'user_requests.service_type_id', '=', 'service_types.id')
                    ->select('user_requests.id','user_requests.provider_id','user_requests.user_name','user_requests.booking_id','service_types.name as service_name','user_requests.status','user_requests.payment_mode','user_requests.s_address','user_requests.corporate_id','user_requests.d_address','user_requests.distance','user_requests.minutes','user_requests.started_at','user_requests.finished_at','user_requests.created_at')
                    ->orderBy('created_at','desc')
                    ->get();
        if(!empty($trips)){
            foreach($trips as $key=>$trip){
                $trips[$key]->distance = $trip->distance.Setting::get('distance_unit');
                $provider = Provider::where('id','=',$trip->provider_id)->first();
                if($provider !=null){
                    $trips[$key]->provider_name = $provider->name;
                    $trips[$key]->provider_avatar = $provider->avatar;
                }else{
                    $trips[$key]->provider_name = '-';
                    $trips[$key]->provider_avatar = '-';
                }
                if($trip->status =='COMPLETED'){
                    $trips[$key]->provider_avatar = asset('storage/'.$trip->provider_avatar);
                    $trips[$key]->new=UserRequestPayment::where('request_id',$trip->id)->pluck('total')->first();
                    $trips[$key]->total = Setting::get('currency');
                    $trips[$key]->rating = UserRequestRating::where('request_id',$trip->id)->select('user_comment','user_rating')->first();          
                }
            }
        }                         
        return view('user.ride.trips',compact('trips'));
    }

    /**
     * Upcoming Trips.
     *
     * @return \Illuminate\Http\Response
     */
    public function upcoming_trips()
    {
        $trips = UserRequest::where('user_id', '=', Auth::user()->id)
                ->where('user_requests.status', '!=', 'CANCELLED')
                ->Where('user_requests.status', '!=', 'COMPLETED')
                ->join('service_types', 'user_requests.service_type_id', '=', 'service_types.id')
                ->select('user_requests.id','user_requests.booking_id','user_requests.provider_id','service_types.name as service_name','user_requests.status','user_requests.payment_mode','user_requests.s_address','user_requests.corporate_id','user_requests.d_address','user_requests.distance','user_requests.minutes','user_requests.started_at','user_requests.schedule_at','user_requests.created_at','user_requests.provider_id')
                ->orderBy('created_at','desc')
                ->get();

        if(!empty($trips)){
            foreach ($trips as $key => $trip){
                if($trip->status =='ACCEPTED'){
                    $provider = Provider::where('id','=',$trip->provider_id)->select('avatar','name')->first();
                    $trips[$key]->provider_avatar = asset('storage/'.$provider->provider_avatar);
                    $trips[$key]->provider_name = $provider->name;         
                }
                if($trip->status =='DROPPED'){
                    $trips[$key]->total = Setting::get('currency').UserRequestPayment::where('request_id',$trip->id)->pluck('total')->first();
                }    
                $trips[$key]->distance = $trip->distance.Setting::get('distance_unit');       
            }
        }

        return view('user.ride.upcoming',compact('trips'));
    }
     /**
     * Payment.
     *
     * @return \Illuminate\Http\Response
     */
    public function payment()
    {
        $cards = (new Resource\CardResource)->index();
        return view('user.account.payment',compact('cards'));
    }

    /**
     * Show the application profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function add_card()
    {
        return view('user.account.card');
    }
    /**
     * Wallet.
     *
     * @return \Illuminate\Http\Response
     */
    public function wallet(Request $request)
    {
        $cards = (new Resource\CardResource)->index();
        if($cards->isEmpty())
        {
            return redirect('addcard')->with('flash_error','Please add card to recharge your wallet');
        }else{
            return view('user.account.wallet',compact('cards'));
        }
    }

    /**
     * Promotion.
     *
     * @return \Illuminate\Http\Response
     */
    public function promotions_index(Request $request)
    {
        try{
            $this->check_expiry();

            $promocodes =  PromocodeUsage::Active()
                    ->where('user_id', Auth::user()->id)
                    ->with('promocode')
                    ->get();

            $new_user = Promocode::where('status','ADDED')
                ->where('user_type','new')
                ->where('updated_at','<=', Auth::user()->created_at)
                ->pluck('id')->toArray();
            $all_user = Promocode::where('status','ADDED')
                ->where('user_type','all')
                ->pluck('id')->toArray();
            $promo_id=array_merge($new_user,$all_user);
            $available = Promocode::whereIn('id',$promo_id)->get();
            $promocode =  PromocodeUsage::where('user_id', Auth::user()->id)->get();
            return view('user.account.promotions', compact('promocodes','available','promocode'));        
        } catch (Exception $e) {
            return back()->with('flash_error', 'Something Went Wrong');
        }
        
    }
    public function check_expiry(){
        try{
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
     * Add promocode.
     *
     * @return \Illuminate\Http\Response
     */
    public function promotions_store(Request $request)
    {
        $this->validate($request, [
                'promocode' => 'required|exists:promocodes,promo_code',
            ]);

        try{

            $find_promo = Promocode::where('promo_code',$request->promocode)->first();

            if($find_promo->status == 'EXPIRED' || (date("Y-m-d") > $find_promo->expiration)){
                if($request->ajax()){
                    return response()->json([
                        'message' => trans('api.promocode_expired'), 
                        'code' => 'promocode_expired'
                    ]);
                }else{
                    return back()->with('flash_error', trans('api.promocode_expired'));
                }

            }elseif(PromocodeUsage::where('promocode_id',$find_promo->id)->where('user_id', Auth::user()->id)->count() > 0){
                if($request->ajax()){
                    return response()->json([
                        'message' => trans('api.promocode_already_in_use'), 
                        'code' => 'promocode_already_in_use'
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
                return back()->with('flash_success', trans('api.promocode_applied'));
               
            }

        }

        catch (Exception $e) {
            return back()->with('flash_error', 'Something Went Wrong');
        }
    }

    

    /**
     * Update profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_ride(Request $request)
    {
        
        // if(Auth::user()->due_balance !=0.00){
        //     return response()->json(['success' =>0,'message' => 'Clear Pending Dues to take trip']);
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
                        return response()->json(['success' =>0,'message' => trans('Pickup Location Zone Restricted')]);
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
                        return response()->json(['success' =>0,'message' => trans('Destination Zone Restricted')]);
                    }
                }
            }
        }

        $corporate_id = 0;
        $corporate_group_id =0;
        if($request->corporate !=0){
            $corporate_user = CorporateUser::where('id','=',Auth::user()->corporate_user_id)->first();
            if($corporate_user !=null){
                $corporate = Corporate::where('id', $corporate_user->corporate_id)->first();
                if($corporate->status !=0){
                    $corporate_id = $corporate->id;
                    $corporate_group_id = $corporate_user->corporate_group_id;
                }else{
                    return response()->json(['success' =>0,'message' => 'Corporate account is disabled']);
                }
            }else{
                return response()->json(['success' =>0,'message' => 'Corporate account Not Found']);
            }
        }

        try{
            if(Auth::user()->trip_id !=0){
                return response()->json(['success' =>0,'message' => trans('api.ride.request_inprogress')]);
            }

            if($request->has('schedule_time') && $request->schedule_time !=''){
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
                $UserRequest->admin_id = Auth::user()->admin_id;
                $UserRequest->user_id = Auth::user()->id;
                $UserRequest->user_name = Auth::user()->first_name;
                $UserRequest->user_mobile = Auth::user()->dial_code.Auth::user()->mobile;
                $UserRequest->service_type_id = $request->service_type;
                $UserRequest->corporate_id = $corporate_id;
                $UserRequest->group_id = $corporate_group_id;
                $UserRequest->payment_mode = "CASH";
                $UserRequest->status = 'SCHEDULED';
                $UserRequest->push = 'AUTO';
                $UserRequest->booking_by = 'WEB';

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
                $UserRequest->route_key = $route_key;
                $UserRequest->message = $request->message ? : "";
                $UserRequest->handicap = $request->handicap ? : 0;
                $UserRequest->pet = $request->pet ? : 0;
                $UserRequest->wagon = $request->wagon ? : 0;
                $UserRequest->booster = $request->booster ? : 0;
                $UserRequest->fixed_rate = $request->fixed_rate ? : 0;
                $UserRequest->distance = $kilometer;
                $UserRequest->minutes = $minutes;
                $UserRequest->fare_type = $fare_calc['fare_type'];
                $UserRequest->estimated_fare = round($fare_calc['fare_flat'],2);
                $UserRequest->assigned_at = Carbon::now();

                $UserRequest->surge = 0;
                $UserRequest->schedule_at = Carbon::parse($request->schedule_time);
                
                $UserRequest->save();
                $UserRequest->booking_id = '100'.$UserRequest->id;
                $UserRequest->save();
                if($UserRequest->schedule_at < Carbon::now()->addHour(Setting::get('feature_time'))){
                    app(\App\Http\Controllers\UserResources\UserTripApiController::class)->notify_driver($UserRequest->id);
                }
                return response()->json([
                        'message' => trans('api.new_request_dispatcher'),
                        'success' => 1,
                    ]);
            }

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
            $UserRequest->admin_id = Auth::user()->admin_id;
            $UserRequest->user_id = Auth::user()->id;
            $UserRequest->user_name = Auth::user()->first_name;
            $UserRequest->user_mobile = Auth::user()->dial_code.Auth::user()->mobile;
            $UserRequest->service_type_id = $request->service_type;
            $UserRequest->corporate_id = $corporate_id;
            $UserRequest->group_id = $corporate_group_id;
            $UserRequest->payment_mode = "CASH";
            $UserRequest->status = 'SEARCHING';
            $UserRequest->push = 'AUTO';
            $UserRequest->booking_by = 'WEB';
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
            $UserRequest->distance = $kilometer;
            $UserRequest->minutes = $minutes;
            $UserRequest->fare_type = $fare_calc['fare_type'];
            $UserRequest->estimated_fare = round($fare_calc['fare_flat'],2);
            $UserRequest->message = $request->message ? : "";
            $UserRequest->handicap = $request->handicap ? : 0;
            $UserRequest->pet = $request->pet ? : 0;
            $UserRequest->wagon = $request->wagon ? : 0;
            $UserRequest->booster = $request->booster ? : 0;
            $UserRequest->fixed_rate = $request->fixed_rate ? : 0;
            $UserRequest->assigned_at = Carbon::now();
            $UserRequest->route_key = "WERW";
            $UserRequest->save();
            $UserRequest->booking_id = '100'.$UserRequest->id;
            $UserRequest->save();
            
            if($request->has('card_id')){
                Card::where('user_id',Auth::user()->id)->update(['is_default' => 0]);
                Card::where('card_id',$request->card_id)->update(['is_default' => 1]);
            }

            app(\App\Http\Controllers\UserResources\UserTripApiController::class)->notify_driver($UserRequest->id);
            
            return response()->json([
                    'message' => trans('api.new_request_created'),
                    'success' => 1,
                ]);
            
        }catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function cancel_request(Request $request) {

        try{
            $UserRequest = UserRequest::findOrFail($request->request_id);
            if($UserRequest->status == 'CANCELLED'){
                return redirect()->back()->with('flash_error', trans('api.ride.already_cancelled'));  
            }

            if(in_array($UserRequest->status, ['SEARCHING','STARTED','ARRIVED','SCHEDULED','ACCEPTED'])){
                if($UserRequest->status != 'SCHEDULED'){
                    if($UserRequest->provider_id != 0){
                        Provider::where('id',$UserRequest->provider_id)->update(['trip_id' => 0,'status' => 'active', 'active_from' =>Carbon::now()]);
                    }
                }

                User::where('id',Auth::user()->id)->update(['trip_id' => 0]);
                $UserRequest->status = 'CANCELLED';
                $UserRequest->cancel_reason = $request->cancel_reason ? : '';
                $UserRequest->cancelled_by = 'USER';
                $UserRequest->save();

                 // Send Push Notification to User
                (new SendPushNotification)->UserCancellRide($UserRequest);
                
                return redirect()->back()->with('flash_success', trans('api.ride.ride_cancelled'));
                
            }else {
                return redirect()->back()->with('flash_error', trans('api.ride.already_onride'));
            }
        }
        catch (ModelNotFoundException $e) {
            return redirect()->back()->with('flash_error', trans('api.something_went_wrong'));
        }

    }


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
            $services = ServiceType::all();
	    $result = array();
            $result[0]=$kilometer;
            $result[1]=$minutes;
            $result[2]=$total;
            $result[3]=$unit;
            $result[4]=$currency;
            $result[5]=$fare_type;
            $result[6]=date("h:i:a", strtotime($estimated_time));
            $result[7]=$service_type->name;
	$total1 = array();
	$seats = array();
	 $i = 0;
	foreach($services as $service){
		$fare_calc = Helper::fare_calc($service->id, $request->s_latitude, $request->s_longitude, $request->d_latitude, $request->d_longitude,$kilometer, $minutes);
            
	    
            if($fare_calc['fare_type'] ==3){
                $fare_type = 'Distance fare';
            }else if($fare_calc['fare_type'] ==2 || $fare_calc['fare_type']==1){
                $fare_type = 'Fixed fare';
            }else{
                $fare_type = 'Not found';
            }
            $price1  = round($fare_calc['fare_flat'],2);
            $service_type1 = ServiceType::where('id','=',$service->id)->first();
            $tax_price1 = $price1 * $service_type1->vat_percent/100;
		$total1[$i] = round(($price1 + $tax_price1),2);
		$seats[$i] = $service->seats_available;
    		$i = $i + 1;
	}

	 $result1 = array();
            $result1[0]=$kilometer;
            $result1[1]=$minutes;
            $result1[2]=$total1;
            $result1[3]=$unit;
            $result1[4]=$currency;
            $result1[5]=$fare_type;
            $result1[6]=date("h:i:a", strtotime($estimated_time));
            $result1[7]=$service_type->name;
	    $result1[8]=$seats;
		
		
            return $result1;

        }catch (Exception $e) {
            return $e;
        }
    }
}
