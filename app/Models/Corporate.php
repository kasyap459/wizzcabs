<?php

namespace App\Models;

use App\Notifications\CorporateResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Builder;
use Auth;

class Corporate extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admin_id','legal_name','display_name','email', 'password','dial_code','mobile','secondary_email','address','country_id','pan_no',
         'picture','status','notify_customer','latitude','longitude','zoom','expires_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
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
      
    }
    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CorporateResetPassword($token));
    }
}
