<?php

namespace App\Http\Controllers\Resource;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Setting;
use Exception;
use App\Helpers\Helper;
use App\Models\ServiceType;
use App\Models\LocationWiseFare;
use App\Models\PoiFare;
use App\Models\Admin;
use Auth;
use App\Models\FareModel;
class ServiceResource extends Controller
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
        $services = ServiceType::all();
	$admin_type = Auth::user()->admin_type;
        return view('admin.service.index', compact('services','admin_type'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.service.create');
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
        //   'image' => 'mimes:ico,png|dimensions:width=1024,height=1024',
	    // 'description_image' => 'mimes:ico,png|dimensions:width=1024,height=1024',   
          'image' => 'mimes:ico,png',
	      'description_image' => 'mimes:ico,png',   
        ]);

        try {
            $service = $request->all();

            if($request->hasFile('image')) {
                $service['image'] = Helper::upload_picture($request->image);
            }

            if($request->hasFile('description_image')) {
                $service['description_image'] = Helper::upload_picture($request->description_image);
            }

            $service['status'] = 1;
            $service = ServiceType::create($service);

            return back()->with('flash_success','Service Type Saved Successfully');
        } catch (Exception $e) {
            dd("Exception", $e);
            return back()->with('flash_error', 'Service Type Not Found');
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
            return ServiceType::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Service Type Not Found');
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
            $service = ServiceType::findOrFail($id);
            return view('admin.service.edit',compact('service'));
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Service Type Not Found');
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
            'name' => 'required|max:255',
        //     'image' => 'mimes:ico,png|dimensions:width=100,height=100',
	    // 'description_image' => 'mimes:ico,png,jpg|dimensions:width=256,height=256',
        'image' => 'mimes:ico,png',
        'description_image' => 'mimes:ico,png',   
        ]);

        try {

            $service = ServiceType::findOrFail($id);

            if($request->hasFile('image')) {
                if($service->image) {
                    Helper::delete_picture($service->image);
                }
                $service->image = Helper::upload_picture($request->image);
            }
            if($request->hasFile('description_image')) {
                if($service->description_image) {
                    Helper::delete_picture($service->description_image);
                }
                $service->description_image = Helper::upload_picture($request->description_image);          
            }

            $service->name = $request->name;
            $service->seats_available = $request->seats_available;
            $service->vat_percent = 0;
            $service->description = $request->description;
            $service->save();

            return redirect()->route('admin.service.index')->with('flash_success', 'Service Type Updated Successfully');    
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Service Type Not Found');
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
            LocationWiseFare::where('service_type_id','=',$id)->delete();
            PoiFare::where('service_type_id','=',$id)->delete();
            FareModel::where('service_type_id','=',$id)->delete();
            ServiceType::find($id)->delete();
            return back()->with('message', 'Service Type deleted successfully');
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Service Type Not Found');
        } catch (Exception $e) {
            return back()->with('flash_error', 'Service Type Not Found');
        }
    }

    public function active($id)
    {
        ServiceType::where('id',$id)->update(['status' => 1]);
        return back()->with('flash_success', "Service Type activated");
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function inactive($id)
    {
        
        ServiceType::where('id',$id)->update(['status' => 0]);
        return back()->with('flash_success', "Service Type inactivated");
    }
}