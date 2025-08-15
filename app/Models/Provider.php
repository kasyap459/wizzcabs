<?php

namespace App\Models;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Notifications\ProviderResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Builder;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provider extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admin_id',
        'name',
        'email',
        'password',
        'dial_code',
        'mobile',
        'password',
        'gender',
        'country_id',
        'avatar',
        'rating',
        'account_status',
        'status',
        'partner_id',
        'service_type_id',
        'taxi_type',
        'mapping_id',
        'trip_id',
        'latitude',
        'longitude',
        'address',
        'otp',
        'stripe_cust_id',
        'due_balance',
        'login_status',
        'active_from',
        'ride_from',
        'login_at',
        'logout_at',
        'login_by',
        'social_unique_id',
        'allowed_service',
        'language',
        'acc_no',
        'license_no',
        'license_expire',
        'wallet_balance',
        'custom_field1',
        'custom_field2',
        'expires_at',
        'active_time',
        'con_earnings',
        'cash_earnings',
        'total_earnings',

        'ptd_number'

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','updated_at',
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
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ProviderResetPassword($token));
    }
    /**
     * The services that belong to the user.
     */
    public function partner()
    {
        return $this->belongsTo('App\Models\Partner');
    }

    /**
     * The services that belong to the user.
     */
    public function service()
    {
        return $this->belongsTo('App\Models\ServiceType', 'service_type_id');
    }
    /**
     * The services that belong to the user.
     */
    public function vehicle()
    {
        return $this->belongsTo('App\Models\Vehicle', 'mapping_id');
    }
    /**
     * The services that belong to the user.
     */
    public function device()
    {
        return $this->hasOne('App\Models\ProviderDevice');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * The services accepted by the provider
     */
    public function accepted()
    {
        return $this->hasMany('App\Models\UserRequest','provider_id')
                    ->where('status','!=','CANCELLED');
    }

    /**
     * service cancelled by provider.
     */
    public function cancelled()
    {
        return $this->hasMany('App\Models\UserRequest','provider_id')
                ->where('status','CANCELLED');
    }

    public function totalrequest()
    {
        return $this->hasMany('App\Models\UserRequest','provider_id');
    }

    public function pending_documents()
    {
        return $this->hasMany('App\Models\ProviderDocument')->where('status', 'ASSESSING')->count();
    }
}
