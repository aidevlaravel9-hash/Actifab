<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeederType extends Model
{
    protected $table = 'feeder_type';
    protected $primaryKey = 'feeder_type_id';

    protected $fillable = ['feeder_type', 'feeder_category_id','section_master_id', 'iStatus'];

    public $timestamps = true;

    public function FeederCategory()
    {
        return $this->belongsTo(FeederCategory::class, 'feeder_category_id', 'feeder_category_id');
    }
    
    public function section()
    {
        return $this->belongsTo(SectionMaster::class, 'section_master_id', 'section_id');
    }
}
