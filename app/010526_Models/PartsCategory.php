<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartsCategory extends Model
{
    protected $table = 'parts_category';
    protected $primaryKey = 'parts_id';

    protected $fillable = ['parts_category_name', 'iStatus'];

    public $timestamps = true;
}
