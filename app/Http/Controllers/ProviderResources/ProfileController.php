<?php

namespace App\Http\Controllers\ProviderResources;

use App\Helpers\Helper;
use App\Models\ProviderBankDetail;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use DateTimeZone;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use Setting;
use Storage;
use File;
use Exception;
use Carbon\Carbon;
use App\Models\UserRequest;
use App\Models\Admin;
use App\Models\Provider;
use App\Models\Vehicle;
use App\Models\ServiceType;
use App\Models\ProviderDocument;
use App\Models\DriverDocList;
use App\Models\ProviderWallet;
use App\Models\DriverLogin;

class ProfileController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Helper::getProviderProfileData(Auth::user());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'mobile' => 'required',
            'avatar' => 'mimes:jpeg,bmp,png',
            'language' => 'max:255',
            'address' => 'max:255',
            'address_secondary' => 'max:255',
            'city' => 'max:255',
            'country' => 'max:255',
            'postal_code' => 'max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'success' => 0], 200);
        }

        try {
            if ($request->hasFile('avatar')) {
                //Storage::delete($Provider->avatar);
                //$Provider->avatar = $request->avatar->store('public/provider/profile');
                //$Provider->avatar = $request->avatar->store('provider/profile');
                $picture = $request->avatar;
                $file_name = time();
                $file_name .= rand();
                $file_name = sha1($file_name);
                if ($picture) {
                    $ext = $picture->getClientOriginalExtension();
                    $picture->move(public_path() . "/uploads/provider/profile/", $file_name . "." . $ext);
                    $local_url = $file_name . "." . $ext;
                    $Provider->avatar = $local_url;
                }
            }

            $Provider = Auth::user();
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Provider Not Found!'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            // 'avatar' => 'required',
            'dob' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => "0", "message" => $validator->errors()->first()], 422);
            // return response()->json(['message' => $validator->errors(), 'success' => 0], 422);
        }

        $Provider = Auth::user();

        $mail = Provider::where('email', '=', $request->email)->where('id', '!=', $Provider->id)->first();
        if ($mail) {
            return response()->json(['success' => "0", "message" => "The email has already been taken"], 422);
        }

        try {

            if ($request->has('name'))
                $Provider->name = $request->name;

            if ($request->has('email'))
                $Provider->email = $request->email;

            if ($request->has('dob'))
                $Provider->dob = $request->dob;

            if ($request->has('avatar'))
                $Provider->avatar = $request->avatar;

            $Provider->save();

            return Helper::getProviderProfileData($Provider);


        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Provider Not Found!'], 404);
        }
    }

    /**
     * Toggle service availability of the provider.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function available(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_status' => 'required|in:active,offline',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'success' => 0], 200);
        }

        $Provider = Auth::user();
        if (Auth::user()->admin_id != null) {
            $admin = Admin::where('id', '=', Auth::user()->admin_id)->first();
            if ($admin->admin_type != 0 && $admin->time_zone != null) {
                date_default_timezone_set($admin->time_zone);
            }
        }
        if ($Provider->status == 'riding') {
            if ($Provider->account_status == 'approved') {
                $Provider->update(['status' => 'riding']);
            } else {
                return response()->json(['success' => "0", "message" => "Your account has not been approved for driving."], 422);
            }
        } else {
            if ($Provider->account_status == 'approved') {
                $Provider->update(['status' => $request->service_status, 'active_from' => Carbon::now()]);
            } else {
                return response()->json(['success' => "0", "message" => "Your account has not been approved for driving."], 422);
            }
        }

        return Helper::getProviderProfileData($Provider);
    }

    /**
     * Update password of the provider.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function password(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed',
            'password_old' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'success' => 0], 200);
        }

        $Provider = Auth::user();

        if (password_verify($request->password_old, $Provider->password)) {
            $Provider->password = bcrypt($request->password);
            $Provider->save();

            return response()->json(['message' => 'Password changed successfully!', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Please enter correct password', 'success' => 0], 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function upload_document(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'document_id' => 'required',
            'image' => 'required|mimes:jpg,jpeg,png,pdf',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'success' => 0], 200);
        }

        try {

            if ($request->hasFile('image')) {
                $Image = ProviderDocument::where('provider_id', Auth::user()->id)
                    ->where('document_id', $request->document_id)
                    ->first();
                $file_name = time();
                $file_name .= rand();
                $file_name = sha1($file_name);
                if ($Image != null) {
                    //Storage::delete($Image->url);                   
                    $image1 = $request->image;
                    if ($image1) {
                        $ext = $image1->getClientOriginalExtension();
                        $image1->move(public_path() . "/uploads/provider/documents/", $file_name . "." . $ext);
                        $local_url = $file_name . "." . $ext;
                        $s3_url = '/uploads/provider/documents/' . $local_url;


                        $Image->update([
                            'url' => url('/') . $s3_url,
                            'status' => 'ASSESSING',
                        ]);
                    }
                } else {
                    $image1 = $request->image;
                    $ext = $image1->getClientOriginalExtension();
                    $image1->move(public_path() . "/uploads/provider/documents/", $file_name . "." . $ext);
                    $local_url = $file_name . "." . $ext;
                    $s3_url = '/uploads/provider/documents/' . $local_url;

                    ProviderDocument::create([
                        'url' => url('/') . $s3_url,
                        'provider_id' => Auth::user()->id,
                        'document_id' => $request->document_id,
                        'status' => 'ASSESSING',
                    ]);
                }
                return response()->json(['message' => 'Documents have been uploaded!']);
            }

        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    public function saveBankDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_holder_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'branch_name' => 'nullable|string|max:255',
            'bank_address' => 'nullable|string|max:500',
            'account_number' => 'required|string|max:50',
            'iban' => 'nullable|string|max:34',
            'swift_bic' => 'nullable|string|max:11',
            'routing_number' => 'nullable|string|max:20',
            'account_type' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => "0", "message" => $validator->errors()->first()], 422);
            // return response()->json(['message' => $validator->errors(), 'success' => 0], 422);
        }

        try {
            $providerId = Auth::user()->id;

            $bankDetail = ProviderBankDetail::updateOrCreate(
                ['provider_id' => $providerId],
                [
                    'account_holder_name' => $request->account_holder_name,
                    'bank_name' => $request->bank_name,
                    'branch_name' => $request->branch_name,
                    'bank_address' => $request->bank_address,
                    'account_number' => $request->account_number,
                    'iban' => $request->iban,
                    'swift_bic' => $request->swift_bic,
                    'routing_number' => $request->routing_number,
                    'account_type' => $request->account_type,
                ]
            );

            return response()->json([
                'message' => 'Bank details saved successfully!',
                'bankDetail' => $bankDetail,
                'success' => 1
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error saving bank details!',
                'details' => $e->getMessage(),
                'success' => 0
            ], 500);
        }
    }

    public function uploadDocument(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'document_type' => 'required',
            'expires_at' => 'required|date|after:today',
            'file' => 'required|mimes:jpg,jpeg,png,pdf',
            'back_file' => 'nullable|mimes:jpg,jpeg,png,pdf'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => "0", "message" => $validator->errors()->first()], 422);
            // return response()->json(['message' => $validator->errors(), 'success' => 0], 422);
        }

        try {

            $providerId = Auth::user()->id;

            if ($request->hasFile('file')) {

                $documentModel = ProviderDocument::where('provider_id', $providerId)
                    ->where('document_type', $request->document_type)
                    ->first();

                if ($documentModel == null) {
                    $documentModel = new ProviderDocument();
                    $documentModel->provider_id = $providerId;
                    $documentModel->document_type = $request->document_type;
                    $documentModel->document_id = 0;
                }

                $frontUrl = Helper::uploadFile($request->file);
                $documentModel->url = $frontUrl;

                if ($request->hasFile('back_file')) {
                    $backUrl = Helper::uploadFile($request->back_file);
                    $documentModel->back_url = $backUrl;
                } else {
                    $documentModel->back_url = null;
                }

                $documentModel->expires_at = $request->expires_at;
                $documentModel->status = 'ACTIVE';
                $documentModel->save();

                return response()->json([
                    'message' => 'Documents have been uploaded!',
                    'document' => $documentModel
                ]);
            }

        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy_document(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'document_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'success' => 0], 200);
        }

        try {

            $Document = ProviderDocument::where('provider_id', Auth::user()->id)
                ->where('document_id', $request->document_id)
                ->first();
            if ($Document != null) {
                Storage::delete($Document->url);
                $Document->delete();
                return response()->json(['message' => 'Documents successfully deleted!']);
            } else {
                return response()->json(['message' => 'Documents not found']);
            }


        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function get_documents(Request $request)
    {

        try {
            $documents = DriverDocList::get();

            foreach ($documents as $key => $document) {
                $doc = ProviderDocument::where('provider_id', Auth::user()->id)
                    ->where('document_id', $document->id)->first();
                if ($doc != null) {
                    $documents[$key]->url = $doc->url;
                    $documents[$key]->status = $doc->status;
                    $documents[$key]->created_at = $doc->created_at;
                }
            }

            return $documents;

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Please Upload Document!'], 500);
        }
    }

    public function provider_wallet_history()
    {
        $data = ProviderWallet::where('provider_id', Auth::user()->id)->select('amount', 'mode', 'created_at as created')->orderBy('created_at', 'DESC')->get()->toArray();
        return response()->json(['data' => $data, 'currency' => Setting::get('currency'), 'wallet_balance' => Auth::user()->wallet_balance], 200);
    }


    public function updateDrivingLicense(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'expiredAt' => 'required|date|after:today',
            'licenseNumber' => 'required|string',
            'front' => 'required|mimes:jpg,jpeg,png,webp,pdf',
            'back' => 'nullable|mimes:jpg,jpeg,png,webp,pdf'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => "0", "message" => $validator->errors()->first()], 422);
            // return response()->json(['message' => $validator->errors(), 'success' => 0], 422);
        }

        try {

            $documentType = Helper::PROVIDER_DOCUMENT_TYPES["DRIVING_LICENSE"];

            $provider = Auth::user();

            if ($request->hasFile('front')) {

                $documentModel = ProviderDocument::where('provider_id', $provider->id)
                    ->where('document_type', $documentType)
                    ->first();

                if ($documentModel == null) {
                    $documentModel = new ProviderDocument();
                    $documentModel->provider_id = $provider->id;
                    $documentModel->document_type = $documentType;
                    $documentModel->document_id = 0;
                }

                $frontUrl = Helper::uploadFile($request->front);
                $documentModel->url = $frontUrl;

                if ($request->hasFile('back')) {
                    $backUrl = Helper::uploadFile($request->back);
                    $documentModel->back_url = $backUrl;
                } else {
                    $documentModel->back_url = null;
                }

                $documentModel->expires_at = $request->expiredAt;
                $documentModel->status = 'ACTIVE';
                $documentModel->save();

                $provider->license_no = $request->licenseNumber;
                $provider->license_expire = $request->expiredAt;

                $provider->save();

                $profileData = Helper::getProviderProfileData($provider);

                return response()->json([
                    'message' => 'License details have been updated!',
                    'document' => $documentModel,
                    'profile' => $profileData
                ]);

            } else {
                return response()->json(['message' => "Documents not found", 'success' => 0], 422);
            }

        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    public function updateRego(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'expiredAt' => 'required|date|after:today',
            'vehicle' => 'required|string',
            'vehicleType' => 'required|string',
            'licensePlateNumber' => 'required|string',

            'file' => 'required|mimes:jpg,jpeg,png,webp,pdf',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => "0", "message" => $validator->errors()->first()], 422);
            // return response()->json(['message' => $validator->errors(), 'success' => 0], 422);
        }

        try {

            $documentType = Helper::PROVIDER_DOCUMENT_TYPES["REGISTRATION"];

            $provider = Auth::user();

            if ($request->hasFile('file')) {

                $documentModel = ProviderDocument::where('provider_id', $provider->id)
                    ->where('document_type', $documentType)
                    ->first();

                if ($documentModel == null) {
                    $documentModel = new ProviderDocument();
                    $documentModel->provider_id = $provider->id;
                    $documentModel->document_type = $documentType;
                    $documentModel->document_id = 0;
                }

                $fileUrl = Helper::uploadFile($request->file);
                $documentModel->url = $fileUrl;
                $documentModel->back_url = null;

                $documentModel->expires_at = $request->expiredAt;
                $documentModel->status = 'ACTIVE';
                $documentModel->save();

                $data = Helper::getOrCreateVehicle($provider);
                $provider = $data["provider"];
                $providerVehicleModel = $data["providerVehicleModel"];

                $providerVehicleModel->vehicle_name = $request->vehicle;
                $providerVehicleModel->vehicle_no = $request->licensePlateNumber;
                $providerVehicleModel->service_type_id = $request->vehicleType;

                $providerVehicleModel->save();

                $profileData = Helper::getProviderProfileData($provider);

                return response()->json([
                    'message' => 'Rego details have been updated!',
                    'document' => $documentModel,
                    'vehicle' => $providerVehicleModel,
                    'profile' => $profileData
                ]);

            } else {
                return response()->json(['message' => "Documents not found", 'success' => 0], 422);
            }

        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    public function updateTaxiInsurance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'expiredAt' => 'required|date|after:today',

            'file' => 'required|mimes:jpg,jpeg,png,webp,pdf',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => "0", "message" => $validator->errors()->first()], 422);
            // return response()->json(['message' => $validator->errors(), 'success' => 0], 422);
        }

        try {

            $documentType = Helper::PROVIDER_DOCUMENT_TYPES["TAXI_INSURANCE"];

            $provider = Auth::user();

            if ($request->hasFile('file')) {

                $documentModel = ProviderDocument::where('provider_id', $provider->id)
                    ->where('document_type', $documentType)
                    ->first();

                if ($documentModel == null) {
                    $documentModel = new ProviderDocument();
                    $documentModel->provider_id = $provider->id;
                    $documentModel->document_type = $documentType;
                    $documentModel->document_id = 0;
                }

                $fileUrl = Helper::uploadFile($request->file);
                $documentModel->url = $fileUrl;
                $documentModel->back_url = null;

                $documentModel->expires_at = $request->expiredAt;
                $documentModel->status = 'ACTIVE';
                $documentModel->save();

                $data = Helper::getOrCreateVehicle($provider);
                $provider = $data["provider"];
                $providerVehicleModel = $data["providerVehicleModel"];

                $providerVehicleModel->insurance_exp = $request->expiredAt;
                $providerVehicleModel->save();

                $profileData = Helper::getProviderProfileData($provider);

                return response()->json([
                    'message' => 'Taxi Insurance have been updated!',
                    'document' => $documentModel,
                    'vehicle' => $providerVehicleModel,
                    'profile' => $profileData
                ]);

            } else {
                return response()->json(['message' => "Documents not found", 'success' => 0], 422);
            }

        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    public function updateTransportVehicleCertificate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'expiredAt' => 'required|date|after:today',
            'ptvNumber' => 'required|string',

            'file' => 'required|mimes:jpg,jpeg,png,webp,pdf',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => "0", "message" => $validator->errors()->first()], 422);
            // return response()->json(['message' => $validator->errors(), 'success' => 0], 422);
        }

        try {

            $documentType = Helper::PROVIDER_DOCUMENT_TYPES["PTV_CERTIFICATE"];

            $provider = Auth::user();

            if ($request->hasFile('file')) {

                $documentModel = ProviderDocument::where('provider_id', $provider->id)
                    ->where('document_type', $documentType)
                    ->first();

                if ($documentModel == null) {
                    $documentModel = new ProviderDocument();
                    $documentModel->provider_id = $provider->id;
                    $documentModel->document_type = $documentType;
                    $documentModel->document_id = 0;
                }

                $fileUrl = Helper::uploadFile($request->file);
                $documentModel->url = $fileUrl;
                $documentModel->back_url = null;

                $documentModel->expires_at = $request->expiredAt;
                $documentModel->status = 'ACTIVE';
                $documentModel->save();

                $data = Helper::getOrCreateVehicle($provider);
                $provider = $data["provider"];
                $providerVehicleModel = $data["providerVehicleModel"];

                $providerVehicleModel->ptv_number = $request->ptvNumber;
                $providerVehicleModel->save();

                $profileData = Helper::getProviderProfileData($provider);

                return response()->json([
                    'message' => 'Transport Vehicle Certificate have been updated!',
                    'document' => $documentModel,
                    'vehicle' => $providerVehicleModel,
                    'profile' => $profileData
                ]);

            } else {
                return response()->json(['message' => "Documents not found", 'success' => 0], 422);
            }

        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    public function updateTransportDriverCertificate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'expiredAt' => 'required|date|after:today',
            'ptdNumber' => 'required|string',

            'file' => 'required|mimes:jpg,jpeg,png,webp,pdf',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => "0", "message" => $validator->errors()->first()], 422);
            // return response()->json(['message' => $validator->errors(), 'success' => 0], 422);
        }

        try {

            $documentType = Helper::PROVIDER_DOCUMENT_TYPES["PTD_CERTIFICATE"];

            $provider = Auth::user();

            if ($request->hasFile('file')) {

                $documentModel = ProviderDocument::where('provider_id', $provider->id)
                    ->where('document_type', $documentType)
                    ->first();

                if ($documentModel == null) {
                    $documentModel = new ProviderDocument();
                    $documentModel->provider_id = $provider->id;
                    $documentModel->document_type = $documentType;
                    $documentModel->document_id = 0;
                }

                $fileUrl = Helper::uploadFile($request->file);
                $documentModel->url = $fileUrl;
                $documentModel->back_url = null;

                $documentModel->expires_at = $request->expiredAt;
                $documentModel->status = 'ACTIVE';
                $documentModel->save();

                $provider->ptd_number = $request->ptdNumber;
                $provider->save();

                $profileData = Helper::getProviderProfileData($provider);

                return response()->json([
                    'message' => 'Transport Driver Certificate have been updated!',
                    'document' => $documentModel,
                    'profile' => $profileData
                ]);

            } else {
                return response()->json(['message' => "Documents not found", 'success' => 0], 422);
            }

        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }


}
