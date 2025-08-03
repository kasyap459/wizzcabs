<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavouriteLocation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'address',
        'lat',
        'lng',
        'house_no',
        'land_mark',
        'save_as',
        'is_default',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
         'created_at', 'updated_at'
    ];
}
