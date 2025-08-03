<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\Helper;

use Auth;
use Setting;
use Exception;
use \Carbon\Carbon;

use App\Models\User;
use App\Models\Partner;
use App\Models\Account;
use App\Models\Provider;
use App\Models\UserPayment;
use App\Models\ServiceType;
use App\Models\UserRequest;
use App\Models\vehicle;
use App\Models\UserRequestRating;
use App\Models\UserRequestPayment;
use App\Models\Admin;

class AccountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
     public function __construct()
    {
        //$this->middleware('admin');

        
       $this->middleware('account');
        $this->middleware(function ($request, $next) {
        $this->id = Auth::user()->id;
        $this->email = Auth::user()->email;
        $this->admin_type = Auth::user()->admin_type;
        $this->admin_id = Auth::user()->admin_id;
        //dd($this->admin_type);
        // if($this->admin_id == null){
        //         //dd($this->admin_id);
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
            return view('account.dashboard');
        }
        catch(Exception $e){
            return redirect()->route('account.user.index')->with('flash_error','Something Went Wrong with Dashboard!');
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

            $rides = UserRequest::has('user')
                ->where('created_at', '>=', $fromdate)
                ->where('created_at', '<', $todate)
                ->orderBy('id','desc')
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

            $service = ServiceType::count();
            $fleet = Partner::count();

            $revenue = UserRequestPayment::where('created_at', '>=', $fromdate)
                ->where('created_at', '<', $todate)
                ->sum('total');

            $providers = Provider::take(10)->orderBy('rating','desc')->get();


            return view('account.dashboard-content',compact('providers','fleet','scheduled_rides','service','rides','completed_rides','user_cancelled','provider_cancelled','dispatcher_cancelled','cancel_rides','revenue','dispatcher_rides','street_rides'));
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
        return view('account.account.profile');
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
            $account = Auth::guard('account')->user();
            $account->name = $request->name;
            $account->mobile = $request->mobile;
            $account->save();

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
        return view('account.account.change-password');
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

           $Account = Account::find(Auth::guard('account')->user()->id);

            if(password_verify($request->old_password, $Account->password))
            {
                $Account->password = bcrypt($request->password);
                $Account->save();

                return redirect()->back()->with('flash_success','Password Updated');
            }
        } catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
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
                $page = 'Provider Ride Statement';
            }elseif($type == 'today'){
                $page = 'Today Statement - '. date('d M Y');
            }elseif($type == 'monthly'){
                $page = 'This Month Statement - '. date('F');
            }elseif($type == 'yearly'){
                $page = 'This Year Statement - '. date('Y');
            }
            $type_data = $type;
            return view('account.providers.statement', compact('page','type_data'));

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
                      ->where('created_at', '>=', $fromdate)
                      ->where('created_at', '<', $todate)
                      ->where('status', 'LIKE', '%'.$tripstatus.'%')
                      ->where('payment_mode','LIKE', '%'.$payment_type.'%');

        $cancel_rides = UserRequest::where('status','CANCELLED')
                      ->where('created_at', '>=', $fromdate)
                      ->where('created_at', '<', $todate)
                      ->where('status', 'LIKE', '%'.$tripstatus.'%')
                      ->where('payment_mode','LIKE', '%'.$payment_type.'%');

        $revenue = UserRequestPayment::where('created_at', '>=', $fromdate)
                ->where('created_at', '<', $todate)
                ->sum('total');

        $total_cancel = $cancel_rides->count();
        $total_revenue = currency_amt($revenue);
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
                $view =  route('account.requests.show',$ride->id);
                if($ride->s_address != ''){ $s_address = $ride->s_address;}else{$s_address = "Not Provided";}
                if($ride->stop1_address != ''){ $stop1_address = $ride->stop1_address;}else{$stop1_address = "-";}
                if($ride->stop2_address != ''){ $stop2_address = $ride->stop2_address;}else{$stop2_address = "-";}
                if($ride->d_address != ''){ $d_address = $ride->d_address;}else{$d_address = "Not Provided";}
                if($ride->status != 'CANCELLED'){ $detail = '<a class="text-primary" href="'.$view.'"><div class="label label-table label-info">'.trans("admin.member.view").'</div></a>'; }else{$detail= '<span>'.trans("admin.member.no_details_found").'</span>'; }
                if($ride->status == "COMPLETED"){$status = '<span class="label label-table label-success">'.$ride->status.'</span>';}
                elseif($ride->status == "CANCELLED"){$status = '<span class="label label-table label-danger">'.$ride->status.'</span>';}
                else{$status = '<span class="label label-table label-primary">'.$ride->status.'</span>';}

                if($ride->corporate_id !=0){
                    $payment_mode = 'CORPORATE';
                }else{
                    $payment_mode = $ride->payment_mode;
                }  
                if($ride->payment){
                    $total_text = $ride->payment->currency.$ride->payment->total;
                }else{
                    $total_text='';
                }

                $nestedData['id'] = $start + 1;
                $nestedData['booking_id'] = $ride->booking_id;
                $nestedData['s_address'] =  $s_address;
                $nestedData['stop1_address'] =  $stop1_address;
                $nestedData['stop2_address'] =  $stop2_address;
                $nestedData['d_address'] =  $d_address;
                $nestedData['detail'] = $detail;
                $nestedData['created_at'] = date('d-m-Y',strtotime($ride->created_at));
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
     * account statements.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function statement_provider(){
        try{

            $Providers = Provider::all();
            return view('account.providers.provider-statement', compact('Providers'))->with('page',trans("admin.driver_statement"));

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
                                   'SUM(total) as overall' ))->get();
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
                                   'SUM(total) as overall' ))->get();
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
                    $status = '<span class="label label-table label-success">'.$provider->status.'</span>';
                }elseif($provider->account_status == "banned"){
                    $status = '<span class="label label-table label-danger">'.$provider->status.'</span>';
                }else{
                    $status = '<span class="label label-table label-primary">'.$provider->status.'</span>';
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
                    $joined_at = $provider->created_at;
                }else{
                    $joined_at = '-';
                }
                $details = '<a href="'.route('account.provider.statement', $provider->id).'"><div class="label label-table label-info">'.trans("admin.member.view").'</div></a>';
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
}
