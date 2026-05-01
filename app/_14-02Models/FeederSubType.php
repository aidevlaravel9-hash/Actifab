<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeederSubType extends Model
{
    protected $table = 'feeder_sub_type_master';
    protected $primaryKey = 'feeder_sub_type_id';

    protected $fillable = ['feeder_type_id', 'feeder_sub_type', 'image', 'iStatus'];

    public $timestamps = true;

    public function feederType()
    {
        return $this->belongsTo(FeederType::class, 'feeder_type_id');
    }
}
