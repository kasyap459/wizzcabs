<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Setting;
use App\Models\Customercare;
use App\Models\Country;
use Exception;
use Auth;
use App\Models\Admin;


class CustomerResource extends Controller
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
        $customers = Customercare::orderBy('created_at' , 'desc')->get();
        return view('admin.customercare.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::all();
        return view('admin.customercare.create', compact('countries'));
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
            'email' => 'required|unique:customercare,email|email|max:255',
            'password' => 'required|min:6|confirmed',
            'country_id' => 'required',
        ]);

        try{

            $customer = $request->all();
            // return $customer;
            $country = Country::where('countryid','=',$request->country_id)->first();
            if(Auth::guard('admin')->user()->admin_type !=0){
                $customer['admin_id'] = Auth::guard('admin')->user()->id;
            }
            $customer['dial_code'] = $country->dial_code;
            $customer['password'] = bcrypt($request->password);
            $customer['status'] = 1;
            $customer = Customercare::create($customer);

            return back()->with('flash_success','Customer Details Saved Successfully');

        } 

        catch (Exception $e) {
            return back()->$e;
        } 
   }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $countries = Country::all();
            $customer = Customercare::findOrFail($id);
            return view('admin.customercare.edit',compact('customer','countries'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
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
        $this->validate($request, [
            'name' => 'required|max:255',
            'mobile' => 'digits_between:6,13',
            'email' => 'required',
            'country_id' => 'required',
        ]);

        try {

            $customer = Customercare::findOrFail($id);
            $country = Country::where('countryid','=',$request->country_id)->first();
            $customer->dial_code = $country->dial_code;
            $customer->country_id = $country->countryid;
            $customer->name = $request->name;
            $customer->mobile = $request->mobile;
            $customer->email = $request->email;
            if($request->filled('password')){
                $customer->password = bcrypt($request->password);
            }
            $customer->save();
            return redirect()->route('admin.customer-care.index')->with('flash_success', 'Customer Updated Successfully');    
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Customer Not Found');
        }  
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
            Customercare::find($id)->delete();
            return back()->with('message', 'Customercare deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'Customercare Not Found');
        }
    }

    public function active($id)
    {
        Customercare::where('id',$id)->update(['status' => 1]);
        return back()->with('flash_success', "Dispatcher activated");
        
    }

    public function inactive($id)
    {
        Customercare::where('id',$id)->update(['status' => 0]);
        return back()->with('flash_success', "Dispatcher inactivated");
    }

}
