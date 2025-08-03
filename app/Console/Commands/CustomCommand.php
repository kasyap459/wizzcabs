<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Http\Controllers\SendPushNotification;
use Carbon\Carbon;
use Log;

use App\Models\User;
use App\Models\UserRequest;
use App\Models\Provider;
use Setting;
use App\Models\RequestFilter;
use App\Models\NotifiedDriver;

class CustomCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:rides';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $notify_time =Setting::get('notification_time');
        $now = Carbon::now()->addMinutes($notify_time);
        
        $UserRequests = UserRequest::whereIn('status',['SCHEDULED','ACCEPTED'])
                    ->where('schedule_at','<', $now)
                    ->select('id','created_at','provider_id','schedule_at','status','cancelled_by','cancel_reason','booking_by','paid','push','assigned_at','partner_id')
                    ->orderBy('created_at','desc')
                    ->get();

        foreach ($UserRequests as $key => $UserRequest) {

            if($UserRequest->push =='AUTO' && $UserRequest->provider_id ==0){
                $UserRequest->assigned_at = $UserRequest->schedule_at;
                $UserRequest->save();
            }

            //Send push notification for scheduled trip accepted drivers
            if($UserRequest->push =='AUTO' && $UserRequest->provider_id !=0){
                (new SendPushNotification)->PushMessageToProvider($UserRequest->provider_id,trans('api.push.schedule_start'));
                $UserRequest->push='SEND';
                $UserRequest->assigned_at = $UserRequest->schedule_at;
                $UserRequest->save();
            }
            //if scheduled trip not started before 3 min, change this trip to ride now
            $now = Carbon::now()->addMinutes(10);
            if($UserRequest->schedule_at !=Null && $now > $UserRequest->schedule_at){
                $UserRequest->provider_id = 0;                
                $UserRequest->cancelled_by ="NONE";
                $UserRequest->cancel_reason ="";              
                $UserRequest->paid =0;               
                $UserRequest->assigned_at = $UserRequest->schedule_at;
                $UserRequest->push = 'AUTO';                
                $UserRequest->status ="SEARCHING";
                $UserRequest->save();
            }       
        }       
       

        $Provider8 = Provider::withTrashed()->where('status','=','active')->get();
        $offline_time =Setting::get('offline_time');

        if($Provider8)
        { 
            foreach($Provider8 as $Provider00)
            {
                $active_time=$Provider00->active_time;
                $newtimestamp = date('Y-m-d H:i:s',strtotime($active_time . '+ '.$offline_time.' minutes')); 
                
                if(date('Y-m-d H:i:s') > $newtimestamp)
                {
                    $Provider77 = Provider::withTrashed()->where('id','=',$Provider00->id)->first();
                    $Provider77->status='offline';
                    $Provider77->save();
                }
            }
        }

        $Requests = UserRequest::where(function ($query) {$query->whereIn('status', ['STARTED', 'ARRIVED','PICKEDUP','DROPPED']);
        })->where('created_at', '<', date('Y-m-d'))
                    ->orderBy('created_at','desc')
                    ->get();
        if($Requests->count() >0){
            foreach ($Requests as $key => $Request) {
                $Request->status = 'CANCELLED';
                $Request->save();                
                $user=User::where('id','=',$Request->user_id)->where('trip_id','=',$Requests->id)->first();
                if($user)
                {
                    $user->trip_id=0;
                    $user->save();
                }
                $pros=Provider::where('id','=',$Request->provider_id)->where('trip_id','=',$Requests->id)->first();
                if($pros)
                {
                    $pros->trip_id=0;
                    $pros->save();
                }
            }
        }        

        $Requests1 = UserRequest::select('id','user_id')->where(function ($query) {$query->whereIn('status', ['STARTED', 'ARRIVED','PICKEDUP','DROPPED']);
        })->orderBy('created_at','desc')->get();
        if(count($Requests1) >0){
            foreach($Requests1 as $Request3)
            {
                $user=User::where('id','=',$Request3->user_id)->where('trip_id','=',0)->first();
                if($user)
                {
                    $user->trip_id=$Request3->id;
                    $user->save();
                }

                $pros=Provider::where('id','=',$Request3->provider_id)->first();
                if($pros)
                {
                    $pros->status='riding';
                    $pros->trip_id=$Request3->id;
                    $pros->save();
                }
            }
        }

         //Back to schedule if driver reject only schecdule trip 
         $changetoschedule = UserRequest::where('status','CANCELLED')
         ->where('schedule_at','!=', NULL)
         ->where('cancelled_by','=','PROVIDER')
         ->select('id','created_at','provider_id','schedule_at','status','cancelled_by','cancel_reason','booking_by','paid','push','assigned_at','partner_id')
         ->orderBy('created_at','desc')
         ->get();

     foreach ($changetoschedule as $key => $Request) {
     $Request->status = 'SCHEDULED';
     $Request->cancel_reason = '';
     $Request->save();
 } 

        //if no driver accepted trip, change to cancel state
        $close_time =Setting::get('close_time');
        $Requests = UserRequest::where(function ($query) {$query->whereIn('status', ['SEARCHING']);
        })->where('provider_id','=', 0)
                    ->where('assigned_at','<', Carbon::now()->subMinutes($close_time))
                    ->select('id','created_at','assigned_at','provider_id','schedule_at','status','cancel_reason','user_id')
                    ->orderBy('created_at','desc')
                    ->get();
        if($Requests->count() >0){
            foreach ($Requests as $key => $Request) {
                $Request->status = 'CANCELLED';
                $Request->cancel_reason = 'Driver Not Accepted';
                $Request->save(); 
                RequestFilter::where('request_id', $Request->id)->delete();
                //NotifiedDriver::where('trip_id', $Request->id)->delete();
            }
        }

        //Change another driver
        $RequestFilter = RequestFilter::with('request')->whereHas('request', function($query){
            $query->where('status','=', 'SEARCHING');
            $query->where('provider_id','=',0);
        })->get();
        if(count($RequestFilter) > 0){
            $Timeout = Setting::get('provider_select_timeout', 180);
            foreach ($RequestFilter as $key => $filter) {
                $time_left_to_respond = $Timeout - (time() - strtotime($filter->request->assigned_at));
                if($time_left_to_respond < 0) {
                    RequestFilter::where('provider_id', $filter->request->current_provider_id)
                                ->where('request_id', $filter->request_id)
                                ->delete();

                    $trip = UserRequest::where('id','=',$filter->request_id)
                        ->where('status','SEARCHING')
                        ->where('provider_id','=',0)
                        ->first();
                    if($trip !=null){
                        $newFilter = RequestFilter::where('request_id','=', $filter->request_id)
                                ->join('providers','request_filters.provider_id','=','providers.id')
                                ->where('providers.status','=','active')
                                ->select('request_filters.*','providers.status as driver_status')
                                ->first();
                        if($newFilter !=null){
                            $trip->assigned_at = Carbon::now();
                            $trip->current_provider_id = $newFilter->provider_id;
                            $trip->save();
                            (new SendPushNotification)->IncomingTrip($newFilter->provider_id);
                        }else{
                            RequestFilter::where('request_id', $trip->id)->delete();
                        }
                    }else{
                        RequestFilter::where('request_id', $trip->id)->delete();
                    }
                }
            }
        }
    }
}