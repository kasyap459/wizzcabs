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

use App\Models\ProviderCashout;


use App\Http\Controllers\SendPushNotification;
use App\Http\Controllers\ProviderResources\TripController;

class CashoutController extends Controller
{

    /**
     * Contact Email.
     *
     * @return \Illuminate\Http\Response
     */

    public function listall(Request $request)
    {
        $cashouttatus = $request->cashouttatus;
        $fromdate = Carbon::today();
        $todate = Carbon::now();

        if($request->fromdate !=''){
            $fromdate = $request->fromdate;
        }
        if($request->todate !=''){
            $todate = Carbon::parse($request->todate)->addDay();
        }
       

       
            $datas = ProviderCashout::with('provider',)
            ->where('status', 'LIKE', '%'.$cashouttatus.'%')
            ->where('created_at', '>=', $fromdate)
            ->where('created_at', '<', $todate)
            ->orderBy('created_at','desc')->get();

           // return $datas;
	
            return view('admin.cashout.show', compact('datas'));
      
  }

 



    public function reject_cashout(Request $request, $id)
    {
        try {
            $cashout = ProviderCashout::findOrFail($id);
            $cashout->status ="REJECTED";
            $cashout->save();

            //  $provider = Provider::findOrFail($cashout->provider_id);
            //  $provider_wallet = $provider->wallet_balance + $cashout->amount;
            //  $provider->wallet_balance = $provider_wallet;
            //  $provider->save(); 
             

           
            return back()->with('flash_success', 'Cashout Rejected');  
        } 

        catch (ModelNotFoundException $e) { 
            return back()->with('flash_error', 'Something Went Wrong');
        }
        
    }

    
    public function approve_cashout(Request $request, $id)
    {
        try {
            $cashout = ProviderCashout::findOrFail($id);
            $cashout->status ="APPROVED";
            $cashout->save();

            $provider = Provider::findOrFail($cashout->provider_id);
            $provider->wallet_balance = $provider->wallet_balance - $cashout->amount;
            $provider->save();
           
            return back()->with('flash_success', 'Cashout Approved');  
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Something Went Wrong');
        }
        
    }

    public function providerlist()
    {
        $providers = Provider::get();
        return view('admin.cashout.provider.index', compact('providers'));

    }

    public function provider_row(Request $request)
    {
        
        $columns = array( 
            0 =>'id', 
            1 =>'full_name',
            2=> 'email',
            3=> 'mobile',
            4=> 'current_earnings',
            5=> 'view',
            6=>'action'
        );

            $AllProviders = Provider::with('service','totalrequest','accepted','cancelled');

            $providerslist = $AllProviders;


            $totalData = $providerslist->count();
            $totalFiltered = $totalData; 

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            if(empty($request->input('search.value')))
            {            
            $providers = $providerslist->offset($start);
            if(Auth::guard('admin')->user()->admin_type != 0){
            $providers = $providers->where('admin_id','=', Auth::guard('admin')->user()->id);
                    }
                 $providers = $providers->limit($limit)
                 ->orderBy('id','desc')
                 ->get();
            }
            else {
            $search = $request->input('search.value'); 

            $providers =  $providerslist->where(function($q) use ($search) {
                          $q->where('name','LIKE',"%{$search}%")
                        ->orWhere('email', 'LIKE',"%{$search}%")
                        ->orWhere('mobile', 'LIKE',"%{$search}%");
                      })
                        ->offset($start);
                    if(Auth::guard('admin')->user()->admin_type != 0){
            $providers = $providers->where('admin_id', Auth::guard('admin')->user()->id);
                    }
                $providers = $providers->limit($limit)
                        ->orderBy('id','desc')
                        ->get();

            $totalFiltered = $providerslist->where(function($q) use ($search) {
                          $q->where('name','LIKE',"%{$search}%")
                        ->orWhere('email', 'LIKE',"%{$search}%")
                        ->orWhere('mobile', 'LIKE',"%{$search}%");
                      });
                    if(Auth::guard('admin')->user()->admin_type != 0){
            $totalFiltered = $totalFiltered->where('admin_id','=', Auth::guard('admin')->user()->id);
                }
                        $totalFiltered = $totalFiltered->count();
            }

            $data = array();
            if(!empty($providers))
            {
            foreach ($providers as $index => $provider)
            {
            if($provider->name != ''){ 
            // $first_name = '<a href="'.route('admin.provider.shift', $provider->id ).'">'.$provider->first_name.'</a>';
            $first_name = $provider->name;
            }else{$first_name = "";}
            if($provider->email != ''){ $email =$provider->email;}else{$email = "";}
            if($provider->mobile != ''){ $mobile = $provider->mobile;}else{$mobile = "";}
            if($provider->wallet_balance != ''){ $wallet_balance = $provider->wallet_balance;}else{$wallet_balance = "";}

            $button ='<a  href="'.route('admin.provider.details', $provider->id ).'"><i class="fa fa-list"></i> </a>';
            $action ='<a  href="#" data-id="'.$provider->id.'" id="resetdriver"><i class="fa fa-edit"></i> </a>';
            

            $nestedData['id'] = $start + 1;
            $nestedData['full_name'] = $first_name;
            $nestedData['email'] =  $email;
            $nestedData['mobile'] = $mobile;
            $nestedData['wallet_balance'] = '$' .$provider->wallet_balance;
            $nestedData['view'] = $button;
            $nestedData['action'] = $action;

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

    public function provider_details($id)
    {
        $provider = UserRequest::where('provider_id',$id)->with('payment','provider')->get();
       // dd($provider); die;
        return view('admin.cashout.provider.providerdetails',compact('provider'));
      
    
    }

    public function update_earnings(Request $request)
     {
         try {

            $result = Provider::where('id',$request->driverid)->update(['wallet_balance' => $request->amount ]);
            return back()->with('flash_success', 'Driver earnings updated');
           //  dd($request->all()); die;
         } catch (\Throwable $th) {
            return back()->with('flash_error', 'Something Went Wrong');
             //throw $th;
         }

     }

    
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
        return view('page',compact('page'));

    }
    public function terms(Request $request)
    {
        $page = Page::where('unique_title','terms')->first();
        return view('page',compact('page'));
    }
    public function faq(Request $request)
    {
        $page = Page::where('unique_title','terms')->first();
        return view('page',compact('page'));
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
            $cashout = ProviderCashout::get();
            return view('admin.cashout.index',compact('cashout'));
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
                    app(\App\Http\Controllers\UserApiController::class)->notify_driver($trip->id);
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
            app(\App\Http\Controllers\UserApiController::class)->notify_driver($trip->id);
            return back()->with('flash_success', 'Updated details');  
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
        app(\App\Http\Controllers\UserApiController::class)->notify_driver($trip->id);
    }

    /**
     * Display a listing of the active trips in the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function providers(Request $request)
    {
    	$Providers = Provider::where('service_type_id','=',$request->service_type)
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
