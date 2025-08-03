<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CorporateGroup extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'corporate_id','group_name','payment_mode','ride_service_type','allowed_days','time_range'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
         'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $casts = [
        'ride_service_type' => 'array',
        'allowed_days' => 'array',
        'time_range' => 'array',
    ];
}
