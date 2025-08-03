<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCare extends Model
{
    use HasFactory;

    protected $table = "user_cares";

    protected $fillable = [
        'admin_id','ticket_id', 'user_id', 'provider_id','trip_id','user_name','enquiry','mobile','status'
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
