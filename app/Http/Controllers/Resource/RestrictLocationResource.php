<?php

namespace App\Http\Controllers\Resource;

use Storage;
use App\Models\RestrictLocation;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Exception;
use Auth;
use Setting;
use App\Models\Admin;
class RestrictLocationResource extends Controller
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
        $restricts = RestrictLocation::get();
        return view('admin.restrictlocations.index', compact('restricts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $locations = Location::all();
        return view('admin.restrictlocations.create', compact('locations'));
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
            'location_id' => 'required|max:255',
            'restrict_area' => 'required',
        ]);
        
        try{
            $restrict = $request->all();
            $restrict['status'] = 1;
            $restrict = RestrictLocation::create($restrict);
            return back()->with('flash_success','Restrict Details Saved Successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'Restrict Not Found');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $restrict = RestrictLocation::findOrFail($id);
            return view('admin.restrictlocations.user-details', compact('restrict'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $restrict = RestrictLocation::findOrFail($id);
            $locations = Location::all();
            return view('admin.restrictlocations.edit',compact('restrict','locations'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'location_id' => 'required|max:255',
            'restrict_area' => 'required',
        ]);

        try {

            $restrict = RestrictLocation::findOrFail($id);
            $restrict->location_id = $request->location_id;
            $restrict->restrict_area = $request->restrict_area;
            $restrict->s_time = $request->s_time;
            $restrict->e_time = $request->e_time;
            $restrict->save();

            return redirect()->route('admin.restrict-location.index')->with('flash_success', 'Restrict Details Updated Successfully');    
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Restrict Details Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            RestrictLocation::find($id)->delete();
            return back()->with('message', 'Restrict Details deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'Restrict Details Not Found');
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
        RestrictLocation::where('id',$id)->update(['status' => 1]);
        return back()->with('flash_success', "Restrict area activated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function inactive($id)
    {
        RestrictLocation::where('id',$id)->update(['status' => 0]);
        return back()->with('flash_success', "Restrict area inactivated");
    }

}
