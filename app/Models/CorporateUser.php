<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CorporateUser extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'corporate_id',
        'corporate_group_id',
        'emp_name',
        'emp_email',
        'emp_phone',
        'manager_email',
        'manager_name',
        'emp_code',
        'emp_brand',
        'emp_costcenter',
        'emp_desig',
        'emp_baseloc',
        'emp_gender',
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
     * The services that belong to the user.
     */
    public function corporate_group()
    {
        return $this->belongsTo('App\Models\CorporateGroup');
    }
    /**
     * The services that belong to the user.
     */
    public function user()
    {
        return $this->hasOne('App\Models\User');
    }
}
