<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $table = 'section';
    protected $primaryKey = 'id';

    protected $fillable = ['panel_id', 'section_id', 'width', 'section_name'];

    public $timestamps = true;

    public function panel()
    {
        return $this->belongsTo(ProjectPanelBoard::class, 'panel_id');
    }

    public function feeders()
    {
        return $this->hasMany(Feeder::class);
    }
}
