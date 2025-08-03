<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Builder;
use Auth;

class Vehicle extends Model
{
   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admin_id',
        'vehicle_name',
        'vehicle_no',
        'seat',
        'partner_id',
        'service_type_id',
        'vehicle_owner',
        'vehicle_model',
        'vehicle_manufacturer',
        'manufacturing_year',
        'vehicle_brand',
        'vehicle_color',
        'insurance_no',
        'insurance_exp',
        'handicap_access',
        'travel_pet',
        'station_wagon',
        'booster_seat',
        'booster_count',
        'child_seat',
        'vehicle_image',
        'preference',
        'location_id',
        'status',
        'custom_field1',
        'custom_field2',
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
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {

      parent::boot();
      if(Auth::guard('admin')->user()){
        if(Auth::guard('admin')->user()->admin_type !=0){
              static::addGlobalScope('default', function (Builder $builder) {
                $builder->where('admin_id', '=', Auth::guard('admin')->user()->id);
              });
        }
      }
    }

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
    public function location()
    {
        return $this->belongsTo('App\Models\Location');
    }
    /**
     * The services that belong to the user.
     */
    public function partner()
    {
        return $this->belongsTo('App\Models\Partner');
    }

}
