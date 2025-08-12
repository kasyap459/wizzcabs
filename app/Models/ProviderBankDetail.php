<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderBankDetail extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'provider_id',
        'account_holder_name',
        'bank_name',
        'branch_name',
        'bank_address',
        'account_number', 
        'iban',
        'swift_bic',
        'routing_number',
        'account_type'
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
     * The services that belong to the user.
     */
    public function provider()
    {
        return $this->belongsTo('App\Models\Provider');
    }
}
