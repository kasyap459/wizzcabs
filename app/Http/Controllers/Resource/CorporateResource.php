<?php

namespace App\Http\Controllers\Resource;

use App\Models\Corporate;
use App\Models\Country;
use App\Models\User;
use App\Models\CorporateUser;
use App\Models\CorporateGroup;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Exception;
use App\Models\Admin;
use Setting;
use \Carbon\Carbon;
use App\Models\UserRequest;
use App\Models\UserRequestPayment;
use Mail;
use Auth;
class CorporateResource extends Controller
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
        $corporates = Corporate::orderBy('created_at' , 'desc')->get();
        return view('admin.corporates.index', compact('corporates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::all();
        return view('admin.corporates.create', compact('countries'));
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
            'legal_name' => 'required|max:255',
            'mobile' => 'digits_between:6,13',
            'email' => 'required|unique:corporates,email|email|max:255',
            'password' => 'required|min:6|confirmed',
            'country_id' => 'required',
        ]);

        try{

            $corporate = $request->all();
            if($request->hasFile('picture')) { 
                $corporate['picture'] = $request->picture->store('public/corporate');
                $corporate['picture'] = $request->picture->store('corporate');
            }
            $country = Country::where('countryid','=',$request->country_id)->first();
            if(Auth::guard('admin')->user()->admin_type !=0){
                $corporate['admin_id'] = Auth::guard('admin')->user()->id;
            }
            $corporate['dial_code'] = $country->dial_code;
            $corporate['password'] = bcrypt($request->password);
            $corporate['status'] = 1;
            $corporate['zoom'] = 8;
            $corporate = Corporate::create($corporate);

            $corporate['subject'] = 'Corporate Member';
            $corporate['passd'] = $request->password;
            if(Setting::get('mail_enable', 0) == 1) {
                Mail::send('emails.corporate-register', ['user' => $corporate], function ($message) use ($corporate){
                    $message->to($corporate['email'], $corporate['legal_name'])->subject(config('app.name').' '.$corporate['subject']);
                });
            }
            return back()->with('flash_success','Corporate Details Saved Successfully');
        } 

        catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Corporate  $corporate
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Corporate  $corporate
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $countries = Country::all();
            $corporate = Corporate::findOrFail($id);
            return view('admin.corporates.edit',compact('corporate','countries'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Corporate  $corporate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $this->validate($request, [
            'legal_name' => 'required|max:255',
            'mobile' => 'digits_between:6,13',
            'email' => 'required',
            'country_id' => 'required',
        ]);

        try {

            $corporate = Corporate::findOrFail($id);
            $country = Country::where('countryid','=',$request->country_id)->first();
            if($request->hasFile('picture')) {
                \Storage::delete($corporate->picture);
                 
                 $corporate->picture = $request->picture->store('public/corporate');
                $corporate->picture = $request->picture->store('corporate');
            }
            $corporate->dial_code = $country->dial_code;
            $corporate->country_id = $country->countryid;
            $corporate->legal_name = $request->legal_name;
            $corporate->display_name = $request->display_name ? :'';
            $corporate->mobile = $request->mobile;
            $corporate->email = $request->email;
            $corporate->secondary_email = $request->secondary_email ? :'';
            $corporate->pan_no = $request->pan_no ? :'';
            $corporate->address = $request->address ? : '';
            if($request->latitude){
            $corporate->latitude = $request->latitude ? :'';
            $corporate->longitude = $request->longitude ? :'';
            }
            
            $corporate->notify_customer = $request->notify_customer ? : '';
            $corporate->zoom = 8;

            if($request->filled('password')){
                $corporate->password = bcrypt($request->password);
            }

          // dd($corporate); die;

            $corporate->save();

            return redirect()->route('admin.corporate.index')->with('flash_success', 'Corporate Updated Successfully');    
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Corporate Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Corporate  $corporate
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        try {

            $corporate_user = CorporateUser::where('corporate_id','=',$id)->pluck('id');
            User::whereIn('corporate_user_id',$corporate_user)->update(['corporate_user_id' => null, 'corporate_status' => 0]);
            CorporateUser::where('corporate_id','=',$id)->delete();
            CorporateGroup::where('corporate_id','=',$id)->delete();
            Corporate::find($id)->delete();
            return back()->with('message', 'Corporate deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'Corporate Not Found');
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
        $corporate_user = CorporateUser::where('corporate_id','=',$id)->pluck('id');
        User::whereIn('corporate_user_id',$corporate_user)->update(['corporate_status' => 1]);
        Corporate::where('id',$id)->update(['status' => 1]);
        return back()->with('flash_success', "Corporate activated");
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function inactive($id)
    {
        $corporate_user = CorporateUser::where('corporate_id','=',$id)->pluck('id');
        User::whereIn('corporate_user_id',$corporate_user)->update(['corporate_status' => 0]);
        Corporate::where('id','=',$id)->update(['status' => 0]);
        return back()->with('flash_success', "Corporate inactivated");
    }

    /**
     * account statements.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function statement($id){

        try{

            $Corporate = Corporate::find($id);
            $corporateid = $id;                    
            $Joined = $Corporate->created_at ? '- Joined '.$Corporate->created_at->diffForHumans() : '';
            $page = $Corporate->legal_name."'s Overall Statement ". $Joined;
            return view('admin.statement.corporate-content', compact('page','corporateid'));

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
    public function corporate_content(Request $request){
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
        $id = $request->corporateid;
        
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
                      ->where('corporate_id',$id)
                      ->where('created_at', '>=', $fromdate)
                      ->where('created_at', '<', $todate)
                      ->where('status', 'LIKE', '%'.$tripstatus.'%')
                      ->where('payment_mode','LIKE', '%'.$payment_type.'%');

        $cancel_rides = UserRequest::where('status','CANCELLED')
                      ->where('corporate_id',$id)
                      ->where('created_at', '>=', $fromdate)
                      ->where('created_at', '<', $todate)
                      ->where('status', 'LIKE', '%'.$tripstatus.'%')
                      ->where('payment_mode','LIKE', '%'.$payment_type.'%');

        $revenue = UserRequestPayment::whereHas('request', function($query) use($id) {
                                    $query->where('corporate_id', $id );
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
