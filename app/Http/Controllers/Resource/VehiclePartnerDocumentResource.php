<?php

namespace App\Http\Controllers\Resource;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SendPushNotification;

use App\Models\Vehicle;
use App\Models\VehicleDocList;
use App\Models\VehicleDocument;
use App\Models\Admin;
use Auth;
use Storage;
class VehiclePartnerDocumentResource extends Controller
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
    public function index(Request $request, $vehicle)
    {
        try {
            $vehicle = Vehicle::findOrFail($vehicle);
            $documents = VehicleDocList::get();
            $vehicledocuments = VehicleDocument::where('vehicle_id','=',$vehicle->id)->get();
            return view('partner.vehicles.document.index', compact('vehicle','documents','vehicledocuments'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $vehicle)
    {
        
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
    public function edit($vehicle, $id)
    {
        try {
            $Document = VehicleDocument::where('vehicle_id', $vehicle)
                ->findOrFail($id);
            return view('partner.vehicles.document.edit', compact('Document'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('partner.dashboard');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $vehicle, $id)
    {
        try {

            $Document = VehicleDocument::where('vehicle_id', $vehicle)
                ->where('document_id', $id)
                ->firstOrFail();
            $Document->update(['status' => 'ACTIVE']);

            return redirect()
                ->route('partner.vehicle.document.index', $vehicle)
                ->with('flash_success', 'Vehicle document has been approved.');
        } catch (ModelNotFoundException $e) {
            return redirect()
                ->route('partner.vehicle.document.index', $vehicle)
                ->with('flash_error', 'Vehicle not found!');
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request, $vehicle, $id)
    {
        $this->validate($request, [
                'document' => 'required|mimes:jpg,jpeg,png,pdf',
            ]);

        try {
            
            $Document = VehicleDocument::where('vehicle_id', $vehicle)
                ->where('document_id', $id)
                ->firstOrFail();
            Storage::delete($Document->url);
            
            $Document->update([
                    'url' => $request->document->store('vehicle/documents'),
                    'status' => 'ASSESSING',
                ]);

            return redirect()
                ->route('partner.vehicle.document.index', $vehicle)
                ->with('flash_success', 'Vehicle document has been uploaded.');
                
        } catch (ModelNotFoundException $e) {

            VehicleDocument::create([
                    'url' => $request->document->store('vehicle/documents'),
                    'vehicle_id' => $vehicle,
                    'document_id' => $id,
                    'status' => 'ASSESSING',
                ]);
            return redirect()
                ->route('partner.vehicle.document.index', $vehicle)
                ->with('flash_success', 'Vehicle document has been uploaded.');
        }

        return back();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($vehicle, $id)
    {
        try {

            $Document = VehicleDocument::where('vehicle_id', $vehicle)
                ->where('document_id', $id)
                ->firstOrFail();
            Storage::delete($Document->url);
            $Document->delete();

            return redirect()
                ->route('partner.vehicle.document.index', $vehicle)
                ->with('flash_success', 'Vehicle document has been deleted');
        } catch (ModelNotFoundException $e) {
            return redirect()
                ->route('partner.vehicle.document.index', $vehicle)
                ->with('flash_error', 'Vehicle not found!');
        }
    }

}
