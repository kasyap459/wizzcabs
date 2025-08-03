<?php

namespace App\Http\Controllers\Resource;

use App\Models\Hotel;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Exception;
use Setting;
use \Carbon\Carbon;
use App\Models\UserRequest;
use App\Models\UserRequestPayment;
use Auth;
use App\Models\Admin;

class HotelResource extends Controller
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
    {       //dd(Carbon::now());
        $hotels = Hotel::orderBy('created_at' , 'desc')->get();
        return view('admin.hotels.index', compact('hotels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::all();
        return view('admin.hotels.create', compact('countries'));
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
            'email' => 'required|unique:hotels,email|email|max:255',
            'password' => 'required|min:6|confirmed',
            'country_id' => 'required',
        ]);

        try{

            $hotel = $request->all();
            if($request->hasFile('picture')) {
                $hotel['picture'] = $request->picture->store('public/hotel');
                $hotel['picture'] = $request->picture->store('hotel');
            }
            $country = Country::where('countryid','=',$request->country_id)->first();
            if(Auth::guard('admin')->user()->admin_type !=0){
                $hotel['admin_id'] = Auth::guard('admin')->user()->id;
            }
            $hotel['dial_code'] = $country->dial_code;
            $hotel['password'] = bcrypt($request->password);
            $hotel['status'] = 1;
            $hotel = Hotel::create($hotel);

            return back()->with('flash_success','Hotel Details Saved Successfully');

        } 

        catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $countries = Country::all();
            $hotel = Hotel::findOrFail($id);
            return view('admin.hotels.edit',compact('hotel','countries'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\partner  $partner
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

            $hotel = Hotel::findOrFail($id);
            $country = Country::where('countryid','=',$request->country_id)->first();
            if($request->hasFile('picture')) {
                \Storage::delete($hotel->picture);

                $hotel->picture = $request->picture->store('public/hotel');
                $hotel->picture = $request->picture->store('hotel');
            }
            $hotel->dial_code = $country->dial_code;
            $hotel->country_id = $country->countryid;
            $hotel->name = $request->name;
            $hotel->mobile = $request->mobile;
            $hotel->email = $request->email;
            $hotel->address = $request->address ? : '';
            if($hotel->latitude){
            $hotel->latitude = $request->latitude ? : '';
            $hotel->longitude = $request->longitude ? : '';
            }

            if($request->filled('password')){
                $hotel->password = bcrypt($request->password);
            }
            $hotel->save();

            return redirect()->route('admin.hotel.index')->with('flash_success', 'Hotels Updated Successfully');    
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Hotel Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        try {
            Hotel::find($id)->delete();
            return back()->with('message', 'Hotel deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'Hotel Not Found');
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
        Hotel::where('id',$id)->update(['status' => 1]);
        return back()->with('flash_success', "Hotel activated");
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function inactive($id)
    {
        
        Hotel::where('id',$id)->update(['status' => 0]);
        return back()->with('flash_success', "Hotel inactivated");
    }

}
