<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProviderFormRequest;
use App\Models\ProviderBankDetail;
use App\Models\ProviderForm;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use DB;
use Exception;
use Setting;
use Storage;
use Mail;
use Twilio;
use File;
use url;
use \Carbon\Carbon;
use App\Models\Country;
use App\Models\Provider;
use App\Models\Partner;
use App\Models\Admin;
use App\Models\ServiceType;
use App\Models\Vehicle;
use App\Helpers\Helper;
use App\Models\UserRequest;
use App\Models\UserRequestPayment;
use Auth;
use App\Models\Location;
use App\Models\ProviderShift;
use App\Models\ProviderDevice;
use App\Models\ProviderDocument;

class ProviderResource extends Controller
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
            // if($this->admin_id == null){

            //      $admin = Admin::where('id','=',$this->id)->first();

            //      if($admin->admin_type != 0 && $admin->time_zone != null){
            //          date_default_timezone_set($admin->time_zone);

            //      }
            //  } else {

            //     $admin = Admin::where('id','=',$this->admin_id)->first();

            //      if($admin->admin_type != 0 && $admin->time_zone != null){
            //          date_default_timezone_set($admin->time_zone);

            //      }
            //}

            return $next($request);
        });


    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $providers = Provider::get();
        // dd($providers); die;
        return view('admin.providers.index', compact('providers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::all();
        $partners = Partner::all();
        $services = ServiceType::all();
        $providerForm = new ProviderForm();
        $serviceTypes = ServiceType::where([])->get();
        return view('admin.providers.create', compact('countries', 'serviceTypes', 'partners', 'services', 'providerForm'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function shifts(Request $request)
    {

        if (Auth::guard('admin')->user()->admin_type == 0) {
            $AllProviders = Provider::with('service', 'accepted', 'cancelled')
                ->orderBy('id', 'DESC');
        } else {
            $AllProviders = Provider::with('service', 'accepted', 'cancelled')
                ->where('admin_id', Auth::guard('admin')->user()->id)
                ->orderBy('id', 'DESC');
        }

        if (request()->has('fleet')) {
            $providers = $AllProviders->where('fleet', $request->fleet)->get();
            $fleet = $request->fleet;
        } else {
            $providers = $AllProviders->get();
            $fleet = '';
        }

        return view('admin.providers.shifts', compact('providers', 'fleet'));
    }

    public function provider_row(Request $request)
    {

        $columns = array(
            0 => 'id',
            1 => 'full_name',
            2 => 'email',
            3 => 'mobile',
            4 => 'total_requests',
            5 => 'accepted_requests',
            6 => 'cancelled_requests',
            // 7 => 'documents',
            8 => 'action',
        );

        $AllProviders = Provider::with('service', 'totalrequest', 'accepted', 'cancelled');

        if (Auth::guard('admin')->user()->admin_type != 0) {
            $AllProviders = $AllProviders->where('admin_id', '=', Auth::guard('admin')->user()->id);
        }

        $providerslist = $AllProviders;


        $totalData = $providerslist->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $providers = $providerslist->offset($start);
            if (Auth::guard('admin')->user()->admin_type != 0) {
                $providers = $providers->where('admin_id', '=', Auth::guard('admin')->user()->id);
            }
            $providers = $providers->limit($limit)
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $search = $request->input('search.value');

            $providers = $providerslist->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('mobile', 'LIKE', "%{$search}%");
            })
                ->offset($start);
            if (Auth::guard('admin')->user()->admin_type != 0) {
                $providers = $providers->where('admin_id', Auth::guard('admin')->user()->id);
            }
            $providers = $providers->limit($limit)
                ->orderBy('id', 'desc')
                ->get();

            $totalFiltered = $providerslist->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('mobile', 'LIKE', "%{$search}%");
            });
            if (Auth::guard('admin')->user()->admin_type != 0) {
                $totalFiltered = $totalFiltered->where('admin_id', '=', Auth::guard('admin')->user()->id);
            }
            $totalFiltered = $totalFiltered->count();
        }

        $data = array();
        if (!empty($providers)) {
            foreach ($providers as $index => $provider) {
                if ($provider->name != '') {
                    // $first_name = '<a href="'.route('admin.provider.shift', $provider->id ).'">'.$provider->first_name.'</a>';
                    $first_name = "<div><p class='mb-0'>" . $provider->name . "</p><span class='badge badge-info' title='".$provider->account_notice."'>" . $provider->account_status . "</span></div>";
                } else {
                    $first_name = "";
                }
                if ($provider->email != '') {
                    $email = $provider->email;
                } else {
                    $email = "";
                }
                if ($provider->mobile != '') {
                    $mobile = $provider->mobile;
                } else {
                    $mobile = "";
                }

                if ($provider->service == null) {
                    $documents = '<a class="btn btn-danger btn-rounded btn-block label-right waves-effect waves-light" href="' . route('admin.provider.document.index', $provider->id) . '">' . trans("admin.member.attention") . '<span class="btn-label">' . $provider->pending_documents() . '</span></a>';
                } else {
                    $documents = '<a class="btn btn-success btn-rounded btn-block waves-effect waves-light" href="' . route('admin.provider.document.index', $provider->id) . '">' . trans("admin.member.all_set") . '</a>';
                }

                // if ($provider->account_status == 'approved') {
                //     $enable = '<a class="btn btn-danger btn-rounded btn-block waves-effect waves-light" href="' . route('admin.provider.banned', $provider->id) . '">' . trans("admin.member.disable") . '</a>';
                // } else {
                //     $enable = '<a class="btn btn-success btn-rounded btn-block waves-effect waves-light" href="' . route('admin.provider.approve', $provider->id) . '">' . trans("admin.member.enable") . '</a>';
                // }

                $enable = '';

                if ($provider->account_status == 'onboarding') {
                    $enable .= '<a class="approveaccount btn btn-success btn-rounded waves-effect waves-light" href="' . route('admin.provider.approve', ['id' => $provider->id, 'status' => 'approved']) . '">Mark Approved</a>';
                    $enable .= '<a class="rejectaccount btn btn-danger btn-rounded waves-effect waves-light" href="' . route('admin.provider.banned', ['id' => $provider->id, 'status' => 'rejected']) . '">Mark Rejected</a>';
                } elseif ($provider->account_status == 'approved') {
                    $enable .= '<a class="banaccount btn btn-danger btn-rounded btn-block waves-effect waves-light" href="' . route('admin.provider.banned', ['id' => $provider->id, 'status' => 'banned']) . '">Mark Banned</a>';
                } elseif ($provider->account_status == 'rejected' || $provider->account_status == 'banned') {
                    $enable .= '<a class="approveaccount btn btn-success btn-rounded btn-block waves-effect waves-light" href="' . route('admin.provider.approve', ['id' => $provider->id, 'status' => 'approved']) . '">Mark Approved</a>';
                }

                $button = '<button type="button" 
                                class="btn btn-info btn-rounded btn-block dropdown-toggle"
                                data-toggle="dropdown">Action
                                <span class="caret"></span>
                            </button>
                    <ul class="dropdown-menu table-dropdown-actions">
                                <li>
                                    <a href="' . route('admin.provider.request', $provider->id) . '" class="btn btn-default"><i class="fa fa-search"></i> ' . trans("admin.member.history") . '</a>
                                </li>
                                <li>
                                    <a href="' . route('admin.provider.statement', $provider->id) . '" class="btn btn-default"><i class="fa fa-account"></i> ' . trans("admin.member.statement") . '</a>
                                </li>
                                <li>
                                    <a href="' . route('admin.provider.edit', $provider->id) . '" class="btn btn-default"><i class="fa fa-pencil"></i> ' . trans("admin.member.edit") . '</a>
                                </li>
                                <li>
                                    <form action="' . route('admin.provider.logout', $provider->id) . '" method="POST">
                                        ' . csrf_field() . '
                                        <input type="hidden" name="_method" value="POST">
                                        <button class="btn btn-default look-a-log" onclick="return confirm(`Do you want to logout this provider?`)"><i class="fa fa-sign-out"></i> ' . trans("admin.member.logout") . '</button>
                                    </form>
                                </li>
                                <li>
                                    <form action="' . route('admin.provider.destroy', $provider->id) . '" method="POST">
                                        ' . csrf_field() . '
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button class="btn btn-default look-a-like" onclick="return confirm(`Are you sure?`)"><i class="fa fa-trash"></i> ' . trans("admin.member.delete") . '</button>
                                    </form>
                                </li>
                            </ul>';
                $action = '<div class="input-group-btn">' . $enable . $button . '</div>';

                $nestedData['id'] = $start + 1;
                $nestedData['full_name'] = $first_name;
                $nestedData['email'] = $email;
                $nestedData['mobile'] = $mobile;
                $nestedData['total_requests'] = $provider->totalrequest->count();
                $nestedData['accepted_requests'] = $provider->accepted->count();
                $nestedData['cancelled_requests'] = $provider->cancelled->count();
                // $nestedData['documents'] = $documents;
                $nestedData['action'] = $action;
                $data[] = $nestedData;
                $start++;
            }
        }
        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);


    }

    public function store(ProviderFormRequest $request)
    {
        $provider = new Provider();
        if (isset($_GET['id'])) {
            $provider = Provider::findOrFail($_GET['id']);
        }

        // ✅ validated input
        $data = $request->validated();

        // Build DTO
        $providerForm = new ProviderForm($data);

        // Handle file uploads
        // foreach (['image', 'dl_front', 'dl_back', 'rego_picture', 'taxi_insurance_picture', 'ptv_picture', 'ptd_picture'] as $fileField) {
        //     if ($request->hasFile($fileField)) {
        //         $providerForm->{$fileField} = Helper::uploadFile($request->file($fileField));
        //     }
        // }

        // 🔽 Now you can insert into another DB or API
        // MyCustomDB::insert($providerForm);
        // dd($providerForm);

        $provider->name = $providerForm->name;
        $provider->email = $providerForm->email;
        $provider->mobile = $providerForm->mobile;
        $provider->dob = $providerForm->dob;
        $provider->five_passenger = $providerForm->five_passenger;

        if ($request->hasFile('image')) {
            $provider->avatar = Helper::uploadFile($request->image);
        }

        $provider->save();

        //save driving license
        $documentType = Helper::PROVIDER_DOCUMENT_TYPES["DRIVING_LICENSE"];

        $documentModel = ProviderDocument::where('provider_id', $provider->id)
            ->where('document_type', $documentType)
            ->first();

        if ($documentModel == null) {
            $documentModel = new ProviderDocument();
            $documentModel->provider_id = $provider->id;
            $documentModel->document_type = $documentType;
            $documentModel->document_id = 0;
        }

        if ($request->hasFile('dl_front')) {
            $documentModel->url = Helper::uploadFile($request->dl_front);
        }

        if ($request->hasFile('dl_back')) {
            $documentModel->back_url = Helper::uploadFile($request->dl_back);
        } else {
            $documentModel->back_url = null;
        }

        $documentModel->expires_at = $request->dl_expiry;
        $documentModel->status = 'ACTIVE';
        $documentModel->save();

        $provider->license_no = $request->dl_number;
        $provider->license_expire = $request->dl_expiry;

        $provider->save();

        //update rego
        $documentType = Helper::PROVIDER_DOCUMENT_TYPES["REGISTRATION"];

        $documentModel = ProviderDocument::where('provider_id', $provider->id)
            ->where('document_type', $documentType)
            ->first();

        if ($documentModel == null) {
            $documentModel = new ProviderDocument();
            $documentModel->provider_id = $provider->id;
            $documentModel->document_type = $documentType;
            $documentModel->document_id = 0;
        }

        if ($request->hasFile('rego_picture')) {
            $documentModel->url = Helper::uploadFile($request->rego_picture);
        }

        $documentModel->back_url = null;

        $documentModel->expires_at = $request->rego_expiry;
        $documentModel->status = 'ACTIVE';
        $documentModel->save();

        $data = Helper::getOrCreateVehicle($provider);
        $provider = $data["provider"];
        $providerVehicleModel = $data["providerVehicleModel"];

        $providerVehicleModel->vehicle_name = $request->rego_vehicle;
        $providerVehicleModel->vehicle_no = $request->rego_plate_no;
        $providerVehicleModel->service_type_id = $request->rego_vehicle_type;

        $providerVehicleModel->save();

        //update taxi insurance
        $documentType = Helper::PROVIDER_DOCUMENT_TYPES["TAXI_INSURANCE"];

        $documentModel = ProviderDocument::where('provider_id', $provider->id)
            ->where('document_type', $documentType)
            ->first();

        if ($documentModel == null) {
            $documentModel = new ProviderDocument();
            $documentModel->provider_id = $provider->id;
            $documentModel->document_type = $documentType;
            $documentModel->document_id = 0;
        }

        if ($request->hasFile('taxi_insurance_picture')) {
            $documentModel->url = Helper::uploadFile($request->taxi_insurance_picture);
        }

        $documentModel->back_url = null;

        $documentModel->expires_at = $request->taxi_insurance_expiry;
        $documentModel->status = 'ACTIVE';
        $documentModel->save();

        $data = Helper::getOrCreateVehicle($provider);
        $provider = $data["provider"];
        $providerVehicleModel = $data["providerVehicleModel"];

        $providerVehicleModel->insurance_exp = $request->expiredAt;
        $providerVehicleModel->save();

        //update Passenger Transport Vehicle Certificate 
        $documentType = Helper::PROVIDER_DOCUMENT_TYPES["PTV_CERTIFICATE"];

        $documentModel = ProviderDocument::where('provider_id', $provider->id)
            ->where('document_type', $documentType)
            ->first();

        if ($documentModel == null) {
            $documentModel = new ProviderDocument();
            $documentModel->provider_id = $provider->id;
            $documentModel->document_type = $documentType;
            $documentModel->document_id = 0;
        }

        if ($request->hasFile('ptv_picture')) {
            $documentModel->url = Helper::uploadFile($request->ptv_picture);
        }

        $documentModel->back_url = null;

        $documentModel->expires_at = $request->ptv_expiry;
        $documentModel->status = 'ACTIVE';
        $documentModel->save();

        $data = Helper::getOrCreateVehicle($provider);
        $provider = $data["provider"];
        $providerVehicleModel = $data["providerVehicleModel"];

        $providerVehicleModel->ptv_number = $request->ptv_number;
        $providerVehicleModel->save();


        //Update Passenger Transport Driver Certificate 
        $documentType = Helper::PROVIDER_DOCUMENT_TYPES["PTD_CERTIFICATE"];

        // if ($request->hasFile('file')) {

        $documentModel = ProviderDocument::where('provider_id', $provider->id)
            ->where('document_type', $documentType)
            ->first();

        if ($documentModel == null) {
            $documentModel = new ProviderDocument();
            $documentModel->provider_id = $provider->id;
            $documentModel->document_type = $documentType;
            $documentModel->document_id = 0;
        }

        if ($request->hasFile('ptd_picture')) {
            $documentModel->url = Helper::uploadFile($request->ptd_picture);
        }

        $documentModel->back_url = null;

        $documentModel->expires_at = $request->ptd_expiry;
        $documentModel->status = 'ACTIVE';
        $documentModel->save();

        $provider->ptd_number = $request->ptd_number;
        $provider->save();

        if (
            $request->bank_holder_name != "" &&
            $request->bank_name != "" &&
            $request->account_no != ""
        ) {
            //Update Bank Details
            ProviderBankDetail::updateOrCreate(
                ['provider_id' => $provider->id],
                [
                    'account_holder_name' => $request->bank_holder_name,
                    'bank_name' => $request->bank_name,
                    'account_number' => $request->account_no,
                    'branch_name' => $request->branch_name
                ]
            );
        }

        return redirect()->route('admin.provider.index')->with('flash_success', 'Provider Saved Successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeold(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|unique:providers,email,NULL,id,deleted_at,NULL|email|max:255',
            'password' => 'required|min:6|confirmed',
            'mobile' => 'required|unique:providers,mobile,NULL,id,deleted_at,NULL',
            // 'avatar' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
            'country_id' => 'required',
            // 'partner_id'=>'required'
        ]);

        try {
            $location = Location::first();
            $service = ServiceType::first();

            // if(Auth::guard('admin')->user()->admin_type !=0){
            //     $vehicle['admin_id'] = Auth::guard('admin')->user()->id;
            // }
            // $vehicle['vehicle_name'] = $request->name.'1234';
            // $vehicle['vehicle_no'] = $request->name.'1234';
            // $vehicle['seat'] = 4;
            // $vehicle['location_id'] = $location->id;
            // $vehicle['partner_id'] = $request->partner_id;
            // $vehicle['service_type_id'] = $service->id;
            // $vehicle['vehicle_owner'] = $request->name;
            // $vehicle['vehicle_model'] = 'Audi';
            // $vehicle['vehicle_manufacturer'] = 'Audi';
            // $vehicle['manufacturing_year'] = '2022';
            // $vehicle['vehicle_brand'] = 'brand';
            // $vehicle['vehicle_color'] = 'green';
            // $vehicle['insurance_no'] = '54354';
            // $vehicle['insurance_exp'] = '2022-07-17';
            // $vehicle['status'] = 1;
            // $vehicle = Vehicle::create($vehicle);

            $provider = $request->all();
            $country = Country::where('countryid', '=', $request->country_id)->first();
            if (Auth::guard('admin')->user()->admin_type != 0) {
                $provider['admin_id'] = Auth::guard('admin')->user()->id;
            }
            $provider['mapping_id'] = 0;
            $provider['password'] = bcrypt($request->password);
            $provider['dial_code'] = $country->dial_code;
            $provider['mobile'] = $request->mobile;
            // $provider['allowed_service'] = implode(',',$request->allowed_service);
            // $provider['service_type_id'] = $request->service_type_id;
            //$provider['language'] = implode(',',$request->language);
            $provider['status'] = 'offline';
            $provider['wallet_balance'] = 0;
            if ($request->hasFile('avatar')) {
                // $provider['avatar'] = $request->avatar->store('public/provider/profile');
                // $provider['avatar'] = $request->avatar->store('provider/profile');
                $picture = $request->avatar;
                $file_name = time();
                $file_name .= rand();
                $file_name = sha1($file_name);
                if ($picture) {
                    $ext = $picture->getClientOriginalExtension();
                    $picture->move(public_path() . "/uploads/provider/profile/", $file_name . "." . $ext);
                    $local_url = $file_name . "." . $ext;
                    // dd($local_url); die;                 
                    $provider['avatar'] = url('/') . "/uploads/provider/profile/" . $local_url;
                }
            }

            $provider = Provider::create($provider);

            return back()->with('flash_success', 'Provider Details Saved Successfully');

        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $provider = Provider::findOrFail($id);
            return view('admin.providers.provider-details', compact('provider'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $countries = Country::all();
            $provider = Provider::findOrFail($id);
            $partners = Partner::all();
            $services = ServiceType::all();
            $serviceTypes = ServiceType::where([])->get();

            $dlDocumentModel = ProviderDocument::where('provider_id', $provider->id)
                ->where('document_type', Helper::PROVIDER_DOCUMENT_TYPES["DRIVING_LICENSE"])
                ->first();

            $regoDocumentModel = ProviderDocument::where('provider_id', $provider->id)
                ->where('document_type', Helper::PROVIDER_DOCUMENT_TYPES["REGISTRATION"])
                ->first();

            $insuranceDocumentModel = ProviderDocument::where('provider_id', $provider->id)
                ->where('document_type', Helper::PROVIDER_DOCUMENT_TYPES["TAXI_INSURANCE"])
                ->first();

            $ptvDocumentModel = ProviderDocument::where('provider_id', $provider->id)
                ->where('document_type', Helper::PROVIDER_DOCUMENT_TYPES["PTV_CERTIFICATE"])
                ->first();

            $ptdDocumentModel = ProviderDocument::where('provider_id', $provider->id)
                ->where('document_type', Helper::PROVIDER_DOCUMENT_TYPES["PTD_CERTIFICATE"])
                ->first();

            $providerBankAccount = ProviderBankDetail::where(['provider_id' => $provider->id])->first();

            $data = Helper::getOrCreateVehicle($provider);
            $providerVehicleModel = $data["providerVehicleModel"];

            $providerForm = new ProviderForm([

                'name' => $provider->name,
                'email' => $provider->email,
                'mobile' => $provider->mobile,
                'dob' => Helper::parseDateToFormInput($provider->dob),
                'image' => $provider->avatar,

                'dl_front' => $dlDocumentModel != null ? $dlDocumentModel->url : '',
                'dl_back' => $dlDocumentModel != null ? $dlDocumentModel->back_url : '',
                'dl_number' => $provider->license_no,
                'dl_expiry' => $dlDocumentModel != null ? Helper::parseDateToFormInput($dlDocumentModel->expires_at) : '',

                'rego_picture' => $regoDocumentModel != null ? $regoDocumentModel->url : '',
                'rego_plate_no' => $providerVehicleModel->vehicle_no,
                'rego_expiry' => $regoDocumentModel != null ? Helper::parseDateToFormInput($regoDocumentModel->expires_at) : '',
                'rego_vehicle' => $providerVehicleModel->vehicle_name,
                'rego_vehicle_type' => $providerVehicleModel->service_type_id,

                'taxi_insurance_picture' => $insuranceDocumentModel != null ? $insuranceDocumentModel->url : '',
                'taxi_insurance_expiry' => $insuranceDocumentModel != null ? Helper::parseDateToFormInput($insuranceDocumentModel->expires_at) : '',

                'ptv_picture' => $ptvDocumentModel != null ? $ptvDocumentModel->url : '',
                'ptv_number' => $providerVehicleModel->ptv_number,
                'ptv_expiry' => $ptvDocumentModel != null ? Helper::parseDateToFormInput($ptvDocumentModel->expires_at) : '',

                'ptd_picture' => $ptdDocumentModel != null ? $ptdDocumentModel->url : '',
                'ptd_number' => $provider->ptd_number,
                'ptd_expiry' => $ptdDocumentModel != null ? Helper::parseDateToFormInput($ptdDocumentModel->expires_at) : '',

                'bank_holder_name' => $providerBankAccount != null ? $providerBankAccount->account_holder_name : '',
                'bank_name' => $providerBankAccount != null ? $providerBankAccount->bank_name : '',
                'account_no' => $providerBankAccount != null ? $providerBankAccount->account_number : '',
                'branch_name' => $providerBankAccount != null ? $providerBankAccount->branch_name : '',

                'five_passenger' => $provider->five_passenger,

            ]);

            // dd($providerForm);

            return view('admin.providers.edit', compact('provider', 'serviceTypes', 'providerForm', 'countries', 'partners', 'services'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required',
            'mobile' => 'digits_between:6,13',
            // 'avatar' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
            // 'country_id' => 'required',
            // 'partner_id'=>'required',
            // 'allowed_service'=>'required',
            // 'language'=>'required',
        ]);

        try {

            $provider = Provider::findOrFail($id);

            if ($request->hasFile('avatar')) {
                if ($provider->avatar) {
                    // Storage::delete($provider->avatar);
                    File::delete(public_path('uploads/provider/profile/' . $provider->avatar));
                }
                // $provider->avatar = $request->avatar->store('public/provider/profile');    
                // $provider->avatar = url('/').$request->avatar->store('/provider/profile');  

                $picture = $request->avatar;
                $file_name = time();
                $file_name .= rand();
                $file_name = sha1($file_name);
                if ($picture) {
                    $ext = $picture->getClientOriginalExtension();
                    $picture->move(public_path() . "/uploads/provider/profile/", $file_name . "." . $ext);
                    $local_url = $file_name . "." . $ext;
                    // dd($local_url); die;                 
                    $provider['avatar'] = url('/') . "/uploads/provider/profile/" . $local_url;
                }
            }

            $provider->name = $request->name;
            $country = Country::where('countryid', '=', $request->country_id)->first();
            $provider->email = $request->email;
            // $provider->country_id = $country->countryid;
            // $provider->dial_code = $country->dial_code;
            $provider->mobile = $request->mobile;
            // $provider->partner_id = $request->partner_id;
            $provider->gender = $request->gender ?: '';
            // $provider->service_type_id = $request->service_type_id ? : '';
            $provider->address = $request->address ?: '';
            // $provider->allowed_service = implode(',',$request->allowed_service);
            // $provider->language = implode(',',$request->language);
            $provider->acc_no = $request->acc_no ?: '';
            // $provider->license_no = $request->license_no ? : '';
            // $provider->license_expire = $request->license_expire ? : '';
            // $provider->custom_field1 = $request->custom_field1 ? : '';
            // $provider->custom_field2 = $request->custom_field2 ? : '';
            $provider->save();

            return redirect()->route('admin.provider.index')->with('flash_success', 'Provider Updated Successfully');
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Provider Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        try {

            $provider = Provider::find($id);
            $provider->status = 'offline';
            $provider->save();

            Provider::find($id)->delete();
            return back()->with('message', 'Provider deleted successfully');
        } catch (Exception $e) {
            return back()->with('flash_error', 'Provider Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function approve($id)
    {
        Provider::where('id', $id)->update(['account_status' => 'approved', 'account_notice' => '']);
        return back()->with('flash_success', "Driver account approved");

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function banned($id)
    {
        $status = $_GET['status'];
        $message = $_GET['message'] ?? "Account marked as ".$status;
        
        Provider::where('id', $id)->update([
            'account_status' => $status,
            'account_notice' => $message,
        ]);

        return back()->with('flash_success', "Provider marked as " . $status);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function assign_list()
    {
        $partners = Partner::all();
        $providers = Provider::where('account_status', '=', 'approved')->get();
        $vehicles = Vehicle::where('status', '=', 1)->get();
        return view('admin.providers.assign', compact('providers', 'vehicles', 'partners'));
    }
    /**
     * Toggle service availability of the provider.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function assign_vehicle(Request $request)
    {
        $this->validate($request, [
            'provider_id' => 'required',
            'vehicle_id' => 'required',
        ]);

        $Provider = Provider::where('id', '=', $request->provider_id)->first();
        $vehicle = Vehicle::where('id', '=', $request->vehicle_id)->first();
        if ($vehicle != null) {
            $prev = Provider::where('mapping_id', '=', $vehicle->id)->first();
            if ($prev != null) {
                if ($prev->status == 'offline') {
                    $prev->service_type_id = 0;
                    $prev->mapping_id = 0;
                    $prev->save();
                } else {
                    return back()->with('flash_error', 'Vehicle is in Ride, Cannot change now');
                }
            }
            $Provider->mapping_id = $vehicle->id;
            $Provider->service_type_id = $vehicle->service_type_id;
            $Provider->save();
            return back()->with('flash_success', 'Vehicle Updated Successfully');
        } else {
            return response()->with('flash_success', 'Vehicle Not Found');
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function assign_row(Request $request)
    {

        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'email',
            3 => 'mobile',
            4 => 'account_status',
            5 => 'status',
            6 => 'vehicle',
        );


        $totalData = Provider::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $providers = Provider::with('vehicle')->offset($start)
                ->limit($limit)
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $search = $request->input('search.value');

            $providers = Provider::with('vehicle')->where('name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhere('mobile', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy('id', 'desc')
                ->get();

            $totalFiltered = Provider::with('vehicle')->where('name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhere('mobile', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        if (!empty($providers)) {
            foreach ($providers as $index => $provider) {
                if ($provider->name != '') {
                    $name = $provider->name;
                } else {
                    $name = "";
                }
                if ($provider->email != '') {
                    $email = $provider->email;
                } else {
                    $email = "";
                }
                if ($provider->mobile != '') {
                    $mobile = $provider->dial_code . $provider->mobile;
                } else {
                    $mobile = "";
                }
                if ($provider->account_status == 'onboarding') {
                    $account_status = '<span class="label label-warning label-sm">Onboarding</span>';
                } elseif ($provider->account_status == 'approved') {
                    $account_status = '<span class="label label-success label-sm">Approved</span>';
                } else {
                    $account_status = '<span class="label label-danger label-sm">Banned</span>';
                }

                if ($provider->status == 'offline') {
                    $status = '<span class="label label-danger label-sm">Offline</span>';
                } elseif ($provider->status == 'active') {
                    $status = '<span class="label label-success label-sm">Active</span>';
                } else {
                    $status = '<span class="label label-primary label-sm">Riding</span>';
                }

                if ($provider->vehicle) {
                    $vehicle = $provider->vehicle->vehicle_no;
                } else {
                    $vehicle = '-';
                }

                $nestedData['id'] = $start + 1;
                $nestedData['name'] = $name;
                $nestedData['email'] = $email;
                $nestedData['mobile'] = $mobile;
                $nestedData['account_status'] = $account_status;
                $nestedData['status'] = $status;
                $nestedData['vehicle'] = $vehicle;
                $data[] = $nestedData;
                $start++;
            }
        }
        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function request($id)
    {

        try {
            $user_id = '';
            $provider_id = $id;
            return view('admin.request.index', compact('user_id', 'provider_id'));
        } catch (Exception $e) {
            return back()->with('flash_error', 'Something Went Wrong!');
        }
    }
    /**
     * account statements.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function statement($id)
    {

        try {

            $Provider = Provider::find($id);
            $providerid = $id;
            $Joined = $Provider->created_at ? '- Joined ' . $Provider->created_at->diffForHumans() : '';
            $page = $Provider->name . "'s Overall Statement " . $Joined;
            return view('admin.statement.provider-content', compact('page', 'providerid'));

        } catch (Exception $e) {
            return back()->with('flash_error', 'Something Went Wrong!');
        }
    }
    /**
     * provider base statements rows.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function provider_content(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'booking_id',
            2 => 's_address',
            3 => 'stop1_address',
            4 => 'stop2_address',
            5 => 'd_address',
            6 => 'detail',
            7 => 'created_at',
            8 => 'status',
            9 => 'payment_mode',
            10 => 'earnings',
            11 => 'total',
        );
        $fromdate = '';
        $todate = Carbon::now();
        $payment_type = '';
        $tripstatus = '';
        $id = $request->providerid;

        if ($request->fromdate != '') {
            $fromdate = $request->fromdate;
        }
        if ($request->todate != '') {
            $todate = Carbon::parse($request->todate)->addDay();
        }
        if ($request->has('payment')) {
            $payment_type = $request->payment;
        }
        if ($request->has('tripstatus')) {
            $tripstatus = $request->tripstatus;
        }
        $main_detail = UserRequest::with('payment')
            ->where('provider_id', $id)
            ->where('created_at', '>=', $fromdate)
            ->where('created_at', '<', $todate)
            ->where('status', 'LIKE', '%' . $tripstatus . '%');
        if ($payment_type == 'CORPORATE') {
            $main_detail = $main_detail->where('corporate_id', '!=', 0);
        } else {
            if ($payment_type != '') {
                $main_detail = $main_detail->where('corporate_id', '=', 0)->where('payment_mode', 'LIKE', '%' . $payment_type . '%');
            }
        }
        $cancel_rides = UserRequest::where('status', 'CANCELLED')
            ->where('provider_id', $id)
            ->where('created_at', '>=', $fromdate)
            ->where('created_at', '<', $todate)
            ->where('status', 'LIKE', '%' . $tripstatus . '%');
        if ($payment_type == 'CORPORATE') {
            $cancel_rides = $cancel_rides->where('corporate_id', '!=', 0);
        } else {
            if ($payment_type != '') {
                $cancel_rides = $cancel_rides->where('corporate_id', '=', 0)->where('payment_mode', 'LIKE', '%' . $payment_type . '%');
            }
        }
        $revenue = UserRequestPayment::whereHas('request', function ($query) use ($id) {
            $query->where('provider_id', $id);
        })->select(\DB::raw(
                    'SUM(revenue) as overall'
                ))->where('created_at', '>=', $fromdate)
            ->where('created_at', '<', $todate)->get();

        $total_cancel = $cancel_rides->count();
        $total_revenue = round($revenue[0]->overall, 2);
        $totalData = $main_detail->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $rides = $main_detail
                ->offset($start)
                ->limit($limit)
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $search = $request->input('search.value');

            $rides = $main_detail
                ->where('booking_id', 'LIKE', "%{$search}%")
                ->orWhere('s_address', 'LIKE', "%{$search}%")
                ->orWhere('d_address', 'LIKE', "%{$search}%")
                ->orWhere('created_at', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy('id', 'desc')
                ->get();

            $totalFiltered = $main_detail
                ->where('booking_id', 'LIKE', "%{$search}%")
                ->orWhere('s_address', 'LIKE', "%{$search}%")
                ->orWhere('d_address', 'LIKE', "%{$search}%")
                ->orWhere('created_at', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        if (!empty($rides)) {
            foreach ($rides as $index => $ride) {
                $view = route('admin.requests.show', $ride->id);
                if ($ride->s_address != '') {
                    $s_address = $ride->s_address;
                } else {
                    $s_address = "Not Provided";
                }
                if ($ride->stop1_address != '') {
                    $stop1_address = $ride->stop1_address;
                } else {
                    $stop1_address = "-";
                }
                if ($ride->stop2_address != '') {
                    $stop2_address = $ride->stop2_address;
                } else {
                    $stop2_address = "-";
                }
                if ($ride->d_address != '') {
                    $d_address = $ride->d_address;
                } else {
                    $d_address = "Not Provided";
                }
                if ($ride->status != 'CANCELLED') {
                    $detail = '<a class="text-primary" href="' . $view . '"><div class="label label-table label-info">' . trans("admin.member.view") . '</div></a>';
                } else {
                    $detail = '<span>' . trans("admin.member.no_details_found") . '</span>';
                }
                if ($ride->status == "COMPLETED") {
                    $status = '<span class="label label-table label-success">' . $ride->status . '</span>';
                } elseif ($ride->status == "CANCELLED") {
                    $status = '<span class="label label-table label-danger">' . $ride->status . '</span>';
                } else {
                    $status = '<span class="label label-table label-primary">' . $ride->status . '</span>';
                }

                if ($ride->payment) {
                    $total_text = $ride->payment->currency . $ride->payment->total;
                } else {
                    $total_text = '';
                }
                if ($ride->payment) {
                    $earning = $ride->payment->currency . $ride->payment->earnings;
                } else {
                    $earning = '';
                }
                if ($ride->corporate_id != 0) {
                    $payment_mode = 'CORPORATE';
                } else {
                    $payment_mode = $ride->payment_mode;
                }
                $nestedData['id'] = $start + 1;
                $nestedData['booking_id'] = $ride->booking_id;
                $nestedData['s_address'] = $s_address;
                $nestedData['stop1_address'] = $stop1_address;
                $nestedData['stop2_address'] = $stop2_address;
                $nestedData['d_address'] = $d_address;
                $nestedData['detail'] = $detail;
                $nestedData['created_at'] = date('d M Y', strtotime($ride->created_at));
                $nestedData['status'] = $status;
                $nestedData['payment_mode'] = $payment_mode;
                $nestedData['earning'] = $earning;
                $nestedData['total'] = $total_text;
                $data[] = $nestedData;
                $start++;
            }
        }
        $percentage = 0.00;
        if ($total_cancel != 0) {
            if ($totalFiltered != 0) {
                $percentage = round($total_cancel / $totalFiltered, 2);
            }
        }
        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
            "cancel_rides" => $total_cancel,
            "revenue" => $total_revenue,
            "percentage" => $percentage
        );

        echo json_encode($json_data);
    }

    /**
     * account statements.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function Accountstatement($id)
    {

        try {

            $Provider = Provider::find($id);
            $providerid = $id;
            $Joined = $Provider->created_at ? '- Joined ' . $Provider->created_at->diffForHumans() : '';
            $page = $Provider->name . "'s Overall Statement " . $Joined;
            return view('account.providers.provider-content', compact('page', 'providerid'));

        } catch (Exception $e) {
            return back()->with('flash_error', 'Something Went Wrong!');
        }
    }
    /**
     * provider base statements rows.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function account_content(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'booking_id',
            2 => 's_address',
            3 => 'd_address',
            4 => 'detail',
            5 => 'created_at',
            6 => 'status',
            7 => 'payment_mode',
            8 => 'total',
        );
        $fromdate = '';
        $todate = Carbon::now();
        $payment_type = '';
        $tripstatus = '';
        $id = $request->providerid;

        if ($request->fromdate != '') {
            $fromdate = $request->fromdate;
        }
        if ($request->todate != '') {
            $todate = Carbon::parse($request->todate)->addDay();
        }
        if ($request->has('payment')) {
            $payment_type = $request->payment;
        }
        if ($request->has('tripstatus')) {
            $tripstatus = $request->tripstatus;
        }
        $main_detail = UserRequest::with('payment')
            ->where('provider_id', $id)
            ->where('created_at', '>=', $fromdate)
            ->where('created_at', '<', $todate)
            ->where('status', 'LIKE', '%' . $tripstatus . '%');
        if ($payment_type == 'CORPORATE') {
            $main_detail = $main_detail->where('corporate_id', '!=', 0);
        } else {
            if ($payment_type != '') {
                $main_detail = $main_detail->where('corporate_id', '=', 0)->where('payment_mode', 'LIKE', '%' . $payment_type . '%');
            }
        }
        $cancel_rides = UserRequest::where('status', 'CANCELLED')
            ->where('provider_id', $id)
            ->where('created_at', '>=', $fromdate)
            ->where('created_at', '<', $todate)
            ->where('status', 'LIKE', '%' . $tripstatus . '%');
        if ($payment_type == 'CORPORATE') {
            $cancel_rides = $cancel_rides->where('corporate_id', '!=', 0);
        } else {
            if ($payment_type != '') {
                $cancel_rides = $cancel_rides->where('corporate_id', '=', 0)->where('payment_mode', 'LIKE', '%' . $payment_type . '%');
            }
        }
        $revenue = UserRequestPayment::whereHas('request', function ($query) use ($id) {
            $query->where('provider_id', $id);
        })->select(\DB::raw(
                    'SUM(total) as overall'
                ))->where('created_at', '>=', $fromdate)
            ->where('created_at', '<', $todate)->get();

        $total_cancel = $cancel_rides->count();
        $total_revenue = round($revenue[0]->overall, 2);
        $totalData = $main_detail->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $rides = $main_detail
                ->offset($start)
                ->limit($limit)
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $search = $request->input('search.value');

            $rides = $main_detail
                ->where('booking_id', 'LIKE', "%{$search}%")
                ->orWhere('s_address', 'LIKE', "%{$search}%")
                ->orWhere('d_address', 'LIKE', "%{$search}%")
                ->orWhere('created_at', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy('id', 'desc')
                ->get();

            $totalFiltered = $main_detail
                ->where('booking_id', 'LIKE', "%{$search}%")
                ->orWhere('s_address', 'LIKE', "%{$search}%")
                ->orWhere('d_address', 'LIKE', "%{$search}%")
                ->orWhere('created_at', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        if (!empty($rides)) {
            foreach ($rides as $index => $ride) {
                $view = route('account.requests.show', $ride->id);
                if ($ride->s_address != '') {
                    $s_address = $ride->s_address;
                } else {
                    $s_address = "Not Provided";
                }
                if ($ride->d_address != '') {
                    $d_address = $ride->d_address;
                } else {
                    $d_address = "Not Provided";
                }
                if ($ride->status != 'CANCELLED') {
                    $detail = '<a class="text-primary" href="' . $view . '"><div class="label label-table label-info">' . trans("admin.member.view") . '</div></a>';
                } else {
                    $detail = '<span>' . trans("admin.member.no_details_found") . '</span>';
                }
                if ($ride->status == "COMPLETED") {
                    $status = '<span class="label label-table label-success">' . $ride->status . '</span>';
                } elseif ($ride->status == "CANCELLED") {
                    $status = '<span class="label label-table label-danger">' . $ride->status . '</span>';
                } else {
                    $status = '<span class="label label-table label-primary">' . $ride->status . '</span>';
                }

                if ($ride->payment) {
                    $total_text = $ride->payment->currency . $ride->payment->total;
                } else {
                    $total_text = '';
                }
                if ($ride->corporate_id != 0) {
                    $payment_mode = 'CORPORATE';
                } else {
                    $payment_mode = $ride->payment_mode;
                }
                $nestedData['id'] = $start + 1;
                $nestedData['booking_id'] = $ride->booking_id;
                $nestedData['s_address'] = $s_address;
                $nestedData['d_address'] = $d_address;
                $nestedData['detail'] = $detail;
                $nestedData['created_at'] = date('d M Y', strtotime($ride->created_at));
                $nestedData['status'] = $status;
                $nestedData['payment_mode'] = $payment_mode;
                $nestedData['total'] = $total_text;
                $data[] = $nestedData;
                $start++;
            }
        }
        $percentage = 0.00;
        if ($total_cancel != 0) {
            if ($totalFiltered != 0) {
                $percentage = round($total_cancel / $totalFiltered, 2);
            }
        }
        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
            "cancel_rides" => $total_cancel,
            "revenue" => $total_revenue,
            "percentage" => $percentage
        );

        echo json_encode($json_data);
    }

    public function shift($id, Request $request)
    {

        $times = 0;
        $date = "";
        $provide = [];
        $newprovide = [];
        $avl_month = 0;
        $brk_month = 0;
        $dataPoints = [];
        $pro = Provider::where('id', $request->id)->first();
        $pro_login = $pro->login_at;
        if ($pro != null) {
            $admin = Admin::where('id', '=', $pro->admin_id)->first();
            //dd($admin);
            if ($admin != null && $admin->admin_type != 0 && $admin->time_zone != null) {
                date_default_timezone_set($admin->time_zone);
            }
        }
        $now = Carbon::now();
        $fromdate = Carbon::now()->subDays(30);
        $todate = Carbon::now();
        $frm = $fromdate->toDateString();
        $to = $todate->toDateString();
        if ($request->fromdate != '') {
            $fromdate = $request->fromdate;
            $frm = $request->fromdate;
        }
        if ($request->todate != '') {
            $todate = $request->todate . " 23:59:59";
            $to = $request->todate;
        }
        try {

            $shifts = ProviderShift::where('provider_id', '=', $request->id)->where('login_at', '>=', $fromdate)->where('logout_at', '<', $todate)->get();
            //dd($shifts);
            if (count($shifts) > 0) {
                foreach ($shifts as $index => $shift) {

                    $startTime = Carbon::parse($shift->login_at);
                    $finishTime = Carbon::parse($shift->logout_at);
                    $dates = explode(" ", $shift->login_at);
                    //dd($startTime);
                    if ($date == "") {
                        $date = $dates[0];
                    }
                    if ($date == $dates[0] && $shift->logout_at != null) {

                        $times += $finishTime->diffInSeconds($startTime);
                        //dd($finishTime->diffInSeconds($startTime));
                    } else if ($date == $dates[0] && $shift->logout_at == null) {

                        $finishTime = Carbon::parse($now);
                        $times += $finishTime->diffInSeconds($startTime);

                    } else {

                        array_push($provide, ["date" => $date, "times" => $times]);
                        $times = 0;
                        if ($shift->logout_at != null) {
                            $nowsss = \Carbon\Carbon::now()->toDateString();
                            $today = explode(" ", $shift->login_at);
                            if ($nowsss == $today[0]) {
                                $timefinish = Carbon::now()->toDateTimeString();
                            } else {
                                $timefinish = $today[0] . " 23:59:59";
                            }

                            $finishTime = Carbon::parse($timefinish);
                            $times += $finishTime->diffInSeconds($startTime);
                        }
                        $date = $dates[0];
                    }



                }
                array_push($provide, ["date" => $date, "times" => $times]);
                //dd($providerss);
            } else {
                $provide = [];
            }


            $providerStatus = Provider::where('id', '=', $request->id)->first();
            if ($providerStatus != null && $providerStatus->status == "active") {

                $shifts_last = ProviderShift::where('provider_id', $request->id)->where('login_at', 'LIKE', '%' . $request->date . '%')->get();

                $start = Carbon::now()->toDateString() . " 00:00:00";
                $end = Carbon::now()->toDateTimeString();
                $startTime = Carbon::parse($start);
                $finishTime = Carbon::parse($end);
                $times += $finishTime->diffInSeconds($startTime);

                array_push($provide, ["date" => Carbon::now()->toDateString(), "times" => $times]);

            }
            // $provide = [];
            //  array_push($provide, ["date" => Carbon::now()->toDateString(), "times" => 159635]);
            //dd($provide);

            if (count($provide) > 0) {
                for ($i = 0; $i < count($provide); $i++) {



                    $date_detail = $provide[$i]['date'];
                    //dd($date_detail);
                    $times = 0;
                    $date = "";
                    $overall = 0;
                    $last_logout = "";
                    $first_login = "";
                    $providerss = [];
                    $shiftarray = [];
                    $last = "";
                    $first = "";
                    $breaks = "";
                    $brk = 0;
                    $now = Carbon::now()->toDateTimeString();
                    $now_date = Carbon::now()->toDateString();
                    $start = $date_detail . " 00:00:00";
                    $end = $date_detail . " 23:59:59";

                    if ($date_detail == $now_date) {
                        $now = Carbon::now()->toDateTimeString();
                    } else {
                        $now = $end;
                    }

                    try {
                        $shifts = ProviderShift::where('provider_id', $id)->where('login_at', '>=', $start)
                            ->where('logout_at', '<=', $end)->get();

                        $shifts_out = [];
                        if (count($shifts) == 0) {

                            $shifts = ProviderShift::where('provider_id', $id)->where('login_at', '>=', $start)->where('logout_at', '=', null)->get();

                            if (count($shifts) == 1) {

                                foreach ($shifts as $index => $shift) {
                                    if ($index == 0) {
                                        $first_login = $shift->login_at;
                                    }

                                    $startTime = \Carbon\Carbon::parse($shift->login_at);
                                    if ($shift->login_at == $shift->logout_at) {
                                        $finishTime = \Carbon\Carbon::parse($now);
                                    } else {
                                        $finishTime = \Carbon\Carbon::parse($shift->logout_at);
                                    }

                                    $totalDuration = $finishTime->diffInSeconds($startTime);
                                    $time = gmdate('H:i:s', $totalDuration);
                                    $shifts[$index]['time'] = $totalDuration;

                                    $overall += $totalDuration;

                                    $last_last = $now;

                                    if ($index == 0) {
                                        //array_push($shiftarray, ["label"=>"Break", "symbol" => "br","y"=>0,"color"=>"red"]);
                                        if ($totalDuration != 0) {
                                            array_push($shiftarray, ["label" => "Avilable", "symbol" => "av", "y" => $totalDuration, "color" => "green", "msg" => gmdate('H:i:s', $totalDuration)]);
                                        }

                                        //array_push($shiftarray, ["break" => '0', "available" => $totalDuration]);
                                    } else {
                                        $first = \Carbon\Carbon::parse($shift->login_at);
                                        $breaks = $first->diffInSeconds($last);
                                        $brk += $breaks;
                                        $break = gmdate('H:i:s', $breaks);
                                        //array_push($shiftarray, ["break" => $breaks, "available" => $totalDuration]);
                                        if ($breaks != 0) {
                                            array_push($shiftarray, ["label" => "Break", "symbol" => "br", "y" => $breaks, "color" => "red", "msg" => gmdate('H:i:s', $breaks)]);
                                        }
                                        if ($totalDuration != 0) {
                                            array_push($shiftarray, ["label" => "Avilable", "symbol" => "av", "y" => $totalDuration, "color" => "green", "msg" => gmdate('H:i:s', $totalDuration)]);
                                        }
                                    }
                                    $last = $finishTime;
                                }

                            } else {

                                $providerStatus = Provider::where('id', '=', $id)->first();

                                if ($providerStatus != null && $providerStatus->status == "active") {
                                    $shifts_last = ProviderShift::where('provider_id', $id)->where('login_at', 'LIKE', '%' . $date_detail . '%')->get();

                                    if (count($shifts_last) == 0) {



                                        $login_at = $date_detail . " 00:00:00";
                                        $logout_at = Carbon::now()->toDateTimeString();
                                        $startTime = \Carbon\Carbon::parse($login_at);
                                        $nowsss = \Carbon\Carbon::now()->toDateString();
                                        $today = explode(" ", $login_at);
                                        if ($nowsss == $today[0]) {
                                            $timefinish = Carbon::now()->toDateTimeString();
                                        } else {
                                            $timefinish = $today[0] . " 23:59:59";
                                        }

                                        $finishTime = \Carbon\Carbon::parse($timefinish);
                                        $totalDuration = $finishTime->diffInSeconds($startTime);
                                        $time = gmdate('H:i:s', $totalDuration);
                                        //$shifts_out[$index]['time'] = $totalDuration;
                                        //dd($totalDuration);
                                        $overall += $totalDuration;
                                        //dd($overall);
                                        if ($login_at == $logout_at) {

                                            $last_logout = $now;
                                        } else {

                                            $last_logout = $now;
                                        }

                                        // if($index == 0) {
                                        // array_push($shiftarray, ["break" => '0', "available" => $totalDuration]);
                                        // } else {
                                        //dd($last);
                                        //$first = \Carbon\Carbon::parse($login_at);
                                        //$breaks = $first->diffInSeconds($last);
                                        $brk += 0;
                                        // $break = gmdate('H:i:s', $breaks);

                                        //array_push($shiftarray, ["break" => 0, "available" => $totalDuration]);
                                        if ($totalDuration != 0) {
                                            array_push($shiftarray, ["label" => "Avilable", "symbol" => "av", "y" => $totalDuration, "color" => "green", "msg" => gmdate('H:i:s', $totalDuration)]);
                                        }
                                        //}
                                        $last = $finishTime;
                                        $pro_login = $request->date . " 00:00:00";
                                        //$shifts = array_merge($shifts->toArray(),$shifts_out->toArray());


                                    }
                                }
                            }
                            //dd($overall);

                        } else if (count($shifts) > 0) {


                            //dd(count( $shifts));

                            foreach ($shifts as $index => $shift) {

                                if ($index == 0) {
                                    $first_login = $shift->login_at;
                                }

                                $startTime = \Carbon\Carbon::parse($shift->login_at);
                                $finishTime = \Carbon\Carbon::parse($shift->logout_at);
                                $totalDuration = $finishTime->diffInSeconds($startTime);
                                $time = gmdate('H:i:s', $totalDuration);
                                $shifts[$index]['time'] = $totalDuration;
                                $overall += $totalDuration;
                                $last_logout = $shift->logout_at;
                                $last_last = $shift->logout_at;

                                if ($index == 0) {
                                    //array_push($shiftarray, ["break" => '0', "available" => $totalDuration]);
                                    //array_push($shiftarray, ["label"=>"Break", "symbol" => "br","y"=>0,"color"=>"red"]);
                                    if ($totalDuration != 0) {
                                        array_push($shiftarray, ["label" => "Avilable", "symbol" => "av", "y" => $totalDuration, "color" => "green", "msg" => gmdate('H:i:s', $totalDuration)]);
                                    }

                                } else {
                                    $first = \Carbon\Carbon::parse($shift->login_at);
                                    $breaks = $first->diffInSeconds($last);
                                    $brk += $breaks;
                                    $break = gmdate('H:i:s', $breaks);
                                    //array_push($shiftarray, ["break" => $breaks, "available" => $totalDuration]);
                                    if ($breaks != 0) {
                                        array_push($shiftarray, ["label" => "Break", "symbol" => "br", "y" => $breaks, "color" => "red", "msg" => gmdate('H:i:s', $breaks)]);
                                    }
                                    if ($totalDuration != 0) {
                                        array_push($shiftarray, ["label" => "Avilable", "symbol" => "av", "y" => $totalDuration, "color" => "green", "msg" => gmdate('H:i:s', $totalDuration)]);
                                    }

                                }
                                $last = $finishTime;
                            }


                            $shifts_out = ProviderShift::where('provider_id', $id)->where('login_at', '>=', $start)->where('login_at', 'LIKE', '%' . $date_detail . '%')->where('logout_at', '=', null)->get();
                            //dd($shifts_out);
                            if (count($shifts_out) == 1) {

                                foreach ($shifts_out as $index => $shift) {


                                    $startTime = \Carbon\Carbon::parse($shift->login_at);
                                    $nowsss = \Carbon\Carbon::now()->toDateString();
                                    $today = explode(" ", $shift->login_at);
                                    if ($nowsss == $today[0]) {
                                        $timefinish = Carbon::now()->toDateTimeString();
                                    } else {
                                        $timefinish = $today[0] . " 23:59:59";
                                    }

                                    $finishTime = \Carbon\Carbon::parse($timefinish);


                                    $totalDuration = $finishTime->diffInSeconds($startTime);
                                    $time = gmdate('H:i:s', $totalDuration);
                                    $shifts_out[$index]['time'] = $totalDuration;

                                    $overall += $totalDuration;

                                    if ($shift->login_at == $shift->logout_at) {

                                        $last_logout = $now;
                                    } else {

                                        $last_logout = $now;
                                    }

                                    // if($index == 0) {
                                    // array_push($shiftarray, ["break" => '0', "available" => $totalDuration]);
                                    // } else {
                                    //dd($last);
                                    $first = \Carbon\Carbon::parse($shift->login_at);
                                    $breaks = $first->diffInSeconds($last);
                                    $brk += $breaks;
                                    $break = gmdate('H:i:s', $breaks);
                                    //array_push($shiftarray, ["break" => $breaks, "available" => $totalDuration]);
                                    if ($breaks != 0) {
                                        array_push($shiftarray, ["label" => "Break", "symbol" => "br", "y" => $breaks, "color" => "red", "msg" => gmdate('H:i:s', $breaks)]);
                                    }
                                    if ($totalDuration != 0) {
                                        array_push($shiftarray, ["label" => "Avilable", "symbol" => "av", "y" => $totalDuration, "color" => "green", "msg" => gmdate('H:i:s', $totalDuration)]);
                                    }
                                    //}
                                    $last = $finishTime;
                                }
                                $shifts = array_merge($shifts->toArray(), $shifts_out->toArray());


                            }

                        } else {
                            $providerss = [];
                        }

                        $first_logins = \Carbon\Carbon::parse($first_login);
                        $last_logouts = \Carbon\Carbon::parse($last_logout);
                        $diff = $last_logouts->diffInSeconds($first_logins);
                        $break = $overall - $diff;
                        //dd($diff);
                        $init = $overall;
                        $hours = floor($init / 3600);
                        $minutes = floor(($init / 60) % 60);
                        $seconds = $init % 60;
                        $strs = "";
                        $availability = "";
                        $hours = str_pad($hours, 2, '0', STR_PAD_LEFT);
                        $minutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);
                        $seconds = str_pad($seconds, 2, '0', STR_PAD_LEFT);
                        if ($hours != 0) {
                            $availability .= $hours;
                            $str = "Hours";
                        }

                        if ($minutes != 0) {

                            if ($strs == "") {
                                $strs = "Minutes";
                            }

                            if ($strs == "Minutes") {

                                $availability .= $minutes;
                            } else if ($strs == "Hours") {
                                $availability .= ":" . $minutes;
                            }


                        }

                        if ($seconds != 0) {

                            if ($strs == "") {
                                $strs = "Seconds";
                            }
                            if ($strs == "Seconds") {
                                $availability .= $seconds;
                            } else if ($strs == "Hours" || $strs == "Minutes") {
                                $availability .= ":" . $seconds;
                            }
                        }

                        if ($hours == 0 && $minutes == 0 && $seconds == 0) {
                            $availability = "0:0 Seconds";
                        }
                        // if($hours != 0) {
                        //   $availability = $hours." Hours ".$minutes." Minutes ".$seconds." seconds.";   
                        //   } else {
                        //      $availability = $minutes." Minutes ".$seconds." seconds";
                        //   }

                        $init = $brk;
                        $hours = floor($init / 3600);
                        $minutes = floor(($init / 60) % 60);
                        $seconds = $init % 60;
                        $break = "";
                        $str = "";
                        $hours = str_pad($hours, 2, '0', STR_PAD_LEFT);
                        $minutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);
                        $seconds = str_pad($seconds, 2, '0', STR_PAD_LEFT);

                        if ($hours != 0) {
                            $break .= $hours;
                            $str = "Hours";
                        }

                        if ($minutes != 0) {

                            if ($str == "") {
                                $str = "Minutes";
                            }

                            if ($str == "Minutes") {

                                $break .= $minutes;
                            } else if ($str == "Hours") {
                                $break .= ":" . $minutes;
                            }


                        }

                        if ($seconds != 0) {

                            if ($str == "") {
                                $str = "Seconds";
                            }
                            if ($str == "Seconds") {
                                $break .= $seconds;
                            } else if ($str == "Hours" || $str == "Minutes") {
                                $break .= ":" . $seconds;
                            }
                        }

                        if ($hours == 0 && $minutes == 0 && $seconds == 0) {
                            $break = "0:0 Seconds";
                        }

                        if ($first_login != "") {
                            $first = explode(" ", $first_login);
                            $first = $first[1];
                        }
                        if ($last_logout != "") {
                            $last = explode(" ", $last_logout);
                            $last = $last[1];

                        }

                        $times = 0;
                        $date = "";
                        $providerss = [];
                        $start = $date_detail . " 00:00:00";
                        $end = $date_detail . " 23:59:59";

                        $shifts = ProviderShift::where('provider_id', $id)->where('login_at', '>=', $start)
                            ->where('logout_at', '<=', $end)->get();

                        $shifts_null = ProviderShift::where('provider_id', $id)->where('login_at', '>=', $start)->where('login_at', 'LIKE', '%' . $date_detail . '%')->where('logout_at', '=', null)->get();
                        //dd($shifts);

                        $shifts = $shifts->merge($shifts_null);


                        $provider = Provider::where('id', $id)->first();

                        $provider_name = $provider->name . " " . $provider->last_name . "(" . $provider->mobile . ") - " . $date_detail;
                        $avail = $availability . " " . $strs;
                        $br = $break . " " . $str;
                        //dd(gmdate('H:i:s', $overall));
                        $avl_month += $overall;
                        $brk_month += $brk;
                        if ($overall != 0) {
                            array_push($dataPoints, ["label" => "Avilable", "symbol" => "av", "y" => $overall, "color" => "green", "msg" => $date_detail . "/" . gmdate('H:i:s', $overall)]);
                        }
                        if ($overall != 0) {
                            array_push($dataPoints, ["label" => "Break", "symbol" => "br", "y" => $brk, "color" => "red", "msg" => $date_detail . "/" . gmdate('H:i:s', $brk)]);
                        }


                    } catch (Exception $e) {
                        return back()->with('flash_error', $e->getMessage());
                    }

                    array_push($newprovide, ["date" => $date_detail, "available" => gmdate('H:i:s', $overall), "break" => gmdate('H:i:s', $brk)]);

                }
            }
            //dd($newprovide);

            $init = $brk_month;
            $hours = floor($init / 3600);
            $minutes = floor(($init / 60) % 60);
            $seconds = $init % 60;
            $break = "";
            $str = "";
            if ($hours != 0) {
                $break .= $hours . ":";
                $str = "Hours";
            }

            if ($minutes != 0) {
                $break .= $minutes;
                if ($str == "") {
                    $str = "Minutes";
                }

            }

            if ($seconds != 0) {
                $break .= ":" . $seconds;
                if ($str == "") {
                    $str = "Seconds";
                }
            }

            if ($hours == 0 && $minutes == 0 && $seconds == 0) {
                $break = "0:0 Seconds";
            }

            $break = $break . " " . $str;

            $init = $avl_month;
            $hours = floor($init / 3600);
            $minutes = floor(($init / 60) % 60);
            $seconds = $init % 60;
            $strs = "";
            $availability = "";
            if ($hours != 0) {

                $availability .= $hours . ":";
                $strs = "Hours";
            }

            if ($minutes != 0) {
                $availability .= $minutes;
                if ($strs == "") {

                    $strs = "Minutes";
                }
            }

            if ($seconds != 0) {
                $availability .= ":" . $seconds;
                if ($strs == "") {
                    $strs = "Seconds";
                }
            }

            if ($hours == 0 && $minutes == 0 && $seconds == 0) {
                $availability = "0:0 Seconds";
            }

            $availability = $availability . " " . $strs;

            $provider = Provider::where('id', $id)->first();
            $provider_id = $id;
            $provider_name = $provider->name . " " . $provider->last_name . "(" . $provider->mobile . ")";
            $fromdate = explode(" ", $fromdate);
            $fromdate = $fromdate[0];
            $todate = explode(" ", $todate);
            $todate = $todate[0];
            // return view('admin.providers.shift', compact('provider_name','providerss','provider_id','dataPoints','break','availability','fromdate','todate','newprovide'));
            return view('admin.providers.shift', compact('provider_name', 'provider_id', 'dataPoints', 'break', 'availability', 'fromdate', 'todate', 'newprovide'));
        } catch (Exception $e) {
            return back()->with('flash_error', $e->getMessage());
        }
    }

    public function listallshift(Request $request)
    {

        $dataPoints = [];
        $id = $request->id;
        $times = 0;
        $date = "";
        $provide = [];
        $now = Carbon::now();
        $fromdate = Carbon::now()->subDays(30);
        $todate = Carbon::now();
        $frm = $fromdate->toDateString();
        $to = $todate->toDateString();
        if ($request->fromdate != '') {
            $fromdate = $request->fromdate;
            $frm = $request->fromdate;
        }
        if ($request->todate != '') {
            $todate = $request->todate . " 23:59:59";
            $to = $request->todate;
        }
        //$frm = $fromdate->toDateString();

        //dd($to);

        try {
            $shifts = ProviderShift::where('provider_id', '=', $request->id)->where('login_at', '>=', $fromdate)->where('logout_at', '<', $todate)->get();

            if (count($shifts) > 0) {
                foreach ($shifts as $index => $shift) {

                    $startTime = Carbon::parse($shift->login_at);
                    $finishTime = Carbon::parse($shift->logout_at);
                    $dates = explode(" ", $shift->login_at);
                    if ($date == "") {
                        $date = $dates[0];
                    }
                    if ($date == $dates[0] && $shift->logout_at != null) {

                        $times += $finishTime->diffInSeconds($startTime);

                    } else if ($date == $dates[0] && $shift->logout_at == null) {

                        $finishTime = Carbon::parse($now);
                        $times += $finishTime->diffInSeconds($startTime);

                    } else {

                        array_push($provide, ["date" => $date, "times" => $times]);
                        $times = 0;
                        if ($shift->logout_at != null) {
                            $times += $finishTime->diffInSeconds($startTime);
                        }
                        $date = $dates[0];
                    }



                }
                array_push($provide, ["date" => $date, "times" => $times]);
                //dd($provide);
            } else {
                $provide = [];
            }

            if (count($provide) > 0) {
                for ($i = 0; $i < count($provide); $i++) {



                    $date_detail = $provide[$i]['date'];
                    $times = 0;
                    $date = "";
                    $overall = 0;
                    $last_logout = "";
                    $first_login = "";
                    $providerss = [];
                    $shiftarray = [];
                    $last = "";
                    $first = "";
                    $breaks = "";
                    $brk = 0;
                    $now = Carbon::now()->toDateTimeString();
                    $now_date = Carbon::now()->toDateString();
                    $start = $date_detail . " 00:00:00";
                    $end = $date_detail . " 23:59:59";

                    if ($date_detail == $now_date) {
                        $now = Carbon::now()->toDateTimeString();
                    } else {
                        $now = $end;
                    }
                    try {
                        $shifts = ProviderShift::where('provider_id', $id)->where('login_at', '>=', $start)
                            ->where('logout_at', '<=', $end)->get();

                        $shifts_out = [];
                        if (count($shifts) == 0) {

                            $shifts = ProviderShift::where('provider_id', $id)->where('login_at', '>=', $start)->where('logout_at', '=', null)->get();

                            if (count($shifts) == 1) {

                                foreach ($shifts as $index => $shift) {
                                    if ($index == 0) {
                                        $first_login = $shift->login_at;
                                    }

                                    $startTime = \Carbon\Carbon::parse($shift->login_at);
                                    if ($shift->login_at == $shift->logout_at) {
                                        $finishTime = \Carbon\Carbon::parse($now);
                                    } else {
                                        $finishTime = \Carbon\Carbon::parse($shift->logout_at);
                                    }

                                    $totalDuration = $finishTime->diffInSeconds($startTime);
                                    $time = gmdate('H:i:s', $totalDuration);
                                    $shifts[$index]['time'] = $totalDuration;

                                    $overall += $totalDuration;

                                    $last_last = $now;

                                    if ($index == 0) {
                                        //array_push($shiftarray, ["label"=>"Break", "symbol" => "br","y"=>0,"color"=>"red"]);
                                        if ($totalDuration != 0) {
                                            array_push($shiftarray, ["label" => "Avilable", "symbol" => "av", "y" => $totalDuration, "color" => "green", "msg" => gmdate('H:i:s', $totalDuration)]);
                                        }

                                        //array_push($shiftarray, ["break" => '0', "available" => $totalDuration]);
                                    } else {
                                        $first = \Carbon\Carbon::parse($shift->login_at);
                                        $breaks = $first->diffInSeconds($last);
                                        $brk += $breaks;
                                        $break = gmdate('H:i:s', $breaks);
                                        //array_push($shiftarray, ["break" => $breaks, "available" => $totalDuration]);
                                        if ($breaks != 0) {
                                            array_push($shiftarray, ["label" => "Break", "symbol" => "br", "y" => $breaks, "color" => "red", "msg" => gmdate('H:i:s', $breaks)]);
                                        }
                                        if ($totalDuration != 0) {
                                            array_push($shiftarray, ["label" => "Avilable", "symbol" => "av", "y" => $totalDuration, "color" => "green", "msg" => gmdate('H:i:s', $totalDuration)]);
                                        }
                                    }
                                    $last = $finishTime;
                                }

                            }

                        } else if (count($shifts) > 0) {


                            //dd(count( $shifts));

                            foreach ($shifts as $index => $shift) {

                                if ($index == 0) {
                                    $first_login = $shift->login_at;
                                }

                                $startTime = \Carbon\Carbon::parse($shift->login_at);
                                $finishTime = \Carbon\Carbon::parse($shift->logout_at);
                                $totalDuration = $finishTime->diffInSeconds($startTime);
                                $time = gmdate('H:i:s', $totalDuration);
                                $shifts[$index]['time'] = $totalDuration;
                                $overall += $totalDuration;
                                $last_logout = $shift->logout_at;
                                $last_last = $shift->logout_at;

                                if ($index == 0) {
                                    //array_push($shiftarray, ["break" => '0', "available" => $totalDuration]);
                                    //array_push($shiftarray, ["label"=>"Break", "symbol" => "br","y"=>0,"color"=>"red"]);
                                    if ($totalDuration != 0) {
                                        array_push($shiftarray, ["label" => "Avilable", "symbol" => "av", "y" => $totalDuration, "color" => "green", "msg" => gmdate('H:i:s', $totalDuration)]);
                                    }

                                } else {
                                    $first = \Carbon\Carbon::parse($shift->login_at);
                                    $breaks = $first->diffInSeconds($last);
                                    $brk += $breaks;
                                    $break = gmdate('H:i:s', $breaks);
                                    //array_push($shiftarray, ["break" => $breaks, "available" => $totalDuration]);
                                    if ($breaks != 0) {
                                        array_push($shiftarray, ["label" => "Break", "symbol" => "br", "y" => $breaks, "color" => "red", "msg" => gmdate('H:i:s', $breaks)]);
                                    }
                                    if ($totalDuration != 0) {
                                        array_push($shiftarray, ["label" => "Avilable", "symbol" => "av", "y" => $totalDuration, "color" => "green", "msg" => gmdate('H:i:s', $totalDuration)]);
                                    }

                                }
                                $last = $finishTime;
                            }


                            $shifts_out = ProviderShift::where('provider_id', $id)->where('login_at', '>=', $start)->where('login_at', 'LIKE', '%' . $date_detail . '%')->where('logout_at', '=', null)->get();
                            //dd($shifts_out);
                            if (count($shifts_out) == 1) {

                                foreach ($shifts_out as $index => $shift) {


                                    $startTime = \Carbon\Carbon::parse($shift->login_at);
                                    $nowsss = \Carbon\Carbon::now()->toDateString();
                                    $today = explode(" ", $shift->login_at);
                                    if ($nowsss == $today[0]) {
                                        $timefinish = Carbon::now()->toDateTimeString();
                                    } else {
                                        $timefinish = $today[0] . " 23:59:59";
                                    }

                                    $finishTime = \Carbon\Carbon::parse($timefinish);


                                    $totalDuration = $finishTime->diffInSeconds($startTime);
                                    $time = gmdate('H:i:s', $totalDuration);
                                    $shifts_out[$index]['time'] = $totalDuration;

                                    $overall += $totalDuration;

                                    if ($shift->login_at == $shift->logout_at) {

                                        $last_logout = $now;
                                    } else {

                                        $last_logout = $now;
                                    }

                                    // if($index == 0) {
                                    // array_push($shiftarray, ["break" => '0', "available" => $totalDuration]);
                                    // } else {
                                    //dd($last);
                                    $first = \Carbon\Carbon::parse($shift->login_at);
                                    $breaks = $first->diffInSeconds($last);
                                    $brk += $breaks;
                                    $break = gmdate('H:i:s', $breaks);
                                    //array_push($shiftarray, ["break" => $breaks, "available" => $totalDuration]);
                                    if ($breaks != 0) {
                                        array_push($shiftarray, ["label" => "Break", "symbol" => "br", "y" => $breaks, "color" => "red", "msg" => gmdate('H:i:s', $breaks)]);
                                    }
                                    if ($totalDuration != 0) {
                                        array_push($shiftarray, ["label" => "Avilable", "symbol" => "av", "y" => $totalDuration, "color" => "green", "msg" => gmdate('H:i:s', $totalDuration)]);
                                    }
                                    //}
                                    $last = $finishTime;
                                }
                                $shifts = array_merge($shifts->toArray(), $shifts_out->toArray());


                            }

                        } else {
                            $providerss = [];
                        }
                        //dd($overall);
                        $first_logins = \Carbon\Carbon::parse($first_login);
                        $last_logouts = \Carbon\Carbon::parse($last_logout);
                        $diff = $last_logouts->diffInSeconds($first_logins);
                        $break = $overall - $diff;
                        //dd($diff);
                        $init = $overall;
                        $hours = floor($init / 3600);
                        $minutes = floor(($init / 60) % 60);
                        $seconds = $init % 60;
                        $strs = "";
                        $availability = "";
                        if ($hours != 0) {
                            $availability .= $hours . ":";
                            $strs = "Hours";
                        }

                        if ($minutes != 0) {
                            $availability .= $minutes;
                            if ($strs == "") {
                                $strs = "Minutes";
                            }
                        }

                        if ($seconds != 0) {
                            $availability .= ":" . $seconds;
                            if ($strs == "") {
                                $strs = "Seconds";
                            }
                        }

                        if ($hours == 0 && $minutes == 0 && $seconds == 0) {
                            $availability = "0:0 Seconds";
                        }
                        // if($hours != 0) {
                        //   $availability = $hours." Hours ".$minutes." Minutes ".$seconds." seconds.";   
                        //   } else {
                        //      $availability = $minutes." Minutes ".$seconds." seconds";
                        //   }

                        $init = $brk;
                        $hours = floor($init / 3600);
                        $minutes = floor(($init / 60) % 60);
                        $seconds = $init % 60;
                        $break = "";
                        $str = "";
                        if ($hours != 0) {
                            $break .= $hours . ":";
                            $str = "Hours";
                        }

                        if ($minutes != 0) {
                            $break .= $minutes;
                            if ($str == "") {
                                $str = "Minutes";
                            }

                        }

                        if ($seconds != 0) {
                            $break .= ":" . $seconds;
                            if ($str == "") {
                                $str = "Seconds";
                            }
                        }

                        if ($hours == 0 && $minutes == 0 && $seconds == 0) {
                            $break = "0:0 Seconds";
                        }

                        if ($first_login != "") {
                            $first = explode(" ", $first_login);
                            $first = $first[1];
                        }
                        if ($last_logout != "") {
                            $last = explode(" ", $last_logout);
                            $last = $last[1];

                        }

                        $times = 0;
                        $date = "";
                        $providerss = [];
                        $start = $date_detail . " 00:00:00";
                        $end = $date_detail . " 23:59:59";

                        $shifts = ProviderShift::where('provider_id', $id)->where('login_at', '>=', $start)
                            ->where('logout_at', '<=', $end)->get();

                        $shifts_null = ProviderShift::where('provider_id', $id)->where('login_at', '>=', $start)->where('login_at', 'LIKE', '%' . $date_detail . '%')->where('logout_at', '=', null)->get();
                        //dd($shifts);

                        $shifts = $shifts->merge($shifts_null);


                        $provider = Provider::where('id', $id)->first();

                        $provider_name = $provider->name . " " . $provider->last_name . "(" . $provider->mobile . ") - " . $date_detail;
                        $avail = $availability . " " . $strs;
                        $br = $break . " " . $str;
                        //dd($br);
                        array_push($dataPoints, ["label" => "Avilable", "symbol" => "av", "y" => $overall, "color" => "green", "msg" => gmdate('H:i:s', $overall)]);
                        array_push($dataPoints, ["label" => "Break", "symbol" => "br", "y" => $brk, "color" => "red", "msg" => gmdate('H:i:s', $brk)]);


                    } catch (Exception $e) {
                        return back()->with('flash_error', $e->getMessage());
                    }

                }
            }
            //dd($dataPoints);
            $provider = Provider::where('id', $request->id)->first();
            $provider_id = $request->id;
            $provider_name = $provider->name . " " . $provider->last_name . "(" . $provider->mobile . ")";
            return view('admin.providers.shiftload', compact('provider_name', 'provide', 'provider_id', 'frm', 'to', 'dataPoints'));
        } catch (Exception $e) {
            return back()->with('flash_error', $e->getMessage());
        }
    }

    public function shift_details($id, $date_detail)
    {

        $times = 0;
        $date = "";
        $overall = 0;
        $last_logout = "";
        $first_login = "";
        $providerss = [];
        $shiftarray = [];
        $last = "";
        $first = "";
        $breaks = "";
        $brk = 0;
        $pro = Provider::where('id', $id)->first();
        $pro_login = $pro->login_at;
        if ($pro != null) {
            $admin = Admin::where('id', '=', $pro->admin_id)->first();
            if ($admin != null && $admin->admin_type != 0 && $admin->time_zone != null) {
                date_default_timezone_set($admin->time_zone);
            }
        }

        $now = Carbon::now()->toDateTimeString();
        $now_date = Carbon::now()->toDateString();
        $start = $date_detail . " 00:00:00";
        $end = $date_detail . " 23:59:59";

        if ($date_detail == $now_date) {
            $now = Carbon::now()->toDateTimeString();
        } else {
            $now = $end;
        }
        try {

            $pro = Provider::where('id', $id)->first();

            $shifts = ProviderShift::where('provider_id', $id)->where('login_at', '>=', $start)
                ->where('logout_at', '<=', $end)->get();

            $shifts_out = [];
            //dd($start."==".$end);
            if (count($shifts) == 0) {

                $shifts = ProviderShift::where('provider_id', $id)->where('login_at', '>=', $start)->where('logout_at', '=', null)->get();

                if (count($shifts) == 1) {

                    foreach ($shifts as $index => $shift) {
                        if ($index == 0) {
                            $first_login = $shift->login_at;
                        }

                        $startTime = \Carbon\Carbon::parse($shift->login_at);
                        if ($shift->login_at == $shift->logout_at) {
                            $finishTime = \Carbon\Carbon::parse($now);
                        } else {
                            $finishTime = \Carbon\Carbon::parse($shift->logout_at);
                        }

                        $totalDuration = $finishTime->diffInSeconds($startTime);

                        $time = gmdate('H:i:s', $totalDuration);
                        $shifts[$index]['time'] = $totalDuration;

                        $overall += $totalDuration;

                        $last_last = $now;

                        if ($index == 0) {
                            //array_push($shiftarray, ["label"=>"Break", "symbol" => "br","y"=>0,"color"=>"red"]);
                            if ($totalDuration != 0) {
                                array_push($shiftarray, ["label" => "Avilable", "symbol" => "av", "y" => $totalDuration, "color" => "green", "msg" => gmdate('H:i:s', $totalDuration)]);
                            }

                            //array_push($shiftarray, ["break" => '0', "available" => $totalDuration]);
                        } else {
                            $first = \Carbon\Carbon::parse($shift->login_at);
                            $breaks = $first->diffInSeconds($last);
                            $brk += $breaks;
                            $break = gmdate('H:i:s', $breaks);
                            //array_push($shiftarray, ["break" => $breaks, "available" => $totalDuration]);
                            if ($breaks != 0) {
                                array_push($shiftarray, ["label" => "Break", "symbol" => "br", "y" => $breaks, "color" => "red", "msg" => gmdate('H:i:s', $breaks)]);
                            }
                            if ($totalDuration != 0) {
                                array_push($shiftarray, ["label" => "Avilable", "symbol" => "av", "y" => $totalDuration, "color" => "green", "msg" => gmdate('H:i:s', $totalDuration)]);
                            }
                        }
                        $last = $finishTime;
                    }

                } else {

                    $providerStatus = Provider::where('id', '=', $id)->first();

                    if ($providerStatus != null && $providerStatus->status == "active") {
                        $shifts_last = ProviderShift::where('provider_id', $id)->where('login_at', 'LIKE', '%' . $date_detail . '%')->get();

                        if (count($shifts_last) == 0) {



                            $login_at = $date_detail . " 00:00:00";
                            $logout_at = Carbon::now()->toDateTimeString();
                            $startTime = \Carbon\Carbon::parse($login_at);
                            $nowsss = \Carbon\Carbon::now()->toDateString();
                            $today = explode(" ", $login_at);
                            if ($nowsss == $today[0]) {
                                $timefinish = Carbon::now()->toDateTimeString();
                            } else {
                                $timefinish = $today[0] . " 23:59:59";
                            }

                            $finishTime = \Carbon\Carbon::parse($timefinish);
                            $totalDuration = $finishTime->diffInSeconds($startTime);
                            $time = gmdate('H:i:s', $totalDuration);
                            //$shifts_out[$index]['time'] = $totalDuration;
                            //dd($totalDuration);
                            $overall += $totalDuration;
                            //dd($overall);
                            if ($login_at == $logout_at) {

                                $last_logout = $now;
                            } else {

                                $last_logout = $now;
                            }

                            // if($index == 0) {
                            // array_push($shiftarray, ["break" => '0', "available" => $totalDuration]);
                            // } else {
                            //dd($last);
                            //$first = \Carbon\Carbon::parse($login_at);
                            //$breaks = $first->diffInSeconds($last);
                            $brk += 0;
                            // $break = gmdate('H:i:s', $breaks);

                            //array_push($shiftarray, ["break" => 0, "available" => $totalDuration]);
                            if ($totalDuration != 0) {
                                array_push($shiftarray, ["label" => "Avilable", "symbol" => "av", "y" => $totalDuration, "color" => "green", "msg" => gmdate('H:i:s', $totalDuration)]);
                            }
                            //}
                            $last = $finishTime;
                            $pro_login = $date_detail . " 00:00:00";
                            //$shifts = array_merge($shifts->toArray(),$shifts_out->toArray());


                        }
                    }
                }

            } else if (count($shifts) > 0) {


                //dd(count($shifts));

                foreach ($shifts as $index => $shift) {

                    if ($index == 0) {
                        $first_login = $shift->login_at;
                    }

                    $startTime = \Carbon\Carbon::parse($shift->login_at);
                    $finishTime = \Carbon\Carbon::parse($shift->logout_at);
                    $totalDuration = $finishTime->diffInSeconds($startTime);
                    $time = gmdate('H:i:s', $totalDuration);
                    $shifts[$index]['time'] = $totalDuration;
                    $overall += $totalDuration;
                    $last_logout = $shift->logout_at;
                    $last_last = $shift->logout_at;
                    //dd($totalDuration);
                    if ($index == 0) {
                        //array_push($shiftarray, ["break" => '0', "available" => $totalDuration]);
                        //array_push($shiftarray, ["label"=>"Break", "symbol" => "br","y"=>0,"color"=>"red"]);
                        if ($totalDuration != 0) {
                            array_push($shiftarray, ["label" => "Avilable", "symbol" => "av", "y" => $totalDuration, "color" => "green", "msg" => gmdate('H:i:s', $totalDuration)]);
                        }

                    } else {
                        $first = \Carbon\Carbon::parse($shift->login_at);
                        $breaks = $first->diffInSeconds($last);
                        $brk += $breaks;
                        $break = gmdate('H:i:s', $breaks);
                        //array_push($shiftarray, ["break" => $breaks, "available" => $totalDuration]);
                        if ($breaks != 0) {
                            array_push($shiftarray, ["label" => "Break", "symbol" => "br", "y" => $breaks, "color" => "red", "msg" => gmdate('H:i:s', $breaks)]);
                        }
                        if ($totalDuration != 0) {
                            array_push($shiftarray, ["label" => "Avilable", "symbol" => "av", "y" => $totalDuration, "color" => "green", "msg" => gmdate('H:i:s', $totalDuration)]);
                        }

                    }
                    $last = $finishTime;

                }


                $shifts_out = ProviderShift::where('provider_id', $id)->where('login_at', '>=', $start)->where('login_at', 'LIKE', '%' . $date_detail . '%')->where('logout_at', '=', null)->get();
                //dd($shifts_out);
                if (count($shifts_out) == 1) {

                    foreach ($shifts_out as $index => $shift) {


                        $startTime = \Carbon\Carbon::parse($shift->login_at);
                        $nowsss = \Carbon\Carbon::now()->toDateString();
                        $today = explode(" ", $shift->login_at);
                        if ($nowsss == $today[0]) {
                            $timefinish = Carbon::now()->toDateTimeString();
                        } else {
                            $timefinish = $today[0] . " 23:59:59";
                        }

                        $finishTime = \Carbon\Carbon::parse($timefinish);


                        $totalDuration = $finishTime->diffInSeconds($startTime);
                        $time = gmdate('H:i:s', $totalDuration);
                        $shifts_out[$index]['time'] = $totalDuration;
                        //dd($totalDuration);
                        $overall += $totalDuration;

                        if ($shift->login_at == $shift->logout_at) {

                            $last_logout = $now;
                        } else {

                            $last_logout = $now;
                        }

                        // if($index == 0) {
                        // array_push($shiftarray, ["break" => '0', "available" => $totalDuration]);
                        // } else {
                        //dd($last);
                        $first = \Carbon\Carbon::parse($shift->login_at);
                        $breaks = $first->diffInSeconds($last);
                        $brk += $breaks;
                        $break = gmdate('H:i:s', $breaks);
                        //array_push($shiftarray, ["break" => $breaks, "available" => $totalDuration]);
                        if ($breaks != 0) {
                            array_push($shiftarray, ["label" => "Break", "symbol" => "br", "y" => $breaks, "color" => "red", "msg" => gmdate('H:i:s', $breaks)]);
                        }
                        if ($totalDuration != 0) {
                            array_push($shiftarray, ["label" => "Avilable", "symbol" => "av", "y" => $totalDuration, "color" => "green", "msg" => gmdate('H:i:s', $totalDuration)]);
                        }
                        //}
                        $last = $finishTime;
                    }
                    $shifts = array_merge($shifts->toArray(), $shifts_out->toArray());


                }

            } else {
                $providerss = [];
            }
            //dd($overall);
            $first_logins = \Carbon\Carbon::parse($first_login);
            $last_logouts = \Carbon\Carbon::parse($last_logout);
            $diff = $last_logouts->diffInSeconds($first_logins);
            $break = $overall - $diff;
            //dd($overall);
            $init = $overall;
            $hours = floor($init / 3600);
            $minutes = floor(($init / 60) % 60);
            $seconds = $init % 60;
            $strs = "";
            $availability = "";
            $hours = str_pad($hours, 2, '0', STR_PAD_LEFT);
            $minutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);
            $seconds = str_pad($seconds, 2, '0', STR_PAD_LEFT);
            if ($hours != 0) {
                $availability .= $hours;
                $strs = "Hours";
            }

            if ($minutes != 0) {

                if ($strs == "") {
                    $strs = "Minutes";
                }

                if ($strs == "Minutes") {

                    $availability .= $minutes;
                } else if ($strs == "Hours") {
                    $availability .= ":" . $minutes;
                }


            }

            if ($seconds != 0) {

                if ($strs == "") {
                    $strs = "Seconds";
                }
                if ($strs == "Seconds") {
                    $availability .= $seconds;
                } else if ($strs == "Hours" || $strs == "Minutes") {
                    $availability .= ":" . $seconds;
                }
            }

            if ($hours == 0 && $minutes == 0 && $seconds == 0) {
                $availability = "0:0 Seconds";
            }
            // if($hours != 0) {
            //   $availability = $hours." Hours ".$minutes." Minutes ".$seconds." seconds.";   
            //   } else {
            //      $availability = $minutes." Minutes ".$seconds." seconds";
            //   }

            $init = $brk;
            $hours = floor($init / 3600);
            $minutes = floor(($init / 60) % 60);
            $seconds = $init % 60;
            $break = "";
            $str = "";
            $hours = str_pad($hours, 2, '0', STR_PAD_LEFT);
            $minutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);
            $seconds = str_pad($seconds, 2, '0', STR_PAD_LEFT);

            if ($hours != 0) {
                $break .= $hours;
                $str = "Hours";
            }

            if ($minutes != 0) {

                if ($str == "") {
                    $str = "Minutes";
                }

                if ($str == "Minutes") {

                    $break .= $minutes;
                } else if ($str == "Hours") {
                    $break .= ":" . $minutes;
                }


            }

            if ($seconds != 0) {

                if ($str == "") {
                    $str = "Seconds";
                }
                if ($str == "Seconds") {
                    $break .= $seconds;
                } else if ($str == "Hours" || $str == "Minutes") {
                    $break .= ":" . $seconds;
                }
            }

            if ($hours == 0 && $minutes == 0 && $seconds == 0) {
                $break = "0:0 Seconds";
            }

            $login_date = "";
            if ($pro_login != "") {
                $first = explode(" ", $pro_login);
                $login_date = $first[0];
                $first = $first[1];

            }
            $logout_date = \Carbon\Carbon::now()->toDateString();
            $last = "00:00:00";
            if ($pro->logout_at != "") {
                $last = explode(" ", $pro->logout_at);
                $logout_date = $last[0];
                $last = $last[1];
                //dd($last);

            }

            $times = 0;
            $date = "";
            $providerss = [];
            $start = $date_detail . " 00:00:00";
            $end = $date_detail . " 23:59:59";

            $shifts = ProviderShift::where('provider_id', $id)->where('login_at', '>=', $start)
                ->where('logout_at', '<=', $end)->get();

            $shifts_null = ProviderShift::where('provider_id', $id)->where('login_at', '>=', $start)->where('login_at', 'LIKE', '%' . $date_detail . '%')->where('logout_at', '=', null)->get();
            //dd($shifts);

            $shifts = $shifts->merge($shifts_null);


            $provider = Provider::where('id', $id)->first();

            $provider_name = $provider->name . " " . $provider->last_name . "(" . $provider->mobile . ") - " . $date_detail;
            $avail = $availability . " " . $strs;
            $br = $break . " " . $str;
            return view('admin.providers.shift_detail', compact('provider_name', 'shifts', 'shiftarray', 'first', 'last', 'avail', 'br', 'date_detail', 'login_date', 'logout_date'));
            // return response()->json([
            //             'shiftarray' =>  $shiftarray,
            //             'login_at' =>  $first,
            //             'logout_at' =>  $last,
            //             'availability' =>  $availability." ".$strs,
            //             'Break' =>  $break." ".$str,
            //             'providers_shit_days' => $shifts,

            //         ]);
        } catch (Exception $e) {
            return back()->with('flash_error', $e->getMessage());
        }
    }

    public function listallshiftmonth(Request $request)
    {


        $times_month = 0;
        $date_month = "";
        $provider_month = [];
        $dataPoints = [];
        $now = Carbon::now();
        $id = $request->id;

        $shifts = ProviderShift::where('provider_id', $id)->get();
        //dd($shifts);
        if (count($shifts) > 0) {
            foreach ($shifts as $index => $shift) {

                $startTime = Carbon::parse($shift->login_at);
                $finishTime = Carbon::parse($shift->logout_at);
                $dates = explode(" ", $shift->login_at);
                //dd($startTime);
                if ($date_month == "") {
                    $date_month = $dates[0];
                }
                if ($date_month == $dates[0] && $shift->logout_at != null) {

                    $times_month += $finishTime->diffInSeconds($startTime);
                    //dd($finishTime->diffInSeconds($startTime));
                } else if ($date_month == $dates[0] && $shift->logout_at == null) {

                    $finishTime = Carbon::parse($now);
                    $times_month += $finishTime->diffInSeconds($startTime);

                } else {

                    array_push($provider_month, ["date_month" => $date_month, "times_month" => $times_month]);
                    $times_month = 0;
                    if ($shift->logout_at != null) {
                        $times_month += $finishTime->diffInSeconds($startTime);
                    }
                    $date_month = $dates[0];
                }



            }
            array_push($provider_month, ["date_month" => $date_month, "times_month" => $times_month]);
            //dd($provider_month);
        } else {
            $provider_month = [];
        }
        if (count($provider_month) > 0) {
            for ($i = 0; $i < count($provider_month); $i++) {



                $date_detail = $provider_month[$i]['date_month'];
                $times = 0;
                $date = "";
                $overall = 0;
                $last_logout = "";
                $first_login = "";
                $providerss = [];
                $shiftarray = [];
                $last = "";
                $first = "";
                $breaks = "";
                $brk = 0;
                $now = Carbon::now()->toDateTimeString();
                $now_date = Carbon::now()->toDateString();
                $start = $date_detail . " 00:00:00";
                $end = $date_detail . " 23:59:59";

                if ($date_detail == $now_date) {
                    $now = Carbon::now()->toDateTimeString();
                } else {
                    $now = $end;
                }
                try {
                    $shifts = ProviderShift::where('provider_id', $id)->where('login_at', '>=', $start)
                        ->where('logout_at', '<=', $end)->get();

                    $shifts_out = [];
                    if (count($shifts) == 0) {

                        $shifts = ProviderShift::where('provider_id', $id)->where('login_at', '>=', $start)->where('logout_at', '=', null)->get();

                        if (count($shifts) == 1) {

                            foreach ($shifts as $index => $shift) {
                                if ($index == 0) {
                                    $first_login = $shift->login_at;
                                }

                                $startTime = \Carbon\Carbon::parse($shift->login_at);
                                if ($shift->login_at == $shift->logout_at) {
                                    $finishTime = \Carbon\Carbon::parse($now);
                                } else {
                                    $finishTime = \Carbon\Carbon::parse($shift->logout_at);
                                }

                                $totalDuration = $finishTime->diffInSeconds($startTime);
                                $time = gmdate('H:i:s', $totalDuration);
                                $shifts[$index]['time'] = $totalDuration;

                                $overall += $totalDuration;

                                $last_last = $now;

                                if ($index == 0) {
                                    //array_push($shiftarray, ["label"=>"Break", "symbol" => "br","y"=>0,"color"=>"red"]);
                                    if ($totalDuration != 0) {
                                        array_push($shiftarray, ["label" => "Avilable", "symbol" => "av", "y" => $totalDuration, "color" => "green", "msg" => gmdate('H:i:s', $totalDuration)]);
                                    }

                                    //array_push($shiftarray, ["break" => '0', "available" => $totalDuration]);
                                } else {
                                    $first = \Carbon\Carbon::parse($shift->login_at);
                                    $breaks = $first->diffInSeconds($last);
                                    $brk += $breaks;
                                    $break = gmdate('H:i:s', $breaks);
                                    //array_push($shiftarray, ["break" => $breaks, "available" => $totalDuration]);
                                    if ($breaks != 0) {
                                        array_push($shiftarray, ["label" => "Break", "symbol" => "br", "y" => $breaks, "color" => "red", "msg" => gmdate('H:i:s', $breaks)]);
                                    }
                                    if ($totalDuration != 0) {
                                        array_push($shiftarray, ["label" => "Avilable", "symbol" => "av", "y" => $totalDuration, "color" => "green", "msg" => gmdate('H:i:s', $totalDuration)]);
                                    }
                                }
                                $last = $finishTime;
                            }

                        }

                    } else if (count($shifts) > 0) {


                        //dd(count( $shifts));

                        foreach ($shifts as $index => $shift) {

                            if ($index == 0) {
                                $first_login = $shift->login_at;
                            }

                            $startTime = \Carbon\Carbon::parse($shift->login_at);
                            $finishTime = \Carbon\Carbon::parse($shift->logout_at);
                            $totalDuration = $finishTime->diffInSeconds($startTime);
                            $time = gmdate('H:i:s', $totalDuration);
                            $shifts[$index]['time'] = $totalDuration;
                            $overall += $totalDuration;
                            $last_logout = $shift->logout_at;
                            $last_last = $shift->logout_at;

                            if ($index == 0) {
                                //array_push($shiftarray, ["break" => '0', "available" => $totalDuration]);
                                //array_push($shiftarray, ["label"=>"Break", "symbol" => "br","y"=>0,"color"=>"red"]);
                                if ($totalDuration != 0) {
                                    array_push($shiftarray, ["label" => "Avilable", "symbol" => "av", "y" => $totalDuration, "color" => "green", "msg" => gmdate('H:i:s', $totalDuration)]);
                                }

                            } else {
                                $first = \Carbon\Carbon::parse($shift->login_at);
                                $breaks = $first->diffInSeconds($last);
                                $brk += $breaks;
                                $break = gmdate('H:i:s', $breaks);
                                //array_push($shiftarray, ["break" => $breaks, "available" => $totalDuration]);
                                if ($breaks != 0) {
                                    array_push($shiftarray, ["label" => "Break", "symbol" => "br", "y" => $breaks, "color" => "red", "msg" => gmdate('H:i:s', $breaks)]);
                                }
                                if ($totalDuration != 0) {
                                    array_push($shiftarray, ["label" => "Avilable", "symbol" => "av", "y" => $totalDuration, "color" => "green", "msg" => gmdate('H:i:s', $totalDuration)]);
                                }

                            }
                            $last = $finishTime;
                        }


                        $shifts_out = ProviderShift::where('provider_id', $id)->where('login_at', '>=', $start)->where('login_at', 'LIKE', '%' . $date_detail . '%')->where('logout_at', '=', null)->get();
                        //dd($shifts_out);
                        if (count($shifts_out) == 1) {

                            foreach ($shifts_out as $index => $shift) {


                                $startTime = \Carbon\Carbon::parse($shift->login_at);
                                $nowsss = \Carbon\Carbon::now()->toDateString();
                                $today = explode(" ", $shift->login_at);
                                if ($nowsss == $today[0]) {
                                    $timefinish = Carbon::now()->toDateTimeString();
                                } else {
                                    $timefinish = $today[0] . " 23:59:59";
                                }

                                $finishTime = \Carbon\Carbon::parse($timefinish);


                                $totalDuration = $finishTime->diffInSeconds($startTime);
                                $time = gmdate('H:i:s', $totalDuration);
                                $shifts_out[$index]['time'] = $totalDuration;

                                $overall += $totalDuration;

                                if ($shift->login_at == $shift->logout_at) {

                                    $last_logout = $now;
                                } else {

                                    $last_logout = $now;
                                }

                                // if($index == 0) {
                                // array_push($shiftarray, ["break" => '0', "available" => $totalDuration]);
                                // } else {
                                //dd($last);
                                $first = \Carbon\Carbon::parse($shift->login_at);
                                $breaks = $first->diffInSeconds($last);
                                $brk += $breaks;
                                $break = gmdate('H:i:s', $breaks);
                                //array_push($shiftarray, ["break" => $breaks, "available" => $totalDuration]);
                                if ($breaks != 0) {
                                    array_push($shiftarray, ["label" => "Break", "symbol" => "br", "y" => $breaks, "color" => "red", "msg" => gmdate('H:i:s', $breaks)]);
                                }
                                if ($totalDuration != 0) {
                                    array_push($shiftarray, ["label" => "Avilable", "symbol" => "av", "y" => $totalDuration, "color" => "green", "msg" => gmdate('H:i:s', $totalDuration)]);
                                }
                                //}
                                $last = $finishTime;
                            }
                            $shifts = array_merge($shifts->toArray(), $shifts_out->toArray());


                        }

                    } else {
                        $providerss = [];
                    }
                    //dd($overall);
                    $first_logins = \Carbon\Carbon::parse($first_login);
                    $last_logouts = \Carbon\Carbon::parse($last_logout);
                    $diff = $last_logouts->diffInSeconds($first_logins);
                    $break = $overall - $diff;
                    //dd($diff);
                    $init = $overall;
                    $hours = floor($init / 3600);
                    $minutes = floor(($init / 60) % 60);
                    $seconds = $init % 60;
                    $strs = "";
                    $availability = "";
                    if ($hours != 0) {
                        $availability .= $hours . ":";
                        $strs = "Hours";
                    }

                    if ($minutes != 0) {
                        $availability .= $minutes;
                        if ($strs == "") {
                            $strs = "Minutes";
                        }
                    }

                    if ($seconds != 0) {
                        $availability .= ":" . $seconds;
                        if ($strs == "") {
                            $strs = "Seconds";
                        }
                    }

                    if ($hours == 0 && $minutes == 0 && $seconds == 0) {
                        $availability = "0:0 Seconds";
                    }
                    // if($hours != 0) {
                    //   $availability = $hours." Hours ".$minutes." Minutes ".$seconds." seconds.";   
                    //   } else {
                    //      $availability = $minutes." Minutes ".$seconds." seconds";
                    //   }

                    $init = $brk;
                    $hours = floor($init / 3600);
                    $minutes = floor(($init / 60) % 60);
                    $seconds = $init % 60;
                    $break = "";
                    $str = "";
                    if ($hours != 0) {
                        $break .= $hours . ":";
                        $str = "Hours";
                    }

                    if ($minutes != 0) {
                        $break .= $minutes;
                        if ($str == "") {
                            $str = "Minutes";
                        }

                    }

                    if ($seconds != 0) {
                        $break .= ":" . $seconds;
                        if ($str == "") {
                            $str = "Seconds";
                        }
                    }

                    if ($hours == 0 && $minutes == 0 && $seconds == 0) {
                        $break = "0:0 Seconds";
                    }

                    if ($first_login != "") {
                        $first = explode(" ", $first_login);
                        $first = $first[1];
                    }
                    if ($last_logout != "") {
                        $last = explode(" ", $last_logout);
                        $last = $last[1];

                    }

                    $times = 0;
                    $date = "";
                    $providerss = [];
                    $start = $date_detail . " 00:00:00";
                    $end = $date_detail . " 23:59:59";

                    $shifts = ProviderShift::where('provider_id', $id)->where('login_at', '>=', $start)
                        ->where('logout_at', '<=', $end)->get();

                    $shifts_null = ProviderShift::where('provider_id', $id)->where('login_at', '>=', $start)->where('login_at', 'LIKE', '%' . $date_detail . '%')->where('logout_at', '=', null)->get();
                    //dd($shifts);

                    $shifts = $shifts->merge($shifts_null);


                    $provider = Provider::where('id', $id)->first();

                    $provider_name = $provider->name . " " . $provider->last_name . "(" . $provider->mobile . ") - " . $date_detail;
                    $avail = $availability . " " . $strs;
                    $br = $break . " " . $str;
                    //dd($br);
                    array_push($dataPoints, ["label" => "Avilable", "symbol" => "av", "y" => $overall, "color" => "green", "msg" => gmdate('H:i:s', $overall)]);
                    array_push($dataPoints, ["label" => "Break", "symbol" => "br", "y" => $brk, "color" => "red", "msg" => gmdate('H:i:s', $brk)]);


                } catch (Exception $e) {
                    return back()->with('flash_error', $e->getMessage());
                }

            }
        }

    }

    public function shift_row(Request $request)
    {

        $columns = array(
            0 => 'id',
            1 => 'full_name',
            2 => 'email',
            3 => 'mobile',
            4 => 'total_requests',
            5 => 'accepted_requests',
            6 => 'cancelled_requests',
        );

        $AllProviders = Provider::with('service', 'totalrequest', 'accepted', 'cancelled');
        if (Auth::guard('admin')->user()->admin_type != 0) {
            $AllProviders = $AllProviders->where('admin_id', '=', Auth::guard('admin')->user()->id);
        }
        // if(request()->has('fleet')){
        //     $providerslist = $AllProviders->where('fleet',$request->fleet);
        // }else{
        $providerslist = $AllProviders;
        // }

        $totalData = $providerslist->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $providers = $providerslist->offset($start)
                ->limit($limit)
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $search = $request->input('search.value');

            $providers = $providerslist->where('name', 'LIKE', "%{$search}%")
                // ->orWhere('last_name', 'LIKE',"%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhere('mobile', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy('id', 'desc')
                ->get();

            $totalFiltered = $providerslist->where('name', 'LIKE', "%{$search}%")
                // ->orWhere('last_name', 'LIKE',"%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhere('mobile', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        if (!empty($providers)) {
            foreach ($providers as $index => $provider) {
                if ($provider->name != '') {
                    $name = '<a href="' . route('admin.shift', $provider->id) . '">' . $provider->name . '</a>';
                    // $name = $provider->name;
                } else {
                    $name = "";
                }
                if ($provider->last_name != '') {
                    $last_name = $provider->last_name;
                } else {
                    $last_name = "";
                }
                if ($provider->email != '') {
                    $email = $provider->email;
                } else {
                    $email = "";
                }
                if ($provider->mobile != '') {
                    $mobile = $provider->mobile;
                } else {
                    $mobile = "";
                }

                if ($provider->pending_documents() > 0 || $provider->service == null) {
                    $documents = '<a class="btn btn-danger btn-rounded btn-block label-right waves-effect waves-light" href="' . route('admin.provider.document.index', $provider->id) . '">' . trans("admin.member.attention") . '<span class="btn-label">' . $provider->pending_documents() . '</span></a>';
                } else {
                    $documents = '<a class="btn btn-success btn-rounded btn-block waves-effect waves-light" href="' . route('admin.provider.document.index', $provider->id) . '">' . trans("admin.member.all_set") . '</a>';
                }
                if ($provider->status == 'approved') {
                    $enable = '<a class="btn btn-danger btn-rounded btn-block waves-effect waves-light" href="' . route('admin.provider.disapprove', $provider->id) . '">' . trans("admin.member.disable") . '</a>';
                } else {
                    $enable = '<a class="btn btn-success btn-rounded btn-block waves-effect waves-light" href="' . route('admin.provider.approve', $provider->id) . '">' . trans("admin.member.enable") . '</a>';
                }
                $button = '<button type="button" 
                                    class="btn btn-info btn-rounded btn-block dropdown-toggle"
                                    data-toggle="dropdown">Action
                                    <span class="caret"></span>
                                </button>
                        <ul class="dropdown-menu">
                                    <li>
                                        <a href="' . route('admin.provider.request', $provider->id) . '" class="btn btn-default"><i class="fa fa-search"></i> ' . trans("admin.member.history") . '</a>
                                    </li>
                                    <li>
                                        <a href="' . route('admin.provider.statement', $provider->id) . '" class="btn btn-default"><i class="fa fa-account"></i> ' . trans("admin.member.statement") . '</a>
                                    </li>
                                    <li>
                                        <a href="' . route('admin.provider.edit', $provider->id) . '" class="btn btn-default"><i class="fa fa-pencil"></i> ' . trans("admin.member.edit") . '</a>
                                    </li>
                                    <li>
                                        <form action="' . route('admin.provider.logout', $provider->id) . '" method="POST">
                                            ' . csrf_field() . '
                                            <input type="hidden" name="_method" value="POST">
                                            <button class="btn btn-default look-a-log" onclick="return confirm(`Do you want to logout this provider?`)"><i class="fa fa-sign-out"></i> ' . trans("admin.member.logout") . '</button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="' . route('admin.provider.destroy', $provider->id) . '" method="POST">
                                            ' . csrf_field() . '
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button class="btn btn-default look-a-like" onclick="return confirm(`Are you sure?`)"><i class="fa fa-trash"></i> ' . trans("admin.member.delete") . '</button>
                                        </form>
                                    </li>
                                </ul>';
                $action = '<div class="input-group-btn">' . $enable . $button . '</div>';

                $nestedData['id'] = $start + 1;
                $nestedData['full_name'] = $name;
                $nestedData['email'] = $email;
                $nestedData['mobile'] = $mobile;
                $nestedData['total_requests'] = $provider->totalrequest->count();
                $nestedData['accepted_requests'] = $provider->accepted->count();
                $nestedData['cancelled_requests'] = $provider->cancelled->count();
                // $nestedData['documents'] = $documents;
                // $nestedData['action'] = $action;
                $data[] = $nestedData;
                $start++;
            }
        }
        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);

    }

    public function track_row(Request $request)
    {

        $columns = array(
            0 => 'id',
            1 => 'full_name',
            2 => 'mobile',
            3 => 'last_login',
            4 => 'last_logout',
            5 => 'login_status',
        );

        $AllProviders = Provider::with('service');

        if (request()->has('fleet')) {
            $providerslist = $AllProviders->where('fleet', $request->fleet);
        } else {
            $providerslist = $AllProviders;
        }

        $totalData = $providerslist->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $providers = $providerslist->offset($start)
                ->limit($limit)
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $search = $request->input('search.value');

            $providers = $providerslist->where('name', 'LIKE', "%{$search}%")
                // ->orWhere('last_name', 'LIKE',"%{$search}%")
                ->orWhere('mobile', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy('id', 'desc')
                ->get();

            $totalFiltered = $providerslist->where('name', 'LIKE', "%{$search}%")
                // ->orWhere('last_name', 'LIKE',"%{$search}%")
                ->orWhere('mobile', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        if (!empty($providers)) {
            foreach ($providers as $index => $provider) {
                if ($provider->name != '') {
                    $name = $provider->name;
                } else {
                    $name = "";
                }
                if ($provider->last_name != '') {
                    $last_name = Helper::hidechar($provider->last_name);
                } else {
                    $last_name = "";
                }

                if ($provider->mobile != '') {
                    $mobile = Helper::hidechar($provider->mobile);
                } else {
                    $mobile = "";
                }
                if ($provider->login_at != '') {
                    $login_at = $provider->login_at;
                } else {
                    $login_at = "";
                }
                if ($provider->logout_at != '') {
                    $logout_at = $provider->logout_at;
                } else {
                    $logout_at = "";
                }
                if ($provider->service) {
                    if ($provider->login_status == 1) {
                        $login_status = '<label class="btn btn-sm btn-rounded  btn-primary waves-effect waves-light">' . trans("admin.member.yes") . '</label>';
                    } else {
                        $login_status = '<label class="btn btn-rounded btn-sm btn-warning waves-effect waves-light">' . trans("admin.member.no") . '</label>';
                    }
                } else {
                    $login_status = '<label class="btn btn-rounded btn-sm btn-warning waves-effect waves-light">' . trans("admin.member.no") . '</label>';
                }

                $nestedData['id'] = $start + 1;
                $nestedData['full_name'] = $name;
                $nestedData['mobile'] = $mobile;
                $nestedData['last_login'] = $login_at;
                $nestedData['last_logout'] = $logout_at;
                $nestedData['login_status'] = $login_status;
                $data[] = $nestedData;
                $start++;
            }
        }
        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }

    public function logout($id)
    {
        try {
            ProviderDevice::where('provider_id', $id)->orderBy('id', 'DESC')->update(['udid' => '', 'token' => '']);
            Provider::where('id', $id)->update(['status' => 'offline']);
            return back()->with('flash_success', 'Provider Logged out successfully');
        } catch (Exception $e) {
            return back()->with('flash_error', 'Something Went Wrong!');
        }
    }
}
