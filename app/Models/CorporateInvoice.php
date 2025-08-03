<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class CorporateInvoice extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admin_id',
        'corporate_id',
        'invoice_id',
        'ride_count',
        'ride_total',
        'prev_payment',
        'prev_balance',
        'current_payment',
        'total',
        'paid',
        'balance',
        'ride_no',
        'from_date',
        'to_date'
    ];

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
        if(Auth::guard('dispatcher')->user()){
            if(Auth::guard('dispatcher')->user()->admin_id !=null){
                static::addGlobalScope('default', function (Builder $builder) {
                    $builder->where('admin_id', '=', Auth::guard('dispatcher')->user()->admin_id);
                });
            }
        }

    }

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
    public function corporate()
    {
        return $this->belongsTo('App\Models\Corporate');
    }
}
