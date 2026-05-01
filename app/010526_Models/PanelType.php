<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PanelType extends Model
{
    protected $table = 'panel_type_master';
    protected $primaryKey = 'panel_type_id';

    protected $fillable = ['panel_type', 'iStatus'];

    public $timestamps = true;
}
