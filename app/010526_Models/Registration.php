<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Registration extends Authenticatable
{
    use HasFactory;

    protected $table = 'registration'; // keep if your table name is capital R

    protected $fillable = [
        'company_name',
        'contact_person',
        'email',
        'mobile',
        'address',
        'password',
        'otp',
        'otp_expires_at',
        'is_verified',
        'is_password_changed'
    ];

    protected $hidden = [
        'password',
    ];
}
