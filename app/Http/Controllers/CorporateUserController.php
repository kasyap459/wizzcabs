<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\URL;
use Mail;
use Setting;
use App\Models\CorporateUser;
use App\Models\CorporateGroup;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\VerifyUser;
use App\Models\Country;

class CorporateUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Users = CorporateUser::with('corporate_group')->where('corporate_id','=',Auth::user()->id)->get();
        return view('corporate.users.index', compact('Users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       $Groups = CorporateGroup::where('corporate_id','=',Auth::user()->id)->get();
       return view('corporate.users.create', compact('Groups'));
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
            'emp_name' => 'required|max:255',
            'emp_email' => 'required',
            'corporate_group_id' => 'required',
        ]);
            
        try{

            $checker = CorporateUser::where('emp_email','=',$request->emp_email)->first();
            if($checker ==null){
                $user = $request->all();
                $user['corporate_id'] = Auth::user()->id;
                $user = CorporateUser::create($user);
                
                $if_user=User::where('mobile',$user->emp_phone)->where('status',1)->first();
                if($if_user){
                    $if_user->corporate_user_id = $user->corporate_id;
                    $if_user->corporate_status = 1;
                    $if_user->save();
                }

                // $user->url = URL::signedRoute('guest.verify', [$user->id]);
                if(Setting::get('mail_enable', 0) == 1) {
                    Mail::send('emails.corporate-link', ['user' => $user], function ($message) use ($user){
                        $message->to($user->emp_email, $user->emp_name)->subject(config('app.name').' Corporate Account Activation');
                    });
                }
                return back()->with('flash_success','User created Successfully');
            }else{
                return back()->with('flash_error','Email id already exists');
            }
        } 
        catch(Exception $e) {
            return back()->$e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CorporateUser  $corporateUser
     * @return \Illuminate\Http\Response
     */
    public function show(CorporateUser $corporateUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CorporateUser  $corporateUser
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $User = CorporateUser::findOrFail($id);
            $Groups = CorporateGroup::where('corporate_id','=',Auth::user()->id)->get();
            return view('corporate.users.edit',compact('User','Groups'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CorporateUser  $corporateUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'emp_name' => 'required|max:255',
            'emp_email' => 'required',
            'corporate_group_id' => 'required',
        ]);

        try {

            $User = CorporateUser::findOrFail($id);
            $User->emp_name = $request->emp_name;
            $User->emp_email = $request->emp_email;
            $User->corporate_group_id = $request->corporate_group_id;
            $User->emp_gender = $request->emp_gender ? : '';
            $User->emp_code = $request->emp_code ? : '';
            $User->emp_phone = $request->emp_phone ? : '';
            $User->manager_email = $request->manager_email ? : '';
            $User->manager_name = $request->manager_name ? : '';
            $User->emp_brand = $request->emp_brand ? : '';
            $User->emp_costcenter = $request->emp_costcenter ? : '';
            $User->emp_desig = $request->emp_desig ? : '';
            $User->emp_baseloc = $request->emp_baseloc ? : '';
            $User->custom_field1 = $request->custom_field1 ? : '';
            $User->custom_field2 = $request->custom_field2 ? : '';
            $User->save();

            return redirect()->route('corporate.user.index')->with('flash_success', 'User Updated Successfully');    
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'User Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CorporateUser  $corporateUser
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            CorporateUser::find($id)->delete();
            return back()->with('message', 'Group deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'Group Not Found');
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\CorporateUser  $corporateUser
     * @return \Illuminate\Http\Response
     */
    public function guest_verify($id)
    {
        
        $corporate_user = CorporateUser::where('id','=',$id)->first();
        if($corporate_user !=null){
            $id_value = $id;
            $countries = Country::all();
            return view('corporate-mobile', compact('id_value','countries'));
        }else{
            return view('corporate-error');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CorporateUser  $corporateUser
     * @return \Illuminate\Http\Response
     */
    public function send_mobile(Request $request)
    {
        
        try {
            $corporate_user = CorporateUser::where('id','=',$request->id_value)->first();
            if($corporate_user !=null){
                $account = VerifyUser::where('corporate_user_id','=',$corporate_user->id)->first();
                //$otp = mt_rand(100000, 999999);
                $otp = 123456;
                $id_value = $request->id_value;
                if($account ==null){
                    VerifyUser::create([
                            'corporate_user_id' => $corporate_user->id,
                            'mobile' => $request->phone,
                            'otp' => $otp,
                            'verified' => 0,
                        ]);
                }else{
                    $account->otp = $otp;
                    $account->save();
                }
                $number = $request->dial_code.$request->phone;
                $message = "DO NOT SHARE:".$otp." is the OTP for your account. Keep this OTP to yourself for account safety.";
                (new SendPushNotification)->sendSMSUser($number,$message);

                return view('corporate-otp', compact('id_value'));
            }else{
                return view('corporate-error');
            }
    
        }  
        catch (Exception $e) {
            return view('corporate-error');
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\CorporateUser  $corporateUser
     * @return \Illuminate\Http\Response
     */
    public function send_otp(Request $request)
    {
        $id_value = $request->id_value;
        $verify = VerifyUser::where('corporate_user_id','=',$request->id_value)->first();
        if($verify->otp == $request->otp){
            $verify->verified =1;
            $verify->save();
            $countries = Country::all();
            return view('corporate-account', compact('id_value','countries'));
        }else{
            return view('corporate-otp', compact('id_value'));
        }
        
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\CorporateUser  $corporateUser
     * @return \Illuminate\Http\Response
     */
    public function send_account(Request $request)
    {
        $id_value = $request->id_value;
        $verify = VerifyUser::where('corporate_user_id','=',$request->id_value)->first();
        if($verify->verified ==1){
            $user = User::where('mobile','=',$verify->mobile)->first();
            if($user !=null){
                $user->corporate_user_id = $verify->corporate_user_id;
                $user->corporate_status = 1;
                $user->save();
            }else{
                $name = $request->name;
                $email = $request->email;
                $password = $request->password;
                $gender = $request->gender;
                $country = Country::where('countryid','=',$request->country_id)->first();
                User::create([
                        'name' => $name,
                        'email' => $email,
                        'password' => bcrypt($request->password),
                        'dial_code' => $country->dial_code,
                        'country_id' => $country->id,
                        'mobile' => $verify->mobile,
                        'corporate_user_id' => $verify->corporate_user_id,
                        'gender' => $request->gender,
                        'status' =>1,
                        'corporate_status' => 1,
                        'device_type' =>'android',
                        'login_by' =>'manual',
                    ]);
            }
            
            return view('corporate-success');
        }else{
            return view('corporate-account', compact('id_value'));
        }
        
    }
}
