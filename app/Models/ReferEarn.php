<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferEarn extends Model
{
    use HasFactory;

    protected $table = "refer_earn";

    protected $fillable = [
        'refer_id','earn_id', 'trip_id', 'used'
    ];

    
}
