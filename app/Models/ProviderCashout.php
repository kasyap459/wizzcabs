<?php

namespace App\Models;

use App\Notifications\DispatcherResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Builder;
use Auth;

class ProviderCashout extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'provider_id','request_id','amount', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
   

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    
    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
    
     */
    public function provider()
    {
        return $this->belongsTo('App\Models\Provider');
    }
    
    /**
     * The services that belong to the user.
     * 
     * 
     */
    
}
