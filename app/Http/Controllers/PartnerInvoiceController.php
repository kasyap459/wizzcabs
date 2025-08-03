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

use App\Models\Partner;
use App\Models\Provider;
use App\Models\UserRequest;
use App\Models\UserRequestPayment;
use App\Models\PartnerInvoice;
use PDF;

class PartnerInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = PartnerInvoice::with('partner')->orderBy('created_at' , 'desc')->get();

        if(Auth::guard('admin')->user()){
            return view('admin.invoice.index', compact('invoices'));
        }elseif(Auth::guard('account')->user()){
            return view('account.invoice.index', compact('invoices'));
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
            'partner_id' => 'required',
        ]);
        $prev_balance =0.00;
        $prev_payment =0.00;
        $commission =0.00;

        $partner = Partner::where('id', '=', $request->partner_id)->first();
        $from_date = $request->from_date;
        $to_date = Carbon::parse($request->to_date)->addDay();
        $vat_percent = Setting::get('vat_percent');
        $commission_percent = $partner->carrier_percentage;
        $commission_vat_percent = 25;

        $rides = UserRequest::where('status','COMPLETED')
                ->where('PAID','=',1)
                ->where('partner_id','=',$request->partner_id)
                ->where('created_at', '>=', $from_date)
                ->where('created_at', '<', $to_date)
                ->get();

        $ride_count= $rides->count();       
        $overall_commission_total = 0;            
        $overall_ride_total = 0; 
        $overall_carrier_total = 0;
        $cash_total = 0;
        $card_total = 0;
        $corporate_total = 0;
        $customer_vat_total =0;
        foreach($rides as $key=>$tb)
        {
            if($tb->payment){
                $commission = $tb->payment->total * ( $commission_percent/100 );
                $commission_vat = $commission * ( $commission_vat_percent/100 );

                $rides[$key]->vat_percent = Setting::get('vat_percent');
                $rides[$key]->commission = $commission;
                $rides[$key]->commission_vat = $commission_vat;
                $rides[$key]->commission_total = $commission + $commission_vat;
                $rides[$key]->ride_total = $tb->payment->total;
                $rides[$key]->carrier_total = $tb->payment->total- ($commission + $commission_vat);
                $overall_commission_total+= $commission + $commission_vat;
                $overall_ride_total+= $tb->payment->total;
                $overall_carrier_total+= $tb->payment->total- ($commission + $commission_vat);

                if($tb->corporate_id !=0){
                    $corporate_total+= $tb->payment->total;
                }
                if($tb->payment_mode =='CASH' && $tb->corporate_id==0){
                    $cash_total+= $tb->payment->total;
                }
                if($tb->payment_mode =='CARD' && $tb->corporate_id==0){
                    $card_total+= $tb->payment->total;
                }
            } 
        }

        $customer_vat_total = $overall_ride_total * Setting::get('vat_percent')/100;

        $last_invoice = PartnerInvoice::where('partner_id', '=', $request->partner_id)->orderBy('created_at' , 'desc')->first();
        if($last_invoice !=null){
            $prev_balance = $last_invoice->balance;
            $prev_payment = $last_invoice->paid;
        }

        $current_payment = $prev_balance + $overall_commission_total;
        $sub_total = $card_total+$corporate_total - $overall_commission_total;
        if($sub_total < 0){
            $carrier_pay = abs($sub_total);
            $admin_pay = 0;
        }else{
            $carrier_pay = 0;
            $admin_pay = abs($sub_total);
        }
        
        $now = Carbon::now();
        $invoice_id = 'IV'.date("y").$now->month.$now->weekOfMonth.$partner->id;

        if(Auth::guard('admin')->user()){
            return view('admin.invoice.create', compact('partner','from_date','to_date','rides','ride_count','customer_vat_total','overall_ride_total','invoice_id','now','commission_percent','commission_vat_percent','overall_commission_total','overall_carrier_total','prev_balance','prev_payment','current_payment','cash_total','card_total','admin_pay','carrier_pay','vat_percent','corporate_total'));
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
                $data['partner_id'] = $request->partner_id;
                $data['invoice_id'] = $request->invoice_id;
                $data['ride_count'] = $request->ride_count;
                $data['cash_total'] = $request->cash_total;
                $data['card_total'] = $request->card_total;
                $data['ride_total'] = $request->ride_total;
                $data['commission_total'] = $request->commission_total;
                $data['carrier_total'] = $request->carrier_total;
                $data['prev_balance'] = $request->prev_balance;
                $data['prev_payment'] = $request->prev_payment;
                $data['current_payment'] = $request->current_payment;
                $data['admin_pay'] = $request->admin_pay;
                $data['carrier_pay'] = $request->carrier_pay;
                $data['from_date'] = $request->from_date;
                $data['to_date'] = $request->to_date;
                $data['vat_percent'] = $request->vat_percent;
                $data['commission_percent'] = $request->commission_percent;
                $data['commission_vat_percent'] = $request->commission_vat_percent;
                $data['ride_no'] ='';
                $data = PartnerInvoice::create($data);

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
     * @param  \App\PartnerInvoice  $partnerInvoice
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $invoice = PartnerInvoice::with('partner')->findOrFail($id);
            $rides = UserRequest::where('status','COMPLETED')
                ->where('PAID','=',1)
                ->where('partner_id','=',$invoice->partner_id)
                ->where('created_at', '>=', $invoice->from_date)
                ->where('created_at', '<', $invoice->to_date)
                ->get();
            $corporate_total = 0;
            $customer_vat_total =0;
                
            foreach($rides as $key=>$tb){
                if($tb->payment){
                    $commission = $tb->payment->total * ( $invoice->commission_percent/100 );
                    $commission_vat = $commission * ( $invoice->commission_vat_percent/100 );

                    $rides[$key]->vat_percent = Setting::get('vat_percent');
                    $rides[$key]->commission = $commission;
                    $rides[$key]->commission_vat = $commission_vat;
                    $rides[$key]->commission_total = $commission + $commission_vat;
                    $rides[$key]->ride_total = $tb->payment->total;
                    $rides[$key]->carrier_total = $tb->payment->total- ($commission + $commission_vat);

                    if($tb->corporate_id !=0){
                        $corporate_total+= $tb->payment->total;
                    }
                } 
            }

            $customer_vat_total = $invoice->ride_total * Setting::get('vat_percent')/100;

            if(Auth::guard('admin')->user()){
                return view('admin.invoice.view',compact('invoice','rides','corporate_total','customer_vat_total'));
            }elseif(Auth::guard('account')->user()){
                return view('account.invoice.view',compact('invoice','rides','corporate_total','customer_vat_total'));
            }

        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Invoice Type Not Found');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PartnerInvoice  $partnerInvoice
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $invoice = PartnerInvoice::findOrFail($id);
            return view('admin.invoice.edit',compact('invoice'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PartnerInvoice  $partnerInvoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'paid' => 'required|max:255',
            'balance' => 'required|max:255',
        ]);

        try {

            $invoice = PartnerInvoice::findOrFail($id);
            $invoice->paid = $request->paid;
            $invoice->balance = $request->balance;
            $invoice->save();

            return redirect()->route('admin.invoicelist')->with('flash_success', 'Invoices Updated Successfully');    
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Invoices Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PartnerInvoice  $partnerInvoice
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            PartnerInvoice::find($id)->delete();
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
