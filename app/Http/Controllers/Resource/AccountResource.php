<?php

namespace App\Http\Controllers\Resource;

use App\Models\Account;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Exception;
use Setting;
use App\Models\Admin;
use Auth;
use Storage;

class AccountResource extends Controller
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
        $accounts = Account::orderBy('created_at' , 'desc')->get();
        return view('admin.account-manager.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::all();
        return view('admin.account-manager.create', compact('countries'));
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
            'mobile' => 'digits_between:6,13',
            'email' => 'required|unique:accounts,email|email|max:255',
            'password' => 'required|min:6|confirmed',
            'picture' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
            'country_id' => 'required',
        ]);

        try{

            $Account = $request->all();
            if(Auth::guard('admin')->user()->admin_type !=0){
                $Account['admin_id'] = Auth::guard('admin')->user()->id;
            }
            $Account['password'] = bcrypt($request->password);
            $country = Country::where('countryid','=',$request->country_id)->first();
            $Account['dial_code'] = $country->dial_code;
            if($request->hasFile('picture')) {
                 $Account['picture'] = $request->picture->store('public/account/profile');
                $Account['picture'] = $request->picture->store('account/profile');
            }
            $Account = Account::create($Account);

            return back()->with('flash_success','Account Manager Details Saved Successfully');

        } 

        catch (Exception $e) {
            return back()->$e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Dispatcher  $account
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\account  $account
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $countries = Country::all();
            $account = Account::findOrFail($id);
            return view('admin.account-manager.edit',compact('account','countries'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\account  $account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $this->validate($request, [
            'name' => 'required|max:255',
            'mobile' => 'digits_between:6,13',
            'picture' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
            'country_id' => 'required',
        ]);

        try {

            $Account = Account::findOrFail($id);

            if($request->hasFile('picture')) {
                Storage::delete($Account->picture);
                $Account['picture'] = $request->picture->store('public/account/profile');
                $Account['picture'] = $request->picture->store('account/profile');
            }
            $Account->name = $request->name;
            $country = Country::where('countryid','=',$request->country_id)->first();
            $Account->country_id = $country->countryid;
            $Account->dial_code = $country->dial_code;
            $Account->mobile = $request->mobile;
            if($request->filled('password')){
                $Account->password = bcrypt($request->password);
            }
            $Account->save();

            return redirect()->route('admin.account-manager.index')->with('flash_success', 'Account Manager Updated Successfully');    
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Account Manager Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Account  $dispatcher
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Account::find($id)->delete();
            return back()->with('message', 'Account Manager deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'Account Not Found');
        }
    }

}
