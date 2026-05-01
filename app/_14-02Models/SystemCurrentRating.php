<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemCurrentRating extends Model
{
    protected $table = 'system_current_rating';
    protected $primaryKey = 'system_current_rating_id';

    protected $fillable = ['rating', 'iStatus'];
    public $timestamps = true;
}
