<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class PlinthType extends Model
{
    protected $table = 'plinth_type';
    protected $primaryKey = 'plinth_type_id';


    protected $fillable = [
        'plinth_type',
        'iStatus'
    ];


    public $timestamps = true;
}
