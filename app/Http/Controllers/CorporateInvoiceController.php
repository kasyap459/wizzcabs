<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Carbon\Carbon;
use App\Http\Controllers\SendPushNotification;
use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;
use Mail;
use Auth;
use Storage;
use Setting;

use App\Models\Corporate;
use App\Models\Provider;
use App\Models\UserRequest;
use App\Models\UserRequestPayment;
use App\Models\CorporateInvoice;
use PDF;

class CorporateInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = CorporateInvoice::with('corporate')->orderBy('created_at' , 'desc')->get();

        if(Auth::guard('admin')->user()){
            return view('admin.corporateinvoice.index', compact('invoices'));
        }elseif(Auth::guard('account')->user()){
            return view('account.corporateinvoice.index', compact('invoices'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'from_date' => 'required',
            'to_date' => 'required',
            'corporate_id' => 'required',
        ]);
        $prev_balance =0.00;
        $prev_payment =0.00;
        
        $corporate = Corporate::where('id', '=', $request->corporate_id)->first();
        $from_date = $request->from_date;
        $to_date = Carbon::parse($request->to_date)->addDay();

        $rides = UserRequest::where('status','COMPLETED')
                ->where('PAID','=',1)
                ->where('corporate_id','=',$request->corporate_id)
                ->where('created_at', '>=', $from_date)
                ->where('created_at', '<', $to_date)
                ->get();

        $ride_count= $rides->count();       
        $ride_total = 0;                
        foreach($rides as $key=>$tb)
        {
           if($tb->payment){
                $ride_total+= $tb->payment->total;
                $rides[$key]->vat_percent = Setting::get('vat_percent');
           } 
        }

        $last_invoice = CorporateInvoice::where('corporate_id', '=', $request->corporate_id)->orderBy('created_at' , 'desc')->first();
        if($last_invoice !=null){
            $prev_balance = $last_invoice->balance;
            $prev_payment = $last_invoice->paid;
        }
        $current_payment = $prev_balance + $ride_total;
        $now = Carbon::now();
        $invoice_id = 'IV'.date("y").$now->month.$now->weekOfMonth.$corporate->id;

        if(Auth::guard('admin')->user()){
            return view('admin.corporateinvoice.create', compact('corporate','from_date','to_date','rides','ride_count','ride_total','invoice_id','now','prev_balance','prev_payment','current_payment'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
                $corporate = Corporate::where('id', '=', $request->corporate_id)->first();
                $from_date = $request->from_date;
                $to_date = Carbon::parse($request->to_date)->addDay();

                $rides = UserRequest::where('status','COMPLETED')
                ->where('PAID','=',1)
                ->where('corporate_id','=',$request->corporate_id)
                ->where('created_at', '>=', $from_date)
                ->where('created_at', '<', $to_date)
                ->get();

                $data['admin_id'] = $corporate->admin_id;
                $data['corporate_id'] = $request->corporate_id;
                $data['invoice_id'] = $request->invoice_id;
                $data['from_date'] = $request->from_date;
                $data['to_date'] = $request->to_date;
                $data['ride_count'] = $request->ride_count;
                $data['ride_total'] = $request->ride_total;
                $data['prev_balance'] = $request->prev_balance;
                $data['prev_payment'] = $request->prev_payment;
                $data['current_payment'] = $request->current_payment;
                $data['total'] = $request->current_payment;
                $data['ride_no'] ='';
                $data = CorporateInvoice::create($data);


                /*$invoice = ProviderStatement::with('provider')->where('invoice_id','=',$request->invoice_id)->first();
                view()->share('invoice',$invoice);
                $pdf = PDF::loadView('pdf.invoice', $invoice);
                $doc_name = $invoice->invoice_id.'.pdf';
                Storage::put($doc_name,$pdf->download($doc_name));
                // send welcome email here
                if(Setting::get('mail_enable', 0) == 1) {
                    $file = storage_path('app/public/'.$doc_name);
                    $user = Provider::find($request->provider_id);
                    $user['path'] = $file;
                    $user['invoice_id'] = $request->invoice_id;
                    $user['from_date'] = $from_date;
                    $user['to_date'] = $to_date;
                    Mail::send('emails.invoice', ['user' => $user], function ($message) use ($user){
                        $message->to($user['email'], $user['first_name'])->subject(config('app.name').' Payment Statement')->attach($user['path']);
                    });
                    Storage::delete($doc_name);
                }else{
                    Storage::delete($doc_name);
                }*/
            
            return response()->json(['message' => 'success']);

        } 

        catch (Exception $e) {
            return back()->with('flash_error', 'Invoices Not Found');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CorporateInvoice  $CorporateInvoice
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $invoice = CorporateInvoice::with('corporate')->findOrFail($id);
            $rides = UserRequest::where('status','COMPLETED')
                ->where('PAID','=',1)
                ->where('corporate_id','=',$invoice->corporate_id)
                ->where('created_at', '>=', $invoice->from_date)
                ->where('created_at', '<', $invoice->to_date)
                ->get();

            foreach($rides as $key=>$tb){
               if($tb->payment){
                    $rides[$key]->vat_percent = Setting::get('vat_percent');
               } 
            }

            if(Auth::guard('admin')->user()){
                return view('admin.corporateinvoice.view',compact('invoice','rides'));
            }elseif(Auth::guard('account')->user()){
                return view('account.corporateinvoice.view',compact('invoice','rides'));
            }

        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Invoice Type Not Found');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CorporateInvoice  $CorporateInvoice
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $invoice = CorporateInvoice::findOrFail($id);
            return view('admin.corporateinvoice.edit',compact('invoice'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CorporateInvoice  $CorporateInvoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'paid' => 'required|max:255',
            'balance' => 'required|max:255',
        ]);

        try {

            $invoice = CorporateInvoice::findOrFail($id);
            $invoice->paid = $request->paid;
            $invoice->balance = $request->balance;
            $invoice->save();

            return redirect()->route('admin.corporateinvoicelist')->with('flash_success', 'Invoices Updated Successfully');    
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Invoices Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CorporateInvoice  $CorporateInvoice
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            CorporateInvoice::find($id)->delete();
            return back()->with('message', 'Invoice deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'Invoice Not Found');
        }
    }

    public function invoiceexport(Request $request)
    {
        $invoice = ProviderStatement::with('provider')->where('invoice_id','=',$request->invoice_id)->first();
        view()->share('invoice',$invoice);
        $pdf = PDF::loadView('pdf.invoice', $invoice);
        $doc_name = $invoice->invoice_id.'.pdf';
        Storage::put($doc_name,$pdf->download($doc_name));
        // send welcome email here
        if(Setting::get('mail_enable', 0) == 1) {
            $file = storage_path('app/public/'.$doc_name);
            $user = Provider::find($invoice->provider_id);
            $user['path'] = $file;
            $user['invoice_id'] = $invoice->invoice_id;
            $user['from_date'] = $invoice->from_date;
            $user['to_date'] = $invoice->to_date;
            Mail::send('emails.invoice', ['user' => $user], function ($message) use ($user){
                $message->to($user['email'], $user['first_name'])->subject(config('app.name').' Payment Statement')->attach($user['path']);
            });
            Storage::delete($doc_name);
        }else{
            Storage::delete($doc_name);
        }
        return back()->with('flash_success', 'Statement Send Successfully');
    }
}
