<?php

namespace App\Http\Controllers\Resource;

use Storage;
use App\Models\Location;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Exception;
use Setting;
use App\Models\LocationWiseFare;
use App\Models\PoiFare;
use App\Models\Admin;
use Auth;
use App\Models\RestrictLocation;
class LocationResource extends Controller
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
        $locations = Location::with('country')->get();
        return view('admin.locations.index', compact('locations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::all();
        return view('admin.locations.create', compact('countries'));
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
            'location_name' => 'required|max:255',
            'tlatitude' => 'required',
            'tlongitude' => 'required',
            'clatitude' => 'required',
            'clongitude' => 'required',
        ]);

        try{
            $location = $request->all();
            $country = Country::where('name','=',$request->iCountry)->first();
            $location['country_id'] = $country->countryid;
            $location = Location::create($location);
            return back()->with('flash_success','Location Details Saved Successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'Location Not Found');
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
            $location = Location::findOrFail($id);
            return view('admin.location.user-details', compact('location'));
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
            $countries = Country::all();
            $location = Location::findOrFail($id);
            return view('admin.locations.edit',compact('location','countries'));
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
            'location_name' => 'required|max:255',
            'iCountry' => 'required',
            'tlatitude' => 'required',
            'tlongitude' => 'required',
            'clatitude' => 'required',
            'clongitude' => 'required',
        ]);

        try {

            $location = Location::findOrFail($id);
            $country = Country::where('name','=',$request->iCountry)->first();
            $location->location_name = $request->location_name;
            $location->country_id = $country->countryid;
            $location->tlatitude = $request->tlatitude;
            $location->tlongitude = $request->tlongitude;
            $location->clatitude = $request->clatitude;
            $location->clongitude = $request->clongitude;
            $location->save();

            return redirect()->route('admin.location.index')->with('flash_success', 'Location Updated Successfully');    
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Location Not Found');
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
            LocationWiseFare::where('source_addr','=',$id)->delete();
            LocationWiseFare::where('destination_addr','=',$id)->delete();
            PoiFare::where('poi_s_addr','=',$id)->delete();
            PoiFare::where('poi_d_addr','=',$id)->delete();
            RestrictLocation::where('location_id','=',$id)->delete();
            Location::find($id)->delete();
            return back()->with('message', 'Location deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'Location Not Found');
        }
    }

}
