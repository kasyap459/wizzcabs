<?php

namespace App\Http\Controllers\Resource;

use App\Models\DriverDocList;
use App\Models\VehicleDocList;
use App\Models\CarrierDocList;
use App\Models\CorporateDocList;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Exception;
use Setting;
use App\Models\Admin;
use Auth;

class DocListResource extends Controller
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
        $driverlists = DriverDocList::orderBy('created_at' , 'desc')->get();
        return view('admin.document.index', compact('driverlists'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function vehicleindex()
    {
        $vehiclelists = VehicleDocList::orderBy('created_at' , 'desc')->get();
        return view('admin.document.vehicleindex', compact('vehiclelists'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function partnerindex()
    {
        $carrierlists = CarrierDocList::orderBy('created_at' , 'desc')->get();
        return view('admin.document.partnerindex', compact('carrierlists'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function corporateindex()
    {
        $corporatelists = CorporateDocList::orderBy('created_at' , 'desc')->get();
        return view('admin.document.corporateindex', compact('corporatelists'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('admin.document.create');
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
            'doc_name' => 'required|max:255',
        ]);

        try{

            DriverDocList::create($request->all());
            return redirect()->route('admin.document.index')->with('flash_success','Document Saved Successfully');
        } 

        catch (Exception $e) {
            return back()->with('flash_error', 'Document Not Found');
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function vehiclestore(Request $request)
    {
        $this->validate($request, [
            'doc_name' => 'required|max:255',
        ]);

        try{

            VehicleDocList::create($request->all());
            return redirect()->route('admin.vehicledocument.index')->with('flash_success','Document Saved Successfully');
        } 

        catch (Exception $e) {
            return back()->with('flash_error', 'Document Not Found');
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function carrierstore(Request $request)
    {
        $this->validate($request, [
            'doc_name' => 'required|max:255',
        ]);

        try{

            CarrierDocList::create($request->all());
            return redirect()->route('admin.partnerdocument.index')->with('flash_success','Document Saved Successfully');
        } 

        catch (Exception $e) {
            return back()->with('flash_error', 'Document Not Found');
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function corporatestore(Request $request)
    {
        $this->validate($request, [
            'doc_name' => 'required|max:255',
        ]);

        try{

            CorporateDocList::create($request->all());
            return redirect()->route('admin.corporatedocument.index')->with('flash_success','Document Saved Successfully');
        } 

        catch (Exception $e) {
            return back()->with('flash_error', 'Document Not Found');
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\DriverDocList  $driverDocList
     * @return \Illuminate\Http\Response
     */
    public function show(DriverDocList $driverDocList)
    {
        return view('admin.document.show', compact('driverDocList'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DriverDocList  $driverDocList
     * @return \Illuminate\Http\Response
     */
    public function edit(DriverDocList $driverDocList)
    {
       
        return view('admin.document.edit', compact('driverDocList'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DriverDocList  $driverDocList
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DriverDocList $driverDocList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DriverDocList  $driverDocList
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DriverDocList::find($id)->delete();
            return back()->with('message', 'Document deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'Document Not Found');
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DriverDocList  $driverDocList
     * @return \Illuminate\Http\Response
     */
    public function vehicledestroy($id)
    {
        try {
            VehicleDocList::find($id)->delete();
            return back()->with('message', 'Document deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'Document Not Found');
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DriverDocList  $driverDocList
     * @return \Illuminate\Http\Response
     */
    public function carrierdestroy($id)
    {
        try {
            CarrierDocList::find($id)->delete();
            return back()->with('message', 'Document deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'Document Not Found');
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DriverDocList  $driverDocList
     * @return \Illuminate\Http\Response
     */
    public function corporatedestroy($id)
    {
        try {
            CorporateDocList::find($id)->delete();
            return back()->with('message', 'Document deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'Document Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DriverDocList  $driverDocList
     * @return \Illuminate\Http\Response
     */
    public function driverupdate(Request $request)
    {
        try {
            DriverDocList::find($request->model_driver_id)->update(['doc_name'=>$request->modal_driver,'description'=>$request->modal_driver_desc]);
            return back()->with('message', 'Document deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'Document Not Found');
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DriverDocList  $driverDocList
     * @return \Illuminate\Http\Response
     */
    public function vehicleupdate(Request $request)
    {
        try {
            VehicleDocList::find($request->model_vehicle_id)->update(['doc_name'=>$request->modal_vehicle,'description'=>$request->modal_vehicle_desc]);
            return back()->with('message', 'Document deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'Document Not Found');
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DriverDocList  $driverDocList
     * @return \Illuminate\Http\Response
     */
    public function carrierupdate(Request $request)
    {
        try {
            CarrierDocList::find($request->model_carrier_id)->update(['doc_name'=>$request->modal_carrier,'description'=>$request->modal_carrier_desc]);
            return back()->with('message', 'Document deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'Document Not Found');
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DriverDocList  $driverDocList
     * @return \Illuminate\Http\Response
     */
    public function corporateupdate(Request $request)
    {
        try {
            CorporateDocList::find($request->model_corporate_id)->update(['doc_name'=>$request->modal_corporate,'description'=>$request->modal_corporate_desc]);
            return back()->with('message', 'Document deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'Document Not Found');
        }
    }
}
