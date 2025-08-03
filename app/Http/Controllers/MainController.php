<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use DB;
use Log;
use Setting;
use Session;
use Auth;
use Exception;
use Notification;
use Carbon\Carbon;
use App\Helpers\Helper;
use Mail;
use App\Models\Page;
use App\Models\User;
use App\Models\Country;
use App\Models\ServiceType;
use App\Models\FareModel;
use App\Models\Promocode;
use App\Models\PromocodeUsage;
use App\Models\UserRequest;
use App\Models\Provider;
use App\Models\UserRequestRating;
use App\Models\UserRequestPayment;
use App\Models\GpsHistory;
use App\Models\Corporate;
use App\Models\RequestFilter;
use App\Models\ProviderWallet;

use App\Http\Controllers\SendPushNotification;
use App\Http\Controllers\ProviderResources\TripController;

class MainController extends Controller
{

    /**
     * Contact Email.
     *
     * @return \Illuminate\Http\Response
     */
    public function contactprocess(Request $request)
    {
       
        try{

            $user = $request->all();
            // send welcome email here
            if(Setting::get('mail_enable', 0) == 1) {
                Mail::send('emails.contact', ['user' => $user], function ($message) use ($user){
                    $message->to(config('app.email'), config('app.name'))->subject(config('app.name').' - Contact Form Inquiry');
                });
            }
            return back()->with('flash_success','Your message sent. Thanks for contacting.');

        } 

        catch (Exception $e) {
            return back()->with('flash_error', 'Sorry, something went wrong. try again later.');
        }  
    }

    public function changelang(Request $request)
    {
        $this->validate($request, [
                'language' => 'in:en,fr,de,es',
            ]);

            $language = $request->language;
            Session::put('language',$language);
            app()->setLocale($language);
            return back();
    }
    public function privacy(Request $request)
    {
        $page = Page::where('unique_title','privacy')->first();
        return view('web.privacy',compact('page'));

    }
    public function terms(Request $request)
    {
        $page = Page::where('unique_title','terms')->first();
        return view('web.terms',compact('page'));
    }
    public function faq(Request $request)
    {
        $page = Page::where('unique_title','terms')->first();
        return view('page',compact('page'));
    }
     // book a taxi
     public function book_taxi()
     {
         $countries = Country::all();
         $services = ServiceType::all();
         return view('web.book_taxi',compact('services','countries'));
     }

