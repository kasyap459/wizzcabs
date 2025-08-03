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
use App\Models\RequestFilter;

use App\Http\Controllers\SendPushNotification;
use App\Http\Controllers\ProviderResources\TripController;

class PartnermainController extends Controller
{

    /**
     * Dispatcher Panel.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = ServiceType::get();
        return view('partner.main.index',compact('services'));
    }
    /**
     * Dispatcher Panel.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function schedule()
    {
        $services = ServiceType::get();
        return view('partner.main.schedule',compact('services'));
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
            $trips = UserRequest::with('user', 'provider', 'service_type', 'payment')
            ->where('partner_id',Auth::user()->id)
            ->where('status', 'LIKE', '%'.$tripstatus.'%')
            ->where('service_type_id', 'LIKE', '%'.$servicetype.'%')
            ->where('booking_by', 'LIKE', '%'.$booking_by.'%')
            ->where('schedule_at', '<', $upcoming_to)
            ->where('assigned_at', '>=', $fromdate)
            ->where('assigned_at', '<', $todate)
            ->orderBy('assigned_at','desc')->get();
            }
    else{
        $trips = UserRequest::with('user', 'provider', 'service_type', 'payment')
            ->where('partner_id',Auth::user()->id)
            ->where('status', 'LIKE', '%'.$tripstatus.'%')
            ->where('service_type_id', 'LIKE', '%'.$servicetype.'%')
            ->where('booking_by', 'LIKE', '%'.$booking_by.'%')
            ->where('assigned_at', '>=', $fromdate)
            ->select('id','booking_id','assigned_at','schedule_at','started_at','finished_at','user_name','user_mobile','provider_id','service_type_id','s_address','d_address','distance','estimated_fare','fare_type','booking_by','cancelled_by','cancel_reason','status')
            ->orderBy('assigned_at','desc')->get();
        }
        /*dd($trips);*/
        $diskm =setting::get('distance_unit');
        
        return view('partner.main.show', compact('trips', 'diskm'));
        
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
        
        return view('partner.main.modal.edit', compact('request', 'services'));
        
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
                    app(\App\Http\Controllers\UserResources\UserApiController::class)->notify_driver($trip->id);
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
            $trip->cancelled_by ="NONE";
            $trip->cancel_reason ="";
            $trip->booking_by ="DISPATCHER";
            $checkstatus ='';
            $trip->assigned_at = Carbon::now();
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
            app(\App\Http\Controllers\UserResources\UserApiController::class)->notify_driver($trip->id);
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
            $trip->push = Null;
            $trip->save();

            return back()->with('flash_success', 'Trip Canceled Successfully');  
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Something Went Wrong');
        }
        
    }

    public function autotrip($request_id)
    {
        $trip = UserRequest::findOrFail($request_id);
        $trip->provider_id = 0;
        $trip->cancelled_by ="NONE";
        $trip->cancel_reason ="";
        $trip->booking_by ="DISPATCHER";
        $trip->paid =0;
        $trip->assigned_at = Carbon::now();
        $trip->push = Null;
        $trip->schedule_at = Null;
        $trip->status ="SEARCHING";
        $trip->save();
        app(\App\Http\Controllers\UserResources\UserApiController::class)->notify_driver($trip->id);
    }

    /**
     * Display a listing of the active trips in the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function providers(Request $request)
    {
        $Providers = Provider::where('partner_id','=', Auth::user()->id)
            ->where('service_type_id','=',$request->service_type)
            ->where('account_status', 'approved')
            ->where('status', 'active')
            ->with('service')
            ->paginate(50);

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
            return redirect()->back()->with('flash_success', 'Request Assigned to Provider!');
        } catch (Exception $e) {
            return redirect()->back()->with('flash_error', 'Something Went Wrong!');
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
   
}
