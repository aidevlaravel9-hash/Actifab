<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeederType extends Model
{
    protected $table = 'feeder_type';
    protected $primaryKey = 'feeder_type_id';

    protected $fillable = ['feeder_type', 'iStatus'];

    public $timestamps = true;
}
