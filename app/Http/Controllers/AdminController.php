<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\Helper;
use DB;
use Auth;
use Setting;
use Exception;
use PushNotification;
use \Carbon\Carbon;
use Twilio;
use DateTimeZone;

use App\Models\User;
use App\Models\Provider;
use App\Models\Corporate;
use App\Models\Partner;
use App\Models\ServiceType;
use App\Models\UserRequest;
use App\Models\UserRequestPayment;
use App\Models\Location;
use App\Models\PoiFare;
use App\Models\FareModel;
use App\Models\LocationWiseFare;
use App\Models\RestrictLocation;
use App\Models\UserRequestRating;
use App\Models\RequestFilter;
use App\Models\Dispatcher;
use App\Models\Admin;
use App\Models\WebNotify;
use App\Models\Demo;
use App\Models\Country;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('admin');

        
       $this->middleware('admin');
        $this->middleware(function ($request, $next) {
        $this->id = Auth::user()->id;
             $admin = Admin::where('id','=',$this->id)->first();
             if($admin->admin_type != 0 && $admin->time_zone != null){
                 date_default_timezone_set($admin->time_zone);
             }
            
        return $next($request);
    });
        

    }
    /**
     * Dashboard.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $days = 20;
	$adminid = auth()->user()->id;
	
        $range = \Carbon\Carbon::now()->subDays($days);
        //dd(\Carbon\Carbon::today()->format('Y-m-d\TH:i', 'America/Los_Angeles'));
        $range = date($range);
        $notifies = WebNotify::where('status',0)->get();
	if(auth()->user()->admin_type == "0"){
        $result = DB::select("SELECT
                                Date(user_requests.created_at) as date,
                                COUNT(CASE WHEN user_requests.status = 'COMPLETED' THEN 1 END) AS completed,
                                COUNT(CASE WHEN user_requests.status = 'CANCELLED' THEN 1 END) AS cancelled,
                                IFNULL(ROUND(SUM(user_request_payments.total),2), 0) as revenue
                                
                            FROM user_requests
                            LEFT JOIN user_request_payments
                            ON user_requests.id = user_request_payments.request_id
                            WHERE user_requests.created_at >= '$range'
                            GROUP BY date
                            ORDER BY date DESC");
        }else{
		$result = DB::select("SELECT
                                Date(user_requests.created_at) as date,
                                COUNT(CASE WHEN user_requests.status = 'COMPLETED' THEN 1 END) AS completed,
                                COUNT(CASE WHEN user_requests.status = 'CANCELLED' THEN 1 END) AS cancelled,
                                IFNULL(ROUND(SUM(user_request_payments.total),2), 0) as revenue
                                
                            FROM user_requests
                            LEFT JOIN user_request_payments
                            ON user_requests.id = user_request_payments.request_id
                            WHERE user_requests.created_at >= '$range' AND user_requests.admin_id = '$adminid'
                            GROUP BY date
                            ORDER BY date DESC");

	}
       $stats = json_encode($result);


       $days = 7;
       $barrange = \Carbon\Carbon::now()->subDays($days);
       $barrange = date($range);
       if(auth()->user()->admin_type == "0"){
       $barresult = DB::select("SELECT
                                Date(user_requests.created_at) as date,
                                COUNT(CASE WHEN user_requests.booking_by = 'APP' THEN 1 END) AS app,
                                COUNT(CASE WHEN user_requests.booking_by = 'DISPATCHER' THEN 1 END) AS dispatcher,
                                 COUNT(CASE WHEN user_requests.booking_by = 'STREET' THEN 1 END) AS street 
                            FROM user_requests
                            WHERE user_requests.created_at >= '$barrange'
                            GROUP BY date
                            ORDER BY date ASC");
       
       		foreach($barresult as $key =>$stat){
            		$barresult[$key]->date = $stat->date;
       		}
	}else{
		$barresult = DB::select("SELECT
                                Date(user_requests.created_at) as date,
                                COUNT(CASE WHEN user_requests.booking_by = 'APP' THEN 1 END) AS app,
                                COUNT(CASE WHEN user_requests.booking_by = 'DISPATCHER' THEN 1 END) AS dispatcher,
                                 COUNT(CASE WHEN user_requests.booking_by = 'STREET' THEN 1 END) AS street 
                            FROM user_requests
                            WHERE user_requests.created_at >= '$barrange' AND user_requests.admin_id = '$adminid'
                            GROUP BY date
                            ORDER BY date ASC");
       
       		foreach($barresult as $key =>$stat){
            		$barresult[$key]->date = $stat->date;
       		}

	}
       $bar = json_encode($barresult);

       $pierange = Carbon::today();
       $pierange = date($pierange);
       if(auth()->user()->admin_type == "0"){
       		$pieresult = DB::select("SELECT
                                COUNT(CASE WHEN user_requests.cancelled_by = 'USER' THEN 1 END) AS user,
                                COUNT(CASE WHEN user_requests.cancelled_by = 'DISPATCHER' THEN 1 END) AS dispatcher,
                                COUNT(CASE WHEN (user_requests.cancelled_by = 'NODRIVER' || user_requests.cancelled_by = 'REJECTED') THEN 1 END) AS rejected,
                                 COUNT(CASE WHEN user_requests.cancelled_by = 'PROVIDER' THEN 1 END) AS provider 
                            FROM user_requests
                            WHERE user_requests.created_at >= '$pierange'");
	}else{
		$pieresult = DB::select("SELECT
                                COUNT(CASE WHEN user_requests.cancelled_by = 'USER' THEN 1 END) AS user,
                                COUNT(CASE WHEN user_requests.cancelled_by = 'DISPATCHER' THEN 1 END) AS dispatcher,
                                COUNT(CASE WHEN (user_requests.cancelled_by = 'NODRIVER' || user_requests.cancelled_by = 'REJECTED') THEN 1 END) AS rejected,
                                 COUNT(CASE WHEN user_requests.cancelled_by = 'PROVIDER' THEN 1 END) AS provider 
                            FROM user_requests
                            WHERE user_requests.created_at >= '$pierange' AND user_requests.admin_id = '$adminid'");
	}
       $pie = $pieresult;

       return view('admin.dashboard',compact('stats', 'bar', 'pie','notifies'));

    }

    public function content(Request $request)
    {
        try{
            $fromdate = Carbon::today();
            $todate = Carbon::now();

            if($request->fromdate !=''){
                $fromdate = $request->fromdate;
            }
            if($request->todate !=''){
                $todate = Carbon::parse($request->todate)->addDay();
            }

            $rides = UserRequest::where('created_at', '>=', $fromdate)
                ->where('created_at', '<', $todate)
                ->orderBy('id','desc')
                ->get();

            $completed_list = UserRequest::with('payment')->where('status','COMPLETED')
                ->where('created_at', '>=', $fromdate)
                ->where('created_at', '<', $todate)
                ->get();
            
            $completed_rides = UserRequest::where('status','COMPLETED')
                ->where('created_at', '>=', $fromdate)
                ->where('created_at', '<', $todate)
                ->count();

            $cancel_rides = UserRequest::where('status','CANCELLED')
                ->where('created_at', '>=', $fromdate)
                ->where('created_at', '<', $todate)
                ->get();

            $scheduled_rides = UserRequest::where('created_at', '>=', $fromdate)
                ->where('created_at', '<', $todate)
                ->where('status','SCHEDULED')
                ->count();

            $dispatcher_rides = UserRequest::where('created_at', '>=', $fromdate)
                ->where('created_at', '<', $todate)
                ->where('booking_by','DISPATCHER')
                ->count();

            $street_rides = UserRequest::where('created_at', '>=', $fromdate)
                ->where('created_at', '<', $todate)
                ->where('booking_by','STREET')
                ->count();
                    
            $user_cancelled = UserRequest::where('status','CANCELLED')
                ->where('created_at', '>=', $fromdate)
                ->where('created_at', '<', $todate)
                ->where('cancelled_by','USER')
                ->count();

            $provider_cancelled = UserRequest::where('status','CANCELLED')
                ->where('created_at', '>=', $fromdate)
                ->where('created_at', '<', $todate)
                ->where('cancelled_by','PROVIDER')
                ->count();

            $dispatcher_cancelled = UserRequest::where('status','CANCELLED')
                ->where('created_at', '>=', $fromdate)
                ->where('created_at', '<', $todate)
                ->where('cancelled_by','DISPATCHER')
                ->count();

	    $driver_not_accepted = UserRequest::where('cancel_reason','Driver Not Accepted')
                ->where('created_at', '>=', $fromdate)
                ->where('created_at', '<', $todate)
                ->count();

            $service = ServiceType::count();
            $partner = Partner::count();

            $revenue = 0;

            foreach($completed_list as $key=>$tb)
            {
               if($tb->payment){
                    $revenue += $tb->payment->total;
               } 
            }  
               
            $providers = Provider::take(10)->orderBy('rating','desc')->get();


            return view('admin.dashboard-content',compact('providers','partner','scheduled_rides','service','rides','completed_rides','user_cancelled','provider_cancelled','dispatcher_cancelled','cancel_rides','revenue','dispatcher_rides','street_rides','driver_not_accepted'));
        }
        catch(Exception $e){
            return redirect()->route('admin.user.index')->with('flash_error','Something Went Wrong with Dashboard!');
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function settings()
    {
        $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        return view('admin.settings.application', compact('tzlist'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function settings_store(Request $request)
    {
        $this->validate($request,[
                // 'store_link_android' => 'required',
                // 'store_link_ios' => 'required',
                // 'site_title' => 'required',
                // 'site_icon' => 'mimes:jpeg,jpg,bmp,png,gif|max:5242880',
                // 'site_logo' => 'mimes:jpeg,jpg,bmp,png,gif|max:5242880',
            ]);

        if($request->hasFile('site_icon')) {
            $site_icon = Helper::upload_picture($request->file('site_icon'));
            Setting::set('site_icon', $site_icon);
        }

        if($request->hasFile('site_logo')) {
            $site_logo = Helper::upload_picture($request->file('site_logo'));
            Setting::set('site_logo', $site_logo);
        }

        if($request->has('timezoner')){
            $name = 'APP_TIMEZONE';
            $value = $request->timezoner;
            $path = base_path('.env');
            if (file_exists($path)) {
                file_put_contents($path, str_replace(
                    $name . '=' . env($name), $name . '=' . $value, file_get_contents($path)
                ));
            }
        }
        //dd(file_get_contents($path));
        $Admin = Auth::guard('admin')->user();
        $Admin->admin_address = $request->address;
        $Admin->admin_lat = $request->address_lat;
        $Admin->admin_long = $request->address_long;
        $Admin->admin_zoom =$request->zoom ? : 8;
;
    $Admin->save();

        if(Auth::guard('admin')->user()->admin_type !=0){
            Dispatcher::where('admin_id','=',Auth::guard('admin')->user()->id)->update(['dispatch_address' =>$request->address,'dispatch_lat' =>$request->address_lat,'dispatch_long' =>$request->address_long,'dispatch_zoom' =>$request->zoom]);
        }else{
            Dispatcher::where('admin_id','=',null)->update(['dispatch_address' =>$request->address,'dispatch_lat' =>$request->address_lat,'dispatch_long' =>$request->address_long,'dispatch_zoom' =>$request->zoom]);
        }

        if($request->site_title){
        Setting::set('site_title', $request->site_title);
        }
        Setting::set('store_link_android', $request->store_link_android);
        Setting::set('store_link_ios', $request->store_link_ios);
        Setting::set('store_link_android_driver', $request->store_link_android_driver);
        Setting::set('store_link_ios_driver', $request->store_link_ios_driver);

        if($request->mail_enable != ""){
        Setting::set('mail_enable', $request->mail_enable);
        }
    if($request->sms_enable != ""){
        Setting::set('sms_enable', $request->sms_enable);
        }
    if($request->provider_select_timeout){
        Setting::set('provider_select_timeout', $request->provider_select_timeout);
        }
    if($request->offline_time){
        Setting::set('offline_time', $request->offline_time);
        }
         if($request->min_wallet){
        Setting::set('min_wallet', $request->min_wallet);
        }
    if($request->distance_unit){
        Setting::set('distance_unit', $request->distance_unit);
        }
    Setting::set('sos_number', $request->sos_number);
        Setting::set('contact_number', $request->contact_number);
        Setting::set('contact_email', $request->contact_email);
        if($request->site_copyright){
        Setting::set('site_copyright', $request->site_copyright);
        }
    // Setting::set('social_login', $request->social_login);
        if($request->address){
            // dd($request->address); die;
        Setting::set('address', $request->address);
        }
    if($request->address_lat){
            Setting::set('address_lat', $request->address_lat);
            Setting::set('address_long', $request->address_long);
            Setting::set('zoom', $request->zoom ? : 8);
        }
    if($request->refferal){
            Setting::set('refferal', $request->refferal);
        }
    if($request->refferal_type){
        Setting::set('refferal_type', $request->refferal_type);
        }
    if($request->refferal_value){
        Setting::set('refferal_value', $request->refferal_value);
        }
    if($request->country_code){
        Setting::set('country_code', $request->country_code);
        }
    if($request->state){
        Setting::set('state', $request->state);
        }
    if($request->city){
        Setting::set('city', $request->city);
        }
    if($request->country){
        Setting::set('country', $request->country);
        }
    //Setting::set('address', $request->address);
      if($request->auto_assign){
        Setting::set('auto_assign', $request->auto_assign ? : 0);
      }
   
      if($request->tipe_type == '0'){
       
           Setting::set('tipe_type', $request->tipe_type);
                
      }
      if($request->tipe_type == '1'){
       
           Setting::set('tipe_type', $request->tipe_type);
                
      }
        //   Setting::set('tip_0', $request->tip_0 ? : 0);
      if($request->tip_1){
           Setting::set('tip_1', $request->tip_1 ? : 1);
      }
      if($request->tip_2){
           Setting::set('tip_2', $request->tip_2 ? : 2);
      }
      if($request->tip_3){
           Setting::set('tip_3', $request->tip_3 ? : 3);
      }
      if($request->tip_4){
           Setting::set('tip_4', $request->tip_4 ? : 4);
      }
      
      
      if($request->wallet_suggestion1){
           Setting::set('wallet_suggestion1', $request->wallet_suggestion1 ? : 1);
      }
      if($request->wallet_suggestion2){
           Setting::set('wallet_suggestion2', $request->wallet_suggestion2 ? : 2);
      }
      if($request->wallet_suggestion3){
           Setting::set('wallet_suggestion3', $request->wallet_suggestion3 ? : 3);
      }
        //   Setting::set('tip_4', $request->tip_4 ? : 4);
      
      
      
         // Setting::set('tip_5', $request->tip_5 ? : 5);
      
        /*Setting::set('stop_title', $request->stop_title ? : 0);
        Setting::set('stop_description', $request->stop_description ? : 0);
        Setting::set('payment_description', $request->payment_description ? : 0);
      
        if($request->android_user_map){
            Setting::set('android_user_map', $request->android_user_map ? : 'AIzaSyCI8N0Wo2WPN8ZGqi0vyb_IDuhRoTswPT');
        }

        if($request->android_driver_map){
            Setting::set('android_driver_map', $request->android_driver_map ? : 'AIzaSyCI8N0Wo2WPN8ZGqi0vyb_IDuhRoTswPT');
        }

        if($request->ios_user_map){
            Setting::set('ios_user_map', $request->ios_user_map ? : 'AIzaSyCI8N0Wo2WPN8ZGqi0vyb_IDuhRoTswPT');
        }
        if($request->ios_driver_map){
            Setting::set('ios_driver_map', $request->ios_driver_map ? : 'AIzaSyCI8N0Wo2WPN8ZGqi0vyb_IDuhRoTswPT');
        }*/
        Setting::save();
        
        return back()->with('flash_success','Settings Updated Successfully');
    }



    public function store_business(Request $request)
    {
        $this->validate($request,[
                // 'store_link_android' => 'required',
                // 'store_link_ios' => 'required',
                // 'site_title' => 'required',
                // 'site_icon' => 'mimes:jpeg,jpg,bmp,png,gif|max:5242880',
                // 'site_logo' => 'mimes:jpeg,jpg,bmp,png,gif|max:5242880',
            ]);
           

    
        if($request->mail_enable != ""){
            Setting::set('mail_enable', $request->mail_enable);
            }

        if($request->sms_enable != ""){
            Setting::set('sms_enable', $request->sms_enable);
            }

        if($request->country_code){
            Setting::set('country_code', $request->country_code);
            }

            if($request->offline_time){
                Setting::set('offline_time', $request->offline_time);
                }

        if($request->provider_select_timeout){
            Setting::set('provider_select_timeout', $request->provider_select_timeout);
            }

      

            if($request->distance_unit){
                Setting::set('distance_unit', $request->distance_unit);
                }

                if($request->auto_assign){
                    Setting::set('auto_assign', $request->auto_assign ? : 0);
                  }

                  if($request->state){
                    Setting::set('state', $request->state);
                    }
                if($request->city){
                    Setting::set('city', $request->city);
                    }

                if($request->country){
                    Setting::set('country', $request->country);
                    }

                    if($request->address){
                        Setting::set('address', $request->address);
                        }
                    if($request->address_lat){
                            Setting::set('address_lat', $request->address_lat);
                            Setting::set('address_long', $request->address_long);
                            Setting::set('zoom', $request->zoom ? : 8);
                        }

                        if($request->tipe_type == '1'){
       
                            Setting::set('tipe_type', $request->tipe_type);
                                 
                       }
                         //   Setting::set('tip_0', $request->tip_0 ? : 0);
                       if($request->tip_1){
                            Setting::set('tip_1', $request->tip_1 ? : 1);
                       }
                       if($request->tip_2){
                            Setting::set('tip_2', $request->tip_2 ? : 2);
                       }
                       if($request->tip_3){
                            Setting::set('tip_3', $request->tip_3 ? : 3);
                       }
                       if($request->tip_4){
                            Setting::set('tip_4', $request->tip_4 ? : 4);
                       }
        

        if($request->has('timezoner')){
            $name = 'APP_TIMEZONE';
            $value = $request->timezoner;
            $path = base_path('.env');
            if (file_exists($path)) {
                file_put_contents($path, str_replace(
                    $name . '=' . env($name), $name . '=' . $value, file_get_contents($path)
                ));
            }
        }
        //dd(file_get_contents($path));
        $Admin = Auth::guard('admin')->user();
        $Admin->admin_address = $request->address;
        $Admin->admin_lat = $request->address_lat;
        $Admin->admin_long = $request->address_long;
        $Admin->admin_zoom =$request->zoom ? : 8;
        $Admin->save();

        if(Auth::guard('admin')->user()->admin_type !=0){
            Dispatcher::where('admin_id','=',Auth::guard('admin')->user()->id)->update(['dispatch_address' =>$request->address,'dispatch_lat' =>$request->address_lat,'dispatch_long' =>$request->address_long,'dispatch_zoom' =>$request->zoom]);
        }else{
            Dispatcher::where('admin_id','=',null)->update(['dispatch_address' =>$request->address,'dispatch_lat' =>$request->address_lat,'dispatch_long' =>$request->address_long,'dispatch_zoom' =>$request->zoom]);
        }

       
        
        Setting::save();
        
        return back()->with('flash_success','Settings Updated Successfully');
    }


   

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function settings_payment()
    {
        return view('admin.payment.settings');
    }

    /**
     * Save payment related settings.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function settings_payment_store(Request $request)
    {
        
        $this->validate($request, [
                'CARD' => 'in:on',
                'CASH' => 'in:on',
                'stripe_secret_key' => 'required_if:CARD,on|max:255',
                'stripe_publishable_key' => 'required_if:CARD,on|max:255',
                'currency' => 'required',
                /*'auto_assign' => 'required',
                'feature_time' => 'required',
                'notification_time' => 'required',
                'manual_time' => 'required',
                'close_time' => 'required'*/
            ]);

        Setting::set('CARD', $request->has('CARD') ? 1 : 0 );
        Setting::set('CASH', $request->has('CASH') ? 1 : 0 );
        // Setting::set('stripe_secret_key', $request->stripe_secret_key);
        // Setting::set('stripe_publishable_key', $request->stripe_publishable_key);
        Setting::set('currency', $request->currency);
        Setting::set('booking_prefix', $request->booking_prefix);
    Setting::set('commission_enable', $request->commission_enable);
    Setting::set('commission_percentage', $request->commission_percentage);
    Setting::set('fare_edit', $request->fare_edit);

    // Setting::set('commission_percentage_next', $request->commission_percentage_next);
    // Setting::set('level_default', $request->level_default);
    // Setting::set('level_1', $request->level_1);
    // Setting::set('level_2', $request->level_2);
    // Setting::set('level_3', $request->level_3);

    // Setting::set('level_default_value', $request->level_default_value);
    // Setting::set('level_1_value', $request->level_1_value);
    // Setting::set('level_2_value', $request->level_2_value);
    // Setting::set('level_3_value', $request->level_3_value);

    // Setting::set('default_commission', $request->default_commission);
    // Setting::set('level_1_ref_commission', $request->level_1_ref_commission);
    // Setting::set('level_2_ref_commission', $request->level_2_ref_commission);
    // Setting::set('level_3_ref_commission', $request->level_3_ref_commission);
    // Setting::set('provider_registeration_fee', $request->provider_registeration_fee);
    // Setting::set('provider_registeration_msg', $request->provider_registeration_msg);



        //Setting::set('auto_assign', $request->auto_assign ? : 0);
        //Setting::set('acc_detail', $request->acc_detail ? :'');
        //Setting::set('feature_time', $request->feature_time);
        //Setting::set('notification_time', $request->notification_time);
        //Setting::set('manual_time', $request->manual_time);
        //Setting::set('close_time', $request->close_time);
        //Setting::set('cancel_percent', $request->cancel_percent);
        Setting::set('vat_percent', $request->vat_percent);
        //Setting::set('dispatch_algorithm', $request->dispatch_algorithm);

        //Setting::set('time_1', $request->time_1);
        //Setting::set('distance_1', $request->distance_1);
        //Setting::set('time_2', $request->time_2);
        //Setting::set('distance_2', $request->distance_2);
        //Setting::set('time_3', $request->time_3);
        //Setting::set('distance_3', $request->distance_3);
        //Setting::set('time_4', $request->time_4);
        //Setting::set('distance_4', $request->distance_4);
        //Setting::set('time_5', $request->time_5);
        //Setting::set('distance_5', $request->distance_5);
        Setting::save();

        return back()->with('flash_success','Settings Updated Successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        return view('admin.account.profile');
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
            'email' => 'required',
            'picture' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
        ]);

        try{
            $admin = Auth::guard('admin')->user();
            $admin->name = $request->name;
            $admin->email = $request->email;
            if($request->hasFile('picture')){
                $admin->picture = $request->picture->store('public/admin/profile');
                $admin->picture = $request->picture->store('admin/profile');  
            }
            $admin->save();

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
        return view('admin.account.change-password');
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

           $Admin = Auth::guard('admin')->user();

            if(password_verify($request->old_password, $Admin->password))
            {
                $Admin->password = bcrypt($request->password);
                $Admin->save();

                return redirect()->back()->with('flash_success','Password Updated');
            }
        } catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
    }

    /**
     * Heat Map.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function heatmap()
    {
        try{
            $rides = UserRequest::has('user')->orderBy('id','desc')->get();
            $providers = Provider::take(10)->orderBy('rating','desc')->get();
            return view('admin.map.heatmap',compact('providers','rides'));
        }
        catch(Exception $e){
            return redirect()->route('admin.user.index')->with('flash_error','Something Went Wrong with Dashboard!');
        }
    }

    /**
     * Map of all Users and Drivers.
     *
     * @return \Illuminate\Http\Response
     */
    public function map_index()
    {
        return view('admin.map.index');
    }

    /**
     * Map of all Users and Drivers.
     *
     * @return \Illuminate\Http\Response
     */
    public function map_ajax()
    {
        try {

            $Providers = Provider::where('latitude', '!=', 0)
                    ->where('longitude', '!=', 0)
                    ->get();

            $Users = User::where('latitude', '!=', 0)
                    ->where('longitude', '!=', 0)
                    ->get();

            for ($i=0; $i < sizeof($Users); $i++) { 
                $Users[$i]->status = 'user';
            }

            $All = $Users->merge($Providers);

            return $All;

        } catch (Exception $e) {
            return [];
        }
    }
    /**
     * account statements.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function statement($type = 'individual'){

        try{

            $page = 'Ride Statement';

            if($type == 'individual'){
                $page = 'Overall  Ride Statement';
            }elseif($type == 'today'){
                $page = 'Today Statement - '. date('d M Y');
            }elseif($type == 'monthly'){
                $page = 'This Month Statement - '. date('F');
            }elseif($type == 'yearly'){
                $page = 'This Year Statement - '. date('Y');
            }
            $type_data = $type;
            return view('admin.statement.overall', compact('page','type_data'));

        } catch (Exception $e) {
            return back()->with('flash_error','Something Went Wrong!');
        }
    }


    public function statement_content(Request $request){

        $columns = array( 
                            0 =>'id', 
                            1 =>'booking_id',
                            2=> 's_address',
                            3=> 'stop1_address',
                            4=> 'stop2_address',
                            5=> 'd_address',
                            6=> 'detail',
                            7=> 'created_at',
                            8=> 'status',
                            9=> 'payment_mode',
                            10=> 'total',
                            11=> 'commission',
                            12=> 'earnings',
                        );
        $fromdate = '';
        $todate = Carbon::now();
        $payment_type ='';
        $tripstatus ='';
        
        if($request->type_data !=''){
            $type = $request->type_data;
            if($type == 'today'){ $fromdate = Carbon::today(); }
            if($type == 'monthly'){ $fromdate = Carbon::now()->startOfMonth(); }
            if($type == 'yearly'){ $fromdate = Carbon::now()->year; }
        }

        if($request->fromdate !=''){
            $fromdate = $request->fromdate;
        }
        if($request->todate !=''){
            $todate = Carbon::parse($request->todate)->addDay();
        }

        if($request->has('payment')){
            $payment_type = $request->payment;
        }

        if($request->has('tripstatus')){
            $tripstatus = $request->tripstatus;
        }
        $main_detail = UserRequest::with('payment')
                        ->where('created_at', '>=', $fromdate)
                        ->where('created_at', '<', $todate)
                        ->where('status', 'LIKE', '%'.$tripstatus.'%');

        if($payment_type =='CORPORATE') {
            $main_detail = $main_detail->where('corporate_id', '!=',0);
        }else {
            if($payment_type !=''){
                $main_detail = $main_detail->where('corporate_id', '=',0)->where('payment_mode','LIKE', '%'.$payment_type.'%');
            }
        }

        $cancel_rides = UserRequest::where('status','CANCELLED')
                      ->where('created_at', '>=', $fromdate)
                      ->where('created_at', '<', $todate)
                      ->where('status', 'LIKE', '%'.$tripstatus.'%');

        if($payment_type =='CORPORATE') {
            $cancel_rides = $cancel_rides->where('corporate_id', '!=',0);
        }else{
            if($payment_type !=''){
                $cancel_rides = $cancel_rides->where('corporate_id', '=',0)->where('payment_mode','LIKE', '%'.$payment_type.'%');
            }
        }  
                      
        $total_base = $main_detail->get();
                $rev_sum = 0;          
                foreach($total_base as $key=>$tb)
                {
                   if($tb->payment){
                        $rev_sum += $tb->payment->total;
                   } 
                } 
        $revenue =  round($rev_sum,2);      
        $total_cancel = $cancel_rides->count();
        $total_revenue = $revenue;
        $totalData = $main_detail->count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

      if(empty($request->input('search.value')))
        {            
            $rides = $main_detail
                     ->offset($start)
                     ->limit($limit)
                     ->orderBy('id','desc')
                     ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $rides =  $main_detail
                            ->where('booking_id','LIKE',"%{$search}%")
                            ->orWhere('s_address', 'LIKE',"%{$search}%")
                            ->orWhere('d_address', 'LIKE',"%{$search}%")
                            ->orWhere('created_at', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy('id','desc')
                            ->get();
	
	           $totalFiltered = $main_detail
                             ->where('booking_id','LIKE',"%{$search}%")
                             ->orWhere('s_address', 'LIKE',"%{$search}%")
                             ->orWhere('d_address', 'LIKE',"%{$search}%")
                             ->orWhere('created_at', 'LIKE',"%{$search}%")
			     ->offset($start)
                     	     ->limit($limit)
                             ->orderBy('id','desc')
			     ->count();

        }

        $data = array();
        if(!empty($rides))
        {
            foreach ($rides as $index => $ride)
            {
                $view =  route('admin.requests.show',$ride->id);
                if($ride->s_address != ''){ $s_address = $ride->s_address;}else{$s_address = "Not Provided";}
                if($ride->stop1_address != ''){ $stop1_address = $ride->stop1_address;}else{$stop1_address = "-";}
                if($ride->stop2_address != ''){ $stop2_address = $ride->stop2_address;}else{$stop2_address = "-";}
                if($ride->d_address != ''){ $d_address = $ride->d_address;}else{$d_address = "Not Provided";}
                if($ride->status != 'CANCELLED'){ $detail = '<a class="text-primary" href="'.$view.'"><div class="label label-table label-info">'.trans("admin.member.view").'</div></a>'; }else{$detail= '<span>'.trans("admin.member.no_details_found").'</span>'; }
                if($ride->status == "COMPLETED"){$status = '<span class="label label-table label-success">'.$ride->status.'</span>';}
                elseif($ride->status == "CANCELLED"){$status = '<span class="label label-table label-danger">'.$ride->status.'</span>';}
                else{$status = '<span class="label label-table label-primary">'.$ride->status.'</span>';}
                if($ride->payment){
                    $total_text = $ride->payment->currency.$ride->payment->total;
                }else{
                    $total_text='';
                }
                if($ride->corporate_id !=0){
                    $payment_mode = 'CORPORATE';
                }else{
                    $payment_mode = $ride->payment_mode;
                }
                if($ride->payment){
                    $commission = $ride->payment->commision;
                }else{
                    $commission=0.00;
                }

                if($ride->payment){
                    $earning = $ride->payment->earnings;
                }else{
                    $earning=0.00;
                }

                $nestedData['id'] = $start + 1;
                $nestedData['booking_id'] = $ride->booking_id;
                $nestedData['s_address'] =  $s_address;
                $nestedData['stop1_address'] =  $stop1_address;
                $nestedData['stop2_address'] =  $stop2_address;
                $nestedData['d_address'] =  $d_address;
                $nestedData['detail'] = $detail;
                $nestedData['created_at'] = date('d M Y',strtotime($ride->created_at));
                $nestedData['status'] = $status;
                $nestedData['payment_mode'] = $payment_mode;
                $nestedData['total'] = $total_text;
                $nestedData['commission'] = $commission;
                $nestedData['earnings'] = $earning;
                $data[] = $nestedData;
                $start++;
            }
        }
        $percentage = 0.00;
	if($total_cancel != 0){
             if($totalFiltered != 0){
		$percentage = round($total_cancel / $totalFiltered, 2);
             }
	}
	
	        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data,
                    "cancel_rides"    => $total_cancel,
                    "revenue"         => $total_revenue,
                    "percentage"      => $percentage
                    );
          // dd($json_data); 
        echo json_encode($json_data);      

    }

    /**
     * account statements today.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function statement_today(){
        return $this->statement('today');
    }

    /**
     * account statements monthly.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function statement_monthly(){
        return $this->statement('monthly');
    }

     /**
     * account statements monthly.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function statement_yearly(){
        return $this->statement('yearly');
    }
    /**
     * User Rating.
     *
     * @return \Illuminate\Http\Response
     */
    public function user_review()
    {
        try {
            return view('admin.review.user_review');
        } catch(Exception $e) {
            return redirect()->route('admin.dashboard')->with('flash_error','Something ');
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function reviewuser_row(Request $request){

        $columns = array( 
                            0 =>'id', 
                            1 =>'request_id',
                            2=> 'user_name',
                            3=> 'provider_name',
                            4=> 'rating',
                            5=> 'date_time',
                            6=> 'comments',
                        );
        $user_review = UserRequestRating::where('user_id','!=', 0)->with('user','provider');
        $totalData = $user_review->count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $Reviews = $user_review->offset($start)
                     ->limit($limit)
                     ->orderBy('id','desc')
                     ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $Reviews =  $user_review->where('request_id','LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy('id','desc')
                            ->get();

            $totalFiltered = $user_review->where('request_id','LIKE',"%{$search}%")
                            ->count();
        }

        $data = array();
        if(!empty($Reviews))
        {
            foreach ($Reviews as $index => $review)
            {
                if($review->user){
                    $user_name = $review->user->first_name;
                }
                // else{
                //     $user_name ='Not Found';
                // }

                if($review->provider){
                    $provider_name = $review->provider->name;
                    $rating = '<div className="rating-outer">
                                        <input type="hidden" value="'.$review->user_rating.'" name="rating" class="rating"/>
                                    </div>';
                }
                // else{
                //     $provider_name ='Not Found';
                // }

                // $rating = '<div className="rating-outer">
                //                     <input type="hidden" value="'.$review->user_rating.'" name="rating" class="rating"/>
                //                 </div>';
                if($review->user){
                $nestedData['id'] = $start + 1;
                $nestedData['request_id'] = $review->request_id;
                $nestedData['user_name'] =  $user_name;
                }
                if($review->provider){
                $nestedData['provider_name'] =  $provider_name;
                $nestedData['rating'] = $rating;
                $nestedData['date_time'] = $review->created_at->diffForHumans();
                $nestedData['comments'] = $review->user_comment;
                $data[] = $nestedData;
                $start++;
                }

            }
        }
        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data
                    );
            
        echo json_encode($json_data);     

    }
    /**
     * Provider Rating.
     *
     * @return \Illuminate\Http\Response
     */
    public function provider_review()
    {
        try {
            return view('admin.review.provider_review');
        } catch(Exception $e) {
            return redirect()->route('admin.dashboard')->with('flash_error','Something Went Wrong!');
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function reviewprovider_row(Request $request){

        $columns = array( 
                            0 =>'id', 
                            1 =>'request_id',
                            2=> 'user_name',
                            3=> 'provider_name',
                            4=> 'rating',
                            5=> 'date_time',
                            6=> 'comments',
                        );
        $provider_review = UserRequestRating::where('provider_id','!=', 0)->with('user','provider');
        $totalData = $provider_review->count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $Reviews = $provider_review->offset($start)
                     ->limit($limit)
                     ->orderBy('id','desc')
                     ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $Reviews =  $provider_review->where('request_id','LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy('id','desc')
                            ->get();

            $totalFiltered = $provider_review->where('request_id','LIKE',"%{$search}%")
                            ->count();
        }

        $data = array();
        if(!empty($Reviews))
        {
            foreach ($Reviews as $index => $review)
            {
                if($review->user){
                    $user_name = $review->user->first_name;
                }
                // else{
                //     $user_name ='Not Found';
                // }

                if($review->provider){
                    $provider_name = $review->provider->name;
                    $rating = '<div className="rating-outer">
                                        <input type="hidden" value="'.$review->provider_rating.'" name="rating" class="rating"/>
                                    </div>';
                }
                // else{
                //     $provider_name ='Not Found';
                // }

                if($review->user){
                    $nestedData['id'] = $start + 1;
                    $nestedData['request_id'] = $review->request_id;
                    $nestedData['user_name'] =  $user_name;
                }
                if($review->provider){
                    $nestedData['provider_name'] =  $provider_name;
                    $nestedData['rating'] = $rating;
                    $nestedData['date_time'] = $review->created_at->diffForHumans();
                    $nestedData['comments'] = $review->provider_comment;
                    $data[] = $nestedData;
                    $start++;

              }

            }
        }
        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data
                    );
            
        echo json_encode($json_data);     

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function payment()
    {
        try {
            return view('admin.request.payment-history');
        } catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function payment_row(Request $request){

        $columns = array( 
                            0 =>'request_id', 
                            1 =>'transaction_id',
                            2=> 'from',
                            3=> 'to',
                            4=> 'total_amount',
                            5=> 'payment_mode',
                            6=> 'payment_status',
                        );

        $payment_list = UserRequest::with('user','provider','payment')
                        ->where('paid', 1);
        $totalData = $payment_list->count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $Payments = $payment_list->offset($start)
                     ->limit($limit)
                     ->orderBy('id','desc')
                     ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $Payments =  $payment_list->where('id','LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy('id','desc')
                            ->get();

            $totalFiltered = $payment_list->where('id','LIKE',"%{$search}%")
                            ->count();
        }

        $data = array();
        if(!empty($Payments))
        {
            foreach ($Payments as $index => $payment)
            {
                
                if($payment->user){
                    $user_name = $payment->user->name;
                }else{
                    $user_name ='Not Found';
                }

                if($payment->provider){
                    $provider_name = $payment->provider->name;
                }else{
                    $provider_name ='Not Found';
                }

                
                if($payment->corporate_id !=0){
                    $payment_mode = 'CORPORATE';
                    $status ='-';
                }else{
                    $payment_mode = $payment->payment_mode;
                    if($payment->paid){
                        $status ='Paid';
                    }else{
                        $status ='Not Paid';
                    }
                }
                $nestedData['request_id'] = $payment->id;
                $nestedData['transaction_id'] =  $payment->payment->payment_id;
                $nestedData['from'] =  $user_name;
                $nestedData['to'] = $provider_name;
                $nestedData['total_amount'] = '<span class="text-info">'.currency_amt($payment->payment->total).'</span>';
                $nestedData['payment_mode'] = $payment_mode;
                $nestedData['payment_status'] = $status;
                $data[] = $nestedData;
                $start++;
            }
        }
        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data
                    );
            
        echo json_encode($json_data);     

    }

    /**
     * account statements.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function statement_provider(){

        try{

            $Providers = Provider::all();

            return view('admin.statement.provider-statement', compact('Providers'))->with('page','Driver Statement');

        } catch (Exception $e) {
            return back()->with('flash_error','Something Went Wrong!');
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function statement_providerlist(Request $request){

        $columns = array( 
                            0 =>'id', 
                            1 =>'provider_name',
                            2=> 'mobile',
                            3=> 'status',
                            4=> 'total_rides',
                            5=> 'total',
                            6=> 'joined_at',
                            7=> 'details',
                        );
        $Providerslist = Provider::all();
        foreach($Providerslist as $index => $Provider){
            $Rides = UserRequest::where('provider_id',$Provider->id)
                        ->orderBy('id','desc')
                        ->get()->pluck('id');

            $Providerslist[$index]->rides_count = $Rides->count();
            $Providerslist[$index]->payment = UserRequestPayment::whereIn('request_id', $Rides)
                            ->select(\DB::raw(
                               'SUM(revenue) as overall' ))->get();
        }

        $totalData = $Providerslist->count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $Providers = Provider::offset($start)
                     ->limit($limit)
                     ->orderBy('id','desc')
                     ->get();
            foreach($Providers as $index => $Provider){
                $Rides = UserRequest::where('provider_id',$Provider->id)
                            ->orderBy('id','desc')
                            ->get()->pluck('id');

                $Providers[$index]->rides_count = $Rides->count();
                $Providers[$index]->payment = UserRequestPayment::whereIn('request_id', $Rides)
                                ->select(\DB::raw(
                                   'SUM(revenue) as overall' ))->get();
            }
        }
        else {
            $search = $request->input('search.value'); 

            $Providers =  Provider::where('name','LIKE',"%{$search}%")
                            ->orWhere('mobile','LIKE',"%{$search}%")
                            ->orWhere('status','LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy('id','desc')
                            ->get();
                foreach($Providers as $index => $Provider){
                    $Rides = UserRequest::where('provider_id',$Provider->id)
                            ->orderBy('id','desc')
                            ->get()->pluck('id');

                    $Providers[$index]->rides_count = $Rides->count();
                    $Providers[$index]->payment = UserRequestPayment::whereIn('request_id', $Rides)
                                ->select(\DB::raw(
                                   'SUM(revenue) as overall' ))->get();
                }

            $totalFiltered = Provider::where('name','LIKE',"%{$search}%")
                            ->orWhere('mobile','LIKE',"%{$search}%")
                            ->orWhere('status','LIKE',"%{$search}%")
                            ->count();
        }

        $data = array();
        if(!empty($Providers))
        {
            foreach ($Providers as $index => $provider)
            {
                
                if($provider->name != ''){ $name = $provider->name;}else{$name = "-";}
                
                if($provider->account_status == "approved"){
                    $status = '<span class="label label-table label-success">'.$provider->account_status.'</span>';
                }elseif($provider->account_status == "banned"){
                    $status = '<span class="label label-table label-danger">'.$provider->account_status.'</span>';
                }else{
                    $status = '<span class="label label-table label-primary">'.$provider->account_status.'</span>';
                }

                if($provider->rides_count){
                    $rides = $provider->rides_count;
                }else{
                    $rides = '-';
                }

                if($provider->payment){
                    $total = currency_amt($provider->payment[0]->overall);
                }else{
                    $total = '-';
                }

                if($provider->created_at){
                    $joined_at = Carbon::parse($provider->created_at)->format('d-m-Y');
                }else{
                    $joined_at = '-';
                }
                $details = '<a href="'.route('admin.provider.statement', $provider->id).'"><div class="label label-table label-info">'.trans("admin.member.view").'</div></a>';
                $nestedData['id'] = $start + 1;
                $nestedData['provider_name'] = $name;
                $nestedData['mobile'] =  $provider->mobile;
                $nestedData['status'] =  $status;
                $nestedData['total_rides'] = $rides;
                $nestedData['total'] = $total;
                $nestedData['joined_at'] = $joined_at;
                $nestedData['details'] = $details;
                $data[] = $nestedData;
                $start++;
            }
        }
        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data
                    );
            
        echo json_encode($json_data);     

    }
    
    /**
     * account statements.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function statement_corporate(){

        try{
            $Corporates = Corporate::all();
            return view('admin.statement.corporate-statement', compact('Corporates'))->with('page','Corporate Statement');
        } catch (Exception $e) {
            return back()->with('flash_error','Something Went Wrong!');
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function statement_corporatelist(Request $request){

        $columns = array( 
                            0 =>'id', 
                            1 =>'corporate_name',
                            2=> 'mobile',
                            3=> 'status',
                            4=> 'total_rides',
                            5=> 'total',
                            6=> 'joined_at',
                            7=> 'details',
                        );

        $fromdate = '';
        $todate = Carbon::now();

        if($request->fromdate !=''){
            $fromdate = $request->fromdate;
        }
        if($request->todate !=''){
            $todate = Carbon::parse($request->todate)->addDay();
        }

        $Corporatelist = Corporate::all();
        foreach($Corporatelist as $index => $Corporate){
            $Rides = UserRequest::where('corporate_id',$Corporate->id)
                        ->where('created_at', '>=', $fromdate)
                        ->where('created_at', '<', $todate)
                        ->orderBy('id','desc')
                        ->get()->pluck('id');

            $Corporatelist[$index]->rides_count = $Rides->count();
            $Corporatelist[$index]->payment = UserRequestPayment::whereIn('request_id', $Rides)
                            ->select(\DB::raw(
                               'SUM(total) as overall' ))->get();
        }
        $ride_sum = 0;
        $payment_sum = 0;
        foreach($Corporatelist as $key=>$tb)
        {
            $ride_sum+= $tb->rides_count;
            $payment_sum+= $tb->payment[0]->overall;
        }
        $rides_count = $ride_sum;
        $total_revenue = $payment_sum;
        $totalData = $Corporatelist->count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $Corporates = Corporate::offset($start)
                     ->limit($limit)
                     ->orderBy('id','desc')
                     ->get();
            foreach($Corporates as $index => $Corporate){
                $Rides = UserRequest::where('corporate_id',$Corporate->id)
                            ->where('created_at', '>=', $fromdate)
                            ->where('created_at', '<', $todate)
                            ->orderBy('id','desc')
                            ->get()->pluck('id');

                $Corporates[$index]->rides_count = $Rides->count();
                $Corporates[$index]->payment = UserRequestPayment::whereIn('request_id', $Rides)
                                ->select(\DB::raw(
                                   'SUM(total) as overall' ))->get();
            }
        }
        else {
            $search = $request->input('search.value'); 

            $Corporates =  Corporate::where('display_name','LIKE',"%{$search}%")
                            ->orWhere('mobile','LIKE',"%{$search}%")
                            ->orWhere('status','LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy('id','desc')
                            ->get();
                foreach($Corporates as $index => $Corporate){
                    $Rides = UserRequest::where('corporate_id',$Corporate->id)
                            ->orderBy('id','desc')
                            ->get()->pluck('id');

                    $Corporates[$index]->rides_count = $Rides->count();
                    $Corporates[$index]->payment = UserRequestPayment::whereIn('request_id', $Rides)
                                ->select(\DB::raw(
                                   'SUM(total) as overall' ))->get();
                }

            $totalFiltered = Corporate::where('display_name','LIKE',"%{$search}%")
                            ->orWhere('mobile','LIKE',"%{$search}%")
                            ->orWhere('status','LIKE',"%{$search}%")
                            ->count();
        }

        $data = array();
        if(!empty($Corporates))
        {
            foreach ($Corporates as $index => $corporate)
            {
                
                if($corporate->display_name != ''){ $name = $corporate->display_name;}else{$name = "-";}
                
                if($corporate->status == 1){
                    $status = '<span class="label label-table label-success">Active</span>';
                }else{
                    $status = '<span class="label label-table label-success">Disabled</span>';
                }

                if($corporate->rides_count){
                    $rides = $corporate->rides_count;
                }else{
                    $rides = '-';
                }

                if($corporate->payment){
                    $total = currency_amt($corporate->payment[0]->overall);
                }else{
                    $total = '-';
                }

                if($corporate->created_at){
                    $joined_at = Carbon::parse($corporate->created_at)->format('d-m-Y');
                }else{
                    $joined_at = '-';
                }
                $details = '<a href="'.route('admin.corporate.statement', $corporate->id).'"><div class="label label-table label-info">'.trans("admin.member.view").'</div></a>';
                $nestedData['id'] = $start + 1;
                $nestedData['corporate_name'] = $name;
                $nestedData['mobile'] =  $corporate->mobile;
                $nestedData['status'] =  $status;
                $nestedData['total_rides'] = $rides;
                $nestedData['total'] = $total;
                $nestedData['joined_at'] = $joined_at;
                $nestedData['details'] = $details;
                $data[] = $nestedData;
                $start++;
            }
        }
        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data,
                    "ride_count"      => $rides_count,
                    "revenue"         => $total_revenue,
                    );
            
        echo json_encode($json_data);     

    }
    /**
     * account statements.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function statement_partner(){

        try{

            $Partners = Partner::all();

            return view('admin.statement.partner-statement', compact('Partners'))->with('page','Sub-company Statement');

        } catch (Exception $e) {
            return back()->with('flash_error','Something Went Wrong!');
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function statement_partnerlist(Request $request){

        $columns = array( 
                            0 =>'id', 
                            1 =>'partner_name',
                            2=> 'mobile',
                            3=> 'status',
                            4=> 'total_rides',
                            5=> 'total',
                            6=> 'joined_at',
                            7=> 'details',
                        );

        $fromdate = '';
        $todate = Carbon::now();

        if($request->fromdate !=''){
            $fromdate = $request->fromdate;
        }
        if($request->todate !=''){
            $todate = Carbon::parse($request->todate)->addDay();
        }

        $Partnerlist = Partner::all();
        foreach($Partnerlist as $index => $Partner){
            $Rides = UserRequest::where('partner_id',$Partner->id)
                        ->where('created_at', '>=', $fromdate)
                        ->where('created_at', '<', $todate)
                        ->orderBy('id','desc')
                        ->get()->pluck('id');

            $Partnerlist[$index]->rides_count = $Rides->count();
            $Partnerlist[$index]->payment = UserRequestPayment::whereIn('request_id', $Rides)
                            ->select(\DB::raw(
                               'SUM(total) as overall' ))->get();
        }
        $ride_sum = 0;
        $payment_sum = 0;
        foreach($Partnerlist as $key=>$tb)
        {
            $ride_sum+= $tb->rides_count;
            $payment_sum+= $tb->payment[0]->overall;
        }
        $rides_count = $ride_sum;
        $total_revenue = $payment_sum;
        $totalData = $Partnerlist->count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $Partners = Partner::offset($start)
                     ->limit($limit)
                     ->orderBy('id','desc')
                     ->get();
            foreach($Partners as $index => $Partner){
                $Rides = UserRequest::where('partner_id',$Partner->id)
                            ->where('created_at', '>=', $fromdate)
                            ->where('created_at', '<', $todate)
                            ->orderBy('id','desc')
                            ->get()->pluck('id');

                $Partners[$index]->rides_count = $Rides->count();
                $Partners[$index]->payment = UserRequestPayment::whereIn('request_id', $Rides)
                                ->select(\DB::raw(
                                   'SUM(total) as overall' ))->get();
            }

        }
        else {
            $search = $request->input('search.value'); 

            $Partners =  Partner::where('name','LIKE',"%{$search}%")
                            ->orWhere('mobile','LIKE',"%{$search}%")
                            ->orWhere('status','LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy('id','desc')
                            ->get();
                foreach($Partners as $index => $Partner){
                    $Rides = UserRequest::where('partner_id',$Partner->id)
                            ->orderBy('id','desc')
                            ->get()->pluck('id');

                    $Partners[$index]->rides_count = $Rides->count();
                    $Partners[$index]->payment = UserRequestPayment::whereIn('request_id', $Rides)
                                ->select(\DB::raw(
                                   'SUM(total) as overall' ))->get();
                }

            $totalFiltered = Partner::where('name','LIKE',"%{$search}%")
                            ->orWhere('mobile','LIKE',"%{$search}%")
                            ->orWhere('status','LIKE',"%{$search}%")
                            ->count();
        }

        $data = array();
        if(!empty($Partners))
        {
            foreach ($Partners as $index => $partner)
            {
                
                if($partner->name != ''){ $name = $partner->name;}else{$name = "-";}
                
                if($partner->status == 1){
                    $status = '<span class="label label-table label-success">Active</span>';
                }else{
                    $status = '<span class="label label-table label-success">Disabled</span>';
                }

                if($partner->rides_count){
                    $rides = $partner->rides_count;
                }else{
                    $rides = '-';
                }

                if($partner->payment){
                    $total = currency_amt($partner->payment[0]->overall);
                }else{
                    $total = '-';
                }

                if($partner->created_at){
                    $joined_at = Carbon::parse($partner->created_at)->format('d-m-Y');
                }else{
                    $joined_at = '-';
                }
                $details = '<a href="'.route('admin.partner.statement', $partner->id).'"><div class="label label-table label-info">'.trans("admin.member.view").'</div></a>';
                $nestedData['id'] = $start + 1;
                $nestedData['partner_name'] = $name;
                $nestedData['mobile'] =  $partner->mobile;
                $nestedData['status'] =  $status;
                $nestedData['total_rides'] = $rides;
                $nestedData['total'] = $total;
                $nestedData['joined_at'] = $joined_at;
                $nestedData['details'] = $details;
                $data[] = $nestedData;
                $start++;
            }
        }
        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data,
                    "ride_count"      => $rides_count,
                    "revenue"         => $total_revenue,
                    );
            
        echo json_encode($json_data);     

    }
    public function test(){
        $current = Carbon::now()->toTimeString();
        /*$restrict_pickup = RestrictLocation::whereIn('restrict_area',[1,2])->where('status','=',1)->get();
        foreach($restrict_pickup as $res_pickup){
            if($current > $res_pickup->s_time && $current < $res_pickup->e_time){
                $location = Location::where('id','=',$res_pickup->location_id)->select('tlatitude','tlongitude','location_name')->first();
                if($location !=null){
                    $vertices_y = array_filter(explode(',', $location->tlatitude));
                    $vertices_x = array_filter(explode(',', $location->tlongitude));
                    $points_polygon = count($vertices_x);
                    $latitude_y = 11.341036;
                    $longitude_x = 11.341036;
                    if(Helper::is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)){
                        return response()->json(['error' => trans('Pickup Location Zone Restricted')], 500);
                    }
                }
            }
        }*/
        $restrict_drop = RestrictLocation::whereIn('restrict_area',[1,3])->where('status','=',1)->get();
        foreach($restrict_drop as $res_drop){
            if($current > $res_drop->s_time && $current < $res_drop->e_time){
                $location = Location::where('id','=',$res_drop->location_id)->select('tlatitude','tlongitude','location_name')->first();
                if($location !=null){
                    $vertices_y = array_filter(explode(',', $location->tlatitude));
                    $vertices_x = array_filter(explode(',', $location->tlongitude));
                    $points_polygon = count($vertices_x);
                    $latitude_y = 11.341036;
                    $longitude_x = 77.717163;
                    if(Helper::is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)){
                        return response()->json(['error' => trans('Destination Zone Restricted')], 500);
                    }
                }
            }
        }
    }

    public function business()
    { 
        if(Auth::guard('admin')->user()->admin_type != 0){

            $demo = Demo::where('email','=',Auth::guard('admin')->user()->email)->first();
            $country = Country::where('countryid','=',$demo->country_id)->first();
            $country_code = $country->dial_code;
            $address = Auth::guard('admin')->user()->admin_address;
            $lat = Auth::guard('admin')->user()->admin_lat;
            $long = Auth::guard('admin')->user()->admin_long;
            $zoom = Auth::guard('admin')->user()->admin_zoom;
            $country = "";
            $state = "";
            $city = "";
        } else {
            $country_code = Setting::get('country_code', '');
            $address = Setting::get('address', '');
            $lat = Setting::get('address_lat', '');
            $long = Setting::get('address_long', '');
            $country = Setting::get('country', '');
            $state = Setting::get('state', '');
            $city = Setting::get('city', '');
            $zoom = Setting::get('zoom', '');
        }

       $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        return view('admin.settings.business',compact('country_code','address','lat','long','country','state','city','zoom','tzlist'));
    }

    public function refferal()
    {   
        $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        return view('admin.settings.referel', compact('tzlist'));
        //return view('admin.settings.referel');
    }

    public function fcm(){
        try{
             $push_message = "Hi test msg";
             $token = "fJig39NXn4s:APA91bEDz5aikzO6rIUItdNXEfcOjW06x16LI_-c7jdCyhv8-AxomGsicDMjdwnu3nUWuotGy6NRT4dD-To-Ls9YXIT6JKs_PA8HJF0Ece3cJwqnutYNWrPTR1zzw5n640SM_1UCN2WW";
        

           

                    $msg = PushNotification::setService('fcm')
                        ->setMessage(['notification' => [
                                     'title'=>'FrescoFud',
                                     'body'=>$push_message,
                                     'sound' => 'default'
                                     ],
                             'data' => [
                                     'title'=>'FrescoFud',
                                     'body'=>$push_message,
                                     ]
                             ])
                        ->setDevicesToken($token)
                        ->send();

                
                dd($msg);
            

        } catch(Exception $e){
            dd($e->getMessage());
        }
    }
    public function cms_settings_store(Request $request)
    {
        Setting::set('stop_title', $request->stop_title ? : "Please keep stops to 3 minutes or less");
        Setting::set('stop_description', $request->stop_description ? : "As a courtesy for your drivers time,Please limit each stop to 3 minutes or less, otherwise your fare may change");
        Setting::set('payment_description', $request->payment_description ? : "Total fare may change due in case of any route or destination changes of if your ride takes longer due to traffic or other factors");
	    Setting::save();
        
        return back()->with('flash_success','Settings Updated Successfully');

    }

}
