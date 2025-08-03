<?php

namespace App\Http\Controllers\Resource;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;

use App\Models\User;
use App\Models\Card;
use Exception;
use App\Models\Admin;
use Auth;
use Setting;

class CardResource extends Controller
{   

    // public function __construct(Request $request)
    // {
    //     //$this->middleware('admin');

        
      
    //     $this->middleware(function ($request, $next) {
    //     $this->id = Auth::user()->id;
    //     $this->email = Auth::user()->email;
    //     $this->admin_type = Auth::user()->admin_type;
    //     $this->admin_id = Auth::user()->admin_id;
    //     //dd($this->admin_type);
    //     if($this->admin_id == null){
            
    //          $admin = Admin::where('id','=',$this->id)->first();
           
    //          if($admin->admin_type != 0 && $admin->time_zone != null){
    //              date_default_timezone_set($admin->time_zone);
                
    //          }
    //      } else {

    //         $admin = Admin::where('id','=',$this->admin_id)->first();
         
    //          if($admin->admin_type != 0 && $admin->time_zone != null){
    //              date_default_timezone_set($admin->time_zone);
                 
    //          }
    //      }
            
    //     return $next($request);
    // });
        

    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{

            $cards = Card::where('user_id',Auth::user()->id)->orderBy('created_at','desc')->get();
            return $cards; 

        } catch(Exception $e){
            return response()->json(['error' => $e], 500);
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
    public function store(Request $request)
    {
        $this->validate($request,[
                'stripe_token' => 'required'
            ]);

        try{
        
                $this->set_stripe();
                $user = User::find(Auth::user()->id);
                if(Auth::user()->stripe_cust_id == null)
                {
                    $customer = \Stripe\Customer::create([
                        'email' => Auth::user()->email
                    ]);
                  
                    $stripe = new \Stripe\StripeClient(Setting::get('stripe_secret_key'));
                  
                     $card =  $stripe->customers->createSource(
                        $customer['id'],
                        ['source' => $request->stripe_token]
                      );

                      $user=User::where('id',Auth::user()->id)->update(['stripe_cust_id' => $customer['id']]);
                }else{
    
                $stripe = new \Stripe\StripeClient(Setting::get('stripe_secret_key'));
                 $card =  $stripe->customers->createSource(
                   Auth::user()->stripe_cust_id,
                   ['source' => $request->stripe_token]
                 );
                }

                // $customer = \Stripe\Customer::retrieve($customer_id);
                // $card = $customer->sources->create(["source" => $request->stripe_token]);

                $exist = Card::where('user_id',Auth::user()->id)
                                ->where('last_four',$card['last4'])
                                ->where('brand',$card['brand'])
                                ->count();

                if($exist == 0){

                    $create_card = new Card;
                    $create_card->user_id = Auth::user()->id;
                    $create_card->card_id = $card['id'];
                    $create_card->last_four = $card['last4'];
                    $create_card->brand = $card['brand'];
                    $create_card->save();

                }else{
                    if($request->is('api/*')){
                        return response()->json(['message' => 'Card Already Added','success' =>0]); 
                    }else{
                        return back()->with('flash_success','Card Already Added');
                    }
                }

            if($request->is('api/*')){
                return response()->json(['message' => 'Card Added','success' => 1]); 
            }else{
                return back()->with('flash_success','Card Added');
            }  
            

        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage(),'success' => 0], 500);
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
    public function destroy(Request $request)
    {

        $this->validate($request,[
                'card_id' => 'required|exists:cards,card_id,user_id,'.Auth::user()->id,
            ]);

        try{


         
            $stripe = new \Stripe\StripeClient(
                Setting::get('stripe_secret_key')
              );

              $stripe->customers->deleteSource(Auth::user()->stripe_cust_id,$request->card_id,
              );


            Card::where('card_id',$request->card_id)->delete();

            if($request->is('api/*')){
                return response()->json(['message' => 'Card Deleted','success' => 1]); 
            }else{
                return back()->with('flash_success','Card Deleted');
            }
            

        } catch(Exception $e){
            
            return response()->json(['error' => $e->getMessage(),'success' => 0], 500);
          
        }
    }

    /**
     * setting stripe.
     *
     * @return \Illuminate\Http\Response
     */
    public function set_stripe(){
        return \Stripe\Stripe::setApiKey(Setting::get('stripe_secret_key'));
    }
    /**
     * setting stripe.
     *
     * @return \Illuminate\Http\Response
     */
    public function customer_key(Request $request){
    
        $key = Setting::get('stripe_publishable_key');

        return response()->json(['publishable_key' => $key]);
    }
    /**
     * Get a stripe customer id.
     *
     * @return \Illuminate\Http\Response
     */
    public function customer_id()
    {
        if(Auth::user()->stripe_cust_id != null){

            return Auth::user()->stripe_cust_id;

        }else{

            try{

                $stripe = $this->set_stripe();

                $customer = \Stripe\Customer::create([
                    'email' => Auth::user()->email,
                ]);

                User::where('id',Auth::user()->id)->update(['stripe_cust_id' => $customer['id']]);
                return $customer['id'];

            } catch(Exception $e){
                return $e;
            }
        }
    }

}
