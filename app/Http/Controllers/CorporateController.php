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

use App\Models\User;
use App\Models\Provider;
use App\Models\UserRequest;
use App\Models\Admin;
class CorporateController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    public function __construct(Request $request)
    {
        //$this->middleware('admin');

        
       $this->middleware('corporate');
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
        return view('corporate.dashboard');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        return view('corporate.account.profile');
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
            'legal_name' => 'required|max:255',
            'display_name' => 'required',
            'email' => 'required',
            'pan_no' => 'required',
            'address' => 'required',
            'picture' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
        ]);

        try{
            $corporate = Auth::guard('corporate')->user();
            $corporate->legal_name = $request->legal_name;
            $corporate->display_name = $request->display_name;
            $corporate->email = $request->email;
            $corporate->secondary_email = $request->secondary_email;
            $corporate->pan_no = $request->pan_no;
            $corporate->address = $request->address;
            if($request->hasFile('picture')){
                $corporate->picture = $request->picture->store('public/corporate/profile');  
                $corporate->picture = $request->picture->store('corporate/profile');  
            }
            $corporate->save();

            return redirect()->back()->with('flash_success','Profile Updated');
        }

        catch (Exception $e) {
             return $e;
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
        return view('corporate.account.change-password');
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

           $Corporate = Auth::guard('corporate')->user();

            if(password_verify($request->old_password, $Corporate->password))
            {
                $Corporate->password = bcrypt($request->password);
                $Corporate->save();

                return redirect()->back()->with('flash_success','Password Updated');
            }else{
                return redirect()->back()->with('flash_error','Old Password Not Matched');
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
                $page = 'User Ride Statement';
            }elseif($type == 'today'){
                $page = 'Today Statement - '. date('d M Y');
            }elseif($type == 'monthly'){
                $page = 'This Month Statement - '. date('F');
            }elseif($type == 'yearly'){
                $page = 'This Year Statement - '. date('Y');
            }
            $type_data = $type;
            return view('corporate.statement.overall', compact('page','type_data'));

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
                      ->where('corporate_id','=', Auth::user()->id)
                      ->where('created_at', '>=', $fromdate)
                      ->where('created_at', '<', $todate)
                      ->where('status', 'LIKE', '%'.$tripstatus.'%')
                      ->where('payment_mode','LIKE', '%'.$payment_type.'%');

        $cancel_rides = UserRequest::where('status','CANCELLED')
                      ->where('corporate_id','=', Auth::user()->id)
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
                $view =  route('corporate.requests.show',$ride->id);
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
}
