<?php

namespace App\Http\Controllers\Resource;

use App\Models\Partner;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Exception;
use Setting;
use App\Models\Admin;
use \Carbon\Carbon;
use App\Models\UserRequest;
use App\Models\UserRequestPayment;
use Auth;

class PartnerResource extends Controller
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
        if($this->admin_id == null){
            
             $admin = Admin::where('id','=',$this->id)->first();
           
             if($admin->admin_type != 0 && $admin->time_zone != null){
                 date_default_timezone_set($admin->time_zone);
                
             }
         } else {

            $admin = Admin::where('id','=',$this->admin_id)->first();
         
             if($admin->admin_type != 0 && $admin->time_zone != null){
                 date_default_timezone_set($admin->time_zone);
                 
             }
         }
            
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
        $partners = Partner::orderBy('created_at' , 'desc')->get();
        return view('admin.partners.index', compact('partners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::all();
        return view('admin.partners.create', compact('countries'));
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
            'mobile' => 'digits_between:6,13',
            'email' => 'required|unique:partners,email|email|max:255',
            'password' => 'required|min:6|confirmed',
            'country_id' => 'required',
            'carrier_percentage' => 'required',
        ]);

        try{

            $partner = $request->all();
            if($request->hasFile('logo')) { 
                $partner['logo'] = $request->logo->store('public/partner');
                $partner['logo'] = $request->logo->store('partner');
            }
            $country = Country::where('countryid','=',$request->country_id)->first();
            if(Auth::guard('admin')->user()->admin_type !=0){
                $partner['admin_id'] = Auth::guard('admin')->user()->id;
            }
            $partner['dial_code'] = $country->dial_code;
            $partner['password'] = bcrypt($request->password);
            $partner['status'] = 1;
            $partner = Partner::create($partner);

            return back()->with('flash_success','partner Details Saved Successfully');

        } 

        catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $countries = Country::all();
            $partner = Partner::findOrFail($id);
            return view('admin.partners.edit',compact('partner','countries'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $this->validate($request, [
            'name' => 'required|max:255',
            'mobile' => 'digits_between:6,13',
            'email' => 'required',
            'country_id' => 'required',
            'carrier_percentage' => 'required',
        ]);

        try {

            $partner = Partner::findOrFail($id);
            $country = Country::where('countryid','=',$request->country_id)->first();
            if($request->hasFile('logo')) {
                \Storage::delete($partner->logo);
                
                $partner['logo'] = $request->logo->store('public/partner');
                $partner->logo = $request->logo->store('partner');
            }
            $partner->dial_code = $country->dial_code;
            $partner->country_id = $country->countryid;
            $partner->name = $request->name;
            $partner->mobile = $request->mobile;
            $partner->email = $request->email;
            $partner->carrier_name = $request->carrier_name ? :'';
            $partner->carrier_percentage = $request->carrier_percentage ? : 0;
            $partner->address = $request->address ? : '';
            $partner->pan_no = $request->pan_no ? :'';
            if($request->filled('password')){
                $partner->password = bcrypt($request->password);
            }
            $partner->save();

            return redirect()->route('admin.partner.index')->with('flash_success', 'partner Updated Successfully');    
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'partner Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        try {
            Partner::find($id)->delete();
            return back()->with('message', 'partner deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'partner Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function active($id)
    {
        Partner::where('id',$id)->update(['status' => 1]);
        return back()->with('flash_success', "Partner activated");
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function inactive($id)
    {
        
        Partner::where('id',$id)->update(['status' => 0]);
        return back()->with('flash_success', "Partner inactivated");
    }

    /**
     * account statements.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function statement($id){

        try{

            $Partner = Partner::find($id);
            $partnerid = $id;                    
            $Joined = $Partner->created_at ? '- Joined '.$Partner->created_at->diffForHumans() : '';
            $page = $Partner->name."'s Overall Statement ". $Joined;
            return view('admin.statement.partner-content', compact('page','partnerid'));

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
    public function partner_content(Request $request){
        $columns = array( 
                            0 =>'id', 
                            1 =>'booking_id',
                            2=> 's_address',
                            3=> 'd_address',
                            4=> 'detail',
                            5=> 'created_at',
                            6=> 'status',
                            7=> 'payment_mode',
                            8=> 'total',
                        );
        $fromdate = '';
        $todate = Carbon::now();
        $payment_type ='';
        $tripstatus ='';
        $id = $request->partnerid;
        
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
                      ->where('partner_id',$id)
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
                      ->where('partner_id',$id)
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
        $revenue = UserRequestPayment::whereHas('request', function($query) use($id) {
                                    $query->where('partner_id', $id );
                                })->select(\DB::raw(
                                   'SUM(total) as overall' 
                               ))->where('created_at', '>=', $fromdate)
                                 ->where('created_at', '<', $todate)->get();

        $total_cancel = $cancel_rides->count();
        $total_revenue = $revenue[0]->overall ? : 0;
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
                $view =  route('admin.requests.show',$ride->id);
                if($ride->s_address != ''){ $s_address = $ride->s_address;}else{$s_address = "Not Provided";}
                if($ride->d_address != ''){ $d_address = $ride->d_address;}else{$d_address = "Not Provided";}
                if($ride->status != 'CANCELLED'){ $detail = '<a class="text-primary" href="'.$view.'"><div class="label label-table label-info">'.trans("admin.member.view").'</div></a>'; }else{$detail= '<span>'.trans("admin.member.no_details_found").'</span>'; }
                if($ride->status == "COMPLETED"){$status = '<span class="label label-table label-success">'.$ride->status.'</span>';}
                elseif($ride->status == "CANCELLED"){$status = '<span class="label label-table label-danger">'.$ride->status.'</span>';}
                else{$status = '<span class="label label-table label-primary">'.$ride->status.'</span>';}

                if($ride->payment){
                    $total_text = $ride->payment->currency.$ride->payment->total;
                }else{
                    $total_text=0;
                }
                if($ride->corporate_id !=0){
                    $payment_mode = 'CORPORATE';
                }else{
                    $payment_mode = $ride->payment_mode;
                }
                $nestedData['id'] = $start + 1;
                $nestedData['booking_id'] = $ride->booking_id;
                $nestedData['s_address'] =  $s_address;
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

}
