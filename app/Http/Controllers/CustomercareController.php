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

class CustomercareController extends Controller
{
    public function __construct(Request $request)
    {
        //$this->middleware('admin');

        
       $this->middleware('customercare');
        $this->middleware(function ($request, $next) {
        $this->id = Auth::user()->id;
        $this->email = Auth::user()->email;
        $this->admin_id = Auth::user()->admin_id;
       
        if($this->admin_id == null){
            
             $admin = Admin::where('id','=',$this->id)->first();
           
             if($admin->admin_type != 0 && $admin->time_zone != null){
                 date_default_timezone_set($admin->time_zone);
                
             }
         } else {

            // $admin = Admin::where('id','=',$this->admin_id)->first();
         
            //  if($admin->admin_type != 0 && $admin->time_zone != null){
            //      date_default_timezone_set($admin->time_zone);
                 
            //  }
         }
            
        return $next($request);
    });
 }

    public function dashboard()
    {  
        return view('customercare.dashboard');
    }
}
