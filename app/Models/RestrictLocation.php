<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestrictLocation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'location_id','restrict_area','s_time','e_time','status','restrict_type'
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
    public function location()
    {
        return $this->belongsTo('App\Models\Location');
    }
}
