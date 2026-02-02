<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpProtection extends Model
{
    protected $table = 'ip_protection';
    protected $primaryKey = 'ip_protection_id';
    protected $fillable = ['ip_protection', 'iStatus'];
    public $timestamps = true;
}
