<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoOfPole extends Model
{
    protected $table = 'no_of_poles';
    protected $primaryKey = 'no_of_poles_id';

    protected $fillable = ['no_of_poles', 'iStatus'];
    public $timestamps = true;
}
