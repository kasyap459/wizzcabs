<?php

namespace App\Http\Controllers\Resource;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserRequest;
use Auth;
use Setting;
use App\Models\Admin;

class PartnerTripResource extends Controller
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
    public function index()
    {
        try {
            
            $user_id = '';
            $provider_id ='';
            return view('partner.request.index', compact('user_id','provider_id'));
        } catch (Exception $e) {
            return back()->with('flash_error','Something Went Wrong!');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function scheduled()
    {
        try{
            $requests = UserRequest::where('status' , 'SCHEDULED')
                        ->with('user','payment','provider')
                        ->get();

            return view('partner.request.scheduled', compact('requests'));
        } catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function requests_row(Request $request){

        $columns = array( 
                            0 =>'id', 
                            1 =>'booking_id',
                            2=> 'user_name',
                            3=> 'provider_name',
                            4=> 'date_time',
                            5=> 'status',
                            6=> 'amount',
                            7=> 'payment_mode',
                            8=> 'payment_status',
                            9=> 'action',
                        );
        if($request->user_id !=''){
            $requestslist = UserRequest::with('user','provider','payment')
                        ->where('user_requests.user_id',$request->user_id)
                        ->where('user_requests.partner_id',Auth::guard('partner')->user()->id)
                        ->orderBy('user_requests.created_at', 'desc');
        }elseif($request->provider_id !=''){
            $requestslist = UserRequest::with('user','provider','payment')
                        ->where('user_requests.provider_id',$request->provider_id)
                        ->where('user_requests.partner_id',Auth::guard('partner')->user()->id)
                        ->orderBy('user_requests.created_at', 'desc');
        }else{
            $requestslist = UserRequest::with('user','provider','payment')
                            ->where('user_requests.partner_id',Auth::guard('partner')->user()->id)
                            ->where('user_requests.status','=','COMPLETED');
        }

        $totalData = $requestslist->count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $trips = $requestslist->offset($start)
                     ->limit($limit)
                     ->orderBy('user_requests.id','desc')
                     ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $trips =  $requestslist->where('booking_id','LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy('user_requests.id','desc')
                            ->get();

            $totalFiltered = $requestslist->where('booking_id','LIKE',"%{$search}%")
                            ->count();
        }

        $data = array();
        if(!empty($trips))
        {
            foreach ($trips as $index => $trip)
            {
                if($trip->user_name != ''){ 
                    $user_name = $trip->user_name;
                }else{
                    $user_name = "Not Found";
                }

                if($trip->name != ''){ 
                    $name = $trip->name;
                }else{
                    $name = "Not Found";
                }

                if($trip->created_at){
                    $date_time ='<span class="text-muted">'.$trip->created_at.'</span>';
                }else{
                    $date_time = '-';
                }

                if($trip->total != ""){
                    $amount = currency_sum($trip->total);
                }else{
                    $amount = 'N/A';
                }

                if($trip->paid){
                    $paid = 'Paid';
                }else{
                    $paid = 'Not Paid';
                }

                $action = '<div class="btn-group" role="group">
                                <button type="button" class="btn btn-info waves-effect waves-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    '.trans("admin.member.action").'
                                </button>
                                <div class="dropdown-menu">
                                    <a href="'.route('partner.requests.show', $trip->id).'" class="dropdown-item">
                                        <i class="fa fa-search"></i> '.trans("admin.member.more_details").'
                                    </a>
                                </div>
                            </div>';
            
                if($trip->corporate_id !=0){
                    $payment_mode = 'CORPORATE';
                    $paid = '-';
                }else{
                    $payment_mode = $trip->payment_mode;
                    if($trip->paid){
                        $paid = 'Paid';
                    }else{
                        $paid = 'Not Paid';
                    }
                }
                $nestedData['id'] = $start + 1;
                $nestedData['booking_id'] = $trip->booking_id;
                $nestedData['user_name'] =  $user_name;
                $nestedData['provider_name'] = $name;
                $nestedData['date_time'] = $date_time;
                $nestedData['status'] = $trip->status;
                $nestedData['amount'] = $amount;
                $nestedData['payment_mode'] = $payment_mode;
                $nestedData['payment_status'] = $paid;
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
                's_latitude' => 'required|numeric',
                'd_latitude' => 'required|numeric',
                's_longitude' => 'required|numeric',
                'd_longitude' => 'required|numeric',
                'service_type' => 'required|numeric|exists:service_types,id',
                'promo_code' => 'exists:promocodes,promo_code',
                'distance' => 'required|numeric',
                'use_wallet' => 'numeric',
                'payment_mode' => 'required|in:CASH,CARD,PAYPAL',
            ]);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $unit = Setting::get('distance_unit','km');
            $request = UserRequest::findOrFail($id);
            return view('partner.request.show', compact('request','unit'));
        } catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        try {
            $Request = UserRequest::findOrFail($id);
            $Request->delete();
            return back()->with('flash_success','Request Deleted!');
        } catch (Exception $e) {
            return back()->with('flash_error','Something Went Wrong!');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancelled()
    {
        try{
            return view('partner.request.cancelled');
        } catch (Exception $e){
             return back()->with('flash_error','Something Went Wrong!');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancelled_row(Request $request){

        $columns = array( 
                            0 =>'id', 
                            1 =>'booking_id',
                            2=> 'user_name',
                            3=> 'provider_name',
                            4=> 'date_time',
                            5=> 'status',
                            6=> 'requested',
                            7=> 'approval',
                            8=>'fare',
                            9=> 'action',
                        );

        $requestslist = UserRequest::with('user','provider')
                        ->where('partner_id','=',Auth::guard('partner')->user()->id)
                        ->where('user_requests.status','=','CANCELLED')
                        ->where('cancelled_by','=','PROVIDER');
        

        $totalData = $requestslist->count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $trips = $requestslist->offset($start)
                     ->limit($limit)
                     ->orderBy('user_requests.id','desc')
                     ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $trips =  $requestslist->where('booking_id','LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy('user_requests.id','desc')
                            ->get();

            $totalFiltered = $requestslist->where('booking_id','LIKE',"%{$search}%")
                            ->count();
        }

        $data = array();
        if(!empty($trips))
        {
            foreach ($trips as $index => $trip)
            {
                if($trip->user_name !=null){
                   $user_name = $trip->user_name;
                }else{
                    $user_name ='Not Found';
                }

                if($trip->provider){
                   $provider_name = $trip->provider->name;
                }else{
                    $provider_name ='Not Found';
                }

                if($trip->created_at){
                    $date_time ='<span class="text-muted">'.$trip->created_at.'</span>';
                }else{
                    $date_time = '-';
                }

                if($trip->cancel_request ==1){
                    $requested ='<span class="label label-success">Yes</span>';
                }else{
                    $requested ='<span class="label label-danger">No</span>';
                }

                if($trip->cancel_request ==1){
                    if($trip->cancel_status =="Disapproved" || $trip->cancel_status ==null){
                        $checker ='<span class="label label-danger">Disapproved</span>';
                        $fare =Setting::get('currency').''.$trip->estimated_fare * Setting::get('cancel_percent')/100;
                    }else{
                        $checker ='<span class="label label-success">Approved</span>';
                        $fare = '-';
                    }
                }else{
                    $checker ='-';
                    $fare = Setting::get('currency').''.$trip->estimated_fare * Setting::get('cancel_percent')/100;
                }

                $action = '<a href="'.route('partner.requests.show', $trip->id).'" class="btn btn-info btn-sm" ><i class="fa fa-search"></i>View</a>';
            
                $nestedData['id'] = $start + 1;
                $nestedData['booking_id'] = $trip->booking_id;
                $nestedData['user_name'] =  $user_name;
                $nestedData['provider_name'] = $provider_name;
                $nestedData['date_time'] = $date_time;
                $nestedData['status'] = $trip->status;
                $nestedData['requested'] = $requested;
                $nestedData['approval'] = $checker;
                $nestedData['fare'] = $fare;
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
}
