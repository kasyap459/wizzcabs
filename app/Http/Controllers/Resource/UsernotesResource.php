<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Setting;
use App\Models\UserNote;
use Exception;
use Auth;
use App\Models\Admin;

class UsernotesResource extends Controller
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
        $usernotes = UserNote::orderBy('created_at' , 'desc')->get();
        return view('admin.usernotes.index', compact('usernotes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.usernotes.create');
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
            'notes' => 'required|max:255',
        ]);

        try{

            $notes = $request->all();
            $notes['status'] = 1;
            $notes = UserNote::create($notes);
            return back()->with('flash_success','User Notes Saved Successfully');
        } 

        catch (Exception $e) {
            return back()->$e;
        } 
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
    public function edit($id)
    {
        try {
            $usernote = UserNote::findOrFail($id);
            return view('admin.usernotes.edit',compact('usernote'));
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
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'notes' => 'required|max:255',
        ]);

        try {

            $notes = UserNote::findOrFail($id);
            $notes->notes = $request->notes;
            $notes->save();
            return redirect()->route('admin.user-note.index')->with('flash_success', 'Updated Successfully');    
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'UserNotes Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            UserNote::find($id)->delete();
            return back()->with('message', 'deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'Not Found');
        }
    }

    public function active($id)
    {
        UserNote::where('id',$id)->update(['status' => 1]);
        return back()->with('flash_success', "activated");
        
    }

    public function inactive($id)
    {
        UserNote::where('id',$id)->update(['status' => 0]);
        return back()->with('flash_success', "inactivated");
    }
}
