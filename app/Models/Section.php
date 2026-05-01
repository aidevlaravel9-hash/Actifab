<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $table = 'section';
    protected $primaryKey = 'id';

    protected $fillable = ['panel_id', 'section_id', 'section_type_id', 'width', 'lock_position', 'section_name'];

    public $timestamps = true;

    public function panel()
    {
        return $this->belongsTo(ProjectPanelBoard::class, 'panel_id');
    }

    // public function feeders()
    // {
    //     return $this->hasMany(Feeder::class);
    // }

    public function feeders()
    {
        return $this->hasMany(Feeder::class, 'section_id');
    }
}
