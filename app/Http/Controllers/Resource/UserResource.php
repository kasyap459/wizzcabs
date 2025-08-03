<?php

namespace App\Http\Controllers\Resource;

use Storage;
use App\Models\User;
use App\Models\Corporate;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Exception;
use Setting;
use Auth;
use File;
use URL;
use \Carbon\Carbon;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;

class UserResource extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
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
       // dd(Carbon::now());
        $users = User::orderBy('created_at' , 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::all();
        $corporates = Corporate::all();
        return view('admin.users.create', compact('countries','corporates'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function user_row(Request $request){

        $columns = array( 
                            0=>'id', 
                            1=>'name',
                            2=> 'email',
                            3=> 'mobile',
                            4=> 'rating',
                            5=> 'corporate',
                            6=> 'action',
                        );
       
        $totalData = User::count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $users = User::with('corporate_user')->offset($start)
                     ->limit($limit)
                     ->orderBy('id','desc')
                     ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $users =  User::with('corporate_user')->where('first_name','LIKE',"%{$search}%")
                            ->orWhere('email', 'LIKE',"%{$search}%")
                            ->orWhere('mobile', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy('id','desc')
                            ->get();

            $totalFiltered = User::with('corporate_user')->where('first_name','LIKE',"%{$search}%")
                            ->orWhere('email', 'LIKE',"%{$search}%")
                            ->orWhere('mobile', 'LIKE',"%{$search}%")
                            ->count();
        }

        $data = array();
        if(!empty($users))
        {
            foreach ($users as $index => $user)
            {
            if($user->first_name != ''){ $name = $user->first_name;}else{$name = "";}
            if($user->email != ''){ $email = $user->email;}else{$email = "";}
            if($user->mobile != ''){ $mobile = $user->dial_code.$user->mobile;}else{$mobile = "";}
            /*if($user->wallet_balance != ''){ $wallet_balance = $user->wallet_balance;}else{$wallet_balance = "-";}*/
            if($user->corporate_user_id){ 
            $corporate= Corporate::where('id','=',$user->corporate_user_id)->pluck('display_name')->first();
            }else{
                $corporate = "-";
            }
            if($user->account_status == 'approved'){
                $enable = '<a class="btn btn-danger btn-rounded btn-block waves-effect waves-light" href="'.route('admin.user.banned', $user->id ).'">'.trans("admin.member.disable").'</a>';
                }else{
                $enable = '<a class="btn btn-success btn-rounded btn-block waves-effect waves-light" href="'.route('admin.user.approve', $user->id ).'">'.trans("admin.member.enable").'</a>';
                }
            $button = '<div class="btn-group" role="group">
                            <button type="button" class="btn btn-info btn-rounded btn-block dropdown-toggle"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="margin-left: 23px">
                                '.trans("admin.member.action").'
                            </button>
                            <div class="dropdown-menu">
                                <a href="'. route('admin.user.request', $user->id).'" class="dropdown-item">
                                    <i class="fa fa-files-o"></i> '.trans("admin.member.history").'
                                </a>
                                <a href="'.route('admin.user.edit', $user->id).'" class="dropdown-item">
                                    <i class="fa fa-pencil-square-o"></i> '.trans("admin.member.edit").'
                                </a>
                                <form action="'.route('admin.user.destroy', $user->id).'" method="POST">
                                    '.csrf_field().'
                                    '.method_field('DELETE').'
                                    <button type="submit" class="dropdown-item">
                                        <i class="fa fa-trash"></i> '.trans("admin.member.delete").'
                                    </button>
                                </form>
                            </div>
                        </div>';

                $action = '<div class="input-group-btn">'.$enable.'<br>'.$button.'</div>';
                $nestedData['id'] = $start + 1;
                $nestedData['name'] = $name;
                $nestedData['email'] =  $email;
                $nestedData['mobile'] = $mobile;
                $nestedData['rating'] = $user->rating;
                $nestedData['corporate'] = $corporate;
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
        // return $request->all();
        $this->validate($request, [
            'first_name' => 'required|max:255',
            'email' => 'required|unique:users,email,NULL,id,deleted_at,NULL|email|max:255',
            'password' => 'required|min:6|confirmed',
            'mobile' => 'required|unique:users,mobile,NULL,id,deleted_at,NULL|digits_between:6,13',
            'picture' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
            'country_id' => 'required',
        ]);

        try{

            $user = $request->all();
            $country = Country::where('countryid','=',$request->country_id)->first();
            if(Auth::guard('admin')->user()->admin_type !=0){
                $user['admin_id'] = Auth::guard('admin')->user()->id;
            }
            $user['password'] = bcrypt($request->password);
            $user['dial_code'] = $country->dial_code;
            $user['email'] = $request->email;
            $user['mobile'] = $request->mobile;
            $user['refferal_code'] = Helper::generate_refferal_code();
            $user['status'] = 1;
            if($request->hasFile('picture')) {

                $picture=$request->picture;
               $file_name = time();
               $file_name .= rand();
               $file_name = sha1($file_name);
              
                $ext = $picture->getClientOriginalExtension();
                $picture->move(public_path() . "/uploads/user/profile/", $file_name . "." . $ext);
                $local_url = $file_name . "." . $ext;                    
                $user['picture'] = $local_url;
                // $user['picture'] = $request->picture->store('user/profile');
                //  // $user['picture'] = $request->picture->store('puplic/user/profile');
            }

            $user = User::create($user);

            return back()->with('flash_success','User Details Saved Successfully');

        } 

        catch (Exception $e) {
            return back()->with('flash_error', 'User Not Found');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return view('admin.users.user-details', compact('user'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $countries = Country::all();
            $corporates = Corporate::all();
            $user = User::findOrFail($id);
            $user->picture = '/uploads/user/profile/'.$user->picture;
            return view('admin.users.edit',compact('user','countries','corporates'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'first_name' => 'required|max:255',
            'email' => 'required',
            'mobile' => 'digits_between:6,13',
            'picture' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
            'country_id' => 'required',
        ]);

        try {

            $user = User::findOrFail($id);

            if ($request->picture != "") {
                 File::delete(public_path('uploads/user/profile/'.$user->picture));
                //Storage::delete($user->picture);
                //$user->picture = $request->picture->store('public/user/profile');
               // $user->picture = $request->picture->store('user/profile');
               $picture=$request->picture;
               $file_name = time();
               $file_name .= rand();
               $file_name = sha1($file_name);
              
                $ext = $picture->getClientOriginalExtension();
                $picture->move(public_path() . "/uploads/user/profile/", $file_name . "." . $ext);
                $local_url = $file_name . "." . $ext;                    
                $user->picture = $local_url;
            
            }

            $user->first_name = $request->first_name;
            $country = Country::where('countryid','=',$request->country_id)->first();
            $user->email = $request->email;
            $user->country_id = $country->countryid;
            $user->dial_code = $country->dial_code;
            $user->mobile = $request->mobile;
            $user->gender = $request->gender ? : '';
            $user->custom_field1 = $request->custom_field1 ? : '';
            $user->custom_field2 = $request->custom_field2 ? : '';
            $user->save();

            return redirect()->route('admin.user.index')->with('flash_success', 'User Updated Successfully');    
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'User Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        try {

            User::find($id)->delete();
            return back()->with('message', 'User deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'User Not Found');
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
        User::where('id',$id)->update(['status' => 1]);
        return back()->with('flash_success', "User activated");
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function inactive($id)
    {
        
        User::where('id',$id)->update(['status' => 0]);
        return back()->with('flash_success', "User inactivated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function request($id){

        try{

            $user_id =  $id;
            $provider_id = '';      
            return view('admin.request.index', compact('user_id','provider_id'));
        }

        catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
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
        User::where('id',$id)->update(['account_status' => 'approved']);
        return back()->with('flash_success', "User account approved");

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function banned($id)
    {
        // DB::table('oauth_access_tokens')->where('user_id', $id)->orderBy('id','DESC')->update(['revoked'=> 1]);
        User::where('id',$id)->update(['account_status' =>'banned']);
        return back()->with('flash_success', "User account inactivated");
    }
}
