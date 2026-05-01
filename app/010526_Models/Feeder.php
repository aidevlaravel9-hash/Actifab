<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feeder extends Model
{
    protected $table = 'feeder';
    protected $primaryKey = 'id';

    protected $fillable =
    [
        'feeder_id',
        'panel_id',
        'section_id',
        'height',
        'width',
        'feeder_name',
        'f_category_id',
        'f_subtype_id',
        'f_type_id',
        'door_cover',
        'lock_position',
        'customer_remarks',
        'created_at',
        'updated_at'
    ];

    public $timestamps = true;

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
    public function FeederCategory()
    {
        return $this->belongsTo(FeederCategory::class, 'f_category_id', 'feeder_category_id');
    }

    public function FeederType()
    {
        return $this->belongsTo(FeederType::class, 'f_type_id', 'feeder_type_id');
    }

    public function FeederSubType()
    {
        return $this->belongsTo(FeederSubType::class, 'f_subtype_id', 'feeder_sub_type_id');
    }
}
