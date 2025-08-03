<?php

namespace App\Http\Controllers\Resource;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Setting;
use Exception;
use App\Helpers\Helper;
use App\Models\FareModel;
use App\Models\Admin;
use App\Models\Country;
use App\Models\ServiceType;
use Auth;
class FareModelResource extends Controller
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
        /*$faremodels = FareModel::join('service_types', 'service_types.id', '=', 'fare_models.service_type_id')
            ->join('countries', 'countries.countryid', '=', 'fare_models.country_id')
            ->get();*/
          


        $faremodels = FareModel::join('service_types', 'service_types.id', '=', 'fare_models.service_type_id')
            ->leftJoin('countries', 'countries.countryid', '=', 'fare_models.country_id')
            ->select('fare_models.status','fare_models.id','service_types.image','service_types.name as servicename','countries.name')
            ->get();
        return view('admin.faremodel.index', compact('faremodels'));  
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $check = FareModel::get()->pluck('service_type_id');
        $countries = Country::all();
        $services = ServiceType::where('status',1)->whereNotIn('id',$check)->get();
        return view('admin.faremodel.create', compact('countries','services'));
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
            'service_type_id' => 'required|max:255',
            't1_stime' => 'required',
            't2_stime' => 'required',
            // 't3_stime' => 'required',
            // 't4_stime' => 'required',
            't1_etime' => 'required',
            't2_etime' => 'required',
            // 't3_etime' => 'required',
            // 't4_etime' => 'required',
            't1_base' => 'required',
            't2_base' => 'required',
            // 't3_base' => 'required',
            // 't4_base' => 'required',
            't1_base_dist' => 'required',
            't2_base_dist' => 'required',
            // 't3_base_dist' => 'required',
            // 't4_base_dist' => 'required',
            't1_distance' => 'required',
            't2_distance' => 'required',
            // 't3_distance' => 'required',
            // 't4_distance' => 'required',
            't1_minute' => 'required',
            't2_minute' => 'required',
            // 't3_minute' => 'required',
            // 't4_minute' => 'required',
            't1_waiting' => 'required',
            't2_waiting' => 'required',
            // 't3_waiting' => 'required',
            // 't4_waiting' => 'required',
            // 't1_cancel' => 'required',
            // 't2_cancel' => 'required',
            // 't3_cancel' => 'required',
            // 't4_cancel' => 'required',
            't1_s_stime' => 'required',
            't2_s_stime' => 'required',
            // 't3_s_stime' => 'required',
            // 't4_s_stime' => 'required',
            't1_s_etime' => 'required',
            't2_s_etime' => 'required',
            // 't3_s_etime' => 'required',
            // 't4_s_etime' => 'required',
            't1_s_base' => 'required',
            't2_s_base' => 'required',
            // 't3_s_base' => 'required',
            // 't4_s_base' => 'required',
            't1_s_base_dist' => 'required',
            't2_s_base_dist' => 'required',
            // 't3_s_base_dist' => 'required',
            // 't4_s_base_dist' => 'required',
            't1_s_distance' => 'required',
            't2_s_distance' => 'required',
            // 't3_s_distance' => 'required',
            // 't4_s_distance' => 'required',
            't1_s_minute' => 'required',
            't2_s_minute' => 'required',
            // 't3_s_minute' => 'required',
            // 't4_s_minute' => 'required',
            't1_s_waiting' => 'required',
            't2_s_waiting' => 'required',
            // 't3_s_waiting' => 'required',
            // 't4_s_waiting' => 'required',
            // 't1_s_cancel' => 'required',
            // 't2_s_cancel' => 'required',
            // 't3_s_cancel' => 'required',
            // 't4_s_cancel' => 'required',
            's1_waiting' => 'required',
            's2_waiting' => 'required',        
            's3_waiting' => 'required',
            's4_waiting' => 'required',
        ]);

        try {
            $faremodel = $request->all();
            $faremodel['status'] = 1;
            $faremodel = FareModel::create($faremodel);

            return back()->with('flash_success','Fare Saved Successfully');

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
            $faremodel = FareModel::findOrFail($id);
            return view('admin.faremodel.show', compact('faremodel'));
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Fare Type Not Found');
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

            $countries = Country::all();
            $services = ServiceType::all();
            $faremodel = FareModel::findOrFail($id);
            return view('admin.faremodel.edit',compact('faremodel','countries','services'));
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Fare Type Not Found');
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
            'service_type_id' => 'required|max:255',
            't1_stime' => 'required',
            't2_stime' => 'required',
            // 't3_stime' => 'required',
            // 't4_stime' => 'required',
            't1_etime' => 'required',
            't2_etime' => 'required',
            // 't3_etime' => 'required',
            // 't4_etime' => 'required',
            't1_base' => 'required',
            't2_base' => 'required',
            // 't3_base' => 'required',
            // 't4_base' => 'required',
            't1_base_dist' => 'required',
            't2_base_dist' => 'required',
            // 't3_base_dist' => 'required',
            // 't4_base_dist' => 'required',
            't1_distance' => 'required',
            't2_distance' => 'required',
            // 't3_distance' => 'required',
            // 't4_distance' => 'required',
            't1_minute' => 'required',
            't2_minute' => 'required',
            // 't3_minute' => 'required',
            // 't4_minute' => 'required',
            't1_waiting' => 'required',
            't2_waiting' => 'required',
            // 't3_waiting' => 'required',
            // 't4_waiting' => 'required',
            // 't1_cancel' => 'required',
            // 't2_cancel' => 'required',
            // 't3_cancel' => 'required',
            // 't4_cancel' => 'required',
            't1_s_stime' => 'required',
            't2_s_stime' => 'required',
            // 't3_s_stime' => 'required',
            // 't4_s_stime' => 'required',
            't1_s_etime' => 'required',
            't2_s_etime' => 'required',
            // 't3_s_etime' => 'required',
            // 't4_s_etime' => 'required',
            't1_s_base' => 'required',
            't2_s_base' => 'required',
            // 't3_s_base' => 'required',
            // 't4_s_base' => 'required',
            't1_s_base_dist' => 'required',
            't2_s_base_dist' => 'required',
            // 't3_s_base_dist' => 'required',
            // 't4_s_base_dist' => 'required',
            't1_s_distance' => 'required',
            't2_s_distance' => 'required',
            // 't3_s_distance' => 'required',
            // 't4_s_distance' => 'required',
            't1_s_minute' => 'required',
            't2_s_minute' => 'required',
            // 't3_s_minute' => 'required',
            // 't4_s_minute' => 'required',
            't1_s_waiting' => 'required',
            't2_s_waiting' => 'required',
            // 't3_s_waiting' => 'required',
            // 't4_s_waiting' => 'required',
            // 't1_s_cancel' => 'required',
            // 't2_s_cancel' => 'required',
            's1_waiting' => 'required',
            's2_waiting' => 'required',        
            's3_waiting' => 'required',
            's4_waiting' => 'required',
            // 't3_s_cancel' => 'required',
            // 't4_s_cancel' => 'required',    
        ]);

        try {

            $faremodel = FareModel::findOrFail($id);
            $faremodel->service_type_id = $request->service_type_id;
            $faremodel->t1_stime = $request->t1_stime;
            $faremodel->t2_stime = $request->t2_stime;
            // $faremodel->t3_stime = $request->t3_stime;
            // $faremodel->t4_stime = $request->t4_stime;
            $faremodel->t1_etime = $request->t1_etime;
            $faremodel->t2_etime = $request->t2_etime;

            // $faremodel->t3_etime = $request->t3_etime;
            // $faremodel->t4_etime = $request->t4_etime;
            $faremodel->t1_base = $request->t1_base;
            $faremodel->t2_base = $request->t2_base;
            // $faremodel->t3_base = $request->t3_base;
            // $faremodel->t4_base = $request->t4_base;
            $faremodel->t1_base_dist = $request->t1_base_dist;
            $faremodel->t2_base_dist = $request->t2_base_dist;
            // $faremodel->t3_base_dist = $request->t3_base_dist;
            // $faremodel->t4_base_dist = $request->t4_base_dist;
            $faremodel->t1_distance = $request->t1_distance;
            $faremodel->t2_distance = $request->t2_distance;
            // $faremodel->t3_distance = $request->t3_distance;
            // $faremodel->t4_distance = $request->t4_distance;
            $faremodel->t1_minute = $request->t1_minute;
            $faremodel->t2_minute = $request->t2_minute;
            // $faremodel->t3_minute = $request->t3_minute;
            // $faremodel->t4_minute = $request->t4_minute;
            $faremodel->t1_waiting = $request->t1_waiting;
            $faremodel->t2_waiting = $request->t2_waiting;
            $faremodel->s1_waiting = $request->s1_waiting;
            $faremodel->s2_waiting = $request->s2_waiting;
            // $faremodel->t3_waiting = $request->t3_waiting;
            // $faremodel->t4_waiting = $request->t4_waiting;
            $faremodel->t1_cancel = $request->t1_cancel;
            $faremodel->t2_cancel = $request->t2_cancel;
            // $faremodel->t3_cancel = $request->t3_cancel;
            // $faremodel->t4_cancel = $request->t4_cancel;
            $faremodel->t1_s_stime = $request->t1_s_stime;
            $faremodel->t2_s_stime = $request->t2_s_stime;
            // $faremodel->t3_s_stime = $request->t3_s_stime;
            // $faremodel->t4_s_stime = $request->t4_s_stime;
            $faremodel->t1_s_etime = $request->t1_s_etime;
            $faremodel->t2_s_etime = $request->t2_s_etime;
            // $faremodel->t3_s_etime = $request->t3_s_etime;
            // $faremodel->t4_s_etime = $request->t4_s_etime;
            $faremodel->t1_s_base = $request->t1_s_base;
            $faremodel->t2_s_base = $request->t2_s_base;
            // $faremodel->t3_s_base = $request->t3_s_base;
            // $faremodel->t4_s_base = $request->t4_s_base;
            $faremodel->t1_s_base_dist = $request->t1_s_base_dist;
            $faremodel->t2_s_base_dist = $request->t2_s_base_dist;
            // $faremodel->t3_s_base_dist = $request->t3_s_base_dist;
            // $faremodel->t4_s_base_dist = $request->t4_s_base_dist;
            $faremodel->t1_s_distance = $request->t1_s_distance;
            $faremodel->t2_s_distance = $request->t2_s_distance;
            // $faremodel->t3_s_distance = $request->t3_s_distance;
            // $faremodel->t4_s_distance = $request->t4_s_distance;
            $faremodel->t1_s_minute = $request->t1_s_minute;
            $faremodel->t2_s_minute = $request->t2_s_minute;
            // $faremodel->t3_s_minute = $request->t3_s_minute;
            // $faremodel->t4_s_minute = $request->t4_s_minute;
            $faremodel->t1_s_waiting = $request->t1_s_waiting;
            $faremodel->t2_s_waiting = $request->t2_s_waiting;
            $faremodel->s3_waiting = $request->s3_waiting;
            $faremodel->s4_waiting = $request->s4_waiting;
            // $faremodel->t3_s_waiting = $request->t3_s_waiting;
            // $faremodel->t4_s_waiting = $request->t4_s_waiting;
            $faremodel->t1_s_cancel = $request->t1_s_cancel;
            $faremodel->t2_s_cancel = $request->t2_s_cancel;
            // $faremodel->t3_s_cancel = $request->t3_s_cancel;
            // $faremodel->t4_s_cancel = $request->t4_s_cancel;
            $faremodel->s1_stime = $request->s1_stime;
            $faremodel->s2_stime = $request->s2_stime;
            $faremodel->s1_etime = $request->s1_etime;
            $faremodel->s2_etime = $request->s2_etime;
            $faremodel->s1_percent = $request->s1_percent;
            $faremodel->s2_percent = $request->s2_percent;
	        $faremodel->s1_enable = $request->s1_enable;
            $faremodel->s2_enable = $request->s2_enable;

            $faremodel->t1_base_wait = $request->t1_base_wait;
            $faremodel->t2_base_wait = $request->t2_base_wait;

            $faremodel->t1s_base_wait = $request->t1s_base_wait;
            $faremodel->t2s_base_wait = $request->t2s_base_wait;

            $faremodel->t3_base_wait = $request->t3_base_wait;
            $faremodel->t4_base_wait = $request->t4_base_wait;

            $faremodel->t3s_base_wait = $request->t3s_base_wait;
            $faremodel->t4s_base_wait = $request->t4s_base_wait;

            $faremodel->save();

            return redirect()->route('admin.faremodel.index')->with('flash_success', 'Fare Updated Successfully');    
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Fare Type Not Found');
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
            FareModel::find($id)->delete();
            return back()->with('message', 'Fare Type deleted successfully');
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Fare Type Not Found');
        } catch (Exception $e) {
            return back()->with('flash_error', 'Fare Type Not Found');
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
        FareModel::where('id',$id)->update(['status' => 1]);
        return back()->with('flash_success', "Fare Model activated");
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function inactive($id)
    {
        
        FareModel::where('id',$id)->update(['status' => 0]);
        return back()->with('flash_success', "Fare Model inactivated");
    }
}