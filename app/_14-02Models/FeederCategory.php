<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeederCategory extends Model
{
    protected $table = 'feeder_category_master';
    protected $primaryKey = 'feeder_category_id';

    protected $fillable = ['feeder_category_name', 'iStatus'];

    public $timestamps = true;
}
