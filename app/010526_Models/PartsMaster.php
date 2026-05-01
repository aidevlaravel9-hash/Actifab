<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartsMaster extends Model
{
    protected $table = 'parts_master';
    protected $primaryKey = 'parts_id';

    protected $fillable = ['parts_category_id', 'parts_name', 'part_amount', 'image', 'iStatus'];

    public $timestamps = true;

    public function category()
    {
        return $this->belongsTo(PartsCategory::class, 'parts_category_id');
    }
}
