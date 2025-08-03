<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderToken extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'provider_id',
        'used',
        'dial_code',
        'mobile',
        'email'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
       'created_at', 'updated_at'
    ];
}
