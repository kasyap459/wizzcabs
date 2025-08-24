<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderWallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'trip_id',
        'amount',
        'mode',
        'status',
        'cashout_id',
        'transaction_type'
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
     * The user who created the request.
     */
    public function provider()
    {
        return $this->belongsTo('App\Models\Provider');
    }
 }
