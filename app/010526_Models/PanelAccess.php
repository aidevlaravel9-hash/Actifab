<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PanelAccess extends Model
{
    protected $table = 'panel_access';
    protected $primaryKey = 'panel_access_id';
    protected $fillable = ['panel_access', 'iStatus'];
    public $timestamps = true;
}
