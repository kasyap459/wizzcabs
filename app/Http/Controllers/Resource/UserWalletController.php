<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserWallet;
use App\Http\Controllers\SendPushNotification;

class UserWalletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('created_at' , 'desc')->get();
        return view('admin.userwallet.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Users = User::select('id','first_name','email','wallet_balance')->get();
        $userwallets = User::get();
        return view('admin.userwallet.create', compact('userwallets','Users'));
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
                $wallet = User::find($request->user_id);
                $wallet->wallet_balance += $request->amount;
                $wallet->save();
                 UserWallet::create([
                'user_id' => $request->user_id,
                'amount' => $request->amount,
                'mode' => 'Offline',
                'status' => 'Credited',
            ]);
            (new SendPushNotification)->WalletMoney($request->user_id,$request->amount);
            return back()->with('flash_success', 'Wallet Added Successfully!');
            
        }
        catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong! (Check All Passenger Details)');
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
	    $userid = $id;
        $user = User::find($id);
        $userwallets = UserWallet::where('user_id',$id)->orderBy('created_at' , 'desc')->get();
        $wallet = $user->wallet_balance;
        $page = $user->first_name."'s Overall Wallet Credited transaction ". $wallet;
        return view('admin.userwallet.credit', compact('userwallets','page','wallet','userid'));
    }


    public function debited(Request $request)
    {
        // $user = User::find($id);
        // $userwallets = UserWallet::where('user_id',$id)->where('status','Debited')->get();
        // $wallet = $user->wallet_balance;
        // $page = $user->first_name."'s Overall Wallet Debited transaction ". $wallet;
        // return view('admin.userwallet.debit', compact('userwallets','page','wallet'));
        try{
                $wallet = User::find($request->userid);
                $wallet->wallet_balance -= $request->debit_amount;
                $wallet->save();
                 UserWallet::create([
                'user_id' => $request->userid,
                'amount' => $request->debit_amount,
                'mode' => 'Offline',
                'status' => 'Debited',
            ]);
            (new SendPushNotification)->DebitWalletMoney($request->userid,$request->debit_amount);
            return back()->with('flash_success', 'Wallet Debited Successfully!');
            
        }
        catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong! (Check All Passenger Details)');
        }

    }

}
