<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class OutgoingCablePosition extends Model
{
    protected $table = 'outgoing_cable_position';
    protected $primaryKey = 'outgoing_cable_position_id';


    protected $fillable = [
        'outgoing_cable_position',
        'iStatus'
    ];


    public $timestamps = true;
}
