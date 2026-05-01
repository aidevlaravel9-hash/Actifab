<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PanelBoardColour extends Model
{
    protected $table = 'panel_board_colour';
    protected $primaryKey = 'panel_board_colour_id';
    protected $fillable = ['panel_board_colour', 'iStatus'];
    public $timestamps = true;
}
