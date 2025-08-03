<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use App\Scopes\AncientScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Passport\HasApiTokens;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
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
        'dial_code',
        'first_name',
        'last_name',
        'mobile',
        'password',
        'gender',
        'country_id',
        'corporate_user_id',
        'department_id',
        'report_id',
        'picture',
        'device_token',
        'device_id',
        'device_type',
        'login_by',
        'social_unique_id',
        'latitude',
        'longitude',
        'trip_id',
        'stripe_cust_id',
        'wallet_balance',
        'due_balance',
        'due_trip',
        'rating',
        'status',
        'corporate_status',
        'refferal_code',
        'refferal_by',
        'fav_provider',
        'fav_service_type',
        'custom_field1',
        'custom_field2',
        'expires_at'
      ];
  
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    protected static function boot()
    {

      parent::boot();
      if(Auth::guard('admin')->user()){
        if(Auth::guard('admin')->user()->admin_type !=0){
              static::addGlobalScope('ancient', function (Builder $builder) {
                $builder->where('admin_id', '=', Auth::guard('admin')->user()->id);
              });
        }
      }
      if(Auth::guard('dispatcher')->user()){
          if(Auth::guard('dispatcher')->user()->admin_id !=null){
              static::addGlobalScope('ancient', function (Builder $builder) {
                  $builder->where('admin_id', '=', Auth::guard('dispatcher')->user()->admin_id);
              });
          }
      }

    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */


    /**
     * The services that belong to the user.
     */
    public function corporate_user()
    {
        return $this->belongsTo('App\Models\CorporateUser');
    }

}
