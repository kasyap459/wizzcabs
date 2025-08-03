<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProviderWallet;
use App\Models\Provider;
use App\Http\Controllers\SendPushNotification;
use Auth;

class ProviderPartnerWalletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $providers = Provider::where('partner_id','=',Auth::user()->id)->orderBy('created_at' , 'desc')->get();
        return view('partner.providerwallet.index', compact('providers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $providers = Provider::where('partner_id','=',Auth::user()->id)->select('id','name','email','wallet_balance')->get();
        $providerwallets = Provider::where('partner_id','=',Auth::user()->id)->get();
        return view('partner.userwallet.create', compact('providerwallets','providers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $wallet = Provider::find($request->provider_id);
        $wallet->wallet_balance += $request->amount;
        $wallet->save();
        ProviderWallet::create([
        'provider_id' => $request->provider_id,
        'amount' => $request->amount,
        'mode' => 'Added by Admin',
        'status' => 'Credited',
        ]);
        (new SendPushNotification)->drviverWalletMoney($request->provider_id,$request->amount);
        return back()->with('flash_success', 'Wallet Added Successfully!');
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function credited($id)
    {
        $providerid = $id;
        $provider = Provider::find($id);
        $providerwallets = ProviderWallet::where('provider_id',$id)->orderBy('created_at' , 'desc')->get();
        $wallet = $provider->wallet_balance;
        $page = $provider->name."'s Overall Wallet Credited transaction ". $wallet;
        return view('partner.providerwallet.credit', compact('providerwallets','page','wallet','providerid'));
    }


    public function debited(Request $request)
    {
        try{
                $wallet = Provider::find($request->providerid);
                $wallet->wallet_balance -= $request->debit_amount;
                $wallet->save();
                 ProviderWallet::create([
                'provider_id' => $request->providerid,
                'amount' => $request->debit_amount,
                'mode' => 'Debited by Admin',
                'status' => 'Debited',
            ]);
            (new SendPushNotification)->drviverDebitMoney($request->providerid,$request->debit_amount);
            return back()->with('flash_success', 'Wallet Debited Successfully!');
            
        }
        catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong! (Check All Passenger Details)');
        }
}

}
