<?php

namespace App\Http\Controllers;

use App\Models\Demo;
use App\Models\Admin;
use App\Models\Account;
use App\Models\Dispatcher;
use App\Models\Corporate;
use App\Models\Partner;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Provider;
use App\Models\Hotel;
use App\Models\Country;
use App\Models\Location;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use App\Models\Customercare;
use \Carbon\Carbon;
use DB;
use Mail;
use Setting;
use DateTimeZone;
use App\Models\CorporateUser;
use App\Models\CorporateGroup;
use App\Helpers\Helper;

class DemoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $demos = Demo::orderBy('created_at' , 'desc')->get();
        return view('admin.demo.index', compact('demos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::all();
        $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        return view('admin.demo.create', compact('countries','tzlist'));
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
            'phone' => 'required|unique:demos|digits_between:6,13',
            'email' => 'required|unique:demos|unique:admins|unique:accounts|unique:dispatchers|unique:corporates',
            'password' => 'required',
            'country_id' => 'required',
            'seller_email' => 'required',
        ]);

        try {

            DB::beginTransaction();
            
            $country = Country::where('countryid','=',$request->country_id)->first();
            $location = Location::first();
            $service = ServiceType::first();
            
            $demo = $request->all();
            $demo['password'] = $request->password;
            $demo['phone'] = $request->phone;
            $demo['expires_at'] = Carbon::now()->addDays(15);
            $demo['status'] = 1;
            $demo['timezone'] = $request->timezoner;
            $demo = Demo::create($demo);

            $Admin = $request->all();
            $Admin['password'] = bcrypt($request->password);
            $Admin['admin_type'] = 1;
            $Admin['admin_address'] = $request->address;
            $Admin['admin_lat'] = $request->a_lat;
            $Admin['admin_long'] = $request->a_long;
            $Admin['admin_zoom'] = $request->zoom;
            $Admin['time_zone'] = $request->timezoner;
            $Admin['expires_at'] = Carbon::now()->addDays(15);
            $Admin = Admin::create($Admin);

            $account = $request->all();
            $account['admin_id'] = $Admin->id;
            $account['dial_code'] = $country->dial_code;
            $account['password'] = bcrypt($request->password);
            $account['expires_at'] = Carbon::now()->addDays(15);
            $account = Account::create($account);

            $Dispatcher = $request->all();
            $Dispatcher['admin_id'] = $Admin->id;
            $Dispatcher['dial_code'] = $country->dial_code;
            $Dispatcher['password'] = bcrypt($request->password);
            $Dispatcher['status'] = 1;
            $Dispatcher['dispatch_address'] = $request->address;
            $Dispatcher['dispatch_lat'] = $request->a_lat;
            $Dispatcher['dispatch_long'] = $request->a_long;
            $Dispatcher['dispatch_zoom'] = $request->zoom;
            $Dispatcher['expires_at'] = Carbon::now()->addDays(15);
            $Dispatcher = Dispatcher::create($Dispatcher);

            $corporate = $request->all();
            $corporate['admin_id'] = $Admin->id;
            $corporate['dial_code'] = $country->dial_code;
            $corporate['password'] = bcrypt($request->password);
            $corporate['legal_name'] = $request->name;
            $corporate['display_name'] = $request->name;
            $corporate['secondary_email'] = $request->email;
            $corporate['address'] = $country->name;
            $corporate['address'] = $request->address;
            $corporate['latitude'] = $request->a_lat;
            $corporate['longitude'] = $request->a_long;
            $corporate['zoom'] = $request->zoom;
            $corporate['expires_at'] = Carbon::now()->addDays(15);
            $corporate['pan_no'] = 1232134;
            $corporate['notify_customer'] = 1;
            $corporate['status'] = 1;
            $corporate = Corporate::create($corporate);

            $corporate_grp['corporate_id']=$corporate->id;
            $corporate_grp['payment_mode']='AUTOPAY';
            $corporate_grp['group_name']= $request->name;
            $corporate_grp = CorporateGroup::create($corporate_grp);

            $corporate_user['corporate_id']=$corporate->id;
            $corporate_user['corporate_group_id']=$corporate_grp->id;
            $corporate_user['emp_name']=$request->name;
            $corporate_user['emp_email']=$request->email;
            $corporate_user['emp_phone']=$request->phone;
            $corporate_user['address']=$request->address;
            $corporate_user = CorporateUser::create($corporate_user);

            $customercare = $request->all();
            $customercare['admin_id'] = $Admin->id;
            $customercare['dial_code'] = $country->dial_code;
            $customercare['password'] = bcrypt($request->password);
            $customercare['address'] = $request->address;
            $customercare['latitude'] = $request->a_lat;
            $customercare['longitude'] = $request->a_long;
            $customercare['zoom'] = $request->zoom;
            $customercare['status'] = 1;
            $customercare['expires_at'] = Carbon::now()->addDays(15);
            $customercare = Customercare::create($customercare);

            $partner = $request->all();
            $partner['admin_id'] = $Admin->id;
            $partner['dial_code'] = $country->dial_code;
            $partner['password'] = bcrypt($request->password);
            $partner['carrier_name'] = $country->name;
            $partner['carrier_percentage'] = 10;
            $partner['address'] = $country->name;
            $partner['pan_no'] = 1232134;
            $partner['status'] = 1;
            $partner['expires_at'] = Carbon::now()->addDays(15);
            $partner = Partner::create($partner);

            $user = $request->except('name');
            $user['admin_id'] = $Admin->id;
            $user['first_name'] =$request->name;
            $user['last_name'] = "Demo";
            $user['password'] = bcrypt($request->password);
            $user['dial_code'] = $country->dial_code;
            $user['gender'] = 'Male';
            $user['mobile'] = $request->phone;
            $user['status'] = 1;
            $user['corporate_user_id'] = $corporate->id;
            $user['corporate_status'] = 1;
            $user['expires_at'] = Carbon::now()->addDays(15);
            $user['refferal_code'] = Helper::generate_refferal_code();
            $user = User::create($user);

            $hotel = $request->all();
            $hotel['admin_id'] = $Admin->id;
            $hotel['dial_code'] = $country->dial_code;
            $hotel['password'] = bcrypt($request->password);
            $hotel['address'] = $country->name;
            $hotel['latitude'] = $request->a_lat;
            $hotel['longitude'] = $request->a_long;
            $hotel['status'] = 1;
            $hotel['expires_at'] = Carbon::now()->addDays(15);
            $hotel = Hotel::create($hotel);
            
            $vehicle['admin_id'] = $Admin->id;
            $vehicle['vehicle_name'] = $request->name.'1234';
            $vehicle['vehicle_no'] = $request->name.'1234';
            $vehicle['seat'] = 4;
            $vehicle['location_id'] = $location->id;
            $vehicle['partner_id'] = $partner->id;
            $vehicle['service_type_id'] = $service->id;
            $vehicle['vehicle_owner'] = $request->name;
            $vehicle['vehicle_model'] = 'Audi';
            $vehicle['vehicle_manufacturer'] = 'Audi';
            $vehicle['manufacturing_year'] = '2022';
            $vehicle['vehicle_brand'] = 'brand';
            $vehicle['vehicle_color'] = 'green';
            $vehicle['insurance_no'] = '54354';
            $vehicle['insurance_exp'] = '2022-07-17';
            $vehicle['status'] = 1;
            $vehicle = Vehicle::create($vehicle);

            $provider = $request->all();
            $provider['admin_id'] = $Admin->id;
            $provider['password'] = bcrypt($request->password);
            $provider['dial_code'] = $country->dial_code;
            $provider['mobile'] = $request->phone;
            $provider['gender'] = 'Male';
            $provider['account_status'] = 'approved';
            $provider['partner_id'] = $partner->id;
            $provider['service_type_id'] = $service->id;
            $provider['mapping_id'] = $vehicle->id;
            $provider['allowed_service'] = '1,2';
            $provider['language'] = '1,2,5';
            $provider['status'] = 'offline';
            $provider['address'] = $country->name;
            $provider['license_no'] = 454544;
            $provider['wallet_balance'] = 0;
            $provider['taxi_type'] = 1;
            $provider['latitude'] = $request->a_lat;
            $provider['longitude'] = $request->a_long;
            $provider['license_expire'] = Carbon::now()->addDays(15);
            $provider['expires_at'] = Carbon::now()->addDays(15);
            $provider = Provider::create($provider);

            $demo['dial_code'] = $country->dial_code;
            $demo['vehicle_name'] = $request->name.'1234';
            $demo['vehicle_no'] = $request->name.'1234';
            $demo['service_name'] = $service->name;
            $demo['status'] = 1;

            if(Setting::get('mail_enable', 0) == 1) {
                Mail::send('emails.demo', ['demo' => $demo], function ($message) use ($demo){
                    $message->to($demo->seller_email, $demo->name)->subject(config('app.name').' Demo Credentials');
                });

                Mail::send('emails.demo', ['demo' => $demo], function ($message) use ($demo){
                    $message->to('sales@unicotaxi.com', $demo->name)->subject(config('app.name').' Demo Credentials');
                });
            }
            DB::commit();

            return back()->with('flash_success','Account Details Created Successfully');

        } catch (Exception $e) {
            DB::rollback();
            return back()->with('flash_error','Please try again later !!');
           // return $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Demo  $demo
     * @return \Illuminate\Http\Response
     */
    public function show(Demo $demo)
    {
        $provider = Provider::where('email','=',$demo->email)->first();
        $country = Country::where('countryid','=',$demo->country_id)->first();
        $vehicle=null;
        $service=null;
        if($provider !=null){
            $vehicle = Vehicle::where('id','=',$provider->mapping_id)->first();
            $service = ServiceType::where('id','=',$vehicle->service_type_id)->first();
        }
        return view('admin.demo.view', compact('demo','country','provider','vehicle','service'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Demo  $demo
     * @return \Illuminate\Http\Response
     */
    public function edit(Demo $demo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Demo  $demo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Demo $demo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Demo  $demo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Demo $demo)
    {
        try {

            Admin::where('email','=',$demo->email)->delete();
            Account::where('email','=',$demo->email)->delete();
            Dispatcher::where('email','=',$demo->email)->delete();
            Corporate::where('email','=',$demo->email)->delete();
            Partner::where('email','=',$demo->email)->delete();
            User::where('email','=',$demo->email)->delete();
            Hotel::where('email','=',$demo->email)->delete();
            Customercare::where('email','=',$demo->email)->delete();
            $provider = Provider::where('email','=',$demo->email)->first();
            if($provider !=null){
                Vehicle::where('id','=',$provider->mapping_id)->delete();
                $provider->delete();
            }
            $demo->delete();
            return back()->with('message', 'Demo account deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'Demo account Found');
        }
    }

    public function renue($id)
    {
        try {
            $demo=Demo::where('id','=',$id)->update(['expires_at' => Carbon::now()->addDays(15)]);
            $demo_id=Demo::where('id','=',$id)->first();
            $user=User::where('email','=',$demo_id->email)->update(['expires_at' => Carbon::now()->addDays(15)]);
            $provider=Provider::where('email','=',$demo_id->email)->update(['expires_at' => Carbon::now()->addDays(15)]);
            return back()->with('message', 'Demo renued successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'Demo account Found');
        }
    }

    public function expire($id)
    {
        try {
            $demo=Demo::where('id','=',$id)->update(['expires_at' => Carbon::now()->subDays(15)]);
            $demo_id=Demo::where('id','=',$id)->first();
            $user=User::where('email','=',$demo_id->email)->update(['expires_at' => Carbon::now()->subDays(15)]);
            $provider=Provider::where('email','=',$demo_id->email)->update(['expires_at' => Carbon::now()->subDays(15)]);
            return back()->with('message', 'Demo Account Expired successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'Demo account Found');
        }
    }

}
