<?php

namespace App\Http\Controllers\Resource;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

use Setting;
use Exception;
use App\Helpers\Helper;
use App\Models\Vehicle;
use App\Models\Location;
use App\Models\ServiceType;
use App\Models\Partner;
use App\Models\Admin;
class VehiclePartnerResource extends Controller
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
        $vehicles = Vehicle::where('partner_id','=',Auth::user()->id)->with('location','service_type')->get();
        return view('partner.vehicles.index', compact('vehicles'));  
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
        return view('partner.vehicles.create', compact('locations','services'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function vehicle_row(Request $request){

        $columns = array( 
                            0 => 'id', 
                            1 => 'vehicle_name',
                            2 => 'vehicle_owner',
                            3 => 'service_type',
                            4 => 'location',
                            5 => 'vehicle_manufacturer',
                            6 => 'vehicle_brand',
                            7 => 'status',
                            8 => 'action'
                        );
       
        
        $totalData = Vehicle::where('partner_id','=',Auth::user()->id)->count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $vehicles = Vehicle::with('location','service_type')
                     ->where('partner_id','=',Auth::user()->id)
                     ->offset($start)
                     ->limit($limit)
                     ->orderBy('id','desc')
                     ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $vehicles = Vehicle::with('location','service_type')
                            ->where('partner_id','=',Auth::user()->id)
                            ->where('name','LIKE',"%{$search}%")
                            ->orWhere('email', 'LIKE',"%{$search}%")
                            ->orWhere('mobile', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy('id','desc')
                            ->get();

            $totalFiltered = Vehicle::with('location','service_type')
                            ->where('partner_id','=',Auth::user()->id)
                            ->where('name','LIKE',"%{$search}%")
                            ->orWhere('email', 'LIKE',"%{$search}%")
                            ->orWhere('mobile', 'LIKE',"%{$search}%")
                            ->count();
        }

        $data = array();
        if(!empty($vehicles))
        {
            foreach ($vehicles as $index => $vehicle)
            {
            if($vehicle->vehicle_no != ''){ $vehicle_no = $vehicle->vehicle_no;}else{$vehicle_no = "";}
            if($vehicle->vehicle_owner != ''){ $vehicle_owner = $vehicle->vehicle_owner;}else{$vehicle_owner = "";}
            if($vehicle->service_type){ 
                $service_type = $vehicle->service_type->name;
            }else{
                $service_type = "";
            }
            if($vehicle->location){ 
                $location = $vehicle->location->location_name;
            }else{
                $location = "";
            }
            if($vehicle->vehicle_manufacturer != ''){ $vehicle_manufacturer = $vehicle->vehicle_manufacturer;}else{$vehicle_manufacturer = "";}
            if($vehicle->vehicle_brand != ''){ $vehicle_brand = $vehicle->vehicle_brand;}else{$vehicle_brand = "";}

            if($vehicle->status ==1){
                $status = '<a class="btn btn-success btn-rounded btn-sm waves-effect waves-light" href="'.route('partner.vehicle.inactive', $vehicle->id ).'">active</a>';
            }else{
                $status = '<a class="btn btn-danger btn-rounded btn-sm waves-effect waves-light" href="'.route('partner.vehicle.active', $vehicle->id ).'">Inactive</a>';
            }

            $action = '<div class="btn-group" role="group">
                            <button type="button" class="btn btn-info btn-sm waves-effect waves-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                '.trans("admin.member.action").'
                            </button>
                            <div class="dropdown-menu">
                                <a href="'.route('partner.vehicle.document.index', $vehicle->id ).'" class="dropdown-item">
                                    <i class="fa fa-cloud-upload"></i> Documents
                                </a>
                                <a href="'.route('partner.vehicle.edit', $vehicle->id).'" class="dropdown-item">
                                    <i class="fa fa-pencil-square-o"></i> '.trans("admin.member.edit").'
                                </a>
                                <form action="'.route('partner.vehicle.destroy', $vehicle->id).'" method="POST">
                                    '.csrf_field().'
                                    '.method_field('DELETE').'
                                    <button type="submit" class="dropdown-item">
                                        <i class="fa fa-trash"></i> '.trans("admin.member.delete").'
                                    </button>
                                </form>
                            </div>
                        </div>';

                $nestedData['id'] = $start + 1;
                $nestedData['vehicle_name'] = $vehicle_no;
                $nestedData['vehicle_owner'] =  $vehicle_owner;
                $nestedData['service_type'] = $service_type;
                $nestedData['location'] = $location;
                $nestedData['vehicle_manufacturer'] = $vehicle_manufacturer;
                $nestedData['vehicle_brand'] = $vehicle_brand;
                $nestedData['status'] = $status;
                $nestedData['action'] = $action;
                $data[] = $nestedData;
                $start++;
            }
        }
        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data
                    );
            
        echo json_encode($json_data);     

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
            'vehicle_name' => 'required|max:255',
            'vehicle_no' => 'required|max:255',
            'vehicle_image' => 'mimes:jpeg,jpg,bmp,png|max:5242880',   
        ]);

        try {
            $vehicle = $request->all();

            if($request->hasFile('vehicle_image')) {
                $vehicle['vehicle_image'] = Helper::upload_picture($request->vehicle_image);
            }
            $vehicle['status'] = 1;
            $vehicle['partner_id'] = Auth::user()->id;
            $vehicle = Vehicle::create($vehicle);

            return back()->with('flash_success','Vehicle Saved Successfully');
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
            $vehicle = Vehicle::findOrFail($id);
            return view('partner.vehicles.show', compact('vehicle'));
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Vehicle Type Not Found');
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
            $vehicle = Vehicle::findOrFail($id);
            return view('partner.vehicles.edit',compact('locations','vehicle','services'));
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Vehicle Type Not Found');
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
            'vehicle_name' => 'required|max:255',
            'vehicle_no' => 'required|max:255',
            'vehicle_image' => 'mimes:jpeg,jpg,bmp,png|max:5242880',  
        ]);

        try {

            $vehicle = Vehicle::findOrFail($id);

            if($request->hasFile('vehicle_image')) {
                if($vehicle->vehicle_image) {
                    Helper::delete_picture($vehicle->vehicle_image);
                }
                $vehicle->vehicle_image = Helper::upload_picture($request->vehicle_image);
            }

            $vehicle->vehicle_name = $request->vehicle_name;
            $vehicle->vehicle_no = $request->vehicle_no;
            $vehicle->seat = $request->seat ? : '';
            $vehicle->location_id = $request->location_id ? : '';
            $vehicle->partner_id = Auth::user()->id;
            $vehicle->service_type_id = $request->service_type_id ? : '';
            $vehicle->vehicle_owner = $request->vehicle_owner ? : '';
            $vehicle->vehicle_model = $request->vehicle_model ? : '';
            $vehicle->vehicle_manufacturer = $request->vehicle_manufacturer ? : '';
            $vehicle->manufacturing_year = $request->manufacturing_year ? : '';
            $vehicle->vehicle_brand = $request->vehicle_brand ? : '';
            $vehicle->vehicle_color = $request->vehicle_color ? : '';
            $vehicle->insurance_no = $request->insurance_no ? : '';
            $vehicle->insurance_exp = $request->insurance_exp ? : '';
            $vehicle->handicap_access = $request->handicap_access ? : 0;
            $vehicle->travel_pet = $request->travel_pet ? : 0;
            $vehicle->station_wagon = $request->station_wagon ? : 0;
            $vehicle->booster_seat = $request->booster_seat ? : 0;
            $vehicle->child_seat = $request->child_seat ? : 0;
            $vehicle->booster_count = $request->booster_count ? : 0;
            $vehicle->custom_field1 = $request->custom_field1 ? : '';
            $vehicle->custom_field2 = $request->custom_field2 ? : '';
            $vehicle->save();

            return redirect()->route('partner.vehicle.index')->with('flash_success', 'Vehicle Updated Successfully');    
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Vehicle Type Not Found');
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
            Vehicle::find($id)->delete();
            return back()->with('message', 'Vehicle Type deleted successfully');
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Vehicle Type Not Found');
        } catch (Exception $e) {
            return back()->with('flash_error', 'Vehicle Type Not Found');
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
        Vehicle::where('id',$id)->update(['status' => 1]);
        return back()->with('flash_success', "Vehicle activated");
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function inactive($id)
    {
        
        Vehicle::where('id',$id)->update(['status' => 0]);
        return back()->with('flash_success', "Vehicle inactivated");
    }
}