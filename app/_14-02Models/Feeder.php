<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feeder extends Model
{
    protected $table = 'feeder';
    protected $primaryKey = 'id';

    protected $fillable = ['feeder_id', 'panel_id', 'section_id', 'height', 'width', 'feeder_name', 'created_at', 'updated_at'];

    public $timestamps = true;

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
