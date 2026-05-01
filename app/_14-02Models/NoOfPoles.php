<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoOfPoles extends Model
{
    use HasFactory;

    protected $table = 'no_of_poles';
    protected $primaryKey = 'no_of_poles_id';

    protected $fillable = [
        'no_of_poles',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
