<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\UserRequest;
use App\Models\Provider;
use Carbon\Carbon;
use Setting;
use Exception;
use App\Models\UserRequestPayment;
use App\Models\DriverDocList;
use App\Models\ProviderDocument;
use App\Models\Country;
use App\Http\Controllers\ProviderResources\TripController;
use Storage;
use Auth;

class ProviderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('provider');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $provider = Provider::where('id',\Auth::guard('provider')->user()->id)
                    ->with('accepted','cancelled')
                    ->first();

        $today = UserRequest::where('provider_id',\Auth::guard('provider')->user()->id)
                    ->where('created_at', '>=', Carbon::today())
                    ->count();

        $fully = UserRequest::where('provider_id',\Auth::guard('provider')->user()->id)
                    ->count();

        return view('provider.index', compact('provider','fully','today'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function earnings()
    {
        $fully = UserRequest::where('provider_id', '=', \Auth::guard('provider')->user()->id)
                    ->where('user_requests.status','!=','CANCELLED')
                    ->join('service_types', 'user_requests.service_type_id', '=', 'service_types.id')
                    ->select('user_requests.id','user_requests.booking_id','service_types.name as service_name','user_requests.status','user_requests.distance','user_requests.minutes','user_requests.started_at','user_requests.finished_at','user_requests.created_at')
                    ->orderBy('user_requests.created_at','desc')
                    ->get();
        $total_sum =0;            
        if(!empty($fully)){
            foreach ($fully as $key => $value) {
                if($value->status =='COMPLETED'){
                    $payment = UserRequestPayment::where('request_id',$value->id)->select('currency','total')->first();
                    $fully[$key]->total = $payment->currency.' '.$payment->total;
                    $total_sum +=$payment->total;
                }    
                $fully[$key]->distance = $value->distance.Setting::get('distance_unit');       
            }
        }

        return view('provider.application.earnings',compact('fully','total_sum'));
    }

    /**
     * Show the planned trips.
     *
     * @return \Illuminate\Http\Response
     */

    public function upcoming_trips() {
    
        try{
            $fully = UserRequest::where('status', '=', 'ACCEPTED')
                    ->where('provider_id', '=', \Auth::guard('provider')->user()->id)
                    ->select('id','created_at','booking_id','service_type_id','schedule_at','s_address','d_address','distance','status','assigned_at')
                    ->orderBy('created_at','desc')
                    ->get();
            return view('provider.application.upcoming',compact('fully'));        
        }
        catch (Exception $e){
            return redirect()->back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }
    /**
     * available.
     *
     * @return \Illuminate\Http\Response
     */
    public function available(Request $request)
    {
        (new ProviderResources\ProfileController)->available($request);
        return back();
    }

    /**
     * Show the application change password.
     *
     * @return \Illuminate\Http\Response
     */
    public function change_password()
    {
        return view('provider.application.change_password');
    }

    /**
     * Change Password.
     *
     * @return \Illuminate\Http\Response
     */
    public function update_password(Request $request)
    {
        $this->validate($request, [
                'password' => 'required|confirmed',
                'old_password' => 'required',
            ]);

        $Provider = \Auth::user();

        if(password_verify($request->old_password, $Provider->password))
        {
            $Provider->password = bcrypt($request->password);
            $Provider->save();

            return back()->with('flash_success','Password changed successfully!');
        } else {
            return back()->with('flash_error','Please enter correct password');
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function location_edit()
    {
        return view('provider.application.location');
    }

    /**
     * Update latitude and longitude of the user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function location_update(Request $request)
    {
        $this->validate($request, [
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);

        if($Provider = \Auth::user()){

            $Provider->latitude = $request->latitude;
            $Provider->longitude = $request->longitude;
            $Provider->save();

            return back()->with(['flash_success' => 'Location Updated successfully!']);

        } else {
            return back()->with(['flash_error' => 'Provider Not Found!']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function document_index()
    {
        $provider = \Auth::user();
        $documents = DriverDocList::get();
        $providerdocuments = ProviderDocument::where('provider_id','=',\Auth::guard('provider')->user()->id)->get();
        return view('provider.application.document', compact('documents','providerdocuments','provider'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function document_upload(Request $request, $id)
    {
        $this->validate($request, [
                'document' => 'required|mimes:jpg,jpeg,png,pdf',
            ]);

            //dd($request->all());

        try {

            $Document = ProviderDocument::where('provider_id', \Auth::user()->id)
                ->where('document_id', $id)
                ->firstOrFail();
                
            //Storage::delete($Document->url);

                $image1=$request->document;
                $file_name = time();
                $file_name .= rand();
                $file_name = sha1($file_name);
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
            
            // $Document->update([
            //         'url' => $request->document->store('provider/documents'),
            //         'status' => 'ASSESSING',
            //     ]);

            return back()->with('flash_success', 'Document has been uploaded.');
                
        } catch (ModelNotFoundException $e) {

            $image1=$request->document;    
            $file_name = time();
            $file_name .= rand();
            $file_name = sha1($file_name);               
                    $ext = $image1->getClientOriginalExtension();
                    $image1->move(public_path() . "/uploads/provider/documents/", $file_name . "." . $ext);
                    $local_url = $file_name . "." . $ext;
                    $s3_url = '/uploads/provider/documents/'.$local_url;                    

                    ProviderDocument::create([
                        'url' => $s3_url,
                        'provider_id' => Auth::user()->id,
                        'document_id' => $id,
                        'status' => 'ASSESSING',
                    ]);

            // ProviderDocument::create([
            //         'url' => $request->document->store('provider/documents'),
            //         'provider_id' => \Auth::user()->id,
            //         'document_id' => $request->document_id,
            //         'status' => 'ASSESSING',
            //     ]);
            return back()->with('flash_success', 'Document has been uploaded.');
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function document_destroy($id)
    {
        try {

            $Document = ProviderDocument::where('provider_id', \Auth::user()->id)
                ->where('document_id', $id)
                ->firstOrFail();
            Storage::delete($Document->url);
            $Document->delete();

            return back()->with('flash_success', 'Document has been uploaded.');

        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Document Not Found.');
        }
    }
     /**
     * Show the application profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        return view('provider.application.profile');
    }

    /**
     * Show the application profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit_profile()
    {
        $countries = Country::all();
        return view('provider.application.edit_profile', compact('countries'));
    }

    /**
     * Update profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function update_profile(Request $request)
    {
        $this->validate($request, [
                'name' => 'required|max:255',
                'email' => 'email|unique:providers,email,'.\Auth::user()->id,
                'country_id' => 'required',
                'mobile' => 'required',
                'picture' => 'mimes:jpeg,bmp,png',
            ]);

         try {

            $provider = Provider::findOrFail(\Auth::user()->id);
            
            if($request->has('name')){ 
                $provider->name = $request->name;
            }
    
            if($request->has('email')){
                $provider->email = $request->email;
            }
        
            if($request->has('mobile')){
                $provider->mobile = $request->mobile;
            }

            if($request->has('gender')){
                $provider->gender = $request->gender;
            }

            if($request->has('language')){
                $provider->language = implode(',',$request->language);
            }

            if($request->has('acc_no')){
                $provider->acc_no = $request->acc_no;
            }

            if($request->has('license_no')){
                $provider->license_no = $request->license_no;
            }

            if($request->has('license_expire')){
                $provider->license_expire = $request->license_expire;
            }

            if($request->has('country_id')){
                $country = Country::where('countryid','=',$request->country_id)->first();
                $provider->dial_code = $country->dial_code;
            }

            if ($request->avatar != ""){
                Storage::delete($provider->avatar);
                $provider->avatar = $request->avatar->store('public/provider/profile');
                $provider->avatar = $request->avatar->store('provider/profile');
            }

            $provider->save();

            return redirect()->back()->with('flash_success', 'Information successfully updated');
        }
        catch (ModelNotFoundException $e) {
             return redirect()->back()->with('flash_error', 'Something went wrong.');
        }
    }
}