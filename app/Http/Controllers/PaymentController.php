<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\SendPushNotification;

use Stripe\Charge;
use Stripe\Stripe;
use Stripe\StripeInvalidRequestError;

use Auth;
use Setting;
use Exception;

use App\Models\Card;
use App\Models\User;
use App\Models\UserRequest;
use App\Models\UserRequestPayment;
use App\Models\UserWallet;

class PaymentController extends Controller
{
    /**
     * add wallet money for user.
     *
     * @return \Illuminate\Http\Response
     */
    public function add_money(Request $request){

        $this->validate($request, [
                'amount' => 'required',
                 'card_id' => 'required|exists:cards,card_id,user_id,'.Auth::user()->id
            ]);

        try{
            
          //  $StripeWalletCharge = $request->amount ;
             $StripeWalletCharge = $request->amount * 100;

            Stripe::setApiKey(Setting::get('stripe_secret_key'));

            $Charge = Charge::create(array(
                  "amount" => $StripeWalletCharge,
                  "currency" => "usd",
                  "customer" => Auth::user()->stripe_cust_id,
                  "card" => $request->card_id,
                  "description" => "Adding Money for ".Auth::user()->email,
                  "receipt_email" => Auth::user()->email
                ));

            $update_user = User::find(Auth::user()->id);
            $update_user->wallet_balance += $request->amount;
            $update_user->save();

            $userwallet = new UserWallet();
            $userwallet->user_id =Auth::user()->id;
            // $userwallet->card_id =;
            $userwallet->amount =$request->amount;
            $userwallet->mode  ="Online";
            $userwallet->status  ="Credited";
            $userwallet->save();

            Card::where('user_id',Auth::user()->id)->update(['is_default' => 0]);
            // Card::where('card_id',$request->card_id)->update(['is_default' => 1]);

            //sending push on adding wallet money
            (new SendPushNotification)->WalletMoney(Auth::user()->id,currency($request->amount));

            if($request->ajax()){
                return response()->json(['message' => 'Credited to your wallet', 'user' => $update_user]); 
            } else {
                return redirect('wallet')->with('flash_success',currency($request->amount).'Credited to your wallet');
            }

        } catch(StripeInvalidRequestError $e) {
            if($request->ajax()){
                 return response()->json(['error' => $e->getMessage()], 500);
            }else{
                return back()->with('flash_error',$e->getMessage());
            }
        } catch(Exception $e) {
            if($request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 500);
            } else {
                return back()->with('flash_error', $e->getMessage());
            }
        }
    }

    /**
     * payment for user.
     *
     * @return \Illuminate\Http\Response
     */
    public function streetride_pay(Request $request)
    {
        $this->validate($request, [
                'request_id' => 'required|exists:user_request_payments,request_id|exists:user_requests,id,paid,0',
                'stripe_token' => 'required',
                'payment_mode' => 'required',
                'email' => 'required'
            ]);


        $UserRequest = UserRequest::find($request->request_id);
        $UserRequest->payment_mode = $request->payment_mode;
        $UserRequest->save();

        if($UserRequest->payment_mode == 'CARD') {
            $RequestPayment = UserRequestPayment::where('request_id',$request->request_id)->first(); 
            $StripeCharge = $RequestPayment->total * 100;
            try {

                Stripe::setApiKey(Setting::get('stripe_secret_key'));
                $customer = \Stripe\Customer::create([
                            'email' => $request->email,
                        ]);
                $strip_cust_id = $customer['id'];
                $customer = \Stripe\Customer::retrieve($strip_cust_id);
                $card = $customer->sources->create(["source" => $request->stripe_token]);
                
                $Charge = Charge::create(array(
                      "amount" => $StripeCharge,
                      "currency" => "usd",
                      "customer" => $strip_cust_id,
                      "card" => $card['id'],
                      "description" => "Payment Charge for ".$request->email." ".$RequestPayment->request_id,
                      "receipt_email" => $request->email
                    ));

                $RequestPayment->payment_id = $Charge["id"];
                $RequestPayment->payment_mode = 'CARD';
                $RequestPayment->save();
                
                $UserRequest->paid = 1;
                $UserRequest->status = 'COMPLETED';
                $UserRequest->save();

                if($request->ajax()) {
                   return response()->json(['message' => trans('api.paid')]); 
                } else {
                    return redirect('dashboard')->with('flash_success','Paid');
                }

            } catch(StripeInvalidRequestError $e){
                if($request->ajax()){
                    return response()->json(['error' => $e->getMessage()], 500);
                } else {
                    return back()->with('flash_error', $e->getMessage());
                }
            } catch(Exception $e) {
                if($request->ajax()){
                    return response()->json(['error' => $e->getMessage()], 500);
                } else {
                    return back()->with('flash_error', $e->getMessage());
                }
            }
        }
    }

    /**
     * add wallet money for user.
     *
     * @return \Illuminate\Http\Response
     */
    public function due_payment($id){

        try{
            $update_user = User::find($id);
            $cards = Card::where('user_id',$id)->where('is_default',1)->first();
            $due_balance = $update_user->due_balance;
            $StripeWalletCharge = $update_user->due_balance * 100;
            Stripe::setApiKey(Setting::get('stripe_secret_key'));
            
            if($update_user->email !=null){
              $user_email = $update_user->email;
            }else{
              $user_email = Setting::get('contact_email');
            }

            $Charge = Charge::create(array(
                  "amount" => $StripeWalletCharge,
                  "currency" => "usd",
                  "customer" => $update_user->stripe_cust_id,
                  "card" => $cards->card_id,
                  "description" => "Cancellation Money for ".$user_email." ".$update_user->due_trip,
                  "receipt_email" => $user_email
                ));

            $update_user->due_balance = 0;
            $update_user->due_trip = null;
            $update_user->save();
            //sending push on adding wallet money
            (new SendPushNotification)->CancelMoney($id,currency($due_balance));

        } catch(StripeInvalidRequestError $e) {
            /*if($request->ajax()){
                 return response()->json(['error' => $e->getMessage()], 500);
            }else{
                return back()->with('flash_error',$e->getMessage());
            }*/
        } catch(Exception $e) {
            /*if($request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 500);
            } else {
                return back()->with('flash_error', $e->getMessage());
            }*/
        }
    }
    
    /**
     * add wallet money for user.
     *
     * @return \Illuminate\Http\Response
     */
    public function trip_payment($id,$user_id){

        $update_user = User::find($user_id);
        $cards = Card::where('user_id',$user_id)->where('is_default',1)->first();
        $UserRequest = UserRequest::find($id);
        $RequestPayment = UserRequestPayment::where('request_id',$UserRequest->id)->first();

        if($update_user->email !=null){
          $user_email = $update_user->email;
        }else{
          $user_email = Setting::get('contact_email');
        }

        if($UserRequest->payment_mode == 'CARD'){
          if($RequestPayment->total !=0.00){
              try{
                $StripeCharge = $RequestPayment->total * 100;
                Stripe::setApiKey(Setting::get('stripe_secret_key'));
                $Charge = Charge::create(array(
                      "amount" => $StripeCharge,
                      "currency" => "usd",
                      "customer" => $update_user->stripe_cust_id,
                      "card" => $cards->card_id,
                      "description" => "Payment Charge for ".$user_email." ".$RequestPayment->request_id,
                      "receipt_email" => $user_email
                    ));

                $RequestPayment->payment_id = $Charge["id"];
                $RequestPayment->payment_mode = 'CARD';
                $RequestPayment->save();
                
                $UserRequest->paid = 1;
                $UserRequest->save();

                $update_user->due_balance = 0;
                $update_user->due_trip = null;
                $update_user->save();
                //sending push on adding wallet money
                (new SendPushNotification)->AutoPay($update_user->id,currency($RequestPayment->total));
             

              } catch(StripeInvalidRequestError $e) {

                $update_user->due_balance = $RequestPayment->total;
                $update_user->due_trip = $UserRequest->id;
                $update_user->save();

                $UserRequest->paid = 1;
                $UserRequest->save();

                  /*if($request->ajax()){
                       return response()->json(['error' => $e->getMessage()], 500);
                  }else{
                      return back()->with('flash_error',$e->getMessage());
                  }*/
              } catch(Exception $e) {

                $update_user->due_balance = $RequestPayment->total;
                $update_user->due_trip = $UserRequest->id;
                $update_user->save();

                $UserRequest->paid = 1;
                $UserRequest->save();

                  /*if($request->ajax()) {
                      return response()->json(['error' => $e->getMessage()], 500);
                  } else {
                      return back()->with('flash_error', $e->getMessage());
                  }*/
              }
          } 
        }
    }    

     public function trip_payment_tips($id,$user_id,$tip_fare){

        $update_user = User::find($user_id);
        $cards = Card::where('user_id',$user_id)->where('is_default',1)->first();
        $UserRequest = UserRequest::find($id);
        $RequestPayment = UserRequestPayment::where('request_id',$UserRequest->id)->first();

        if($update_user->email !=null){
          $user_email = $update_user->email;
        }else{
          $user_email = Setting::get('contact_email');
        }

        if($UserRequest->payment_mode == 'CARD'){
          
              try{
                $StripeCharge = $tip_fare * 100;
                Stripe::setApiKey(Setting::get('stripe_secret_key'));
                $Charge = Charge::create(array(
                      "amount" => $StripeCharge,
                      "currency" => "usd",
                      "customer" => $update_user->stripe_cust_id,
                      "card" => $cards->card_id,
                      "description" => "Tip Payment Charge for ".$user_email." ".$RequestPayment->request_id,
                      "receipt_email" => $user_email
                    ));

              //sending push on adding wallet money
                (new SendPushNotification)->AutoPay($update_user->id,currency($RequestPayment->total));
             

              } catch(StripeInvalidRequestError $e) {

                $update_user->due_balance = $tip_fare;
                $update_user->due_trip = $UserRequest->id;
                $update_user->save();

                $UserRequest->paid = 1;
                $UserRequest->save();

                  /*if($request->ajax()){
                       return response()->json(['error' => $e->getMessage()], 500);
                  }else{
                      return back()->with('flash_error',$e->getMessage());
                  }*/
              } catch(Exception $e) {

                $update_user->due_balance = $tip_fare;
                $update_user->due_trip = $UserRequest->id;
                $update_user->save();

                $UserRequest->paid = 1;
                $UserRequest->save();

                  /*if($request->ajax()) {
                      return response()->json(['error' => $e->getMessage()], 500);
                  } else {
                      return back()->with('flash_error', $e->getMessage());
                  }*/
              }
          
        }
    }    

    /**
     * setting stripe.
     *
     * @return \Illuminate\Http\Response
     */
    public function payment_mode(Request $request){
    
        $cash = Setting::get('CASH');
        $card = Setting::get('CARD');
        $wallet = Setting::get('WALLET');
        return response()->json(['cash' => $cash,'card'=>$card,'wallet' => $wallet]);
    }
}
?>
