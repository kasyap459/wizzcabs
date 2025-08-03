<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use DB;
use Exception;
use Setting;
use Storage;
use Mail;
use Twilio;
use Auth;
use \Carbon\Carbon;
use App\Models\Country;
use App\Models\Provider;
use App\Models\Partner;
use App\Models\Admin;
use App\Models\ServiceType;
use App\Models\Vehicle;
use App\Helpers\Helper;
use App\Models\UserRequest;
use App\Models\UserRequestPayment;
use App\Models\Location;
use App\Models\ProviderDevice;

class ProviderPartnerResource extends Controller
{   
    public function __construct(Request $request)
    {
        //$this->middleware('admin');

        
      
        $this->middleware(function ($request, $next) {
        $this->id = Auth::user()->id;
        $this->email = Auth::user()->email;
        $this->admin_type = Auth::user()->admin_type;
        $this->admin_id = Auth::user()->admin_id;
        //dd($this->admin_type);
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $providers = Provider::where('partner_id','=',Auth::user()->id)->get();
        return view('partner.providers.index', compact('providers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::all();
        $services = ServiceType::all();
        return view('partner.providers.create', compact('countries','services'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function provider_row(Request $request){

        $columns = array( 
                            0 =>'id', 
                            1 =>'full_name',
                            2=> 'email',
                            3=> 'mobile',
                            4=> 'total_requests',
                            5=> 'accepted_requests',
                            6=> 'cancelled_requests',
                            7=> 'documents', 
                            8=> 'action',
                        );
       
        $AllProviders = Provider::where('partner_id','=',Auth::user()->id)->with('service','totalrequest','accepted','cancelled');

        if(Auth::user()->admin_id != 0){
         $AllProviders = $AllProviders->where('partner_id','=',Auth::user()->id)->where('admin_id','=', Auth::user()->admin_id);
        } 

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
        if(Auth::user()->admin_id != 0){
        $providers = $providers->where('admin_id','=', Auth::user()->admin_id);
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
                if(Auth::user()->admin_id != 0){
        $providers = $providers->where('admin_id', Auth::user()->admin_id);
                }
            $providers = $providers->limit($limit)
                    ->orderBy('id','desc')
                    ->get();

        $totalFiltered = $providerslist->where(function($q) use ($search) {
                      $q->where('name','LIKE',"%{$search}%")
                    ->orWhere('email', 'LIKE',"%{$search}%")
                    ->orWhere('mobile', 'LIKE',"%{$search}%");
                  });
                if(Auth::user()->admin_id != 0){
        $totalFiltered = $totalFiltered->where('admin_id','=', Auth::user()->admin_id);
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
        if($provider->email != ''){ $email = Helper::hideEmail($provider->email);}else{$email = "";}
        if($provider->mobile != ''){ $mobile = Helper::hidechar($provider->mobile);}else{$mobile = "";}

        if($provider->service == null){
        $documents = '<a class="btn btn-danger btn-rounded btn-block label-right waves-effect waves-light" href="'.route('partner.provider.document.index', $provider->id ).'">'.trans("admin.member.attention").'<span class="btn-label">'.$provider->pending_documents().'</span></a>';
        }else{
        $documents = '<a class="btn btn-success btn-rounded btn-block waves-effect waves-light" href="'.route('partner.provider.document.index', $provider->id ).'">'.trans("admin.member.all_set").'</a>';
        }
        if($provider->account_status == 'approved'){
        $enable = '<a class="btn btn-danger btn-rounded btn-block waves-effect waves-light" href="'.route('partner.provider.banned', $provider->id ).'">'.trans("admin.member.disable").'</a>';
        }else{
        $enable = '<a class="btn btn-success btn-rounded btn-block waves-effect waves-light" href="'.route('partner.provider.approve', $provider->id ).'">'.trans("admin.member.enable").'</a>';
        }
        $button ='<button type="button" 
                            class="btn btn-info btn-rounded btn-block dropdown-toggle"
                            data-toggle="dropdown">Action
                            <span class="caret"></span>
                        </button>
                <ul class="dropdown-menu">
                            <li>
                                <a href="'.route('partner.provider.request', $provider->id).'" class="btn btn-default"><i class="fa fa-search"></i> '.trans("admin.member.history").'</a>
                            </li>
                            <li>
                                <a href="'.route('partner.provider.statement', $provider->id).'" class="btn btn-default"><i class="fa fa-account"></i> '.trans("admin.member.statement").'</a>
                            </li>
                            <li>
                                <a href="'.route('partner.provider.edit', $provider->id).'" class="btn btn-default"><i class="fa fa-pencil"></i> '.trans("admin.member.edit").'</a>
                            </li>
                            <li>
                                <form action="'.route('partner.provider.logout', $provider->id) .'" method="POST">
                                    '.csrf_field().'
                                    <input type="hidden" name="_method" value="POST">
                                    <button class="btn btn-default look-a-log" onclick="return confirm(`Do you want to logout this provider?`)"><i class="fa fa-sign-out"></i> '.trans("admin.member.logout").'</button>
                                </form>
                            </li>
                            <li>
                                <form action="'.route('partner.provider.destroy', $provider->id).'" method="POST">
                                    '.csrf_field().'
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button class="btn btn-default look-a-like" onclick="return confirm(`Are you sure?`)"><i class="fa fa-trash"></i> '.trans("admin.member.delete").'</button>
                                </form>
                            </li>
                        </ul>';
        $action = '<div class="input-group-btn">'.$enable.$button.'</div>';

        $nestedData['id'] = $start + 1;
        $nestedData['full_name'] = $first_name;
        $nestedData['email'] =  $email;
        $nestedData['mobile'] = $mobile;
        $nestedData['total_requests'] = $provider->totalrequest->count();
        $nestedData['accepted_requests'] = $provider->accepted->count();
        $nestedData['cancelled_requests'] = $provider->cancelled->count();
        $nestedData['documents'] = $documents;
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
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|unique:providers,email|email|max:255',
            'password' => 'required|min:6|confirmed',
            'mobile' => 'unique:providers,mobile',
            'country_id' => 'required',
        ]);

        try{
            $location = Location::first();
            $service = ServiceType::first();

            $provider = $request->all();
            $country = Country::where('countryid','=',$request->country_id)->first();
            if(Auth::user()->admin_id !=0){
                $provider['admin_id'] = Auth::user()->admin_id;
            }
            $provider['mapping_id'] = 0;
            $provider['password'] = bcrypt($request->password);
            $provider['dial_code'] = $country->dial_code;
            $provider['mobile'] = $request->mobile;
            $provider['partner_id'] = Auth::user()->id;
            $provider['language'] = implode(',',$request->language);
            $provider['status'] = 'offline';
            $provider['wallet_balance'] = 0;
            if($request->hasFile('avatar')) {
                $provider['avatar'] = $request->avatar->store('public/provider/profile');
                $provider['avatar'] = $request->avatar->store('provider/profile');
            }

            $provider = Provider::create($provider);

            return back()->with('flash_success','Provider Details Saved Successfully');

        } 

        catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $provider = Provider::findOrFail($id);
            return view('admin.providers.provider-details', compact('provider'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $countries = Country::all();
            $provider = Provider::findOrFail($id);
            $services = ServiceType::all();
            return view('partner.providers.edit',compact('provider','countries','services'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required',
            'mobile' => 'digits_between:6,13',
            'avatar' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
            'country_id' => 'required',
        ]);

        try {

            $provider = Provider::findOrFail($id);

            if($request->hasFile('avatar')) {
                if($provider->avatar) {
                    Storage::delete($provider->avatar);
                }
                $provider->avatar = $request->avatar->store('provider/profile');    
            }

            $provider->name = $request->name;
            $country = Country::where('countryid','=',$request->country_id)->first();
            $provider->email = $request->email;
            $provider->country_id = $country->countryid;
            $provider->dial_code = $country->dial_code;
            $provider->mobile = $request->mobile;
            $provider->partner_id = Auth::user()->id;
            $provider->gender = $request->gender ? : '';
            $provider->address = $request->address ? : '';
            $provider->allowed_service = implode(',',$request->allowed_service);
            $provider->language = implode(',',$request->language);
            $provider->acc_no = $request->acc_no ? : '';
            $provider->license_no = $request->license_no ? : '';
            $provider->license_expire = $request->license_expire ? : '';
            $provider->custom_field1 = $request->custom_field1 ? : '';
            $provider->custom_field2 = $request->custom_field2 ? : '';
            $provider->save();

            return redirect()->route('partner.provider.index')->with('flash_success', 'Provider Updated Successfully');    
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Provider Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        try {
            Provider::find($id)->delete();
            return back()->with('message', 'Provider deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'Provider Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function approve($id)
    {
        Provider::where('id',$id)->update(['account_status' =>'approved']);
        return back()->with('flash_success', "Driver account approved");  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function banned($id)
    {
        Provider::where('id',$id)->update(['account_status' =>'banned']);
        return back()->with('flash_success', "Provider inactivated");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function assign_list()
    {
        $providers = Provider::where('partner_id','=',Auth::user()->id)->get();
        $vehicles = Vehicle::where('partner_id','=',Auth::user()->id)->get();
        return view('partner.providers.assign', compact('providers','vehicles'));
    }
    /**
     * Toggle service availability of the provider.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function assign_vehicle(Request $request)
    {
        $this->validate($request, [
                'provider_id' => 'required',
                'vehicle_id' => 'required',
            ]);

        $Provider = Provider::where('id','=',$request->provider_id)->first();
        $vehicle = Vehicle::where('id','=',$request->vehicle_id)->first();
        if($vehicle !=null){
            $prev = Provider::where('mapping_id','=',$vehicle->id)->first();
            if($prev !=null){
                if($prev->status =='offline'){
                    $prev->service_type_id = 0;
                    $prev->mapping_id = 0;
                    $prev->save();
                }else{
                    return back()->with('flash_error', 'Vehicle is in Ride, Cannot change now');
                }
            }
            $Provider->mapping_id = $vehicle->id;
            $Provider->service_type_id = $vehicle->service_type_id;
            $Provider->save();
            return back()->with('flash_success', 'Vehicle Updated Successfully');
        }else{
            return response()->with('flash_success', 'Vehicle Not Found');
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function assign_row(Request $request){

        $columns = array( 
                            0 => 'id', 
                            1 => 'name',
                            2 => 'email',
                            3 => 'mobile',
                            4 => 'account_status',
                            5 => 'status',
                            6 => 'vehicle',
                        );
       
        
        $totalData = Provider::where('partner_id','=',Auth::user()->id)->count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $providers = Provider::with('vehicle')
                     ->where('partner_id','=',Auth::user()->id)
                     ->offset($start)
                     ->limit($limit)
                     ->orderBy('id','desc')
                     ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $providers =  Provider::with('vehicle')->where('partner_id','=',Auth::user()->id)
                            ->where('name','LIKE',"%{$search}%")
                            ->orWhere('email', 'LIKE',"%{$search}%")
                            ->orWhere('mobile', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy('id','desc')
                            ->get();

            $totalFiltered = Provider::with('vehicle')->where('partner_id','=',Auth::user()->id)
                            ->where('name','LIKE',"%{$search}%")
                            ->orWhere('email', 'LIKE',"%{$search}%")
                            ->orWhere('mobile', 'LIKE',"%{$search}%")
                            ->count();
        }

        $data = array();
        if(!empty($providers))
        {
            foreach ($providers as $index => $provider)
            {
            if($provider->name != ''){ $name = $provider->name;}else{$name = "";}
            if($provider->email != ''){ $email = $provider->email;}else{$email = "";}
            if($provider->mobile != ''){ $mobile = $provider->dial_code.$provider->mobile;}else{$mobile = "";}
            if($provider->account_status =='onboarding'){
                $account_status = '<span class="label label-warning label-sm">Onboarding</span>';
            }elseif($provider->account_status =='approved'){
                $account_status = '<span class="label label-success label-sm">Approved</span>';
            }else{
                $account_status = '<span class="label label-danger label-sm">Banned</span>';
            }

            if($provider->status =='offline'){
                $status = '<span class="label label-danger label-sm">Offline</span>';
            }elseif($provider->status =='active'){
                $status = '<span class="label label-success label-sm">Active</span>';
            }else{
                $status = '<span class="label label-primary label-sm">Riding</span>';
            }

             if($provider->vehicle){
                $vehicle = $provider->vehicle->vehicle_no;
            }else{
                $vehicle = '-';
            }

                $nestedData['id'] = $start + 1;
                $nestedData['name'] = $name;
                $nestedData['email'] = $email;
                $nestedData['mobile'] = $mobile;
                $nestedData['account_status'] = $account_status;
                $nestedData['status'] = $status;
                $nestedData['vehicle'] = $vehicle;
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
    public function statement($id){

        try{

            $Provider = Provider::find($id);
            $providerid = $id;                    
            $Joined = $Provider->created_at ? '- Joined '.$Provider->created_at->diffForHumans() : '';
            $page = $Provider->name."'s Overall Statement ". $Joined;
            return view('partner.statement.provider-content', compact('page','providerid'));

        } catch (Exception $e) {
            return back()->with('flash_error','Something Went Wrong!');
        }
    }
    /**
     * provider base statements rows.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function provider_content(Request $request){
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
                            10=> 'earnings',
                            11=> 'total',
                        );
        $fromdate = '';
        $todate = Carbon::now();
        $payment_type ='';
        $tripstatus ='';
        $id = $request->providerid;
        
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
                      ->where('provider_id',$id)
                      ->where('created_at', '>=', $fromdate)
                      ->where('created_at', '<', $todate)
                      ->where('status', 'LIKE', '%'.$tripstatus.'%')
                      ->where('payment_mode','LIKE', '%'.$payment_type.'%');

        $cancel_rides = UserRequest::where('status','CANCELLED')
                      ->where('provider_id',$id)
                      ->where('created_at', '>=', $fromdate)
                      ->where('created_at', '<', $todate)
                      ->where('status', 'LIKE', '%'.$tripstatus.'%')
                      ->where('payment_mode','LIKE', '%'.$payment_type.'%');

        $revenue = UserRequestPayment::whereHas('request', function($query) use($id) {
                                    $query->where('provider_id', $id );
                                })->select(\DB::raw(
                                   'SUM(total) as overall' 
                               ))->where('created_at', '>=', $fromdate)
                                 ->where('created_at', '<', $todate)->get();

        $total_cancel = $cancel_rides->count();
        $total_revenue = $revenue[0]->overall;
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
                if($ride->payment){
                    $earning = $ride->payment->currency.$ride->payment->earnings;
                }else{
                    $earning='';
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
                $nestedData['earning'] = $earning;
                $nestedData['total'] = $total_text;
                $data[] = $nestedData;
                $start++;
            }
        }
        $percentage = 0.00;
        if($total_cancel != 0){
             $percentage = round($total_cancel / $totalFiltered, 2);
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

    public function request($id){

        try{
            $user_id = '';
            $provider_id = $id;        
            return view('partner.request.index', compact('user_id','provider_id'));
        } catch (Exception $e) {
            return back()->with('flash_error','Something Went Wrong!');
        }
    }

    public function logout($id)
    {
        try {
            ProviderDevice::where('provider_id', $id)->orderBy('id','DESC')->update(['udid'=> '', 'token' => '']);
            Provider::where('id',$id)->update(['status' => 'offline']);
            return back()->with('flash_success','Provider Logged out successfully');
        } catch (Exception $e) {
            return back()->with('flash_error','Something Went Wrong!');
        }
    }

}
