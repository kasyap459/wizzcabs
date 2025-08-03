<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Builder;
use Auth;

class UserRequest extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admin_id',
        'booking_id',
        'user_name',
        'user_mobile',
        'guest',
        'user_id',
        'corporate_id',
        'group_id',
        'provider_id',
        'current_provider_id',
        'partner_id',
        'service_type_id',
        'vehicle_id',
        'hotel_id',
        'fare_type',
        'estimated_fare',
        'status',
        'trip_status',
        'push',
        'cancelled_by',
        'cancel_reason',
        'cancel_request',
        'cancel_status',
        'booking_by',
        'payment_mode',
        'paid',
        'distance',
        'minutes',
        's_address',
        's_latitude',
        's_longitude',
        'd_address',
        'd_latitude',
        'd_longitude',
        'stop1_latitude',
        'stop1_longitude', 
        'stop1_address', 
        'stop2_latitude', 
        'stop2_longitude', 
        'stop2_address', 
        'message',
        'comment',
        'handicap',
        'pet',
        'wagon',
        'booster',
        'child_seat',
        'fixed_rate',
        'passenger_count',
        'luggage',
        'ladies_only',
        'assigned_at',
        'schedule_at',
        'accepted_at',
        'started_at',
        'finished_at',
        'waiting_time',
        'payment_update',
        'user_rated',
        'provider_rated',
        'user_notes',
        'user_rates',
        'use_wallet',
        'surge',
        'completed_by',
        'base_fare',
        'km_price',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        // 'created_at', 'updated_at'
    ];
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'assigned_at',
        'schedule_at',
        'started_at',
        'finished_at',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        if(Auth::guard('admin')->user()){
            if(Auth::guard('admin')->user()->admin_type !=0){
                static::addGlobalScope('default', function (Builder $builder) {
                    $builder->where('admin_id', '=', Auth::guard('admin')->user()->id);
                });
            }
        }
        if(Auth::guard('dispatcher')->user()){
            if(Auth::guard('dispatcher')->user()->admin_id !=null){
                static::addGlobalScope('default', function (Builder $builder) {
                    $builder->where('admin_id', '=', Auth::guard('dispatcher')->user()->admin_id);
                });
            }
        }

        if(Auth::guard('account')->user()){
            if(Auth::guard('account')->user()->admin_id !=null){
                static::addGlobalScope('default', function (Builder $builder) {
                    $builder->where('admin_id', '=', Auth::guard('account')->user()->admin_id);
                });
            }
        }
    }
    
    /**
     * ServiceType Model Linked
     */
    public function service_type()
    {
        return $this->belongsTo('App\Models\ServiceType');
    }
    /**
     * UserRequestRating Model Linked
     */
    public function rating()
    {
        return $this->hasOne('App\Models\UserRequestRating', 'request_id');
    }

    /**
     * UserRequestRating Model Linked
     */
    public function filter()
    {
        return $this->hasMany('App\Models\RequestFilter', 'request_id');
    }

    public function currentprovider()
    {
        return $this->belongsTo('App\Models\Provider', 'current_provider_id');
    }
    /**
     * The user who created the request.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * The provider assigned to the request.
     */
    public function provider()
    {
        return $this->belongsTo('App\Models\Provider');
    }
    /**
     * The user who created the request.
     */
    public function vehicle()
    {
        return $this->belongsTo('App\Models\Vehicle');
    }    
    /**
     * UserRequestPayment Model Linked
     */
    public function payment()
    {
        return $this->hasOne('App\Models\UserRequestPayment', 'request_id');
    }
}
