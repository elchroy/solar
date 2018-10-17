<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Panel extends Model
{
    protected $fillable = ['serial', 'longitude', 'latitude'];

    public static $fieldValidations = [
        'serial'    => 'required|unique:panels|size:15',
        'latitude'  => 'required|numeric|between:-90,90'
    ];

    public function oneHourElectricities()
    {
        return $this->hasMany('App\Models\OneHourElectricity');
    }
}
