<?php

namespace App\Http\Controllers\Resource;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SendPushNotification;
use App\Models\Admin;
use App\Models\Corporate;
use App\Models\CorporateDocList;
use App\Models\CorporateDocument;
use Storage;
use Auth;
class CorporateDocumentResource extends Controller
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
    public function index(Request $request, $corporate)
    {
        try {
            $corporate = Corporate::findOrFail($corporate);
            $documents = CorporateDocList::get();
            $corporatedocuments = CorporateDocument::get();
            return view('admin.corporates.document.index', compact('corporate','documents','corporatedocuments'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $corporate)
    {
        
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
    public function edit($corporate, $id)
    {
        try {
            $Document = CorporateDocument::where('corporate_id', $corporate)
                ->findOrFail($id);
            return view('admin.corporates.document.edit', compact('Document'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('admin.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $corporate, $id)
    {
        try {

            $Document = CorporateDocument::where('corporate_id', $corporate)
                ->where('document_id', $id)
                ->firstOrFail();
            $Document->update(['status' => 'ACTIVE']);

            return redirect()
                ->route('admin.corporate.document.index', $corporate)
                ->with('flash_success', 'Corporate document has been approved.');
        } catch (ModelNotFoundException $e) {
            return redirect()
                ->route('admin.corporate.document.index', $corporate)
                ->with('flash_error', 'Corporate not found!');
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request, $corporate, $id)
    {
        $this->validate($request, [
                'document' => 'required|mimes:jpg,jpeg,png,pdf',
            ]);

        try {
            
            $Document = CorporateDocument::where('corporate_id', $corporate)
                ->where('document_id', $id)
                ->firstOrFail();
            Storage::delete($Document->url);
            
            $Document->update([
                    'url' => $request->document->store('corporate/documents'),
                    'status' => 'ASSESSING',
                ]);

            return redirect()
                ->route('admin.corporate.document.index', $corporate)
                ->with('flash_success', 'Corporate document has been uploaded.');
                
        } catch (ModelNotFoundException $e) {

            CorporateDocument::create([
                    'url' => $request->document->store('corporate/documents'),
                    'corporate_id' => $corporate,
                    'document_id' => $id,
                    'status' => 'ASSESSING',
                ]);
            return redirect()
                ->route('admin.corporate.document.index', $corporate)
                ->with('flash_success', 'Corporate document has been uploaded.');
        }

        return back();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($corporate, $id)
    {
        try {

            $Document = CorporateDocument::where('corporate_id', $corporate)
                ->where('document_id', $id)
                ->firstOrFail();
            Storage::delete($Document->url);
            $Document->delete();

            return redirect()
                ->route('admin.corporate.document.index', $corporate)
                ->with('flash_success', 'Corporate document has been deleted');
        } catch (ModelNotFoundException $e) {
            return redirect()
                ->route('admin.corporate.document.index', $corporate)
                ->with('flash_error', 'Corporate not found!');
        }
    }
}
