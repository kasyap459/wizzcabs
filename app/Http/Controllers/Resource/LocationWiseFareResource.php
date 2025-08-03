<?php

namespace App\Http\Controllers\Resource;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Setting;
use Exception;
use App\Helpers\Helper;
use App\Models\LocationWiseFare;
use App\Models\Country;
use App\Models\Location;
use App\Models\ServiceType;
use App\Models\Admin;
use Auth;
class LocationWiseFareResource extends Controller
{   
    public function __construct(Request $request)
    {   
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
        $locationfares = LocationWiseFare::with('location_source','location_dest','service_type')->get();
        return view('admin.locationfare.index', compact('locationfares'));  
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $locations = Location::all();
        $services = ServiceType::all();
        return view('admin.locationfare.create', compact('locations','services'));
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
            'source_addr' => 'required',
            'destination_addr' => 'required',
            'service_type_id' =>'required',
            't1_stime' => 'required',
            't2_stime' => 'required',
            // 't3_stime' => 'required',
            // 't4_stime' => 'required',
            't1_etime' => 'required',
            't2_etime' => 'required',
            // 't3_etime' => 'required',
            // 't4_etime' => 'required',
            't1_flat' => 'required',
            't2_flat' => 'required',
            // 't3_flat' => 'required',
            // 't4_flat' => 'required',
            't1_s_stime' => 'required',
            't2_s_stime' => 'required',
            // 't3_s_stime' => 'required',
            // 't4_s_stime' => 'required',
            't1_s_etime' => 'required',
            't2_s_etime' => 'required',
            // 't3_s_etime' => 'required',
            // 't4_s_etime' => 'required',
            't1_s_flat' => 'required',
            't2_s_flat' => 'required',
            // 't3_s_flat' => 'required',
            // 't4_s_flat' => 'required',     
        ]);

        try {
            $locationfare = $request->all();
            $locationfare['status'] = 1;
            $locationfare = LocationWiseFare::create($locationfare);
            return back()->with('flash_success','Location wise fare Saved Successfully');

        } catch (Exception $e) {
            return back()->$e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ServiceType  $serviceType
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $locationfare = LocationWiseFare::findOrFail($id);
            return view('admin.locationfare.show', compact('locationfare'));
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Location wise fare Not Found');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ServiceType  $serviceType
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $locations = Location::all();
            $services = ServiceType::all();
            $locationfare = LocationWiseFare::findOrFail($id);
            return view('admin.locationfare.edit',compact('locationfare','locations','services'));
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Location wise fare Type Not Found');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ServiceType  $serviceType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'source_addr' => 'required',
            'destination_addr' => 'required',
            'service_type_id' =>'required',
            't1_stime' => 'required',
            't2_stime' => 'required',
            // 't3_stime' => 'required',
            // 't4_stime' => 'required',
            't1_etime' => 'required',
            't2_etime' => 'required',
            // 't3_etime' => 'required',
            // 't4_etime' => 'required',
            't1_flat' => 'required',
            't2_flat' => 'required',
            // 't3_flat' => 'required',
            // 't4_flat' => 'required',
            't1_s_stime' => 'required',
            't2_s_stime' => 'required',
            // 't3_s_stime' => 'required',
            // 't4_s_stime' => 'required',
            't1_s_etime' => 'required',
            't2_s_etime' => 'required',
            // 't3_s_etime' => 'required',
            // 't4_s_etime' => 'required',
            't1_s_flat' => 'required',
            't2_s_flat' => 'required',
            // 't3_s_flat' => 'required',
            // 't4_s_flat' => 'required',   
        ]);

        try {

            $locationfare = LocationWiseFare::findOrFail($id);
            $locationfare->source_addr = $request->source_addr;
            $locationfare->destination_addr = $request->destination_addr;
            $locationfare->service_type_id = $request->service_type_id;
            $locationfare->reverse_loc = $request->reverse_loc;
            $locationfare->t1_stime = $request->t1_stime;
            $locationfare->t2_stime = $request->t2_stime;
            $locationfare->t3_stime = $request->t3_stime;
            $locationfare->t4_stime = $request->t4_stime;
            $locationfare->t1_etime = $request->t1_etime;
            $locationfare->t2_etime = $request->t2_etime;
            $locationfare->t3_etime = $request->t3_etime;
            $locationfare->t4_etime = $request->t4_etime;
            $locationfare->t1_flat = $request->t1_flat;
            $locationfare->t2_flat = $request->t2_flat;
            $locationfare->t3_flat = $request->t3_flat;
            $locationfare->t4_flat = $request->t4_flat;
            $locationfare->t1_s_stime = $request->t1_s_stime;
            $locationfare->t2_s_stime = $request->t2_s_stime;
            $locationfare->t3_s_stime = $request->t3_s_stime;
            $locationfare->t4_s_stime = $request->t4_s_stime;
            $locationfare->t1_s_etime = $request->t1_s_etime;
            $locationfare->t2_s_etime = $request->t2_s_etime;
            $locationfare->t3_s_etime = $request->t3_s_etime;
            $locationfare->t4_s_etime = $request->t4_s_etime;
            $locationfare->t1_s_flat = $request->t1_s_flat;
            $locationfare->t2_s_flat = $request->t2_s_flat;
            $locationfare->t3_s_flat = $request->t3_s_flat;
            $locationfare->t4_s_flat = $request->t4_s_flat;
            $locationfare->save();

            return redirect()->route('admin.locationfare.index')->with('flash_success', 'Location wise fare Updated Successfully');    
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Location wise fare Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ServiceType  $serviceType
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            LocationWiseFare::find($id)->delete();
            return back()->with('message', 'Location wise fare deleted successfully');
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Location wise fare Not Found');
        } catch (Exception $e) {
            return back()->with('flash_error', 'Location wise fare Not Found');
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
        LocationWiseFare::where('id',$id)->update(['status' => 1]);
        return back()->with('flash_success', "Location wise fare activated");
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function inactive($id)
    {
        
        LocationWiseFare::where('id',$id)->update(['status' => 0]);
        return back()->with('flash_success', "Location wise fare inactivated");
    }
}