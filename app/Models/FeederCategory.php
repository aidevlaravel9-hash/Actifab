<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeederCategory extends Model
{
    protected $table = 'feeder_category_master';
    protected $primaryKey = 'feeder_category_id';

    protected $fillable = ['feeder_category_name', 'section_master_id','image','iStatus'];

    public $timestamps = true;
    
    public function section()
{
    return $this->belongsTo(SectionMaster::class, 'section_master_id', 'section_id');
}
}
