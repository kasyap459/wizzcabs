<?php

namespace App\Http\Controllers\Resource;

use Storage;
use App\Models\Page;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Exception;
use Setting;
use App\Models\Admin;
use Auth;

class PageResource extends Controller
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
        /*$pages = Page::get();
        return view('admin.pages.index', compact('pages'));*/
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /*return view('admin.pages.create');*/
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*$this->validate($request, [
            'page_title' => 'required',
            'content' => 'required',
        ]);

        try{
            $page = $request->all();
            $page = Page::create($page);
            return back()->with('flash_success','Page Details Saved Successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'Page Not Found');
        }*/
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /*try {
            $page = Page::findOrFail($id);
            return view('admin.pages.show', compact('page'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }*/
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
            $page = Page::findOrFail($id);
            return view('admin.pages.edit',compact('page'));
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
            'page_title' => 'required',
            'content' => 'required',
        ]);

        try {

            $page = Page::findOrFail($id);
            $page->page_title = $request->page_title;
            $page->content = $request->content;
            $page->save();
            
            return back()->with('flash_success', 'Page Updated Successfully');   
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Page Not Found');
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
        
        /*try {
            Page::find($id)->delete();
            return back()->with('message', 'Page deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'Page Not Found');
        }*/
    }

}
