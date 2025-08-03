<?php

namespace App\Http\Controllers\Resource;

use App\Models\Dispatcher;
use App\Models\Partner;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Exception;
use Setting;
use App\Models\Admin;
use Auth;
class DispatcherResource extends Controller
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
        $dispatchers = Dispatcher::with('partner')->orderBy('created_at' , 'desc')->get();
        return view('admin.dispatcher.index', compact('dispatchers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::all();
        $partners = Partner::all();
        return view('admin.dispatcher.create', compact('countries','partners'));
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
            'email' => 'required|unique:dispatchers,email|email|max:255',
            'password' => 'required|min:6|confirmed',
            'country_id' => 'required',
        ]);

        try{

            $Dispatcher = $request->all();
            $country = Country::where('countryid','=',$request->country_id)->first();
            if(Auth::guard('admin')->user()->admin_type !=0){
                $Dispatcher['admin_id'] = Auth::guard('admin')->user()->id;
            }
            if($request->fleet_id != ""){
                $adminId = Partner::where('id','=',$request->fleet_id)->first();
                if($adminId != null){
                    if($adminId->admin_id !=null){
                        $Dispatcher['admin_id'] = $adminId->admin_id;
                    }
                }
                $Dispatcher['partner_id'] = $request->fleet_id;

            }
            $Dispatcher['dial_code'] = $country->dial_code;
            $Dispatcher['password'] = bcrypt($request->password);
            $Dispatcher['status'] = 1;
            $Dispatcher = Dispatcher::create($Dispatcher);

            return back()->with('flash_success','Dispatcher Details Saved Successfully');

        } 

        catch (Exception $e) {
            return back()->$e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Dispatcher  $dispatcher
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Dispatcher  $dispatcher
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $countries = Country::all();
            $dispatcher = Dispatcher::findOrFail($id);
            $partners = Partner::all();
            return view('admin.dispatcher.edit',compact('dispatcher','countries','partners'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Dispatcher  $dispatcher
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

            $dispatcher = Dispatcher::findOrFail($id);
            $country = Country::where('countryid','=',$request->country_id)->first();
            $dispatcher->dial_code = $country->dial_code;
            $dispatcher->country_id = $country->countryid;
            $dispatcher->name = $request->name;
            $dispatcher->mobile = $request->mobile;
            $dispatcher->partner_id = $request->partner_id ? : 0;
            $dispatcher->email = $request->email;
            if($request->filled('password')){
                $dispatcher->password = bcrypt($request->password);
            }
            $dispatcher->save();

            return redirect()->route('admin.dispatch-manager.index')->with('flash_success', 'Dispatcher Updated Successfully');    
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Dispatcher Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Dispatcher  $dispatcher
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        try {
            Dispatcher::find($id)->delete();
            return back()->with('message', 'Dispatcher deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'Dispatcher Not Found');
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
        Dispatcher::where('id',$id)->update(['status' => 1]);
        return back()->with('flash_success', "Dispatcher activated");
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function inactive($id)
    {
        
        Dispatcher::where('id',$id)->update(['status' => 0]);
        return back()->with('flash_success', "Dispatcher inactivated");
    }
    
}
