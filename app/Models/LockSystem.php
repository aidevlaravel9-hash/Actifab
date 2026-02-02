<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LockSystem extends Model
{
    protected $table = 'lock_system';
    protected $primaryKey = 'lock_system_id';
    protected $fillable = ['lock_system', 'iStatus'];
    public $timestamps = true;
}
