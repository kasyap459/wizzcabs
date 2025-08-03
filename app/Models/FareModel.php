<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FareModel extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country_id',
        'service_type_id',
        't1_stime',
        't2_stime',
        't3_stime',
        't4_stime',
        't1_etime',
        't2_etime',
        't3_etime',
        't4_etime',
        't1_base',
        't2_base',
        't3_base',
        't4_base',
        't1_base_dist',
        't2_base_dist',
        't3_base_dist',
        't4_base_dist',
        't1_distance',
        't2_distance',
        't3_distance',
        't4_distance',
        't1_minute',
        't2_minute',
        't3_minute',
        't4_minute',
        't1_waiting',
        't2_waiting',
        't3_waiting',
        't4_waiting',
        't1_cancel',
        't2_cancel',
        't3_cancel',
        't4_cancel',

        't1_s_stime',
        't2_s_stime',
        't3_s_stime',
        't4_s_stime',
        't1_s_etime',
        't2_s_etime',
        't3_s_etime',
        't4_s_etime',
        't1_s_base',
        't2_s_base',
        't3_s_base',
        't4_s_base',
        't1_s_base_dist',
        't2_s_base_dist',
        't3_s_base_dist',
        't4_s_base_dist',
        't1_s_distance',
        't2_s_distance',
        't3_s_distance',
        't4_s_distance',
        't1_s_minute',
        't2_s_minute',
        't3_s_minute',
        't4_s_minute',
        't1_s_waiting',
        't2_s_waiting',
        't3_s_waiting',
        't4_s_waiting',
        't1_base_wait',
        't2_base_wait',
        't3_base_wait',
        't4_base_wait',
        't1_s_cancel',
        't2_s_cancel',
        't3_s_cancel',
        't4_s_cancel',
        's1_enable',
        's2_enable',
        's1_stime',
        's2_stime',
        's1_etime',
        's2_etime',
        't1s_base_wait',
        't2s_base_wait',
        't3s_base_wait',
        't4s_base_wait',
        's1_percent',
        's2_percent',
        's1_waiting',
        's2_waiting',
        's3_waiting',
        's4_waiting'
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
    public function country()
    {
        return $this->hasOne('App\Models\Country','countryid');
    }
}
