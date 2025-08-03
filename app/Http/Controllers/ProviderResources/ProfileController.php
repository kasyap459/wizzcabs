<?php

namespace App\Http\Controllers\ProviderResources;

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
        //try {
         
            $provider = Provider::where('id',Auth::user()->id)->select('id','name','email','mobile','avatar','account_status')->first();
            $vehicle = Vehicle::where('id','=',Auth::user()->mapping_id)->select('vehicle_no','service_type_id')->first();

            if($vehicle != null){
                $provider['service_type'] = ServiceType::where('id',$vehicle->service_type_id)->pluck('name')->first();
                $provider['vehicle'] = $vehicle->vehicle_no;
            }
            $provider->avatar = $provider->avatar;
            $provider->currency = Setting::get('currency');
            $provider->contact_number = Setting::get('contact_number');
            $provider->sos_number = Setting::get('sos_number');

            return $provider;

        // } catch (Exception $e) {
        //     return $e->getMessage();
        // }
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
        if($validator->fails()) { 
            return response()->json(['message'=>$validator->errors(), 'success'=>0], 200);
        }

        try {
            if ($request->hasFile('avatar')) {
                //Storage::delete($Provider->avatar);
                //$Provider->avatar = $request->avatar->store('public/provider/profile');
                //$Provider->avatar = $request->avatar->store('provider/profile');
                $picture=$request->avatar;
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
        }

        catch (ModelNotFoundException $e) {
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
        $this->validate($request, [
                // 'name' => 'required|max:255',
                // 'mobile' => 'required',
                // 'dial_code' => 'required',
                // 'avatar' => 'mimes:jpeg,bmp,png',
            ]);

        try {

            $Provider = Auth::user();

            if($request->has('name')) 
                $Provider->name = $request->name;

            if ($request->has('mobile'))
                $Provider->mobile = $request->mobile;

            if ($request->has('dial_code'))
                $Provider->dial_code = $request->dial_code;

            if ($request->hasFile('avatar')) {

                  File::delete(public_path('uploads/provider/profile/'.$Provider->avatar));
                // Storage::delete($Provider->avatar);
                // $Provider->avatar = $request->avatar->store('public/provider/profile');
                // $Provider->avatar = $request->avatar->store('provider/profile');
                $picture=$request->avatar;
                $file_name = time();
                $file_name = rand();
                $file_name = sha1($file_name);
                if ($picture) {
                    $ext = $picture->getClientOriginalExtension();
                    $picture->move(public_path() . "/uploads/provider/profile/", $file_name . "." . $ext);
                    $local_url = $file_name . "." . $ext;                    
                    $Provider->avatar = url('/')."/uploads/provider/profile/".$local_url;
                }
            }

            $Provider->save();

            return $Provider;
        }

        catch (ModelNotFoundException $e) {
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
            if($validator->fails()) { 
                return response()->json(['message'=>$validator->errors(), 'success'=>0], 200);
            }

        $Provider = Auth::user();
        if(Auth::user()->admin_id !=  null){
            $admin = Admin::where('id','=',Auth::user()->admin_id)->first();
            if($admin->admin_type != 0 && $admin->time_zone != null){
                 date_default_timezone_set($admin->time_zone);
             }
         }
         if($Provider->status =='riding'){
            if($Provider->account_status == 'approved') {
                $Provider->update(['status' => 'riding']);
            } else {
                return response()->json(['error' => 'Su cuenta no ha sido aprobada para conducir.']);
            }
         }
         else{
            if($Provider->account_status == 'approved') {
                $Provider->update(['status' => $request->service_status, 'active_from' =>Carbon::now()]);
            } else {
                return response()->json(['error' => 'Su cuenta no ha sido aprobada para conducir.']);
            }
         }

        return $Provider;
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

        if($validator->fails()) {
            return response()->json(['message'=>$validator->errors()->first(), 'success'=>0], 200);
        }

        $Provider = Auth::user();

        if(password_verify($request->password_old, $Provider->password))
        {
            $Provider->password = bcrypt($request->password);
            $Provider->save();

            return response()->json(['message' => 'Password changed successfully!', 'success'=>1]);
        } else {
            return response()->json(['message' => 'Please enter correct password', 'success'=>0], 200);
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
        if($validator->fails()) { 
            return response()->json(['message'=>$validator->errors(), 'success'=>0], 200);
        }

        try {
            
            if($request->hasFile('image')) { 
                $Image = ProviderDocument::where('provider_id', Auth::user()->id)
                    ->where('document_id', $request->document_id)
                    ->first();
                $file_name = time();
                $file_name .= rand();
                $file_name = sha1($file_name);
                if($Image !=null){
                    //Storage::delete($Image->url);                   
                    $image1=$request->image;
                    if ($image1) {
                        $ext = $image1->getClientOriginalExtension();
                        $image1->move(public_path() . "/uploads/provider/documents/", $file_name . "." . $ext);
                        $local_url = $file_name . "." . $ext;
                        $s3_url = '/uploads/provider/documents/'.$local_url;


                        $Image->update([
                            'url' => url('/').$s3_url,
                            'status' => 'ASSESSING',
                        ]);
                    }
                }else{
                    $image1=$request->image;                   
                    $ext = $image1->getClientOriginalExtension();
                    $image1->move(public_path() . "/uploads/provider/documents/", $file_name . "." . $ext);
                    $local_url = $file_name . "." . $ext;
                    $s3_url = '/uploads/provider/documents/'.$local_url;                    

                    ProviderDocument::create([
                        'url' => url('/').$s3_url,
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
        if($validator->fails()) { 
            return response()->json(['message'=>$validator->errors(), 'success'=>0], 200);
        }

        try {
            
            $Document = ProviderDocument::where('provider_id', Auth::user()->id)
            ->where('document_id', $request->document_id)
            ->first();
            if($Document !=null){
                Storage::delete($Document->url);
                $Document->delete();
                return response()->json(['message' => 'Documents successfully deleted!']);
            }else{
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

            foreach($documents as $key => $document){
                $doc =  ProviderDocument::where('provider_id', Auth::user()->id)
                        ->where('document_id', $document->id)->first();
                if($doc !=null){
                    $documents[$key]->url = $doc->url;
                    $documents[$key]->status = $doc->status;
                    $documents[$key]->created_at = $doc->created_at;
                }
            }

            return $documents;

        }catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Please Upload Document!'], 500);
        }
    }

    public function provider_wallet_history()
    {
        $data = ProviderWallet::where('provider_id',Auth::user()->id)->select('amount','mode','created_at as created')->orderBy('created_at', 'DESC')->get()->toArray();
        return response()->json(['data' => $data , 'currency' => Setting::get('currency') ,'wallet_balance' => Auth::user()->wallet_balance ], 200);
    }
    
}
