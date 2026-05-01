<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectPanelBoard extends Model
{
    protected $table = 'project_panel_boards';
    protected $primaryKey = 'id';


    protected $fillable = [
        'project_id',
        'panel_board_job_no',
        'panel_board_name',
        'system_current_rating_id',
        'no_of_poles_id',
        'type_of_panel_id',
        'operating_voltage_id',
        'form_type_id',
        'panel_access_id',
        'ip_protection_id',
        'frame_height',
        'frame_width',
        'frame_depth',
        'thickness',
        'plinth_height',
        'lock_system_id',
        'gland_plate_thickness_id',
        'certification_id',
        'busbar_position_id',
        'outgoing_cable_position_id',
        'plinth_type_id'
    ];

    public function sections()
    {
        return $this->hasMany(Section::class, 'panel_id');
    }
    public function panelType()
    {
        return $this->belongsTo(PanelType::class, 'type_of_panel_id', 'panel_type_id');
    }
}
