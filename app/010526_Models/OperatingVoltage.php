<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperatingVoltage extends Model
{
    protected $table = 'operating_voltage';
    protected $primaryKey = 'operating_voltage_id';

    protected $fillable = ['operating_voltage', 'iStatus'];
    public $timestamps = true;
}
