<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoiFare extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'poi_s_addr',
        'poi_d_addr',
        'service_type_id',
        'reverse_loc',
        'status',
        't1_stime',
        't2_stime',
        't3_stime',
        't4_stime',
        't1_etime',
        't2_etime',
        't3_etime',
        't4_etime',
        't1_flat',
        't2_flat',
        't3_flat',
        't4_flat',
        't1_s_stime',
        't2_s_stime',
        't3_s_stime',
        't4_s_stime',
        't1_s_etime',
        't2_s_etime',
        't3_s_etime',
        't4_s_etime',
        't1_s_flat',
        't2_s_flat',
        't3_s_flat',
        't4_s_flat',
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
    public function service_type()
    {
        return $this->belongsTo('App\Models\ServiceType');
    }
    /**
     * The services that belong to the user.
     */
    public function location_source()
    {
        return $this->belongsTo('App\Models\Location','poi_s_addr');
    }
    public function location_dest()
    {
        return $this->belongsTo('App\Models\Location','poi_d_addr');
    }
}
