<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusbarPosition extends Model
{
    protected $table = 'busbar_position';
    protected $primaryKey = 'busbar_position_id';

    protected $fillable = [
        'busbar_position',
        'iStatus'
    ];

    public $timestamps = true;
}