     public function book_taxi_sp()
     {
         $countries = Country::all();
         $services = ServiceType::all();
         return view('web_sp.book_taxi',compact('services','countries'));
     }
    /**
     * Dispatcher Panel.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        //dd(Auth::guard('admin')->user());
        if(Auth::guard('admin')->user()){

            // $tz = carbon::now()->tz('cst');
            // $tz =  date('d-m-y h:i:A',strtotime($tz));

            // dd($tz);
            $services = ServiceType::get();
            return view('admin.main.index',compact('services'));
        }elseif(Auth::guard('dispatcher')->user()){
            $services = ServiceType::get();
            return view('dispatcher.main.index',compact('services'));
        }
            elseif(Auth::guard('corporate')->user()){
            $services = ServiceType::get();
            return view('corporate.main.index',compact('services'));
        }   
     }
    /**
     * Dispatcher Panel.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function schedule()
    {
        if(Auth::guard('admin')->user()){
            $services = ServiceType::get();
            return view('admin.main.schedule',compact('services'));
        }elseif(Auth::guard('dispatcher')->user()){
            $services = ServiceType::get();
            return view('dispatcher.main.schedule',compact('services'));
        }
        elseif(Auth::guard('corporate')->user()){
            $services = ServiceType::get();
            return view('corporate.main.schedule',compact('services'));
        }    
    }
    public function listall(Request $request)
    {


        $tripstatus = $request->tripstatus;
        $servicetype = $request->servicetype;
        $booking_by = $request->booking_by;
        $fromdate = Carbon::today();
        $todate = Carbon::now();

        if($request->fromdate !=''){
            $fromdate = $request->fromdate;
        }
        if($request->todate !=''){
            $todate = Carbon::parse($request->todate)->addDay();
        }
        if($request->upcoming_trip !=''){
            $upcoming_to=Carbon::now()->addMinutes($request->upcoming_trip);   
       		$upcoming_to=$upcoming_to->toDateTimeString();
          }
         else{
			$upcoming_to=" ";
         }

        if($request->upcoming_trip){
            $trips = UserRequest::with('user', 'provider', 'service_type', 'payment','currentprovider')
            ->where('status', 'LIKE', '%'.$tripstatus.'%')
            ->where('service_type_id', 'LIKE', '%'.$servicetype.'%')
            ->where('booking_by', 'LIKE', '%'.$booking_by.'%')
            ->where('schedule_at', '<', $upcoming_to)
            ->where('assigned_at', '>=', $fromdate)
            ->where('assigned_at', '<', $todate)
            ->orderBy('assigned_at','desc')->get();
            }
        else{
            $trips = UserRequest::with('user', 'provider', 'service_type', 'payment','currentprovider')
            ->where('status', 'LIKE', '%'.$tripstatus.'%')
            ->where('service_type_id', 'LIKE', '%'.$servicetype.'%')
            ->where('booking_by', 'LIKE', '%'.$booking_by.'%')
            ->where('assigned_at', '>=', $fromdate)
            ->select('id','booking_id','assigned_at','schedule_at','started_at','finished_at','user_name','user_mobile','provider_id','service_type_id','s_address','d_address','distance','estimated_fare','fare_type','booking_by','cancelled_by','cancel_reason','status','user_id','current_provider_id')
            // ->where('assigned_at', '<', $todate)
            ->orderBy('assigned_at','desc')->get();
			// if($request->upcoming_trip !=''){
        }
            /*dd($trips);*/
            $diskm =setting::get('distance_unit');
            // return view('admin.main.show', compact('trips', 'diskm'));
            // return $trips;
        if(Auth::guard('admin')->user()){
            return view('admin.main.show', compact('trips', 'diskm'));
        }elseif(Auth::guard('dispatcher')->user()){
            return view('dispatcher.main.show', compact('trips', 'diskm'));
        }
        elseif(Auth::guard('corporate')->user()){
            $trips = UserRequest::where('corporate_id',Auth::guard('corporate')->user()->id)->with('user', 'provider', 'service_type', 'payment','currentprovider')
            ->where('status', 'LIKE', '%'.$tripstatus.'%')
            ->where('service_type_id', 'LIKE', '%'.$servicetype.'%')
            ->where('booking_by', 'LIKE', '%'.$booking_by.'%')
            ->where('assigned_at', '>=', $fromdate)
            ->where('assigned_at', '<', $todate)
            ->orderBy('assigned_at','desc')->get();
            return view('corporate.main.show', compact('trips', 'diskm'));
        }  
  }

    public function schedule_listall(Request $request)
    {
        $tripstatus = $request->tripstatus;
        $servicetype = $request->servicetype;
        $booking_by = $request->booking_by;
        $fromdate = '';
        $todate = Carbon::now();
        
        if($request->fromdate !=''){
            $fromdate = $request->fromdate;
        }
        if($request->todate !=''){
            $todate = Carbon::parse($request->todate)->addDay();
        }
        
            $trips = UserRequest::with('user', 'provider', 'service_type', 'payment')
            ->whereIn('status',['SCHEDULED','ACCEPTED'])
            ->where('service_type_id', 'LIKE', '%'.$servicetype.'%')
            ->where('booking_by', 'LIKE', '%'.$booking_by.'%')
            ->where('schedule_at', '!=', '')
            ->orderBy('schedule_at','asc')->get();
            /*dd($trips);*/
            $diskm =setting::get('distance_unit');
        if(Auth::guard('admin')->user()){
            return view('admin.main.show', compact('trips', 'diskm'));
        }elseif(Auth::guard('dispatcher')->user()){
            return view('dispatcher.main.show', compact('trips', 'diskm'));
        }
        elseif(Auth::guard('corporate')->user()){
            return view('corporate.main.show', compact('trips', 'diskm'));
        }
    }

    public function showdetail(Request $request)
    {
       
        if($request->has('id')){
            $id = $request->id;
            $request = UserRequest::with('user','provider','vehicle','service_type','payment')->findOrFail($id);
            $request->assigned_at1=date("Y-m-d h:i A", strtotime($request->assigned_at));
            $request->finished_at1=date("Y-m-d h:i A", strtotime($request->finished_at));
            return $request;
        }
    }
    
    public function editdetail($request_id)
    {
        $request = UserRequest::with('user','provider', 'service_type','payment')->findOrFail($request_id);
        $services = ServiceType::get();
        if(Auth::guard('admin')->user()){
            return view('admin.main.modal.edit', compact('request', 'services'));
        }elseif(Auth::guard('dispatcher')->user()){
            return view('dispatcher.main.modal.edit', compact('request', 'services'));
        }
        elseif(Auth::guard('corporate')->user()){
            return view('corporate.main.modal.edit', compact('request', 'services'));
        }
    }
    public function storedetail(Request $request, $id)
    {
           $this->validate($request, [
            's_address' => 'required|max:255',
            'd_address' => 'required|max:255',
            'service_type_id' => 'required',
            ]);

           try {

            $trip = UserRequest::findOrFail($id);
            $status = $request->status;
            if($status !='SEARCHING' && $status !='SCHEDULED' && $status !='CANCELLED'){
                    if($request->has('s_longitude')) {
                        $details = "https://maps.googleapis.com/maps/api/directions/json?origin=".$request->s_latitude.",".$request->s_longitude."&destination=".$request->d_latitude.",".$request->d_longitude."&mode=driving&key=".Setting::get('map_key');

                    $json = curl($details);

                    $details = json_decode($json, TRUE);

                    $route_key = $details['routes'][0]['overview_polyline']['points'];
                    $meter = $details['routes'][0]['legs'][0]['distance']['value'];
                    $unit =Setting::get('distance_unit');
                    
                    if($unit =='km'){
                        $kilometer = $meter/1000;
                    }else{
                        $base = $meter/1000;
                        $kilometer = $base * 0.62137119;
                    }

                    if($request->filled('schedule_at')) {
                        $trip->schedule_at = Carbon::parse($request->schedule_at);
                        $trip->push = 'AUTO';
                        if($trip->provider_id !=0){
                            (new SendPushNotification)->ScheduleTime($trip->provider_id, $trip->booking_id); 
                        }
                    }

                    $trip->distance = $kilometer;
                    $trip->s_latitude = $request->s_latitude;
                    $trip->s_longitude = $request->s_longitude;
                    $trip->d_latitude = $request->d_latitude;
                    $trip->d_longitude = $request->d_longitude;
                    $trip->route_key = $route_key;
                    $trip->s_address = $request->s_address;
                    $trip->d_address = $request->d_address;
                    $trip->save();
                    app(\App\Http\Controllers\UserResources\UserTripApiController::class)->notify_driver($trip->id);
                    return back()->with('flash_success', 'Updated details');    
                    }
            }
            if($request->has('s_longitude')) {
                $details = "https://maps.googleapis.com/maps/api/directions/json?origin=".$request->s_latitude.",".$request->s_longitude."&destination=".$request->d_latitude.",".$request->d_longitude."&mode=driving&key=".Setting::get('map_key');

            $json = curl($details);

            $details = json_decode($json, TRUE);

            $route_key = $details['routes'][0]['overview_polyline']['points'];
            $meter = $details['routes'][0]['legs'][0]['distance']['value'];
            $unit =Setting::get('distance_unit');
            
            if($unit =='km'){
                $kilometer = $meter/1000;
            }else{
                $base = $meter/1000;
                $kilometer = $base * 0.62137119;
            }

            $trip->distance = $kilometer;
            $trip->s_latitude = $request->s_latitude;
            $trip->s_longitude = $request->s_longitude;
            $trip->d_latitude = $request->d_latitude;
            $trip->d_longitude = $request->d_longitude;
            $trip->route_key = $route_key;
            }
            
            
            $trip->s_address = $request->s_address;
            $trip->d_address = $request->d_address;
            $trip->service_type_id = $request->service_type_id;
            $trip->provider_id = 0;
            $trip->current_provider_id = 0;
            $trip->vehicle_id = 0;
            $trip->cancelled_by ="NONE";
            $trip->cancel_reason ="";
            $trip->booking_by ="DISPATCHER";
            $checkstatus ='';
            $trip->assigned_at = Carbon::now()->tz('cst');
            if($request->has('schedule_at') && $request->schedule_at !='') {
                $trip->schedule_at = Carbon::parse($request->schedule_at);
                $trip->status ="SCHEDULED";
                $trip->push = 'AUTO';
            }else{
                $trip->schedule_at = Null;
                $trip->status ="SEARCHING";
                $trip->push = Null;
                $checkstatus = "SEARCHING";
            }
            
            $trip->user_rated =0;
            $trip->provider_rated =0;
            $trip->save();
            app(\App\Http\Controllers\UserResources\UserTripApiController::class)->notify_driver($trip->id);
            return back()->with('flash_success', 'Updated details');  
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Something Went Wrong');
        }
        
    }
    public function canceldetail(Request $request, $id)
    {
        try {
            $trip = UserRequest::findOrFail($id);
            $trip->status ="CANCELLED";
            $trip->cancelled_by ="DISPATCHER";
            $trip->cancel_reason='DISPATCHER';
            $trip->push = Null;
            $trip->save();
            Provider::where('id',$trip->current_provider_id)->update(['trip_id' => 0,'status' => 'active', 'active_from' =>Carbon::now()]);
            // User::where('id',$trip->user_id)->update(['trip_id' => 0]);

            return back()->with('flash_success', 'Trip Canceled Successfully');  
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Something Went Wrong');
        }
        
    }
    public function completedetail(Request $request)
    {
        try {

            // dd($request->all()); die;
            $trip = UserRequest::findOrFail($request->compltetrip);
          
         
            $fare_base =  (float)$request->final_fare;
          
          
            $commission_enable = Setting::get('commission_enable', 0);
          
            $commission_percentage = Setting::get('commission_percentage', 0);
        
          //  dd($commission_enable); die;
          
           if($commission_enable == '1'){
                $total_amount = $fare_base;
                $Commision = $total_amount * $commission_percentage/100;
                $earnings=$total_amount-$Commision;
                $revenue=$total_amount-$Commision;
             
             // dd($Commision); die;
             
            }
            else{
                $total_amount = $fare_base;
                $Commision = 0;
                $earnings=$total_amount;
                $revenue=$total_amount;
            }
          
        // dd($total_amount); die;
          
            $Payment = new UserRequestPayment;
            $Payment->request_id = $trip->id;
            $Payment->currency = Setting::get('currency');
            $Payment->base_fare = 0;
            $Payment->flat_fare = $fare_base;
            $Payment->distance_fare = $fare_base;
            $Payment->commision = $Commision;
            $Payment->earnings = $earnings;
            $Payment->revenue = $revenue;
            $Payment->min_fare = 0;
            $Payment->waiting_fare = 0;
            $Payment->stop_waiting_fare = 0;
            $Payment->vat = 0;
            $Payment->payment_mode = $trip->payment_mode;
            $Payment->discount = 0;
            $Payment->tip_fare = 0;
            $Payment->cash = $fare_base;
            $Payment->total = $fare_base;
            $Payment->save();
          
            
            $trip->estimated_fare =abs($fare_base);
            $trip->status ="COMPLETED";
            $trip->paid = 1; 
            $trip->completed_by = 'Admin';
          //  $trip->cancelled_by ="DISPATCHER";
            $trip->finished_at = Carbon::now();
          
            $trip->save();
          
          
           $wallet = Provider::find($trip->provider_id);
         
        if($Payment->payment_mode == "CASH")
        {
            $wallet->wallet_balance = $wallet->wallet_balance - $Payment->commision;
            $wallet->total_earnings +=  $earnings;
            $wallet->cash_earnings +=  $earnings;
            $wallet->con_earnings +=   $Payment->total;
          
        //  dd($wallet); die;
          
            $wallet->save();
        }else{
            $wallet->wallet_balance +=  $earnings;
            $wallet->total_earnings +=  $earnings;
            $wallet->card_earnings +=  $earnings;
            $wallet->con_earnings +=   $Payment->total;
            $wallet->save();

        }
        ProviderWallet::create([
        'provider_id' => $trip->provider_id,
        'trip_id' =>$trip->id,
        'amount' => $Payment->total,
        'mode' => 'Added by Trips',
        'status' => 'Credited',
        ]);
          
          
           $UserRequest = UserRequest::with('user','service_type','payment')->findOrFail($request->compltetrip);
          
                      if($UserRequest->payment_mode == 'CASH'){
                $UserRequest->paid = 1;
            }
            if($UserRequest->payment_mode == 'CARD'){
               app(\App\Http\Controllers\PaymentController::class)->trip_payment_admin($trip->id,$UserRequest->user->id);
            }

            if($UserRequest->payment_mode == 'WALLET'){
                $User = User::find($UserRequest->user_id);
                $Wallet = $User->wallet_balance;
                $Total =$UserRequest->payment->total;
                $WalletBalance = $Wallet-$Total;
                User::where('id',$UserRequest->user_id)->update(['wallet_balance' => $WalletBalance]);
            }
          
           $UserRequest->save();

          // dd($trip); die;
           // Provider::where('id',$trip->current_provider_id)->update(['trip_id' => 0,'status' => 'active', 'active_from' =>Carbon::now()]);
            //User::where('id',$trip->user_id)->update(['trip_id' => 0]);

            return back()->with('flash_success', 'Trip Completed Successfully');  
        }  

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Something Went Wrong');
        }
        
    }
    public function autotrip($request_id)
    {
        $trip = UserRequest::findOrFail($request_id);
        $trip->provider_id = 0;
        $trip->current_provider_id = 0;
        $trip->vehicle_id = 0;
        $trip->cancelled_by ="NONE";
        $trip->cancel_reason ="";
        $trip->booking_by ="DISPATCHER";
        $trip->paid =0;
        $trip->assigned_at = Carbon::now();
        $trip->push = 'AUTO';
        $trip->schedule_at = Null;
        $trip->status ="SEARCHING";
        $trip->save();
        app(\App\Http\Controllers\UserResources\UserTripApiController::class)->notify_driver($trip->id);
    }

    /**
     * Display a listing of the active trips in the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function providers(Request $request)
    {
        $admin_id=null;
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

        if($admin_id){
        $Providers = Provider::
        //where('service_type_id','=',$request->service_type)
              where('admin_id',$admin_id)
            ->where('account_status', 'approved')
            ->where('status', 'active')
            ->with('service')
            ->paginate(50);

        }
        else{
        $Providers = Provider::
        //where('service_type_id','=',$request->service_type)
            where('account_status', 'approved')
            ->where('status', 'active')
            ->with('service')
            ->paginate(50);
        }
        return $Providers;
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

            if($Request->booking_by != 'WEB')
            {
                if ($Request->user_id != 0) {
                    $user = User::findOrFail($Request->user_id);
                    $user->trip_id = $request_id;
                    $user->save();
                }
              
            }

            $Request->assigned_at = Carbon::now();
            $Request->status ="SEARCHING";
            $Request->vehicle_id = $Provider->mapping_id;
            $Request->provider_id = 0;
            $Request->partner_id = $Provider->partner_id ? : 0;
            $Request->current_provider_id = $Provider->id;
            $Request->assigned_at = Carbon::now();
            $Request->cancel_reason =null;
            $Request->cancelled_by = 'NONE';
            $Request->save();

            $Filter = new RequestFilter;
            $Filter->request_id = $request_id;
            $Filter->provider_id =$provider_id; 
            $Filter->save();

            (new SendPushNotification)->AssignedTrip($provider_id);
            if(Auth::guard('admin')->user()){
                return redirect()->back()
                        ->with('flash_success', 'Request Assigned to Provider!');

            }elseif(Auth::guard('dispatcher')->user()){
                return redirect()->back()
                        ->with('flash_success', 'Request Assigned to Provider!');
            }
            elseif(Auth::guard('corporate')->user()){
                return redirect()->back()
                        ->with('flash_success', 'Request Assigned to Provider!');
            }
        } catch (Exception $e) {
            if(Auth::guard('admin')->user()){
                   return $e;
                return redirect()->back()->with('flash_error', 'Something Went Wrong!');
            }elseif(Auth::guard('dispatcher')->user()){
                return redirect()->route('dispatcher.index')->with('flash_error', 'Something Went Wrong!');
            }
            elseif(Auth::guard('corporate')->user()){
                return redirect()->route('corporate.index')->with('flash_error', 'Something Went Wrong!');
            }
        }
    }

    public function storecomment(Request $request)
    {
        $this->validate($request, [
            'request_id' => 'required|max:255',
        ]);
        try {
            $trip = UserRequest::where('id','=',$request->request_id)->first();
            $trip->comment = $request->comment ? : '';
            $trip->save();
        }catch (Exception $e ){
            return $e;
        }  
    }
    
    public function routedetail($request_id)
    {
        $request = UserRequest::findOrFail($request_id);
        $data =GpsHistory::where('provider_id','=',$request->provider_id)
                   ->where('created_at', '>=', $request->assigned_at)
                   ->where('created_at', '<=', $request->updated_at)
                   ->get();
        $data = json_encode($data);
        if(Auth::guard('admin')->user()){
            return view('admin.main.modal.routes', compact('data'));
        }elseif(Auth::guard('dispatcher')->user()){
            return view('dispatcher.main.modal.routes', compact('data'));
        }
        elseif(Auth::guard('corporate')->user()){
            return view('corporate.main.modal.routes', compact('data'));
        }   
     }
}
