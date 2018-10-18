<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Panel extends Model
{
    protected $fillable = ['serial', 'longitude', 'latitude'];

    public static $fieldValidations = [
        'serial'    => 'required|unique:panels|size:16',
        'latitude'  => 'required|numeric|between:-90,90',
        'longitude'  => 'required|numeric|between:-180,180'
    ];

    public function oneHourElectricities()
    {
        return $this->hasMany('App\Models\OneHourElectricity');
    }
}
