<?php

namespace App\Http\Controllers\Resource;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SendPushNotification;

use App\Models\Provider;
use App\Models\DriverDocList;
use App\Models\Admin;
use Auth;
use App\Models\ProviderDocument;
use Storage;
use App\Models\ServiceType;
use App\Models\Partner;
use App\Models\Location;
use App\Models\Vehicle;

class ProviderDocumentResource extends Controller
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
    public function index(Request $request, $provider)
    {
       
        try {
            $providerService = Provider::with('service')->where('id',$provider)->where('service_type_id','!=',0)->first();
            $services = ServiceType::all();
            $provider = Provider::findOrFail($provider);
            $documents = DriverDocList::get();
            $partners = Partner::all();
            if($providerService != Null)
            {
            $allowed_service = explode(',',$providerService->allowed_service);
            }else{
                $allowed_service = [];
            }
            $providerdocuments = ProviderDocument::where('provider_id','=',$provider->id)->get();
            return view('admin.providers.document.index', compact('provider','providerService','documents','providerdocuments','services','partners','allowed_service'));
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
    public function store(Request $request, $provider)
    {
        $this->validate($request, [
            'service_type_id' => 'required|exists:service_types,id',
            'vehicle_no' => 'required',
            'vehicle_model' => 'required',
        ]);
    
    try {
            $service = ServiceType::where('id',$request->service_type_id)->firstOrFail();
            $ProviderService = Provider::where('id', $provider)->firstOrFail();
            $location = Location::first();


            if(Auth::guard('admin')->user()->admin_type !=0){
            $vehicle['admin_id'] = Auth::guard('admin')->user()->id;
            $vehicle['partner_id'] = $ProviderService->partner_id;
            }
            $vehicle['vehicle_name'] =$service->name;
            $vehicle['vehicle_no'] = $request->vehicle_no;
            $vehicle['seat'] =$service->seats_available;
            $vehicle['location_id'] = $location->id;
            $vehicle['partner_id'] = $ProviderService->partner_id;
            $vehicle['service_type_id'] = $service->id;
            $vehicle['vehicle_owner'] = $ProviderService->name;
            $vehicle['vehicle_model'] = $request->vehicle_model;
            $vehicle['vehicle_manufacturer'] = $request->vehicle_model;
            $vehicle['manufacturing_year'] = '2022';
            $vehicle['vehicle_brand'] = $request->vehicle_model;
            $vehicle['vehicle_color'] = $request->vehicle_model;
            $vehicle['insurance_no'] =$request->license_number;
            $vehicle['insurance_exp'] =$request->license_expire;
            $vehicle['status'] = 1;
            $vehicle = Vehicle::create($vehicle);

             if(!empty($request->allowed_service))
            {
            $allowed_service = [];
            $allowed_service = $request->allowed_service;
            $count = count($request->allowed_service);
            if(!in_array($request->service_type_id, $request->allowed_service))
            {
                
                array_push($allowed_service,$request->service_type_id);
               // dd($allowed_service); die;
            
            }
        }else{
              $allowed_service = [];
              array_push($allowed_service,$request->service_type_id);
        }

        $allowed_service = implode(',',$allowed_service);
        $allowed_service = ",".$allowed_service.",";
        $ProviderService->update([
                'service_type_id' => $request->service_type_id,
                'partner_id' => $ProviderService->partner_id,
                'allowed_service' => $allowed_service,
                'license_no' => $request->license_number,
                'service_model' => $request->service_model,
                'license_expire' => $request->license_expire,
                'taxi_type' => $request->taxi_type,
                'mapping_id'=>$vehicle->id,
            ]);

        // Sending push to the provider
        (new SendPushNotification)->DocumentsVerfied($provider);

    } catch (ModelNotFoundException $e) {
    }

    return redirect()->route('admin.provider.document.index', $provider)->with('flash_success', 'Provider service type updated successfully!');

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
    public function edit($provider, $id)
    {
        try {
            $Document = ProviderDocument::where('provider_id', $provider)
                ->findOrFail($id);
            return view('admin.providers.document.edit', compact('Document'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('admin.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $provider, $id)
    {
        try {

            $Document = ProviderDocument::where('provider_id', $provider)
                ->where('document_id', $id)
                ->firstOrFail();
            $Document->update(['status' => 'ACTIVE']);

            return redirect()
                ->route('admin.provider.document.index', $provider)
                ->with('flash_success', 'Provider document has been approved.');
        } catch (ModelNotFoundException $e) {
            return redirect()
                ->route('admin.provider.document.index', $provider)
                ->with('flash_error', 'Provider not found!');
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request, $provider, $id)
    {
        $this->validate($request, [
                'document' => 'required|mimes:jpg,jpeg,png,pdf',
            ]);

        try {
            
            $Document = ProviderDocument::where('provider_id', $provider)
                ->where('document_id', $id)
                ->firstOrFail();
                $file_name = time();
                $file_name .= rand();
                $file_name = sha1($file_name);
                $image1=$request->document;
                if ($image1) {
                    $ext = $image1->getClientOriginalExtension();
                    $image1->move(public_path() . "/uploads/provider/documents/", $file_name . "." . $ext);
                    $local_url = $file_name . "." . $ext;
                    $s3_url = '/uploads/provider/documents/'.$local_url;


                    $Document->update([
                        'url' => $s3_url,
                        'status' => 'ASSESSING',
                    ]);
                }

            return redirect()
                ->route('admin.provider.document.index', $provider)
                ->with('flash_success', 'Driver document has been uploaded.');
                
        } catch (ModelNotFoundException $e) {

            $file_name = time();
            $file_name .= rand();
            $file_name = sha1($file_name);
            $image1=$request->document;                   
            $ext = $image1->getClientOriginalExtension();
            $image1->move(public_path() . "/uploads/provider/documents/", $file_name . "." . $ext);
            $local_url = $file_name . "." . $ext;
            $s3_url = '/uploads/provider/documents/'.$local_url;                    

            ProviderDocument::create([
                'url' => $s3_url,
                'provider_id' => $provider,
                'document_id' => $id,
                'status' => 'ASSESSING',
            ]);
            return redirect()
                ->route('admin.provider.document.index', $provider)
                ->with('flash_success', 'Driver document has been uploaded.');
        }

        return back();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($provider, $id)
    {
        try {

            $Document = ProviderDocument::where('provider_id', $provider)
                ->where('document_id', $id)
                ->firstOrFail();
            Storage::delete($Document->url);
            $Document->delete();

            return redirect()
                ->route('admin.provider.document.index', $provider)
                ->with('flash_success', 'Provider document has been deleted');
        } catch (ModelNotFoundException $e) {
            return redirect()
                ->route('admin.provider.document.index', $provider)
                ->with('flash_error', 'Provider not found!');
        }
    }

    public function service_destroy(Request $request, $id)
    {
        try {
            $provider = Provider::where('id',$id)->update(['service_type_id'=> 0]);
 
            return redirect()
                ->route('admin.provider.document.index', $id)
                ->with('flash_success', 'Provider service has been deleted.');
        } catch (ModelNotFoundException $e) {
            return redirect()
                ->route('admin.provider.document.index',$id)
                ->with('flash_error', 'Provider service not found!');
        }
    }

}
