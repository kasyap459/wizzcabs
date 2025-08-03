<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Provider;
use App\Models\MemberNotification;
use App\Http\Controllers\SendPushNotification;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Exception;
use Setting;
use Twilio;

class MailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $Users = User::select('id','first_name','email')->get();
            $pushdatas = MemberNotification::where('notification_type','mail')->where('member','passenger')->orderBy('created_at' , 'desc')->get();
            return view('admin.mailmessage.passenger-mail', compact('Users','pushdatas'));
        }
        catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function driver_index()
    {
        try{
            $Providers = Provider::select('id','name','email')->get();
            $pushdatas = MemberNotification::where('notification_type','mail')->where('member','driver')->orderBy('created_at' , 'desc')->get();
            return view('admin.mailmessage.driver-mail', compact('Providers','pushdatas'));
        }
        catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
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
            $message = $request->push_content;
            $users = array_unique($request->users);
            $title = $request->title;
            $name = array();
            foreach ($users as $user_id) {
                (new SendPushNotification)->PushMessageToUser($user_id,$message);
                $value = User::find($user_id);
                $name[] = $value->first_name.' '.$value->last_name;
            }

            $users = implode(', ', $name);
            MemberNotification::create([
                'person_id' => $value->id,
                'title' => $title,
                'message' => $message,
                'mobile_numbers' => $users,
                'member' =>'passenger',
                'notification_type' =>'mail'
            ]);
            return back()->with('flash_success', 'Mail Sent Successfully!');
       } catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong! (Check All Passenger Details)');
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function driver_store(Request $request)
    {
        try{
            $message = $request->push_content;
            $providers = array_unique($request->Providers);
            $name = array();
            foreach ($providers as $provider_id) {
                $value = Provider::find($provider_id);
                $value->content = $message;
                $value->subject = 'Information';
                (new SendPushNotification)->sendMailUser($value);
                $name[] = $value->name;
            }

            $providers = implode(', ', $name);
            MemberNotification::create([
                'person_id' => $value->id,
                'message' => $message,
                'mobile_numbers' => $providers,
                'member' =>'driver',
                'notification_type' =>'mail'
            ]);
            return back()->with('flash_success', 'Mail Sent Successfully!');
            
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
    public function destroy(Request $request)
    {
        try{
            $checkboxes = $request->input('checkbox');
            $count = count($checkboxes);
            if($count == 0){
                return back()->with('flash_error', 'Please Select Row to Delete');
            }
            foreach($checkboxes as $id) {
                MemberNotification::where('id', $id)->delete();
            }
            return back()->with('flash_success', 'Deleted Successfully!');
        }
        catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
    }
}
