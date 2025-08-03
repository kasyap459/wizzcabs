<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerInvoice extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'partner_id',
        'invoice_id',
        'ride_count',
        'ride_total',
        'vat_percent',
        'prev_payment',
        'prev_balance',
        'current_payment',
        'total',
        'paid',
        'balance',
        'ride_no',
        'from_date',
        'to_date',
        'cash_total',
        'card_total',
        'commission_total',
        'carrier_total',
        'admin_pay',
        'carrier_pay',
        'commission_percent',
        'commission_vat_percent'
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
}
