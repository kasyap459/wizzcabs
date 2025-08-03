<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderDocument extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'provider_id','document_id','url','unique_id','status','expires_at'
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
    /**
     * The services that belong to the user.
     */
    public function document()
    {
        return $this->belongsTo('App\Models\DriverDocList');
    }
}
