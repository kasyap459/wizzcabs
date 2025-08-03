<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\Helper;

use Auth;
use Setting;
use Exception;
use \Carbon\Carbon;
use DB;

use App\Models\User;
use App\Models\Provider;
use App\Models\ServiceType;
use App\Models\UserRequest;
use App\Models\UserRequestPayment;
use App\Models\Vehicle;
use App\Models\UserRequestRating;
use App\Models\Partner;
use App\Models\PartnerInvoice;
use App\Models\Admin;

class PartnerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    public function __construct(Request $request)
    {
        //$this->middleware('admin');

        
       $this->middleware('partner');
        $this->middleware(function ($request, $next) {
        $this->id = Auth::user()->id;
        $this->email = Auth::user()->email;
        $this->admin_type = Auth::user()->admin_type;
        $this->admin_id = Auth::user()->admin_id;
       
        // if($this->admin_id == null){
            
        //      $admin = Admin::where('id','=',$this->id)->first();
           
        //      if($admin->admin_type != 0 && $admin->time_zone != null){
        //          date_default_timezone_set($admin->time_zone);
                
        //      }
        //  } else {

        //     $admin = Admin::where('id','=',$this->admin_id)->first();
         
        //      if($admin->admin_type != 0 && $admin->time_zone != null){
        //          date_default_timezone_set($admin->time_zone);
                 
        //      }
        //  }
            
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
        try{

            $days = 20;

            $range = \Carbon\Carbon::now()->subDays($days);

            $range = date($range);

            $providers = Provider::where('partner_id','=', Auth::user()->id)->get()->pluck('id')->toArray();
            // $providers = implode(',',$providers);
            //dd($providers);
        //     $result = DB::select("SELECT
        //                             Date(user_requests.created_at) as date,
        //                             COUNT(CASE WHEN user_requests.status = 'COMPLETED' THEN 1 END) AS completed,
        //                             COUNT(CASE WHEN user_requests.status = 'CANCELLED' THEN 1 END) AS cancelled,
        //                             IFNULL(ROUND(SUM(user_request_payments.total),2), 0) as revenue
                                    
        //                         FROM user_requests
        //                         LEFT JOIN user_request_payments
        //                         ON user_requests.id = user_request_payments.request_id
        //                         WHERE user_requests.provider_id IN(".implode(',',$providers).") AND
        //                         user_requests.created_at >= '$range'
        //                         GROUP BY date
        //                         ORDER BY date DESC");
            
        //    $stats = json_encode($result);


        //    $days = 7;
        //    $barrange = \Carbon\Carbon::now()->subDays($days);
        //    $barrange = date($range);
        //    $barresult = DB::select("SELECT
        //                             Date(user_requests.created_at) as date,
        //                             COUNT(CASE WHEN user_requests.booking_by = 'APP' THEN 1 END) AS app,
        //                             COUNT(CASE WHEN user_requests.booking_by = 'DISPATCHER' THEN 1 END) AS dispatcher,
        //                              COUNT(CASE WHEN user_requests.booking_by = 'STREET' THEN 1 END) AS street 
        //                         FROM user_requests
        //                         WHERE user_requests.provider_id IN(".implode(',',$providers).") AND
        //                         user_requests.created_at >= '$barrange'
        //                         GROUP BY date
        //                         ORDER BY date ASC");
           
        //    foreach($barresult as $key =>$stat){
        //         $barresult[$key]->date = $stat->date;
        //    }
        //    $bar = json_encode($barresult);

        //    $pierange = Carbon::today();
        //    $pierange = date($pierange);
        //    $pieresult = DB::select("SELECT
        //                             COUNT(CASE WHEN user_requests.cancelled_by = 'USER' THEN 1 END) AS user,
        //                             COUNT(CASE WHEN user_requests.cancelled_by = 'DISPATCHER' THEN 1 END) AS dispatcher,
        //                             COUNT(CASE WHEN (user_requests.cancelled_by = 'NODRIVER' || user_requests.cancelled_by = 'REJECTED') THEN 1 END) AS rejected,
        //                              COUNT(CASE WHEN user_requests.cancelled_by = 'PROVIDER' THEN 1 END) AS provider 
        //                         FROM user_requests
        //                         WHERE user_requests.provider_id IN(".implode(',',$providers).") AND  
        //                         user_requests.created_at >= '$pierange'");

        //    $pie = $pieresult;

          $stats  = [];
           $bar  = [];
           $pie  = [];
           return view('partner.dashboard',compact('stats', 'bar', 'pie'));
        }
        catch(Exception $e){
            return $e;
        }
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
            $providers = Provider::where('partner_id','=', Auth::user()->id)->get()->pluck('id');

            // $rides = UserRequest::whereIn('provider_id',$providers)
            $rides = UserRequest::where('partner_id','=', Auth::user()->id)
                ->where('created_at', '>=', $fromdate)
                ->where('created_at', '<', $todate)
                ->orderBy('id','desc')
                ->get();

            
            $cancel_rides = UserRequest::where('partner_id','=', Auth::user()->id)
                ->where('status','CANCELLED')
                ->where('created_at', '>=', $fromdate)
                ->where('created_at', '<', $todate)
                ->get();
    

            $service = ServiceType::count();

            $ridesid = UserRequest::where('partner_id','=', Auth::user()->id)
                ->get()->pluck('id');

            $revenue = 0;

            foreach($rides as $key=>$tb)
            {
               if($tb->payment){
                    $revenue += $tb->payment->total;
               } 
            }  
                
            $revenue = UserRequestPayment::where('created_at', '>=', $fromdate)
                ->whereIn('request_id',$ridesid)
                ->where('created_at', '<', $todate)
                ->sum('revenue');

            return view('partner.dashboard-content',compact('service','rides','cancel_rides','revenue'));
        }
        catch(Exception $e){
            return $e;
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
        return view('partner.account.profile');
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
            'carrier_name' => 'required|max:255',
            'mobile' => 'required|digits_between:6,13',
            'pan_no' => 'required',
            'address' => 'required',
            'logo' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
        ]);

        try{
            $partner = Auth::guard('partner')->user();
            $partner->name = $request->name;
            $partner->carrier_name = $request->carrier_name;
            $partner->mobile = $request->mobile;
            $partner->pan_no = $request->pan_no;
            $partner->address = $request->address;
            if($request->hasFile('logo')){
               \Storage::delete($partner->logo);
                $partner->logo = $request->logo->store('public/partner/profile');
                $partner->logo = $request->logo->store('partner/profile');  
            }
            $partner->save();

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
        return view('partner.account.change-password');
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

           $partner = Partner::find(Auth::guard('partner')->user()->id);

            if(password_verify($request->old_password, $partner->password))
            {
                $partner->password = bcrypt($request->password);
                $partner->save();

                return redirect()->back()->with('flash_success','Password Updated');
            } else {
                return back()->with('flash_error','Password entered doesn\'t match');
            }
        } catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
    }

    /**
     * Map of all Users and Drivers.
     *
     * @return \Illuminate\Http\Response
     */
    public function map_index()
    {
        return view('partner.map.index');
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
                    ->where('partner_id', Auth::user()->id)
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
                $page = 'Driver Ride Statement';
            }elseif($type == 'today'){
                $page = 'Today Statement - '. date('d M Y');
            }elseif($type == 'monthly'){
                $page = 'This Month Statement - '. date('F');
            }elseif($type == 'yearly'){
                $page = 'This Year Statement - '. date('Y');
            }
            $type_data = $type;
            return view('partner.statement.overall', compact('page','type_data'));

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
            $todate = $request->todate;
        }
        if($request->has('payment')){
            $payment_type = $request->payment;
        }
        if($request->has('tripstatus')){
            $tripstatus = $request->tripstatus;
        }
        $main_detail = UserRequest::with('payment')
                      ->where('partner_id','=',Auth::user()->id)
                      ->where('created_at', '>=', $fromdate)
                      ->where('created_at', '<', $todate)
                      ->where('status', 'LIKE', '%'.$tripstatus.'%')
                      ->where('payment_mode','LIKE', '%'.$payment_type.'%');

        $cancel_rides = UserRequest::where('status','CANCELLED')
                      ->where('partner_id','=',Auth::user()->id)
                      ->where('created_at', '>=', $fromdate)
                      ->where('created_at', '<', $todate)
                      ->where('status', 'LIKE', '%'.$tripstatus.'%')
                      ->where('payment_mode','LIKE', '%'.$payment_type.'%');

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
                             ->count();
        }

        $data = array();
        if(!empty($rides))
        {
            foreach ($rides as $index => $ride)
            {
                $view =  route('partner.requests.show',$ride->id);
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
     * Provider Rating.
     *
     * @return \Illuminate\Http\Response
     */
    public function provider_review()
    {
        try {
            return view('partner.review.provider_review');
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
        $rides = UserRequest::where('partner_id','=', Auth::user()->id)->get()->pluck('id');

        $provider_review = UserRequestRating::whereIn('request_id',$rides)
                        ->where('provider_id','!=', 0)
                        ->with('user','provider');

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
                }else{
                    $user_name ='Not Found';
                }

                if($review->provider){
                    $provider_name = $review->provider->name;
                }else{
                    $provider_name ='Not Found';
                }

                $rating = '<div className="rating-outer">
                                    <input type="hidden" value="'.$review->provider_rating.'" name="rating" class="rating"/>
                                </div>';
        
                $nestedData['id'] = $start + 1;
                $nestedData['request_id'] = $review->request_id;
                $nestedData['user_name'] =  $user_name;
                $nestedData['provider_name'] =  $provider_name;
                $nestedData['rating'] = $rating;
                $nestedData['date_time'] = $review->created_at->diffForHumans();
                $nestedData['comments'] = $review->provider_comment;
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

            $Providers = Provider::where('partner_id','=',Auth::user()->id)->get();

            return view('partner.statement.provider-statement', compact('Providers'))->with('page','Driver Statement');

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
        $Providerslist = Provider::where('partner_id','=',Auth::user()->id)->get();
        foreach($Providerslist as $index => $Provider){
            $Rides = UserRequest::where('provider_id',$Provider->id)
                        ->orderBy('id','desc')
                        ->get()->pluck('id');

            $Providerslist[$index]->rides_count = $Rides->count();
            $Providerslist[$index]->payment = UserRequestPayment::whereIn('request_id', $Rides)
                            ->select(\DB::raw(
                               'SUM(total) as overall' ))->get();
        }

        $totalData = $Providerslist->count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $Providers = Provider::where('partner_id','=',Auth::user()->id)->offset($start)
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
                                   'SUM(total) as overall' ))->get();
            }
        }
        else {
            $search = $request->input('search.value'); 

            $Providers =  Provider::where('partner_id','=',Auth::user()->id)
                            ->where('name','LIKE',"%{$search}%")
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
                                   'SUM(total) as overall' ))->get();
                }

            $totalFiltered = Provider::where('partner_id','=',Auth::user()->id)
                            ->where('name','LIKE',"%{$search}%")
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
                    $total = $provider->payment[0]->overall;
                }else{
                    $total = '-';
                }

                if($provider->created_at){
                    $joined_at = Carbon::parse($provider->created_at)->format('d-m-Y');
                }else{
                    $joined_at = '-';
                }
                $details = '<a href="'.route('partner.provider.statement', $provider->id).'"><div class="label label-table label-info">'.trans("admin.member.view").'</div></a>';
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function invoice_list()
    {
        $invoices = PartnerInvoice::with('partner')->where('partner_id','=',Auth::user()->id)->orderBy('created_at' , 'desc')->get();
        return view('partner.invoice.index', compact('invoices')); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PartnerInvoice  $partnerInvoice
     * @return \Illuminate\Http\Response
     */
    public function invoice_view($id)
    {
        try {
            $invoice = PartnerInvoice::with('partner')->findOrFail($id);
            $rides = UserRequest::where('status','COMPLETED')
                ->where('PAID','=',1)
                ->where('partner_id','=',$invoice->partner_id)
                ->where('created_at', '>=', $invoice->from_date)
                ->where('created_at', '<', $invoice->to_date)
                ->get();

            $corporate_total = 0;
            $customer_vat_total =0;
            foreach($rides as $key=>$tb){
                if($tb->payment){
                    $commission = $tb->payment->total * ( $invoice->commission_percent/100 );
                    $commission_vat = $commission * ( $invoice->commission_vat_percent/100 );
                    $rides[$key]->vat_percent = Setting::get('vat_percent');
                    $rides[$key]->commission = $commission;
                    $rides[$key]->commission_vat = $commission_vat;
                    $rides[$key]->commission_total = $commission + $commission_vat;
                    $rides[$key]->ride_total = $tb->payment->total;
                    $rides[$key]->carrier_total = $tb->payment->total- ($commission + $commission_vat);

                    if($tb->corporate_id !=0){
                        $corporate_total+= $tb->payment->total;
                    }
                } 
            }
            $customer_vat_total = $invoice->ride_total * Setting::get('vat_percent')/100;
            return view('partner.invoice.view',compact('invoice','rides','corporate_total','customer_vat_total'));
            
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Invoice Type Not Found');
        }
    }
}
