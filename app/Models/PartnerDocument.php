<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerDocument extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'partner_id','document_id','url','unique_id','status','expires_at'
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
    public function partner()
    {
        return $this->belongsTo('App\Models\Partner');
    }
    /**
     * The services that belong to the user.
     */
    public function document()
    {
        return $this->belongsTo('App\Models\CarrierDocList');
    }
}
