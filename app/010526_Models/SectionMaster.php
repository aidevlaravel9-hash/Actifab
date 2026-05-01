<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SectionMaster extends Model
{
    protected $table = 'section_master';
    protected $primaryKey = 'section_id';

    protected $fillable = ['section_name' , 'iStatus'];

    public $timestamps = true;
}
