<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class GlandPlateThickness extends Model
{
    protected $table = 'gland_plate_thickness';
    protected $primaryKey = 'gland_plate_thickness_id';


    protected $fillable = [
        'gland_plate_thickness',
        'iStatus'
    ];


    public $timestamps = true;
}
