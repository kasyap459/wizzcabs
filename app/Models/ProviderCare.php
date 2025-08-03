<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderCare extends Model
{
    use HasFactory;

    protected $table = "provider_cares";

    protected $fillable = [
        'admin_id','ticket_id', 'user_id', 'provider_id','trip_id','provider_name','user_name','user_mobile','provider_mobile','status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

}
