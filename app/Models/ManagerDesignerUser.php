<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class ManagerDesignerUser extends Authenticatable
{
    protected $table = 'manager_designer_user';
    protected $primaryKey = 'manager_designer_user_id';
    public $timestamps = true;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'mobile_number',
        'password',
        'role_type',
        'status'
    ];
}
