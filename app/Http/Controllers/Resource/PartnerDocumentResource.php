<?php

namespace App\Http\Controllers\Resource;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SendPushNotification;
use App\Models\Admin;
use Auth;
use App\Models\Partner;
use App\Models\CarrierDocList;
use App\Models\PartnerDocument;
use Storage;
class PartnerDocumentResource extends Controller
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
    public function index(Request $request, $partner)
    {
        try {
            $partner = Partner::findOrFail($partner);
            $documents = CarrierDocList::get();
            $partnerdocuments = PartnerDocument::get();
            return view('admin.partners.document.index', compact('partner','documents','partnerdocuments'));
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
    public function store(Request $request, $partner)
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
    public function edit($partner, $id)
    {
        try {
            $Document = PartnerDocument::where('partner_id', $partner)
                ->findOrFail($id);
            return view('admin.partners.document.edit', compact('Document'));
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
    public function update(Request $request, $partner, $id)
    {
        try {

            $Document = PartnerDocument::where('partner_id', $partner)
                ->where('document_id', $id)
                ->firstOrFail();
            $Document->update(['status' => 'ACTIVE']);

            return redirect()
                ->route('admin.partner.document.index', $partner)
                ->with('flash_success', 'Carrier document has been approved.');
        } catch (ModelNotFoundException $e) {
            return redirect()
                ->route('admin.partner.document.index', $partner)
                ->with('flash_error', 'Carrier not found!');
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request, $partner, $id)
    {
        $this->validate($request, [
                'document' => 'required|mimes:jpg,jpeg,png,pdf',
            ]);

        try {
            
            $Document = PartnerDocument::where('partner_id', $partner)
                ->where('document_id', $id)
                ->firstOrFail();
            Storage::delete($Document->url);
            
            $Document->update([
                    'url' => $request->document->store('partner/documents'),
                    'status' => 'ASSESSING',
                ]);

            return redirect()
                ->route('admin.partner.document.index', $partner)
                ->with('flash_success', 'Carrier document has been uploaded.');
                
        } catch (ModelNotFoundException $e) {

            PartnerDocument::create([
                    'url' => $request->document->store('partner/documents'),
                    'partner_id' => $partner,
                    'document_id' => $id,
                    'status' => 'ASSESSING',
                ]);
            return redirect()
                ->route('admin.partner.document.index', $partner)
                ->with('flash_success', 'Carrier document has been uploaded.');
        }

        return back();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($partner, $id)
    {
        try {

            $Document = PartnerDocument::where('partner_id', $partner)
                ->where('document_id', $id)
                ->firstOrFail();
            Storage::delete($Document->url);
            $Document->delete();

            return redirect()
                ->route('admin.partner.document.index', $partner)
                ->with('flash_success', 'Carrier document has been deleted');
        } catch (ModelNotFoundException $e) {
            return redirect()
                ->route('admin.partner.document.index', $partner)
                ->with('flash_error', 'Carrier not found!');
        }
    }
}
