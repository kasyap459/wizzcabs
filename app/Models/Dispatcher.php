<?php

namespace App\Models;

use App\Notifications\DispatcherResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Builder;
use Auth;

class Dispatcher extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admin_id','name', 'email', 'password','mobile','dial_code','country_id','partner_id','status','dispatch_address','dispatch_lat','dispatch_long','dispatch_zoom','expires_at'
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
      
    }
    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new DispatcherResetPassword($token));
    }
    /**
     * The services that belong to the user.
     */
    public function partner()
    {
        return $this->belongsTo('App\Models\Partner');
    }
}
