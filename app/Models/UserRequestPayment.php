<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRequestPayment extends Model
{
   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'request_id',
        'payment_id',
        'payment_mode',
        'currency',
        'flat_fare',
        'base_fare',
        'distance_fare',
        'commision',
        'earnings',
        'revenue',
        'min_fare',
        'waiting_fare',
        'stop_waiting_fare',
        'vat',
        'discount',
        'toll',
        'extra_fare',
        'extra_desc',
        'cash',
        'total',
        'due_pending',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'status', 'password', 'remember_token', 'created_at', 'updated_at'
    ];

    /**
     * The services that belong to the user.
     */
    public function request()
    {
        return $this->belongsTo('App\Models\UserRequest');
    }

    /**
     * The services that belong to the user.
     */
    public function provider()
    {
        return $this->belongsTo('App\Models\Provider');
    }
}
